<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
chkLogin(true,$_SERVER["REQUEST_URI"]);
$base = new mysql_database();
$base->tablename = "calendar_sharing"; 

if ($_REQUEST['cmd']=="add") $showAction = "Add";
elseif ($_REQUEST['cmd']=="modify"){
	$showAction = "Modify";	
	$base->use_fields = "*";
	$cal_id = mysql_real_escape_string($_GET['id']);
	$data=$base->select("WHERE calendar_id='".$cal_id."' ");
}
elseif ($_REQUEST['cmd']=="delete"){ 
	$cal_id = mysql_real_escape_string($_GET['del_id']);
	$data=$base->deletes("WHERE calendar_id='".$cal_id."' AND user_id = '".$_SESSION["s_user_id"]."' ");
	echo "<html><script>alert(\"The calendar has been deleted.\");";
	echo "window.parent.delCalPanel('2','".$_GET['rowIndex']."');";
	echo "window.parent.popup1.close();</script></html>";
	exit;
}

////////// Submit Form ///////////
/// Preparing the var
if($_POST['cmd']=="add" || $_POST['cmd']=="modify"){
	if($_POST['title']=="" || $_POST['url']=="" || $_POST['cal_color']=="") $err='Invalid data';
	elseif(strlen($_POST['title'])<4) $err='Calendar\'s title is too short.';
	elseif(strlen($_POST['cal_name'])>150) $err='Calendar\'s title is too long.';
	if($err==""){
		$title = mysql_real_escape_string($_POST['title']);
		$url = mysql_real_escape_string($_POST['url']);
		$color = $_POST['cal_color'];
		$show = ($_POST['show']=="1")?1:0;
	}else{
		if ($_REQUEST['cmd']=="add") $showAction = "Add";
		elseif ($_REQUEST['cmd']=="modify") $showAction = "Modify";	
	}
}

// Add New Calendar
if($_POST['cmd']=="add" && $err==""){
	$sql = "INSERT INTO calendar_sharing (user_id, title, url, `show`, color) VALUES ('" .$_SESSION["s_user_id"]. "', '".$title."', '".$url."', '".$show."', '".$color."')";
	$base->execute($sql);
	$calid = mysql_insert_id();
	/// Set the refresh item and close popup
	echo "<html><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8;\"><script>alert(\"We have added '".$title."' into the system. \n Please note that if the data has not shown meaning it is loading...\");";
	echo "window.parent.setCalPanel('2','".$calid."','".$title."','".$color."','ext');";
	echo "window.parent.popup1.close();</script>Please note that if the data has not shown meaning it is loading...</html>";
	exit;
}
// Edit New Calendar
elseif($_POST['cmd']=="modify" && $err==""){
	if($_POST['show_old']<>$show || $color<>$_POST['cal_color_old']){
		$file = CACHE_PATH . md5("/calendar/share/ical_select.php?uid=".$_SESSION["s_user_id"]."&timeshift=-420");
		if(file_exists($file)) @unlink($file);
	}

	$cal_id = mysql_real_escape_string($_GET['id']);
	$sql = "UPDATE calendar_sharing SET title='".$title."', url='".$url."', `show`='".$show."', color='".$color."' WHERE calendar_id='".$cal_id."'";
	$base->execute($sql);
	$isRefresh = ($color<>$_POST['cal_color_old'])?1:0;
	/// Set the change name and close popup
	echo "<html><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8;\"><script>alert(\"We have saved '".$title."' into the system\");";
	echo "window.parent.editCalPanel('2','".$title."','".$color."','".$_GET['rowIndex']."', $isRefresh, $show);";
	echo "window.parent.popup1.close();</script>Please wait a second...</html>";
	exit;
}

//$id_encrypt = encrypt($cal_id);
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
	<script language="javascript">
	function setColor(number){
		document.getElementById('cal_color').value = number;
		document.getElementById('color_test').style.backgroundColor = "#"+number;
		document.getElementById('color_test').style.color = "WHITE"
	}
	
	function delCal(cal_id){
		if (window.confirm("Please confirm you want to unlink this calendar?")==true){
			window.location.href ="?cmd=delete&del_id="+cal_id+"&rowIndex=<?=$_GET['rowIndex']?>";
		}
	}
	
	function showUrl(title,url){
		document.getElementById("show_share_url").style.visibility='visible';
		document.getElementById("share_url").value = url;
		document.getElementById("url_title").innerHTML = title;
		document.getElementById("share_url").focus();
		document.getElementById("share_url").select();
	}
	function hideUrl(){
		document.getElementById("show_share_url").style.visibility='hidden';
	}
	</script>
	</head>

<body <?=($_GET['cmd']=="modify")?"onload=\"setColor('".$data['color']."');\"":NULL;?>>
<form method="post" action="shared_settings.php?cmd=<?=$_GET['cmd']?><?=($_GET['cmd']=="modify")?"&id=".$cal_id."&rowIndex=".$_GET['rowIndex']:NULL;?>">
<table width="320" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td colspan="<?=($err=="")?3:2;?>" class="hl-identifier"><?=$showAction?> Public Sharing Calendar   </td>
    <? if($err!=""){?><td bgcolor="#FF9999" style="color:#F00"><img src="../common/icons/warning_16.png" width="16" height="16" />      <?=$err?></td><? }//if ?>
  </tr>
  
  <tr>
    <td width="26%">Calendar Name</td>
    <td width="6%">:</td>
    <td width="68%"><input name="title" type="text" id="title" size="25" maxlength="150" value="<?=$data['title']?>" /></td>
  </tr>
  <tr>
    <td valign="top">Address (URL)</td>
    <td valign="top">:</td>
    <td><input name="url" type="text" id="url" size="25" maxlength="150" value="<?=($_GET['cmd']=="add")?"":$_POST[url];?><?=$data['url']?>" />
      <br />
      File format <img src="../common/icons/ical.gif" width="36" height="14" /> <a href="http://en.wikipedia.org/wiki/ICalendar" target="_blank"><img src="../common/icons/help.png" width="16" height="16" border="0" /></a></td>
  </tr>
  <tr>
    <td valign="top"><span id=color_test>Calendar Colour&nbsp;</span>
      <input name="cal_color" type="hidden" id="cal_color" size="6" maxlength="6" value="<?=$data['color']?>" />
      <input name="cal_color_old" type="hidden" id="cal_color_old" size="6" maxlength="6" value="<?=$data['color']?>" /></td>
    <td valign="top">:</td>
    <td><img src="../common/image/calendar_color.jpg" width="129" height="55" border="0" usemap="#Map_Color" />
      <map name="Map_Color" id="Map_Color">
        <area shape="rect" coords="1,1,15,14" href="javascript:void(0);" alt="cc3333" onclick="setColor('cc3333');" />
        <area shape="rect" coords="20,1,33,14" href="javascript:void(0);" alt="dd4477" onclick="setColor('dd4477');"/>
        <area shape="rect" coords="39,1,52,14" href="javascript:void(0);" alt="994499" onclick="setColor('994499');"/>
        <area shape="rect" coords="58,1,71,14" href="javascript:void(0);" alt="6633cc" onclick="setColor('6633cc');"/>
        <area shape="rect" coords="77,1,91,14" href="javascript:void(0);" alt="336699" onclick="setColor('336699');"/>
        <area shape="rect" coords="96,1,109,14" href="javascript:void(0);" alt="3366cc" onclick="setColor('3366cc');"/>
        <area shape="rect" coords="115,1,128,14" href="javascript:void(0);" alt="22aa99" onclick="setColor('22aa99');"/>
        
        <area shape="rect" coords="0,21,14,34" href="javascript:void(0);" alt="329262" onclick="setColor('329262');"/>
        <area shape="rect" coords="20,21,33,34" href="javascript:void(0);" alt="109618" onclick="setColor('109618');"/>
        <area shape="rect" coords="39,21,52,34" href="javascript:void(0);" alt="66aa00" onclick="setColor('66aa00');"/>
        <area shape="rect" coords="58,21,71,34" href="javascript:void(0);" alt="aaaa11" onclick="setColor('aaaa11');"/>
        <area shape="rect" coords="77,21,90,34" href="javascript:void(0);" alt="d6ae00" onclick="setColor('d6ae00');"/>
        <area shape="rect" coords="96,21,109,34" href="javascript:void(0);" alt="ee8800" onclick="setColor('ee8800');"/>
        <area shape="rect" coords="115,21,128,34" href="javascript:void(0);" alt="dd5511" onclick="setColor('dd5511');"/>
        
        <area shape="rect" coords="1,41,14,54" href="javascript:void(0);" alt="a87070" onclick="setColor('a87070');"/>
        <area shape="rect" coords="20,41,33,54" href="javascript:void(0);" alt="8c6d8c" onclick="setColor('8c6d8c');"/>
        <area shape="rect" coords="39,41,52,54" href="javascript:void(0);" alt="627487" onclick="setColor('627487');"/>
        <area shape="rect" coords="58,41,71,54" href="javascript:void(0);" alt="7083a8" onclick="setColor('7083a8');"/>
        <area shape="rect" coords="77,41,91,54" href="javascript:void(0);" alt="5c8d87" onclick="setColor('5c8d87');"/>
        <area shape="rect" coords="96,41,109,54" href="javascript:void(0);" alt="898951" onclick="setColor('898951');"/>
        <area shape="rect" coords="115,41,128,54" href="javascript:void(0);" alt="b08b59" onclick="setColor('b08b59');"/>
      </map></td>
  </tr>
  <tr>
    <td>Status:</td>
    <td colspan="2"><input name="show" type="checkbox" id="show" value="1" <?=($data['show']==1)?"checked":NULL;?><?=($_GET['cmd']=="add")?"checked":NULL;?>/>
      <input name="show_old" type="hidden" id="show_old" value="<?=$data['show']?>" />
<label for="show">display on the scheduler</label></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><input type="submit" name="button" id="button" value="  Save  " />
      <input name="cmd" type="hidden" id="cmd" value="<?=$_GET['cmd']?>" />
      <? if ($_GET['cmd']=="modify"){?><input type="button" name="button3" id="button3" value="  Remove  " onclick="delCal(<?=$cal_id?>);"/><? }//if?>
<input type="button" name="button2" id="button2" value="Cancel" onclick="window.parent.popup1.close();"/></td>
  </tr>
</table>
<p class="hl-brackets">&nbsp;</p>
</form>
</body>
</html>