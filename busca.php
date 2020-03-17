<?php

// CONFIGURACOES DA PAGINA
$menuItem = 7;
$pagefile = 'busca.php';


// INCLUDES
include 'inc/config.inc.php';

include 'inc/header.inc.php';



$html .= openFullWidget('<i class="fa fa-search"></i> Busca avançada',$actions=5);

if(isset($_GET['buscaavancada'])){
	
	$query = '';
	$bcumb = 'Buscando: ';
	
	if(!empty($_POST['nome'])){
		$frmtNome = str_replace(' ', '%', $_POST['nome']);
		$query .= ' AND `sys_pacientes`.`nome` LIKE "%'.utf8_decode($frmtNome).'%" ';
		$bcumb .= 'Nome: '.$_POST['nome'].', ';
	}

	if(!empty($_POST['idade1']) && !empty($_POST['idade2'])){
		$valor = (int)$_POST['idade1'];
		$idade = date('Y');
		$idade = $idade - $valor;
		$valor2 = (int)$_POST['idade2'];
		$idade2 = date('Y');
		$idade2 = $idade2 - $valor2;
		$query .= ' AND (`sys_pacientes`.`dataNascimento` BETWEEN "'.$idade2.'-01-01" AND "'.$idade.'-12-31") ';
		$bcumb .= 'Idade: entre "'.$_POST['idade1'].'" e "'.$_POST['idade2'].'", ';
	}

	if(!empty($_POST['sexo'])){
		$query .= ' AND `sys_pacientes`.`sexo` LIKE "'.$_POST['sexo'].'" ';
		$format = $_POST['sexo']=='M' ? 'Masculino' : 'Feminino';
		$bcumb .= 'Sexo: "'.$format.'" , ';
	}

	if(!empty($_POST['cidade'])){
		$query .= ' AND `sys_pacientes`.`cidade` LIKE "%'.utf8_decode($_POST['cidade']).'%" ';
		$bcumb .= 'Cidade: "'.$_POST['cidade'].'" , ';
	}

	if(!empty($_POST['indicacao'])){
		$frmtNome = str_replace(' ', '%', $_POST['indicacao']);
		$query .= ' AND `sys_pacientes`.`indicacao` LIKE "%'.utf8_decode($frmtNome).'%" ';
		$bcumb .= 'Indicação: "'.$_POST['indicacao'].'" , ';
	}

	if(!empty($_POST['profissao'])){
		$query .= ' AND `sys_pacientes`.`ocupacao` LIKE "%'.utf8_decode($_POST['profissao']).'%" ';
		$bcumb .= 'Profissão: "'.$_POST['profissao'].'" , ';
	}

	if(isset($_POST['cirgOutroMedico'])){
		$query .= ' AND `sys_diagnostico`.`checkCirurgia` = 1 ';
		$bcumb .= 'Cirurgia com outro médico, ';
	}

	if(isset($_POST['autorizacao'])){
		$query .= ' AND `sys_pacientes`.`autorizacao` = 1 ';
		$bcumb .= 'Exibir apenas pacientes que autorizam o uso de sua imagem, ';
	}

	if(!empty($_POST['qntfios1']) && !empty($_POST['qntfios2'])){
		$query .= ' AND (`sys_descricao_cirurgia`.`rqf` BETWEEN "'.(int)$_POST['qntfios1'].'" AND "'.(int)$_POST['qntfios2'].'") ';
		$bcumb .= 'Quantidade de fios: "'.$_POST['qntfios1'].'" a "'.$_POST['qntfios2'].'" , ';
	}

	if(!empty($_POST['regoperada'])){
		$query .= ' AND `sys_descricao_cirurgia`.`local` LIKE "'.utf8_decode($_POST['regoperada']).'" ';
		$bcumb .= 'Região operada: "'.$_POST['regoperada'].'" , ';
	}

	if(!empty($_POST['focoprincipal'])){
		$query .= ' AND `sys_descricao_cirurgia`.`focoprincipal` LIKE "'.utf8_decode($_POST['focoprincipal']).'" ';
		$bcumb .= 'Foco principal: "'.$_POST['focoprincipal'].'" , ';
	}

	if(!empty($_POST['desenhoconservador'])){
		$query .= ' AND `sys_descricao_cirurgia`.`campodesenho` LIKE "'.utf8_decode($_POST['desenhoconservador']).'" ';
		$bcumb .= 'Desenho conservador: "'.$_POST['desenhoconservador'].'" , ';
	}

	if(!empty($_POST['densidade'])){
		$query .= ' AND `sys_planejamento_cirurgia`.`densidade` LIKE "'.utf8_decode($_POST['densidade']).'" ';
		$bcumb .= 'Densidade: "'.$_POST['densidade'].'" , ';
	}

	if(!empty($_POST['corfio'])){
		$query .= ' AND `sys_planejamento_cirurgia`.`cor` LIKE "'.utf8_decode($_POST['corfio']).'" ';
		$bcumb .= 'Cor do fio: "'.$_POST['corfio'].'" , ';
	}

	if(!empty($_POST['diametrofio'])){
		$query .= ' AND `sys_planejamento_cirurgia`.`diametro` LIKE "'.utf8_decode($_POST['diametrofio']).'" ';
		$bcumb .= 'Diâmetro do fio: "'.$_POST['diametrofio'].'" , ';
	}

	if(!empty($_POST['tipofio'])){
		$query .= ' AND `sys_planejamento_cirurgia`.`tipo` LIKE "'.utf8_decode($_POST['tipofio']).'" ';
		$bcumb .= 'Tipo do fio: "'.$_POST['tipofio'].'" , ';
	}

	if(!empty($_POST['elasticidade'])){
		$query .= ' AND `sys_planejamento_cirurgia`.`elasticidade` LIKE "'.utf8_decode($_POST['elasticidade']).'" ';
		$bcumb .= 'Elasticidade: "'.$_POST['elasticidade'].'" , ';
	}

	if(!empty($_POST['totufs1']) && !empty($_POST['totufs2'])){
		$query .= ' AND (`sys_descricao_cirurgia`.`total` BETWEEN "'.$_POST['totufs1'].'" AND "'.$_POST['totufs2'].'") ';
		$bcumb .= 'Total UFs: "'.$_POST['totufs1'].'" a "'.$_POST['totufs2'].'" , ';
	}

	if(!empty($_POST['anestesista'])){
		$query .= ' AND `sys_descricao_cirurgia`.`anest` LIKE "'.$_POST['anestesista'].'" ';
		$bcumb .= 'Anestesista: "'.$_POST['anestesista'].'", ';
	}

	if(!empty($_POST['bomaula'])){
		$query .= ' AND `sys_imagemfull`.`famoso` = 1 ';
		$bcumb .= 'Bom para aula, ';
	}

	if(!empty($_POST['famoso'])){
		$query .= ' AND `sys_pacientes`.`famoso` = 1 ';
		$bcumb .= 'Famoso, ';
	}

	if(!empty($_POST['period'])){
		$query .= ' AND `sys_imagemfull`.`periodo` LIKE "'.utf8_decode($_POST['period']).'" ';
		$bcumb .= 'Período: "'.$_POST['period'].'" , ';
	}
	
	$html .= '<h4>Resultados da busca por:</h4> <div class="clear h10"></div> '.$bcumb.' <div class="clear h10"></div><hr><div class="clear h10"></div>';
	

	$pac_search = new Crud('sys_pacientes');
	$pac_searchQR = $pac_search->customQuery('
	SELECT `sys_pacientes`.`idPaciente` as pacID , `sys_pacientes`.`nome` as pacNome , `sys_imagemfull`.`linkPaciente` as pasta , 
		`sys_imagemfull`.`linkImagem` as imagem 
	FROM `sys_pacientes` 
	LEFT JOIN `sys_imagemfull` ON `sys_imagemfull`.`idPaciente`=`sys_pacientes`.`idPaciente` 
	LEFT JOIN `sys_descricao_cirurgia` ON `sys_descricao_cirurgia`.`idPaciente`=`sys_pacientes`.`idPaciente` 
	LEFT JOIN `sys_planejamento_cirurgia` ON `sys_planejamento_cirurgia`.`idPaciente`=`sys_pacientes`.`idPaciente` 
	WHERE 1=1 '.$query.'
	');
	
	
	if(mysql_num_rows($pac_searchQR)>0){
		
		$puxaFoto = new Crud('sys_multi_uploads');
	
		while($csDATA = mysql_fetch_array($pac_searchQR)){
			$resultARR[$csDATA['pacID']]['image'][] = $csDATA['imagem'];
			$resultARR[$csDATA['pacID']]['folder'][$csDATA['pasta']] = $csDATA['pasta'];
			$resultARR[$csDATA['pacID']]['nome'] = utf8_encode($csDATA['pacNome']);
			$resultARR[$csDATA['pacID']]['idPaciente'] = $csDATA['pacID'];
			
			$getFoto = $puxaFoto->selectArrayConditions("file",'user_id="'.$csDATA['pacID'].'" AND type_id=2 LIMIT 1');
			if(mysql_num_rows($getFoto)>0){
				$fotoQR = mysql_fetch_array($getFoto);
				$foto = 'js/AJAXupload/files/thumbnail/'.$fotoQR['file'];
			} elseif(file_exists("avatar/".$csDATA['pacID'].".jpg")) {
				$foto = 'avatar/'.$csDATA['pacID'].'.jpg';
			} else {
				$foto = 'img/user.png';
			}
			
			$resultARR[$csDATA['pacID']]['avatar'] = $foto;
		}
				
		$html .= '
			<table class="table table-bordered table-striped table-hover">
				<tr>
					<th width="10%">Avatar</th>
					<th width="15%">Nome</th>
					<th width="65%">Imagens relativas ao filtro</th>
					<th width="10%">Ações</th>
				</tr>';
		
		foreach($resultARR as $key => $value){
		  $html .= '<tr>
					<td><img style="height:70px; width:auto;" src="'.$value['avatar'].'" /></td>
					<td>'.$value['nome'].'</td>
					<td>';
					
					foreach($value['folder'] as $key2 => $value2){
						$pastafotos = 'fotos/'.$value2.'/';
						$arquivos = glob("$pastafotos{*.jpg,*.png,*.gif,*.bmp,*.JPG,*.PNG,*.GIF,*.BMP}", GLOB_BRACE);
						
						foreach($arquivos as $images){
							$filename = substr($images, -21, 17);
							if(in_array($filename, $value['image'])){
								$html .= '<a class="fancybox" data-fancybox-group="gallery" href="thumb2.php?img='.$images.'">
										  	<img style="height:70px; width:auto;" class="imgResultsAdvSearch" src="thumb.php?img='.$images.'" />
										  </a>';
							}
						}
					}
					
		  $html .= '</td>
					<td>
						<button class="btn btn-sm btn-primary"><i class="fa fa-search" title="Selecionar paciente" onclick="window.location=\'pacientes.php?selecionar='.$value['idPaciente'].'\'"></i></button>
					</td>
				  </tr>';
		}
		$html .= '
			</table>';
	} else {
		$html .= '<div class="alert alert-info">Nenhum resultado correspondente aos filtros selecionados.</div>';
	}
	

} else {
	$html .= '<h5>Busca avançada (deixar campos em branco para buscar todos)</h5><hr>

	<form action="?buscaavancada" id="form1" method="post">
			
			<div class="form-group" style="width:50%">
				<label for="nome">Nome </label>
				<input type="text" class=" form-control"  name="nome">
			</div>
			
			<div class="clear h10"></div>
			
			<div class="form-group pull-left" style="width:10%">
				<label for="nome">Idade inicial </label>
				<input type="number" class=" form-control"  name="idade1">
			</div>
			
			<div class="form-group pull-left" style="width:10%">
				<label for="nome">Idade final </label>
				<input type="number" class=" form-control"  name="idade2">
			</div>
			
			<div class="form-group pull-left" style="width:20%">
				<label for="nome">Sexo </label>
					<div class="clear"></div>
					<div class="pull-left" style="width:45%">
						<input type="radio" class="pull-left" name="sexo" value="M"> Masculino
					</div>
					<div class="pull-left" style="width:45%">
						<input type="radio" class="pull-left" name="sexo" value="F"> Feminino
					</div>
			</div>
			
			<div class="clear h10"></div>

			<div class="form-group" style="width:50%">
				<label for="nome">Cidade </label>
				<input type="text" class=" form-control"  name="cidade">
			</div>
			
			<div class="clear h10"></div>

			<div class="form-group" style="width:25%">
				<label for="nome">Indicação </label>
				<input type="text" class=" form-control"  name="indicacao">
			</div>

			<div class="form-group" style="width:25%">
				<label for="nome">Profissão </label>
				<input type="text" class=" form-control"  name="profissao">
			</div>
			
			<div class="clear h10"></div>

			<div class="form-group" style="width:50%">
				<div class="pull-left" style="display:inline-block; margin: 5px;"><input type="checkbox" name="cirgOutroMedico" value="1"> Cirurgia com outro médico</div>
			</div>
			
			<div class="clear h10"></div>
			
			<div class="form-group" style="width:50%">
				<div class="pull-left" style="display:inline-block; margin: 5px;"><input type="checkbox" name="autorizacao" value="1"> Exibir apenas pacientes que autorizam o uso de sua imagem</div>
			</div>

			<div class="clear h10"></div>
			
			<div class="form-group" style="width:50%">
				<div class="pull-left" style="display:inline-block; margin: 5px;"><input type="checkbox" name="bomaula" value="1"> Bom para aula</div>
			</div>

			<div class="clear h10"></div>
			
			<div class="form-group" style="width:50%">
				<div class="pull-left" style="display:inline-block; margin: 5px;"><input type="checkbox" name="famoso" value="1"> Famoso</div>
			</div>

			<div class="clear h10"></div>
			
			<div class="form-group" style="width:50%">
				<label for="nome">Período </label>
				<select name="period" class="form-control">
				<option></option>';
		
					foreach($selectableData['periodo'] as $value){
						$html .= '<option>'.$value.'</option>';
					}
					
				$html .= '
				</select>
				<div class="clear"></div>
			</div>
			
			<div class="clear h10"></div>
			
			<div class="form-group" style="width:15%">
				<label for="nome">Quantidade de fios inicial</label>
				<input type="number" class=" form-control"  name="qntfios1">
			</div>
			
			<div class="form-group" style="width:15%">
				<label for="nome">Quantidade de fios final</label>
				<input type="number" class="form-control"  name="qntfios2">
			</div>
			
			<div class="clear h10"></div>

			<div class="form-group" style="width:50%">
				<label for="nome">Região operada </label>
					<select class="form-control" name="regoperada">
					<option></option>';
						
						foreach($selectableData['regiaooperada'] as $value){
							$html .= '<option>'.$value.'</option>';
						}
						
					$html .= '
					</select>
					<div class="clear"></div>
			</div>
			
			<div class="clear h10"></div>

			<div class="form-group" style="width:50%">
				<label for="nome">Foco principal </label>
					<select class="form-control" name="focoprincipal">
					<option></option>';
						
						foreach($selectableData['regiaooperada'] as $value){
							$html .= '<option>'.$value.'</option>';
						}
						
					$html .= '
					</select>
					<div class="clear"></div>
			</div>
			
			<div class="clear h10"></div>
			
			<div class="form-group" style="width:50%">
				<label for="nome">Desenho conservador </label>
					<select class="form-control" name="desenhoconservador">
					<option></option>';
						
						foreach($selectableData['desenhocons'] as $value){
							$html .= '<option>'.$value.'</option>';
						}
						
					$html .= '
					</select>
					<div class="clear"></div>
			</div>
			
			<div class="clear h10"></div>
			
			<div class="form-group" style="width:50%">
				<label for="nome">Densidade </label>
					<select class="form-control" name="densidade">
					<option></option>';
						
						foreach($selectableData['densidade'] as $value){
							$html .= '<option>'.$value.'</option>';
						}
						
					$html .= '
					</select>
					<div class="clear"></div>
			</div>
			
			<div class="clear h10"></div>
			
			<div class="form-group" style="width:50%">
				<label for="nome">Cor do fio </label>
					<select class="form-control" name="corfio">
					<option></option>';
						
						foreach($selectableData['corfio'] as $value){
							$html .= '<option>'.$value.'</option>';
						}
						
					$html .= '
					</select>
					<div class="clear"></div>
			</div>
			
			<div class="clear h10"></div>
			
			<div class="form-group" style="width:50%">
				<label for="nome">Diâmetro do fio </label>
					<select class="form-control" name="diametrofio">
					<option></option>';
						
						foreach($selectableData['diametrofio'] as $value){
							$html .= '<option>'.$value.'</option>';
						}
						
					$html .= '
					</select>
					<div class="clear"></div>
			</div>
			
			<div class="clear h10"></div>
			
			<div class="form-group" style="width:50%">
				<label for="nome">Tipo do fio </label>
					<select class="form-control" name="tipofio">
					<option></option>';
						
						foreach($selectableData['tipofio'] as $value){
							$html .= '<option>'.$value.'</option>';
						}
						
					$html .= '
					</select>
					<div class="clear"></div>
			</div>
			
			<div class="clear h10"></div>
			
			<div class="form-group" style="width:50%">
				<label for="nome">Elasticidade </label>
					<select class="form-control" name="elasticidade">
					<option></option>';
						
						foreach($selectableData['elasticidade'] as $value){
							$html .= '<option>'.$value.'</option>';
						}
						
					$html .= '
					</select>
					<div class="clear"></div>
			</div>
			
			<div class="clear h10"></div>
			
			<div class="form-group" style="width:15%">
				<label for="nome">Total UFs  inicial</label>
				<input type="number" class=" form-control" name="totufs1">
			</div>
			
			<div class="form-group" style="width:15%">
				<label for="nome">Total UFs  final</label>
				<input type="number" class="form-control" name="totufs2">
			</div>
			
			<div class="clear h10"></div>
			
			<div class="form-group" style="width:50%">
				<label for="nome">Anestesista  </label>
				<input type="text" class=" form-control" name="anestesista">
			</div>
			
			<div class="clear h10"></div>
			
			<button class="btn btn-success pull-left" type="submit"><i class="fa fa-search"></i> Buscar</button>
		  </form><div class="clear h10"></div>';
}

$html .= closeFullWidget();
	

echo $html;

include 'inc/footer.inc.php';

?>