<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title><?php echo $_SESSION["session_company_name"]; ?></title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<script type="text/javascript">
checked=false;
function checkedAll(frm_ren) {
	var aa= document.getElementById('frm_ren');
	 if (checked == false)
          {
           checked = true
          }
        else
          {
          checked = false
          }
	for (var i =0; i < aa.elements.length; i++) 
	{
	 aa.elements[i].checked = checked;
	}
      }
</script>

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
.style6 {font-size: x-small}
-->
</style>
<!-- InstanceEndEditable -->
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
  TRANFER COMPANY 
    <input name="button" type="button" onclick="window.close()" value="CLOSE" />
    <button onclick="window.location='frm_report_movepayment.php'">รายงานการย้าย bill payment</button>
    <?php 
  include("../config/config.php");
  $dateqer=$_GET["pdate"];
  $qry_trpay=pg_query("select * from \"TranPay\" 
                       WHERE (post_on_asa_sys=FALSE) and ((post_to_idno='')or(post_to_idno is NULL))");
  $nurow=pg_num_rows($qry_trpay);					   
  ?>

	<form id="frm_ren" method="post" action="list_tranfer_ref.php" >
    <table width="776" border="0" cellspacing="1" style="background-color:#999999;">

  <tr style="background-color:#FBFBFB;">
    <td colspan="10">โอน bill payment ข้ามบริษัท </td>
    </tr>
  <?php 
    if($numrow > 0)
	  {
  ?>
  <tr style="background-color:#FCF1C5">
    <td colspan="10">ไม่พบข้อมูลการโอนเงิน</td>
    </tr>
  <tr>
  <?php 	   
	  }
	  else
	  {
	  
	  
  
  ?>	
  <tr style="background-color:#DDE6B7">
    <td width="75">postlog</td>
    <td width="72"><div align="center">tr_date</div></td>
    <td width="78">ref1</td>
    <td width="74">ref2</td>
    <td width="151"><div align="center">ref_name</div></td>
    <td width="55"><div align="center">termanal<br />
    id</div></td>
    <td width="56"><div align="center">bank</div></td>
    <td width="35"><div align="center">type</div></td>
    <td width="72"><div align="center">amt</div></td>
    <td width="79"><div align="left">select all
      <input type="checkbox" name="checkall" onclick="checkedAll()" />
    </div></td>
  </tr>
  <?php
  $qry_tr=pg_query("select A.*,B.* from \"TranPay\" A
                       LEFT OUTER JOIN \"BankCheque\" B on B.\"BankNo\" = A.bank_no
					    
                       WHERE (A.post_on_asa_sys=FALSE) and ((A.post_to_idno='') or (post_to_idno is NULL)) ORDER BY A.tr_date desc");
  while($res_tr=pg_fetch_array($qry_tr))
  {	
   $n++;
   $s_asa=$res_tr["post_on_asa_sys"];
   $sn_id=$res_tr["id_tranpay"];
   $postLog=$res_tr["PostID"];
   if($s_asa=="f")
   {
   
   $ref1=trim($res_tr["ref1"]);
   $ref2=trim($res_tr["ref2"]);
   $amts=$res_tr["amt"];
   
   
   
   
   
   
    $select_chk="<input type=\"checkbox\" name=\"id_tran[]\" id=\"id_tran\" value=\"$postLog\"  />";
   
   $bt_post="<button onclick=\"window.location=
	   'edit_post.php?sid=$sn_id&r1=$ref1&r2=$ref2&trd=$datepost&amt=$amts&plog=$postLog'\">EDIT</button>";
   }
   else
   {
   $bt_post="";
   }
  ?>
  <tr style="background-color:#FFFFFF;">
    <td><?php echo $postLog; ?></td>
    <td><?php echo $res_tr["tr_date"]; ?></td>
    <td><input type="hidden" name="ref1[]" value="<?php echo $res_tr["ref1"]; ?>" /><?php echo $res_tr["ref1"]; ?></td>
    <td><input type="hidden" name="ref2[]" value="<?php echo $res_tr["ref2"]; ?>" /><?php echo $res_tr["ref2"]; ?></td>
    <td><?php echo $res_tr["ref_name"]; ?></td>
    <td><?php echo $res_tr["terminal_id"]; ?></td>
    <td><?php echo $res_tr["BankCode"]; ?></td>
    <td ><?php echo $res_tr["tran_type"]; ?></td>
    <td style="text-align:right;"><?php echo number_format($res_tr["amt"],2); ?></td>
    <td><?php echo $select_chk; ?></td>
  </tr>
  <?php
  }
  ?>
  <tr style="background-color:#FFFFFF;">
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
	<td>&nbsp;</td>
    <td style="background-color:#FFFFFF; font-size:x-small;">&nbsp;</td>
    <td><input type="submit" value="Next" /></td>
  </tr>
  <?php
     }
  ?>
</table>
</form>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
