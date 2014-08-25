<?php 
include("../../config/config.php");
include("../function/checknull.php");
session_start();?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$appUser = $_SESSION["av_iduser"]; //รับ id ผู้ใช้
$appdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server // วันที่ปัจจุบัน

	$dcNoteID = pg_escape_string($_POST["dcNoteID"]); //รับรหัสการคืนเงิน
	$stateapp = pg_escape_string($_POST["stateapp"]); //สถานะการอนุมัติ
	$remark = pg_escape_string($_POST["appremark"]);
	$remark = checknull($remark); //เหตุผลการอนุมัติ
	
	// ตรวจสอบว่ากดปุ่มอนุมัติหรือไม่อนุมัติ
	if(isset($_POST["btn_app"])){
		$stateapp='app';//อนุมัติ
	}elseif(isset($_POST["btn_notapp"])){
		$stateapp='notapp';//ไม่อนุมัติ
	}
	
	$status = 0; 
	
	pg_query("BEGIN");
	
	//ชื่อผู้อนุมัติรายการ
			$qry_username = pg_query("SELECT \"fullname\" FROM \"Vfuser\" where \"id_user\" = '$appUser'");
			if($qry_username){}else{$status++;}
			list($app_fullname) = pg_fetch_array($qry_username);
			
	if($stateapp == "app"){ // อนุมติรายการ
	
			$qry_up = pg_query("UPDATE account.thcap_dncn_details SET \"appvID\"='$appUser', 
																	  \"appvName\"='$app_fullname', 
																	  \"appvStamp\"='$appdate',
																	  \"appvRemask\"=$remark      
																WHERE \"dcNoteID\"= '$dcNoteID' ");
																
			$qry_up = pg_query("UPDATE account.thcap_dncn SET \"dcNoteStatus\"='1' WHERE \"dcNoteID\" = '$dcNoteID'");
			if($qry_up){}else{$status++;}

			if($qry_up){}else{$status++;}
	
	}else if($stateapp == "notapp"){ // ไม่อนุมัติรายการ
	
			$qry_up = pg_query("UPDATE account.thcap_dncn_details SET \"appvID\"='$appUser', 
																	  \"appvName\"='$app_fullname', 
																	  \"appvStamp\"='$appdate',
																	  \"appvRemask\"=$remark      
																WHERE \"dcNoteID\"= '$dcNoteID' ");
	
			$qry_up = pg_query("UPDATE account.thcap_dncn SET \"dcNoteStatus\"='0' WHERE \"dcNoteID\" = '$dcNoteID'");
			if($qry_up){}else{$status++;}
			

			if($qry_up){}else{$status++;}
	
	}

if($status == 0){
	pg_query("COMMIT");
	$script= '<script language=javascript>';
	if($stateapp=="notapp"){			
		$script.= " alert('ปฎิเสธการอนุมัติเรียบร้อยแล้ว');
				location.href='frm_approve.php';
				window.opener.document.form[0].btn_notapp.attr('disabled', false);";			
	}else{			
		$script.= " alert('อนุมัติเรียบร้อยแล้ว');
				location.href='frm_approve.php';
				window.opener.document.form[0].btn_app.attr('disabled', false);";
	}
	$script.= '</script>';
	echo $script;		
}else{
	pg_query("ROLLBACK");
	$script= '<script language=javascript>';
	if($stateapp=="notapp"){			
		$script.= " alert('ไม่สามารถปฎิเสธการการอนุมัติได้ โปรดลองใหม่ในภายหลัง !');
				location.href='frm_approve.php';
				window.opener.document.form[0].btn_notapp.attr('disabled', false);";			
	}else{			
		$script.= " alert('ไม่สามารถอนุมัติได้ โปรดลองใหม่ในภายหลัง !');
				location.href='frm_approve.php';
				window.opener.document.form[0].btn_app.attr('disabled', false);";
	}
	$script.= '</script>';
	echo $script;
}	
?>