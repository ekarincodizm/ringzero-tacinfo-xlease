<?php

require_once("sys_initial.php");

// เวลาที่คงสถานะ Login
$sys_staylogon = time()+3600;

// เวลามาตราฐาน
date_default_timezone_set('Asia/Bangkok');

// Load ข้อมูลจาก html
function func_fetch_html($file_html){
	$fopen = fopen($file_html, "r");
	while(!feof($fopen)){
		$data = fgets($fopen);
		echo "$data";
	} 
	fclose($fopen);
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
function explodeStrPlus($Str){
	
				$data = explode("+",$Str) ;
				
				return $data;
}
function explode_CountPlus($Str){
		
				$data_num = count(explode("+",$Str));
				$data_num = $data_num-1;
				return $data_num;
}
function date_ch_form($Str){
		list($dd,$mm,$yy)=split("/",$Str);
		//$yy =$yy-543;
				$new_date = "$yy-$mm-$dd";
				return $new_date;
}
function date_ch_form_c($Str){
		list($yy,$mm,$dd)=split("-",$Str);
		//$yy =$yy+543;
				$new_date = "$dd/$mm/$yy";
				return $new_date;
}
function date_ch_form_m($Str){
		list($yy,$mm,$dd)=split("-",$Str);
		//$yy =$yy+543;
				$new_date = "$mm/$yy";
				return $new_date;
}
function createCookie($name, $value='', $maxage=0, $path='', $domain='', $secure=false, $HTTPOnly=false)
 {
        $ob = ini_get('output_buffering');

        // Abort the method if headers have already been sent, except when output buffering has been enabled
        if ( headers_sent() && (bool) $ob === false || strtolower($ob) == 'off' )
            return false;

        if ( !empty($domain) )
        {
            // Fix the domain to accept domains with and without 'www.'.
            if ( strtolower( substr($domain, 0, 4) ) == 'www.' ) $domain = substr($domain, 4);
            // Add the dot prefix to ensure compatibility with subdomains
            if ( substr($domain, 0, 1) != '.' ) $domain = '.'.$domain;

            // Remove port information.
            $port = strpos($domain, ':');

            if ( $port !== false ) $domain = substr($domain, 0, $port);
        }

        // Prevent "headers already sent" error with utf8 support (BOM)
        //if ( utf8_support ) header('Content-Type: text/html; charset=utf-8');

        header('Set-Cookie: '.rawurlencode($name).'='.rawurlencode($value)
                                    .(empty($domain) ? '' : '; Domain='.$domain)
                                    .(empty($maxage) ? '' : '; Max-Age='.$maxage)
                                    .(empty($path) ? '' : '; Path='.$path)
                                    .(!$secure ? '' : '; Secure')
                                    .(!$HTTPOnly ? '' : '; HttpOnly'), false);
        return true;
} 

// ปัดเศษขึ้นตลอดโดยไม่สนทศนิยม
function func_math_roundup ($value, $dp)
{
    $offset = pow (10, -($dp + 1)) * 5;
    return round ($value + $offset, $dp);
}

function func_time_year_translate($date,$add_char) // Translate from YYYY-MM-DD to DD*MM*YYYY
{
	$date_year = $date[0].$date[1].$date[2].$date[3];
	//$date_year =$date_year+543;
	$date_month = $date[5].$date[6];
	$date_day = $date[8].$date[9];
	
	return $date_day.$add_char.$date_month.$add_char.$date_year;
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
	
	$offset = $second_date-$first_date;
	return FLOOR($offset/60/60/24);
}

// return next month at $day like (21-08-2010,23) -> 23-09-2010
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

	/*
	// สูตรเก่าในการคำนวณ แต่จะมีปัญหา ถ้าเจอวันที่ 29 กุุมภา หรือ เดือนที่มี 31,30 วัน
	if($date_day == $day) // ถ้าต้องให้ใช้เดือนถัดไปได้เลย ในวันที่เดิม หรือ $day=0
		return date("Y-m-d",strtotime('+1 months', strtotime( $date)));
	else{
		$new_date = $date_year."-".$date_month."-".$day;
		return date("Y-m-d",strtotime('+1 months', strtotime( $new_date)));
	}
	*/
}

function func_tal_hp_cal($IDNO, $car_month, $car_credit, $car_rate, $vat_rate, $isfirsthand, $req){
	// เป็นการคำนวณแบบ FLATRATE
	
	/*
	$IDNO	เลขที่สัญญา
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
		$num_day = func_time_datediff($start_date, func_time_next_month($start_date, $pay_date));
		$start_date = func_time_next_month($start_date, $pay_date);
		$interest_month = round((($int_normal/100)*$credit*$num_day*(1/365)),2);
		$credit -= $min_pay - $interest_month;
		$length--;
		$strText = "$length::$start_date::$num_day::$min_pay::$interest_month::$credit\n"; 
		fwrite($objFopen, $strText);
	}
	fclose($objFopen); 
	return $credit;
}

// Check file UPLOAD
function func_check_valid_upload($code){
	if ($code == UPLOAD_ERR_OK) {
		return;
	}

	switch ($code) {
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
		$msg = 'File is too large';
		break;
		
		case UPLOAD_ERR_PARTIAL:
		$msg = 'File was only partially uploaded';
		break;
		 
		case UPLOAD_ERR_NO_FILE:
		$msg = 'No File was uploaded';
		break;
		 
		case UPLOAD_ERR_NO_TMP_DIR:
		$msg = 'Upload folder not found';
		break;
		 
		case UPLOAD_ERR_CANT_WRITE:
		$msg = 'Unable to write uploaded file';
		break;
		 
		case UPLOAD_ERR_EXTENSION:
		$msg = 'Upload failed due to extension';
		break;
		 
		default:
		$msg = 'Unknown error';
	}
	throw new Exception($msg);
}

// Generate ID สำหรับ table และ field ต่างๆ ในรูปแบบของ (รหัสหน้า, ชื่อ table, ชื่อ field, วันที่หรือคำ, จำนวนหลักรหัส)
function generate_id($prefix, $dbtb, $field, $date, $num){
	$query = 	"SELECT $field 
				FROM $dbtb
				WHERE $field LIKE '%$date%'";
				
	$sql_query = pg_query($query);
	$num_row = pg_num_rows($sql_query);
	
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

// Generate ID สำหรับ table และ field ต่างๆ ในรูปแบบของ (รหัสหน้า, ชื่อ table, ชื่อ field, วันที่หรือคำ, จำนวนหลักรหัส)
function generate_id_receipt($prefix, $dbtb, $field, $date, $num ,$date_field){
	$query = 	"SELECT $field 
				FROM $dbtb
				WHERE date = '$date_field' ";
				
	$sql_query = pg_query($query);
	$num_row = pg_num_rows($sql_query);
	
	if($num_row=='0'){
				$query2 =	"INSERT INTO $dbtb (date,$field) 
					VALUES('$date_field','0') ";
					$sql_query2 = pg_query($query2);
					$reciept_num=0;
	}else{
		while($sql_row = pg_fetch_array($sql_query))
		{			
			$reciept_num =$sql_row[$field];
		}
	}
	
	$code = $prefix.$date;
	$reciept_num_new = $reciept_num+1;
	
	$query5 =	"UPDATE $dbtb SET 
	
					$field='$reciept_num_new'
	
					WHERE date='$date_field' ";
		$sql_query5 = pg_query($query5);

	if($num == 1) {
		if($reciept_num < 9) return $code.($reciept_num+1);
		else return $code."9";
	}

	else if($num == 2) {
		if($reciept_num < 9) return $code."0".($reciept_num+1);
		else if($reciept_num < 99) return $code.($reciept_num+1);
		else return $code."99";
	}

	else if($num == 3) {
		if($reciept_num < 9) return $code."00".($reciept_num+1);
		else if($reciept_num < 99) return $code."0".($reciept_num+1);
		else if($reciept_num < 999) return $code.($reciept_num+1);
		else return $code."999";
	}
	
	else if($num == 4) {
		if($reciept_num < 9) return $code."000".($reciept_num+1);
		else if($reciept_num < 99) return $code."00".($reciept_num+1);
		else if($reciept_num < 999) return $code."0".($reciept_num+1);
		else if($reciept_num < 9999) return $code.($reciept_num+1);
		else return $code."9999";
	}
	
	else if($num == 5) {
		if($reciept_num < 9) return $code."0000".($reciept_num+1);
		else if($reciept_num < 99) return $code."000".($reciept_num+1);
		else if($reciept_num < 999) return $code."00".($reciept_num+1);
		else if($reciept_num < 9999) return $code."0".($reciept_num+1);
		else if($reciept_num < 99999) return $code.($reciept_num+1);
		else return $code."99999";
	}
	
	else {
		if($reciept_num < 9) return $code."000".($reciept_num+1);
		else if($reciept_num < 99) return $code."00".($reciept_num+1);
		else if($reciept_num < 999) return $code."0".($reciept_num+1);
		else if($reciept_num < 9999) return $code.($reciept_num+1);
		else return $code."9999";
	}
	
	
}

// Generate ID สำหรับ table และ field ต่างๆ ในรูปแบบของ (รหัสหน้า, ชื่อ table, ชื่อ field, วันที่หรือคำ, จำนวนหลักรหัส)
function generate_addr_id($prefix, $dbtb, $field, $date, $num){
	$query = 	"SELECT $field 
				FROM $dbtb
				WHERE $field LIKE '%$date%'";
	$sql_query = pg_query($query);
	$num_row = pg_num_rows($sql_query);
	
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

// Create log จากรายละเอียด
function generate_log($dbtb,$luser,$laction,$ltarget,$lpos){
	
	// กำนหดค่า current time เพื่อ stamp เวลา (กำหนดใหม่เพราะ function ไม่มอง variable นอก func)
	$info_currentdatetimesql = strtotime(date("Y-m-d H:i:s"));
	
	$query = "INSERT INTO $dbtb(logs_users, logs_action, logs_target, logs_pos, logs_time)
				VALUES('$luser','$laction','$ltarget','$lpos','$info_currentdatetimesql2')";
	$sql_query = pg_query($query);
}

function generate_cmort_id($cus_idnum,$cmort_version,$cmort_db='contracts_mortgage',$cmort_f_id='cmort_id'){
	/*
	w = รหัสประจำตัวประชาชน/เลขประจำตัวผู้เสียภาษี (กรณีนิติบุคคล)
	x = รหัสสาขา
	y = รหัสครั้งที่จำนอง
	z = ขึ้นจำนองครั้งที่?
	โครงสร้างรหัส wwwwwwwwwwwww-xxyyy-zz
	*/
	
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! ยังไม่สมบูรณ์ !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// - ขาดตรวจเช็คการยกเลิก
	
	$branch_code = $cmort_version[0].$cmort_version[1]; // นำรหัส สาขามาใส่
	$code = $cus_idnum."-".$branch_code;
	
	$query = 	"SELECT $cmort_f_id 
				FROM $cmort_db
				WHERE $cmort_f_id LIKE '%$cus_idnum%'
				ORDER BY $cmort_f_id ASC";
	$sql_query = pg_query($query);
	$num_row = pg_num_rows($sql_query);
	if($num_row != 0){ // เคยมีบัญชีมาแล้ว หาว่ามีมาแล้ว ล่าสุดเป็นอะไร
		while($sql_row = pg_fetch_array($sql_query))
		{
			$num_row--;
			if($num_row = 0){ // เป็นรายการสุดท้าย
				$last_num = $sql_row[$cmort_f_id][16].$sql_row[$cmort_f_id][17].$sql_row[$cmort_f_id][18];
				if($last_num < 9) return $code."00".($last_num+1)."-00";
				else if($last_num < 99) return $code."0".($last_num+1)."-00";
				else if($last_num < 999) return $code.($last_num+1)."-00";
				else return $code."999-00";
			}
		}
	}
	else // ไม่เคยมีข้อมูลมาก่อน ให้เป็น ฐัยชีแรก
		$code = $code."001-00";

	return $code;
}

function generate_hp_sub_id($car_contract_db='car_contract',$contract_sub_id_f='contract_sub_id', $num=3){
	/*
	//โครงสร้างรหัส xxxxxxxxxxxxx แบบ RANDOM
	
	$rand = rand(0,9);
	while(true){
		// random เลข 12 หลัก รวมหลักแรกก่อนหน้าเป็น 13 หลัก
		for($i=1; $i<=12; $i++)
			$rand .= rand(0,9);
			
		$query = 	"SELECT $contract_sub_id_f
					FROM $car_contract_db
					WHERE $contract_sub_id_f='$rand'";
		$sql_query = pg_query($query);
		$num_row = pg_num_rows($sql_query);
		if($num_row != 0);
		else // ไม่มีเลข sub id นี้มาก่อนใช้ id นี้เลย
			return $rand;
	}*/
	
	// โครงสร้างรหัส SQ-YYYYMMDD/xxx
	$prefix = "SQ-";		// อักษณกำกับเอกสาร
	$date = date("Ymd")."-";	// ปีเดือนวันกำกับเอกสาร
	
	$query = 	"SELECT $contract_sub_id_f 
				FROM $car_contract_db";
	$sql_query = pg_query($query);
	$num_row = pg_num_rows($sql_query);
	
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

function generate_hp_uni_id($car_contract_db='car_contract',$contract_sub_id_f='contract_sub_id', $num=3){
	
	//โครงสร้างรหัส xxxxxxxxxxxxx แบบ RANDOM
	
	$rand = rand(0,9);
	while(true){
		// random เลข 12 หลัก รวมหลักแรกก่อนหน้าเป็น 13 หลัก
		for($i=1; $i<=12; $i++)
			$rand .= rand(0,9);
			
		$query = 	"SELECT $contract_sub_id_f
					FROM $car_contract_db
					WHERE $contract_sub_id_f='$rand'";
		$sql_query = pg_query($query);
		$num_row = pg_num_rows($sql_query);
		if($num_row != 0);
		else // ไม่มีเลข sub id นี้มาก่อนใช้ id นี้เลย
			return $rand;
	}
}

// Generate HP Contract (Audit Code)
function generate_hp_cal_check_code($uni_id, $car_month, $car_credit, $car_rate, $digit=13){
	// digit จำนวนหลักของ รหัสที่ต้องการ โดยค่า default ถ้าไม่ใส่ คือ 13 หลัก
	
	/*
	// เอาเฉพาะตัวเลขในรหัส
	$contract_sub_id_tmp=$IDNO[3].$IDNO[4].$IDNO[5].$IDNO[6].$IDNO[7].$IDNO[8].$IDNO[9].$IDNO[10];
	$contract_sub_id_tmp .= $IDNO[12].$IDNO[13].$IDNO[14];
	$contract_sub_id = $contract_sub_id_tmp;
	*/
	
	$code = '';
	$code_temp1 = '';
	$code_temp2 = '';
	$code_temp3 = '';
	$code_temp4 = '';
	$i = 1;
	while($i <= $digit){
		$code_temp1 = $uni_id%pow(10,1);
		$uni_id /= 10;
		$code_temp2 = $car_month%pow(10,1);
		$car_month /= 10;
		$code_temp3 = $car_credit%pow(10,1);
		$car_credit /= 10;
		$code_temp4 = $car_rate%pow(10,1);
		$car_rate /= 10;
		$code = (($code_temp1+$code_temp2+$code_temp3+$code_temp4)%10).$code;
		$i++;
	}
	return $code;
}

// Generate Contract mortgage (Approve Code)
function generate_cmort_approvecode($cmort_credit, $cmort_length, $cmort_minpay, $digit=13){
	// digit จำนวนหลักของ รหัสที่ต้องการ โดยค่า default ถ้าไม่ใส่ คือ 13 หลัก
	
	$code = '';
	$code_temp1 = '';
	$code_temp2 = '';
	$code_temp3 = '';
	$i = 1;
	while($i <= $digit){
		$code_temp1 = $cmort_credit%pow(10,1);
		$cmort_credit /= 10;
		$code_temp2 = $cmort_length%pow(10,1);
		$cmort_length /= 10;
		$code_temp3 = $cmort_minpay%pow(10,1);
		$cmort_minpay /= 10;
		$code = (($code_temp1+$code_temp2+$code_temp3)%10).$code;
		$i++;
	}
	return $code;
}

// Generate Contract mortgage (Investigator Code)
function generate_cmort_cashiercode($cmort_credit, $cmort_length, $cmort_minpay, $cmort_cnet, $digit=13){
	// digit จำนวนหลักของ รหัสที่ต้องการ โดยค่า default ถ้าไม่ใส่ คือ 13 หลัก
	
	$code = '';
	$code_temp1 = '';
	$code_temp2 = '';
	$code_temp3 = '';
	$code_temp4 = '';
	$i = 1;
	while($i <= $digit){
		$code_temp1 = $cmort_credit%pow(10,1);
		$cmort_credit /= 10;
		$code_temp2 = $cmort_length%pow(10,1);
		$cmort_length /= 10;
		$code_temp3 = $cmort_minpay%pow(10,1);
		$cmort_minpay /= 10;
		$code_temp4 = $cmort_cnet%pow(10,1);
		$cmort_cnet /= 10;	
		$code = (($code_temp1+$code_temp2+$code_temp3+$code_temp4)%10).$code;
		$i++;
	}
	return $code;
}

// แปลแผนกภาษาไทยเป็นตัวย่อแผนก
function translate_div_to_code($div,$tb){
	$query2 =	"SELECT * 
				FROM $tb
				WHERE users_default_base = '$div' and users_group = '1' ";
				$sql_query2 = pg_query($query2);
				//echo $query2;
				while($sql_row2 = pg_fetch_array($sql_query2))
						{
	            		$users_default_value = $sql_row2['users_default_value'];
						}
						return $users_default_value;
			
	
	
}

// แปลค่าตัวเลขเดือนเป็นภาษาไทย
function translate_month($month){
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
function translate_code_to_addr_type($addr_type){
	switch ($addr_type) {
		case "R":
			return "บ้านเช่า";
			break;
		case "F":
			return "แฟลต";
			break;
		case "C":
			return "คอนโด";
			break;
		case "A":
			return "อพาทเม้นท์";
			break;
		case "B":
			return "ตึกแถว";
			break;
		case "H":
			return "บ้านเดี่ยว";
			break;
		case "T":
			return "ทาวเฮ้าส์";
			break;
					
		default:
			return "ไม่มีลักษณะของบ้านนี้!";
	}
}
function translate_addr_type_to_code($addr_type){
	switch ($addr_type) {
		case "บ้านเช่า":
			return "R";
			break;
		case "แฟลต":
			return "F";
			break;
		case "คอนโด":
			return "C";
			break;
		case "อพาทเม้นท์":
			return "A";
			break;
		case "ตึกแถว":
			return "B";
			break;
		case "บ้านเดี่ยว":
			return "H";
			break;
		case "ทาวเฮ้าส์":
			return "T";
			break;
					
		default:
			return "N"; // No
	}
}
function image_add($i,$image_name,$image_id,$dbtb_ta_images,$tb,$des){

	$errors = array();
		try {
			if (!array_key_exists($image_name, $_FILES)) {
				throw new Exception('Image not found in uploaded data');
			}
			
			
			$image = $_FILES[$image_name];
	 
			// ensure the file was successfully uploaded
			func_check_valid_upload($image['error']);
	 
			if (!is_uploaded_file($image['tmp_name'])) {
				throw new Exception('File is not an uploaded file');
			}
	 
			$info = getImageSize($image['tmp_name']);
	// $info = $_FILES[$image_name]['type'] ;
			if (!$info) {
				throw new Exception('File is not an image');
			}
		}
		catch (Exception $ex) {
			$errors[] = $ex->getMessage();
		}
	 	//echo $ex;
		if (count($errors) == 0) {
			// no errors, so insert the image
	 
				$query = sprintf(
				"INSERT INTO $dbtb_ta_images(img_id, img_name, img_mime_type, img_size, img_data, img_from, img_from_type)
					VALUES('$image_id','%s', '%s', %d, '%s', '$tb', '$image_name $des')",
				pg_escape_bytea($image['name']),
				$info['mime'],
				pg_escape_bytea($image['size']),
				pg_escape_bytea(file_get_contents($image['tmp_name']))
				);
		
				
			$sql_query = pg_query($query);
			$image_name_value = 1;
		} else $image_name_value = 0;
						
	return $image_name_value;
			
			}
function image_edit($i,$image_name,$image_id,$dbtb_ta_images,$tb,$des,$image_value){
	
$image_name_value = $image_value;


	$errors = array();
	try {
		if (!array_key_exists($image_name, $_FILES)) {
			throw new Exception('Image not found in uploaded data');
		}
			
		$image = $_FILES[$image_name];
	 
		// ensure the file was successfully uploaded
		func_check_valid_upload($image['error']);
	 
		if (!is_uploaded_file($image['tmp_name'])) {
			throw new Exception('File is not an uploaded file');
		}
	 
		$info = getImageSize($image['tmp_name']);
	 
		if (!$info) {
			throw new Exception('File is not an image');
		}
	}
	catch (Exception $ex) {
		$errors[] = $ex->getMessage();
	}
	 
	if (count($errors) == 0 && $image_value) {
	// no errors & had image
//echo $ex;
	$query = sprintf(
		"UPDATE $dbtb_ta_images SET
				img_name='%s', img_mime_type='%s', img_size=%d, img_data='%s' 
				WHERE img_id='$image_id' AND img_from_type LIKE '%s'",
				pg_escape_bytea($image['name']),
				$info['mime'],
				pg_escape_bytea($image['size']),
				pg_escape_bytea(file_get_contents($image['tmp_name'])),
				$image_name. "%"
				);
				
	$sql_query = pg_query($query);
	
	$image_name_value=$image_value;
	//generate_log($dbtb_ta_logs,$_SESSION['user_id'],$log_action_cus_image_edit,$cus_id." $image_name",$log_pos_customers);
	} else if(count($errors) == 0){ 
		// no errors, no image before so insert the image
		$query = sprintf(
			"INSERT INTO $dbtb_ta_images(img_id, img_name, img_mime_type, img_size, img_data, img_from, img_from_type)
				VALUES('$image_id','%s', '%s', %d, '%s', '$tb', '$image_name $des')",
				pg_escape_bytea($image['name']),
				$info['mime'],
				pg_escape_bytea($image['size']),
				pg_escape_bytea(file_get_contents($image['tmp_name']))
				);
				
		$sql_query = pg_query($query);
		//generate_log($dbtb_ta_logs,$_SESSION['user_id'],$log_action_cus_image_add,$cus_id." $image_name",$log_pos_customers);
		$image_name_value = 1;
	}
						
	return $image_name_value;
			
			}
			
function file_add($i,$image_name,$image_id,$dbtb_ta_images,$tb,$des){

	$errors = array();
		try {
			if (!array_key_exists($image_name, $_FILES)) {
				throw new Exception('Image not found in uploaded data');
			}
			
			
			$image = $_FILES[$image_name];
	 
			// ensure the file was successfully uploaded
			func_check_valid_upload($image['error']);
	 
		        if (!is_uploaded_file($image['tmp_name'])) {
				throw new Exception('File is not an uploaded file');
			}
	 
			//$info = getImageSize($image['tmp_name']);
	 $info = $_FILES[$image_name]['type'] ;
			if (!$info) {
				throw new Exception('File is not an file');
			}
		}
		catch (Exception $ex) {
			$errors[] = $ex->getMessage();
		}
	 	//echo $ex;
		if (count($errors) == 0) {
			// no errors, so insert the image
	 
				$query = sprintf(
				"INSERT INTO $dbtb_ta_images(img_id, img_name, img_mime_type, img_size, img_data, img_from, img_from_type)
					VALUES('$image_id','%s', '%s', %d, '%s', '$tb', '$image_name $des')",
				pg_escape_bytea($image['name']),
				$info,
				pg_escape_bytea($image['size']),
				pg_escape_bytea(file_get_contents($image['tmp_name']))
				);
		
				
			$sql_query = pg_query($query);
			$image_name_value = 1;
		} else $image_name_value = 0;
						
	return $image_name_value;
			
			}
function file_edit($i,$image_name,$image_id,$dbtb_ta_images,$tb,$des,$image_value){
	
$image_name_value = $image_value;


	$errors = array();
	try {
		if (!array_key_exists($image_name, $_FILES)) {
			throw new Exception('Image not found in uploaded data');
		}
			
		$image = $_FILES[$image_name];
	 
		// ensure the file was successfully uploaded
		func_check_valid_upload($image['error']);
	 
		if (!is_uploaded_file($image['tmp_name'])) {
			throw new Exception('File is not an uploaded file');
		}
	 
		$info = $_FILES[$image_name]['type'] ;
	 
		if (!$info) {
			throw new Exception('File is not an file');
		}
	}
	catch (Exception $ex) {
		$errors[] = $ex->getMessage();
	}
	
	if (count($errors) == 0 && $image_value) {
	// no errors & had image

	$query = sprintf(
		"UPDATE $dbtb_ta_images SET
				img_name='%s', img_mime_type='%s', img_size=%d, img_data='%s' 
				WHERE img_id='$image_id' AND img_from_type LIKE '%s'",
				pg_escape_bytea($image['name']),
				$info,
				pg_escape_bytea($image['size']),
				pg_escape_bytea(file_get_contents($image['tmp_name'])),
				$image_name. "%"
				);
	$sql_query = pg_query($query);
	
	$image_name_value=$image_value;
	//generate_log($dbtb_ta_logs,$_SESSION['user_id'],$log_action_cus_image_edit,$cus_id." $image_name",$log_pos_customers);
	} else if(count($errors) == 0){ 
		// no errors, no image before so insert the image
		$query = sprintf(
			"INSERT INTO $dbtb_ta_images(img_id, img_name, img_mime_type, img_size, img_data, img_from, img_from_type)
				VALUES('$image_id','%s', '%s', %d, '%s', '$tb', '$image_name $des')",
				pg_escape_bytea($image['name']),
				$info['mime'],
				pg_escape_bytea($image['size']),
				pg_escape_bytea(file_get_contents($image['tmp_name']))
				);
				
		$sql_query = pg_query($query);
		//generate_log($dbtb_ta_logs,$_SESSION['user_id'],$log_action_cus_image_add,$cus_id." $image_name",$log_pos_customers);
		$image_name_value = 1;
	}
						
	return $image_name_value;
			
			}
			
function setting($setting_name,$dbtb,$default_value,$column_default_name){
	//$db_schema_d_cd = '"d_cd"';	
	//$dbtb_contracts_mortgage_default  = $db_schema_d_cd.'.'.'contracts_mortgage_default';
	 $query2 =	"SELECT $default_value 
			FROM $dbtb  
			WHERE $column_default_name = '$setting_name'";
		
$sql_query2 = pg_query($query2);

while($sql_row2 = pg_fetch_array ($sql_query2))

	{
		$setting_default_value = $sql_row2[$default_value];
		
		return $setting_default_value;
	}
}
function addr_details($addr_id,$dbtb_gen_address){
								$query =	"SELECT * 
				FROM $dbtb_gen_address 
				WHERE addr_id ='$addr_id' ";
	$sql_query = pg_query($query);
	
	
	while($sql_row = pg_fetch_array($sql_query))
	{
	if($sql_row['addr_numaddr']!='')
			$addr = "เลขที่: ".$sql_row['addr_numaddr']." ";
			if($sql_row['addr_village']!='')
			$addr .= "หมู่บ้าน: ".$sql_row['addr_village']." ";
			if($sql_row['addr_soi']!='')
			$addr .= "ซอย: ".$sql_row['addr_soi']." ";
			if($sql_row['addr_road']!='')
			$addr .= "ถนน: ".$sql_row['addr_road']." ";
			if($sql_row['addr_district']!='')
			$addr .= "แขวง/ตำบล: ".$sql_row['addr_district']." ";
			if($sql_row['addr_amphur']!='')
			$addr .= "เขต/อำเภอ: ".$sql_row['addr_amphur']." ";
			if($sql_row['addr_province']!='')
			$addr .= "จังหวัด: ".$sql_row['addr_province']." ";
			if($sql_row['addr_zip']!='')
			$addr .= "รหัสไปรษณีย์: ".$sql_row['addr_zip']." ";
	}
	return $addr;
					}
					function addr_details_sub($addr_id,$dbtb_gen_address){
								$query =	"SELECT * 
				FROM $dbtb_gen_address 
				WHERE addr_id ='$addr_id' ";
	$sql_query = pg_query($query);
	
	
	while($sql_row = pg_fetch_array($sql_query))
	{
	if($sql_row['addr_numaddr']!='')
			$addr = "เลขที่: ".$sql_row['addr_numaddr']." ";
			if($sql_row['addr_village']!='')
			$addr .= "หมู่บ้าน: ".$sql_row['addr_village']." ";
			if($sql_row['addr_soi']!='')
			$addr .= "ซอย: ".$sql_row['addr_soi']." ";
			if($sql_row['addr_road']!='')
			$addr .= "ถนน: ".$sql_row['addr_road']." ";
			if($sql_row['addr_district']!='')
			$addr .= "<br>แขวง/ตำบล: ".$sql_row['addr_district']." ";
			if($sql_row['addr_amphur']!='')
			$addr .= "เขต/อำเภอ: ".$sql_row['addr_amphur']." ";
			if($sql_row['addr_province']!='')
			$addr .= "จังหวัด: ".$sql_row['addr_province']." ";
			if($sql_row['addr_zip']!='')
			$addr .= "รหัสไปรษณีย์: ".$sql_row['addr_zip']." ";
	}
	return $addr;
					}
					
					function getCusJoin($cusID,$f_type,$id_main){
	
	if($f_type==0){
	   $sql_fname = pg_query("SELECT \"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\" FROM \"Fa1\" WHERE \"CusID\"='$cusID' ");
    if($rs_fname = pg_fetch_array($sql_fname)){
		
        $name[0] = trim($rs_fname['A_FIRNAME']);
		$name[1] = trim($rs_fname['A_NAME']);
		$name[2] = trim($rs_fname['A_SIRNAME']);
		
    }
	}else if($f_type==1){
		  $sql_fname = pg_query("SELECT \"prefix\",\"f_name\",\"l_name\" FROM \"ta_join_main\" WHERE \"id\"='$id_main' ");
    if($rs_fname = pg_fetch_array($sql_fname)){
		
        $name[0] = trim($rs_fname['prefix']);
		$name[1] = trim($rs_fname['f_name']);
		$name[2] = trim($rs_fname['l_name']);
		
    }
	
	}
	return $name ;

}

?>