<?php
session_start();
$id_user=$_SESSION["av_iduser"];
$dateqry=pg_escape_string($_POST["qryDate"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>




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
	color: #FF0000;
	font-weight: bold;
}
-->
</style>
<!-- InstanceEndEditable -->
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
  <div class="style5" style="width:auto; height:60px; padding-left:10px;">
   
   <table width="782" border="0" style="background-color:#CCCCCC;" cellpadding="1" cellspacing="1">
  <tr style="background-color:#EBFB91;">
    <td colspan="5">พิมพ์่เช็ค      
      </td>
    </tr>

  <tr style="background-color:#EEF2DB">
    <td width="131">ChequeNo.</td>
    <td width="230">Bank Name </td>
    <td width="183">BankBranch</td>
    <td width="131">AmtOnCheque</td>
    <td width="91">Print</td>
  </tr>
  <?php
   include("../config/config.php");
   
   /*Process pass cheque */
   
   $ic=pg_escape_string($_GET["icno"]);
   $ip=pg_escape_string($_GET["postid"]);
   $user=pg_escape_string($_GET["userid"]);
   
   $bbname=pg_escape_string($_GET["bname"]);
   $bbbranch=pg_escape_string($_GET["bbranch"]);
   $bpamt=pg_escape_string($_GET["amt"]);
   	
   $qry_cc=pg_query("select pass_cheque('$ip','$ic','$user')");
   $res_csc=pg_fetch_result($qry_cc,0);
	
   if($res_csc=='t')
   {
     $bt_print="<input type=\"button\" value=\"PRINT\" onclick=\"window.open('frm_recprint_ch.php?pid=$ip')\"  />";
   }
   else
   {
     $bt_print="เกิดข้อผิดพลาด";
   }
   
   
   
   
   
 
  ?>  
  <tr style="background-color:#E8EE66; font:bold;">
    <td ><?php echo $n; ?><?php echo $ic; ?></td>
    <td width="230"><?php echo $bbname; ?></td>
    <td><?php echo $bbbranch; ?></td>
    <td style="text-align:right;"><?php echo number_format($bpamt,2); ?></td>
    <td style="text-align:center; background-color:#99FF99;"><!-- <button onclick="toggleContent()">Toggle</button> -->
	<?php echo $bt_print; ?></td>
  </tr>
  
  <tr style="background-color:#DFF4F7;">
  <td colspan="5"><input name="button" type="button" onclick="window.location='receipt_ch.php'" value="BACK"  />
</table>


  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
