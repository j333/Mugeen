<?php
	include('../mysql/mysql.php');
	include('includes/funciones.php');
	$get = LimpiarGET();
	$post = LimpiarPOST();
	
	if(isset($post['btn']))
	{
		if($post['btn'] == 'Cancelar')
		{
			header("location: colores.php");
			exit;
		}
		if($post['btn'] == 'Guardar')
		{
			$valores['nombre'] = $post['nombre'];
			$valores['color'] = $post['color'];

			if($post['id']!='')
			{
				Sql_update('colores',$valores,array('id_color' => $post['id']));
				header("location: colores.php?exito=actualizar");
				exit;
			}
			else
			{
				$valores['id_color'] = '';
				Sql_insertar('colores',$valores);
				header("location: colores.php?exito=insertar");
				exit;
			}
		}
	}
	$datos['color'] = '#fff';
	if(isset($get['id']))
	{
		$datos = Sql_select('colores',array('id_color' => $get['id']),'=');
		$datos = $datos[0];
	}
	
	ControlAdmin();
	
	$pag_actual = 'Agregar Color';
	
?>
<?php include('includes/header.php'); ?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#form').submit(function(){
				if($('#nombre').val()==''){ $('.error_acciones').html('Campo Nombre es Requerido'); $('.error_acciones').show(); return false; }
			});
			$('#new_color').ColorPicker({
				onSubmit : function(){
					$('.colorpicker').hide();
					$('.color_final_edit').css({'background-color' : '#'+$('.colorpicker_hex').children().val() });
					$('#color').val('#'+$('.colorpicker_hex').children().val());
				}
			});
		});
	</script>
	<h4 class="alert_error error_acciones" style="display:none;">Debe seleccionar un elemento.</h4>
	<form action="" method="post" id="form">
		<input type="hidden" id="id" name="id" value="<?php echo $get['id']; ?>">
		<input type="hidden" id="color" name="color" value="<?php echo $datos['color']; ?>">
		<article class="module width_full">
			<header><h3>Datos de Color</h3></header>
				<div class="module_content">
						<fieldset style="width:48%; float:left; margin-right:3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Nombre:</label>
							<input type="text" name="nombre" id="nombre" style="width:92%;" value="<?php echo $datos['nombre']; ?>">
						</fieldset>
						<fieldset style="width:48%; float:left; "> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Color: <a href="javascript:void(0);" id="new_color">Cambiar Color</a></label>
							<div class="color_final_edit" style="background-color:<?php echo $datos['color']; ?>"></div>
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