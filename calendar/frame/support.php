<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
chkLogin(true,$_SERVER['REQUEST_URI']);
$base = new mysql_database();
$base->tablename = "support"; 

selectUserSettings(-1);

////////// Submit Form ///////////
/// Preparing the var
if($_POST['cmd']=="submit"){
	if($_POST['title']=="" || $_POST['detail']=="") $err='กรอกข้อมูลไม่ครบ';
	elseif(strlen($_POST['title'])<4) $err='หัวข้อสั้นเกินไป';
	if($err==""){
		$title = mysql_real_escape_string($_POST['title']);
		$detail = mysql_real_escape_string($_POST['detail']);
		$IP = getRealIpAddr();
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

	$sql = "INSERT INTO support (notice, user_id, detail, date, IP, user_agent) VALUES ('".$title."','" .$_SESSION["s_user_id"]. "', '".$detail."', NOW(),'".$IP."', '".$user_agent."')";
	$base->execute($sql);
	$support_id = mysql_insert_id();
	echo "<html><script>alert(\"We have received your feedback and we will check it out soon.\");";
	echo "window.parent.popup1.close();</script></html>";
	exit;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <link rel="stylesheet" href="../common/css/style.css" type="text/css" media="screen" />
	  <style>
	  html, body {
		  width: 100%;
		  height: 100%;
		  margin: 1px;
		  padding: 0px;
		  overflow: hidden;
	  }
      </style>

	</head>

<body>
<form method="post">
<table width="320" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td colspan="<?=($err=="")?3:2;?>" class="hl-identifier">Send us your feedback</td>
    <? if($err!=""){?><td bgcolor="#FF9999" style="color:#F00"><img src="../common/icons/warning_16.png" width="16" height="16" />      <?=$err?></td><? }//if ?>
  </tr>
  
  <tr>
    <td width="29%">Incident title</td>
    <td width="3%">:</td>
    <td width="68%"><input name="title" type="text" id="title" size="25" maxlength="150" /></td>
  </tr>
  <tr>
    <td valign="top">Description</td>
    <td valign="top">:</td>
    <td><textarea name="detail" id="detail" cols="20" rows="5"></textarea></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><input type="submit" name="button" id="button" value="Send" />
      <input type="button" name="button2" id="button2" value="Cancel" onclick="window.parent.popup1.close();"/>
      <input name="cmd" type="hidden" id="cmd" value="submit" /></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><font color="#FF3300">e-mail : </font>
            <?=$settings['SYSTEM_EMAIL']?></td>
    </tr>
</table>
<p class="hl-brackets">&nbsp;</p>
</form>
</body>
</html>