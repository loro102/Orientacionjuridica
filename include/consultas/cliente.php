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
?>
<?php
mysql_select_db($database_OJ, $OJ);
$query_profesion = "SELECT * FROM profesion";
$profesion = mysql_query($query_profesion, $OJ) or die(mysql_error());
$row_profesion = mysql_fetch_assoc($profesion);
$totalRows_profesion = mysql_num_rows($profesion);

mysql_select_db($database_OJ, $OJ);
$query_agente = "SELECT * FROM agentes";
$agente = mysql_query($query_agente, $OJ) or die(mysql_error());
$row_agente = mysql_fetch_assoc($agente);
$totalRows_agente = mysql_num_rows($agente);

mysql_select_db($database_OJ, $OJ);
$query_provincias = "SELECT * FROM t_provincias";
$provincias = mysql_query($query_provincias, $OJ) or die(mysql_error());
$row_provincias = mysql_fetch_assoc($provincias);
$totalRows_provincias = mysql_num_rows($provincias);
$colname_localidad = "0";
if (isset($_GET['provincia'])) {
  $colname_localidad = $_GET['provincia'];
}
mysql_select_db($database_OJ, $OJ);
$query_localidad = sprintf("SELECT * FROM t_municipios WHERE CodProv = %s ORDER BY Municipio ASC", GetSQLValueString($colname_localidad, "text"));
$localidad = mysql_query($query_localidad, $OJ) or die(mysql_error());
$row_localidad = mysql_fetch_assoc($localidad);
$totalRows_localidad = mysql_num_rows($localidad);
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
    <h1>Consulta de clientes</h1>
    <?php if ($_GET['listo']>0)
	{
		$buscar= "resultado_cliente.php";
	}
	else{
		$buscar="";}?>
    <form id="form1" name="form1" method="get" action="resultado_cliente.php">
      <p>
        <label for="profesion">profesion</label>
        <select name="profesion" id="profesion">
          <option value="">Todos</option>
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
      </p>
      <p>
        <label for="provincia">provincia</label>
        <select name="provincia" id="provincia" onchange="submit()">
          <option value="" <?php if (!(strcmp("", $_get['CodProv']))) {echo "selected=\"selected\"";} ?>>Todos</option>
          <?php
do {  
?>
          <option value="<?php echo $row_provincias['CodProv']?>"<?php if (!(strcmp($row_provincias['CodProv'], $_get['CodProv']))) {echo "selected=\"selected\"";} ?>><?php echo $row_provincias['Provincia']?></option>
          <?php
} while ($row_provincias = mysql_fetch_assoc($provincias));
  $rows = mysql_num_rows($provincias);
  if($rows > 0) {
      mysql_data_seek($provincias, 0);
	  $row_provincias = mysql_fetch_assoc($provincias);
  }
?>
        </select>
      </p>
      <p>
        <label for="localidad">localidad</label>
        <select name="localidad" id="localidad">
          <option value="">Todos</option>
          <?php
do {  
?>
          <option value="<?php echo $row_localidad['idMunicipio']?>"><?php echo $row_localidad['Municipio']?></option>
          <?php
} while ($row_localidad = mysql_fetch_assoc($localidad));
  $rows = mysql_num_rows($localidad);
  if($rows > 0) {
      mysql_data_seek($localidad, 0);
	  $row_localidad = mysql_fetch_assoc($localidad);
  }
?>
        </select>
      </p>
      <p>
        <label for="agente">agente</label>
        <select name="agente" id="agente">
          <option value="">Todos</option>
          <?php
do {  
?>
          <option value="<?php echo $row_agente['id']?>"><?php echo $row_agente['nombre_comercial']?></option>
          <?php
} while ($row_agente = mysql_fetch_assoc($agente));
  $rows = mysql_num_rows($agente);
  if($rows > 0) {
      mysql_data_seek($agente, 0);
	  $row_agente = mysql_fetch_assoc($agente);
  }
?>
        </select>
      </p>
      <p>confirmar 
        <input name="listo" type="checkbox" id="listo" value="1" />
        <label for="listo"></label>
      </p>
      <p>
        <input type="submit" name="button" id="button" value="Consultar" />
      </p>
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
mysql_free_result($profesion);

mysql_free_result($agente);

mysql_free_result($provincias);

mysql_free_result($localidad);
?>
