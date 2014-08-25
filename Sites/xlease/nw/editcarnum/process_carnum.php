<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$add_user = $_SESSION["av_iduser"];
$add_stamp = nowDateTime();

//$method=$_POST["method"]; //สถานะการอนุมัติ
$car_num=$_POST["car_num"]; //เลขตัวถังที่แก้ไข
$car_num_old=$_POST["car_num_old"]; //เลขตัวถังเก่า
$CarID=$_POST["CarID"]; //รหัสรถยนต์
$idno=$_POST["idno"]; //เลขที่สัญญา


//$sendfrom = มาจาก show_approve.php เพื่อ alert ข้อความ
if(isset($_POST["btn1"])){
	$sendfrom="showapprove";
	$method='approve';//อนุมัติ
}else if(isset($_POST["btn2"])){
	$sendfrom="showapprove";
	$method='noapp';//ไม่อนุมัติ
}

pg_query("BEGIN WORK");
$status=0;

if($method=="approve" || $method=="noapp"){ //กรณีเป็นในส่วนอนุมัติ
	$auto_id=$_POST["auto_id"];
	$result=checknull($_POST["result"]);
	
	//ค้นหาข้อมูลที่อนุมัติ
	$qrydata=pg_query("select \"CarID\",\"CARNUM_NEW\" from \"Carnum_Temp\"
				where auto_id='$auto_id' and \"appStatus\"='2'");
	$numidno=pg_num_rows($qrydata);
	list($CarID,$CARNUM_NEW)=pg_fetch_array($qrydata);
	if($numidno==0){
		$status=-1; //ไม่พบรายการที่จะอนุมัติ
	}else{
		if($method=="approve"){ //กรณีอนุมัติ
			$appstatus=1;
			
			/*update ข้อมูลว่ามีการแก้ไขเลขถัง โดยจะแก้ไขทุกสัญญาที่มีรหัสรถยนต์เหมือนกัน*/
				//update Fc
				$upfc="UPDATE \"Fc\" SET \"C_CARNUM\"='$CARNUM_NEW' WHERE \"CarID\"='$CarID'";
				if($resfc=pg_query($upfc)){
				}else{
					$status++;
				}
				
				//update FGas
				$upgas="UPDATE \"FGas\" SET \"carnum\"='$CARNUM_NEW' WHERE \"GasID\"='$CarID'";
				if($resfc=pg_query($upgas)){
				}else{
					$status++;
				}
			
				//ค้นหาทุกเลขที่สัญญาที่มีรหัสรถยนต์เหมือนกัน
				$qryfp=pg_query("select \"IDNO\" from \"Fp\" where asset_id='$CarID'");
				while($resfp=pg_fetch_array($qryfp)){
					$idnofp=$resfp["IDNO"];
					
					$instemp="INSERT INTO \"Carregis_temp\"(
							\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
							\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
							\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
							\"RadioID\", \"CarType\", fc_type, fc_brand, fc_model, fc_category, 
							fc_newcar, fc_gas,type_in_act)
						SELECT \"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
						   \"C_COLOR\", '$CARNUM_NEW', \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
						   \"C_TAX_MON\", \"C_StartDate\", \"CarID\", '$add_user', '$add_stamp', \"C_CAR_CC\", 
						   \"RadioID\", \"CarType\", fc_type, fc_brand, fc_model, fc_category, 
						   fc_newcar, fc_gas,type_in_act
						FROM \"Carregis_temp\" WHERE \"IDNO\"='$idnofp' ORDER BY \"auto_id\" DESC LIMIT 1";

					if($restemp=pg_query($instemp)){
					}else{
						$status++;
					}
				}
			/*จบการ update และ insert ข้อมูล*/	
		}else{ //กรณีไม่อนุมัติ
			$appstatus=0;
		}
		
		$update="UPDATE \"Carnum_Temp\"
			SET \"appUser\"='$add_user', \"appStamp\"='$add_stamp', \"appStatus\"='$appstatus',\"result\"=$result
			WHERE auto_id='$auto_id'";
		if($resup=pg_query($update)){
		}else{
			$status++;
		}
	}
}else{
	if($car_num!=$car_num_old){
		//ตรวจสอบข้อมูลว่ากำลังรออนุมัติอยู่หรือไม่
		$qrychk=pg_query("select * from \"Carnum_Temp\" where \"IDNO\"='$idno' and \"CarID\"='$CarID' and \"appStatus\"='2'");
		$numchk=pg_num_rows($qrychk);
		
		if($numchk==0){ //แสดงว่าไม่มีการรอนุมัติสามารถขอแก้ไขได้
			//insert ข้อมูลในตาราง Temp
			$ins="INSERT INTO \"Carnum_Temp\"(
						\"IDNO\", \"CarID\", \"CARNUM_OLD\", \"CARNUM_NEW\", \"addUser\", \"addStamp\", \"appStatus\")
				VALUES ('$idno', '$CarID', '$car_num_old', '$car_num', '$add_user', '$add_stamp', '2')";
			if($res=pg_query($ins)){
			}else{
				$status++;
			}
		}else{
			$status=-1; //กรณีรายการรออนุมัติอยู่
		}
	}else{
		$status=-2; //กรณีไม่มีการแก้ไขข้อมูล
	}
}

if($status==0){
	pg_query("COMMIT");
	if($sendfrom=="showapprove"){
		$script= '<script language=javascript>';
		if($method=="approve"){
			$script.= " alert('อนุมัติเรียบร้อยแล้ว');";
		}
		else{
			$script.= " alert('ไม่อนุมัติเรียบร้อยแล้ว');";
		}		
		$script.= "	opener.location.reload(true);
					self.close();";
		$script.= '</script>';
		echo $script;
	}
	else{
		echo 1;
	}
}else if($status==-1){
	pg_query("ROLLBACK");
	if($sendfrom=="showapprove"){
		$script= '<script language=javascript>';
		$script.= " alert('ไม่พบรายการอนุมัติ อาจได้รับอนุมัติก่อนหน้านี้ กรุณาตรวจสอบ');";		
		$script.= "	opener.location.reload(true);
					self.close();";
		$script.= '</script>';
		echo $script;
	}
	else{
		echo 2;
	}
}else if($status==-2){
	pg_query("ROLLBACK");
	if($sendfrom=="showapprove"){
		$script= '<script language=javascript>';
		$script.= " alert('ผิดพลาดไม่สามารถอนุมัติได้');";		
		$script.= "	opener.location.reload(true);
					self.close();";
		$script.= '</script>';
		echo $script;
	}
	else{
		echo 3;
	}
}else{
	pg_query("ROLLBACK");
	if($sendfrom=="showapprove"){
		$script= '<script language=javascript>';
		$script.= " alert('ผิดพลาดไม่สามารถอนุมัติได้');";		
		$script.= "	opener.location.reload(true);
					self.close();";
		$script.= '</script>';
		echo $script;
	}
	else{
		echo 4;
	}
}
?>