<?php include_once('../../Connections/OJ.php'); ?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE contrario SET nombre=%s, apellido1=%s, apellido2=%s, dni=%s, direccion=%s, cp=%s, localidad=%s, provincia=%s, fecha_nacimiento=%s, tel1=%s, tel2=%s, fax=%s, email=%s, cia=%s, numeropoliza=%s, vehiculo=%s, matricula=%s, conductor=%s, propietario=%s, tomador=%s, culpable=%s WHERE id=%s",
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['apellido1'], "text"),
                       GetSQLValueString($_POST['apellido2'], "text"),
                       GetSQLValueString($_POST['dni'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['cp'], "text"),
                       GetSQLValueString($_POST['localidad'], "text"),
                       GetSQLValueString($_POST['provincia'], "text"),
                       GetSQLValueString($_POST['fecha_nacimiento'], "date"),
                       GetSQLValueString($_POST['tel1'], "text"),
                       GetSQLValueString($_POST['tel2'], "text"),
                       GetSQLValueString($_POST['fax'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['cia'], "int"),
                       GetSQLValueString($_POST['numeropoliza'], "text"),
                       GetSQLValueString($_POST['vehiculo'], "int"),
                       GetSQLValueString($_POST['matricula'], "text"),
                       GetSQLValueString($_POST['conductor'], "text"),
                       GetSQLValueString($_POST['propietario'], "text"),
                       GetSQLValueString($_POST['tomador'], "text"),
                       GetSQLValueString($_POST['culpable'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_OJ, $OJ);
  $Result1 = mysql_query($updateSQL, $OJ) or die(mysql_error());

  $updateGoTo = "/include/vista_datos/datos_cliente.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE contrario SET nombre=%s, apellido1=%s, apellido2=%s, dni=%s, direccion=%s, cp=%s, localidad=%s, provincia=%s, fecha_nacimiento=%s, tel1=%s, tel2=%s, fax=%s, email=%s, cia=%s, numeropoliza=%s, vehiculo=%s, matricula=%s, conductor=%s, propietario=%s, tomador=%s, culpable=%s WHERE id=%s",
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['apellido1'], "text"),
                       GetSQLValueString($_POST['apellido2'], "text"),
                       GetSQLValueString($_POST['dni'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['cp'], "text"),
                       GetSQLValueString($_POST['localidad'], "text"),
                       GetSQLValueString($_POST['provincia'], "text"),
                       GetSQLValueString($_POST['fecha_nacimiento'], "date"),
                       GetSQLValueString($_POST['tel1'], "text"),
                       GetSQLValueString($_POST['tel2'], "text"),
                       GetSQLValueString($_POST['fax'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['cia'], "int"),
                       GetSQLValueString($_POST['numeropoliza'], "text"),
                       GetSQLValueString($_POST['vehiculo'], "int"),
                       GetSQLValueString($_POST['matricula'], "text"),
                       GetSQLValueString($_POST['conductor'], "text"),
                       GetSQLValueString($_POST['propietario'], "text"),
                       GetSQLValueString($_POST['tomador'], "text"),
                       GetSQLValueString($_POST['culpable'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_OJ, $OJ);
  $Result1 = mysql_query($updateSQL, $OJ) or die(mysql_error());

  $updateGoTo = "/include/vista_datos/datos_cliente.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$sinistro_contario = "0";
if (isset($_GET["sini"])) {
  $sinistro_contario = $_GET["sini"];
}
mysql_select_db($database_OJ, $OJ);
$query_contario = sprintf("SELECT * FROM contrario WHERE contrario.id=%s", GetSQLValueString($sinistro_contario, "int"));
$contario = mysql_query($query_contario, $OJ) or die(mysql_error());
$row_contario = mysql_fetch_assoc($contario);
$totalRows_contario = mysql_num_rows($contario);

mysql_select_db($database_OJ, $OJ);
$query_vahiculo = "SELECT * FROM vehiculo";
$vahiculo = mysql_query($query_vahiculo, $OJ) or die(mysql_error());
$row_vahiculo = mysql_fetch_assoc($vahiculo);
$totalRows_vahiculo = mysql_num_rows($vahiculo);
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
    <h1>editar contrario</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    </form>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nombre:</td>
          <td><input type="text" name="nombre" value="<?php echo htmlentities($row_contario['nombre'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Apellido1:</td>
          <td><input type="text" name="apellido1" value="<?php echo htmlentities($row_contario['apellido1'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Apellido2:</td>
          <td><input type="text" name="apellido2" value="<?php echo htmlentities($row_contario['apellido2'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Dni:</td>
          <td><input type="text" name="dni" value="<?php echo htmlentities($row_contario['dni'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Direccion:</td>
          <td><input type="text" name="direccion" value="<?php echo htmlentities($row_contario['direccion'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Cp:</td>
          <td><input type="text" name="cp" value="<?php echo htmlentities($row_contario['cp'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Localidad:</td>
          <td><input type="text" name="localidad" value="<?php echo htmlentities($row_contario['localidad'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Provincia:</td>
          <td><input type="text" name="provincia" value="<?php echo htmlentities($row_contario['provincia'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha_nacimiento:</td>
          <td><input type="text" name="fecha_nacimiento" value="<?php echo htmlentities($row_contario['fecha_nacimiento'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tel1:</td>
          <td><input type="text" name="tel1" value="<?php echo htmlentities($row_contario['tel1'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tel2:</td>
          <td><input type="text" name="tel2" value="<?php echo htmlentities($row_contario['tel2'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fax:</td>
          <td><input type="text" name="fax" value="<?php echo htmlentities($row_contario['fax'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Email:</td>
          <td><input type="text" name="email" value="<?php echo htmlentities($row_contario['email'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Cia:</td>
          <td><select name="cia">
            <option value="" >elemento1</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Numeropoliza:</td>
          <td><input type="text" name="numeropoliza" value="<?php echo htmlentities($row_contario['numeropoliza'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Vehiculo:</td>
          <td><select name="vehiculo">
            <?php 
do {  
?>
            <option value="<?php echo $row_vahiculo['id']?>" <?php if (!(strcmp($row_vahiculo['id'], htmlentities($row_contario['vehiculo'], ENT_COMPAT, 'UTF-8')))) {echo "SELECTED";} ?>><?php echo $row_vahiculo['marca']?></option>
            <?php
} while ($row_vahiculo = mysql_fetch_assoc($vahiculo));
?>
          </select></td>
        </tr>
        <tr> </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Matricula:</td>
          <td><input type="text" name="matricula" value="<?php echo htmlentities($row_contario['matricula'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Conductor:</td>
          <td><input type="text" name="conductor" value="<?php echo htmlentities($row_contario['conductor'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Propietario:</td>
          <td><input type="text" name="propietario" value="<?php echo htmlentities($row_contario['propietario'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tomador:</td>
          <td><input type="text" name="tomador" value="<?php echo htmlentities($row_contario['tomador'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Culpable:</td>
          <td><input type="text" name="culpable" value="<?php echo htmlentities($row_contario['culpable'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Actualizar registro" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form2" />
      <input type="hidden" name="id" value="<?php echo $row_contario['id']; ?>" />
    </form>
    <p>&nbsp;</p>
<p>&nbsp;</p>
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
mysql_free_result($contario);

mysql_free_result($vahiculo);
?>
