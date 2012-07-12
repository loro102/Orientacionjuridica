<?php 
session_start();
	$_SESSION['s_user_id'] = '';
	unset($_SESSION['s_user_id']);
	session_unregister('s_user_id');
	session_destroy();
?>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv='refresh' content='0;URL=./'>
</head><body>
<br>
<span style="color:#F93">
Logging out ....</span>
</body></html>