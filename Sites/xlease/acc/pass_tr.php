<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
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
  
  <table width="771" border="0" style="background-color:#CCCCCC;" cellpadding="1">
  <tr style="background-color:#DDE6B7">
    <td colspan="6">รายการรับเงิน-บัญชี</td>
    </tr>
  <tr style="background-color:#FCF1C5">
    <td width="37">No.</td>
    <td width="114">IDNO</td>
    <td width="128">PostID</td>
    <td width="93">typepay</td>
    <td width="93">Amount</td>
    <td width="95">Receipt</td>
  </tr>
  <?php
  $sdate=pg_escape_string($_GET["trdate"]);
  $qry_tr=pg_query("select * from \"TranPay\" WHERE post_on_date='$sdate' ");
  while($res_tr=pg_fetch_array($qry_tr))
  {
   $n++;
   $bt_rec="";
   $ppid=$res_tr["PostID"];
   $idnoid=$res_tr["post_to_idno"];
   $amtid=$res_tr["amt"]; 
  ?>
  <tr style="background-color:#FFFFFF;">
    <td><?php echo $n; ?></td>
    <td><?php echo $res_tr["post_to_idno"]; ?></td>
    <td><?php echo $res_tr["PostID"]; ?></td>
    <td><?php echo $res_tr["tran_type"]; ?></td>
    <td><?php echo $res_tr["amt"]; ?></td>
    <td><?php echo $bt_rec; ?><button onclick="window.location='process_pass_tranpay.php?pid=<?php echo $ppid;?>&idno=<?php echo $idnoid;?>&samt=<?php echo $amtid; ?>'">Receipt</button></td>
  </tr>
  <?php
  }
  ?>
</table>

  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
