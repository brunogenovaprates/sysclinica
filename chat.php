<?php

// CONFIGURACOES DA PAGINA
$menuItem = 4;
$pagefile = 'chat.php';
$database = 'sys_chat';


// INCLUDES
include 'inc/config.inc.php';
include 'inc/header.inc.php';


// FORM TREE




// RENDERS

$html ='';// renderMainTop('fa-edit','Gerenciar usuários do sistema','Home > Usuários');


if(isset($_GET['openChat'])){
	// CRUD::CRIAR
	
	$de = USER_ID;
	$para = $_GET['openChat'];
	
	$chatData = new Crud($database);
	$chatDataQR = $chatData->selectArrayConditions('*','((de='.$de.' AND para='.$para.') OR (de='.$para.' AND para='.$de.')) ORDER BY date ASC');
	
	// EXECUTAR
	if(isset($_GET['execute'])){
		
		$editar = new Crud('sys_usuarios');
		$editDataQR = $editar->selectArrayConditions('*','id='.$para);
		$editData = mysql_fetch_array($editDataQR);
		
		$postValues = "'".$de."','".$para."','".utf8_encode(USER_NAME)."','".$editData['nome']."','".utf8_encode($_POST['text'])."','".date("Y-m-d H:i:s")."',
					   '".$de."'";
		$insere = new Crud($database);
		$insere->insert("`de`, `para`, `de_nome`, `para_nome`, `text`, `date`, `view`", $postValues);
		if($insere->execute() == 1){
			header('Location: ?openChat='.$para);
		} else {
			header('Location: ?openChat='.$para);
		}
	}
	
	// EXIBIR INPUT

	$html .= '<form action="?openChat='.$para.'&execute" class="validate" id="form1" method="post">
              	<fieldset>';
	$html .= openFullWidget('Chat');
	$html .= '
              <ul class="chats" style="overflow-y:auto;">';
				  while($chatDataData = mysql_fetch_array($chatDataQR)){
					  
					  $dataHora = explode(" ", $chatDataData['date']);
					  
					  if($chatDataData['de'] == $de){$by='me'; $pull='left'; $person='Eu';} 
					  else {$by='other'; $pull='right'; $person=utf8_decode($chatDataData['de_nome']);}
						  
						  $html .= '
							  <li class="by-'.$by.'">
								<div class="avatar pull-'.$pull.'">
								  <img src="img/user.jpg" alt=""/>
								</div>
		
								<div class="chat-content">
								  <div class="chat-meta">'.$person.' 
								  <span class="pull-right">'.dataDecode($dataHora[0]).' às '.horaDecode($dataHora[1]).'</span></div>
								  '.utf8_decode($chatDataData['text']).'
								  <div class="clearfix"></div>
								</div>
							  </li> 
						  ';
				  }
    $html .= '<div id="newMsgs"></div></ul>';
	
	$chatForm = '
	<form class="form-inline" action="?openChat&execute" method="post">
	  <div class="form-group" style="width:85%;">
		  <input name="text" type="text" class="form-control" id="chatText" placeholder="Digite uma mensagem para enviar...">
	  </div>
	  	  <input type="hidden" id="chat_de" value="'.$de.'" />
	  	  <input type="hidden" id="chat_para" value="'.$para.'" />
		  <button type="submit" class="btn btn-default pull-right">Enviar</button>
	</form>
	';
	
	$html .= closeFullWidget($widgetFootContent=$chatForm);
	
	?>
    
    <script>
   	setInterval(function(){
		$.post('chatQuery.php',
				{
				  action : 'update',
				  de : $("#chat_de").val(),
				  para : $("#chat_para").val()
				}, function(retorno){
					if(retorno){
						$("#newMsgs").append(retorno);
						$('.chats').scrollTop($('.chats')[0].scrollHeight);	
					}
				});	
		
	}, 5000);
	$(document).ready(function(){
		 	var altura_tela = $(window).height();
			$(".chats").height(altura_tela-200);
			$( window ).resize(function() {
				 var altura_tela = $(window).height();
		   		 $(".chats").height(altura_tela-200);
		 	});
 			$('.chats').scrollTop($('.chats')[0].scrollHeight);
	});
	</script>
	
<?php
} elseif(isset($_GET['editar'])){
// CRUD::EDITAR

	$id = $_GET['editar'];
	$editar = new Crud($database);
	$editDataQR = $editar->selectArrayConditions('*','id='.$id);
	$editData = mysql_fetch_array($editDataQR);

	// EXECUTAR
	if(isset($_GET['execute'])){
		$dataFormat = dataEncode($_POST['data']);
		$postValues = "`nome`='".$_POST['nome']."',`login`='".$_POST['login']."',`senha`='".$_POST['senha']."',`email`='".$_POST['email']."',`telefone`='".$_POST['telefone']."',`acesso`='".$_POST['acesso']."',`status`='".$_POST['status']."',`data`='".$dataFormat."'";
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
	'btn-success|?novo|fa-plus|Novo'
);
//$html .= controlButtons($controlButtons);


$html .= openChatUsers('Usuários disponíveis no chat','sys_usuarios',array('ID','Nome','Ações'),
						  array('id','nome'));





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