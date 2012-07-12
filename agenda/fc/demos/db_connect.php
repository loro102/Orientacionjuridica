<?php
$host = 'localhost';
$db_user = 'andrei';
$db_pass = 'password';
$database_name = 'calendar';
$database_table = 'calendar_list';

$connect = mysql_connect ($host,$db_user,$db_pass);
$select = mysql_select_db($database_name,$connect); 
ini_set('display_errors', 1);
?>