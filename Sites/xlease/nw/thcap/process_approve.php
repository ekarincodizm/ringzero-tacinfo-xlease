<?php
session_start();
include("../../config/config.php");
include("../function/emplevel.php");

$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

// ระดับสิทธิพนักงาน
$emplevel = emplevel($app_user);

$debtID=pg_escape_string($_GET["debtID"]); 
if($debtID==""){
	$post="p";
	$debtID=pg_escape_string($_POST["debtID"]);
	$statusapp=pg_escape_string($_POST["stsapp"]);  
	$typeapp=pg_escape_string($_REQUEST["typeapp"]); }
else{
	$statusapp=pg_escape_string($_GET["stsapp"]);  
	$typeapp=pg_escape_string($_REQUEST["typeapp"]); 
}

if($statusapp=='1'){
	$debstatus='1';
}else{
	$debstatus='0';
}
//  อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน)
$qry_check=pg_query("select * from thcap_temp_otherpay_debt where \"debtID\"='$debtID' and \"debtStatus\" ='9'");
$num_check=pg_num_rows($qry_check);
if($num_check == 0)
{
	if($post=="p"){echo "3";}
	else{
	echo $insnw;
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>มีการทำรายการไปก่อนหน้านี้แล้ว</b></font><br>";
	echo "<input type=button value=\"กลับไปทำรายการ \" onclick=\"window.location='approve_setdept.php'\">";
	}
}
else
{ //กรณียังไม่ได้รับการอนุมัติก่อนหน้านี้
	pg_query("BEGIN WORK");
	
	// หาพนักงานที่ขอตั้งหนี้
	$qry = pg_query("select \"doerID\" from \"thcap_temp_otherpay_debt\" where \"debtID\" = '$debtID' ");
	$doerID = pg_fetch_result($qry,0);
	
	// ตรวจสอบสิทธิ
	if($app_user != $doerID || $emplevel <= 1)
	{ // ถ้า คนละคนกัน หรือ level น้อยกว่าหรือเท่ากับ 0 สามารถทำงานได้ตามปกติ
		//บันทึกข้อมูล
		$ins=pg_query("SELECT thcap_process_setdebtloan(null,null,null,null,null,null,null,'2','$debtID','$debstatus','$app_user')");
		list($status) = pg_fetch_array($ins);
		
		if($status == 't'){
			//ACTIONLOG
				// $sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '(THCAP) อนุมัติการตั้งหนี้เงินกู้', '$app_date')");
			//ACTIONLOG---
			pg_query("COMMIT");
			if($post=="p"){echo "1";}
			else{
			echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
			echo "<meta http-equiv='refresh' content='2; URL=approve_setdept.php'>";}
		}else{
			pg_query("ROLLBACK");
			if($post=="p"){echo "2";}
			else{
				echo $insnw;
				echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font><br>";
				echo "<input type=button value=\"กลับไปทำรายการ \" onclick=\"window.location='approve_setdept.php'\">";
			}
		}
	}
	else
	{
		if($post=="p"){echo "4";}
		else{
			echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถดำเนินการได้ ผู้อนุมัติจะต้องเป็นบุคคล คนละคนกับ ผู้ตั้งหนี้</b></font><br>";
			echo "<input type=button value=\"กลับไปทำรายการ \" onclick=\"window.location='approve_setdept.php'\">";
		}
	}
}
?>