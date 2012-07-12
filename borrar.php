<?php require_once('Connections/OJ.php'); ?>
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

$varusuario_consultafuncion = "0";
if (isset(xxxx)) {
  $varusuario_consultafuncion = xxxx;
}
mysql_select_db($database_OJ, $OJ);
$query_consultafuncion = sprintf("SELECT empleados.empleado FROM empleados WHERE empleados.id=%s", GetSQLValueString($varusuario_consultafuncion, "int"));
$consultafuncion = mysql_query($query_consultafuncion, $OJ) or die(mysql_error());
$row_consultafuncion = mysql_fetch_assoc($consultafuncion);
$totalRows_consultafuncion = mysql_num_rows($consultafuncion);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<?php echo $row_consultafuncion['empleado']; ?>
</body>
</html>
<?php
mysql_free_result($consultafuncion);
?>
