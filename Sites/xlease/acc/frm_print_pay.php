<?php
session_start();
include("../config/config.php");
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
<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>

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

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  <br />
  <form name="frm_ppay" action="list_print_pay.php" method="post">
   <table width="743" border="0" cellpadding="1" style="font-size:small;">
   <tr style="background-color:#E9ECD5;">
    <td colspan="7"><div align="center"><strong>พิมพ์ใบสำคัญจ่าย <?php echo $_SESSION["session_company_thainame"]; ?></strong></div></td>
  </tr>
  <tr>
    <td width="90">หมวดที่จะพิมพ์</td>
    <td width="69" style="padding:0px 0px 2px 2px;"><select name="s_type">
	               <option value="GJ">GJ</option>
				   <option value="AJ">AJ</option>
				   </select>	</td>
    <td width="43">เลือกปี</td>
    <td width="60"><select name="s_year">
					<option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
					<option value="<?php echo date("Y")-1; ?>"><?php echo date("Y")-1; ?></option>
					<option value="<?php echo date("Y")-2; ?>"><?php echo date("Y")-2; ?></option>
	                </select>	</td>
    <td width="70">เลือกรายการ</td>
    <td width="160"><select name="s_mode">
	<option value="ALL">ทั้งหมด</option>
	<option value="MONTH">เลือกเดือน</option>
	<option value="ID">เลือกพิมพ์เฉพาะรายการ</option>
	</select>	</td>
    <td width="221"><input type="submit" value="NEXT" /></td>
  </tr>
  <tr>
    <td colspan="7" style="padding-left:10px; padding-top:5px; padding-bottom:5px;"><div align="left"><a href="#" onclick="javascript:window.close();">close [x]</a></div></td>
    </tr>
</table>
</form>
 
  </div>
  
</div>
	

<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
