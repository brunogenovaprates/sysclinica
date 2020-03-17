<?php
include 'inc/config.inc.php';

if(isset($_GET['upload'])){
	
	//INFO IMAGEM
	$file 		= $_FILES['files'];
	$numFile	= count(array_filter($file['name']));
	
	//MENSAGENS
	$result		= array();
	$errorMsg	= array(
		1 => 'tamanho maior que o limite do servidor.',
		2 => 'tamanho maior que MAX_FILE_SIZE',
		3 => 'upload feito parcialmente',
		4 => 'erro no upload'
	);
			
	//CRIA PASTA DO PACIENTE SE NAO EXISTIR
		if (!file_exists("fotos/" . $_POST['pastaPaciente'] . "/")) {
			mkdir("fotos/" . $_POST['pastaPaciente'], 0777);
		} else {
			$result['msg'][] = 'A pasta de fotos do paciente já exite mas não há registros no banco de dados.';
		}
	
	//PASTA
	$folder		= "fotos/" . $_POST['pastaPaciente'];
	
	//REQUISITOS
	$permite 	= array('image/jpeg', 'image/png', 'image/gif', 'image/jpg');
	$maxSize	= 1024 * 1024 * 10;
	
	//NOVO NOME PARA A FOTO SEGUINDO O PADRAO DO SYS ANTIGO
	$nomeFoto = filenameFilter($_POST['nomePaciente']).'_('.$_POST['pastaPaciente'].')_'.date('Ymd');
	
	$imgBD = new Crud('sys_imagemfull');
	
	for($i = 0; $i < $numFile; $i++){
		$name 	= $file['name'][$i];
		$type	= $file['type'][$i];
		$size	= $file['size'][$i];
		$error	= $file['error'][$i];
		$tmp	= $file['tmp_name'][$i];
		
		$extensao = @end(explode('.', $name));
		$randonName = rand(111111111,999999999);
		$novoNome = $nomeFoto.$randonName.".".$extensao;
		$nomeBD = date('Ymd').$randonName;
		
		if($error != 0)
			$result['erro'][] = "$name ".$errorMsg[$error];
		else if(!in_array($type, $permite))
			$result['erro'][] = "$name - formato nao suportado";
		else if($size > $maxSize)
			$result['erro'][] = "$name excede o limite de upload";
		else{
			if(move_uploaded_file($tmp, $folder.'/'.$novoNome)){
				
				$imgBD->insert("`nome`, `linkPaciente`, `linkImagem`, `idPaciente`, `nomeImagem`", "'".utf8_decode($_POST['nomePaciente'])."','".$_POST['pastaPaciente']."','".$nomeBD."','".$_POST['idPac']."','".$novoNome."'");
				if($imgBD->execute() == 0){
					$result['ok'][] = "$name upload e banco de dados ok";
					//$result['files'][] = array('id'=>mysql_insert_id(), 'file'=>$novoNome);
				} else {
					$result['erro'][] = "$name upload ok - falha ao inserir no banco";
				}
			} else {
				$result['erro'][] = "$name erro no upload";
			}
		}
	}
}

echo json_encode($result);

?>