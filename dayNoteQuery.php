<?php
include 'inc/config.inc.php';
 
if($_POST['act'] == 'read'){
	$dia = $_POST['dia'];
	$mes = $_POST['mes'];
	$ano = $_POST['ano'];
	$data = $_POST['ano'].'-'.$_POST['mes'].'-'.$_POST['dia'];
	
	$chatData = new Crud('sys_daynote');
	$chatDataQR = $chatData->selectArrayConditions('*','date="'.$data.'"');
	$chatDataData = mysql_fetch_array($chatDataQR);
	
	echo utf8_decode($chatDataData['text']);
}

if($_POST['act'] == 'write'){
	$data = $_POST['data'];
	$texto = utf8_encode($_POST['text']);
	
	if(!empty($texto)){
		$chatData = new Crud('sys_daynote');
		$chatData->replace('`date`, `text`','"'.$data.'", "'.$texto.'"');
		if($chatData->execute()){ echo 1; } else { echo 0; }
	}
}

if($_POST['act'] == 'readCirurgica'){
	$dia = $_POST['dia'];
	$mes = $_POST['mes'];
	$ano = $_POST['ano'];
	$data = $_POST['ano'].'-'.$_POST['mes'].'-'.$_POST['dia'];
	
	$chatData = new Crud('sys_daynote_agCirurgica');
	$chatDataQR = $chatData->selectArrayConditions('*','date="'.$data.'"');
	$chatDataData = mysql_fetch_array($chatDataQR);
	
	echo utf8_decode($chatDataData['text']);
}

if($_POST['act'] == 'writeCirurgica'){
	$data = $_POST['data'];
	$texto = utf8_encode($_POST['text']);
	
	if(!empty($texto)){
		$chatData = new Crud('sys_daynote_agCirurgica');
		$chatData->replace('`date`, `text`','"'.$data.'", "'.$texto.'"');
		if($chatData->execute()){ echo 1; } else { echo 0; }
	}
}

if($_POST['act'] == 'novoEvento'){
	
		$data1 = dataEncode($_POST['data']);
		$hora1 = $_POST['hora'].':00';
		
		$duracao = empty($_POST['duracao']) ? 5 : $_POST['duracao'];
		
		$daAgendaTermino = strtotime("$hora1 + $duracao minutes");
		$daAgendaTermino = $daAgendaTermino - 1;
		$daAgendaInicio = strtotime($hora1);
		$daAgendaInicio = $daAgendaInicio + 1;
		
		$event['log']['agenda inicio'] = $daAgendaInicio;
		$event['log']['agenda fim'] = $daAgendaTermino;
		
		$slct = new Crud('sys_agenda');
		$checkSlot = $slct->customQuery('SELECT horaConsulta,duracao FROM sys_agenda WHERE data = "'.$data1.'"');
		while($checkSlotQR = mysql_fetch_array($checkSlot)){
			
			$duracaoBD = (int)$checkSlotQR['duracao'];
			$inicioBD = $checkSlotQR['horaConsulta'];
			$doBancoInicio = strtotime($checkSlotQR['horaConsulta']);
			$doBancoTermino = strtotime("$inicioBD + $duracaoBD minutes");
			
			$event['log']['banco inicio'] = $doBancoInicio;
			$event['log']['banco fim'] = $doBancoTermino;
			
			if($daAgendaTermino > $doBancoInicio && $daAgendaInicio < $doBancoInicio){
				$haveEvent=1;
				$difference_start = $daAgendaTermino - $doBancoInicio;
				$event['log'][] = 'bate no inicio do de baixo';
			} elseif($daAgendaInicio < $doBancoTermino && $daAgendaTermino > $doBancoTermino){
				$haveEvent=1;
				$difference_end = $doBancoTermino - $daAgendaInicio;
				$event['log'][] = 'bate no fim do de cima';
			} elseif($daAgendaInicio > $doBancoInicio && $daAgendaTermino < $doBancoTermino){
				$haveEvent=1;
				$difference_start = 0;
				$difference_end = 0;
				$event['log'][] = 'dentro do outro';
			}

		}

		if(isset($haveEvent)){
			$event['status'] = 2;
			$event['difstr'] = (isset($difference_start)) ? ceil($difference_start/60) : 0;
			$event['difend'] = (isset($difference_end)) ? ceil($difference_end/60) : 0;
			
		} else {
				
			if($_POST['tipoCad']!=1){
				$postValues2 = "'".utf8_decode($_POST['nome'])."','".$_POST['telRes']."','".$_POST['telCel']."','".$_POST['email']."', '".$data1."', '".date("Y-m-d")."',1, '".utf8_decode($_POST['convenio'])."', '".$_POST['ddd_cel']."', '".$_POST['telCom']."', '".$_POST['ddd_com']."', '".$_POST['ddd_tel']."'";
				$insere2 = new Crud('sys_pacientes');
				$insere2->insert("`nome`, `telefoneRes`, `celular`, `email`, `dataPrimeiraConsulta`, `dataCadastro`, `cadRapido`, `convenio`, `ddd_celular`, `telefoneCom`, `ddd_com`, `ddd_res`", $postValues2);
				$insere2->execute();
				
				$lastID = mysql_insert_id();
				$cadRapido = 1;
			} else {
				$lastID = $_POST['paciente_id'];
				$cadRapido = 0;
			}
			
			$slct = new Crud('sys_pacientes');
			$slctQR = $slct->selectArrayConditions('*','idPaciente='.$lastID);
			$slctData = mysql_fetch_array($slctQR);
			
			
			$postValues = "'".$slctData['nome']."','".$data1."','".$_POST['tipo']."','".utf8_decode($_POST['obs'])."','".$_POST['status']."','".$lastID."','".$hora1."','".$duracao."',".$cadRapido;
			
			$insere = new Crud('sys_agenda');
			$insere->insert("`nomePaciente`, `data`, `tipo`, `observacao`, `status`, `idPaciente`, `horaConsulta`, `duracao`, `cadRapido`", $postValues);
			if($insere->execute()){ $event['status'] = 0; } else { $event['status'] = 1; }
			
		}
		
	echo json_encode($event);

}





if($_POST['act'] == 'novoEventoCirurgica'){
	
		$data1 = dataEncode($_POST['data']);
		$data2 = dataEncode($_POST['dataAgendamento']);
		$hora1 = $_POST['hora'].':00';
		
		$duracao = empty($_POST['duracao']) ? 5 : $_POST['duracao'];
		
		$daAgendaTermino = strtotime("$hora1 + $duracao minutes");
		$daAgendaTermino = $daAgendaTermino - 1;
		$daAgendaInicio = strtotime($hora1);
		$daAgendaInicio = $daAgendaInicio + 1;
		
		$event['log']['agenda inicio'] = $daAgendaInicio;
		$event['log']['agenda fim'] = $daAgendaTermino;
		
		$slct = new Crud('sys_agendaCirurgica');
		$checkSlot = $slct->customQuery('SELECT horaConsulta,duracao FROM sys_agendaCirurgica WHERE data = "'.$data1.'"');
		while($checkSlotQR = mysql_fetch_array($checkSlot)){
			
			$duracaoBD = (int)$checkSlotQR['duracao'];
			$inicioBD = $checkSlotQR['horaConsulta'];
			$doBancoInicio = strtotime($checkSlotQR['horaConsulta']);
			$doBancoTermino = strtotime("$inicioBD + $duracaoBD minutes");
			
			$event['log']['banco inicio'] = $doBancoInicio;
			$event['log']['banco fim'] = $doBancoTermino;
			
			if($daAgendaTermino > $doBancoInicio && $daAgendaInicio < $doBancoInicio){
				$haveEvent=1;
				$difference_start = $daAgendaTermino - $doBancoInicio;
				$event['log'][] = 'bate no inicio do de baixo';
			} elseif($daAgendaInicio < $doBancoTermino && $daAgendaTermino > $doBancoTermino){
				$haveEvent=1;
				$difference_end = $doBancoTermino - $daAgendaInicio;
				$event['log'][] = 'bate no fim do de cima';
			} elseif($daAgendaInicio > $doBancoInicio && $daAgendaTermino < $doBancoTermino){
				$haveEvent=1;
				$difference_start = 0;
				$difference_end = 0;
				$event['log'][] = 'dentro do outro';
			}
			
		}
		
		if(isset($haveEvent)){
			$event['status'] = 2;
			$event['difstr'] = (isset($difference_start)) ? ceil($difference_start/60) : 0;
			$event['difend'] = (isset($difference_end)) ? ceil($difference_end/60) : 0;
			
		} else {
				
			if($_POST['tipoCad']!=1){
				$postValues2 = "'".utf8_decode($_POST['nome'])."', '".$data1."', '".date("Y-m-d")."', 1 ";
				$insere2 = new Crud('sys_pacientes');
				$insere2->insert("`nome`, `dataPrimeiraConsulta`, `dataCadastro`, `cadRapido`", $postValues2);
				$insere2->execute();
				
				$lastID = mysql_insert_id();
				$cadRapido = 1;
			} else {
				$lastID = $_POST['paciente_id'];
				$cadRapido = 0;
			}
			
			$slct = new Crud('sys_pacientes');
			$slctQR = $slct->selectArrayConditions('*','idPaciente='.$lastID);
			$slctData = mysql_fetch_array($slctQR);
			
			
			$postValues = "'".$slctData['nome']."','".$data1."','".utf8_decode($_POST['obs'])."','".$_POST['prioridade']."','".$lastID."','".$hora1."','".$duracao."',".$cadRapido.", '".utf8_decode($_POST['cancelSubject'])."', '".utf8_decode($_POST['procedimento'])."' ,'".$data2."'";
			
			$insere = new Crud('sys_agendaCirurgica');
			$insere->insert("`nomePaciente`, `data`, `observacao`, `prioridade`, `idPaciente`, `horaConsulta`, `duracao`, `cadRapido`, `cancelSubject`, `procedimento`, `dataAgendamento`", $postValues);
			if($insere->execute()){ $event['status'] = 0; } else { $event['status'] = 1; }
			
		}
			echo json_encode($event);
}





if($_POST['act'] == 'updateEventoCirurgica'){
			
		$lastID = $_POST['paciente_id'];
		$slct = new Crud('sys_pacientes');
		$slctQR = $slct->selectArrayConditions('*','idPaciente='.$lastID);
		$slctData = mysql_fetch_array($slctQR);
		
		$data1 = dataEncode($_POST['data']);
		$data2 = dataEncode($_POST['dataAgendamento']);
		$hora1 = $_POST['hora'].':00';
		
		$duracao = empty($_POST['duracao']) ? 5 : $_POST['duracao'];
		
		$daAgendaTermino = strtotime("$hora1 + $duracao minutes");
		$daAgendaTermino = $daAgendaTermino - 1;
		$daAgendaInicio = strtotime($hora1);
		$daAgendaInicio = $daAgendaInicio + 1;
		
		$event['log']['agenda inicio'] = $daAgendaInicio;
		$event['log']['agenda fim'] = $daAgendaTermino;
		
		$slct = new Crud('sys_agendaCirurgica');
		$checkSlot = $slct->customQuery('SELECT horaConsulta,duracao FROM sys_agendaCirurgica WHERE data = "'.$data1.'" AND idPaciente != '.$lastID);
		while($checkSlotQR = mysql_fetch_array($checkSlot)){
			
			$duracaoBD = (int)$checkSlotQR['duracao'];
			$inicioBD = $checkSlotQR['horaConsulta'];
			$doBancoInicio = strtotime($checkSlotQR['horaConsulta']);
			$doBancoTermino = strtotime("$inicioBD + $duracaoBD minutes");
			
			$event['log']['banco inicio'] = $doBancoInicio;
			$event['log']['banco fim'] = $doBancoTermino;
			
			if($daAgendaTermino > $doBancoInicio && $daAgendaInicio < $doBancoInicio){
				$haveEvent=1;
				$difference_start = $daAgendaTermino - $doBancoInicio;
				$event['log'][] = 'bate no inicio do de baixo';
			} elseif($daAgendaInicio < $doBancoTermino && $daAgendaTermino > $doBancoTermino){
				$haveEvent=1;
				$difference_end = $doBancoTermino - $daAgendaInicio;
				$event['log'][] = 'bate no fim do de cima';
			} elseif($daAgendaInicio > $doBancoInicio && $daAgendaTermino < $doBancoTermino){
				$haveEvent=1;
				$difference_start = 0;
				$difference_end = 0;
				$event['log'][] = 'dentro do outro';
			}
			
		}
		
		if(isset($haveEvent)){
			$event['status'] = 2;
			$event['difstr'] = (isset($difference_start)) ? ceil($difference_start/60) : 0;
			$event['difend'] = (isset($difference_end)) ? ceil($difference_end/60) : 0;
			
		} else {
			
			
			$updateAgenda = new Crud('sys_agendaCirurgica');
			$updateAgenda->update("`nomePaciente`='".$slctData['nome']."', `data`='".$data1."' , `observacao`='".utf8_decode($_POST['obs'])."', `prioridade`='".$_POST['prioridade']."', `idPaciente`='".$lastID."', `horaConsulta`='".$hora1."', `duracao`='".$duracao."', `cancelSubject`='".utf8_decode($_POST['cancelSubject'])."' , `procedimento`='".utf8_decode($_POST['procedimento'])."' , `dataAgendamento` = '".$data2."'", '`idagenda`='.$_POST['idagenda']);
			if($updateAgenda->execute()) { $upAgenda = 1; }
			
			if(isset($upPac) && isset($upAgenda)){ $event['status'] = 0; } else { $event['status'] = 1; }
			
		}
	
	echo json_encode($event);
	exit;		
}








if($_GET['act'] == 'buscaEvento'){
	
		$tipoAgenda = $_GET['tipoAgenda'];
		
		if($tipoAgenda==0){ 
				$slct = new Crud('sys_agenda');
				$slctQR = $slct->customQuery('SELECT sys_agenda.* , sys_pacientes.telefoneRes as tel , sys_pacientes.celular as cel , sys_pacientes.email as mail , sys_pacientes.ddd_celular as ddd_cel , sys_pacientes.ddd_res as ddd_tel , sys_pacientes.ddd_com as ddd_com , sys_pacientes.telefoneCom as com , sys_pacientes.convenio as convenio , `sys_multi_uploads`.`file` as avatar FROM sys_agenda LEFT JOIN sys_pacientes ON sys_pacientes.idPaciente = sys_agenda.idPaciente LEFT JOIN `sys_multi_uploads` ON (`sys_multi_uploads`.`user_id` = `sys_agenda`.`idPaciente` AND `sys_multi_uploads`.`display_order` = 0) WHERE idagenda='.$_GET['id']);
				$slctData = mysql_fetch_array($slctQR);
				
				$tel = $slctData['tel'];
				$cel = $slctData['cel'];
				$com = $slctData['com'];
				$ddd_tel = $slctData['ddd_tel'];
				$ddd_cel = $slctData['ddd_cel'];
				$ddd_com = $slctData['ddd_com'];
		} else {
				$slct = new Crud('sys_agendaCirurgica');
				$slctQR = $slct->customQuery('SELECT sys_agendaCirurgica.* , `sys_multi_uploads`.`file` as avatar FROM sys_agendaCirurgica LEFT JOIN `sys_multi_uploads` ON (`sys_multi_uploads`.`user_id` = `sys_agendaCirurgica`.`idPaciente` AND `sys_multi_uploads`.`display_order` = 0) WHERE idagenda='.$_GET['id']);
				$slctData = mysql_fetch_array($slctQR);
		}
		
		$lastRet = new Crud('sys_agenda');
		$lastRetQR = $lastRet->customQuery('SELECT data FROM sys_agenda WHERE idPaciente = '.$slctData['idPaciente'].' AND status = "T" ORDER BY data DESC LIMIT 1');
		$ultRet = mysql_fetch_array($lastRetQR);
		$ultimoRetorno = ($ultRet['data']) ? dataDecode($ultRet['data']) : '';
		
		
		if(!empty($slctData['avatar'])){$img = 'js/AJAXupload/files/thumbnail/'.$slctData['avatar'];}
		elseif(file_exists("avatar/".$slctData['idPaciente'].".jpg")) { $img = 'avatar/'.$slctData['idPaciente'].'.jpg'; }
		else {$img = 'img/user.png';}
		
		$obs = $slctData['observacao'] == 'NULL' ? '' : utf8_encode($slctData['observacao']);
		
		$events = array(			'nome' => utf8_encode($slctData['nomePaciente']),
									'status' => $slctData['status'],
									'data' => dataDecode($slctData['data']),
									'hora' => horaDecode($slctData['horaConsulta']),
									'tipo' => $slctData['tipo'],
									'duracao' => $slctData['duracao'],
									'convenio' => utf8_encode($slctData['convenio']),
									'obs' => $obs,
									'pid' => $slctData['idPaciente'],
									'tel' => $tel,
									'cel' => $cel,
									'com' => $com,
									'ddd_tel' => $ddd_tel,
									'ddd_cel' => $ddd_cel,
									'ddd_com' => $ddd_com,
									'mail' => $slctData['mail'],
									'img' => $img,
									'ok' => 1,
									'prioridade' => $slctData['prioridade'],
									'ultimoretorno' => $ultimoRetorno,
									'cancelSubject' => utf8_encode($slctData['cancelSubject']),
									'procedimento' => utf8_encode($slctData['procedimento'])
					 );
					 
		echo json_encode($events);
		
		exit;
}

if($_GET['act'] == 'deletaevento'){
	
		$bdToDel = $_GET['tipoAgenda']==1 ? 'sys_agendaCirurgica' : 'sys_agenda';
		
		$slct = new Crud($bdToDel);
		$slct->delete('idagenda='.$_GET['id']);
		if($slct->execute()){ echo 1; } else { echo 0; }
}

if($_POST['act'] == 'updateEvento'){
			
		$lastID = $_POST['paciente_id'];
		$slct = new Crud('sys_pacientes');
		$slctQR = $slct->selectArrayConditions('*','idPaciente='.$lastID);
		$slctData = mysql_fetch_array($slctQR);
		
		$data1 = dataEncode($_POST['data']);
		$hora1 = $_POST['hora'].':00';
		
		$duracao = empty($_POST['duracao']) ? 5 : $_POST['duracao'];
		
		$daAgendaTermino = strtotime("$hora1 + $duracao minutes");
		$daAgendaTermino = $daAgendaTermino - 1;
		$daAgendaInicio = strtotime($hora1);
		$daAgendaInicio = $daAgendaInicio + 1;
		
		$event['log']['agenda inicio'] = $daAgendaInicio;
		$event['log']['agenda fim'] = $daAgendaTermino;
		
		$slct = new Crud('sys_agenda');
		$checkSlot = $slct->customQuery('SELECT horaConsulta,duracao FROM sys_agenda WHERE data = "'.$data1.'" AND idPaciente != '.$lastID);
		while($checkSlotQR = mysql_fetch_array($checkSlot)){
			
			$duracaoBD = (int)$checkSlotQR['duracao'];
			$inicioBD = $checkSlotQR['horaConsulta'];
			$doBancoInicio = strtotime($checkSlotQR['horaConsulta']);
			$doBancoTermino = strtotime("$inicioBD + $duracaoBD minutes");
			
			$event['log']['banco inicio'] = $doBancoInicio;
			$event['log']['banco fim'] = $doBancoTermino;
			
			if($daAgendaTermino > $doBancoInicio && $daAgendaInicio < $doBancoInicio){
				$haveEvent=1;
				$difference_start = $daAgendaTermino - $doBancoInicio;
				$event['log'][] = 'bate no inicio do de baixo';
			} elseif($daAgendaInicio < $doBancoTermino && $daAgendaTermino > $doBancoTermino){
				$haveEvent=1;
				$difference_end = $doBancoTermino - $daAgendaInicio;
				$event['log'][] = 'bate no fim do de cima';
			} elseif($daAgendaInicio > $doBancoInicio && $daAgendaTermino < $doBancoTermino){
				$haveEvent=1;
				$difference_start = 0;
				$difference_end = 0;
				$event['log'][] = 'dentro do outro';
			}
			
		}
		
		if(isset($haveEvent)){
			$event['status'] = 2;
			$event['difstr'] = (isset($difference_start)) ? ceil($difference_start/60) : 0;
			$event['difend'] = (isset($difference_end)) ? ceil($difference_end/60) : 0;
			
		} else {
			
			$safeQuery = '';
			$safeQuery .= (empty($_POST['telRes'])) ? '' : " , `telefoneRes`='".$_POST['telRes']."', `ddd_res`='".$_POST['ddd_tel']."'";
			$safeQuery .= (empty($_POST['telCel'])) ? '' : " , `celular`='".$_POST['telCel']."', `ddd_celular`='".$_POST['ddd_cel']."'";
			$safeQuery .= (empty($_POST['telRes'])) ? '' : " , `telefoneCom`='".$_POST['telCom']."', `ddd_com`='".$_POST['ddd_com']."'";
			
			$updatePac = new Crud('sys_pacientes');
			$updatePac->update("`email`='".$_POST['email']."', `convenio`='".utf8_decode($_POST['convenio'])."' ".$safeQuery, '`idPaciente`='.$lastID);
			if($updatePac->execute()) { $upPac = 1; }
			
			$updateAgenda = new Crud('sys_agenda');
			$updateAgenda->update("`nomePaciente`='".$slctData['nome']."', `data`='".$data1."', `tipo`='".$_POST['tipo']."', `observacao`='".utf8_decode($_POST['obs'])."', `status`='".$_POST['status']."', `idPaciente`='".$lastID."', `horaConsulta`='".$hora1."', `duracao`='".$duracao."'", '`idagenda`='.$_POST['idagenda']);
			if($updateAgenda->execute()) { $upAgenda = 1; }
			
			if(isset($upPac) && isset($upAgenda)){ $event['status'] = 0; } else { $event['status'] = 1; }
			
		}
	
	echo json_encode($event);
	exit;		
}



if($_POST['act'] == 'upPacImg'){
			
		$insere = new Crud('sys_imagemfull');
		$insere->update("txt='".utf8_decode($_POST['desc'])."', title='".utf8_decode($_POST['title'])."'", 'linkImagem='.$_POST['id']);
		if($insere->execute()){ echo 1; } else { echo 0; }
}


if($_POST['act'] == 'delPacImg'){
			
		$insere = new Crud('sys_imagemfull');
		$insere->delete('linkImagem='.$_POST['id']);
		
		$apaga = unlink($_POST['file']);
		
		if($insere->execute()==1 && !$apaga){ echo 1; } else { echo 0; }
}


if($_POST['act'] == 'newBlock'){
			
		$titleblock = utf8_decode($_POST['titleblock']);	
		
		$start = dataEncode($_POST['start']);
		$end = dataEncode($_POST['end']);
		
		$hstart = $_POST['hstart'];
		$hend = $_POST['hend'];
		
		$postValues = "'" .$titleblock."', '" .$start."', '".$hstart."', 1 ,'".$end."', '".$hend. " ' ";
		
		$insere = new Crud('sys_agenda');
		$insere->insert("`titleblock`,`data`, `horaConsulta`, `block`, `blockend`, `horaend`", $postValues);
		if($insere->execute()){ echo 1; } else { echo 0; }
}


if($_POST['act'] == 'imageDataDownload'){
			
		$slct = new Crud('sys_imagemfull');
		$slctQR = $slct->selectArrayConditions('*','linkImagem="'.$_POST['id'].'"');
		$slctData = mysql_fetch_array($slctQR);
		
		if($slctData){
			$events = array(			'estado' => $slctData['estado'] ? utf8_encode($slctData['estado']) : '',
										'cidade' => $slctData['cidade'] ? utf8_encode($slctData['cidade']) : '',
										'procedimento' => $slctData['procedimento'] ? utf8_encode($slctData['procedimento']) : '',
										'regoperada' => $slctData['regoperada'] ? utf8_encode($slctData['regoperada']) : '',
										'qntfios' => $slctData['qntfios'] ? utf8_encode($slctData['qntfios']) : '',
										'cirurgia' => $slctData['cirurgiasComOutroMedico'] ? utf8_encode($slctData['cirurgiasComOutroMedico']) : '',
										'corcabelo' => $slctData['corDoCabelo'] ? utf8_encode($slctData['corDoCabelo']) : '',
										'fio' => $slctData['fio'] ? utf8_encode($slctData['fio']) : '',
										'haste' => $slctData['haste'] ? utf8_encode($slctData['haste']) : '',
										'indicacao' => $slctData['indicacao'] ? utf8_encode($slctData['indicacao']) : '',
										'dataimg' => $slctData['dataImagem'] ? dataDecode($slctData['dataImagem']) : '',
										'dataproc' => $slctData['dataProcedimento'] ? dataDecode($slctData['dataProcedimento']) : '',
										'obs' => $slctData['obs'] == 'NULL' ? '' : utf8_encode($slctData['obs']),
										'dataTable_cirurgia' => $slctData['dataTable_cirurgia'] ? $slctData['dataTable_cirurgia'] : '',
										'dataTable_planejamento' => $slctData['dataTable_planejamento'] ? $slctData['dataTable_planejamento'] : '',
										'periodo' => $slctData['periodo'] ? $slctData['periodo'] : '',
										'cirurgiatipo' => $slctData['cirurgiatipo'] ? $slctData['cirurgiatipo'] : '',
										'famoso' => $slctData['famoso'] ? $slctData['famoso'] : '',
										'ok' => 1
						 );
		} else {
			$events['ok'] = 0;
		}
		
		echo json_encode($events);
}



if($_POST['act'] == 'imageDataUpload'){
		
		$dataTable_planejamento = ( $_POST['dataTable_planejamento'])  ? $_POST['dataTable_planejamento'] : 0;
		$dataTable_cirurgia = ($_POST['dataTable_cirurgia']) ? $_POST['dataTable_cirurgia']  : 0  ;
 			
		$insere = new Crud('sys_imagemfull');
		
		if($_POST['tab']==2){
			$insere->update('`procedimento`="'.utf8_decode($_POST['procedimento']).'", `regoperada`="'.utf8_decode($_POST['regoperada']).'", `qntfios`="'.utf8_decode($_POST['qntfios']).'", `cidade`="'.utf8_decode($_POST['cidade']).'",`cirurgiasComOutroMedico`="'.utf8_decode($_POST['cirurgia']).'",`corDoCabelo`="'.utf8_decode($_POST['corcabelo']).'",`estado`="'.utf8_decode($_POST['estado']).'",`fio`="'.utf8_decode($_POST['fio']).'",`haste`="'.utf8_decode($_POST['haste']).'",`indicacao`="'.utf8_decode($_POST['indicacao']).'",`dataImagem`="'.dataEncode($_POST['dataimg']).'",`dataProcedimento`="'.dataEncode($_POST['dataproc']).'",  `obs`="'.utf8_decode($_POST['obs']).'"', 'linkImagem="'.$_POST['id'].'"');
		}
		
		if($_POST['tab']==1){
			$insere->update('`dataTable_cirurgia`='.$dataTable_cirurgia.' , `dataTable_planejamento`='.$dataTable_planejamento.' , `periodo`="'.utf8_decode($_POST['dataTable_periodo']).'" , `cirurgiatipo`="'.utf8_decode($_POST['dataTable_cirurgiatipo']).'" , `famoso`="'.utf8_decode($_POST['dataTable_famoso']).'"', 'linkImagem="'.$_POST['id'].'"');
		}

		if($insere->execute()){ echo 1; } else { echo 0; }
				
}



if($_POST['act'] == 'multiImageData'){
		
		$ids = array();
		$postIDS = explode(',',$_POST['ids']);
		foreach($postIDS as $key=>$value){
			$ids[] = 'linkImagem="'.$value.'"';
		}
		$idsImp = implode(' OR ', $ids);
			
		$dataTable_planejamento = ( $_POST['dataTable_planejamento'])  ? $_POST['dataTable_planejamento'] : 0;
		$dataTable_cirurgia = ($_POST['dataTable_cirurgia']) ? $_POST['dataTable_cirurgia']  : 0  ;	
			
		$insere = new Crud('sys_imagemfull');
		$insere->update(
		'`procedimento`="'.utf8_decode($_POST['procedimento']).'",
		`regoperada`="'.utf8_decode($_POST['regoperada']).'",
		`qntfios`="'.utf8_decode($_POST['qntfios']).'", 
		`cidade`="'.utf8_decode($_POST['cidade']).'",
		`cirurgiasComOutroMedico`="'.utf8_decode($_POST['cirurgia']).'",
		`corDoCabelo`="'.utf8_decode($_POST['corcabelo']).'",
		`estado`="'.utf8_decode($_POST['estado']).'",
		`fio`="'.utf8_decode($_POST['fio']).'",
		`haste`="'.utf8_decode($_POST['haste']).'",
		`indicacao`="'.utf8_decode($_POST['indicacao']).'",
		`dataImagem`="'.dataEncode($_POST['dataimg']).'",
		`dataProcedimento`= "'.dataEncode($_POST['dataproc']).'", 
		`dataTable_cirurgia`= "'.$dataTable_cirurgia.'",
		`dataTable_planejamento`= "'.$dataTable_planejamento.'",
		`obs`= "'.utf8_decode($_POST['obs']).'" , 
		`periodo`= "'.utf8_decode($_POST['dataTable_periodo']).'" , 
		`famoso`= "'.utf8_decode($_POST['dataTable_famoso']).'" ,
		`cirurgiatipo`= "'.utf8_decode($_POST['dataTable_cirurgiatipo']).'"', 
		$idsImp);
		if($insere->execute()){ echo 1; } else { echo 0; }
				
}



if($_POST['act'] == 'getImgDataTable_cirurgia'){
			
		$slct = new Crud('sys_descricao_cirurgia');
		$slctQR = $slct->selectArrayConditions('*','id='.$_POST['id']);
		$slctData = mysql_fetch_array($slctQR);
		
		$events = array(  'local' => $slctData['local'] ? utf8_encode($slctData['local']) : '',
						  'foco' => $slctData['focoprincipal'] ? utf8_encode($slctData['focoprincipal']) : '',
						  'haste' => $slctData['haste'] ? utf8_encode($slctData['haste']) : '',
						  'fio' => $slctData['fio'] ? utf8_encode($slctData['fio']) : '',
						  'qntfios' => $slctData['qntfios'] ? utf8_encode($slctData['qntfios']) : '',
						  'rqf' => $slctData['rqf'] ? utf8_encode($slctData['rqf']) : '',
						  'obs' => $slctData['obs'] == 'NULL' ? '' : utf8_encode($slctData['obs']),
						  'data' => $slctData['data'] ? $slctData['data'] : '',
						  'ok' => 1
		);
				
		echo json_encode($events);
}

if($_POST['act'] == 'getImgDataTable_planejamento'){
			
		$slct = new Crud('sys_planejamento_cirurgia');
		$slctQR = $slct->selectArrayConditions('*','id='.$_POST['id']);
		$slctData = mysql_fetch_array($slctQR);
		
		$events = array(  'regiao' => $slctData['regoperada'] ? utf8_encode($slctData['regoperada']) : '',
						  'densidade' => $slctData['densidade'] ? utf8_encode($slctData['densidade']) : '',
						  'cor' => $slctData['cor'] ? utf8_encode($slctData['cor']) : '',
						  'tipo' => $slctData['tipo'] ? utf8_encode($slctData['tipo']) : '',
						  'diametro' => $slctData['diametro'] ? utf8_encode($slctData['diametro']) : '',
						  'obs' => $slctData['obs'] == 'NULL' ? '' : utf8_encode($slctData['obs']),
						  'data' => $slctData['data'] ? dataDecode($slctData['data']) : '',
						  'ok' => 1
		);
				
		echo json_encode($events);
}





if($_POST['act'] == 'updateCounters'){
			
	$slct = new Crud('sys_contador_agenda');
	
	foreach($_POST['ids'] as $key=>$value){
		
		$getReg = $slct->selectArrayConditions('idPaciente','idPaciente='.$value);
		if(mysql_num_rows($getReg)>0){
			$slct->update('current="'.date('Y-m-d H:i:s').'"', 'idPaciente='.$value);
			$slct->execute();
		} else {
			$slct->insert('idPaciente,current', $value.',"'.date('Y-m-d H:i:s').'"');
			$slct->execute();
		}
	}
	
	$inlineIds = implode(' OR idPaciente=', $_POST['ids']);
	
	$getCount = $slct->selectArrayConditions('*','idPaciente='.$inlineIds);
	while($getCntQR = mysql_fetch_array($getCount)){
	
		$time = strtotime($getCntQR['current']) - strtotime($getCntQR['start']);
		$time = date("i:s", $time);
		
		$events[] = array(  'idpac' => $getCntQR['idPaciente'],
							'time' => $time,
					);
	}
			
	echo json_encode($events);
}

if($_POST['act'] == 'removeCounters'){
	
	$queryIDs = implode(' OR idPaciente=', $_POST['ids']);
	
	$slct = new Crud('sys_contador_agenda');
	$slct->delete('idPaciente='.$queryIDs);
	$slct->execute();
		
}





?>