<?php
session_start();
include("../../config/config.php");
$creditID=$_GET["creditID"];

$query=pg_query("select * from \"nw_credit\" where \"creditID\" = '$creditID'");
$result=pg_fetch_array($query);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>คำอธิบายรายละเอียดประเภทสินเชื่อ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<table width="550" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
    <td>      
		<div class="wrapper">
			<fieldset><legend><B>คำอธิบายรายละเอียดประเภทสินเชื่อ</B></legend>	
				<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
				<tr align="left">
					<td align="right" valign="top"><b>ประเภทสินเชื่ีอ</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td><?php echo $result["creditType"];?></td>
				</tr>
				<tr align="left">
					<td align="right" valign="top"><b>คำอธิบายรายละเอียด</b></td>
					<td width="10" align="center"valign="top">:</td>
					<td class="text_gray"><textarea name="creditDetail" cols="50" rows="4" readonly><?php echo $result["creditDetail"];?></textarea></td>
				</tr>
				<tr align="center">
				  <td colspan=3 height="50"><input name="button" type="button" onclick="javascript:window.close();" value=" Close " /></td>
				</tr>
				</table>
			</fieldset> 
		</div>
    </td>
</tr>
</table>         
</body>
</html>