<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$id_user = $_SESSION["av_iduser"];
$contractID=$_REQUEST["contractID"]; 
$receiptID=$_REQUEST["receiptID"];
$receiveDate=$_REQUEST["receiveDate"];
$resultcancel=$_REQUEST["resultcancel"];
$cancelID=$_GET['cancelID'];
$method=$_REQUEST["method"];

if($method==""){
	$cancelID=$_POST['cancelID'];
	if(isset($_POST["rec_appv"])){
		$method="approve1";//อนุมัติ
	}else if(isset($_POST["rec_unappv"])){
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

if($contractID == "")
{ // ถ้าไม่มีเลขที่สัญญา ให้หาเอาเอง
	$qry_contractData = pg_query("SELECT \"contractID\" from thcap_v_receipt_otherpay where \"receiptID\" = '$receiptID' ");
	$contractID = pg_fetch_result($qry_contractData,0);
}

// หาประเภทสินเชื่อ
$creditType = pg_creditType($contractID);

if($method=="request"){ //ขอยกเลิกใบเสร็จค่างวด
	//แสดงเลขที่ใบเสร็จที่เกี่ยวข้อง ไม่รวมค่า Gen ค่า Gen ไว้เก็บตอนอนุมัติแล้ว
	// $qryreceive=pg_query("select * FROM thcap_temp_int_201201 where \"contractID\"='$contractID' 
	// and \"receiptID\" >= '$receiptID' and \"isReceiveReal\" != '0' order by \"receiptID\"");

	//เก็บเลขที่ใบเสร็จที่ขอยกเลิก
	//while($resshow=pg_fetch_array($qryreceive)){
	//	$receiptID2=$resshow["receiptID"];
		$ins="INSERT INTO thcap_temp_receipt_cancel(
            \"contractID\", \"receiptID\", \"requestUser\", \"requestDate\", \"approveStatus\",\"result\")
			VALUES ('$contractID', '$receiptID', '$id_user', '$curdate', '2','$resultcancel')";
		
		if($resins=pg_query($ins)){
		}else{
			$ins_error=$resins;
			$status++;
		}
	//}
}else if($method=="request_other"){ //ขอยกเลิกใบเสร็จค่าอื่นๆ
	$qryreceive=pg_query("select * FROM thcap_v_receipt_otherpay where \"receiptID\"='$receiptID' 
	order by \"receiptID\"");
	$i=1;
	$ins="INSERT INTO thcap_temp_receipt_cancel(
        \"contractID\", \"receiptID\", \"requestUser\", \"requestDate\", \"approveStatus\",\"result\")
		VALUES ('$contractID', '$receiptID', '$id_user', '$curdate', '2','$resultcancel')";
		
	if($resins=pg_query($ins)){
	}else{
		$ins_error=$resins;
		$status++;
	}
}else if($method=="approve1"){  //กรณีอนุมัติ
	//ตรวจสอบก่อนว่าเลขที่ใบเสร็จนี้ได้รับการอนุมัติไปก่อนหน้านี้หรือไม่
	$qrycheck=pg_query("select * from thcap_temp_receipt_cancel where \"cancelID\" = '$cancelID' and \"approveStatus\"='2'");
	$numcheck=pg_num_rows($qrycheck);
	if($numcheck==0){ //ไม่พบแสดงว่าได้รับการอนุมัติไปก่อนหน้านี้แล้ว
		$status=-1;
	}else{ //กรณียังไม่ได้อนุมัติ
	
		if($creditType == "JOINT_VENTURE")
		{
			$qryprocess="SELECT thcap_process_cancel_joint_venture('$receiptID','$id_user')";
		}
		else
		{
			$qryprocess="SELECT thcap_process_cancel_payment('$receiptID','$id_user')";
		}
		
		if($resprocess=pg_query($qryprocess)){
		}else{
			$status++;
		}
	}
}else if($method=="approve0"){ //กรณีไม่อนุมัติ
	//ตรวจสอบก่อนว่าเลขที่ใบเสร็จนี้ได้รับการอนุมัติไปก่อนหน้านี้หรือไม่
	$qrycheck=pg_query("select * from thcap_temp_receipt_cancel where \"receiptID\" = '$receiptID' and \"approveStatus\"='2'");
	$numcheck=pg_num_rows($qrycheck);
	if($numcheck==0){ //ไม่พบแสดงว่าได้รับการอนุมัติไปก่อนหน้านี้แล้ว
		$status=-1;
	}else{
		//ดึงข้อมูลเพิ่มเติมเพื่อนำมาเป็นเงื่อนไขในการ update ตาราง thcap_temp_receipt_cancel
		$qryselect=pg_query("SELECT  \"requestUser\",\"requestDate\",\"result\" 
		FROM thcap_temp_receipt_cancel 
		WHERE \"cancelID\"='$cancelID'");
		list($requestUser,$requestDate,$result)=pg_fetch_array($qryselect);
		
		if($result==""){
			$result="";
		}else{
			$result="and \"result\"='$result'";
		}
		//ให้อัพเดทตาราง thcap_temp_receipt_cancel ให้มีสถานะ "ไม่อนุมัติ"	
		$up="update thcap_temp_receipt_cancel set \"approveUser\"='$id_user',\"approveDate\"=current_timestamp::timestamp without time zone,\"approveStatus\"='0'
		where \"cancelID\"='$cancelID' and \"approveStatus\"='2'";
		
		if($resup=pg_query($up)){
		}else{
			$status++;
		}
	}
}else if($method=="approve3"){ //กรณีกดรับทราบว่าใบเสร็จได้ถูกลบแล้ว
//ตรวจสอบก่อนว่าเลขที่ใบเสร็จนี้ได้รับทราบก่อนหน้านี้หรือไม่
	$qrycheck=pg_query("select * from thcap_temp_receipt_cancel where \"cancelID\" = '$cancelID' and \"approveStatus\"='3'");
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
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) ขอยกเลิกใบเสร็จ', '$datelog')");
		//ACTIONLOG---
		echo "<font size=4><b>บันทึกรายการเรียบร้อยแล้ว</b></font><br><br>";
		echo "<font size=4><b>การยกเลิกใบเสร็จจะสมบูรณ์ก็ต่อเมื่อใบเสร็จได้รับการอนุมัติแล้ว</b></font><br><br>";
	}else{
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) อนุมัติยกเลิกใบเสร็จ', '$datelog')");
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
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถยกเลิกใบเสร็จได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
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