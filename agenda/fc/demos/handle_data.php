<?php
include_once('db_connect.php');

$start = $_POST['start'];
$end = $_POST['end'];

$query = mysql_query("INSERT INTO calendar_list (start, end) VALUES('".$start."', '".$end."' ) ") 
or die(mysql_error());  

$query = mysql_query("INSERT INTO calendar_list (start, end) VALUES('".$start."', '".$end."' ) ") 
or die(mysql_error());  

mysql_close($connect);
?>