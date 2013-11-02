<?php
	include('../mysql/mysql.php');
	include('includes/funciones.php');
	$get = LimpiarGET();
	$post = LimpiarPOST();
	
	if(isset($post['new_color']))
	{
		$valores['id_color'] = '';
		$valores['nombre'] = $post['new_color'];
		$valores['color'] = $post['color'];
		$id = Sql_insertar('colores',$valores);
		echo '<div class="color_campo color_save">
				<input type="hidden" id="id_color" value="'.$id.'">
				<input type="hidden" id="color" value="'.$post['color'].'">
				<input type="hidden" id="nombre" value="'.$post['new_color'].'">
				<div class="color_final" style="background-color:'.$post['color'].'"></div>
				<div class="color_nombre">'.$post['new_color'].'</div>
				<div class="icn_logout agregar_color" style="font-size: 20px; line-height: 13px;">
					+
				</div>
			</div>';
		exit;
	}
	if(isset($post['btn']))
	{
		if($post['btn'] == 'Cancelar')
		{
			header("location: productos.php");
			exit;
		}
		if($post['btn'] == 'Guardar')
		{
			$list_cat = explode(',',$post['categorias']);
			$gg='';
			foreach($list_cat as $rr)
			{
				if($rr==''){break;}
				$categoria = Sql_select('categorias',array('id_categoria' => $rr),'=');
				$categoria = $categoria[0];
				
				$categoria = Sql_select('categorias',array('id_categoria' => $categoria['sub_de']),'=');
				$categoria = $categoria[0]; 
				
				$gg.=$categoria['id_categoria'].',';
			}
			$valores['nombre'] = $post['nombre'];
			$valores['codigo'] = $post['codigo'];
			$valores['id_categoria'] = $gg;
			$valores['id_subcategoria'] = $post['categorias'];
			$valores['talles'] = $post['talles'];
			$valores['colores'] = $post['colores'];
			$valores['materiales'] = $post['materiales'];
			$valores['caracteristicas'] = $post['caracteristicas'];
			$valores['importancia'] = $post['importancia'];
			$valores['subproductos'] = $post['subproductos'];
			
			if($post['img_erase'] !='')
			{
				$post['img_erase'] = explode(',',$post['img_erase']);
				foreach($post['img_erase'] as $img)
				{
					if($img == ''){break;}
					Sql_delete('imagenes_productos',array('id_image' => $img));
				}
			}
			
			if($post['id']!='')
			{
				if($_FILES['images']['tmp_name'][0]!='')
				{
					for($i = 0; $i<count($_FILES['images']['tmp_name']);$i+=1)
					{
						$nombre = generarCodigo(30,'qwertyuiopasdfhjklzxcbbnm');
						$ext = pathinfo($_FILES['images']['name'][$i]);
						$ext = $ext['extension'];
						move_uploaded_file($_FILES['images']['tmp_name'][$i],'../images/productos/'.$nombre.'.'.$ext);
						chmod('../images/productos/'.$nombre.'.'.$ext, 0755);
						$valores_b['id_image'] = '';
						$valores_b['nombre'] = $nombre.'.'.$ext;
						$valores_b['id_producto'] = $post['id'];
						
						Sql_insertar('imagenes_productos',$valores_b);
					}
				}
				Sql_update('productos',$valores,array('id_producto' => $post['id']));
				header("location: productos.php?exito=actualizar");
				exit;
			}
			else
			{
				$valores['id_producto'] = '';
				$valores['fecha_creacion'] = date('Y-m-d H:i:s',time());
				$id = Sql_insertar('productos',$valores);
				if($_FILES['images']['tmp_name'][0]!='')
				{
					for($i = 0; $i<count($_FILES['images']['tmp_name']);$i+=1)
					{
						$nombre = generarCodigo(30,'qwertyuiopasdfhjklzxcbbnm');
						$ext = pathinfo($_FILES['images']['name'][$i]);
						$ext = $ext['extension'];
						move_uploaded_file($_FILES['images']['tmp_name'][$i],'../images/productos/'.$nombre.'.'.$ext);
						$valores_b['id_image'] = '';
						$valores_b['nombre'] = $nombre.'.'.$ext;
						$valores_b['id_producto'] = $id;
						
						Sql_insertar('imagenes_productos',$valores_b);
					}
				}
				header("location: productos.php?exito=insertar");
				exit;
			}
		}
	}
	
	if(isset($get['id']))
	{
		$datos = Sql_select('productos',array('id_producto' => $get['id']),'=');
		$datos = $datos[0];
		
		$lst_imagenes = Sql_select('imagenes_productos',array('id_producto' => $get['id']),'=');
		$lst_subproductos = explode('}{',$datos['subproductos']);
		
		$lst_categorias_add = explode(',',$datos['id_subcategoria']);
	}
	
	ControlAdmin();
	
	$pag_actual = 'Agregar Producto';
	
	$lst_categorias = Sql_select('categorias',array('nodo_final' => 0),'=');
	foreach($lst_categorias as $i => $cat)
	{
		$cat = Sql_select('categorias',array('sub_de' => $cat['id_categoria']),'=');
		$lst_categorias[$i]['sub'] = $cat;
		
		foreach($cat as $c)
		{
			$lst_sub[$c['id_categoria']] = utf8_encode($lst_categorias[$i]['nombre']);
		}
	}
	$lst_colores = Sql_select('colores',0,'=');
?>
<?php include('includes/header.php'); ?>
	<script type="text/javascript">
		var img_erase = '';
		var image_sub = '';
		
		var lista_cats = new Array(<?php 
			echo '{';
			$cont=0;
			foreach($lst_sub as $k => $l)
			{
				echo '"'.$k.'":"'.$l.'"';
				if($cont<count($lst_sub)-1){ echo ','; }
				$cont+=1;
			}
			echo '}';
		?>);
		
		var editando = 0;
		var edit_codigo = '';
		var edit_materiales = '';
		var edit_carac = '';
		var edit_image = '';
		
		function ComprobarColores()
		{
			$('.agregar_color').attr('id',null);
			$('.agregar_color').css({'cursor':'hand','opacity' : 0.5});
			$('.color_save').css({'cursor':'hand','opacity':1});
			$('.color_end').each(function(){
				var id = $(this).find('#color').val();
				$('.color_save').each(function(){
					var id_b = $(this).find('#id_color').val();
					if(id == id_b)
					{
						$(this).find('.agregar_color').attr('id','bloq');
						$(this).find('.agregar_color').css({'cursor':'default'});
						$(this).css({'opacity':'0.5'});
					}
				});
			});
		}
		$(document).ready(function(){
			$('#select_categorias').change(function(){
				var valor = $(this).val();
				var nombre = $( "#select_categorias option:selected" ).text()
				if(valor != "-1")
				{
					$( "#select_categorias option:selected" ).attr('disabled','disabled');
					$($('#select_categorias').children()[0]).attr('selected','selected');
					var tmp = $('.categoria_example').clone().appendTo('.lista_cat');
					tmp.show();
					tmp.attr('class','color_campo cat_end');
					tmp.find('.nombre').html('('+lista_cats[0][valor]+')  '+nombre);
					tmp.find('#id_cat').val(valor);
					
					tmp.find('.borrar_cat').click(function(){
						$(this).parent().remove();
						$('#sin_cat').hide();
						if($('.cat_end').length<1)
						{
							$('#sin_cat').show();
						}
						$('#select_categorias').children().each(function(){
							if($(this).attr('value')==valor)
							{
								$(this).attr('disabled',null);
							}
						});
					});
					
					$('#sin_cat').hide();
				}
			});
			$('.agregar_color').click(function(){
					if($(this).attr('id') == 'bloq'){ return false; }
					var id_color = $(this).parent().find('#id_color').val();
					var color = $(this).parent().find('#color').val();
					var nombre = $(this).parent().find('#nombre').val();
					
					var tmp = $('.ejemplo_color').clone().appendTo('.list_color_real');
					tmp.attr('class','color_campo color_end');
					tmp.css({'display':'block'});
					
					tmp.find('.borrar_color').mouseover(function(){
						$(this).css({'opacity' : 1 });
					});
					tmp.find('.borrar_color').mouseout(function(){
						$(this).css({'opacity' : 0.5 });
					});
					tmp.find('.borrar_color').click(function(){
						$(this).parent().remove();
						if($('.color_end').length<1)
						{
							$('#sin_colores').show();
						}
						ComprobarColores()
					});
					tmp.find('.color_final').css({'background-color' : color });
					tmp.find('.nombre').html(nombre);
					
					tmp.find('#color').val(id_color);
					
					$('#sin_colores').hide();
					
					ComprobarColores();
				});
			
			$('.borrar_color').mouseover(function(){
				$(this).css({'opacity' : 1 });
			});
			$('.borrar_color').mouseout(function(){
				$(this).css({'opacity' : 0.5 });
			});
			$('.agregar_color').mouseover(function(){
				if($(this).attr('id') == 'bloq'){ return false; }
				$(this).css({'opacity' : 1 });
			});
			$('.agregar_color').mouseout(function(){
				if($(this).attr('id') == 'bloq'){ return false; }
				$(this).css({'opacity' : 0.5 });
			});
			$('.borrar_color').click(function(){
				$(this).parent().remove();
				if($('.color_end').length<1)
				{
					$('#sin_colores').show();
				}
				ComprobarColores();
			});
			<?php if($datos){ ?>
				var talles = ' <?php echo $datos['talles']; ?>';
				if(talles.search('nino;')>0){ $('#nino').attr('checked','checked'); }
				if(talles.search('mujer;')>0){ $('#mujer').attr('checked','checked'); }
				if(talles.search('hombre;')>0){ $('#hombre').attr('checked','checked'); }
				
				if($('.color_end').length>0)
				{
					$('#sin_colores').hide();
				}
			<?php } ?>
			
			$('#form').submit(function(){
				if($('#nombre').val() == ''){
					$('#error_producto').html('Campo Nombre es Requerido.');
					$('#error_producto').show();
					return false;
				}
				if($('#codigo').val() == ''){
					$('#error_producto').html('Campo Código es Requerido.');
					$('#error_producto').show();
					return false;
				}
				if($('#id_categoria').val() == ''){
					$('#error_producto').html('Selecciona una Categoría.');
					$('#error_producto').show();
					return false;
				}
				if($('#materiales').val() == ''){
					$('#error_producto').html('Campo Materiales es Requerido.');
					$('#error_producto').show();
					return false;
				}
				if($('#caracteristicas').val() == ''){
					$('#error_producto').html('Campo Características es Requerido.');
					$('#error_producto').show();
					return false;
				}
				
				var talles = '';
				$('.talles').each(function(){
					if($(this).attr('checked') == 'checked')
					{
						talles+= $(this).attr('id')+';';
					}
				});
				if(talles == ''){
					$('#error_producto').html('Debe seleccionar al menos un tipo de talles.');
					$('#error_producto').show();
					return false;
				}
				
				var colores = '';
				$('.color_end').each(function(){
					colores+= $(this).find('#color').val()+';';
				});
				
				$('#talles').val(talles);
				$('#colores').val(colores);
				$('#img_erase').val(img_erase);
				
				if(colores == ''){
					$('#error_producto').html('Ingrese al menos un color.');
					$('#error_producto').show();
					return false;
				}
				
				var subproductos = '';
				$('.subproducto_final').each(function(){
					var codigo = $(this).find('.cod').html();
					var materiales = $(this).find('.mat').html();
					var carac = $(this).find('.car').html();
					var image = $(this).find('#image_name').val();
					subproductos += codigo+']['+materiales+']['+carac+']['+image+'}{';
				});
				$('#subproductos').val(subproductos);
				
				var categorias_lista = '';
				$('.cat_end').each(function(){
					var id = $(this).find('#id_cat').val();
					categorias_lista+=id+',';
				});
				$('#categorias').val(categorias_lista);
			});
			
			$('.img_producto_erase').mouseover(function(){
				$(this).css({'opacity' : 1 });
			});
			$('.img_producto_erase').mouseout(function(){
				$(this).css({'opacity' : 0.5 });
			});
			$('.img_producto_erase').click(function(){
				var id = $(this).parent().find('#id').val();
				$(this).parent().remove();
				img_erase+= id+',';
			});
			$('#add_new_subproducto').click(function(){
				if(editando == 1){ return false;}
				$('#sin_subproductos').hide();
				$('.add_new_subproducto').show();
				$('.add_new_subproducto').find('#sub_codigo').val('');
				$('.add_new_subproducto').find('#sub_materiales').val('');
				$('.add_new_subproducto').find('#sub_caracteristicas').val('');
				
				$('#sub_image').show();
				$('#sub_image_img').hide();
				$('#sub_image_img').children().attr('src','');
				image_sub = '';
			});
			$('.sub_producto_x').mouseover(function(){
				$(this).css({'opacity' : 1 });
			});
			$('.sub_producto_x').mouseout(function(){
				$(this).css({'opacity' : 0.5 });
			});
			$('.sub_producto_x_cancel').click(function(){
				$('.add_new_subproducto').hide();
				if(editando == 0)
				{
					$('#sin_subproductos').show();
					if($('.subproducto_final').length>0)
					{
						$('#sin_subproductos').hide();
					}
				}
				else
				{
					$('.add_new_subproducto').find('#sub_codigo').val(edit_codigo);
					$('.add_new_subproducto').find('#sub_materiales').val(edit_materiales);
					$('.add_new_subproducto').find('#sub_caracteristicas').val(edit_carac);
					$('#sub_image_img').children().attr('src','../images/productos/'+edit_image);
					image_sub = edit_image;
					$('.sub_producto_x_accept').click();
					editando = 0;
				}
			});
			$('.sub_producto_x_accept').click(function(){
				if(editando == 1){ image_sub = edit_image; }
				if($('.add_new_subproducto').find('#sub_codigo').val() == '')
				{
					$('#error_subproductos').html('Es necesario un Código');
					$('#error_subproductos').show();
					return false;
				}
				if($('.add_new_subproducto').find('#sub_materiales').val() == '')
				{
					$('#error_subproductos').html('Complete campo Materiales');
					$('#error_subproductos').show();
					return false;
				}
				if($('.add_new_subproducto').find('#sub_caracteristicas').val() == '')
				{
					$('#error_subproductos').html('Complete campo Características');
					$('#error_subproductos').show();
					return false;
				}
				if(image_sub == '')
				{
					$('#error_subproductos').html('Imagen es requerida');
					$('#error_subproductos').show();
					return false;
				}
				
				var tmp = $('.ejemplo_subproducto').clone().insertAfter('.ejemplo_subproducto');
				tmp.attr('class','sub_producto subproducto_final');
				tmp.show();
				
				var codigo = $('.add_new_subproducto').find('#sub_codigo').val();
				var materiales = $('.add_new_subproducto').find('#sub_materiales').val();
				var caracteristicas = $('.add_new_subproducto').find('#sub_caracteristicas').val();
				var image = $('#sub_image_img').children().attr('src');
				
				tmp.find('.cod').html(codigo);
				tmp.find('.mat').html(materiales);
				tmp.find('.car').html(caracteristicas);
				tmp.find('#imagen_sub').children().attr('src',image);
				tmp.find('#image_name').val(image_sub);
				
				tmp.find('.sub_producto_x').mouseover(function(){
					$(this).css({'opacity' : 1 });
				});
				tmp.find('.sub_producto_x').mouseout(function(){
					$(this).css({'opacity' : 0.5 });
				});
				tmp.find('.btn_erase').click(function(){
					$(this).parent().parent().remove();
					$('#sin_subproductos').show();
					if($('.subproducto_final').length>0)
					{
						$('#sin_subproductos').hide();
					}
				});
				tmp.find('.btn_edit').click(function(){
					edit_codigo = $(this).parent().parent().find('.cod').html();
					edit_materiales = $(this).parent().parent().find('.mat').html();
					edit_carac = $(this).parent().parent().find('.car').html();
					edit_image = $(this).parent().parent().find('#image_name').val();
					
					$('.add_new_subproducto').find('#sub_codigo').val(edit_codigo);
					$('.add_new_subproducto').find('#sub_materiales').val(edit_materiales);
					$('.add_new_subproducto').find('#sub_caracteristicas').val(edit_carac);
					$('#sub_image_img').children().attr('src','../images/productos/'+edit_image);
					$('#sub_image_img').show();
					$('#sub_image').hide();
					
					$(this).parent().parent().remove();
					$('.add_new_subproducto').show();
					editando = 1;
					$(document).scrollTop($('#point_scroll').offset().top);
				});
				$('.add_new_subproducto').hide();
				$('#sin_subproductos').show();
				if($('.subproducto_final').length>0)
				{
					$('#sin_subproductos').hide();
				}
				editando = 0;
				$('#error_subproductos').hide();
			});
			$('#change_pic_sub').click(function(){
				$('#sub_image').show();
				$('#sub_image_img').hide();
				$('#sub_image_img').children().attr('src','');
				image_sub = '';
			});
				new AjaxUpload('#sub_image',{
					action: 'ajax/procesa_imagen_sub.php',
					name: 'img',
					onSubmit : function(file, ext){
						$('#sub_image').hide();
						$('#sub_image_load').show();
					},
					onComplete: function(file, response){
						$('#sub_image_load').hide();
						$('#sub_image_img').show();
						$('#sub_image_img').children().attr('src','../images/productos/'+response);
						image_sub = response;
					}
				});
				
				$('.btn_erase').click(function(){
					$(this).parent().parent().remove();
					$('#sin_subproductos').show();
					if($('.subproducto_final').length>0)
					{
						$('#sin_subproductos').hide();
					}
				});
				$('.btn_edit').click(function(){
					edit_codigo = $(this).parent().parent().find('.cod').html();
					edit_materiales = $(this).parent().parent().find('.mat').html();
					edit_carac = $(this).parent().parent().find('.car').html();
					edit_image = $(this).parent().parent().find('#image_name').val();
					
					$('.add_new_subproducto').find('#sub_codigo').val(edit_codigo);
					$('.add_new_subproducto').find('#sub_materiales').val(edit_materiales);
					$('.add_new_subproducto').find('#sub_caracteristicas').val(edit_carac);
					$('#sub_image_img').children().attr('src','../images/productos/'+edit_image);
					$('#sub_image_img').show();
					$('#sub_image').hide();
					
					$(this).parent().parent().remove();
					$('.add_new_subproducto').show();
					editando = 1;
					$(document).scrollTop($('#point_scroll').offset().top);
				});
				$('#sin_subproductos').show();
				if($('.subproducto_final').length>0)
				{
					$('#sin_subproductos').hide();
				}
				$('#new_color').ColorPicker({
					onSubmit : function(){
						$('.colorpicker').hide();
						$('.color_final_edit').css({'background-color' : '#'+$('.colorpicker_hex').children().val() });
						$('#color_dddd').val('#'+$('.colorpicker_hex').children().val());
					}
				});
				$('.boton_add_color_s').click(function(){
					var nombre = $('#nombre_new_color').val();
					var color = $('#color_dddd').val();
					if(nombre == ''){ $('#error_colores').show(); return false;}
					$('#error_colores').hide();
					$.ajax({
						type : 'post',
						data : { new_color :  nombre, color:color},
						url : 'add_productos.php',
						beforeSend : function(){
							$('.boton_add_color_s').html('Espere');
						}
					}).done(function(msg){
						var tmp = $(msg).appendTo('.colores_no_agregados');
						tmp.find('.agregar_color').mouseover(function(){
							if($(this).attr('id') == 'bloq'){ return false; }
							$(this).css({'opacity' : 1 });
						});
						tmp.find('.agregar_color').mouseout(function(){
							if($(this).attr('id') == 'bloq'){ return false; }
							$(this).css({'opacity' : 0.5 });
						});
						tmp.find('.agregar_color').click(function(){
							if($(this).attr('id') == 'bloq'){ return false; }
							var id_color = $(this).parent().find('#id_color').val();
							var color = $(this).parent().find('#color').val();
							var nombre = $(this).parent().find('#nombre').val();
							
							var tmp = $('.ejemplo_color').clone().appendTo('.list_color_real');
							tmp.attr('class','color_campo color_end');
							tmp.css({'display':'block'});
							
							tmp.find('.borrar_color').mouseover(function(){
								$(this).css({'opacity' : 1 });
							});
							tmp.find('.borrar_color').mouseout(function(){
								$(this).css({'opacity' : 0.5 });
							});
							tmp.find('.borrar_color').click(function(){
								$(this).parent().remove();
								if($('.color_end').length<1)
								{
									$('#sin_colores').show();
								}
								ComprobarColores()
							});
							tmp.find('.color_final').css({'background-color' : color });
							tmp.find('.nombre').html(nombre);
							
							tmp.find('#color').val(id_color);
							
							$('#sin_colores').hide();
							
							ComprobarColores();
						});
						$('#new_color_f').hide();
						$('.boton_add_color_s').html('Agregar Color');
					});
				});
			ComprobarColores();
			$('.borrar_cat').click(function(){
				$(this).parent().remove();
				$('#sin_cat').hide();
				if($('.cat_end').length<1)
				{
					$('#sin_cat').show();
				}
				$('#select_categorias').children().each(function(){
					if($(this).attr('value')==valor)
					{		
						$(this).attr('disabled',null);
					}
				});
			});
			$('#sin_cat').hide();
			if($('.cat_end').length<1)
			{
				$('#sin_cat').show();
			}
		});
	</script>
	<h4 class="alert_error" id="error_producto" style="display:none;">Debe seleccionar un elemento.</h4>
	<form action="" method="post" id="form" enctype="multipart/form-data">
		<input type="hidden" id="id" name="id" value="<?php echo $get['id']; ?>">
		<input type="hidden" id="talles" name="talles" value="<?php echo $datos['talles']; ?>">
		<input type="hidden" id="colores" name="colores" value="<?php echo $datos['colores']; ?>">
		<input type="hidden" id="color_dddd" value="#ffffff">
		<input type="hidden" id="img_erase" name="img_erase">
		<input type="hidden" id="subproductos" name="subproductos">
		<input type="hidden" id="categorias" name="categorias">
		<article class="module width_full">
			<header><h3>Datos de Producto</h3></header>
				<div class="module_content">
						<fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Nombre:</label>
							<input type="text" id="nombre" name="nombre" style="width:92%;" value="<?php echo $datos['nombre']; ?>">
						</fieldset>
						<fieldset style="width:48%; float:left; "> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Código:</label>
							<input type="text" id="codigo" name="codigo" style="width:92%;" value="<?php echo $datos['codigo']; ?>">
						</fieldset><div class="clear"></div>
						<fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Categorías:</label>
							<select id="select_categorias" style="width:92%;">
								<option value="-1">Seleccione una o varias categorías a agregar</option>
								<?php foreach($lst_categorias as $cat){ ?>
									<option value="-1" disabled style="background-color:#ccc;"><?php echo $cat['nombre']; ?></option>
									<?php foreach($cat['sub'] as $d){ ?>
										<option <?php if(strpos(' '.$datos['id_subcategoria'],$d['id_categoria'].',')){ echo 'disabled'; } ?> value="<?php echo $d['id_categoria']; ?>"><?php echo $d['nombre']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</fieldset>
						<fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Categorías Agregadas:</label>
							<div class="lista_cat" style="width:100%; margin-top:30px;">
									<div class="color_campo categoria_example" style="display:none;">
										<input type="hidden" id="id_cat" value="<?php echo $color['id_color']; ?>">
										<div class="color_nombre nombre">Golf(Chomba)</div>
										<div class="icn_logout borrar_cat">
											<img width="10" src="images/icn_logout.png">
										</div>
									</div>
									<?php foreach($lst_categorias_add as $gg){ 
											if($gg==''){break;}
											$categoria = Sql_select('categorias',array('id_categoria' => $gg),'=');
											$categoria = $categoria[0];
											
											$parent = Sql_select('categorias',array('id_categoria' => $categoria['sub_de']),'=');
											$parent = $parent[0];
									?>
										<div class="color_campo cat_end">
											<input type="hidden" id="id_cat" value="<?php echo $gg; ?>">
											<div class="color_nombre nombre">(<?php echo $parent['nombre']; ?>)  <?php echo $categoria['nombre']; ?></div>
											<div class="icn_logout borrar_cat">
												<img width="10" src="images/icn_logout.png">
											</div>
										</div>
									<?php } ?>
							</div>
							<span id="sin_cat">&nbsp;&nbsp;&nbsp;Sin Categorías Agregadas</span>
						</fieldset>
						<fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label style="width:100%;">Talles:</label>
							<div style="width:100%; font-size:12px;">
								<input class="talles" id="nino" type="checkbox">Niño
								<input class="talles" id="mujer" type="checkbox">Mujer
								<input class="talles" id="hombre" type="checkbox">Hombre
							</div>
						</fieldset>
						<fieldset style="width:48%; float:left; "> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Importancia:</label>
							<select name="importancia" style="width:92%;">
								<option <?php if($datos['importancia'] == 0){ echo 'selected="selected"'; } ?> value="0">0 ( Lo más bajo )</option>
								<option <?php if($datos['importancia'] == 1){ echo 'selected="selected"'; } ?> value="1">1</option>
								<option <?php if($datos['importancia'] == 2){ echo 'selected="selected"'; } ?> value="2">2</option>
								<option <?php if($datos['importancia'] == 3){ echo 'selected="selected"'; } ?> value="3">3</option>
								<option <?php if($datos['importancia'] == 4){ echo 'selected="selected"'; } ?> value="4">4</option>
								<option <?php if($datos['importancia'] == 5){ echo 'selected="selected"'; } ?> value="5">5 ( Lo más alto )</option>
							</select>
						</fieldset><div class="clear"></div>
						<fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
							<label style="width:100%;">Colores Guardados: <a href="javascript:void(0);" onclick="$('#new_color_f').show()">Nuevo Color</a></label>
							<div class="list_color colores_no_agregados">
								<?php if(is_array($lst_colores)){
										foreach($lst_colores as $color){
								?>
									<div class="color_campo color_save">
										<input type="hidden" id="id_color" value="<?php echo $color['id_color']; ?>">
										<input type="hidden" id="color" value="<?php echo $color['color']; ?>">
										<input type="hidden" id="nombre" value="<?php echo $color['nombre']; ?>">
										<div class="color_final" style="background-color:<?php echo $color['color']; ?>"></div>
										<div class="color_nombre"><?php echo $color['nombre']; ?></div>
										<div class="icn_logout agregar_color" style="font-size: 20px; line-height: 13px;">
											+
										</div>
									</div>
								<?php }} ?>
							</div>
							<?php if(!is_array($lst_colores)){ ?>
								<span>&nbsp;&nbsp;&nbsp;Sin Colores Guardados</span>
							<?php } ?>
						</fieldset><div class="clear"></div>
						<h4 class="alert_error" id="error_colores" style="display:none;">Debe ingresar un nombre.</h4>
						<div id="new_color_f" style="display:none; width:100%; background: #E2E2E2; border: 1px solid #ccc; border-radius:5px;">
							<div style="width:90%; margin:auto">
								<fieldset style="width:48%; float:left; margin-right:3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
									<label>Nombre:</label>
									<input type="text" id="nombre_new_color" style="width:92%;" value="">
								</fieldset>
								<fieldset style="width:48%; float:left; "> <!-- to make two field float next to one another, adjust values accordingly -->
									<label>Color: <a href="javascript:void(0);" id="new_color">Cambiar Color</a></label>
									<div class="color_final_edit" style="background-color:#fff"></div>
								</fieldset><div class="clear"></div>
								<div class="boton_add_color_s">Agregar Color</div>
							</div>
						</div>
						<fieldset> <!-- to make two field float next to one another, adjust values accordingly -->
							<label style="width:100%;">Colores:</label>
							<div class="list_color list_color_real">
								<div class="color_campo ejemplo_color" style="display:none;">
									<input type="hidden" id="color">
									<div class="color_final"></div>
									<div class="color_nombre nombre"></div>
									<div class="icn_logout borrar_color">
										<img width="10" src="images/icn_logout.png">
									</div>
								</div>
								<?php if($datos){
										$datos['colores'] = explode(';',$datos['colores']);
										foreach($datos['colores'] as $color){
											if($color == ''){break;}
											$color = Sql_select('colores',array('id_color' => $color),'=');
											$color = $color[0];
								?>
									<div class="color_campo color_end">
										<input type="hidden" id="color" value="<?php echo $color['id_color']; ?>">
										<div class="color_final" style="background-color:<?php echo $color['color']; ?>"></div>
										<div class="color_nombre nombre"><?php echo $color['nombre']; ?></div>
										<div class="icn_logout borrar_color">
											<img width="10" src="images/icn_logout.png">
										</div>
									</div>
								<?php }} ?>
							</div>
							<span id="sin_colores">&nbsp;&nbsp;&nbsp;Sin Colores Agregados</span>
						</fieldset><div class="clear"></div>
						<fieldset>
							<label>Materiales:</label>
							<textarea id="materiales" name="materiales" rows="5"><?php echo $datos['materiales']; ?></textarea>
						</fieldset>
						<fieldset>
							<label>Características:</label>
							<textarea id="caracteristicas" name="caracteristicas" rows="5"><?php echo $datos['caracteristicas']; ?></textarea>
						</fieldset>
						<fieldset > <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Imágenes:</label>
							<input type="file" name="images[]" style="width:92%; margin-left:2%;" multiple><br>
							<?php if(is_array($lst_imagenes)){ ?>
								<?php foreach($lst_imagenes as $img){
									$tamano = getimagesize('../images/productos/'.$img['nombre']);
									$width = 64*$tamano[0]/$tamano[1];
								?>
									<div class="img_producto">
										<input type="hidden" id="id" value="<?php echo $img['id_image']; ?>">
										<div class="img_producto_img">
											<img width="<?php echo $width; ?>" height="64" src="../images/productos/<?php echo $img['nombre']; ?>">
										</div>
										<div class="img_producto_erase">
											<img width="16" src="images/icn_logout.png">
										</div>
									</div>
								<?php } ?>
							<?php } ?>
						</fieldset>
						<h4 class="alert_error" id="error_subproductos" style="display:none;">Debe seleccionar un elemento.</h4>
						<fieldset id="point_scroll"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label style="width:100%;" >SubProductos: <a href="javascript:void(0);" id="add_new_subproducto">Agregar SubProducto</a></label><br>
							<div class="sub_producto add_new_subproducto" style="display:none;">
								<div class="sub_producto_a">
									<fieldset style="width:48%; float:left; margin-right: 1%; margin-left:1%;"> <!-- to make two field float next to one another, adjust values accordingly -->
										<label>Codigo:</label>
										<input type="text" id="sub_codigo" style="width:92%; margin-left:4px;" value="">
									</fieldset>
									<fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
										<label style="width: 52px;" >Imagen:</label>
										<input type="file" id="sub_image" style="width:92%; margin-left:4px;">
										<div id="sub_image_load" style="width:100%; margin-top: 33px; margin-left: 11px; display:none;">Cargando...</div>
										<div id="sub_image_img" style="width:100%; display:none;"><img height="46"><a href="javascript:void(0);" id="change_pic_sub"> Cambiar Foto</a></div>
									</fieldset><div class="clear"></div>
									<fieldset style="width:48%; float:left; margin-right: 1%; margin-left:1%;"> <!-- to make two field float next to one another, adjust values accordingly -->
										<label>Materiales:</label>
										<textarea id="sub_materiales" style="width: 90%; height: 100px; margin-left:4px;"></textarea>
									</fieldset>
									<fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
										<label>Características:</label>
										<textarea id="sub_caracteristicas" style="width: 90%; height: 100px; margin-left:4px;"></textarea>
									</fieldset><div class="clear"></div>
								</div>
								<div class="sub_producto_b">
									<img width="20" class="sub_producto_x sub_producto_x_accept" src="images/icn_alert_success.png"><br>
									<img width="20" class="sub_producto_x sub_producto_x_cancel" src="images/icn_logout.png">
								</div>
							</div>
							<div class="sub_producto ejemplo_subproducto" style="display:none;">
								<input type="hidden" id="image_name">
								<div class="sub_producto_a">
									<fieldset style="width:48%; float:left; margin-right: 1%; margin-left:1%;"> <!-- to make two field float next to one another, adjust values accordingly -->
										<label>Codigo:</label>
										<div class="text_sub cod">XXXX</div>
									</fieldset>
									<fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
										<label style="width: 52px;">Imagen:</label>
										<div id="imagen_sub" style="width:100%;"><img height="46"></div>
									</fieldset><div class="clear"></div>
									<fieldset style="width:48%; float:left; margin-right: 1%; margin-left:1%;"> <!-- to make two field float next to one another, adjust values accordingly -->
										<label>Materiales:</label>
										<div class="desc_sub mat"></div>
									</fieldset>
									<fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
										<label>Características:</label>
										<div class="desc_sub car"></div>
									</fieldset><div class="clear"></div>
								</div>
								<div class="sub_producto_b">
									<img width="20" class="sub_producto_x btn_edit" src="images/icn_edit.png">
									<img width="20" class="sub_producto_x btn_erase" src="images/icn_logout.png">
								</div>
							</div>
							<?php if(is_array($lst_subproductos)){ ?>
								<?php foreach($lst_subproductos as $p){ 
										if($p == ''){break;}
										$p = explode('][',$p);
								?>
									<div class="sub_producto subproducto_final">
										<input type="hidden" id="image_name" value="<?php echo $p[3]; ?>">
										<div class="sub_producto_a">
											<fieldset style="width:48%; float:left; margin-right: 1%; margin-left:1%;"> <!-- to make two field float next to one another, adjust values accordingly -->
												<label>Codigo:</label>
												<div class="text_sub cod"><?php echo $p[0]; ?></div>
											</fieldset>
											<fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
												<label style="width: 52px;">Imagen:</label>
												<div id="imagen_sub" style="width:100%;"><img src="../images/productos/<?php echo $p[3]; ?>"height="46"></div>
											</fieldset><div class="clear"></div>
											<fieldset style="width:48%; float:left; margin-right: 1%; margin-left:1%;"> <!-- to make two field float next to one another, adjust values accordingly -->
												<label>Materiales:</label>
												<div class="desc_sub mat"><?php echo $p[1]; ?></div>
											</fieldset>
											<fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
												<label>Características:</label>
												<div class="desc_sub car"><?php echo $p[2]; ?></div>
											</fieldset><div class="clear"></div>
										</div>
										<div class="sub_producto_b">
											<img width="20" class="sub_producto_x btn_edit" src="images/icn_edit.png">
											<img width="20" class="sub_producto_x btn_erase" src="images/icn_logout.png">
										</div>
									</div>
								<?php } ?>
							<?php } ?>
							<span id="sin_subproductos">&nbsp;&nbsp;&nbsp;Sin SubProductos Agregados</span>
						</fieldset>
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