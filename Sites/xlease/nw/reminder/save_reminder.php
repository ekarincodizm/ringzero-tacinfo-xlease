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
<div style="height:50px; width:auto; text-align:center; opacity:20;"><h1>บันทึกการติดตาม</h1></div>
<div style="height:50px; width:500px; text-align:left; margin:0px auto;">

<?php
$curdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
// ---------------------------------------------------------------------------------------------
// รับค่าต่างๆที่ POST มา
// ---------------------------------------------------------------------------------------------
$get_userid = $_POST['userid'];
$addtype = $_POST['addtype'];
$data0 = $_POST['data0'];
$data1 = $_POST['data1'];
$data2 = $_POST['data2'];
$data3 = $_POST['data3'];
$cb_expiredate  = $_POST['cb_expiredate'];
$cb_private  = $_POST['cb_private'];
$reminderdetails = $_POST['reminderdetails'];
$focusdate = $_POST['focusdate'];

$reminderdetails= str_replace("\n", "<br>\n", "$reminderdetails");

// ---------------------------------------------------------------------------------------------
// กำหนดค่าเบื้องต้นของตัวแปร $data สำหรับ insert ข้อมูล
// ---------------------------------------------------------------------------------------------
$data = '';
$expiredate = '';
$isprivate = 0; // ค่า default คือบันทึกรายการแบบ public

// ---------------------------------------------------------------------------------------------
// ถ้ามีการกำหนด expiredate จะต้องนำไป insert ด้วย
// ---------------------------------------------------------------------------------------------
if($cb_expiredate == 1)
	$expiredate = $_POST['expiredate'];
else
	$expiredate = '2999-12-31';

// ---------------------------------------------------------------------------------------------
// ถ้ามีการกำหนด private จะต้องแก้ไขให้เป็นรายการ private
// ---------------------------------------------------------------------------------------------
if($cb_private == '1')
	$isprivate = 1;

if(empty($reminderdetails)){
    echo "<div align=center><font color=\"#FF0000\">ผิดผลาด กรุณากรอกรายละเอียดด้วย หรือใส่ข้อมูลตามที่เลือกให้ครบถ้วน</font></div>";
}else{

	// ---------------------------------------------------------------------------------------------
	// บันทึกในกรณีที่ให้เตือนทุกวันที่ $addtype == 1
	// ---------------------------------------------------------------------------------------------
	if ($addtype == 1) {
		// ตรวจสอบข้อมูลว่าข้อมูลที่ส่งมาถูกต้องครบถ้วน
		if ($data0 == '-') {
			echo "<div align=center><font color=\"#FF0000\">ผิดผลาด กรุณากรอกรายละเอียดด้วย หรือใส่ข้อมูลตามที่เลือกให้ครบถ้วน</font></div>";
		} else {
			$data = $data0; // ข้อมูลคือวันที่
		}
	}
	
	// ---------------------------------------------------------------------------------------------
	// บันทึกในกรณีที่ให้เตือนทุกวัน ในสัปดาห์ที่ $addtype == 2
	// ---------------------------------------------------------------------------------------------
	if ($addtype == 2) {
		// ตรวจสอบข้อมูลว่าข้อมูลที่ส่งมาถูกต้องครบถ้วน
		if ($data1 == '-' || $data2 == '-') {
			echo "<div align=center><font color=\"#FF0000\">ผิดผลาด กรุณากรอกรายละเอียดด้วย หรือใส่ข้อมูลตามที่เลือกให้ครบถ้วน</font></div>";
		} else {
			$data = $data1.$data2; // ข้อมูลคือ วัน + สัปดาห์ที่
		}
	}
	
	// ---------------------------------------------------------------------------------------------
	// บันทึกในกรณีที่ให้เตือนทุกวัน ในสัปดาห์ที่ $addtype == 3
	// ---------------------------------------------------------------------------------------------
	if ($addtype == 3) {
		// ตรวจสอบข้อมูลว่าข้อมูลที่ส่งมาถูกต้องครบถ้วน
		if ($data3 == '' || $data3 == '-') {
			echo "<div align=center><font color=\"#FF0000\">ผิดผลาด กรุณากรอกรายละเอียดด้วย หรือใส่ข้อมูลตามที่เลือกให้ครบถ้วน</font></div>";
		} else {
			$data = str_replace('-', '', $data3); // ข้อมูลคือ ปีเดือนวัน ที่จะเตือน
		}
	}
	
	// ---------------------------------------------------------------------------------------------
	// บันทึกในกรณีที่ให้เตือนทุกวัน $addtype == 4
	// ---------------------------------------------------------------------------------------------
	if ($addtype == 4) {
		// เตือนทุกวัน $data=0
		$data = '0';
	}
	
	// ---------------------------------------------------------------------------------------------
	// ถ้าข้อมูล data ถูกต้อง จึงค่อยทำการบันทึก
	// ---------------------------------------------------------------------------------------------
	$in_sql="insert into \"reminder\" (\"reminder_type\",\"reminder_ref\",\"reminder_details\",\"reminder_doerid\",\"reminder_doerstamp\", \"reminder_expiredate\", \"reminder_isprivate\") 
				values  ($addtype,$data,'$reminderdetails','$get_userid','$add_date','$expiredate','$isprivate'::smallint)";
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
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', 'บันทึกเตือนการติดตาม', '$add_date')");
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
<form name="frm_reminder_back" method="post" action="index.php">
	<input type="submit" value="กลับ" class="ui-button">
	<INPUT TYPE="hidden" NAME="focusdate" VALUE="<?php echo $focusdate; ?>">
</form>
</center>
</div>

</div>
</div>

</center>
</body>
</html>