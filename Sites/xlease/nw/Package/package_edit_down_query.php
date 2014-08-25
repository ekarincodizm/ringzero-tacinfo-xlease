<?php
session_start();

include("../../config/config.php");
 ?>
 
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
<?php
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$numtest = $_POST['numtest'];
$down = $_POST['down'];
$downsame = $_POST['downsame'];
$date = nowDate();

$status = 0;

		pg_query('BEGIN');	


			for($q=0;$q<sizeof($down);$q++){
			
		if($down[$q] == $downsame[$q]){
			
				
			}else{
			
			$output = "select * from \"Fp_package\" where \"numtest\" = $numtest and cast(\"down_payment\" as character varying(50)) = '$downsame[$q]'";
				$outputfpackID = pg_query($output);
				
				while($re = pg_fetch_array($outputfpackID)){
				$fpackID = $re['fpackID'];

			
			
				$strSQL1 = "insert into \"approve_Fp_package\"(\"fpackID\",\"down_payment\",\"down_payment_same\",\"numtest\",\"status\",\"date\")  values('$fpackID','$down[$q]','$downsame[$q]','$numtest',0,'$date')";
				$objQuery1 = pg_query($strSQL1);
			
			}
			
			
			
			
				// $strSQL = "update \"Fp_package\" SET \"down_payment\" = $down[$q] where \"numtest\" = $numtest and cast(\"down_payment\" as character varying(50)) = '$downsame[$q]'";
				// $objQuery = pg_query($strSQL);
			
				
				if($objQuery1){
				}else{
					$status++;
				}
			}
		}
			
			if($status == 0){
				//ACTIONLOG
					$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไข Package เช่าซื้อ', '$add_date')");
				//ACTIONLOG---
					pg_query("COMMIT");
					
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=package_edit.php?value=down&brand=$numtest\">";
							echo "<script type='text/javascript'>alert(' เปลี่ยนราคาดาวน์เรียบร้อย ')</script>";
							exit();
			}else{
					pg_query("ROLLBACK");
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=package_edit.php?value=down&brand=$numtest\">";
							echo "<script type='text/javascript'>alert(' ไม่สามารถเปลี่ยนราคาดาวน์ได้ ')</script>";
							echo $strSQL;
							exit();
			}
			
?>