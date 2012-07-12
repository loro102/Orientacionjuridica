<?
include ('../config.php');
include("buffer.php");
//	error_reporting(E_ALL );
include("../component/mysql_module.php");
include("../function.php");

function htmlspecialchars_deep($mixed) { 
    return htmlspecialchars(htmlspecialchars_decode($mixed, ENT_QUOTES), ENT_QUOTES, 'UTF-8'); ; 
} 

///////// GET SHARING LIST ///////////////
$base = new mysql_database();
$base->tablename = "calendar_sharing"; 
$user_id = $_SESSION["s_user_id"];
$calendar=$base->select("WHERE user_id='".$user_id."' AND `show`='1' AND (calendar_id_shared is NULL OR calendar_id_shared  = 0)  ");
if($user_id=="")exit;

	require_once( '../component/iCalcreator.class.php' ); 
	ob_clean();
	header("Content-type:text/xml");
	echo "<?xml version='1.0' encoding='utf-8'?>";
	echo "<data>";
	
	//// SET PERIOD 
	$e_time = time()+(240*24*3600);// plus 240 days (8 months)
	$e_year = date('Y',$e_time);
	$e_month = date('m',$e_time);
	$s_time = time()-(120*24*3600);// minus 120 days (4 months)
	$s_year = date('Y',$s_time);
	$s_month = date('m',$s_time);
	
//// LOOP EACH SHARING CALENDAR /////
do{
	/////////////// ICAL READER ///////////////
	$v = new vcalendar();
		/// GET DOMAIN NAME
		preg_match("/^((http?|https|ical|webcal):\/\/)?([^\/]+)/i", $calendar['url'], $domain);
		$host = str_replace("www.","",$domain[3]);
	$v->setConfig( 'unique_id', $host );
	$v->setProperty( 'method', 'PUBLISH' );
	$v->setProperty( "X-WR-CALNAME", $calendar['calendar_name'] );
	$v->setProperty( "X-WR-CALDESC", $calendar['calendar_description'] );
	$v->setProperty( "X-WR-TIMEZONE", "Asia/Bangkok" );
	$v->setConfig( 'url', $calendar['url'] );
	$v->parse();
	$v->sort();
	
	$eventArray = $v->selectComponents($s_year, $s_month, 1, $e_year, $e_month, 1);
	foreach( $eventArray as $year => $yearArray) {
	 foreach( $yearArray as $month => $monthArray ) {
	  foreach( $monthArray as $day => $dailyEventsArray ) {
	   foreach( $dailyEventsArray as $vevent ) {
		//$currddate = $vevent->getProperty( 'x-current-dtstart' );
		$uid = $vevent->getProperty( 'uid' );
		$uidCut = explode("@",$uid,2);
		$str .= "<event id='".$uidCut[0]."' >";
		$start_date = setDateTZ($vevent->getProperty('dtstart'));
		$str .= "<start_date><![CDATA[".$start_date."]]></start_date>";
		$end_date = setDateTZ($vevent->getProperty('dtend'));
//		$end_date =($end_date==" 00:00:00") ? $start_date : $end_date;
		$str .= "<end_date><![CDATA[".$end_date."]]></end_date>";
		$str .= "<text><![CDATA[".htmlspecialchars_deep($vevent->getProperty( 'summary' ))."]]></text>";
		$str .= '<details><![CDATA['.htmlspecialchars_deep($vevent->getProperty( 'description' )).']]></details>';
		$str .= '<color><![CDATA['.$calendar['color'].']]></color>';
		$str .= '<calendar_id>'.$calendar['calendar_id'].'</calendar_id>';
		$str .= '<readonly>1</readonly>';
		$str .= '</event>
		';
	   }
	  }
	 }
	}
	echo $str;
} while ($calendar=$base->isNext());
echo "</data>";

ob_end_flush();
/*echo "<pre>";
print_r($eventArray);
echo "</pre>";*/
?>