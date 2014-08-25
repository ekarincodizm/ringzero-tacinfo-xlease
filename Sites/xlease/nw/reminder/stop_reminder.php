<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>XLEASE</title>
<style type="text/css">
<!--
BODY{
	font-family: Tahoma;
	font-size: 11px;
}
TEXTAREA,SELECT,INPUT{
	font-family: Tahoma;
	font-size: 11px;
	color: #3A3A3A;
}
legend{
	font-family: Tahoma;
	font-size: 12px;	
	color: #0000CC;
}
fieldset{
	padding:3px;
}
-->
</style>
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<center>

<div id="wmax" style="width:500px; border:#666666 solid 0px; margin-top:0px;">
<div style="height:50px; width:auto; text-align:center; opacity:20;"><h1>หยุดการแจ้งเตือน</h1></div>
<div style="height:50px; width:500px; text-align:left; margin:0px auto;">

<?php
$curdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
// ---------------------------------------------------------------------------------------------
// รับค่าต่างๆที่ POST มา
// ---------------------------------------------------------------------------------------------
$get_userid =pg_escape_string($_POST['userid']);
$reminder_id =pg_escape_string($_POST['reminder_id']);
$reminder_date_stop =pg_escape_string($_POST['reminder_date_stop']);//วันที่จะหยุดการแจ้งเตือน
$reminder_after_date =pg_escape_string($_POST['reminder_after_date']);


if(empty($reminder_id)){
    echo "<div align=center><font color=\"#FF0000\">ผิดผลาด กรุณาลองอีกครั้ง</font></div>";
}else{

	// ---------------------------------------------------------------------------------------------
	// อัปเดตข้อมูล
	// ---------------------------------------------------------------------------------------------
	$in_sql="UPDATE \"reminder\" SET reminder_canceluserid='$get_userid',reminder_canceluserstamp='$reminder_date_stop',reminder_status = '0'
	where reminder_id ='$reminder_id'";
	if($result=pg_query($in_sql)) {
		$status ="OK";
	} else {
		$status ="Error";
	}	
	// ---------------------------------------------------------------------------------------------
	// เก็บ LOG ที่ผู้ใช้งานทำรายการ
	// ---------------------------------------------------------------------------------------------
	if($status == "OK")
	{
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', 'หยุดการแจ้งเตือน', '$add_date')");
		//ACTIONLOG---
		echo "<center><div align=center>บันทึกข้อมูลเรียบร้อยแล้ว</div></center>";
	}
	else
	{
		echo "<center><div align=center><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด</font></div></center>";
	}
}
?>
<div align="center">
<center><br>
<form name="frm_reminder_update_back" method="post" action="index.php">
	<input type="submit" value="กลับ" class="ui-button">
	<INPUT TYPE="hidden" NAME="focusdate" VALUE="<?php echo $reminder_after_date; ?>">
</form>
</center>
</div>

</div>
</div>

</center>
</body>
</html>