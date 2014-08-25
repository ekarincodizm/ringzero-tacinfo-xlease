<?php

include("../../config/config.php");
$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$receipt_id =$_REQUEST["receipt_id"];
$reason =$_REQUEST["reason"];
$type = $_REQUEST["typeprint"];
if($type == "all"){
	$typeprint = '{"1","2"}';
}else{
	$typeprint = "{".$type."}";
}
$status =0;	

$query1 =	"INSERT INTO \"thcap_reprint_log\" (
		
										\"receipt_id\",
										\"reprint_reason\",
										\"reprint_user\",
										\"reprint_datetime\",
										type_reprint
										) 
							VALUES(
							           '$receipt_id',
									   '$reason',
									   '$app_user',
									   '$app_date',
									   '$typeprint')";

				
				
if($res_inss=pg_query($query1)){	
		}else{
			$status=$status+1;
			//echo $query1;
		}
echo $status;
	     
?>