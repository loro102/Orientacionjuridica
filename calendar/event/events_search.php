<?
//error_reporting(E_ALL);
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
chkLogin(false);

$base = new mysql_database();
$search_txt = mysql_real_escape_string(($_GET['q']));
$ev=$base->execute("SELECT e.*, c.color FROM `events` e INNER JOIN calendar c ON e.calendar_id = c.calendar_id  WHERE c.user_id = '".trim($_SESSION["s_user_id"])."'  AND (e.event_name LIKE '%".$search_txt."%' OR e.details LIKE '%".$search_txt."%') ");

ob_clean();
header("Content-type:text/xml");
echo "<?xml version='1.0' encoding='utf-8'?>";
echo '<rows><head>
		<column width="*" type="ro">Events</column>
		<column width="*" type="txt">Detail</column>
		<column width="80" type="dhxCalendar">Date</column>
	</head>';
while ($ev=$base->isNext()){
	echo '<row id="'.$ev['event_id'].'">';
	echo '<cell>'.htmlspecialchars($ev['event_name']).'</cell>';
	echo '<cell>'.htmlspecialchars($ev['details']).'</cell>';
	echo '<cell>'.date("d/m/Y",strtotime($ev['start_date'])).'</cell></row>';
} ;
echo "</rows>";
?>