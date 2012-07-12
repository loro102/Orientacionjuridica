<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

//******************************************************************************

//******************************************************************************
//******************************************************************************
//******************************************************************************
/*Hago una consulta para sacar el nombre del empleado*/
function obtenernombreusuario($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT empleados.empleado FROM empleados WHERE empleados.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['empleado'];
	mysql_free_result($consultafuncion);
}


//******************************************************************************

//******************************************************************************
//******************************************************************************
//******************************************************************************
/*Hago una consulta para sacar el nombre del agente*/
function obtenernombreagente($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT agentes.nombre_fiscal FROM agentes WHERE agentes.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['nombre_fiscal'];
	mysql_free_result($consultafuncion);
}
//******************************************************************************


//******************************************************************************
//******************************************************************************
//******************************************************************************
/*Hago una consulta para sacar el nombre del de la profesion*/
function obtenernombreprofesion($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT profesion.descripcion FROM profesion WHERE profesion.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['descripcion'];
	mysql_free_result($consultafuncion);
}
//******************************************************************************

//******************************************************************************
//******************************************************************************
//******************************************************************************
/*Hago una consulta para sacar datos del cliente*/
//Sacar nombre y apellidos
function obtenerdatosclientes($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT clientes.nombre,clientes.apellidos1,clientes.apellidos2 FROM clientes WHERE clientes.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['nombre'];
	echo "&nbsp;";
	echo $row_consultafuncion['apellidos1'];
	echo "&nbsp;";
	echo $row_consultafuncion['apellidos2'];
	mysql_free_result($consultafuncion);
}
//nif
function obtenerdatosnif($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT clientes.nif FROM clientes WHERE clientes.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['nif'];
	mysql_free_result($consultafuncion);
}
//direccion
function obtenerdireccion($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT clientes.direccion,clientes.codigopostal,clientes.localidad,clientes.provincia FROM clientes WHERE clientes.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['direccion'];
	echo "&nbsp;";
	echo $row_consultafuncion['codigopostal'];
	echo "&nbsp;";
	echo $row_consultafuncion['localidad'];
	echo "&nbsp;";
	echo $row_consultafuncion['provincia'];
	mysql_free_result($consultafuncion);
}
//fecha de nacimiento
function obtenernacimiento($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT clientes.fechanacimiento FROM clientes WHERE clientes.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

$oldDate = $row_consultafuncion['fechanacimiento'];; // DD/MM/YYYY
mysql_free_result($consultafuncion);
 
$parts = explode('-', $oldDate);
 
/**
* Now we have:
*   $parts[0]: the day
*   $parts[1]: the month
*   $parts[2]: the year
* We could also have done:
*   list($day, $month, $year) = explode('/', $oldDate);
*/
 
$newDate = "{$parts[2]}-{$parts[1]}-{$parts[0]}"; // MM-DD-YYYY
 
echo $newDate; // 08-18-2009
			
}



//Datos de contacto
function obtenertelefono1($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT clientes.telefono1 FROM clientes WHERE clientes.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['telefono1'];
	mysql_free_result($consultafuncion);
}
function obtenertelefono2($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT clientes.telefono2 FROM clientes WHERE clientes.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['telefono2'];
	mysql_free_result($consultafuncion);
}
function obtenerfax($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT clientes.fax FROM clientes WHERE clientes.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['fax'];
	mysql_free_result($consultafuncion);
}
function obtenermail($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT clientes.email FROM clientes WHERE clientes.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['email'];
	mysql_free_result($consultafuncion);
}
function obtenerprofesion($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT clientes.agente FROM clientes WHERE clientes.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);
	echo $profesion=obtenernombreagente($row_consultafuncion['agente']);
	mysql_free_result($consultafuncion);
}
//******************************************************************************

//******************************************************************************
//******************************************************************************
//******************************************************************************
/*Hago una consulta para sacar el nombre fiscal de la tabla profesionales*/
function obtenernombrefiscalprofesional($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT profesionales.nombre_fiscal FROM profesionales WHERE profesionales.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['nombre_fiscal'];
	mysql_free_result($consultafuncion);
}
//******************************************************************************

//******************************************************************************
//******************************************************************************
//******************************************************************************
/*Hago una consulta para sacar el nombre del sector de la tabla sector_profesional*/
function obtenernombresector($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT sector_profesional.descripcion FROM sector_profesional WHERE sector_profesional.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['sector_profesional.descripcion'];
	mysql_free_result($consultafuncion);
}
//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
/*Hago una consulta para sacar el nombre de la esècialidad de la tabla especialidad*/
function obtenernombrespecialidad($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT descripcion FROM especialidad WHERE id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['descripcion'];
	mysql_free_result($consultafuncion);
}
//******************************************************************************
//******************************************************************************
//******************************************************************************
//******************************************************************************
/*Formateo la fecha*/
function formatofecha($identificador)
{
	global $fecha;
$oldDate = $fecha; // DD/MM/YYYY
 
$parts = explode('-', $oldDate);
 
/**
* Now we have:
*   $parts[0]: the day
*   $parts[1]: the month
*   $parts[2]: the year
* We could also have done:
*   list($day, $month, $year) = explode('/', $oldDate);
*/
 
$newDate = "{$parts[2]}-{$parts[1]}-{$parts[0]}"; // MM-DD-YYYY
 
echo $newDate; // 08-18-2009
			
}
//******************************************************************************

//******************************************************************************
/*Hago una consulta para sacar el nombre comercial de la tabla profesionales*/
function obtenernombrecomercialprofesional($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT profesionales.nombre_comercial FROM profesionales WHERE profesionales.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['nombre_comercial'];
	mysql_free_result($consultafuncion);
}
//******************************************************************************


//******************************************************************************
/*Formateo la fecha*/
function formatoinsertfecha($identificador)
{
	global $fecha;
$oldDate = $fecha; // DD/MM/YYYY
 
$parts = explode('-', $oldDate);
 
/**
* Now we have:
*   $parts[0]: the day
*   $parts[1]: the month
*   $parts[2]: the year
* We could also have done:
*   list($day, $month, $year) = explode('/', $oldDate);
*/
 
$newDate = "{$parts[2]}-{$parts[1]}-{$parts[0]}"; // MM-DD-YYYY
 
echo $newDate; // 08-18-2009
			
}
//******************************************************************************

//******************************************************************************
/*Hago una consulta para sacar el nombre de la aseguradora de la tabla aseguradoras*/
function obtenercia($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT aseguradoras.nombre FROM aseguradoras WHERE aseguradoras.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['nombre'];
	mysql_free_result($consultafuncion);
}
//******************************************************************************

//******************************************************************************
/*Hago una consulta para sacar el nombre de la aseguradora de la tabla aseguradoras*/
function obtenertramitadorcia($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT tramitadores_cia.nombre,tramitadores_cia.apellido1,tramitadores_cia.apellido2 FROM tramitadores_cia WHERE tramitadores_cia.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['nombre'];
	echo "&nbsp;";
	echo $row_consultafuncion['apellido1'];
	echo "&nbsp;";
	echo $row_consultafuncion['apellido2'];
	mysql_free_result($consultafuncion);
}
//******************************************************************************

//******************************************************************************
/*Hago una consulta para sacar el nombre de la provincia*/
function obtenerprovincia($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT provincia.provincia FROM provincia WHERE provincia.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['provincia'];
	mysql_free_result($consultafuncion);
}
//******************************************************************************
//******************************************************************************
/*Hago una consulta para sacar el nombre de la localidad*/
function obtenerlocalidad($identificador)
{
	global $database_OJ, $OJ;
	mysql_select_db($database_OJ, $OJ);
	$query_consultafuncion = sprintf("SELECT localidad.localidad FROM localidad WHERE localidad.id=%s", $identificador);
	$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
	$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
	$totalRows_consultafuncion = mysql_num_rows($consultafuncion);

	echo $row_consultafuncion['localidad'];
	mysql_free_result($consultafuncion);
}
//******************************************************************************


?>
 
