<?php
session_start();
include("../../../config/config.php");
include("../../function/checknull.php");
$id_user = $_SESSION["av_iduser"];
$datetime = date("Y-m-d H:i:s");
$cmd = $_POST["cmd"];
$appid = $_POST["appid"];
$appid = explode("@",$appid);
$note = checknull($_POST["note"]);
$stauts = 0;

pg_query("BEGIN");

if($cmd == 'app'){
	for($i=0;$i<sizeof($appid);$i++){
		if($appid[$i] != ""){
			$qry_up = pg_query("UPDATE thcap_financial_amount_add_temp SET  appstatus='1', app_user='$id_user', app_date='$datetime' WHERE financial_amount_serial = '$appid[$i]' ");
			if($qry_up){
			
			//ดึงข้อมูลที่เปลี่ยนแปลง
				$qry_sel = pg_query("select * from thcap_financial_amount_add_temp where financial_amount_serial = '$appid[$i]'  ");
				$reqry = pg_fetch_array($qry_sel);
				$conid = $reqry["contractID"];
				$fpayamp = $reqry["feeandvat"];
				$fee = $reqry["fee"];
				$financial_amount_new = $reqry["financial_amount_new"];
			
			//หากมีค่าประเมินและวิเคราะห์
			IF($fee != ""){
				//หาประเภทหนี้
					$qry_contype = pg_query("SELECT account.\"thcap_mg_getRaiseCreditType\"('$conid')");
					list($fpayid) = pg_fetch_array($qry_contype);

				//ตั้งหนี้ค่าประเมินและวิเคราะห์
				
					$ins=pg_query("SELECT thcap_process_setdebtloan('$conid','$fpayid','-','$datetime','$fpayamp','เพิ่มวงเงิน','$id_user')");
					list($return) = pg_fetch_array($ins);
					if($return == 't'){}else{ $status++; }
			}	
			//เพิ่มวงเงินให้สัญญา
				$conup = pg_query("UPDATE \"thcap_contract\" SET \"conCredit\" = '$financial_amount_new' WHERE \"contractID\"='$conid'");
				if($conup){}else{ $status++; }
				
			//---------------------------	
			
			
			}else{ $status++ ;}
		}	
	}
}else if($cmd == 'notapp'){
	for($i=0;$i<sizeof($appid);$i++){
		if($appid[$i] != ""){
			$qry_up = pg_query("UPDATE thcap_financial_amount_add_temp SET  appstatus='2', app_user='$id_user', app_date='$datetime', app_not_note=$note WHERE financial_amount_serial = '$appid[$i]' ");
			if($qry_up){}else{ $status++ ;}
		}	
	}

}
							
if($status == 0){				
	pg_query("COMMIT");
	echo "1";									
}else{				
	pg_query("ROLLBACK");
	echo "2";							
}




?>