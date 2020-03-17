<?php

ini_set('max_execution_time', '1800');
ini_set('session.gc_maxlifetime', '3600');


//BANCO DE DADOS
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'liviazag_sysClin');
define('DB_PASSWORD', 'J8vp4dx@');
define('DB_DATABASE', 'liviazag_sysClinica');


//EXECUCAO RUNTIME SCRIPT
mysql_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
mysql_select_db(DB_DATABASE);

$s = $_GET['s'];
$f = $_GET['f'];


$lp2 = mysql_query("SELECT * FROM `sys_imagem` GROUP BY `linkPaciente`");
while($lp2Data = mysql_fetch_array($lp2)){
	
	$lp3 = mysql_query("SELECT * FROM `sys_imagem` WHERE `linkPaciente` = '".$lp2Data['linkPaciente']."'");
	if($lp3){
		while($lp3Data = mysql_fetch_array($lp3)){
			$txt[] = $lp3Data['caracteristica'].': '.$lp3Data['valor'];
		}
		$imp_txt = implode(", ", $txt);
		mysql_query("INSERT INTO `sys_old_imgs`(`idPaciente`, `nome`, `linkPaciente`, `txt`, `dataProcedimento`, `dataImagem`) VALUES (".$lp2Data['idPaciente'].",'".$lp2Data['nome']."','".$lp2Data['linkPaciente']."','".$imp_txt."','".$lp2Data['dataProcedimento']."','".$lp2Data['dataImagem']."')");
		unset($lp3);
		unset($lp3Data);
		unset($txt);
	}
}

?>