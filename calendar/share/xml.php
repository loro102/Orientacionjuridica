<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");

$base = new mysql_database();
$base->tablename = "calendar"; 
//$cal_id = mysql_real_escape_string(base64_decode($_GET['id']));
$cal_id = mysql_real_escape_string(($_GET['id']));
$calendar=$base->select("WHERE calendar_id='".$cal_id."' AND sharing='1' ");
$user_id = $calendar['user_id'];
$cal_id_en64 = base64_encode($calendar['calendar_id']);
if($calendar['calendar_id']==0){echo "No valid for using this calendar.";exit;}

ob_clean();
header("Content-type:text/xml");
echo "<?xml version='1.0' encoding='utf-8'?>";
echo '<feed xmlns="http://www.w3.org/2005/Atom" xml:lang="en" xml:base="'.BASE_URL.'">';
echo "<id>".BASE_URL."calendar/".$user_id."/share/edit/".base64_encode($calendar['calendar_id'])."</id>";
echo '<title>'.$calendar['calendar_name'].'</title>';
echo '<updated>'.date('c').'</updated>';
echo '<link href="'.BASE_URL.'calendar/" />';
echo '<link rel="self" href="'.BASE_URL.'calendar/" />';

$base->tablename = "events"; 
$ev=$base->select("WHERE calendar_id='".$cal_id."'");
do{
	echo "<entry>";
		echo "<id>".htmlspecialchars(BASE_URL."calendar/".$user_id."/share/edit/".$ev['event_id	'])."</id>";;
		echo "<title>".htmlspecialchars(substr($ev['event_name'],0,150))."</title>";
		echo "<link href=\"/calendar/".$ev['event_id	']."\" />";
		echo "<updated>".date('c',strtotime($ev['start_date']))."</updated>";
		echo "<author><name>".$user_id."</name><email>".trim($user_id)."</email></author>";
		echo "<content type='text'>".htmlspecialchars(substr($ev['event_name'],0,150))."</content>";
		echo "<summary type='html'>".htmlspecialchars($ev['details']."<br>")."From ".$ev['start_date']." to ".$ev['start_date']."</summary>";
	echo "</entry>";
} while ($ev=$base->isNext());
echo "</feed>";
?>