<?php
include("../../config/config.php");
$datepick=$_REQUEST['datepick'];
$bank=$_REQUEST['bank'];

//ตรวจสอบว่ามีรายการอนุมัติแล้วหรือไม่
$qrycheck=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where 
\"appvXID\" is null and \"bankRevAccID\"='$bank' and date(\"bankRevStamp\")='$datepick'");
$num_chk=pg_num_rows($qrycheck);

if($num_chk>0){ //แสดงว่ายังไม่ได้อนุมัติสามารถเพิ่มได้
	echo 1;
}else{
	//ตรวจสอบก่อนว่ามีข้อมูลหรือไม่ ถ้ามีคือมีการอนุมัิติแล้ว แต่ถ้าไม่มีคือยังไม่มีการเพิ่มข้อมูล
	$qrycheck=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where 
	\"bankRevAccID\"='$bank' and date(\"bankRevStamp\")='$datepick'");
	$num_chk=pg_num_rows($qrycheck);
	
	if($num_chk>0){
		echo 0; //ไม่สามารถเพิ่มข้อมูล
	}else{
		echo 1;
	}
}

?>