<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title></title>

<script src="../../codebase/dhtmlxscheduler.js" type="text/javascript" charset="utf-8"></script>
<script src="../../codebase/ext/dhtmlxscheduler_readonly.js" type="text/javascript" charset="utf-8"></script>

	<link rel="stylesheet" href="../../codebase/dhtmlxscheduler.css" type="text/css" title="no title" charset="utf-8">
    <link rel="stylesheet" href="../../codebase/ext/dhtmlxscheduler_ext.css" type="text/css" title="no title" charset="utf-8">
    <link rel="stylesheet" href="../../common/css/css_event.php" type="text/css" media="screen">
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
		scheduler.init('scheduler_here',null,"month");
		scheduler.config.readonly_form = true;

		//block all modifications
		scheduler.attachEvent("onBeforeDrag",function(){return false;})
		scheduler.attachEvent("onClick",function(){return false;})
		scheduler.config.details_on_dblclick = true;
		scheduler.config.dblclick_create = false;
		scheduler.load("../../event/events_share.php?uid="+scheduler.uid()+"&id=<?=trim($_GET['id'])?>");
		
		scheduler.templates.event_class=function(start,end,event){
		   return "c"+event.color;
		}

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
			<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
			<div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
			<div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
		</div>
		<div class="dhx_cal_header">
		</div>
		<div class="dhx_cal_data">
		</div>		
	</div>
</body>