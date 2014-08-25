<?php
session_start();
include("../../config/config.php");
$nowdate=nowDateTime();
$iduser = $_SESSION["av_iduser"];

$method = $_POST["method"];
$CusID = $_POST["CusID"];
$type = $_POST["type"];
$phonenum = json_decode(stripcslashes($_POST["phonenum"]));

pg_query("BEGIN WORK");
$status = 0;

if($method=='add'){ //กรณีเป็นการเพิ่มเบอร์โทร
	foreach($phonenum as $key => $value){
		$phone = $value->phone;
		
		//ตรวจสอบว่าข้อมูลซ้ำกับข้อมูลเดิมหรือไม่ (รวมถึงการเพิ่มใหม่ด้วย)
		$qrychk=pg_query("SELECT * FROM ta_phonenumber 
		WHERE \"CusID\"='$CusID' and replace(replace(\"phonenum\",' ',''),'-','')=replace(replace('$phone',' ',''),'-','') and phonetype='$type'");
		$numchk=pg_num_rows($qrychk);
		if($numchk>0){ //ถ้ามีแล้วแสดงว่าข้อมูลซ้ำกันไม่อนุญาตให้บันทึก
			$status=-1;
		}else{ //ถ้าไม่ซ้ำให้บันทึกตามปกติ
			$ins="INSERT INTO ta_phonenumber(
				\"CusID\", phonetype, phonenum, \"doerID\", \"doerStamp\")
				VALUES ('$CusID', '$type', '$phone', '$iduser', '$nowdate')";
			if($resins=pg_query($ins)){
			}else{
				$status++;
			}
		}
	}

}
if($status==-1){
	pg_query("ROLLBACK");
	echo 1;
}else if($status == 0){
	pg_query("COMMIT");
	echo 2;
}else{
	pg_query("ROLLBACK");
	echo 3;
}
?>