<?PHP 
session_start();
include("../../config/config.php");
(int)$repassID = trim($_POST['hdrepassid']);

 $pin =trim($_POST['pin']);
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$strSQL2 = "SELECT \"id_user\" FROM \"repass_admin\" where \"repassID\" = $repassID AND \"repass_pin\" = '$pin'";
$objQuery2 = pg_query($strSQL2);
$result4=pg_fetch_array($objQuery2); 
$nrows=pg_num_rows($objQuery2);



if($nrows == 0){

			echo "<meta http-equiv=\"refresh\" content=\"0; URL=repass_admin.php\">";
			echo "<script type='text/javascript'>alert('PIN ไม่ถูกนะจ๊าาาาาาา')</script>";
			echo "Error Save $sql2";
}else{
				
					pg_query("BEGIN");

					
					$status = 0;
					$status1 = 0;
					$iduser = $result4['id_user'];
					$true = true;
					
						$sql3 = "Update \"fuser\" set \"status_user\" = 'TRUE' where \"id_user\"='$iduser' ";
						$results3=pg_query($sql3);
						
							if($results3)
							{}
							else{
							$status++;
							}
							
							if($status == 0){
						
									$sql4 = "Update \"repass_admin\" set \"repass_status\" = '1',\"appvID\" = '$user_id',\"appv_datetime\" = LOCALTIMESTAMP(0) where \"repassID\" = '$repassID' ";
									$results4=pg_query($sql4);
							
										if($results4)
										{}
										else{
										$status1++;
										}
										
										if($status1==0){
												//ACTIONLOG
													$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) ยืนยันการ Reset Password', '$datelog')");
												//ACTIONLOG---
												pg_query("COMMIT");
												echo "<meta http-equiv=\"refresh\" content=\"0; URL=repass_admin.php\">";
												echo "<script type='text/javascript'>alert('อนุมัติเรียบร้อยแล้ว')</script>";
												
										}else{
										
											pg_query("ROLLBACK");
											echo "<meta http-equiv=\"refresh\" content=\"0; URL=repass_admin.php\">";
											echo "<script type='text/javascript'>alert('การอนุมัติไม่สำเร็จ')</script>";
											echo "Error Save $sql2";
											exit();
										}
							}else{
							
									pg_query("ROLLBACK");
									echo "<meta http-equiv=\"refresh\" content=\"0; URL=repass_admin.php\">";
									echo "<script type='text/javascript'>alert('การอนุมัติไม่สำเร็จ')</script>";
									echo "Error Save $sql2";
									exit();
							}
						
												
				
}
						

?>