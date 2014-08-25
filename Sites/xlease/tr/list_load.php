<?php
session_start();
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
<h1 class="style4"><?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?></div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
  LIST LOAD TRANSPAY 
    <button onclick="window.location='frm_transpaydate.php'">BACK</button>
  <?php 
  include("../config/config.php");
  $dateqer=$_GET["pdate"];
  $qry_trpay=pg_query("select * from \"TranPay\" 
                       WHERE (tr_date='$dateqer') AND (tran_type!='TR')");
  $nurow=pg_num_rows($qry_trpay);					   
  ?>
  <table width="776" border="0" cellspacing="1" style="background-color:#999999;">

  <tr>
    <td colspan="8">&nbsp;</td>
    </tr>
  <?php 
    if($numrow > 0)
	  {
  ?>
  <tr style="background-color:#FCF1C5">
    <td colspan="8">ไม่พบข้อมูลการโอนเงิน</td>
    </tr>
  <tr>
  <?php 	   
	  }
	  else
	  {
	  
	  
  
  ?>	
  <tr style="background-color:#DDE6B7">
    <td width="36">No</td>
    <td width="112">ref1</td>
    <td width="135">ref2</td>
    <td width="164"><div align="center">ref_name</div></td>
    <td width="80"><div align="center">terminal_id</div></td>
    <td width="67"><div align="center">tran_type</div></td>
    <td width="91"><div align="center">amt</div></td>
    <td width="66"><div align="center">post</div></td>
  </tr>
  <?php
  $qry_tr=pg_query("select * from \"TranPay\" 
                       WHERE (tr_date='$dateqer') AND (tran_type!='TR')");
  while($res_tr=pg_fetch_array($qry_tr))
  {	
   $n++;
   $s_asa=$res_tr["post_on_asa_sys"];
   if($s_asa=="f")
   {
   $sn_id=$res_tr["id_tranpay"];
   $ref1=trim($res_tr["ref1"]);
   $ref2=trim($res_tr["ref2"]);
   $amts=$res_tr["amt"];
   
   $postLog=$res_tr["PostID"];
   
   $bt_post="<button onclick=\"window.location='tr_post.php?sid=$sn_id&r1=$ref1&r2=$ref2&trd=$dateqer&amt=$amts&plog=$postLog'\">POST</button>";
   }
   else
   {
   $bt_post="Posted";
   }
  ?>
  <tr style="background-color:#FFFFFF;">
    <td><?php echo $n; ?></td>
    <td><?php echo $res_tr["ref1"]; ?></td>
    <td><?php echo $res_tr["ref2"]; ?></td>
    <td><?php echo $res_tr["ref_name"]; ?></td>
    <td><?php echo $res_tr["terminal_id"]; ?></td>
    <td ><?php echo $res_tr["tran_type"]; ?></td>
    <td style="text-align:right;"><?php echo number_format($res_tr["amt"],2); ?></td>
    <td><?php echo $bt_post; ?></td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
