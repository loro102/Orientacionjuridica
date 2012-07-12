<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
chkLogin();

	$base = new mysql_database();
	$base->tablename = "settings";


////////// Submit Form ///////////
if($_POST['type']=="save_system"){
	unset($_POST['button']);
	unset($_POST['type']);
	$_POST['SIGN_UP'] = ($_POST['SIGN_UP']<>"on")? "":"on";
	foreach ($_POST as $key => $value) {	
		if($base->countrow("WHERE settings_id='".$key."' AND user_id ='-1' ")==0)
		  $sql = "INSERT INTO settings (user_id, settings_id, value) VALUES ('-1', '".$key."', '".$value."')";
		else
		  $sql = "UPDATE settings SET value='".$value."' WHERE settings_id='".$key."' AND user_id ='-1' ";
		
		$base->execute($sql);
	}
	goAlert("The settings have been saved successfully.","/admin/main.php");
}
	
///////////////////// GET DATA ///////////////////////
	selectUserSettings('-1');
///////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>User calendar</title>
<script src="../component/main_script.js" type="text/javascript"></script>
<link href="style_admin.css" rel="stylesheet" type="text/css" />

<script language="JavaScript">

</script>

</head>

<body>
<form id="form_settings" name="form_settings" method="post">
  <span class="big">System Settings</span>
<table width="500" border="0" cellspacing="1" cellpadding="1">
    <? if($err!=""){?>
    <tr>
      <td colspan="3" bgcolor="#FF9999" style="color:#F00"><img src="../common/icons/warning_16.png" />
      <?=$err?></td>
    </tr>
    <? }//if ?>
    

    <tr>
      <td>Calendar System Name</td>
      <td>:</td>
      <td><input name="SYSTEM_NAME" type="text" id="SYSTEM_NAME" value="<?=$settings["SYSTEM_NAME"]?>" /></td>
    </tr>
    <tr>
      <td>Support Email</td>
      <td>:</td>
      <td><input name="SYSTEM_EMAIL" type="text" id="SYSTEM_EMAIL" value="<?=$settings["SYSTEM_EMAIL"]?>" size="40" /></td>
    </tr>
    <tr>
      <td width="33%">Language</td>
      <td width="3%">:</td>
      <td width="64%"><input type="radio" name="SYSTEM_LANG" id="radio" value="EN"<? if($settings["SYSTEM_LANG"]=="EN") echo " checked";?>/>
        English        </td>
    </tr>
    <tr>
      <td valign="top">Allow user to sign up</td>
      <td valign="top">:</td>
      <td valign="top"><input name="SIGN_UP" type="checkbox" id="SIGN_UP" value="on" <? if($settings["SIGN_UP"]=="on") echo " checked";?>/></td>
    </tr>
    <tr>
      <td valign="top">Number of Annouments</td>
      <td valign="top">:</td>
      <td valign="top"><select name="NO_ANNOUNCE" id="NO_ANNOUNCE">
        <option value="1"<? if($settings["NO_ANNOUNCE"]==1) echo " selected";?>>1</option>
        <option value="2"<? if($settings["NO_ANNOUNCE"]==2) echo " selected";?>>2</option>
        <option value="3"<? if($settings["NO_ANNOUNCE"]==3) echo " selected";?>>3</option>
        <option value="4"<? if($settings["NO_ANNOUNCE"]==4) echo " selected";?>>4</option>
        <option value="5"<? if($settings["NO_ANNOUNCE"]==5) echo " selected";?>>5</option>
      </select></td>
    </tr>
    <tr>
      <td valign="top">System Version</td>
      <td valign="top">:</td>
      <td valign="top"><input name="SYSTEM_VER" type="text" id="SYSTEM_VER" value="Beta 1.0" size="10" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><input type="submit" name="button" id="button" value="  Save  " />
      <input name="type" type="hidden" id="type" value="save_system" /></td>
    </tr>
  </table>
  </form>
</body>
</html>