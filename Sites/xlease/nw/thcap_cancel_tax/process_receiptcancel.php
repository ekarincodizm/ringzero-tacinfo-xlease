<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$id_user = $_SESSION["av_iduser"];
$contractID=$_REQUEST["contractID"]; 
$taxinvoiceID=$_REQUEST["taxinvoiceID"];
$receiveDate=$_REQUEST["receiveDate"];
$resultcancel=$_REQUEST["resultcancel"];
$cancelID=$_GET['cancelID'];
$method=$_REQUEST["method"];
if(($cancelID=="")and ($method=="")){
	$cancelID=$_POST["cancelID"];
	$contractID=$_POST["contractID"];
	$taxinvoiceID=$_POST["taxinvoiceID"];
	$receiveDate=$_POST["receiveDate"];	
	if(isset($_POST["appv"])){
		$method="approve1";//อนุมัติ
	}else{
		$method="approve0";//ไม่อนุมัติ
	}
}
$curdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>บันทึกข้อมูล</title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">
 <?php
pg_query("BEGIN WORK");
$status = 0;

if($method=="request")
{
	// ตรวจสอบก่อนว่ามีการขออยู่แล้วหรือยัง
	$qry_chk = pg_query("select * from thcap_temp_taxinvoice_cancel where \"contractID\" = '$contractID' and \"taxinvoiceID\" = '$taxinvoiceID' and \"approveStatus\" = '2' ");
	$numrow_chk = pg_num_rows($qry_chk);
	if($numrow_chk > 0)
	{
		$ins_error = $ins_error." มีการขอยกเลิกใบกำกับภาษีดังกล่าวไปแล้ว ";
		$status++;
	}
	
	$ins="INSERT INTO thcap_temp_taxinvoice_cancel(
		\"contractID\", \"taxinvoiceID\", \"requestUser\", \"requestDate\", \"approveStatus\",\"result\")
		VALUES ('$contractID', '$taxinvoiceID', '$id_user', '$curdate', '2','$resultcancel')";
	
	if($resins=pg_query($ins)){
	}else{
		$ins_error = $ins_error." ".$resins;
		$status++;
	}
}else if($method=="request_other")
{
	// ตรวจสอบก่อนว่ามีการขออยู่แล้วหรือยัง
	$qry_chk = pg_query("select * from thcap_temp_taxinvoice_cancel where \"contractID\" = '$contractID' and \"taxinvoiceID\" = '$taxinvoiceID' and \"approveStatus\" = '2' ");
	$numrow_chk = pg_num_rows($qry_chk);
	if($numrow_chk > 0)
	{
		$ins_error = $ins_error." มีการขอยกเลิกใบกำกับภาษีดังกล่าวไปแล้ว ";
		$status++;
	}

	$qryreceive=pg_query("select * FROM thcap_v_taxinvoice_otherpay where \"taxinvoiceID\"='$taxinvoiceID' 
	order by \"taxinvoiceID\"");
	$i=1;
	$ins="INSERT INTO thcap_temp_taxinvoice_cancel(
        \"contractID\", \"taxinvoiceID\", \"requestUser\", \"requestDate\", \"approveStatus\",\"result\")
		VALUES ('$contractID', '$taxinvoiceID', '$id_user', '$curdate', '2','$resultcancel')";
		
	if($resins=pg_query($ins)){
	}else{
		$ins_error=$resins;
		$status++;
	}
}else if($method=="approve1"){  //กรณีอนุมัติ
	//ตรวจสอบก่อนว่าเลขที่ใบกำกับภาษีนี้ได้รับการอนุมัติไปก่อนหน้านี้หรือไม่
	$qrycheck=pg_query("select * from thcap_temp_taxinvoice_cancel where \"cancelTaxID\" = '$cancelID' and \"approveStatus\"='2'");
	$numcheck=pg_num_rows($qrycheck);
	if($numcheck==0){ //ไม่พบแสดงว่าได้รับการอนุมัติไปก่อนหน้านี้แล้ว
		$status=-1;
	}else{ //กรณียังไม่ได้อนุมัติ
		//$qryprocess="SELECT thcap_process_cancel_payment('$taxinvoiceID','$id_user')";
		
		// นำข้อมูลใน thcap_temp_taxinvoice_otherpay มา insert ใน thcap_temp_taxinvoice_otherpay_cancel ด้วย
		$qryprocess = "INSERT INTO thcap_temp_taxinvoice_otherpay_cancel(
                        \"taxinvoiceID\", \"debtID\", \"netAmt\", \"vatAmt\", \"debtAmt\", \"whtAmt\", 
                        \"typePayID\", \"typePayRefValue\", \"tpDesc\", \"tpFullDesc\")
                        SELECT a.\"taxinvoiceID\", a.\"debtID\", a.\"netAmt\", a.\"vatAmt\", a.\"debtAmt\", a.\"whtAmt\", 
                        a.\"typePayID\", a.\"typePayRefValue\", a.\"tpDesc\", a.\"tpFullDesc\" from thcap_v_taxinvoice_otherpay a, thcap_temp_taxinvoice_details b
                        where a.\"taxinvoiceID\" = b.\"taxinvoiceID\" and a.\"taxinvoiceID\" = '$taxinvoiceID' ";
		if($resprocess=pg_query($qryprocess)){
		}else{
			$status++;
		}
		
		// ลบใบกำกับภาษีออกจากตารางหลักของ TAX
        $qry_del = "DELETE FROM public.\"thcap_temp_taxinvoice_otherpay\" WHERE \"taxinvoiceID\" = '$taxinvoiceID' ";
		if($res_qry_del=pg_query($qry_del)){
		}else{
			$status++;
		}
		
		//ให้อัพเดทตาราง thcap_temp_taxinvoice_cancel ให้มีสถานะ "อนุมัติ"	
		$up="update thcap_temp_taxinvoice_cancel set \"approveUser\"='$id_user',\"approveDate\"=\"nowDateTime\"(),\"approveStatus\"='1'
		where \"cancelTaxID\"='$cancelID' and \"approveStatus\"='2'";
		if($resup=pg_query($up)){
		}else{
			$status++;
		}
	}
}else if($method=="approve0"){ //กรณีไม่อนุมัติ
	//ตรวจสอบก่อนว่าเลขที่ใบกำกับภาษีนี้ได้รับการอนุมัติไปก่อนหน้านี้หรือไม่
	$qrycheck=pg_query("select * from thcap_temp_taxinvoice_cancel where \"taxinvoiceID\" = '$taxinvoiceID' and \"approveStatus\"='2'");
	$numcheck=pg_num_rows($qrycheck);
	if($numcheck==0){ //ไม่พบแสดงว่าได้รับการอนุมัติไปก่อนหน้านี้แล้ว
		$status=-1;
	}else{
		//ดึงข้อมูลเพิ่มเติมเพื่อนำมาเป็นเงื่อนไขในการ update ตาราง thcap_temp_taxinvoice_cancel
		$qryselect=pg_query("SELECT  \"requestUser\",\"requestDate\",\"result\" 
		FROM thcap_temp_taxinvoice_cancel 
		WHERE \"cancelTaxID\"='$cancelID'");
		list($requestUser,$requestDate,$result)=pg_fetch_array($qryselect);
		
		if($result==""){
			$result="";
		}else{
			$result="and \"result\"='$result'";
		}
		//ให้อัพเดทตาราง thcap_temp_taxinvoice_cancel ให้มีสถานะ "ไม่อนุมัติ"	
		$up="update thcap_temp_taxinvoice_cancel set \"approveUser\"='$id_user',\"approveDate\"=\"nowDateTime\"(),\"approveStatus\"='0'
		where \"cancelTaxID\"='$cancelID' and \"approveStatus\"='2'";
		
		if($resup=pg_query($up)){
		}else{
			$status++;
		}
	}
}else if($method=="approve3"){ //กรณีกดรับทราบว่าใบกำกับภาษีได้ถูกลบแล้ว
//ตรวจสอบก่อนว่าเลขที่ใบกำกับภาษีนี้ได้รับทราบก่อนหน้านี้หรือไม่
	$qrycheck=pg_query("select * from thcap_temp_taxinvoice_cancel where \"cancelTaxID\" = '$cancelID' and \"approveStatus\"='3'");
	$numcheck=pg_num_rows($qrycheck);
	if($numcheck>0){ //ถ้าพบบแสดงว่ามีการกดรับทราบไปก่อนหน้าแล้ว
		$status=-1;
	}else{		
		//ให้อัพเดทตาราง thcap_temp_receipt_cancel ให้มีสถานะ "ไม่อนุมัติ"	
		$up="update thcap_temp_receipt_cancel set \"approveUser\"='$id_user',\"approveDate\"=current_timestamp::timestamp without time zone,\"approveStatus\"='3' where \"cancelID\"='$cancelID'";
		if($resup=pg_query($up)){
		}else{
			$status++;
		}
	}	
}


if($status == 0){
	pg_query("COMMIT");
	if($method=="request" || $method=="request_other"){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) ขอยกเลิกใบกำกับภาษี', '$datelog')");
		//ACTIONLOG---
		echo "<font size=4><b>บันทึกรายการเรียบร้อยแล้ว</b></font><br><br>";
		echo "<font size=4><b>การยกเลิกใบกำกับภาษีจะสมบูรณ์ก็ต่อเมื่อใบกำกับภาษีได้รับการอนุมัติแล้ว</b></font><br><br>";
	}else{
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) อนุมัติยกเลิกใบกำกับภาษี', '$datelog')");
		//ACTIONLOG---
		echo "<font size=4><b>อนุมัติรายการเรียบร้อยแล้ว</b></font><br><br>";
	}
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}else if($status<0){
	echo "<font size=4><b>รายการนี้ได้รับการอนุมัติไปก่อนหน้านี้แล้ว</b></font><br><br>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}else{
	pg_query("ROLLBACK");
	echo $ins_error."<br>";
	if($method=="request" || $method=="request_other"){
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถยกเลิกใบกำกับภาษีได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	}else{
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>เกิดข้อผิดพลาดเกี่ยวกับการอนุมัติรายการ</b></font></div>";
	}
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}

?>
</td>
</tr>
</table>
</body>
</html>