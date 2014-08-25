<?php
include("../config/config.php");
session_start();
$id_user=$_SESSION["av_iduser"];
$type_branch=$_POST["type_branch"];
$ref1=$_POST["ref1"];
$ref2=$_POST["ref2"];
$tr_date=$_POST["tr_date"];
$PostID=$_POST["PostID"];
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$datenow=date("Y-m-d");

pg_query("BEGIN WORK");
$status = 0;

$update="update \"TranPay\" set post_on_asa_sys='TRUE',post_on_date='$datenow',\"post_by\"='$id_user' where branch_id='$type_branch' and ref1='$ref1' and ref2='$ref2' and tr_date='$tr_date' and \"PostID\"='$PostID'";
if($result=pg_query($update)){
}else{
	$status++;
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ตัดรายการ Bill Payment', '$datelog')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></div>";

}else{
	pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}

echo "<meta http-equiv=\"refresh\" content=\"2;URL=frm_transpaydate.php?\" >";
?>