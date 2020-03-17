<?php

// CONFIGURACOES DA PAGINA

$menuItem = 3;

$pagefile = 'pacientes.php';

$database = 'sys_pacientes';

// INCLUDES

include 'inc/config.inc.php';

if (isset($_GET['relatorio']) && isset($_GET['print'])) {
} else {

	include 'inc/header.inc.php';

}

// FORM TREE

$lp1 = new Crud('sys_convenio');

$lp1qr = $lp1 -> selectArray('*');

while ($lp1Data = mysql_fetch_array($lp1qr)) {

	$loopConvenio[] = array(utf8_encode($lp1Data['nome']), utf8_encode($lp1Data['nome']));

}

//-----------------------------------------DADOS PESSOAIS------------------------------------//

$formStructure = array(

//Dados pessoais

array('Dados pessoais', 'divider'), array('Nome completo*', 'nome', 'text', ' form-control doubleField validate[required]'), array('Data de nascimento', 'dataNascimento', 'data', 'form-control'), array('Idade', 'idade', 'idade', 'form-control'), array('Sexo', 'sexo', 'select' => array( array('M', 'Masculino'), array('F', 'Feminino')), 'form-control'), array('RG|RNE', 'rg', 'text', 'form-control'), array('CPF', 'cpf', 'text', 'validate[funcCall[validateCPF]] form-control'), array('Estado civil', 'estadoCivil', 'select' => array( array('S', 'Solteiro(a)'), array('C', 'Casado(a)'), array('D', 'Divorciado(a)'), array('V', 'Viúvo(a)')), 'form-control'), array('Profissão', 'ocupacao', 'text', 'form-control'), array('Indicação', 'indicacao', 'text', 'form-control'),

//Contatos

array('Contatos', 'divider'), array('Telefone residencial', 'telefoneRes', 'ddd' => 'ddd_res', 'form-control'), array('Telefone celular', 'celular', 'ddd' => 'ddd_celular', 'form-control'), array('Telefone comercial', 'telefoneCom', 'ddd' => 'ddd_com', 'form-control'), array('Falar com', 'falarcom', 'text', 'form-control'), array('E-mail', 'email', 'text', 'form-control doubleField'),

//Endereço

array('Endereço', 'divider'), array('CEP', 'cep', 'text', 'form-control'), array('País', 'pais', 'text', 'form-control'), array('Estado', 'estado', 'text', 'form-control'), array('Cidade', 'cidade', 'text', 'form-control'), array('Bairro', 'bairro', 'text', 'form-control'), array('Endereço completo', 'endereco', 'text', 'form-control doubleField'), array('Número', 'numero', 'text', 'form-control'), array('Complemento', 'complemento', 'text', 'form-control'),

//Consulta

array('Consulta', 'divider'), array('Data da primeira consulta', 'dataPrimeiraConsulta', 'data', 'form-control'), array('Último retorno', 'ultimoRetorno', 'data', 'form-control'), array('Convênio médico', 'convenio', 'select' => $loopConvenio, ' form-control'),

//Observacoes

array('Observacoes gerais', 'divider'), array('Observacoes', 'observacao', 'obs', 'form-control'),

//Autorizo uso da imagem

array('Termos e condições', 'divider'), array('', 'autorizacao', 'checkbox' => 'Autorizo o uso das minhas imagens', 'form-control'),

//fomosos

array('Status paciente', 'divider'), array('', 'famoso', 'checkbox' => 'Selecionar paciente  |', 'form-control'), array('', 'falecido', 'checkbox' => 'Paciente falecido', 'form-control'));

//Historico cirurgia

$formCirurgia = array( array('Data', 'data', 'data', 'form-control validate[required]'), array('Título cirurgia', 'title', 'select' => $selectableData['titulocir'], 'form-control'), array('Hospital', 'hospital', 'text', 'form-control'), array('S', 's', 'text', 'form-control'), array('D', 'd', 'text', 'form-control'), array('T', 't', 'text', 'form-control'), array('Q|FG', 'umdoiscinco', 'text', 'form-control'), array('Faixa', 'faixa', 'text', 'form-control'), array('Haste', 'haste', 'select' => $selectableData['haste'], 'form-control'), array('Fio', 'fio', 'select' => $selectableData['fio'], 'form-control'), array('Local', 'local', 'select' => $selectableData['regiaooperada'], 'form-control'), array('Foco princ.', 'focoprincipal', 'select' => $selectableData['regiaooperada'], 'form-control'), array('Des.Conservador', 'campodesenho', 'select' => $selectableData['desenhocons'], 'form-control'), array('Nota', 'nota', 'text', 'form-control'), array('Retirada', 'retirada', 'text', 'form-control'), array('Peças', 'pecas', 'text', 'form-control'), array('A', 'a', 'text', 'form-control'), array('D', 'd2', 'text', 'form-control'), array('Qnt. de fios', 'qntfios', 'text', 'form-control'), array('R.Q.F.', 'rqf', 'text', 'form-control'), array('Elasticidade', 'elast', 'select' => $selectableData['elasticidade'], 'form-control'), array('Implantação', 'imp', 'select' => $selectableData['implantacao'], 'form-control'), array('Equipe', 'equipe', 'text', 'form-control'), array('Anestesista', 'anest', 'text', 'form-control'), array('Início', 'inicio', 'hora', 'form-control'), array('Colocação', 'coloc', 'hora', 'form-control'), array('Final corte', 'finco', 'hora', 'form-control'), array('Término', 'termino', 'hora', 'form-control'), array('TOTAL', 'total', 'text', 'form-control '), array('Observações', 'obs', 'text', 'form-control'));

$formPlan = array( array('Data do atendimento', 'data', 'data', 'form-control'), array('Título planejamento', 'cirurgia', 'select' => $selectableData['titulopla'], 'form-control'), array('Densidade', 'densidade', 'select' => $selectableData['densidade'], 'form-control'), array('Cor do fio', 'cor', 'select' => $selectableData['corfio'], 'form-control'), array('Tipo do fio', 'tipo', 'select' => $selectableData['tipofio'], 'form-control'), array('Diâmetro do fio', 'diametro', 'select' => $selectableData['diametrofio'], 'form-control'), array('Cor de pele', 'corpele', 'select' => $selectableData['corpele'], 'form-control'), array('Elasticidade', 'elasticidade', 'select' => $selectableData['elasticidade'], 'form-control'), array('Cond. couro cabeludo', 'condcouro', 'select' => $selectableData['condcouro'], 'form-control'), array('Estágio da calvície', 'estagiocal', 'select' => $selectableData['estagiocal'], 'form-control'), array('Região da operação', 'regoperada', 'select' => $selectableData['regiaooperada'], 'form-control'), array('N° enx. Anterior', 'anterior', 'text', 'form-control'), array('N° enx. Coroa', 'coroa', 'text', 'form-control'), array('N° enx. Ant. + Coroa', 'antcoroa', 'text', 'form-control'), array('Custo', 'custo', 'text', 'form-control'), array('Obs', 'obs', 'text', 'form-control'), );

// RENDERS

$html = '';
//renderMainTop('fa-medkit','Pacientes','Home / Pacientes');

if (isset($_GET['pg'])) { $pgn = $_GET['pg'];
}

// 	RECEBE O REDIRECIONAMENTO DA AGENDA E SETA O PACIENTE

if (isset($_GET['fromAgenda'])) {

	$editar = new Crud($database);

	$editDataQR = $editar -> selectArrayConditions('*', 'idPaciente = ' . $_GET['fromAgenda']);

	$editData = mysql_fetch_array($editDataQR);

	$_SESSION['selectedUser'] = utf8_encode($editData['nome']);

	$_SESSION['selectedUserID'] = $editData['idPaciente'];

	header('Location: ?editar&dAM=1');

}
if (isset($_GET['selecionar'])) {

	// CRIA A SESSAO COM O PACIENTE SELECIONADO

	$id = $_GET['selecionar'];

	$editar = new Crud($database);

	$editDataQR = $editar -> selectArrayConditions('*', 'idPaciente=' . $id);

	$editData = mysql_fetch_array($editDataQR);

	$_SESSION['selectedUser'] = utf8_encode($editData['nome']);

	$_SESSION['selectedUserID'] = $id;

	if (isset($_GET['redirAgenda'])) {

		header('Location: agenda.php?dAM=1');

	} else {

		header('Location: ?editar&dAM=1');

	}

}
if (isset($_GET['unselect'])) {

	// REMOVE A SELECAO DO PACIENTE

	unset($_SESSION['selectedUser']);

	unset($_SESSION['selectedUserID']);

	if (isset($_GET['novo'])) {

		header('Location: pacientes.php?novo');

	} else {

		header('Location: agenda.php');

	}

}

if (isset($_GET['excluir'])) {

	// CRUD::EXCLUIR

	if (empty($_GET['excluir'])) {

		$id = $_SESSION['selectedUserID'];

	} else {

		$id = $_GET['excluir'];

	}

	$deletar = new Crud($database);

	$deletar -> delete('idPaciente=' . $id);

	if ($deletar -> execute() == 1) {

		header('Location: ' . $pagefile . '?dAM=0');

	} else {

		header('Location: ' . $pagefile . '?dAM=1');

	}

}

// FIM CRUD::EXCLUIR

//Mensagem na parte selecionar um paciente amarela na hora de selecionar uma paciente.

//if(!isset($_SESSION['selectedUserID']) && !isset($_GET['dAM']) && !isset($_GET['pg']) && !isset($_GET['novo']) && !isset($_GET['singledisplay'])){

//	header('Location: ?dAM=2');

//}

elseif (isset($_GET['novo'])) {

	// CRUD::CRIAR

	// EXECUTAR

	if (isset($_GET['execute'])) {

		$dtFrmt1 = dataEncode($_POST['dataNascimento']);

		$dtFrmt2 = dataEncode($_POST['dataPrimeiraConsulta']);

		$dtFrmt3 = dataEncode($_POST['ultimoRetorno']);

		$objectID = $_POST['media_token'];

		$postValues = "'" . safeInsert($_POST['nome'], $mysqllink) . "',

		'" . safeInsert($_POST['endereco'], $mysqllink) . "',

		'" . safeInsert($_POST['bairro'], $mysqllink) . "',

		'" . safeInsert($_POST['cidade'], $mysqllink) . "',

		'" . $_POST['cep'] . "',

		'" . safeInsert($_POST['ddd_res'], $mysqllink) . "',

		'" . safeInsert($_POST['telefoneRes'], $mysqllink) . "',

		'" . safeInsert($_POST['ddd_com'], $mysqllink) . "',

		'" . safeInsert($_POST['telefoneCom'], $mysqllink) . "',

		'" . safeInsert($_POST['ddd_celular'], $mysqllink) . "',

		'" . safeInsert($_POST['celular'], $mysqllink) . "',

		'" . safeInsert($_POST['falarcom'], $mysqllink) . "',

		'" . safeInsert($_POST['email'], $mysqllink) . "',

		'" . safeInsert($_POST['rg'], $mysqllink) . "',

		'" . safeInsert($_POST['cpf'], $mysqllink) . "',

		'" . $dtFrmt1 . "',

		'" . safeInsert($_POST['estadoCivil'], $mysqllink) . "',

		'" . safeInsert($_POST['sexo'], $mysqllink) . "',

		'" . utf8_decode($_POST['indicacao']) . "',

		'" . utf8_decode($_POST['ocupacao']) . "',

		'" . $dtFrmt2 . "',

		'" . $dtFrmt3 . "',

		'" . safeInsert($_POST['estado'], $mysqllink) . "',

		'" . safeInsert($_POST['pais'], $mysqllink) . "',

		'" . date("Y-m-d") . "',

		'" . safeInsert($_POST['convenio'], $mysqllink) . "',

		'" . safeInsert($_POST['numero'], $mysqllink) . "',

		'" . safeInsert($_POST['complemento'], $mysqllink) . "',

		'" . safeInsert($_POST['observacao'], $mysqllink) . "',

		'" . $_POST['autorizacao'] . "',

		'" . $_POST['famoso'] . "',

		'" . $_POST['falecido'] . "'";

		$insere = new Crud($database);

		$insere -> insert("`nome`, `endereco`, `bairro`, `cidade`, `cep`, `ddd_res`,`telefoneRes`, `ddd_com`,`telefoneCom`, `ddd_celular`,`celular`,`falarcom`, `email`, `rg`, `cpf`, `dataNascimento`, `estadoCivil`, `sexo`, `indicacao`, `ocupacao`, `dataPrimeiraConsulta`, `ultimoRetorno`, `estado`, `pais`, `dataCadastro`, `convenio`, `numero`, `complemento`, `observacao` , `autorizacao`,`famoso`,`falecido`", $postValues);

		if ($insere -> execute() == 1) {

			die(mysql_error($mysqllink));

			//header('Location: '.$pagefile.'?dAM=0');

		} else {

			$userID = mysql_insert_id();

			$update = new Crud('sys_multi_uploads');

			$update -> update("object_id='" . $userID . "', user_id='" . $userID . "'", 'object_id="' . $objectID . '"');

			$update -> execute();

			header('Location: ' . $pagefile . '?selecionar=' . $userID . '&redirAgenda');

		}

	}

	// EXIBIR INPUT

	//$controlButtons = array(

	//	'btn-success|'.$pagefile.'|fa-reply|Voltar'

	//);

	//$html .= controlButtons($controlButtons);

	$html .= '<form action="?novo&execute" class="validate exitCheck" id="form1" method="post">

              	<fieldset>';

	$html .= openFullWidget('Novo paciente', $actions = 1);

	$html .= formCreateInsert($formStructure, date("d/m/Y"), false, $foto = 1);

	$html .= '</fieldset>

		  </form>';

	$html .= closeFullWidget();

	$html .= '

	<div class="modal fade" id="webcamModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                <h4 class="modal-title">Foto da webcam</h4>

            </div>

            <input type="hidden" id="webcam-image">

            <div class="modal-body">

            </div>

            <div class="modal-footer">

                <span class="loading"></span>

                <button type="button" class="btn btn-default cancel" data-dismiss="modal">Cancelar</button>

                <button type="button" class="btn btn-primary capture" onclick="imgSelect.webcamSnap()">Capturar</button>

                <button type="button" class="btn btn-info new" onclick="imgSelect.webcam();">Tirar outra</button>

                <button type="button" class="btn btn-success save">Salvar</button>

            </div>

        </div>

    </div>

	</div>

';

	// FIM CRUD::CRIAR

} elseif (isset($_GET['editar'])) {

	// CRUD::EDITAR

	$submenuItem == 32;

	$id = $_SESSION['selectedUserID'];

	$editar = new Crud($database);

	$editDataQR = $editar -> selectArrayConditions('*', 'idPaciente=' . $id);

	$editData = mysql_fetch_array($editDataQR);

	// EXECUTAR

	if (isset($_GET['execute'])) {

		$dtFrmt1 = dataEncode($_POST['dataNascimento']);

		$dtFrmt2 = dataEncode($_POST['dataPrimeiraConsulta']);

		$dtFrmt3 = dataEncode($_POST['ultimoRetorno']);

		$postValues = "`nome`='" . safeInsert($_POST['nome'], $mysqllink) . "',

		`endereco`='" . safeInsert($_POST['endereco'], $mysqllink) . "',

		`bairro`='" . safeInsert($_POST['bairro'], $mysqllink) . "',

		`cidade`='" . safeInsert($_POST['cidade'], $mysqllink) . "',

		`cep`='" . $_POST['cep'] . "',

		`ddd_res`='" . safeInsert($_POST['ddd_res'], $mysqllink) . "',

		`telefoneRes`='" . safeInsert($_POST['telefoneRes'], $mysqllink) . "',

		`ddd_com`='" . safeInsert($_POST['ddd_com'], $mysqllink) . "',

		`telefoneCom`='" . safeInsert($_POST['telefoneCom'], $mysqllink) . "',

		`ddd_celular`='" . safeInsert($_POST['ddd_celular'], $mysqllink) . "',

		`celular`='" . safeInsert($_POST['celular'], $mysqllink) . "',

		`falarcom`='" . safeInsert($_POST['falarcom'], $mysqllink) . "',

		`email`='" . safeInsert($_POST['email'], $mysqllink) . "',

		`rg`='" . safeInsert($_POST['rg'], $mysqllink) . "',

		`cpf`='" . safeInsert($_POST['cpf'], $mysqllink) . "',

		`dataNascimento`='" . $dtFrmt1 . "',

		`estadoCivil`='" . safeInsert($_POST['estadoCivil'], $mysqllink) . "',

		`sexo`='" . safeInsert($_POST['sexo'], $mysqllink) . "',

		`indicacao`='" . safeInsert($_POST['indicacao'], $mysqllink) . "',

		`ocupacao`='" . safeInsert($_POST['ocupacao'], $mysqllink) . "',

		`dataPrimeiraConsulta`='" . $dtFrmt2 . "',

		`ultimoRetorno`='" . $dtFrmt3 . "',

		`pais`='" . safeInsert($_POST['pais'], $mysqllink) . "',

		`estado`='" . safeInsert($_POST['estado'], $mysqllink) . "',

		`convenio`='" . safeInsert($_POST['convenio'], $mysqllink) . "',

		`cadRapido`=0,

		`numero`='" . safeInsert($_POST['numero'], $mysqllink) . "',

		`complemento`='" . safeInsert($_POST['complemento'], $mysqllink) . "',

		`observacao`='" . safeInsert($_POST['observacao'], $mysqllink) . "',

		`autorizacao`='" . $_POST['autorizacao'] . "',

		`famoso`='" . $_POST['famoso'] . "',

		`falecido`='" . $_POST['falecido'] . "'";

		$editar -> update($postValues, 'idPaciente=' . $_GET['editar']);
		$editaAgenda = new Crud('sys_agenda');
		$editaAgenda -> update('`nomePaciente`="' . safeInsert($_POST['nome'], $mysqllink) . '"', 'idPaciente=' . $_GET['editar']);
		$editaAgenda -> execute();

		if ($editar -> execute() == 1) {

			header('Location: ' . $pagefile . '?editar&dAM=0');

		} else {

			header('Location: ' . $pagefile . '?editar&dAM=1');

		}

	}

	// EXIBIR INPUT

	$html .= '<form action="?editar=' . $id . '&execute" class="validate exitCheck" id="form1" method="post">

              	<fieldset>';

	$html .= openFullWidget('Informações pessoais do paciente', $actions = 1);

	$html .= formCreateEdit($formStructure, $editData, $utf8 = 1, $foto = 1);

	$html .= '</fieldset>

		  </form><div class="clear h10"></div>';

	$html .= closeFullWidget();

	$html .= '

	<div class="modal fade" id="webcamModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                <h4 class="modal-title">Foto da webcam</h4>

            </div>

            <input type="hidden" id="webcam-image">

            <div class="modal-body">

            </div>

            <div class="modal-footer">

                <span class="loading"></span>

                <button type="button" class="btn btn-default cancel" data-dismiss="modal">Cancelar</button>

                <button type="button" class="btn btn-primary capture" onclick="imgSelect.webcamSnap()">Capturar</button>

                <button type="button" class="btn btn-info new" onclick="imgSelect.webcam();">Tirar outra</button>

                <button type="button" class="btn btn-success save">Salvar</button>

            </div>

        </div>

    </div>

	</div>

';

	// FIM CRUD::EDITAR

} elseif (isset($_GET['imagens'])) {

	$submenuItem == 33;

	$id = $_SESSION['selectedUserID'];

	$token_img = md5(uniqid(rand(), true));

	$html .= openFullWidget('<i class="fa fa-picture-o"></i> Imagens do paciente', false, false);

	$html .= '

	   <iframe name="imgDownload" id="imgDownload" style="display:none !important;"></iframe>

	   

	   <form name="imagensForm" method="post" action="imgDownload.php" target="imgDownload">

	   

	   <div id="fileupload">   

		  

		  <ul class="files images gallery">

		  </ul>

		  

		  <div class="clear"></div>

		  <ul class="old_images">';

	$getOldImg = new Crud('sys_imagemfull');

	$getOldImgQR = $getOldImg -> selectArrayConditions('*', 'idPaciente=' . $id . ' GROUP BY `linkPaciente`');

	while ($gOIQR = mysql_fetch_array($getOldImgQR)) {

		$pastafotos = 'fotos/' . $gOIQR['linkPaciente'] . '/';

		$arquivos = glob("$pastafotos{*.jpg,*.jpeg,*.png,*.gif,*.bmp,*.JPG,*.JPEG,*.PNG,*.GIF,*.BMP}", GLOB_BRACE);

		//echo $pastafotos;

		$html .= '<input type="hidden" name="nomePaciente" value="' . utf8_encode($gOIQR['nome']) . '" />';

		foreach ($arquivos as $img) {

			$linkImagem = substr($img, -21, 17);

			$getOldDetQR = $getOldImg -> selectArrayConditions('*', 'linkImagem = "' . $linkImagem . '"');

			$gODQR = mysql_fetch_array($getOldDetQR);

			$getCirgData = new Crud('sys_descricao_cirurgia');

			$getCirgDataObj = $getCirgData -> selectArrayConditions('title', 'id = ' . $gODQR['dataTable_cirurgia']);

			if ($getCirgDataObj) {$getCirgDataQR = mysql_fetch_array($getCirgDataObj);
			}

			$getPlanData = new Crud('sys_planejamento_cirurgia');

			$getPlanDataObj = $getPlanData -> selectArrayConditions('cirurgia', 'id = ' . $gODQR['dataTable_planejamento']);

			if ($getPlanDataObj) {$getPlanDataQR = mysql_fetch_array($getPlanDataObj);
			}

			$imageDados = array('Cirurgia Tipo:' => $gODQR['cirurgiatipo'] ? utf8_encode($gODQR['cirurgiatipo']) : '', 'Período:' => $gODQR['periodo'] ? utf8_encode($gODQR['periodo']) : '', 'Selecionada:' => $gODQR['famoso'] ? 'Sim' : 'Não', 'Cirurgia:' => $getCirgDataQR['title'] ? utf8_encode($getCirgDataQR['title']) : '', 'Planejamento:' => $getPlanDataQR['cirurgia'] ? utf8_encode($getPlanDataQR['cirurgia']) : '', );

			$imageDetails = '';

			foreach ($imageDados as $key => $value) {

				if ($value) {

					$imageDetails .= '<b>' . $key . '</b><br>' . $value . '<hr>';

				}

			}

			$bkgndImg = "background-image:url('thumb.php?img=" . $img . "');";
			$html .= '<li id="' . $linkImagem . '" data-file="' . $img . '" data-toggle="popover" data-placement="auto right" data-title="Detalhes da imagem" data-content="' . $imageDetails . '" class="more-info">

								<span class="preview" id="title_' . $linkImagem . '" style="' . $bkgndImg . '">

									<input type="checkbox" name="imgDownload[]" value="' . $img . '" alt="' . $linkImagem . '" class="imgDownloadCB" />

									<a class="fancybox" data-fancybox-group="gallery" href="thumb2.php?img=' . $img . '">
									</a>

								</span>

								<div class="actions">

									<button type="button" class="btn btn-warning btn-xs edit_old" id="' . $linkImagem . '" title="Editar detalhes">

										<i class="fa fa-edit"></i></button>

									<button type="button" class="btn btn-danger btn-xs delete_old" onclick="if(confirm(\'Deseja realmente excluir?\')) { deletaOldImg(\'' . $linkImagem . '\'); } event.returnValue = false; return false;" title="Excluir imagem">

										<i class="fa fa-trash-o"></i></button>

								</div>

						</li>';

		}

		$haveFolder = 1;

		$ultimaPasta = $gOIQR['linkPaciente'];

	}

	$randomdateid = date('Ymd') . rand(111111111, 999999999);

	$pastaPaciente = isset($haveFolder) ? $ultimaPasta : $randomdateid;

	$crianovapasta = isset($haveFolder) ? 0 : 1;

	$html .= '

		  </ul>

		  <div class="clear h30"></div>

		  <div class="fileupload-buttonbar">

		 	  <input type="checkbox" id="imgDownloadBtn" name="cbSeleciona" class="imgDownloadCB" style="display:none;" onclick="CheckAll(this.checked)" /><i class="h5" id="imgDownloadBtn" style="display:none;" >Selecionar todos </i></br>

			  <button type="submit" class="btn btn-sm btn-info" id="imgDownloadBtn" style="display:none;"><i class="fa fa-cloud-download"></i> Download</button>

			  <button type="button" class="btn btn-sm btn-warning" id="imgMultiEdit" style="display:none;"><i class="fa fa-pencil-square-o"></i> Editar selecionadas</button>

		  </div>

	  </div>

	  </form>

	

	  

	  <iframe name="imgUpload" id="imgUpload" style="display:none !important;"></iframe>

	   

	  <form action="imgUpload.php?upload" method="post" id="imgUploadForm" enctype="multipart/form-data" target="imgUpload" style="margin:0; padding:0;">

		  <input type="hidden" id="idPac" name="idPac" value="' . $id . '">

		  <input type="hidden" id="pastaPaciente" name="pastaPaciente" value="' . $pastaPaciente . '">

		  <input type="hidden" id="nomePaciente" name="nomePaciente" value="' . $_SESSION['selectedUser'] . '">

		  <input type="hidden" id="crianovapasta" name="crianovapasta" value="' . $crianovapasta . '">

		  <span class="btn btn-sm btn-info fileinput-button"><i class="fa fa-upload"></i><span> Enviar fotos</span><input id="imgUpInpt" type="file" name="files[]" multiple></span>

		  <span class="btn btn-sm btn-success imgUploading" style="display:none;"><i class="fa fa-refresh fa-spin"></i><span> Enviando, por favor aguarde...</span></span>

	  </form>';

	$editImgTREE = array( array('Procedimento', 'imgedit_procedimento', 'text', 'form-control tagSplit2', 100), array('Região operada', 'imgedit_regoperada', 'text', 'form-control tagSplit2', 100), array('Quantidade de fios', 'imgedit_qntfios', 'text', 'form-control tagSplit2', 100), array('Cirurgias(outro médico)', 'imgedit_cirurgia', 'text', 'form-control tagSplit2', 100), array('Cor do cabelo', 'imgedit_corcabelo', 'text', 'form-control tagSplit2', 100), array('Fio', 'imgedit_fio', 'text', 'form-control tagSplit2', 100), array('Haste', 'imgedit_haste', 'text', 'form-control tagSplit2', 100), array('Indicação', 'imgedit_indicacao', 'text', 'form-control tagSplit2', 100), array('Estado', 'imgedit_estado', 'text', 'form-control tagSplit2', 100), array('Cidade', 'imgedit_cidade', 'text', 'form-control tagSplit2', 100), array('Data da imagem', 'imgedit_dataimg', 'data', 'form-control'), array('Data procedimento', 'imgedit_dataproc', 'data', ' form-control'), );

	$html .= '	  

	  <div id="editOldModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editOldModal" aria-hidden="true" style="display: none;">

			<div class="modal-dialog" style="width: 900px;">

			  <div class="modal-content">

                  <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                    <h4 class="modal-title">Editar características da imagem</h4>

                  </div>

					  <div class="modal-body">

					  <div class="alert alert-danger" id="multiDataEditAlert" style="margin-bottom:10px; display:none;"><b>Atenção:</b> Os dados inseridos aqui irão <b>substituir</b> os dados existentes em todas as imagens selecionadas.</div>

					  <div class="tabbable" style="margin-bottom: 18px;">

					  

					  <ul class="nav nav-tabs">

							<li class="active"><a href="#tab1"  role="tab" data-toggle="tab">Características cirurgicas</a></li>

							<li><a href="#tab2" role="tab" data-toggle="tab">Caracteristicas anteriores</a></li>

					   </ul>

					  

					  <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">

					  

					  <div class="tab-pane active" id="tab1">

						<div class="clear h10"></div>

						

						<div class="col-md-12">

							

							<div class="form-group" style="width:200px;">

								<label>Tipo cirurgia</label>

								<select name="cirurgiatipo" id="dataTable_cirurgiatipo" class="form-control">';

	foreach ($selectableData['cirurgiatipo'] as $value) {

		$html .= '<option>' . $value . '</option>';

	}

	$html .= '

								</select>

							</div>						

							

							<div class="form-group" style="width:200px;">

								<label>Período</label>

								<select name="period" id="dataTable_periodo" class="form-control">';

	foreach ($selectableData['periodo'] as $value) {

		$html .= '<option>' . $value . '</option>';

	}

	$html .= '

								</select>

							</div>

							

							

							

							

							

							<div class="form-group ">

								<label class="checkbox-inline" style="font-size: 14px;">

								<div class="clear h30"></div>

								<input type="checkbox" id="dataTable_famoso"> Bom para aula

							    </label><div class="clear"></div>

							</div>	

						</div>		

							

						<div class="clear h20"></div>

						

						<div class="col-md-6">

							<div class="form-group ">

								<label>Selecionar cirurgia</label>

								<select name="cirurgia" id="dataTable_cirurgia" class="form-control">

								<option value="0">- Selecione -</option>';

	$getCirg = new Crud('sys_descricao_cirurgia');

	$getCirgList = $getCirg -> selectArrayConditions('id,title', 'idPaciente = ' . $id . ' ORDER BY data ASC');

	while ($getCirgListQR = mysql_fetch_array($getCirgList)) {

		$html .= '<option value="' . $getCirgListQR['id'] . '">' . utf8_encode($getCirgListQR['title']) . '</option>';

	}

	$html .= '

								</select>

							</div>

							

							<div class="clear h10"></div>

							

							<div id="getImgDataTable_cirurgia">

							</div>

							

						</div>

						

						<div class="col-md-6">

							<div class="form-group ">

								<label>Selecionar planejamento</label>

								<select name="planejamento" id="dataTable_planejamento" class="form-control">

								<option value="0">- Selecione -</option>';

	$getPlan = new Crud('sys_planejamento_cirurgia');

	$getPlanList = $getPlan -> selectArrayConditions('id,cirurgia', 'idPaciente = ' . $id . ' ORDER BY data ASC');

	while ($getPlanListQR = mysql_fetch_array($getPlanList)) {

		$html .= '<option value="' . $getPlanListQR['id'] . '">' . utf8_encode($getPlanListQR['cirurgia']) . '</option>';

	}

	$html .= '

								</select>

							</div>

							

							<div class="clear h10"></div>

							

							<div id="getImgDataTable_planejamento">

							</div>

							

						</div>



						<div class="clear h20"></div>

						

						<button type="button" class="btn btn-success pull-right" data-dismiss="modal" aria-hidden="true" id="old_save2" alt="0">Salvar</button>

						

						<div class="clear"></div>

						

					  </div>

					 

					  

					 

					  <div class="tab-pane" id="tab2">

						  <div class="clear h10"></div>

						  <div class="alert alert-info" style="margin-bottom:5px;">Os campos abaixo permitem a criação de "tags". Digite uma característica e insira uma vírgula para digitar outra.</div>

						  <form id="imgDataForm" class="exitCheck">

						  ';

	$html .= formCreateInsert($editImgTREE);

	$html .= '

								<div class="form-group" style="width:95%;">

									<label for="imgedit_obs">Observações adicionais</label>

									<textarea class="form-control" id="imgedit_obs" name="imgedit_obs" style="height:150px;"></textarea>

								</div>

								<input type="hidden" id="id_upbd">

						  </form>

						  

					  <div class="clear h10"></div>

					  <button type="button" class="btn btn-success pull-right" data-dismiss="modal" aria-hidden="true" id="old_save" alt="0">Salvar</button>

					  <div class="clear"></div>

					  </div>

					  </div>

					  </div>

					  

					  </div>

					  

					  <div class="modal-footer">

						<button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true" id="multiSave" style="display:none;">Salvar para todos</button>

					  </div>

                </div>

			</div>

		</div>

	  

		';

	$html .= closeFullWidget();

} elseif (isset($_GET['documentos'])) {

	$html .= '



		<iframe class="galeria_frame" src="js/elfinder/elfinder.html" scrolling="no" frameborder="0" ></iframe>



	';

} elseif (isset($_GET['evolucao'])) {

	$submenuItem == 34;

	$formStructure = array( array('Gerenciar registros', 'divider'), array('Data', 'data', 'data', 'form-control'), array('Observações adicionais', 'text', 'obs', 'form-control'));

	if (isset($_GET['add'])) {

		$postValues = "

		'" . $_SESSION['selectedUserID'] . "',

		'" . dataEncode($_POST['data']) . "',

		'" . utf8_decode($_POST['text']) . "

		'";

		$insere = new Crud('sys_evolucao_paciente');

		$insere -> insert("`idPaciente`, `data`, `text`", $postValues);

		if ($insere -> execute() == 1) {

			header('Location: ?evolucao&dAM=0');

		} else {

			header('Location: ?evolucao&dAM=1');

		}

	}

	if (isset($_GET['delete'])) {

		$id = $_GET['delete'];

		$deletar = new Crud('sys_evolucao_paciente');

		$deletar -> delete('id=' . $id);

		if ($deletar -> execute() == 1) {

			header('Location: ?evolucao&dAM=0');

		} else {

			header('Location: ?evolucao&dAM=1');

		}

	}

	if (isset($_GET['edit'])) {

		if (isset($_GET['act'])) {

			$editar = new Crud('sys_evolucao_paciente');

			$editar -> update('text="' . utf8_decode($_POST['text']) . '"', 'id=' . $_GET['act']);

			if ($editar -> execute() == 1) {

				header('Location: ?evolucao&dAM=0');

			} else {

				header('Location: ?evolucao&dAM=1');

			}

		}

		$id = $_GET['edit'];

		$editar = new Crud('sys_evolucao_paciente');

		$editDataQR = $editar -> selectArrayConditions('*', 'id=' . $id);

		$editData = mysql_fetch_array($editDataQR);

		$html .= '<form action="?evolucao&edit&act=' . $id . '" class="validate exitCheck" id="form1" method="post">

						<fieldset>';

		$html .= openFullWidget('Editar registro', $actions = 1);

		$html .= formCreateEdit($formStructure, $editData, $utf8 = 1);

		$html .= '</fieldset>

				  </form><div class="clear h10"></div>';

		$html .= closeFullWidget();

	} else {

		$html .= openFullWidget('Tratamento clínico e evolução do paciente', false, false);

		$id = $_SESSION['selectedUserID'];

		$editar = new Crud('sys_evolucao_paciente');

		$editDataQR = $editar -> selectArrayConditions('*', 'idPaciente=' . $id . ' ORDER BY data DESC');

		$html .= '<div class="col-md-6">

						<ul class="list-group">';

		while ($editData = mysql_fetch_array($editDataQR)) {

			$hasdata = 1;

			$html .= '

					  <li class="list-group-item">

						<button class="btn btn-danger pull-right btn-xs" type="button" onclick="if(confirm(\'Deseja realmente excluir?\')) { window.location=\'?evolucao&delete=' . $editData['id'] . '\'; } event.returnValue = false; return false;"><i class="fa fa-times"></i></button>

						<button class="btn btn-warning pull-right btn-xs" type="button" onclick="window.location=\'?evolucao&edit=' . $editData['id'] . '\'"><i class="fa fa-edit"></i></button>

						<span class="label label-warning">' . dataDecode($editData['data']) . '</span>

						<div class="clear h10"></div>

						' . utf8_encode($editData['text']) . '

					  </li>

					';

		}

		$html .= '</ul></div>';

		if (!isset($hasdata)) {$html .= '<div class="alert alert-info">Nenhum registro inserido.</div>';
		}

		$html .= '<div class="col-md-6">';

		$html .= '<form action="?evolucao&add" class="validate exitCheck" id="form1" method="post" style="margin-top: -20px;">

						<fieldset>';

		$html .= formCreateInsert($formStructure, $getdate = date("d/m/Y"));

		$html .= '</fieldset>

				  

				  <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>

				  

				  </form></div><div class="clear h10"></div>';

		$html .= closeFullWidget();

	}

} elseif (isset($_GET['diagnostico'])) {

	$submenuItem == 34;

	$formStructure = array( array('Diagnóstico', 'diagnostico', 'textarea', 'form-control diagText'), array('Tratamento clínico pregresso', 'traclipre', 'textarea', 'form-control diagText'), array('Patologias prévias e atuais', 'patologias', 'textarea', 'form-control diagText'), array('Alergias', 'alergias', 'textarea', 'form-control diagText'), array('Cirurgias', 'cirurgias', 'textarea', 'form-control diagText'), array('', 'checkCirurgia', 'formGroupComps' => '" style="', 'checkbox' => 'Cirurgia capilar prévia', 'form-control checkCirurgia'), array('Descrição da cirurgia capilar prévia', 'formGroupComps' => 'checkCirurgia_txt', 'cirgCapPrevDesc', 'textarea', 'form-control diagText'), array('Anestesia', 'anestesia', 'textarea', 'form-control diagText'), array('Uso de medicação', 'medicacao', 'textarea', 'form-control diagText'), array('Hábitos', 'habitos', 'textarea', 'form-control diagText'), array('Observacoes adicionais', 'observacoes', 'textarea', 'form-control diagText'), );

	$formStructureDiag2 = array( array('História Pregressa', 'divider'), array('Início calvície', 'iniCalvicie', 'text', 'form-control'), array('Perda recente de cabelo', 'perdaRecente', 'text', 'form-control'), array('Tipo de calvície', 'tipoCalvicie', 'text', 'form-control'), array('Uso de peruca', 'usoAplique', 'text', 'form-control'), array('História Famíliar', 'divider'), array('Pai', 'pai', 'text', 'form-control'), array('Mãe', 'mae', 'text', 'form-control'), array('Irmãos/Irmãs', 'irmaosIrmas', 'text', 'form-control'), array('Tios/tias paterno', 'tiosTiasPaterno', 'text', 'form-control'), array('Tios/tias materno', 'tiosTiasMaterno', 'text', 'form-control'), array('Avô paterno', 'avoPaterno', 'text', 'form-control'), array('Avó paterna', 'avoPaterna', 'text', 'form-control'), array('Avô materno', 'avoMaterno', 'text', 'form-control'), array('Avó materno', 'avoMaterna', 'text', 'form-control'), );

	if (isset($_GET['save'])) {

		$arrayCompleto = array_merge($formStructure, $formStructureDiag2);

		foreach ($arrayCompleto as $key => $value) {

			if ($value[1] != 'divider') {

				$postvalues[] = "'" . safeInsert($_POST[$value[1]], $mysqllink) . "'";

				$dbfields[] = $value[1];

			}

		}

		$dbvals = implode(",", $postvalues);

		$dbflds = implode(",", $dbfields);

		$crud = new Crud('sys_diagnostico');

		$crud -> replace('idPaciente, ' . $dbflds, $_SESSION['selectedUserID'] . ', ' . $dbvals);

		if ($crud -> execute() == 1) {

			die(mysql_error($mysqllink));

			header('Location: ?diagnostico&dAM=0');

		} else {

			header('Location: ?diagnostico&dAM=1');

		}

	} else {

		$html .= '<form action="?diagnostico&save" class="validate exitCheck formDiagnostico" id="form1" method="post">

			<fieldset>';

		$html .= openFullWidget('Diagnóstico e histórico pregresso do paciente', $actions = 1);

		$html .= '<div class="tabbable" style="margin-bottom: 18px;">

		

				<ul class="nav nav-tabs">

                	<li class="active"><a href="#tab1"  role="tab" data-toggle="tab">Diagnóstico</a></li>

					<li><a href="#tab2" role="tab" data-toggle="tab">História Pregressa</a></li>

                </ul>

		

		<div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">

		

			<div class="tab-pane active" id="tab1">';

		$editar = new Crud('sys_diagnostico');

		$editDataQR = $editar -> selectArrayConditions('*', 'idPaciente=' . $_SESSION['selectedUserID']);

		$editData = mysql_fetch_array($editDataQR);

		$html .= formCreateEdit($formStructure, $editData, $utf8 = 1);

		$html .= '<div class="clear h20"></div>

		

		</div>';

		$html .= '<div class="tab-pane" id="tab2">';

		$html .= formCreateEdit($formStructureDiag2, $editData, $utf8 = 1);

		$html .= '<div class="clear h20"></div>

				  </div>

		

			  

			  <div class="clear h10"></div>

			  

		</div>

		</div>';

		$html .= closeFullWidget();

		$html .= '</fieldset>

			</form>';

	}

	//PLANEJAMENTO

} elseif (isset($_GET['planejamento'])) {

	$id = $_SESSION['selectedUserID'];

	$crud = new Crud('sys_planejamento_cirurgia');

	$crud3 = new Crud('sys_planejamento_imagem');

	if (isset($_GET['add'])) {

		foreach ($formPlan as $key => $value) {

			if (in_array('data', $value)) {

				$toBdField = "`data`";

				$toBdVal = "'" . dataEncode($_POST['data']) . "'";

			} else {

				$toBdField = '`' . $value[1] . '`';

				$toBdVal = "'" . safeInsert($_POST[$value[1]], $mysqllink) . "'";

			}

			$postvalues[] = $toBdVal;

			$dbfields[] = $toBdField;

		}

		$postvalues[] = "'" . mysql_real_escape_string($id, $mysqllink) . "'";

		$dbfields[] = "`idPaciente`";

		$dbvals = implode(", ", $postvalues);

		$dbflds = implode(", ", $dbfields);

		$crud -> insert($dbflds, $dbvals);

		if ($crud -> execute() == 1) {

			die(mysql_error($mysqllink));

			header('Location: ?planejamento&dAM=0');

		} else {

			header('Location: ?planejamento&dAM=1');

		}

	}

	if (isset($_GET['editAct'])) {

		foreach ($formPlan as $key => $value) {

			if (in_array('data', $value)) {

				$postvalues[] = "`data` = '" . dataEncode($_POST['data']) . "'";

			} else {

				$postvalues[] = '`' . $value[1] . "` = '" . safeInsert($_POST[$value[1]], $mysqllink) . "'";

			}

		}

		$postvaluesln = implode(", ", $postvalues);

		$crud -> update($postvaluesln . ', `idPaciente`=' . $id, 'id=' . $_GET['editAct']);

		if ($crud -> execute() == 1) {

			header('Location: ?planejamento&dAM=0');

		} else {

			header('Location: ?planejamento&dAM=1');

		}

	}

	if (isset($_GET['delete'])) {

		$id = $_GET['delete'];

		$crud -> delete('id=' . $id);

		if ($crud -> execute() == 1) {

			header('Location: ?planejamento&dAM=0');

		} else {

			header('Location: ?planejamento&dAM=1');

		}

	}

	if (isset($_GET['saveHeadMarks'])) {

		$selecteds = implode(',', $_POST['headMark']);

		$postvalues = $id . " , '" . $selecteds . "'";

		$dbfields = "`idPaciente` , `selects`";

		$crud3 -> replace($dbflds, $postvalues);

		if ($crud3 -> execute() == 1) {

			header('Location: ?planejamento&dAM=0');

		} else {

			header('Location: ?planejamento&dAM=1');

		}

	}

	$html .= openFullWidget('Planejamento da cirurgia do paciente', $actions = 2);

	if (isset($_GET['edit'])) {$tabledisplay = 'style="display:block; width:300px;"';
		$formact = '?planejamento&editAct=' . $_GET['edit'];
	} else {$tabledisplay = 'style="display:none; width:0%;"';
		$formact = '?planejamento&add';
	}

	if (!isset($_GET['edit'])) {

		$html .= '

					<div class="clear h10"></div>

						<label>Paciente: ' . $_SESSION['selectedUser'] . '</label>

						<div class="clear"></div>

						<hr />

					';

	}

	$html .= '

			

				<div class="clear h10"></div>

				

					<table class="table table-striped table-bordered table-hover table-condensed table_cirurgia_head" style="width: 16% !important;">

						<tbody>

						';

	foreach ($formPlan as $key => $value) {

		$html .= '<tr><th>' . $value[0] . '</th></tr>';

	}

	$html .= '

						

						</tbody>

					</table>

					

					

					  <form action="' . $formact . '" id="form2" method="post" class="exitCheck">

					  <table class="table table-striped table-bordered table-hover table-condensed table_cirurgia_form" ' . $tabledisplay . '>

						<tbody>

						';

	foreach ($formPlan as $key => $value) {

		if (isset($_GET['edit'])) {

			$editDataQR2 = $crud -> selectArrayConditions('*', 'id=' . $_GET['edit']);

			$dataQR2 = mysql_fetch_array($editDataQR2);

		}

		$html .= '<tr>';

		if (in_array('data', $value)) {

			$html .= '

										 <td>

											  <div id="datetimepicker' . $key . '" class="input-append">

												<input data-format="dd/MM/yyyy" type="text" class="form-control dtpicker" id="' . $value[1] . '" name="' . $value[1] . '" 

												value="' . dataDecode($dataQR2[$value[1]]) . '" />

												<span class="add-on">

												  <i data-time-icon="fa fa-time" data-date-icon="fa fa-calendar" class="btn btn-info btn-xs"></i>

												</span>

											  </div>

											  

											  <script>

												  $(function() {

													$(\'#datetimepicker' . $key . '\').datetimepicker({

													  pickTime: false

													}).on("changeDate", function(e){$(\'#datetimepicker' . $key . '\').datetimepicker(\'hide\');});

												  });

											  </script>

										  </td>';

		} elseif (in_array('obs', $value)) {

			$html .= '<td><textarea name="' . $value[1] . '" id="' . $value[1] . '_txt">' . utf8_encode($dataQR2[$value[1]]) . '</textarea></td>';

		} elseif (array_key_exists('select', $value)) {

			$html .= '<td><select name="' . $value[1] . '" id="' . $value[1] . '">';

			foreach ($value['select'] as $value2) {

				$html .= '<option ';

				if ($value2 == $dataQR2[$value[1]]) { $html .= 'selected="selected"';
				}

				$html .= '>' . $value2 . '</option>';

			}

			$html .= '</select></td>';

		} else {

			$html .= '<td><input type="text" class="form-control" id="' . $value[1] . '" name="' . $value[1] . '" value="' . utf8_encode($dataQR2[$value[1]]) . '" /></td>';

		}

		$html .= '</tr>';

	}

	$html .= '

						<tr>

							<td>

								<button type="submit" class="btn btn-success">Salvar</button> <a href="#" class="btn btn-warning cancelarCirurgia">Cancelar</a>

							</td>

						</tr>

						</tbody>

					  </table>

					  </form>';

	if (!isset($_GET['edit'])) {

		$html .= '

					<div class="table_cirurgia_holder" style="width:84%;">

					  <table class="table table-striped table-bordered table-hover table-condensed table_cirurgia_scroll">

						<tbody>';

		foreach ($formPlan as $key => $value) {

			$html .= '<tr>';

			$editDataQR = $crud -> selectArrayConditions('id,' . $value[1], 'idPaciente=' . $id . ' ORDER BY data ASC');

			if ($editDataQR) {

				while ($dataQR = mysql_fetch_array($editDataQR)) {

					if (!empty($dataQR[$value[1]])) {

						if ($value[1] == 'data') {

							$html .= '<td class="dados">' . dataDecode($dataQR[$value[1]]) . '

												<a href="#" onclick="if(confirm(\'Deseja realmente excluir?\')) { window.location=\'?planejamento&delete=' . $dataQR['id'] . '\'; } event.returnValue = false; return false;" class="pull-right" style="color:red; margin:0 2px;" title="Deletar coluna"><i class="fa fa-trash-o"></i></a>

												<a href="?planejamento&edit=' . $dataQR['id'] . '" class="pull-right" style="color:orange; margin:0 2px;" title="Editar coluna"><i class="fa fa-edit"></i></a>

												</td>';

						} else {

							$html .= '<td class="dados">' . utf8_encode($dataQR[$value[1]]) . '</td>';

						}

					} else {

						$html .= '<td class="dados">-</td>';

					}

				}

			}

			$html .= '</tr>';

		}

		$html .= '

						</tbody>

					  </table>

					  <div class="clear h20"></div>

					</div>';

	}

	$headData = $crud3 -> selectArrayConditions('selects', 'idPaciente=' . $id);

	$headQR = mysql_fetch_array($headData);

	$selectedHead = array();

	$selectedHead = explode(',', $headQR['selects']);

	$html .= '

					<div class="clear h10"></div>

					<hr />

					<div class="clear h10"></div>



					<label>Planejamento visual</label>

				<div class="clear h10"></div>

				

				<form action="?planejamento&saveHeadMarks" id="form5" method="post" class="exitCheck">

					<div class="head_planejamento">';

	$numPontos = array(1 => 1, 2 => 3, 3 => 4, 4 => 5, 5 => 5, 6 => 2, 7 => 2);

	for ($marks = 1; $marks <= 7; $marks++) {

		$html .= '<div class="headMark' . $marks . '"><input type="checkbox" class="headMarkChbox" name="headMark[]" value="' . $marks . '"';

		$html .= (in_array($marks, $selectedHead)) ? ' checked="checked"' : '';

		$html .= ' /><span>' . $numPontos[$marks] . '</span></div>';

	}

	$html .= '

					</div>

						<div class="clear h30">	1 - Frente | 2 - Entradas | 3 - Meio | 4 - Coroa | 5 - Corredores</div></br>

					<div class="clear"></div>

					<button type="submit" class="btn btn-success saveheadMark" style="display:none;">Salvar</button>

				</form>

	

	

	' . closeFullWidget();

	//CIRURGIAS

} elseif (isset($_GET['cirurgia'])) {

	$submenuItem == 35;

	if (isset($_GET['add'])) {

		$postValues = "

			'" . $_SESSION['selectedUserID'] . "',

			'" . utf8_decode($_POST['title']) . "',

			'" . utf8_decode($_POST['data']) . "',

			'" . utf8_decode($_POST['hospital']) . "',

			'" . utf8_decode($_POST['s']) . "',

			'" . utf8_decode($_POST['d']) . "',

			'" . utf8_decode($_POST['t']) . "',

			'" . utf8_decode($_POST['umdoiscinco']) . "',

			'" . utf8_decode($_POST['faixa']) . "',

			'" . utf8_decode($_POST['haste']) . "',

			'" . utf8_decode($_POST['fio']) . "',

			'" . utf8_decode($_POST['local']) . "',

			'" . utf8_decode($_POST['focoprincipal']) . "',

			'" . utf8_decode($_POST['nota']) . "',

			'" . utf8_decode($_POST['retirada']) . "',

			'" . utf8_decode($_POST['pecas']) . "',

			'" . utf8_decode($_POST['a']) . "',

			'" . utf8_decode($_POST['d2']) . "',

			'" . utf8_decode($_POST['qntfios']) . "',

			'" . utf8_decode($_POST['elast']) . "',

			'" . utf8_decode($_POST['imp']) . "',

			'" . utf8_decode($_POST['equipe']) . "',

			'" . utf8_decode($_POST['anest']) . "',

			'" . utf8_decode($_POST['inicio']) . "',

			'" . utf8_decode($_POST['coloc']) . "',

			'" . utf8_decode($_POST['rqf']) . "',

			'" . utf8_decode($_POST['finco']) . "',

			'" . utf8_decode($_POST['termino']) . "',

			'" . utf8_decode($_POST['total']) . "',

			'" . utf8_decode($_POST['obs']) . "',

			'" . utf8_decode($_POST['campodesenho']) . "'

		";

		$insere = new Crud('sys_descricao_cirurgia');

		$insere -> insert("`idPaciente`, `title`,  `data`, `hospital`, `s`, `d`, `t`, `umdoiscinco`, `faixa`, `haste`, `fio`, `local`,`focoprincipal`,`nota`, `retirada`, `pecas`, `a`, `d2`, `qntfios`, `elast`, `imp`, `equipe`, `anest`, `inicio`, `coloc`, `rqf`, `finco`, `termino`, `total`, `obs` , `campodesenho`", $postValues);

		if ($insere -> execute() == 1) {

			header('Location: ?cirurgia&dAM=0');

		} else {

			header('Location: ?cirurgia&dAM=1');

		}

	}

	if (isset($_GET['editAct'])) {

		$postValues = "

			idPaciente='" . $_SESSION['selectedUserID'] . "',

			title='" . utf8_decode($_POST['title']) . "',

			data='" . utf8_decode($_POST['data']) . "',

			hospital='" . utf8_decode($_POST['hospital']) . "',

			s='" . utf8_decode($_POST['s']) . "',

			d='" . utf8_decode($_POST['d']) . "',

			t='" . utf8_decode($_POST['t']) . "',

			umdoiscinco='" . utf8_decode($_POST['umdoiscinco']) . "',

			faixa='" . utf8_decode($_POST['faixa']) . "',

			haste='" . utf8_decode($_POST['haste']) . "',

			fio='" . utf8_decode($_POST['fio']) . "',

			local='" . utf8_decode($_POST['local']) . "',

			focoprincipal='" . utf8_decode($_POST['focoprincipal']) . "',

			nota='" . utf8_decode($_POST['nota']) . "',

			retirada='" . utf8_decode($_POST['retirada']) . "',

			pecas='" . utf8_decode($_POST['pecas']) . "',

			a='" . utf8_decode($_POST['a']) . "',

			d2='" . utf8_decode($_POST['d2']) . "',

			qntfios='" . utf8_decode($_POST['qntfios']) . "',

			elast='" . utf8_decode($_POST['elast']) . "',

			imp='" . utf8_decode($_POST['imp']) . "',

			equipe='" . utf8_decode($_POST['equipe']) . "',

			anest='" . utf8_decode($_POST['anest']) . "',

			inicio='" . utf8_decode($_POST['inicio']) . "',

			coloc='" . utf8_decode($_POST['coloc']) . "',

			rqf='" . utf8_decode($_POST['rqf']) . "',

			finco='" . utf8_decode($_POST['finco']) . "',

			termino='" . utf8_decode($_POST['termino']) . "',

			total='" . utf8_decode($_POST['total']) . "',

			obs='" . utf8_decode($_POST['obs']) . "',

			campodesenho='" . utf8_decode($_POST['campodesenho']) . "'

		";

		$insere = new Crud('sys_descricao_cirurgia');

		$insere -> update($postValues, 'id=' . $_GET['editAct']);

		if ($insere -> execute() == 1) {

			header('Location: ?cirurgia&dAM=0');

		} else {

			header('Location: ?cirurgia&dAM=1');

		}

	}

	if (isset($_GET['delete'])) {

		$id = $_GET['delete'];

		$deletar = new Crud('sys_descricao_cirurgia');

		$deletar -> delete('id=' . $id);

		if ($deletar -> execute() == 1) {

			header('Location: ?cirurgia&dAM=0');

		} else {

			header('Location: ?cirurgia&dAM=1');

		}

	}

	if (isset($_GET['saveOldObs'])) {

		$updateOldObs = new Crud('sys_anotacoes_anteriores');

		$updateOldObs -> replace('`idPaciente`, `text`', '"' . $_SESSION['selectedUserID'] . '","' . utf8_decode($_POST['text']) . '"');

		if ($updateOldObs -> execute() == 1) {

			header('Location: ?cirurgia&dAM=0');

		} else {

			header('Location: ?cirurgia&dAM=1');

		}

	}

	if (isset($_GET['saveOutrasCirg'])) {

		$updateOldObs = new Crud('sys_outras_cirurgias');

		$updateOldObs -> replace('`idPaciente`, `text`', '"' . $_SESSION['selectedUserID'] . '","' . utf8_decode($_POST['text2']) . '"');

		if ($updateOldObs -> execute() == 1) {

			header('Location: ?cirurgia&dAM=0');

		} else {

			header('Location: ?cirurgia&dAM=1');

		}

	}

	$html .= openFullWidget('Histórico das cirurgias do paciente', $actions = 6);

	if (isset($_GET['edit'])) {$tabledisplay = 'style="display:block; width:300px;"';
		$formact = '?cirurgia&editAct=' . $_GET['edit'];
	} else {$tabledisplay = 'style="display:none; width:0%;"';
		$formact = '?cirurgia&add';
	}

	$html .= '<div class="tabbable" style="margin-bottom: 18px;">

                      <ul class="nav nav-tabs">

                        <li class="active"><a href="#tab1" data-toggle="tab">Histórico</a></li>

						<li><a href="#tab2" data-toggle="tab">Anotações anteriores</a></li>

                        <li><a href="#tab3" data-toggle="tab">Outras cirurgias</a></li>

                      </ul>

                      <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">

                        

						<div class="tab-pane active" id="tab1">

						

						<div class="clear h10"></div>

							<label>Paciente: ' . $_SESSION['selectedUser'] . '</label>

						<div class="clear h10"></div>

							

							<table class="table table-striped table-bordered table-hover table-condensed table_cirurgia_head">

								<tbody>';

	foreach ($formCirurgia as $key => $value) {

		$html .= '<tr><th>' . $value[0] . '</th></tr>';

	}

	$html .= '

								</tbody>

							</table>

							

							  <form action="' . $formact . '" id="form2" method="post" class="exitCheck validate">

							  <table class="table table-striped table-bordered table-hover table-condensed table_cirurgia_form" ' . $tabledisplay . '>

								<tbody>';

	$editar = new Crud('sys_descricao_cirurgia');

	foreach ($formCirurgia as $key => $value) {

		if (isset($_GET['edit'])) {

			$editDataQR2 = $editar -> selectArrayConditions('*', 'id=' . $_GET['edit']);

			$dataQR2 = mysql_fetch_array($editDataQR2);

		}

		$html .= '<tr>';

		if (in_array('data', $value)) {

			$html .= '

										 <td>

											  <div id="datetimepicker' . $key . '" class="input-append">

												<input data-format="dd/MM/yyyy" type="text" class="form-control dtpicker" id="' . $value[1] . '" name="' . $value[1] . '" 

												value="' . $dataQR2[$value[1]] . '" />

												<span class="add-on">

												  <i data-time-icon="fa fa-time" data-date-icon="fa fa-calendar" class="btn btn-info btn-xs"></i>

												</span>

											  </div>

											  

											  <script>

												  $(function() {

													$(\'#datetimepicker' . $key . '\').datetimepicker({

													  pickTime: false

													}).on("changeDate", function(e){$(\'#datetimepicker' . $key . '\').datetimepicker(\'hide\');});

												  });

											  </script>

										  </td>';

		} elseif (in_array('hora', $value)) {

			$horaexp = explode(":", $dataQR2[$value[1]]);

			$hora1 = $horaexp[0] ? $horaexp[0] : '00';

			$hora2 = $horaexp[1] ? $horaexp[1] : '00';

			$horafinal = $hora1 . ':' . $hora2;

			$html .= '

											 <td>

											  <div id="datetimepicker' . $key . '" class="input-append">

												<input data-format="hh:mm" type="text" class="form-control dtpicker" id="' . $value[1] . '" name="' . $value[1] . '" value="' . $horafinal . '" />

												<span class="add-on">

												  <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar" class="btn btn-info btn-xs"></i>

												</span>

											  </div>

											  

											  <script>

												  $(function() {

													$(\'#datetimepicker' . $key . '\').datetimepicker({

													  pickDate: false,

													  pick12HourFormat: true,

													  format: \'hh:mm\'

													});

												  });

											  </script>

										  	</td>';

		} elseif (in_array('obs', $value)) {

			$html .= '<td><textarea name="' . $value[1] . '" id="' . $value[1] . '_txt">' . utf8_encode($dataQR2[$value[1]]) . '</textarea></td>';

		} elseif (array_key_exists('select', $value)) {

			$html .= '<td><select name="' . $value[1] . '" id="' . $value[1] . '">';

			foreach ($value['select'] as $value2) {

				$html .= '<option';

				if ($value2 == $dataQR2[$value[1]]) {$html .= ' selected="selected"';
				}

				$html .= '>' . $value2 . '</option>';

			}

			$html .= '</select></td>';

		} else {

			$html .= '<td><input type="text" class="form-control" id="' . $value[1] . '" name="' . $value[1] . '" value="' . utf8_encode($dataQR2[$value[1]]) . '" /></td>';

		}

		$html .= '</tr>';

	}

	$html .= '

								<tr>

									<td>

								  		<button type="submit" class="btn btn-success">Salvar</button> <a href="#" class="btn btn-warning cancelarCirurgia">Cancelar</a>

									</td>

								</tr>

								</tbody>

							  </table>

							  </form>';

	if (!isset($_GET['edit'])) {

		$html .= '

							<div class="table_cirurgia_holder" style="width:87%;">

							  <table class="table table-striped table-bordered table-hover table-condensed table_cirurgia_scroll">

								<tbody>';

		foreach ($formCirurgia as $key => $value) {

			$html .= '<tr>';

			$editDataQR = $editar -> selectArrayConditions('id,' . $value[1], 'idPaciente=' . $_SESSION['selectedUserID'] . ' ORDER BY str_to_date(data, "%d/%m/%Y") ASC');

			while ($dataQR = mysql_fetch_array($editDataQR)) {

				if (!empty($dataQR[$value[1]])) {

					if ($value[1] == 'data') {

						$html .= '<td class="dadosdados">' . utf8_encode($dataQR[$value[1]]) . '

													<a href="#" onclick="if(confirm(\'Deseja realmente excluir?\')) { window.location=\'?cirurgia&delete=' . $dataQR['id'] . '\'; } event.returnValue = false; return false;" class="pull-right" style="color:red; margin:0 2px;" title="Deletar coluna"><i class="fa fa-trash-o"></i></a>

													<a href="?cirurgia&edit=' . $dataQR['id'] . '" class="pull-right" style="color:orange; margin:0 2px;" title="Editar coluna"><i class="fa fa-edit"></i></a>

													</td>';

					} else {

						$html .= '<td class="dados">' . utf8_encode($dataQR[$value[1]]) . '</td>';

					}

				} else {

					$html .= '<td class="dados">-</td>';

				}

			}

			$html .= '</tr>';

		}

		$html .= '

								</tbody>

							  </table>

							  <div class="clear h20"></div>

							</div>';

	}

	$html .= '

							  

							  <div class="clear h20"></div>

							  

							  

							  <div class="clear h20"></div>

							  

							  

                        </div>

						

                        <div class="tab-pane" id="tab2">';

	$getOldObs = new Crud('sys_descricao_cirurgia_antiga');

	$getOldObsQR = $getOldObs -> selectArrayConditions('text', 'idPaciente=' . $_SESSION['selectedUserID']);

	$oldTxt = '';

	if ($getOldObsQR) {

		while ($oldDataQR = mysql_fetch_array($getOldObsQR)) {

			$oldTxt .= utf8_encode(nl2br($oldDataQR['text'])) . '<br>';

		}

	}

	$html .= '<form action="?cirurgia&saveOldObs" class="validate exitCheck" id="form5" method="post">

              	<fieldset>';

	$html .= '<textarea class="cleditor" name="text" style="height:500px; width:90%;">' . $oldTxt . '</textarea>';

	$html .= '</fieldset>

		  

		  	  </form></div>

			  

              <div class="tab-pane" id="tab3">';

	$getOldObs2 = new Crud('sys_outras_cirurgias');

	$getOldObsQR2 = $getOldObs2 -> selectArrayConditions('text', 'idPaciente=' . $_SESSION['selectedUserID']);

	if ($getOldObsQR2) {

		$oldDataQR2 = mysql_fetch_array($getOldObsQR2);

		$oldTxt2 = utf8_encode(nl2br($oldDataQR2['text'])) . '<br>';

	}

	$html .= '<form action="?cirurgia&saveOutrasCirg" class="validate exitCheck" id="form6" method="post">

              	<fieldset>';

	$html .= '<textarea class="cleditor" name="text2" style="height:500px; width:90%;">' . $oldTxt2 . '</textarea>';

	$html .= '<button type="submit" class="btn btn-success">Salvar</button></fieldset>

		  

		  	  </form></div>

			  

			  

			  

			  

			  <div class="clear h10"></div>

						

			</div>

		  </div>

        </div>';

	$html .= closeFullWidget();

	//ANAMNESE

} elseif (isset($_GET['anamnese'])) {

	$submenuItem == 35;
	$id = $_SESSION['selectedUserID'];

	if (isset($_GET['add'])) {
			
		$insere = new Crud('sys_anamnese_values');

		foreach($_POST as $key => $value){
			$idField = explode("_", $key);
			$insere -> insert("`idField`, `idPaciente`, `value`", "'".$idField[1]."','".$_SESSION['selectedUserID']."','".$_POST[$key]."'");
			$insere -> execute();
		}

		header('Location: ?anamnese&dAM=1');
		
	}

	if (isset($_GET['editAct'])) {

		$postValues = "";
		$insere = new Crud('sys_descricao_cirurgia');
		$insere -> update($postValues, 'id=' . $_GET['editAct']);

		if ($insere -> execute() == 1) {
			header('Location: ?anamnese&dAM=0');
		} else {
			header('Location: ?anamnese&dAM=1');
		}
	}

	if (isset($_GET['delete'])) {

		$id = $_GET['delete'];
		$deletar = new Crud('sys_descricao_cirurgia');
		$deletar -> delete('id=' . $id);

		if ($deletar -> execute() == 1) {
			header('Location: ?anamnese&dAM=0');
		} else {
			header('Location: ?anamnese&dAM=1');
		}
	}

	if (isset($_GET['saveFields'])) {

		$updateFields = new Crud('sys_anamnese_fields');
		$updateFields -> delete('id>0');
		$updateFields -> execute();		
		
		$fieldsToSave = explode(',',$_POST['fields_edit']);
		
		foreach($fieldsToSave as $val){
			$updateFields -> insert("`field`", "'".utf8_decode($val)."'");
			$updateFields -> execute();
		}
	
		header('Location: ?anamnese&dAM=1');
		
	}
	
	
	$getFields = new Crud('sys_anamnese_fields');
	$getFieldsArr = $getFields -> selectArray('*');	

	if (isset($_GET['editFields'])) {
				
		$html .= openFullWidget('Editando colunas da anamnese', $actions = 5);		
			
		while ($fieldsArr = mysql_fetch_array($getFieldsArr)) {
			$fieldsArrLP[] = $fieldsArr['field'];
		}
		$fieldsLine = utf8_encode(implode(',', $fieldsArrLP));
		$html .= '
				<form action="?anamnese&saveFields" id="form2" method="post" class="exitCheck validate">
					<div class="form-group " style="width:100%;">
					<label for="imgedit_procedimento">Edite abaixo as colunas desejadas para a anamnese, separando por vírgula (,) cada campo</label>
					<input type="text" class="form-control tagSplit2" id="fields_edit" name="fields_edit" value="'.$fieldsLine.'">
					</div>
					
					<div class="clear h20"></div>
					
					<button type="submit" class="btn btn-success">Salvar</button>
					
					<div class="clear"></div>					
				</form>
					';

	} else {
		
		$html .= openFullWidget('Anamnese do paciente', $actions = 7);	

		if (isset($_GET['edit'])) {$tabledisplay = 'style="display:block; width:30% !important;"';
			$formact = '?anamnese&editAct=' . $_GET['edit'];
		} else {$tabledisplay = 'style="display:none; width:50% !important;"';
			$formact = '?anamnese&add';
		}

		$html .= '<div class="tabbable" style="margin-bottom: 18px;">
	
	                      <ul class="nav nav-tabs">
	
	                        <li class="active"><a href="#tab1" data-toggle="tab">Histórico</a></li>
	
							<li><a href="#tab2" data-toggle="tab">Observações complementares</a></li>
	
	
	                      </ul>
	
	                      <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
	
	                        
	
							<div class="tab-pane active" id="tab1">
	
							
	
							<div class="clear h10"></div>
	
								<label>Paciente: ' . $_SESSION['selectedUser'] . '</label>
	
							<div class="clear h10"></div>
	
								
	
								<table class="table table-striped table-bordered table-hover table-condensed table_cirurgia_head">
	
									<tbody>';


		$toAddField = '';
		while ($fieldsArr = mysql_fetch_array($getFieldsArr)) {
			$html .= '<tr><th>' . utf8_encode($fieldsArr['field']) . '</th></tr>';
			$toAddField .= '<tr><td><input type="text" class="form-control" id="field_' . $fieldsArr['id'] . '" name="field_' . $fieldsArr['id'] . '" /></td></tr>';
			$valuesToGet[]=$fieldsArr['id'];
		}

		$html .= '
	
									</tbody>
	
								</table>				
	
								  <form action="' . $formact . '" id="form2" method="post" class="exitCheck validate">
	
								  <table class="table table-striped table-bordered table-hover table-condensed table_cirurgia_form" ' . $tabledisplay . '>
	
									<tbody>';
									$html .= $toAddField . '
								    <tr><td>
									<div class="clear h10"></div>									
									<button type="submit" class="btn btn-success">Salvar</button>								
									<div class="clear h10"></div>								  
									</td></tr>	  
									</tbody>
	
								  </table>
	
								  </form>';

		$html .= '
								
								  <table class="table table-striped table-bordered table-hover table-condensed table_cirurgia_form" >
	
									<tbody>';
		
		$anValues = new Crud('sys_anamnese_values');
		foreach ($valuesToGet as $key => $value) {
			$html .= '<tr>';
			
			$getAnValues = $anValues -> selectArrayConditions('*', 'idPaciente=' . $id . ' AND idField='.$value);
			while ($valuesArr = mysql_fetch_array($getAnValues)) {
				$html .= '<td>'.utf8_encode($valuesArr['value']).'</td>';
			}
			
			$html .= '</tr>';			
		}
		$html .= '

									</tbody>
	
								  </table>
								
					  
								  <div class="clear h20"></div>
	
	                        </div>
	
	              <div class="tab-pane" id="tab2">';

		$getOldObs2 = new Crud('sys_outras_cirurgias');
		$getOldObsQR2 = $getOldObs2 -> selectArrayConditions('text', 'idPaciente=' . $_SESSION['selectedUserID']);

		if ($getOldObsQR2) {
			$oldDataQR2 = mysql_fetch_array($getOldObsQR2);
			$oldTxt2 = utf8_encode(nl2br($oldDataQR2['text'])) . '<br>';
		}

		$html .= '<form action="?cirurgia&saveOutrasCirg" class="validate exitCheck" id="form6" method="post">
	
	              	<fieldset>';

		$html .= '<textarea class="cleditor" name="text2" style="height:500px; width:90%;">' . $oldTxt2 . '</textarea>';

		$html .= '<button type="submit" class="btn btn-success">Salvar</button></fieldset>
		  
	
			  	  </form></div>
				  
				  <div class="clear h10"></div>
							
	
				</div>
	
			  </div>
	
	        </div>';

	}

	$html .= closeFullWidget();

	// RELATORIO

} elseif (isset($_GET['relatorio'])) {

	$menuItem == 6;

	$id = $_SESSION['selectedUserID'];

	if (isset($_GET['print'])) {

		//<!--Open Sans-->

		$html2 = '

		<head>

			<link href="style/fichacirurgica_pdf.css" rel="stylesheet">

		</head>';

		if (isset($_GET['pagina_1'])) {

			$editar = new Crud($database);

			$editDataQR = $editar -> selectArrayConditions('*', 'idPaciente=' . $id);

			$editData = mysql_fetch_array($editDataQR);

			include 'inc/fichaMedicaTmpl1.inc.php';

		}

		if (isset($_GET['pagina_2'])) {

			include 'inc/fichaMedicaTmpl2.inc.php';

		}

		if (isset($_GET['historico'])) {

			$editar = new Crud('sys_descricao_cirurgia');

			$html2 .= '

				<body>

				<div class="clear h10"></div>

							<label><big>Paciente: ' . $_SESSION['selectedUser'] . '</big></label>

						<div class="clear h10"></div><br>

							

							<table alighn="left"  style="float:left; width:12%" class="histofull">

								<tbody>';

			foreach ($formCirurgia as $key => $value) {

				$html2 .= '<tr><th>' . $value[0] . '</th></tr>';

			}

			$html2 .= '

								</tbody>

							</table>

	

							

							 <table alighn="left" class="histofull" style="margin-left:12%; margin-top:-60.75%; width:35%">

								<tbody>';

			foreach ($formCirurgia as $key => $value) {

				$html2 .= '<tr>';

				$editDataQR = $editar -> selectArrayConditions('id,' . $value[1], 'idPaciente=' . $_SESSION['selectedUserID'] . ' ORDER BY str_to_date(data, "%d/%m/%Y") ASC');

				while ($dataQR = mysql_fetch_array($editDataQR)) {

					if (!empty($dataQR[$value[1]])) {

						if ($value[1] == 'data') {

							$html2 .= '<td>' . utf8_encode($dataQR[$value[1]]) . '

													';

						} else {

							$html2 .= '<td>' . utf8_encode($dataQR[$value[1]]) . '</td>';

						}

					} else {

						$html2 .= '<td>-</td>';

					}

				}

				$html2 .= '</tr>';

			}

			$html2 .= '

								</tbody>

							  </table>

							  <div class="clear h20"></div>';

			$html2 .= '

							  

							  <div class="clear h20"></div>

							  

				

				</body>';

		}

		include ("MPDF56/mpdf.php");

		$mpdf = new mPDF('c', 'A4-L');

		$mpdf -> WriteHTML($html2);

		$mpdf -> Output();

		exit ;

	} else {

		$html .= openFullWidget('<i class="fa fa-list"></i> Impressão da ficha médica', false, false, false, false);

		$html .= '

			<h5>Ficha médica</h5>

			<div class="separador"></div> 

			<a class="btn btn-info" href="?relatorio&print&pagina_1" target="_blank"><i class="fa fa-print"></i> Imprimir Frente</a>

			<a class="btn btn-info" href="?relatorio&print&pagina_2" target="_blank"><i class="fa fa-print"></i> Imprimir Verso</a>

		';

		$html .= closeFullWidget();

	}

} else {

	// CRUD::EXIBIR

	$controlButtons = array('btn-success|?novo|fa-plus|Novo paciente');

	$html .= '<script src="js/datatables/js/jquery.dataTables.js"></script>';

	if (isset($_GET['singledisplay'])) { $singledisplay = 1;
	} else { $singledisplay = '';
	}

	$html .= openDinamicTable('Pacientes cadastrados', 'sys_pacientes', array('Foto' => '', 'Nome' => '', 'Telefone' => '', 'Celular' => 'mobileHide', 'Cidade' => 'mobileHide', 'Indicação' => 'mobileHide', 'Ações' => ''), array('idPaciente', 'nome', 'telefoneRes', 'mobileHide1' => 'celular', 'mobileHide2' => 'cidade', 'mobileHide3' => 'indicacao'), '?unselect&novo', array('excluir'), $utf8 = 1, $pg = $pgn, $singledisplay);

	// array('idPaciente','nome','telefoneRes','celular','cidade','indicacao'),'?unselect&novo',array('editar','excluir'),$utf8=1,$pg=$pgn,$singledisplay);

	$footerComplements = '

	<script>

		$(document).ready(function() {

				$(\'#datatable\').dataTable({"bPaginate": false});

		});

	

	</script>

';

}
//FIM DO CRUD::EXIBIR

echo $html;

$footerComplements = '

	<script>

		$(document).ready(function() {

				$(".validate").validationEngine();

		});

	</script>

';

include 'inc/footer.inc.php';
?>