<?php
session_start();
include("../../config/config.php");

$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime();

$signDate = $_POST["signDate"]; // วันที่เลือก
?>

<?php
pg_query("BEGIN WORK");
$status = 0;

$inter = pg_query("SELECT \"thcap_EndofDay\"('$signDate')");
$resin = pg_fetch_array($inter);
list($seletedate)=$resin;

if($seletedate = "t"){$seletedate = "1";}
elseif($seletedate = "f"){$seletedate = "2";}
else{$seletedate = "0";}

$sql_check_user = pg_query("select * from \"Vfuser\" where \"id_user\" = '$id_user' ");
while($resultuser=pg_fetch_array($sql_check_user))
{
	$username = $resultuser["username"]; // user ที่ทำรายการ
}

$qry_in="insert into public.\"thcap_endofday\" (\"username\",\"doerstamp\",\"selectdate\",\"result\") values ('$username','$logs_any_time','$signDate','$seletedate') ";
if($resultin=pg_query($qry_in)){
}else{
	$status++;
}

if($status == 0){
	pg_query("COMMIT");
	pg_query("BEGIN WORK");
}else{
	pg_query("ROLLBACK");
	$errorStep1 = "yes";
}

// เปลี่ยน base ชั่วคราว เพื่อตรวจสอบการทำงานของระบบ
$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=postgres user=postgres password=". $_SESSION["session_company_dbpass"] ."";
$db_connect = pg_connect($conn_string) or die("Can't Connect !");

// ตรวจสอบการ run process auto ในระบบ ทั้งหมด
$qry_check_process = "SELECT \"check_process_job\"()";
if($resultin=pg_query($qry_check_process)){
	// กลับมาต่อ base เดิม
	$conn_string = "host=". $_SESSION["session_company_server"] ." port=5432 dbname=". $_SESSION["session_company_dbname"] ." user=". $_SESSION["session_company_dbuser"] ." password=". $_SESSION["session_company_dbpass"] ."";
	$db_connect = pg_connect($conn_string) or die("Can't Connect !");
}else{
	$status++;
	$error = "เกิดข้อผิดพลาดจาก function check_process_job";
	$errorStep2 = "yes";
}

    
if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) Process End Of Day', '$logs_any_time')");
	//ACTIONLOG---
	pg_query("COMMIT");
	if($seletedate == "1")
	{
		echo "<br><center>ดำเนินการเสร็จสมบูรณ์</center>";
	}
	elseif($seletedate == "2")
	{
		echo "<br><center>เกิดข้อผิดพลาด</center>";
	}
	else
	{
		echo "<br><center>เกิด error จาก function <br> กรุณาติดต่อเจ้าหน้าที่เพื่อทำการแก้ไข</center>";
	}
	
	echo "<br><br><center><INPUT TYPE=\"BUTTON\" VALUE=\"กลับ\" ONCLICK=\"window.location.href='frm_Index.php?signDate=$signDate'\"> <input type=\"button\" value=\"  Close  \" onclick=\"javascript:window.close();\" class=\"ui-button\"><center>";
	//echo "<meta http-equiv='refresh' content='2; URL=Payments_history.php?ConID=$contractID'>";
	
}else{
	pg_query("ROLLBACK");
	echo "<br><center>ไม่สามารถบันทึกได้</center>";
	if($error != ""){echo "<br><center>$error</center>";}
	
	if($errorStep1 == "yes" && $errorStep2 == "")
	{
		echo "<br><center>ไม่สามารถ Run End Of Day ได้ สามารถตรวจสอบข้อผิดพลาดในระบบได้เรียบร้อย</center>";
	}
	elseif($errorStep1 == "" && $errorStep2 == "yes")
	{
		echo "<br><center>สามารถ Run End Of Day ได้สมบูรณ์ แต่ไม่สามารถตรวจสอบข้อผิดพลาดในระบบได้</center>";
	}
	elseif($errorStep1 == "yes" && $errorStep2 == "yes")
	{
		echo "<br><center>ไม่สามารถ Run End Of Day ได้สมบูรณ์ และ ไม่สามารถตรวจสอบข้อผิดพลาดในระบบได้</center>";
	}
	
	echo "<br><br><center><INPUT TYPE=\"BUTTON\" VALUE=\"กลับ\" ONCLICK=\"window.location.href='frm_Index.php?signDate=$signDate'\"> <input type=\"button\" value=\"  Close  \" onclick=\"javascript:window.close();\" class=\"ui-button\"><center>";
}
?>