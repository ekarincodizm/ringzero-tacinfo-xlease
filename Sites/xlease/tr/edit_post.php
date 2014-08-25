<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="file:///C|/wamp/www/av/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title><?php echo $_SESSION["session_company_name"]; ?></title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<script language="javascript">
function cal_fr()
{ 
 var sta1 =parseFloat(document.frm_ps.amts.value); //ยอดโอน
 var va1 = parseFloat(document.frm_ps.count_fr.value); //จำนวนเดือนจ่าย
 var va2 = parseFloat(document.frm_ps.fr_pay.value); //ค่างวด
 var ress= parseFloat(document.frm_ps.rescal.value=va1*va2);
 
 if(ress > sta1)
 {
  alert("ยอดทำรายการเกินกว่ายอดเงินโอน");
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

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?></div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;">
   <form name="frm_edit-tr" action="process_edit_tr.php" method="post" >
    
	 <?php
	 $rr1=$_GET["r1"]; 
	 $rr2=$_GET["r2"];
	 $trdate=$_GET["trd"];
	 $amtpost=$_GET["amt"];
	 
	 $p_idno=$_GET["m_idno"];
	
     ?>
	 <div style="width:700px; padding-top:20px;">
	 <table width="780" border="0" cellpadding="1" cellspacing="1" style="background-color:#838FA7">
  <tr>
    <td colspan="3" style="text-align:center; background-color:#BDCFEA"> แก้ไขข้อมูลการโอน id_tranpay [<?php echo $_GET["sid"]; ?>]</td>
    </tr>
  <tr style="background-color:#E4EEFC">
  
    <td width="77" style="padding-left:3px;">PostID</td>
    <td colspan="2"><?php echo $_GET["plog"]; ?></td>
    </tr>
  <tr style="background-color:#E4EEFC; padding-left:3px;">
    <td>วันที่โอน</td>
    <td colspan="2"><?php echo $_GET["trd"]; ?></td>
    </tr>
  <tr style="background-color:#E4EEFC; padding-left:30px;">
    <td>จำนวนเงิน</td>
    <td colspan="2"><?php echo number_format($_GET["amt"],2); ?></td>
    </tr>
  <tr style="background-color:#E4EEFC; padding-left:3px;">
	<td width="77" style="padding-left:3px;">Ref 1 </td>
    <td colspan="2"><?php echo $_GET["r1"]; ?></td>
    </tr>
  <tr style="background-color:#E4EEFC">
    <td style="padding-left:3px;">Ref 2</td>
    <td colspan="2"><?php echo $_GET["r2"]; ?></td>
    </tr>
	 <input type="hidden" name="h_date" value="<?php echo $trdate; ?>" />
	 <input type="hidden" name="h_sid" value="<?php echo $_GET["sid"]; ?>" />
	 <tr style="background-color:#E4EEFC; padding-left:3px;">
	<td width="77" style="padding-left:3px;">ค้นหา</td>
    <td colspan="2"><input type="text" size="100" id="idno_names" name="idno_names" onKeyUp="findNames();" style="height:20;"/>
	<input name="h_id" type="hidden" id="h_id" value="" />
	<script type="text/javascript">
function make_autocom(autoObj,showObj){
	var mkAutoObj=autoObj; 
	var mkSerValObj=showObj; 
	new Autocomplete(mkAutoObj, function() {
		this.setValue = function(id) {		
			document.getElementById(mkSerValObj).value = id;
		}
		if ( this.isModified )
			this.setValue("");
		if ( this.value.length < 1 && this.isNotClick ) 
			return ;	
		return "s_listdata.php?q=" + this.value;
    });	
}	

make_autocom("idno_names","h_id");
</script>	</td>
    </tr>
  <tr style="background-color:#E4EEFC">
    <td>&nbsp;</td>
    <td><input type="submit" value="Update" /></td>
    <td><input type="button" value="cancel" onclick="window.location='list_edit_ref.php'" /></td>
  </tr>
</table>
</form>
</div>

</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
