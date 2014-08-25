<?php
session_start();
include("../config/config.php");

$method = $_REQUEST['method'];

if($method == "searchguide"){	
	$creditID=$_POST["creditID"];
	
	//ตรวจสอบว่าประเภทนี้มีค่าแนะนำหรือไม่
	$querycredit=pg_query("select \"creditReserved\",\"oldidno\" from \"nw_credit\" where \"creditID\"='$creditID'");
	$res=pg_fetch_array($querycredit);
	list($creditReserved,$oldidno)=$res;
	if($creditReserved==1 and $oldidno==1){
		echo "1"; //มีค่าแนะนำและมีสัญญาเก่า
	}else if($creditReserved==1 and $oldidno==0){
		echo "2"; //มีค่าแนะนำและไม่มีสัญญาเก่า
	}else if($creditReserved=="" and $oldidno==1){
		echo "3"; //ไม่มีค่าแนะนำและมีสัญญาเก่า
	}else{
		echo "4"; //ไม่มีค่าแนะนำและไม่มีสัญญาเก่า
	}
}

?>
