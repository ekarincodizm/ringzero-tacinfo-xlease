<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
$id_user2 = $_SESSION["av_iduser"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>

<meta http-equiv="Expires" content="Fri, Jan 01 1900 00:00:00 GMT">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="content-language" content="en">
<meta name="author" content="">
<meta http-equiv="Reply-to" content="@.com">
<meta name="generator" content="PhpED 5.2">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="creation-date" content="09/20/2007">
<meta name="revisit-after" content="15 days">

<title>Post placeholder</title>

<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/table.css">
<link rel="stylesheet" type="text/css" href="js/autocomplete/jquery.autocomplete.css">


<script type="text/javascript" src="js/jquery.js"></script> 
<script type="text/javascript" src="js/json2.js"></script>
<script type="text/javascript" src="js/autocomplete/jquery.autocomplete.js"></script> 
<script type="text/javascript" src="js/maskinput/jquery.maskedinput.js"></script>
<script type="text/javascript" src="js/mousewheel/jquery.mousewheel.js"></script>


<link rel="stylesheet" type="text/css" href="js/datepick/redmond.datepick.css">
<script type="text/javascript" src="js/datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="js/datepick/jquery.datepick-th.js"></script>

</head>

<body>

<div style="width:800px; margin-left:auto; margin-right:auto; border:#DDDDDD 1px dashed; padding:5px;">

  <?php
    include("postpayment/post.php");
  ?>

</div>
<div style="width:800px;height:auto; margin-left:auto; margin-right:auto; border:#DDDDDD 1px dashed; padding:5px;">

  <?php
	$typeshow = 'frm_postav';
    include('postpay/postlog_show.php');
  ?>

</div>  
</body>

</html>

