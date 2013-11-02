<?php
	include('mysql/mysql.php');
	$get = LimpiarGET();

	$categoria_int = Sql_select('categorias',array('id_categoria' => $get['id_categoria']),'=');
	$categoria_int = $categoria_int[0];
	
	$subcategorias_int = Sql_select('categorias',array('sub_de' => $categoria_int['id_categoria'],'estado' => 'activa'),'=');
	
	$new_productos = Sql_select_especial('productos',array('id_categoria' => $categoria_int['id_categoria'].','),'LIKE',array('inicio' => 0,'final' => 3),array('campo' => 'fecha_creacion','orden' => 'DESC'),'AND');
	
	$des_productos = Sql_select_especial('productos',array('id_categoria' => $categoria_int['id_categoria'].',','destacado' => 1),'LIKE',array('inicio' => 0,'final' => 3),array('campo' => 'fecha_creacion','orden' => 'DESC'),'AND');
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
                <li class="active"><?php echo $categoria_int['nombre']; ?></li>
            </ul>
            <h1><?php echo $categoria_int['nombre']; ?></h1>
        </div>
        <div class="span12">
            <div class="hero-unit banner-section" style="background-image:url('images/categorias/<?php echo $categoria_int['image_b']; ?>');">
            </div>
        </div>
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
                <li class="nav-header">Categorías <?php echo $categoria_int['nombre']; ?></li>
				<?php foreach($subcategorias_int as $subc){ ?>
					<li><a href="subseccion/<?php echo $subc['id_categoria']; ?>/"><i class="icon-chevron-right"></i> <?php echo $subc['nombre']; ?></a></li>
				<?php } ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9 no-mobile">
            <div class="breadcrumb"><i class="icon-plus"></i></i> Nuevos Productos</div>
            <div class="row-fluid">
                <ul class="thumbnails">
					<?php foreach($new_productos as $producto){ 
							if(!$imagenes = Sql_select('imagenes_productos',array('id_producto' => $producto['id_producto']),'='))
							{
								$imagenes[0]['nombre'] = 'sin_imagen.jpg';
							}
					?>
						<li class="span4">
							<a href="producto/<?php echo $producto['id_producto']; ?>/" class="thumbnail">
								<img data-src="holder.js/300x300" alt="300x300" src="images/productos/<?php echo $imagenes[0]['nombre']; ?>">
								<div class="caption">
									<h3><?php echo $producto['nombre']; ?></h3>
								</div>
							</a>
						</li>
					<?php } ?>
                </ul>
            </div>
			<?php if(is_array($des_productos)){ ?>
				<div class="breadcrumb"><i class="icon-heart"></i> Productos Destacados</div>
				<div class="row-fluid">
					<ul class="thumbnails">
						<?php foreach($des_productos as $producto){ 
							if(!$imagenes = Sql_select('imagenes_productos',array('id_producto' => $producto['id_producto']),'='))
							{
								$imagenes[0]['nombre'] = 'sin_imagen.jpg';
							}
						?>
							<li class="span4">
								<a href="producto/<?php echo $producto['id_producto']; ?>/" class="thumbnail">
									<img data-src="holder.js/300x300" alt="300x300" src="images/productos/<?php echo $imagenes[0]['nombre']; ?>">
									<div class="caption">
										<h3><?php echo $producto['nombre']; ?></h3>
									</div>
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>
        </div><!--/span-->
    </div><!--/row-->
</div>

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
</body>
</html>