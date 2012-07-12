<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
include('../common/css/config_color.php');
chkLogin(1);

	$base = new mysql_database();
	$base->tablename = "user";
	$base2 = new mysql_database();

////////////////////// SAVE EDITED CALEDAR ///////////////////////
if($_POST['type']=='save_edit_calendar'){
	if(strlen($_POST['cal_name'])<4) $err='Calendar\'s title is too short.';
	elseif(strlen($_POST['cal_name'])>150) $err='Calendar\'s title is too long.';
	if($err==""){
		$name = mysql_real_escape_string($_POST['cal_name']);
		$description = mysql_real_escape_string($_POST['cal_desc']);
		$color = $_POST['color'];
		$share = ($_POST['cal_share']=="1")?1:0;
		$adjustable = ($_POST['cal_adjustable']=="1")?1:0;
		$cal_id = mysql_real_escape_string($_GET['calendar_id']);
		$sql = "UPDATE calendar SET calendar_name='$name', calendar_description='$description', sharing='$share', adjustable='$adjustable', color='$color' WHERE calendar_id='$cal_id' ";
		$base->execute($sql);
		goAlert("The calendar have been saved successfully.","/admin/calendar.php?page=$_GET[page]");
	}

////////////////////// SAVE EDITED SHARING CALEDAR /////////////////////
}else if($_POST['type']=='save_edit_calendar_sharing'){
	if(strlen($_POST['title'])<4) $err='Calendar\'s title is too short.';
	elseif(strlen($_POST['title'])>150) $err='Calendar\'s title is too long.';
	if($err==""){
		$title = mysql_real_escape_string($_POST['title']);
		$url = mysql_real_escape_string($_POST['url']);
		$color = $_POST['color'];
		$show = ($_POST['show']=="1")?1:0;
		$cal_id = mysql_real_escape_string($_GET['calendar_id']);
		$sql = "UPDATE calendar_sharing SET title='$title', url='$url', `show`='$show', color='$color' WHERE calendar_id='$cal_id' ";
		$base->execute($sql);
		goAlert("The calendar have been saved successfully.","/admin/calendar.php?page=$_GET[page]");
	}

////////////////////// DELETE DATA /////////////////////
}else if($_GET['type']=="del_edit_calendar"){ 
	$base->tablename = "calendar";
	$result = $base->deletes("WHERE calendar_id='$_REQUEST[calendar_id]'");
	$base->tablename = "events";
	if($result) $base->deletes("WHERE calendar_id='$_REQUEST[calendar_id]'");
	goAlert("The calendar was removed successfully!","/admin/calendar.php?page=$_GET[page]");
}else if($_GET['type']=="del_edit_calendar_sharing"){ 
	$base->tablename = "calendar_sharing";
	$base->deletes("WHERE calendar_id='$_REQUEST[calendar_id]'");
	goAlert("The calendar was removed successfully!","/admin/calendar.php?page=$_GET[page]");
}

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
    <li class="TabbedPanelsTab" tabindex="1">Calendar List</li>
    <li class="TabbedPanelsTab" tabindex="2">Search User</li>
	<li class="TabbedPanelsTab" tabindex="3">Search Calendar</li>
	<li class="TabbedPanelsTab" tabindex="4">Calendar Detail</li>
  </ul>
  
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
<?
///////////////////// USER LIST ///////////////////////
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

    <? if($_REQUEST['action']=='search_user'){?>
    <div class="SearchPanel">Searching results: "<i><?=$_REQUEST['key']?></i>" <a href="<?=$_SERVER["PHP_SELF"]?>">Reset</a></div><? } // if search panel ?>

<? if($totalrows>0){ ?>
      <table width="100%" border="1" cellpadding="1" cellspacing="1">
        <tr bgcolor="#FFFF99">
          <td width="10%" align="center"><b>User ID</b></td>
          <td width="17%" align="center"><b>Username</b></td>
          <td width="43%" align="center"><b>Calendar List (Number of Event)</b></td>
          <td width="15%" align="center"><b>User's Status</b></td>
          <td width="15%" align="center"><b>Action</b></td>
        </tr>
<?  
	$base3 = new mysql_database();
	$base2->tablename = "events";
	do{
?>
        <tr>
          <td align="center" bgcolor="#FFFFFF"><?=$data['ID']?>.</td>
          <td bgcolor="#CCFFFF" align="center"><b><?=$data['user_login']?></b>&nbsp;<?=($data['display_name']!='')?'('.$data['display_name'].')':null?></td>
          <td bgcolor="#FFFFFF"><u>Own</u>: 
<? 
// Get the lisft of own calendar
$base3->tablename = "calendar";
$cal=$base3->select("WHERE user_id='$data[ID]' ");
$totalrows_calendar = $base3->numrow;

if($totalrows_calendar>0) do{ 
	// Number of event in each calendar
	$c_event = $base2->countrow('WHERE calendar_id='.$cal['calendar_id']);

?><a href="?type=edit_calendar&page=<?=$_GET['page']?>&calendar_id=<?=$cal['calendar_id']?>"><?=$cal['calendar_name']?></a>&nbsp;(<?=$c_event?>)&nbsp;<?
} while ($cal=$base3->isNext());
else echo "<i>No calendar</i>";?>

<br /><u>Sharing</u>: <? 
// Get the lisft of sharing calendar (internal and public)
$base3->tablename = "calendar_sharing";
$cal=$base3->select("WHERE user_id='$data[ID]' ");
$totalrows_calendar_sharing = $base3->numrow;

if($totalrows_calendar_sharing>0) do{ 
?><a href="?type=edit_calendar_sharing&page=<?=$_GET['page']?>&calendar_id=<?=$cal['calendar_id']?>"><?=$cal['title']?></a>&nbsp;<?
} while ($cal=$base3->isNext());
else echo "<i>No calendar</i>";?>
          </td>
          <td align="center" bgcolor="#FFFFFF"><?=($data['user_status']==0)?'Inactive':'Active';?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><p><a href="user.php?id=<?=$data['ID']?>&type=edit?>" title="Edit this profile">Show User Profile</a>
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
    <? if($_REQUEST['action']=='search_calendar'){?>
    <div class="SearchPanel">Searching results: "<i><?=$_REQUEST['key']?></i>" <a href="<?=$_SERVER["PHP_SELF"]?>">Reset</a></div><? } // if search panel ?>

<?
///////////////////// CALENDAR LIST ///////////////////////
	$base->use_fields = '*';
	$base->tablename = "calendar, user";
	$query = 'WHERE (`user`.ID = `calendar`.user_id)';
	
	// Seaching calendar
	if($_REQUEST['action']=='search_calendar')
		$query .= ' AND (`calendar`.calendar_name LIKE \'%'.$_REQUEST['key'].'%\' OR `calendar`.calendar_description LIKE \'%'.$_REQUEST['key'].'%\') ';
		
	$data=$base->select();
	if (!isset($_GET['totalrows'])) {
		$base->select($query);
		$totalrows = $base->numrow;
	}
	$data=$base->select($query."ORDER BY calendar_id DESC".calLimitPage());
///////////////////////////////////////////////////////////////////////////
?>
<? 
if($totalrows>0){ ?>
<span class="big">Own Calendars:</span>
<table width="100%" border="1" cellpadding="1" cellspacing="1">
        <tr bgcolor="#FFCCFF">
          <td width="11%" align="center"><b>Calendar ID</b></td>
          <td width="16%" align="center"><b>Username</b></td>
          <td width="43%" align="center"><b>Calendar Title / Short Description</b></td>
          <td width="15%" align="center"><b>User's Status</b></td>
          <td width="15%" align="center"><b>Action</b></td>
        </tr>
<?  
	do{
?>
        <tr>
          <td align="center" bgcolor="#FFFFFF"><i><?=$data['calendar_id']?></i></td>
          <td bgcolor="#CCFFFF" align="center"><?=$data['user_login']?>&nbsp;<?=($data['display_name']!='')?'('.$data['display_name'].')':null?></td>
          <td bgcolor="#FFFFFF" align="center"><b><?=$data['calendar_name']?></b> <?=($data['calendar_description']!='')? ' ('.substr($data['calendar_description'],0,50).')':null;?></td>
          <td align="center" bgcolor="#FFFFFF"><?=($data['user_status']==0)?'Inactive':'Active';?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><p><a href="user.php?id=<?=$data['ID']?>&type=edit?>" title="Edit this profile">Show User Profile</a>
           <br />
          </p></td>
        </tr>
        <?
		} while ($data=$base->isNext());
		
		?>
        <? if($totalPages>0){?><tr><td colspan="5"><? showTableSelect();?></td></tr><? } // if?>
      </table>
<? } // if table ends
else echo "No data found in own calendar list";
?>
<hr />
<?
///////////////////// CALENDAR SHARING LIST ///////////////////////
	$base->use_fields = '*';
	$base->tablename = "calendar_sharing, user";
	$query = 'WHERE (`user`.ID = `calendar_sharing`.user_id)';
	
	// Seaching calendar
	if($_REQUEST['action']=='search_calendar')
		$query .= ' AND (`calendar_sharing`.title LIKE \'%'.$_REQUEST['key'].'%\' OR `calendar_sharing`.url LIKE \'%'.$_REQUEST['key'].'%\') ';

	$data=$base->select();
	if (!isset($_GET['totalrows'])) {
		$base->select($query);
		$totalrows = $base->numrow;
	}
	$data=$base->select($query."ORDER BY calendar_id DESC".calLimitPage());
///////////////////////////////////////////////////////////////////////////
?>
<? 
if($totalrows>0){ ?>
<span class="big">Sharing Calendars:</span>
<table width="100%" border="1" cellpadding="1" cellspacing="1">
        <tr bgcolor="#CCFFCC">
          <td width="11%" align="center"><b>Calendar ID</b></td>
          <td width="16%" align="center"><b>Username</b></td>
          <td width="43%" align="center"><b>Calendar Title / URL</b></td>
          <td width="15%" align="center"><b>User's Status</b></td>
          <td width="15%" align="center"><b>Action</b></td>
        </tr>
<?  
	do{
?>
        <tr>
          <td align="center" bgcolor="#FFFFFF"><i><?=$data['calendar_id']?></i></td>
          <td bgcolor="#CCFFFF" align="center"><?=$data['user_login']?>&nbsp;<?=($data['display_name']!='')?'('.$data['display_name'].')':null?></td>
          <td bgcolor="#FFFFFF" align="center"><b><?=$data['title']?></b> <?=($data['url']!='')? ' ('.$data['url'].')':null;?></td>
          <td align="center" bgcolor="#FFFFFF"><?=($data['user_status']==0)?'Inactive':'Active';?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><p><a href="user.php?id=<?=$data['ID']?>&type=edit?>" title="Edit this profile">Show User Profile</a>
           <br />
          </p></td>
        </tr>
        <?
		} while ($data=$base->isNext());
		
		?>
        <? if($totalPages>0){?><tr><td colspan="5"><? showTableSelect();?></td></tr><? } // if?>
      </table>
<? } // if table ends
else echo "No data found in sharing calendar list";
?>

    </div>
    <div class="TabbedPanelsContent">
      <form id="form_search_user" name="form_search_user" method="get" action="" onsubmit="return validate_form();" >
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
	<div class="TabbedPanelsContent">
<form id="form_search_user" name="form_search_user" method="get" action="" onsubmit="return validate_form();" >
        <table width="500" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="3"><center>
              <b>Search calendar</b>
            </center></td>
          </tr>
          <tr>
            <td width="104"><div align="right">Title/Description:</div></td>
            <td width="8">&nbsp;</td>
            <td width="265"><input name="key" type="text" id="key" size="50" value="<?=$_REQUEST['key']?>"/></td>
          </tr>
          <tr>
            <td><div align="right"></div></td>
            <td>&nbsp;</td>
            <td><input type="submit" name="button" id="button" value="Search" />
            <input name="action" type="hidden" id="action" value="search_calendar" /></td>
          </tr>
        </table>
  </form>
</div>

<?  /////////////// Calendar Detail Tab //////////////////
if($_REQUEST['type']=='edit_calendar_sharing' || $_REQUEST['type']=='edit_calendar'){

	if($_GET['type']=='edit_calendar'){
		$base2->tablename = "calendar";
		$edit=$base2->select("WHERE calendar_id='$_REQUEST[calendar_id]' ");
	}else if($_GET['type']=='edit_calendar_sharing'){
		$base2->tablename = "calendar_sharing";
		$edit=$base2->select("WHERE calendar_id='$_REQUEST[calendar_id]' ");
	}
?>
<div class="TabbedPanelsContent">
      <? if($err!=""){?>
<table width="100%">
        <tr>
          <td bgcolor="#FF9999" style="color:#F00"><img src="../common/icons/warning_16.png" width="16" height="16" />
            <?=$err?></td>
        </tr>
        </table>
        <? }//if ?>
        
<form id="form_edit" name="form_edit" method="post" >
      <? if($_REQUEST['type']=='edit_calendar'){?>
      <table width="500" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="31%">Calendar Name</td>
          <td width="1%">:</td>
          <td width="68%"><input name="cal_name" type="text" id="cal_name" size="25" maxlength="150" value="<?=$edit['calendar_name']?>" /></td>
        </tr>
        <tr>
          <td valign="top">Description</td>
          <td valign="top">:</td>
          <td><textarea name="cal_desc" id="cal_desc" cols="20" rows="2"><?=$edit['calendar_description']?></textarea></td>
        </tr>
        <tr>
          <td valign="top">Calendar Colour&nbsp;</td>
          <td valign="top">:</td>
          <td><?=optionColor($edit['color']);?></td>
        </tr>
        <tr>
          <td valign="top">Make a public:</td>
          <td colspan="2"><input name="cal_share" type="checkbox" id="cal_share" value="1" <?=($edit['sharing']==1)?"checked":NULL;?> onclick="if(this.checked==false) document.getElementById('cal_adjustable').disabled=true; else document.getElementById('cal_adjustable').disabled=false; " />
            <label for="cal_share">Allow everyone can use this calendar link.</label>
            <br />
            <input name="cal_adjustable" type="checkbox" id="cal_adjustable" value="1" <?=($edit['adjustable']==1)?"checked":NULL;?>/>
            <label for="cal_adjustable">Share calendar to everyone can modify whatever events in this calendar.</label>
            <p><img src="../common/icons/xml.gif" alt="XML" width="36" height="14" border="0" /> <a href="<?=BASE_URL?>/share/<?=$edit['user_id']?>/<?=$edit['calendar_id']?>.xml" target="_blank"><?=BASE_URL?>/share/<?=$edit['user_id']?>/<?=$edit['calendar_id']?>.xml </a><br />
            <img src="../common/icons/ical.gif" alt="iCal" width="36" height="14" border="0" /> <a href="<?=BASE_URL?>/share/<?=$edit['user_id']?>/<?=$edit['calendar_id']?>.ics" target="_blank"><?=BASE_URL?>/share/<?=$edit['user_id']?>/<?=$edit['calendar_id']?>.ics </a><br />
            <img src="../common/icons/html.gif" alt="HTML" width="36" height="14" border="0" /></a> <a href="<?=BASE_URL?>/share/<?=$edit['user_id']?>/<?=$edit['calendar_id']?>.html" target="_blank"><?=BASE_URL?>/share/<?=$edit['user_id']?>/<?=$edit['calendar_id']?>.html </a><br />
            </p></td>
        </tr>
      </table>
      <? } else if($_REQUEST['type']=='edit_calendar_sharing'){?>
      <table width="500" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="28%">Calendar Name</td>
          <td width="4%">:</td>
          <td width="68%"><input name="title" type="text" id="title" size="25" maxlength="150" value="<?=$edit['title']?>" /></td>
        </tr>
        <tr>
          <td valign="top">Address (URL)</td>
          <td valign="top">:</td>
          <td><input name="url" type="text" id="url" size="50" maxlength="150" value="<?=$edit['url']?>" />
            <br />
            Acceptable File format <img src="../common/icons/ical.gif" width="36" height="14" /> <a href="http://en.wikipedia.org/wiki/ICalendar" target="_blank"><img src="../common/icons/help.png" width="16" height="16" border="0" /></a></td>
        </tr>
        <tr>
          <td valign="top">Calendar Colour&nbsp;</td>
          <td valign="top">:</td>
          <td><?=optionColor($edit['color']);?></td>
        </tr>
        <tr>
          <td>Status</td>
          <td colspan="2">:
            <input name="show" type="checkbox" id="show" value="1" <?=($edit['show']==1)?"checked":NULL;?>/>
            <label for="show">display on the scheduler</label></td>
        </tr>
</table>
    <? } // end if the edit type ?>

<p><input type="submit" name="button" id="button" value="  Save  " />
  <input name="type" type="hidden" id="type" value="save_<?=$_GET['type']?>" />
  <input name="calendar_id" type="hidden" id="calendar_id" value="save_<?=$_GET['calendar_id']?>" />
  <input type="button" name="button" id="button" value="  Remove*  " onclick="confirmDelete('?calendar_id=<?=$edit['calendar_id']?>&type=del_<?=$_GET['type']?>');"/>
</p>
</form>
  * Please note that all events belonging to this calendar will be deleted completely.</div>
<? } // end if the calendar detail tab ?>
</div>
</div>
<script type="text/javascript">
<!--
<?
if($_GET['type']=='edit_calendar_sharing' || $_GET['type']=='edit_calendar') $tab = 4;
else if($_GET['action']=='search_calendar') $tab = 1;
else $tab = 0;
?>
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1", {defaultTab:<?=$tab?>});
//-->
</script>
</body>
</html>