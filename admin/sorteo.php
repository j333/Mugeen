<?php
	include('../mysql/mysql.php');
	include('includes/funciones.php');
	$get = LimpiarGET();
	$post = LimpiarPOST();
	
	if(isset($post['cantidad']))
	{
		$ganadores = mysql_query('SELECT * FROM newsletters Order By Rand() Limit 0,'.$post['cantidad']);
		$i=0;
		while ( ($reg = mysql_fetch_assoc ( $ganadores )) != false ) {
			$arr [$i ++] = $reg;
		}
		$lst_ganadores = $arr;
	}
	
	ControlAdmin();
	
	$pag_actual = 'Sorteo de NewsLetter';
	
?>
<?php include('includes/header.php'); ?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#cantidad').change(function(){
				if($(this).val() == ''){ $(this).val(1); }
				if($(this).val() < 1){ $(this).val(1); }
			});
		});
	</script>
	<h4 class="alert_error error_acciones" style="display:none;">Debe seleccionar un elemento.</h4>
	<form action="" method="post" id="form">
		<input type="hidden" id="id" name="id" value="<?php echo $get['id']; ?>">
		<article class="module width_full">
			<header><h3>Datos de Sorteo</h3></header>
				<div class="module_content">
						<fieldset style="width:48%; float:left; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
							<label>Cantidad de Ganadores:</label>
							<input type="number" name="cantidad" id="cantidad" style="width:92%;" value="3">
						</fieldset><div class="clear"></div>
					<?php if(is_array($lst_ganadores)){ ?>
					<h2>Ganadores:</h2>
					<?php foreach($lst_ganadores as $n => $ganador){ ?>
						<h3 style="text-transform:none;"><?php echo $n+1; ?>° - <?php echo $ganador['email']; ?></h3>
					<?php }} ?>
				</div>
			<footer>
				<div class="submit_link">
					<input type="submit" name="btn" value="Procesar" class="alt_btn">
				</div>
			</footer>
		</article>	
	</form>
	<div class="spacer"></div>
<?php include('includes/footer.php'); ?>