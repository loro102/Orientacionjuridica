<?php
include ('../config.php');
include ('../codebase/connector/scheduler_connector.php');
	
	$res=mysql_connect(BASS_HOST, BASS_USER, BASS_PASS);
	mysql_query("SET NAMES utf8;", $res);
	
	mysql_select_db(BASS_BASENAME);
	$scheduler = new schedulerConnector($res);
	$cid = trim($_GET["id"]);
	
	if ($scheduler->is_select_mode())
		$scheduler->render_sql("SELECT e.*, c.color FROM `events` e INNER JOIN calendar c ON e.calendar_id = c.calendar_id  WHERE c.calendar_id = '".$cid."' AND c.sharing='1' ", "event_id","start_date,end_date,event_name,details,calendar_id,color");
	
?>