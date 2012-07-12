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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO siniestro (id_cliente, fecha_baja_laboral, fecha_siniestro, hora, fecha_alta_laboral, desarrollo_accidente, lugar_accidente, circunstancias, danos_vehiculo, danos_personales, notas_expediente, firma_carta_designacion, vehiculo, matricula, conductor, num_poliza, CIA, fecha_poliza, caducidad_poliza, apertura_expediente, cierre_expediente, AJ, cuantia_AJ, condicion, ocupante, localidad, indemnizacion, adelantos, cobrado_oficina, indemnizacion_real, pago_agente, cobrado_cliente, num_procedimiento, tipo_accidente, tipo_caso, diligencias_previas, tomador_seguro, siniestro, representado, dias_impeditivos, dias_noimpeditivos, dias_hospitalizacion, valor_impeditivos, valor_noimpeditivos, valor_hospitalizacion, tipo_sinestro, tramitador_exp, alta_expediente, archivado, referencia_expediente) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_cliente'], "int"),
                       GetSQLValueString($_POST['fecha_baja_laboral'], "date"),
                       GetSQLValueString($_POST['fecha_siniestro'], "date"),
                       GetSQLValueString($_POST['hora'], "date"),
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
                       GetSQLValueString($_POST['apertura_expediente'], "date"),
                       GetSQLValueString($_POST['cierre_expediente'], "date"),
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
                       GetSQLValueString($_POST['tipo_sinestro'], "int"),
                       GetSQLValueString($_POST['tramitador_exp'], "int"),
                       GetSQLValueString($_POST['alta_expediente'], "date"),
                       GetSQLValueString($_POST['archivado'], "date"),
                       GetSQLValueString($_POST['referencia_expediente'], "text"));

  mysql_select_db($database_OJ, $OJ);
  $Result1 = mysql_query($insertSQL, $OJ) or die(mysql_error());

  $insertGoTo = "/include/fichas/ficha_cliente.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
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
    <h1>Siniestro nuevo</h1>
    <p>&nbsp;</p>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha_baja_laboral:</td>
          <td><input type="text" name="fecha_baja_laboral" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha_siniestro:</td>
          <td><input type="text" name="fecha_siniestro" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Hora:</td>
          <td><input type="text" name="hora" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha_alta_laboral:</td>
          <td><input type="text" name="fecha_alta_laboral" value="" size="32" /></td>
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
          <td nowrap="nowrap" align="right">Circunstancias:</td>
          <td><input type="text" name="circunstancias" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Danos_vehiculo:</td>
          <td><input type="text" name="danos_vehiculo" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Danos_personales:</td>
          <td><input type="text" name="danos_personales" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Notas_expediente:</td>
          <td><input type="text" name="notas_expediente" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Firma_carta_designacion:</td>
          <td><input type="text" name="firma_carta_designacion" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Vehiculo:</td>
          <td><input type="text" name="vehiculo" value="" size="32" /></td>
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
          <td nowrap="nowrap" align="right">Num_poliza:</td>
          <td><input type="text" name="num_poliza" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">CIA:</td>
          <td><input type="text" name="CIA" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Fecha_poliza:</td>
          <td><input type="text" name="fecha_poliza" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Caducidad_poliza:</td>
          <td><input type="text" name="caducidad_poliza" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Apertura_expediente:</td>
          <td><input type="text" name="apertura_expediente" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Cierre_expediente:</td>
          <td><input type="text" name="cierre_expediente" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">AJ:</td>
          <td><input type="text" name="AJ" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Cuantia_AJ:</td>
          <td><input type="text" name="cuantia_AJ" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Condicion:</td>
          <td><input type="text" name="condicion" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Ocupante:</td>
          <td><input type="text" name="ocupante" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Localidad:</td>
          <td><input type="text" name="localidad" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Indemnizacion:</td>
          <td><input type="text" name="indemnizacion" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Adelantos:</td>
          <td><input type="text" name="adelantos" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Cobrado_oficina:</td>
          <td><input type="text" name="cobrado_oficina" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Indemnizacion_real:</td>
          <td><input type="text" name="indemnizacion_real" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Pago_agente:</td>
          <td><input type="text" name="pago_agente" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Cobrado_cliente:</td>
          <td><input type="text" name="cobrado_cliente" value="" size="32" /></td>
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
          <td nowrap="nowrap" align="right">Tomador_seguro:</td>
          <td><input type="text" name="tomador_seguro" value="" size="32" /></td>
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
          <td nowrap="nowrap" align="right">Dias_impeditivos:</td>
          <td><input type="text" name="dias_impeditivos" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Dias_noimpeditivos:</td>
          <td><input type="text" name="dias_noimpeditivos" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Dias_hospitalizacion:</td>
          <td><input type="text" name="dias_hospitalizacion" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Valor_impeditivos:</td>
          <td><input type="text" name="valor_impeditivos" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Valor_noimpeditivos:</td>
          <td><input type="text" name="valor_noimpeditivos" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Valor_hospitalizacion:</td>
          <td><input type="text" name="valor_hospitalizacion" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tipo_sinestro:</td>
          <td><input type="text" name="tipo_sinestro" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Tramitador_exp:</td>
          <td><input type="text" name="tramitador_exp" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Alta_expediente:</td>
          <td><input type="text" name="alta_expediente" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Archivado:</td>
          <td><input type="text" name="archivado" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Referencia_expediente:</td>
          <td><input type="text" name="referencia_expediente" value="" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="Insertar registro" /></td>
        </tr>
      </table>
      <input type="hidden" name="id_cliente" value="<?php $_GET["clientid"]?>" />
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