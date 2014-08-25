<?php
set_time_limit(0);
session_start();
include("../../../config/config.php");
$s=mssql_select_db("Taxiacc") or die("Can't select database");

$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status=0;

//ดึงข้อมูลทั้งหมดที่ได้กรอกลงไปหรือ migrate ไปมาตรวจสอบ
$qryname = pg_query("SELECT \"CusID\",app_user, app_date FROM \"Customer_Temp\" 
group by \"CusID\", add_user, add_date, app_user, app_date having count(\"CusID\")>1");
$numname=pg_num_rows($qryname);

while($resname=pg_fetch_array($qryname)){
	list($CusID,$app_user,$app_date)=$resname;
	$CusID=trim($CusID);
	$app_user=trim($app_user);
	$app_date=trim($app_date);
	
	$i=0;
	$qrycustomer=pg_query("SELECT \"CustempID\" FROM \"Customer_Temp\" where \"CusID\"='$CusID' and \"app_user\"='$app_user' and \"app_date\"='$app_date' order by \"CustempID\" DESC");
	while($rescus=pg_fetch_array($qrycustomer)){
		list($CustempID)=$rescus;
		
		if($i>0){ //ให้ update ค่าอื่นๆที่ไม่ใช่ record ล่าสุด
			$update="update \"Customer_Temp\" set \"app_user\"=null,\"app_date\"=null where \"CustempID\"='$CustempID'";
			echo "$update<br>";
			if($resup=pg_query($update)){
			}else{
				$status++;
			}
		}
		$i++;
	}
}

if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>แก้ไขข้อมูลเรียบร้อยแล้ว</b></font></div>";
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถแก้ไขข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}
?>
