<?php
	session_start();
	include("../../config/config.php");
	$contentURL=$_GET["content"];
	if($contentURL=="")
	{
		$contentURL="index_content.php";
	}
	else
	{
		$contentURL.".php";
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>Untitled Document</title>
<link href="css/main.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" type="image/x-icon" href="icon/icon.ico">
<script type="text/javascript" src="script/jquery-1.7.2.min.js"></script>
<?php
echo"<script type=\"text/javascript\">
	$(document).ready(function(){
		$('#divshowheader').load(\"header.php\");
		$('#divshowcontent').load(\"$contentURL\");
		$('#divshowfooter').load(\"footer.php\");
	});
</script>";
?>
</head>

<body>
<div id="divcontrainer">
	<div align="center">
    	<div id="divshowheader"></div>
    </div>
</div>
<div id="divshowcontent"></div>
<div id="footercontrainer1">
    <div align="center">
        <div id="divshowfooter"></div>
    </div>
</div>
<?php
include("fix_menu.php");
?>
</body>
</html>