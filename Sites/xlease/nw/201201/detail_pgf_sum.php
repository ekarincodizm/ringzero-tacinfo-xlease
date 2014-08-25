<?php
session_start();
include("../../config/config.php");
include("../../core/core_thcap_cal.php");


$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$month=$_POST["mount"];
$year=$_POST["year"];

$query1 = "select \"thcap_process_genCloseMonth\"('$month','$year')";
if($res=pg_query($query1)){
}else{
	$status++;
	$up_error=$res;
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) Process คำนวณดอกเบี้ยชั่วคราว จัดการยอดสรุปสิ้นเดือน', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
    echo "1";
	
}else{
	pg_query("ROLLBACK");
	echo "2";

}		
?>

