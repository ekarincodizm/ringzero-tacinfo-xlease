<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$id_user=$_SESSION["av_iduser"];
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php	
$giveTakerID = pg_escape_string($_POST["giveTakerID"]);
$giveTakerDate = pg_escape_string($_POST["giveTakerDate"]);
$BID = pg_escape_string($_POST["giveTakerToBankAcc"]);
$chqID=$_POST["chqID"]; // เป็น array ใส่ pg_escape_string ตรงนี้ไม่ได้ ต้องไปใส่ตอนวนลูปสมาชิก array แต่ละตัว
$revID=$_POST["revID"]; // เป็น array ใส่ pg_escape_string ตรงนี้ไม่ได้ ต้องไปใส่ตอนวนลูปสมาชิก array แต่ละตัว
$result=$_POST["res"]; //หมายเหตุนำเช็คเข้าธนาคาร // เป็น array ใส่ pg_escape_string ตรงนี้ไม่ได้ ต้องไปใส่ตอนวนลูปสมาชิก array แต่ละตัว
$giveTakerStamp=nowDateTime();

pg_query("BEGIN WORK");
$status = 0;

for($i=0;$i<sizeof($chqID);$i++){ //รายการที่ทำการ update
	$chqKeeperID = pg_escape_string($chqID[$i]);
	$revChqID = pg_escape_string($revID[$i]);
	$result2 = checknull(pg_escape_string($result[$i]));
	
	//ตรวจสอบสถานะเช็คก่อนนำไปเข้าธนาคาร
	$qryStatus_chk=pg_query("SELECT \"revChqStatus\" FROM finance.\"thcap_receive_cheque\"  WHERE \"revChqID\"='$revChqID' ");
	$beforChqtoBank=pg_fetch_result($qryStatus_chk,0);
	
	//ตรวจสอบข้อมูลก่อนว่ามีการ update revChqStatus ในตาราง  thcap_receive_cheque เป็น 7 หรือยังถ้ามีแล้ว แสดงมีการทำรายการก่อนหน้านี้แล้ว
	$qrychk=pg_query("SELECT * FROM finance.\"thcap_receive_cheque\"  WHERE \"revChqID\"='$revChqID' and \"revChqStatus\"='7'");
	$numchk=pg_num_rows($qrychk);
	if($numchk>0){
		$status=-1; //แสดงว่ามีการทำรายการก่อนหน้านี้แล้ว
		break;
	}
	
	//ตรวจสอบว่ามีรายการที่กำลังคืนเช็คหรือไม่
	$qrychkapp=pg_query("select * from finance.thcap_receive_cheque_return where \"statusChq\"='2' and \"revChqID\"='$revChqID'");
	$numchkapp=pg_num_rows($qrychkapp);
	if($numchkapp>0){ //แสดงว่ามีรายการที่รอคืนเช็คอยู่
		echo "<div align=\"center\"><h2>มีบางรายการกำลังรออนุมัติคืนเช็คอยู่ กรุณาทำรายการใหม่อีกครั้ง</h2></div>";
		echo "<div align=\"center\"><input type=\"button\" value=\"กลับไปทำรายการใหม่\" onclick=\"location.href='frm_chequetobank.php'\"></div>";
		exit();	
	}
	
	$updatekeep="update finance.thcap_receive_cheque_keeper set \"giveTakerID\"='$giveTakerID',
				\"giveTakerDate\"='$giveTakerDate',
				\"giveTakerStamp\"='$giveTakerStamp',
				\"BID\"='$BID',
				\"beforChqtoBank\"='$beforChqtoBank'
				where \"chqKeeperID\"='$chqKeeperID'";
	if($resupkeep=pg_query($updatekeep)){
	}else{
		$status++;
	}
	
	$updaterev="update finance.thcap_receive_cheque set \"revChqStatus\"='7',\"result\"=$result2
				where \"revChqID\"='$revChqID'";
	if($resuprev=pg_query($updaterev)){
	}else{
		$status++;
	}
	
	unset($result2);
}

if($status== -1){
	pg_query("ROLLBACK");
	echo "<div align=\"center\"><font size=4><b>มีบางรายการได้รับไปก่อนหน้านี้แล้ว กรุณาทำรายการใหม่อีกครั้ง</b></font></div>";
	echo "<meta http-equiv='refresh' content='3; URL=frm_chequetobank.php'>";
}else if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></div>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_chequetobank.php'>";
}else{
	pg_query("ROLLBACK");
	echo $resupkeep."<br>";
	echo $resuprev;
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	echo "<input type=button value=\"  กลับ  \" onclick=\"window.location='frm_chequetobank.php'\">";

}
?>