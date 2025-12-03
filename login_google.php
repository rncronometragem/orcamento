<?php include_once"config.php";?>
<?php session_start();?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Cadastro de Clientes - Login</title>
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/signin.css" rel="stylesheet">
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	</head>
	<body>
		<div class="container">
			<div class="form-signin" style="background: <?php echo $barra;?>;border-radius: 10px;">
				<h3 class="text-center"><img src="logo/<?php echo $imagem;?>" alt="Em Geral" width=150 height=150></h3>
				<h3 class="text-center"><font color="<?php echo $letra;?>">Login</font></h3>
				<font color="<?php echo $letra;?>"><?php
					if(isset($_SESSION['msg'])){
						echo $_SESSION['msg'];
						unset($_SESSION['msg']);
					}
				?></font>
				<form method="POST" autocomplete="off" action="valida.php">
					<!--<label>Usuário</label>-->
					<input type="usuario" name="usuario" id="usuario" class="hidden" autocomplete="off" style="display: none;" />
					<input type="text" name="usuario" value="admin" placeholder="Digite o seu usuário" class="form-control"><br>
					<!--<label>Senha</label>-->
					<input type="password" autocomplete="off" value="01022025$%" name="senha" placeholder="Digite a sua senha" class="form-control"><br>
					<input type="password" name="password"  id="password" class="hidden" autocomplete="off" style="display: none;" />
		       <!--chave do google - CHAVE DE SITE Caixa de verificação v2-->	
				<div class="g-recaptcha" data-sitekey="6LcmIzobAAAAAD7mQTjJkdR5Ge1_hNUDC0sWU7Zx"></div>
				<!--chave do google -->	
					<div class="row text-center" style="margin-top: 20px;"> 
						<button type="submit" name="btnLogin" value="Acessar" class="btn btn-light" style="font-size: 16px;" >Acessar</button>	
					</div>
					<div class="row text-center" style="margin-top: 20px;">
					<h4></h4>
					</div>
				</form>
			</div>
		</div>		
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>