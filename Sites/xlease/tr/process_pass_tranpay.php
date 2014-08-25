<?php
session_start();
include("../config/config.php");
$id_user=$_SESSION["av_iduser"];
$c_code=$_SESSION["session_company_code"];
//$c_code="THA";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?></title>
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
<h1 class="style4"> AV.LEASING</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?></div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  <?php
	$post_id=$_GET["pid"];
	$idno_id=$_GET["idno"];
	$amt_id=$_GET["samt"];
  
	//ตรวจสอบว่า PostID นี้มีการทำรายการก่อนหน้านี้แล้วหรือยัง
	$qrychk=pg_query("select * from \"TranPay\" where \"post_on_asa_sys\"='TRUE' and \"PostID\"='$post_id'");
	$numchk=pg_num_rows($qrychk);
	if($numchk>0){ //ถ้าพบแสดงว่าได้ทำรายการนี้ไปก่อนหน้านี้แล้ว
		echo "<div style=\"padding:20px;text-align:center\">มีการทำรายการไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ<input type=\"button\" value=\"กลับ\" onclick=\"window.location='frm_transpaydate.php'\"></div>";
	}else{ //กรณียังไม่ทำรายการให้ทำขั้นตอนต่อไป
		$qry_passtr=pg_query("select pass_tranpay('$post_id','$idno_id','$id_user')");
		$res_pass=pg_fetch_result($qry_passtr,0);
	  
		if($res_pass=='t')
		{
			$bt_print="<input type=\"button\" value=\"PRINT\" onclick=\"window.open('frm_recprint_tr_".$c_code.".php?pid=$post_id')\"  />";
		}
		else
		{
			$bt_print="เกิดข้อผิดพลาด";
		}
  
		?>
		<table width="782" border="0" style="background-color:#CCCCCC;" cellpadding="1">
		<tr style="background-color:#DDE6B7">
			<td colspan="4">Print Tranpay <button onclick="window.location='frm_transpaydate.php'">LIST TRANFER</button></td>
		</tr>
		<tr style="background-color:#FFFFFF;">
			<td width="99"><?php echo $post_id; ?></td>
			<td width="398"><?php echo $idno_id; ?></td>
			<td width="145"><?php echo $amt_id; ?></td>
			<td width="112"><?php echo $bt_print; ?></td>
		</tr>
		</table>
	<?php
	}
	?>
	</div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
