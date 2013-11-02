<?php
	include('../mysql/mysql.php');
	include('includes/funciones.php');
	$get = LimpiarGET();
	$post = LimpiarPOST();
	
	if(isset($post['btn']))
	{
		if($post['btn'] == 'Guardar')
		{
			Sql_update('config',array('valor' => $post['email_contacto']),array('campo' => 'email_contacto'));
			Sql_update('config',array('valor' => $post['email_pedido']),array('campo' => 'email_pedido'));
			header("location: panel.php?exito=guardar");
			exit;
		}
	}
	
	ControlAdmin();
	
	$datos = Sql_select('config',0,'=');
	
	$pag_actual = 'Panel de Administrador';
	
?>
<?php include('includes/header.php'); ?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#form').submit(function(){
				if($('#nombre').val()==''){ $('.error_acciones').html('Campo Nombre es Requerido'); $('.error_acciones').show(); return false; }
				if($('#user').val()==''){ $('.error_acciones').html('Campo Usuario es Requerido'); $('.error_acciones').show(); return false; }
				if($('#password').val()==''){ $('.error_acciones').html('Campo Contraseña es Requerido'); $('.error_acciones').show(); return false; }
			});
		});
	</script>
	<?php if(isset($get['exito'])){ ?>
		<?php if($get['exito']=='guardar'){ ?>
			<h4 class="alert_success">Guardado Exitosamente.</h4>
		<?php } ?>
	<?php } ?>
	<h4 class="alert_error" id="error_producto" style="display:none;">Debe seleccionar un elemento.</h4>
	<form action="" method="post" id="form">
		<article class="module width_full">
			<header><h3>Panel de Administrador</h3></header>
				<div class="module_content">
					<fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
						<label>Email de Contacto:</label>
						<input type="text" name="email_contacto" id="email_contacto" style="width:92%;" value="<?php echo $datos[0]['valor']; ?>">
					</fieldset>
					<fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
						<label>Email de Pedidos:</label>
						<input type="text" name="email_pedido" id="email_pedido" style="width:92%;" value="<?php echo $datos[1]['valor']; ?>">
					</fieldset><div class="clear"></div>
				</div>
			<footer>
				<div class="submit_link">
					<input type="submit" name="btn" value="Guardar" class="alt_btn">
				</div>
			</footer>
		</article>	
	</form>
	<div class="spacer"></div>
<?php include('includes/footer.php'); ?>