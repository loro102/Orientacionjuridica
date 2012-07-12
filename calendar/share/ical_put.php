<?
include ('../config.php');
$calendar_id = array_shift(explode('.',basename($_SERVER['REQUEST_URI'])));
$filename = $calendar_id.'_'.session_id().'.ics';
if ($_SERVER['REQUEST_METHOD'] == "PUT"){
	/* PUT data comes in on the stdin stream */
	$putdata = fopen("php://input", "r");
	$fp = fopen($filename, "w");
	/* Read the data 1 KB at a time and write to the file */
	while ($data = fread($putdata, 1024)) fwrite($fp, $data);
		fclose($fp);
		fclose($putdata);
 }else exit;

include("../component/mysql_module.php");
include("../function.php");
require_once( '../component/iCalcreator.class.php' ); 


//////////////////////////////////////////////////////// ICAL INSERT AND UPDATE EVENT //////////////////////////////////////////////////
$c = new vcalendar();
$c->setConfig( "filename", $filename );
if( FALSE === $c->parse() ) exit;
else{
	$base = new mysql_database();
	$base->tablename = "calendar"; 
	
	/// Authurize Checking
	if($base->countrow("WHERE calendar_id='$calendar_id' AND sharing = '1' AND adjustable = '1' ")==1){
		$c->sort();
		$types = $c->getConfig( 'compsinfo' ); // get type info about each component
		$base->tablename = "events"; 
		
		/// Loop Each Component from ICAL
		foreach( $types as $component ) {
		  $comp = $c->getComponent();
			$last = $comp->getProperty('last-modified');
			$uid = base64_decode(array_shift(explode('@', $comp->getProperty('uid') )));
			$new_uid[] = $uid;
	
			if(!empty($last)){
				$event_name = $comp->getProperty( 'summary' );
				$details = $comp->getProperty( 'description' );
				$dtstart = $comp->getProperty('dtstart');
				$start_date = setDateTZ($dtstart);
				$dtend = $comp->getProperty('dtend');
				$end_date = setDateTZ($dtend);
	
			if($base->countrow("WHERE event_id='$uid'  ")==0)
			  $sql = 'INSERT INTO events (event_name, details, start_date, end_date, calendar_id) VALUES' 
													." ('$event_name', '$details', '$start_date', '$end_date', '$calendar_id' )";
			else
			  $sql = "UPDATE events SET event_name='$event_name', details='$details', start_date='$start_date', end_date='$end_date' WHERE event_id='$uid' ";
			$base->execute($sql);
			
			}
		}
		
		// Delete Event
		$base->deletes("WHERE event_id NOT IN ('".implode("','", $new_uid)."') AND calendar_id='$calendar_id'  LIMIT 5");

	} // if authurize
	//errorlog(implode("','", $new_uid));
}
@unlink($filename);
?>