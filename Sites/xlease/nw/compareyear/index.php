<?php
set_time_limit(0);
session_start();
include("../../config/config.php");

$condition=$_POST["condition"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ตรวจสอบการย้ายระบบ (ปีรถ)</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<div  align="center"><h1>ตรวจสอบการย้ายระบบ (ปีรถ)</h1></div>
<div class="title_top" align="center"  >
<table width="500" border="0" align="center">
<tr><td>
<fieldset>
<form method="POST" name="form1" action="index.php">
<table width="50%" border="0" align="center">
	<tr>
		<td width="" align="right" height="25">
			<input type="radio" name="condition" value="1" <?php if($condition =="" || $condition==1){ echo "checked";}?>>
		</td>
		<td>
			แสดงเฉพาะค่าที่ไม่เหมือนกัน
		</td>
	</tr>
	<tr>
		<td align="right"  height="25">
			<input type="radio" name="condition" value="2" <?php if($condition==2){ echo "checked";}?>>
		</td>
		<td>
			แสดงทั้งหมด
		</td>
	</tr>
	<tr align="center"  height="50">
		<td colspan="2">
			<input type="submit" value="เริ่มเปรียบเทียบ" name="btn1" style="width: 150px; height:30px">
		</td>
	</tr>
	</table>
</fieldset>
</tr></td></table>
</form>
</div>
<div id="panel" style="padding-top: 10px;">
	<?php	
		if($condition !=""){
			include "checkcompare.php";
		}
	?>
</div>
</body>
</html>