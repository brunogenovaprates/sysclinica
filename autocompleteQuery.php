<?php

include 'inc/config.inc.php';
 
$lp1 = new Crud('sys_pacientes');

$term = explode(' ',$_GET['term']);
$termimp = implode('%',$term);

$lp1qr = $lp1->customQuery("SELECT `sys_pacientes`.`nome`, `sys_pacientes`.`idPaciente`, `sys_pacientes`.`telefoneRes`, `sys_pacientes`.`celular`, `sys_pacientes`.`ddd_celular`, `sys_pacientes`.`telefoneCom`, `sys_pacientes`.`ddd_com`, `sys_pacientes`.`ddd_res`, `sys_pacientes`.`email`, `sys_multi_uploads`.`file` as avatar , `sys_convenio`.`idconvenio` as conv_id FROM `sys_pacientes` LEFT JOIN `sys_multi_uploads` ON (`sys_multi_uploads`.`user_id` = `sys_pacientes`.`idPaciente` AND `sys_multi_uploads`.`display_order` = 0) LEFT JOIN `sys_convenio` ON `sys_convenio`.`nome` = `sys_pacientes`.`convenio` WHERE `sys_pacientes`.`nome` LIKE '%".utf8_decode($termimp)."%'");


while($lp1Data = mysql_fetch_array($lp1qr)){
	
	if(!empty($lp1Data['avatar'])){$img = 'js/AJAXupload/files/thumbnail/'.$lp1Data['avatar'];}
	elseif(file_exists("avatar/".$lp1Data['idPaciente'].".jpg")) { $img = 'avatar/'.$lp1Data['idPaciente'].'.jpg'; }
	else {$img = 'img/user.png';}
	
	$lastRet = new Crud('sys_agenda');
	$lastRetQR = $lastRet->customQuery('SELECT data FROM sys_agenda WHERE idPaciente = '.$lp1Data['idPaciente'].' AND status = "T" ORDER BY data DESC LIMIT 1');
	$ultRet = mysql_fetch_array($lastRetQR);
	$ultimoRetorno = ($ultRet['data']) ? dataDecode($ultRet['data']) : '';
	
	$loopPacientes[] = array('label' => utf8_encode($lp1Data['nome']), 'id' => $lp1Data['idPaciente'], 'value' => utf8_encode($lp1Data['nome']), 'tel' => utf8_encode($lp1Data['telefoneRes']), 'cel' => utf8_encode($lp1Data['celular']), 'ddd_cel' => utf8_encode($lp1Data['ddd_celular']), 'ddd_tel' => utf8_encode($lp1Data['ddd_res']), 'tel_com' => utf8_encode($lp1Data['telefoneCom']), 'ddd_com' => utf8_encode($lp1Data['ddd_com']), 'img' => $img, 'convid' => $lp1Data['conv_id'], 'email' => utf8_encode($lp1Data['email']), 'ultimoRetorno' => $ultimoRetorno);
}
 
echo json_encode($loopPacientes);

unset($loopPacientes);

exit;
 
?>