<?php
session_start();
include("../config/config.php");
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$cid = $_POST['cid'];
$typeac = $_POST['typeac'];
$amtdr = $_POST['amtdr'];
$amtcr = $_POST['amtcr'];

pg_query("BEGIN WORK");
$status = 0;

$up_sql="UPDATE \"account\".\"AccountBookDetail\" SET \"AcID\"='$typeac',\"AmtDr\"='$amtdr',\"AmtCr\"='$amtcr' WHERE \"auto_id\"='$cid'";
if(!$res_up_sql=@pg_query($up_sql)){
    $status++;
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) แก้ไขบัญชียกมา', '$add_date')");
	//ACTIONLOG---
    pg_query("COMMIT");
    $data['success'] = true;
    $data['message'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกได้!";
}

echo json_encode($data);
?>