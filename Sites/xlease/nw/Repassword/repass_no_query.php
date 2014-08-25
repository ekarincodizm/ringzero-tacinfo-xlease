<?PHP 
session_start();
include("../../config/config.php");
$repassID = trim($_GET['PASSID']);

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$strSQL2 = "SELECT * FROM \"repass_admin\" where \"repassID\" = $repassID";
$objQuery2 = pg_query($strSQL2);
$nrows=pg_num_rows($objQuery2);



if($nrows == 0){

			echo "<meta http-equiv=\"refresh\" content=\"0; URL=repass_admin.php\">";
			echo "<script type='text/javascript'>alert('ไม่มีข้อมูลนี้ในระบบ กรุณาลองดูในฐานข้อมูล ระครับ')</script>";
			echo "Error Save $sql2";
}else{
				
					pg_query("BEGIN");

					
					$status = 0;

									$sql4 = "Update \"repass_admin\" set \"repass_status\" = '2' where \"repassID\" = '$repassID' ";
									$results4=pg_query($sql4);
							
										if($results4)
										{}
										else{
										$status++;
										}
										
										if($status==0){
												//ACTIONLOG
													$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) ยืนยันการ Reset Password', '$datelog')");
												//ACTIONLOG---
												pg_query("COMMIT");
												echo "<meta http-equiv=\"refresh\" content=\"0; URL=repass_admin.php\">";
												echo "<script type='text/javascript'>alert('เปลี่ยนสถานะเรียบร้อย')</script>";
												
										}else{
										
											pg_query("ROLLBACK");
											echo "<meta http-equiv=\"refresh\" content=\"0; URL=repass_admin.php\">";
											echo "<script type='text/javascript'>alert('เปลี่ยนสถานะไม่สำเร็จ')</script>";
											echo "Error Save $sql2";
											exit();
										}
						
												
				
}
						

?>