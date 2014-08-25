<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
pg_query("BEGIN WORK");
$status=0;
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
$reminder_id = $_POST['reminder_id'];
$rd_status = $_POST['rd_status'];
$reminder_date = $_POST['reminder_date'];
$txt_remark = $_POST['txt_remark'];
$txt_remark= str_replace("\n", "<br>\n", "$txt_remark");


if(empty($txt_remark)){
    echo "<div align=center><font color=\"#FF0000\">ผิดผลาด กรุณากรอกรายละเอียดด้วย หรือใส่ข้อมูลตามที่เลือกให้ครบถ้วน</font></div>";
}else{
	//ตรวจสอบว่ามีข้อมูลใน reminder_job หรือไม่
	$query=pg_query("select * from \"reminder_job\"
					where \"reminder_id\" = '$reminder_id' and \"reminder_job_date\"::date = '$reminder_date'");
			
	$num_row=pg_num_rows($query);
	if($num_row==0){
		//เพิ่ม ข้อมูลก่อน
		$in_reminder_job="insert into \"reminder_job\" (\"reminder_id\",\"reminder_job_date\",\"reminder_job_doerid\",\"reminder_job_doerstamp\", \"reminder_job_doerremark\", \"reminder_job_status\") 
				select \"reminder_id\",'$reminder_date','$get_userid','$add_date',\"reminder_details\",'-1' from \"reminder\" where \"reminder_id\"='$reminder_id'";
		if($result=pg_query($in_reminder_job)) {		
		} else {
			$status ++;
		}		
	}	
	//กรณีที่เลือก ดำเนินการเรียบร้อยแล้ว (ปิดงาน และ หยุดการแจ้งเตือน ) 
	if($rd_status=='2'){
		$in_sql_stop="UPDATE \"reminder\" SET reminder_canceluserid='$get_userid',reminder_canceluserstamp='$reminder_date',reminder_status = '0'
		where reminder_id ='$reminder_id'";
		if($result=pg_query($in_sql_stop)) {
			//$status ="OK";
		} else {
			$status ++;
		}	
		$rd_status ='1';
	}
	// ---------------------------------------------------------------------------------------------
	// บันทึกข้อมูล
	// ---------------------------------------------------------------------------------------------
	$in_sql="insert into \"reminder_job\" (\"reminder_id\",\"reminder_job_date\",\"reminder_job_doerid\",\"reminder_job_doerstamp\", \"reminder_job_doerremark\", \"reminder_job_status\") 
				values  ($reminder_id,'$reminder_date','$get_userid','$add_date','$txt_remark','$rd_status'::smallint)";
	if($result=pg_query($in_sql)) {
		//$status ="OK";
	} else {
		$status ++;
	}
	// ---------------------------------------------------------------------------------------------
	// เก็บ LOG ที่ผู้ใช้งานทำรายการ
	// ---------------------------------------------------------------------------------------------
	if($status == 0)
	{
		pg_query("COMMIT");	
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', 'บันทึกเตือนการติดตาม', '$add_date')");
		//ACTIONLOG---
		echo "<center><div align=center>บันทึกข้อมูลเรียบร้อยแล้ว</div></center>";
	}
	else
	{
		pg_query("ROLLBACK"); 
		echo "<center><div align=center><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด</font></div></center>";
	}
}

?>
<div align="center">
<center><br>
<form name="frm_reminder_update_back" method="post" action="index.php">
	<input type="submit" value="กลับ" class="ui-button">
	<INPUT TYPE="hidden" NAME="focusdate" VALUE="<?php echo $reminder_date; ?>">
</form>
</center>
</div>

</div>
</div>

</center>
</body>
</html>