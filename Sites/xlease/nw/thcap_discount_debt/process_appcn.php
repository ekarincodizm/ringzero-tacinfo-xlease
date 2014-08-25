<?php 
session_start();
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
include("../../config/config.php");
include("../function/checknull.php");

$appUser = $_SESSION["av_iduser"]; //รับ id ผู้ใช้
$appdate = nowDateTime(); // วันที่ปัจจุบัน

	$dcNoteID = $_POST["dcNoteID"]; //รับรหัสการคืนเงิน
	//$stateapp = $_POST["stateapp"]; //สถานะการอนุมัติ
	//$remark = checknull($_POST["remark"]); //เหตุผลการอนุมัติ
	$remark = checknull($_POST["appremark"]); //เหตุผลการอนุมัติ มาจาก popup_app.php
	
	if(isset($_POST["btn_app"])){
		$stateapp='app';   //อนุมัติ
	}else{
		$stateapp='notapp';//ไม่อนุมัติ
	}
	
	//ชื่อผู้อนุมัติรายการ
	$qry_username = pg_query("SELECT \"fullname\" FROM \"Vfuser\" where \"id_user\" = '$appUser'");
	list($app_fullname) = pg_fetch_array($qry_username);

	pg_query("BEGIN");
	$status = 0;
	
	// ตรวจสอบก่อนว่ามีการอนุมัติไปก่อนหน้านี้แล้วหรือยัง
	$qry_chkDcNoteStatus = pg_query("select \"dcNoteStatus\" from account.\"thcap_dncn\" where \"dcNoteID\"= '$dcNoteID' ");
	$chkDcNoteStatus = pg_fetch_result($qry_chkDcNoteStatus,0);
	if($chkDcNoteStatus == "1")
	{
		$status++;
		$error = "มีการ อนุมัติ ไปก่อนหน้านี้แล้ว";
	}
	elseif($chkDcNoteStatus == "0")
	{
		$status++;
		$error = "มีการ ปฏิเสธ ไปก่อนหน้านี้แล้ว";
	}
	elseif($chkDcNoteStatus != "8")
	{
		$status++;
	}
	elseif($chkDcNoteStatus == "8")
	{
		if($stateapp == 'app')
		{
			//--- กรณีที่ วันที่ส่วนลดมีผลเป็น null จะใส่วันที่อนุมัติเป็นวันที่ส่วนลดมีผล
				$qry_up = pg_query("UPDATE account.thcap_dncn SET \"dcNoteStatus\"='1', \"dcNoteDate\"='$appdate' WHERE \"dcNoteID\" = '$dcNoteID' AND \"dcNoteStatus\" = '8' AND \"dcNoteDate\" is null ");
				if($qry_up){}else{$status++;}
			//---
			
			//--- กรณีที่ มีการระบุวันที่ส่วนลดมีผลอยู่แล้ว
				$qry_up = pg_query("UPDATE account.thcap_dncn SET \"dcNoteStatus\"='1' WHERE \"dcNoteID\" = '$dcNoteID' AND \"dcNoteStatus\" = '8' AND \"dcNoteDate\" is not null ");
				if($qry_up){}else{$status++;}
			//---
			
			
			// เก็บรายละเอียด
			$qry_up = pg_query("UPDATE account.thcap_dncn_details SET \"appvID\"='$appUser', 
																	  \"appvName\"='$app_fullname', 
																	  \"appvStamp\"='$appdate',
																	  \"appvRemask\"=$remark      
																WHERE \"dcNoteID\"= '$dcNoteID' ");
			if($qry_up){}else{$status++;}
		}
		else if($stateapp == 'notapp')
		{
			$qry_up = pg_query("UPDATE account.thcap_dncn SET \"dcNoteStatus\"='0' WHERE \"dcNoteID\" = '$dcNoteID' AND \"dcNoteStatus\" = '8' ");
			if($qry_up){}else{$status++;}
			
			$qry_up = pg_query("UPDATE account.thcap_dncn_details SET \"appvID\"='$appUser', 
																	  \"appvName\"='$app_fullname', 
																	  \"appvStamp\"='$appdate',
																	  \"appvRemask\"=$remark      
																WHERE \"dcNoteID\" = '$dcNoteID' ");
			if($qry_up){}else{$status++;}
		}
	}

if($status == 0){
	pg_query("COMMIT");
	//echo "1";	
	$script= '<script language=javascript>';
	if($stateapp=='app'){
		$script.= " alert('อนุมัติเรียบร้อยแล้ว');";	
		}
	else{
		$script.= " alert('ปฎิเสธการอนุมัติเรียบร้อยแล้ว');";
		}	
	$script.= "location.href='frm_approve.php';";
	$script.= '</script>';
	echo $script;
}else{
	pg_query("ROLLBACK");
	//echo "2";
	$script= '<script language=javascript>';
	if($stateapp=='app'){
		$script.= " alert('ไม่สามารถอนุมัติได้ โปรดลองใหม่ในภายหลัง !');";		
		}
	else{
		$script.= " alert('ไม่สามารถปฎิเสธการการอนุมัติได้ โปรดลองใหม่ในภายหลัง !');";
		}	
	$script.= "location.href='frm_approve.php';";
	$script.= '</script>';
	echo $script;
}	
?>