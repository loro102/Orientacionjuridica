<?php require_once('../../Connections/OJ.php');?>
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

$sinistro_Recordset1 = "0";
if (isset($_GET["sinistro"])) {
  $sinistro_Recordset1 = $_GET["sinistro"];
}
mysql_select_db($database_OJ, $OJ);
$query_Recordset1 = sprintf("SELECT * FROM siniestro WHERE siniestro.id=%s", GetSQLValueString($sinistro_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $OJ) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

$fulanda_Recordset2 = "0";
if (isset($_GET["sinistro"])) {
  $fulanda_Recordset2 = $_GET["sinistro"];
}
mysql_select_db($database_OJ, $OJ);
$query_Recordset2 = sprintf("SELECT * FROM contrario WHERE  contrario.id_siniestro=%s", GetSQLValueString($fulanda_Recordset2, "int"));
$Recordset2 = mysql_query($query_Recordset2, $OJ) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

$sinistro_Recordset1 = "0";
if (isset($_GET["sinistro"])) {
  $sinistro_Recordset1 = $_GET["sinistro"];
}
mysql_select_db($database_OJ, $OJ);
$query_Recordset1 = sprintf("SELECT * FROM  siniestro WHERE siniestro.id=%s", GetSQLValueString($sinistro_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $OJ) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
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
    <div class=textotabla>
    <table width="100%" border="1">
      <tr bgcolor="#FFFF66">
        <td><a href="/include/vista_datos/datos_cliente.php?sinistro=<?php echo $_GET['sinistro']; ?>">Datos Siniestro</a></td>
        <td><a href="/include/vista_datos/profesionales.php?sinistro=<?php echo $_GET['sinistro']; ?>">Profesionales</a></td>
        <td><a href="/include/vista_datos/facturas.php?sinistro=<?php echo $_GET['sinistro'];?>">Facturas</a></td>
        <td><a href="datos_sanitarios.php?sinistro=<?php echo $_GET['sinistro'];?>">Datos Sanitarios</a></td>
        <td>Indemnización</td>
        <td>Documentacion</td>
        <td><a href="/include/vista_datos/notas_expediente.php?sinistro=<?php echo $_GET['sinistro']; ?>">Notas</a></td>
      </tr>
    </table>
    </div>
    
    <?php 
	
		 ?>
    <p>&nbsp;</p>
    <div class="datos_clientes">
      <h2>Datos cliente      </h2>
      <table width="100%" border="0">
        <tr>
          <td>Nombre:  <?php echo obtenerdatosclientes ($row_Recordset1['id_cliente']); ?></td>
          <td>&nbsp;</td>
          <td>NIF:<?php echo obtenerdatosnif($row_Recordset1['id_cliente']);?></td>
        </tr>
      </table>
      <table width="100%" border="0">
        <tr>
          <td>Direccion:<?php echo obtenerdireccion($row_Recordset1['id_cliente']);?>   </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <table width="100%" border="0">
        <tr>
          <td>Fecha de nacimiento:<?php echo obtenernacimiento($row_Recordset1['id_cliente']);?></td>
          <td>&nbsp;</td>
          <td>agente:
            <?php 
		  echo obtenerprofesion($row_Recordset1['id_cliente']); ?></td>
        </tr>
      </table>
      <table width="100%" border="0">
        <tr>
          <td>Telefono:<?php echo obtenertelefono1($row_Recordset1['id_cliente']);?></td>
          <td>Movil:<?php echo obtenertelefono2($row_Recordset1['id_cliente']);?></td>
          <td>Fax:<?php echo obtenerfax($row_Recordset1['id_cliente']);?></td>
        </tr>
      </table>
      <table width="100%" border="0">
        <tr>
          <td>E-mail:<?php echo obtenermail($row_Recordset1['id_cliente']);?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </div>
    <p>&nbsp;</p>
    <div class="datos_expediente">
      <h2>Datos Expediente: </h2>
      <p>Caso tipo:<?php echo $row_Recordset1['tipo_caso']; ?></p>
    <table width="100%" border="0">
      <tr>
        <td>Fecha de apertura:<?php $fecha = $row_Recordset1['apertura_expediente']; 
		echo (formatofecha($fecha));?></td>
        <td>Fecha de cierre:<?php if ($row_Recordset1['cierre_expediente'] == NULL)
		{ 
		echo "no esta cerrado";
		}
		else 
		{
			$fecha = $row_Recordset1['cierre_expediente']; 
		echo (formatofecha($fecha));
			
		} ?></td>
        
        <td>Fecha de archivo:<?php if ($row_Recordset1['archivado'] == NULL)
		{ 
			echo "no esta archivado";
		}
		else 
		{
			$fecha = $row_Recordset1['archivado']; 
		echo (formatofecha($fecha));
		
		}?></td>
      </tr>
  </table>
    <table width="100%" border="0">
      <tr>
        <td>Fecha y hora de siniestro:<?php echo $row_Recordset1['fecha_siniestro']; ?> <?php echo $row_Recordset1['hora']; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <table width="100%" border="0">
      <tr>
        <td>Desarrollo:<?php echo $row_Recordset1['desarrollo_accidente']; ?></td>
        <td>Circunstancias:<?php echo $row_Recordset1['circunstancias']; ?></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><a href="/include/editar/editar_siniestro.php?sinistro=<?php echo $_GET['sinistro']; ?>&clientid=<?php echo $row_Recordset1['id_cliente'];?>">modificar</a></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    </div>
    <h2>&nbsp;</h2>
    <div class=expedientes>
    <h2>Datos Vehículo</h2>
    <table width="100%" border="0">
      <tr>
        <td>Marca y modelo:<?php echo $row_Recordset1['vehiculo']; ?></td>
        <td>&nbsp;</td>
        <td>Matricula:<?php echo $row_Recordset1['matricula']; ?></td>
      </tr>
    </table>
    <table width="100%" border="0">
      <tr>
        <td>Tomador:<?php echo $row_Recordset1['tomador_seguro']; ?></td>
        <td>&nbsp;</td>
        <td>Conductor:<?php echo $row_Recordset1['conductor']; ?></td>
      </tr>
    </table>
    <table width="100%" border="0">
      <tr>
        <td>Nº Póliza:<?php echo $row_Recordset1['num_poliza']; ?></td>
        <td>Fecha Póliza:<?php echo $row_Recordset1['fecha_poliza']; ?></td>
        <td>Fin Póliza:<?php echo $row_Recordset1['caducidad_poliza']; ?></td>
      </tr>
    </table>
    <table width="100%" border="0">
      <tr>
        <td>Aseguradora:<?php echo (obtenercia($row_Recordset1['CIA'])); ?></td>
        <td>Tramitador:<?php echo (obtenertramitadorcia($row_Recordset1['tramitador_exp'])); ?></td>
        <td>Ref. Expediente:<?php echo $row_Recordset1['referencia_expediente']; ?></td>
      </tr>
    </table>
    </div>
    <p>&nbsp;</p>
    <div class="datos_expediente">
      <h2>Contrario </h2>
      <?php if ($totalRows_Recordset2 == 0) { // Show if recordset empty ?>
        <p>No tiene ningun contrario insertado</p>
        <p><a href="/include/vista_datos/Insertar contrario.php?sinistro=<?php echo $_GET['sinistro']; ?>" target="_new">insertar contrario</a></p>
        <?php } // Show if recordset empty ?>
        <?php if ($totalRows_Recordset2 > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0">
    <tr>
      <td>Nombre:<?php echo $row_Recordset2['nombre']; ?> <?php echo $row_Recordset2['apellido1']; ?> <?php echo $row_Recordset2['apellido2']; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Direccion:<?php echo $row_Recordset2['direccion']; ?> <?php echo $row_Recordset2['cp']; ?> <?php echo $row_Recordset2['localidad']; ?> <?php echo $row_Recordset2['provincia']; ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
          <table width="100%" border="0">
            <tr>
              <td>Marca y Modelo:<?php echo $row_Recordset2['vehiculo']; ?></td>
              <td>&nbsp;</td>
              <td>Matricula:<?php echo $row_Recordset2['matricula']; ?></td>
            </tr>
          </table>
          <table width="100%" border="0">
            <tr>
              <td>Tomador:<?php echo $row_Recordset2['tomador']; ?></td>
              <td>&nbsp;</td>
              <td>Conductor:<?php echo $row_Recordset2['conductor']; ?></td>
            </tr>
          </table>
          <table width="100%" border="0">
            <tr>
              <td width="31%">Nº Poliza:<?php echo $row_Recordset2['numeropoliza']; ?></td>
              <td width="36%">Fecha Poliza:</td>
              <td width="33%">Fin Póliza:</td>
            </tr>
          </table>
          <table width="100%" border="0">
            <tr>
              <td>Aseguradora:<?php echo $row_Recordset2['cia']; ?></td>
              <td>&nbsp;</td>
              <td>Tramitador:</td>
            </tr>
          </table>
        <table width="100%" border="0">
            <tr>
              <td>Ref Expediente:</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
          <a href="/include/vista_datos/editar_contrario.php?sini=<?php echo $row_Recordset2['id']; ?>&sinistro=<?php echo $row_Recordset1['id']; ?>">Modificar datos</a><br />
          
          <?php } // Show if recordset not empty ?>
    </div>
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

mysql_free_result($Recordset2);
?>
