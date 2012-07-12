<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
chkLogin(true,$_SERVER["REQUEST_URI"]);
$base = new mysql_database();
$base->tablename = "settings"; 

////////// Submit Form ///////////
/// Preparing the var
if($_POST['cmd']=="save"){
	if($_POST['user_pass']!='' && $_POST['user_pass2']!=$_POST['user_pass']) 
		$err = "The password you entered does not match the password confirmation.";
	else  if (strlen($_POST['user_email']) == 0 ||  !preg_match('/^(?:[\w\d]+\.?)+@(?:(?:[\w\d]\-?)+\.)+\w{2,4}$/i', $_POST['user_email']))
		$err = "Email address entered is invalid.";
	else  if (strlen($_POST['display_name']) < 6)
		$err = "Disply name entered is invalid.";
	else{
		unset($_POST['button']);
		unset($_POST['cmd']);
		$_POST['SHOW_Y'] = ($_POST['SHOW_Y']<>"on")? "":"on";
		foreach ($_POST as $key => $value) {	
			if($base->countrow("WHERE settings_id='".$key."' AND user_id ='".$_SESSION["s_user_id"]."' ")==0)
			  $sql = "INSERT INTO settings (user_id, settings_id, value) VALUES ('" .$_SESSION["s_user_id"]. "', '".$key."', '".$value."')";
			else
			  $sql = "UPDATE settings SET value='".$value."' WHERE settings_id='".$key."' AND user_id ='".$_SESSION["s_user_id"]."' ";
			
			$base->execute($sql);
		}// for each $_POST
		
		if($_POST['user_pass'] != '')  $password = 'user_pass=\''.md5($_POST['user_pass']).'\', ';
		$base->tablename = "user";
		$base->update('WHERE ID="'.$_SESSION["s_user_id"].'"', 
			sprintf("display_name='%s', $password user_email='%s', user_url='%s' ",
				$_POST['display_name'],
				$_POST['user_email'],
				$_POST['user_url']));
		
		  echo "<html><script>alert(\"We have saved your calendar settings successfully.\");";
		  echo "window.parent.location.reload();";
		  echo "window.parent.popup1.close();</script></html>";
		  exit;
	}// if
}

// Select User data
$base->tablename = "user";
$user = $base->select('WHERE ID="'.$_SESSION["s_user_id"].'"');
	
// Settings value
selectUserSettings($_SESSION["s_user_id"]);
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
	
	</script>
	</head>

<body <?=($_GET['cmd']=="modify")?"onload=\"setColor('".$data['color']."');\"":NULL;?>>
<form method="post" action="system_settings.php">
<table width="400" border="0" cellspacing="2" cellpadding="2">
  <? if($err!=""){?><tr><td bgcolor="#FF9999" style="color:#F00"><img src="../common/icons/warning_16.png" /><?=$err?></td></tr><? }//if ?>
  
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="hl-brackets">
        <tr>
          <td colspan="3" class="hl-special"><b>Profile Settings</b></td>
          </tr>
        <tr>
          <td width="30%">Display Name</td>
          <td width="4%">:</td>
          <td width="66%"><input type="text" name="display_name" id="display_name"  value="<?=$user["display_name"]?>" /></td>
          </tr>
        <tr>
          <td>My Email</td>
          <td>:</td>
          <td><input name="user_email" type="text" id="user_email" size="30"  value="<?=$user["user_email"]?>" /></td>
          </tr>
        <tr>
          <td>My URL</td>
          <td>:</td>
          <td><input name="user_url" type="text" id="user_url" size="30"  value="<?=$user["user_url"]?>" /></td>
        </tr>
        <tr>
          <td>Password</td>
          <td>:</td>
          <td><input name="user_pass" type="password" id="user_pass" value="" size="25" /></td>
          </tr>
        <tr>
          <td>Confirm password</td>
          <td>:</td>
          <td><input name="user_pass2" type="password" id="user_pass2" value="" size="25" /></td>
          </tr>
        <tr>
          <td colspan="3" style="font-size:80%; color:red;"><i>Please leave these boxes empty, if you don not want to change your password.</i></td>
          </tr>
      </table></td>
  </tr>
  <tr>
    <td><b class="hl-special">View Settings</b></td>
  </tr>
  <tr>
    <td>Default view: 
      <select name="DEF_VIEW" id="DEF_VIEW">
        <option value="day"<? if($settings["DEF_VIEW"]=="day") echo " selected";?>>Day</option>
        <option value="week"<? if($settings["DEF_VIEW"]=="week") echo " selected";?>>Week</option>
        <option value="month"<? if($settings["DEF_VIEW"]=="month") echo " selected";?>>Month</option>
        <option value="year"<? if($settings["DEF_VIEW"]=="year") echo " selected";?>>Year</option>
      </select></td>
  </tr>
  <tr>
    <td width="31%">Week starts on: 
      <input type="radio" name="START_ON" id="radio" value="false"<? if($settings["START_ON"]=="false") echo " checked";?>/>
      Sunday
  <input type="radio" name="START_ON" id="radio" value="true"<? if($settings["START_ON"]=="true") echo " checked";?>/>
      Monday</td>
    </tr>
  <tr>
  <tr>
    <td valign="top">Frequency step of time scale:
      <select name="TIME_STEP" id="TIME_STEP">
        <option value="1"<? if($settings["TIME_STEP"]=="1") echo " selected";?>>1</option>
        <option value="2"<? if($settings["TIME_STEP"]=="2") echo " selected";?>>2</option>
        <option value="5"<? if($settings["TIME_STEP"]=="5") echo " selected";?>>5</option>
        <option value="10"<? if($settings["TIME_STEP"]=="10") echo " selected";?>>10</option>
        <option value="15"<? if($settings["TIME_STEP"]=="15") echo " selected";?>>15</option>
        <option value="20"<? if($settings["TIME_STEP"]=="20") echo " selected";?>>20</option>
      </select>
minutes</td>
  </tr>
      <td valign="top">First hour on the day/week view: 
          <select name="FRIST_HOUR" id="FRIST_HOUR">
       <? for($i=1;$i<=12;$i++){?>
        <option value="<?=$i?>" <? if($settings["FRIST_HOUR"]==$i) echo "selected";?> ><?=sprintf("%2d",$i)?>:00</option>
       <? }//for loop ?>
      </select> 
      to <select name="LAST_HOUR" id="LAST_HOUR">
      	<? for($i=13;$i<=23;$i++){?>
        <option value="<?=$i?>" <? if($settings["LAST_HOUR"]==$i) echo "selected";?> ><?=$i?>:00</option>
       <? }//for loop ?>
      </select></td>
    </tr>
  <tr>
    <td valign="top">Frequency step of time scale on the day/week view: 
      <select name="STEP_WD" id="STEP_WD">
        <option value="5"<? if($settings["STEP_WD"]=="5") echo " selected";?>>5</option>
        <option value="10"<? if($settings["STEP_WD"]=="10") echo " selected";?>>10</option>
        <option value="15"<? if($settings["STEP_WD"]=="15") echo " selected";?>>15</option>
        <option value="20"<? if($settings["STEP_WD"]=="20") echo " selected";?>>20</option>
      </select> 
      min.</td>
    </tr>
  <tr>
    <td>Year view
      <input name="SHOW_Y" type="checkbox" id="SHOW_Y" value="on"<? if($settings["SHOW_Y"]=="on") echo " checked";?>/>
      : number of month on row 
      <select name="Y_YSCALE" id="Y_YSCALE">
        <option value="1"<? if($settings["Y_YSCALE"]=="1") echo " selected";?>>1</option>
        <option value="2"<? if($settings["Y_YSCALE"]=="2") echo " selected";?>>2</option>
        <option value="3"<? if($settings["Y_YSCALE"]=="3") echo " selected";?>>3</option>
        <option value="4"<? if($settings["Y_YSCALE"]=="4") echo " selected";?>>4</option>
        </select> 
      column
  <select name="X_YSCALE" id="X_YSCALE">
    <option value="1"<? if($settings["X_YSCALE"]=="1") echo " selected";?>>1</option>
    <option value="2"<? if($settings["X_YSCALE"]=="2") echo " selected";?>>2</option>
    <option value="3"<? if($settings["X_YSCALE"]=="3") echo " selected";?>>3</option>
    <option value="4"<? if($settings["X_YSCALE"]=="4") echo " selected";?>>4</option>
    </select></td>
  </tr>
  <tr>
    <td><input type="submit" name="button" id="button" value="  Save  " />
      <input name="cmd" type="hidden" id="cmd" value="save" />
      <input type="button" name="button2" id="button2" value=" Close " onclick="window.parent.popup1.close();"/></td>
  </tr>
</table>
<p class="hl-brackets">&nbsp;</p>
</form>
</body>
</html>