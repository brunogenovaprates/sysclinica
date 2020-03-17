<?php

// CONFIGURACOES DA PAGINA
$menuItem = 6;
$pagefile = 'impressoOutros.php';

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
	 array('Texto','text','obs','form-control','formGroupComps'=>'" style="width:99%; height:400px;')
);

$formStructure = array(
	 //Dados pessoais
	 array('Dados pessoais','divider','validate[required]'),
	 array('Nome completo*','nome','text',' form-control doubleField validate[required]'),
	 array('Data de nascimento','dataNascimento','data','form-control'),
	 array('Idade','idade','idade','form-control'),
	 array('Sexo','sexo','select' => array(array('M','Masculino'), array('F','Feminino')),'form-control'),
	 array('RG|RNE','rg','text','form-control'),
	 array('CPF','cpf','text','validate[funcCall[validateCPF]] form-control'),
	  array('Estado civil','estadoCivil', 'select' => array(array('S','Solteiro(a)'), array('C','Casado(a)'), array('D','Divorciado(a)'), array('V','Viúvo(a)')), 'form-control'),
	 array('Profissão','ocupacao', 'text','form-control'),
	 array('Indicação','indicacao','text','form-control'),
	


	 //Contatos
	 array('Contatos','divider'),
	 array('Telefone residencial','telefoneRes', 'ddd'=>'ddd_res', 'form-control'),
	 array('Telefone celular','celular','ddd'=>'ddd_celular', 'form-control'),
	 array('Telefone comercial','telefoneCom', 'ddd'=>'ddd_com', 'form-control'),	
	 array('Falar com','falarcom','text','form-control'),	 
	 array('E-mail','email', 'text','form-control doubleField'),
	
	 //Endereço
	 array('Endereço','divider'),
	 array('CEP','cep','text','form-control'),
	 array('País','pais','text','form-control'),
	 array('Estado','estado','text','form-control'),
	 array('Cidade','cidade','text','form-control'),
	 array('Bairro','bairro','text','form-control'),
	 array('Endereço completo','endereco', 'text' ,'form-control doubleField'),
	 array('Número','numero', 'text' ,'form-control'),
	 array('Complemento','complemento', 'text' ,'form-control'),
	 
	 //Consulta
	 array('Consulta','divider'),
	 array('Data da primeira consulta','dataPrimeiraConsulta','data','form-control'),
	 array('Último retorno','ultimoRetorno','data','form-control'),
	 array('Convênio médico','convenio','select' => $loopConvenio,' form-control'),
	 
	 //Autorizo uso da imagem
	 array('Termos e condições','divider'),
	 array('','autorizacao','checkbox' => 'Autorizo o uso das minhas imagens','form-control'),
	 );

$formCirurgia = array(
		 array('Data','data','data','form-control'),
		 array('Título cirurgia','title','text','form-control'),
		 array('Hospital','hospital','text','form-control'),
		 array('S','s','text','form-control'),
		 array('D','d','text','form-control'),
		 array('T','t','text','form-control'),
		 array('Q|FG','umdoiscinco','text','form-control'),
		 array('Faixa','faixa','text','form-control'),
		 array('Haste','haste','text','form-control'),
		 array('Fio','fio','text','form-control'),
		 array('Local','local','text','form-control'),
		 array('Foco princ.','focoprincipal','text','form-control'),
		 array('Des.Conservador','campodesenho','text','form-control'),
		 array('Nota','nota','text','form-control'),
		 array('Retirada','retirada','text','form-control'),
		 array('Peças','pecas','text','form-control'),
		 array('A','a','text','form-control'),
		 array('D','d2','text','form-control'),
		 array('Qnt. de fios','qntfios','text','form-control'),
		 array('R.Q.F.','rqf','text','form-control'),
		 array('Elasticidade','elast','text','form-control'),
		 array('Implantação','imp','text','form-control'),
		 array('Equipe','equipe','text','form-control'),
		 array('Anestesista','anest','text','form-control'),
		 array('Início','inicio','hora','form-control'),
		 array('Colocação','coloc','hora','form-control'),
		 array('Final corte','finco','hora','form-control'),
		 array('Término','termino','hora','form-control'),
		 array('TOTAL','total','text','form-control '),
		 array('Observações','obs','text','form-control')
	);


	$formPlan = array(
		 array('Data do atendimento','data','data','form-control'),
		 array('Título planejamento','cirurgia','text','form-control'),
		 array('Densidade','densidade','text','form-control'),
		 array('Cor do fio','cor','text','form-control'),
		 array('Tipo do fio','tipo','text','form-control'),
		 array('Diâmetro do fio','diametro','text','form-control'),
		 array('Cor de pele','corpele','text','form-control'),
		 array('Elasticidade','elasticidade','text','form-control'),
		 array('Cond. couro cabeludo','condcouro','text','form-control'),
		 array('Região da operação','regoperada','text','form-control'),
		 array('N° enx. Anterior','anterior','text','form-control'),
		 array('N° enx. Coroa','coroa','text','form-control'),
		 array('N° enx. Ant. + Coroa','antcoroa','text','form-control'),
		 array('Custo','custo','text','form-control'),
		 array('Obs','obs','text','form-control')
	);


	$id = $_SESSION['selectedUserID'];

if(isset($_GET['relatorio'])){
	
	$menuItem == 6;
	$id = $_SESSION['selectedUserID'];

	
	if(isset($_GET['print'])){
		 //<!--Open Sans-->
		$html2 = '
		<head>
			<link href="style/fichacirurgica_pdf.css" rel="stylesheet">
		
		</head>';

		if(isset($_GET['pagina_1'])){
		
			
			
			$html2 .= '
				<body>
				<div style="width:48%; height:100%; float:left; display:block; border-right:1px solid #38b56d; padding-right:2%; line-height:20px;">
					

				<table class="historico">
				  <tr class="sim">
					<th>Data</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  			  
				  <tr>
					  <th>Hospital</th>
						<td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th>S</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>D</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th>T</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Q|FG</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th>Faixa</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Haste</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th>Fio</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Local</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>	 <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>

				  
				  <tr class="sim">
					  <th>Foco princ.</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Des.Conservador</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th>Nota</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Retirada</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th>Peças</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>A</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th>D</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Qnt. de fios</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th>R.Q.F.</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Elasticidade</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th>Implantação</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Equipe</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th>Anestesista</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Início</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th>Colocação</th>
					<td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Final corte</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th>Término</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>

				</table>

									
				</div>
			
			<!--LADO DIREITO -->
			
			
				<div style="width:47%; height:100%; float:right; display:block; border:none; padding-left:2%; line-height:20px;">';
					
					$editar = new Crud($database);
					$editDataQR = $editar->selectArrayConditions('*','idPaciente='.$id);
					$editData = mysql_fetch_array($editDataQR);
				
					
					
					$html2 .= '
					<img src="img/headerFicha.png" width="100%" style="height:auto" />
					
					<h6>Identificação</h6>
					<p>Nome: '.utf8_encode($editData["nome"]).' &nbsp;&nbsp;&nbsp;&nbsp; Id: '.utf8_encode($editData["id"]).' </p>
					
					<div class="clear h20"></div>
					
					
				</div>
			
			</body>';
			
		}
		
		if(isset($_GET['pagina_2'])){
			
			
			$html2 .= '
				<body>
				<div style="width:48%; height:100%; float:left; display:block; border-right:1px solid #38b56d; padding-right:2%; line-height:20px;">
					
					<h6 class="title">Patologias prévias e atuais</h6>
					<div class="linha"></div>
					<div class="linha"></div>
					<div class="linha"></div>
					<div class="linha"></div>
					<div class="linha"></div><br>
					
					<h6 class="title">Alergias</h6>
					<div class="linha"></div>
					<div class="linha"></div><br>

					<h6 class="title">Anestesia</h6>
					<div class="linha"></div>
					<div class="linha"></div><br>
					
					<h6 class="title">Cirurgia capilar prévia</h6>
					<div class="linha"></div>
					<div class="linha"></div><br>

					
					<h6 class="title">Uso de medicação</h6>
					<div class="linha"></div>
					<div class="linha"></div>
					<div class="linha"></div><br>
					
					<h6 class="title">Hábitos</h6>
					<div class="linha"></div>
					<div class="linha"></div>
	
				</div>
			
			<!--LADO DIREITO -->
				<div style="width:47%; height:100%; float:right; display:block; border:none; padding-left:2%; line-height:20px;">';
					
					
					
					$html2 .= '
					<table class="interna">
				  <tr class="sim">
					<th>Data</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  			  
				  <tr>
					  <th >Densidade</th>
						<td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th class="sim">Cor do fio</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Tipo do fio</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr class="sim">
					  <th class="sim">Diâmetro do fio</th>
							<td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Cor de pele</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				   <tr class="sim">
					  <th class="sim">Elasticidade</th>
							<td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				  <tr>
					  <th>Couro cabeludo</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				    <tr class="sim">
					  <th class="sim">Região da operação</th>
							<td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  				    
				    <tr>
					  <th>N° enx. Anterior</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				   <tr class="sim">
					  <th class="sim">N° enx. Coroa</th>
							 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				  
				    <tr>
					  <th>N° enx. Ant. + Coroa</th>
					 <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td> <td>&nbsp;</td>
				  </tr>
				 
				</table>
					
					
					<br><div class="center">
					<img src="img/head.jpg" width="40%" style="height:auto padding-bottom: 10px;" />
					<h6>1 - Frente | 2 - Entradas | 3 - Meio | 4 - Coroa | 5 - Corredores</h6>
					</div>
		
					
				
			
			</body>';
			
		}
		
		if(isset($_GET['historico'])){
		
		$editar = new Crud('sys_descricao_cirurgia');
					
		$html2 .= '
				<body>
				
				<div class="clear h10"></div>
							<label>Paciente: '.$_SESSION['selectedUser'].'</label>
						<div class="clear h10"></div>
							
							<table style="float:left; width:15%" class="interna ">
								<tbody>';
										foreach($formCirurgia as $key => $value){
											$html2 .= '<tr><th>'.$value[0].'</th></tr>';
										}
								$html2 .= '
								</tbody>
							</table>
	
							
							 <table class="interna" style="float:left; width:80%">
								<tbody>';
								
									foreach($formCirurgia as $key => $value){
										
										$html2 .= '<tr>';
										$editDataQR = $editar->selectArrayConditions('id,'.$value[1],'idPaciente='.$_SESSION['selectedUserID'].' ORDER BY str_to_date(data, "%d/%m/%Y") ASC');
										while($dataQR = mysql_fetch_array($editDataQR)){
											if(!empty($dataQR[$value[1]])){
												if($value[1] == 'data'){
													$html2 .= '<td class="dadosdados">'.utf8_encode($dataQR[$value[1]]).'
													<a href="#" onclick="if(confirm(\'Deseja realmente excluir?\')) { window.location=\'?cirurgia&delete='.$dataQR['id'].'\'; } event.returnValue = false; return false;" class="pull-right" style="color:red; margin:0 2px;" title="Deletar coluna"><i class="fa fa-trash-o"></i></a>
													<a href="?cirurgia&edit='.$dataQR['id'].'" class="pull-right" style="color:orange; margin:0 2px;" title="Editar coluna"><i class="fa fa-edit"></i></a>
													</td>';
												} else {
													$html2 .= '<td class="dados">'.utf8_encode($dataQR[$value[1]]).'</td>';
												}	
											} else {
												$html2 .= '<td class="dados">-</td>';
											}
										}
										$html2 .= '</tr>';
									}
							  	$html2 .= '
								</tbody>
							  </table>
							  <div class="clear h20"></div>';
							
						
						
						$html2 .= '
							  
							  <div class="clear h20"></div>
							  
				
				
				</body>';
			
		}
			
		
		
		include("MPDF56/mpdf.php");
		$mpdf=new mPDF('c', 'A4-L');		
		$mpdf->WriteHTML($html2);
		$mpdf->Output();
		exit;
		
		
		
		
		
		
	} else {
	
	$html .= openFullWidget('<i class="fa fa-list"></i> Impressão dos dados do paciente',false,false,false,false);
			
		
		$html .='
			<h5>Ficha médica</h5>
			<div class="separador"></div> 
			<a class="btn btn-info" href="?relatorio&print&pagina_1" target="_blank"><i class="fa fa-print"></i> Página 1</a>
			<a class="btn btn-info" href="?relatorio&print&pagina_2" target="_blank"><i class="fa fa-print"></i> Página 2</a>
			
				</br></br>
			<h5>Histórico de cirurgias</h5>
			<div class="separador"></div> 
			<a class="btn btn-info" href="?relatorio&print&historico" target="_blank"><i class="fa fa-print"></i> Histórico</a>
		';
	
	$html .= closeFullWidget();
	}

}


echo $html;
include 'inc/footer.inc.php';

?>