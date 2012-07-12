<?php
set_time_limit(0);

$output_messages=array();
$succeed_messages=array();


//test mysql connection
if( isset($_REQUEST['action']) )
{
	$host=$_REQUEST['mysql_host'];
	$database=$_REQUEST['mysql_database'];
	$user=$_REQUEST['mysql_username'];
	$passwd=$_REQUEST['mysql_password'];
	$newdatabase=$_REQUEST['cr_database'];
	if($host=='' || $database=='' || $user=='')
		$output_messages[] = 'Database parameters cannot be null';

	$link = @mysql_connect($host, $user, $passwd);
	if ($link){
		$succeed_messages[] = "Connected with MySQL server:$mysql_username@$mysql_host successfully";
		
		if($link && $newdatabase == 1){
			mysql_query("CREATE DATABASE `$database` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;");
		}else{
			$db_selected = mysql_select_db($database, $link);
			if ($db_selected)
				$succeed_messages[] = "Connected with MySQL database:$mysql_database successfully";
			else $output_messages[] = "Cannot use $mysql_database: " . mysql_error();
		}
		
	}else $output_messages[] = "Could not connect: " . mysql_error();
	
	
	
	
	if( 'Install' == $_REQUEST['action']){		
		$filename = "database.sql";
		if(!@file_exists($filename)) 
			$output_messages[] = 'The SQL file is not existence';
		else{
			$handle = @fopen($filename, 'r');
			while (!feof($handle)) {				
				$query .= fgets($handle, 4096);
				if (substr(rtrim($query), -1) == ';') {
					$line++;
					//echo '.['.$query.']<br>';
					$result = mysql_query($query);
					if(!$result)
						$output_messages[] = mysql_error()." : in line ".$line;
				 	$query = '';
				}
			} 
			@fclose($handle);
			$succeed_messages[] = "Congratulation!! You have installed the calendar system successful! <br /> Important thing to do, please remove install folder immediately.";
			
			$filename = "config_theme.php";
			if(!@file_exists($filename)) 
				$output_messages[] = $filename.' file does not exist in install folder';
			else{
				$str = file_get_contents($filename);
				$str = str_replace('VAR_BASS_HOST', $host, $str);
				$str = str_replace('VAR_BASS_USER', $user, $str);
				$str = str_replace('VAR_BASS_PASS', $passwd, $str);
				$str = str_replace('VAR_BASS_BASENAME', $database, $str);
				$str = str_replace('VAR_TIMEZONE', $_REQUEST['timezone'], $str);
				$cf = fopen("../config.php", 'w') or die("can't open file");
				fwrite($cf, trim($str));
				fclose($cf);
			}
		
		}
		
	}// install action

}



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>mysqldump.php version <?php echo $mysqldump_version; ?></title>
<style type="text/css">
form{margin:0;}
.dottedline{border-bottom:1px solid #333;}
.installheader{color:#FFF;font-size:24px;}
textarea{color:#00d;font-family:Arial;font-size:11px;border:1px;}
.red{color:red; font-weight:bold;}
.green{color:green;}
input{font-size:18px;}
</style>
</head>

<body>
<form action="" method="post">
<p><img src="../common/image/calendar-logo.png" alt="calendar system logo" width="250" height="44"><br>
  <h3>ArTrix Calendar Installation</h3>
</p>

<?php
if($output_messages){
	echo '<span class="red">ERROR:<pre>';
	foreach ($output_messages as $msg)
    	echo $msg."<br />";
	echo '</pre></span>';
}

else if($succeed_messages){
	echo '<span class="green">';
	foreach ($succeed_messages as $msg)
    	echo $msg."<br />";
	echo '</span>';
}
?>

<p><b>System check</b>:</p>
<table width="263" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td> PHP version &gt;= 4.3.0</td>
    <td><?php echo phpversion() < '4.3' ? '<span class="red">No</span>' : '<span class="green">Yes</span>';?></td>
  </tr>
  <tr>
    <td width="148">XML support</td>
    <td width="101"><?php echo extension_loaded('xml') ? '<span class="green">Available</span>' : '<span class="red">Unavailable</span>';?></td>
  </tr>
  <tr>
    <td>MySQL support</td>
    <td><?php echo function_exists( 'mysql_connect' ) ? '<span class="green">Available</span>' : '<span class="red">Unavailable</span>';?></td>
  </tr>
  <tr>
    <td> confign.php </td>
    <td>
    <?php
	if (@file_exists('../config.php') &&  @is_writable( '../config.php' )){
		echo '<span class="green">Writeable</span>';
	} else if (is_writable( '..' )) {
		echo '<span class="green">Writeable</span>';
	} else {
		echo '<span class="red">Unwriteable</span><br /><span class="small">You can still continue the install as the configuration will be displayed at the end, just copy & paste this and upload.</span>';
	} ?>
    </td>
  </tr>
  </table>
<p><br>
  <b>MySQL connection parameters</b>: </p>
<table border="0">
  <tr>
    <td>Host:</td>
    <td><input  name="mysql_host" value="<?php if(isset($_REQUEST['mysql_host'])) echo $_REQUEST['mysql_host']; else echo 'localhost';?>"  /></td>
  </tr>
  <tr>
    <td>Database:</td>
    <td><input  name="mysql_database" value="<? echo $_REQUEST['mysql_database']; ?>"  /></td>
  </tr>
  <tr>
    <td>Username:</td>
    <td><input  name="mysql_username" value="<? echo $_REQUEST['mysql_username']; ?>"  /></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input  type="password" name="mysql_password" value="<? echo $_REQUEST['mysql_password']; ?>"  /></td>
  </tr>
  <tr>
    <td>Create database: </td>
    <td><input name="cr_database" type="checkbox" id="cr_database" value="1" <? if($_REQUEST['cr_database']==1) echo ' checked';?>>
      <label for='cr_database'>if not exist</label></td>
  </tr>
</table>
<p>
  <input type="submit" name="action"  value="Test Connection">
  <br />
  <br>
</p>
<table width="512" border="0">
    <tr>
      <td width="79">TimeZone: </td>
      <td width="488">
      <select name="timezone" id="timezone">
          <option value="-12">(GMT -12:00) Eniwetok, Kwajalein</option>
          <option value="-11">(GMT -11:00) Midway Island, Samoa</option>
          <option value="-10">(GMT -10:00) Hawaii</option>
          <option value="-9">(GMT -9:00) Alaska</option>
          <option value="-8">(GMT -8:00) Pacific Time (US &amp; Canada)</option>
          <option value="-7">(GMT -7:00) Mountain Time (US &amp; Canada)</option>
          <option value="-6">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
          <option value="-5">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
          <option value="-4">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
          <option value="-3.5">(GMT -3:30) Newfoundland</option>
          <option value="-3">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
          <option value="-2">(GMT -2:00) Mid-Atlantic</option>
          <option value="-1">(GMT -1:00 hour) Azores, Cape Verde Islands</option>
          <option value="0">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
          <option value="1" selected>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
          <option value="2">(GMT +2:00) Kaliningrad, South Africa</option>
          <option value="3">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
          <option value="3.5">(GMT +3:30) Tehran</option>
          <option value="4">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
          <option value="4.5">(GMT +4:30) Kabul</option>
          <option value="5">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
          <option value="5.5">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
          <option value="5.75">(GMT +5:45) Kathmandu</option>
          <option value="6">(GMT +6:00) Almaty, Dhaka, Colombo</option>
          <option value="7">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
          <option value="8">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
          <option value="9">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
          <option value="9.5">(GMT +9:30) Adelaide, Darwin</option>
          <option value="10">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
          <option value="11">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
          <option value="12">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
	</select></td>
    </tr>
  </table>
  <br>
  <b>Directory and File Permissions</b>:
  <table width="265" border="0">

    <tr>
      <td width="51">/share </td>
      <td width="204"><?php if(is_writable( '../share' )) echo '<span class="green">Writeable</span>'; else echo '<span class="red">Unwriteable</span>';?></td>
    </tr>
    <tr>
      <td>/event</td>
      <td><?php if(is_writable( '../event' )) echo '<span class="green">Writeable</span>'; else echo '<span class="red">Unwriteable</span>';?></td>
    </tr>
  </table>
  <p>ArTrix Calendaris Free Software released under the GNU/GPL License.<br>
    <input name="acceptance" type="checkbox" id="acceptance" value="Yes"<? if($_REQUEST['acceptance']=="Yes") echo ' checked';?>>
  <label for='acceptance'>I read and accept the license.</label>
  <p>
    <input type="submit" name="action"  value="Install" >
    <br />
  </p>
</form>
</body>
</html>
