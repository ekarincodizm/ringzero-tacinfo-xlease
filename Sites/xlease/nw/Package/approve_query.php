<?php
session_start();
include("../../config/config.php");
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

$appfpackID = $_POST['checkdown'];
$status = 0 ;
$type = $_GET['type'];

if($type == 'period'){
pg_query("BEGIN");		
	
		for($z=0;$z<sizeof($appfpackID);$z++){	
			$app = pg_query("select * from \"approve_Fp_package\"  where \"appfpackID\" = '$appfpackID[$z]'");
			$reapp = pg_fetch_array($app);
			$fpackID = $reapp['fpackID'];
			$period = $reapp['period'];
			
							$strSQL = "update \"Fp_package\" SET \"period\" = $period where \"fpackID\" = '$fpackID'";
							 $objQuery = pg_query($strSQL);
						
							if($objQuery){
							}else{
								$status++;
							}
							
							$strSQL1 = "update \"approve_Fp_package\" SET \"status\" = 1 where \"appfpackID\" = '$appfpackID[$z]'";
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
										echo "<script type='text/javascript'>alert(' อนุมัติการเปลี่ยนค่างวด เรียบร้อย ')</script>";
										exit();
						}else{
								pg_query("ROLLBACK");
										// echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve_index.php\">";
										echo "<script type='text/javascript'>alert(' ไม่สามารถอรุมัติค่างวดได้ ')</script>";
										
										exit();
						}
						
						
	
}else if($type == 'down'){
pg_query("BEGIN");
		for($z=0;$z<sizeof($appfpackID);$z++){

						$app = pg_query("select * from \"approve_Fp_package\"  where \"appfpackID\" = '$appfpackID[$z]'");
						$reapp = pg_fetch_array($app);
						$fpackID = $reapp['fpackID'];
						$downpayment = $reapp['down_payment'];
						$downpaymentsame = $reapp['down_payment_same'];
						$numtest = $reapp['numtest'];
						
										$strSQL = "update \"Fp_package\" SET \"down_payment\" = $downpayment where \"numtest\" = '$numtest' and cast(\"down_payment\" as character varying(50)) = '$downpaymentsame'";
										 $objQuery = pg_query($strSQL);
									
										if($objQuery){
										}else{
											$status++;
										}
										
										$strSQL1 = "update \"approve_Fp_package\" SET \"status\" = 1 where \"numtest\" = '$numtest' and \"down_payment\" is not null and cast(\"down_payment\" as character varying(50)) = '$downpayment'";
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
													echo "<script type='text/javascript'>alert(' อนุมัติการเปลี่ยนเงินดาวน์ เรียบร้อย ')</script>";
													exit();
									}else{
											pg_query("ROLLBACK");
													echo "<meta http-equiv=\"refresh\" content=\"0; URL=approve_index.php\">";
													echo "<script type='text/javascript'>alert(' ไม่สามารถอรุมัติเงินดาวน์ได้ ')</script>";
													echo $strSQL;
													exit();
									}
									
						
}			?>