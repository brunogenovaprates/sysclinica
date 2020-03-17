<?php

// CONFIGURACOES DA PAGINA
$menuItem = 6;
$pagefile = 'impressos.php';


// INCLUDES
include 'inc/config.inc.php';

if(!isset($_GET['print'])){
	include 'inc/header.inc.php';
}


// FORM TREE
$formStructure = array(
	 //Dados pessoais
	 array('Título','divider'),
	 array('Título','title','text','validate[required] form-control'),
	 //Observacoes
	 array('Conteúdo / texto','divider'),
	 array('Texto','text','obs2','form-control','formGroupComps'=>'" style="width:99%; height:400px;')
);

	$docs = new Crud('sys_impressos');

if(isset($_GET['novo'])){
	
	
		if(isset($_GET['act'])){
			$docs2 = new Crud('sys_impressos');
			$docs2->insert('`title`, `text`', '"'.mysql_real_escape_string(utf8_decode($_POST['title']), $mysqllink).'","'.mysql_real_escape_string(utf8_decode($_POST['text']), $mysqllink).'"');
			if($docs2->execute() == 1){
				header('Location: ?dAM=0');
			} else {
				header('Location: ?dAM=1');
			}
		}
		
		$html .= '<form action="?novo&act" class="validate" id="form1" method="post">
					<fieldset>';
		$html .= openFullWidget('<i class="fa fa-files"></i> Cadastrar novo impresso',$actions=1);
		$html .= formCreateInsert($formStructure);
		$html .= '</fieldset>
			  </form><div class="clear h10"></div>';
		$html .= closeFullWidget();
	


} elseif(isset($_GET['edit'])) {
		
		if(isset($_GET['act'])) {
			$docs3 = new Crud('sys_impressos');
			$docs3->update('`title`="'.mysql_real_escape_string(utf8_decode($_POST['title']), $mysqllink).'", `text`="'.mysql_real_escape_string(utf8_decode($_POST['text']), $mysqllink).'"',' `id`='.$_GET['act']);
			if($docs3->execute() == 1){
				header('Location: ?list&dAM=0');
			} else {
				header('Location: ?list&dAM=1');
			}
			
		}
		
		$dataQR = $docs->selectArrayConditions('*','id='.$_GET['edit']);
		$data = mysql_fetch_array($dataQR);
				
		$html .= '<form action="?edit&act='.$_GET['edit'].'" class="validate" id="form1" method="post">
					<fieldset>';
		$html .= openFullWidget('<i class="fa fa-edit"></i> Editar impresso',$actions=1);
		$html .= formCreateEdit($formStructure,$data,$utf8=1);
		$html .= '</fieldset>
			  </form><div class="clear h10"></div>';
		$html .= closeFullWidget();
		

} elseif(isset($_GET['delete'])) {

		$docs->delete('id='.$_GET['delete']);
		if($docs->execute() == 1){
			header('Location: ?listar&dAM=0');
		} else {
			header('Location: ?listar&dAM=1');
		}
		
		
//LINHA DO IMPRESSO h6 {margin:0 0 10px 0;padding:0 0 10px 0;border-bottom:solid 1px #dddddd; font-size:18px; text-align:center;}
} elseif(isset($_GET['print'])) {
		
		$dataQR = $docs->selectArrayConditions('*','id='.$_GET['print']);
		$data = mysql_fetch_array($dataQR);

		$html2 = '
		<head>
			<style>
				body {margin:0; font-size:13px; font-family:Tahoma, Geneva, sans-serif; padding:0; position:relative;}
				p {margin:0;padding:0;}
				table tr {margin:0;padding:0;}
				table td {margin:0;padding:4px;border:1px solid #ddd;text-align:left;}
				table th {margin:0;padding:4px;border:1px solid #ddd;text-align:left; font-weight:bold;}
				table {border:none;}
				
				.clear {clear:both;}
				.h20 {margin:15px;}
				.h10 {margin:10px;}
				#rodape {display:block; width:100%; position:absolute; text-align:center; font-size:14px; line-height:22px; bottom:20px; right:0px; left:0px;}
				
			</style>
		</head>
		<body>
		
			<img src="header.jpg" width="100%" style="height:auto" />
			<div class="clear h20"></div><div class="clear h20"></div><h6>&nbsp;</h6><div class="clear h20"></div>';
			
			$html2 .= utf8_encode($data['text']);
		
		$html2 .= '<div class="clear h10"></div><div id="rodape">Rua Mato Grosso, 306 - Cj. 1801 - Higienópolis - SP Cep: 01239-040<br>Tel.: (11) 2114 6066 / 3214.0237<br>www.naturalhair.com.br / clinica@ricardolemos.med.br</div>
		</body>
		';


		include("MPDF56/mpdf.php");
		$mpdf=new mPDF('c', 'A4');		
		$mpdf->WriteHTML($html2);
		$mpdf->Output();
		exit;
		

} elseif(isset($_GET['listar'])) {

		$html .= openFullWidget('<i class="fa fa-files"></i> Impressos cadastrados');
		
			$html .= '
				<table class="table table-bordered table-striped table-hover">
					<tr>
						<th width="80%">Título</th>
						<th width="10%">Data</th>
						<th width="10%">Ações</th>
					</tr>';
			$docsQR = $docs->selectArray('*');
			while($docsLS = mysql_fetch_array($docsQR)){
				$html .= '<tr>
						<td>'.utf8_encode($docsLS['title']).'</td>
						<td>'.dataDecode($docsLS['data']).'</td>
						<td>
							<a class="btn btn-xs btn-info" type="button" href="?print='.$docsLS['id'].'" target="_blank"><i class="fa fa-print"></i> </a>
							<button class="btn btn-xs btn-warning" type="button" onclick="window.location=\'?edit='.$docsLS['id'].'\'"><i class="fa fa-pencil"></i> </button>
							<button class="btn btn-xs btn-danger" type="button" onclick="if(confirm(\'Deseja realmente excluir?\')) { window.location=\'?delete='.$docsLS['id'].'\'; } event.returnValue = false; return false;"><i class="fa fa-times"></i> </button>
						</td>
					  </tr>';
			}

		$html .= '</table>';
		$html .= closeFullWidget();
		

} else {
	
		$html .= '<form action="?novo" id="form1" method="post">
					<fieldset>';
		$html .= openFullWidget('<i class="fa fa-check"></i> Selecionar documento para impressão',$actions=3);
		
		$html .= '<h6>Selecione o documento desejado e clique em \'Imprimir\'</h6><hr>
				  <select name="docSel" id="docSel">';	
		
		$docsQR = $docs->selectArray('*');
		while($docsLS = mysql_fetch_array($docsQR)){
			$html .= '<option value="'.$docsLS['id'].'">'.utf8_encode($docsLS['title']).'</option>';
		}
		$html .= '	</select>
					<div class="clear h20"></div>
					<button class="btn btn-info printDoc" type="button"><i class="fa fa-print"></i> Imprimir</button>
			  </form><div class="clear h10"></div>';
		$html .= closeFullWidget();
	
}

echo $html;

include 'inc/footer.inc.php';

?>