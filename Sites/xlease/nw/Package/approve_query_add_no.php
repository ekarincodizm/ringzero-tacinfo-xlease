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
$appfID = $_POST['appfID'];

$reason = $_POST['reason'];

$status = 0 ;

pg_query("BEGIN WORK");
for($z=0;$z<sizeof($appfID);$z++)
{

							$strSQL1 = "update \"approve_Fp_package_add\" SET \"status\" = 2,\"reason\" = '$reason' where \"appfpackaddID\" = '$appfID[$z]'";

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
										echo "<script type='text/javascript'>alert(' ปฎิเสธการขออนุมัติการ ขอเพิ่ม Package เรียบร้อย ')</script>";
										exit();
						}else{
								pg_query("ROLLBACK");
										echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve_index.php\">";
										echo "<script type='text/javascript'>alert(' ไม่สามารถปฎิเสธการขอเพิ่ม Package ได้')</script>";
									
										exit();
						}
						

						
			

?>