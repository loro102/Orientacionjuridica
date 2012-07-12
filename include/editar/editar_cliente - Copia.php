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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE clientes SET nombre=%s, apellidos1=%s, apellidos2=%s, direccion=%s, codigopostal=%s, localidad=%s, provincia=%s, nif=%s, fechanacimiento=%s, fecha_abono=%s, telefono1=%s, telefono2=%s, fax=%s, email=%s, notas=%s, profesion=%s, agente=%s, precio=%s, CCC=%s, mailing=%s, id_empleado=%s WHERE id=%s",
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['apellidos1'], "text"),
                       GetSQLValueString($_POST['apellidos2'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['codigopostal'], "text"),
                       GetSQLValueString($_POST['localidad'], "text"),
                       GetSQLValueString($_POST['provincia'], "text"),
                       GetSQLValueString($_POST['nif'], "text"),
                       GetSQLValueString($_POST['fechanacimiento'], "date"),
                       GetSQLValueString($_POST['fecha_abono'], "date"),
                       GetSQLValueString($_POST['telefono1'], "text"),
                       GetSQLValueString($_POST['telefono2'], "text"),
                       GetSQLValueString($_POST['fax'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['notas'], "text"),
                       GetSQLValueString($_POST['profesion'], "int"),
                       GetSQLValueString($_POST['agente'], "int"),
                       GetSQLValueString($_POST['precio'], "text"),
                       GetSQLValueString($_POST['CCC'], "text"),
                       GetSQLValueString(isset($_POST['mailing']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['id_empleado'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_OJ, $OJ);
  $Result1 = mysql_query($updateSQL, $OJ) or die(mysql_error());

  $updateGoTo = "../fichas/ficha_cliente.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_OJ, $OJ);
$query_clientes = "SELECT * FROM clientes";
$clientes = mysql_query($query_clientes, $OJ) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);$clientid_clientes = "0";
if (isset($_GET["clientid"])) {
  $clientid_clientes = $_GET["clientid"];
}
mysql_select_db($database_OJ, $OJ);
$query_clientes = sprintf("SELECT * FROM clientes WHERE clientes.id=%s", GetSQLValueString($clientid_clientes, "int"));
$clientes = mysql_query($query_clientes, $OJ) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

mysql_select_db($database_OJ, $OJ);
$query_profesion = "SELECT * FROM profesion";
$profesion = mysql_query($query_profesion, $OJ) or die(mysql_error());
$row_profesion = mysql_fetch_assoc($profesion);
$totalRows_profesion = mysql_num_rows($profesion);

mysql_select_db($database_OJ, $OJ);
$query_Agente = "SELECT * FROM agentes";
$Agente = mysql_query($query_Agente, $OJ) or die(mysql_error());
$row_Agente = mysql_fetch_assoc($Agente);
$totalRows_Agente = mysql_num_rows($Agente);
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
    <h1>Actualizar cliente</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nombre:</td>
          <td><input type="text" name="nombre" value="<?php echo htmlentities($row_clientes['nombre'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Apellidos1:</td>
          <td><input type="text" name="apellidos1" value="<?php echo htmlentities($row_clientes['apellidos1'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Apellidos2:</td>
          <td><input type="text" name="apellidos2" value="<?php echo htmlentities($row_clientes['apellidos2'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Direccion:</td>
          <td><input type="text" name="direccion" value="<?php echo htmlentities($row_clientes['direccion'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Codigopostal:</td>
          <td><input type="text" name="codigopostal" value="<?php echo htmlentities($row_clientes['codigopostal'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Localidad:</td>
          <td><input type="text" name="localidad" value="<?php echo htmlentities($row_clientes['localidad'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Provincia:</td>
          <td><input type="text" name="provincia" value="<?php echo htmlentities($row_clientes['provincia'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nif:</td>
          <td><input type="text" name="nif" value="<?php echo htmlentities($row_clientes['nif'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fechanacimiento:</td>
          <td><input type="text" name="fechanacimiento" value="<?php echo htmlentities($row_clientes['fechanacimiento'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha_abono:</td>
          <td><input type="text" name="fecha_abono" value="<?php echo htmlentities($row_clientes['fecha_abono'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Telefono1:</td>
          <td><input type="text" name="telefono1" value="<?php echo htmlentities($row_clientes['telefono1'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Telefono2:</td>
          <td><input type="text" name="telefono2" value="<?php echo htmlentities($row_clientes['telefono2'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fax:</td>
          <td><input type="text" name="fax" value="<?php echo htmlentities($row_clientes['fax'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Email:</td>
          <td><input type="text" name="email" value="<?php echo htmlentities($row_clientes['email'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right" valign="top">Notas:</td>
          <td><textarea name="notas" cols="50" rows="5"><?php echo htmlentities($row_clientes['notas'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Profesion:</td>
          <td><select name="profesion">
            <?php
do {  
?>
            <option value="<?php echo $row_profesion['id']?>"<?php if (!(strcmp($row_profesion['id'], htmlentities($row_clientes['profesion'], ENT_COMPAT, 'UTF-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_profesion['descripcion']?></option>
            <?php
} while ($row_profesion = mysql_fetch_assoc($profesion));
  $rows = mysql_num_rows($profesion);
  if($rows > 0) {
      mysql_data_seek($profesion, 0);
	  $row_profesion = mysql_fetch_assoc($profesion);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Agente:</td>
          <td><select name="agente">
            <?php
do {  
?>
            <option value="<?php echo $row_Agente['id']?>"<?php if (!(strcmp($row_Agente['id'], htmlentities($row_clientes['agente'], ENT_COMPAT, 'UTF-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_Agente['nombre_comercial']?></option>
            <?php
} while ($row_Agente = mysql_fetch_assoc($Agente));
  $rows = mysql_num_rows($Agente);
  if($rows > 0) {
      mysql_data_seek($Agente, 0);
	  $row_Agente = mysql_fetch_assoc($Agente);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Precio:</td>
          <td><input type="text" name="precio" value="<?php echo htmlentities($row_clientes['precio'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">CCC:</td>
          <td><input type="text" name="CCC" value="<?php echo htmlentities($row_clientes['CCC'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Mailing:</td>
          <td><input type="checkbox" name="mailing" value="1"  <?php if (!(strcmp(htmlentities($row_clientes['mailing'], ENT_COMPAT, 'UTF-8'),""))) {echo "checked=\"checked\"";} ?> /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Actualizar registro" /></td>
        </tr>
      </table>
      <input type="hidden" name="id_empleado" value="<?php echo htmlentities($row_clientes['id_empleado'], ENT_COMPAT, 'UTF-8'); ?>" />
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="id" value="<?php echo $row_clientes['id']; ?>" />
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
mysql_free_result($clientes);

mysql_free_result($profesion);

mysql_free_result($Agente);
?>
