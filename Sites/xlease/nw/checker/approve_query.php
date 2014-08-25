<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
session_start();
include("../../config/config.php");


$check = $_POST['check'];
$check1 = $_GET['Check'];
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$status = 0;
$status1 = 0;

if($check1==""){
	if(isset($_POST["appv"])){ 
		$check1='agree';
	}
}

if($check1 == 'agree'){

	/*$appsecurID = $_GET['appsecurID'];
	$securdeID = $_GET['securdeID'];*/
	$appsecurID = $_POST['appsecurID'];
	$securdeID = $_POST['securdeID'];

	pg_query('BEGIN');			
		$strSQL = "update \"approve_securities_detail\" SET \"status\" = '1' where \"appsecurID\" = '$appsecurID' ";
		$objQuery = pg_query($strSQL);
	
		
		if($objQuery){
		}else{
			$status++;
		}
			if($status == 0){
				$strSQL1 = "insert into \"nw_securities_detail\" select * from \"temp_securities_detail\" where \"securdeID\" = '$securdeID' ";
				$objQuery1 = pg_query($strSQL1);
			
					if($objQuery1){
					}else{
						$status1++;
					}
					
							if($status == 0){
							
								//ACTIONLOG
									$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) ยืนยันบันทึกตรวจสอบหลักทรัพย์', '$datelog')");
								//ACTIONLOG---
								pg_query("COMMIT");
								
										echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve.php\">";
										echo "<script type='text/javascript'>alert('อนุมติ รายการประเมินหลักทรัพย์หมายเลข $securdeID เรียบร้อย')</script>";
										exit();
							}else{
								pg_query("ROLLBACK");
										echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve.php\">";
										echo "<script type='text/javascript'>alert('ไม่สามารถ อนุมัติ การประเมินหลักทรัพย์หมายเลข $securdeID ได้  โปรดลองใหม่ในภายหลัง')</script>";
										exit();
							}
			}else{
					pg_query("ROLLBACK");
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve.php\">";
							echo "<script type='text/javascript'>alert('ไม่สามารถ อนุมัติ การประเมินหลักทรัพย์หมายเลข $securdeID ได้  โปรดลองใหม่ในภายหลัง')</script>";
							exit();
			}
}else if($check == 'del'){
	
	$appsecurID = $_POST['appsecurID'];
	$securdeID = $_POST['securdeID'];
	$reason = $_POST['reason'];

	pg_query('BEGIN');			
		$strSQL = "update \"approve_securities_detail\" SET \"status\" = '2',\"reason\" = '$reason' where \"appsecurID\" = '$appsecurID' ";
		$objQuery = pg_query($strSQL);
	
		
		if($objQuery){
		}else{
			$status++;
		}
			
				if($status == 0){
				
					//ACTIONLOG
						$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) ยืนยันบันทึกตรวจสอบหลักทรัพย์', '$datelog')");
					//ACTIONLOG---
					pg_query("COMMIT");
					
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve.php\">";
							echo "<script type='text/javascript'>alert('ปฎิเสธการประเมินหลักทรัพย์หมายเลข $securdeID เรียบร้อย')</script>";
							exit();
				}else{
					pg_query("ROLLBACK");
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve.php\">";
							echo "<script type='text/javascript'>alert('ไม่สามารถปฎิเสธการประเมินหลักทรัพย์หมายเลข $securdeID ได้ โปรดลองใหม่ในภายหลัง')</script>";
							exit();
				}
		

}else{

		echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve.php\">";
		echo "<script type='text/javascript'>alert('--ข้อมูลมาไม่ครบ ไม่สามรถดำเนินการได้--')</script>";
		exit();

}

?>