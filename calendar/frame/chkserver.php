<?php
	session_start();
	header("Content-type:text/xml");
	echo "<?xml version='1.0' encoding='utf-8'?>";
	if(session_id()==$_POST["cid"])	echo "<status>OK</status>";
	else echo "<status>".$_POST["cid"]."</status>";
?>