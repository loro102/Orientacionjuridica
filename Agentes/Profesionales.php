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
?>
<?php
$colname_Recordset1 = "";
if (isset($_POST['busqueda'])) {
  $colname_Recordset1 = $_POST['busqueda'];
}


mysql_select_db($database_OJ, $OJ);
$query_Recordset1 = sprintf("SELECT * FROM profesionales WHERE nombre_fiscal LIKE %s OR profesionales.nombre_comercial LIKE %s OR profesionales.especialidad LIKE %s OR profesionales.num_colegiado LIKE %s", GetSQLValueString("%" . $colname_Recordset1 . "%", "text"),GetSQLValueString("%" . $colname_Recordset1 . "%", "text"),GetSQLValueString("%" . $colname_Recordset1 . "%", "text"),GetSQLValueString("%" . $colname_Recordset1 . "%", "text"));
$Recordset1 = mysql_query($query_Recordset1, $OJ) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
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
  <body onload="ocultarColumna(2,false),ocultarColumna(6,false),ocultarColumna(8,false),ocultarColumna(9,false),ocultarColumna(10,false),ocultarColumna(11,false),ocultarColumna(12,false),ocultarColumna(13,false),ocultarColumna(14,false),ocultarColumna(15,false),ocultarColumna(16,false),ocultarColumna(17,false),ocultarColumna(18,false),ocultarColumna(19,false),ocultarColumna(20,false),ocultarColumna(21,false),ocultarColumna(22,false),ocultarColumna(23,false),ocultarColumna(24,false),ocultarColumna(25,false),ocultarColumna(26,false)">
    <h1>Consulta de profesionales    </h1>
    <form id="form1" name="form1" method="post" action="Profesionales.php">
      <p>
        <input type="text" name="busqueda" id="busqueda" />
        <input type="submit" name="Buscar" id="Buscar" value="Buscar" />
        <a href="../include/anadir/nuevo_profesional.php">Nuevo </a></p>
    </form>
    <p>&nbsp;</p>
    <table border="1"id="tabla" class="cebra">
      <tr class="consulta">
        <td nowrap="nowrap">Nombre Fiscal</td>
        <td nowrap="nowrap">Nombre Comercial</td>
        <td nowrap="nowrap">Direccion</td>
        <td nowrap="nowrap">CP</td>
        <td nowrap="nowrap">Localidad</td>
        <td nowrap="nowrap">Provincia</td>
        <td nowrap="nowrap">Nif</td>
        <td nowrap="nowrap">Tel1</td>
        <td nowrap="nowrap">Tel2</td>
        <td nowrap="nowrap">Fax</td>
        <td nowrap="nowrap">Email</td>
        <td nowrap="nowrap">Email2</td>
        <td nowrap="nowrap">Homologado</td>
        <td nowrap="nowrap">Especialidad</td>
        <td nowrap="nowrap">Num. Colegiado</td>
        <td nowrap="nowrap">CCC</td>
        <td nowrap="nowrap">Mailing</td>
        <td nowrap="nowrap">Notas1</td>
        <td nowrap="nowrap">Notas2</td>
        <td nowrap="nowrap">Acuerdo Econ.</td>
        <td nowrap="nowrap">Sector Profesional</td>
        <td nowrap="nowrap">Acuerdo Pago</td>
        <td nowrap="nowrap">Cuenta Factura</td>
      </tr>
      <?php do { ?>
        <tr bgcolor="#FFFFFF">
          <td nowrap="nowrap"><a href="../include/fichas/ficha_profesional.php?profe=<?php echo $row_Recordset1['id']; ?>"><?php echo $row_Recordset1['nombre_fiscal']; ?></a></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['nombre_comercial']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['direccion']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['cp']; ?></td>
          <td nowrap="nowrap"><?php echo obtenerlocalidad($row_Recordset1['localidad']); ?></td>
          <td nowrap="nowrap"><?php echo obtenerprovincia($row_Recordset1['provincia']); ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['nif']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['tel1']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['tel2']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['fax']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['email']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['email2']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['homologado']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['especialidad']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['num_colegiado']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['cccc']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['mailing']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['notas1']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['notas2']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['acuerdo_eco']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['sector_pro']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['acuerdo_pago']; ?></td>
          <td nowrap="nowrap"><?php echo $row_Recordset1['cuenta_factura']; ?></td>
        </tr>
        <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
    </table>
<p></p> 
<p>
      <input type="button" value="Mostrar todo" onclick="ocultarColumna(1,true),ocultarColumna(2,true),ocultarColumna(3,true),ocultarColumna(4,true),ocultarColumna(5,true),ocultarColumna(6,true),ocultarColumna(7,true),ocultarColumna(8,true),ocultarColumna(9,true),ocultarColumna(10,true),ocultarColumna(11,true),ocultarColumna(12,true),ocultarColumna(13,true),ocultarColumna(14,true),ocultarColumna(15,true),ocultarColumna(16,true),ocultarColumna(17,true),ocultarColumna(18,true),ocultarColumna(19,true),ocultarColumna(20,true),ocultarColumna(21,true),ocultarColumna(22,true),ocultarColumna(23,true),ocultarColumna(24,true),ocultarColumna(25,true),ocultarColumna(26,true)" />
    </p>
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
?>
