<?php require_once('../../Connections/OJ.php'); ?>
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
if ($_POST['listo']>0)
{
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE clientes SET nombre=%s, apellidos1=%s, apellidos2=%s, direccion=%s, codigopostal=%s, provincia=%s, localidad=%s, nif=%s, fechanacimiento=%s, telefono1=%s, telefono2=%s, fax=%s, email=%s, notas=%s, profesion=%s, agente=%s, CCC=%s, mailing=%s WHERE id=%s",
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['apellidos1'], "text"),
                       GetSQLValueString($_POST['apellidos2'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['codigopostal'], "text"),
                       GetSQLValueString($_POST['provincia'], "text"),
                       GetSQLValueString($_POST['localidad'], "text"),
                       GetSQLValueString($_POST['nif'], "text"),
                       GetSQLValueString($_POST['fechanacimiento'], "date"),
                       GetSQLValueString($_POST['telefono1'], "text"),
                       GetSQLValueString($_POST['telefono2'], "text"),
                       GetSQLValueString($_POST['fax'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['notas'], "text"),
                       GetSQLValueString($_POST['profesion'], "int"),
                       GetSQLValueString($_POST['agente'], "int"),
                       GetSQLValueString($_POST['CCC'], "text"),
                       GetSQLValueString(isset($_POST['mailing']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_OJ, $OJ);
  $Result1 = mysql_query($updateSQL, $OJ) or die(mysql_error());

  $updateGoTo = "../fichas/ficha_cliente.php?clientid=".$_GET["clientid"];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
}
?>
<?php
$colname_Cliente = "-1";
if (isset($_GET['clientid'])) {
  $colname_Cliente = $_GET['clientid'];
}
mysql_select_db($database_OJ, $OJ);
$query_Cliente = sprintf("SELECT * FROM clientes WHERE id = %s", GetSQLValueString($colname_Cliente, "int"));
$Cliente = mysql_query($query_Cliente, $OJ) or die(mysql_error());
$row_Cliente = mysql_fetch_assoc($Cliente);
$totalRows_Cliente = mysql_num_rows($Cliente);

mysql_select_db($database_OJ, $OJ);
$query_provincia = "SELECT * FROM provincia ORDER BY provincia ASC";
$provincia = mysql_query($query_provincia, $OJ) or die(mysql_error());
$row_provincia = mysql_fetch_assoc($provincia);
$totalRows_provincia = mysql_num_rows($provincia);

mysql_select_db($database_OJ, $OJ);
$query_localidad = "SELECT * FROM localidad ORDER BY localidad ASC";
$localidad = mysql_query($query_localidad, $OJ) or die(mysql_error());
$row_localidad = mysql_fetch_assoc($localidad);
$totalRows_localidad = mysql_num_rows($localidad);

mysql_select_db($database_OJ, $OJ);
$query_profesion = "SELECT * FROM profesion ORDER BY descripcion ASC";
$profesion = mysql_query($query_profesion, $OJ) or die(mysql_error());
$row_profesion = mysql_fetch_assoc($profesion);
$totalRows_profesion = mysql_num_rows($profesion);

mysql_select_db($database_OJ, $OJ);
$query_Agente = "SELECT * FROM agentes";
$Agente = mysql_query($query_Agente, $OJ) or die(mysql_error());
$row_Agente = mysql_fetch_assoc($Agente);
$totalRows_Agente = mysql_num_rows($Agente);
?>
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
       
  <h1>Editar Cliente</h1>
  <p>&nbsp;</p>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Nombre:</td>
        <td><input name="nombre" type="text"  value="<?php echo htmlentities($row_Cliente['nombre'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Apellidos1:</td>
        <td><input type="text" name="apellidos1"  value="<?php echo htmlentities($row_Cliente['apellidos1'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Apellidos2:</td>
        <td><input type="text"  name="apellidos2" value="<?php echo htmlentities($row_Cliente['apellidos2'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Direccion:</td>
        <td><input type="text"  name="direccion" value="<?php echo htmlentities($row_Cliente['direccion'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Codigopostal:</td>
        <td><input name="codigopostal" type="text" value="<?php echo htmlentities($row_Cliente['codigopostal'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Provincia:</td>
        <td><select name="provincia" >
          <?php
do {  
?>
          <option value="<?php echo $row_provincia['id']?>"<?php if (!(strcmp($row_provincia['id'], htmlentities($row_Cliente['provincia'], ENT_COMPAT, 'UTF-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_provincia['provincia']?></option>
          <?php
} while ($row_provincia = mysql_fetch_assoc($provincia));
  $rows = mysql_num_rows($provincia);
  if($rows > 0) {
      mysql_data_seek($provincia, 0);
	  $row_provincia = mysql_fetch_assoc($provincia);
  }
?>
        </select>
          <a href="../listas/lugares.php" target="_new">Buscar</a></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Localidad:</td>
        <td><select name="localidad" >
          <?php
do {  
?>
          <option value="<?php echo $row_localidad['id']?>"<?php if (!(strcmp($row_localidad['id'], htmlentities($row_Cliente['localidad'], ENT_COMPAT, 'UTF-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_localidad['localidad']?></option>
          <?php
} while ($row_localidad = mysql_fetch_assoc($localidad));
  $rows = mysql_num_rows($localidad);
  if($rows > 0) {
      mysql_data_seek($localidad, 0);
	  $row_localidad = mysql_fetch_assoc($localidad);
  }
?>
        </select>
          <a href="../listas/localidad.php" target="_new">Buscar</a></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Nif:</td>
        <td><input type="text"  name="nif" value="<?php echo htmlentities($row_Cliente['nif'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Fechanacimiento:</td>
        <td><input type="text"  name="fechanacimiento" value="<?php echo htmlentities($row_Cliente['fechanacimiento'], ENT_COMPAT, 'UTF-8'); ?>" size="32" id="f_date3" value="" size="32" /><button id="f_btn3">...</button><br />
    <script type="text/javascript">//<![CDATA[
      Calendar.setup({
        inputField : "f_date3",
        trigger    : "f_btn3",
        onSelect   : function() { this.hide() },
        showTime   : 12,
        dateFormat : "%Y-%m-%d"
      });
    //]]></script></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Telefono1:</td>
        <td><input type="text"  name="telefono1" value="<?php echo htmlentities($row_Cliente['telefono1'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Telefono2:</td>
        <td><input type="text"  name="telefono2" value="<?php echo htmlentities($row_Cliente['telefono2'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Fax:</td>
        <td><input type="text" name="fax" value="<?php echo htmlentities($row_Cliente['fax'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Email:</td>
        <td><input type="text"  name="email" value="<?php echo htmlentities($row_Cliente['email'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right" valign="top">Notas:</td>
        <td><textarea name="notas"  cols="50" rows="5"><?php echo htmlentities($row_Cliente['notas'], ENT_COMPAT, 'UTF-8'); ?></textarea></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Profesion:</td>
        <td><select name="profesion" >
          <?php
do {  
?>
          <option value="<?php echo $row_profesion['id']?>"<?php if (!(strcmp($row_profesion['id'], htmlentities($row_Cliente['profesion'], ENT_COMPAT, 'UTF-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_profesion['descripcion']?></option>
          <?php
} while ($row_profesion = mysql_fetch_assoc($profesion));
  $rows = mysql_num_rows($profesion);
  if($rows > 0) {
      mysql_data_seek($profesion, 0);
	  $row_profesion = mysql_fetch_assoc($profesion);
  }
?>
        </select>
          <a href="../../Agentes/Agentesphp.php" target="_new">buscar</a></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Agente:</td>
        <td><select name="agente" >
          <?php
do {  
?>
          <option value="<?php echo $row_Agente['id']?>"<?php if (!(strcmp($row_Agente['id'], htmlentities($row_Cliente['agente'], ENT_COMPAT, 'UTF-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_Agente['nombre_fiscal']?></option>
          <?php
} while ($row_Agente = mysql_fetch_assoc($Agente));
  $rows = mysql_num_rows($Agente);
  if($rows > 0) {
      mysql_data_seek($Agente, 0);
	  $row_Agente = mysql_fetch_assoc($Agente);
  }
?>
        </select>
          <a href="../../Agentes/Agentesphp.php" target="_new">buscar</a></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">CCC:</td>
        <td><input type="text"  name="CCC" value="<?php echo htmlentities($row_Cliente['CCC'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Mailing:</td>
        <td><input type="checkbox"  name="mailing" value="1" <?php if (!(strcmp(htmlentities($row_Cliente['mailing'],ENT_COMPAT, 'UTF-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">Confirmar:</td>
        <td><select name="listo" id="listo" onfocus="onfocus=&quot;submit()&quot;" onchange="onfocus=&quot;submit()&quot;">
          <option value="0">No</option>
          <option value="1">Si</option>
        </select></td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Actualizar registro" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="id" value="<?php echo $row_Cliente['id']; ?>" />
  </form>
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
mysql_free_result($Cliente);

mysql_free_result($provincia);

mysql_free_result($localidad);

mysql_free_result($profesion);

mysql_free_result($Agente);
?>
