<?
include("function.php");
include("./component/mysql_module.php");
include ('config.php');

selectUserSettings(-1);

// Check the user id, if user has already logged in.
if(isset($_SESSION['s_user_id']) && $_SESSION['s_user_id'] > 0)goAlert('',"/render",0);

$base = new mysql_database();

////////////////////////// LOGIN ///////////////////////////////////
if (isset($_POST['action']) && $_POST['action'] == 'login') {
  $user_login = mysql_real_escape_string($_POST['user_login']);
  if($user_login == '') goAlert('',"/?er=2",0);
  $password = mysql_real_escape_string($_POST['password']);
  if($password == '') goAlert('',"/?er=3",0);
  		
	$base->tablename = "user";
	$data=$base->select(sprintf('WHERE user_login="%s" AND user_pass="%s" ', $user_login, md5($password)));

  if ($base->numrow>0) {
	// Check inavtive user
	if($data["user_status"]==0) goAlert('',"/?er=6",0);
    $_SESSION['s_user_id']  = $data["ID"];
	$_SESSION['s_username']  = $user_login;
	$_SESSION['s_user_email']  = $data["user_email"];
	$_SESSION['s_name']  = $data["display_name"];
	$_SESSION['s_user_type']  = $data["user_type"];
	// update last login
	$base->update("WHERE ID='".$data["ID"]."' LIMIT 1", 'user_lastlogin = now()' );
    header("Location: ./render" );
  }
  else goAlert('',"/?er=1",0);
}
////////////////////////// END OF LOGIN /////////////////////////


////////////////////////// SIGNUP ///////////////////////////////////
else if (isset($_POST['action']) && $_POST['action'] == 'signup' && $settings['SIGN_UP']=='on') {
  $user_login = substr(mysql_real_escape_string($_POST['sign_user_login']),0,60);
  $password = mysql_real_escape_string($_POST['sign_password']);
  $email = mysql_real_escape_string($_POST['email']);
  $captcha = mysql_real_escape_string($_POST['ct_captcha']);
  if(strlen($user_login) == 0 || strlen($password) == 0) goAlert('',"/?er=7",0);
  if (strlen($email) == 0 ||  !preg_match('/^(?:[\w\d]+\.?)+@(?:(?:[\w\d]\-?)+\.)+\w{2,4}$/i', $email)) goAlert('',"/?er=11",0);
  		
	$base->tablename = "user";
	// Check occupied username
	$base->select(sprintf('WHERE user_login="%s" ', $user_login));
	if ($base->numrow>0) goAlert('',"/?er=9",0);
	// Check occupied email
	$base->select(sprintf('WHERE user_email="%s" ', $email));
	if ($base->numrow>0) goAlert('',"/?er=0",0);
	
	// Only try to validate the captcha if the form has no errors
     require_once 'component/securimage/securimage.php';
     $securimage = new Securimage();
     if ($securimage->check($captcha) == false) goAlert('',"/?er=12",0); 
	 
	$key = generatePassword(25,7);
	
	// Insert User
	$result = $base->insert("user_login, user_pass, user_email, user_activation_key, user_status, display_name, user_registered, user_lastlogin", sprintf("'%s', '%s', '%s', '%s', '%s', 0, now(), '0000-00-00 00:00:00'", $user_login, md5($password), $email,	$akey, $user_login));

 	if($result) {
		$subject = $settings[SYSTEM_NAME].": activation user"; 
		$message = activateEmail($user_login, $password, $email, $key);
		mailing( $email, 'Autosending - No Reply', $user_login, $subject, $message );
		goAlert("The user have been created, please check your email to activate the account.","?success");
	}
	else goAlert('',"/?er=11",0);
}
////////////////////////// END OF SIGNUP /////////////////////////


////////////////////////// VERIFY EMAIL ///////////////////////////////////
if (isset($_GET['verify']) && $_GET['verify'] != '') {
	$user = mysql_real_escape_string($_GET['user']);
	if($user == '') goAlert('Invalid username',"/",0);
	$email = mysql_real_escape_string($_GET['email']);
	if($email == '') goAlert('Invalid email',"/",0);
	$key = mysql_real_escape_string($_GET['verify']);
  		
	$base->tablename = "user";
	$data=$base->select(sprintf('WHERE user_login="%s" AND user_email="%s" AND user_activation_key="%s" AND user_status = 0 LIMIT 1', $user, $email, $key));

  if ($base->numrow>0) {
	$base->update(sprintf('WHERE user_login="%s" AND user_email="%s" AND user_activation_key="%s" LIMIT 1 ', $user, $email, $key), 'user_lastlogin = now(), user_status=1' );
	goAlert('You have activated your account successful.',"/?user=".$user);
  }
  else goAlert('Sorry this is not valid information',"/?user=".$user);
}
////////////////////////// END OF VERIFYEMAIL /////////////////////////

// Get the list of announcement
$base->tablename = "announcement"; 
$base->use_fields = "*";
$no_ann = ($settings['NO_ANNOUNCE']!=0)?$settings['NO_ANNOUNCE']:2;
$ann=$base->select("WHERE status = '1' ORDER BY laste_modified DESC LIMIT 0,".$no_ann);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html> 
 <head>
  <title><?=$settings['SYSTEM_NAME']?> : ArTrix Calendar <?=$settings['SYSTEM_VER']?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta id="request-method" name="request-method" content="GET">
  <meta name="author" content="Blackboard">
  <meta name="copyright" content="">
  <meta name="keywords" content="Blackboard">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="-1">
  <link rel="stylesheet" type="text/css" href="common/css/login.css">
  <? if($settings['SIGN_UP']=='on'){ ?><script language="javascript" src="component/csspopup.js"></script><? } // if ?>

  </head>
  
  <body >
  <div id="loginPageContainer">
  <div id="loginPane">
    <div id="loginContainer">
<div class="clearfix loginBody">
        <div id="loginBox">
          


<form onsubmit="return validate_form();" method="POST" action="./" id="login" name='login'>

  <div id="loginFormFields" class="clearfix">
    <ul id="loginFormList" class="clearfix">
      <li class="clearfix">
        <label for="user_id">Username:</label>
        <input type="text" name="user_login" id="user_login" size="25" maxlength="50" tabindex="1"  value="<?=isset($_REQUEST['user'])?$_REQUEST['user']:null;?>"/>
      </li>
      <li class="clearfix">
        <label for="password">Password:</label>
        <input size="25" name="password" id="password" type="password" tabindex="2" />
      </li>
      <li class="clearfix">
          <span class="forgot"><a href="#" onclick="login_openForgotPassword('./password');return false;" >Forgotten Your Password?
                  <span class="hideoff">(new window)</span>
                </a></span>
        </li>
      <li class="clearfix">
      
		<a href="#" onclick="popup('popUpDiv')">Create an account</a>
        <input type="submit" value="Login" name="login" class="submit button-1" tabindex="3" />
      </li>
    </ul>
  </div>

  <input type="hidden" name="action" value="login" />
</form>
</div>
</div>
<div id="loginErrorMessage" <?=($_GET['er']>0) ? null : 'style="display:none;"';?>><img src="common/icons/warning_16.png" width="16" height="16" alt="Error"><span id="msg_er">
<? if($_GET['er']==1) echo 'You\'ve tried to log in with an incorrect username or password.';
	else if($_GET['er']==2) echo 'You\'ve not type your username, please try your username.';
	else if($_GET['er']==3) echo 'You\'ve not type  your password, please try your password..';
	else if($_GET['er']==4) echo 'You\'ve tried to access in restricted page, please log in the system.';
	else if($_GET['er']==5) echo 'You don\'t have permission to access the page.';
	else if($_GET['er']==6) echo 'Sorry, your user account is not active. Please contact the system administrator.';
	else if($_GET['er']==7) echo 'Please enter your username or password correctly';
	else if($_GET['er']==8) echo 'Invalid secure code, please try again.';
	else if($_GET['er']==9) echo 'This username is already taken.';
	else if($_GET['er']==10) echo 'This e-mail is already taken.';
	else if($_GET['er']==0) echo 'Unexpected error!';
	else if($_GET['er']==11) echo 'Email address entered is invalid';
	else if($_GET['er']==12) echo 'Incorrect security code entered';
?></span>
</div>
<script language="javascript">
function validate_form(){
	var username = document.getElementById('user_login').value;
	var password = document.getElementById('password').value;
	var msg1 = "", msg2 = "";
	if(username == ""){msg1 = ' username';}
	if(password == ""){msg2 = ' password';}
	if(msg1 =="" && msg2 =="") return true;
	else {
		if(msg1 !="" && msg2 != "") {msg_and = " and ";} else {msg_and = "";}
		showMsg(" Please type your " + msg1+msg_and+msg2+ " correctly.");
	}
	return false;
}

function showMsg(msg){
	document.getElementById('loginErrorMessage').style.display = 'block';
	document.getElementById('msg_er').innerHTML = msg;
}

<? if($settings['SIGN_UP']=='on'){ ?>
function validate_signup(){
	var username = document.getElementById('sign_user_login').value;
	var password = document.getElementById('sign_password').value;
	var email = document.getElementById('email').value;
	var ct_captcha = document.getElementById('ct_captcha').value;
	var msg = "";
	var atpos=email.indexOf("@");
	var dotpos=email.lastIndexOf(".");

	if(username == ""){msg = ' Please type your username';}
	else if(username.length < 6){msg = ' Enter a username at least 6 characters long';}
	else if(username.length > 60){msg = ' Your username is too long, must be not longer than 60 characters.';}
	else if(email == ""){msg = 'Please type your email';}
	else if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) msg = 'Not a valid e-mail address';
	else if(password == ""){msg = ' Please type your password';}
	else if(password.length < 4){msg = ' Enter a password at least 4 characters long';}
	else if(ct_captcha == ""){msg = ' Please type your secure code';}
	
	if(msg =="") return true;
	else {showMsg(msg);}
	return false;
}
<? } // if sign up is on ?>
</script>

<div id="loginAnnouncements">
  <h3>System Announcements</h3>
  <ul>
          <? do { ?>
			<li><strong><?=$ann['title']?></strong><em>
            (<?=$ann['laste_modified']?>)
          </em>
                <br />
                <div class="vtbegenerated"><?=$ann['detail']?></div>
      </li>
      <? } while ($ann=$base->isNext()); ?>
      </ul>
</div>
</div>

    <div class="bottomRound loginBack"></div>
    </div>

  <div id="copyright"><b><?=$settings['SYSTEM_NAME']?></b>, powered by ArTrix Calendar <?=$settings['SYSTEM_VER']?>.
</div>
</div>

<? if($settings['SIGN_UP']=='on'){ ?>
<div id="blanket" <?=($_GET['er']>7 && $_GET['er']<12) ? null : 'style="display:none;"';?>></div>
<div id="popUpDiv" <?=($_GET['er']>7 && $_GET['er']<12) ? null : 'style="display:none;"';?>>
	<form onsubmit="return validate_signup();" method="POST" action="./" id="login" name='login'>
    <ul id="loginFormList" class="clearfix">
    <h4>Create a <?=$settings['SYSTEM_NAME']?> Account</h4>
      <li class="clearfix">
        <label for="user_id">Username:</label>
        <input type="text" name="sign_user_login" id="sign_user_login" size="25" maxlength="50" tabindex="4" />
      </li>
      <li class="clearfix">
        <label for="email">Email:</label>
        <input size="25" name="email" id="email" type="text" tabindex="5" />
      </li>
      <li class="clearfix">
        <label for="password">Password:</label>
        <input size="25" name="sign_password" id="sign_password" type="password" tabindex="6" />
      </li>
      <li class="clearfix">
        <p>
    <img id="siimage" style="border: 1px solid #000; margin-right: 15px" src="component/securimage/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" align="left">
    <object type="application/x-shockwave-flash" data="component/securimage/securimage_play.swf?audio_file=component/securimage/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" height="32" width="32">
    <param name="movie" value="component/securimage/securimage_play.swf?audio_file=component/securimage/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000">
    </object>
    &nbsp;
    <a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = 'component/securimage/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="component/securimage/images/refresh.png" alt="Reload Image" onclick="this.blur()" align="bottom" border="0"></a><br />
     Confirmation code:
    <input type="text" name="ct_captcha" id="ct_captcha" size="12" maxlength="8" />
  </p>
      </li>
      <li class="clearfix">
        <input type="submit" value="Sign up" name="signup" class="submit button-1" tabindex="8" />
      </li>
    </ul>
    <input type="hidden" name="action" value="signup" />
    </form>
<a href="#" onclick="popup('popUpDiv')">Click here to close popup</a>
</div>
<? } // if sign up is on ?>

</body>
</html>

