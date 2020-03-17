<?php



// CONFIGURACOES DA PAGINA

$menuItem = 5;

$submenuItem == 41;

$pagefile = 'usuarios.php';

$database = 'sys_usuarios';





// INCLUDES

include 'inc/config.inc.php';

include 'inc/header.inc.php';





// FORM TREE



$formStructure = array(

	 1 => array('Nome completo','nome','text','validate[required] form-control'),

	 2 => array('Login de acesso','login','text','validate[required] form-control'),

	 3 => array('Senha','senha','password','validate[required] form-control'),

	 4 => array('E-mail','email','text','validate[required,custom[email]] form-control'),

	 5 => array('Telefone','telefone','text','validate[required] form-control'),

	 6 => array('Nível de acesso','acesso', 'select' => array(array('1','Administrador'), array('2','Secretaria'), array('3','Médico'), array('4','Paciente')), 'form-control'),

	 7 => array('Status','status', 'select' => array(array('1','Ativo'), array('0','Inativo')), 'form-control'),

	 8 => array('Data da criação','data','text','validate[required] form-control','editOnly'),

	 9 => array('Tema menu','layout', 'select' => array(array('1','Layout 1'), array('0','Layout 2')), 'form-control')

);







// RENDERS



$html ='';// renderMainTop('fa-edit','Gerenciar usuários do sistema','Home > Usuários');





if(isset($_GET['novo'])){

	// CRUD::CRIAR

	

	// EXECUTAR

	if(isset($_GET['execute'])){

		$postValues = "'".$_POST['nome']."','".$_POST['login']."','".$_POST['senha']."','".$_POST['email']."','".$_POST['telefone']."','".$_POST['acesso']."','".$_POST['status']."','".date("Y-m-d").",'".$_POST['layout']."'";

		$insere = new Crud($database);

		$insere->insert("`nome`, `login`, `senha`, `email`, `telefone`, `acesso`, `status`, `data`, `layout`", $postValues);

		if($insere->execute() == 1){

			header('Location: '.$pagefile.'?dAM=0');

		} else {

			header('Location: '.$pagefile.'?dAM=1');

		}

	}

	

	// EXIBIR INPUT



	$html .= '<form action="?novo&execute" class="validate" id="form1" method="post">

              	<fieldset>';

	$html .= openFullWidget('Novo usuário',$actions=1);

	$html .= formCreateInsert($formStructure);

	$html .= '</fieldset>

		  </form>';

	$html .= closeFullWidget();



// FIM CRUD::CRIAR





























} elseif(isset($_GET['editar'])){

// CRUD::EDITAR



	$id = $_GET['editar'];

	$editar = new Crud($database);

	$editDataQR = $editar->selectArrayConditions('*','id='.$id);

	$editData = mysql_fetch_array($editDataQR);



	// EXECUTAR

	if(isset($_GET['execute'])){

		$dataFormat = dataEncode($_POST['data']);

		$postValues = "`nome`='".$_POST['nome']."',`login`='".$_POST['login']."',`senha`='".$_POST['senha']."',`email`='".$_POST['email']."',`telefone`='".$_POST['telefone']."',`acesso`='".$_POST['acesso']."',`status`='".$_POST['status']."',`data`='".$dataFormat."',`layout`='".$_POST['layout']."'";

		$editar->update($postValues,'id='.$_GET['editar']);

		if($editar->execute() == 1){

			header('Location: '.$pagefile.'?dAM=0');

		} else {

			header('Location: '.$pagefile.'?dAM=1');

		}

	}

	

	// EXIBIR INPUT

	

	

	$html .= '<form action="?editar='.$id.'&execute" class="validate" id="form1" method="post">

              	<fieldset>';

	$html .= openFullWidget('Editar usuário',$actions=1);

	$html .= formCreateEdit($formStructure,$editData);

	$html .= '</fieldset>

		  </form>';

	$html .= closeFullWidget();



// FIM CRUD::EDITAR































} elseif(isset($_GET['excluir'])){

// CRUD::EXCLUIR



	$id = $_GET['excluir'];

	$deletar = new Crud($database);

	$deletar->delete('id='.$id);

	if($deletar->execute() == 1){

		header('Location: '.$pagefile.'?dAM=0');

	} else {

		header('Location: '.$pagefile.'?dAM=1');

	}



// FIM CRUD::EXCLUIR































} elseif(isset($_GET['detalhes'])){

// DETALHES



	$id = $_GET['detalhes'];

	$editar = new Crud($database);

	$editDataQR = $editar->selectArrayConditions('*','id='.$id);

	$editData = mysql_fetch_array($editDataQR);



	

	// EXIBIR INPUT

	

	$html .= openFullWidget('Exibindo detalhes do usuário',$false,$detalhes=$id);

	$html .= '<form class="validate" id="form1" method="post">

              	<fieldset>';

	$html .= formCreateEdit($formStructure,$editData);

	$html .= '	</fieldset>

		 	  </form>';

	$html .= closeFullWidget();





// FIM DETALHES

































} else {

// CRUD::EXIBIR





$controlButtons = array(

	'btn-info|?novo|fa-plus|Novo'

);

//$html .= controlButtons($controlButtons);





$html .= openDinamicTable('Usuários cadastrados',$database,array('ID'=>'mobileHide','Nome'=>'','E-mail'=>'mobileHide','Telefone'=>'mobileHide','Acesso'=>'','Status'=>'','Ações'=>''),

						  array('mobileHide1'=>'id','nome','mobileHide2'=>'email','mobileHide3'=>'telefone','acesso','status'),'?novo',array('detalhes','editar','excluir'));











} //FIM DO CRUD::EXIBIR





echo $html;







$footerComplements = '

<script>

    $(document).ready(function() {

			

			$(\'#datatable\').dataTable({"sPaginationType": "full_numbers"});

		

  			$(".validate").validationEngine();

	

	});



</script>

';



include 'inc/footer.inc.php';



?>