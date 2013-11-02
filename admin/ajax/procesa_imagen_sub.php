<?php
	include('../../mysql/mysql.php');
	
	$nombre = generarCodigo(30,'qwertyuiopasdfhjklzxcbbnm');
	$ext = pathinfo($_FILES['img']['name']);
	$ext = $ext['extension'];
	move_uploaded_file($_FILES['img']['tmp_name'],'../../images/productos/'.$nombre.'.'.$ext);
	chmod('../../images/productos/'.$nombre.'.'.$ext, 0755);
	echo $nombre.'.'.$ext;
?>