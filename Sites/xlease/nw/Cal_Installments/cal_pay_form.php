<?PHP
session_start();
include("../../config/config.php");

$user_id = $_SESSION["av_iduser"];
$pay = $_POST['pay']; // ประเภทการค้นหา

//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) คำนวณยอดผ่อน',LOCALTIMESTAMP(0))");
//ACTIONLOG---

	if($pay == 'pay05'){ // (เช่าซื้อ) คำนวณหายอดผ่อนต่อเดือน
	
		$pay05_datestart = $_POST["datestart"]; //วันที่ทำสัญญา
		$pay05_investment = $_POST["investment"]; //ยอดจัด/ยอดลงทุน
		$pay05_notvat = $_POST["vat"]; //รวม VAT หรือไม่
		$pay05_vatcal = $_POST["vatcal"]; //VAT
		$pay05_interest = $_POST["interest"]; //อัตราดอกเบี้ยต่อปี
		$pay05_month = $_POST["month"]; //จำนวนเดือน
		
		IF($pay05_notvat == 'sumvat'){	
			/*    
			- กรณี ratio เลือกรวม VAT
			ยอดผ่อนต่อเดือน = ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) - [ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) * (7/107)]
			ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) = [ยอดลงทุน * (((อัตราดอกเบี้ยต่อปี*(จำนวนเดือน / 12))+100)/100) *(1/จำนวนเดือน)]
			[] - ปัดเศษ 2 ตำแหน่ง
			() - ไม่ปัดเศษ
			*/
			/*__คำนวณแบบคิดเศษทศนิยม____________*/
			$paypermonthsumvat = round(($pay05_investment * (((($pay05_interest*($pay05_month/12))+100)/100)*(1/$pay05_month))),2); //ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) 
			$paypermonth = $paypermonthsumvat - round(($paypermonthsumvat*(7/107)),2); //ยอดผ่อนต่อเดือน				
						
			echo "$paypermonthsumvat";
		}else{
		
			/* 	
			-กรณี ratio เลือก ไม่รวม VAT
			ยอดผ่อนต่อเดือน = [ยอดลงทุน * (((อัตราดอกเบี้ยต่อปี*(จำนวนเดือน / 12))+100)/100) *(1/จำนวนเดือน)]
			ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) = [ยอดผ่อนต่อเดือน * ((อัตราภาษี+100)/100)] 
			[] - ปัดเศษ 2 ตำแหน่ง
			() - ไม่ปัดเศษ
			*/
			/*__คำนวณแบบคิดเศษทศนิยม____________*/
			$paypermonth = round(($pay05_investment * ((($pay05_interest*($pay05_month/12))+100)/100)*(1/$pay05_month)),2); //ยอดผ่อนต่อเดือน
			$paypermonthsumvat = round(($paypermonth * (($pay05_vatcal+100)/100)),2); //ยอดผ่อนต่อเดือน (รวมภาษีมูลค่าเพิ่ม) 	
		
			echo "$paypermonthsumvat";	
		}
	}
?>

