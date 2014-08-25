<?php
set_time_limit(0);
session_start();
include("../../config/config.php");

$db1="ta_mortgage_datastore";
$db2="ta_mortgage";

$check=$_POST["chk"];

pg_query("BEGIN WORK");
$status = 0;

//หาอัตราดอกเบี้ยสูงสุด
$chkrate=mysql_query("select interestrate_default_maximum from $db2.tbcinterestrate_default");
if($resrate=mysql_fetch_array($chkrate)){
	$ratemax=$resrate["interestrate_default_maximum"]; //อัตราดอกเบี้ยสูงสุด
} 
//ดึงค่าที่ได้จาก mysql 
for($i=0;$i<sizeof($check);$i++){
	$query = mysql_query("select a.contract_loans_code,a.appv_credit_money,a.appv_interest,a.appv_month,a.contract_loans_minpay,
	a.contract_loans_startdate,a.ActualTransDate,a.contract_loans_paystart,a.contract_loans_damagecloseaccount,b.cusname from $db1.loan_data a
	inner join $db1.vcustomerbycontract b on a.contract_loans_code=b.contract_loans_code 
	where b.cus_group_type_code='01' and a.contract_loans_code='$check[$i]'"); 
	if($result = mysql_fetch_array($query)){
		$contract_loans_code = $result["contract_loans_code"]; //เลขที่สัญญาจำนอง
		$appv_credit_money = $result["appv_credit_money"]; //จำนวนเงินกู้
		$appv_interest = $result["appv_interest"]; //อัตราดอกเบี้ยที่ตกลงตอนแรก
		$appv_month = $result["appv_month"]; //ระยะเวลาผ่อนชำระคืนเงินกู้ (เดือน)
		$appv_month2=round($appv_month,2);
		$contract_loans_minpay = $result["contract_loans_minpay"]; //จำนวนเงินผ่อนขั้นต่ำต่อ Due
		$startdate = $result["contract_loans_startdate"]; 
		$y=substr($startdate,0,4);
		if($y>="2400"){
			$y=$y-543;
		}else{
			$y=$y;
		}
		$m=substr($startdate,5,2);
		$d=substr($startdate,8,2);
		$contract_loans_startdate=$y."-".$m."-".$d; //วันที่ทำสัญญา
		
		$ActualTransDate = $result["ActualTransDate"];
		$yy=substr($ActualTransDate,0,4);
		if($yy>="2400"){
			$yy=$yy-543;
		}else{
			$yy=$yy;
		}

		$mm=substr($ActualTransDate,5,2);
		$dd=substr($ActualTransDate,8,2);
		$ActualTransDate=$yy."-".$mm."-".$dd; //วันที่รับเิงินที่ขอกู้
		
		$contract_loans_paystart = $result["contract_loans_paystart"];
		$y2=substr($contract_loans_paystart,0,4);
		if($y2>="2400"){
			$y2=$y2-543;
		}else{
			$y2=$y2;
		}

		$m2=substr($contract_loans_paystart,5,2);
		$d2=substr($contract_loans_paystart,8,2); //วันที่ชำระของทุกๆเดือน
		$contract_loans_paystart=$y2."-".$m2."-".$d2; //Due แรก
		
		$conloansclose = $result["contract_loans_damagecloseaccount"]; if($conloansclose=="") $conloansclose="5.00"; //%ค่าปรับปิดบัญชีก่อนกำหนด
		
		
		//insert ลงในตาราง pg
		$ins="INSERT INTO thcap_mg_contract(
            \"contractID\",\"conLoanAmt\", \"conLoanIniRate\", \"conLoanMaxRate\", 
			\"conTerm\", \"conMinPay\", \"conDate\", \"conStartDate\", 
			\"conFirstDue\", \"conRepeatDueDay\",\"conClosedFee\", 
            \"conStatus\", \"conFlow\", rev)
			VALUES ('$contract_loans_code', '$appv_credit_money', '$appv_interest', '$ratemax', 
					'$appv_month2', '$contract_loans_minpay', '$contract_loans_startdate', '$ActualTransDate', 
					'$contract_loans_paystart', '$d2', $conloansclose , 
					'10', '1', '1')";
		if($resins=pg_query($ins)){
		}else{
			$status++;
		}	

		//สร้างตารางการผ่อนชำระหนี้ โดยใช้ function php - thcap_gen_ptDate()
		$ex = thcap_gen_ptDate($contract_loans_code,$contract_loans_minpay,$contract_loans_paystart,$appv_month2);
		$ex2=substr($ex,4,15);
		echo "<div style=\"text-align:center\">สัญญาที่เลือกทำคือ <b>$ex2</b></div><br>";
	}
}
//function
function thcap_gen_ptDate($contractID, $conMinPay, $conFirstDue, $numDue, $dayRepeat=0){

	// กรณีเป็นรูปแบบ 'DD/MM/YYYY' เช่น แปลงกลับเป็น '2011-04-15' แปลงจาก '15/04/2011'
	if($conFirstDue[2] == "/" && $conFirstDue[5] == "/"){
		$conFirstDue_year = $conFirstDue[6].$conFirstDue[7].$conFirstDue[8].$conFirstDue[9];
		$conFirstDue_month = $conFirstDue[3].$conFirstDue[4];
		$conFirstDue_day = $conFirstDue[0].$conFirstDue[1];
		$conFirstDue = $conFirstDue_year."-".$conFirstDue_month."-".$conFirstDue_day;
	}
	
	// กรณีเป็นรูปแบบ 'D/M/YYYY' เช่น แปลงกลับเป็น '2011-04-05' แปลงจาก '5/4/2011'
	if($conFirstDue[1] == "/" && $conFirstDue[3] == "/"){
		$conFirstDue_year = $conFirstDue[4].$conFirstDue[5].$conFirstDue[6].$conFirstDue[7];
		$conFirstDue_month = '0'.$conFirstDue[2];
		$conFirstDue_day = '0'.$conFirstDue[0];
		$conFirstDue = $conFirstDue_year."-".$conFirstDue_month."-".$conFirstDue_day;
	}
	
	// กรณีเป็นรูปแบบ 'DD/M/YYYY' เช่น แปลงกลับเป็น '2011-04-15' แปลงจาก '15/4/2011'
	if($conFirstDue[2] == "/" && $conFirstDue[4] == "/"){
		$conFirstDue_year = $conFirstDue[5].$conFirstDue[6].$conFirstDue[7].$conFirstDue[8];
		$conFirstDue_month = '0'.$conFirstDue[3];
		$conFirstDue_day = $conFirstDue[0].$conFirstDue[1];
		$conFirstDue = $conFirstDue_year."-".$conFirstDue_month."-".$conFirstDue_day;
	}
	
	// กรณีเป็นรูปแบบ 'D/MM/YYYY' เช่น แปลงกลับเป็น '2011-04-05' แปลงจาก '5/04/2011'
	if($conFirstDue[1] == "/" && $conFirstDue[4] == "/"){
		$conFirstDue_year = $conFirstDue[5].$conFirstDue[6].$conFirstDue[7].$conFirstDue[8];
		$conFirstDue_month = $conFirstDue[2].$conFirstDue[3];
		$conFirstDue_day = '0'.$conFirstDue[0];
		$conFirstDue = $conFirstDue_year."-".$conFirstDue_month."-".$conFirstDue_day;
	}

	// ถ้าไม่มีการกำหนด $dayRepeat เข้ามาจะให้เป็นค่า Default ก็คือวันเดียวกันกับที่มาใน $conFirstDue
	if($dayRepeat == 0){
		$dayRepeat = $conFirstDue_day;
	}

	// ให้วันที่งวดแรก เป็นวันที่กรอก firstDue
	$nextDueDate = $conFirstDue;

	// เริ่มต้น TRANSACTION
	pg_query("BEGIN");
	$transactionStatus = 0;

	for($i = 1; $i <= $numDue; $i++){
	
		// INSERT PAY TERM
		$query = 	"INSERT INTO account.\"thcap_mg_payTerm\"(
							\"contractID\",
							\"ptNum\",
							\"ptDate\",
							\"ptMinPay\")
					VALUES ('".$contractID."', 
							".$i.",
							'".$nextDueDate."',
							".$conMinPay.")";

		if($res_ins=pg_query($query)){
		}else{
			$transactionStatus++;
		}

		$nextDueDate = core_time_nextmonth($nextDueDate, $dayRepeat);
	}

	// ถ้า PAY TERM ที่มีไม่เท่ากับ $numDue แสดงว่า INSERT ไม่ครบ ต้องครบถึง COMMIT ถ้าไม่ครบ ROLLBACK และ Return ค่าตามผล
	if($transactionStatus == 0){
		pg_query("COMMIT");
		return '1 - '.$contractID;
	} else {
		pg_query("ROLLBACK");
		return '0 - '.$contractID;
	}
}
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
	else if($date_month == "12") {$next_month = "01"; $date_year += 1; } // ถ้าสิ้นปีเืดือนหน้าต้องเป็นปีถัดไป
	
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

if($status == 0){
	pg_query("COMMIT");
	echo "<br><div style=\"text-align:center;\"><font size=4><b>ดำเนินการเรียบร้อยแล้ว</b></font></div>";
	echo "<div style=\"text-align:center;padding-top:20px;\"><input type=button value=\"กลับหน้าทำรายการ \" onclick=\"window.location='frm_Index.php?show=1'\"></div>";
}else{
	pg_query("ROLLBACK");
	echo $insnw;
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font><br>";
	echo "<div style=\"text-align:center;\"><input type=button value=\"กลับไปทำรายการ \" onclick=\"window.location='frm_Index.php?show=1'\"></div>";
}
?>
