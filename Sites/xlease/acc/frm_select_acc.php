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
  <div class="style5" style="width:auto; height:100px; padding-left:10px;"><br />
    <form action="frm_list_accbook.php" name="form1" method="post" style="font-family:Tahoma; font-size:small;">
  <table width="777" border="0" style="font-family:Tahoma; font-size:small;">
  <tr style="background-color:#DEE7BE;">
    <td colspan="6" style="padding:3px;"><div align="center"><strong>สมุดรายวันทั่วไป</strong></div></td>
    </tr>
  <tr style="font-family:Tahoma; font-size:small;">
    <td width="162">เลือกรายการ</td>
    <td width="211" style="font-family:Tahoma; font-size:small;" ><select name="select_book">
	  <option value="ALL">รายการทั้งหมด</option>
      <option value="GJ">รายการบันทึกด้วยมือ</option>
	  <option value="AJ">รายการปรับปรุง</option>
	  <option value="AP">รายการออโต้โพส [auto post]</option>
	  <option value="AP-RE">รายการจัดใหม่ / รถยึด</option   >
	  <option value="AP-BR">รายการรายวันรับเงิน</option   >
	  <option value="AP-PSL">รายการส่วนลดจ่าย</option>
	  <option value="AP-BSAL">รายการจากรายวันขาย</option>
	  <option value="AP-VATS">รายการภาษีขาย</option>
	  <option value="AP-VATB">รายการภาษีซื้อ</option>   
    </select></td>
	<td>
		<div align="right">
		<select name="mount" id="mount">
			<option value="">----เดือน----</option>
			<option value="01">มกราคม</option>
			<option value="02">กุมภาพันธ์</option>
			<option value="03">มีนาคม</option>
			<option value="04">เมษายน</option>
			<option value="05">พฤษภาคม</option>
			<option value="06">มิถุนายน</option>
			<option value="07">กรกฎาคม</option>
			<option value="08">สิงหาคม</option>
			<option value="09">กันยายน</option>
			<option value="10">ตุลาคม</option>
			<option value="11">พฤศจิกายน</option>
			<option value="12">ธันวาคม</option>
		</select>
		</div>
	</td>
    <td width="57"><div align="right">เลือกปี</div></td>
    <td width="58"><select name="year_select">
      <option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
      <option value="<?php echo date("Y")-1; ?>"><?php echo date("Y")-1; ?></option>
      <option value="<?php echo date("Y")-2; ?>"><?php echo date("Y")-2; ?></option>
      </select></td>
    <td><input type="submit" value="NEXT"/></td>
	</form>
  </tr>
    <tr style="font-family:Tahoma; font-size:small;">
		<td colspan="6"></td>
    </tr>
	<tr style="font-family:Tahoma; font-size:small; background-color:#DEE7BE;">
    <td colspan="6"><div align="center"><strong>สมุดบัญชีแยกประเภท</strong></div></td>
    </tr>
	<form name="frm_acid" method="post" action="frm_list_acidbook.php">
	<tr style="font-family:Tahoma; font-size:small;">
	  <td>เลือกเลขที่บัญชี</td>
	  <td style="font-family:Tahoma; font-size:small;" >
	  <select name="acid_id">
	  <?php 
	  
	  $qry_acid=pg_query("select * from account.\"AcTable\" order by \"AcID\" ");
	  while($res_acid=pg_fetch_array($qry_acid))
	  {
	    $ac_id=$res_acid["AcID"];
	    $ac_name=$res_acid["AcName"];
		echo "<option value=\"$ac_id\">[ ".$ac_id." ] - ".$ac_name."</option>";
	  }
	  ?>
	
	  
	  </select>
	  
	  </td>
	  <td>
		<div align="right">
		<select name="mount" id="mount">
			<option value="">----เดือน----</option>
			<option value="01">มกราคม</option>
			<option value="02">กุมภาพันธ์</option>
			<option value="03">มีนาคม</option>
			<option value="04">เมษายน</option>
			<option value="05">พฤษภาคม</option>
			<option value="06">มิถุนายน</option>
			<option value="07">กรกฎาคม</option>
			<option value="08">สิงหาคม</option>
			<option value="09">กันยายน</option>
			<option value="10">ตุลาคม</option>
			<option value="11">พฤศจิกายน</option>
			<option value="12">ธันวาคม</option>
		</select>
		</div>
	</td>
	<td><div align="right">เลือกปี</div></td>
	<td>
		<select name="se2_year">
			<option value="<?php echo date("Y"); ?>"><?php echo date("Y"); ?></option>
			<option value="<?php echo date("Y")-1; ?>"><?php echo date("Y")-1; ?></option>
			<option value="<?php echo date("Y")-2; ?>"><?php echo date("Y")-2; ?></option>
		</select></td>
	<td><input name="submit" type="submit" value="NEXT"/> </form></td>
	</tr>
    <tr style="font-family:Tahoma; font-size:small; background-color:#DEE7BE;">
    <td colspan="6"><div align="center"><strong>สมุดเงินสดรับจ่าย</strong></div></td>
	 
    </tr>
	<tr style="font-family:Tahoma; font-size:small;">
    <td colspan="6"><div align="left"><iframe width="100%" height="280" src="../report/frm_table.php" frameborder="0"></iframe></div></td>
    </tr>
</table>
  </div>
  
</div>
	

<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
