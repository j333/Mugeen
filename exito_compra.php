<?php
	include('mysql/mysql.php');
	$post = LimpiarPOST();
	
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

    Enviado exitosamente.

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
