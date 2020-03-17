<?php



// CONFIGURACOES DA PAGINA

$menuItem = 8;

$pagefile = 'contatos.php';





// INCLUDES

include 'inc/config.inc.php';



include 'inc/header.inc.php';



$formContato = array(

	 array('Nome','nome','text','validate[required] form-control doubleField'),

	 array('E-mail','email','text','form-control doubleField'),

	 array('Tel. res','fone', 'ddd'=>'dddFone', 'form-control '),

	 array('Celular','fone2', 'ddd'=>'dddCel', 'form-control'),

	 array('Tel. Com.','fone3', 'ddd'=>'dddCom', 'form-control'),

	 array('Outros','ocupacao','text','form-control'),

	 array('Categoria','categoria','text','form-control'),

	 array('CEP','cep','text','form-control'),

	 array('Cidade','cidade','text','form-control'),

	 array('Estado','estado','text','form-control'),

	 array('Bairro','bairro','text','form-control'),

	 array('Endereço','endereco','text','form-control doubleField'),

	 array('Observações','observacao','obs','form-control')

);





$crud = new Crud('sys_agenda_contatos');



if(isset($_GET['add'])){

	

	if(isset($_GET['act'])){

		foreach($formContato as $key => $value){

			

			if(array_key_exists('ddd', $value)){

				$dbfields[] = $value['ddd'];

				$postvalues[] = "'".mysql_real_escape_string(utf8_decode($_POST[$value['ddd']]), $mysqllink)."'";

				$dbfields[] = $value[1];

				$postvalues[] = "'".mysql_real_escape_string(utf8_decode($_POST[$value[1]]), $mysqllink)."'";

				

			} else {

				$dbfields[] = $value[1];

				$postvalues[] = "'".mysql_real_escape_string(utf8_decode($_POST[$value[1]]), $mysqllink)."'";

			}



		}

		

		$dbvals = implode(",", $postvalues);

		$dbflds = implode(",", $dbfields);

		

		$crud->insert($dbflds, $dbvals);

		if($crud->execute() == 1){

			header('Location: ?&dAM=0');

		} else {

			header('Location: ?&dAM=1');

		}

	}

	

	$html .= '<form action="?add&act" class="validate" id="form1" method="post">

			  <fieldset>';

	$html .= openFullWidget('<i class="fa fa-files"></i> Cadastrar novo contato', $actions=1);

	$html .= formCreateInsert($formContato);

	$html .= '</fieldset>

		  </form><div class="clear h10"></div>';

	$html .= closeFullWidget();

}





elseif(isset($_GET['edit'])){

	

	if(isset($_GET['act'])){

		foreach($formContato as $key => $value){

			

			if(array_key_exists('ddd', $value)){

				$toBdVal = $value['ddd'];

				$postvalues[] = '`'.$toBdVal."` = '".mysql_real_escape_string(utf8_decode($_POST[$toBdVal]), $mysqllink)."'";

				$postvalues[] = '`'.$value[1]."` = '".mysql_real_escape_string(utf8_decode($_POST[$value[1]]), $mysqllink)."'";

				

			} else {

				$toBdVal = $value[1];

				$postvalues[] = '`'.$toBdVal."` = '".mysql_real_escape_string(utf8_decode($_POST[$toBdVal]), $mysqllink)."'";

				

			}

			

			

		}

		

		$postvaluesln = implode(", ", $postvalues);

					

		$crud->update($postvaluesln,'agendaid='.$_GET['act']);

		if($crud->execute() == 1){

			header('Location: ?&dAM=0');

		} else {

			header('Location: ?&dAM=1');

		}

	}

	

	$dataQR = $crud->selectArrayConditions('*','agendaid='.$_GET['edit']);

	$data = mysql_fetch_array($dataQR);

	

	$html .= '<form action="?edit&act='.$_GET['edit'].'" class="validate" id="form1" method="post">

				<fieldset>';

	$html .= openFullWidget('<i class="fa fa-phone-square"></i> <span>Editar contato',$actions=1);

	$html .= formCreateEdit($formContato,$data,$utf8=1);

	$html .= '</fieldset>

		  </form><div class="clear h10"></div>';

	$html .= closeFullWidget();

}







elseif(isset($_GET['delete'])){



	$crud->delete('agendaid='.$_GET['delete']);

	if($crud->execute() == 1){

		header('Location: ?&dAM=0');

	} else {

		header('Location: ?&dAM=1');

	}

	

}







else {

	

	$dataQR = $crud->selectDistinct('LEFT(nome, 1) AS inicial','ORDER BY nome ASC');

	while($letra = mysql_fetch_object($dataQR)){

		$letras[] = $letra->inicial;

	}

	

	$html .= '<form action="?add" method="post">';

	$html .= openFullWidget('<i class="fa fa-phone-square"></i> <span>Agenda de contatos',$actions=3);

	$html .= '

		<div style="text-align:center;">';

			foreach($letras AS $pagletra){

				$html .= '<a class="btn" href="?page='.$pagletra.'">'.$pagletra.'</a>';

			}

	$html .= '</div>

	<div class="clear h10"></div>';

	$html .= '

		<table class="table table-bordered table-striped table-hover">

			<tr><td colspan="8"><i class="fa fa-lightbulb"></i> Clique no contato para exibir todas as informações.

			<div class="agendaGotoDate" style="float:right !important;">

				<input type="text" id="search" value="" placeholder="Buscar um contato..." style="width: 200px !important;">

				<button type="button" class="btn btn-info" style="padding:0px 0px -1px 0px; margin-left: 2px;" onclick="window.location=\'?search=\'+$(\'#search\').val();"><i class="fa fa-search"></i> Buscar</button>

			</div>

			</td></tr>

			<tr>

				<th width="20%">Nome</th>

				<th width="15%" class="mobileHide600">Email</th>

				<th width="10%">Categoria</th>

				<th width="10%" class="mobileHide600">Outros</th>

				<th width="10%">Telefone</th>

				<th width="10%" class="mobileHide600">Celular</th>

				<th width="10%" class="mobileHide600">Tel. Com.</th>

				<th width="10%">Ações</th>

			</tr>';

			

	

	if(isset($_GET['search'])){

		$sql = 'nome LIKE "%'.urldecode(utf8_decode($_GET['search'])).'%" ORDER BY nome ASC';

	}

	else {

		if(isset($_GET['page'])){

			$page = $_GET['page'];

		} else {

			$page = 'a';

		}

		$sql = 'LEFT(nome, 1) = "'.$page.'" ORDER BY nome ASC';

	}

	

	$html .= '<tr><th colspan="8" style="background: #444 !important; color: #FFF;">'.ucfirst($page).'</th></tr>';

			

	$contatos = $crud->selectArrayConditions('*',$sql);

	  while ($nomeQR = mysql_fetch_object($contatos))

	  {

		

		$html .= '<tr style="cursor:pointer;" id="'.$nomeQR->agendaid.'">';

		$hasdata = 1;

		$html .= '<td onclick="window.location=\'?edit='.$nomeQR->agendaid.'\';">' . utf8_encode($nomeQR->nome) . '</td>';

		$html .= '<td onclick="window.location=\'?edit='.$nomeQR->agendaid.'\';" class="mobileHide600">' . utf8_encode($nomeQR->email) . '</td>';

		$html .= '<td onclick="window.location=\'?edit='.$nomeQR->agendaid.'\';">' . utf8_encode($nomeQR->categoria) . '</td>';

		$html .= '<td onclick="window.location=\'?edit='.$nomeQR->agendaid.'\';" class="mobileHide600">' . utf8_encode($nomeQR->ocupacao) . '</td>';

		$html .= '<td onclick="window.location=\'?edit='.$nomeQR->agendaid.'\';">' .utf8_encode($nomeQR->dddFone) ." ".  utf8_encode($nomeQR->fone) . '</td>';

		$html .= '<td onclick="window.location=\'?edit='.$nomeQR->agendaid.'\';" class="mobileHide600">' .utf8_encode($nomeQR->dddCel) ." ". utf8_encode($nomeQR->fone2) . '</td>';

		$html .= '<td onclick="window.location=\'?edit='.$nomeQR->agendaid.'\';" class="mobileHide600">' .utf8_encode($nomeQR->dddCom) ." ". utf8_encode($nomeQR->fone3) . '</td>';

		$html .= '<td style="width:90px !important"><button type="button" class="btn btn-warning btn-xs" onclick="window.location=\'?edit='.$nomeQR->agendaid.'\';"><i class="fa fa-edit"></i></button>

					  <button type="button" class="btn btn-danger btn-xs" onclick="if(confirm(\'Deseja realmente excluir?\')) { window.location=\'?delete='.$nomeQR->agendaid.'\'; } event.returnValue = false; return false;"><i class="fa fa-times"></i></button>

				  </td>';

		$html .= '</tr>';

	  }

	

	

	if(!isset($hasdata)){$html .= '<tr><td colspan="7">Nenhum registro foi encontrado.</td></tr>';}

	$html .= '</table><div class="clear h10"></div>

		<div style="text-align:center;">';

			foreach($letras AS $pagletra){

				$html .= '<a class="btn" href="?page='.$pagletra.'">'.$pagletra.'</a>';

			}

	$html .= '</div>

	<div class="clear h10"></div>';

	$html .= closeFullWidget();

	$html .= '</form>';

	

	$html .= '	  

		  <div id="showContactData" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editOldModal" aria-hidden="true" style="display: none;">

				<div class="modal-dialog" style="width: 900px;">

				  <div class="modal-content">

					  <div class="modal-header">

						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

						<h4 class="modal-title">Detalhes do contato</h4>

					  </div>

						  <div class="modal-body">

						  		<table class="table table-bordered table-striped table-hover">';

									foreach($formContato as $value){

									$html .= '

										<tr>

											<th>'.$value[0].'</th>

											<td id="data_'.$value[1].'"></td>

										</tr>';

									}

									$html .= '

								</table>

						  </div>

						  <div class="modal-footer">

						  </div>

					</div>

				</div>

			</div>

		  

			';

}







echo $html;



include 'inc/footer.inc.php';



?>