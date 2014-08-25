<?php
session_start();
include("../../config/config.php");
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
 ?>
 
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
<?php
$appfpackID = $_POST['appfpackID'];
$status = 0 ;
$type = $_POST['type'];
$reason = $_POST['reason'];

if($type == 'period'){	
pg_query("BEGIN");
	for($z=0;$z<sizeof($appfpackID);$z++){
							$strSQL1 = "update \"approve_Fp_package\" SET \"status\" = 2,\"reason\" = '$reason' where \"appfpackID\" = '$appfpackID[$z]'";
							 $objQuery1 = pg_query($strSQL1);
							
							if($objQuery1){
							}else{
								$status++;
							}
}							
						if($status == 0){
								//ACTIONLOG
									$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) อนุมัติ Package เช่าซื้อ', '$datelog')");
								//ACTIONLOG---
								pg_query("COMMIT");
								
										echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve_index.php\">";
										echo "<script type='text/javascript'>alert(' ปฎิเสธการขออนุมัติการเปลี่ยนค่างวด เรียบร้อย ')</script>";
										exit();
						}else{
								pg_query("ROLLBACK");
										echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve_index.php\">";
										echo "<script type='text/javascript'>alert(' ไม่สามารถปฎิเสธอนุมัติค่างวดได้ ')</script>";
										echo $strSQL1;
										exit();
						}
						
}else if($type == 'down'){

pg_query("BEGIN");

for($z=0;$z<sizeof($appfpackID);$z++){
					$strSQL1 = "update \"approve_Fp_package\" SET \"status\" = 2,\"reason\" = '$reason' where \"appfpackID\" = '$appfpackID[$z]'";
											 $objQuery1 = pg_query($strSQL1);
											
											if($objQuery1){
											}else{
												$status++;
											}
}											
										if($status == 0){
												//ACTIONLOG
													$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) อนุมัติ Package เช่าซื้อ', '$datelog')");
												//ACTIONLOG---
												pg_query("COMMIT");
												
														echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve_index.php\">";
														echo "<script type='text/javascript'>alert(' ปฎิเสธการขออนุมัติการเปลี่ยนค่างวด เรียบร้อย ')</script>";
														exit();
										}else{
												pg_query("ROLLBACK");
														echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve_index.php\">";
														echo "<script type='text/javascript'>alert(' ไม่สามารถปฎิเสธอนุมัติค่างวดได้ ')</script>";
														echo $strSQL1;
														exit();
										}

}
						
			

?>