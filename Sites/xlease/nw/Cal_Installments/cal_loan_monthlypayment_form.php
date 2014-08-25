<?php
session_start();
include("../../config/config.php");
require("function/cal_tools.php");

$user_id = $_SESSION["av_iduser"];
$pay = $_POST['pay']; // ประเภทการค้นหา

//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) คำนวณยอดผ่อน',LOCALTIMESTAMP(0))");
//ACTIONLOG---

if($pay == 'pay02'){ //(เงินกู้) คำนวณหาจำนวนเดือนที่ผ่อน
	$min_pay = $_POST["moneypay"]; //ยอดผ่อนขั้นต่ำ
	$first_pay_date = $_POST['datestart']; //วันที่เริ่มจ่าย		
	$start_date = $_POST["datestartcon"]; //วันที่ทำสัญญา
	$int_normal = $_POST["interest1"]; //อัตราดอกเบี้ย
	$credit = $_POST["tbmoney1"]; //จำนวนเงินต้น
	$payday = $_POST["payday"]; //ชำระทุกวันที่
	
	list($pay_year,$pay_month,$pay_date) = explode("-",$first_pay_date); //ตัดเอาวันที่จ่ายของทุกเดือน
	list($yy,$mm,$dd) = explode("-",$MinimumInsDate); //ตัดเอาวันที่ทำสัญญา

	$credit = str_replace(',','',$credit);
	$min_pay = str_replace(',','',$min_pay);

	$r = 1+(($int_normal/36500)*(30.4167));		
	$length = log($min_pay/(($credit*(1-$r))+$min_pay),$r);		
	$length = number_format($length) ;	
	$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay,$first_pay_date);
	
	while($last>0){					
			$length = $length+1;
			$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay,$first_pay_date);
	}
	$min_next_neg = ($min_pay)*(-1); //จำนวนเงินเต็มสำหรับดักให้ยอดผ่อนงวดสุดท้ายไม่เกิน การจ่ายของค่างวด เช่น ค่างวด 5000 จะต้องมีส่วนเกินได้ไม่เกิน 5000 ในการจ่ายครั้งสุดท้าย
	while($last < $min_next_neg ){
		$length = $length - 1;
		$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay,$first_pay_date);		
	}			
	$sumlength = number_format($length) ;
	$creditshow = number_format($credit,2);
	$int_normalshow = number_format($int_normal,2);
	$min_payshow = number_format($min_pay,2);
	
	echo " ยอดเงินกู้ : $creditshow บาท  \n ดอกเบี้ย : $int_normalshow % \n วันที่ทำสัญญา : $start_date \n วันที่เริ่มชำระ :  $first_pay_date \n ชำระทุกวันที่ : $payday \n ผ่อนได้ : $min_payshow  บาท ต่อเดือน  \n\n ระยะเวลาในการผ่อน : $sumlength เดือน \n\n";
}			
	
?>