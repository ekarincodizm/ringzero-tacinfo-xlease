<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>AV. leasing co.,ltd</title>

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
<h1 class="style4"> AV.LEASING</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">AV. Leasing </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  เพิ่มข้อมูลผู้ทำสัญญา
  <form name="frm_cusid" method="post" action="save_cusid.php">
  <table width="785" border="0" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="3" style="background-color:#FFFFCC;">ผู้ทำสัญญา</td>
    </tr>
  <tr>
    <td width="102">คำนำหน้า</td>
    <td width="582"><select name="cus_firname">
	                 <option value="นาย">นาย</option>
					 <option value="นาง">นาง</option>
					 <option value="นางสาว">นางสาว</option>
	                </select>	</td>
    <td width="91">&nbsp;</td>
  </tr>
  <tr>
    <td>ชื่อ</td>
    <td><input type="text" name="cus_name" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>นามสกุล</td>
    <td><input type="text" name="cus_surname" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" style="background-color:#FFFFCC;">คู่สมรส</td>
    </tr>
  <tr>
    <td>ชื่อ-นามสกุล</td>
    <td><input type="text" name="pair_name" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" value="NEXT" /></td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
