<?php
	include('mysql/mysql.php');
	$post = LimpiarPOST();
	
	if(isset($post['contact_name']))
	{
		$to = getGral('email_contacto');
		$titulo = 'Mensaje de Contacto de '.$post['contact_name'];
		$msj = 'Nombre: <b>'.$post['contact_name'].'</b><br>';
		$msj .= 'Teléfono: <b>'.$post['contact_tel'].'</b><br>';
		$msj .= 'Email: <b>'.$post['contact_email'].'</b><hr>';
		$msj .= 'Mensaje: <b>'.$post['contact_message'].'</b><br>';
		$de = $post['contact_email'];
		EnviarMail($to,$titulo,$msj,$de);
		
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
    <?php include('includes/header.php'); ?>
    <!-- Carousel
    ================================================== -->

    <div class="container">
        <div class="row">
            <div class="span12">
                <ul class="breadcrumb">
                    <li><a href="home/">Home</a> <span class="divider">/</span></li>
                    <li class="active">Contacto</li>
                </ul>
                <h1>Contacto</h1>
            </div>
            <div class"row">
                <div class="span12">
                    <div id="map-canvas"></div>
                </div>
            </div>
			<form action="" method="post">
            <div class="span8">
                <h2>Formulario</h2>
                <p>Por favor, complete el siguiente formulario. Le responderemos a la brevedad.</p>
                <div class="row contact">
                    <div class="span3">
                        <label for="input1">Nombre</label>
                        <input type="text" name="contact_name" class="form-control" id="input1">
                    </div>
                    <div class="span2">
                        <label for="input2">Mail</label>
                        <input type="email" name="contact_email" class="form-control" id="input2">
                    </div>
                    <div class="span2">
                        <label for="input3">Teléfono</label>
                        <input type="tel" name="contact_tel" class="form-control" id="input3">
                    </div>
                    <div class="clearfix"></div>
                    <div class="span8">
                        <label for="input4">Mensaje</label>
                        <textarea name="contact_message" class="form-control" rows="6" id="input4"></textarea>
                    </div>
                    <div class="span8">
                        <input type="hidden" name="save" value="contact">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </div>
            </div>
			</form>
            <div class="span4">
                <h2>Información</h2>
                <p>Perito Moreno 793 Godoy Cruz, Mendoza, República Argentina</p>
                <p><strong><abbr title="Mail">M</abbr>:</strong> contacto@mugeen.com.ar</p>
                <p><strong><abbr title="Teléfono">T</abbr>:</strong> +054 (261) 4224470</p>
                <p><strong><abbr title="Celular">C</abbr>:</strong> +054 (261) 15 4151188</p>
                <p><strong><abbr title="Nextel">N</abbr>:</strong> ID 561* 909</p>
                <h3>Redes Sociales</h3>
                <p><strong>Twitter:</strong> @MugeenAR</p>
                <p><strong>Facebook:</strong> /MugeenARG</p>
                <p><strong>Google Plus:</strong> /102728917579091996947</p>
            </div>
        </div><!--/span-->
    </div><!--/row-->

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
    
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
        var map;
        function initialize() {
            var mapOptions = {
                zoom: 15,
                center: new google.maps.LatLng(-32.933443,-68.846394),
                mapTypeId: google.maps.MapTypeId.ROADMAP
              };
            map = new google.maps.Map(document.getElementById('map-canvas'),
                mapOptions);

            var companyLogo = new google.maps.MarkerImage('img/map.png',
                new google.maps.Size(50,50),
                new google.maps.Point(0,0),
                new google.maps.Point(25,50)
            );
            var companyPos = new google.maps.LatLng(-32.933443,-68.846394);
            var companyMarker = new google.maps.Marker({
                position: companyPos,
                map: map,
                icon: companyLogo,
                title:"Mugeen"
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
</body>
</html>
