<?php
session_start();
include("../../config/config.php");


$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<title></title>
</head>
<body>
<?php 
$query1 = "select \"thcap_cal_reCalIntReceipt\"()";
if($res=pg_query($query1)){
}else{
	$status++;
	$up_error=$res;
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) Process คำนวณเงินต้นดอกเบี้ย', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
}else{
	pg_query("ROLLBACK");
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ข้อมูลผิดพลาด !!</b></font><br>";
	echo $up_error."</div>";
}		
?>
</table>

</body>
</html>