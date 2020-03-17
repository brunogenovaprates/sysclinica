<?php // CONFIGURACOES DA PAGINA

$menuItem = 1;

$pagefile = 'agenda.php';

$database = 'sys_agenda';

// INCLUDES

include 'inc/config.inc.php';

include 'inc/header.inc.php';

if (!isset($_SESSION['login'])) {

	header('Location: login.php');

}

$fullStatus = array('M' => 'Marcado', 'R' => 'Remarcado', 'C' => 'Cancelado', 'F' => 'Faltou', 'N' => 'Confirmado', 'A' => 'Aguardando', 'E' => 'Em Atendimento', 'T' => 'Atendido');

//INICIO

$lp2 = new Crud('sys_tipo_atendimento');

$lp2qr = $lp2 -> selectArray('*');

while ($lp2Data = mysql_fetch_array($lp2qr)) {

	$loopTipos[] = array($lp2Data['nome'], utf8_encode($lp2Data['descricao']), $lp2Data['tempo']);

}

$lp3 = new Crud('sys_convenio');

$lp3qr = $lp3 -> selectArray('*');

while ($lp3Data = mysql_fetch_array($lp3qr)) {

	$loopConvenio[] = array(utf8_encode($lp3Data['nome']), utf8_encode($lp3Data['nome']));

}

if (isset($_GET['editar'])) {

	// CRUD::EDITAR

	$formMerge1 = array( array('Paciente', 'nomePaciente', 'text', 'validate[required] form-control', 88), array('Status', 'status', 'select' => array( array('M', 'Marcado'), array('R', 'Remarcado'), array('C', 'Cancelado'), array('F', 'Faltou'), array('N', 'Confirmado'), array('A', 'Aguardando'), array('E', 'Em Atendimento'), array('T', 'Atendido')), 'form-control'), array('Data', 'data', 'data', 'form-control', 25), array('Horário', 'horaConsulta', 'hora', 'form-control', 25), array('Tipo de atendimento', 'tipo', 'select' => $loopTipos, 'form-control'), array('Duração', 'duracao', 'text', 'form-control', 10), array('Convenio médico', 'convenioId', 'select' => $loopConvenio, 'form-control'), array('Observações adicionais', '.utf8_encode(observacao)', 'obs', 'form-control', 95), );

	$id = $_GET['editar'];

	$editar = new Crud($database);

	$editDataQR = $editar -> selectArrayConditions('*', 'idagenda=' . $id);

	$editData = mysql_fetch_array($editDataQR);

	// EXECUTAR

	if (isset($_GET['execute'])) {

		$data1 = dataEncode($_POST['data']);

		$hora1 = $_POST['horaConsulta'] . ':00';

		$postValues = "nomePaciente='" . utf8_encode($_POST['idPaciente']) . "',data='" . $data1 . "',tipo='" . $_POST['tipo'] . "',observacao='" . utf8_encode($_POST['observacao']) . "',status='" . $_POST['status'] . "',idPaciente='" . $lastID . "',horaConsulta='" . $hora1 . "',duracao='" . $_POST['duracao'] . "',convenioId='" . $_POST['convenioId'] . "'";

		$editar -> update($postValues, 'idagenda=' . $_GET['editar']);

		if ($editar -> execute() == 1) {

			header('Location: ' . $pagefile . '?dAM=0');

		} else {

			header('Location: ' . $pagefile . '?dAM=1');

		}

	}

	// EXIBIR INPUT

	$html .= '
<form action="?editar=' . $id . '&execute" class="validate" id="form1" method="post">

	<fieldset>
		';

	$html .= openFullWidget('Editar evento', $actions = 1);

	$html .= formCreateEdit($formMerge1, $editData);

	$html .= '
	</fieldset>

</form>';

	$html .= closeFullWidget();

	// FIM CRUD::EDITAR

} elseif (isset($_GET['excluir'])) {

	// CRUD::EXCLUIR

	$id = $_GET['excluir'];

	$deletar = new Crud($database);

	$deletar -> delete('idagenda=' . $id);

	if ($deletar -> execute() == 1) {

		header('Location: ' . $pagefile . '?dAM=0');

	} else {

		header('Location: ' . $pagefile . '?dAM=1');

	}

	// FIM CRUD::EXCLUIR

} elseif (isset($_GET['detalhes'])) {

	// DETALHES

	$id = $_GET['detalhes'];

	$editar = new Crud($database);

	$editDataQR = $editar -> selectArrayConditions('*', 'idagenda=' . $id);

	$editData = mysql_fetch_array($editDataQR);

	// EXIBIR INPUT

	$html .= openFullWidget('Exibindo detalhes do evento', false, $detalhes = $id);

	$html .= '
<form class="validate" id="form1" method="post">

	<fieldset>
		';

	$html .= formCreateEdit($formMerge1, $editData);

	$html .= '
	</fieldset>

</form>';

	$html .= closeFullWidget();

	// FIM DETALHES

	//Eventos de hoje

} elseif (isset($_GET['exibeAllEventos'])) {

	// DETALHES

	if (isset($_GET['data'])) {

		$hoje = dataEncode($_POST['gotodate']);

	} else {

		$hoje = date("Y-m-d");

	}

	$getEventosHJ = new Crud($database);

	$editDataQR = $getEventosHJ -> customQuery('SELECT sys_agenda.* , sys_pacientes.cadRapido as cadRapPac FROM sys_agenda LEFT JOIN sys_pacientes ON sys_pacientes.idPaciente = sys_agenda.idPaciente WHERE data="' . $hoje . '" AND block=0 ORDER BY horaConsulta ASC');

	$getDayObs = new Crud('sys_daynote');

	$getDayObsQR = $getDayObs -> selectArrayConditions('*', 'date="' . $hoje . '"');

	$dayObsData = mysql_fetch_array($getDayObsQR);

	// EXIBIR INPUT

	$html .= openFullWidget('Compromissos do dia ' . dataDecode($hoje), false, false, false, false);

	$html .= '

<div class="agendaGotoDate">

	<form action="?exibeAllEventos&data" method="post">

		<input type="text" name="gotodate" value="' . dataDecode($hoje) . '"/>

		<button type="submit" class="btn btn-success">
			<i class="fa fa-search"></i>
		</button>

	</form>

</div>

<button type="button" onclick="window.print();" class="btn btn-info btn-xs btnImprimeEventos">
	<i class="fa fa-print"></i> Imprimir
</button>

<div class="clear h20"></div>

<ul class="latest-news">
	';

	$eventos = 0;

	while ($editData = mysql_fetch_array($editDataQR)) {

		$hora5 = horaDecode($editData['horaConsulta']);

		$hora6 = strtotime("$hora5 + " . $editData['duracao'] . " minutes");

		$hora6 = date("H:i", $hora6);

		$cadRap = $editData['cadRapPac'] == 1 ? '(Paciente novo)' : '';

		$html .= '
	<li>

		' . $hora5 . ' até ' . $hora6 . ' - <b>' . $fullStatus[$editData['status']] . '</b> - <a href="pacientes.php?selecionar=' . $editData['idPaciente'] . '">' . utf8_encode($editData['nomePaciente']) . ' </a>  - ' . utf8_encode($editData['tipo']) . ' - ' . utf8_encode($editData['observacao']) . ' ' . $cadRap . '

	</li>';

		$eventos++;

	}

	if ($eventos == 0) {

		$html .= '
	<li>
		Não há eventos para esta data.
	</li>';

	}

	$html .= '
	<li>

		<div class="clear h10"></div>

		<h6><b>Anotações do dia</b></h6>

		<div class="clear h10"></div>

		<p>
			' . strip_tags(utf8_encode($dayObsData['text'])) . '
		</p>

	</li>

</ul> 	';

	$html .= closeFullWidget();

	// FIM DETALHES

} elseif (isset($_GET['busca'])) {

	// DETALHES

	$html .= openFullWidget('Buscar todos os agendamentos do paciente', false, false, false, false);

	if (isset($_GET['paciente'])) {

		$lp1 = new Crud('sys_agenda');

		$lp1qr = $lp1 -> selectArrayConditions('*', "nomePaciente LIKE '%" . utf8_decode(urldecode($_GET['paciente'])) . "%' ORDER BY data DESC");

		$html .= '

<table class="table table-bordered table-striped table-hover">

	<tr>

		<th width="5%">Status</th>

		<th width="25%">Paciente</th>

		<th width="10%">Data</th>

		<th width="10%">Hora</th>

		<th width="25%">Atendimento</th>

		<th width="25%">Data último retorno</th>

	</tr>';

		while ($lp1Data = mysql_fetch_array($lp1qr)) {

			$html .= '
	<tr>

		<td>' . $fullStatus[$lp1Data['status']] . '</td>

		<td><a href="pacientes.php?selecionar=' . $lp1Data['idPaciente'] . '">' . utf8_encode($lp1Data['nomePaciente']) . '</a></td>

		<td><a href="agenda.php?exibeData=' . dataDecode($lp1Data['data']) . '">' . dataDecode($lp1Data['data']) . '</a></td>

		<td>' . horaDecode($lp1Data['horaConsulta']) . '</td>

		<td>' . utf8_encode($lp1Data['tipo']) . '</td>

		<td>' . dataDecode($lp1Data['ultimoRetorno']) . '</td>

	</tr>';

		}

		$html .= '
</table>';

	} else {

		$html .= '

<label> Buscar: &nbsp;&nbsp;
	<input type="text" placeholder="Digite o nome do paciente " id="buscaagenda_autocomplete" class="ui-autocomplete-input" autocomplete="off" style="width:200px;">
</label>

';

	}

	$html .= closeFullWidget();

	// FIM DETALHES

} else {

	// CRUD::EXIBIR

	$controlButtons = array('btn-success|?novo|fa-plus|Novo evento');

	//$html .= controlButtons($controlButtons,$searchbox = 1);

	//AGENDA

	$html .= openFullWidget('Agenda', false, false, false, $exibeAllEventos = 1);

	$html .= '

<link rel="stylesheet" href="assets/css/stylesNote.css" />

<script src="js/phpjs_date.js"></script>

<script src="js/phpjs_strtotime.js"></script>

<!-- A custom fnt GOOGLE -->

<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Courgette" />

<div id="calendar" class="col-md-9" style="padding:0 !important;"></div>

<div class="col-md-3 portlets ui-sortable">

	<div class="widget white headless footless">

		<div class="widget-head">

			<div class="clearfix"></div>

		</div>

		<div class="widget-content">

			<div id="pad">

				<h4>Anotações</h4>
				<textarea name="daynote" id="daynote" ></textarea>
				


			</div>

		</div>

	</div>

</div>

<div class="clear"></div>

';

	$html .= closeFullWidget();

	$formStructure = array( array('', 'pacienteAvatar'), array('Paciente', 'idPaciente', 'selPacAgenda', 'validate[required] form-control', 88), array('Tel. Res', 'telefoneRes', 'ddd' => 'ddd_tel', 'form-control cadRapido', 22), array('Celular', 'celular', 'ddd' => 'ddd_cel', 'form-control cadRapido', 22), array('Tel. Com.', 'telefoneCom', 'ddd' => 'ddd_com', 'form-control cadRapido', 22), array('E-mail', 'email', 'text', 'form-control cadRapido', 60), array('Convenio médico', 'convenioId', 'select' => $loopConvenio, 'form-control'), array('Data do agendamento', 'data', 'data', 'form-control', 30), array('Último retorno', 'ultimoRet', 'data', 'form-control', 30), array('Horário', 'horaConsulta', 'hora', 'form-control', 30), array('Tipo de atendimento', 'tipo', 'select' => $loopTipos, 'form-control'), array('Duração', 'duracao', 'text', 'form-control', 8), array('Status', 'status', 'select' => array( array('M', 'Marcado'), array('R', 'Remarcado'), array('C', 'Cancelado'), array('F', 'Faltou'), array('N', 'Confirmado'), array('A', 'Aguardando'), array('E', 'Em Atendimento'), array('T', 'Atendido')), 'form-control'), array('Observações adicionais', 'observacao', 'obs', 'form-control', 95), );

	$html .= '
<input type="hidden" name="tipoAgenda" id="tipoAgenda" value="0" />
';

	//CRIAR periodo bloqueado

	$html .= '
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close limpaForm" data-dismiss="modal" aria-hidden="true">
					×
				</button>

				<h4 class="modal-title">Agendar paciente</h4>

			</div>

			<div class="modal-body">

				<form action="?addEvent&execute" class="validate2" id="novoEvento" method="post">

					<fieldset>
						';

	$html .= formCreateInsert($formStructure);

	$html .= '

						<input type="hidden" id="tipoCad" name="tipoCad">

						<input type="hidden" id="paciente_id" name="paciente_id">

						<input type="hidden" id="idagenda" name="idagenda">

						<input type="hidden" id="selectedUserID" value="' . $_SESSION['selectedUserID'] . '">

						<input type="hidden" id="selectedUserName" value="' . $_SESSION['selectedUser'] . '">

					</fieldset>

				</form>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true" id="salvarEvento" alt="0">
					Salvar
				</button>

			</div>

		</div>

	</div>

</div>

<div id="blockModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="blockModal" aria-hidden="true" style="display: none;">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close limpaForm" data-dismiss="modal" aria-hidden="true">
					×
				</button>

				<h4 class="modal-title">Criar período de indisponibilidade</h4>

			</div>

			<div class="modal-body">

				<form action="?addEvent&execute" id="novoBlocked" method="post">

					<fieldset>

						<label>Título do periodo</label></br>

						<input type="text" id="titleblock" placeholder="Inserir títuro campo"/>
						</br>

						</br>

						<label>Início do período</label>

						<div class="clear h10"></div>

						<div id="datetimepicker_8" class="input-append" style="float:left;">

							<input data-format="dd/MM/yyyy" type="text" id="blockDt1" class=" dtpicker" placeholder="00/00/0000"/>

							<span class="add-on"> <i data-date-icon="fa fa-table" class="btn btn-sm btn-info" style="margin: -1px 0 0 0;"></i> </span>

						</div>

						<div id="datetimepicker_10" class="input-append" style="float:left; margin-left:10px;">

							<input data-format="hh:mm" type="text" id="blockHr1" class=" dtpicker" placeholder="00:00"/>

							<span class="add-on"> <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar" class="btn btn-info fa fa-clock-o"></i> </span>

						</div>

						<div class="clear h20"></div>

						<label>Término do período</label>

						<div class="clear h10"></div>

						<div id="datetimepicker_9" class="input-append" style="float:left;">

							<input data-format="dd/MM/yyyy" type="text" id="blockDt2" class=" dtpicker" placeholder="00/00/0000"/>

							<span class="add-on"> <i data-date-icon="fa fa-table" class="btn btn-sm btn-info" style="margin: -1px 0 0 0;"></i> </span>

						</div>

						<div id="datetimepicker_11" class="input-append" style="float:left; margin-left:10px;">

							<input data-format="hh:mm" type="text" id="blockHr2" class=" dtpicker" placeholder="00:00"/>

							<span class="add-on"> <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar" class="btn btn-info fa fa-clock-o"></i> </span>

						</div>

						<script>
							$(function() {

							$(\'#datetimepicker_8\').datetimepicker({

							pickTime: false

							}).on("changeDate", function(e){$(\'#datetimepicker_8\').datetimepicker(\'hide\');});

							});

							$(function() {

							$(\'#datetimepicker_9\').datetimepicker({

							pickTime: false

							}).on("changeDate", function(e){$(\'#datetimepicker_9\').datetimepicker(\'hide\');});

							});

							$(function() {

							$(\'#datetimepicker_10\').datetimepicker({

							pickDate: false

							}).on("changeDate", function(e){$(\'#datetimepicker_10\').datetimepicker(\'hide\');});

							});

							$(function() {

							$(\'#datetimepicker_11\').datetimepicker({

							pickDate: false

							}).on("changeDate", function(e){$(\'#datetimepicker_11\').datetimepicker(\'hide\');});

							});
						</script>

					</fieldset>

				</form>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true" id="newBlockEvent">
					Criar
				</button>

				<button type="button" class="btn btn-default limpaForm" data-dismiss="modal" aria-hidden="true">
					Cancelar
				</button>

			</div>

		</div>

	</div>

</div>';

}

echo $html;

$footerComplements = '

<script>

$(document).ready(function() {

$(".validate2").validationEngine();

});

</script>

';

include 'inc/footer.inc.php';
?>