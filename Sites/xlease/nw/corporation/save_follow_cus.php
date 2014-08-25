<?php
session_start();
include("../../config/config.php");
$get_userid = $_SESSION["av_iduser"];
$corpID= trim($_POST['corpID']);
$detail = trim($_POST['followdetail']);
$date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$status = 0 ;
pg_query("BEGIN");

$sqlin = "INSERT INTO th_corp_follow_cus(\"corpID\", id_user, fol_detail, fol_date) VALUES ('$corpID', '$get_userid', '$detail', '$date')";
$sqlque = pg_query($sqlin);

if($sqlque){

}else{

$status++;
}
	if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', '(ALL) ลงบันทึกการติดตาม-ลูกค้านิติบุคคล', '$date')");
		//ACTIONLOG---
		pg_query("COMMIT");
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=follow_cus.php?corpID=$corpID\">";
		echo "<script type='text/javascript'>alert('Save successful')</script>";
	}else{
		pg_query("ROLLBACK");
		echo "<center>Error"."<p>";
		echo $sqlin."</center>";
		echo "<meta http-equiv=\"refresh\" content=\"5; URL=follow_cus.php?corpID=$corpID\">";
		echo "<script type='text/javascript'>alert('Save successful')</script>";
		
	}

?>