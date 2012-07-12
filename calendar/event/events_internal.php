<?php
include ('../config.php');
include ('../codebase/connector/scheduler_connector.php');
include("../function.php");
chkLogin(false);
	
	$res=mysql_connect(BASS_HOST, BASS_USER, BASS_PASS);
	mysql_query("SET NAMES utf8;", $res);
	
	mysql_select_db(BASS_BASENAME);
	
	$scheduler = new schedulerConnector($res);
	$user_id = trim($_SESSION["s_user_id"]);
	$scheduler->readonly = true;
	mysql_query("SET @ro =  '1'", $res); // No permit to modify

	if ($scheduler->is_select_mode()) 
		$scheduler->render_sql("SELECT @ro AS readonly, e.*, c.color FROM `events` e INNER JOIN calendar_sharing c ON e.calendar_id = c.calendar_id_shared  WHERE c.user_id = '".$user_id."' AND c.show='1' ", "event_id","start_date,end_date,event_name,details,calendar_id,color,readonly");

?>