<?php
	function ControlAdmin()
	{
		if(!isset($_SESSION['administrador']))
		{
			header("location: login.php");
			exit;
		}
	}
?>