<?php
session_start();
include("../config/config.php");
set_time_limit(0); // ให้ใช้เวลาในการ run เท่าไหร่ก็ได้

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$datepicker=pg_escape_string($_POST['datepicker']);

$rs=pg_query("select account.\"CreateEFTEndYear\"('$datepicker')");
$rt1=pg_fetch_result($rs,0);
if(!$rt1){
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกข้อมูลได้";
}else{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ทำรายการรับรู้รายได้', '$add_date')");
	//ACTIONLOG---
    $data['success'] = true;
    $data['message'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
}

echo json_encode($data);
?>