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
   $f_year=pg_escape_string($_POST["f_year"]);
   $f_type=pg_escape_string($_POST["f_type"]);
  ?>
  <table width="684" border="0" cellpadding="0" cellspacing="1"  style="font-size:small; background-color:#CCCCCC;">
  <tr style="background-color:#E9ECD5">
    <td colspan="5" style="padding-left:10px; padding-top:5px; padding-bottom:5px;"><a href="pdf_print_pay.php?mmode=<?php echo $f_mode; ?>&myear=<?php echo $f_year; ?>&mtype=<?php echo $f_type; ?>"><img src="icoPrint.png" border="0" /></a> <span class="style6">&lt;-<a href="pdf_print_pay.php?mmon=<?php echo $f_mon; ?>&myear=<?php echo $f_year; ?>&mtype=<?php echo $f_type; ?>">พิมพ์ใบสำคัญจ่าย</a> </span></td>
    </tr>
  <tr style="background-color:#E4EEFC">
    <td width="36" style="padding-left:3px;">No.</td>
    <td width="97" style="padding-left:3px;"><div align="center">acb_date</div></td>
    <td width="82" style="padding-left:3px;"><div align="center">acb_id</div></td>
    <td colspan="2" style="padding-left:3px;"><div align="center">acb_detail</div> <div align="center"></div>
      <div align="center"></div>      <div align="center"></div></td>
    </tr>
  <?php
  $qry_m=pg_query("select \"acb_date\",\"acb_id\",\"acb_detail\" from account.\"AccountBookHead\" where (EXTRACT(YEAR FROM acb_date)='$f_year') AND (EXTRACT(MONTH FROM acb_date)='$f_mon')   AND (type_acb='$f_type') AND (cancel=false) ORDER BY acb_id ");
  while($res_m=pg_fetch_array($qry_m))
  {
    $n++;
  ?>
  <tr style="background-color:#FFFFFF;">
    <td height="18" style="padding-left:3px;"><?php echo $n; ?></td>
    <td style="padding-left:3px;"><?php echo $res_m["acb_date"]; ?></td>
    <td style="padding-left:3px;"><?php echo $res_m["acb_id"]; ?></td>
    <td colspan="2" style="padding-left:3px;"><?php echo $res_m["acb_detail"]; ?>      </td>
    </tr>
  <?php
  }
  ?>
  <tr style="background-color:#E9ECD5">
    <td colspan="5">&nbsp;</td>
    </tr>
</table>

 </div>
 </div>

 	

<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
