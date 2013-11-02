<?php
	include('mysql/mysql.php');
	$get = LimpiarGET();
	
	if(isset($get['newsletters']))
	{
		$val['id_new'] = '';
		$val['email'] = $get['newsletters'];
		Sql_insertar('newsletters',$val);
		header("location: home/");
		exit;
	}
	
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
    <style type="text/css">
    /* Preloader */
        #preloader {
            position:fixed;
            top:0;
            left:0;
            right:0;
            bottom:0;
            background-color:#e80032; /* change if the mask should have another color then white */
            z-index:999999999; /* makes sure it stays on top */
        }
        #status {
            width:300px;
            height:60px;
            position:absolute;
            left:50%; /* centers the loading animation horizontally one the screen */
            top:50%; /* centers the loading animation vertically one the screen */
            background-image:url(img/status.png); /* path to your loading animation */
            background-repeat:no-repeat;
            background-position:center;
            margin:-50px 0 0 -150px; /* is width and height divided by two */
        }
        #status p{
            margin-top: 30px;
            color:#ccc;
        }
    </style>
    <div id="preloader">
        <div id="status">&nbsp;
            <p>Cargando...</p>
        </div>
    </div>
    <?php include('includes/header.php'); ?>
    <!-- Carousel
    ================================================== -->

    <div id="myCarousel" class="carousel slide">
        <ol class="carousel-indicators">
			<?php foreach($lst_categorias as $index => $categoria){ ?>
				<li data-target="#myCarousel" data-slide-to="<?php echo $index; ?>" <?php if($index == 0){ echo 'class="active"';} ?> ></li>
			<?php } ?>
        </ol>
        <div class="carousel-inner">
			<?php foreach($lst_categorias as $index => $categoria){ ?>
				<div class="item <?php if($index == 0){ echo 'active';} ?>">
					<div class="fill" style="background-image:url('images/categorias/<?php echo $categoria['image']; ?>');">
						<div class="container">
							<div class="carousel-caption">
								<a href="seccion/<?php echo $categoria['id_categoria']; ?>/"><h1><?php echo $categoria['nombre']; ?></h1></a>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
        </div>
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
    </div>
    <!-- /.carousel -->
    <div class="container">
        <!-- Example row of columns -->
        <div class="row">
            <div class="span3">
                <h3>Diseños Personalizados</h3>
                <p>Podemos hacer realidad tu idea…
                Desarrolla tu propio diseño, envíanos un archivo o imagen y te enviaremos la propuesta.</p>
            </div>
            <div class="span3">
                <h3>Pedidos Telefónicos</h3>
                <p>Podemos hacer realidad tu idea…
                Desarrolla tu propio diseño, envíanos un archivo o imagen y te enviaremos la propuesta.</p>
            </div>
            <div class="span3">
                <h3>Entrega en 48hs</h3>
                <p>Podes realizar tu pedido por esta página o telefónicamente al (054 261) 422 4470 - 15 4151188 - ID 561* 909.</p>
            </div>
            <div class="span3">
                <h3></i>Envios a todo el país</h3>
                <p>Previa confirmación de disponibilidad entregamos tu pedido en 48 horas.</p>
            </div>
        </div>
    </div> <!-- /container -->

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
