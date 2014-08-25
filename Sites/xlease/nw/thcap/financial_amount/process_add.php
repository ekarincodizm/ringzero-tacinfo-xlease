<?php
session_start();
include("../../../config/config.php");
include("../../function/checknull.php");
$id_user = $_SESSION["av_iduser"];
$datetime = date("Y-m-d H:i:s");
$contractid = $_POST["conid"];
$textnote = $_POST["textnote"];
$addmoney = $_POST["addmoney"];
$valuefee = $_POST["valuevat"];
$status = 0;
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html>
	<head>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	</head>

<?php

//ตรวจสอบว่าเลขที่สัญญานี้รออนุมัติวงเงินอยุ่หรือไม่
$chk_con = pg_query("SELECT * FROM \"thcap_financial_amount_add_temp\" WHERE \"contractID\" = '$contractid' AND appstatus = '0'");
$chk_row = pg_num_rows($chk_con);
IF($chk_row > 0){
	echo "<center><h1>เลขที่สัญญานี้ รออนุมัติเพิ่มวงเงินอยู่ กรุณาลองใหม่ในภายหลัง</h1></center>";
	echo "<meta http-equiv=\"refresh\" content=\"1; URL=index.php?conid=$contractid\">";
}else{

	//หากไม่มีการเก็บค่าวิเคราะห์ ให้เป้นค่าว่าง
	if($valuefee == ""){
		$sendsummore = "";
	}else{
		$sendsummore = $_POST["sendsummore"];
	}

	$sendsummore = checknull($sendsummore);
	$valuefee = checknull($valuefee );

	pg_query("BEGIN");

				$sql_head1=pg_query("select * from \"thcap_contract\" where \"contractID\" = '$contractid' ");
				$result=pg_fetch_array($sql_head1);
				$conCredit = $result["conCredit"]; //วงเงินสินเชื่อ
					
					$sql_sel_addtime = pg_query("select MAX(\"addtime\") from thcap_financial_amount_add_temp where \"contractID\" = '$contractid' ");
					list($addtimemax)=pg_fetch_array($sql_sel_addtime);
					if($addtimemax == ""){
						$addtimemax = 0;
					}else{
						$addtimemax += 1;
					}
					
					$financial_amount_new = $addmoney + $conCredit;
						
					$sql_in = pg_query("INSERT INTO thcap_financial_amount_add_temp(\"contractID\", financial_amount_old,financial_amount_add, financial_amount_new, fee, feeandvat, note,add_user, add_date, addtime, appstatus)
										VALUES ('$contractid', '$conCredit', '$addmoney', '$financial_amount_new',$valuefee,$sendsummore,'$textnote','$id_user', '$datetime', '$addtimemax', 0)");
					IF($sql_in){}else{$status++;}
					
	if($status == 0){
					
		pg_query("COMMIT");
		
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php?conid=$contractid\">";
		echo "<script type='text/javascript'>alert('บันทึกสำเร็จ !')</script>";
		exit();			
					
					
	}else{
					
		pg_query("ROLLBACK");
		
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php?conid=$contractid\">";
		echo "<script type='text/javascript'>alert('เกิดข้อผิดพลาดในการบันทึก กรุณาลองใหม่ภายหลัง !')</script>";
		exit();				
					
	}
}	
	?>
	
</html>	