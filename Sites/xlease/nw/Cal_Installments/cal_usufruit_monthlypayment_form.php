<?PHP
session_start();
include("../../config/config.php");

$user_id = $_SESSION["av_iduser"];
$pay = $_POST['pay']; // ประเภทการค้นหา

//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) คำนวณยอดผ่อน',LOCALTIMESTAMP(0))");
//ACTIONLOG---

if($pay == 'pay06'){ // (เช่าซื้อ) คำนวณหายอดผ่อนต่อเดือน	
	$pay06_datestartcon = $_POST["datestartcon"]; //วันที่ทำสัญญา
	$pay06_investment = $_POST["tbmoney"]; //ยอดจัด/ยอดลงทุน
	$pay06_interest = $_POST["interest"]; //อัตราดอกเบี้ยต่อปี
	$pay06_month = $_POST["month"]; //จำนวนเดือน
	$pay06_payday = $_POST["payday"]; //ชำระทุกวันที่
	$pay06_datestart = $_POST["datestart"]; //วันที่เริ่มจ่าย
	$pay06_nw_province = $_POST["nw_province"]; //จังหวัดที่เลือก
	$pay06_payother = $_POST["payother"]; //ค่าใช้จ่ายอื่นๆ
	
	$qry_prov = pg_query("select * from \"price_messenger_rate\" WHERE \"pmr_serial\" = '$pay06_nw_province'"); 
	$re_prov = pg_fetch_array($qry_prov);
	$pmr_destination = $re_prov["pmr_destination"]; //ชื่อจังหวัด
	$pmr_price_inc_vat = $re_prov["pmr_price_inc_vat"]; //ค่าใช้จ่าย
	$pmr_price_inc_vatshow = number_format($pmr_price_inc_vat,2);

	$paypermonth = round($pay06_investment * ((($pay06_interest*($pay06_month/12))+100)/100)*(1/$pay06_month),2); //ยอดผ่อนต่อเดือน
	$paypermonthshow = number_format($paypermonth,2); //ยอดค่าประกันต่อเดือนที่คำนวณได้
	$pay06_investmentshow = number_format($pay06_investment,2);
	$pay06_interestshow = number_format($pay06_interest,2);
	$pay06_payothershow = number_format($pay06_payother,2);
	
	//คำนวณค่าเช่าต่อบุคคลภายนอก
	$hire_perother = ceil($paypermonth);
	$v2 = substr($hire_perother,-1,1);
	while($v2 != 0){			
		$hire_perother++;
		$v2 = substr($hire_perother,-1,1);
	}
	$pay_simshow = number_format($hire_perother,2);
	$hire_perother = $hire_perother + (($hire_perother*20)/100);
	$hire_perothershow = number_format($hire_perother,2);
	
	//ค่าธรรมเนียมจดจำนองและอากรดำเนินการ 
	/*	
		ค่าธรรมเนียมจดจำนอง = [จำนวนเงินต้น * 0.01]
		ค่าอากร = [จำนวนเงินต้น * 0.001]
		[] - ปัดเป็นจำนวนเต็ม, ปัดขึ้นเสมอ
		-----------------------------------------------------------
		*ค่าธรรมเนียมจดจำนองและอากรดำเนินการ* = ค่าธรรมเนียมจดจำนอง + ค่าอากร + 3500
	*/
	$Mortgage = ceil($pay06_investment * 0.01);
	$taxation = ceil($pay06_investment * 0.001);
	$Fee_revenue = $Mortgage + $taxation + 3500;
	$Fee_revenueshow = number_format($Fee_revenue,2);
		
	//รวมเงินที่ต้องเก็บจากลูกค้า
	$total_payother = $pmr_price_inc_vat + $pay06_payother + $Fee_revenue;
	$total_payothershow = number_format($total_payother,2);
	
	echo 	"<fieldset><legend>รายละเอียด</legend>";
	echo	"<p>";
	echo 	"วันที่ทำสัญญา: $pay06_datestartcon <br>";
	echo	"ยอดจัด/ยอดลงทุน: $pay06_investmentshow บาท<br>";
	echo	"อัตราดอกเบี้ยต่อปี: $pay06_interestshow %<br>";
	echo	"จำนวนเดือน:  $pay06_month <br>";
	echo	"ชำระทุกวันที่: $pay06_payday <br>";
	echo	"วันที่เริ่มจ่าย: $pay06_datestart <br>";
	echo	" จังหวัด:  $pmr_destination($pmr_price_inc_vatshow) <br>";
	IF($pay06_payother != ""){
		echo	"ค่าใช้จ่ายอื่นๆ: $pay06_payothershow บาท<br>";
	}
	echo	"<p><font color=\"red\">ค่าธรรมเนียมจดจำนองและอากรดำเนินการ $Fee_revenueshow บาท</font>";
	echo	"<p>รวมค่าใช้จ่ายทั้งหมด : $total_payothershow บาท <br>";
	echo	"<font color=\"blue\">________________________________</font><p>";
	echo	" ยอดค่าประกันต่อเดือน <font color=\"red\"><b>$pay_simshow($paypermonthshow)</b></font> บาท<p>";
	echo	"ค่าเช่าต่อบุคคลภายนอก <font color=\"red\"><b>$hire_perothershow</b></font> บาท";
	echo	"<p>";
	echo	"</fieldset>";
	echo 	"<p><p>";
	echo	"ต้องการพิมพ์ตารางการผ่อนหรือไม่ ?";
}		
?>

