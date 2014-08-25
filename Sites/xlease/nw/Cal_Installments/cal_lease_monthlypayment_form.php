<?PHP
session_start();
include("../../config/config.php");

$user_id = $_SESSION["av_iduser"];
$pay = $_POST['pay']; // ประเภทการค้นหา

//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) คำนวณยอดผ่อน',LOCALTIMESTAMP(0))");
//ACTIONLOG---

if($pay == 'pay04'){ // (เช่าซื้อ) คำนวณหายอดผ่อนต่อเดือน
	
	$pay04_datestart = $_POST["datestart"]; //วันที่ทำสัญญา
	$pay04_investment = $_POST["investment"]; //ยอดจัด/ยอดลงทุน
	$pay04_notvat = $_POST["vat"]; //รวม VAT หรือไม่
	$pay04_vatcal = $_POST["vatcal"]; //VAT
	$pay04_interest = $_POST["interest"]; //อัตราดอกเบี้ยต่อปี
	$pay04_month = $_POST["month"]; //จำนวนเดือน
	
	IF($pay04_notvat == 'sumvat'){
		/*    
		- กรณี ratio เลือกรวม VAT
		ยอดผ่อนต่อเดือน = ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) - [ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) * (7/107)]
		ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) = [ยอดลงทุน * (((อัตราดอกเบี้ยต่อปี*(จำนวนเดือน / 12))+100)/100) *(1/จำนวนเดือน)]
		[] - ปัดเศษ 2 ตำแหน่ง
		() - ไม่ปัดเศษ
		*/
		/*__คำนวณแบบคิดเศษทศนิยม____________*/
		$paypermonthsumvat = round($pay04_investment * (((($pay04_interest*($pay04_month/12))+100)/100)*(1/$pay04_month)),2); //ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) 
		$paypermonth = $paypermonthsumvat - round($paypermonthsumvat*(7/107),2); //ยอดผ่อนต่อเดือน	
		$paypermonthshow = number_format($paypermonth ,2);
		$paypermonthsumvatshow = number_format($paypermonthsumvat ,2);
		/*__คำนวณแบบปัดเศษทศนิยม____________*/
		$paypermonthsumvat2 = round($pay04_investment * (((($pay04_interest*($pay04_month/12))+100)/100)*(1/$pay04_month)),2); //ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) 
		$paypermonth2 = ceil($paypermonthsumvat2 - round($paypermonthsumvat2*(7/107),2)); //ยอดผ่อนต่อเดือน	
		$paypermonthshow2 = number_format($paypermonth2,2);
		$paypermonthsumvatshow2 = number_format($paypermonthsumvat2 ,2);
		
		$pay04_investment  = number_format($pay04_investment,2);
		
		echo " วันที่ทำสัญญา : $pay04_datestart \n ยอดจัด/ยอดลงทุน : $pay04_investment บาท \n รวม VAT หรือไม่ : รวม  \n อัตราดอกเบี้ยต่อปี : $pay04_interest % \n จำนวนเดือน : $pay04_month เดือน \n\n<fieldset><legend>คำนวณแบบคิดเศษทศนิยม</legend>\n ยอดผ่อนต่อเดือน : $paypermonthshow บาท \n ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) : $paypermonthsumvatshow บาท </fieldset>\n\n<fieldset><legend>คำนวณแบบปัดเศษทศนิยม</legend>\n ยอดผ่อนต่อเดือน : $paypermonthshow2 บาท \n ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) : $paypermonthsumvatshow2 บาท </fieldset>\n\n\n คุณต้องการพิมพ์รายละเอียดทั้งหมดหรือไม่ ? \n";
	}else{
		/* 	
		-กรณี ratio เลือก ไม่รวม VAT
		ยอดผ่อนต่อเดือน = [ยอดลงทุน * (((อัตราดอกเบี้ยต่อปี*(จำนวนเดือน / 12))+100)/100) *(1/จำนวนเดือน)]
		ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) = [ยอดผ่อนต่อเดือน * ((อัตราภาษี+100)/100)] 
		[] - ปัดเศษ 2 ตำแหน่ง
		() - ไม่ปัดเศษ
		*/
		/*__คำนวณแบบคิดเศษทศนิยม____________*/
		$paypermonth = round($pay04_investment * ((($pay04_interest*($pay04_month/12))+100)/100)*(1/$pay04_month),2); //ยอดผ่อนต่อเดือน
		$paypermonthsumvat = round($paypermonth * (($pay04_vatcal+100)/100),2); //ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) 	
		$paypermonthshow = number_format($paypermonth ,2);
		$paypermonthsumvatshow = number_format($paypermonthsumvat ,2);
		/*__คำนวณแบบปัดเศษทศนิยม____________*/
		$paypermonth2 = ceil($pay04_investment * ((($pay04_interest*($pay04_month/12))+100)/100)*(1/$pay04_month)); //ยอดผ่อนต่อเดือน
		$paypermonthsumvat2 = round($paypermonth2 * (($pay04_vatcal+100)/100),2); //ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) 	
		$paypermonthshow2 = number_format($paypermonth2 ,2);
		$paypermonthsumvatshow2 = number_format($paypermonthsumvat2 ,2);
		
		$pay04_investment  = number_format($pay04_investment,2);
		
		echo " วันที่ทำสัญญา : $pay04_datestart \n ยอดจัด/ยอดลงทุน : $pay04_investment บาท \n รวม VAT หรือไม่ : ไม่รวม \n VAT : $pay04_vatcal % \n อัตราดอกเบี้ยต่อปี : $pay04_interest % \n จำนวนเดือน : $pay04_month เดือน \n\n<fieldset><legend>คำนวณแบบคิดเศษทศนิยม</legend>\n ยอดผ่อนต่อเดือน : $paypermonthshow บาท \n ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) : $paypermonthsumvatshow บาท </fieldset>\n\n<fieldset><legend>คำนวณแบบปัดเศษทศนิยม</legend> \n ยอดผ่อนต่อเดือน : $paypermonthshow2 บาท \n ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) : $paypermonthsumvatshow2 บาท </fieldset>\n\n\n คุณต้องการพิมพ์รายละเอียดทั้งหมดหรือไม่ ? \n";
	}
}	
	
?>

