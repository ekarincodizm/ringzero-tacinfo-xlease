<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>AV. leasing co.,ltd</title>
<script language="javascript">
function add_select()
{
 var fn_add=document.frm_letter.f_fn_add.value;
 //var fn_add=document.frm_letter.type_add.value;
 if(document.frm_letter.type_add.value==1)
 {
    //alert("ที่อยู่เดิม");  
	//document.frm_letter.f_ads.disabled=true;
	document.frm_letter.f_ads.value=fn_add;
	
  
 }
 else if(document.frm_letter.type_add.value==2)
 {
    alert("กรุณาใส่ที่อยู่");
   document.frm_letter.f_ads.disabled=false;
	document.frm_letter.f_ads.value='';
	document.frm_letter.f_ads.focus();
	
  
 }
 else
 {
  alert("กรุณาทำรายการที่อยู่");
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
  <?php 
  if(empty($_POST["h_arti_id"]))
  {
    $cusletid=pg_escape_string($_GET["cusid"]);
  }
  else
  {
    $cusletid=pg_escape_string($_POST["h_arti_id"]); 
  }
  
  
  
  
  
  $qry_let=pg_query("select  * from letter.send_address WHERE \"CusLetID\" ='$cusletid' ");
  $resvcon=pg_fetch_array($qry_let);
	
	
	

  ?>	
 
   <form action="update_letter.php" method="post" name="frm_letter" >
   <input type="hidden" name="cusid" value="<?php echo $cusletid; ?>"  />
  <table width="100%" border="0">
  <tr style="background-color:#ffffff">
    <td colspan="6">	</td>
    </tr> 
   
  <tr style="background-color:#DDE6B7;">
    <td colspan="6">แก้ไขข้อมูลผู้รับจดหมาย CusLetID <?php echo $cusletid; ?></td>
    </tr>
  <tr>
    <td width="159">ชื่อผู้รับจดหมาย</td>
    <td colspan="5"><span style="background-color:#EBF2FA;">
      <input type="text" name="f_name"  size="60" value="<?php echo $resvcon["name"]; ?>" tabindex="1" />
    </span></td>
   </tr>
  
  <tr>
    <td>ที่อยู่</td>
    <td colspan="5" rowspan="2" valign="top"><textarea name="f_ads" cols="80" rows="4"><?php echo $resvcon["dtl_ads"]; ?></textarea></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="147"><input type="submit" value="บันทึก"  /></td>
    <td width="74">&nbsp;</td>
    <td colspan="3"><input name="BACK" value="BACK" type="button" onclick="window.location='frm_edit_letter.php'"/></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
</form>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
