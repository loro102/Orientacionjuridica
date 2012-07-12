<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
chkLogin(1);

	$base = new mysql_database();
	$base->tablename = "announcement";

///////////////////// FOR SHOW DATA ///////////////////////
if($_REQUEST['type']=='edit'){
	$base2 = new mysql_database();
	$base2->tablename = "announcement";
	$data2=$base2->select("WHERE announceID='$_REQUEST[id]' ");
	
////////////////////// FOR EDIT DATA ///////////////////////
}else if($_POST['type']=='save_edit'){ // after edited
	$base->update("WHERE announceID='$_REQUEST[id]' ", 
		sprintf("title='%s', detail='%s', status='%s', laste_modified=now()",
			$_POST['title'],
			$_POST['detail'],
			$_POST['status']));
	goAlert("The user have been saved successfully.","/admin/announce.php?page=$_GET[page]");

////////////////////// FOR INSERT DATA /////////////////////
}else if($_POST['type']=="save_"){	
	$base->insert("title, detail, laste_modified, user_id, status", 
		sprintf("'%s', '%s', now(), '".$_SESSION['s_user_id']."', '%s'",
			$_POST['title'],
			$_POST['detail'],
			$_POST['status']));
	goAlert("The user have been saved successfully.","/admin/announce.php?page=$_GET[page]");

////////////////////// FOR DELETE DATA /////////////////////
}else if($_GET['type']=="del"){ 
	$base->deletes("WHERE announceID='$_REQUEST[id]'");
	goAlert("The user was deleted successfully!","/admin/announce.php?page=$_GET[page]");
}
	
///////////////////// FOR SHOW DATA ///////////////////////
	$base->use_fields = '*';
	$data=$base->select();
	if (!isset($_GET['totalrows'])) {
		$base->select($query);
		$totalrows = $base->numrow;
	}
	$data=$base->select("ORDER BY laste_modified DESC".calLimitPage());
///////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Announcement</title>

<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="../component/main_script.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="style_admin.css" rel="stylesheet" type="text/css" />

<script language="JavaScript">

function validate_form(){
	if(document.getElementById('title').value == ""){
		alert('Please type the title...'); return false;
	}
	else if(document.getElementById('detail').value == ""){
		alert('Please type the detail...'); return false;
	}
	else if(document.getElementById('display_name').value == ""){
		alert('Please type the display name...'); return false;
	}
	return true;
}
</script>

</head>

<body>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Announcement List</li>
    <li class="TabbedPanelsTab" tabindex="0">Add/Edit </li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
      <table width="100%" border="1" cellpadding="1" cellspacing="1">
        <tr bgcolor="#FFFF99">
          <td width="11%" align="center"><b>ID</b></td>
          <td width="55%" align="center"><b>Title</b></td>
          <td width="10%" align="center"><b>Status</b></td>
          <td width="18%" align="center"><b>Last Update</b></td>
          <td width="6%" align="center"><b>Action</b></td>
        </tr>
        <?  
		
		if($totalrows>0) do{ ?>
        <tr bgcolor="#FFFFFF">
          <td align="center"><?=$data['announceID']?>.</td>
          <td align="center"><?=$data['title']?>&nbsp;</td>
          <td align="center"><?=($data['status']==0)?'Inactive':'Active';?>&nbsp;</td>
          <td align="center"><?=$data['laste_modified']?>&nbsp;</td>
          <td align="center"><a href="?id=<?=$data['announceID']?>&type=edit&page=<?=$_GET[page]?>" title="Edit this profile">Edit</a> <br />            <a href="#" onclick="confirmDelete('?id=<?=$data['announceID']?>&type=del');" title="Delete permanently this account">Delete</a>
          </td>
        </tr>
        <?
		} while ($data=$base->isNext());
		else echo "No data found";
		?>
        <? if($totalPages>0){?><tr><td colspan="5"><? showTableSelect();?></td></tr><? } // if?>
      </table>
    </div>
    <div class="TabbedPanelsContent"><form id="form1" name="form1" method="post" action="" onsubmit="return validate_form();" >
      <table width="550" border="0" cellspacing="0" cellpadding="0">
  <tr>
            <td colspan="3"><center>
  			<b><? if($_REQUEST['type']=="edit") echo 'Edit'; else echo 'Create New';?> Announcement</b>
            </center>            </td>
          </tr>
          <tr>
            <td width="173"><div align="right"> User:</div></td>
            <td width="10">&nbsp;</td>
            <td width="317"><?=$_SESSION['s_username']?> (<?=$_SESSION['s_user_id']?>)</td>
          </tr>
          <tr>
            <td width="173"><div align="right"> Title:</div></td>
            <td width="10">&nbsp;</td>
            <td width="317"><input name="title" type="text" id="title" value="<? if($_REQUEST['type']=="edit") echo $data2["title"]?>" size="50" /></td>
          </tr>
          <tr>
            <td valign="top"><div align="right">Detail:</div></td>
            <td>&nbsp;</td>
            <td><textarea name="detail" id="detail" cols="50" rows="10"><? if($_REQUEST['type']=="edit") echo $data2["detail"]?></textarea></td>
          </tr>
          <tr>
            <td><div align="right">Status:</div></td>
            <td>&nbsp;</td>
            <td><input type="radio" name="status" id="active" value="1"<? if($data2["status"]==1) echo " checked";?>/>
            <label for="active">Active </label>
              <input type="radio" name="status" id="inactive" value="0"<? if($data2["status"]==0 || $_REQUEST['type']=="add") echo " checked";?> />
           <label for="inactive"> Inactive</label></td>
          </tr>
          <tr>
            <td><div align="right">Last Modified:</div></td>
            <td>&nbsp;</td>
            <td><?=($_REQUEST['type']=='edit')?$data2['laste_modified']:' Right Now';?></td>
          </tr>
          <tr>
            <td><div align="right"></div></td>
            <td>&nbsp;</td>
            <td><input type="submit" name="button" id="button" value="Save" />
              <? if($_REQUEST['type']=="edit") echo "<a href='?type=add&page=$_GET[page]'>Create a new announcement</a>";?>
              <input name="id" type="hidden" id="id" value="<? if($_REQUEST['type']=="edit") echo $data2["announceID"]?>" />
              <input name="type" type="hidden" id="type" value="save_<?=$_GET['type']?>" /></td>
          </tr>
        </table>
  
    </form>
    </div>
  </div>
</div>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1", {defaultTab:<?=($_REQUEST['type']=='edit' || $_REQUEST['type']=='add')?1:0;?>});
//-->
</script>
</body>
</html>