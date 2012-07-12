<?php
include ('../config.php');
include ('../codebase/connector/scheduler_connector.php');
include("../function.php");
chkLogin(false);
	
	$res=mysql_connect(BASS_HOST, BASS_USER, BASS_PASS);
	mysql_query("SET NAMES utf8;", $res);
	
	mysql_select_db(BASS_BASENAME);
	
	$scheduler = new schedulerConnector($res);
	$scheduler->enable_log("log.txt",true);
//	$scheduler->render_table("events","event_id","start_date,end_date,event_name,details");

	$user_id = trim($_SESSION["s_user_id"]);
//	$calendar_id = intval($_SESSION["s_calendar_id"]);
	
	function default_values($action){
		global $calendar_id;
		$calendar_id = $action->get_value("calendar_id");
		if ($calendar_id == "")  $calendar_id = $_SESSION["s_user_cal_id"];
		$action->set_value("calendar_id",$calendar_id);
	}
	$scheduler->event->attach("beforeProcessing","default_values");
	
	if ($scheduler->is_select_mode())
		$scheduler->render_sql("SELECT e.*, c.color FROM `events` e INNER JOIN calendar c ON e.calendar_id = c.calendar_id  WHERE c.user_id = '".$user_id."' ", "event_id","start_date,end_date,event_name,details,calendar_id,color,readonly");
	else $scheduler->render_table("`events`","event_id","start_date,end_date,event_name,details,calendar_id");
?>