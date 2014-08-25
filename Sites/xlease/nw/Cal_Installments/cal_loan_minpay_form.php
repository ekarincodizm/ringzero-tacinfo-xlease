<?php
session_start();
include("../../config/config.php");
require("function/cal_tools.php");

$user_id = $_SESSION["av_iduser"];
$pay = $_POST['pay']; // ประเภทการค้นหา

//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) คำนวณยอดผ่อน',LOCALTIMESTAMP(0))");
//ACTIONLOG---
if($pay == 'pay01'){ //(เงินกู้) คำนวณหายอดจ่ายขั้นต่ำ	
	$length = $_POST["month"]; //ระยะเวลา (เดือน)
	$first_pay_date = $_POST['datestart']; //วันที่เริ่มจ่าย		
	$MinimumInsDate = $_POST["datestartcon"]; //วันที่ทำสัญญา
	$int_normal = $_POST["interest"]; //อัตราดอกเบี้ย
	$credit = $_POST["tbmoney"]; //จำนวนเงินต้น
	$payday = $_POST["payday"]; //ชำระทุกวันที่
	$payother = $_POST["payother"]; //ค่าใช้จ่ายอื่นๆ
	$percentpayother = $_POST["percentpayother"]; //% ค่าใช้จ่าย	
	
	if($length / round($length,0) != 1)
	{
		echo "จำนวนเดือน ต้องเป็นตัวเลขจำนวนเต็มเท่านั้น";
	}
	else
	{
		list($pay_year,$pay_month,$pay_date) = explode("-",$first_pay_date); //ตัดเอาวันที่จ่ายของเดือนแรก
		list($yy,$mm,$dd) = explode("-",$MinimumInsDate); //ตัดเอาวันที่ทำสัญญา

		//require("cal_loan_minpay_form.php"); //เรียกไฟล์คำนวณ
		
		//-===================================================================================================-
		//			คำนวณหายอดจ่ายขั้นต่ำ
		//-===================================================================================================-
		$start =  MKTIME(0,0,0,$mm, $dd, $yy);
		$start_date = $yy."-".$mm."-".$dd;
		
		$last =  MKTIME(0,0,0,$mm+$length, $payday, $yy);
		$date1 = $last-$start;
		$date1 = round(($date1/60/60/24),4);
		$date1 = $date1/$length;
		$r = 1+(($int_normal/36500)*($date1));
		
		$min_pay =  round($credit*(pow($r,$length)*(1-$r))/(1-pow($r,$length)),2);
		$min_pay2 = $min_pay;
		$p = 0.1 ;  // %minpay ที่เพิ่ม
		$min_pay = $min_pay+$min_pay*($p/100);
		$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay,$first_pay_date);

		while($last > 0){			
			$p=$p+0.1;
			$min_pay = $min_pay2+($min_pay2*($p/100));
			 $last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay,$first_pay_date);
		}
		
		$min_pay2 = floor($min_pay/10)*10 ;
		$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay2,$first_pay_date);
		
		$halfminpay = ($min_pay2/2)*(-1);
		$valueminus = (-5);
		$valueplus = 5;
		if($last < $halfminpay OR $last > 0){
			while($stop != 1){
				if($last < $halfminpay AND $last < 0){
					$valueminus = $valueminus - (-5);
					$min_pay2 = (ceil($min_pay/10)*10)-$valueminus;	
				}else if($last > 0 AND $last > $halfminpay ){
					$valueplus = $valueplus + 5;
					$min_pay2 = (ceil($min_pay/10)*10)+$valueplus;	
				}else{
					$stop = 1;
				}		
					$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay2,$first_pay_date);	
			}
		}
				
		$showresult = number_format($min_pay2,2) ;
		$creditshow = number_format($credit,2);
		$payothershow = number_format($payother,2);
		$percentpayothershow = number_format($percentpayother,2);
		
		echo " เงินต้น : $creditshow บาท  \n ดอกเบี้ย : $int_normal % \n % ค่าใช้จ่าย : $percentpayothershow % \n ค่าใช้จ่ายอื่นๆ : $payothershow บาท \n วันที่ทำสัญญา : $MinimumInsDate \n วันที่เริ่มชำระ : $first_pay_date \n ชำระทุกวันที่ : $payday \n ระยะเวลา : $length เดือน  \n\n ยอดจ่ายขั้นต่ำ : $showresult  บาท/เดือน \n\n ";
	}
}	
?>