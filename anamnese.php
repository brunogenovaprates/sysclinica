<?php

// CONFIGURACOES DA PAGINA
$menuItem = 4;
$pagefile = 'anamnese.php';
$database = 'sys_pacientes';


// INCLUDES
include 'inc/config.inc.php';
include 'inc/functions.inc.php';
include 'inc/header.inc.php';

// FORM TREE

	$lp1 = new Crud('sys_convenio');
	$lp1qr = $lp1->selectArray('*');
	while($lp1Data = mysql_fetch_array($lp1qr)){
		$loopConvenio[] = array($lp1Data['idconvenio'],utf8_encode($lp1Data['nome']));
	}


$formStructure = array(
	 array('Nome completo','nome','text','validate[required] form-control'),
	 array('RG','rg','text','validate[required] form-control'),
	 array('CPF','cpf','text','validate[required] form-control'),
	 array('Data de nascimento','dataNascimento','data','form-control'),
	 array('Sexo','sexo','select' => array(array('M','Masculino'), array('F','Feminino')),'validate[required] form-control'),
	 array('Estado civil','estadoCivil', 'select' => array(array('S','Solteiro(a)'), array('C','Casado(a)'), array('D','Divorciado(a)'), array('V','Viúvo(a)')), 'form-control'),
	 array('Endereço completo','endereco', 'text' ,'validate[required] form-control'),
	 array('Bairro','bairro','text','validate[required] form-control'),
	 array('Cidade','cidade','text','validate[required] form-control'),
	 array('Estado','estado','text','validate[required] form-control'),
	 array('CEP','cep','text','validate[required] form-control'),
	 array('Telefone residencial','telefoneRes', 'text','form-control'),
	 array('Telefone comercial','telefoneCom', 'text','form-control'),
	 array('Telefone celular','celular','text','validate[required] form-control'),
	 array('E-mail','email', 'text','validate[required] form-control'),
	 array('Profissão','ocupacao', 'text','validate[required] form-control'),
	 array('Data da primeira consulta','dataPrimeiraConsulta','data','form-control'),
	 array('Último retorno','ultimoRetorno','data','form-control'),
	 array('Indicação','indicacao','text','form-control'),
	 array('Convenio médico','convenio','select' => $loopConvenio,'validate[required] form-control')
);





// RENDERS



$html = ''; //renderMainTop('fa-medkit','Pacientes','Home / Pacientes');





if(isset($_GET['novo'])){
	// CRUD::CRIAR
	
	// EXECUTAR
	if(isset($_GET['execute'])){
		
		$postValues = "'".utf8_decode($_POST['nome'])."',
		'".utf8_decode($_POST['endereco'])."',
		'".utf8_decode($_POST['bairro'])."',
		'".utf8_decode($_POST['cidade'])."',
		'".$_POST['cep']."',
		'".$_POST['telefoneRes']."',
		'".$_POST['telefoneCom']."',
		'".$_POST['celular']."',
		'".$_POST['email']."',
		'".$_POST['rg']."',
		'".$_POST['cpf']."',
		'".$dtFrmt1."',
		'".$_POST['estadoCivil']."',
		'".$_POST['sexo']."',
		'".utf8_decode($_POST['indicacao'])."',
		'".utf8_decode($_POST['ocupacao'])."',
		'".$dtFrmt2."',
		'".$dtFrmt3."',
		'".utf8_decode($_POST['estado'])."',
		'".date("Y-m-d")."'";
		
		$insere = new Crud($database);
		$insere->insert("`nome`, `endereco`, `bairro`, `cidade`, `cep`, `telefoneRes`, `telefoneCom`, `celular`, `email`, `rg`, `cpf`, `dataNascimento`, `estadoCivil`, `sexo`, `indicacao`, `ocupacao`, `dataPrimeiraConsulta`, `ultimoRetorno`, `estado`, `dataCadastro`", $postValues);
		if($insere->execute() == 1){
			header('Location: '.$pagefile.'?dAM=0');
		} else {
			header('Location: '.$pagefile.'?dAM=1');
		}
	}
	
	// EXIBIR INPUT

	//$controlButtons = array(
	//	'btn-success|'.$pagefile.'|fa-reply|Voltar'
	//);
	//$html .= controlButtons($controlButtons);
	$html .= '<form action="?novo&execute" class="validate" id="form1" method="post">
              	<fieldset>';
	$html .= openFullWidget('Novo paciente',$actions=1);
	$html .= formCreateInsert($formStructure);
	$html .= '</fieldset>
		  </form>';
	$html .= closeFullWidget();

// FIM CRUD::CRIAR











// FIM CRUD::EXCLUIR















} elseif(isset($_GET['detalhes'])){
// DETALHES

	$id = $_GET['detalhes'];
	$editar = new Crud($database);
	$editDataQR = $editar->selectArrayConditions('*','idPaciente='.$id);
	$editData = mysql_fetch_array($editDataQR);

	
	














} 


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