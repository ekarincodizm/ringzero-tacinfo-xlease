<?PHP
session_start();
include("../../config/config.php");

$user_id = $_SESSION["av_iduser"];
$pay = $_POST['pay']; // ประเภทการค้นหา

//ACTIONLOG
	$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) คำนวณยอดผ่อน',LOCALTIMESTAMP(0))");
//ACTIONLOG---

if($pay == 'pay07'){ // (แฟคตอริ่ง) คำนวณยอดตั๋ว
	$pay07_datestart = $_POST["datestart"]; //วันที่รับเงิน
	$pay07_pay_cus = $_POST["pay_cus"]; //จำนวนเงินที่ให้ลูกค้ารวม (เงินต้น+เงินค้ำ)
	$pay07_factoring = $_POST["factoring"]; //จำนวนเงินค่า Factoring Fee :
	$pay07_dateend = $_POST["dateend"]; //วันที่ครบกำหนดตั๋ว
	$pay07_interest = $_POST["interest"]; //อัตราดอกเบี้ย
	$pay07_payin = $_POST["check"]; //จ่ายในยอดตั๋วหรือไม่
			
	$pay07_datestart_0=$pay07_datestart;
	list($year_0,$month_0,$day_0) = explode("-", $pay07_datestart_0); //แยกวันที่เดือนปีจากกัน (นำวันที่ไปใช้งานอย่างเดียว)
	$pay07_dateend_1=$pay07_dateend;
	list($year_1,$month_1,$day_1) = explode("-", $pay07_dateend_1); //แยกวันที่เดือนปีจากกัน (นำวันที่ไปใช้งานอย่างเดียว)
	$result_1 = mktime(0, 0, 0, $month_0, $day_0, $year_0); //นำวันเดือนปี 1 มาแปลงเป็นรูปแบบ Unix timestamp
	$result_2 = mktime(0, 0, 0, $month_1, $day_1, $year_1); //นำวันเดือนปี 2 มาแปลงเป็นรูปแบบ Unix timestamp
	$result_date = $result_2 - $result_1; //นำวันที่ 2 - วันที่ 1
	$result = $result_date / (60 * 60 * 24); //แปลงค่าเวลารูปแบบ Unix timestamp ให้เป็นจำนวนวัน
	
	echo "	<fieldset style=\"background-color:#FFFFFF;text-align:left;\">";
	echo "	<legend align=\"left\">รายละเีอียด</legend>";
	if($pay07_payin == 'payin'){ //หากเลือกการจ่ายในยอดตั๋ว 
	
		$cal_paycus_fac=$pay07_pay_cus + $pay07_factoring;
		$qr=pg_query("SELECT \"cal_interestTypeB\"($cal_paycus_fac,$pay07_interest,'$pay07_datestart','$pay07_dateend')");
		list($interest) = pg_fetch_array($qr);
		$cal_result=$cal_paycus_fac+$interest;
		echo "	<span><font color=#000000>วันที่รับเงิน :</font> $pay07_datestart<br></span>";
		echo "	<span><font color=#000000>จำนวนเงินที่ให้ลูกค้ารวม (เงินต้น+เงินค้ำ) :</font> ".number_format($pay07_pay_cus,2)." บาท<br></span>";
		echo "	<span><font color=#000000>จำนวนเงินค่า Factoring Fee :</font> ".number_format($pay07_factoring,2)." บาท<br></span>";
		echo "	<span><font color=#000000>วันที่ครบกำหนดตั๋ว :</font> $pay07_dateend<br></span>";
		echo "	<span><font color=#000000>อัตราดอกเบี้ย  :</font> $pay07_interest %<br></span>";
		echo	"<font color=\"blue\">___________________________________________________________</font><p>";
		echo "	<span><font color=#000000>จำนวนวันที่คิดดอกเบี้ย ตั้งแต่  $pay07_datestart ถึงวันที่  $pay07_dateend :</font> ".$result." วัน<br></span>";
		echo "	<span><font color=#000000>คำนวณดอกเบี้ยจากเงินต้น ".number_format($pay07_pay_cus,2)." + Factoring Fee ".number_format($pay07_factoring,2)." ในอัตรา $pay07_interest % <br>จะได้ดอกเบี้ยทั้งหมด : </font>".number_format($interest,2)." บาท";
		echo "	<span><font color=#000000><br><br><b>ยอดตั๋วสุทธิ : </b></font>".number_format($cal_result,2)." บาท</span>";
	}else{
		$cal_paycus_fac=$pay07_pay_cus;
		$qr=pg_query("SELECT \"cal_interestTypeB\"($cal_paycus_fac,$pay07_interest,'$pay07_datestart','$pay07_dateend')");
		list($interest) = pg_fetch_array($qr);
		$cal_result=$cal_paycus_fac+$interest;
		echo "	<span><font color=#000000>วันที่รับเงิน :</font> $pay07_datestart<br></span>";
		echo "	<span><font color=#000000>จำนวนเงินที่ให้ลูกค้ารวม (เงินต้น+เงินค้ำ) :</font> ".number_format($pay07_pay_cus,2)." บาท<br></span>";
		echo "	<span><font color=#000000>วันที่ครบกำหนดตั๋ว :</font> $pay07_dateend<br></span>";
		echo "	<span><font color=#000000>อัตราดอกเบี้ย  :</font> $pay07_interest %<br></span>";
		echo	"<font color=\"blue\">___________________________________________________________</font><p>";
		echo "	<span><font color=#000000>จำนวนวันที่คิดดอกเบี้ย ตั้งแต่  $pay07_datestart ถึงวันที่  $pay07_dateend :</font> ".$result." วัน<br></span>";
		echo "	<span><font color=#000000>คำนวณดอกเบี้ยจากเงินต้น ".number_format($pay07_pay_cus,2)." + Factoring Fee 0.00 ในอัตรา $pay07_interest % <br>จะได้ดอกเบี้ยทั้งหมด : </font>".number_format($interest,2)." บาท";
		echo "	<span><font color=#000000><br><br><b>ยอดตั๋วสุทธิ : </b></font>".number_format($cal_result,2)," บาท</span>";
	}
	echo "	</fieldset>";
}
	

