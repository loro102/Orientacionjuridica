<?php
function goAlert($msg="",$targetURL="",$chkexit=0){
	if($targetURL<>"") echo "<html><head>";
	if($msg<>"") echo '<script language=javascript>alert("'.$msg.'")</script>';
	if($targetURL=="[BACK]") echo '<script language=javascript>history.go(-1);</script>';
	else if($targetURL=="[CLOSE]") echo '<script language=javascript>window.close();</script>';
	else if($targetURL<>""){ 
		echo"<meta http-equiv='refresh' content='0;URL=".BASE_URL.$targetURL."'>"; 
	 	echo "</head><body><span style='color:#F93'>Please wait a second....</span></body></body></html>";
	}
	if($chkexit==0) exit;
}

function chkLogin($user_type=0, $rediredct=true,  $url=""){
	if(!session_is_registered("s_user_id") || $_SESSION['s_user_id']==""){
		if( $_SESSION['s_user_type'] < $user_type ) goAlert('',"/?er=5");
		if($rediredct==true) {
			if($url!="") $_SESSION['redirect_page'] = $url;
			goAlert('',"/?er=4");
		}
		return false;
	}
	return true;
}

function titleTags($title, $space='-'){
	$targetChars=array(',','^','+','-','<', '>', '\\', '/', '=', ' '); 
	return str_replace($targetChars,$space,$title);
}

function message(){
	global $err, $msg;	
	if($err<>"") $re = "<div class='errorbox'>".$err."</div>";
	else if($_SESSION['err']!="") {$re =  "<div class='errorbox'>".$_SESSION['err']."</div>";$_SESSION['err']='';}
	if($msg<>"") $re = "<div class='msgbox'>".$msg."</div>";
	else if($_SESSION['msg']!="") {$re = "<div class='msgbox'>".$_SESSION['msg']."</div>"; $_SESSION['msg']='';}	
	return $re;
}

function chkValueRow($v,$file=NULL){
/// for check valye which query id is 0 or NULL
	if($v==0 || $v==NULL){
		if($file==NULL) $file=$_SERVER["HTTP_REFERER"];
		goAlert("ไม่พบข้อมูล!",$file);
	}
}

function UploadPic($newname,$path='thumb/',$width_fit=400, $height_fit=0){
	global $files;
	if($_FILES['files']['type']=='image/pjpeg' || 
	$_FILES['files']['type']=='image/jpeg'||
	$_FILES['files']['type']=='image/jpg' || $_FILES['files']['type']=='image/gif'){
		$size=GetimageSize($_FILES['files']['tmp_name']);
		$newname = $newname.".jpg";
			// ตรวจสอบขนาด ว่าต้องย่อไหม
		if($width_fit<$size[0]){ 
			if($height_fit==0) $height=round($width_fit*$size[1]/$size[0]); else $height = $height_fit; // ถ้าไม่กำหนดขนาดความสูงจะใช้สัดส่วนเดิม
			if($_FILES['files']['type']=='image/pjpeg' || $_FILES['files']['type']=='image/jpeg' || $_FILES['files']['type']=='image/jpg'){  // ตรวจสอบว่าเป็นประเภท jpeg
				$images_orig = ImageCreateFromJPEG($_FILES['files']['tmp_name']);
				$images_fin = ImageCreateTrueColor($width_fit, $height); 
				imageCopyResized($images_fin, $images_orig, 0, 0, 0, 0,$width_fit+1, $height+1, imagesx($images_orig), imagesy($images_orig)); 
				ImageJPEG($images_fin,$path.$newname); 
			}		
			elseif($_FILES['files']['type']=='image/gif'){  // ตรวจสอบว่าเป็นประเภท gif
				$images_orig = ImageCreateFromGIF($_FILES['files']['tmp_name']);
				$images_fin = ImageCreateTrueColor($width_fit, $height); 
				imageCopyResized($images_fin, $images_orig, 0, 0, 0, 0,$width_fit+1, $height+1, imagesx($images_orig), imagesy($images_orig)); 
				ImageGIF($images_fin,$path.$newname); 
			}
			ImageDestroy($images_orig);
			ImageDestroy($images_fin); 
		}
		else // ถ้าขนาดไม่ต้องย่อให้คัดลอกได้ทันที
		copy($_FILES['files']['tmp_name'], $path.$newname);
	}
}

function getDataFiledByID($id,$table,$filedID,$want){
	/// สำหรับดึงข้อมูลเฉพาะ ID ที่ต้องการเท่านั้นในตารางที่กำหนด
	$mybase = new mysql_database();
	$mybase->connect();
	$mybase->use_fields = $want;
	$mybase->tablename = $table;
	$data=$mybase->select("WHERE ".$filedID." = '".$id."' ORDER BY ".$filedID);
	$mybase->close();
	return $data["$want"];
}

///////////////////// FUNCTION PAGING /////////////////////////////////
function calLimitPage($maxRows = 30,$page = 0){
	global $totalPages, $query_limit, $queryString, $page, $totalrows;
	if (isset($_GET['page'])) $page = $_GET['page'];
	$startRow = $page * $maxRows;
	$query_limit = " LIMIT ".$startRow.",".$maxRows;
	
	if (isset($_GET['totalrows'])) $totalrows = $_GET['totalrows']; 
	$totalPages = ceil($totalrows/$maxRows)-1;
	
	/////////// Make a new query string /////////////
	$queryString = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
	  $params = explode("&", $_SERVER['QUERY_STRING']);
	  $newParams = array();
	  foreach ($params as $param) {
		if(stristr($param, "page") == false && stristr($param, "totalrows") == false) 
			array_push($newParams, $param);
	  }
	  if(count($newParams) != 0) $queryString = "&" . htmlentities(implode("&", $newParams));
	}
	$queryString = sprintf("&totalrows=%d%s", $totalrows, $queryString);
	return $query.$query_limit;
}

function showTableSelect(){ 
	global $totalPages, $queryString, $totalrows, $page;
	$currentPage = $_SERVER["PHP_SELF"];
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="50%">';
	if($page!=0){ 
		echo '<a href="'; 
		printf("%s?page=%d%s", $currentPage, 0, $queryString);
		echo '">&lt;&lt; First page</a> &nbsp;&nbsp;<a href="';
		printf("%s?page=%d%s", $currentPage, max(0, $page-1), $queryString);
		echo '">&lt; Previous page</a>';
	}
	echo '</td><td><div align="right">'; 
	if($page!=$totalPages && $totalPages<>-1){
		echo '<a href="';
		printf("%s?page=%d%s", $currentPage, min($totalPages, $page + 1), $queryString);
		echo'">Next page &gt;</a>&nbsp;&nbsp;<a href="';
		printf("%s?page=%d%s", $currentPage, $totalPages, $queryString);
		echo '">Last page &gt;&gt;</a>';
	}
	echo '</div></td></tr></table>';
}


function check_field_exists($fvalue,$field,$table){
        global $base;
        $sql    = "SELECT count(*) AS cnt FROM " .$table. " WHERE " .$field . " = '" .mysql_real_escape_string($fvalue). "'";
		$base->execute($sql);
		$res = $base->getrows(1);
       if ( $res['cnt'] > 0 ) return 1;
        return 0;
}

function f_date($string, $format = '%e %b %yTH - %H:%M น.'){ // Date Format
	 if(empty($string)) $timestamp = time();
     elseif (preg_match('/^\d{14}$/', $string)) 
        $timestamp = mktime(substr($string, 8, 2),substr($string, 10, 2),substr($string, 12, 2),
                       substr($string, 4, 2),substr($string, 6, 2),substr($string, 0, 4));
    elseif (is_numeric($string)) $timestamp = (int)$string;
	else{
        $timestamp = strtotime($string);
        if ($timestamp == -1 || $time === false) $timestamp = time();
    }
	$format = str_replace( '%yTH', (date('Y', $timestamp)+543), $format);
    if (DIRECTORY_SEPARATOR == '\\') {
        $_win_from = array('%D',       '%h', '%n', '%r',          '%R',    '%t', '%T');
        $_win_to   = array('%m/%d/%y', '%b', "\n", '%I:%M:%S %p', '%H:%M', "\t", '%H:%M:%S');
        if (strpos($format, '%e') !== false) {
            $_win_from[] = '%e';
            $_win_to[]   = sprintf('%\' 2d', date('j', $timestamp));
        }
        if (strpos($format, '%l') !== false) {
            $_win_from[] = '%l';
            $_win_to[]   = sprintf('%\' 2d', date('h', $timestamp));
        }

        $format = str_replace($_win_from, $_win_to, $format);
    }
    return strftime($format, $timestamp);
}

function schkbox($v,$chk=1){ // set checked to checkbox
	if($v==$chk) return " checked='checked' "; 
}

// Encrypting
function encrypt($string) {
	global $keyen;
    $iv = mcrypt_create_iv(16);
    return substr(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $keyen, $string, MCRYPT_MODE_ECB, $iv)),0,-2);
}

// Decrypting 
function decrypt($string) {
	global $keyen;
    $iv = mcrypt_create_iv(16);
return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $keyen, base64_decode($string), MCRYPT_MODE_ECB, $iv));
}

function getRealIpAddr(){
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    else
      $ip=$_SERVER['REMOTE_ADDR'];
    return $ip;
}

function setDateTZ($d){ 
	$date = ($d['year']>0) ? $d['year'].'-'.$d['month'].'-'.$d['day'] : null;
	$time = ($d['hour']>0) ? ' '.$d['hour'].':'.$d['min'].':'.$d['sec'] : ' 00:00:00';
	$UTC = strtotime($date.$time) + (TIMEZONE * 3600);
	return date('Y-m-d H:i:s',$UTC);
}

function errorlog($msg) {
	$filename = "debug.txt";
	if (!$handle = fopen($filename, 'a')) exit;
	if (is_writable($filename)) fwrite($handle, $msg . "\n");
	fclose($handle);
}

function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy';
	$consonants = 'bdghjmnpqrstvz';
	if ($strength & 1) $consonants .= 'BDGHJLMNPQRSTVWXZ';
	if ($strength & 2) $vowels .= "AEUY";
	if ($strength & 4) $consonants .= '23456789';
	if ($strength & 8) $consonants .= '@#$%';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}

//MAIL FUNCTIION
function mailing( $to, $name, $from, $subj, $body, $bcc = '' )
{
	require_once dirname(dirname(__FILE__)).'/calendar/component/phpmailer/class.phpmailer.php';
	
	$mail 			= new PHPMailer();
	$mail->IsMail();

	$mail->IsSMTP();
	$mail->Host 		= 'xxx.xxx.xxx.xxx';
	$mail->Port		= '25';
//		$mail->SMTPSecure	= $config['smtp_prefix'];

	
	$mail->IsHTML(true);
	$mail->From     	= $from;
	$mail->FromName 	= $name;
	// Return-Path
	$mail->Sender		= $from;
	$mail->AddAddress($to);
	if ( $bcc ) {
		$mail->AddAddress($bcc);
    }
	$mail->AddReplyTo( $from, $name);
	$mail->Subject 		= $subj;
	$mail->AltBody		= $body;
	$mail->Body		= nl2br($body);		
	if ( !$mail->Send() ) {
        return false;
    }
    
    return true;
}

function activateEmail($user, $pass, $email, $key){
	global $settings;
      $message = "Your new account on $settings[SYSTEM_NAME]

Your $settings[SYSTEM_NAME] registration details are:
Your username: $user
Your password: $pass
Registered IP Address: $_SERVER[REMOTE_ADDR]
Registered Time: ".date('r')."

========================================================
Please click on the link below to ACTIVATE your account:

".BASE_URL."/verifyemail/?verify=$key&email=$email&user=$user

========================================================

If you are not able to open this link.
Please go to: ".BASE_URL."/verifyemail and verify manually using the details below:
Username	: $uer
Email	: $email
Verify code	: $key

Thank you,
$settings[SYSTEM_NAME] support
$settings[SYSTEM_EMAIL]";
					
     return $message = wordwrap($message, 70);
}

function userSearchFilter($key){
	$keyword = mysql_real_escape_string($key);
	return ' WHERE user_login LIKE \'%'.$keyword.'%\' OR user_email LIKE \'%'.$keyword.'%\' OR display_name LIKE \'%'.$keyword.'%\' ';
}

function defaultSettings(){
	global $settings;
	$settings['DEF_VIEW'] = "month";
	$settings['START_ON'] = "false";
	$settings['FRIST_HOUR'] = "6";
	$settings['LAST_HOUR'] = "21";
	$settings['TIME_STEP'] = "5";
	$settings['STEP_WD'] = "15";
	$settings['SHOW_Y'] = "on";
	$settings['Y_YSCALE'] = "3";
	$settings['X_YSCALE'] = "4";
}

function selectUserSettings($id){
	global $settings;
	$base = new mysql_database();
	$base->tablename = "settings";
	$stt=$base->select("WHERE user_id ='".$id."' ");
	if($base->numrow()>0)
		do{ 
			$settings[$stt['settings_id']] = $stt['value'];
		} while ($stt=$base->isNext());
	else defaultSettings(); // Set Default
}
?>