<?php
session_start();
include("../../../config/config.php");
require_once("../../../settings.php");
include("../../function/currency_totext.php"); //function แปลงจำนวนเงินเป็นตัวหนังสือ

$id_user = $_SESSION["av_iduser"];
$contractID = pg_escape_string($_GET['contractID']); //เลขที่สัญญา


$nowdate=nowDateTime();
$nowdate_1=nowDate();

$status = 0;

//---------- ตรวรสอบข้อมูล
	// ตรวจสอบว่ามีรายการที่ยังไม่เสร็จสิ้นกระบวนการหรือไม่
	$qry_chk_NT = pg_query("select \"contractID\" from \"thcap_history_nt\" where \"contractID\" = '$contractID' and \"NT_status\" = '1' ");
	$row_chk_NT = pg_num_rows($qry_chk_NT);
	if($row_chk_NT > 0)
	{
		$status++;
		echo iconv('UTF-8','windows-874',"ผิดพลาด : ไม่สามารถออก NT ซ้ำได้");
		exit;
	}

	// กำหนด NT_times
	$qry_chk_have_contract = pg_query("select max(\"NT_times\") from \"thcap_history_nt\" where \"contractID\" = '$contractID' ");
	$max_NT_times = pg_fetch_result($qry_chk_have_contract,0);
	if($max_NT_times == "") //ถ้ายังไม่มีจ้อมูลในตาราง thcap_history_nt
	{
		$next_NT_times = '1';
	}
	else // ถ้ามีข้อมูลในตาราง thcap_history_nt แล้ว
	{
		$next_NT_times = $max_NT_times + 1;
	}
//---------- จบการตรวรสอบข้อมูล

pg_query("BEGIN WORK");

//วันที่ภาษาไทย
$qrydatethai=pg_query("select get_date_thai_format('$nowdate')");
list($nowdatethai)=pg_fetch_array($qrydatethai);

//หาชื่อ ผู้เช่า
$qry_namemain=pg_query("select \"thcap_fullname\"  from \"vthcap_ContactCus_detail\"
where \"contractID\" = '$contractID' and \"CusState\" ='0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);	
}

//หาชื่อ ผู้เช่าร่วม
$qry_join1=pg_query("select \"thcap_fullname\"  from \"vthcap_ContactCus_detail\"
where \"contractID\" = '$contractID' and \"CusState\" = '1'");

//หาผู้ค้ำประกัน
$qry_name1=pg_query("select \"thcap_fullname\"  from \"vthcap_ContactCus_detail\"
where \"contractID\" = '$contractID' and \"CusState\" = '2'");

//งวดถัดไป
$qrynextDueDate=pg_query("SELECT \"thcap_nextDueDate\"('$contractID','$nowdate_1')");
if($rs_nextDueDate = pg_fetch_array($qrynextDueDate)){
	list($nextDueDate) = $rs_nextDueDate;
}else{
		$status++;
}

//ข้อมูลสัญญา
	$qrycon=pg_query("select \"conDate\",\"conLoanIniRate\" from thcap_contract where \"contractID\"='$contractID'");
	if($rescon=pg_fetch_array($qrycon)){
		$conDate=$rescon["conDate"];
		$conLoanIniRate=$rescon["conLoanIniRate"];
	}
$qryID_NT=pg_query("SELECT \"thcap_gen_documentID\"('$contractID','$nowdate_1','6')");
list($ID_NT)=pg_fetch_array($qryID_NT);
//หา typePayID ของค่าทนาย
	$qrytype=pg_query("select \"tpID\" from account.\"thcap_typePay\"
	where \"tpID\"=substring(account.\"thcap_mg_getMinPayType\"('$contractID'),1,1)||'004'");
	list($tpID)=pg_fetch_array($qrytype);
			
	if($tpID == "")
	{ // ถ้าไม่พบ typePayID ของค่าทนาย ของประเภทสัญญาดังกล่าว
		$status++;
		$error = "ไม่พบรหัสค่าใช้จ่ายของค่าทนาย";
	}
	
	//ตั้งหนี้หนังสือเตือนอัตโนมัติ
	$qrysetdebt=pg_query("SELECT thcap_process_setdebtloan('$contractID','$tpID','$ID_NT','$nowdate','1500',null,'000','0')");
	list($setdebt)=pg_fetch_array($qrysetdebt);
	if($setdebt!='t'){
		$status++;
	}

// ผู้เช่าซื้อร่วม
$Cus_join="";
$numjoin1=pg_num_rows($qry_join1);
while($resJoin=pg_fetch_array($qry_join1)){	
	$join1=trim($resJoin["thcap_fullname"]);
	if($Cus_join==""){$Cus_join=$join1;}
	else{$Cus_join.=','.$join1;}
}

//ผู้ค้ำ
$guarantee="";
$numco1=pg_num_rows($qry_name1);
while($resGua=pg_fetch_array($qry_name1)){	
	$name1=trim($resGua["thcap_fullname"]);
	if($guarantee==""){$guarantee=$name1;}
	else{$guarantee.=','.$name1;}
}

$qrydatethai_conDate=pg_query("select get_date_thai_format('$conDate')");
list($nowdatethai_conDate)=pg_fetch_array($qrydatethai_conDate);

$count_c=0;
$qryPay=pg_query("SELECT  \"typePayRefValue\",\"debtDueDate\",\"typePayAmt\"
        FROM
            vthcap_otherpay_debt_current
        WHERE
            \"debtStatus\" = 1 AND
            \"debtIsOther\" !='1' AND           
			\"debtDueDate\" < '$nextDueDate' AND -- น้อยกว่า วันครบกำหนดชำระถัดไป ถัดไป
            \"contractID\" = '$contractID' -- ของสัญญานั้นๆ");
$numPay=pg_num_rows($qryPay);
$sum_typePayAmt=0;

while($resPay=pg_fetch_array($qryPay)){
	$typePayRefValue = $resPay['typePayRefValue'];
	$debtDueDate = $resPay['debtDueDate'];
	$typePayAmt = $resPay['typePayAmt'];	
	$sum_typePayAmt +=$typePayAmt;
	$count_c++;
	if($count_c==1){
		//วันที่ภาษาไทย
		$qrydebtDueDate=pg_query("select get_date_thai_format('$debtDueDate')");
		list($s_debtDueDate)=pg_fetch_array($qrydebtDueDate);
		$arrayfirst_unpaid=$typePayRefValue.",".$debtDueDate;
	}
	else if($count_c==$numPay){
		//วันที่ภาษาไทย
		$qrydebtDueDate=pg_query("select get_date_thai_format('$debtDueDate')");
		list($s_debtDueDate)=pg_fetch_array($qrydebtDueDate);
		$arrayend_unpaid=$typePayRefValue.",".$debtDueDate;
	}
}
$pay_amt=$sum_typePayAmt;
$arrayunpaid_detailall="";
$arrayunpaid_detailall="{"." ค่างวด".",".$sum_typePayAmt."}";

//ค่าอื่น ๆ
$qrydetail=pg_query("SELECT \"typePayID\" , SUM(\"typePayAmt\") as \"typePayAmt\"
        FROM
            thcap_v_otherpay_debt_realother_current --ค่าที่ไม่รวม ค่างวด
        WHERE
            \"debtStatus\" = 1 AND
			((\"debtDueDate\" IS NULL) OR ((\"debtDueDate\" IS NOT NULL) AND (\"debtDueDate\" <= '$nowdate_1'))) AND
            \"contractID\" = '$contractID' AND -- ของสัญญานั้นๆ
			\"typePayID\" !='$tpID'
            GROUP BY \"typePayID\"");
$numPaydetail=pg_num_rows($qrydetail);
if($numPaydetail>0){
	
	while($resPaydetail=pg_fetch_array($qrydetail)){
		$typePayID = $resPaydetail['typePayID'];
		$typePayAmt = $resPaydetail['typePayAmt'];
		$sum_typePayAmt=$sum_typePayAmt+$typePayAmt ;
		//หาชื่อหนี้	
		$qrynametype=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typePayID'");
		list($tpDesc)=pg_fetch_array($qrynametype);
		if($arrayunpaid_detailall==""){$arrayunpaid_detailall="{".$tpDesc.",".$typePayAmt."}";}
		else{$arrayunpaid_detailall.=","."{".$tpDesc.",".$typePayAmt."}";	}
	}
}

//ค่าทนาย
$qrydetail_advocacy=pg_query("SELECT \"typePayID\" , SUM(\"typePayAmt\") as \"typePayAmt\"
        FROM
            thcap_v_otherpay_debt_realother_current --ค่าที่ไม่รวม ค่างวด
        WHERE
            \"debtStatus\" = 1 AND
            \"contractID\" = '$contractID' AND -- ของสัญญานั้นๆ
			\"typePayID\" ='$tpID' AND 
			\"typePayRefValue\"='$ID_NT'
            GROUP BY \"typePayID\"");
$numPay_advocacy=pg_num_rows($qrydetail_advocacy);			
if($numPay_advocacy==1){
	while($resPaydetail=pg_fetch_array($qrydetail_advocacy)){
		$typePayID = $resPaydetail['typePayID'];
		$typePayAmt = $resPaydetail['typePayAmt'];
		$sum_typePayAmt=$sum_typePayAmt+$typePayAmt ;
		//หาชื่อหนี้	
		$qrynametype=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$typePayID'");
		list($tpDesc)=pg_fetch_array($qrynametype);
		$arrayunpaid_detailall.=","."{".$tpDesc.",".$typePayAmt."}";
	}
}
else{
	$status++;
}

//เบี้ยปรับ  45 วันจากปัจจุบัน
$daylease_fine=date('Y-m-d',strtotime("+45 day"));
$qr_get_lease_fine=pg_query("select \"thcap_get_lease_fine\"('$contractID','$daylease_fine')");
if($rs_get_lease_fine = pg_fetch_array($qr_get_lease_fine)){	
	list($lease_fine) = $rs_get_lease_fine;
	
	if($lease_fine != "" && $lease_fine > 0)
	{
		$arrayunpaid_detailall.=","."{"."ค่าเบี้ยปรับล่าช้า".",".$lease_fine."}";
		$sum_typePayAmt += $lease_fine;
	}
}else{
	$status++;
}

$unpaid_detailall_amt=$sum_typePayAmt;

//วันที่จะครบกำหนดชำระถัดไป
$qrynextDueDate=pg_query("SELECT \"thcap_nextDueDate\"('$contractID','$nextDueDate')");
list($nextDueDate)=pg_fetch_array($qrynextDueDate);

$qrydatethai_conDatenext=pg_query("select get_date_thai_format('$nextDueDate')");
list($nowdatethai_conDatenext)=pg_fetch_array($qrydatethai_conDatenext);

$qryPay_t=pg_query("SELECT  \"typePayRefValue\",\"typePayAmt\"
        FROM
            vthcap_otherpay_debt_current
        WHERE
            \"debtStatus\" = 1 AND
            \"debtIsOther\" !='1' AND           
			\"debtDueDate\" = '$nextDueDate' AND 
            \"contractID\" = '$contractID' -- ของสัญญานั้นๆ");
$numPaydetail=pg_num_rows($qryPay_t);
if($numPaydetail>0){	
	$resPaydetail=pg_fetch_array($qryPay_t);
	$typePayRefValue = $resPaydetail['typePayRefValue'];
	$nextDueAmt = $resPaydetail['typePayAmt'];
	$arraypay_next=$typePayRefValue.",".$nextDueDate;
}
$sum_typePayAmt +=$nextDueAmt;

//บันทึกข้อมูล
$ins="INSERT INTO \"thcap_history_nt\"(
	\"NT_ID\", \"contractID\", \"NT_Date\", \"NT_number\", \"NT_docversion\", \"NT_isprint\",\"NT_doerid\", \"NT_times\")
	VALUES ('$ID_NT','$contractID','$nowdate','1','1','0','$id_user','$next_NT_times')";
if($resin=pg_query($ins)){
	$ntid = pg_fetch_result($resin,0); // NT
}else{
	$status++;
}

//บันทึกข้อมูลใน pdf
$arrayfirst_unpaid="{".$arrayfirst_unpaid."}";
$arrayend_unpaid="{".$arrayend_unpaid."}";
$arrayunpaid_detailall="{".$arrayunpaid_detailall."}";
$arraypay_next="{".$arraypay_next."}";
$ins="INSERT INTO thcap_pdf_nt( \"NT_ID\", \"contractID\", \"NT_Date\", \"Cus_main\", \"Guarantee\", \"Cus_join\",
        \"arrayfirst_unpaid\", \"arrayend_unpaid\", \"pay_amt\", \"arrayunpaid_detailall\", 
        \"unpaid_detailall_amt\",\"interest\",\"arraypay_next\",\"amountpay_next\",\"amountpay_all\")
		
	VALUES ('$ID_NT','$contractID','$nowdate','$name3','$guarantee','$Cus_join',
		'$arrayfirst_unpaid','$arrayend_unpaid','$pay_amt','$arrayunpaid_detailall',
		'$unpaid_detailall_amt','$conLoanIniRate','$arraypay_next','$nextDueAmt','$sum_typePayAmt')";
	if($resin=pg_query($ins)){
		$ntid = pg_fetch_result($resin,0); // NT
	}else{
		$status++;
}

if($status==0){ 
	pg_query("COMMIT");
	echo "<script>window.location='pdf_reprint_nt1_HP.php?NT_ID=$ID_NT';</script>";
}
else{ pg_query("ROLLBACK");}

?>