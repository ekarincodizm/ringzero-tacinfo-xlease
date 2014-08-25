<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$id_user=$_SESSION["av_iduser"];
	
$AssignNo=pg_escape_string($_POST["AssignNo"]);
$NoteCancle=pg_escape_string($_POST["NoteCancle"]);
$dateTime=nowDateTime();

pg_query("BEGIN WORK");
$status = 0;
	
	//อัดเดตสถานะงาน
	$upkeep="update assign_work_detail set \"WorkStatus\"='0',\"CancleID\"='$id_user',\"CancleStamp\"='$dateTime',\"CancleNote\"='$NoteCancle'
					where \"AssignNo\"='$AssignNo' ";
	if(pg_query($upkeep)){
	
	}else{
		$status++;
	}
	
if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></div>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_Report.php'>";
}else{
	pg_query("ROLLBACK");
	echo $resupkeep."<br>";
	echo $resuprev;
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	echo "<input type=button value=\"  กลับ  \" onclick=\"window.location='frm_Report.php'\">";

}
?>