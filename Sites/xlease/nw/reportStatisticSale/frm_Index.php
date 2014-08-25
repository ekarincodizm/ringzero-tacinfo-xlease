<?php
session_start();
include("../../config/config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายงานสถิติการขาย</title>
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
				<fieldset><legend><B>รายงานสถิติการขาย</B></legend>
					<div align="center" style="padding-top:50px"><input type="button" value="รายงานสินเชื่อประจำปี" style="width: 250px; height:50px" onclick="window.location='frm_Annual.php'"></div>
					<div align="center" style="padding-bottom:20px"><input type="button" value="รายงานสินเชื่อในช่วงปี" style="width: 250px; height:50px" onclick="window.location='frm_DuringYear.php'"></div>
					<div style="width:450px;padding-left:120px;padding-bottom:10px;"><b>หมายเหตุ:</b> ข้อมูลที่นำมาแสดงนี้รวมสัญญาทุกประเภท ทั้งเช่าซื้อ ขายสด รีไฟแนนท์ และโอนสิทธิ์โดยสัญญาที่ขายสดจะไม่มียอดสินเชื่อ และสัญญารีไฟแนนท์/โอนสิทธิ์จะไม่หักลบยอดสินเชื่อเดิม เป็นเพียงข้อมูลอย่างหยาบเท่านั้น</div>
				</fieldset>
			</div>
        </td>
    </tr>

</table>          

</body>
</html>