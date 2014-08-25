<?php
session_start();
include("../../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$tc_clSerial = $_POST['chkbox'];
$typeappat = $_POST['typeappat'];
$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$status = 0;


pg_query('BEGIN');
for($i=0;$i<sizeof($tc_clSerial);$i++){
	if($typeappat[$i] == '1'){
		$sqlf = "UPDATE thcap_contract_collateral_regdetails SET appvreg_iduser='$id_user', appvreg_stamp='$datenow', appvreg_status='1' WHERE \"tc_clSerial\"= '$tc_clSerial[$i]' ";
		$sql = pg_query($sqlf);
		if($sql){}else{$status++; echo "<p>".$sqlf."<p>";}
	}else if($typeappat[$i] == '2'){
		$sqlf = "UPDATE thcap_contract_collateral_regdetails SET appvunreg_iduser='$id_user', appvunreg_stamp='$datenow', appvunreg_status='1' WHERE \"tc_clSerial\"= '$tc_clSerial[$i]' ";
		$sql = pg_query($sqlf);
		if($sql){}else{$status++; echo "<p>".$sqlf."<p>";}
	}
}


if($status == 0){
pg_query("COMMIT");
echo "<center><h2>การอนุมัติสำเร็จ</h2></center>";
echo "<meta http-equiv=\"refresh\" content=\"3; URL=Index.php\">";
}else{
pg_query("ROLLBACK");
echo "<center><h2>เกิดข้อผิดพลาด กรุณาลองใหม่ในภายหลัง</h2></center><p>";
echo "<center><h2>กรุณาเซฟภาพการเกิด Error นี้และส่งให้เจ้าหน้าที่ฝ่าย IT ตรวจสอบ</h2></center><p>";
}
?>