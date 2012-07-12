<?
include("../function.php");
include("../component/mysql_module.php");
include ('../config.php');

$base = new mysql_database();

// Get the list of announcement
$base->tablename = "announcement"; 
$base->use_fields = "*";
$ann=$base->select("WHERE status = '1' ORDER BY laste_modified DESC LIMIT 0,2");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../common/css/style.css" type="text/css" media="screen" />
<title>What's new?</title>
<style type="text/css">
<!--
#apDiv1 {
	position:absolute;
	left:3px;
	top:22px;
	width:335px;
	height:512px;
	z-index:1;
}
-->
</style>
</head>

<body>
<div id="apDiv1">

          <? do { ?>
			<li><strong><?=$ann['title']?></strong><em>
            (<?=$ann['laste_modified']?>)
          </em>
                <br />
                <div class="vtbegenerated"><?=$ann['detail']?></div>
<hr />
      <? } while ($ann=$base->isNext()); ?>
</div>
<span class="header"><u>What's new in Calendar System</u></span><br />
</body>
</html>
