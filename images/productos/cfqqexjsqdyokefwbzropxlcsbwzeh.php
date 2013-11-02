<?php
	include('mysql/mysql.php');
	$get = LimpiarGET();
	$post = LimpiarPOST();
	
	
	if(isset($post['nombre']))
	{
		$to = 'agustin@sfidasoft.com';
		$titulo = 'Nuevo pedido de '.$post['nombre'];
		$msj = 'Nombre: <b>'.$post['nombre'].'</b><br>';
		$msj .= 'Tel: <b>'.$post['tel'].'</b><br>';
		$msj .= 'Email: <b>'.$post['email'].'</b><hr>';
		$msj .= 'Mensaje: <b>'.$post['message'].'</b><hr>';
		
		$msj .= '<table>';
			$msj .= '<tr>';
			$msj .= 	'<td width="200">';
			$msj .= 		'CODIGO';
			$msj .= 	'</td>';
			$msj .= 	'<td width="300">';
			$msj .= 		'NOMBRE';
			$msj .= 	'</td>';
			$msj .= 	'<td width="150">';
			$msj .= 		'COLOR';
			$msj .= 	'</td>';
			$msj .= 	'<td width="150">';
			$msj .= 		'TALLE';
			$msj .= 	'</td>';
			$msj .= 	'<td width="150">';
			$msj .= 		'CANTIDAD';
			$msj .= 	'</td>';
			$msj .= '</tr>';
		
		$lst_carrito = Sql_select('carrito',array('sesion' => $_COOKIE['sesion_carrito']),'=');
		foreach($lst_carrito as $item)
		{
			$producto = Sql_select('productos',array('id_producto' => $item['id_item']),'=');
			$producto = $producto[0];
									
			$color = Sql_select('colores',array('color' => $item['color']),'=');
			$color =  $color[0];
			
			$msj .= '<tr>';
			$msj .= 	'<td>';
			$msj .= 		$producto['codigo'];
			$msj .= 	'</td>';
			$msj .= 	'<td>';
			$msj .= 		$producto['nombre'];
			$msj .= 	'</td>';
			$msj .= 	'<td>';
			$msj .= 		$color['nombre'];
			$msj .= 	'</td>';
			$msj .= 	'<td>';
			$msj .= 		strtoupper($item['talle']);
			$msj .= 	'</td>';
			$msj .= 	'<td>';
			$msj .= 		$item['cantidad'];
			$msj .= 	'</td>';
			$msj .= '</tr>';
			
		}
		$msj .= '</table>';
		
		$de = $post['email'];
		EnviarMail($to,$titulo,$msj,$de);
		
		Sql_delete('carrito',array('sesion' => $_COOKIE['sesion_carrito']));
		
		header("location: carrito/");
		exit;
	}
	
	if(isset($post['change_cantidad']))
	{
		Sql_update('carrito',array('cantidad' => $post['change_cantidad']),array('id_item' => $post['id_item']));
		exit;
	}
	
	if(isset($get['erase_item']))
	{
		Sql_delete('carrito',array('id_carrito' => $get['erase_item']));
		header("location: carrito/");
		exit;
	}
	
	$lst_carrito = Sql_select('carrito',array('sesion' => $_COOKIE['sesion_carrito']),'=');
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
    <?php include('includes/header.php'); ?>
    <!-- Carousel
    ================================================== -->


    <div class="container">
        <div class="row">
            <div class="span12">
                <ul class="breadcrumb">
                    <li><a href="home/">Home</a> <span class="divider">/</span></li>
                    <li class="active">Pedido</li>
                </ul>
                <h1>Pedido</h1>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Color</th>
                            <th>Talle</th>
                            <th><abbr title="Cantidad">Cant.</abbr></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
						<?php if(is_array($lst_carrito)){ ?>
							<?php foreach($lst_carrito as $item){
									$producto = Sql_select('productos',array('id_producto' => $item['id_item']),'=');
									$producto = $producto[0];
									
									$color = Sql_select('colores',array('color' => $item['color']),'=');
									$color =  $color[0];
							?>
								<tr>
									<input type="hidden" id="id_item" value="<?php echo $item['id_item']; ?>">
									<td><a href="producto/<?php echo $producto['id_producto']; ?>/"><?php echo $producto['nombre']; ?></a></td>
									<td><?php echo $producto['codigo']; ?></td>
									<td><?php echo $color['nombre']; ?></td>
									<td><?php echo strtoupper($item['talle']); ?></td>
									<td><input class="cantidad_input" style="width:50px; background-color:#ffffff;" type="number" value="<?php echo $item['cantidad']; ?>"></td>
									<td><a href="carrito.php?erase_item=<?php echo $item['id_carrito']; ?>"><i class="icon-remove"></i></a></td>
								</tr>
							<?php } ?>
						<?php }else{ ?>
							<tr>
								<td colspan="6" >Sin productos agregados.</td>
							</tr>
						<?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="span4">
                <p>Complete el siguiente formulario y le responderemos inmediatamente un presupuesto acorde a lo solicitado.</p>
                <p>Usted no tiene responsabilidad de compra al realizar este pedido.</p>
            </div>
			<form action="carrito.php" method="post">
				<div class="span8">
					<div class="row contact">
						<div class="span3">
							<label for="input1">Nombre</label>
							<input type="text" name="nombre" class="form-control" id="input1">
						</div>
						<div class="span2">
							<label for="input2">Mail</label>
							<input type="email" name="email" class="form-control" id="input2">
						</div>
						<div class="span2">
							<label for="input3">Teléfono</label>
							<input type="tel" name="tel" class="form-control" id="input3">
						</div>
						<div class="clearfix"></div>
						<div class="span8">
							<label for="input4">Comentarios</label>
							<textarea name="message" class="form-control" rows="6" id="input4"></textarea>
						</div>
						<div class="span8">
							<input type="hidden" name="save" value="contact">
							<button type="submit" class="btn btn-primary">Realizar pedido</button>
						</div>
					</div>
				</div>
			</form>
        </div><!--/row-->
    </div><!--/container-->

    <?php include('includes/footer.php'); ?>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.cantidad_input').change(function(){
				if($(this).val()==''){ $(this).val(1); }
				if($(this).val()<1){ $(this).val(1); }
				$.ajax({
					type : 'post',
					url : 'carrito.php',
					data : { change_cantidad : $(this).val(), id_item : $(this).parent().parent().find('#id_item').val()}
				});
			});
		});
	</script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

    <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>

    <script>
        var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src='//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
    <script>
        // PRELOADER
        $(window).load(function() { // makes sure the whole site is loaded
            $("#status").fadeOut(); // will first fade out the loading animation
            $("#preloader").delay(350).fadeOut("slow"); // will fade out the white DIV that covers the website.
            $("html").css({ 'overflow-y' : 'auto'});
            $("body").css({ 'overflow-y' : 'auto'});
        })
    </script>
</body>
</html>
