<?php
	include('mysql/mysql.php');
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
                    <li class="active">Nosotros</li>
                </ul>
                <h1>Nosotros</h1>
            </div>
            <div class="span12">
                <blockquote>
                    <p><strong>Mugeen Gmbh.</strong> Dusseldorf, Deutschland, nuestra filial local en la República Argentina, desde hace más de tres décadas brinda asistencia y apoyo a la industria textil, entendiendo las necesidades específicas de cada caso.</p>
                </blockquote>
            </div>
            <div class="span6">
                <h2>Servicios de bordados indutriales</h2>
                <p>Destinados a empresas del sector textil, marroquinero, calzados, Merchandising y terceros en general.</p>
                <ul>
                    <li>Diseño de logotipo e isótopo.</li>
                    <li>Diseño y desarrollo matricaria para bordado.</li>
                    <li>Diseño, desarrollo y fabricación de prendas especiales, prendas personalizadas a su necesidad.</li>
                    <li>Proveemos materiales técnicos según el requerimiento especifico de cada caso en particular.</li>
                    </ul>
                <h3>Nuestra característica principal</h3>
                <p>Lograr la prenda ideal que cada actividad requiere.</p>
                <p>La satisfacción de nuestros clientes es nuestro primordial objetivo, por ese motivo controlamos exhaustivamente la calidad total del producto.</p>
                <blockquote>Nuestro objetivo: La calidad</blockquote>
                <ul>
                    <li>Maquinarias con tecnología Dual, de cabezales múltiples.</li>
                    <li>Comandos LCD táctil.</li>
                    <li>Programas específicos de diseño de bordado de última generación.</li>
                </ul>
                <p>Permiten perfectos trazos y claras definiciones. Hoy podemos trasladar originales de diseños a bordados que anteriormente era imposible realizar y plasmar en las telas.</p>
                <p>En MUGEEN Realizamos grandes producciones porque contamos con Máquinas bordadoras computarizadas de última generación.</p>
                <p>Tecnología Dual y múltiples cabezas de bordado que pueden producir múltiples diseños al mismo tiempo, agilizando notablemente la producción seriada.  Garantizamos entregas en 24 Hs. Esto es posibles gracias a nuestro  personal altamente calificado, y maquinaria muy confiables de origen Japonés.</p>
                <h3>Bordados y terminaciones especiales</h3>
                <p>Apliques con diferentes texturas, escudos, cordones, lentejuelas, calados. Bordados con múltiples rellenos, fondos y texturas, detalles que permiten competir en calidad con los antiguos bordados artesanales realizados a mano cuando el trabajo así lo requiere.</p>
                <h3>Materias primas</h3>
                <blockquote>Utilizamos Hilado Multifilamento importado (poliéster siliconado) de alta calidad.</blockquote>
                <img src="../img/about/hilos.jpg">
                <p>Telas no tejidas de base,  de gran resistencia.</p>
                <p>Agujas de origen Alemán, poseen características especiales que no dañan los productos al bordarlos.</p>
                <h3>Control de calidad</h3>
                <p>No solo controlamos la calidad de nuestros bordados, limpieza de hilos, terminaciones, sino que previamente al bordado descartamos cualquier prenda que presente fallas para no ponerla en máquina  ahorrando al cliente el costo de una pieza que se descartara posteriormente.</p>
            </div>
            <div class="span6">
                <h2>Uniformes especiales</h2>
                <blockquote>Calidad + tecnología</blockquote>
                <p>Contamos con excelente calidad en confección de prendas, maquinarias que garantizan excelente terminación y mano de obra calificada.</p>
                <p>Nuestro riguroso control de calidad, no aprueba detalles de ningún tipo por mínimos que sean estos.</p>
                <img src="../img/about/bordadora.jpg">
                <h3>Materia prima</h3>                 
                <p>Entendemos  el compromiso que significa aportar imagen a su empresa, desde el lugar del uniforme, por este motivo somos muy exigentes en la calidad de las materias primas y solo utilizamos de primera categoría con control certificado por fábrica.</p>
                <p>La relación con nuestros proveedores no es esporádica, como tampoco compramos ocasionales ofertas. Por el contrario se mantiene desde hace tiempo estable temporada tras temporada lo que garantiza una seguridad en la calidad de los productos utilizados.</p>
                <h3>Nuestras líneas de producción</h3>
                <ul>
                    <li>Sport & Urbano y tiempo libre</li>
                    <li>Industrial y  Colegial</li>
                    <li>Sanidad y Gastronómica</li>
                    <li>Merchandising</li>
                </ul>
                <p>Amplia oferta de Artículos en todas nuestras líneas.</p>
                <p>Ofrecemos productos de excelente calidad adaptado a las necesidades específicas que cada actividad requiere, diseños cómodos y elegantes, telas especiales y productos a medida.</p>
                <img src="../img/about/fabrica.jpg">
            </div>
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
