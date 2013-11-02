<?php
	include('../mysql/mysql.php');
	include('includes/funciones.php');
	$get = LimpiarGET();
	$post = LimpiarPOST();
	
	if(isset($post['btn']))
	{
		if($post['btn'] == 'Cancelar')
		{
			header("location: administradores.php");
			exit;
		}
		if($post['btn'] == 'Guardar')
		{
			$valores['nombre'] = $post['nombre'];
			$valores['user'] = $post['user'];
			$valores['password'] = $post['password'];
			$valores['rol'] = $post['rol'];

			if($post['id']!='')
			{
				Sql_update('administradores',$valores,array('id_administrador' => $post['id']));
				header("location: administradores.php?exito=actualizar");
				exit;
			}
			else
			{
				$valores['id_administrador'] = '';
				Sql_insertar('administradores',$valores);
				header("location: administradores.php?exito=insertar");
				exit;
			}
		}
	}
	
	if(isset($get['id']))
	{
		$datos = Sql_select('administradores',array('id_administrador' => $get['id']),'=');
		$datos = $datos[0];
	}
	
	ControlAdmin();
	
	$pag_actual = 'Agregar Administrador';
	
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
	<h4 class="alert_error error_acciones" style="display:none;">Debe seleccionar un elemento.</h4>
	<form action="" method="post" id="form">
		<input type="hidden" id="id" name="id" value="<?php echo $get['id']; ?>">
		<article class="module width_full">
			<header><h3>Datos de Administrador</h3></header>
				<div class="module_content">
						<fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Nombre:</label>
							<input type="text" name="nombre" id="nombre" style="width:92%;" value="<?php echo $datos['nombre']; ?>">
						</fieldset>
						<fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Rol:</label>
							<select name="rol">
								<option <?php if($datos['rol']=='god'){ echo 'selected="selected"'; } ?> value="god">GOD</option>
								<option <?php if($datos['rol']=='limitado'){ echo 'selected="selected"'; } ?> value="limitado">Limitado</option>
							</select>
						</fieldset><div class="clear"></div>
						<fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Usuario:</label>
							<input type="text" name="user" id="user" style="width:92%;" value="<?php echo $datos['user']; ?>">
						</fieldset>
						<fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Contraseña:</label>
							<input type="text" name="password" id="password" style="width:92%;" value="<?php echo $datos['password']; ?>">
						</fieldset><div class="clear"></div>
				</div>
			<footer>
				<div class="submit_link">
					<input type="submit" name="btn" value="Guardar" class="alt_btn">
					<input type="submit" name="btn" value="Cancelar">
				</div>
			</footer>
		</article>	
	</form>
	<div class="spacer"></div>
<?php include('includes/footer.php'); ?>