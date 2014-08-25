<?PHP
session_start();
include("../../config/config.php");

$user_id = $_SESSION["av_iduser"];
$pay = $_POST['pay']; // ประเภทการค้นหา

//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) คำนวณยอดผ่อน',LOCALTIMESTAMP(0))");
//ACTIONLOG---

if($pay == 'pay03'){ //(เงินกู้) คำนวณยอดเช็คค้ำแฟคตอริ่ง
	$ticketmoney = $_POST['ticketmoney']; //จำนวนเงินบนตั๋ว 
	$realmoney = $_POST['realmoney']; //จำนวนเงินที่ลูกค้ารับจริง 
	$interestrate = $_POST['interestrate']; //อัตราดอกเบี้ย 
	$datestart = $_POST['datestart']; //วันที่เริ่มตั๋ว 
	$dateend = $_POST['dateend']; //วันที่สิ้นสุดตั๋ว 
	
	$sql = pg_query("SELECT \"thcap_cal_factoringRepayChqAmt\"('$ticketmoney','$interestrate','$datestart','$dateend','$realmoney')");
	$result = pg_fetch_array($sql);
	list($sum)=$result;
	$moneyshow = number_format($sum,2);
	$ticketmoney = number_format($ticketmoney,2);
	$realmoney = number_format($realmoney,2);

	echo " เงินต้น : $ticketmoney บาท  \n เงินต้นที่ลูกค้าได้รับจริง : $realmoney บาท \n อัตราดอกเบี้ย : $interestrate  %  \n วันที่เริ่มคำนวณ : $datestart \n วันที่สิ้นสุดการคำนวณ : $dateend \n\n ยอดเช็คที่ได้ : $moneyshow บาท \n\n ";
}
	
?>

