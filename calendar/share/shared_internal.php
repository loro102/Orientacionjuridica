<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
chkLogin(false);


$base = new mysql_database();
$search_txt = mysql_real_escape_string(($_GET['q']));

$c=$base->execute("SELECT c.*, user.display_name, user.user_login FROM calendar c, user WHERE ID = user_id AND user_id != '".$_SESSION["s_user_id"]."' AND `sharing` = '1' AND calendar_id NOT IN (SELECT DISTINCT calendar_id_shared FROM calendar_sharing WHERE user_id = '".$_SESSION["s_user_id"]."' AND calendar_id_shared >0 ) "); // เลือกปฏิทินในองค์กรเดียวกันที่ไม่ซ้ำกับที่ใช้งานอยู่

ob_clean();
header("Content-type:text/xml");
echo "<?xml version='1.0' encoding='utf-8'?>";
echo '<rows><head>
		<column width="*" type="ro">Title / Detail</column>
		<column width="150" type="ro">Account</column>
	</head>';
while ($c=$base->isNext()){
	echo '<row id="'.$c['calendar_id'].'">';
	echo '<cell><![CDATA[<b>'.htmlspecialchars($c['calendar_name'])."</b>: ".substr(htmlspecialchars($c['calendar_description']),0,100).']]></cell>';
	echo '<cell>'.$c['display_name'].' ('.$c['user_login'].')</cell>';
	echo '</row>';
} ;
echo "</rows>";
?>