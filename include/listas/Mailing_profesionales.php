<?php require_once('../../Connections/OJ.php'); ?>
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

$maxRows_mailing = 10;
$pageNum_mailing = 0;
if (isset($_GET['pageNum_mailing'])) {
  $pageNum_mailing = $_GET['pageNum_mailing'];
}
$startRow_mailing = $pageNum_mailing * $maxRows_mailing;

mysql_select_db($database_OJ, $OJ);
$query_mailing = "SELECT nombre, apellidos1, apellidos2, direccion, codigopostal, localidad, provincia, mailing FROM clientes WHERE mailing = 0 ORDER BY apellidos1 ASC";
$query_limit_mailing = sprintf("%s LIMIT %d, %d", $query_mailing, $startRow_mailing, $maxRows_mailing);
$mailing = mysql_query($query_limit_mailing, $OJ) or die(mysql_error());
$row_mailing = mysql_fetch_assoc($mailing);

if (isset($_GET['totalRows_mailing'])) {
  $totalRows_mailing = $_GET['totalRows_mailing'];
} else {
  $all_mailing = mysql_query($query_mailing);
  $totalRows_mailing = mysql_num_rows($all_mailing);
}

$pageNum_mailing = 0;
if (isset($_GET['pageNum_mailing'])) {
  $pageNum_mailing = $_GET['pageNum_mailing'];
}
$startRow_mailing = $pageNum_mailing * $maxRows_mailing;

mysql_select_db($database_OJ, $OJ);
$query_mailing = "SELECT nombre, apellidos1, apellidos2, direccion, codigopostal, localidad, provincia, mailing FROM clientes WHERE mailing = 1 ORDER BY apellidos1 ASC";
$query_limit_mailing = sprintf("%s LIMIT %d, %d", $query_mailing, $startRow_mailing, $maxRows_mailing);
$mailing = mysql_query($query_limit_mailing, $OJ) or die(mysql_error());
$row_mailing = mysql_fetch_assoc($mailing);

if (isset($_GET['totalRows_mailing'])) {
  $totalRows_mailing = $_GET['totalRows_mailing'];
} else {
  $all_mailing = mysql_query($query_mailing);
  $totalRows_mailing = mysql_num_rows($all_mailing);
}
$totalPages_mailing = ceil($totalRows_mailing/$maxRows_mailing)-1;$maxRows_mailing = 10;
$pageNum_mailing = 0;
if (isset($_GET['pageNum_mailing'])) {
  $pageNum_mailing = $_GET['pageNum_mailing'];
}
$startRow_mailing = $pageNum_mailing * $maxRows_mailing;

mysql_select_db($database_OJ, $OJ);
$query_mailing = "SELECT * FROM profesionales WHERE profesionales.mailing=1 ORDER BY profesionales.nombre_fiscal ASC";
$query_limit_mailing = sprintf("%s LIMIT %d, %d", $query_mailing, $startRow_mailing, $maxRows_mailing);
$mailing = mysql_query($query_limit_mailing, $OJ) or die(mysql_error());
$row_mailing = mysql_fetch_assoc($mailing);

if (isset($_GET['totalRows_mailing'])) {
  $totalRows_mailing = $_GET['totalRows_mailing'];
} else {
  $all_mailing = mysql_query($query_mailing);
  $totalRows_mailing = mysql_num_rows($all_mailing);
}
$totalPages_mailing = ceil($totalRows_mailing/$maxRows_mailing)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/Programa.dwt.php" codeOutsideHTMLIsLocked="false" -->
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction ="?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);

	
$logoutGoTo = "../../index.php";

 
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Documento sin t&iacute;tulo</title>
<link href="../../estilo/twoColLiqLtHdr.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 7]>
<style>
.content { margin-right: -1px; } /* este margen negativo de 1 px puede situarse en cualquiera de las columnas de este diseño con el mismo efecto corrector. */
ul.nav a { zoom: 1; }  /* la propiedad de zoom da a IE el desencadenante hasLayout que necesita para corregir el espacio en blanco extra existente entre los vínculos */
</style>
<![endif]-->
<style type="text/css">
body {
	background-color: #8090AB;
}
</style>
<script src="../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<!--calendario -->

<script src="../../calendario/js/jscal2.js"></script>
<script src="../../calendario/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../../calendario/css/jscal2.css" />
<link rel="stylesheet" type="text/css" href="../../calendario/css/border-radius.css" />
<link rel="stylesheet" type="text/css" href="../../calendario/css/steel/steel.css" />

<!--fin calendario -->
<!-- InstanceBeginEditable name="head" -->

<!-- InstanceEndEditable -->
</head>



<body >


<div class="container">
  <div class="header"><ul id="MenuBar1" class="MenuBarHorizontal">
    <li><a href="/principal.php">Inicio</a>      </li>
    <li><a class="MenuBarItemSubmenu" href="#">Clientes</a>
      <ul>
        <li><a href="../../Agentes/cliente.php">Consultar</a></li>
        <li><a href="../anadir/nuevo_cliente.php">A&ntilde;adir</a></li>
        <li><a href="/include/listas/Mailing_clientes.php">Mailing</a></li>
      </ul>
    </li>
    <li><a href="#" class="MenuBarItemSubmenu">Profesionales</a>
      <ul>
        <li><a href="../../Agentes/Profesionales.php">Consultar</a></li>
        <li><a href="/include/anadir/nuevo_profesional.php">A&ntilde;adir</a></li>
        <li><a href="Mailing_profesionales.php">Mailing</a></li>
      </ul>
    </li>
    <li><a href="#" class="MenuBarItemSubmenu">Agentes</a>
      <ul>
        <li><a href="../../Agentes/Agentesphp.php">Consultar</a></li>
        <li><a href="../../Agentes/Nuevoagente.php">A&ntilde;adir</a></li>
        <li><a href="Mailing_agentes.php">Mailing</a></li>
      </ul>
    </li>
    <li><a href="#" class="MenuBarItemSubmenu">alertas</a>
      <ul>
        <li><a href="/include/anadir/nueva_alerta.php">Alerta nueva</a></li>
        <li><a href="/include/listas/lista_alerta.php">Mostrar todas</a></li>
      </ul>
    </li>
    <li><a href="#" class="MenuBarItemSubmenu">Informes</a>
      <ul>
        <li><a href="#">Facturas</a></li>
      </ul>
    </li>
    <li><a href="#" class="MenuBarItemSubmenu">Configuracion</a>
      <ul>
        <li><a href="/include/listas/lista_profesion.php">Profesion</a></li>
        <li><a href="/include/listas/lista_vehiculo.php">Vehiculos</a></li>
        <li><a href="/include/listas/lista_especialidad.php">Especialidad</a></li>
        <li><a href="/include/listas/lista_banco.php">Bancos</a></li>
        <li><a href="/include/anadir/nuevo_profesional.php">Partidos Judiciales</a></li>
        <li><a href="#">Tipo Caso</a></li>
        <li><a href="#">Objetos Personales</a></li>
        <li><a href="/include/listas/lista_aseguradoras.php">Aseguradoras</a></li>
        <li><a href="#">Articulos</a></li>
        <li><a href="/include/listas/lista_fases.php">Fases</a></li>
        <li><a href="#">Formas de Pago</a></li>
      </ul>
    </li>
    <li><a href="<?php echo $logoutAction ?>">Desconectar</a></li>
  </ul>   <!-- end .header --></div>
  
  <div class="content"><!-- InstanceBeginEditable name="region editable" -->
    <h1>listado para mailing</h1>
    <table width="100%" border="1">
      <tr>
        <td>Nombre fiscal</td>
        <td>Nombre comercial</td>
        <td>CP</td>
        <td>Dirección</td>
        <td>Localidad</td>
        <td>Provincia</td>
      </tr>
      <?php do { ?>
        <tr>
          <td><?php echo $row_mailing['nombre_fiscal']; ?></td>
          <td><?php echo $row_mailing['nombre_comercial']; ?></td>
          <td><?php echo $row_mailing['cp']; ?></td>
          <td><?php echo $row_mailing['direccion']; ?></td>
          <td><?php echo $row_mailing['localidad']; ?></td>
          <td><?php echo $row_mailing['provincia']; ?></td>
        </tr>
        <?php } while ($row_mailing = mysql_fetch_assoc($mailing)); ?>
    </table>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    
  <!-- InstanceEndEditable -->
  <!-- end .content --></div>
  <div class="footer">
    <p>&nbsp;</p>
  <!-- end .footer --></div>
<!-- end .container --></div>

<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"../SpryAssets/SpryMenuBarDownHover.gif", imgRight:"../SpryAssets/SpryMenuBarRightHover.gif"});
</script>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($mailing);
?>
