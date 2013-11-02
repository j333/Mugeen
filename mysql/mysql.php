<?php
session_start();
ini_set("display_error",0);
error_reporting(0);
//date_default_timezone_set ('America/Argentina/Cordoba');

$hostname_bbdd = "localhost";
$database_bbdd = "mb000036_mg";
$username_bbdd = "mb000036_mg";
$password_bbdd = "Mugeen123456";

$bbdd = mysql_connect($hostname_bbdd, $username_bbdd, $password_bbdd) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_bbdd);

function LimpiarPOST()
{
	foreach($_POST as $campo =>$valor)
	{
		$array[$campo] = addslashes(strip_tags($valor));
	}
	return $array;
}
function LimpiarGET()
{
	$array['vacio']=0;
	foreach($_GET as $campo =>$valor)
	{
		$array[$campo] = addslashes(strip_tags($valor));
	}
	return $array;
}
function Sql_insertar($table,$valores)
{
	$cont=0;
	$sql = 'INSERT INTO `'.$table.'` (';
	foreach($valores as $campo => $valor)
	{
		$sql .= "`".$campo."`";
		if($cont<count($valores)-1){$sql.=',';}
		$cont+=1;
	}
	$cont=0;
	$sql .= ') VALUES (';
	foreach($valores as $campo => $valor)
	{
		$sql .= "'".$valor."'";
		if($cont<count($valores)-1){$sql.=',';}
		$cont+=1;
	}
	$sql  .= ');';
	if(mysql_query($sql))
	{
		return mysql_insert_id();
	}
	else
	{
		return false;
	}
}
function Sql_select($table,$valores,$operador)
{
	$cont=0;
	$sql = 'SELECT * FROM `'.$table.'` ';
	if(is_array($valores))
	{
		$sql.='WHERE ';
		if($operador=='=')
		{
			foreach( $valores as $campo => $valor)
			{
				$sql .= '`'.$campo.'` '.$operador." '".$valor."'";
				if($cont<count($valores)-1){$sql.=' AND ';}
				$cont+=1;
			}
		}
		if($operador=='LIKE')
		{
			foreach( $valores as $campo => $valor)
			{
				$sql .= '`'.$campo.'` LIKE'." '%".$valor."%'";
				if($cont<count($valores)-1){$sql.=' AND ';}
				$cont+=1;
			}
		}
		if($operador=='<>')
		{
			foreach( $valores as $campo => $valor)
			{
				$sql .= '`'.$campo.'` '.$operador." '".$valor."'";
				if($cont<count($valores)-1){$sql.=' AND ';}
				$cont+=1;
			}
		}
	}
	$sql .=' ;';
	$msql = mysql_query($sql);
	$i=0;
	while ( ($reg = mysql_fetch_assoc ( $msql )) != false ) {
		$arr [$i ++] = $reg;
	}
	return $arr;
}
function Sql_select_especial($table,$valores,$operador,$limite,$orden,$conector = 'AND')
{
	$cont=0;
	$sql = 'SELECT * FROM `'.$table.'` ';
	if(is_array($valores))
	{
		$sql.='WHERE ';
		if(is_array($operador))
		{
			foreach( $valores as $campo => $valor)
			{
				$sql .= '`'.$campo.'` '.$operador[$cont]." '".$valor."'";
				if($cont<count($valores)-1){$sql.=' '.$conector.' ';}
				$cont+=1;
			}
		}
		if($operador=='=')
		{
			foreach( $valores as $campo => $valor)
			{
				$sql .= '`'.$campo.'` '.$operador." '".$valor."'";
				if($cont<count($valores)-1){$sql.=' '.$conector.' ';}
				$cont+=1;
			}
		}
		if($operador=='LIKE')
		{
			foreach( $valores as $campo => $valor)
			{
				$sql .= '`'.$campo.'` LIKE'." '%".$valor."%'";
				if($cont<count($valores)-1){$sql.=' '.$conector.' ';}
				$cont+=1;
			}
		}
		if($operador=='<>')
		{
			foreach( $valores as $campo => $valor)
			{
				$sql .= '`'.$campo.'` '.$operador." '".$valor."'";
				if($cont<count($valores)-1){$sql.=' '.$conector.' ';}
				$cont+=1;
			}
		}
	}
	if(is_array($orden))
	{
		if($orden['orden'] == 'RAND()')
		{
			$sql .=' ORDER BY '.$orden['orden'];
		}
		else
		{
			$sql .=' ORDER BY `'.$orden['campo'].'` '.$orden['orden'];
		}
	}
	if(is_array($limite))
	{
		$sql .=' LIMIT '.$limite['inicio'].' , '.$limite['final'].' ;';
	}
	$msql = mysql_query($sql);
	$i=0;
	while ( ($reg = mysql_fetch_assoc ( $msql )) != false ) {
		$arr [$i ++] = $reg;
	}
	return $arr;
}
function Sql_update($table,$valores,$condiciones)
{
	$sql = 'UPDATE `'.$table.'` SET ';
	$cont=0;
	foreach( $valores as $campo => $valor)
	{
		$sql.='`'.$campo."` = '".$valor."' ";
		if($cont<count($valores)-1){$sql.=',';}
		$cont+=1;
	}
	$sql.=' WHERE ';
	$cont=0;
	foreach( $condiciones as $campo => $valor)
	{
		$sql.='`'.$campo."` = '".$valor."'";
		if($cont<count($condiciones)-1){$sql.=' AND ';}
		$cont+=1;
	}
	return mysql_query($sql);
}
function Sql_drop($table)
{
	$sql = 'TRUNCATE '.$table;
	return mysql_query($sql);
}
function Sql_delete($table,$condiciones)
{
	$sql = 'DELETE FROM '.$table.' WHERE';
	$cont=0;
	foreach($condiciones as $campo => $valor)
	{
		$sql.=' '.$campo." = '".$valor."' ";
		if($cont<count($condiciones)-1){$sql.='AND';}
		$cont+=1;
	}
	return mysql_query($sql);
}
function EnviarMail($to,$titulo,$msj,$de)
{
	$para      = $to;
	$titulo = $titulo;
	$mensaje = $msj;
	$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
	$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$cabeceras .= 'From: '.$de. "\r\n";

return mail($para, $titulo, $mensaje, $cabeceras);
}
function cortarCadena($str,$largo)
{
	if(strlen($str)<$largo){ return $str;}
	$str = substr($str,0,$largo).'...';
	return $str;
}
function generarCodigo($longitud,$caracteres) {
 $key = '';
 $pattern = $caracteres;
 $max = strlen($pattern)-1;
 for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
 return $key;
}
function isPermiso($permiso)
{
	$admin = Sql_select('administradores',array('id_administrador' => $_SESSION['administrador']['id_administrador']),'=');
	$_SESSION['administrador'] = $admin[0];
	if(!isset($_SESSION['administrador'])){return false;}
	$status = strpos(' '.$_SESSION['administrador']['permisos'],'access_total');
	if($status)
	{
		return $status;
	}
	$status = strpos(' '.$_SESSION['administrador']['permisos'],$permiso);
	return $status;
}
?>
