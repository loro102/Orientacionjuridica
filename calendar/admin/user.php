<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
chkLogin(1);

	$base = new mysql_database();
	$base->tablename = "user";

///////////////////// SHOW DATA ///////////////////////
if($_REQUEST['type']=='edit'){
	$base2 = new mysql_database();
	$base2->tablename = "user";
	$data2=$base2->select("WHERE ID='$_REQUEST[id]' ");
	
////////////////////// EDIT DATA ///////////////////////
}else if($_POST['type']=='save_edit'){ // after edited
	if($_POST['user_pass'] != '') 
		$password = 'user_pass=\''.md5($_POST['user_pass']).'\', ';
	$base->update("WHERE ID='$_REQUEST[id]' ", 
		sprintf("user_login='%s', $password user_email='%s', user_url='%s', user_activation_key='%s', user_status='%s', display_name='%s'",
			$_POST['user_login'],
			$_POST['user_email'],
			$_POST['user_url'],
			$_POST['user_activation_key'],
			$_POST['user_status'],
			$_POST['display_name']));
	goAlert("The user have been saved successfully.","/admin/user.php?page=$_GET[page]");

////////////////////// INSERT DATA /////////////////////
}else if($_POST['type']=="save_"){	
	$base->insert("user_login, user_pass, user_email, user_url, user_activation_key, user_status, display_name, user_registered", 
		sprintf("'%s', '%s', '%s', '%s', '%s', '%s', '%s', now()",
			$_POST['user_login'],
			md5($_POST['user_pass']),
			$_POST['user_email'],
			$_POST['user_url'],
			$_POST['user_activation_key'],
			$_POST['user_status'],
			$_POST['display_name']));
	goAlert("The user have been saved successfully.","/admin/user.php?page=$_GET[page]");

////////////////////// DELETE DATA /////////////////////
}else if($_GET['type']=="del"){ 
	if($_REQUEST[id]=='1') goAlert("Not allow to delete this user!","/admin/user.php");
	$base->deletes("WHERE ID='$_REQUEST[id]'");
	goAlert("The user was deleted successfully!","/admin/user.php?page=$_GET[page]");

////////////////////// RESEND ACTIVATION EMAIL /////////////////////
}else if($_GET['type']=="resend"){ 
	$data=$base->select('WHERE ID="$_GET[id]" AND user_status = 0 LIMIT 1');
	if ($base->numrow>0) {
		$subject = $settings[SYSTEM_NAME].": activation user"; 
		$message = activateEmail($data['user_login'], $data['user_pass'], $data['user_email'], $data['user_activation_key']);
		mailing( $data['user_email'], 'Autosending - No Reply', $data['user_login'], $subject, $message );
		goAlert("The system has sent the activation email to user.","/admin/user.php?page=$_GET[page]");
	}else goAlert("Sorry this user has been activated.","/admin/user.php?page=$_GET[page]");
}
	
///////////////////// SHOW DATA ///////////////////////
	$base->use_fields = '*';
	$data=$base->select();
	if (!isset($_GET['totalrows'])) {
		$base->select($query);
		$totalrows = $base->numrow;
	}
	$data=$base->select("ORDER BY ID DESC".calLimitPage());
///////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>User list</title>

<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="../component/main_script.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="style_admin.css" rel="stylesheet" type="text/css" />

<script language="JavaScript">
function randomToken(length)
{
  chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
  token = "";
  for(x=0;x<length;x++){
    i = Math.floor(Math.random() * 62);
    token += chars.charAt(i);
  }
  return token; 
}

function getPassword(){
	document.getElementById('user_pass').value = randomToken(8);
}

function getNewKey(){
	document.getElementById('user_activation_key').value = randomToken(25);
}

function validate_form(){
	if(document.getElementById('user_login').value == ""){
		alert('Please type the username...'); return false;
	}
	<? if($_REQUEST['type']=='add'){?>
	else if(document.getElementById('user_pass').value == ""){
		alert('Please type the password...'); return false;
	}<? } //if ?>
	else if(document.getElementById('user_email').value == ""){
		alert('Please type the valid email...'); return false;
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
    <li class="TabbedPanelsTab" tabindex="0">User List</li>
    <li class="TabbedPanelsTab" tabindex="0">Add/Edit User</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
      <table width="100%" border="1" cellpadding="1" cellspacing="1">
        <tr bgcolor="#FFFF99">
          <td width="7%" align="center"><b>User ID</b></td>
          <td width="16%" align="center"><b>Username</b></td>
          <td width="19%" align="center"><b>Display name</b></td>
          <td width="22%" align="center"><b>Email</b></td>
          <td width="9%" align="center"><b>User Type</b></td>
          <td width="7%" align="center"><b>Status</b></td>
          <td width="14%" align="center"><b>Registered date</b></td>
          <td width="6%" align="center"><b>Action</b></td>
        </tr>
        <?  
		
		if($totalrows>0) do{ ?>
        <tr>
          <td align="center" bgcolor="#FFFFFF"><?=$data['ID']?>.</td>
          <td bgcolor="#FFFFFF" align="center"><?=$data['user_login']?>&nbsp;</td>
          <td bgcolor="#CCFF99" align="center"><i><?=$data['display_name']?></i>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><?=$data['user_email']?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><?=($data['user_status']==1)?'Administrator':'General User';?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><?=($data['user_status']==0)?'Inactive':'Active';?>&nbsp;</td>
          <td bgcolor="#FFFFFF"><?=$data['user_registered']?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><a href="?id=<?=$data['ID']?>&type=edit&page=<?=$_GET[page]?>" title="Edit this profile">Edit</a> <br />
            <a href="?id=<?=$data['ID']?>&type=resend&page=<?=$_GET[page]?>" title="Resend the confirmation email">Resend</a> <a href="#" onclick="confirmDelete('?id=<?=$data['ID']?>&type=del');" title="Delete permanently this account">Delete</a>
          </td>
        </tr>
        <?
		} while ($data=$base->isNext());
		else echo "No data found";
		?>
        <? if($totalPages>0){?><tr><td colspan="8"><? showTableSelect();?></td></tr><? } // if?>
      </table>
    </div>
    <div class="TabbedPanelsContent"><form id="form1" name="form1" method="post" action="" onsubmit="return validate_form();" >
      <table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr>
            <td colspan="3"><center>
  			<b><? if($_REQUEST['type']=="edit") echo 'Edit'; else echo 'Create New';?> User</b>
            </center>            </td>
          </tr>
          <tr>
            <td width="104"><div align="right"> User ID:</div></td>
            <td width="8">&nbsp;</td>
            <td width="265"><?=($_REQUEST['type']=='edit')?$data2['ID']:' Unjustified';?></td>
          </tr>
          <tr>
            <td width="104"><div align="right"> Username:</div></td>
            <td width="8">&nbsp;</td>
            <td width="265"><input name="user_login" type="text" id="user_login" value="<? if($_REQUEST['type']=="edit") echo $data2["user_login"]?>" size="25" /></td>
          </tr>
          <tr>
            <td><div align="right">Password:</div></td>
            <td>&nbsp;</td>
            <td><input name="user_pass" type="text" id="user_pass" value="" size="25" />
            <input type="button" name="button2" id="button2" value="Random" onclick="getPassword()" />
            </td>
          </tr>
          <tr>
            <td><div align="right">User Type:</div></td>
            <td>&nbsp;</td>
            <td><select name="user_type" id="user_type">
              <option value="0"<? if($data2["user_type"]==0) echo " selected";?>>General User</option>
              <option value="1"<? if($data2["user_type"]==1) echo " selected";?>>Administrator</option>
            </select>            </td>
        </tr>
          <tr>
            <td><div align="right">Email:</div></td>
            <td>&nbsp;</td>
            <td><input name="user_email" type="text" id="user_email" size="30"  value="<? if($_REQUEST['type']=="edit") echo $data2["user_email"]?>" /></td>
          </tr>
          <tr>
            <td><div align="right">User Url:</div></td>
            <td>&nbsp;</td>
            <td><input name="user_url" type="text" id="user_url"  value="<? if($_REQUEST['type']=="edit") echo $data2["user_url"]?>" size="50"/></td>
          </tr>
          <tr>
            <td><div align="right">Display Name:</div></td>
            <td>&nbsp;</td>
            <td><input type="text" name="display_name" id="display_name"  value="<? if($_REQUEST['type']=="edit") echo $data2["display_name"]?>" /></td>
          </tr>
          <tr>
            <td><div align="right">Active Key:</div></td>
            <td>&nbsp;</td>
            <td><input name="user_activation_key" type="text" id="user_activation_key" value="<? if($_REQUEST['type']=="edit") echo $data2["user_activation_key"]?>" size="30" maxlength="60"  readonly="readonly"/> <input type="button" name="button3" id="button3" value="Get new key"  onclick="getNewKey()"/></td>
          </tr>
          <tr>
            <td><div align="right">Status:</div></td>
            <td>&nbsp;</td>
            <td><input type="radio" name="user_status" id="active" value="1"<? if($data2["user_status"]==1) echo " checked";?>/>
            <label for="active">Active </label>
              <input type="radio" name="user_status" id="inactive" value="0"<? if($data2["user_status"]==0 || $_REQUEST['type']=="add") echo " checked";?> />
           <label for="inactive"> Inactive</label></td>
          </tr>
          <tr>
            <td><div align="right">Last Login:</div></td>
            <td>&nbsp;</td>
            <td><?=($_REQUEST['type']=='edit')?$data2['user_lastlogin']:' --- ';?></td>
          </tr>
          <tr>
            <td><div align="right">Registered Date:</div></td>
            <td>&nbsp;</td>
            <td><?=($_REQUEST['type']=='edit')?$data2['user_registered']:' Right Now';?></td>
          </tr>
          <tr>
            <td><div align="right"></div></td>
            <td>&nbsp;</td>
            <td><input type="submit" name="button" id="button" value="Save" />
              <? if($_REQUEST['type']=="edit") echo "<a href='?type=add&page=$_GET[page]'>Create a new user</a>";?>
              <input name="id" type="hidden" id="id" value="<? if($_REQUEST['type']=="edit") echo $data2["ID"]?>" />
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