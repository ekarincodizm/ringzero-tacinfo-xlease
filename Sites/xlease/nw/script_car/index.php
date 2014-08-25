<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>แก้ไขยอดหนี้ทะเบียนที่ถูกตั้งผิด</title>
<script language=javascript>
$(document).ready(function(){
	$('#btn1').click(function(){
		$("#btn1").attr('disabled',true);
		$("#panel").text('กำลังอัพเดทข้อมูล...ระบบอาจจะใช้เวลาประมวลผลนาน 1-5 นาที');
		$("#panel").load("update105.php");
		$("#btn1").attr('disabled',false);
		
    });	
	
	$('#btn2').click(function(){
		$("#btn2").attr('disabled',true);
		$("#panel").text('กำลังอัพเดท...ระบบอาจจะใช้เวลาประมวลผลนาน 1-5 นาที');
		$("#panel").load("update101.php");
		$("#btn2").attr('disabled',false);
		
    });	
});
</script>
</head>
<body>
<div  align="center"><h1>แก้ไขยอดหนี้ทะเบียนที่ถูกตั้งผิด</h1></div>
<div class="title_top" align="center"  ><input type="button" value="Update ค่าตรวจมิเตอร์" name="btn1" id="btn1" style="width: 250px; height:50px"><input type="submit" value="Update ค่าภาษีรถยนต์" name="btn2" id="btn2" style="width: 250px; height:50px"></div>
<div id="panel" style="padding-top: 10px;"></div>
</body>
</html>