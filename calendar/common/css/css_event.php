<?php
ob_start();
include("config_color.php");
header("Content-type: text/css"); 

for($i=0;$i<count($color);$i++){
	echo "/* ".($i+1).": ".$color_name[$i]."*/\r\n";
	echo ".dhx_cal_event.c".$color[$i]." div{background-color:#".$color[$i]."; color:white;}\n";
	echo ".dhx_cal_event_line.c".$color[$i]."{background-color:#".$color[$i]."; color:white;}\n";
	echo ".dhx_cal_event_clear.c".$color[$i]."{background-color:#".$color[$i]."; color:white;}\r\n";
}
ob_flush();
?>