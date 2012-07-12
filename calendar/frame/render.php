<? 
include ('../config.php');
include("../component/mysql_module.php");
include("../function.php");
include('get_settings.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title></title>

<script src="../codebase/dhtmlxscheduler.js" type="text/javascript" charset="utf-8"></script>
<script src="../codebase/ext/dhtmlxscheduler_agenda_view.js" type="text/javascript" charset="utf-8"></script>
<script src="../codebase/ext/dhtmlxscheduler_readonly.js" type="text/javascript" charset="utf-8"></script>
<? if($settings['SHOW_Y']=="on" && $settings['SHOW_Y']<>""){?><script src="../codebase/ext/dhtmlxscheduler_year_view.js"></script><? }?>

	<link rel="stylesheet" href="../codebase/dhtmlxscheduler.css" type="text/css" title="no title" charset="utf-8">
    <link rel="stylesheet" href="../codebase/ext/dhtmlxscheduler_ext.css" type="text/css" title="no title" charset="utf-8">
    <link rel="stylesheet" href="../common/css/css_event.php" type="text/css" media="screen">
<style type="text/css" media="screen">
html, body{
	margin:0px;
	padding:0px;
	height:100%;
	overflow:hidden;
}
</style>

<script type="text/javascript" charset="utf-8">
	function init() {
		
		scheduler.config.xml_date="%Y-%m-%d %H:%i";
		scheduler.config.multi_day = true;

		scheduler.config.first_hour = <?=isset($settings['FRIST_HOUR'])?$settings['FRIST_HOUR']:6;?>;
		scheduler.config.last_hour = <?=isset($settings['LAST_HOUR'])?$settings['LAST_HOUR']:21;?>;
		scheduler.locale.labels.section_location="Location";
		scheduler.config.start_on_monday = <?=isset($settings['START_ON'])?$settings['START_ON']:"false";?>;
		scheduler.config.details_on_create = true;
		scheduler.config.details_on_dblclick = false;
		scheduler.config.readonly_form = false;
		scheduler.config.time_step = <?=isset($settings['TIME_STEP'])?$settings['TIME_STEP']:5;?>;
		scheduler.config.positive_closing = false;
		scheduler.xy.scale_height = 20;
		scheduler.xy.scale_width = 50;
		<? if($settings['SHOW_Y']=="on" && $settings['SHOW_Y']<>""){?>
			scheduler.config.year_x = <?=isset($settings['X_YSCALE'])?$settings['X_YSCALE']:4;?>;
			scheduler.config.year_y = <?=isset($settings['Y_YSCALE'])?$settings['Y_YSCALE']:3;?>;
		<? } //if year view?>

		scheduler.templates.event_class=function(start,end,event){
		   return "c"+event.color;
		}
		
		scheduler.config.details_on_create=true;
		
		var step = <?=isset($settings['STEP_WD'])?$settings['STEP_WD']:15;?>;
		var format = scheduler.date.date_to_str("%H:%i");
		scheduler.config.hour_size_px=(60/step)*21;
		scheduler.templates.hour_scale = function(date){
			html="";
			for (var i=0; i<60/step; i++){
				html+="<div style='height:21px;line-height:21px;'>"+format(date)+"</div>";
				date = scheduler.date.add(date,step,"minute");
			}
			return html;
		}
		
		scheduler.attachEvent("onEventSave",function(id,data){
			if (!data.text) {
				alert("Title must not be empty");
				return false;
			}
			if (data.text.length<7) {
				alert("Title too short!");
				return false;
			}
			else if (data.text.length<7) {
				alert("Title too short!");
				return false;
			}
			return true;
		})

	
	scheduler.attachEvent("onClick",allow_own);
   	scheduler.attachEvent("onDblClick",allow_own);
	scheduler.attachEvent("onBeforeDrag",allow_drag);
   
		scheduler.init('scheduler_here',null,"<?=isset($settings['DEF_VIEW'])?$settings['DEF_VIEW']:"month";?>");
		scheduler.load("../event/events.php?uid="+scheduler.uid()); // My Calendars
		scheduler.load("../share/ical_select.php?uid=<?=$_SESSION["s_user_id"]?>"); // External Shared
		scheduler.load("../event/events_internal.php?uid=<?=$_SESSION["s_user_id"]?>"); // Internal Shared
		
		var dp = new dataProcessor("../event/events.php");
		dp.init(scheduler);

		scheduler.form_blocks["l_editor"]={
			render:function(sns){
				return "<div class='dhx_cal_ltext' style='height:60px;'>Title:&nbsp;<input type='text' size=40>&nbsp;&nbsp;Calendars:&nbsp;<select id='celdendar_select' onkeyup='onChangeCalendar(this);' onchange='onChangeCalendar(this);'></select><br/>Description:&nbsp;<input type='text' size=70><input type='hidden' id='celdendar_color' ></div>"; // size 70 of details
			},
			set_value:function(node,value,ev){
				clearOptions('celdendar_select');
					var calopt = document.getElementById('celdendar_select');
					if(!ev.readonly){
						for (var i=0;i<window.parent.calendar_number;i++){
							calopt.options[i]= new Option(window.parent.myCalendars[i],window.parent.myCalendarID[i]);
							if(window.parent.myCalendarID[i] == ev.calendar_id) calopt[i].selected = "1"
						}
					}else{
						var index = window.parent.myCsharingID.indexOf(ev.calendar_id);
						if(index==-1)calopt.options[0]= new Option("(Not justified)",0);
						else calopt.options[0]= new Option(window.parent.myCsharing[index],0);
						calopt[0].selected = "1";
					}
				node.childNodes[1].value=value||"";
				node.childNodes[6].value=ev.details||"";
				node.childNodes[7].value=ev.color||window.parent.myCalendarColor[0];
				//alert(ev.calendar_id);
			},
			get_value:function(node,ev){
				ev.calendar_id = node.childNodes[3].value;
				ev.details = node.childNodes[6].value;
				ev.color = node.childNodes[7].value;
				return node.childNodes[1].value;
			},
			focus:function(node){
				var a=node.childNodes[1]; a.select(); a.focus(); 
			}
			
		}
		
		scheduler.config.lightbox.sections=[	
				{ name:"description", height:200, map_to:"text", type:"l_editor" , focus:true},
				{ name:"time", height:72, type:"time", map_to:"auto"}	
		]	
	
	//window.parent.checkServer();
	//window.parent.dhxLayout.progressOff();
}
function onChangeCalendar(c){
	document.getElementById("celdendar_color").value= window.parent.myCalendarColor[c.selectedIndex];
}

//allow edit operations only for own events
function allow_drag(id){
	var ev = scheduler.getEvent(id); 
	try{ 
		if(ev.readonly == "1") return false;                 
	}catch(er){ } 
	return true; 
}
function allow_own(id){
var ev = scheduler.getEvent(id); 
 	try{ 
		if(ev.readonly == "1"){ 
			scheduler.config.readonly_form = true;                         
 		} 
		else{
			
		}
	}catch(er){ } 
	scheduler.config.readonly_form = false; 	
	
 	return true; 
}
   
function clearOptions(id){
	var selectObj = document.getElementById(id);
	var selectParentNode = selectObj.parentNode;
	var newSelectObj = selectObj.cloneNode(false); // Make a shallow copy
	selectParentNode.replaceChild(newSelectObj, selectObj);
	return newSelectObj;
}

function main_setCurrentView(date,view_type){scheduler.setCurrentView(date,view_type);}
function main_reload(){
	window.parent.dhxLayout.progressOn();
	scheduler.clearAll(); 
	scheduler.load("../event/events.php?uid="+scheduler.uid()); // My Calendars
	scheduler.load("../share/ical_select.php?uid=<?=$_SESSION["s_user_id"]?>"); // External Shared
	scheduler.load("../event/events_internal.php?uid=<?=$_SESSION["s_user_id"]?>"); // Internal Shared
	window.parent.dhxLayout.progressOff();
}

(function(){
	var A=false;scheduler.attachEvent("onBeforeLightbox",function(){A=true;return true});
	scheduler.attachEvent("onAfterLightbox",function(){A=false;return true});
	dhtmlxEvent(document,(_isOpera?"keypress":"keydown"),function(C){C=C||event;if(!A){
		if(C.keyCode==37||C.keyCode==39){
			C.cancelBubble=true;
			var B=scheduler.date.add(scheduler._date,(C.keyCode==37?-1:1),scheduler._mode);
			scheduler.setCurrentView(B);return true}}})})();
</script>
</head>
<body onload="init();">
	<div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%;'>
		<div class="dhx_cal_navline">
			<div class="dhx_cal_prev_button">&nbsp;</div>
			<div class="dhx_cal_next_button">&nbsp;</div>
			<div class="dhx_cal_today_button"></div>
			<div class="dhx_cal_date"></div>
            <div class="dhx_cal_tab" name="agenda_tab" title="แสดงรายการเหตุการณ์ล่วงหน้าตามลำดับ" style="right:280px;"></div>
			<div class="dhx_cal_tab" name="day_tab" title="มุมมองรายวัน" style="right:204px;"></div>
			<div class="dhx_cal_tab" name="week_tab" title="มุมมองรายสัปดาห์" style="right:140px;"></div>
			<div class="dhx_cal_tab" name="month_tab" title="มุมมองรายเดือน" style="right:76px;"></div>
            <? if($settings['SHOW_Y']=="on" && $settings['SHOW_Y']<>""){?><div class="dhx_cal_tab" name="year_tab" title="มุมมองรายปี" style="right:10px;"></div><? }//if ?>
		</div>
		<div class="dhx_cal_header">
		</div>
		<div class="dhx_cal_data">
		</div>		
	</div>
</body>