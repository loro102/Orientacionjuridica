<?php 
ini_set('session_save_path', 'tmp');
session_name('MM_id');
if(@session_start() == false){session_destroy();session_start();}
?>
<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_OJ = "mysql.maxevolution.tk";
$database_OJ = "u731166316_oj";
$username_OJ = "u731166316_oj";
$password_OJ = "123456";
$OJ = mysql_pconnect($hostname_OJ, $username_OJ, $password_OJ) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
<?php 
//se llama al archivo de includes
if (is_file("/Connections/funciones.php"))
{
	 include("/Connections/funciones.php");
}
else
{
	include("funciones.php");	
	}
?>