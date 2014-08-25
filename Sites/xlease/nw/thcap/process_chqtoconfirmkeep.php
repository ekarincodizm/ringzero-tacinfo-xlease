<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$id_user=$_SESSION["av_iduser"];
	
$revChqID=pg_escape_string($_GET["revChqID"]);
$dateTime=nowDateTime();
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php
pg_query("BEGIN WORK");
$status = 0;
$concurrent = 0; 
	
//ตรวจสอบว่าเช็คว่าเคยทำรายการยืนยันแล้วหรือยัง?
$chkkeep=pg_query("select \"revChqID\" from finance.\"thcap_receive_cheque_keeper\" where \"revChqID\"='$revChqID' and \"replyByTakerID\" is not null and \"bankRevResult\" <> '4'");
$numrow = pg_num_rows($chkkeep);
if($numrow>0){
		$status++;
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถนำ \"เช็คที่เคยยืนยันนำเข้าธนาคาร\" ไปยืนยันเก็บรักษาเช็คใหม่ได้</b></font></div>";
		//echo "<div style=\"padding: 50px 0px;text-align:center;\"><input type=button value=\"  กลับ  \" onclick=\"window.location='frm_chequetobank.php'\"></div>";
} else {
	//ตรวจสอบว่าทำรายการไปแล้วหรือยัง
	$chkup=pg_query("select \"revChqID\" from finance.\"thcap_receive_cheque\" where \"revChqID\"='$revChqID' and \"revChqStatus\"='8'");
	$numrow = pg_num_rows($chkup);
	if($numrow==0){
		$concurrent++;
	}
	
		//อัดเดตสถานะเช็คให้เป็น  "พนักงานรับเช็ค"
		$upkeep="update finance.\"thcap_receive_cheque\" set \"revChqStatus\"='9'
					where \"revChqID\"='$revChqID' and \"revChqStatus\"='8' 
					and \"revChqID\" not in (select \"revChqID\" from finance.\"thcap_receive_cheque_keeper\" where \"revChqID\"='$revChqID' and \"replyByTakerID\"is not null and \"bankRevResult\" <> '4') 
					returning \"revChqStatus\"";
		if($res_return=pg_fetch_result(pg_query($upkeep),0)){
			if($res_return==""){
				$concurrent++;
			}
		}else{
			$status++;
		}
		
		//ลบ cheque ออกจากตารางเก็บรักษาเช็ค
		$delkeep="delete from finance.\"thcap_receive_cheque_keeper\" where \"revChqID\"='$revChqID'";
		if($res_del=pg_query($delkeep)){
		}else{
			$status++;
		}
	
		//เก็บ log ประวัิติเช็ค
		$inslog="INSERT INTO finance.thcap_receive_cheque_log (\"revChqID\",\"revChqStatus\",\"effStamp\",\"doerID\",\"doerStamp\")
			VALUES ('$revChqID','9','$dateTime'::date,'$id_user','$dateTime')";
		if($res_inslog=pg_query($inslog)){
		}else{
			$status++;
		}
}
	
if($concurrent>0){
	pg_query("ROLLBACK");
	echo $resupkeep."<br>";
	echo $resuprev;
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>มีการทำรายการไปก่อนหน้านี้แล้ว</b></font></div>";
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><input type=button value=\"  กลับ  \" onclick=\"window.location='frm_chequetobank.php'\"></div>";
}else{
	if($status == 0){
		pg_query("COMMIT");
		echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></div>";
		echo "<meta http-equiv='refresh' content='2; URL=frm_chequetobank.php'>";
	}else if($status>0){
		pg_query("ROLLBACK");
		echo $resupkeep."<br>";
		echo $resuprev;
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><input type=button value=\"  กลับ  \" onclick=\"window.location='frm_chequetobank.php'\"></div>";
	}
}

?>