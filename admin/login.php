<?php
	include('../mysql/mysql.php');
	$post = LimpiarPOST();
	
	if(isset($post['user']))
	{
		if($user = Sql_select('administradores',array('user' => $post['user'],'password' => $post['password']),'='))
		{
			$_SESSION['administrador'] = $user[0];
			header("location: productos.php");
			exit;
		}
		else
		{
			$error = 1;
		}
	}
?>
<!DOCTYPE>
<html>
	<head>
		<title>Ingreso Mugeen.com.ar</title>
		<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="icon" href="images/favicon.ico" type="images/vnd.microsoft.icon">
		<link rel="stylesheet" href="css/login.css">
		<script type="text/javascript">
			$(document).ready(function(){
				$('#form').submit(function(){
					$('.login_error').css({'display':'none'});
					if($('#user').val()=='')
					{
						$('.login_error').html('Ingrese su Usuario');
						$('.login_error').css({'display':'block'});
						return false;
					}
					if($('#password').val()=='')
					{
						$('.login_error').html('Ingrese su Contraseña');
						$('.login_error').css({'display':'block'});
						return false;
					}
					return true;
				});
			});
		</script>
	</head>
	<body>
		<div class="login_logo">
			<img src="images/logo.png">
		</div>
		<div class="window_login">
			<form action="" method="post" id="form">
				<div class="login_lbl">Usuario:</div>
				<input type="text" class="login_input" id="user" name="user">
				<div class="login_lbl">Contraseña:</div>
				<input type="password" class="login_input" id="password" name="password">
				<input type="submit" class="login_btn" value="Ingresar"></div>
			</form>	
		</div>
		<?php if(isset($error)){ ?>
			<div class="login_error" style="display:block">Error en Usuario/Contraseña</div>
		<?php }else{ ?>
			<div class="login_error"></div>
		<?php } ?>
	</body>
</html>