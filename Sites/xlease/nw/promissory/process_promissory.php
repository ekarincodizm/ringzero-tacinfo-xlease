<?php
session_start();
include("../../config/config.php");

$cmd = pg_escape_string($_REQUEST['cmd']);
$id_user=$_SESSION["av_iduser"];
$currentdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

// รับค่าไม่ว่าจะเพิ่ม หรือแก้ไข ที่จะต้องมี
$boeNumber = pg_escape_string($_POST["boeNumber"]);
$payUser = pg_escape_string($_POST["payUser"]);
$purchaseUser = pg_escape_string($_POST["purchaseUser"]);
$loan_amount = pg_escape_string($_POST["loan_amount"]);
$interest = pg_escape_string($_POST["interest"]);
$payDate = pg_escape_string($_POST["payDate"]);
$returnDate = pg_escape_string($_POST["returnDate"]);
$receivechqno = pg_escape_string($_POST["receivechqno"]);
$receivepaybackamt = pg_escape_string($_POST["receivepaybackamt"]);
$receivewhtamt = pg_escape_string($_POST["receivewhtamt"]);
$receivewhtref = pg_escape_string($_POST["receivewhtref"]);

// checknull สำหรับการบันทึก
if($returnDate==""){
	$returnDate="null";
}else{
	$returnDate="'$returnDate'";	
}
	
if($receivechqno==""){
	$receivechqno="null";
}else{
	$receivechqno="'$receivechqno'";
}
	
if($receivepaybackamt==""){
	$receivepaybackamt="null";
}else{
	$receivepaybackamt="'$receivepaybackamt'";
}
	
if($receivewhtamt==""){
	$receivewhtamt="null";
}else{
	$receivewhtamt="'$receivewhtamt'";
}
	
if($receivewhtref==""){
	$receivewhtref="null";
}else{
	$receivewhtref="'$receivewhtref'";
}

pg_query("BEGIN WORK");
$status = 0;

if($cmd == "add"){

	//บันทึกข้อมูล
	$ins="INSERT INTO account.boe(
            \"boeNumber\", 
			\"payUser\", 
			\"purchaseUser\", 
			loan_amount, 
            interest, 
			\"payDate\", 
			\"returnDate\", 
			\"keyUser\",
			\"keyDate\",
			receivechqno,
			receivepaybackamt,
			receivewhtamt,
			receivewhtref
		) VALUES (
			'$boeNumber', 
			'$payUser', 
			'$purchaseUser', 
			'$loan_amount',
			'$interest',
			'$payDate', 
			$returnDate, 
			'$id_user', 
			'$currentdate',
			$receivechqno,
			$receivepaybackamt,
			$receivewhtamt,
			$receivewhtref
	);";
	if($resin=pg_query($ins)){
	}else{
		$status++;
	}
	
	if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) เพิ่มตั๋วสัญญาใช้เงินภายใน', '$currentdate')");
		//ACTIONLOG---
		pg_query("COMMIT");
			echo "1";
	}else{
		pg_query("ROLLBACK");
		echo "2";
	}	
}else if($cmd=="lock"){
	$lockboe = pg_escape_string($_POST["lockboe"]); 
}else if($cmd=="edit"){
	$boeID = pg_escape_string($_POST["boeID"]);

	//แก้ไขข้อมูล
	$update="UPDATE account.boe
			SET 
				\"boeNumber\"='$boeNumber', 
				\"payUser\"='$payUser', 
				\"purchaseUser\"='$purchaseUser', 
				loan_amount='$loan_amount', 
				interest='$interest', 
				\"payDate\"='$payDate', 
				\"returnDate\"=$returnDate, 
				\"keyUser\"='$id_user', 
				\"keyDate\"='$currentdate',
				receivechqno = $receivechqno,
				receivepaybackamt = $receivepaybackamt,
				receivewhtamt = $receivewhtamt,
				receivewhtref = $receivewhtref
			WHERE 
				\"boeID\"='$boeID'";
	if($resup=pg_query($update)){
	}else{
		$status++;
	}
	
	if($status == 0){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) แก้ไขตั๋วสัญญาใช้เงินภายใน', '$currentdate')");
		//ACTIONLOG---
		pg_query("COMMIT");
			echo "1";
	}else{
		pg_query("ROLLBACK");
		echo "2";
	}	
}else if($cmd=="checklock"){
	$month=pg_escape_string($_POST["month"]);
	$year=pg_escape_string($_POST["year"]);
	$puruser=pg_escape_string($_POST["puruser"]);
	
	//ตรวจสอบว่ามีการล็อคหมดหรือยัง
	$qryboe=pg_query("SELECT \"boeNumber\" FROM account.boe 
	where \"returnDate\" is not null and \"purchaseUser\"='$puruser' and EXTRACT(MONTH FROM \"returnDate\")='$month' and EXTRACT(YEAR FROM \"returnDate\")='$year' and \"statusTicket\"='TRUE'");
	$numboe=pg_num_rows($qryboe); //ถ้ามีค่าแสดงว่ามีรายการที่ยังไม่ได้ล็อค
	
	if($numboe==0){
		echo "1";
	}else{
		echo "ไม่สามารถออกรายงานได้ กรุณาล็อคเลขที่ตั๋วดังนี้ก่อน จึงจะสามารถออกรายงานได้\n\n";
		while($res=pg_fetch_array($qryboe)){
			list($boeNumber)=$res;
			echo "- $boeNumber\n";
		}
	}
}
 
?>