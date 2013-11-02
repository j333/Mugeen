<?php
	include('../mysql/mysql.php');
	include('includes/funciones.php');
	
	$get = LimpiarGET();
	$post = LimpiarPOST();
	
	ControlAdmin();
	
	$pag_actual = 'Colores';
	
	if(isset($get['ids']))
	{
		$ids = explode(',',$get['ids']);
		foreach($ids as $id)
		{
			if($id == ''){ break; }
			Sql_delete('colores',array('id_color' => $id));
		}
		header("location: colores.php?exito=borrar");
		exit;
	}
	
	//LISTA
	
	$tabla = 'colores';
	$pagina = 'colores.php';
	$page = (isset($get['page'])) ? $get['page'] : 0;
	$search = (isset($get['search'])) ? $get['search'] : '';
	$limite['inicio'] = $page*20;
	$limite['final'] = ($page+1)*20;
	$orden['campo'] = 'nombre';
	$orden['orden'] = 'ASC';
	if($search)
	{
		$lst = Sql_select_especial($tabla,array('nombre' => $search),'LIKE',0,$orden,'OR');
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
						top.location = 'colores.php?ids='+elements;
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
				<h3 class="tabs_involved">Colores</h3>
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
						<li class="active" ><a>Resultados de '<?php echo $search; ?>'</a></li>
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
						<th>Color</th> 
						<th style="text-align:center;" >Acciones</th> 
					</tr> 
				</thead> 
				<tbody> 
					<?php if(is_array($lst)){ ?>
						<?php foreach($lst as $item){ ?>
							<tr>
								<input type="hidden" id="id" value="<?php echo $item['id_color']; ?>">
								<td><input type="checkbox" class="checkbox_list" ></td> 
								<td><?php echo $item['id_color']; ?></td> 
								<td><?php echo $item['nombre']; ?></td> 
								<td><div class="color_final" style="background-color:<?php echo $item['color']; ?>"></div></td> 
								<td style="text-align:center;">
									<a href="add_colores.php?id=<?php echo $item['id_color']; ?>">
										<img src="images/icn_edit.png" title="Editar">
									</a>
									<?php if($_SESSION['administrador']['rol'] == 'god'){ ?>
									<a href="colores.php?ids=<?php echo $item['id_color']; ?>">
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
					<h4 style="text-align:center;" >No existen colores</h4>
				<?php } ?>
			</div><!-- end of .tab_container -->
		</article>
		<h4 class="alert_error error_acciones" style="display:none;">Debe seleccionar un elemento.</h4>
		<article class="module width_full">
			<header><h3>Panel</h3></header>
			<div class="module_content">
				<fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
					<label>Buscar</label>
					<input type="text" style="width:92%;" id="buscar_tabla" value="<?php echo $search; ?>">
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