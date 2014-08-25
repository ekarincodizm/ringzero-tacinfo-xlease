<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");

pg_query("BEGIN WORK");
$status=0;

$user_id = $_SESSION["av_iduser"];

$add_date = nowDateTime();//วันเวลาปัจจุบันจาก server
$date_transaction = nowDate();//วันที่ทำรายการ
$now_time = nowTime(); //เวลาปัจจุบันจาก server

$date_add = pg_escape_string($_POST['datepicker']);//วันที่ทำรายการ : ที่  user คีย์ข้อมูล
$text_add = pg_escape_string($_POST['text_add']);
$text_add =checknull($text_add);

$datevat = pg_escape_string($_POST['datevat']);//วันที่รายการภาษีมูลค่าเพิ่ม
$datevat =checknull($datevat);


$fullname_main= pg_escape_string($_POST['fullname_main']);
$fullname_main = checknull($fullname_main);

$cusid_main= pg_escape_string($_POST['cusid_main']);//id
$cusid_main = checknull($cusid_main);
$voucherPurpose= pg_escape_string($_POST['voucherPurpose']);

$chk_insert_channel= pg_escape_string($_POST['chk_insert_channel']); 
$rowaddFile = pg_escape_string($_POST["noaddFile"]);//จำนวนของข้อมูลใน บันทึกรายการ Channel มีกี่ รายการ

$contractid = pg_escape_string($_POST["contractid"]);//เลขที่สัญญากรณีที่เป็นการจ่ายเพื่อตั้งหนี้
$contractid = checknull($contractid);

$sendfrom_noconid = pg_escape_string($_POST['sendfrom_noconid']);//ถ้า เป็นการที่ไม่มีเลขที่สัญญา ในระบบ เป็น 1

?>
<script type="text/javascript">
window.onbeforeunload = WindowCloseHanlder;
function WindowCloseHanlder()
{    
     opener.location.reload(true);
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function RefreshMe(){	
    opener.location.reload(true);
    self.close();
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
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
<div style="clear:both;"></div>
<div align="center">
<?php


// ========================================================================================
//ส่วน ช่องทางการจ่าย
// ========================================================================================
$rowaddFile = pg_escape_string($_POST["noaddFile"]);//จำนวนของข้อมูลใน บันทึกรายการ ช่องทางการจ่าย มีกี่ รายการ
// กำหนดค่าเริ่มต้น
$precord_channel_bid = '{}';
$precord_channel_bywhat = '{}';
$precord_channel_payerchqno_or_payeebankno = '{}';
$precord_channel_payeebankname = '{}';
$precord_channel_payamt = '{}';
$e=0;
$byChannel =$_POST['array_fromChannel'][$e];
$proviso_return =$_POST['array_proviso_return'][$e];
$temp_returnTranToCus =$_POST['array_returnTranToCus'][$e];
$temp_returnTranToBank  =$_POST['array_returnTranToBank'][$e];
$returnTranToAccNo =$_POST['array_returnTranToAccNo'][$e];
$temp_returnChqCus =$_POST['array_returnChqCus'][$e];
$returnChqNo =$_POST['array_returnChqNo'][$e];
$returnChqDate =$_POST['array_returnChqDate'][$e];
$payamt =$_POST['array_payamt'][$e];
list($returnTranToCus,$returnTranToCusName)=explode('#',$temp_returnTranToCus); //เจ้าของบัญชี
list($returnTranToBank,$returnTranToBankName)=explode('#',$temp_returnTranToBank); //รหัสธนาคาร
list($returnChqCus,$returnChqCusName)=explode('#',$temp_returnChqCus); //ออกเช็คให้

//แทนค่า	
	if($byChannel==""){
		$byChannel="null";
		$returnChqNo="null";
		$returnChqDate="null";
	}else{
		$byChannel=checknull($byChannel);
		if($proviso_return==1){ //คืนโดยโอนธนาคาร
			$returnChqNo="null"; //ที่ไม่เรียกใช้ function checknull เนื่องจากบางครั้งค่าอาจค้างอยู่ทำให้มีค่า
			$returnChqDate="null";
			$returnChqCus="null";
			$returnTranToCus=checknull($returnTranToCus);
			$returnTranToBank=checknull($returnTranToBank);
			$returnTranToAccNo=checknull($returnTranToAccNo);
		}else{ //คืนโดยเช็ค
			$returnChqNo=checknull($returnChqNo);
			$returnChqDate=checknull($returnChqDate);
			$returnTranToCus="null";
			$returnTranToBank="null";
			$returnTranToAccNo="null";
			$returnChqCus=checknull($returnChqCus);
		}
	}
	
	// ถ้าทำรายการวันนี้ เวลาเป็นเวลาปัจจุบัน ถ้าทำรายการย้อนหลัง เวลาเป็น ณ สิ้นวัน
	if($date_add==$date_transaction){
		$vtime=$now_time;
	}
	else{
		$vtime='23:59:59';
	}
	
	//**อาจมีการระบุ จุดประสงค์ ตามเงื่อนไข**	
	$voucherPurpose=$voucherPurpose;
	
	
	//**อาจต้องมีการระบุ**
	$fromChannelDetails=$fromChannelDetails;
	$fromChannelDetails = checknull($fromChannelDetails);
	
	
		if($sendfrom_noconid =='0'){	
			//ตรวจสอบสอบว่าที่ ผลรวมจากการชำระทั้งหมด + ยอดที่ทำรายการนี้  > ยอดลงทุน หรือไม่
			//หาผลรวมที่ได้ทำการ อนุมัติ ทั้งหมด
			$res_ChannelAmt=0;			
			$qry_paid = pg_query("SELECT \"thcap_get_all_payment_paid_for_contract\"($contractid)");
			list($res_ChannelAmt) = pg_fetch_array($qry_paid);
			
		
		
			// หาจำนวนเงินลงทุน โดยใส่ parameter type = 3 (เงินลงทุนรวมภาษีมูลค่าเพิ่ม (ถ้ามี) ก่อนหักเงินดาวน์ (ถ้ามี))
			$qry = pg_query("SELECT \"thcap_get_iniinvestmentamt\"($contractid,'3')");
			list($res_amount) = pg_fetch_array($qry);
			
			//ผลรวมจากการชำระทั้งหมด+ยอดที่ทำรายการนี้
			$sum_amt=$res_ChannelAmt+$payamt;
		}
		if((($sum_amt <=$res_amount) and ($sendfrom_noconid =='0')) or ($sendfrom_noconid =='1')){
		$ins_detail="INSERT INTO  \"thcap_temp_payment_debtor_creditors_cost\"(
			\"contractID\",
			\"voucherDate\" ,
			\"voucherTime\" ,
			\"doerID\" ,
			\"doerStamp\" ,
			\"cusid\",
			\"fullname\" ,
			\"voucherRemark\" ,
			\"fromChannelDetails\" ,
			\"appvStatus\" ,
			\"voucherPurpose\",
			\"byChannel\" ,
			\"returnChqNo\" ,
			\"returnChqDate\" ,
			\"returnTranToCus\" ,
			\"returnTranToBank\" ,
			\"returnTranToAccNo\",
			\"returnChqCus\" ,
			\"ChannelAmt\"
		) VALUES (
			$contractid,
			'$date_add',
			'$vtime',
			'$user_id',
			'$add_date', 
			$cusid_main, 
			$fullname_main,
			$text_add, 
			'$fromChannelDetails',			
			'9',
			'$voucherPurpose',
			$byChannel,
			$returnChqNo,
			$returnChqDate,
			$returnTranToCus,
			$returnTranToBank,
			$returnTranToAccNo,
			$returnChqCus,
			'$payamt');";	
	
		if($resins_detail=pg_query($ins_detail)){
		}else{
			$status ++;
			$ins_error="การบันทึกผืดพลาด ";
		}	
	}else{
		$status ++;	
		$ins_error="ผลรวมจากการชำระทั้งหมด + ยอดที่ทำรายการนี้  > ยอดลงทุน ";
	}
if($status == 0){
	pg_query("COMMIT");
	echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";	
}else{
	pg_query("ROLLBACK");
	echo $ins_error."<br>";
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";	
}
?>

        </td>
    </tr>
</table>
</body>
</html>