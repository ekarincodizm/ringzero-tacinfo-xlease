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
$period = $_POST['period'];
$periodsame = $_POST['periodsame'];
$time = $_POST['time'];
$date = nowDate();

$status = 0;

		pg_query('BEGIN');	
	
		for($q=0;$q<sizeof($period);$q++){	
		
			if($period[$q] == $periodsame[$q]){
			
				
			}else{
			$output = "select * from \"Fp_package\" where \"numtest\" = $numtest and cast(\"down_payment\" as character varying(50)) = '$down[$q]' and \"period\" = '$periodsame[$q]'";
				$outputfpackID = pg_query($output);
				
				$re = pg_fetch_array($outputfpackID);
				$fpackID = $re['fpackID'];

			
			
			$strSQL1 = "insert into \"approve_Fp_package\"(\"fpackID\",\"period\",\"period_same\",\"numtest\",\"status\",\"month\",\"date\")  values('$fpackID','$period[$q]','$periodsame[$q]','$numtest',0,$time,'$date')";
				$objQuery1 = pg_query($strSQL1);
			
				
				
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
					
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=package_edit.php?value=period&brand=$numtest&period=$time\">";
							echo "<script type='text/javascript'>alert(' ยื่นเรื่องขออนุมัติการเปลี่ยนค่างวด เรียบร้อย ')</script>";
							exit();
			}else{
					pg_query("ROLLBACK");
							//echo "<meta http-equiv=\"refresh\" content=\"0; URL=package_edit.php?value=period&brand=$numtest&period=$time\">";
							echo "<script type='text/javascript'>alert(' ไม่สามารถเปลี่ยนค่างวดได้ ')</script>";
							echo $strSQL1;
							exit();
			}
			
?>