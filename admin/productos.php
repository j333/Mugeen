<?php
	include('../mysql/mysql.php');
	include('includes/funciones.php');
	
	$get = LimpiarGET();
	$post = LimpiarPOST();
	
	ControlAdmin();
	
	$pag_actual = 'Productos';
	
	if(isset($post['destacar']))
	{
		$producto = Sql_select('productos',array('id_producto' => $post['destacar']),'=');
		$producto = $producto[0];
		if($producto['destacado'])
		{
			Sql_update('productos',array('destacado' => 0),array('id_producto' => $post['destacar']));
			echo '0';
			exit;
		}
		Sql_update('productos',array('destacado' => 1),array('id_producto' => $post['destacar']));
		echo '1';
		exit;
	}
	if(isset($get['ids']))
	{
		$ids = explode(',',$get['ids']);
		foreach($ids as $id)
		{
			if($id == ''){ break; }
			Sql_delete('productos',array('id_producto' => $id));
		}
		header("location: productos.php?exito=borrar");
		exit;
	}
	
	//LISTA
	
	$tabla = 'productos';
	$pagina = 'productos.php';
	$page = (isset($get['page'])) ? $get['page'] : 0;
	$search = (isset($get['search'])) ? $get['search'] : '';
	$limite['inicio'] = $page*20;
	$limite['final'] = ($page+1)*20;
	$orden['campo'] = 'nombre';
	$orden['orden'] = 'DESC';
	if($search)
	{
		if($search = 'p_destacados')
		{
			$lst = Sql_select_especial($tabla,array('destacado' => 1),'LIKE',0,$orden,'OR');
		}
		else
		{
			$lst = Sql_select_especial($tabla,array('nombre' => $search),'LIKE',0,$orden,'OR');
		}
		$max_reg = 0;
	}
	else
	{
		$lst = Sql_select_especial($tabla,0,'=',$limite,$orden,'AND');
		$max_reg = count(Sql_select($tabla,0,'='));
	}
	$max_pag = ceil($max_reg/20);
?>
<?php include('includes/header.php'); ?>
		<script type="text/javascript">
			function Destacar(n)
			{
				var este = $(n).parent().parent();
				$.ajax({
					type : 'POST',
					data : { destacar : este.find('#id').val() },
					url : 'productos.php',
					beforeSend : function(){
						este.find('#star').attr('src','images/loading_ajax.gif');
					}
				}).done(function(msg){
					if(msg == '0')
					{
						este.find('#star').attr('src','images/icon_star.png');
					}
					if(msg == '1')
					{
						este.find('#star').attr('src','images/icon_start.png');
					}
				});
			}
			$(document).ready(function(){
				$('#buscar_tabla').keyup(function(e){
					if(e.keyCode == 13)
					{
						top.location = '<?php echo $pagina; ?>?search='+encodeURI($(this).val());
					}
				});
				$('#acciones_tabla').change(function(){
					var accion = $(this).val();
					var elements = '';
					$('.checkbox_list').each(function(){
						if($(this).attr('checked') == 'checked')
						{
							elements += $(this).parent().parent().find('#id').val()+',';
						}
					});
					if(elements == '')
					{
						$('.error_acciones').show();
						$($(this).children()[0]).attr('selected','selected');
						return false;
					}
					if(accion = 'borrar')
					{
						top.location = 'productos.php?ids='+elements;
					}
				});
			});
		</script>
		<?php if(isset($get['exito'])){ ?>
			<?php if($get['exito']=='borrar'){ ?>
				<h4 class="alert_success">Borrado/s Exitosamente.</h4>
			<?php } ?>
			<?php if($get['exito']=='insertar'){ ?>
				<h4 class="alert_success">Insertado Exitosamente.</h4>
			<?php } ?>
			<?php if($get['exito']=='actualizar'){ ?>
				<h4 class="alert_success">Actualizado Exitosamente.</h4>
			<?php } ?>
		<?php } ?>
		<article class="module width_full">
			<header>
				<h3 class="tabs_involved">Productos</h3>
				<ul class="tabs">
					<?php if($search == ''){ ?>
						<?php if($page>0){ ?>
							<li><a href="<?php echo $pagina; ?>?page=<?php echo $page-1; ?>&search=<?php echo $search; ?>"><</a></li>
						<?php } ?>
						<?php for($i=0;$i<5;$i+=1){ 
								$d = $page+$i;
						?>		
							<?php if($d == $page){?>
								<li class="active"><a href="#"><?php echo (($d+1)<10) ? '0'.($d+1) : ($d+1); ?></a></li>
							<?php continue; } ?>
							<?php if($d > $max_pag-1){?>
								<li><a>-</a></li>
							<?php continue; } ?>
							<?php if($d != $page){?>
								<li><a href="<?php echo $pagina; ?>?page=<?php echo $d; ?>&search=<?php echo $search; ?>"><?php echo (($d+1)<10) ? '0'.($d+1) : ($d+1); ?></a></li>
							<?php } ?>
						<?php } ?>
						<?php if($page<$max_pag-1){ ?>
							<li><a href="<?php echo $pagina; ?>?page=<?php echo $page+1; ?>&search=<?php echo $search; ?>">></a></li>
						<?php } ?>
					<?php }else{ ?>
						<li class="active" ><a>Resultados de '<?php echo ($search == 'p_destacados') ? 'Destacados' : $search; ?>'</a></li>
					<?php } ?>
				</ul>
			</header>
			<div class="tab_container">
				<table class="tablesorter" cellspacing="0"> 
				<thead> 
					<tr> 
						<th><input type="checkbox" id="checkbox_gral"></th> 
						<th>ID</th> 
						<th>Nombre</th> 
						<th>Categoría</th> 
						<th width="30" >
							<a href="productos.php?search=p_destacados">
								<img src="images/icon_start.png">
							</a>
						</th> 
						<th>Código</th> 
						<th style="text-align:center;" >Acciones</th> 
					</tr> 
				</thead> 
				<tbody> 
					<?php if(is_array($lst)){ ?>
						<?php foreach($lst as $item){ 
								$categoria = Sql_select('categorias',array('id_categoria' => $item['id_subcategoria']),'=');
								$categoria = $categoria[0];
						?>
							<tr>
								<input type="hidden" id="id" value="<?php echo $item['id_producto']; ?>">
								<td><input type="checkbox" class="checkbox_list" ></td> 
								<td><?php echo $item['id_producto']; ?></td> 
								<td><?php echo $item['nombre']; ?></td> 
								<td><?php echo $categoria['nombre']; ?></td>
								<td width="30" >
									<?php if(!$item['destacado']){ ?>
										<a href="javascript:void(0);" onclick="Destacar(this);">
											<img id="star" src="images/icon_star.png">
										</a>
									<?php }else{ ?>
										<a href="javascript:void(0);" onclick="Destacar(this);">
											<img id="star" src="images/icon_start.png">
										</a>
									<?php } ?>
								</td> 								
								<td><?php echo $item['codigo']; ?></td>
								<td style="text-align:center;">
									<a href="add_productos.php?id=<?php echo $item['id_producto']; ?>">
										<img src="images/icn_edit.png" title="Editar">
									</a>
									<?php if($_SESSION['administrador']['rol'] == 'god'){ ?>
									<a href="productos.php?ids=<?php echo $item['id_producto']; ?>">
										<img style="margin-left: 10px;" src="images/icn_trash.png" title="Editar">
									</a>
									<?php } ?>
								</td> 
							</tr> 
						<?php } ?>
					<?php } ?>
				</tbody> 
				</table>
				<?php if(!is_array($lst)){ ?>
					<h4 style="text-align:center;" >No existen productos</h4>
				<?php } ?>
			</div><!-- end of .tab_container -->
		</article>
		<h4 class="alert_error error_acciones" style="display:none;">Debe seleccionar un elemento.</h4>
		<article class="module width_full">
			<header><h3>Panel</h3></header>
			<div class="module_content">
				<fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
					<label>Buscar</label>
					<input type="text" style="width:92%;" id="buscar_tabla" value="<?php echo ($search == 'p_destacados') ? '' : $search; ?>">
				</fieldset>
				<?php if($_SESSION['administrador']['rol'] == 'god'){ ?>
				<fieldset style="width:48%; float:left;"> <!-- to make two field float next to one another, adjust values accordingly -->
					<label>Acciones</label>
					<select style="width:92%;" id="acciones_tabla">
						<option value="-1">Seleccione...</option>
						<option value="borrar">Borrar</option>
					</select>
				</fieldset>
				<?php } ?>
				<div class="clear"></div>
			</div>
		</article>
		<div class="spacer"></div>
<?php include('includes/footer.php'); ?>