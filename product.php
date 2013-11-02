<?php
	include('mysql/mysql.php');
	$get = LimpiarGET();
	$post = LimpiarPOST();

	if(isset($post['add_producto']))
	{	
		$val['id_carrito'] = '';
		$val['sesion'] = $_SESSION['sesion_carrito'];
		$val['id_item'] = $post['add_producto'];
		$val['cantidad'] = 1;
		$val['talle'] = $post['talle'];
		$val['color'] = $post['color'];
		$val['sub'] = $post['sub'];
		Sql_insertar('carrito',$val);
		exit;
	}
	
	$producto_int = Sql_select('productos',array('id_producto' => $get['id_producto']),'=');
	$producto_int = $producto_int[0];
	
	$subcategoria_int = Sql_select('categorias',array('id_categoria' => $producto_int['id_subcategoria']),'=');
	$subcategoria_int = $subcategoria_int[0];
	
	$categoria_int = Sql_select('categorias',array('id_categoria' => $subcategoria_int['sub_de']),'=');
	$categoria_int = $categoria_int[0];
	
	$imagenes = Sql_select('imagenes_productos',array('id_producto' => $producto_int['id_producto']),'=');
	
	$colores_b = explode(';',$producto_int['colores']);
	foreach($colores_b as $col)
	{
		if($col == ''){ break; }
		$col = Sql_select('colores',array('id_color' => $col['id_color']),'=');
		$colores[] = $col[0];
	}
	
	$sub_productos = explode('}{',$producto_int['subproductos']);
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <?php include('includes/head.php'); ?>
</head>

<body>
    <!--[if lt IE 7]>
        <p class="chromeframe">Usted esta usando un navegador <strong>obsoleto</strong>. Por favor <a href="http://browsehappy.com/">actualice su navegador</a> o <a href="http://www.google.com/chromeframe/?redirect=true">active Google Chrome Frame</a> para mejorar su experiencia.</p>
    <![endif]-->
     <?php include('includes/header.php'); ?>

    <div class="container">
        <div class="row">
            <div class="span12">
                <ul class="breadcrumb">
                    <li><a href="home/">Home</a> <span class="divider">/</span></li>
                    <li><a href="seccion/<?php echo $categoria_int['id_categoria']; ?>/"><?php echo $categoria_int['nombre']; ?></a> <span class="divider">/</span></li>
                    <li><a href="subseccion/<?php echo $subcategoria_int['id_categoria']; ?>/"><?php echo $subcategoria_int['nombre']; ?></a> <span class="divider">/</span></li>
                    <li class="active"><?php echo $producto_int['nombre']; ?></li>
                </ul>
            </div>
            <div class="span6 title">
                <h1><?php echo $producto_int['nombre']; ?></h1>
                <div class="codigo"><abbr title="Código">Cód.</abbr> <?php echo $producto_int['codigo']; ?><span class="sub_codigo"></span></div>
            </div>
            <div class="span6 product">
                <img id="zoom" src='images/productos/<?php echo $imagenes[0]['nombre']; ?>' data-zoom-image="images/productos/<?php echo $imagenes[0]['nombre']; ?>"/>
                <div id="gallery">
					<?php foreach($imagenes as $n => $img){ ?>
						<?php if($n==0){ ?>
							<a onclick="SubProducto(-1)" href="#" data-image="images/productos/<?php echo $img['nombre']; ?>" data-zoom-image="images/productos/<?php echo $img['nombre']; ?>" class="active">
								<img id="img_01" src="images/productos/<?php echo $img['nombre']; ?>" />
							</a>
						<?php }else{ ?>
							<a onclick="SubProducto(-1)" href="#" data-image="images/productos/<?php echo $img['nombre']; ?>" data-zoom-image="images/productos/<?php echo $img['nombre']; ?>">
								<img id="img_01" src="images/productos/<?php echo $img['nombre']; ?>" />
							</a>
						<?php } ?>
					<?php } ?>
					<?php $cont=0; foreach($sub_productos as $sub){ 
								if($sub == ''){break;}
								$sub = explode('][',$sub);
					?>
						<a onclick="SubProducto(<?php echo $cont; ?>)" href="#" data-image="images/productos/<?php echo $sub[3]; ?>" data-zoom-image="images/productos/<?php echo $sub[3]; ?>">
							<img id="img_01" src="images/productos/<?php echo $sub[3]; ?>" />
						</a>
					<?php $cont+=1; } ?>
                </div>
            </div><!--/span-->
            <div class="span6">
                Colores:
                <div class="option colors">
					<?php foreach($colores as $n => $color){ ?>
						<a href="javascript:void(0);" class="color_final" <?php if($n == 0){ echo 'id="active"'; } ?>>
							<input type="hidden" id="color" value="<?php echo $color['color']; ?>">
							<div class="color <?php if($n == 0){ echo 'active'; } ?>" style="background:<?php echo $color['color']; ?>"></div>
						</a>
					<?php } ?>
                </div>
                <div class="option">Talles:
                    <select name="talle" class="span2" id="talle">
                        <option value="XS" title="XS"><abbr title="Extra Chico">XS</abbr></option>
                        <option value="S" title="S">S</option>
                        <option selected value="M" title="M">M</option>
                        <option value="L" title="L">L</option>
                        <option value="XL" title="XL">XL</option>
                        <option value="XXL" title="XXL">XXL</option>
                    </select>
                </div>
				<span id="compra_-1" class="boton_comprar">
					<?php if(!Sql_select('carrito',array('sesion' => $_SESSION['sesion_carrito'],'id_item' => $producto_int['id_producto']),'=')){ ?>
						<p class="btn_compra_-1"><button class="btn btn-primary" type="button" onclick="Comprar(-1)"><i class="icon-plus"></i> Agregar a pedidos</button></p>
						<div class="alert alert-success exito_compra_-1" style="display:none;">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong><?php echo $producto_int['nombre']; ?></strong> ha sido agregado a la lista de productos.
						</div>
					<?php }else{ ?>
						<div class="alert alert-success">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong><?php echo $producto_int['nombre']; ?></strong> ha sido agregado a la lista de productos.
						</div>
					<?php } ?>
				</span>
				<?php $cont=0; foreach($sub_productos as $sub){ 
								if($sub == ''){break;}
								$sub = explode('][',$sub);
				?>
					<span id="compra_<?php echo $cont; ?>" class="boton_comprar" style="display:none;">
						<?php if(!Sql_select('carrito',array('sesion' => $_SESSION['sesion_carrito'],'id_item' => $producto_int['id_producto'],'sub' => $sub[0]),'=')){ ?>
							<p class="btn_compra_<?php echo $cont; ?>"><button class="btn btn-primary" type="button" onclick="Comprar(<?php echo $cont; ?>)"><i class="icon-plus"></i> Agregar a pedidos</button></p>
							<div class="alert alert-success exito_compra_<?php echo $cont; ?>" style="display:none;">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong><?php echo $producto_int['nombre']; ?> Sub <?php echo $sub[0]; ?></strong> ha sido agregado a la lista de productos.
							</div>
						<?php }else{ ?>
							<div class="alert alert-success">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<strong><?php echo $producto_int['nombre']; ?> Sub <?php echo $sub[0]; ?></strong> ha sido agregado a la lista de productos.
							</div>
						<?php } ?>
					</span>
				<?php } ?>
                <!-- info -->
                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#pane1" data-toggle="tab">Descripción</a></li>
                        <li><a href="#pane2" data-toggle="tab">Talles</a></li>
                        <li><a href="#pane3" data-toggle="tab">Comprar</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="pane1" class="tab-pane active">
                            <h3>Material</h3>
                            <p id="materiales_reales" ><?php echo $producto_int['materiales']; ?></p>
                            <p id="materiales_sub" style="display:none;"></p>
                            <h3>Características</h3>
                            <p id="caracteristicas_reales"><?php echo $producto_int['caracteristicas']; ?></p>
                            <p id="caracteristicas_sub" style="display:none;"></p>
                        </div>
                        <div id="pane2" class="tab-pane">
                            <h3>¿Cómo determinar tu talle?</h3>
                            <p>En el siguiente PDF usted encontrará toda la información necesaria para que pueda conocer su talle.</p>    
                            <a href="../determinar_talle.pdf"><i class="icon-file" target="_blank"></i>Descargar</a>
                        </div>
                        <div id="pane3" class="tab-pane">
                            <h3>¿Cómo comprar?</h3>
                            <p><strong>Horario de atención en planta:</strong> Lunes a Viernes de 8 a 18 hs
                            <p>Haga su consulta completando el pedido de compra o llamando al (054 261) 4224470 - 154151188 | Nextel 561 * 909</p>
                            <p><strong>Mail:</strong> contacto@mugeen.com.ar</p>
                            <p><strong>Usted Puede consultar:</strong> Pecios, stock y realizar pedidos.</p>
                            <p>Solicite el producto que requiere específicamente.</p>
                            <p>Por favor envíenos su inquietud o diseño y a la brevedad nos comunicaremos con usted.</p>
                        </div>
                    </div><!-- /.tab-content -->
                </div><!-- /.tabbable -->
                <!-- /info -->
            </div><!--/span-->
        </div>
    </div>

   <?php include('includes/footer.php'); ?>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.color_final').click(function(){
				$('.color_final').children().attr('class','color');
				$('.color_final').attr('id',null);
				$(this).attr('id','active');
				$(this).children().attr('class','color active');
			});
		});
	</script>
    <script src="js/vendor/bootstrap.min.js"></script>

    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>
    <script src="js/jquery.elevatezoom.js"></script>

    <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>

    <script>
        var _gaq=[['_setAccount','UA-19883854-4'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
	<script type="text/javascript">
		var sub_productos = new Array(
			<?php $cont = 0; foreach($sub_productos as $sub){ 
					if($sub == ''){break;}
					$sub = explode('][',$sub);
					echo '{';
						echo 'codigo:"'.$sub[0].'",';
						echo 'materiales:"'.$sub[1].'",';
						echo 'caracteristicas:"'.$sub[2].'",';
					echo '}';
					if($cont>count($sub_productos)-1){ echo ',';}
					$cont+=1;
			?>
			<?php } ?>
		);
		var actual_sub = '';
		function SubProducto(n)
		{
			$('.boton_comprar').hide();
			$('#compra_'+n).show();
			if(n == -1)
			{
				$('#materiales_sub').stop(true).animate({'opacity': 0},200,function(){
					$('#materiales_reales').show();
					$('#materiales_sub').hide();
					$('#materiales_reales').stop(true).animate({'opacity': 1},200);
				});
				$('#caracteristicas_sub').stop(true).animate({'opacity': 0},200,function(){
					$('#caracteristicas_reales').show();
					$('#caracteristicas_sub').hide();
					$('#caracteristicas_reales').stop(true).animate({'opacity': 1},200);
				});
				$('.sub_codigo').stop(true).animate({'opacity' : 0},200,function(){
					$('.sub_codigo').hide();
				});
			}
			if(n != -1)
			{
				$('#materiales_reales').stop(true).animate({'opacity': 0},200,function(){
					$('#materiales_sub').html(sub_productos[n].materiales);
					$('#materiales_sub').show();
					$('#materiales_reales').hide();
					$('#materiales_sub').stop(true).animate({'opacity': 1},200);
				});
				$('#caracteristicas_reales').stop(true).animate({'opacity': 0},200,function(){
					$('#caracteristicas_sub').html(sub_productos[n].caracteristicas);
					$('#caracteristicas_sub').show();
					$('#caracteristicas_reales').hide();
					$('#caracteristicas_sub').stop(true).animate({'opacity': 1},200);
				});
				$('.sub_codigo').html(' - '+sub_productos[n].codigo);
				$('.sub_codigo').stop(true).animate({'opacity' : 1},200,function(){
					$('.sub_codigo').show();
				});
			}
		}
		function Comprar(n)
		{
			var talle = $('#talle').val()
			var color;
			$('.color_final').each(function(){
				if($(this).attr('id') == 'active')
				{
					color = $(this).find('#color').val();
				}
			});
			if(n == -1){ var sub = '';}
			if(n != -1){ var sub = sub_productos[n].codigo;}
			$.ajax({
				type : 'post',
				url : 'product.php',
				data : { add_producto : '<?php echo $producto_int['id_producto']; ?>', talle : talle, color : color ,sub : sub}
			});
			$('.btn_compra_'+n).stop(true).animate({'opacity' : 0},200,function(){
				$('.btn_compra_'+n).hide();
				$('.exito_compra_'+n).show();
				$('.exito_compra_'+n).css({'opacity' : 0});
				$('.exito_compra_'+n).stop(true).animate({'opacity' : 1},200);
			});
		}
	</script>
</body>
</html>