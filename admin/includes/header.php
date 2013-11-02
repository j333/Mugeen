<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Admin Mugeen.com.ar</title>
		
		<link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" />
		<!--[if lt IE 9]>
		<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" media="screen" type="text/css" href="css/colorpicker.css" />
		<link rel="icon" href="images/favicon.ico" type="images/vnd.microsoft.icon">
		<link href="css/jquery-te-1.4.0.css" rel="stylesheet" type="text/css" />
		<script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
		<script src="js/hideshow.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/colorpicker.js"></script>
		<script type="text/javascript" src="js/ajaxupload.js"></script>
		<script type="text/javascript" language="javascript" src="js/jquery-te-1.4.0.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			
			setInterval(function() {
				var height = $('#main').height()+55+37;
				if(height < $(window).height())
				{
					$('#main').height($(window).height()-55-37);
				}
				if($('#main').height() != $('#sidebar').height())
				{
					$('#sidebar').height($('#main').height());
				}
			},10);
			
			$('#checkbox_gral').click(function(){
				if($(this).attr('checked') == 'checked')
				{
					$('.checkbox_list').attr('checked','checked');
				}
				if($(this).attr('checked') == undefined)
				{
					$('.checkbox_list').attr('checked',null);
				}
			});
		});
		</script>
	</head>
	<body>
		<header id="header">
			<hgroup>
				<h1 class="site_title"><a href="index.php"><img src="images/logo_white.png"></a></h1>
				<h2 class="section_title"><?php echo $pag_actual; ?></h2>
			</hgroup>
		</header> <!-- end of header bar -->
		<section id="secondary_bar">
			<div class="user">
				<p><?php echo $_SESSION['administrador']['nombre']; ?></p>
				<!-- <a class="logout_user" href="#" title="Logout">Logout</a> -->
			</div>
			<div class="breadcrumbs_container">

			</div>
		</section><!-- end of secondary bar -->
		<aside id="sidebar" class="column">
			<?php if($_SESSION['administrador']['rol'] == 'god'){ ?>
			<h3>Administradores</h3>
			<ul class="toggle">
				<li class="icn_add_user"><a href="add_administrador.php">Agregar Administrador</a></li>
				<li class="icn_view_users"><a href="administradores.php">Ver Administradores</a></li>
			</ul>
			<h3>Categorías</h3>
			<ul class="toggle">
				<li class="icn_add_categories"><a href="add_categorias.php">Agregar Categoría</a></li>
				<li class="icn_categories"><a href="categorias.php">Ver Categorías</a></li>
			</ul>
			<?php } ?>
			<h3>Productos</h3>
			<ul class="toggle">
				<li class="icn_add_categories"><a href="add_productos.php">Agregar Producto</a></li>
				<li class="icn_categories"><a href="productos.php">Ver Productos</a></li>
				<li class="icn_add_categories"><a href="add_colores.php">Agregar Color</a></li>
				<li class="icn_categories"><a href="colores.php">Ver Colores</a></li>
			</ul>
			<h3>Newsletters</h3>
			<ul class="toggle">
				<li class="icn_add_categories"><a href="newsletter.php">Ver Agregados</a></li>
				<li class="icn_settings"><a href="sorteo.php">Sorteo</a></li>
			</ul>
			<h3>Admin</h3>
			<ul class="toggle">
				<?php if($_SESSION['administrador']['rol'] == 'god'){ ?>
					<li class="icn_settings"><a href="panel.php">Panel</a></li>
				<?php } ?>
				<li class="icn_jump_back"><a href="logout.php">Cerrar Sesión</a></li>
			</ul>
			
			<footer>
				<hr />
			</footer>
		</aside><!-- end of sidebar -->
		
		<section id="main" class="column">