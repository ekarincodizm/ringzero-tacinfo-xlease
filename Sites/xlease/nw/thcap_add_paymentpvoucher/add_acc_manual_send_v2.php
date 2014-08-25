<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$user_id = $_SESSION["av_iduser"];

$add_date = nowDateTime();//วันเวลาปัจจุบันจาก server
$date_transaction = nowDate();//วันที่ทำรายการ
$now_time = nowTime(); //เวลาปัจจุบันจาก server

$date_add = pg_escape_string($_POST['datepicker']);//วันที่ทำรายการ : ที่  user คีย์ข้อมูล
$text_add = pg_escape_string($_POST['text_add']);
$text_add =checknull($text_add);

$datevat = pg_escape_string($_POST['datevat']);//วันที่รายการภาษีมูลค่าเพิ่ม
$datevat =checknull($datevat);

$chk_to= pg_escape_string($_POST['to']);//0=บุคคลภายนอก,1=ลูกค้าบุคคล,2=ลูกค้านิติบุคคล,3=พนักงานบริษัท 

$topayfullin= pg_escape_string($_POST['topayfullin']);//id
$topayfullin = checknull($topayfullin);

$topayfullout= pg_escape_string($_POST['topayfullout']);//name (บุคคลภายนอก) 
$topayfullout = checknull($topayfullout);


$voucherPurpose= pg_escape_string($_POST['voucherPurpose']);//จุดประสงค์

$chk_insert_channel= pg_escape_string($_POST['chk_insert_channel']); 
$rowaddFile = pg_escape_string($_POST["noaddFile"]);//จำนวนของข้อมูลใน บันทึกรายการ Channel มีกี่ รายการ

$contractid = pg_escape_string($_POST["contractid"]);//เลขที่สัญญากรณีที่เป็นการจ่ายเพื่อตั้งหนี้
$contractid = checknull($contractid);

pg_query("BEGIN WORK");
$status=0;
?>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>

<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<div style="float:left"><input type="button" value=" กลับ " onclick="javascript:window.location='frm_Index_v2.php';" class="ui-button"></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>

<fieldset><legend><B>ทำรายการใบสำคัญจ่าย</B></legend>

<div align="left">
<?php
// ========================================================================================
//ส่วน detail
// ========================================================================================
echo '<br><b> ส่วน detail </b><br>';
echo 'วันที่ทำรายการ (date_add):= '.$date_add.'<br>';
echo 'คำอธิบายรายการ  (text_add):= '.$text_add.'<br>';
echo 'radio จ่ายให้  (0=บุคคลภายนอก,1=ลูกค้าบุคคล,2=ลูกค้านิติบุคคล,3=พนักงานบริษัท)(chk_to):= '.$chk_to.'<br>';
echo 'ถ้าเลือก บุคคลภายนอก จ่ายให้  (topayfullout):= '.$topayfullout.'<br>';
echo 'ถ้าเลือก etc จ่ายให้  (topayfullin):= '.$topayfullin.'<br>';

echo 'จุดประสงค์  (voucherPurpose):= '.$voucherPurpose.'<br>';
echo 'เพื่อเป็นการตั้งหนี้สัญญา ($contractid) ถ้ามี :='.$contractid.'<br>';
echo '<br>';
echo '<br><b> ส่วน บัญชี</b><br>';

$precord_acc_type = '{}';
$precord_acc_book = '{}';
$precord_acc_amt = '{}';

for($i=0;$i<count($_POST["acid"]);$i++)
{
	$adds_serial  = pg_escape_string($_POST['acid'][$i]); 
	$adds_money   = pg_escape_string($_POST['text_money'][$i]);
	$abd_bookType = pg_escape_string($_POST['actype'][$i]);
	
	echo 'ลำดับที่ '.$i.'<br>';
	echo 'เลือกบัญชี  ($adds_serial)='.$adds_serial.'<br>';
	echo 'สถานะ ($adds_money)='.$adds_money.'<br>';
	echo 'ยอดเงิน ($abd_bookType)='.$abd_bookType.'<br><br>';
	
	$precord_acc_type = pg_fetch_result(pg_query("select array_append('$precord_acc_type'::varchar[], '$abd_bookType')"),0);
	$precord_acc_book = pg_fetch_result(pg_query("select array_append('$precord_acc_book'::varchar[], '$adds_serial')"),0);
	$precord_acc_amt = pg_fetch_result(pg_query("select array_append('$precord_acc_amt'::varchar[], '$adds_money')"),0);
}

// ========================================================================================
// ส่วน หนี้ที่ต้องการจะจ่าย
// ========================================================================================
echo '<br><b> ส่วน หนี้ที่ต้องจะจ่าย </b><br>';
$rowShare = pg_escape_string($_POST["rowShare"]); // จน.หนี้ที่ต้องการจะตั้ง
$txt_sumvat = pg_escape_string($_POST["txt_sumvat"]); 
$note = pg_escape_string($_POST["note"]); 
$note = checknull($note);

// กำหนดค่าเริ่มต้น
$precord_details = '{}';
$precord_netamt = '{}';
$precord_vatamt = '{}';
$precord_whtamt = '{}';
$precord_whtref = '{}';

for($i=1; $i<=$rowShare; $i++)
{
	$txt_namep_s[$i] = $_POST["txt_namep_s$i"]; 
	$txt_amountp_s[$i] = $_POST["txt_amountp_s$i"]; 
	$txt_vatp_s[$i] = $_POST["txt_vatp_s$i"];	
	
	$txt_amountwithhol_s[$i] = $_POST["txt_amountwithhol_s$i"]; 
	$txt_nowithhol_s[$i] = $_POST["txt_nowithhol_s$i"]; 
	$txt_vatp_s[$i] = $_POST["txt_vatp_s$i"];	
	$txt_vatp_s[$i] = $_POST["txt_vatp_s$i"];
	
	echo '<br>ลำดับที่ '.$i.'<br>';	
	echo 'ชื่อรายการสินค้าหรือบริการ  (txt_namep_s['.$i.'])='.$txt_namep_s[$i].'<br>';
	echo 'จำนวนเงินค่าสินค้าหรือบริการ  (txt_amountp_s['.$i.'])='.$txt_amountp_s[$i].'<br>';
	echo 'จำนวนภาษีมูลค่าเพิ่ม  (txt_vatp_s['.$i.'])='.$txt_vatp_s[$i].'<br>';

	echo 'จำนวนภาษีหัก ณ ที่จ่าย  (txt_amountwithhol_s['.$i.'])='.$txt_amountwithhol_s[$i].'<br>';
	echo 'เลขที่ใบภาษีหัก ณ ที่จ่าย  (txt_nowithhol_s['.$i.'])='.$txt_nowithhol_s[$i].'<br>';	

	// นำค่าที่ผู้ใช้งานกรอกเก็บเข้า array
	$precord_details = pg_fetch_result(pg_query("select array_append('$precord_details'::varchar[], '$txt_namep_s[$i]')"),0);
	$precord_netamt = pg_fetch_result(pg_query("select array_append('$precord_netamt'::varchar[], '$txt_amountp_s[$i]')"),0);
	$precord_vatamt = pg_fetch_result(pg_query("select array_append('$precord_vatamt'::varchar[], '$txt_vatp_s[$i]')"),0);
	$precord_whtamt = pg_fetch_result(pg_query("select array_append('$precord_whtamt'::varchar[], '$txt_amountwithhol_s[$i]')"),0);
	$precord_whtref = pg_fetch_result(pg_query("select array_append('$precord_whtref'::varchar[], '$txt_nowithhol_s[$i]')"),0);
}
echo '<br>';
echo 'รวมยอดภาษีมูลค่าเพิ่ม  (txt_sumvat):= '.$txt_sumvat.'<br>';
echo 'หมายเหตุ  (note):= '.$note.'<br>';
echo '<br>';

// ========================================================================================
//ส่วน ช่องทางการจ่าย
// ========================================================================================
echo '<br><b> ช่องทางการจ่าย</b><br>';
$rowaddFile = pg_escape_string($_POST["noaddFile"]);//จำนวนของข้อมูลใน บันทึกรายการ ช่องทางการจ่าย มีกี่ รายการ

// กำหนดค่าเริ่มต้น
$precord_channel_bid = '{}';
$precord_channel_bywhat = '{}';
$precord_channel_payerchqno_or_payeebankno = '{}';
$precord_channel_payeebankname = '{}';
$precord_channel_payamt = '{}';

for($e=0;$e<$rowaddFile;$e++)
{
	$fromChannel =$_POST['array_fromChannel'][$e];
	$proviso_return =$_POST['array_proviso_return'][$e];
	$returnTranToCus =$_POST['array_returnTranToCus'][$e];
	$returnTranToBank =$_POST['array_returnTranToBank'][$e];
	$returnTranToAccNo =$_POST['array_returnTranToAccNo'][$e];
	$returnChqCus =$_POST['array_returnChqCus'][$e];
	$returnChqNo =$_POST['array_returnChqNo'][$e];
	$returnChqDate =$_POST['array_returnChqDate'][$e];
	$payamt =$_POST['array_payamt'][$e];
	
	echo '<br>ลำดับที่ : '.$e.'<br>';		
	echo 'ช่องทางการจ่าย: ($fromChannel)='.$fromChannel.'<br>';
	echo 'คืนโดย(1-คืนโดยโอนธนาคาร,2-คืนโดยเช็ค): ($proviso_return)='.$proviso_return.'<br>';	
	echo 'เจ้าของบัญชี:  ($returnTranToCus)='.$returnTranToCus.'<br>';	
	echo 'รหัสธนาคาร: ($returnTranToBank)='.$returnTranToBank.'<br>';	
		
	echo 'เลขที่บัญชีปลายทาง : ($returnTranToAccNo)='.$returnTranToAccNo.'<br>';
	echo 'ออกเช็คให้: ($returnChqCus)='.$returnChqCus.'<br>';	
	echo 'เลขที่เช็ค:  ($returnChqNo)='.$returnChqNo.'<br>';	
	echo 'วันที่บนเช็ค:: ($returnChqDate)='.$returnChqDate.'<br>';
	echo 'จำนวนเงืนที่จ่าย:: ($payamt)='.$payamt.'<br>';
	
	// แปลงค่าเพื่อนำเข้า array สำหรับเป็น parameter ส่งเข้าฐานข้อมูล
	if ($proviso_return <> 1 and  $proviso_return <> 2 ) $proviso_return = '0'; // ถ้าไม่ใช่เงินโอน  และ เช็ค ปัจจุบันก็๋ต้องเป็นเงินสด (0)
	
	if ($proviso_return == 1) { // กรณีคืนเงินโดยเงินโอนธนาคาร
		$payerchqno_or_payeebankno = $returnTranToBank.'-'.$returnTranToAccNo;
		$payeebankname = $returnTranToCus;
	}
	if ($proviso_return == 2) { // กรณีคืนเงินโดยจ่ายเช็ค
		$payerchqno_or_payeebankno = $returnChqNo;
		$payeebankname = $returnChqCus;
	}
	
	// นำค่าที่ผู้ใช้งานกรอกเก็บเข้า array
	$precord_channel_bid = pg_fetch_result(pg_query("select array_append('$precord_channel_bid'::varchar[], '$fromChannel')"),0);
	$precord_channel_bywhat = pg_fetch_result(pg_query("select array_append('$precord_channel_bywhat'::varchar[], '$proviso_return')"),0);
	$precord_channel_payerchqno_or_payeebankno = pg_fetch_result(pg_query("select array_append('$precord_channel_payerchqno_or_payeebankno'::varchar[], '$payerchqno_or_payeebankno')"),0);
	$precord_channel_payeebankname = pg_fetch_result(pg_query("select array_append('$precord_channel_payeebankname'::varchar[], '$payeebankname')"),0);
	$precord_channel_payamt = pg_fetch_result(pg_query("select array_append('$precord_channel_payamt'::varchar[], '$payamt')"),0);
}

// ========================================================================================
// Query Process
// ========================================================================================

$status = 0;

// ---------------------------------------------------------------------------
// เตรียมทำรายการขอออก Payment Voucher สำหรับการรับเงินค่าอื่นๆ
// ---------------------------------------------------------------------------
// todo ยังไม่รองรับการจ่ายเพื่อตั้งหนี้
$qry_vprevoucherdetailsid_payment_text = "
	SELECT \"thcap_process_voucherCreate_payment\"(
		'$date_add'::date,--pvoucherdate (date) : วันทีทำรายการ
		$datevat::date,--pvatdate (date) : วันที่รายการใบกำกับภาษี (ถ้าไม่มี VAT จะเป็นค่าว่า / ถ้ามี VAT ชำระวันนี้วันที่จะตรงกันกับ voucherDate / ถ้ามี VAT แต่ชำระไปแล้ววันที่จะก่อนหน้า voucherDate)
		'$user_id'::varchar,--puserid (varchar) : รหัสผู้ทำรายการ - อนุมัติรายการ
		$topayfullin::varchar,--ppayid (varchar) : รหัาลูกค้าในระบบ
		'$chk_to'::smallint,--ppayidwhat (smallint) : ประเภทของข้อมูลลูกค้าที่ส่งมาจาก GUI
		$topayfullout::varchar,--ppayfull (varchar) : ชื่อลูกค้าเต็ม
		$voucherPurpose::integer,--pvoucherpurpose (integer) : วัตถุประสงค์ในการทำรายการ voucher
		$text_add::varchar,--premark (varchar) : หมายเหตุรายการ
		'$precord_details'::varchar[],--precord_details (character varying[]) : ชื่อรายการสินค้าหรือบริการ
		'$precord_netamt'::varchar[],--precord_netamt (character varying[]) : จำนวนเงินค่าสินค้าหรือบริการ
		'$precord_vatamt'::varchar[],--precord_vatamt (character varying[]) : จำนวนภาษีมูลค่าเพิ่ม
		'$precord_whtamt'::varchar[],--precord_whtamt (character varying[]) : จำนวนภาษีหัก ณ ที่จ่าย
		'$precord_whtref'::varchar[],--precord_whtref (character varying[]) : เลขที่อ้างอิงใบหัก ณ ที่จ่าย
		'$precord_acc_type'::varchar[],--precord_acc_type (character varying[]) : ประเภทของรายการที่จะบันทึกบัญชี 1-Debit 2-Credit
		'$precord_acc_book'::varchar[],--precord_acc_book (character varying[]) : accBookSerial รายการที่จะบันทึกบัญชี
		'$precord_acc_amt'::varchar[],--precord_acc_amt (character varying[]) : จำนวนเงินของรายการที่จะบันทุกบัญชี
		'$precord_channel_bid'::varchar[],--precord_channel_bid (character varying[]) : แหล่งที่คืนเงินออก เช่น จากเงินสดช่องทางไหน หรือบัญชีธนาคารใดๆ (BankInt.BID)
		'$precord_channel_bywhat'::varchar[],--precord_channel_bywhat (character varying[]) : ช่องทางการจ่ายออกตามที่ผู้ใช้เลือก 0-ไม่ใช่เงินโอนหรือเช็ค 1-เงินโอน 2-เช็ค
		'$precord_channel_payerchqno_or_payeebankno'::varchar[],--precord_channel_payerchqno_or_payeebankno (character varying[]) : เลขที่เช็คจ่าย หรือ เลขที่บัญชีธนาคารผู้รับโอนปลายทาง
		'$precord_channel_payeebankname'::varchar[],--precord_channel_payeebankname (character varying[]) : ธนาคารผู้รับโอนปลายทาง หรือ ผู้ที่เช็คสั่งจ่าย
		'$precord_channel_payamt'::varchar[],--precord_channel_payamt (character varying[]) : จำนวนเงินที่ได้สั่งจ่ายผ่านช่องทางนั้นๆ
		$note,--pdocumentref เลขอ้างอิงเอกสาร (ชั่วคราว)
		$contractid::varchar --pcontractid (varchar) [DEFAULT NULL] : เลขที่สัญญา หาก AP นี้เป็นการทำเพื่อตั้งลูกหนี้
	)
";
echo '<br>'.$qry_vprevoucherdetailsid_payment_text.'<br>';
$qry_vprevoucherdetailsid_payment = pg_query($qry_vprevoucherdetailsid_payment_text);
if($qry_vprevoucherdetailsid_payment){
	list($vprevoucherdetailsid_payment) = pg_fetch_array($qry_vprevoucherdetailsid_payment);
}else{
	$status++;
}

// COMMIT / ROLLBACK
if($status == 0){
	pg_query("COMMIT");
	echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
}else{
	pg_query("ROLLBACK");
	echo $ins_error."<br>";
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}
?>
</div>
</fieldset>
        </td>
    </tr>
</table>
</body>
</html>