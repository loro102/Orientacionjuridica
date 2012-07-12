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
<?php if (!isset($_SESSION)) {
session_start();
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Recordset1 = 10;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

$clientid_Recordset1 = "0";
if (isset($_GET["clientid"])) {
  $clientid_Recordset1 = $_GET["clientid"];
}
mysql_select_db($database_OJ, $OJ);
$query_Recordset1 = sprintf("SELECT * FROM siniestro WHERE siniestro.id_cliente=%s", GetSQLValueString($clientid_Recordset1, "int"));
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $OJ) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$datosclient_mostrar_datos_cliente = "0";
if (isset($_GET["clientid"])) {
  $datosclient_mostrar_datos_cliente = $_GET["clientid"];
}
mysql_select_db($database_OJ, $OJ);
$query_mostrar_datos_cliente = sprintf("SELECT * FROM clientes WHERE clientes.id=%s", GetSQLValueString($datosclient_mostrar_datos_cliente, "int"));
$mostrar_datos_cliente = mysql_query($query_mostrar_datos_cliente, $OJ) or die(mysql_error());
$row_mostrar_datos_cliente = mysql_fetch_assoc($mostrar_datos_cliente);
$totalRows_mostrar_datos_cliente = mysql_num_rows($mostrar_datos_cliente);

$clientid_siniestro_notrafico = "0";
if (isset($_GET["clientid"])) {
  $clientid_siniestro_notrafico = $_GET["clientid"];
}
mysql_select_db($database_OJ, $OJ);
$query_siniestro_notrafico = sprintf("SELECT * FROM siniestro_notrafico WHERE siniestro_notrafico.id_cliente=%s", GetSQLValueString($clientid_siniestro_notrafico, "int"));
$siniestro_notrafico = mysql_query($query_siniestro_notrafico, $OJ) or die(mysql_error());
$row_siniestro_notrafico = mysql_fetch_assoc($siniestro_notrafico);
$totalRows_siniestro_notrafico = mysql_num_rows($siniestro_notrafico);

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
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
    <h1>Ficha del cliente</h1>
    <div class="datos_clientes">
    <h2>Datos cliente    </h2>
    <p>&nbsp;</p>
    <div class=nombre>
    Nombre: <?php echo $row_mostrar_datos_cliente['nombre']; ?> <?php echo $row_mostrar_datos_cliente['apellidos1']; ?> <?php echo $row_mostrar_datos_cliente['apellidos2']; ?></div> 
    <p>&nbsp;</p>
    
    </p>
    <div class=nif>
      NIF:  <?php echo $row_mostrar_datos_cliente['nif']; ?></div>
    <div class="direccion"> 
      <p>Direccion: <?php echo $row_mostrar_datos_cliente['direccion']; ?> </p>
      <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;<?php echo $row_mostrar_datos_cliente['codigopostal']; ?> <?php echo obtenerlocalidad($row_mostrar_datos_cliente['localidad']); ?></p>
    </div>
          <?php echo obtenerprovincia($row_mostrar_datos_cliente['provincia']); ?>
          <div class="fecha_nac"> Fecha de nacimiento:
            <?php $fecha=$row_mostrar_datos_cliente['fechanacimiento']; 
		echo (formatofecha($fecha));?>
          </div>
          <div class="profesion"> Profesion: <?php echo obtenernombreprofesion($row_mostrar_datos_cliente['profesion']); ?></div>
          <div class="telefono">Telefono: <?php echo $row_mostrar_datos_cliente['telefono1']; ?></div>
          <div class="movil">Movil: <?php echo $row_mostrar_datos_cliente['telefono2']; ?></div>
          <div class="fax">Fax: <?php echo $row_mostrar_datos_cliente['fax']; ?></div>
          <div class="mail">E-mail: <?php echo $row_mostrar_datos_cliente['email']; ?></div>
    </div>
      
    
    
    <a href="/include/editar/editar_cliente.php?clientid=<?php echo $row_mostrar_datos_cliente['id']; ?>">Modificar datos</a><br></br>
    <div class="otrosdatos">
    <h2>Otros datos de interes</h2>
    <table width="100%" border="0">
      <tr>
        <td>Agente:<?php echo obtenernombreagente($row_mostrar_datos_cliente['agente']); ?></td>
        <td>&nbsp;</td>
        <td>Mailing:
          <?php  
	if ($row_mostrar_datos_cliente['mailing']=="0")
	{
		echo "No quiere mailing";
	}
	else
	{
		echo "Quiere mailing";
		}
	?></td>
      </tr>
  </table>
    <p>
      <?php
	if ((isset($_SESSION['MM_UserGroup']))&&($_SESSION['MM_UserGroup']<="3"))
	{
		echo ("CCC:");
    echo $row_mostrar_datos_cliente['CCC']; 
	}
	?>
    </p>
    <p>
    <script>
function mostrardiv() 
{
div = document.getElementById('flotante');
div.style.display = '';
}
function cerrar()
{
div = document.getElementById('flotante');
div.style.display='none';
}
</script>
<div id="mostrarDiv"><a href="javascript:mostrardiv();">Generar codigo qr</a></div>
<div id="flotante" class=imagen style="display:none;">
  <img src="http://chart.apis.google.com/chart?cht=qr&amp;chs=300x300&amp;;chld=H&amp;chl=http://fostato.ssssss.tk/include/fichas/ficha_cliente.php?clientid=<?php echo $row_mostrar_datos_cliente['id']; ?>" alt="codigo qr" width="300" height="300" align="middle" longdesc="Codigo qr de acceso rapido" />
  <br><a href="javascript:cerrar();"> presiona aquí para cerrar</a> </div>
 
  </p>
  </div>
   
    <p>&nbsp;</p>
    <div class=expedientes>
  <p>Expedientes Tráfico</p>
  <?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
  <table width="100%" border="1">
    <tr>
      <td>Fecha del siniestro</td>
      <td>Fecha apertura</td>
      <td>Lugar de siniestro</td>
      <td>descripcion del caso</td>
      <td>Acciones</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><a href="/include/vista_datos/datos_cliente.php?sinistro=<?php echo $row_Recordset1['id']; ?>"><?php $fecha=$row_Recordset1['fecha_siniestro']; 
		echo (formatofecha($fecha));?></a></td>
        <td height="30"><?php $fecha=$row_Recordset1['apertura_expediente']; 
		echo (formatofecha($fecha));?></td>
        <td><a href="/include/vista_datos/datos_cliente.php?sinistro=<?php echo $row_Recordset1['id']; ?>"><?php echo $row_Recordset1['lugar_accidente']; ?></a></td>
        <td><?php echo $row_Recordset1['tipo_sinestro']; ?></td>
        <td><a href="/include/editar/editar_siniestro.php?sinistro=<?php echo $row_Recordset1['id']; ?>&clientid=<?php echo $row_Recordset1['id_cliente']; ?>"><img src="../../imagenes/iconos/edit.png" width="20" height="20" alt="editar" longdesc="editar expediente" /></a> <a href="../eliminar/eliminar_siniestro.php?sinistro=<?php echo $row_Recordset1['id']; ?>&clientid=<?php echo $_GET['clientid']; ?>"><img src="../../imagenes/iconos/delete.png" width="20" height="20" alt="borrar" longdesc="borrar expediente" /></a><a><a href="/include/vista_datos/datos_cliente.php?sinistro=<?php echo $row_Recordset1['id']; ?>"><img src="../../imagenes/iconos/perfil.png" width="20" height="20" alt="mas perfil" longdesc="expediente completo" /></a></td>
      </tr>
      <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
  </table>
      <table border="0">
        <tr>
          <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>"><img src="../../imagenes/First.gif" /></a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>"><img src="../../imagenes/Previous.gif" /></a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>"><img src="../../imagenes/Next.gif" /></a>
            <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>"><img src="../../imagenes/Last.gif" /></a>
            <?php } // Show if not last page ?></td>
        </tr>
      </table>
      
      <!--Este trozo sirve para que se modifique los contratos en archivos RTF -->
      
      <?php
 
// Lee la plantilla
$plantilla = file_get_contents('../../plantillas_doc/prueba.rtf');
// Agregamos los escapes necesarios
$plantilla = addslashes($plantilla);
$plantilla = str_replace('\r','\\r',$plantilla);
$plantilla = str_replace('\t','\\t',$plantilla);
 
// Datos de la plantilla
$nombre = $row_mostrar_datos_cliente['nombre'];
$apellido1 = $row_mostrar_datos_cliente['apellidos1'];
$apellido2 = $row_mostrar_datos_cliente['apellidos2'];
 
// Procesa la plantilla
eval( '$rtf = <<<EOF_RTF
' . $plantilla . '
EOF_RTF;
' ); 
// Guarda el RTF generado, el nombre del RTF en este caso sera el apellido-nombre.fechaactual.rtf
file_put_contents("../../tmp/$apellido1-$apellido2-$nombre.rtf",$rtf);
 
echo "<a href=\"/tmp/$apellido1-$apellido2-$nombre.rtf\">descargar</a>";
?>
      </a>
      
      
      <!--Fin del trozo -->
    
<?php } // Show if recordset not empty ?>
  <p><a href="../anadir/nuevo_siniestro.php?clientid=<?php echo $row_mostrar_datos_cliente['id']; ?>"><img src="../../imagenes/iconos/add.png" width="20" height="20" alt="añadir" longdesc="añadir expediente" /></a></p>
  <p><br />
  </p>
    </div>
    
    <p>&nbsp;</p>
    <div class=expedientes>
      <p>Otros Expedientes</p>
      <table width="100%" border="1">
        <tr>
          <td>Fecha del siniestro</td>
          <td>Apertura Expediente</td>
          <td>Tipo de caso</td>
          <td>Acciones</td>
        </tr>
        <tr>
          <td><?php echo $row_siniestro_notrafico['id']; ?></td>
          <td><?php echo $row_siniestro_notrafico['fecha apertura']; ?></td>
          <td><?php echo $row_siniestro_notrafico['tipo_caso']; ?></td>
          <td><a href="../nuevo/editar_siniestro_notrafico.php?nosinistro=<?php echo $row_siniestro_notrafico['id']; ?>"><img src="../../imagenes/iconos/edit.png" width="20" height="20" alt="editar" longdesc="editar expediente" /></a> <img src="../../imagenes/iconos/delete.png" width="20" height="20" alt="borrar" longdesc="borrar expediente" /><a><a href="/include/vista_datos/datos_cliente.php?sinistro=<?php echo $row_Recordset1['id']; ?>"><img src="../../imagenes/iconos/perfil.png" width="20" height="20" alt="mas perfil" longdesc="expediente completo" /></a></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <p><a href="/include/nuevo/Nuevo_siniestro_notrafico.php?clientid=<?php echo $row_mostrar_datos_cliente['id']; ?>"><img src="../../imagenes/iconos/add.png" width="20" height="20" alt="añadir" longdesc="añadir expediente" /></a></p>
    </div>
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

mysql_free_result($mostrar_datos_cliente);

mysql_free_result($siniestro_notrafico);
?>
