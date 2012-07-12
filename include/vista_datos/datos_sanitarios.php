<?php require_once('../../Connections/OJ.php'); ?>
<?php require_once('../../Connections/OJ.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../../index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$fecha=$_POST['fecha'];
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO datos_medicos (id_siniestro, fecha, profesional, motivo) VALUES (%s, ".formatoinsertfecha($fecha).", %s, %s)",
                       GetSQLValueString($_POST['id_siniestro'], "int"),
                       //GetSQLValueString($_POST['fecha'], "date"),
                       GetSQLValueString($_POST['profesional'], "int"),
                       GetSQLValueString($_POST['motivo'], "text"));

  mysql_select_db($database_OJ, $OJ);
  $Result1 = mysql_query($insertSQL, $OJ) or die(mysql_error());
}

mysql_select_db($database_OJ, $OJ);
$query_datos = "SELECT * FROM datos_medicos";
$datos = mysql_query($query_datos, $OJ) or die(mysql_error());
$row_datos = mysql_fetch_assoc($datos);
$totalRows_datos = "0";
if (isset($_GET['sinistro'])) {
  $totalRows_datos = $_GET['sinistro'];
}

mysql_select_db($database_OJ, $OJ);
$query_datos = sprintf("SELECT * FROM datos_medicos WHERE datos_medicos.id_siniestro=%s", GetSQLValueString($sinistro_datos, "int"));
$datos = mysql_query($query_datos, $OJ) or die(mysql_error());
$row_datos = mysql_fetch_assoc($datos);

mysql_select_db($database_OJ, $OJ);
$query_datos = sprintf("SELECT * FROM datos_medicos WHERE datos_medicos.id_siniestro=%s", GetSQLValueString($sinistro_datos, "int"));
$datos = mysql_query($query_datos, $OJ) or die(mysql_error());
$row_datos = mysql_fetch_assoc($datos);
$totalRows_datos = mysql_num_rows($datos);$sinistro_datos = "0";
if (isset($_GET["sinistro"])) {
  $sinistro_datos = $_GET["sinistro"];
}
mysql_select_db($database_OJ, $OJ);
$query_datos = sprintf("SELECT * FROM datos_medicos WHERE datos_medicos.id_siniestro=%s", GetSQLValueString($sinistro_datos, "int"));
$datos = mysql_query($query_datos, $OJ) or die(mysql_error());
$row_datos = mysql_fetch_assoc($datos);
$totalRows_datos = mysql_num_rows($datos);

mysql_select_db($database_OJ, $OJ);
$query_profesionales = "SELECT * FROM profesionales";
$profesionales = mysql_query($query_profesionales, $OJ) or die(mysql_error());
$row_profesionales = mysql_fetch_assoc($profesionales);
$totalRows_profesionales = mysql_num_rows($profesionales);
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
        <li><a href="../listas/Mailing_profesionales.php">Mailing</a></li>
      </ul>
    </li>
    <li><a href="#" class="MenuBarItemSubmenu">Agentes</a>
      <ul>
        <li><a href="../../Agentes/Agentesphp.php">Consultar</a></li>
        <li><a href="../../Agentes/Nuevoagente.php">A&ntilde;adir</a></li>
        <li><a href="../listas/Mailing_agentes.php">Mailing</a></li>
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
    <div class="textotabla">
      <div class="textotabla">
        <table width="100%" border="1">
          <tr bgcolor="#FFFF66">
            <td><a href="/include/vista_datos/datos_cliente.php?sinistro=<?php echo $_GET['sinistro']; ?>">Datos Siniestro</a></td>
            <td><a href="/include/vista_datos/profesionales.php?sinistro=<?php echo $_GET['sinistro']; ?>">Profesionales</a></td>
            <td><a href="/include/vista_datos/facturas.php?sinistro=<?php echo $_GET['sinistro'];?>">Facturas</a></td>
            <td>Datos Sanitarios</td>
            <td>Indemnización</td>
            <td>Documentacion</td>
            <td><a href="/include/vista_datos/notas_expediente.php?sinistro=<?php echo $_GET['sinistro']; ?>">Notas</a></td>
          </tr>
        </table>
      </div>
      <?php 
	
		 ?>
    </div>
    <h1>Datos Sanitarios    </h1>
<p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha:</td>
          <td><input name="fecha" type="text" disabled="disabled" id="f_date3" value="" size="32" /><button id="f_btn3">...</button><br />
    <script type="text/javascript">//<![CDATA[
      Calendar.setup({
        inputField : "f_date3",
        trigger    : "f_btn3",
        onSelect   : function() { this.hide() },
        showTime   : 12,
        dateFormat : "%d-%m-%Y"
      });
    //]]></script></td></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Profesional:</td>
          <td><select name="profesional">
            <option value="" >Ninguno</option>
            <?php
do {  
?>
            <option value="<?php echo $row_profesionales['id']?>"><?php echo $row_profesionales['nombre_comercial']?></option>
            <?php
} while ($row_profesionales = mysql_fetch_assoc($profesionales));
  $rows = mysql_num_rows($profesionales);
  if($rows > 0) {
      mysql_data_seek($profesionales, 0);
	  $row_profesionales = mysql_fetch_assoc($profesionales);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right" valign="top">Motivo:</td>
          <td><textarea name="motivo" cols="50" rows="5"></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insertar registro" /></td>
        </tr>
      </table>
      <input type="hidden" name="id_siniestro" value="<?php echo $_GET['sinistro']; ?>" />
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
    <?php if ($totalRows_datos == 0) { // Show if recordset empty ?>
  <p>No hay datos introducidos</p>
  <?php } // Show if recordset empty ?>
    <?php if ($totalRows_datos > 0) { // Show if recordset not empty ?>
      <table width="100%" border="1">
        <tr>
          <td>Fecha</td>
          <td>Profesionales</td>
          <td>Motivo</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php 
		 $fecha = $row_datos['fecha'];
		  echo formatoinsertfecha($fecha); ?></td>
            <td><?php echo obtenernombrecomercialprofesional($row_datos['profesional']); ?></td>
            <td><?php echo $row_datos['motivo']; ?></td>
          </tr>
          <?php } while ($row_datos = mysql_fetch_assoc($datos)); ?>
      </table>
      <p>&nbsp;</p>
      <?php } // Show if recordset not empty ?>
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
mysql_free_result($datos);

mysql_free_result($profesionales);
?>
