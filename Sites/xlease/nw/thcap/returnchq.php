<?php
session_start();
include("../../config/config.php");
require_once ("../../core/core_functions.php");

$cmd = $_REQUEST['cmd'];
$currentdate=nowDate();
$currenttime=date('H:i:s');

$nowdate=nowDateTime();
$iduser = $_SESSION["av_iduser"];

pg_query("BEGIN WORK");
$status = 0;

if($cmd=="returnchq"){ //เช็คคืน
	$revTranID=$_POST["revTranID"]; //รหัสเงินโอนที่ต้องการลบ
	$chqKeeperID=$_POST["ChqID"]; //รหัสเก็บเช็ค	

	//หา  revChqID
	$qry_revChqID = pg_query("select \"revChqID\" from finance.thcap_receive_cheque_keeper where \"chqKeeperID\"=$chqKeeperID ");
	$revChqID = pg_fetch_result($qry_revChqID,0);
	
	//ตรวจสอบอีกครั้งว่ามีการทำรายการนี้หรือยัง โดยถ้ายังไม่ทำสถานะต้องเป็น 9
	$qry=pg_query("select cancel from finance.\"thcap_receive_transfer\" where \"revTranID\"='$revTranID' and \"revTranStatus\"='9'");
	$numrow=pg_num_rows($qry);
	
	//กรณีไม่พบข้อมูล
	if($numrow==0){
		$status=-2;
	}else{
		list($cancel)=pg_fetch_array($qry);
		if($cancel=='t'){ //กรณีมีการลบรายการก่อนหน้านี้
			$status=-1;
		}else{ //กรณียังไม่ถูกลบข้อมูล
			
			//เก็บ log ก่อนลบข้อมูล
			if($sqlaction = pg_query("INSERT INTO finance.thcap_receive_transfer_log(detail,\"revTranID\", id_user, \"dateStamp\",\"BAccount\", 
			   \"bankRevBranch\", \"bankRevAmt\", \"bankRevStamp\" , \"chqKeeperID\") 
				SELECT 'เช็คคืนรหัส $revChqID รหัสเงินโอน $revTranID',a.\"revTranID\",'$iduser', '$nowdate',\"BAccount\"||'-'||\"BName\",\"bankRevBranch\",\"bankRevAmt\",\"bankRevStamp\", '$chqKeeperID'
				FROM finance.thcap_receive_transfer a
				LEFT JOIN \"BankInt\" b on a.\"bankRevAccID\"=b.\"BID\"::text
				LEFT JOIN finance.\"thcap_receive_transfer_action\" c on a.\"revTranID\"=c.\"revTranID\"
				WHERE a.\"revTranID\"='$revTranID'")); else $status++;
			//END LOG---
		
			//ให้ update ข้อมูลเป็นเช็คคืน พร้อมกับระบุว่าเป็นเช็คอันไหน
			$qry_up = "UPDATE finance.thcap_receive_transfer SET \"revTranStatus\"='7', \"revChqID\"='$revChqID', \"chqKeeperID\" = '$chqKeeperID' WHERE \"revTranID\"='$revTranID'";
			if($resup=pg_query($qry_up)){
			}else{
				$status++;
			}
			
			$up_action="UPDATE finance.thcap_receive_transfer_action
			SET \"appvYID\"='$iduser', \"appvYStamp\"='$nowdate',\"appvYStatus\"='2'
			WHERE \"revTranID\"='$revTranID'";
				
			if($resup_action=pg_query($up_action)){
			}else{
				$status++;
			}
		}
	}
}

if($status==-1){ //กรณีรายการคืนเช็คขออนุมัติซ้ำ หรืออนุมัติคืนเช็คซ้ำกัน
	pg_query("ROLLBACK");
	$script= '<script language=javascript>';
	$script.= " alert('รายการนี้ได้ลบไปก่อนหน้านี้แล้วกรุณาตรวจสอบ!');
				location.href='frm_Index_finance.php';";
	$script.= '</script>';
	echo $script;
	//echo "3";
}else if($status==-2){
	pg_query("ROLLBACK");
	$script= '<script language=javascript>';
	$script.= " alert('ไม่พบข้อมูลที่จะลบ อาจตรวจสอบรายการหรือถูกลบจากฐานข้อมูลแล้ว กรุณาตรวจสอบ!');
				location.href='frm_Index_finance.php';";
	$script.= '</script>';
	echo $script;
	//echo "4";
}else if($status == 0){
	pg_query("COMMIT");
	$script= '<script language=javascript>';
	$script.= " alert('บันทึกรายการเรียบร้อย');
				location.href='frm_Index_finance.php';";
	$script.= '</script>';
	echo $script;
	//echo "1";
}else{
	pg_query("ROLLBACK");
	$script= '<script language=javascript>';
	$script.= " alert('ผิดผลาด ไม่สามารถบันทึกได้!');
				location.href='frm_Index_finance.php';";
	$script.= '</script>';
	echo $script;
	//echo "2";
	
}
?>