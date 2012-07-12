<?php
	@session_start();
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	ini_set('display_errors', 1);
	
	// Database connection
	define('BASS_HOST', 'VAR_BASS_HOST', TRUE);
	define('BASS_USER', 'VAR_BASS_USER', TRUE);
	define('BASS_PASS', 'VAR_BASS_PASS', TRUE);
	define('BASS_BASENAME', 'VAR_BASS_BASENAME', TRUE);
	define('BASE_URL', 'http://'.$_SERVER['SERVER_NAME'].'/calendar', TRUE);
	define('TIMEZONE', VAR_TIMEZONE); 
	
	define('CACHE_PATH', '/tmp/cache_');
	define('CACHE_TIME', 2);// how long to keep the cache files (hours)
?>