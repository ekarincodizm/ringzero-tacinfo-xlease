<?php
//-===================================================================================================-
//			Function ใช้ในการคำนวณ...
//-===================================================================================================-
function func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay,$first_pay_date){
	$f=1;//เช็คว่าเป็นครั้งแรกหรือไม่	
	while($length > 0){ // Loop การจ่ายตรง due ตรงจำนวน แต่ละเดือนจนครบกำหนด
	   
		   if($f==1){//เช็คว่าเป็นครั้งแรกหรือไม่ 1= เป็นครั้งแรก	
				$f=0; //setค่าให้เป็น 0 เพื่อออกจาก เงื่อนไข
				$qryint = pg_query("select \"cal_interestTypeB\"('$credit','$int_normal','$start_date','$first_pay_date')");
				$start_date = $first_pay_date;	
			}else{ //f !+1 ไม่เป็นครั้งแรก
				$start_date = func_time_next_month($start_date, $pay_date);		
				$qryint = pg_query("select \"cal_interestTypeB\"('$credit','$int_normal','$start_later','$start_date')");				
			}
			list($interest_month) = pg_fetch_array($qryint);
			$credit = $credit - ($min_pay - $interest_month);
			$length--;		
			$start_later = $start_date;	
			
	} 
	return $credit;
}

function func_time_datediff($date1, $date2) // count number of days between date 1 to date 2
{
	$date1_year = $date1[0].$date1[1].$date1[2].$date1[3];
	$date1_month = $date1[5].$date1[6];
	$date1_day = $date1[8].$date1[9];

	$date2_year = $date2[0].$date2[1].$date2[2].$date2[3];
	$date2_month = $date2[5].$date2[6];
	$date2_day = $date2[8].$date2[9];
	
	$first_date = MKTIME(12,0,0,$date1_month,$date1_day,$date1_year);
	$second_date = MKTIME(12,0,0,$date2_month,$date2_day,$date2_year);
	
	$LIMIT = $second_date-$first_date;
	return FLOOR($LIMIT/60/60/24);
}

function func_time_next_month($date, $day)
{
	// ตรวจสอบว่า วันเริ่มทำสัญญา กับ วันที่ต้องจ่ายทุกๆ เดือนตรงกันหรือไม่
	$date_year = $date[0].$date[1].$date[2].$date[3];
	$date_month = $date[5].$date[6];
	$date_day = $date[8].$date[9];
	
	// ถ้าปี ค.ศ. ที่หารด้วย 4 ลงตัว เดือน กุมภาพันธ์ จะมี 29 วัน
	if(($date_year%4)==0 && $date_month=="01" && $day>=29) // เดือนหน้าของ มกราคม ที่เป็น ปีที่มี กุมภา 29 วัน
		$day = 29;
	else if(($date_year%4)!=0 && $date_month=="01" && $day>=29)
		$day = 28;
	
	// ถ้า กำหนดเป็นวันที่ 31 เดือนที่มี 30 วันให้เหลือ แค่ 30 พอ (มีนา>เมษา; พฤษภา>มิถุนา; สิงหา>กันยา; ตุลา>พฤศจิกา)
	if($day == 31 && ($date_month=="03" || $date_month=="05" || $date_month=="08" || $date_month=="10"))
		$day = 30;
	
	// หลบวันในกรณีใช้ day = 0 อิงจากวันที่ ของวันที่ใส่ $date เป็นหลัก
	if($day==0 && $date_day=="31" && ($date_month=="03" || $date_month=="05" || $date_month=="08" || $date_month=="10"))
		$day = 30;
	else if(($date_year%4)==0 && $date_month=="01" && $day==0 && $date_day>=29)
		$day = 29;
	else if(($date_year%4)!=0 && $date_month=="01" && $day==0 && $date_day>=29)
		$day = 28;
	else if($day == 0)
		$day = $date_day;

	if($date_month == "01") $next_month = "02";
	else if($date_month == "02") $next_month = "03";
	else if($date_month == "03") $next_month = "04";
	else if($date_month == "04") $next_month = "05";
	else if($date_month == "05") $next_month = "06";
	else if($date_month == "06") $next_month = "07";
	else if($date_month == "07") $next_month = "08";
	else if($date_month == "08") $next_month = "09";
	else if($date_month == "09") $next_month = "10";
	else if($date_month == "10") $next_month = "11";
	else if($date_month == "11") $next_month = "12";
	else if($date_month == "12") {$next_month = "01"; $date_year += 1;}

	if($date_day == $day) // ถ้าต้องให้ใช้เดือนถัดไปได้เลย ในวันที่เดิม หรือ $day=0
		return $date_year."-".$next_month."-".$date_day;
	else{
		return $date_year."-".$next_month."-".$day;
	}
}

//ฟังก์ชันสำหรับหา "ยอดการผ่อนขั้นต่ำ"
function minimumPay($conTerm,$conStartDate,$conDate,$conLoanIniRate,$conLoanAmt,$conRepeatDueDay){
		$length = $conTerm; //ระยะเวลา (เดือน)
		$first_pay_date = $conStartDate; //วันที่เริ่มจ่าย		
		$MinimumInsDate = $conDate; //วันที่ทำสัญญา
		$int_normal = $conLoanIniRate; //อัตราดอกเบี้ย
		$credit = $conLoanAmt; //จำนวนเงินต้น
		$payday = $conRepeatDueDay; //ชำระทุกวันที่
		$payother = 0; //ค่าใช้จ่ายอื่นๆ
		$percentpayother = 0; //% ค่าใช้จ่าย	
	
			list($pay_year,$pay_month,$pay_date) = explode("-",$first_pay_date); //ตัดเอาวันที่จ่ายของเดือนแรก
			list($yy,$mm,$dd) = explode("-",$MinimumInsDate); //ตัดเอาวันที่ทำสัญญา
		
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
				
			$showresult = $min_pay2 ; // ยอดจ่ายขั้นต่ำ
		
			return $showresult;
}
?>