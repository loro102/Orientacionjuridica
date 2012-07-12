// JavaScript Document
	window.dhx_globalImgPath = "./dhtmlx/imgs/";
	var popUp = new Array(); // Name, Title on popup, width, hight, file
		popUp[0] = ["support","System Troubleshoot Report",320,250,"frame/support.php"];
		popUp[1] = ["settings","Calendar Settings",420,450,"frame/system_settings.php"];
		popUp[2] = ["cal_settings","_",350,330,"_"]; // calendar settings popup
		popUp[3] = ["seach_events","Event Search: ",540,290,"frame/event/events_search.php?q="]; // search
		popUp[4] = ["whatnew","What's new?",350,600,"help/new.php"];
		
	/////// Create Popup
	function showPop(id){
		if(!dhxWins.isWindow(popUp[id][0])){
			popup1 = dhxWins.createWindow(popUp[id][0], 20, 30, popUp[id][2], popUp[id][3]);
			popup1.setText(popUp[id][1]);
			popup1.denyResize();
			dhxWins.window(popUp[id][0]).center();
			popup1.button("minmax1").hide();
			popup1.button("park").hide();
			popup1.keepInViewport(true);
			popup1.attachURL(popUp[id][4]);
		}
	}
	
	function doSearch(){
		stxt = document.getElementById("search_text").value;
		if(stxt==""){ alert("Invalid search - Please type your query."); document.getElementById("search_text").focus(); return false;}
		if(!dhxWins.isWindow("seach_events")){
			popup1 = dhxWins.createWindow(popUp[3][0], 20, 30, popUp[3][2], popUp[3][3]);
			popup1.setText(popUp[3][1]+"<i>'"+stxt+"</i>'");
			dhxWins.window("seach_events").center();
			grsearch = popup1.attachGrid();
			grsearch.setImagePath("./component/dhtmlx/imgs/");
			grsearch.loadXML(popUp[3][4] + stxt);
		}else{
			popup1.setText(popUp[3][1]+"<i>'"+stxt+"</i>'");
			grsearch.loadXML(popUp[3][4] + stxt);
		}
	}
	
	function showInternalList(){
		if(!dhxWins.isWindow("internal_list")){
			popup1 = dhxWins.createWindow("internal_list", 20, 30, 540, 290);
			popup1.setText("Internal Calendar");
			dhxWins.window("internal_list").center();
			grlist = popup1.attachGrid();
			grlist.setImagePath("./component/dhtmlx/imgs/");
			grlist.loadXML("share/shared_internal.php");
			grlist.attachEvent("onRowSelect", function(id,ind){
				popup1.close();
				calendarSettings(2,'add',id,0,'int');
			});
		}else
			grlist.loadXML("share/shared_internal.php");
	}
	
	/////// Show calendar settings (private/internal/external)
	function calendarSettings(tableid,cmd,id,rowIndex,typesource){
		if(tableid==1){
			popUp[2][1] = "Personal calendar configuration"
			popUp[2][4] = "frame/calendar_settings.php?cmd="+cmd+"&id="+id+"&rowIndex="+rowIndex
		}
		else if(tableid==2 && typesource=="ext"){
			popUp[2][1] = "Other calendar configuration"
			popUp[2][4] = "frame/shared_settings.php?cmd="+cmd+"&id="+id+"&rowIndex="+rowIndex;
		}
		else if(tableid==2 && typesource=="int"){
			popUp[2][1] = "Other calendar configuration"
			popUp[2][4] = "frame/internal_settings.php?cid="+id+"&rowIndex="+rowIndex;
		}
		showPop(2);
	}

	var dhxLayout, dhxTabbar, ifr, mCal, dhxWins, popup1;
	function doOnLoad() {
		dhxLayout = new dhtmlXLayoutObject(document.body, "3w");
		//dhxLayout.progressOn();
		dhxLayout.attachEvent("onExpand", doOnExpand);
		dhxLayout.cont.obj._offsetTop = 50; // top margin
		dhxLayout.cont.obj._offsetHeight = -75; // bottom margin
		dhxLayout.setSizes();
		dhxLayout.cells("a").hideHeader();
		dhxLayout.cells("b").hideHeader();
		dhxLayout.cells("c").setText("Features");
		dhxLayout.cells("a").setWidth(180);
		dhxLayout.cells("c").setWidth(170);
		dhxLayout.cells("c").collapse();
		dhxLayout.cells("a").fixSize(true, true);
		dhxLayout.cells("c").fixSize(false, true);
		dhxLayout.setAutoSize("b", "a;b;c");
		dhxLayout.setEffect("resize", true);
		dhxLayout.setCollapsedText("a", "<img src='../common/image/collapedTextLeft.gif' border='0'>");
		
		// On Calendar View
		dhxLayout.cells("b").attachURL("frame/render.php");
		ifr = dhxLayout.cells("b")._frame;
		
		/// Calendar Manages				
		dhxLayout.cells("a").attachObject("left_view");
		
		dhtmlx.skin = "dhx_skyblue";
		dhtmlx.image_path = "component/dhtmlx/imgs/";
		mCal = new dhtmlxCalendarObject('calendarToggle', false, {
			isYearEditable: true,
			isMonthEditable: true
		});
		mCal.attachEvent("onClick", mSelectDate);
		mCal.attachEvent("onChangeMonth", mSelectMonth);
		mCal.draw();
		mCal.setSkin("dhx_blue");

	   
		dhxAccord = new dhtmlXAccordion("cal_panel");
		dhxAccord.enableMultiMode();
		
		dhxAccord.addItem("c_panel1", "My calendars");
		dhxAccord.addItem("c_panel2", "Other calendars");	
		dhxAccord.cells("c_panel1").attachObject('div_cal_panel1');
		dhxAccord.cells("c_panel2").attachObject('div_cal_panel2');
		//dhxAccord.cells("c_panel2").close();
		dhxAccord.cells("c_panel1").setHeight((calendar_number*24)+60);
		dhxAccord.cells("c_panel2").setHeight((csharing_number*24)+60);
		
		/// Popup Windows
		dhxWins = new dhtmlXWindows();
		dhxWins.enableAutoViewport(true);
		dhxWins.setImagePath("component/dhtmlx/imgs/");
		//dhxWins.attachEvent("onClose", function(win){dhxWins.unload();});

	}
	
	function doOnExpand(itemId) {
		dhxLayout.cells('a').hideHeader();
	}
	
	
	function mSelectDate(date) {
		ifr.contentWindow.main_setCurrentView(date,'day');
		return true;
	}
	
	function mSelectMonth(curMonth,prevMonth,curDate) {
		ifr.contentWindow.main_setCurrentView(curDate,'month');
		return true;
	}
	

	
	function setCalPanel(tableid,calid,name,color,typesource){
		(tableid==1)? calendar_number +=1 : csharing_number +=1;
		myCalendars[myCalendars.length] = name;
		myCalendarID[myCalendars.length] =calid;
		myCalendarColor[myCalendarColor.length] = color;
		var numberrow = (tableid==1)? calendar_number : csharing_number;
		dhxAccord.cells("c_panel"+tableid).setHeight((numberrow*24)+60);
		var x=document.getElementById("table_cal_panel"+tableid).insertRow(0);
		var y=x.insertCell(0);
		y.bgColor = "#"+color;
		y.innerHTML = name;
		y=x.insertCell(1);
		y.innerHTML = '<a href="javascript:void(0);" onclick="calendarSettings('+tableid+',\'modify\','+calid+',this.parentNode.parentNode.rowIndex,\''+typesource+'\');"><img src="../common/icons/note_edit.png"  border="0" title="'+name+' Settings"/></a>';
window.location.reload(); /////////////////////////////////// temporaly fixed bug
		return true;	
	}
	
	function editCalPanel(tableid,name,color,rowIndex,isRefresh,showCal){
		try{
			if(showCal){
				document.getElementById("table_cal_panel"+tableid).rows[rowIndex].cells[0].style.backgroundColor = "#"+color;
				document.getElementById("table_cal_panel"+tableid).rows[rowIndex].cells[0].style.color = "#FFFFFF";
			}else{
				document.getElementById("table_cal_panel"+tableid).rows[rowIndex].cells[0].style.color = "#"+color;
				document.getElementById("table_cal_panel"+tableid).rows[rowIndex].cells[0].style.backgroundColor = "#FFFFFF";
			}
			document.getElementById("table_cal_panel"+tableid).rows[rowIndex].cells[0].innerHTML = name;
			myCalendarColor[rowIndex] = color;
			myCalendars[rowIndex] = name;
//			if(isRefresh) ifr.contentWindow.main_reload();
window.location.reload(); /////////////////////////////////// temporaly fixed bug
		}catch(err){debugReload();}
	}
	
	function delCalPanel(tableid,rowIndex){
		(tableid==1)? calendar_number -=1 : csharing_number -=1;
		myCalendars.splice(rowIndex,1);
		myCalendarID.splice(rowIndex,1);
		myCalendarColor.splice(rowIndex,1);
		var numberrow = (tableid==1)? calendar_number : csharing_number;
		dhxAccord.cells("c_panel"+tableid).setHeight((numberrow*24)+60);
		document.getElementById("table_cal_panel"+tableid).deleteRow(rowIndex);
		//ifr.contentWindow.main_reload();
window.location.reload(); /////////////////////////////////// temporaly fixed bug
		return true;	
	}

function cEvent() {
	var event;
	ifr.contentWindow.scheduler.addEventNow(null,null,event);
}

function hideQuickAdd() {
  document.getElementById("divQuickAdd").style.visibility='hidden';
  document.getElementById("quickaddtext").value="";
}

function showQuickAdd() {
	document.getElementById("divQuickAdd").style.visibility='visible';
	document.getElementById("quickaddtext").focus();
}

function placeIt() {
	var y1 = 280;
  if (document.all) {document.all["divQuickAdd"].style.top = document.documentElement.scrollTop + (document.documentElement.clientHeight - (document.documentElement.clientHeight-y1)) + "px";}
  else{document.getElementById("divQuickAdd").style.top = window.pageYOffset + (window.innerHeight - (window.innerHeight-y1)) + "px";}
}
  
function quickAdd(){
	if(document.getElementById("quickaddtext").value!=""){
		var data = reg2Text(document.getElementById("quickaddtext").value);
		ifr.contentWindow.scheduler.addEvent({
			start_date: data[1]+" "+data[2],
			end_date: data[1]+" "+data[3],
			text:data[0],
			color: myCalendarColor[0]
		});
	}
	hideQuickAdd();
}
function focusQtxt(){document.getElementById("quickaddtext").focus();}
  

function reg2Text(text) {
	var today = new Date();
	var date,timeS,timeE,fmmyyyy =(today.getMonth()+1)+"-"+today.getFullYear(),  flag=false;
	var arrWords = text.split(/\s+/);
	
	for(var x = 0; x < arrWords.length; x++) {
		if(arrWords[x].match("พรุ่งนี้")){ date = (today.getDate()+1)+"-"+fmmyyyy; flag=true}
		else if(arrWords[x].match("เมื่อวาน")){ date = (today.getDate()-1)+"-"+fmmyyyy; flag=true}
		else if(arrWords[x].match("tomorrow")){ date = (today.getDate()+1)+"-"+fmmyyyy; flag=true}
		else if(arrWords[x].match("yesterday")){ date = (today.getDate()-1)+"-"+fmmyyyy; flag=true}
		else if(arrWords[x].match(/^\d{2}\/\d{2}\/\d{4}$/)){ date=convertDate(arrWords[x]); flag=true} //dd/mm/yyyy
		else if(arrWords[x].match(/^\d{2}\-\d{2}\-\d{4}$/)){ date=arrWords[x]; flag=true} // dd-mm-yyyy
		else if(arrWords[x].match(/^\d{1,2}\:\d{1,2}$/)){ timeS=arrWords[x]; timeE=arrWords[x]; flag=true}// hh:mm
		//else if(arrWords[x].match( /^\d{1,2}(:\d{1,2})([ap]m)?$/)) { alert(x); flag=true} // hh:mmpm
		else flag=false;
		if(flag) text=text.replace(arrWords[x],'');
	}
	text = trimStr(text);
	
	if(!date) date = today.getDate()+"-"+fmmyyyy;
	if(!timeS) timeS = today.getHours()+":00";
	if(!timeE) timeE = (today.getHours()+1)+":50";
	// Round the minute
		timeS = roundTime(timeS,0);
		timeE = roundTime(timeE,5);
	return new Array(text,date,timeS,timeE);
}

function roundTime(strT,plus){
	var arrT = strT.split(":",2);
	arrT[1] = (Math.round(parseFloat(arrT[1]) / 10) * 10) + plus;
	return arrT[0] + ":" + arrT[1].toString();
}

function trimStr (str) {
	var	str = str.replace(/^\s\s*/, ''),
		ws = /\s/,
		i = str.length;
	while (ws.test(str.charAt(--i)));
	return str.slice(0, i + 1);
}

function convertDate(d) { // dd/mm/yyyy  --> dd-mm-yyyy
		var nd = d.split(/\//);
		return nd[0]+"-"+nd[1]+"-"+nd[2];
}

function showOffLoad() { 
	dhxLayout.progressOff();
}

function showOnLoad() { 
	dhxLayout.progressOn();
}

function checkServer() { 
	try{
		var loader = dhtmlxAjax.postSync("./frame/chkserver.php"," cid="+getCookie("PHPSESSID"));
	    outputResponse(loader);
		setTimeout("checkServer()",30000);
	}catch(err){
		networkFail();
	}
}

function outputResponse(loader) {
    if (loader.xmlDoc.responseXML != null)
		loader.doSerialization();
	else
    	networkFail();
}

function getCookie(c_name){
if (document.cookie.length>0){
  c_start=document.cookie.indexOf(c_name + "=");
  if (c_start!=-1){
    c_start=c_start + c_name.length+1;
    c_end=document.cookie.indexOf(";",c_start);
    if (c_end==-1) c_end=document.cookie.length;
    return unescape(document.cookie.substring(c_start,c_end));
    }
  } return "";
}

function networkFail(){
	ifr.contentWindow.scheduler.clearAll();
	dhxLayout.progressOn();
	dhxLayout.cells('a').collapse();
	dhxLayout.cells("b").detachObject();
	document.getElementById("buttonSearch").disabled = true;
	document.getElementById("top_menu").innerHTML = "Oops, we couldn't connect to the network, please try again in a few minutes <a href='./'>Refresh</a>";
}

function debugReload(){
	window.location.reload();
}

function confirmDelete(url){
		if (window.confirm("Do you want to delete this data?")==true){
			window.location.href =url;
		}
	}