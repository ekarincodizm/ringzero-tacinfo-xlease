<?php
session_start();
include("../../config/config.php");	
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<link type="text/css" rel="stylesheet" href="act.css"></link>

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<style type="text/css">
    .mouseOut {
    background: #708090;
    color: #FFFAFA;
    }

    .mouseOver {
    background: #FFFAFA;
    color: #000000;
    }
</style>
   
<!-- InstanceEndEditable -->
<style type="text/css">
<!--
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

-->
</style>
<script type="text/javascript">
$(document).ready(function(){

    $("#securID").autocomplete({
        source: "s_secur.php",
        minLength:1
    });
});
</script>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;" onload="setfocus();">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
	</div>

	<!-- InstanceBeginEditable name="EditRegion3" -->
	<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
		<div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>
		<div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
		<div class="style5" style="width:auto; padding:10px;"><b>แก้ไขข้อมูลหลักทรัพย์</b></div>
		<form method="post" action="frm_Edit.php">
			เลขที่โฉนด, เลขที่ดิน, ชื่ออาคารชุด, ทะเบียนอาคารชุด, ตำบล/อำเภอ:
			<input type="text" size="60" id="securID" name="securID" style="height:20;"/>
			<input name="h_id" type="hidden" id="h_id" value="" />
			<input type="submit" value="NEXT" />
			<input name="button" type="button" onclick="window.close()" value="CLOSE" />
		</form>	
		<br>		
		<center>
		<fieldset style="width:50%;">
		<legend><B>รายการรออนุมัติข้อมูลหลักทรัพย์</B></legend>
		<?php 
			$chk_show = "true";
			include("frm_ApproveDetail.php");
		?>
		</fieldset>
		</center>
	</div>	
</div>
</body>
</html>
