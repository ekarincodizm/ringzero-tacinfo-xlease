<?php
include("../../../config/config.php");
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>

<?php
$id_user = $_SESSION["av_iduser"];
$get_groupid = $_SESSION["av_usergroup"];
$col_preID = pg_escape_string($_POST['col_preID']);
$nowdate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$status = 0;

// ตรวจสอบก่อนว่า มีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
$qry_chk_Concurrency = pg_query("select \"colpre_status\" from \"thcap_collect_pre\" where \"colpre_serial\" = '$col_preID' ");
$chk_Concurrency = pg_fetch_result($qry_chk_Concurrency,0);
if($chk_Concurrency != 0)
{
	$status++;
	$Concurrency = "มีการทำรายการไปก่อนหน้านี้แล้ว";
}
else
{
$conmunication = pg_escape_string($_POST['Communication']);
$datacall = pg_escape_string($_POST['calldata']);

$calldate = pg_escape_string($_POST['calldate']);
$hour = pg_escape_string($_POST['hourstart']);
$min = pg_escape_string($_POST['minutsstart']);

$paymentdate = pg_escape_string($_POST['calldate_1']);

$datedata = $calldate." ".$hour.":".$min.":00";

if($conmunication == "calling"){ //รับสาย

	$Destination = pg_escape_string($_POST['Destination']);  //ผู้รับสายเกี่ยวข้องกับสัญญาหรือไม่
	if($Destination == "relation"){	//เกี่ยวข้อง
		$cuscall = pg_escape_string($_POST['cuscall']); //ใครคือผู้รับ
		$statusdata = '1';
		
		$qry_selconforname = pg_query("SELECT \"contractID\" FROM thcap_collect_pre where \"colpre_serial\" = '$col_preID' ");
		list($contractID_frist) = pg_fetch_array($qry_selconforname);
		
		$qry_cusname = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"CusID\" = '$cuscall' AND \"contractID\" = '$contractID_frist' ");
		list($personcall) = pg_fetch_array($qry_cusname);
		

		
	}else if($Destination == "norelation"){ //ไม่เกี่ยวข้อง
		$chknorela1 = pg_escape_string($_POST['chknorela1']);
		$chknorela2 = pg_escape_string($_POST['chknorela2']);
		$chknorela3 = pg_escape_string($_POST['chknorela3']);
		$reasonother = pg_escape_string($_POST['reasonother']);
		$statusdata = '3';
		

		
		$personcall = "บุคคลอื่น\n - ผู้รับสายแจ้งว่า:".$chknorela1." ".$chknorela2." ".$chknorela3." ".$reasonother;
	
	}else{
		$status++;
	}

}else if($conmunication == "misscall"){ //ไม่รับสาย
	$statusdata = '2';
	$personcall = "ไม่รับสาย";
}else if($conmunication == "receiptend"){ //ชำระแล้ว	
	$statusdata = '4';
	$personcall = "ชำระแล้ว";
}else{

	$status++;
}

 $text = "ติดตามหนี้เบื้องต้น\n---------------------------------\nผู้รับสาย:".$personcall."\nรายละเอียด: \n".$datacall;


pg_query("BEGIN");

	$qry_selcol = pg_query("SELECT \"contractID\" FROM thcap_collect_pre where \"colpre_serial\" = '$col_preID' ");
	$re_selcol = pg_fetch_array($qry_selcol);
	$contractID = $re_selcol['contractID'];
	
	//หา tyep ของเลขที่สัญญานี้
	$qrytype=pg_query("select \"thcap_get_contractType\"('$contractID')");
	list($contype)=pg_fetch_array($qrytype);

	/*
	// ได้แก้ไขใน function แทนให้ทำการ INSERT ซ้ำกรณี status = 2 หรือ 3 ในวันถัดไป แทนการ INSERT ซ้ำทันที
	if($statusdata == '2' OR $statusdata == '3'){
			$Debt = $re_selcol['colpre_debtamt'];
			$duedate = $re_selcol['colpre_duedate'];
			$Debt_Details = $re_selcol['colpre_debtdetails'];
			$qry_inre = pg_query("INSERT INTO thcap_collect_pre(\"contractID\",\"colpre_duedate\" ,\"colpre_debtamt\",\"colpre_debtdetails\", colpre_doerid, colpre_doerstamp, colpre_contacttime, colpre_status)
									VALUES ('$contractID','$duedate', '$Debt','$Debt_Details', null, null, null, '0')");
			if($qry_inre){}else{ $status++; }						
	}
	*/

	$qry_in = pg_query("UPDATE thcap_collect_pre SET colpre_doerid='$id_user', colpre_doerstamp='$nowdate', colpre_contacttime='$datedata', colpre_status='$statusdata'
						,\"payment_Date\"='$paymentdate'
						WHERE \"colpre_serial\"= '$col_preID' ");
	if($qry_in){}else{ $status++; }
	
	// ถ้ามีวันที่นัด ให้ระบุวันที่นัดในข้อความที่บันทึกใน บันทึกการติดตามด้วย
	if($paymentdate != ''){
		$text = $text."\nวันที่นัด: $paymentdate";
	}

	$in_sql=pg_query("	INSERT INTO \"thcap_FollowUpContract\" (
																	\"FollowDate\",
																	\"GroupID\",
																	\"userid\",
																	\"contractID\",
																	\"FollowDetail\",
																	\"refFrom\",
																	\"refID\"																	
																) 
														VALUES(		'$datedata',
																	'$get_groupid',
																	'$id_user',
																	'$contractID',
																	'$text',
																	'(THCAP) ติดตามหนี้เบื้องต้น',
																	'$col_preID'
																)
					");
	if($in_sql){}else{ $status++; }
}

	if($status==0){
		pg_query("COMMIT");
		echo "<script type='text/javascript'>alert('Successful')</script>";
		echo "<script type='text/javascript'>
			self.opener.location.replace(\"index.php?page=$contype\");						
			self.close();
		
		</script>";
		
	}else{
		pg_query("ROLLBACK");
		
		echo "<script type='text/javascript'>alert('Error $Concurrency')</script>";
		if($Concurrency != "")
		{
			echo "<script type='text/javascript'>
				self.opener.location.replace(\"index.php\");
				self.close();
				</script>";
		}
		else
		{
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_add_call.php?col_preID=$col_preID\">";
		}
	}
?>
</html>