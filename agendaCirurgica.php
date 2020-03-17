<?php

// CONFIGURACOES DA PAGINA

$menuItem = 10;

$pagefile = 'agendaCirurgica.php';

$database = 'sys_agendaCirurgica';

// INCLUDES

include 'inc/config.inc.php';

include 'inc/header.inc.php';

if (!isset($_SESSION['login'])) {

	header('Location: login.php');

}

$formStructure = array( array('', 'pacienteAvatar'), array('Paciente', 'idPaciente', 'selPacAgenda', 'validate[required] form-control', 85), array('Data do agendamento', 'dataAgendamento', 'data', 'form-control', 25), array('Data da cirurgia', 'data', 'data', 'form-control', 25), array('Horário', 'horaConsulta', 'hora', 'form-control', 25), array('Duração', 'duracao', 'text', 'validate[required] form-control', 15), 'status' => array('Status', 'prioridade', 'select' => array( array('', '- Selecione -'), array(8, 'Realizado'), array(7, 'Antecipado'), array(6, 'Remarcado'), array(5, 'Reservado'), array(4, 'Urgente'), array(3, 'Média'), array(2, 'Baixa'), array(1, 'Agendado'), array(0, 'Cancelado')), 'form-control'), array('Motivo do cancelamento', 'formGroupComps' => 'hided cancelSubject', 'cancelSubject', 'text', 'form-control', 94), array('Procedimento', 'procedimento', 'text', 'form-control', 78), array('Observações adicionais', 'observacao', 'obs', 'form-control', 94), );

if (isset($_GET['exibeAllEventos'])) {

	// DETALHES

	if (isset($_GET['data'])) {

		$hoje = dataEncode($_POST['gotodate']);

	} else {

		$hoje = date("Y-m-d");

	}

	$editar = new Crud($database);

	$editDataQR = $editar -> selectArrayConditions('*', 'data="' . $hoje . '"');

	// EXIBIR INPUT

	$html .= openFullWidget('Compromissos do dia ' . dataDecode($hoje), false, false, false, false);

	$html .= '

				  <div class="agendaGotoDate">

				  	<form action="?exibeAllEventos&data" method="post">

				  	<input type="text" name="gotodate" value="' . dataDecode($hoje) . '"/>

				  	<button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>

				  	</form>

				  </div>

				  

				  <button type="button" onclick="window.print();" class="btn btn-info btn-xs btnImprimeEventos"><i class="fa fa-print"></i> Imprimir</button>

				   

				  <div class="clear h20"></div>                 

                    <ul class="latest-news">';

	$eventos = 0;

	while ($editData = mysql_fetch_array($editDataQR)) {

		$hora5 = horaDecode($editData['horaConsulta']);

		$hora6 = strtotime("$hora5 + " . $editData['duracao'] . " minutes");

		$hora6 = date("H:i", $hora6);

		$html .= '<li>

					  				<h6><a href="?detalhes=' . $editData['idagenda'] . '">' . utf8_encode($editData['nomePaciente']) . ' </a> - 

					  				<span>' . dataDecode($hoje) . ' &nbsp; das ' . $hora5 . ' as ' . $hora6 . '</span></h6>

					  				<p><b>' . utf8_encode($editData['tipo']) . '</b> - ' . utf8_encode($editData['observacao']) . '</p>

					  			  </li>';

		$eventos++;

	}

	if ($eventos == 0) {

		$html .= '<li>Não há eventos para esta data.</li>';

	}

	$html .= '      </ul> 	';

	$html .= closeFullWidget();

	// FIM DETALHES

} elseif (isset($_GET['busca'])) {

	// DETALHES

	$html .= openFullWidget('Buscar os agendamentos e status na agenda cirurgica', false, false, false, false);

	if (isset($_GET['pid'])) {

		$lp1 = new Crud('sys_agendaCirurgica');

		$lp1qr = $lp1 -> selectArrayConditions('*', "idPaciente = " . $_GET['pid'] . " ORDER BY data DESC");

		if (mysql_num_rows($lp1qr) > 0) {

			$puxaFoto = new Crud('sys_multi_uploads');

			$getFoto = $puxaFoto -> selectArrayConditions("file", 'user_id="' . $_GET['pid'] . '" AND type_id=2 LIMIT 1');

			if (mysql_num_rows($getFoto) > 0) {

				$fotoQR = mysql_fetch_array($getFoto);

				$foto = 'js/AJAXupload/files/thumbnail/' . $fotoQR['file'];

			} elseif (file_exists("avatar/" . $queryLP[$fields[0]] . ".jpg")) {

				$foto = 'avatar/' . $queryLP[$fields[0]] . '.jpg';

			} else {

				$foto = 'img/user.png';

			}

			$priorityARR = array(0 => 'Cancelado', 1 => 'Agendado', 2 => 'Baixa', 3 => 'Média', 4 => 'Urgente', 5 => 'Reservado', 6 => 'Remarcado');

			while ($lp1Data = mysql_fetch_array($lp1qr)) {

				$tempoEspera = ceil((((strtotime(date("Y-m-d")) - strtotime($lp1Data['dataAgendamento'])) / 60) / 60) / 24);

				$temp_html .= '<tr>

								  <td>' . utf8_encode($lp1Data['procedimento']) . '</td>

								  <td>' . $priorityARR[$lp1Data['prioridade']] . '</td>

								  <td>' . utf8_encode($lp1Data['duracao']) . ' minutos</td>

								  <td>' . dataDecode($lp1Data['dataAgendamento']) . '</td>

								  <td>' . dataDecode($lp1Data['data']) . '</td>

								  <td>' . $tempoEspera . ' dias</td>

							  </tr>';

				$nomePaciente = utf8_encode($lp1Data['nomePaciente']);

			}

			$html .= '

			<div class="pull-left" style="margin:0 10px 0 0">

				<img style="height:50px; width:auto;" src="' . $foto . '" />

			</div>

			<div class="pull-left" style="margin:0 10px 0 0">

				<h3>' . $nomePaciente . '</h3>

			</div>

			

			<div class="clear h20"></div>';

			$html .= '

				<table class="table table-bordered table-striped table-hover">

					<tr>

						<th width="30%">Procedimento</th>

						<th width="10%">Status</th>

						<th width="15%">Duração</th>

						<th width="15%">Data agendamento</th>

						<th width="15%">Data da cirurgia</th>

						<th width="15%">Tempo de espera</th>

					</tr>';

			$html .= $temp_html;

			$html .= '</table>';

		} else {

			$html .= 'Não há eventos para este paciente.';

		}

	} else {

		$html .= '

			<label> Nome: &nbsp;&nbsp;

				<input type="text" placeholder="Digite o nome..." id="buscaagendaCirurgica_autocomplete" class="ui-autocomplete-input" autocomplete="off" style="width:280px;">

			</label>

			<div class="clear h30">

			</div>

			<label> Status: &nbsp;&nbsp;

				<select  id="statusCirurgica" class="form-control">';

		$statusArray = $formStructure['status'];

		foreach ($statusArray['select'] as $key => $value) {

			$html .= '

						<option value="' . $value[0] . '"> ' . $value[1] . '</option>';

		}

		$html .= '</select>

				

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

	$html .= openFullWidget('Agenda Cirúrgica', false, false, false, $exibeAllEventos = 1);

	$html .= '



		<link rel="stylesheet" href="assets/css/stylesNote.css" />

		

		<script src="js/phpjs_date.js"></script>

		<script src="js/phpjs_strtotime.js"></script>

        

        <!-- A custom fnt GOOGLE -->

        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Courgette" />



		<div id="calendarCirurgica" class="col-md-9" style="padding:0 !important;"></div>

				  	

			<div class="col-md-3 portlets ui-sortable">

              <div class="widget white headless footless">

                <div class="widget-head">

                 

                  <div class="clearfix"></div>

                </div>              



				<div class="widget-content">

                  <div id="pad">

				  <h4>Anotações</h4>

                  	<textarea name="daynoteCirurgica" id="daynoteCirurgica" ></textarea>

                  </div>

                </div>

              </div>

            </div>		  	

		  	

		  <div class="clear"></div>

		 ';

	$html .= closeFullWidget();

	$html .= '<input type="hidden" name="tipoAgenda" id="tipoAgenda" value="1" />';

	//CRIAR periodo bloqueado

	$html .= '<div id="myModalCirurgica" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

			<div class="modal-dialog">

			  <div class="modal-content">

                  <div class="modal-header">

                    <button type="button" class="close limpaForm" data-dismiss="modal" aria-hidden="true">×</button>

                    <h4 class="modal-title">Agendar paciente</h4>

                  </div>

					  <div class="modal-body">

				  		<form action="?addEvent&execute" class="validate2" id="novoEventoCirurgica" method="post">

							<fieldset>';

	$html .= formCreateInsert($formStructure);

	$html .= '

							<input type="hidden" id="tipoCad" name="tipoCad">

							<input type="hidden" id="paciente_id" name="paciente_id">

							<input type="hidden" id="idagenda" name="idagenda">

							</fieldset>

						</form> 				 

					  </div>

					  <div class="modal-footer">

							<button type="button" class="btn btn-success" data-dismiss="modalCirurgica" aria-hidden="true" id="salvarEventoCirurgica" alt="0">Salvar</button>

					  </div>

                </div>

			</div>

		</div>';

	$html .= '<div id="blockModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="blockModal" aria-hidden="true" style="display: none;">

			<div class="modal-dialog">

			  <div class="modal-content">

                  <div class="modal-header">

                    <button type="button" class="close limpaForm" data-dismiss="modal" aria-hidden="true">×</button>

                    <h4 class="modal-title">Criar período de indisponibilidade</h4>

                  </div>

					  <div class="modal-body">

				  		<form action="?addEvent&execute" id="novoBlockedCirurgica" method="post">

							<fieldset>

								

							  	<label>Início do período</label>

								<div class="clear h10"></div>

								<div id="datetimepicker_8" class="input-append" style="float:left;">

							  	<input data-format="dd/MM/yyyy" type="text" id="blockDt1" class=" dtpicker" placeholder="Inserir data"/>

									<span class="add-on">

									  <i data-date-icon="fa fa-table" class="btn btn-sm btn-info" style="margin: -1px 0 0 0;"></i>

									</span>	

								</div>

								<div id="datetimepicker_10" class="input-append" style="float:left; margin-left:10px;">

							  	<input data-format="hh:mm" type="text" id="blockHr1" class=" dtpicker" placeholder="Inserir hora"/>

									<span class="add-on">

									  <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar" class="btn btn-info fa fa-clock-o"></i>

									</span>	

								</div>

														  	

							  	<div class="clear h20"></div>

								

								<label>Término do período</label>

								<div class="clear h10"></div>

							  	<div id="datetimepicker_9" class="input-append" style="float:left;">

							  	<input data-format="dd/MM/yyyy" type="text" id="blockDt2" class=" dtpicker" placeholder="Inserir data"/>

									<span class="add-on">

									  <i data-date-icon="fa fa-table" class="btn btn-sm btn-info" style="margin: -1px 0 0 0;"></i>

									</span>	

								</div>						  	

								<div id="datetimepicker_11" class="input-append" style="float:left; margin-left:10px;">

							  	<input data-format="hh:mm" type="text" id="blockHr2" class=" dtpicker" placeholder="Inserir hora"/>

									<span class="add-on">

									  <i data-time-icon="fa fa-clock-o" data-date-icon="fa fa-calendar" class="btn btn-info fa fa-clock-o"></i>

									</span>	

								</div>

							  

							  

							  <script>

								  $(function() {

									$(\'#datetimepicker_8\').datetimepicker({

									  pickTime: false

									});

								  });

								  $(function() {

									$(\'#datetimepicker_9\').datetimepicker({

									  pickTime: false

									});

								  });

								  $(function() {

									$(\'#datetimepicker_10\').datetimepicker({

									  pickDate: false

									});

								  });

								  $(function() {

									$(\'#datetimepicker_11\').datetimepicker({

									  pickDate: false

									});

								  });

							  </script>							  

								

								

								

							</fieldset>

						</form> 				 

					  </div>

					  <div class="modal-footer">

						<button type="button" class="btn btn-success" data-dismiss="modal" aria-hidden="true" id="newBlockEvent">Criar</button>

						<button type="button" class="btn btn-default limpaForm" data-dismiss="modal" aria-hidden="true">Cancelar</button>

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