<?php
session_start();
include("../config/config.php");
  $idno=pg_escape_string($_POST["h_arti_id"]); 
  $qry_lads=pg_query("select * from letter.send_address where \"IDNO\"='$idno'");
  $resnum=pg_num_rows($qry_lads);
	if($resnum==0)
	{
     echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_dtl_let.php?IDNO=$idno\">";
	} 
	else
	{
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
	document.frm_letter.f_add.disabled=true;
	document.frm_letter.f_subadd.disabled=true;
	document.frm_letter.f_soi.disabled=true;
	document.frm_letter.f_road.disabled=true;
	document.frm_letter.f_tum.disabled=true;
	document.frm_letter.f_aum.disabled=true;
	document.frm_letter.f_province.disabled=true;
	document.frm_letter.f_post.disabled=true;
  
 }
 else if(document.frm_letter.type_add.value==2)
 {
    alert("กรุณาใส่ที่อยู่");
    document.frm_letter.f_add.disabled=false;
	document.frm_letter.f_subadd.disabled=false;
	document.frm_letter.f_soi.disabled=false;
	document.frm_letter.f_road.disabled=false;
	document.frm_letter.f_tum.disabled=false;
	document.frm_letter.f_aum.disabled=false;
	document.frm_letter.f_province.disabled=false;
	document.frm_letter.f_post.disabled=false;
	
  
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
  <div class="style5" style="width:auto; height:100px; padding-left:10px;"><button onclick="window.location='frm_letter.php'">BACK</button>
  <form name="old_sent" method="post" action="process_save_sent.php" >
  <table width="100%" border="0">
  <tr style="background-color:#ffffff">
    <td colspan="4">	</td>
    </tr>

	<tr style="background-color:#FCF1C5">
    <td colspan="4">
	</td>
  </tr>
  
	<tr style="background-color:#FCF1C5">
     <td colspan="4">รายการที่เคยส่งจดหมาย <?php echo $idno; ?></td>
    </tr>
	<?php
	 while($res_lads=pg_fetch_array($qry_lads))
	 { 
	  $n++;
	?>
	<tr style="background-color:#FCF1C5">
    <td width="5%"><?php echo $n; ?></td>
    <td width="21%"><?php echo $res_lads["name"]; ?></td>
    <td><?php echo $res_lads["dtl_ads"]; ?></td>
    
    <td width="7%"><input type="button" name="let_id" value="select" onclick="window.location='frm_add_let.php?CID=<?php echo $res_lads["CusLetID"]; ?>&IDNO=<?php echo $idno; ?>'"  /></td>
	</tr>
	
  
   <?php
     }
   }
   ?>
   <tr style="background-color:#FCF1C5">
	  <td>&nbsp;</td>
	  <td><input type="button" value="เพิ่มรายการ" onclick="window.location='frm_dtl_let.php?IDNO=<?php echo $idno; ?>'"  /></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
   </table>
   </form>

  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
