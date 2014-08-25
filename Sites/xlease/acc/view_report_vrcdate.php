<?php
session_start();
include("../config/config.php");
$_SESSION["av_iduser"];
$tday=pg_escape_string($_POST["report_vrc_Date"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php echo $_SESSION["session_company_name"]; ?> co.,ltd</title>
  	

<script language="javascript">
function checkAllBox(obj)
{
  var theForm = obj.form;
  var i;
  if(obj.checked){
   for(i=1;i<theForm.length; i++)
   {
     theForm[i].checked = true;
   }
  }else if(!obj.checked){
   for(i=1;i<theForm.length; i++)
   {
     theForm[i].checked = false;
   }
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
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;">รายงานใบกำกับ ประจำวันที่ <?php echo $tday; ?></div>
  <div class="style5" style="width:auto; height:100px; padding-left:0px;">
  
<?php

$vat_receipt=pg_query("select gen_vat_receipt('$tday')");
$res_vat=pg_fetch_result($vat_receipt,0);

?>
  
<form  action="process_vrcdate.php" method="post">
<table width="800" border="0" cellpadding="1" cellspacing="1"  style="background-color:#CCCCCC; font-size:small;">
<tr style="background-color:#D4D4D4">
	<td width="30" height="24">No.</td>
	<td width="67">IDNO</td>
	<td width="57">V_DueNo</td>
	<td width="74">V_Receipt</td>
	<td width="70">V_Date</td>
	<td width="74">V_PrnDate</td>
	<td width="148">full_name</td>
	<td width="61">asset_type</td>
	<td width="56">regis</td>
	<td width="65"><div align="center">VatValue</div></td>
    <td width="66"><div align="center">select all<input type="checkbox" onclick="checkAllBox(this)" /></div></td>
</tr>

<?php

$qry_fr=pg_query("select * from \"FVat\" WHERE (\"V_Date\"='$tday')");
$numr=pg_num_rows($qry_fr);
if($numr==0){
?>
	<tr style="background-color:#FFFFFF"><td colspan=11 align="center">- ไม่มีข้อมูล -</td></tr>
<?php
}else{

	while($res_fr=pg_fetch_array($qry_fr)){
	$n++;

	$qry_ct=pg_query("select \"full_name\",\"asset_type\",\"C_REGIS\",\"car_regis\" from \"VContact\" WHERE (\"IDNO\"='$res_fr[IDNO]')");
	$res_ct=pg_fetch_array($qry_ct);

		if($res_ct["asset_type"] == 1){ 
			$regis = $res_ct["C_REGIS"]; 
		} else { 
			$regis = $res_ct["car_regis"]; 
		}
?> 

<tr style="background-color:#FFFFFF">
	<td height="24"><?php echo $n; ?></td>
	<td><?php echo $res_fr["IDNO"]; ?></td>
	<td><?php echo $res_fr["V_DueNo"]; ?></td>
	<td><?php echo $res_fr["V_Receipt"]; ?></td>
	<td><?php echo $res_fr["V_Date"]; ?></td>
	<td><?php echo $res_fr["V_PrnDate"]; ?></td>
	<td><?php echo $res_ct["full_name"]; ?></td>
	<td><?php echo $res_ct["asset_type"]; ?></td>
	<td><?php echo $regis; ?></td>
	<td style="text-align:right;"><?php echo number_format($res_fr["VatValue"],2); ?></td>
    <td style="text-align:right;"><div align="center">
	<input type="checkbox" name="chk_vrec[]" value="<?php echo $res_fr["V_Receipt"]; ?>" />
	</div></td>
</tr>

<?php
	$summoney=$summoney+$res_fr["VatValue"]; 
	}
}	
?>

<tr style="background-color:#EBFB91">
	<td height="24" colspan="9" style="text-align:right;"><B>รวมยอดทั้งหมด</B></td>
	<td style="text-align:right;"><B><?php echo number_format($summoney,2); ?></B></td>
    <td style="text-align:right;"><div align="center">
      <input type="submit" />
    </div></td>
</tr>
</table>
</form>
  </div>

 <div></div> 
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
