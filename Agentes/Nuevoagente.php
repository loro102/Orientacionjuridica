<?php require_once('../Connections/OJ.php'); ?>
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

	
$logoutGoTo = "../index.php";

 
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Documento sin t&iacute;tulo</title>
<link href="../estilo/twoColLiqLtHdr.css" rel="stylesheet" type="text/css" />
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
<script src="../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<!--calendario -->

<script src="../calendario/js/jscal2.js"></script>
<script src="../calendario/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
<link rel="stylesheet" type="text/css" href="../calendario/css/border-radius.css" />
<link rel="stylesheet" type="text/css" href="../calendario/css/steel/steel.css" />

<!--fin calendario -->
<!-- InstanceBeginEditable name="head" -->
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
?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO agentes (nombre_fiscal, nombre_comercial, apellido1, apellido2, CIF, direccion, codigo_postal, localidad, provincia, telefono1, telefono2, fax, email, notas, notas2, comercial, profesion, ccc, mailing, homologado, placa, contrato) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nombre_fiscal'], "text"),
                       GetSQLValueString($_POST['nombre_comercial'], "text"),
                       GetSQLValueString($_POST['apellido1'], "text"),
                       GetSQLValueString($_POST['apellido2'], "text"),
                       GetSQLValueString($_POST['CIF'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['codigo_postal'], "text"),
                       GetSQLValueString($_POST['localidad'], "int"),
                       GetSQLValueString($_POST['provincia'], "int"),
                       GetSQLValueString($_POST['telefono1'], "text"),
                       GetSQLValueString($_POST['telefono2'], "text"),
                       GetSQLValueString($_POST['fax'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['notas'], "text"),
                       GetSQLValueString($_POST['notas2'], "text"),
                       GetSQLValueString($_POST['comercial'], "int"),
                       GetSQLValueString($_POST['profesion'], "int"),
                       GetSQLValueString($_POST['ccc'], "int"),
                       GetSQLValueString(isset($_POST['mailing']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['homologado']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['placa'], "int"),
                       GetSQLValueString(isset($_POST['contrato']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_OJ, $OJ);
  $Result1 = mysql_query($insertSQL, $OJ) or die(mysql_error());

  $insertGoTo = "Agentesphp.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_OJ, $OJ);
$query_Provincia = "SELECT * FROM provincia";
$Provincia = mysql_query($query_Provincia, $OJ) or die(mysql_error());
$row_Provincia = mysql_fetch_assoc($Provincia);
$totalRows_Provincia = mysql_num_rows($Provincia);

$colname_Localidad = "-1";
if (isset($_POST['provincia'])) {
  $colname_Localidad = $_POST['provincia'];
}
mysql_select_db($database_OJ, $OJ);
$query_Localidad = sprintf("SELECT * FROM localidad WHERE id_provincia = %s", GetSQLValueString($colname_Localidad, "int"));
$Localidad = mysql_query($query_Localidad, $OJ) or die(mysql_error());
$row_Localidad = mysql_fetch_assoc($Localidad);
$totalRows_Localidad = mysql_num_rows($Localidad);
?>
<!-- InstanceEndEditable -->
</head>



<body >


<div class="container">
  <div class="header"><ul id="MenuBar1" class="MenuBarHorizontal">
    <li><a href="/principal.php">Inicio</a>      </li>
    <li><a class="MenuBarItemSubmenu" href="#">Clientes</a>
      <ul>
        <li><a href="cliente.php">Consultar</a></li>
        <li><a href="../include/anadir/nuevo_cliente.php">A&ntilde;adir</a></li>
        <li><a href="/include/listas/Mailing_clientes.php">Mailing</a></li>
      </ul>
    </li>
    <li><a href="#" class="MenuBarItemSubmenu">Profesionales</a>
      <ul>
        <li><a href="Profesionales.php">Consultar</a></li>
        <li><a href="/include/anadir/nuevo_profesional.php">A&ntilde;adir</a></li>
        <li><a href="../include/listas/Mailing_profesionales.php">Mailing</a></li>
      </ul>
    </li>
    <li><a href="#" class="MenuBarItemSubmenu">Agentes</a>
      <ul>
        <li><a href="Agentesphp.php">Consultar</a></li>
        <li><a href="Nuevoagente.php">A&ntilde;adir</a></li>
        <li><a href="../include/listas/Mailing_agentes.php">Mailing</a></li>
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
    <h1>Nuevo Agente</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nombre Comercial:</td>
          <td><input type="text" name="nombre_comercial" value="<?php $_POST['nombre_comercial'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nombre Fiscal:</td>
          <td><input type="text" name="nombre_fiscal" value="<?php $_POST['nombre_fiscal'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Apellido1:</td>
          <td><input type="text" name="apellido1" value="<?php $_POST['apellido1'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Apellido2:</td>
          <td><input type="text" name="apellido2" value="<?php $_POST['apellido2'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">CIF:</td>
          <td><input type="text" name="CIF" value="<?php $_POST['CIF'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Direccion:</td>
          <td><input type="text" name="direccion" value="<?php $_POST['direccion'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Codigo postal:</td>
          <td><input type="text" name="codigo_postal" value="<?php $_POST['codigo_postal'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Provincia:</td>
          <td><select name="provincia2">
            <option value="0"  <?php if (!(strcmp(0, $_POST['provincia']))) {echo "selected=\"selected\"";} ?>>Ninguno</option>
            <?php
do {  
?>
            <option value="<?php echo $row_Provincia['id']?>"<?php if (!(strcmp($row_Provincia['id'], $_POST['provincia']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Provincia['provincia']?></option>
            <?php
} while ($row_Provincia = mysql_fetch_assoc($Provincia));
  $rows = mysql_num_rows($Provincia);
  if($rows > 0) {
      mysql_data_seek($Provincia, 0);
	  $row_Provincia = mysql_fetch_assoc($Provincia);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Localidad:</td>
          <td><select name="localidad2">
            <option value="0"  <?php if (!(strcmp(0, $_POST['localidad']))) {echo "selected=\"selected\"";} ?>>Ninguno</option>
            <?php
do {  
?>
            <option value="<?php echo $row_Localidad['id']?>"<?php if (!(strcmp($row_Localidad['id'], $_POST['localidad']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Localidad['localidad']?></option>
            <?php
} while ($row_Localidad = mysql_fetch_assoc($Localidad));
  $rows = mysql_num_rows($Localidad);
  if($rows > 0) {
      mysql_data_seek($Localidad, 0);
	  $row_Localidad = mysql_fetch_assoc($Localidad);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Telefono1:</td>
          <td><input type="text" name="telefono1" value="<?php $_POST['telefono1'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Telefono2:</td>
          <td><input type="text" name="telefono2" value="<?php $_POST['telefono2'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fax:</td>
          <td><input type="text" name="fax" value="<?php $_POST['fax'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Email:</td>
          <td><input type="text" name="email" value="<?php $_POST['email'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Notas:</td>
          <td><input type="text" name="notas" value="<?php $_POST['notas'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Notas2:</td>
          <td><input type="text" name="notas2" value="<?php $_POST['notas2'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Comercial:</td>
          <td><input type="text" name="comercial" value="<?php $_POST['comercial'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Profesion:</td>
          <td><input type="text" name="profesion" value="<?php $_POST['profesion'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">CCC:</td>
          <td><input type="text" name="ccc" value="<?php $_POST['ccc'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Mailing:</td>
          <td><input <?php if (!(strcmp("$_POST[\'mailing\']",1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="mailing" value="1" checked="checked" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Homologado:</td>
          <td><input <?php if (!(strcmp("$_POST[\'homologado\']",1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="homologado" value="" checked="checked" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Placa:</td>
          <td><input <?php if (!(strcmp("$_POST[\'placa\']",1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="placa" id="placa" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Contrato:</td>
          <td><input <?php if (!(strcmp("$_POST[\'provincia\']",1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="contrato" value="" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insertar registro" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
    </form>
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
mysql_free_result($Provincia);

mysql_free_result($Localidad);
?>
