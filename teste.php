<?php

include 'inc/config.inc.php';

$crud = new Crud('sys_imagemfull'); 

$pacientes = $crud->selectArrayConditions('*','`idPaciente` IS NULL');
while ($pacQR = mysql_fetch_object($pacientes))
{
  $quebranome = explode(' ', $pacQR->nome);
  $final = end($quebranome);
  
  //echo $quebranome[0].' '. $final;
  
  $crud2 = new Crud('sys_pacientes');
  $query2 = $crud2->selectArrayConditions('*',"`nome` LIKE '".$quebranome[0]." % ".$final."'");
  if($query2 && mysql_num_rows($query2)==1){
		  
		  $query2QR = mysql_fetch_object($query2);
		  
		  $query3 = $crud->update('idPaciente = "'.$query2QR->idPaciente.'"', 'id = '.$pacQR->id);
		  $crud->execute();
  }
  
}

?>