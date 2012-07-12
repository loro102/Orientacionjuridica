<?php require_once('../Connections/OJ.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> <!-- InstanceBegin template="/Templates/Programa.dwt.php" codeOutsideHTMLIsLocked="false" -->
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
$colname_cientes = "";
if (isset($_POST['busqueda'])) {
  $colname_cientes = $_POST['busqueda'];
}

$totalRows_cientes = "0";
if (isset($_POST['busqueda'])) {
  $totalRows_cientes = $_POST['busqueda'];
}

mysql_select_db($database_OJ, $OJ);
$query_cientes = sprintf("SELECT * FROM clientes WHERE nombre LIKE %s OR clientes.apellidos1 LIKE %s OR clientes.apellidos2 LIKE %s OR clientes.nif LIKE %s", GetSQLValueString("%" . $colname_cientes . "%", "text"),GetSQLValueString("%" . $colname_cientes . "%", "text"),GetSQLValueString("%" . $colname_cientes . "%", "text"),GetSQLValueString("%" . $colname_cientes . "%", "text"));
$cientes = mysql_query($query_cientes, $OJ) or die(mysql_error());
$row_cientes = mysql_fetch_assoc($cientes);
$totalRows_cientes = mysql_num_rows($cientes);
?>
    <script type="text/javascript">
<!--
function ocultarFila(num,ver) {
  dis= ver ? '' : 'none';
  tab=document.getElementById('tabla');
  tab.getElementsByTagName('tr')[num].style.display=dis;
}
function ocultarColumna(num,ver) {
  dis= ver ? '' : 'none';
  fila=document.getElementById('tabla').getElementsByTagName('tr');
  for(i=0;i<fila.length;i++)
    fila[i].getElementsByTagName('td')[num].style.display=dis;
}
-->
</script>
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
  
    <h1>Consulta de Clientes    </h1>
    <form action="cliente.php" method="post" name="form1" id="form1" >
      <input type="text" name="busqueda" id="busqueda" />
      <input type="submit" name="Buscar" id="Buscar" value="Buscar" />
      <a href="../include/anadir/nuevo_cliente.php">Nuevo </a>
    </form>
    <table id="tabla" width="100%" border="1" align="left" class="cebra">
    
      <tr class="consulta">
      <div class="consulta">
        <td width="250" nowrap="nowrap">Cliente</td>
        <td width="5%" nowrap="nowrap">Direccion</td>
        <td width="6%" nowrap="nowrap">CP</td>
        <td width="5%" nowrap="nowrap">Localidad</td>
        <td width="5%" nowrap="nowrap">Provincia</td>
        <td width="3%" nowrap="nowrap">Nif</td>
        <td width="7%" nowrap="nowrap">Nacido en.:</td>
        <td width="6%" nowrap="nowrap">Abonado en:</td>
        <td width="5%" nowrap="nowrap">Telf1</td>
        <td width="5%" nowrap="nowrap">Telf2</td>
        <td width="4%" nowrap="nowrap">Fax </td>
        <td width="4%" nowrap="nowrap">Email</td>
        <td width="4%" nowrap="nowrap">Notas</td>
        <td width="5%" nowrap="nowrap">Profesion</td>
        <td width="5%" nowrap="nowrap">Agente</td>
        <td width="4%" nowrap="nowrap">Precio</td>
        <td width="4%" nowrap="nowrap">CCC</td>
        <td width="5%" nowrap="nowrap">Mailing</td>
        <td width="13%" nowrap="nowrap">Empleado</td>
        </div>
      </tr>
      
      
      <?php do { ?>
      
      <tr bgcolor="#FFFFFF">
        <td width="250" height="10" nowrap="nowrap"><a href="../include/fichas/ficha_cliente.php?clientid=<?php echo $row_cientes['id']; ?>"><?php echo $row_cientes['nombre']; ?> <?php echo $row_cientes['apellidos1']; ?> <?php echo $row_cientes['apellidos2']; ?></a></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['direccion']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['codigopostal']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo obtenerlocalidad($row_cientes['localidad']); ?></td>
        <td height="10" nowrap="nowrap"><?php echo obtenerprovincia($row_cientes['provincia']); ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['nif']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['fechanacimiento']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['fecha_abono']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['telefono1']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['telefono2']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['fax']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['email']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['notas']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['profesion']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo obtenernombreagente($row_cientes['agente']); ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['precio']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['CCC']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['mailing']; ?></td>
        <td height="10" nowrap="nowrap"><?php echo $row_cientes['id_empleado'] ?></td>
      </tr>
      <?php } while ($row_cientes = mysql_fetch_assoc($cientes)); ?>
    </table>
    <p>&nbsp; </p>
    <p>&nbsp;</p>
    <p>
      <input type="button" value="Mostrar todo" onclick="ocultarColumna(1,true),ocultarColumna(3,true),ocultarColumna(2,true),ocultarColumna(4,true),ocultarColumna(5,true),ocultarColumna(6,true),ocultarColumna(7,true),ocultarColumna(8,true),ocultarColumna(9,true),ocultarColumna(10,true),ocultarColumna(11,true),ocultarColumna(12,true),ocultarColumna(13,true),ocultarColumna(14,true),ocultarColumna(15,true),ocultarColumna(16,true),ocultarColumna(17,true),ocultarColumna(18,true),ocultarColumna(19,true),ocultarColumna(20,true),ocultarColumna(21,true)" />
    </p>
    <body onload="ocultarColumna(1,false),ocultarColumna(5,false),ocultarColumna(6,false),ocultarColumna(7,false),ocultarColumna(10,false),ocultarColumna(12,false),ocultarColumna(13,false),ocultarColumna(15,false),ocultarColumna(16,false),ocultarColumna(17,false),ocultarColumna(18,false),ocultarColumna(19,false),ocultarColumna(20,false),ocultarColumna(21,false)">
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
mysql_free_result($cientes);
?>
