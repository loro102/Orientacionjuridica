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
 If ($_POST['listo']>1) {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO siniestro (apertura_expediente , cierre_expediente, id_cliente, fecha_siniestro, hora, fecha_baja_laboral, fecha_alta_laboral, desarrollo_accidente, lugar_accidente, circunstancias, danos_vehiculo, danos_personales, notas_expediente, firma_carta_designacion, vehiculo, matricula, conductor, num_poliza, CIA, fecha_poliza, caducidad_poliza, AJ, cuantia_AJ, condicion, ocupante, localidad, indemnizacion, adelantos, cobrado_oficina, indemnizacion_real, pago_agente, cobrado_cliente, num_procedimiento, tipo_accidente, tipo_caso, diligencias_previas, tomador_seguro, siniestro, representado, dias_impeditivos, dias_noimpeditivos, dias_hospitalizacion, valor_impeditivos, valor_noimpeditivos, valor_hospitalizacion, tramitador_exp, tipo_sinestro) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['apertura_expediente'], "date"),
                       GetSQLValueString($_POST['cierre_expediente'], "date"),
                       GetSQLValueString($_POST['id_cliente'], "int"),
                       GetSQLValueString($_POST['fecha_siniestro'], "date"),
                       GetSQLValueString($_POST['hora'], "date"),
                       GetSQLValueString($_POST['fecha_baja_laboral'], "date"),
                       GetSQLValueString($_POST['fecha_alta_laboral'], "date"),
                       GetSQLValueString($_POST['desarrollo_accidente'], "text"),
                       GetSQLValueString($_POST['lugar_accidente'], "text"),
                       GetSQLValueString($_POST['circunstancias'], "int"),
                       GetSQLValueString($_POST['danos_vehiculo'], "int"),
                       GetSQLValueString($_POST['danos_personales'], "int"),
                       GetSQLValueString($_POST['notas_expediente'], "text"),
                       GetSQLValueString($_POST['firma_carta_designacion'], "int"),
                       GetSQLValueString($_POST['vehiculo'], "int"),
                       GetSQLValueString($_POST['matricula'], "text"),
                       GetSQLValueString($_POST['conductor'], "text"),
                       GetSQLValueString($_POST['num_poliza'], "text"),
                       GetSQLValueString($_POST['CIA'], "int"),
                       GetSQLValueString($_POST['fecha_poliza'], "date"),
                       GetSQLValueString($_POST['caducidad_poliza'], "date"),
                       GetSQLValueString($_POST['AJ'], "int"),
                       GetSQLValueString($_POST['cuantia_AJ'], "text"),
                       GetSQLValueString($_POST['condicion'], "text"),
                       GetSQLValueString($_POST['ocupante'], "text"),
                       GetSQLValueString($_POST['localidad'], "text"),
                       GetSQLValueString($_POST['indemnizacion'], "text"),
                       GetSQLValueString($_POST['adelantos'], "text"),
                       GetSQLValueString($_POST['cobrado_oficina'], "text"),
                       GetSQLValueString($_POST['indemnizacion_real'], "text"),
                       GetSQLValueString($_POST['pago_agente'], "text"),
                       GetSQLValueString($_POST['cobrado_cliente'], "text"),
                       GetSQLValueString($_POST['num_procedimiento'], "text"),
                       GetSQLValueString($_POST['tipo_accidente'], "int"),
                       GetSQLValueString($_POST['tipo_caso'], "int"),
                       GetSQLValueString($_POST['diligencias_previas'], "int"),
                       GetSQLValueString($_POST['tomador_seguro'], "text"),
                       GetSQLValueString($_POST['siniestro'], "text"),
                       GetSQLValueString($_POST['representado'], "text"),
                       GetSQLValueString($_POST['dias_impeditivos'], "text"),
                       GetSQLValueString($_POST['dias_noimpeditivos'], "text"),
                       GetSQLValueString($_POST['dias_hospitalizacion'], "text"),
                       GetSQLValueString($_POST['valor_impeditivos'], "text"),
                       GetSQLValueString($_POST['valor_noimpeditivos'], "text"),
                       GetSQLValueString($_POST['valor_hospitalizacion'], "text"),
					   GetSQLValueString($_POST['tramitador_exp'], "int"),
                       GetSQLValueString($_POST['tipo_sinestro'], "int"));

  mysql_select_db($database_OJ, $OJ);
  $Result1 = mysql_query($insertSQL, $OJ) or die(mysql_error());
 
  $updateGoTo = "../fichas/ficha_cliente.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
}

mysql_select_db($database_OJ, $OJ);
$query_Recordset1 = "SELECT * FROM siniestro ";
$Recordset1 = mysql_query($query_Recordset1, $OJ) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_OJ, $OJ);
$query_vehiculos = "SELECT * FROM vehiculo";
$vehiculos = mysql_query($query_vehiculos, $OJ) or die(mysql_error());
$row_vehiculos = mysql_fetch_assoc($vehiculos);
$totalRows_vehiculos = mysql_num_rows($vehiculos);

mysql_select_db($database_OJ, $OJ);
$query_CIA = "SELECT * FROM aseguradoras";
$CIA = mysql_query($query_CIA, $OJ) or die(mysql_error());
$row_CIA = mysql_fetch_assoc($CIA);
$totalRows_CIA = mysql_num_rows($CIA);

$colname_Tramitador = "0";
if (isset($_POST['CIA'])) {
  $colname_Tramitador = $_POST['CIA'];
}
mysql_select_db($database_OJ, $OJ);
$query_Tramitador = sprintf("SELECT id, nombre, apellido1, apellido2 FROM tramitadores_cia WHERE id_CIA = %s", GetSQLValueString($colname_Tramitador, "int"));
$Tramitador = mysql_query($query_Tramitador, $OJ) or die(mysql_error());
$row_Tramitador = mysql_fetch_assoc($Tramitador);
$totalRows_Tramitador = mysql_num_rows($Tramitador);
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
    <h1>Nuevo siniestro</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right"><p>Fecha de apertura :</p>
          <p>CIA:</p>
          <p>Tramitador:</p></td>
          <td><p>
          <?php $fecha_actual=date("Y/m/d");?>
            <input name="apertura_expediente" type="text" value="<?php echo $fecha_actual; ?>" size="32" readonly="readonly" />
          </p>
          <p>
            <label for="tramitador_exp">
              <select name="CIA" id="CIA" onchange="submit()">
                <option value="" <?php if (!(strcmp("", $_POST['CIA']))) {echo "selected=\"selected\"";} ?>>Ninguno</option>
                <?php
do {  
?>
                <option value="<?php echo $row_CIA['id']?>"<?php if (!(strcmp($row_CIA['id'], $_POST['CIA']))) {echo "selected=\"selected\"";} ?>><?php echo $row_CIA['nombre']?></option>
                <?php
} while ($row_CIA = mysql_fetch_assoc($CIA));
  $rows = mysql_num_rows($CIA);
  if($rows > 0) {
      mysql_data_seek($CIA, 0);
	  $row_CIA = mysql_fetch_assoc($CIA);
  }
?>
                </select>
              <a href="../listas/lista_aseguradoras.php" target="_parent" id="sprytrigger2">Nueva CIA</a><br />
            </label>
            </p>
          <p>
            <select name="tramitador_exp" id="tramitador_exp" onchange="submit()">
              <option value="" <?php if (!(strcmp("", $_POST['tramitador_exp']))) {echo "selected=\"selected\"";} ?>>Ninguno</option>
              <?php
do {  
?>
              <option value="<?php echo $row_Tramitador['id']?>"<?php if (!(strcmp($row_Tramitador['id'], $_POST['tramitador_exp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Tramitador['nombre']?></option>
              <?php
} while ($row_Tramitador = mysql_fetch_assoc($Tramitador));
  $rows = mysql_num_rows($Tramitador);
  if($rows > 0) {
      mysql_data_seek($Tramitador, 0);
	  $row_Tramitador = mysql_fetch_assoc($Tramitador);
  }
?>
            </select>
          <a href="../fichas/ficha_aseguradora.php?cia=<?php echo $_POST['CIA'];?>" target="_parent" id="sprytrigger3">Nuevo tramitador</a></p></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha_siniestro:</td>
          <td><input type="text" name="fecha_siniestro" value="<?php echo $fecha_actual; ?>" size="32"id="f_date3" /><button id="f_btn3">...</button><br />
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
          <td nowrap="nowrap" align="right">Hora:</td>
          <td><input type="text" name="hora" value="00:00" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha_baja_laboral:</td>
          <td><input type="text" name="fecha_baja_laboral" value="<?php echo $fecha_actual; ?>" size="32" id="f_date1" /><button id="f_btn1">...</button><br />
    <script type="text/javascript">//<![CDATA[
      Calendar.setup({
        inputField : "f_date1",
        trigger    : "f_btn1",
        onSelect   : function() { this.hide() },
        showTime   : 12,
        dateFormat : "%Y-%m-%d"
      });
    //]]></script></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha_alta_laboral:</td>
          <td><input type="text" name="fecha_alta_laboral" value="<?php echo $fecha_actual; ?>" size="32" id="f_date2" /><button id="f_btn2">...</button><br />
    <script type="text/javascript">//<![CDATA[
      Calendar.setup({
        inputField : "f_date2",
        trigger    : "f_btn2",
        onSelect   : function() { this.hide() },
        showTime   : 12,
        dateFormat : "%Y-%m-%d"
      });
    //]]></script></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Desarrollo_accidente:</td>
          <td><input type="text" name="desarrollo_accidente" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Lugar_accidente:</td>
          <td><input type="text" name="lugar_accidente" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Localidad:</td>
          <td><input type="text" name="localidad" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Circunstancias:</td>
          <td><input type="text" name="circunstancias" value="" size="32" /></td>
        </tr>
         <tr valign="baseline">
          <td nowrap="nowrap" align="right">Condicion:</td>
          <td><input type="text" name="condicion" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Daños del vehiculo:</td>
          <td><select name="danos_vehiculo">
            <option value="menuitem1" >[ Etiqueta ]</option>
            <option value="menuitem2" >[ Etiqueta ]</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Daños personales:</td>
          <td><select name="danos_personales">
            <option value="menuitem1" >[ Etiqueta ]</option>
            <option value="menuitem2" >[ Etiqueta ]</option>
          </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Firma_carta_designacion:</td>
          <td><input type="checkbox" name="checkbox2" id="checkbox2" />
          <label for="checkbox2"></label></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">AJ:</td>
          <td><input type="checkbox" name="checkbox" id="checkbox" />
          <label for="checkbox"></label></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Vehiculo:</td>
          <td><label for="select"></label>
            <select name="select" id="select" ondblclick="var popUpWin=0;">
              <option value="0">Ninguno</option>
              <?php
do {  
?>
              <option value="<?php echo $row_vehiculos['id']?>"><?php echo $row_vehiculos['marca']?></option>
              <?php
} while ($row_vehiculos = mysql_fetch_assoc($vehiculos));
  $rows = mysql_num_rows($vehiculos);
  if($rows > 0) {
      mysql_data_seek($vehiculos, 0);
	  $row_vehiculos = mysql_fetch_assoc($vehiculos);
  }
?>


            </select> 
            <a href="../listas/lista_vehiculo.php" target="_parent">Nuevo vehiculo</a></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Matricula:</td>
          <td><input type="text" name="matricula" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Conductor:</td>
          <td><input type="text" name="conductor" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tomador_seguro:</td>
          <td><input type="text" name="tomador_seguro" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Num_poliza:</td>
          <td><input type="text" name="num_poliza" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Referencia expediente:</td>
          <td><label for="listo"></label>
            <label for="textfield"></label>
          <input type="text" name="textfield" id="textfield" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha_poliza:</td>
          <td><input type="text" name="fecha_poliza" value="<?php echo $fecha_actual; ?>" size="32" id="f_date4" /><button id="f_btn4">...</button><br />
    <script type="text/javascript">//<![CDATA[
      Calendar.setup({
        inputField : "f_date4",
        trigger    : "f_btn4",
        onSelect   : function() { this.hide() },
        showTime   : 12,
        dateFormat : "%Y-%m-%d"
      });
    //]]></script></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Caducidad_poliza:</td>
          <td><input type="text" name="caducidad_poliza" value="<?php echo $fecha_actual; ?>" size="32" id="f_date5" /><button id="f_btn5">...</button><br />
    <script type="text/javascript">//<![CDATA[
      Calendar.setup({
        inputField : "f_date5",
        trigger    : "f_btn5",
        onSelect   : function() { this.hide() },
        showTime   : 12,
        dateFormat : "%Y-%m-%d"
      });
    //]]></script></td>
        </tr>
        
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Cuantia_AJ:</td>
          <td><input type="text" name="cuantia_AJ" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Num_procedimiento:</td>
          <td><input type="text" name="num_procedimiento" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tipo_accidente:</td>
          <td><input type="text" name="tipo_accidente" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tipo_caso:</td>
          <td><input type="text" name="tipo_caso" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Diligencias_previas:</td>
          <td><input type="text" name="diligencias_previas" value="" size="32" /></td>
        </tr>
        
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Siniestro:</td>
          <td><input type="text" name="siniestro" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Representado:</td>
          <td><input type="text" name="representado" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tipo_sinestro:</td>
          <td><input type="text" name="tipo_sinestro" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Listo:</td>
          <td><label for="listo"></label>
            <select name="listo" id="listo">
              <option value="1" <?php if (!(strcmp(1, $_POST['listo']))) {echo "selected=\"selected\"";} ?>>No</option>
              <option value="2" <?php if (!(strcmp(2, $_POST['listo']))) {echo "selected=\"selected\"";} ?>>Si</option>
            </select></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insertar registro" /></td>
        </tr>
      </table>
      <p>
        <input type="hidden" name="id_cliente" value=<?php echo $_GET["clientid"];?> />
        <input type="hidden" name="MM_insert" value="form1" />
      </p>
      <p>&nbsp;</p>
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

mysql_free_result($vehiculos);

mysql_free_result($CIA);

mysql_free_result($Tramitador);
?>
