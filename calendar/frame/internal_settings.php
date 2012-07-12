<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
chkLogin(true,$_SERVER["REQUEST_URI"]);
$base = new mysql_database();
$base->tablename = "calendar_sharing"; 
$cal_id = mysql_real_escape_string($_REQUEST['cid']);

if ($_REQUEST['cmd']=="delete"){ 
	if($base->countrow("WHERE calendar_id_shared='".$cal_id."' AND user_id ='".$_SESSION["s_user_id"]."' ")>0){
		$base->deletes("WHERE calendar_id_shared='".$cal_id."' AND user_id = '".$_SESSION["s_user_id"]."' ");
		echo "<html><script>alert(\"The calendar have been unlinked with the system.\");";
		echo "window.parent.delCalPanel('2','".$_GET['rowIndex']."');";
		echo "window.parent.popup1.close();</script></html>";
	} else echo "<html><script>window.parent.popup1.close();</script></html>";
	exit;
}

////////// Submit Form ///////////
/// Preparing the var
if($_POST['cmd']=="save"){
	if($_POST['title']=="" || $_POST['cal_color']=="") $err='Invalid data';
	elseif(strlen($_POST['title'])<4) $err='Calendar\'s title is too short.';
	elseif(strlen($_POST['title'])>150) $err='Calendar\'s title is too long.';
	if($err==""){
		$title = mysql_real_escape_string($_POST['title']);
		$url = mysql_real_escape_string($_POST['url']);
		$color = $_POST['cal_color'];
		$show = ($_POST['show']=="1")?1:0;
	}

//echo $base->countrow("WHERE calendar_id_shared='".$cal_id."' AND user_id ='".$_SESSION["s_user_id"]."' ")>0; exit;
	if($err=="" && $base->countrow("WHERE calendar_id_shared='".$cal_id."' AND user_id ='".$_SESSION["s_user_id"]."' ")==0){
		$sql = "INSERT INTO calendar_sharing (user_id, title, url, `show`, color, calendar_id_shared) VALUES ('" .$_SESSION["s_user_id"]. "', '".$title."', '".$url."', '".$show."', '".$color."', '".$cal_id."')";
		$base->execute($sql);
		/// Set the refresh item and close popup
		echo "<html><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8;\"><script>alert(\" We have added '".$title."' calendar completely\");";
		echo "window.parent.setCalPanel('2','".$cal_id."','".$title."','".$color."','int');";
		echo "window.parent.popup1.close();</script></html>";
		exit;
	}else{
		$sql = "UPDATE calendar_sharing SET title='".$title."', url='".$url."', `show`='".$show."', color='".$color."' WHERE calendar_id_shared='".$cal_id."' AND user_id ='".$_SESSION["s_user_id"]."' ";
		$base->execute($sql);
		$isRefresh = ($color<>$_POST['cal_color_old'])?1:0;
		/// Set the change name and close popup
		echo "<html><meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8;\"><script>alert(\" We have saved '".$title."' calendar into the system.\");";
		echo "window.parent.editCalPanel('2','".$title."','".$color."','".$_GET['rowIndex']."', $isRefresh, $show);";
		echo "window.parent.popup1.close();</script></html>";
		exit;
	}
}

// Calendar Details
$base->tablename = "calendar"; 
$base->use_fields = "*";
$calendar=$base->select("WHERE calendar_id='".$cal_id."' ");

// Calendar Details
$base->tablename = "calendar_sharing";
$calendar_save=$base->select("WHERE calendar_id_shared='".$cal_id."' AND user_id ='".$_SESSION["s_user_id"]."' ");

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
		if (window.confirm("Please confirm you want to unlink the calendar?")==true){
			window.location.href ="?cmd=delete&cid="+cal_id+"&rowIndex=<?=$_GET['rowIndex']?>";
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

<body onload="setColor('<?=($calendar_save['color']=="")?$calendar['color']:$calendar_save['color'];?>')";>
<form method="post" action="internal_settings.php?cmd=<?=$_GET['cmd']?>&rowIndex=<?=$_GET['rowIndex']?>">
<table width="280" border="0" cellspacing="1" cellpadding="1">
  <? if($err!=""){?><tr>
    <td colspan="3" bgcolor="#FF9999" style="color:#F00"><img src="../common/icons/warning_16.png" width="16" height="16" /><?=$err?></td>
  </tr><? }//if ?>
  
  <tr>
    <td valign="top">Calendar Name</td>
    <td valign="top">:</td>
    <td><b>
      <?=$calendar['calendar_name']?>
    </b></td>
  </tr>
  <tr>
    <td valign="top">Owner</td>
    <td valign="top">:</td>
    <td><b>
      <?
		$base->tablename = "user";
		$ower=$base->select("WHERE ID='".$calendar['user_id']."' ");
		echo "<font color='blue'>".$ower['display_name']."</font> [<font color='red'>".$ower['user_login']."</font>]";?>
      <input type="hidden" name="url" id="url" value="<?=$calendar['user_id']?>" />
    </b></td>
  </tr>
  <tr>
    <td width="35%">Display Name</td>
    <td width="4%">:</td>
    <td width="61%"><input name="title" type="text" id="title" size="20" maxlength="150" value="<?=($calendar_save['title']=="")?$calendar['calendar_name']:$calendar_save['title'];?>" /></td>
  </tr>
  <tr>
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
    <td>Status:</td>
    <td colspan="2"><input name="show" type="checkbox" id="show" value="1" <?=($calendar_save['show']==1)?"checked":NULL;?><?=($calendar_save['show']=="")?"checked":NULL;?>/>
    <label for="show">Make a share calendar</label>
    only inside the system</td>
  </tr>
  <tr>
  	<td>Calendar Link:</td>
    <td colspan="2">
    <div id="show_share_url" style="visibility:hidden;position:absolute;background:#FF9;border:thick;float:left; background-color:#FF6; padding:2px; border:1px solid black">
          คัดลอก <span id='url_title'></span> URL: <input name="share_url" type="text" id="share_url" size="22" onmouseover="this.select();" readonly="readonly" /> 
            <a href="javascript:void(0);" onclick="hideUrl();"><b>X</b></a></div>
    <table width="100%" border="0" cellspacing="2" cellpadding="1">
        <tr align="center">
          <td align="center"><a href="javascript:void(0);" onclick="showUrl('XML','<?=BASE_URL?>calendar/share/<?=$calendar['user_id']?>/<?=($cal_id)?>.xml');"><img src="../common/icons/xml.gif" width="36" height="14" border="0" /></a></td>
          <td><a href="javascript:void(0);" onclick="showUrl('ICAL','<?=BASE_URL?>calendar/share/<?=$calendar['user_id']?>/<?=($cal_id)?>.ics');"><img src="../common/icons/ical.gif" width="36" height="14" border="0" /></a></td>
          <td><a href="javascript:void(0);" onclick="showUrl('HTML','<?=BASE_URL?>calendar/share/<?=$calendar['user_id']?>/<?=($cal_id)?>.html');"><img src="../common/icons/html.gif" width="36" height="14" border="0" /></a></td>
        </tr>
      </table>
    </td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><input type="submit" name="button" id="button" value=" Save " />
    <? if($_GET['cmd']=='add'){?>
      <input type="button" name="button2" id="button2" value="Unlink" onclick="delCal(<?=$cal_id?>); "/>
     <? }else{?>
      <input type="button" name="button2" id="button2" value="Cancel" onclick="window.parent.popup1.close();"/><? } // if ?>
      <input name="cmd" type="hidden" id="cmd" value="save" />
      <input name="cid" type="hidden" id="cid" value="<?=$cal_id?>" /></td>
  </tr>
</table>
</form>
</body>
</html>