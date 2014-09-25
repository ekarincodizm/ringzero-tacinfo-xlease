<?php
include('../../config/config.php');
require('../Cal_Installments/function/cal_tools.php');
$ShowfromReal = pg_escape_string($_GET["ShowfromReal"]); // หากมีค่าเป็น 't' คือให้แสดงข้อมูลจากตารางที่ใช้ข้อมูลจริง แต่หากเป็น null หรือ ไม่มีค่า หรือเป็นอื่นใดที่ไม่ใช่ 't' เท่ากับให้แสดงข้อมูลจากตารางรออนุมัติหรือตาราง temp
$menu= pg_escape_string($_GET["namemenu"]); //ใช้ ตรวจสอบว่า ถูกเรียกใช้จากเมนูไหน
$iduser = $_SESSION["av_iduser"];
IF($ShowfromReal == 't'){	
	$contract = pg_escape_string($_GET["contract"]);
}else{
	$contractAutoID = pg_escape_string($_GET["contractAutoID"]);
	$StampAppv = pg_escape_string($_GET['StampAppv']); // วันเวลาที่ทำรายการอนุมัติ  มาจาก table_waitapp.php
}
$AppvStatus = pg_escape_string($_GET['AppvStatus']); // สถานะอนุมัติ 1 อนุมัติ 0 ไม่อนุมัติ  มาจาก table_waitapp.php
$lookonly = pg_escape_string($_GET['lonly']);
$readonlyna = pg_escape_string($_GET['readonly']);
//หา Path ให้ไปเริ่มที่ root
$realpath = redirect($_SERVER['PHP_SELF'],'');

	// หากมีค่าเป็น 't' คือให้แสดงข้อมูลจากตารางที่ใช้ข้อมูลจริง แต่หากเป็น null หรือ ไม่มีค่า หรือเป็นอื่นใดที่ไม่ใช่ 't' เท่ากับให้แสดงข้อมูลจากตารางรออนุมัติหรือตาราง temp
	IF($ShowfromReal == 't'){
		$numchk = 1;
	}else{
		//ตรวจสอบเบื้องต้นว่ารายการนี้อนุมัติหรือยังเพื่อป้องกันการอนุมัติซ้ำ
		$querychk = pg_query("select * from public.\"thcap_contract_temp\" where \"Approved\" is null and \"editNumber\" = '0' and \"autoID\" = '$contractAutoID'");
		$numchk = pg_num_rows($querychk);
	}	
if($numchk<=0 && $lookonly != "true"){ //แสดงว่าอนุมัติแล้ว
	echo "<div style=\"text-align:center;padding:20px;\"><h1>รายการนี้ทำรายการอนุมัติไปแล้ว กรุณาตรวจสอบอีกครั้ง !!</h1>";
	echo "<input type=\"button\" value=\" ตกลง \"  onclick=\"javascript:opener.location.reload(true);self.close();\"></div>";
}
else
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<?php if($menu =="check") {?>
	<title>ตรวจสอบ ผูกสัญญาเงินกู้ชั่วคราว</title>
<?php } else {?>
	<title>อนุมัติ ผูกสัญญาเงินกู้ชั่วคราว</title>
<?php }?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="<?php echo $realpath ?>jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="<?php echo $realpath ?>jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo $realpath ?>jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script>
function showTablePayTerm() // function สำหรับแสดงตารางผ่อนชำระ
{ 
	var showPayTerm = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร showPayTerm  
		url: "<?php echo $realpath; ?>nw/loans_temp/TablePayTerm.php", // ไฟล์แสดงตารางการผ่อนชำระ
		data:"contractID="+$("#showTablePayTerm").val()+"&StampAppv="+$("#StampAppv").val()+"&AppvStatus="+$("#AppvStatus").val()+"&doerStamp="+$("#doerStamp").val()+"&conMinPay="+$("#h_conMinPay").val()+"&conTerm="+$("#h_conTerm").val()+"&conFirstDue="+$("#h_conFirstDue").val()+"+&conRepeatDueDay="+$("#h_conRepeatDueDay").val(), // ส่งตัวแปรแบบ GET
		async: false  
	}).responseText;
	$("#showPayTerm").html(showPayTerm); // นำค่า showPayTerm มาแสดงใน div ที่ชื่อ showPayTerm
}

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>
<?php
 if($menu =="check") {
	echo "<center><h2>ตรวจสอบ ผูกสัญญาเงินกู้ชั่วคราว</h2></center>";
 } 
IF(($ShowfromReal != 't')&&($menu !="check")){	
	echo "<center><h2>อนุมัติ ผูกสัญญาเงินกู้ชั่วคราว</h2></center>";
}?>
<body>

<?php
// หากมีค่าเป็น 't' คือให้แสดงข้อมูลจากตารางที่ใช้ข้อมูลจริง แต่หากเป็น null หรือ ไม่มีค่า หรือเป็นอื่นใดที่ไม่ใช่ 't' เท่ากับให้แสดงข้อมูลจากตารางรออนุมัติหรือตาราง temp
IF($ShowfromReal == 't'){
	$query_main = pg_query("select a.*,b.\"conFineRate\",b.\"conInvoicePeriod\" from public.\"thcap_contract\" a left join \"thcap_mg_contract_current\" b on a.\"contractID\" = b.\"contractID\" where a.\"contractID\" = '$contract' and b.\"rev\" = '1' ");
}else{
	$query_main = pg_query("select * from public.\"thcap_contract_temp\" where \"autoID\" = '$contractAutoID' ");
}
while($result = pg_fetch_array($query_main))
{
	$contractID = $result["contractID"]; // เลขที่สัญญา
	$conType = $result["conType"]; // รหัสประเภทสินเชื่อ
	
	// ชื่อประเภทสินเชื่อแบบเต็ม
	$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$conType') ");
	$chk_con_type = pg_fetch_result($qry_chk_con_type,0);
	
	$conLoanAmt = $result["conFinanceAmount"]; // จำนวนเงินขายฝาก
	$conGuaranteeAmt = $result["conGuaranteeAmt"]; // จำนวนเงินค้ำประกัน
	if($conGuaranteeAmt!="")
	{
		$conGuaranteeAmt = number_format($conGuaranteeAmt,2,".",",")." บาท";
	}
	$conCredit = $result["conCredit"]; // วงเงินสินเชื่อ
	$doerUser = $result["doerUser"]; // ผู้ทำรายการ
	$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
	$doerStampp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
	$conCompany = $result["conCompany"]; // รหัสบริษัท
	$conLoanIniRate = $result["conLoanIniRate"]; // อัตราดอกเบี้ยที่ตกลงตอนแรก
	$conLoanMaxRate = $result["conLoanMaxRate"]; // อัตราดอกเบี้ยสูงสุด
	$conInvoicePeriod = $result["conInvoicePeriod"]; // จำนวนวันที่ให้ส่งใบแจ้งหนี้ก่อนครบกำหนด
	$conTerm = $result["conTerm"]; // ระยะเวลาผ่อนชำระคืนเงินกู้ (เดือน) ------------------------------- คำนวนตาราง 2
	$conMinPay = $result["conMinPay"]; // จำนวนเงินผ่อนขั้นต่ำต่อ Due -------------------------คำนวนตาราง 1
	$conExtRentMinPay = $result["conExtRentMinPay"]; // จำนวนเงินขั้นต่ำค่าเช่า (เฉพาะ creditType ประเภท 'GUARANTEED_INVESTMENT, JOINT_VENTURE')
	$conPenaltyRate = $result["conPenaltyRate"]; // ค่าติดตามทวงถามปัจจุบัน
	$conDate = $result["conDate"]; // วันที่ทำสัญญา
	$conStartDate = $result["conStartDate"]; // วันที่รับเงินที่ขอกู้
	$conEndDate = $result["conEndDate"]; // วันที่สิ้นสุดการกู้ที่ระบุไว้ในสัญญา
	$case_owners_id = $result["case_owners_id"]; // รหัสพนักงาน เจ้าของเคส
	
	// หาชื่อเจ้าของเคส
	$qry_case_owners_name = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$case_owners_id' ");
	$case_owners_name = pg_fetch_result($qry_case_owners_name,0);
	
	$conFinAmtExtVat = $result["conFinAmtExtVat"]; // ยอดจัด/ยอดลงทุน (ก่อนภาษี)
	
	$conFineRate = $result["conFineRate"]; // % เบี้ยปรับผิดนัด
	
	$conDownToDealer = $result["conDownToDealer"]; // ชำระเงินดาวน์ให้ผู้ขาย
	$conDownToFinance = $result["conDownToFinance"]; // ชำระเงินดาวน์ให้ไฟแนนซ์
	$conDownToFinanceVat = $result["conDownToFinanceVat"]; // VAT ของเงินดาวน์ ให้ไฟแนนซ์
	
	$conSubType_serial = $result["conSubType_serial"]; // ประเภทสัญญาย่อย
	
	$conFacFee = $result["conFacFee"]; // ค่าธรรมเนียมรวมในตั๋ว
	$connote = $result["conNote"];//หมายเหตุ
	if($conDownToDealer != "" || $conDownToFinance != "")
	{ // ถ้าชำระเงินดาวน์ให้ผู้ขายหรือไฟแนนซ์
		if($conDownToDealer != "" && $conDownToFinance != "" && $conDownToFinanceVat != "")
		{ // ถ้าชำระเงินดาวน์ให้ทั้งผู้ขายและไฟแนนซ์ แบบมี vat
			$conDownText = "ชำระให้ผู้ขาย ".number_format($conDownToDealer,2)." บาท และชำระให้ไฟแนนซ์ ก่อน VAT".number_format($conDownToFinance,2)." บาท ยอด VAT".number_format($conDownToFinanceVat,2)." บาท";
		}
		elseif($conDownToDealer != "" && $conDownToFinance != "")
		{ // ถ้าชำระเงินดาวน์ให้ทั้งผู้ขายและไฟแนนซ์ แบบไม่มี vat
			$conDownText = "ชำระให้ผู้ขาย ".number_format($conDownToDealer,2)." บาท และชำระให้ไฟแนนซ์ ".number_format($conDownToFinance,2)." บาท";
		}
		elseif($conDownToDealer != "")
		{ // ถ้าชำระเงินดาวน์ให้ผู้ขายเพียงอย่างเดียว
			$conDownText = "ชำระให้ผู้ขาย ".number_format($conDownToDealer,2)." บาท";
		}
		elseif($conDownToFinance != "" && $conDownToFinanceVat != "")
		{ // ถ้าชำระเงินดาวน์ให้ไฟแนนซ์เพียงอย่างเดียว แบบมี vat
			$conDownText = "ชำระให้ไฟแนนซ์ <b>เงินดาวน์ก่อน VAT</b> ".number_format($conDownToFinance,2)." บาท <b>VAT ของเงินดาวน์</b> ".number_format($conDownToFinanceVat,2)." บาท";
		}
		elseif($conDownToFinance != "")
		{ // ถ้าชำระเงินดาวน์ให้ไฟแนนซ์เพียงอย่างเดียว แบบไม่มี vat
			$conDownText = "ชำระให้ไฟแนนซ์ ".number_format($conDownToFinance,2)." บาท";
		}
	}
	else
	{ // ถ้าไม่ได้ชำระเงินดาวน์
		$conDownText = "";
	}
	
	$conFirstDue = $result["conFirstDue"]; // Due แรก  ------------------------------------------ คำนวนตาราง 2
	$conRepeatDueDay = $result["conRepeatDueDay"]; // Due วันที่ชำระของทุกๆเดือน เช่น 01 หรือ 28
	$conFreeDate = $result["conFreeDate"]; // วันที่พ้นกำหนดห้ามปิดบัญชีก่อนกำหนด (Default = กึ่งหนึ่งของระยะเวลาทั้งสัญญา)
	$conClosedDate = $result["conClosedDate"]; // วันที่ปิดบัญชีจริง
	$conClosedFee = $result["conClosedFee"]; // % ค่าปรับปิดบัญชีก่อนกำหนด คิดจากยอดกู้
	$conStatus = $result["conStatus"]; // NCB...
	$conFlow = $result["conFlow"]; // สถานะสัญญา / internal
	$rev = $result["rev"]; // เปลี่ยนแปลงสัญญาครั้งที่
	$conCreditRef = $result["conCreditRef"]; // สัญญากู้นี้ใช้วงเงินไหน วงเงินเท่าไหร่
	$CusIDarray = $result["CusIDarray"]; // ประเภทลูกค้า และ รหัสลูกค้า
	$addrTempID = $result["addrTempID"]; // รหัสที่อยู่ของตาราง thcap_addrContractID_temp
	$editNumber = $result["editNumber"]; // จำนวนครั้งที่แก้ไข
	$conResidualValue = $result["conResidualValue"]; // ค่าซาก (ก่อนภาษีมูลค่าเพิ่ม)
	
	$conResidualValueIncVat = $result["conResidualValueIncVat"]; // ค่าซากรวมภาษีมูลค่าเพิ่ม
	$conLeaseIsForceBuyResidue = $result["conLeaseIsForceBuyResidue"]; // บังคับซื้อซาก
	$conLeaseBaseFinanceForCal = $result["conLeaseBaseFinanceForCal"]; // ยอดจัดที่ใช้ในการคิดดอกเบี้ย
	
	if($conLoanAmt != ""){$conLoanAmtText = number_format($conLoanAmt,2)." บาท";}else{$conLoanAmtText = "";}
	if($conMinPay != ""){$conMinPayText = number_format($conMinPay,2)." บาท";}else{$conMinPayText = "";}
	if($conExtRentMinPay != ""){$conExtRentMinPayText = number_format($conExtRentMinPay,2)." บาท";}else{$conExtRentMinPayText = "";}
	if($conPenaltyRate != ""){$conPenaltyRateText = number_format($conPenaltyRate,2)." บาท";}else{$conPenaltyRateText = "";}
	if($conFinAmtExtVat != ""){$conFinAmtExtVatText = number_format($conFinAmtExtVat,2)." บาท";}else{$conFinAmtExtVatText = "";}
	if($conFineRate != ""){$conFineRateText = $conFineRate." %";}else{$conFineRateText = "";}
	if($conResidualValue != ""){$conResidualValueText = number_format($conResidualValue,2)." บาท";}else{$conResidualValueText = "";}
	
	if($conResidualValueIncVat != ""){$conResidualValueIncVatText = number_format($conResidualValueIncVat,2)." บาท";}else{$conResidualValueIncVatText = "";}
	if($conLeaseIsForceBuyResidue == "0"){$conLeaseIsForceBuyResidueText = "ไม่บังคับ";}elseif($conLeaseIsForceBuyResidue == "1"){$conLeaseIsForceBuyResidueText = "บังคับ";}else{$conLeaseIsForceBuyResidueText = "ไม่ระบุ";}
	if($conLeaseBaseFinanceForCal != ""){$conLeaseBaseFinanceForCalText = number_format($conLeaseBaseFinanceForCal,2)." บาท";}else{$conLeaseBaseFinanceForCalText = "";}
}

	$qr_half_date = pg_query("select '$conStartDate'::date + ceil((('$conEndDate'::date - '$conStartDate'::date)::numeric/2))::integer as half_day");
	if($qr_half_date)
	{
		$rs_half_date = pg_fetch_array($qr_half_date);
		$half_day = $rs_half_date['half_day'];
	}
// หาที่อยู่
	// หากมีค่าเป็น 't' คือให้แสดงข้อมูลจากตารางที่ใช้ข้อมูลจริง แต่หากเป็น null หรือ ไม่มีค่า หรือเป็นอื่นใดที่ไม่ใช่ 't' เท่ากับให้แสดงข้อมูลจากตารางรออนุมัติหรือตาราง temp
	IF($ShowfromReal == 't'){
		$query_addr = pg_query("select * from public.\"thcap_addrContractID\" where \"contractID\" = '$contract' ");
	}else{
		$query_addr = pg_query("select * from public.\"thcap_addrContractID_temp\" where \"tempID\" = '$addrTempID' ");
	}
while($result_addr = pg_fetch_array($query_addr))
{
	$addsType = $result_addr["addsType"]; // รหัสสถานะที่อยู่
	$A_NO = $result_addr["A_NO"];
	$A_SUBNO = $result_addr["A_SUBNO"];
	$A_BUILDING = $result_addr["A_BUILDING"];
	$A_ROOM = $result_addr["A_ROOM"];
	$A_FLOOR = $result_addr["A_FLOOR"];
	$A_VILLAGE = $result_addr["A_VILLAGE"];
	$A_SOI = $result_addr["A_SOI"];
	$A_RD = $result_addr["A_RD"];
	$A_TUM = $result_addr["A_TUM"];
	$A_AUM = $result_addr["A_AUM"];
	$A_PRO = $result_addr["A_PRO"];
	$A_POST = $result_addr["A_POST"];
}
$textAddr = "";
$textAddr .= "$A_NO";
if($A_SUBNO != "" && $A_SUBNO != "-" && $A_SUBNO != "--"){$textAddr .= " หมู่$A_SUBNO";}
if($A_BUILDING != "" && $A_BUILDING != "-" && $A_BUILDING != "--"){$textAddr .= " อาคาร/ที่ตั้ง $A_BUILDING";}
if($A_ROOM != "" && $A_ROOM != "-" && $A_ROOM != "--"){$textAddr .= " ห้อง$A_ROOM";}
if($A_FLOOR != "" && $A_FLOOR != "-" && $A_FLOOR != "--"){$textAddr .= " ชั้น$A_FLOOR";}
if($A_VILLAGE != "" && $A_VILLAGE != "-" && $A_VILLAGE != "--"){$textAddr .= " หมู่บ้าน$A_VILLAGE";}
if($A_SOI != "" && $A_SOI != "-" && $A_SOI != "--"){$textAddr .= " ซอย$A_SOI";}
if($A_RD != "" && $A_RD != "-" && $A_RD != "--"){$textAddr .= " ถนน$A_RD";}
if($A_PRO == 'กรุงเทพ' OR $A_PRO == 'กรุงเทพฯ' OR $A_PRO == 'กรุงเทพมหานคร' OR $A_PRO == 'กทม' OR $A_PRO == 'กทม.'){
	if($A_TUM != "" && $A_TUM != "-" && $A_TUM != "--"){$textAddr .= " แขวง$A_TUM";}
	if($A_AUM != "" && $A_AUM != "-" && $A_AUM != "--"){$textAddr .= " เขต$A_AUM";}
	if($A_PRO != "" && $A_PRO != "-" && $A_PRO != "--"){$textAddr .= "  $A_PRO";}
}else{
	if($A_TUM != "" && $A_TUM != "-" && $A_TUM != "--"){$textAddr .= " ตำบล$A_TUM";}
	if($A_AUM != "" && $A_AUM != "-" && $A_AUM != "--"){$textAddr .= " อำเภอ$A_AUM";}
	if($A_PRO != "" && $A_PRO != "-" && $A_PRO != "--"){$textAddr .= " จังหวัด$A_PRO";}
}	

if($A_POST != "" && $A_POST != "-" && $A_POST != "--" && $A_POST != "0"){$textAddr .= " $A_POST";}
// จบการหาที่อยู่
$count=0;//ใช้นับว่ามี ถูกต้อง เท่าไหร่
?>

<input type="hidden" id="h_conMinPay" value="<?php echo $conMinPay; ?>" />
<input type="hidden" id="h_conExtRentMinPay" value="<?php echo $conExtRentMinPay; ?>" />
<input type="hidden" id="h_conTerm" value="<?php echo $conTerm; ?>" />
<input type="hidden" id="h_conFirstDue" value="<?php echo $conFirstDue; ?>" />
<input type="hidden" id="h_conRepeatDueDay" value="<?php echo $conRepeatDueDay; ?>" /> <!-- จ่ายทุกๆวันที่ -->

<div style="width:900px; text-align:right;">
	<div style="display:inline-block; vertical-align:top; cursor:pointer;">
    	<img src="images/print.png" width="24" height="24" onclick="window.print();" />
    </div>
    <span style="line-height:25px; height:25px; font-size:13px; display:inline-block; vertical-align:top; cursor:pointer; font-weight:bold;" onclick="window.print();">พิมพ์หน้านี้</span>
</div>
<table width="900" border="0" cellspacing="3" cellpadding="3" style="margin-top:1px" align="center" bgcolor="#DDFFAA" id="tble">
<tr>
	<td width="25%"><br></td>
	<td width="45%"><br></td>
	<input type="hidden" name="valuechk" id="valuechk">
	
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>เลขที่สัญญา : </b></font></td>
	<td><?php echo $contractID; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>ประเภทสินเชื่อ : </b></font></td>
	<td id="con_type"><?php echo $conType; ?></td>
	<input type="hidden" id="chk_con_type" value="<?php echo $chk_con_type; ?>">
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php
	if($conSubType_serial != "")
	{
?>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
			<td align="right"><font color="#FF5555"><b>ประเภทสัญญาย่อย  : </b></font></td>
			<td>
				<?php
					// หารูปภาพ
					$qry_imgSubtype = pg_query("select * from \"thcap_contract_subtype\" where \"conSubType_serial\" = '$conSubType_serial' ");
					while($res_Subtype = pg_fetch_array($qry_imgSubtype))
					{
						$conSubType_name = $res_Subtype["conSubType_name"]; // ชื่อ
						$conSubType_iconpath = $res_Subtype["conSubType_iconpath"]; // path file
					}
					
					if($conSubType_iconpath != "")
					{
						if(file_exists("../upload/consubtype_icon/$conSubType_iconpath"))
						{ // ถ้ามีไฟล์นั้นอยู่จริง
							echo "<img src=\"../upload/consubtype_icon/$conSubType_iconpath\" width=\"180\" height=\35\" >";
						}
						else
						{ // ถ้าไม่พบไฟล์
							echo $conSubType_name;
						}
					}
					else
					{
						echo $conSubType_name;
					}
				?>
			</td>
			<?php	
				if($menu =="check") 
				{ 	
					$chk="chk".$count;
					$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
					echo "<td align=\"right\">$check</td>";
				}	
			?>			
		</tr>
<?php
	}
?>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
<td align="right"><font color="#FF5555"><b>บริษัท : </b></font></td>
	<td><?php echo $conCompany; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>

<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
<td align="right"><font color="#FF5555"><b>เจ้าของเคส : </b></font></td>
	<td><?php echo $case_owners_name; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>

<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>จำนวนเงินขายฝาก : </b></font></td>
	<td><?php echo $conLoanAmtText; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>

<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>ดอกเบี้ยที่ตกลงต่อปี : </b></font></td>
	<td><?php echo "$conLoanIniRate %"; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>จำนวนวันที่ให้ส่งใบแจ้งหนี้ก่อนครบกำหนด : </b></font></td>
	<td><?php if($conInvoicePeriod != ""){echo "$conInvoicePeriod วัน";} ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>จำนวนงวด : </b></font></td>
	<td><?php echo $conTerm; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>จำนวนเงินสินไถ่ : </b></font></td>
	<td><?php echo $conMinPayText; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
	</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>ค่าติดทางทวงถาม กรณีไม่จ่าย : </b></font></td>
	<td><?php echo $conPenaltyRateText; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>วันที่ทำสัญญา : </b></font></td>
	<td><?php echo $conDate; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b id="receive_label">วันที่รับเงิน : </b></font></td>
	<td><?php echo $conStartDate; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>วันสิ้นสุดสัญญากู้ : </b></font></td>
	<td><?php echo $conEndDate; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>วันที่ครบกำหนดชำระงวดแรก  : </b></font></td>
	<td><?php echo $conFirstDue; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>
<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>จ่ายทุกวันๆ  : </b></font></td>
	<td><?php echo $conRepeatDueDay; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>

<?php $count+=1;
if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>% เบี้ยปรับผิดนัด  : </b></font></td>
	<td><?php echo $conFineRateText; ?></td>
	<?php	
	if($menu =="check") 
	{ 	
		
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}	
	?>
</tr>

<tr>
	<td colspan="2"> <hr width="650"> </td>
</tr>
<?php $count+=1;
	if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right"><font color="#FF5555"><b>ผู้ขายฝาก : </b></font></td>
	<?php
		$haveCusMain = "no";
// หากมีค่าเป็น 't' คือให้แสดงข้อมูลจากตารางที่ใช้ข้อมูลจริง แต่หากเป็น null หรือ ไม่มีค่า หรือเป็นอื่นใดที่ไม่ใช่ 't' เท่ากับให้แสดงข้อมูลจากตารางรออนุมัติหรือตาราง temp
IF($ShowfromReal == 't'){	
		$qry_cusMain = pg_query("SELECT a.\"CusID\" AS \"cusMainID\",a.\"thcap_fullname\",a.\"CusState\" AS \"cusMainType\", a.\"contractID\",a.\"type\"
								FROM \"vthcap_ContactCus_detail\" a
								WHERE a.\"contractID\" = '$contract' AND a.\"CusState\" = '0'
								order by \"cusMainType\" ");
		while($res_cusMain = pg_fetch_array($qry_cusMain))
		{
				$haveCusMain = "yes";
				$cusMainID = $res_cusMain["cusMainID"];
				$cusMainName = $res_cusMain["thcap_fullname"];
				$cusType = $res_cusMain["type"]; // ประเภทลูกค้า
			
					if($cusType == 1)
					{
						echo "<td>$cusMainName <a onclick=\"javascript:popU('../manageCustomer/showdetail2.php?CusID=$cusMainID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
					}
					elseif($cusType == 2)
					{
						$qry_corp_regis = pg_query("select \"corp_regis\" from \"th_corp\" where \"corpID\" = '$cusMainID' ");
						$corp_regis = pg_fetch_result($qry_corp_regis,0);
						echo "<td>$cusMainName <a onclick=\"javascript:popU('../corporation/frm_viewcorp_detail.php?corp_regis=$corp_regis','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
					}
					if($menu =="check") 
						{ 	
							
							$chk="chk".$count;
							$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
							echo "<td align=\"right\">$check</td>";
						}	
					
						
		}

}else{	
		$qry_cusMain = pg_query("SELECT distinct ta_array_list(a.\"CusIDarray\") AS \"cusMainType\", a.\"contractID\"
								FROM thcap_contract_temp a
								WHERE a.\"autoID\" = '$contractAutoID'
								order by \"cusMainType\" ");
		while($res_cusMain = pg_fetch_array($qry_cusMain))
		{
			$cusMainType = $res_cusMain["cusMainType"]; // ประเภทลูกค้า
			if($cusMainType == "0") // ถ้าเป็นผู้กู้หลัก
			{
				$haveCusMain = "yes";
				$qry_cusMainID = pg_query("SELECT a.\"contractID\", ta_array_get(a.\"CusIDarray\", '0') AS \"cusMainID\"
											FROM thcap_contract_temp a
											WHERE a.\"autoID\" = '$contractAutoID' ");
				while($res_cusMainID = pg_fetch_array($qry_cusMainID))
				{
					$cusMainID = $res_cusMainID["cusMainID"];
					
					// หาชื่อ
					$qry_cusMainName = pg_query("select * from \"VSearchCusCorp\" where \"CusID\" = '$cusMainID' ");
					while($res_cusMainName = pg_fetch_array($qry_cusMainName))
					{
						$cusMainName = $res_cusMainName["full_name"];
						$cusType = $res_cusMainName["type"]; // ประเภทลูกค้า
					}
					
					if($cusType == 1)
					{
						echo "<td>$cusMainName <a onclick=\"javascript:popU('".$realpath."nw/manageCustomer/showdetail2.php?CusID=$cusMainID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
					}
					elseif($cusType == 2)
					{
						$qry_corp_regis = pg_query("select \"corp_regis\" from \"th_corp\" where \"corpID\" = '$cusMainID' ");
						$corp_regis = pg_fetch_result($qry_corp_regis,0);
						echo "<td>$cusMainName <a onclick=\"javascript:popU('".$realpath."nw/corporation/frm_viewcorp_detail.php?corp_regis=$corp_regis','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
						
					}
					if($menu =="check") 
						{ 	
							
							$chk="chk".$count;
							$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
							echo "<td align=\"right\">$check</td>";
						}	
				}
			}
		}
		
		if($haveCusMain == "no")
		{
		
			if($menu =="check") 
			{ 	
				
				$chk="chk".$count;
				$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
				echo "<td align=\"right\">$check</td>";
			}

		}
}//ปิด IF มาจาก ใส่รายละเอียด BH		
	?>
</tr>
	<?php // หา ผู้ขายฝากร่วม
		
// หากมีค่าเป็น 't' คือให้แสดงข้อมูลจากตารางที่ใช้ข้อมูลจริง แต่หากเป็น null หรือ ไม่มีค่า หรือเป็นอื่นใดที่ไม่ใช่ 't' เท่ากับให้แสดงข้อมูลจากตารางรออนุมัติหรือตาราง temp
IF($ShowfromReal == 't'){	
		$jo = 1;
		$qry_cusJoin = pg_query("SELECT a.\"CusID\" AS \"cusJoinID\",a.\"thcap_fullname\", a.\"contractID\",a.\"type\"
								FROM \"vthcap_ContactCus_detail\" a
								WHERE a.\"contractID\" = '$contract' AND a.\"CusState\" = '1'
								order by \"CusID\" ");
		$row_cusjoin = pg_num_rows($qry_cusJoin);	
		if($row_cusjoin > 0){	
			while($res_cusJoin = pg_fetch_array($qry_cusJoin))
			{
					$cusJoinID = $res_cusGuarantor["cusJoinID"];
					$cusJoinName = $res_cusJoin["thcap_fullname"];
					$cusType = $res_cusJoin["type"]; // ประเภทลูกค้า
						 $count+=1;
						if($menu =="check") 
						{
							if($count%2==0){
								echo "<tr>";
							}else { 
								echo "<tr bgcolor=\"#99FF99\" >";}
						}else{
							echo "<tr>";
						}

						echo "<td align=\"right\"><font color=\"#FF5555\"><b>ผู้ขายฝากร่วม คนที่ $jo : </b></font></td>";
						if($cusType == 1)
						{
							echo "<td>$cusJoinName <a onclick=\"javascript:popU('../manageCustomer/showdetail2.php?CusID=$cusJoinID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
								
						}
						elseif($cusType == 2)
						{
							$qry_corp_regis = pg_query("select \"corp_regis\" from \"th_corp\" where \"corpID\" = '$cusJoinID' ");
							$corp_regis = pg_fetch_result($qry_corp_regis,0);
							echo "<td>$cusJoinName <a onclick=\"javascript:popU('../corporation/frm_viewcorp_detail.php?corp_regis=$corp_regis','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
						}
						if($menu =="check") 
							{ 	
								
								$chk="chk".$count;
								$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
								echo "<td align=\"right\">$check</td>";
							}
						echo "</tr>";
					$jo++;
			}	
		}else{
			$count+=1;
			if($menu =="check") 
			{
				if($count%2==0){
				echo "<tr>";
				}else { 
				echo "<tr bgcolor=\"#99FF99\" >";}
			}else{
				echo "<tr>";
			}
			echo "<td align=\"right\"><font color=\"#FF5555\"><b>ผู้ขายฝากร่วม : </b></font></td><td></td>";
			if($menu =="check") 
			{ 	
				
				$chk="chk".$count;
				$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
				echo "<td align=\"right\">$check</td>";
				}
			echo "</tr>";
		}
}else{	
		$J = 0;	
		$haveCusJoin = "no";
		$qry_cusJoin = pg_query("SELECT distinct ta_array_list(a.\"CusIDarray\") AS \"cusJoinType\", a.\"contractID\"
								FROM thcap_contract_temp a
								WHERE a.\"autoID\" = '$contractAutoID'
								order by \"cusJoinType\" ");
		while($res_cusJoin = pg_fetch_array($qry_cusJoin))
		{
			$cusJoinType = $res_cusJoin["cusJoinType"]; // ประเภทลูกค้า
			if($cusJoinType == "1") // ถ้าเป็นผู้ขายฝากร่วม
			{
				$haveCusJoin = "yes";
				$qry_cusJoinID = pg_query("SELECT a.\"contractID\", ta_array_get(a.\"CusIDarray\", '1') AS \"cusJoinID\"
											FROM thcap_contract_temp a
											WHERE a.\"autoID\" = '$contractAutoID' ");
				while($res_cusJoinID = pg_fetch_array($qry_cusJoinID))
				{
					$J++;
					$cusJoinID = $res_cusJoinID["cusJoinID"];
					
					// หาชื่อ
					$qry_cusJoinName = pg_query("select * from \"VSearchCusCorp\" where \"CusID\" = '$cusJoinID' ");
					while($res_cusJoinName = pg_fetch_array($qry_cusJoinName))
					{
						$cusJoinName = $res_cusJoinName["full_name"];
						$cusType = $res_cusJoinName["type"]; // ประเภทลูกค้า
					}
					 $count+=1;
					if($menu =="check") 
					{
						if($count%2==0){
							echo "<tr>";
						}else { 
							echo "<tr bgcolor=\"#99FF99\" >";}
					}else{
						echo "<tr>";
					}

					echo "<td align=\"right\"><font color=\"#FF5555\"><b>ผู้ขายฝากร่วม คนที่ $J : </b></font></td>";
					if($cusType == 1)
					{
						echo "<td>$cusJoinName <a onclick=\"javascript:popU('".$realpath."nw/manageCustomer/showdetail2.php?CusID=$cusJoinID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
					}
					elseif($cusType == 2)
					{
						$qry_corp_regis = pg_query("select \"corp_regis\" from \"th_corp\" where \"corpID\" = '$cusJoinID' ");
						$corp_regis = pg_fetch_result($qry_corp_regis,0);
						echo "<td>$cusJoinName <a onclick=\"javascript:popU('".$realpath."nw/corporation/frm_viewcorp_detail.php?corp_regis=$corp_regis','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
					}
					if($menu =="check") 
							{ 	
								
								$chk="chk".$count;
								$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
								echo "<td align=\"right\">$check</td>";
							}
					echo "</tr>";
				}
			}
		}
		
		if($haveCusJoin == "no")
		{	 $count+=1;
			if($menu =="check") 
			{
				if($count%2==0){
				echo "<tr>";
				}else { 
				echo "<tr bgcolor=\"#99FF99\" >";}
			}else{
				echo "<tr>";
			}

			echo "<td align=\"right\"><font color=\"#FF5555\"><b>ผู้ขายฝากร่วม : </b></font></td><td></td>";
			if($menu =="check") 
			{ 	
				
				$chk="chk".$count;
				$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
				echo "<td align=\"right\">$check</td>";
			}
			echo"</tr>";
		}
}//ปิด IF มาจาก ใส่รายละเอียด BH		
	?>
	<?php // หา ผู้ค้ำประกัน
	
// หากมีค่าเป็น 't' คือให้แสดงข้อมูลจากตารางที่ใช้ข้อมูลจริง แต่หากเป็น null หรือ ไม่มีค่า หรือเป็นอื่นใดที่ไม่ใช่ 't' เท่ากับให้แสดงข้อมูลจากตารางรออนุมัติหรือตาราง temp
IF($ShowfromReal == 't'){	
		$G = 1;
		$qry_cusGuarantor = pg_query("SELECT a.\"CusID\" AS \"cusGuarantorID\",a.\"thcap_fullname\", a.\"contractID\",a.\"type\"
								FROM \"vthcap_ContactCus_detail\" a
								WHERE a.\"contractID\" = '$contract' AND a.\"CusState\" = '2'
								order by a.\"CusID\"");
		$row_guanjoin = pg_num_rows($qry_cusGuarantor);	
		if($row_guanjoin > 0){						
			while($res_cusGuarantor = pg_fetch_array($qry_cusGuarantor))
			{
				$cusGuarantorID = $res_cusGuarantor["cusGuarantorID"];
				$cusGuarantorName = $res_cusGuarantor["thcap_fullname"];
				$cusType = $res_cusGuarantor["type"]; // ประเภทลูกค้า
				$count+=1;
				if($menu =="check") 
				{
					if($count%2==0){
					echo "<tr>";
					}else { 
					echo "<tr bgcolor=\"#99FF99\" >";}
				}else{
					echo "<tr>";
				}
					
						echo "<td align=\"right\"><font color=\"#FF5555\"><b>ผู้ค้ำประกัน คนที่ $G : </b></font></td>";
						if($cusType == 1)
						{
							echo "<td>$cusGuarantorName <a onclick=\"javascript:popU('../manageCustomer/showdetail2.php?CusID=$cusGuarantorID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
						}
						elseif($cusType == 2)
						{
							$qry_corp_regis = pg_query("select \"corp_regis\" from \"th_corp\" where \"corpID\" = '$cusGuarantorID' ");
							$corp_regis = pg_fetch_result($qry_corp_regis,0);
							echo "<td>$cusGuarantorName <a onclick=\"javascript:popU('../corporation/frm_viewcorp_detail.php?corp_regis=$corp_regis','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
						}
						if($menu =="check") 
							{ 	
								
								$chk="chk".$count;
								$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
								echo "<td align=\"right\">$check</td>";
							}
						echo "</tr>";
					
				$G++;
			}
		}else{	
			 $count+=1;
	if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}

			echo "<td align=\"right\"><font color=\"#FF5555\"><b>ผู้ค้ำประกัน : </b></font></td><td></td>";
			if($menu =="check") 
			{ 	
				
				$chk="chk".$count;
				$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
				echo "<td align=\"right\">$check</td>";
			}
			echo "</tr>";
		}

}else{	
	
		$haveCusGuarantor = "no";
		$G = 0;
		$qry_cusGuarantor = pg_query("SELECT distinct ta_array_list(a.\"CusIDarray\") AS \"cusGuarantorType\", a.\"contractID\"
								FROM thcap_contract_temp a
								WHERE a.\"autoID\" = '$contractAutoID'
								order by \"cusGuarantorType\" ");
		while($res_cusGuarantor = pg_fetch_array($qry_cusGuarantor))
		{
			$cusGuarantorType = $res_cusGuarantor["cusGuarantorType"]; // ประเภทลูกค้า
			if($cusGuarantorType == "2") // ถ้าเป็นผู้ค้ำประกัน
			{
				$haveCusGuarantor = "yes";
				$qry_cusGuarantorID = pg_query("SELECT a.\"contractID\", ta_array_get(a.\"CusIDarray\", '2') AS \"cusGuarantorID\"
											FROM thcap_contract_temp a
											WHERE a.\"autoID\" = '$contractAutoID' ");
				while($res_cusGuarantorID = pg_fetch_array($qry_cusGuarantorID))
				{
					$G++;
					$cusGuarantorID = $res_cusGuarantorID["cusGuarantorID"];
					
					// หาชื่อ
					$qry_cusGuarantorName = pg_query("select * from \"VSearchCusCorp\" where \"CusID\" = '$cusGuarantorID' ");
					while($res_cusGuarantorName = pg_fetch_array($qry_cusGuarantorName))
					{
						$cusGuarantorName = $res_cusGuarantorName["full_name"];
						$cusType = $res_cusGuarantorName["type"]; // ประเภทลูกค้า
					}	

					 $count+=1;
	if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}

					echo "<td align=\"right\"><font color=\"#FF5555\"><b>ผู้ค้ำประกัน คนที่ $G : </b></font></td>";
					if($cusType == 1)
					{
						echo "<td>$cusGuarantorName <a onclick=\"javascript:popU('../manageCustomer/showdetail2.php?CusID=$cusGuarantorID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
					}
					elseif($cusType == 2)
					{
						$qry_corp_regis = pg_query("select \"corp_regis\" from \"th_corp\" where \"corpID\" = '$cusGuarantorID' ");
						$corp_regis = pg_fetch_result($qry_corp_regis,0);
						echo "<td>$cusGuarantorName <a onclick=\"javascript:popU('".$realpath."nw/corporation/frm_viewcorp_detail.php?corp_regis=$corp_regis','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
					}
					if($menu =="check") 
							{ 	
								
								$chk="chk".$count;
								$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
								echo "<td align=\"right\">$check</td>";
							}
					echo "</tr>";
				}
			}
		}
		
		if($haveCusGuarantor == "no")
		{   
		     $count+=1;
	if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}

			echo "<td align=\"right\"><font color=\"#FF5555\"><b>ผู้ค้ำประกัน : </b></font></td><td></td>";
			if($menu =="check") 
			{ 	
				
				$chk="chk".$count;
				$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
				echo "<td align=\"right\">$check</td>";
			}echo "</tr>";
		}
}//ปิด IF มาจาก ใส่รายละเอียด BH			
	?>
<?php $count+=1;
	if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
	<td align="right" valign="top"><font color="#FF5555"><b>รายละเอียดที่อยู่ : </b></font></td>
	<td><textarea cols="50" name="address" id="address" rows="5" style="background-color: #DDDDDD;" readonly><?php echo $textAddr;?></textarea></td>
	<?php
	if($menu =="check") 
	{ 	
		
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\" >$check</td>";
	}
	?>
</tr>
<?php $count+=1;
	if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
<?php if($menu =="check") 
	{?>
	<td></td>
	<td align="center">
		<div id="showPayTerm">

		</div>
	</td>
	<?php } else {?>
	<td colspan="2" align="center">
		<div id="showPayTerm">

		</div>
	</td>
	<?php } if($menu =="check") 
	{ 	
		
		$chk="chk".$count;
		$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
		echo "<td align=\"right\">$check</td>";
	}
	?>
</tr>

<tr>
	<td colspan="3" align="center">
			<div>
				<fieldset>
					<legend style="width:150"  ><B>รายการหนี้ที่ขอตั้งหนี้</B></legend>
					<center>
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
						<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
							<td>รหัสประเภท<br>ค่าใช้จ่าย</td>
							<td>รายละเอียดค่าใช้จ่าย</td>
							<td>ค่าอ้างอิง<br>ของค่าใช้จ่าย</td>
							<td>วันที่ตั้งหนี้</td>
							<td>จำนวนหนี้</td>
							<td>ผู้ตั้งหนี้</td>
							<td>วันที่ครบกำหนด</td>
							<td colspan="2" align="left">เหตุผล</td>
						</tr>
			<?php
			$qry_fr=pg_query("select * from \"thcap_temp_otherpay_debt\" a
				left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
				where \"ShowAppvStatus\"='0' and \"create_ref_contractID\" = '$contractAutoID'
				and \"contractID\"='$contractID' order by \"debtID\" ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$debtID=$res_fr["debtID"];
				$contractID=$res_fr["contractID"];
				$typePayID=$res_fr["typePayID"];
				$typePayRefValue=$res_fr["typePayRefValue"];
				$typePayRefDate=$res_fr["typePayRefDate"];
				$typePayAmt=$res_fr["typePayAmt"];
				$fullname=$res_fr["fullname"];
				$debtDueDate=$res_fr["debtDueDate"];
				$PayAmt+=$typePayAmt;
				if($debtDueDate==""){
					$debtDueDate="ไม่ระบุ";
				}
				// หารายละเอียดค่าใช้จ่ายนั้นๆ
				$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
				while($res_tpDesc = pg_fetch_array($qry_tpDesc))
				{
					$tpDesc = $res_tpDesc["tpDesc"];
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				
				<td><?php echo $typePayID; ?></td>
				<td><?php echo $tpDesc; ?></td>
				<td><?php echo $typePayRefValue; ?></td>
				<td><?php echo $typePayRefDate; ?></td>
				<td align="right"><?php echo number_format($typePayAmt,2); ?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td align="center"><?php echo $debtDueDate; ?></td>
				<?php
					//กำหนดขนาด popup 
					$qry_cancel_note_chk = pg_query("SELECT * FROM \"thcap_temp_otherpay_debt\" WHERE \"typePayID\" = '$typePayID' AND \"typePayRefValue\" = '$typePayRefValue' AND \"contractID\" = '$contractID' AND \"debtID\" != '$debtID'");
					$row_cancel_note_chk = pg_num_rows($qry_cancel_note_chk);
					IF($row_cancel_note_chk > 0){ $pop_h = '550'; }else{ $pop_h = '400';  }
				
				?>
				<td><span onclick="javascript:popU('../thcap/show_remark.php?debtID=<?php echo $debtID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=<?php echo $pop_h; ?>')" style="cursor: pointer;"><img src="images/detail.gif" height="19" width="19" border="0"></span></td>
				<?php $count+=1; 
				if($menu =="check") 
				{ 	
					$chk="chk".$count;
					$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
					echo "<td align=\"right\">$check</td>";
				}
				?>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr>";
				echo "<td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td>";
				 $count+=1; 
				if($menu =="check") 
				{ 
					$chk="chk".$count;
					$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ";
					echo "<td align=\"right\">$check</td>";
				}
				echo "</tr>";
			}
			else{ //ยอดรวมของหนี้
				echo "<tr bgcolor=\"#FFCECE\">";
				echo "<td  colspan=4 align=right><b>ยอดรวม<b></td>";
				$PayAmt=number_format($PayAmt,2);
				echo "<td align=right><b>$PayAmt<b></td>";
				echo "<td colspan=3 align=right></td>";
				 $count+=1; 
				if($menu =="check") 
				{ 
					$chk="chk".$count;
					$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ";
					echo "<td align=\"right\">$check</td>";
				}
				echo "</tr>";
			
			}
			?>
			</table>
					</center>
				</fieldset>
			</div>
	</td>
	</tr>
<?php 
// หากมีค่าเป็น 't' คือให้แสดงข้อมูลจากตารางที่ใช้ข้อมูลจริง แต่หากเป็น null หรือ ไม่มีค่า หรือเป็นอื่นใดที่ไม่ใช่ 't' เท่ากับให้แสดงข้อมูลจากตารางรออนุมัติหรือตาราง temp
IF($ShowfromReal == 't'){
	if($readonlyna != 't'){ ?>
		<?php $count+=1;
	if($menu =="check") 
	{
		if($count%2==0){
		echo "<tr>";
		}else { 
		echo "<tr bgcolor=\"#99FF99\" >";}
	}else{
		echo "<tr>";
	}
?>
			<td align="center" colspan="6"><input type="button" value="แก้ไขที่อยู่สัญญา" style="width:150px;" onclick="popU('<?php echo $realpath; ?>nw/thcap/frm_EditAddress.php?conid=<?php echo $contract; ?>&autoapp=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800');"></td>
			<?php if($menu =="check") 
			{ 	
				
				$chk="chk".$count;
				$check="<input type=\"checkbox\" name=\"$chk\" id=\"$chk\">ตรวจสอบ"; 
				echo "<td align=\"right\">$check</td>";
			}
			?>
		</tr>
	<?php } 
}//ปิด IF มาจาก ใส่รายละเอียด BH			
	?>
<form name="my" method="post" action="processcontract_check.php">
<?php
	if($menu =="check") 
	{ ?>
	<tr>
	<td align="right" valign="top"><font color="#FF5555"><b>หมายเหตุการตรวจสอบ :</b></font></td>
	<td><textarea cols="50" name="note" id="note" rows="5" ></textarea><font color="red">
	<span id="require">*</span><span name="f_note" id="f_note"></span></font></td>
	
	<?php	
	}	
	?>
		
	</tr>	
<input type="hidden" name="type" id="type" value="appv_loan">
<input type="hidden" name="contractAutoID" id="contractAutoID" value="<?php echo $contractAutoID; ?>">	
<?php	
	if($menu =="check") 
	{ 	
		$True1="<tr><td colspan=\"2\" align=\"center\">
		<input type=\"submit\" name=\"appv\" value=\"ถูกต้อง\" onclick=\"return ChecktrueOrfalse('$contractAutoID',true);\"> &nbsp;&nbsp;&nbsp; 
		<input type=\"submit\" name=\"unappv\"  value=\"ไม่ถูกต้อง/มีข้อสงสัย\" onclick=\"return ChecktrueOrfalse('$contractAutoID',false);\"> &nbsp;&nbsp;&nbsp; 
		<input type=\"button\"  value=\"ออก\" onclick=\"javascript:window.close();\"></td></tr>"; 
		echo "<td>$True1</td>";
	} 
	else{	
	?>
</form>
<form method="post" action="process_appv.php">
	<tr>
		<td align="right" valign="top"><font color="#FF5555"><b>หมายเหตุการอนุมัติ :</font></td>
		<td><textarea cols="50" name="note" id="note" rows="5" <?php if($lookonly == 'true'){ ?> style="background-color: #DDDDDD;" readonly <?php } ?>><?php if($lookonly == 'true'){echo $connote ;}?></textarea>
		<font color="red">
		<span id="require">*</span><span name="f_note" id="f_note"></span></font></td>
		
	</tr>
<?php 
if($lookonly != 'true'){ ?>

<tr>
	<td colspan="2" align="center">
		
			<input type="hidden" name="contractAutoID" id="contractAutoID" value="<?php echo $contractAutoID; ?>">
			<input type="hidden" name="doerStamp" id="doerStamp" value="<?php echo $doerStampp; ?>">
			<input name="appv" type="submit" value="อนุมัติ"  onclick="return Checktruenoteapp()" />
			<input name="unappv" type="submit" value="ไม่อนุมัติ"  onclick="return Checktruenoteapp()" />
		</form>	
	<!--input type="button" value="อนุมัติ" onclick="window.location='process_appv.php?appv=1&contractAutoID=<?php echo $contractAutoID; ?>&doerStamp=<?php echo $doerStamp; ?>'"> &nbsp;&nbsp;&nbsp; 
	<input type="button" value="ไม่อนุมัติ" onclick="window.location='process_appv.php?appv=2&contractAutoID=<?php echo $contractAutoID; ?>&doerStamp=<?php echo $doerStamp; ?>'"> &nbsp;&nbsp;&nbsp; <input type="button" value="ออก" onclick="javascript:window.close();"-->
	</td>
</tr>
<?php }
} ?>
<tr>
	<td><br></td>
</tr>	
<!--ตาราง รายชื่อคนตรวจสอบ-->
	<table align="center" width="50%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
		<tr align="center" bgcolor="#79BCFF">
			<th>ตรวจสอบครั้งที่</th>
			<th>รายชื่อผู้ตรวจสอบ</th>
			<th>วันที่ตรวจสอบ</th>
			<th>หมายเหตุ</th>
			<th>ผลการตรวจสอบ</th>		
		</tr>
	<?php 
		//ตรวจสอบ level ของ ผู้ใช้งาน
		$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$iduser' ");
		$leveluser = pg_fetch_array($query_leveluser);
		$emplevel=$leveluser["emplevel"];
		
		if($ShowfromReal == "t" && $contractAutoID == "")
		{ // ถ้าเป็นการหาข้อมูลเบื่องต้นจากตารางจริง และไม่มีค่า autoID ของตาราง thcap_contract_temp จะต้องหาค่า autoID ใหม่
			$qry_contract_temp = pg_query("select max(\"autoID\") from \"thcap_contract_temp\" where \"contractID\" = '$contract' and \"Approved\" = true ");
			$contractAutoID = pg_fetch_result($qry_contract_temp,0);
		}
		
		$query_main = pg_query("select * from \"thcap_contract_check_temp\" where \"ID\" = '$contractAutoID' order by  \"appvStamp\" desc");
		$row_check = pg_num_rows($query_main);
		$no=$row_check;
		if($row_check > 0)
		{
			while($result = pg_fetch_array($query_main))
			{   
				$autoIDSelect=$result ["autoID"];
				$appvID= $result ["appvID"];
				$appvStamp=$result ["appvStamp" ];
				$note=$result ["note"];
				$Approved=$result ["Approved"];
				
				
				if($Approved=="1"){$Approved="ถูกต้อง";}
				else{$Approved="ไม่ถูกต้อง";}
				
				if($emplevel<=1 or $appvID==$iduser)
				{
					$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$appvID' ");
					$nameuser = pg_fetch_array($query_fullname);
					$fullname=$nameuser["fullname"];
				}
				else{ 
					//$fullname="ผู้ตรวจสอบลำดับที่ ".$row_check;
					$fullname="T".($appvID+2556);//เลขที่ผู้ใช้ที่ตรวจสอบ+2556  เพื่อไม่ให้ ผู้ใช้เห็นว่าใครเป็นคนตรวจสอบ
					$row_check-=1;
				}
				
				echo "<tr bgcolor=\"#B2DFEE\">";
				echo "<td align=\"center\">$no</td>";
				echo "<td align=\"center\">$fullname</td>";
				echo "<td align=\"center\">$appvStamp</td>";
				echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_contract_check/frm_note_contract_check.php?autoIDCheck=$autoIDSelect','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=350')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>หมายเหตุ</u></font></a></td>";
				echo "<td align=\"center\">$Approved</td>";
				echo "</tr>";
				$no-=1;
			}
		}
		else
		{
			echo "<tr bgcolor=\"#B2DFEE\">";
			echo "<td align=\"center\" colspan=\"5\"><font color=\"#FF0000\">ไม่มีผู้ตรวจสอบสัญญา!!</font></td>";
			echo "</tr>";
		}
	?>	
</table>


<input type="hidden" id="showTablePayTerm" value="<?php echo $contractID; ?>">
<input type="hidden" id="StampAppv" value="<?php echo $StampAppv; ?>">
<input type="hidden" id="AppvStatus" value="<?php echo $AppvStatus; ?>">
<input type="hidden" id="doerStamp" value="<?php echo $doerStamp; ?>">

</body>
</table>
<script>
$(document).ready(function(){
	showTablePayTerm(); // แสดงตารางผ่อนชำระ
});
</script>

</html>
<?php
}
?>
<script type="text/javascript">
function ChecktrueOrfalse(contractAutoID,Approved) //ตรวจสอบการ ติกถูกต้อง ว่าครบหรือไม่
{   if(document.getElementById("note").value==''){alert('กรุณาป้อนข้อมูล หมายเหตุ');return false;	}
    else{
		var j=1;
		var ncount=1;
		while(j<=<?php echo $count?>)
		{ 
			if(document.getElementById("chk"+j).checked == true)
			{
				ncount+=1;	   
			}
			j++;
		}
		if(ncount!=j)
		{ 
			alert('กรุณาตรวจสอบข้อมูลให้ครบ');
			return false;		
		}
		else{ return true;		
		}
	}
}
function Checktruenoteapp(){
	if(document.getElementById("note").value==''){alert('กรุณาป้อนข้อมูล หมายเหตุ');return false;	}
    else{return true;		
	}
}
</script>