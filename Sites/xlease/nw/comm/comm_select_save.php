<?php
session_start();
include("../../config/config.php");

$comm=$_POST['comm'];
$idno=$_POST['idno'];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
pg_query("BEGIN WORK");

$status = 0;

$qr=pg_query("select \"CreateAccPayment\"('$idno')");
$rs=pg_fetch_result($qr,0);
if(!$rs){
    $status++;
}

$resuilt = pg_query("UPDATE \"Fp\" SET \"Comm\"='$comm' WHERE \"IDNO\"='$idno' ");
if(!$resuilt){
    $status++;
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) ใส่ค่าคอมมิชชั่น', '$add_date')");
	//ACTIONLOG---
    pg_query("COMMIT");
    $data['success'] = true;
    $data['message'] = "บันทึกเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง!!";
}
echo json_encode($data);
?>