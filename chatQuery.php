<?php
 
include 'inc/config.inc.php';
 
	$de = $_POST['de'];
	$para = $_POST['para'];
	
	$chatData = new Crud('sys_chat');
	$chatDataQR = $chatData->selectArrayConditions('*','((de='.$de.' AND para='.$para.') OR (de='.$para.' AND para='.$de.')) AND view = 0 ORDER BY date ASC');
	while($chatDataData = mysql_fetch_array($chatDataQR)){
	 
		$dataHora = explode(" ", $chatDataData['date']);

		if($chatDataData['de'] == $de){$by='me'; $pull='left'; $person='Eu';} 
		else {$by='other'; $pull='right'; $person=utf8_decode($chatDataData['de_nome']);}
		
		echo '
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
				
		$upChat = new Crud('sys_chat');
		$postValues = '`view`=1';
		$upChat->update($postValues,'id='.$chatDataData['id']);
		$upChat->execute();
	
	}
 
?>