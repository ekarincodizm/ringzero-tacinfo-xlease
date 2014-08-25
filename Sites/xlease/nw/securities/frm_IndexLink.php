<?php
session_start();
include("../../config/config.php");		 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
</head>
<body style="background-color:#ffffff; margin-top:0px;" onload="document.getElementById('number_running').focus();">
<form>
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
	<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
		<h1 class="style4">+เชื่อมโยงหลักทรัพย์ค้ำประกัน+</h1>
	</div>

	<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;padding:10px;">
		<div align="right" style="padding:15px"><span style="cursor:pointer;" onclick="window.close();"><u>x ปิดหน้านี้</u></span></div>
		<table width="785" border="0" cellpadding="1" cellspacing="1" bgcolor="#CECECE" style="font-weight:bold;">
		<tr height="30" bgcolor="#FFFFFF">
			<td align="center" width="210">
				<div style="padding:50px;">
					<input type="button" value="เพิ่มการเชื่อมโยง" style="width:180px;height:80px;font-size:14pt;" onclick="window.location='frm_IndexLinkAdd.php'">
					<input type="button" value="แก้ไขการเชื่อมโยง" style="width:180px;height:80px;font-size:14pt;" onclick="window.location='frm_IndexLinkEdit.php'">
				</div>
			</td>
		</tr>
		</table>
	</div>
</div>
</body>
</html>
