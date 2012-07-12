<?
include_once ('../config.php');
include_once("../component/mysql_module.php");
include_once("../function.php");
$base = new mysql_database();
$base->tablename = "settings"; 

// Settings value
$base->use_fields = "*";
$stt=$base->select("WHERE user_id ='".$_SESSION["s_user_id"]."' ");
if($base->numrow()>0)
	do{
		$settings[$stt['settings_id']] = $stt['value'];
	} while ($stt=$base->isNext());

?>