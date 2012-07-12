<?php include('../../Connections/OJ.php'); ?>
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
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO clientes (nombre, apellidos1, apellidos2, direccion, codigopostal, localidad, provincia, nif, fechanacimiento, telefono1, telefono2, fax, email, notas, profesion, agente, CCC, mailing, id_empleado) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nombre'], "text"),
                       GetSQLValueString($_POST['apellidos1'], "text"),
                       GetSQLValueString($_POST['apellidos2'], "text"),
                       GetSQLValueString($_POST['direccion'], "text"),
                       GetSQLValueString($_POST['codigopostal'], "text"),
                       GetSQLValueString($_POST['localidad'], "text"),
                       GetSQLValueString($_POST['provincia'], "text"),
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
                       GetSQLValueString($_POST['id_empleado'], "int"));

  mysql_select_db($database_OJ, $OJ);
  $Result1 = mysql_query($insertSQL, $OJ) or die(mysql_error());

  $insertGoTo = "../../Agentes/cliente.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
mysql_select_db($database_OJ, $OJ);
$query_Recordset1 = "SELECT * FROM clientes";
$Recordset1 = mysql_query($query_Recordset1, $OJ) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_OJ, $OJ);
$query_profesion = "SELECT * FROM profesion";
$profesion = mysql_query($query_profesion, $OJ) or die(mysql_error());
$row_profesion = mysql_fetch_assoc($profesion);
$totalRows_profesion = mysql_num_rows($profesion);

mysql_select_db($database_OJ, $OJ);
$query_agentes = "SELECT id, nombre_comercial FROM agentes";
$agentes = mysql_query($query_agentes, $OJ) or die(mysql_error());
$row_agentes = mysql_fetch_assoc($agentes);
$totalRows_agentes = mysql_num_rows($agentes);

mysql_select_db($database_OJ, $OJ);
$query_provincia = "SELECT * FROM provincia ORDER BY provincia.provincia ASC";
$provincia = mysql_query($query_provincia, $OJ) or die(mysql_error());
$row_provincia = mysql_fetch_assoc($provincia);
$totalRows_provincia = mysql_num_rows($provincia);

$lugar_Localidad = "0";
if (isset($_POST["provincia"])) {
  $lugar_Localidad = $_POST["provincia"];
}
mysql_select_db($database_OJ, $OJ);
$query_Localidad = sprintf("SELECT * FROM localidad WHERE localidad.id_provincia=%s ORDER BY localidad.localidad ASC", GetSQLValueString($lugar_Localidad, "int"));
$Localidad = mysql_query($query_Localidad, $OJ) or die(mysql_error());
$row_Localidad = mysql_fetch_assoc($Localidad);
$totalRows_Localidad = mysql_num_rows($Localidad);
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
        <li><a href="nuevo_cliente.php">A&ntilde;adir</a></li>
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
    <h1>Nuevo Cliente    </h1>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nombre:</td>
          <td><input name="nombre" type="text" value="<?php echo $_POST['nombre']?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Apellidos1:</td>
          <td><input type="text" name="apellidos1" value="<?php echo $_POST['apellidos1'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Apellidos2:</td>
          <td><input type="text" name="apellidos2" value="<?php echo $_POST['apellidos2'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Direccion:</td>
          <td><input name="direccion" type="text" value="<?php echo $_POST['direccion'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Codigopostal:</td>
          <td><input name="codigopostal" type="text" value="<?php echo $_POST["codigopostal"]; ?>" size="32" /> 
          <a href="http://correos.es/comun/CodigosPostales/1010_s-CodPostal.asp" target="_blank">buscar</a></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Provincia:</td>
          <td>            <select name="provincia" id="provincia" onchange="submit()">
            <option value="0" <?php if (!(strcmp(0, $_POST['provincia']))) {echo "selected=\"selected\"";} ?>>Ninguno</option>
            <?php
do {  
?>
            <option value="<?php echo $row_provincia['id']?>"<?php if (!(strcmp($row_provincia['id'], $_POST['provincia']))) {echo "selected=\"selected\"";} ?>><?php echo $row_provincia['provincia']?></option>
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
          <td><select name="localidad" id="localidad" onchange="submit()">
            <option value="0" <?php if (!(strcmp(0, $_POST['localidad']))) {echo "selected=\"selected\"";} ?>>Ninguno</option>
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
          </select>
          <a href="../listas/localidad.php" target="_new">Buscar</a></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Nif/Cif:</td>
          <td><input name="nif" type="text" value="<?php echo $_POST['nif']; ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha de nacimiento:</td> 
          <td><input type="text" id="f_date1" name="fechanacimiento" value="<?php echo $_POST['fechanacimiento']; ?>" size="32"   id="f_date1" /><button id="f_btn1">...</button><br />
    <script type="text/javascript">//<![CDATA[
      Calendar.setup({
        inputField : "f_date1",
        trigger    : "f_btn1",
        onSelect   : function() { this.hide() },
        showTime   : 12,
        dateFormat : "%Y-%m-%d"
      });
    //]]></script>
          </td>
          
            
    

        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Telefono1:</td>
          <td><input type="text" name="telefono1" value="<?php echo $_POST['telefono1'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Telefono2:</td>
          <td><input type="text" name="telefono2" value="<?php echo $_POST['telefono2'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fax:</td>
          <td><input type="text" name="fax" value="<?php echo $_POST['fax'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Email:</td>
          <td><input type="text" name="email" value="<?php echo $_POST['email'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right" valign="top">Notas:</td>
          <td><textarea name="notas" cols="50" rows="5"><?php echo $_POST['notas'] ?></textarea></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Profesion:</td>
          <td><select name="profesion">
          <?php if ($row_profesion['id']<"1")
          {
          $prof="No hay profesiones";
		  }
		  else
		  {
			  $prof="Ninguna opcion";
		  } 
		  ?>
            <option value="null"><?php echo $prof; ?></option>
            <?php
do {  
?>
            <option value="<?php echo $row_profesion['id']?>"><?php echo $row_profesion['descripcion']?></option>
            <?php
} while ($row_profesion = mysql_fetch_assoc($profesion));
  $rows = mysql_num_rows($profesion);
  if($rows > 0) {
      mysql_data_seek($profesion, 0);
	  $row_profesion = mysql_fetch_assoc($profesion);
  }
?>
          </select>
          <a href="../listas/lista_profesion.php" target="_new">buscar</a></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Agente:</td>
          <td><select name="agente">
          <?php 
		  if ($row_agentes['id']<1)
		  {
			  $prof="No hay agentes";
			  }
			  else
			  {
				  $prof="Ningun agente";
				  }
		  
		  ?>
          
            <option value="null"><?php echo $prof;?></option>
            <?php
do {  
?>
            <option value="<?php echo $row_agentes['id']?>" selected="selected"><?php echo $row_agentes['nombre_comercial']?></option>
            <?php
} while ($row_agentes = mysql_fetch_assoc($agentes));
  $rows = mysql_num_rows($agentes);
  if($rows > 0) {
      mysql_data_seek($agentes, 0);
	  $row_agentes = mysql_fetch_assoc($agentes);
  }
?>
          </select>
          <a href="../../Agentes/Agentesphp.php" target="_new">          buscar</a></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">CCC:</td>
          <td><input type="text" name="CCC" value="<?php echo $_POST['CCC'] ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Mailing:</td>
          <td><input type="checkbox" name="mailing" value="1" checked="checked" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Confirmar</td>
          <td><select name="listo" id="listo">
            <option value="0">No</option>
            <option value="1">Si</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insertar registro"  name="button" id="button"/></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
      <input name="id_empleado" type="hidden" value="<?php echo $_SESSION['MM_id']; ?>" />
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
mysql_free_result($Recordset1);

mysql_free_result($profesion);

mysql_free_result($Localidad);

mysql_free_result($agentes);

mysql_free_result($provincia);


?>
