<?php
		include('../mysql/mysql.php');
		$post = LimpiarPOST();
		
		$page = $post['page'];
		$sub_cat = $post['sub_cat'];
		$max_per_page = 12;
		
		$lst_productos = Sql_select_especial('productos',array('id_subcategoria' => $sub_cat.','),'LIKE',0,array('orden' => 'DESC','campo' => 'importancia'),'AND');
		$max_productos = count($lst_productos);
		
		
		$max_page = ceil($max_productos/$max_per_page);
		if($page <= $max_page-5)
		{
			for($i= $page;$i<$page+5;$i+=1)
			{
				if($i<$max_page)
				{
					$pages[] = $i;
				}
			}
		}
		else
		{
			if($max_page-5 < 1)
			{
				for($i= $page;$i<$page+5;$i+=1)
				{
					if($i<$max_page+1)
					{
						$pages[] = $i;
					}
					else
					{
						$pages[] = '-';
					}
				}
			}
			else
			{
				for($i= $max_page-4;$i<$max_page+1;$i+=1)
				{
					$pages[] = $i;
				}
			}
		}
		echo '<ul>';
			if($page>1){
                echo '<li><a href="javascript:void(0);" onclick="changePage('.($page-1).')">Anterior</a></li>';
			}else{
                echo '<li class="disabled"><a href="javascript:void(0);">Anterior</a></li>';
			}
			foreach($pages as $pag)
			{
				if($pag == $page)
				{
					echo '<li class="active" ><a href="javascript:void(0);">'.$pag.'</a></li>';
				}
				else
				{
					echo '<li><a href="javascript:void(0);" onclick="changePage('.($pag).')">'.$pag.'</a></li>';
				}
			}
			if($page<$max_page)
			{
                echo '<li><a href="javascript:void(0);" onclick="changePage('.($page+1).')">Siguiente</a></li>';
			}else{
                echo '<li class="disabled" ><a href="javascript:void(0);">Siguiente</a></li>';
			}
        echo '</ul>'.$post['codigo'];
		$cont = 0;
		for($i=(($page-1)*$max_per_page);$i<(($page-1)*$max_per_page)+$max_per_page;$i+=1)
		{
			if(!isset($lst_productos[$i]))
			{ 
				if($cont>1)
				{
					echo '</ul></div>';
				}
				break; 
			}
			if($cont == 0){ echo '<div class="row-fluid"><ul class="thumbnails">'; }
				if(!$imagenes = Sql_select('imagenes_productos',array('id_producto' => $lst_productos[$i]['id_producto']),'='))
				{
					$imagenes[0]['nombre'] = 'sin_imagen.jpg';
				}
				echo '<li class="span4">
                            <a href="producto/'.$lst_productos[$i]['id_producto'].'/" class="thumbnail">
                                <img data-src="holder.js/300x300" alt="300x300" src="images/productos/'.$imagenes[0]['nombre'].'">
                                <div class="caption">
                                    <h3>'.$lst_productos[$i]['nombre'].'</h3>
                                </div>
                            </a>
                        </li>';
			if($cont == 2){ echo '</ul></div>'; }
			$cont+=1;
			if($cont==3){ $cont = 0; }
		}
		
		exit;
?>