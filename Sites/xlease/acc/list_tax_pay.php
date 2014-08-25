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
<!-- InstanceBeginEditable name="head" -->
<style type="text/css">
<!--
.style6 {
	color: #666666;
	font-weight: bold;
}
-->
</style>
<!-- InstanceEndEditable -->
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
  <?php
   $f_mon=pg_escape_string($_POST["f_mon"]);
   $f_year=pg_escape_string($_POST["s_year"]);

  ?>
  <table width="684" border="0" cellpadding="0" cellspacing="1"  style="font-size:small; background-color:#CCCCCC;">
  <tr style="background-color:#E9ECD5">
    <td colspan="6" style="padding-left:10px; padding-top:5px; padding-bottom:5px;"><a href="pdf_print_pay.php?mmode=<?php echo $f_mode; ?>&myear=<?php echo $f_year; ?>&mtype=<?php echo $f_type; ?>"><img src="icoPrint.png" border="0" /></a> <span class="style6">&lt;-<a href="pdf_tax_pay.php?mmon=<?php echo $f_mon; ?>&myear=<?php echo $f_year; ?>">พิมพ์รายงานยอดอากรสัญญาประจำเดือน</a> </span></td>
    </tr>
  <tr style="background-color:#E4EEFC">
    <td width="40" style="padding-left:3px;">No.</td>
    <td width="121" style="padding-left:3px;"><div align="center">IDNO(เลขที่สัญญา)</div></td>
    <td width="194" style="padding-left:3px;"><div align="center">ชื่อผู้เช่าซื้อ</div></td>
    <td width="99" style="padding-left:3px;"><div align="center">ราคาเช่าซื้อ <br />
      ไม่รวม VAT </div> <div align="center"></div>
      <div align="center"></div>      <div align="center"></div></td>
    <td width="111" style="padding-left:3px;"><div align="center">ค่าอากรเช่าซื้อ</div></td>
    <td width="112" style="padding-left:3px;"><div align="center">ค่าอากรผู้ค้ำ</div></td>
  </tr>
  <?php
  $qry_m=pg_query("select * from \"VContact\" where (EXTRACT(YEAR FROM \"P_STDATE\")='$f_year') AND (EXTRACT(MONTH FROM \"P_STDATE\")='$f_mon') AND (\"P_TOTAL\"!=0) ORDER BY \"IDNO\" ");
  while($res_m=pg_fetch_array($qry_m))
  {
    $n++;

	$pm=$res_m["P_MONTH"];
	$pt=$res_m["P_TOTAL"];
	$novat=$pt*$pm; 
	$aak_cost=$novat/1000;
	$ak_cost=ceil($aak_cost);
  ?>
  <tr style="background-color:#FFFFFF;">
    <td height="18" style="padding-left:3px;"><?php echo $n; ?></td>
    <td style="padding-left:3px;"><?php echo $res_m["IDNO"]; ?></td>
    <td style="padding-left:3px;"><?php echo $res_m["full_name"]; ?></td>
    <td style="padding-left:3px; text-align:right; padding-right:3px;"><?php echo number_format($novat,2); ?></td>
    <td style="padding-left:3px;text-align:right; padding-right:3px;"><?php echo number_format($ak_cost,2); ?></td>
    <td style="padding-left:3px;text-align:right; padding-right:3px;">10.00</td>
  </tr>
  <?php
  }
  ?>
  <tr style="background-color:#E9ECD5">
    <td colspan="6">&nbsp;</td>
    </tr>
</table>

 </div>
 </div>

 	

<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
