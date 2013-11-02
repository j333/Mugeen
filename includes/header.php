<?php
	if(!isset($_SESSION['sesion_carrito']))
	{
		$value = generarCodigo(10,'qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM');
		$_SESSION['sesion_carrito'] = $value;
	}
	$lst_categorias = Sql_select('categorias',array('sub_de' => 0),'=');
	foreach($lst_categorias as $index => $categoria)
	{
		$c = Sql_select_especial('categorias',array('sub_de' => $categoria['id_categoria']),'=',0,array('orden' => 'ASC','campo' => 'nombre'),'AND');
		$lst_categorias[$index]['subcategorias'] = $c;
	}
	
	$items_carrito = count(Sql_select('carrito',array('sesion' => $_SESSION['sesion_carrito']),'='));
?>
	<div class="navbar navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="home/">Mugeen</a>                
                <div class="nav-collapse collapse">
                    <ul class="nav">
						<?php foreach($lst_categorias as $categoria){ ?>
							<li class="dropdown">
								<a href="seccion/<?php echo $categoria['id_categoria']; ?>/" class="dropdown-toggle" data-toggle="dropdown"><?php echo $categoria['nombre']; ?><i class="icon-chevron-down"></i></a>
								<ul class="dropdown-menu">
									<li><a href="seccion/<?php echo $categoria['id_categoria']; ?>/">Entrar</a></li>
									<li class="divider"></li>
									<li class="nav-header">Categorías</li>
									<?php
										$a_column = ceil(count($categoria['subcategorias'])/2);
									?>
									<div class="columna">
										<?php for($i=0;$i<$a_column;$i+=1){ ?>
										<li><a href="subseccion/<?php echo $categoria['subcategorias'][$i]['id_categoria']; ?>/"><?php echo $categoria['subcategorias'][$i]['nombre']; ?></a></li>
										<?php } ?>
									</div>  
									<div class="columna">
										<?php for($i=$a_column;$i<count($categoria['subcategorias']);$i+=1){ ?>
										<li><a href="subseccion/<?php echo $categoria['subcategorias'][$i]['id_categoria']; ?>/"><?php echo $categoria['subcategorias'][$i]['nombre']; ?></a></li>
										<?php } ?>
									</div>                         
								</ul>
							</li>
                        <?php } ?>
                        <li><a href="nosotros/">Nosotros</a></li>
                        <li><a href="contacto/">Contacto</a></li>
                        <li id="pedidos"><a href="carrito/"><i class="icon-shopping-cart"></i>Productos  <?php if($items_carrito>0){ ?><span class="badge"><?php echo $items_carrito; ?></span><?php } ?></a></li>
                    </ul>
                    <form class="navbar-form pull-right" method="get" action="search.php">
                        <input id="appendedInputButtons" type="text" name="search_new">
                        <button class="btn" type="submit"><i class="icon-search"></i></button>
                    </form>
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>