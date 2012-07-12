<?php 
include ('config.php');
include("component/mysql_module.php");
include("function.php");
chkLogin();

selectUserSettings(-1);

$base = new mysql_database();

// Get the list of calendars
$base->tablename = "calendar"; 
$base->use_fields = "*";
$c=$base->select("WHERE user_id='".$_SESSION["s_user_id"]."' ORDER BY calendar_id ASC");
$calendar_number=$base->numrow(); // Number of Calendars
do{
	$arr_calendar[] = $c['calendar_name'];
	$arr_calendar_id[] = $c['calendar_id'];
	$arr_calendar_color[] = $c['color'];
} while ($c=$base->isNext());

// Get the list of outsource calendars
$base->tablename = "calendar_sharing";
$base->use_fields = "*";
$c=$base->select("WHERE user_id='".$_SESSION["s_user_id"]."' ORDER BY calendar_id ASC");
$csharing_number=$base->numrow(); // Number of Calendars
if($csharing_number>0)
do{
	$arr_csharing[] = $c['title'];
	$arr_csharing_id[] = ($c['calendar_id_shared']>0)?$c['calendar_id_shared']:$c['calendar_id']; // Used main calendar ID only
	$arr_csharing_color[] = $c['color'];
	$arr_csharing_show[] = $c['show'];
	$arr_calendar_id_shared[] = $c['calendar_id_shared'];
} while ($c=$base->isNext());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
      <title><?=$settings['SYSTEM_NAME']?> : ArTrix Calendar <?=$settings['SYSTEM_VER']?></title>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <meta name="title" content="Samples" />
      <!--<meta http-equiv="X-UA-Compatible" content="IE=8" />-->
      <meta name="keywords" content="" />
      <meta name="description" content="" />
      <link ref="shortcut icon" href="./favicon.ico">
      <link rel="stylesheet" href="common/css/style.css" type="text/css" media="screen" />
      <script language="javascript">
	  	// My Calendar
	  	var calendar_number = <?=$calendar_number?>;	
		var myCalendars = new Array('<?=implode("','",$arr_calendar)?>');
		var myCalendarID = new Array('<?=implode("','",$arr_calendar_id)?>');
		var myCalendarColor = new Array('<?=implode("','",$arr_calendar_color)?>');
		/// Other Calendar / Shared Calendar
		var csharing_number = <?=$csharing_number?>;	
		var myCsharing = new Array('<?=implode("','",$arr_csharing)?>');
		var myCsharingID = new Array('<?=implode("','",$arr_csharing_id)?>');
		var myCsharingColor = new Array('<?=implode("','",$arr_csharing_color)?>');
	  </script>

	</head>
	<body onload="doOnLoad();" >
    <div id="divQuickAdd" style="position:absolute; z-index: 180; left:20; width:293px; height:20; visibility:hidden" onmouseover="focusQtxt()"><form onsubmit="quickAdd();return false;">
    <div style="float:left; background-color:#FF9; padding:2px; border:1px solid black">
    <span style="float:right; background-color:gray; color:white; font-weight:bold; text-align:center; cursor:pointer" onclick="javascript:hideQuickAdd()">&nbsp;X&nbsp;</span>
    Quick Add: <input type="text" name="quickaddtext" id="quickaddtext" style="font-size:15px;"/>
    <img src="common/icons/add_16.png" style="cursor:pointer;" onclick="quickAdd();"/>  
    <br /><i><font size="-1">Example: Dinner with John 7pm tomorrow</font></i>
    <a href="help/quick_add.htm" target="_blank"><img src="common/icons/help.png" border="0" title="วิธีการใช้งาน" /></a></div></form>
    </div>
    <div class="headerline">
    <form onsubmit="doSearch();return false;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="30%" rowspan="2" valign="top"><img src="common/image/calendar-logo.png" alt="ArTrix Calendar Logo"/></td>
        <td width="70%" align="right"><b>
        <font color='blue'><?=$_SESSION['s_name']?></font>
        </b><span id="top_menu">| <? if($_SESSION['s_user_type']>=1){?> 
        <a href="./admin/">Administration</a> |
        <? } // if ?> <a href="javascript:void(0);" onclick="showPop(1);" title="Calendar Settings"> Settings</a> | <a href="javascript:void(0);" onclick="showPop(4);" title="Updating new  features of the system" ><font color=red>What's new?</font></a> | <a href="javascript:void(0);" onclick="showPop(0);" title="Calendar Help / Send us your feedback">Help</a> | <a href="signout.php" title="Sign out">Sign out</a></span>&nbsp;&nbsp;</td>
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
        <link rel="stylesheet" type="text/css" href="component/dhtmlx/dhtmlx.css">
      <script src="component/dhtmlx/dhtmlx.js"></script>
      <script src="component/main_script.js"></script>
      <script src="component/extension.js"></script>
      
      	<div id="left_view">
           	<div id="calendarToggle"></div>
			<div id="MainMenu">
       	    <table width="100%" border="0" cellspacing="1" cellpadding="1">
              <tr>
                <td width="2%" rowspan="6">&nbsp;</td>
                <td></td>
              </tr>
              <tr>
                <td width="98%">&#8226; <a href="javascript:void(0)" onclick="cEvent();" title="You can enter as much information as you'd like about your event.">Create an event</a></td>
              </tr>
              <tr>
                <td>&#8226; <a href="javascript:void(0)" onmousedown="placeIt();showQuickAdd(); return false" title="Quick Add can figure out what you mean and pop the new event right onto your calendar.">Quick Add!</a></td>
              </tr>
              <tr>
                <td>&#8226; <a href="javascript:void(0)" onclick="dhxLayout.cells('a').showHeader();dhxLayout.cells('a').collapse();" title="You can fold or open the left panel by clicking on this link.">Hide this panel </a></td>
              </tr>
              <tr>
                <td>&#8226; <a href="#" target="_blank" title="Find out more documentation.">User Manual</a></td>
              </tr>
              <tr>
                <td></td>
              </tr>
            </table>
				<div id="cal_panel" style="position: relative; width: 100%; height: 900px;"></div>
				<div id="div_cal_panel1">

                  <table width="100%" border="0" cellpadding="2" cellspacing="2" id="table_cal_panel1" style="color:#FFF;"><?
                    ///// Showing the category (calendar)
                    for($i=0;$i<$calendar_number;$i++){
                    ?>
                        <tr height="20">
                          <td width="98%" onclick="calendarSettings(1,'modify',<?=$arr_calendar_id[$i]?>,this.parentNode.parentNode.rowIndex);" style="cursor:pointer;background-color:#<?=$arr_calendar_color[$i]?>;"><?=$arr_calendar[$i]?></td>
                          <td width="2%"><a href="javascript:void(0);" onclick="calendarSettings(1,'modify',<?=$arr_calendar_id[$i]?>,this.parentNode.parentNode.rowIndex);"><img src="common/icons/note_edit.png" border="0" title="Setting your <?=$arr_calendar[$i]?> calendar"/></a></td>
                        </tr>
                    <? } //  for loop table?>
                  </table>
                  <table width="100%" border="0" align="right" cellpadding="3" cellspacing="3">
                    <tr>
                      <td align="right" valign="bottom"><font color="blue">
                       <a href="javascript:void(0);" onclick="calendarSettings(1,'create',0,0);" title="Create a new calendar">Create new calendar</a></font></td>
                    </tr>
                  </table>
            </div>
            <div id="div_cal_panel2" title="Other calendars">

                  <table width="100%" border="0" cellpadding="2" cellspacing="2" id="table_cal_panel2" style="color:#FFF;"><?
                    ///// Showing the category (calendar)
                    for($i=0;$i<$csharing_number;$i++){
                    ?>
                        <tr height="20">
                          <td width="98%" onclick="calendarSettings(2,'modify',<?=$arr_csharing_id[$i]?>,this.parentNode.parentNode.rowIndex,'<?=($arr_calendar_id_shared[$i]>0)?'int':'ext';?>');" style="cursor:pointer;<? if($arr_csharing_show[$i]==1){?>background-color:#<?=$arr_csharing_color[$i]?><? }else{ ?>color:#<?=$arr_csharing_color[$i]?><? }//if ?>;" ><?=$arr_csharing[$i]?></td>
                          <td width="2%">
                          <a href="javascript:void(0);" onclick="calendarSettings(2,'modify',<?=$arr_csharing_id[$i]?>,this.parentNode.parentNode.rowIndex,'<?=($arr_calendar_id_shared[$i]>0)?'int':'ext';?>');">
                          <img src="common/icons/note_edit.png" border="0" title="Setting <?=$arr_csharing[$i]?> calendar"/></a></td>
                        </tr>
                    <? } //  for loop table?>
                  </table>
                  <table width="100%" border="0" align="right" cellpadding="3" cellspacing="3">
                    <tr>
                      <td align="right" valign="bottom"><font color="blue">
                      Add new: <a href="javascript:void(0);" onclick="showInternalList();" title="List of internal public calendars in the system">Internal</a> |
                       <a href="javascript:void(0);" onclick="calendarSettings(2,'add',0,0,'ext');" title="Add public calendar from existing outside the system e.g. Google, Apple in format iCal (.ics)">Public</a></font></td>
                    </tr>
                  </table>
            </div>
            
          </div>
        </div>
	</div>
    
    <div id="footer">
        <p><b><?=$settings['SYSTEM_NAME']?></b> Powered by ArTrix Calendar <?=$settings['SYSTEM_VER']?>, under the <a href="http://www.gnu.org/licenses/gpl-2.0.html">GNU/GPL License</a>.
        </p>
    </div>
	</body>
</html>