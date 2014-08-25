<?php

include("../config/config.php");
$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$receipt_id =pg_escape_string($_REQUEST["receipt_id"]);
$reason =pg_escape_string($_REQUEST["reason"]);

$status =0;	

$query1 =	"INSERT INTO \"reprint_log\" (
		
										\"receipt_id\",
										\"reprint_reason\",
										\"reprint_user\",
										\"reprint_datetime\"
										
										
										) 
							VALUES(
							           '$receipt_id',
									   '$reason',
									   '$app_user',
									   '$app_date')";

				
				
if($res_inss=pg_query($query1)){	
		}else{
			$status=$status+1;
			//echo $query1;
		}
//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '(TAL) พิมพ์ใบเสร็จซ้ำ', '$app_date')");
//ACTIONLOG---			
echo $status;
	     
?>