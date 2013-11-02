<?php
	include('mysql/mysql.php');
	$get = LimpiarGET();
	
	if(isset($get['search_new']))
	{
		header("location: busqueda/".urlencode($get['search_new']).'/');
		exit;
	}
	
	$tabla = 'productos';
	$page = (isset($get['page'])) ? $get['page'] : 0;
	$search = (isset($get['search'])) ? $get['search'] : '';
	$limite['inicio'] = $page*12;
	$limite['final'] = 12;
	$orden['campo'] = (isset($get['order'])) ? $get['order'] : 'nombre';
	$orden['orden'] = (isset($get['order_by'])) ? $get['order_by'] : 'ASC';
	$order = $orden['campo'];
	$order_by = $orden['orden'];
	$order_by_new = ($order_by=='DESC') ? 'ASC' : 'DESC';

	if($search)
	{
		$lst = Sql_select_especial($tabla,array('nombre' => $search),'LIKE',0,$orden,'AND');
		$max_reg = count(Sql_select_especial($tabla,array('nombre' => $search),'LIKE',0,0,'AND'));
	}
	
	$max_pag = ceil($max_reg/12);
	
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
                    <li class="active">Resultado de Busqueda</li>
                </ul>
            </div>
            <div class="span12">
                <h1><strong><?php echo count($lst); ?></strong> resultados...</h1>
				<?php if($max_pag>1){ ?>
					<div class="pagination pagination-right no-mobile">
						<ul>
							<?php if($page>0){ ?>
								<li><a href="busqueda/<?php echo urlencode($search); ?>/<?php echo $page-1; ?>/">Anterior</a></li>
							<?php }else{ ?>
								<li class="disabled"><a href="javascript:void(0);">Anterior</a></li>
							<?php } ?>
							<?php for($i=0;$i<5;$i+=1){ 
							$d = $page+$i;
							?>	
								<?php if($d == $page){?>
									<li class="active"><a href="javascript:void(0);"><?php echo $d+1; ?></a></li>
								<?php continue; } ?>
								<?php if($d > $max_pag-1){?>
									<li class="disabled"><a href="javascript:void(0);">-</a></li>
								<?php continue; } ?>
								<?php if($d != $page){?>
									<li><a href="busqueda/<?php echo urlencode($search); ?>/<?php echo $d; ?>/"><?php echo $d+1; ?></a></li>
								<?php } ?>
							<?php } ?>
							<?php if($page<$max_pag-1){ ?>
								<li><a href="busqueda/<?php echo urlencode($search); ?>/<?php echo $page+1; ?>/">Siguiente</a></li>
							<?php }else{ ?>
								<li class="disabled"><a href="javascript:void(0);">Siguiente</a></li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
				<?php
					$cont = 0;
					foreach($lst as $item){ 
							$imagenes = Sql_select('imagenes_productos',array('id_producto' => $item['id_producto']),'=');
							if(!$imagenes)
							{
								$imagenes[0]['nombre'] = 'sin_imagen.jpg';
							}
						?>
						<?php if($cont == 0) { ?>
						<div class="row-fluid">
							<ul class="thumbnails">
						<?php } ?>
								<li class="span3">
									<a href="producto/<?php echo $item['id_producto']; ?>/" class="thumbnail">
										<img data-src="holder.js/300x300" alt="300x300" src="images/productos/<?php echo $imagenes[0]['nombre']; ?>">
										<div class="caption">
											<h3><?php echo $item['nombre']; ?></h3>
										</div>
									</a>
								</li>
						<?php $cont+=1;
						if($cont == 4) { $cont=0; ?>
							</ul>
						</div>
						<?php } ?>
					<?php } 
					if(($cont != 4)&&($cont!=0)) { 
					?>
							</ul>
						</div>
						<?php } ?>
					
            </div><!--/span-->
				<?php if($max_pag>1){ ?>
					<div class="pagination pagination-right no-mobile">
						<ul>
							<?php if($page>0){ ?>
								<li><a href="busqueda/<?php echo urlencode($search); ?>/<?php echo $page-1; ?>/">Anterior</a></li>
							<?php }else{ ?>
								<li class="disabled"><a href="javascript:void(0);">Anterior</a></li>
							<?php } ?>
							<?php for($i=0;$i<5;$i+=1){ 
							$d = $page+$i;
							?>	
								<?php if($d == $page){?>
									<li class="active"><a href="javascript:void(0);"><?php echo $d+1; ?></a></li>
								<?php continue; } ?>
								<?php if($d > $max_pag-1){?>
									<li class="disabled"><a href="javascript:void(0);">-</a></li>
								<?php continue; } ?>
								<?php if($d != $page){?>
									<li><a href="busqueda/<?php echo urlencode($search); ?>/<?php echo $d; ?>/"><?php echo $d+1; ?></a></li>
								<?php } ?>
							<?php } ?>
							<?php if($page<$max_pag-1){ ?>
								<li><a href="busqueda/<?php echo urlencode($search); ?>/<?php echo $page+1; ?>/">Siguiente</a></li>
							<?php }else{ ?>
								<li class="disabled"><a href="javascript:void(0);">Siguiente</a></li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
        </div><!--/row-->
    </div><!--/container-->

    <?php include('includes/footer.php'); ?>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

    <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>

    <script>
        var _gaq=[['_setAccount','UA-19883854-4'],['_trackPageview']];
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
