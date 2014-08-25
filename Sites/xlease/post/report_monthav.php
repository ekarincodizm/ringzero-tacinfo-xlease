<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>

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
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  รายงานประจำเดือน <?php echo $_SESSION["session_company_name"]; ?> 
  <form name="frm_report" method="post" action="data_report_month.php" target="_blank">
  <table width="680" border="0">
  <tr>
    <td width="243">
	<select name="f_mon">
	 <option value="1">มกราคม</option>
	 <option value="2">กุมพาพันธ์</option>
	 <option value="3">มีนาึคม</option>
	 <option value="4">เมษายน</option>
	 <option value="5">พฤษภาคม</option>
	 <option value="6">มิถุนายน</option>
	 <option value="7">กรกฏาคม</option>
	 <option value="8">สิงหาคม</option>
	 <option value="9">กันยายน</option>
	 <option value="10">ตุลาคม</option>
	 <option value="11">พฤศจิกายน</option>
	 <option value="12">ธันวาคม</option>
	 </select>
	 <select name="f_year">
	 <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
	 <option value="<?php echo date("Y")-3; ?>"><?php echo date("Y")-3; ?></option>
	 <option value="<?php echo date("Y")-2; ?>"><?php echo date("Y")-2; ?></option>
	 <option value="<?php echo date("Y")-1; ?>"><?php echo date("Y")-1; ?></option>
	 <option value="<?php echo date("Y")+1; ?>"><?php echo date("Y")+1; ?></option>
	 <option value="<?php echo date("Y")+2; ?>"><?php echo date("Y")+2; ?></option>
	 </select>
	 <input name="submit" type="submit" value="NEXT"/>	</td>
    <td width="427"><input type="button" value="CLOSE" onclick="javascript:window.close();" /></td>
  </tr>
</table>
  </form> 
  </div>
  
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
