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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE profesionales SET nombre_fiscal=%s, nombre_comercial=%s, direccion=%s, cp=%s, localidad=%s, provincia=%s, nif=%s, tel1=%s, tel2=%s, fax=%s, email=%s, email2=%s, homologado=%s, especialidad=%s, num_colegiado=%s, cccc=%s, mailing=%s, notas1=%s, notas2=%s, acuerdo_eco=%s, sector_pro=%s, acuerdo_pago=%s, cuenta_factura=%s WHERE id=%s",
                       GetSQLValueString($_POST['nombre_fiscal'], "text"),
                       GetSQLValueString($_POST['nombre_comercial'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['cp'], "text"),
                       GetSQLValueString($_POST['localidad'], "int"),
                       GetSQLValueString($_POST['provincia'], "int"),
                       GetSQLValueString($_POST['nif'], "text"),
                       GetSQLValueString($_POST['tel1'], "text"),
                       GetSQLValueString($_POST['tel2'], "text"),
                       GetSQLValueString($_POST['fax'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['email2'], "text"),
                       GetSQLValueString(isset($_POST['homologado']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['especialidad'], "int"),
                       GetSQLValueString($_POST['num_colegiado'], "text"),
                       GetSQLValueString($_POST['cccc'], "text"),
                       GetSQLValueString(isset($_POST['mailing']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['notas1'], "text"),
                       GetSQLValueString($_POST['notas2'], "text"),
                       GetSQLValueString($_POST['acuerdo_eco'], "int"),
                       GetSQLValueString($_POST['sector_pro'], "int"),
                       GetSQLValueString($_POST['acuerdo_pago'], "int"),
                       GetSQLValueString(isset($_POST['cuenta_factura']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_OJ, $OJ);
  $Result1 = mysql_query($updateSQL, $OJ) or die(mysql_error());

  $updateGoTo = "../include/fichas/ficha_profesional.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
?>
<?php
$colname_profesionales = "-1";
if (isset($_GET["profe"])) {
  $colname_profesionales = $_GET["profe"];
}
mysql_select_db($database_OJ, $OJ);
$query_profesionales = sprintf("SELECT * FROM profesionales WHERE id = %s", GetSQLValueString($colname_profesionales, "int"));
$profesionales = mysql_query($query_profesionales, $OJ) or die(mysql_error());
$row_profesionales = mysql_fetch_assoc($profesionales);
$totalRows_profesionales = mysql_num_rows($profesionales);

mysql_select_db($database_OJ, $OJ);
$query_Recordset1 = "SELECT * FROM provincia WHERE provincia.id ";
$Recordset1 = mysql_query($query_Recordset1, $OJ) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_OJ, $OJ);
$query_especialidad = "SELECT * FROM especialidad ORDER BY descripcion ASC";
$especialidad = mysql_query($query_especialidad, $OJ) or die(mysql_error());
$row_especialidad = mysql_fetch_assoc($especialidad);
$totalRows_especialidad = mysql_num_rows($especialidad);

mysql_select_db($database_OJ, $OJ);
$query_Recordset2 = "SELECT * FROM sector_profesional ORDER BY descripcion ASC";
$Recordset2 = mysql_query($query_Recordset2, $OJ) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

mysql_select_db($database_OJ, $OJ);
$query_Recordset3 = "SELECT * FROM localidad";
$Recordset3 = mysql_query($query_Recordset3, $OJ) or die(mysql_error());
$row_Recordset3 = mysql_fetch_assoc($Recordset3);
$totalRows_Recordset3 = mysql_num_rows($Recordset3);
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
    <h1>editar esto</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nombre_fiscal:</td>
          <td><input type="text" name="nombre_fiscal" value="<?php echo htmlentities($row_profesionales['nombre_fiscal'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nombre_comercial:</td>
          <td><input type="text" name="nombre_comercial" value="<?php echo htmlentities($row_profesionales['nombre_comercial'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Direccion:</td>
          <td><input type="text" name="direccion" value="<?php echo htmlentities($row_profesionales['direccion'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Cp:</td>
          <td><input type="text" name="cp" value="<?php echo htmlentities($row_profesionales['cp'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Localidad:</td>
          <td><select name="localidad">
            <?php
do {  
?>
            <option value="<?php echo $row_Recordset3['id']?>"><?php echo $row_Recordset3['localidad']?></option>
            <?php
} while ($row_Recordset3 = mysql_fetch_assoc($Recordset3));
  $rows = mysql_num_rows($Recordset3);
  if($rows > 0) {
      mysql_data_seek($Recordset3, 0);
	  $row_Recordset3 = mysql_fetch_assoc($Recordset3);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Provincia:</td>
          <td><select name="provincia">
            <?php
do {  
?>
            <option value="<?php echo $row_Recordset1['id']?>"><?php echo $row_Recordset1['provincia']?></option>
            <?php
} while ($row_Recordset1 = mysql_fetch_assoc($Recordset1));
  $rows = mysql_num_rows($Recordset1);
  if($rows > 0) {
      mysql_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysql_fetch_assoc($Recordset1);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nif:</td>
          <td><input type="text" name="nif" value="<?php echo htmlentities($row_profesionales['nif'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tel1:</td>
          <td><input type="text" name="tel1" value="<?php echo htmlentities($row_profesionales['tel1'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tel2:</td>
          <td><input type="text" name="tel2" value="<?php echo htmlentities($row_profesionales['tel2'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fax:</td>
          <td><input type="text" name="fax" value="<?php echo htmlentities($row_profesionales['fax'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Email:</td>
          <td><input type="text" name="email" value="<?php echo htmlentities($row_profesionales['email'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Email2:</td>
          <td><input type="text" name="email2" value="<?php echo htmlentities($row_profesionales['email2'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Homologado:</td>
          <td><input type="checkbox" name="homologado" value=""  <?php if (!(strcmp(htmlentities($row_profesionales['homologado'], ENT_COMPAT, 'UTF-8'),""))) {echo "checked=\"checked\"";} ?> /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Especialidad:</td>
          <td><select name="especialidad">
            <?php
do {  
?>
            <option value="<?php echo $row_especialidad['id']?>"><?php echo $row_especialidad['descripcion']?></option>
            <?php
} while ($row_especialidad = mysql_fetch_assoc($especialidad));
  $rows = mysql_num_rows($especialidad);
  if($rows > 0) {
      mysql_data_seek($especialidad, 0);
	  $row_especialidad = mysql_fetch_assoc($especialidad);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Num_colegiado:</td>
          <td><input type="text" name="num_colegiado" value="<?php echo htmlentities($row_profesionales['num_colegiado'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Cccc:</td>
          <td><input type="text" name="cccc" value="<?php echo htmlentities($row_profesionales['cccc'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Mailing:</td>
          <td><input type="checkbox" name="mailing" value=""  <?php if (!(strcmp(htmlentities($row_profesionales['mailing'], ENT_COMPAT, 'UTF-8'),""))) {echo "checked=\"checked\"";} ?> /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Notas1:</td>
          <td><input type="text" name="notas1" value="<?php echo htmlentities($row_profesionales['notas1'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Notas2:</td>
          <td><input type="text" name="notas2" value="<?php echo htmlentities($row_profesionales['notas2'], ENT_COMPAT, 'UTF-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Sector_pro:</td>
          <td><select name="sector_pro">
            <?php
do {  
?>
            <option value="<?php echo $row_Recordset2['id']?>"><?php echo $row_Recordset2['descripcion']?></option>
            <?php
} while ($row_Recordset2 = mysql_fetch_assoc($Recordset2));
  $rows = mysql_num_rows($Recordset2);
  if($rows > 0) {
      mysql_data_seek($Recordset2, 0);
	  $row_Recordset2 = mysql_fetch_assoc($Recordset2);
  }
?>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Cuenta_factura:</td>
          <td><input type="checkbox" name="cuenta_factura" value=""  <?php if (!(strcmp(htmlentities($row_profesionales['cuenta_factura'], ENT_COMPAT, 'UTF-8'),""))) {echo "checked=\"checked\"";} ?> /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Actualizar registro" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="id" value="<?php echo $row_profesionales['id']; ?>" />
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
mysql_free_result($profesionales);

mysql_free_result($Recordset1);

mysql_free_result($especialidad);

mysql_free_result($Recordset2);

mysql_free_result($Recordset3);
?>
