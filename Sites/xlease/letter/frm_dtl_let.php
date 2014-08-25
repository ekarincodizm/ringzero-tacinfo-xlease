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
  
  $idno=pg_escape_string($_GET["IDNO"]); 
  $qry_let=pg_query("select  A.\"C_REGIS\",A.car_regis,A.\"IDNO\",A.\"full_name\",B.\"N_ContactAdd\",B.\"CusID\" from \"VContact\" A  
                     LEFT OUTER JOIN \"Fn\" B on B.\"CusID\"=A.\"CusID\" 
					 WHERE   A.\"IDNO\"='$idno'
					 
					");
	$resvcon=pg_fetch_array($qry_let);
	if($resvcon["C_REGIS"]=="")
		{
		
		$rec_regis=$resvcon["car_regis"]; 
		$rec_cnumber=$resvcon["gas_number"];
		$res_band=$resvcon["gas_name"];
		}
		else
		{
		
		$rec_regis=$resvcon["C_REGIS"];
		$rec_cnumber=$resvcon["C_CARNUM"];
		$res_band=$resvcon["C_CARNAME"];
		}
		
  
      $qry_let=pg_query("select * from letter.send_address WHERE \"CusLetID\"='$cuslet'");
	  $res_let=pg_fetch_array($qry_let);
	

  ?>	
 
   <form action="process_save.php" method="post" name="frm_letter" >
  <table width="100%" border="0">
  <tr style="background-color:#ffffff">
    <td colspan="6">	</td>
    </tr> 
   
  <tr >
    <td width="159">IDNO ชื่อ-นามสกุล </td>
    <td colspan="2"><?php echo $resvcon["full_name"]; ?></td>
    <td width="97">ทะเบียน</td>
    <td width="151"><?php echo $rec_regis; ?></td>
    <td width="136">&nbsp;</td>
  </tr>
  
  <tr>
    <td><p>ข้อมูลที่อยู่ </p>      </td>
    <td colspan="5" rowspan="2" valign="top" style="background-color:#EBF2FA;">
	<input type="hidden" name="f_idno" value="<?php echo $idno; ?>"  />
	<input type="hidden" name="f_fn_add" value="<?php echo $resvcon["N_ContactAdd"];?>"  />
	<?php echo $resvcon["N_ContactAdd"];?></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td>ชื่อผู้รับจดหมาย</td>
    <td colspan="5"><span style="background-color:#EBF2FA;">
      <input type="text" name="f_name"  size="60" value="<?php echo $resvcon["full_name"]; ?>" tabindex="1" />
    </span></td>
   </tr>
    <td>เลือกที่ส่งจดหมาย</td>
    <td colspan="2">
		<select name="type_add" onchange="add_select();" tabindex="2">
			<option value="0">เลือกที่ส่งจดหมาย</option>
			<option value="1">ใช้ที่อยู่ตาม IDNO</option>
			<option value="2">ใช้ที่อยู่อื่น ๆ</option>
		</select>	</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>ที่อยู่</td>
    <td colspan="5" rowspan="2" valign="top"><textarea name="f_ads" cols="80" rows="4"></textarea></td>
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
    <td width="147"><input type="submit" value="NEXT"  /></td>
    <td width="74">&nbsp;</td>
    <td colspan="3"><input name="BACK" value="BACK" type="button" onclick="window.location='frm_letter.php'"/></td>
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
