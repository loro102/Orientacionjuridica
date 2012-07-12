<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
chkLogin(1);

	$base = new mysql_database();
	$base->tablename = "user";

	
////////////////////// SAVING SETTING VALUES ///////////////////////
if($_POST['type']=="save_edit"){
	$base->tablename = "settings";
	unset($_POST['button2']);
	unset($_POST['type']);
	$id = $_POST['id'];
	unset($_POST['id']);
	$_POST['SHOW_Y'] = ($_POST['SHOW_Y']<>"on")? "":"on";
	foreach ($_POST as $key => $value) {	
		if($base->countrow("WHERE settings_id='".$key."' AND user_id ='".$id."' ")==0)
		  $sql = "INSERT INTO settings (user_id, settings_id, value) VALUES ('" .$id. "', '".$key."', '".$value."')";
		else
		  $sql = "UPDATE settings SET value='".$value."' WHERE settings_id='".$key."' AND user_id ='".$id."' ";
		
		$base->execute($sql);
	}// ending foreach $_POST
	goAlert("The user have been saved successfully.","/admin/settings_user.php?page=$_GET[page]");
}
	
///////////////////// FOR SHOW DATA ///////////////////////
	$base->use_fields = '*';
	// Seaching the users, if user submitted
	if($_REQUEST['action']=='search_user') $query = userSearchFilter($_REQUEST['key']);
	
	$data=$base->select();
	if (!isset($_GET['totalrows'])) {
		$base->select($query);
		$totalrows = $base->numrow;
	}
	$data=$base->select($query."ORDER BY ID DESC".calLimitPage());
///////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>User calendar</title>

<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="../component/main_script.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="style_admin.css" rel="stylesheet" type="text/css" />

<script language="JavaScript">

</script>

</head>

<body>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">User List</li>
<li class="TabbedPanelsTab" tabindex="0">Search User</li>
<li class="TabbedPanelsTab" tabindex="0">Setting Detail</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
    <? if($_REQUEST['action']=='search_user'){?>
    <div class="SearchPanel">Searching results: "<i><?=$_REQUEST['key']?></i>" <a href="<?=$_SERVER["PHP_SELF"]?>">Reset</a></div><? } // if search panel ?>

<? if($totalrows>0){ ?>
      <table width="100%" border="1" cellpadding="1" cellspacing="1">
        <tr bgcolor="#FFFF99">
          <td width="10%" align="center"><b>User ID</b></td>
          <td width="17%" align="center"><b>Username</b></td>
          <td width="43%" align="center"><b>Setting Values</b></td>
          <td width="15%" align="center"><b>User's Status</b></td>
          <td width="15%" align="center"><b>Action</b></td>
        </tr>
<?  
	$base3 = new mysql_database();
	do{
		
		
		
?>
        <tr>
          <td align="center" bgcolor="#FFFFFF"><?=$data['ID']?>.</td>
          <td bgcolor="#CCFFFF" align="center"><b><?=$data['user_login']?></b>&nbsp;<?=($data['display_name']!='')?'('.$data['display_name'].')':null?></td>
          <td bgcolor="#FFFFFF">
          <? selectUserSettings($data['ID']);
		 foreach ($settings as $k => $v) echo $k.': '.$v.' | ';
		  ?>
          </td>
          <td align="center" bgcolor="#FFFFFF"><?=($data['user_status']==0)?'Inactive':'Active';?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><p><a href="user.php?id=<?=$data['ID']?>&type=edit" title="Edit this profile">Show User Profile</a> <br />
            <a href="?id=<?=$data['ID']?>&user=<?=$data['user_login']?>&type=edit&page=<?=$_GET[page]?>" title="Edit this profile">Change Settings</a> 
            <br />
          </p></td>
        </tr>
        <?
		} while ($data=$base->isNext());
		
		?>
        <? if($totalPages>0){?><tr><td colspan="5"><? showTableSelect();?></td></tr><? } // if?>
      </table>
<? } // if table ends
else echo "No data found";
?>
    </div>
<div class="TabbedPanelsContent">
      <form id="form_search_user" name="form_search_user" method="get" action="" >
        <table width="500" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="3"><center>
              <b>Search user</b>
            </center></td>
          </tr>
          <tr>
            <td width="104"><div align="right">Keyword:</div></td>
            <td width="8">&nbsp;</td>
            <td width="265"><input name="key" type="text" id="key" size="50" value="<?=$_REQUEST['key']?>"/></td>
          </tr>
          <tr>
            <td><div align="right"></div></td>
            <td>&nbsp;</td>
            <td><input type="submit" name="button" id="button" value="Search" />
            <input name="action" type="hidden" id="action" value="search_user" /></td>
          </tr>
        </table>
      </form>
    </div>
<?  if($_REQUEST['type']=='edit'){?>
<div class="TabbedPanelsContent">
<form id="form_settings" name="form_settings" method="post">
  <table width="500" border="0" cellspacing="1" cellpadding="1">
    <? if($err!=""){?>
    <tr>
      <td bgcolor="#FF9999" style="color:#F00"><img src="../common/icons/warning_16.png" />
        <?=$err?></td>
    </tr>
    <? }//if ?>
    
 <?
 	// Settings value
	selectUserSettings($_GET['id']);
 ?>
    <tr>
      <td>User: <b><?=$_GET['user']?></b> (ID: <?=$_GET['id']?>)</td>
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
    <tr></tr>
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
    <tr>
      <td valign="top">First hour on the day/week view:
        <select name="FRIST_HOUR" id="FRIST_HOUR">
          <? for($i=1;$i<=12;$i++){?>
          <option value="<?=$i?>" <? if($settings["FRIST_HOUR"]==$i) echo "selected";?> >
            <?=sprintf("%2d",$i)?>
            :00</option>
          <? }//for loop ?>
        </select>
        to
        <select name="LAST_HOUR" id="LAST_HOUR">
          <? for($i=13;$i<=23;$i++){?>
          <option value="<?=$i?>" <? if($settings["LAST_HOUR"]==$i) echo "selected";?> >
            <?=$i?>
            :00</option>
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
      <td><input type="submit" name="button2" id="button2" value="  Save  " />
        <input name="id" type="hidden" id="id" value="<?=$_GET['id']?>" />
        <input name="type" type="hidden" id="type" value="save_edit" /></td>
    </tr>
  </table>
  </form>
</div>
<? } // if tab ?>
  </div>
</div>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1", {defaultTab:<?=($_REQUEST['type']=='edit' || $_REQUEST['type']=='add')?2:0;?>});
//-->
</script>
</body>
</html>