<?php

	include 'inc/config.inc.php';


if(isset($_GET['login'])){
	$loginAction = 1;
	
	$login = mysql_real_escape_string(utf8_decode($_POST['login']), $mysqllink);
	$senha = mysql_real_escape_string(utf8_decode($_POST['senha']), $mysqllink);
	
	$puxaBD = new Crud('sys_usuarios');
	$query = $puxaBD->selectArrayConditions("*","login='".$login."'");
	$queryQR = mysql_fetch_array($query);
		if($queryQR['senha'] == $_POST['senha']){
			$_SESSION['login'] = $queryQR['login'];
			header('Location: agenda.php');
		} else {
			header('Location: login.php?erro');
		}
}

if(isset($_GET['logout'])){
	session_start(); 
	unset($_SESSION['login']);
	session_destroy();
	header('Location: login.php');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <!-- Title and other stuffs -->
  <title>Login - Acesso ao sistema</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">

  <!-- Stylesheets -->
  <link href="style/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="style/font-awesome.css">
  <link href="style/style.css" rel="stylesheet">
  <link href="style/bootstrap-responsive.html" rel="stylesheet">
  <link href="style/signin.css" rel="stylesheet" type="text/css" />
  
  <!-- HTML5 Support for IE -->
  <!--[if lt IE 9]>
  <script src="js/html5shim.js"></script>
  <![endif]-->

  <!-- Favicon -->
  <link rel="shortcut icon" href="img/favicon/favicon.png">
</head>

<body>










<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand" href="./"> <img src="img/logoRicardo.png" align="left"> </a>
     
      
    </div>
    <!-- /container --> 
    
  </div>
  <!-- /navbar-inner --> 
  
</div>
<!-- /navbar -->

		<div class="account-container">
		<div class="content clearfix">
		    <?php if(isset($_GET['erro'])){ ?>
                    <div class="alert alert-danger">
                      Login ou senha inválidos, tente novamente!
                    </div>
                    <?php } ?>
                
                  <!-- Login form -->
                  <form class="form-horizontal" action='?login' method="post">
				  </br>
					  <div class="login-fields">
					    <h5>Acesso ao sistema:</h5>
						<div class="field">
					      <label for="inputEmail">Login:</label>
					      <input type="text" id="inputEmail" name="login" placeholder="Digite o usuário" class="login username-field" />
					    </div>
					    <!-- /field -->
					    
					    <div class="field">
					      <label for="inputPassword">Senha:</label>
					      <input type="password" id="inputPassword" name="senha" placeholder="Digite a senha" class="login password-field" />
					    </div>
					    <!-- /password --> 
					    
					  </div>
					  <!-- /login-fields -->
					  
					  <div class="login-actions"> <br />
					  <button type="submit" class="btn button btn-info">Entrar</button> 
					    <!-- Sem Acesso? <a href="./cadastrar.html">Cadastre-se</a><br />-->
					    <!--Lembrar <a href="./lembrarSenha.html">Senha</a> </div>
					  <!-- .actions -->
  
 					 </form>
				</div>
		<!-- /account-container --> 

		<!-- Text Under Box -->
		
		<div class="center">Sistema Natural Hair | Desenvolvido por:  <a href="http://www.proinov.com.br/" target="_blank"><img src="img/logoProinov.png" style="width: 80px;"  alt="Powered by Proinov.com.br"></a> </p>
		
		<!-- /login-extra --> 















<!--

<div class="admin-form">
  <div class="container">

    <div class="row">
      <div class="col-md-12">
       
            <div class="widget">
              
              <div class="widget-head">
                <i class="icon-lock"></i> Acesso ao sistema 
              </div>

              <div class="widget-content">
                <div class="padd">
                
                	<?php if(isset($_GET['erro'])){ ?>
                    <div class="alert alert-danger">
                      Login ou senha inválidos.
                    </div>
                    <?php } ?>
                
                 
                  <form class="form-horizontal" action='?login' method="post">
                   
                    <div class="form-group">
                      <label class="control-label col-lg-3" for="inputEmail">Login</label>
                      <div class="col-lg-9">
                        <input type="text" class="form-control" id="inputEmail" name="login">
                      </div>
                    </div>
                  
                    <div class="form-group">
                      <label class="control-label col-lg-3" for="inputPassword">Senha</label>
                      <div class="col-lg-9">
                        <input type="password" class="form-control" id="inputPassword" name="senha">
                      </div>
                    </div>
                 
                    
                        <div class="col-lg-9 col-lg-offset-3">
							<button type="submit" class="btn btn-success">Entrar</button>
							<button type="reset" class="btn btn-default">Limpar</button>
						</div>
                    <br />
                  </form>
				  
				</div>
                </div>
              
                <div class="widget-foot">
                  Copyright &copy; <?php echo date("Y"); ?> - Versão 1.0b
                </div>
            </div>  
      </div>
    </div>
  </div> 
</div>
-->

<!-- JS -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>