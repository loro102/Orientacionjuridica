<?php echo $row_consulta_funcion['empleado']; ?>
<?php require_once('../Connections/OJ.php'); ?>
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

$varusuario_consulta_funcion = "0";
if (isset(xxxx)) {
  $varusuario_consulta_funcion = xxxx;
}
mysql_select_db($database_OJ, $OJ);
$query_consulta_funcion = sprintf("SELECT empleados.empleado FROM empleados WHERE empleados.id=%s", GetSQLValueString($varusuario_consulta_funcion, "int"));
$consulta_funcion = mysql_query($query_consulta_funcion, $OJ) or die(mysql_error());
$row_consulta_funcion = mysql_fetch_assoc($consulta_funcion);
$totalRows_consulta_funcion = mysql_num_rows($consulta_funcion);

mysql_free_result($consulta_funcion);
?>
