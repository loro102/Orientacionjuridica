<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");

selectUserSettings(-1);

chkLogin(true,$_SERVER["REQUEST_URI"]);
$base = new mysql_database();
$base->tablename = "calendar"; 

if ($_REQUEST['cmd']=="create") $showAction = "Create";
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
	echo "window.parent.delCalPanel('1','".$_GET['rowIndex']."');";
	echo "window.parent.popup1.close();</script></html>";
	exit;
}
// Send Link to Email
elseif($_REQUEST['cmd']=="sendmail"){
	$cal_id = mysql_real_escape_string($_GET['id']);
	$send_to = mysql_real_escape_string($_GET['sendto']);	
	
	$base->use_fields = "*";
	$data=$base->select("WHERE calendar_id='".$cal_id."' AND user_id = '".$_SESSION["s_user_id"]."'  ");
    $subject = "$settings[SYSTEM_NAME]: calendar sharing address"; 
	$urlpath = BASE_URL.'calendar/share/'.$data['user_id']."/$cal_id";
	
	//begin of HTML message 
    $message = "<html> 
  <body bgcolor=\"#DCEEFC\"> 
    <center><img src='http://sumo.mailgothai.net/calendar/common/image/calendar-logo.gif'><br> 
        <b><u>Sharing Calendar URL</u><br> 
		Calendar Title: '".$data['calendar_name']."'<br> 
		Description: '".$data['calendar_description']."'<br> 
        <font color=\"red\">Other calendar ".$data['user_id']."</font> <br> 
        <img src='http://sumo.mailgothai.net/calendar/common/icons/xml.gif'>: <a href=\"$urlpath.xml\">$urlpath.xml</a><br>
		<img src='http://sumo.mailgothai.net/calendar/common/icons/ical.gif'>: <a href=\"$urlpath.ics\">$urlpath.ics</a><br>
		<img src='http://sumo.mailgothai.net/calendar/common/icons/html.gif'>: <a href=\"$urlpath.html\">$urlpath.html</a><br>
    </center> 
      <br><br><i>Regards,<br>$settings[SYSTEM_NAME]<br> e-mail : ".$settings['SYSTEM_EMAIL']."</i>
  </body> 
</html>"; 
   //end of message 
   
	mailing( $send_to, 'Autosending Calendar [No Reply]', $data['user_id'], $subject, $message );
	 
	/// Set the change name and close popup
	echo "<html><script>alert(\"We have sent access link of calendar to $send_to completely!\");";
	echo "window.parent.popup1.close();</script></html>";
	exit;
}

////////// Submit Form ///////////
/// Preparing the var
if($_POST['cmd']=="create" || $_POST['cmd']=="modify"){
	if($_POST['cal_name']=="" || $_POST['cal_color']=="") $err='Invalid data';
	elseif(strlen($_POST['cal_name'])<4) $err='Calendar\'s title is too short.';
	elseif(strlen($_POST['cal_name'])>150) $err='Calendar\'s title is too long.';
	if($err==""){
		$name = mysql_real_escape_string($_POST['cal_name']);
		$description = mysql_real_escape_string($_POST['cal_desc']);
		$color = $_POST['cal_color'];
		$share = ($_POST['cal_share']=="1")?1:0;
		$adjustable = ($_POST['cal_adjustable']=="1")?1:0;
	}
}

// Add New Calendar
if($_POST['cmd']=="create" && $err==""){
	$sql = 'INSERT INTO calendar (user_id, calendar_name, calendar_description, sharing, adjustable, color)'
		." VALUES ('" .$_SESSION["s_user_id"]. "', '$name', '$description', '$share', '$adjustable', '$color')";
	$base->execute($sql);
	$calid = mysql_insert_id();
	/// Set the refresh item and close popup
	echo "<html><script>alert(\"We have created '$name' into the system.\");";
	echo "window.parent.setCalPanel('1','$calid','$name','$color');";
	echo "window.parent.popup1.close();</script></html>";
	exit;
}
// Edit New Calendar
elseif($_POST['cmd']=="modify" && $err==""){
	$cal_id = mysql_real_escape_string($_GET['id']);
	$sql = "UPDATE calendar SET calendar_name='$name', calendar_description='$description', sharing='$share', adjustable='$adjustable', color='$color' WHERE calendar_id='$cal_id' AND user_id = '".$_SESSION["s_user_id"]."' ";
	$base->execute($sql);
	$isRefresh = ($color<>$_POST['cal_color_old'])?1:0;
	/// Set the change name and close popup
	echo "<html><script>alert(\"We have modified '$name' configuration into the system.\");";
	echo "window.parent.editCalPanel('1','$name','$color','".$_GET['rowIndex']."', $isRefresh, 1);";
	echo "window.parent.popup1.close();</script></html>";
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
		if (window.confirm("Please confirm you want to delete this calendar?")==true){
			window.location.href ="?cmd=delete&del_id="+cal_id+"&rowIndex=<?=$_GET['rowIndex']?>";
		}
	}
	
	function showUrl(title,url){
		chkEnableShared();
		document.getElementById("show_share_url").style.visibility='visible';
		document.getElementById("share_url").value = url;
		document.getElementById("url_title").innerHTML = title;
		document.getElementById("share_url").focus();
		document.getElementById("share_url").select();
	}
	function showSendMail(){
		chkEnableShared();
		document.getElementById("send_mail_link").style.visibility='visible';
		document.getElementById("send_to").focus();
		document.getElementById("send_to").select();
	}
	function hideUrl(){
		document.getElementById("show_share_url").style.visibility='hidden';
	}
	function hideSMail(){
		document.getElementById("send_mail_link").style.visibility='hidden';
	}
	
	function chkEnableShared(){
		if(document.getElementById("cal_share").checked == false){
			alert("Please enable on sharing calendar and click on Edit");
			return false;
		}
	}
	
	function sendMail(){
		var send_to = document.getElementById("send_to").value;
		if(send_to == ""){
			alert("Please type the receiver email");
			return false;
		}
		window.location.href ="?cmd=sendmail&id=<?=($cal_id)?>&sendto="+send_to;
		return true;
	}
	</script>
	</head>

<body <?=($_GET['cmd']=="modify")?"onload=\"setColor('".$data['color']."');\"":NULL;?>>
<form method="post" action="calendar_settings.php?cmd=<?=$_GET['cmd']?><?=($_GET['cmd']=="modify")?"&id=".$cal_id."&rowIndex=".$_GET['rowIndex']:NULL;?>">
<table width="320" border="0" cellspacing="1" cellpadding="1">
  <tr bgcolor="#FFFFCC">
    <td colspan="<?=($err=="")?3:2;?>" class="hl-identifier"><b><?=$showAction?> Details</b></td>
    <? if($err!=""){?><td bgcolor="#FF9999" style="color:#F00"><img src="../common/icons/warning_16.png" width="16" height="16" />      <?=$err?></td><? }//if ?>
  </tr>
  
  <tr bgcolor="#ffffff">
    <td width="31%">Calendar Name</td>
    <td width="1%">:</td>
    <td width="68%"><input name="cal_name" type="text" id="cal_name" size="25" maxlength="150" value="<?=$data['calendar_name']?>" /></td>
  </tr>
  <tr>
    <td valign="top">Description</td>
    <td valign="top">:</td>
    <td><textarea name="cal_desc" id="cal_desc" cols="20" rows="2"><?=$data['calendar_description']?></textarea></td>
  </tr>
  <tr bgcolor="#ffffff">
    <td valign="top"><span id=color_test>&nbsp;Calendar Colour&nbsp;</span>
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
    <td valign="top">Make a public:</td>
    <td colspan="2"><input name="cal_share" type="checkbox" id="cal_share" value="1" <?=($data['sharing']==1)?"checked":NULL;?> onclick="if(this.checked==false) document.getElementById('cal_adjustable').disabled=true; else document.getElementById('cal_adjustable').disabled=false; " />
    <label for="cal_share">Allow everyone can use this calendar link.</label>
    <br />
    <input name="cal_adjustable" type="checkbox" id="cal_adjustable" value="1" <?=($data['adjustable']==1)?"checked":NULL;?> <?=($_REQUEST['cmd']=="create")?"disabled":NULL;?>/>
    <label for="cal_adjustable">Share calendar to everyone can modify whatever events in this calendar.</label>
      <? if ($_GET['cmd']=="modify"){?>
       <div id="show_share_url" style="visibility:hidden;position:absolute;background:#FF9;border:thick;float:left; background-color:#FF6; padding:2px; border:1px solid black">
          Copy <span id='url_title'></span> URL: <input name="share_url" type="text" id="share_url" size="22" onmouseover="this.select();" readonly="readonly" /><a href="javascript:void(0);" onclick="hideUrl();" title="Close"><b>X</b></a>
        </div>
       <div id="send_mail_link" style="visibility:hidden;position:absolute;background:#FF9;border:thick;float:left; background-color:#FF6; padding:2px; border:1px solid black">
          Send calendar link via email:<input name="send_to" type="text" id="send_to" size="22" value="<?=$data['user_id']?>" >
          <input type="button" onclick="sendMail();" name="btsmail" id="btsmail" value="Send" />
          <a href="javascript:void(0);" onclick="hideSMail();"><b>X</b></a></div>
      <table width="100%" border="0" cellspacing="2" cellpadding="1">
        <tr>
          <td width="3%">&nbsp;</td>
          <td width="18%"><a href="javascript:void(0);" onclick="showUrl('XML','<?=BASE_URL?>/share/<?=$data['user_id']?>/<?=($cal_id)?>.xml');"><img src="../common/icons/xml.gif" width="36" height="14" border="0" /></a></td>
          <td width="18%"><a href="javascript:void(0);" onclick="showUrl('ICAL','<?=BASE_URL?>/share/<?=$data['user_id']?>/<?=($cal_id)?>.ics');"><img src="../common/icons/ical.gif" width="36" height="14" border="0" /></a></td>
          <td width="19%"><a href="javascript:void(0);" onclick="showUrl('HTML','<?=BASE_URL?>/share/<?=$data['user_id']?>/<?=($cal_id)?>.html');"><img src="../common/icons/html.gif" width="36" height="14" border="0" /></a></td>
          <td width="42%"><a href="javascript:void(0);" onclick="showSendMail();"><img src="../common/icons/email_link.png" border="0" title='Send calendar link via email' /></a></td>
        </tr>
      </table><? }//if?></td>
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