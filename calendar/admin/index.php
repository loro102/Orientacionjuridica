<?php 
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
chkLogin(1);

$base = new mysql_database();

selectUserSettings(-1);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
      <title>Administration Site : ArTrix Calendar</title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <meta name="title" content="Samples" />
      <!--<meta http-equiv="X-UA-Compatible" content="IE=8" />-->
      <meta name="keywords" content="" />
      <meta name="description" content="" />
      <link ref="shortcut icon" href="../favicon.ico">
      <link rel="stylesheet" href="../common/css/style.css" type="text/css" media="screen" />
      

	</head>
	<body onload="doOnLoad();" >
    <div id="divQuickAdd" style="position:absolute; z-index: 180; left:20; width:293px; height:20; visibility:hidden" onmouseover="focusQtxt()"><form onsubmit="quickAdd();return false;">
    <div style="float:left; background-color:#FF9; padding:2px; border:1px solid black">
    <span style="float:right; background-color:gray; color:white; font-weight:bold; text-align:center; cursor:pointer" onclick="javascript:hideQuickAdd()">&nbsp;X&nbsp;</span>
    Quick Add: <input type="text" name="quickaddtext" id="quickaddtext" style="font-size:15px;"/>
    <img src="../common/icons/add_16.png" style="cursor:pointer;" onclick="quickAdd();"/>  
    <br /><i><font size="-1">Example: Dinner with John 7pm tomorrow</font></i>
    <a href="../help/quick_add.htm" target="_blank"><img src="../common/icons/help.png" border="0" title="วิธีการใช้งาน" /></a></div></form>
    </div>
    <div class="headerline">
    <form onsubmit="doSearch();return false;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="45%" rowspan="2" valign="top"><a href="./"><img src="admin-logo.png" alt="ArTrix Calendar Logo"/></a></td>
        <td width="55%" align="right"><b>User: 
        <font color='blue'><?=$_SESSION['s_name']?></font></b> [ <?=$_SESSION['s_username']?> ] | 
        <a href="../render">Go to Calendar</a> 
        | <a href="../signout.php" title="Sign out">Sign out</a></span>&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td align="right"><input name="search_text" type="text" id="search_text" size="20"/>
          <input type="button" id="buttonSearch" value="Search" onclick="doSearch();"/> <!--Advanced Search-->&nbsp;</td>
      </tr>
    </table>
    </form>
    <br />
    </div>
    
    <div class="content">
        <link rel="stylesheet" type="text/css" href="../component/dhtmlx/dhtmlx.css">
<script src="../component/dhtmlx/dhtmlx.js"></script>
<script language="javascript">
	window.dhx_globalImgPath = "./dhtmlx/imgs/";

	var dhxLayout, dhxTabbar, ifr, mCal, dhxWins, popup1;
	function doOnLoad() {
		dhxLayout = new dhtmlXLayoutObject(document.body, "2U");
		dhxLayout.progressOn();
		dhxLayout.cont.obj._offsetTop = 50; // top margin
		dhxLayout.cont.obj._offsetHeight = -75; // bottom margin
		dhxLayout.setSizes();
		dhxLayout.cells("b").hideHeader();
		dhxLayout.cells("a").setText("Menu");
		dhxLayout.cells("a").setWidth(150);
		dhxLayout.cells("a").fixSize(true, true);
		
		dhxLayout.setAutoSize("b", "a;b");
		dhxLayout.setEffect("resize", true);
		dhxLayout.cells("a").attachObject("left_view");
		
		dhxLayout.cells("b").attachObject("main_view");
		ifr = dhxLayout.cells("b")._frame;

		dhxLayout.progressOff();
	}
	
	function loadPage(page){
		dhxLayout.progressOn();
		dhxLayout.cells("b").attachURL(page);
		dhxLayout.progressOff();
	}
	

</script>
      
      	<div id="left_view">
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
              <tr>
                <td width="2%" rowspan="7">&nbsp;</td>
                <td></td>
              </tr>
              <tr>
                <td width="98%">&#8226; <a href="javascript:void()" onclick="loadPage('announce.php');">Announcements</a></td>
              </tr>
              <tr>
                <td>&#8226; <a href="javascript:void()" onclick="loadPage('user.php');">User List</a></td>
              </tr>
              <tr>
                <td>&#8226; <a href="javascript:void()" onclick="loadPage('calendar.php')">User's Calendar</a></td>
              </tr>
              <tr>
                <td>&#8226; <a href="javascript:void()" onclick="loadPage('settings_user.php')">User's Settings</a></td>
              </tr>
              <tr>
                <td>&#8226; <a href="javascript:void()" onclick="loadPage('feedback.php')">Feedbacks</a></td>
              </tr>
              <tr>
                <td>&#8226; <a href="javascript:void()" onclick="loadPage('settings_system.php')">System Settings</a></td>
              </tr>
            </table>
        </div>
        <div id="main_view">
          <p align="center"><img src="../common/image/calendar-logo.png" alt="" width="250" height="44" /></p>
          <table width="550" border="5" align="center" cellpadding="4" cellspacing="4">
            <tr>
              <td colspan="2"><b>System Information</b></td>
            </tr>
            <? 
			$no_query = 3;
			$base->tablename = 'calendar';
			$number = $base->numrow();
			$lastc = $base->select('ORDER BY `calendar_id` DESC LIMIT '.$no_query);
			?>
            <tr>
              <td width="25%">Calendars:</td>
              <td width="75%"><b><?=$number?></b>, last created: [<? $i=1; do{ ?> <a href="javascript:void()" onclick="loadPage('calendar.php?type=edit_calendar&calendar_id=<?=$lastc['calendar_id']?>')"><?=$lastc['calendar_name']?></a> <?=($no_query!=$i)?'|' :''; $i++?> <? } while ($lastc=$base->isNext());?>]</td>
            </tr>
            <? 
			$base->tablename = 'calendar_sharing';
			$number = $base->countrow('WHERE calendar_id_shared IS NOT NULL');
			$lastc = $base->select('WHERE calendar_id_shared IS NOT NULL ORDER BY `calendar_id` DESC LIMIT '.$no_query);
			?>
            <tr>
              <td>Internal Calendars:</td>
              <td><b><?=$number?></b>, last created: [<? $i=1; do{ ?> <a href="javascript:void()" onclick="loadPage('calendar.php?type=edit_calendar_sharing&calendar_id=<?=$lastc['calendar_id']?>')"><?=$lastc['title']?></a> <?=($no_query!=$i)?'|' :''; $i++?> <? } while ($lastc=$base->isNext());?>]</td>
            </tr>
            <? 
			$base->tablename = 'calendar_sharing';
			$number = $base->countrow('WHERE calendar_id_shared IS NULL');
			$lastc = $base->select('WHERE calendar_id_shared IS NULL ORDER BY `calendar_id` DESC LIMIT '.$no_query);
			?>
            <tr>
              <td>Public Calendars:</td>
              <td><b><?=$number?></b>, last created: [<? $i=1; do{ ?> <a href="javascript:void()" onclick="loadPage('calendar.php?type=edit_calendar_sharing&calendar_id=<?=$lastc['calendar_id']?>')"><?=$lastc['title']?></a> <?=($no_query!=$i)?'|' :''; $i++?> <? } while ($lastc=$base->isNext());?>]</td>
            </tr>
            <? 
			$base->tablename = 'events';
			$number = $base->numrow();
			?>
            <tr>
              <td>Events:</td>
              <td><b><?=$number?></b></td>
            </tr>
            <? 
			$no_query = 5;
			$base->tablename = 'user';
			$number = $base->numrow();
			$lastc = $base->select('ORDER BY `user_registered` DESC LIMIT '.$no_query);
			?>
            <tr>
              <td>Users:</td>
              <td><b><?=$number?></b></td>
            </tr>
            <tr>
              <td><?=$no_query?> new users:</td>
              <td><? $i=1; do{ ?> <a href="javascript:void()" onclick="loadPage('user.php?type=edit&id=<?=$lastc['ID']?>')"><?=$lastc['display_name']?> (<?=$lastc['user_login']?>)</a> <?=($no_query!=$i)?'|' :''; $i++?> <? } while ($lastc=$base->isNext());?></td>
            </tr>
            <? $lastc = $base->select('ORDER BY `user_lastlogin` DESC LIMIT '.$no_query);?>
            <tr>
              <td><?=$no_query?> last user loggin:</td>
              <td><? $i=1; do{ ?> <a href="javascript:void()" onclick="loadPage('user.php?type=edit&id=<?=$lastc['ID']?>')"><?=$lastc['display_name']?> (<?=$lastc['user_login']?>)</a> <?=($no_query!=$i)?'|' :''; $i++?> <? } while ($lastc=$base->isNext());?></td>
            </tr>
            <tr>
              <td>System info:</td>
              <td><? foreach ($settings as $k => $v) echo $k.': '.$v.'<br>';
		  ?></td>
            </tr>
          </table>
        </div>
	</div>
    
    <div id="footer">
        <p>ArTrix Calendar <?=$settings['SYSTEM_VER']?>, under the <a href="http://www.gnu.org/licenses/gpl-2.0.html">GNU/GPL License</a>.
        </p>
    </div>
	</body>
</html>