<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
$db1="ta_mortgage_datastore";

$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status=0;

////////////////////////////****************************การเพิ่มข้อมูลในส่วนนี้ให้เพิ่มแค่ครั้งเดียวเท่านั้น*************************/////////////////////
//ดึงข้อมูลเลขที่สัญญาทั้งหมดขึ้นมาเพื่อ insert ใน ตาราง thcap_ContactCus 
$qrycontract=mysql_query("select contract_loans_code,miscellaneous_code,
case when(cus_group_type_code='01') then 0 else 1 end 'CusState' from $db1.vcustomerbycontract
where substr(contract_loans_code,1,2)<>'LI'");
$num=mysql_num_rows($qrycontract);
while($rescontract=mysql_fetch_array($qrycontract)){
	
	list($contractID,$Cus,$CusState)=$rescontract;
	
	//ตรวจสอบก่อนว่ามีข้อมูลนี้ในฐานหรือยัง ถ้ายังให้ insert
	$qrycontractchk=pg_query("select * from \"thcap_ContactCus\" where \"contractID\"='$contractID' and \"CusID\"='$Cus'");
	$numcontract=pg_num_rows($qrycontractchk);
	
	if($numcontract==0){ //ไม่พบข้อมูล		
		$inscon="INSERT INTO \"thcap_ContactCus\" (\"contractID\", \"CusState\", \"CusID\") VALUES ('$contractID', '$CusState', '$Cus')";
		if($rescon=pg_query($inscon)){
		}else{
			$status++;
		}
		
	}
}
////////////////////////////****************************การเพิ่มข้อมูลในส่วนนี้ให้เพิ่มแค่ครั้งเดียวเท่านั้น*************************/////////////////////

if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>เพิ่มข้อมูลเรียบร้อยแล้ว กรุณาทำขั้นตอนที่ 2</b></font></div>";
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}


?>
