<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");

$base = new mysql_database();
$base->tablename = "calendar"; 
//$cal_id = mysql_real_escape_string(base64_decode($_GET['id']));
$uid = mysql_real_escape_string(($_GET['uid']));
$cal_id = mysql_real_escape_string(($_GET['id']));
$calendar=$base->select("WHERE calendar_id='".$cal_id."' AND sharing='1' ");
$user_id = $calendar['user_id'];
//$cal_id_en64 = base64_encode($calendar['calendar_id']);
if($calendar['calendar_id']==0 || $uid<>$user_id){echo "No valid for using this calendar.";exit;}

//////////////////////////////////////////////////////// ICAL SELETE /////////////////////////////////////////////////////////////
require_once( '../component/iCalcreator.class.php' ); 
$v = new vcalendar();
$v->setConfig( 'unique_id', $_SERVER['SERVER_NAME'] );
$v->setConfig( 'filename', $cal_id.".ics" );
$v->setProperty( 'method', 'PUBLISH' );
$v->setProperty( "X-WR-CALNAME", $calendar['calendar_name'] );
$v->setProperty( "X-WR-CALDESC", $calendar['calendar_description'] );
$v->setProperty( "X-WR-TIMEZONE", "Asia/Bangkok" );
$v->setProperty( "tzoffsetfrom", "+0".TIMEZONE."00" );
$v->setProperty( "tzoffsetto", "+0".TIMEZONE."00" );

$base->tablename = "events"; 
$ev=$base->select("WHERE calendar_id='".$cal_id."'");
do{
	$vevent = new vevent();
	$vevent->setProperty("uid",base64_encode($ev['event_id'])."@".$_SERVER['SERVER_NAME']);
	$vevent->setProperty( 'dtstart', date("YmdHis", strtotime($ev['start_date']) - (TIMEZONE*3600))."Z" );
	$vevent->setProperty( 'dtend', date("YmdHis", strtotime($ev['end_date']) - (TIMEZONE*3600))."Z" );
	$vevent->setProperty( 'summary', htmlspecialchars($ev['event_name']) );
	$vevent->setProperty( 'description', htmlspecialchars($ev['details']) );
	$vevent->setProperty( 'attendee', $user_id );
	$vevent->setProperty( 'transp', "TRANSPARENT");
	$v->setComponent ( $vevent );
} while ($ev=$base->isNext());
$v->setComponent ( $vevent );
$v->returnCalendar();
?>