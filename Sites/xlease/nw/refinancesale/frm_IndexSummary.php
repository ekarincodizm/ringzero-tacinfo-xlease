<?php
session_start();
include("../../config/config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>สรุปผลการชวนลูกค้า Refinance</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper">
				<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
				<fieldset><legend><B>สรุปผลการชวนลูกค้า Refinance</B></legend>
					<div align="center" style="padding:100px 0px 0px;"><input type="button" value="สรุปจำนวนการชักชวนและจับคู่" style="width: 250px; height:50px" onclick="window.location='summary_InviteMatch.php'"></div>
					<div align="center"><input type="button" value="เปรียบเทียบจำนวนการชักชวนและจับคู่" style="width: 250px; height:50px" onclick="window.location='summary_Compare.php'"></div>
					<div align="center"><input type="button" value="สรุปรายละเอียดการชักชวน" style="width: 250px; height:50px" onclick="window.location='summary_DetailInvite.php'"></div>
					<div align="center"><input type="button" value="สรุปรายละเอียดการจับคู่" style="width: 250px; height:50px" onclick="window.location='summary_Match.php'"></div>
					<div align="center" style="padding:0px 0px 100px;"><input type="button" value="สรุปรายละเอียดการชักชวนแบบเลือกวันได้" style="width: 250px; height:50px" onclick="window.location='frm_invite_datetime_Detail.php'"></div>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          

</body>
</html>