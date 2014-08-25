<?php
session_start();
include("../../config/config.php");
$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$chqpayID=pg_escape_string($_REQUEST["chqpayID"]);
$statusapp=pg_escape_string($_REQUEST["stsapp"]);

$cancel=pg_escape_string($_POST["cancel"]);
$appvpg=pg_escape_string($_POST["appv"]);

if($appvpg=="อนุมัติ"){
	if($cancel!=""){
		$statusapp='11';//อนุมัติยกเลิกเช็ค
		}
	else{
		$statusapp='1';//อนุมัติสั่งจ่ายเช็ค
	}
}
else{
	if($cancel!=""){
		$statusapp='22';//ไม่อนุมัติยกเลิกเช็ค
	}
	else{
		$statusapp='0';//ไม่อนุมัติสั่งจ่ายเช็ค
	}
}
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
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

if($statusapp=="1" || $statusapp=="0"){
	//ตรวจสอบว่าได้รับการอนุมัติก่อนหน้านี้หรือไม่
	$qrychk=pg_query("select \"BAccount\" from cheque_pay where \"chqpayID\"='$chqpayID' and \"appStatus\"='2'");
	$reschk=pg_fetch_array($qrychk);
	list($BAccount)=$reschk;
	$numchk=pg_num_rows($qrychk);
	if($numchk>0){ //แสดงว่ายังไม่ได้รับการอนุมัติ
		//หาเลขที่เช็คของธนาคารที่เลือก
		$qrynumchk=pg_query("SELECT min(\"chequeNum\") FROM cheque_order a
			left join cheque_detail b on a.\"detailID\"=b.\"detailID\"
			where stscheque='FALSE' and \"BAccount\"='$BAccount' 
			group by \"BAccount\"");
		$numchknext=pg_num_rows($qrynumchk);
		$resnumchk=pg_fetch_array($qrynumchk);
		list($chequeNum)=$resnumchk;
		
		if($numchknext>0){ //เช็คยังไม่หมด
			//update cheque ว่ามีการนำเลขนี้ไปใช้แล้ว
			if($statusapp=="1"){
				$upchk="UPDATE cheque_order SET stscheque='TRUE' WHERE \"chequeNum\"='$chequeNum'";
				if($reschk=pg_query($upchk)){
				}else{
					$status++;
				}
				
				$addchknum='"'."chequeNum".'"'."='$chequeNum',";
			}else{
				$addchknum="";
			}
			
			$up="UPDATE cheque_pay
			SET $addchknum \"appUser\"='$app_user', \"appStamp\"='$app_date', \"appStatus\"='$statusapp' WHERE \"chqpayID\"='$chqpayID'";

			if($res=pg_query($up)){
			}else{
				$status++;
			}
		}else{ //กรณีที่เลขที่เช็คหมดแล้ว 
			if($statusapp=="0"){//กรณีไม่อนุมัติ
				$up="UPDATE cheque_pay
				SET \"appUser\"='$app_user', \"appStamp\"='$app_date', \"appStatus\"='0' WHERE \"chqpayID\"='$chqpayID'";
				if($res=pg_query($up)){
				}else{
					$status++;
				}
				$numchk=1;
			}else{ 
				$numchk=0;
				$numchknext=1;
			}
		}	
	}

	if($numchk>0){
		if($status == 0){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '(TAL) อนุมัติสั่งจ่ายเช็ค', '$app_date')");
		//ACTIONLOG---
			pg_query("COMMIT");
			echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
			//echo "<meta http-equiv='refresh' content='2; URL=frm_Approve.php'>";
			echo "<input type=button value=\"ปิด\" onclick=\"javascript:RefreshMe();\" />";
		}else{
			pg_query("ROLLBACK");
			echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
			//echo "<meta http-equiv='refresh' content='10; URL=frm_Approve.php'>";
			echo "<input type=button value=\"ปิด\" onclick=\"javascript:RefreshMe();\" />";
		}
	}else{
		if($numchknext==1){
			echo "<font size=4><b>เช็คของบัญชีนี้หมดแล้ว กรุณาตรวจสอบ</b></font><br><br>";
			//echo "<meta http-equiv='refresh' content='2; URL=frm_Approve.php'>";
			echo "<input type=button value=\"ปิด\" onclick=\"javascript:RefreshMe();\" />";
		}else{
			echo "<font size=4><b>รายการนี้ได้รับการอนุมัติไปก่อนหน้านี้ กรุณาตรวจสอบ</b></font><br><br>";
			//echo "<meta http-equiv='refresh' content='2; URL=frm_Approve.php'>";
			echo "<input type=button value=\"ปิด\" onclick=\"javascript:RefreshMe();\" />";
		}
	}
}else{
	//ตรวจสอบว่าได้รับการอนุมัติไปก่อนหน้านี้หรือไม่
	$qrychk=pg_query("select \"chqpayID\" from cheque_cancel where \"cancelID\"='$chqpayID' and \"cancelStatus\"='2'");
	$numrows=pg_num_rows($qrychk);
	if($numrows>0){ //แสดงว่ายังไม่ได้รับการอนุมัติ
		$reschk=pg_fetch_array($qrychk);
		list($chqpayID2)=$reschk;
		
		if($statusapp=="11"){ //กรณีอนุมัติ
			//update ตารางจ่ายเช็คให้ statusPay=false คือยกเลิกเช็คและเก็บรายการที่ยกเลิกไว้ด้วย
			$upd="UPDATE cheque_pay
			   SET \"statusPay\"='FALSE', \"cancelID\"='$chqpayID' WHERE \"chqpayID\"='$chqpayID2'";
			if($res=pg_query($upd)){
			}else{
				$status++;
			}
			
			//update ตารางให้มีสถานะเป็นอนุมัติ และใครเป็นผู้อนุมัติ
			$upd2="UPDATE cheque_cancel
			   SET \"cancelStatus\"='1', \"appUser\"='$app_user', \"appStamp\"='$app_date' WHERE \"chqpayID\"='$chqpayID2'";
			if($res2=pg_query($upd2)){
			}else{
				$status++;
			}
		}else{ //กรณีไม่ได้รับการอนุมัติ
			//update ตารางให้มีสถานะเป็นอนุมัติ และใครเป็นผู้อนุมัติ
			$upd2="UPDATE cheque_cancel
			   SET \"cancelStatus\"='0', \"appUser\"='$app_user' ,\"appStamp\"='$app_date' WHERE \"chqpayID\"='$chqpayID2'";
			if($res2=pg_query($upd2)){
			}else{
				$status++;
			}
		}
		
		if($status == 0){
			//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '(TAL) อนุมัติสั่งจ่ายเช็ค', '$app_date')");
			//ACTIONLOG---
			pg_query("COMMIT");
			echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
			//echo "<meta http-equiv='refresh' content='2; URL=frm_Approve.php'>";
			echo "<input type=button value=\"ปิด\" onclick=\"javascript:RefreshMe();\" />";
		}else{
			pg_query("ROLLBACK");
			echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
			//echo "<meta http-equiv='refresh' content='10; URL=frm_Approve.php'>";
			echo "<input type=button value=\"ปิด\" onclick=\"javascript:RefreshMe();\" />";
		}
	}else{
		echo "<font size=4><b>รายการนี้ได้รับการอนุมัติไปก่อนหน้านี้ กรุณาตรวจสอบ</b></font><br><br>";
		//echo "<meta http-equiv='refresh' content='2; URL=frm_Approve.php'>";
		echo "<input type=button value=\"ปิด\" onclick=\"javascript:RefreshMe();\" />";
	}
	
}
?>
</td>
</tr>
</table>
</body>
</html>