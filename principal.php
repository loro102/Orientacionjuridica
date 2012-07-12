<?php require_once('Connections/OJ.php');

 ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
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
	
  $logoutGoTo = "/index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
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

$MM_restrictGoTo = "index.php";
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

$colname_facturas = "-1";
if (isset($_GET['clientid'])) {
  $colname_facturas = $_GET['clientid'];
}
mysql_select_db($database_OJ, $OJ);
$query_facturas = sprintf("SELECT * FROM facturas WHERE cliente = %s ORDER BY fecha_factura ASC", GetSQLValueString($colname_facturas, "int"));
$facturas = mysql_query($query_facturas, $OJ) or die(mysql_error());
$row_facturas = mysql_fetch_assoc($facturas);
$totalRows_facturas = mysql_num_rows($facturas);

$empleado_Recordset1 = "0";
if (isset($_SESSION['MM_UserGroup'])) {
  $empleado_Recordset1 = $_SESSION['MM_UserGroup'];
}
mysql_select_db($database_OJ, $OJ);
$query_Recordset1 = sprintf("SELECT * FROM alerta WHERE alerta.mostrar_alerta=1 AND alerta.id_empleado=%s OR alerta.id_empleado=6 ORDER BY alerta.fecha ASC", GetSQLValueString($empleado_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $OJ) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
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

	
$logoutGoTo = "index.php";

 
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Documento sin t&iacute;tulo</title>
<link href="estilo/twoColLiqLtHdr.css" rel="stylesheet" type="text/css" />
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
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
<!--calendario -->

<script src="calendario/js/jscal2.js"></script>
<script src="calendario/js/lang/es.js"></script>
<link rel="stylesheet" type="text/css" href="calendario/css/jscal2.css" />
<link rel="stylesheet" type="text/css" href="calendario/css/border-radius.css" />
<link rel="stylesheet" type="text/css" href="calendario/css/steel/steel.css" />

<!--fin calendario -->
<!-- InstanceBeginEditable name="head" -->

<script src="/SpryAssets/SpryCollapsiblePanel.js" type="text/javascript"></script>
<script src="SpryAssets/SpryTooltip.js" type="text/javascript"></script>
<link href="/SpryAssets/SpryCollapsiblePanel.js" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryCollapsiblePanel.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryTooltip.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.textotablaprincipal {
	font-size: small;
}
.textotablaprincipal {
	font-size: small;
}
</style>
<!-- InstanceEndEditable -->
</head>



<body >


<div class="container">
  <div class="header"><ul id="MenuBar1" class="MenuBarHorizontal">
    <li><a href="/principal.php">Inicio</a>      </li>
    <li><a class="MenuBarItemSubmenu" href="#">Clientes</a>
      <ul>
        <li><a href="Agentes/cliente.php">Consultar</a></li>
        <li><a href="include/anadir/nuevo_cliente.php">A&ntilde;adir</a></li>
        <li><a href="/include/listas/Mailing_clientes.php">Mailing</a></li>
      </ul>
    </li>
    <li><a href="#" class="MenuBarItemSubmenu">Profesionales</a>
      <ul>
        <li><a href="Agentes/Profesionales.php">Consultar</a></li>
        <li><a href="/include/anadir/nuevo_profesional.php">A&ntilde;adir</a></li>
        <li><a href="include/listas/Mailing_profesionales.php">Mailing</a></li>
      </ul>
    </li>
    <li><a href="#" class="MenuBarItemSubmenu">Agentes</a>
      <ul>
        <li><a href="Agentes/Agentesphp.php">Consultar</a></li>
        <li><a href="Agentes/Nuevoagente.php">A&ntilde;adir</a></li>
        <li><a href="include/listas/Mailing_agentes.php">Mailing</a></li>
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
    <h1>Principal </h1>
    <p>
      <?php  if ((isset($_SESSION['MM_Username']))&&($_SESSION['MM_Username']!=""))
    {
   		echo "Bienvenido " ;
		echo obtenernombreusuario ($_SESSION['MM_id']);
    }
	else
	{
	}?>
  <a href="<?php echo $logoutAction ?>">Desconectar</a></p>
  <form id="form1" name="form1" method="get" action="/buscar.php">
      <label for="busqueda">
        
        buscar cliente :
        <input type="text" name="busqueda" id="busqueda" />
        <input type="submit" name="button" id="button" value="Buscar" />
      </label>
  </form>
    <form id="form2" name="form2" method="get" action="/busca_sinistro.php">
      Matrícula:
    <label for="busqueda"></label>
    <input type="text" name="busqueda" id="busqueda" />
    <input type="submit" name="button2" id="button2" value="Buscar" />
    </form>
    <p>&nbsp;</p>
    <div id="CollapsiblePanel2" class="CollapsiblePanel">
      <div class="CollapsiblePanelTab" tabindex="0">Alertas:</div>
      <div class="CollapsiblePanelContent">
        <?php if ($totalRows_Recordset1 == 0) { // Show if recordset empty ?>
        <p>No hay alertas</p>
        <?php } // Show if recordset empty ?>
        <?php if ($totalRows_Recordset1 > 0) { // Show if recordset not empty ?>
        <table width="100%" border="1">
          <tr>
            <td width="15%" class="textotablaprincipal">Fecha</td>
            <td width="85%" class="textotablaprincipal">Alerta</td>
          </tr>
          <?php do { ?>
          <tr>
            <td>
			<?php 
			$oldDate = $row_Recordset1['fecha']; // DD/MM/YYYY
 
$parts = explode('-', $oldDate);
 
/**
* Now we have:
*   $parts[0]: the day
*   $parts[1]: the month
*   $parts[2]: the year
* We could also have done:
*   list($day, $month, $year) = explode('/', $oldDate);
*/
 
$newDate = "{$parts[2]}-{$parts[1]}-{$parts[0]}"; // MM-DD-YYYY
 
echo $newDate; // 08-18-2009
			
 ?></td>
            <td><?php echo $row_Recordset1['evento']; ?></td>
          </tr>
          <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
        </table>
        <?php } // Show if recordset not empty ?>
      </div>
    </div>
    <p id="sprytrigger1">&nbsp;</p>
    <p>Clientes con llamadas pendientes(insertar pantalla de llamadas)</p>
    <p>expediente sin revisar en 1 mes(nombre client, fecha siniestro, tramit y fecha de ultima revision)</p>
    <p>fin de procedimiento penal(nombre fecha siniestro y dias que faltan 30 dias antes)</p>
    <p>Expedientes pendientes de factura + de 5 dias (siempre que este en fase pendiente de factura)</p>
    <p>Pendiente de AJ + de 15 dias de fecha de presentacion</p>
    <p>Expedientes pendiente de talon(cuando llega al dia que llega el talon salta y desaparece cuando se haya cobrado empresa o cliente)</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>Agenda:</p>
<p id="sprytrigger2"><a href="http://programa.maxevo.tk/calendar/" target="_new">Insertar</a></p>
<div class="tooltipContent" id="sprytooltip1">
  <p>Para insertar citas o recordatorios por favor use </p>
  <p>estos datos para acceder</p>
  <p>Usuario=&gt;administracion</p>
  <p>Password=&gt;administracion</p>
</div>
<p>
    <?php 
	/*if ($_SESSION['MM_UserGroup']== 1)
     {
 echo ('<iframe src="http://programa.maxevo.tk/calendar/share/2/2.html" width="100%" height="500"></iframe>');
	 }
	 else{
		 if ($_SESSION['MM_UserGroup']== 3){
			 echo ('<iframe src="http://programa.maxevo.tk/calendar/share/2/2.html" width="100%" height="500"></iframe>');
			 }
			 else{
				if ($_SESSION['MM_UserGroup']== 4){
					echo ('<iframe src="http://programa.maxevo.tk/calendar/share/2/2.html" width="100%" height="500"></iframe>');
				}
				else
				{
					if ($_SESSION['MM_UserGroup']== 5){
						echo ('<iframe src="http://programa.maxevo.tk/calendar/share/2/2.html" width="100%" height="500"></iframe>');
					}
					
				}
			 }
	 }
    */?>
    
    <p>&nbsp;</p>
    <p>
      
    <script type="text/javascript">
var CollapsiblePanel2 = new Spry.Widget.CollapsiblePanel("CollapsiblePanel2", {contentIsOpen:false});
    </script>
    <script type="text/javascript">
var sprytooltip1 = new Spry.Widget.Tooltip("sprytooltip1", "#sprytrigger2");
    </script>
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
mysql_free_result($facturas);

mysql_free_result($Recordset1);
?>
