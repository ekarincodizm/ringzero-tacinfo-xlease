<?php

/* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Created by Kanitchet Vaiassava
Description :
	ฟังก์ชั่นพื้นฐานต่างๆสำหรับใช้ทั่วๆไปในทุกๆ Project
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

// require_once("sys_initial.php");

// เวลามาตราฐาน
date_default_timezone_set('Asia/Bangkok');

/* ==============================================================================
คำอธิบาย: เวลาที่คงสถานะ login (Inactive)
รูปแบบ: Return - ค่าเวลาเป็น วินาที
============================================================================== */
function core_sys_stayloged(){
	return time()+3600;
}

/* ==============================================================================
คำอธิบาย: เรียก html code จากไฟล์ html ที่มีอยู่แล้ว ให้แสดงในจุดที่เรียก
การส่งค่า: $file_html - ตำแหน่งที่ตั้งของไฟล์ HTML หรือข้อความที่ต้องารให้มาแสดงในจุดของหน้านั้นๆที่เรียก function
รูปแบบ: Void
============================================================================== */
function core_fetch_html($file_html){
	$fopen = fopen($file_html, "r");
	while(!feof($fopen)){
		$data = fgets($fopen);
		echo "$data";
	}
	fclose($fopen);
}

/* ==============================================================================
คำอธิบาย:	แปลงวันเวลาในรูปแบบ YYYY-MM-DD ให้เป็น DD*MM*YYYY (โดยแทน * ด้วยเครื่องหมายที่กำหนด)
การส่งค่า:	$date - วันที่ที่ต้องการแปลง
		$add_char - เครื่องหมายที่จะใช้ในส่วนที่เป็น * ใน format
รูปแบบ:	Void
============================================================================== */
function core_time_yeartranslate($date,$add_char) // Translate from YYYY-MM-DD to DD*MM*YYYY
{
	$date_year = $date[0].$date[1].$date[2].$date[3];
	$date_month = $date[5].$date[6];
	$date_day = $date[8].$date[9];
	
	return $date_day.$add_char.$date_month.$add_char.$date_year;
}

/* ==============================================================================
คำอธิบาย:	นับระยะห่างเป็นวันแบบ  นับท้าย หรือ หัว อย่างเดียว ระหว่าง 2 วันที่ใดๆ 
การส่งค่า:	$date1 - วันที่หัว
		$date2 - วันที่ท้าย
รูปแบบ:	Return - จำนวนวันที่เป็นระยะระหว่าง 2 วันใดๆ
============================================================================== */
function core_time_datediff($date1, $date2) // count number of days between date 1 to date 2
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

/* ==============================================================================
คำอธิบาย:	ใช้หาว่า เดือนหน้าวันเดียวกันกับวันที่ที่ให้คือวันเดือนปีอะไร และถ้าในกรณีเป็นวันที่ 31/30/29 และวันที่เดือนหน้าไม่มีวันนั้นๆ
	ให้ใช้เป็นวันที่สุดท้ายของเดือนหน้า
ตัวอย่าง:	(2010-08-01,23) -> 2010-09-23 หรือ  (2010-01-31,31) -> 2010-02-28 (กรณีปีนั้น เดือนกุมภา มี 28 วัน)
การส่งค่า:	$date 
		$day - วันที่ของเดือนถัดไปที่ต้องการ ($day = 0 : คือวันเดียวกันกับวันที่ใน $date)
รูปแบบ:	Return - จำนวนวันที่เป็นระยะระหว่าง 2 วันใดๆ
============================================================================== */
function core_time_nextmonth($date, $day)
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
	else if($day == 0) // ถ้าค่า $day เป็น 0 ให้ใช้วันที่เดิม ทั้งนี้จะต้องไม่ผิด หลักวันที่ 31/30/29
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
	else if($date_month == "12") {$next_month = "01"; $date_year += 1; } // ถ้าสิ้นปีเืดอนหน้าต้องเป็นปีถัดไป
	
	if(	$day == "1" || 
		$day == "2" || 
		$day == "3" || 
		$day == "4" || 
		$day == "5" || 
		$day == "6" || 
		$day == "7" || 
		$day == "8" || 
		$day == "9" ) {
		
		$day = '0'.$day;
	}

	if($date_day == $day) // ถ้าต้องให้ใช้เดือนถัดไปได้เลย ในวันที่เดิม หรือ $day=0
		return $date_year."-".$next_month."-".$date_day;
	else{
		return $date_year."-".$next_month."-".$day;
	}
}

/* ==============================================================================
คำอธิบาย:	ใส่เลข 0 ด้านหน้า ให้ครบ ตามจำนวนหลักที่กำหนด
ตัวอย่าง:	-
การส่งค่า:	$target - สิ่งที่ต้องการให้เพิ่ม 0 เข้าไปด้านหลักให้ครบตามจำนวนหลัก
		$num - จำนวนหลักตั้งแต่ 1 ขึ้นไป (กรณีเป็นค่า 0 หรือ negative หรือน้อยกว่า $target ที่มีอยู่แล้ว / จะ return ค่าที่ส่งให้มาแปลง)
รูปแบบ:	Return - สิ่งที่โดนใส่ 0 ด้านหน้าให้ครบหลักแล้ว
============================================================================== */
function core_generate_frontzero($target, $num, $prefix=''){
	// Declare Variable
	$final = 0; // ค่าที่ return

	if(strlen($target) >= $num){
		$final = $target;
	}
	else {
		$final = $target;
		while(strlen($final) < $num)
			$final = "0".$final;
	}
	return $prefix.$final;
}

/* ==============================================================================
คำอธิบาย:	ใช้ generate id สำหรับเรื่องต่างๆโดยใช้เทคนิคนับ Row ข้อมูลที่มีอยู่แล้ว ซึ่งสามารถที่จะเพิ่ม prefix หรือวันที่เข้ามาเป็นส่วนหนึ่งของ ID ได้ (MySQL)
ตัวอย่าง:	-
การส่งค่า:	$prefix - ตัวอักษรหรืออักขระอะไรที่จะถูกใส่ไว้หน้า ID เช่น "MG-"
		$dbtb - ชื่อ Table ใน Database
		$field - ชื่อ Field
		$date - วันที่ที่สนใจ
		$num - จำนวนหลัก ได้ตั้งแต่ 1-5 *** ถ้าใส่เลขนอกเหนือจากนี้ระบบจะจัดให้เป็น 4 หลัก
รูปแบบ:	Return - ค่า ID ที่ generate ได้
============================================================================== */
function core_generate_id($prefix, $dbtb, $field, $date, $num){
	$query = 	"SELECT $field 
				FROM $dbtb
				WHERE $field LIKE '%$date%'";
	$sql_query = mysql_query($query);
	$num_row = mysql_num_rows($sql_query);
	//echo "num_row =".$num_row."<br>";
	if($num == 1) {
		if($num_row < 9) return $prefix.$date.($num_row+1);
		else return $prefix.$date."9";
	}

	else if($num == 2) {
		if($num_row < 9) return $prefix.$date."0".($num_row+1);
		else if($num_row < 99) return $prefix.$date.($num_row+1);
		else return $prefix.$date."99";
	}

	else if($num == 3) {
		if($num_row < 9) return $prefix.$date."00".($num_row+1);
		else if($num_row < 99) return $prefix.$date."0".($num_row+1);
		else if($num_row < 999) return $prefix.$date.($num_row+1);
		else return $prefix.$date."999";
	}
	
	else if($num == 4) {
		if($num_row < 9) return $prefix.$date."000".($num_row+1);
		else if($num_row < 99) return $prefix.$date."00".($num_row+1);
		else if($num_row < 999) return $prefix.$date."0".($num_row+1);
		else if($num_row < 9999) return $prefix.$date.($num_row+1);
		else return $prefix.$date."9999";
	}
	
	else if($num == 5) {
		if($num_row < 9) return $prefix.$date."0000".($num_row+1);
		else if($num_row < 99) return $prefix.$date."000".($num_row+1);
		else if($num_row < 999) return $prefix.$date."00".($num_row+1);
		else if($num_row < 9999) return $prefix.$date."0".($num_row+1);
		else if($num_row < 99999) return $prefix.$date.($num_row+1);
		else return $prefix.$date."99999";
	}
	
	else {
		if($num_row < 9) return $prefix.$date."000".($num_row+1);
		else if($num_row < 99) return $prefix.$date."00".($num_row+1);
		else if($num_row < 999) return $prefix.$date."0".($num_row+1);
		else if($num_row < 9999) return $prefix.$date.($num_row+1);
		else return $prefix.$date."9999";
	}
	
}

/* ==============================================================================
คำอธิบาย:	บันทึกข้อมูล log อย่างย่อลง Database (MySQL)
ตัวอย่าง:	-
การส่งค่า:	-
รูปแบบ:	Void
============================================================================== */
function core_generate_log($dbtb,$luser,$laction,$ltarget,$lpos){
	
	// กำนหดค่า current time เพื่อ stamp เวลา (กำหนดใหม่เพราะ function ไม่มอง variable นอก func)
	$info_currentdatetimesql = date("Y-m-d H:i:s");

	$query = "INSERT INTO $dbtb(logs_users, logs_action, logs_target, logs_pos, logs_time)
				VALUES('$luser','$laction','$ltarget','$lpos','$info_currentdatetimesql')";
	$sql_query = mysql_query($query);
}

/* ==============================================================================
คำอธิบาย:	แปลงตัวเลขเดือนจากตัวเลข format xx เป็นเดือน ภาษาไทย
ตัวอย่าง:	-
การส่งค่า:	$month - เลขเดือน formate xx ที่ต้องการเปลี่ยนเป็นภาษาไทย
รูปแบบ:	Return - เดือนที่ถูกแปลงเป็นภาษาไทยแล้ว
============================================================================== */
function core_translate_month($month){
		switch ($month) {
		case 01:
			return "มกราคม";
			break;
		case 02:
			return "กุมภาพันธ์";
			break;
		case 03:
			return "มีนาคม";
			break;
		case 04:
			return "เมษายน";
			break;
		case 05:
			return "พฤษภาคม";
			break;
		case 06:
			return "มิถุนายน";
			break;
		case 07:
			return "กรกฎาคม";
			break;
		case 08:
			return "สิงหาคม";
			break;
		case 09:
			return "กันยายน";
			break;
		case 10:
			return "ตุลาคม";
			break;
		case 11:
			return "พฤศจิกายน";
			break;
		case 12:
			return "ธันวาคม";
			break;
		default:
					 if($month=='01') 
				return "มกราคม";
				else if($month=='02')
				return "กุมภาพันธ์"; 
				else if($month=='03')
				return "มีนาคม"; 
				else if($month=='04')
				return "เมษายน"; 
				else if($month=='05')
				return "พฤษภาคม"; 
				else if($month=='06')
				return "มิถุนายน"; 
				else if($month=='07')
				return "กรกฎาคม"; 
				else if($month=='08')
				return "สิงหาคม"; 
				else if($month=='09')
				return "กันยายน"; 
				else if($month=='10')
				return "ตุลาคม"; 
				else if($month=='11')
				return "พฤศจิกายน"; 
				else if($month=='12')
				return "ธันวาคม"; 
				
			else
				return "ตัวเลขเดือนผิดพลาด!";
	}
}	

/* ==============================================================================
คำอธิบาย:	แปลงตัวเลขเดือนจากตัวเลข format xx เป็นเดือน ภาษาไทย
ตัวอย่าง:	-
การส่งค่า:	$month - เลขเดือน formate xx ที่ต้องการเปลี่ยนเป็นภาษาไทย
รูปแบบ:	Return - เดือนที่ถูกแปลงเป็นภาษาไทยแล้ว
============================================================================== */
function core_translate_ADtoBD($year_AD){
	return $year_AD + 543;
}

function explodeStr($Str){
	
				$deeds_owner = explode(";",$Str) ;
				
				return $deeds_owner;
}
function explode_Count($Str){
		
				$deeds_owner_num = count(explode(";",$Str));
				$deeds_owner_num = $deeds_owner_num-1;
				return $deeds_owner_num;
}
function explode_bank_Str($Str){
	
				$deeds_owner = explode("+",$Str) ;
				
				return $deeds_owner;
}
function explode_bank_Count($Str){
		
				$deeds_owner_num = count(explode("+",$Str));
				$deeds_owner_num = $deeds_owner_num-1;
				return $deeds_owner_num;
}
function date_ch_form($Str){
		list($dd,$mm,$yy)=split("/",$Str);
		$yy =$yy-543;
				$new_date = "$yy-$mm-$dd";
				return $new_date;
}
function date_ch_form_c($Str){
		list($yy,$mm,$dd)=split("-",$Str);
		$yy =$yy+543;
				$new_date = "$dd/$mm/$yy";
				return $new_date;
}
function date_ch_form_c2($Str){
		list($yy,$mm,$dd)=split("-",$Str);
		
				$new_date = "$dd/$mm/$yy";
				return $new_date;
}
function date_ch_form_m($Str){
		list($yy,$mm,$dd)=split("-",$Str);
		$yy =$yy+543;
				$new_date = "$mm/$yy";
				return $new_date;
}
function date_ch_to_db($Str){
		list($dd,$mm,$yy)=split("/",$Str);
		
				$new_date = "$yy-$mm-$dd";
				return $new_date;
}
function date_and_time($Str){
		list($date,$time)=split(" ",$Str);
		
				$new_date = "$date";
				return $new_date;
}
function func_math_roundup($value, $dp)
{
    $LIMIT = pow (10, -($dp + 1)) * 5;
    return round ($value + $LIMIT, $dp);
}


function func_tal_hp_cal($contract_id, $car_month, $car_credit, $car_rate, $vat_rate, $isfirsthand, $req){
	// เป็นการคำนวณแบบ FLATRATE
	
	/*
	$contract_id	เลขที่สัญญา
	$car_month		จำนวนเดือน
	$car_credit		ยอดเช่าซื้อ
	$car_rate		ดอกเบี้ย
	$isfirsthand	เป็นรถใหม่หรือรถเก่า (0-รถเก่า 1-รถใหม่)
	$req			return อะไร? 
		0- ผ่อนงวดละ (รวม VAT),		$installment
		1- เฉพาะภาษีงวดละ,				$vat_per_month
		2- เฉพาะ่างวดงวดละ,			$capital_per_month
		3- ยอดผ่อนงวดทั้งหมด,			$total_pay
		4- เยอดเฉพาะภาษีทั้งหมด,			$total_vat
		5- ยอดเฉพาะงวดผ่อนทั้งหมด		$total_capital
	
	$int_per_month		อัตรดอกเบี้ยต่อเดือน
	$total_pay			ยอดที่ต้องจ่ายตลอดสัญญารวม VAT
	$installment		ค่างวดรวมVAT/เดือน
	$vat_per_month		ค่างวด/เดือน (เฉพาะ VAT)
	$capital_per_month	ค่างวด/เดือน (ไม่มี VAT)
	$total_vat = 		VAT ทั้งสัญญา
	$total_capital = 	เงินผ่อนทั้งสัญญา (ไม่ VAT)
	*/
	
	$int_per_month = $car_rate/12;
	
	$total_pay = ($car_credit+($car_credit*($int_per_month/100)*$car_month));
	if($isfirsthand); // รถใหม่มี VAT อยู่แล้ว ไม่ต้องเพิ่มเข้าไป
	else // ถ้าเป็นรุถเก่าให้ มี VAT เพิ่มเข้าไป
		$total_pay += ($car_credit+($car_credit*($int_per_month/100)*$car_month))*($vat_rate/100);		
	
	$installment = round($total_pay/$car_month);
	$capital_per_month = round($installment*(100/107),2); // todo WFC-VAT
	$vat_per_month = $installment-$capital_per_month;
	
	
	$total_vat = $vat_per_month*$car_month;
	$total_capital = $capital_per_month*$car_month;
	$total_pay = $installment*$car_month;
	
	if($req == 0)
		return $installment;
	else if($req == 1)
		return $vat_per_month;
	else if($req == 2)
		return $capital_per_month;
	else if($req == 3)
		return $total_pay;
	else if($req == 4)
		return $total_vat;
	else if($req == 5)
		return $total_capital;
		
}
// ทดสอบว่า ค่าต่างๆ เพื่อใช้ในการ จดจำนอง ถูกต้องหรือไม่ (ยอดจด,ดอกเบี้ยปกติ,วันเริ่ม,วันจ่าย,ระยะเวลา,จ่ายขั้นต่ำ)
function func_mort_check_valid_accounting($credit,$int_normal,$start_date,$pay_date,$length,$min_pay){
	$strFileName = "test.txt"; 
$objFopen = fopen($strFileName, 'w'); 
	while($length > 0){ // Loop การจ่ายตรง due ตรงจำนวน แต่ละเดือนจนครบกำหนด
		$num_day = core_time_datediff($start_date, core_time_nextmonth($start_date, $pay_date));
		$start_date = core_time_nextmonth($start_date, $pay_date);
		$interest_month = round((($int_normal/100)*$credit*$num_day*(1/365)),2);
		$credit -= $min_pay - $interest_month;
		$length--;
		$strText = "$length::$start_date::$num_day::$min_pay::$interest_month::$credit\n"; 
		fwrite($objFopen, $strText);
	}
	fclose($objFopen); 
	return $credit;
}
function func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$pay_date,$length,$min_pay,$first_pay_date){
	$strFileName = "test.txt"; 
$objFopen = fopen($strFileName, 'w'); 
		$f=1;//เช็คว่าเป็นครั้งแรกหรือไม่
		
	while($length > 0){ // Loop การจ่ายตรง due ตรงจำนวน แต่ละเดือนจนครบกำหนด
	    if($f==1){//เช็คว่าเป็นครั้งแรกหรือไม่ 1= เป็นครั้งแรก
		
		
		$num_day = core_time_datediff($start_date, $first_pay_date);
			$start_date = $first_pay_date;
			$f=0; //setค่าให้เป็น 0 เพื่อออกจาก เงื่อนไข
		}else{ //f !+1 ไม่เป็นครั้งแรก
		//echo $start_date;
		$start_date2 = core_time_nextmonth($start_date, $pay_date);
		$num_day = core_time_datediff($start_date,$start_date2);
		$start_date = $start_date2;
		
		}
		
		
		$interest_month = round((($int_normal/100)*$credit*$num_day*(1/365)),2);
		$credit -= $min_pay - $interest_month;
		$length--;
		$strText = "$length::$start_date::$num_day::$min_pay::$interest_month::$credit\n"; 
		fwrite($objFopen, $strText);
	}
	fclose($objFopen); 
	return $credit;
}

function NumberToText($number){//แปลงตัวเลขเป็นตัวอักษร
  $txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ');
  $txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');
  $number = str_replace(",","",$number);
  $number = str_replace(" ","",$number);
  $number = str_replace("บาท","",$number);
  $number = explode(".",$number);
  if(sizeof($number)>2){
    return 'ทศนิยมหลายตัวนะจ๊ะ';
    exit;
  }
  $strlen = strlen($number[0]);
  $convert = '';
  for($i=0;$i<$strlen;$i++){
    $n = substr($number[0], $i,1);
    if($n!=0){
      if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; }
      elseif($i==($strlen-2) AND $n==2){ $convert .= 'ยี่'; }
      elseif($i==($strlen-2) AND $n==1){ $convert .= ''; }
      else{ $convert .= $txtnum1[$n]; }
      $convert .= $txtnum2[$strlen-$i-1];
    }
  }
  $convert .= 'บาท';
  if($number[1]=='0' OR $number[1]=='00' OR $number[1]==''){
    $convert .= 'ถ้วน';
  }else{
    $strlen = strlen($number[1]);
    for($i=0;$i<$strlen;$i++){
      $n = substr($number[1], $i,1);
      if($n!=0){
        if($i==($strlen-1) AND $n==1){$convert .= 'เอ็ด';}
        elseif($i==($strlen-2) AND $n==2){$convert .= 'ยี่';}
        elseif($i==($strlen-2) AND $n==1){$convert .= '';}
        else{ $convert .= $txtnum1[$n];}
        $convert .= $txtnum2[$strlen-$i-1];
      }
    }
    $convert .= 'สตางค์';
  }
  return $convert;
}
?>