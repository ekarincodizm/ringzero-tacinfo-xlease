<?php
session_start();
include("../config/config.php");

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$beginx = pg_escape_string($_POST['beginx']);
$idno = pg_escape_string($_POST['idno']);

pg_query("BEGIN WORK");
$status = 0;

$sql="UPDATE \"Fp\" SET \"P_BEGINX\"='$beginx' WHERE \"IDNO\"='$idno'";
$res=@pg_query($sql);
if(!$res){
    $status++;
}else{
    $rs=pg_query("select \"CreateAccPayment\"('$idno')");
    $rt1=pg_fetch_result($rs,0);
    if(!$rt1){
        $status++;
    }
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) บันทึกต้นทุนรถทางบัญชี', '$add_date')");
	//ACTIONLOG---
    pg_query("COMMIT");
    $data['success'] = true;
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกข้อมูลได้";
}
echo json_encode($data);
?>