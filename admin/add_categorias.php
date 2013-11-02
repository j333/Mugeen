<?php
	include('../mysql/mysql.php');
	include('includes/funciones.php');
	$get = LimpiarGET();
	$post = LimpiarPOST();
	
	if(isset($post['btn']))
	{
		if($post['btn'] == 'Cancelar')
		{
			header("location: categorias.php");
			exit;
		}
		if($post['btn'] == 'Guardar')
		{
			$valores['nombre'] = $post['nombre'];
			$valores['nodo_final'] = 0;
			$list_sub = explode(';',$post['sub_cat']);
			
			if($_FILES['image']['tmp_name']!='')
			{
				$nombre = generarCodigo(15,'qwertyuiopasdfhjklzxcbbnm');
				$ext = pathinfo($_FILES['image']['name']);
				$ext = $ext['extension'];
				move_uploaded_file($_FILES['image']['tmp_name'],'../images/categorias/'.$nombre.'.'.$ext);
				chmod('../images/categorias/'.$nombre.'.'.$ext, 0755);
				$valores['image'] = $nombre.'.'.$ext;
			}
			if($_FILES['image_b']['tmp_name']!='')
			{
				$nombre = generarCodigo(15,'qwertyuiopasdfhjklzxcbbnm');
				$ext = pathinfo($_FILES['image_b']['name']);
				$ext = $ext['extension'];
				move_uploaded_file($_FILES['image_b']['tmp_name'],'../images/categorias/'.$nombre.'.'.$ext);
				chmod('../images/categorias/'.$nombre.'.'.$ext, 0755);
				$valores['image_b'] = $nombre.'.'.$ext;
			}
			if($post['id']!='')
			{
				Sql_delete('categorias',array('sub_de' => $post['id']));
				foreach($list_sub as $c)
				{
					if($c == ''){break;}
					$c = explode(':',$c);
					if($c[0] == '-1')
					{
						Sql_insertar('categorias',array('id_categoria' => '','nombre' => $c[1],'nodo_final'=> 1,'sub_de' => $post['id'],'estado' => 'activa'));
					}
					else
					{
						Sql_insertar('categorias',array('id_categoria' => $c[0],'nombre' => $c[1],'nodo_final'=> 1,'sub_de' => $post['id'],'estado' => 'activa'));
					}
				}
				
				Sql_update('categorias',$valores,array('id_categoria' => $post['id']));
				header("location: categorias.php?exito=actualizar");
				exit;
			}
			else
			{
				$valores['id_categoria'] = '';
				$valores['estado'] = 'activa';
				
				$id = Sql_insertar('categorias',$valores);
				header("location: categorias.php?exito=insertar");
				foreach($list_sub as $c)
				{
					if($c == ''){break;}
					$c = explode(':',$c);
					Sql_insertar('categorias',array('id_categoria' => '','nombre' => $c[1],'nodo_final'=> 1,'sub_de' => $id,'estado' => 'activa'));
				}
				exit;
			}
		}
	}
	
	if(isset($get['id']))
	{
		$datos = Sql_select('categorias',array('id_categoria' => $get['id']),'=');
		$datos = $datos[0];
		
		$lst_sub = Sql_select_especial('categorias',array('sub_de' => $get['id']),'=',0,array('orden' => 'ASC','campo' => 'nombre'),'AND');
	}
	
	ControlAdmin();
	
	$pag_actual = 'Agregar Categoría';

?>
<?php include('includes/header.php'); ?>
	<script type="text/javascript">
		var array_sub = new Array();
		function OrdenarSubCat()
		{
			var tmp = $('.ejemplo_cat').clone().appendTo('.list_subcat');
			tmp.attr('class','color_campo final_cat');
			tmp.show();
			tmp.find('.nombre').html($('.new_cat').find('#nombre').val());
			tmp.find('#nombre').val($('.new_cat').find('#nombre').val());
					
			tmp.find('.borrar_cat').mouseover(function(){
				$(this).css({'opacity' : 1 });
			});
			tmp.find('.borrar_cat').mouseout(function(){
				$(this).css({'opacity' : 0.5 });
			});
					
			tmp.find('.borrar_cat').click(function(){
				$(this).parent().remove();
				if($('.final_cat').length<1)
				{
					$('#sin_cat').show();
				}
			});
			tmp.find('.edit_cat').click(function(){
				$(this).parent().find('.nombre').html('<input type="text" style="width:100px; margin-top:2px;" value="'+$(this).parent().find('#nombre').val()+'">');
				$(this).parent().find('.nombre').children().focus();
				$(this).parent().find('.btn_final').hide();
				$(this).parent().find('.btn_edit').show();
			});
			tmp.find('.cancel_edit').click(function(){
				$(this).parent().find('.nombre').html($(this).parent().find('#nombre').val());
				$(this).parent().find('.btn_final').show();
				$(this).parent().find('.btn_edit').hide();
			});
			
			tmp.find('.save_edit').click(function(){
				$(this).parent().find('#nombre').val($(this).parent().find('.nombre').children().val());
				$(this).parent().find('.nombre').html($(this).parent().find('.nombre').children().val());
				$(this).parent().find('.btn_final').show();
				$(this).parent().find('.btn_edit').hide();
			});
			
			$('#sin_cat').hide();		
			$('.new_cat').hide();
			$('.btn_edit').hide();
		}
		$(document).ready(function(){
			$('#nodo_final').change(function(){
				if($(this).val() == 0)
				{
					$('#subcategorias').show();
					return false;
				}
				$('#subcategorias').hide();
			});
			$('#nodo_final').change();
			
			$('#add_cat').click(function(){
				$('#sin_cat').hide();
				$('.new_cat').show();
				$('.new_cat').find('#nombre').val('');
				$('.new_cat').find('#nombre').focus();
			});
			$('.no_agregar_cat').click(function(){
				$('.new_cat').hide();
				if($('.final_cat').length<1)
				{
					$('#sin_cat').show();
				}
			});
			$('.agregar_cat').click(function(){
				OrdenarSubCat();
			});
			$('.icn_logout').mouseover(function(){
				$(this).css({'opacity' : 1 });
			});
			$('.icn_logout').mouseout(function(){
				$(this).css({'opacity' : 0.5 });
			});
			
			$('.borrar_cat').click(function(){
				$(this).parent().remove();
				if($('.final_cat').length<1)
				{
					$('#sin_cat').show();
				}
			});
			$('#sin_cat').hide();
			if($('.final_cat').length<1)
			{
				$('#sin_cat').show();
			}
			$('.btn_edit').hide();
			
			$('.edit_cat').click(function(){
				$(this).parent().find('.nombre').html('<input type="text" style="width:100px; margin-top:2px;" value="'+$(this).parent().find('#nombre').val()+'">');
				$(this).parent().find('.nombre').children().focus();
				$(this).parent().find('.btn_final').hide();
				$(this).parent().find('.btn_edit').show();
			});
			$('.cancel_edit').click(function(){
				$(this).parent().find('.nombre').html($(this).parent().find('#nombre').val());
				$(this).parent().find('.btn_final').show();
				$(this).parent().find('.btn_edit').hide();
			});
			
			$('.save_edit').click(function(){
				$(this).parent().find('#nombre').val($(this).parent().find('.nombre').children().val());
				$(this).parent().find('.nombre').html($(this).parent().find('.nombre').children().val());
				$(this).parent().find('.btn_final').show();
				$(this).parent().find('.btn_edit').hide();
			});
			$('#change_pic').click(function(){
				$('.con_foto').hide();
				$('#image').show();
			});
			$('#change_pic_b').click(function(){
				$('.con_foto_b').hide();
				$('#image_b').show();
			});
			$('#form').submit(function(){
				if($('#nombre').val()==''){ $('.error_acciones').html('Campo Nombre es Requerido'); $('.error_acciones').show(); return false; }
				var lista = '';
				$('.final_cat').each(function(){
					var id = $(this).find('#id').val();
					var nombre = $(this).find('#nombre').val();
					lista+=id+':'+nombre+';';
				});
				$('#sub_cat').val(lista);
			});
		});
	</script>
	<h4 class="alert_error error_acciones" style="display:none;">Debe seleccionar un elemento.</h4>
	<form action="" method="post" id="form" enctype="multipart/form-data">
		<input type="hidden" id="id" name="id" value="<?php echo $get['id']; ?>">
		<input type="hidden" id="sub_cat" name="sub_cat">
		<article class="module width_full">
			<header><h3>Datos de Categoría</h3></header>
				<div class="module_content">
						<fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Nombre:</label>
							<input type="text" id="nombre" name="nombre" style="width:92%;" value="<?php echo $datos['nombre']; ?>">
						</fieldset>
						<fieldset style="width:48%; float:left; display:none;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>¿Tiene Subcategorias?:</label>
							<select name="nodo_final" id="nodo_final">
								<option <?php if($datos['nodo_final']){ echo 'selected="selected"'; } ?> value="1">No</option>
								<option <?php if(!$datos['nodo_final']){ echo 'selected="selected"'; } ?> value="0">Si</option>
							</select>
						</fieldset><div class="clear"></div>
						<fieldset id="subcategorias"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label style="width:100%;">SubCategorías: <a href="javascript:void(0);" id="add_cat" >Agregar SubCategoría</a></label>
							<div class="list_color list_subcat">
								<div class="color_campo new_cat" style="display:none;">
									<div class="color_nombre nombre"><input type="text" id="nombre" style="width:100px; margin-top:2px;"></div>
									<div class="icn_logout agregar_cat">
										<img width="10" src="images/icn_alert_success.png">
									</div>
									<div class="icn_logout no_agregar_cat">
										<img width="10" src="images/icn_logout.png">
									</div>
								</div>
								<div class="color_campo ejemplo_cat" style="display:none;">
									<input type="hidden" id="nombre">
									<input type="hidden" id="id" value="-1">
									<div class="color_nombre nombre"></div>
									<div class="icn_logout edit_cat btn_final">
										<img width="10" src="images/icn_edit.png">
									</div>
									<div class="icn_logout borrar_cat btn_final">
										<img width="10" src="images/icn_logout.png">
									</div>
									<div class="icn_logout save_edit btn_edit">
										<img width="10" src="images/icn_alert_success.png">
									</div>
									<div class="icn_logout cancel_edit btn_edit">
										<img width="10" src="images/icn_logout.png">
									</div>
								</div>
								<?php if(is_array($lst_sub)){ ?>
									<?php foreach($lst_sub as $l){ ?>
										<div class="color_campo final_cat">
											<input type="hidden" id="nombre" value="<?php echo $l['nombre']; ?>" >
											<input type="hidden" id="id" value="<?php echo $l['id_categoria']; ?>">
											<div class="color_nombre nombre"><?php echo $l['nombre']; ?></div>
											<div class="icn_logout edit_cat btn_final">
												<img width="10" src="images/icn_edit.png">
											</div>
											<div class="icn_logout borrar_cat btn_final">
												<img width="10" src="images/icn_logout.png">
											</div>
											<div class="icn_logout save_edit btn_edit">
												<img width="10" src="images/icn_alert_success.png">
											</div>
											<div class="icn_logout cancel_edit btn_edit">
												<img width="10" src="images/icn_logout.png">
											</div>
										</div>
									<?php } ?>
								<?php } ?>
							</div>
							<span id="sin_cat">&nbsp;&nbsp;&nbsp;Sin SubCategorías Agregadas</span>
						</fieldset><div class="clear"></div>
						<?php if($datos['image'] != ''){ ?>
							<fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
								<label>Imagen: <a href="javascript:void(0);" id="change_pic" class="con_foto" >Cambiar Foto</a></label>
								<img class="con_foto" src="../images/categorias/<?php echo $datos['image']; ?>" height="100">
								<input type="file" id="image" name="image" style="width:92%; display:none;">
							</fieldset>
						<?php }else{ ?>
							<fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
								<label>Imagen:</label>
								<input type="file" id="image" name="image" style="width:92%;">
							</fieldset>
						<?php } ?>
						<?php if($datos['image_b'] != ''){ ?>
							<fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
								<label>Imagen Slider: <a href="javascript:void(0);" id="change_pic_b" class="con_foto_b" >Cambiar Foto</a></label>
								<img class="con_foto_b" src="../images/categorias/<?php echo $datos['image_b']; ?>" height="100">
								<input type="file" id="image_b" name="image_b" style="width:92%; display:none;">
							</fieldset>
						<?php }else{ ?>
							<fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
								<label>Imagen Interna:</label>
								<input type="file" id="image_b" name="image_b" style="width:92%;">
							</fieldset>
						<?php } ?>
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