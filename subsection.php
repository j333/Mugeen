<?php
	include('mysql/mysql.php');
	$get = LimpiarGET();
	$post = LimpiarPOST();
	
	$codigo_sep = 'codigoirrepetible';
	
	$subcategoria_int = Sql_select('categorias',array('id_categoria' => $get['id_categoria']),'=');
	$subcategoria_int = $subcategoria_int[0];
	
	$categoria_int = Sql_select('categorias',array('id_categoria' => $subcategoria_int['sub_de']),'=');
	$categoria_int = $categoria_int[0];
	
	$subcategoria_ints = Sql_select('categorias',array('sub_de' => $categoria_int['id_categoria'],'estado' => 'activa'),'=');
	
	
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
                    <li class="active"><?php echo $subcategoria_int['nombre']; ?></li>
                </ul>
            </div>
            <div class="span3">
                <div class="well sidebar-nav no-mobile">
                    <ul class="nav nav-list">
                        <li class="nav-header">Categorías <?php echo $categoria_int['nombre']; ?></li>
						<?php foreach($subcategoria_ints as $subc){ ?>
							<li <?php if($subc['id_categoria'] == $subcategoria_int['id_categoria']){ echo 'class="active"'; } ?> ><a href="subseccion/<?php echo $subc['id_categoria']; ?>/"><i class="icon-chevron-right"></i> <?php echo $subc['nombre']; ?></a></li>
						<?php } ?>
                    </ul>
                </div><!--/.well -->
            </div><!--/span-->
            <div class="span9">
                <h1><?php echo $subcategoria_int['nombre']; ?></h1>
                <div class="pagination pagination-right no-mobile paginador_superior">
                </div>
            </div><!--/span-->
            <div class="pagination pagination-right">
            </div>
        </div><!--/row-->
    </div>

    <?php include('includes/footer.php'); ?>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

	<script type="text/javascript">
		function changePage(n)
		{
			$('.row-fluid').stop(true).animate({'opacity' : 0 },300);
			$('.pagination').stop(true).animate({'opacity' : 0 },300,function(){
				$.ajax({
					type : 'POST',
					data : { page : n , sub_cat : <?php echo $subcategoria_int['id_categoria']; ?>, codigo : '<?php echo $codigo_sep; ?>'},
					url : 'ajax/get_page.php'
				}).done(function(res){
					res = res.split('<?php echo $codigo_sep; ?>');
					$('.row-fluid').remove();
					$('.pagination').html(res[0]);
					$('.pagination').stop(true).animate({'opacity' : 1 },300);
					$(res[1]).insertAfter('.paginador_superior');
				});
			});
		}
		$(document).ready(function(){
			changePage(<?php echo (isset($get['page'])) ? $get['page'] : 1; ?>);
		});
	</script>
	
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