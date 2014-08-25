<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION["session_company_name"]; ?></title>

<style type="text/css">
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
  }
  .style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
  }
</style>

</head>

<body style="background-color:#ffffff; margin-top:0px;">

<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">

<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
    <h1 class="style4">(THCAP) คีย์เงินโอนผ่านระบบ</h1>
</div>

<div id="login" style="height:50px; width:900px; text-align:left; margin-left:auto; margin-right:auto;">
    <div class="style3" style="background-color:#8B7765; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?></div>
    <div class="style3" style="background-color:#CDAF95; width:auto; height:20px; padding-left:10px;">ใส่รายการเงินโอน</div>
    <div><?php include("frm_KeySystemInsert.php"); ?></div>
</div>

</div>

</body>
</html>
