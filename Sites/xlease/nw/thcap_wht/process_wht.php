<?php
session_start();
include("../../config/config.php");
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php
$add_user = $_SESSION["av_iduser"];
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$cmd=$_REQUEST["cmd"]; 
$receiptid=$_REQUEST["receiptid"];  

pg_query("BEGIN WORK");
$status = 0;

if($cmd=="add"){ //กำหนดว่าเป็นการ approve ของการตั้งหนี้เงินกู้
	$ins="INSERT INTO thcap_asset_wht(\"receiptID\", \"recUser\", \"recStamp\",\"statusReceiptID\") VALUES ('$receiptid', '$add_user', '$add_date','1');";
	if($res=pg_query($ins)){
	}else{
		$status++;
	}	
}	
	
if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$add_user', '(THCAP) ทำการรับใบ WHT', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	//echo "1";
	$script= '<script language=javascript>';
	$script.= " alert('บันทึกรายการเรียบร้อย');location.href='frm_Index.php';";
	$script.= '</script>';
	echo $script;

}else{
	pg_query("ROLLBACK");
	//echo "2";
	$script= '<script language=javascript>';
	$script.= " alert('ผิดผลาด ไม่สามารถบันทึกได้!');location.href='frm_Index.php';";
	$script.= '</script>';
	echo $script;
	
}
?>
