<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->
<!--<title><?php //echo $_SESSION["session_company_name"]; ?> co.,ltd</title>-->
<title>(THCAP) ผูกสูตรทางบัญชี</title>

<script language="JavaScript">

	var HttPRequest = false;

	function doCallAjax(ID)
	{
		HttPRequest = false;
		if (window.XMLHttpRequest)
		{ // Mozilla, Safari,...
			HttPRequest = new XMLHttpRequest();
			if (HttPRequest.overrideMimeType)
			{
				HttPRequest.overrideMimeType('text/html');
			}
		}
		else if (window.ActiveXObject)
		{ // IE
			try
			{
				HttPRequest = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					HttPRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
		} 

		if (!HttPRequest)
		{
			alert('Cannot create XMLHTTP instance');
			return false;
		}

		var url = 'del_fm_list.php';
		var pmeters = "tID="+ID;

		HttPRequest.open('POST',url,true);

		HttPRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		HttPRequest.setRequestHeader("Content-length", pmeters.length);
		HttPRequest.setRequestHeader("Connection", "close");
		HttPRequest.send(pmeters);

		HttPRequest.onreadystatechange = function()
		{
			if(HttPRequest.readyState == 4) // Return Request
			{
				if(HttPRequest.responseText == 'Y')
				{
					document.getElementById("tr"+ID).style.display = 'none';
				}
			}				
		}

		setTimeout(function() { location.reload(true); }, 300); // เมื่อลบเสร็จให้ reload ใหม่
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
<!--<h1 class="style4"> <?php echo $_SESSION["session_company_name"]; ?></h1>-->
<h1 class="style4">(THCAP) ผูกสูตรทางบัญชี</h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <!--<div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;"><?php echo $_SESSION["session_company_name"]; ?> </div>-->
  <div class="style3" style="background-color:#333333; width:auto; height:20px; padding-left:10px;">(THCAP) ผูกสูตรทางบัญชี</div>
  <div class="style3" style="background-color:#000000; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:10px;"><br>
  <table width="722" border="0" cellpadding="1" cellspacing="1" style="background-color:#CCCCCC;">
  <tr style="background-color:#F5F7E1; padding:2px;">
    <td colspan="2" style="padding: 3px 3px 3px 3px;"><div align="center"><strong>ผูกสูตรทางบัญชี</strong></div></td>
    <td colspan="2" style="padding: 3px 3px 3px 3px;"><button onclick="window.location='frm_add_formular.php'">เพิ่มสูตร</button></td>
    </tr>
  <tr style="background-color:#DEE7BE; padding:2px;" >
    <td width="116" style="padding:2px;">frm ID </td>
    <td width="419">ชื่อสูตรทางบัญชี</td>
    <td width="102"><div align="center">แก้ไข</div></td>
    <td width="72"><div align="center">delete</div></td>
  </tr>
	<?php
	$sql_fm=pg_query("select * from account.\"all_accFormula\" ");
	while($res_fm=pg_fetch_array($sql_fm))
	{
	?>
  <tr id="tr<?php echo $res_fm["fm_id"]; ?>"  style="background-color:#EDF1DA;">
    <td style="padding-left:3px;"><?php echo $res_fm["af_fmid"]; ?></td>
    <td style="padding-left:3px;"><?php echo $res_fm["af_fmname"]; ?></td>
    <td><div align="center">
    <button onclick="window.location='frm_edit_fmacc.php?fmID=<?php echo $res_fm["af_fmid"]; ?>&fmname=<?php echo $res_fm["af_fmname"]; ?>'">แก้ไขสูตร</button></div></td>
    <td><div align="center"><a href="JavaScript:if(confirm('Are you delete'))doCallAjax('<?php echo $res_fm["af_fmid"]; ?>');">Del</a></div></td>
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
