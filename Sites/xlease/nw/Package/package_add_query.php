 <?php
 session_start();
 include("../../config/config.php");
 ?>
 
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
<?php

$id_user = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$check = $_POST['chiose'];

$price_not_accessory = $_POST['price_not_accessory'];
$price_accessory = $_POST['price_accessory'];
$month = $_POST['month'];
$period = $_POST['period'];
$interest = $_POST['interest'];
$num = $_POST['num'];
$down = $_POST['down'];
$date = nowDate();
$status = 0;
pg_query("BEGIN");

if($check == 'same'){

	$sql = pg_query("select distinct \"brand\" from \"Fp_package\" where \"numtest\" = '$num' "); 
	$result = pg_fetch_array($sql);
	$brand = $result['brand'];
	
		$sql2 = pg_query("select MAX(\"fpackID\") maxid from \"Fp_package\""); 
		$result2 = pg_fetch_array($sql2);
		$rowssql2 = pg_num_rows($sql2);
		$maxfpack = $result2['maxid'];
		
			
					$sql3 = pg_query(" select MAX(\"fpackID\") maxtemp from \"temp_Fp_package\" ");
						$maxtemp = pg_fetch_array($sql3);
						$maxtempID = $maxtemp['maxtemp'];
						
		if($rowssql2 == 0){
			$maxfpack = null;
		}else{		
			if($maxfpack <= $maxtempID){
			
				for($i = $maxfpack;$i<=$maxtempID;$i++){
				
							$maxfpack++;
					}
			}else{}
		}
	
		
	
		$strSQL1 = "INSERT INTO \"temp_Fp_package\"(\"fpackID\",brand, price_accessory, price_not_accessory, down_payment, period, month_payment, interest, numtest,id_user)
											VALUES ($maxfpack,'$brand','$price_accessory','$price_not_accessory','$down','$period','$month','$interest','$num','$id_user') ";
		$objQuery1 = pg_query($strSQL1);

		
			if($objQuery1){	
			}else{
					$status++;
			}

						// $objQuery2 = pg_query(" select MAX(\"fpackID\") as max from \"temp_Fp_package\" ");
						// $maxid = pg_fetch_array($objQuery2);
						// $fpackID = $maxid['max'];
	
	
			$strSQL3 = "insert into \"approve_Fp_package_add\"(\"fpackID\",\"status\",\"date\")  values($maxfpack,0,'$date')";
			$objQuery3 = pg_query($strSQL3);
	
			if($objQuery3){	
			}else{
					$status++;
			}
			
			
			if($status == 0){
					//ACTIONLOG
						$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) เพิ่ม Package เช่าซื้อ', '$add_date')");
					//ACTIONLOG---
					pg_query("COMMIT");
					
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=package_add.php\">";
							echo "<script type='text/javascript'>alert(' บันทึกข้อมูลเสร็จสิ้น รออนุมัติ ')</script>";
							exit();
			}else{
					pg_query("ROLLBACK");
							echo $strSQL3;
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=package_add.php\">";
							echo "<script type='text/javascript'>alert(' ไม่สามารถบันทึกข้อมูลได้ ')</script>";
							
							exit();
			}
	
}else if($check == 'new'){

	$brand = $_POST['brandtype'];
	
	
	$strSQL1 = "INSERT INTO \"temp_Fp_package\"(brand, price_accessory, price_not_accessory, down_payment, period, month_payment, interest, numtest,id_user)
											VALUES ('$brand','$price_accessory','$price_not_accessory','$down','$period','$month','$interest','$num','$id_user') ";
		$objQuery1 = pg_query($strSQL1);

		
			if($objQuery1){	
			}else{
					$status++;
			}

						$objQuery2 = pg_query(" select MAX(\"fpackID\") as max from \"temp_Fp_package\" ");
						$maxid = pg_fetch_array($objQuery2);
						$fpackID = $maxid['max'];
	
	
			$strSQL3 = "insert into \"approve_Fp_package_add\"(\"fpackID\",\"status\",\"date\")  values($fpackID,0,'$date')";
			$objQuery3 = pg_query($strSQL3);
	
			if($objQuery3){	
			}else{
					$status++;
			}
			
			
			if($status == 0){
				//ACTIONLOG
					$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) เพิ่ม Package เช่าซื้อ', '$add_date')");
				//ACTIONLOG---
					pg_query("COMMIT");
					
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=package_add.php\">";
							echo "<script type='text/javascript'>alert(' บันทึกข้อมูลเสร็จสิ้น รออนุมัติ ')</script>";
							exit();
			}else{
					pg_query("ROLLBACK");
							echo $strSQL3;
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=package_add.php\">";
							echo "<script type='text/javascript'>alert(' ไม่สามารถบันทึกข้อมูลได้ ')</script>";
							
							exit();
			}

}
?>