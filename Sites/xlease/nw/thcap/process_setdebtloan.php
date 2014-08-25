<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");

$cmd = $_REQUEST['cmd'];
$id_user=$_SESSION["av_iduser"];
$currentdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$fpayid = pg_escape_string($_POST["fpayid"]);  
$fpayrefvalue = pg_escape_string($_POST["fpayrefvalue"]);
$datepicker = pg_escape_string($_POST["datepicker"]);
$fpayamp = pg_escape_string($_POST["fpayamp"]);
$contractID = pg_escape_string($_POST["contractID"]);
$remark = checknull(pg_escape_string($_POST["remark"]));
$maturityDatepicker = checknull(pg_escape_string($_POST["maturityDatepicker"])); // วันที่ครบกำหนดชำระ

//$debtinvoiceID = gen_debtinvoiceID($datepicker, $contractID); //หาเลขที่ใบแจ้งหนี้
pg_query("BEGIN WORK");

	//ตรวจสอบข้อมูลการตั้งหนี้ซ้ำ
		$qry_chk = pg_query("	
								SELECT 	count(*) from public.\"thcap_temp_otherpay_debt\" 
								WHERE 	\"contractID\" = '$contractID' and 
										\"typePayID\" = '$fpayid' and 
										\"typePayRefValue\" = '$fpayrefvalue' and 
										\"debtStatus\" IN ('9','2','1','5')
						");
		list($row_chk) = pg_fetch_array($qry_chk);	
		IF($row_chk  > 0){
			$status++;
			$same_debt = '1';
		}else{		

			//บันทึกข้อมูล
			$ins=pg_query("SELECT thcap_process_setdebtloan('$contractID','$fpayid','$fpayrefvalue','$datepicker','$fpayamp',$remark,'$id_user','1',null,null,null,$maturityDatepicker)");
			list($return) = pg_fetch_array($ins);
			if($return == 't'){}else{ $status++; }
		}
	

	if($status == 0){
		pg_query("COMMIT");
		echo "1";
	}else{
		pg_query("ROLLBACK");
		if($same_debt != "") // ถ้าตั้งหนี้ซ้ำ
		{
			echo "3";
		}
		else // ถ้าไม่ได้ตั้งหนี้ซ้ำ
		{
			echo "2";
		}
	}
		
// }
	 


?>