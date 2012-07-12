<?
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
chkLogin(1);
$account = explode('@',$_SESSION["s_user_id"]);

// Status 0 = Open, 1 = Fixed, 2 = Deletes
$base = new mysql_database();

if(isset($_GET['action'])){
	
	if($_GET['action']=='fixed') $status = 1; 
	else if($_GET['action']=='delete') $status = 2;
	else if($_GET['action']=='cancel_fixed') $status = 0;
	
	$sql = "UPDATE support SET status='".$status."' WHERE support_id ='".mysql_real_escape_string($_GET['id'])."' LIMIT 1";
	$base->execute($sql);
	goAlert("Status has changed successful","./admin/support.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Feedback</title>

<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<link href="style_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">New Arrival Items</li>
    <li class="TabbedPanelsTab" tabindex="0">Approved List</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
    <div class="TabbedPanelsContent">
      <table width="100%" border="1" cellpadding="1" cellspacing="1">
        <tr bgcolor="#FFFF99">
          <td width="6%" align="center"><b>ID</b></td>
          <td width="11%" align="center"><b>Title</b></td>
          <td width="28%" align="center"><b>Description</b></td>
          <td width="9%" align="center"><b>User</b></td>
          <td width="15%" align="center"><b>Date / Time</b></td>
          <td width="7%" align="center"><b>IP</b></td>
          <td width="18%" align="center"><b>User Agent</b></td>
          <td width="6%" align="center"><b>Action</b></td>
        </tr>
        <?  
		// Get the list
		$base->tablename = "support"; 
		$base->use_fields = "*";
		$data=$base->select("WHERE status = '0' ORDER BY date DESC");
		$row=$base->numrow(); // Number Rows
		
		if($row>0) do{ ?>
        <tr>
          <td align="center" bgcolor="#FFFFFF"><?=$data['support_id']?>.</td>
          <td bgcolor="#FFFFFF"><?=$data['notice']?>&nbsp;</td>
          <td bgcolor="#FF9999"><?=nl2br($data['detail'])?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><?=$data['user_id']?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><?=$data['date']?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><?=$data['IP']?>&nbsp;</td>
          <td bgcolor="#FFFFFF"><?=$data['user_agent']?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><a href="?id=<?=$data['support_id']?>&action=fixed">Fixed</a> <a href="?id=<?=$data['support_id']?>&amp;action=delete">Delete</a>
          </td>
        </tr>
        <?
		} while ($data=$base->isNext());
		else echo "ไม่พบรายการ";
		?>
      </table>
    </div>
    <div class="TabbedPanelsContent">
    <table width="100%" border="1" cellpadding="1" cellspacing="1">
        <tr bgcolor="#FFFF99">
          <td width="6%" align="center"><b>ID</b></td>
          <td width="11%" align="center"><b>Title</b></td>
          <td width="28%" align="center"><b>Description</b></td>
          <td width="9%" align="center"><b>User</b></td>
          <td width="15%" align="center"><b>Date / Time</b></td>
          <td width="7%" align="center"><b>IP</b></td>
          <td width="18%" align="center"><b>User Agent</b></td>
          <td width="6%" align="center"><b>Action</b></td>
        </tr>
        <?  
		// Get the list
		$base->tablename = "support"; 
		$base->use_fields = "*";
		$data=$base->select("WHERE status = '1' ORDER BY date DESC");
		$row=$base->numrow(); // Number Rows
		
		if($row>0) do{ ?>
        <tr>
          <td align="center" bgcolor="#FFFFFF"><?=$data['support_id']?>.</td>
          <td bgcolor="#FFFFFF"><?=$data['notice']?>&nbsp;</td>
          <td bgcolor="#CCFFCC"><?=nl2br($data['detail'])?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><?=$data['user_id']?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><?=$data['date']?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><?=$data['IP']?>&nbsp;</td>
          <td bgcolor="#FFFFFF"><?=$data['user_agent']?>&nbsp;</td>
          <td align="center" bgcolor="#FFFFFF"><a href="?id=<?=$data['support_id']?>&action=cancel_fixed">Un-fixed</a> <a href="?id=<?=$data['support_id']?>&action=delete">Delete</a>
          </td>
        </tr>
        <?
		} while ($data=$base->isNext());
		else echo "ไม่พบรายการ";
		?>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//-->
</script>
</body>
</html>