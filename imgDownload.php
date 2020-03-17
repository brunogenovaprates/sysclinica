<?php

include 'inc/config.inc.php';

$zip = new ZipArchive();
 
if( $zip->open( 'zips/imgDownload.zip' , ZipArchive::CREATE  )  === true){
     
    foreach($_POST['imgDownload'] as $key => $value){
		$path = explode("/",  $value);
		if(sizeof($path) > 3){
			$path = 'js/AJAXupload/files/'.end($path);
		} else {
			$path = $value;
		}
		
		$extImg = explode('.', $path);
		$extImg = end($extImg);
		$nomeImg = filenameFilter($_POST['nomePaciente']);
		$nomeImg = $nomeImg.'_'.$key;
		$nomeImg = $nomeImg.'.'.$extImg;
		$zip->addFile( $path , $nomeImg );
	}
	     
     
    $zip->close();
 
    header('Content-type: application/zip');
    header('Content-disposition: attachment; filename="imagens_selecionadas.zip"');
    readfile('zips/imgDownload.zip');
 
    unlink('zips/imgDownload.zip');
}

?>