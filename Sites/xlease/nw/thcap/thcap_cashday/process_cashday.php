<?php
session_start();
include("../../../config/config.php");
include("../../function/checknull.php");
$id_user = $_SESSION["av_iduser"];
$nowdate = nowDateTime();
$date=nowDate();

$method=$_POST["method"]; //ประเภทการจัดการ
$receiveuserid=$_POST["receiveuserid"]; //user ที่ทำการชำระเงิน
$auditdate=$_POST["auditdate"]; //วันที่รับชำระเงิน

pg_query("BEGIN WORK");
$status=0;	

if($method=='approve'){ //กรณีตรวจสอบถูกต้อง
	$stsapp=$_POST['stsapp'];//สถานะการตรวจสอบ
	
	//ตรวจสอบว่ารายการนี้รอตรวจสอบหรือไม่
	$qrychkapp=pg_query("select * from \"thcap_audit_cashday\" where \"receiveuserid\"='$receiveuserid' and \"auditdate\"='$auditdate' and \"status\" in ('0','2')");
	if(pg_num_rows($qrychkapp)>0){ //แสดงว่ายังมีรายการรอตรวจสอบอยู่
		if($stsapp=='yes'){ //แสดงว่าตรวจสอบแล้วถูำกต้อง
			//update ตาราง thcap_audit_cashday ว่าอนุมัติแล้ว
			$uptemp="UPDATE \"thcap_audit_cashday\" SET \"status\"='1', \"audituserid\"='$id_user', \"auditstamp\"='$nowdate'
			where \"receiveuserid\"='$receiveuserid' and \"auditdate\"='$auditdate' and \"status\" in ('0','2')";
			if($resup=pg_query($uptemp)){
			}else{
				$status++;
			}
		}
	}else{
		$status=-1;
	}
	
}
$script= '<script language=javascript>';
if($status==-1){
	pg_query("ROLLBACK");
	$script.= " alert('ไม่พบรายการตรวจรับเงินสด อาจได้รับการตรวจรับก่อนหน้านี้แล้ว');";
	//echo 1;
}else if($status == 0){
	pg_query("COMMIT");
	$script.= " alert('บันทึกการตรวจรับเงินสดเรียบร้อยแล้ว');";
	//echo 2;
}else{
	pg_query("ROLLBACK");
	$script.= " alert('ผิดพลาดไม่สามารถตรวจรับเงินสดได้');";
	//echo 3;
}
$script.= " opener.location.reload(true);";
$script.= " self.close();";
$script.= '</script>';
echo $script;

