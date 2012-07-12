<?php
	@session_start();
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	ini_set('display_errors', 1);
	
	// Database connection
	define('BASS_HOST', 'localhost', TRUE);
	define('BASS_USER', 'root', TRUE);
	define('BASS_PASS', '', TRUE);
	define('BASS_BASENAME', 'calendar', TRUE);
	define('BASE_URL', 'http://'.$_SERVER['SERVER_NAME'].'/calendar', TRUE);
	define('TIMEZONE', 1); 
	
	define('CACHE_PATH', '/tmp/cache_');
	define('CACHE_TIME', 2);// how long to keep the cache files (hours)
?>