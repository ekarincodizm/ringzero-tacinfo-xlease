<?php
session_start();
include("../../../config/config.php");
require_once("../../../settings.php");
include("../../function/currency_totext.php"); //function แปลงจำนวนเงินเป็นตัวหนังสือ

$id_user = $_SESSION["av_iduser"];
$NT_ID = pg_escape_string($_GET['NT_ID']); // เลขที่ใบแจ้งเตือน

pg_query("BEGIN WORK");
$status = 0;

$qry_nt = pg_query("select \"contractID\", \"NT_Date\", \"Cus_main\", \"Guarantee\", \"Cus_join\",
					\"arrayfirst_unpaid\", \"arrayend_unpaid\", \"pay_amt\", \"arrayunpaid_detailall\", 
					\"unpaid_detailall_amt\",\"interest\",\"arraypay_next\",\"amountpay_next\",\"amountpay_all\"
				from \"thcap_pdf_nt\"  
				where \"NT_ID\" = '$NT_ID'");
list($contractID,$nowdate,$name3,$guarantee,$Cus_join,$arrayfirst_unpaid,$arrayend_unpaid,$pay_amt,$arrayunpaid_detailall,$unpaid_detailall_amt,$conLoanIniRate,$arraypay_next,$nextDueAmt,$sum_typePayAmt) = pg_fetch_array($qry_nt);

//----- หาชื่อที่เกี่ยวข้องทั้งหมด
	$name3_forPage = $name3." (ผู้เช่าซื้อ)";
	$Cus_all_forPage =  $name3_forPage;

	if($Cus_join != '')
	{
		$Cus_join_forPage = $Cus_join." (ผู้เช่าซื้อร่วม)";
		$Cus_join_forPage = str_replace(","," (ผู้เช่าซื้อร่วม),",$Cus_join_forPage);
		$Cus_all_forPage =  $Cus_all_forPage.",".$Cus_join_forPage;
	}

	if($guarantee != '')
	{
		$guarantee_forPage = $guarantee." (ผู้ค้ำประกัน)";
		$guarantee_forPage = str_replace(","," (ผู้ค้ำประกัน),",$guarantee_forPage);
		$Cus_all_forPage =  $Cus_all_forPage.",".$guarantee_forPage;
	}
//----- จบการหาชื่อที่เกี่ยวข้องทั้งหมด

//วันที่ภาษาไทย
$qrydatethai=pg_query("select get_date_thai_format('$nowdate')");
list($nowdatethai)=pg_fetch_array($qrydatethai);

//ข้อมูลสัญญา
$qrycon=pg_query("select \"conDate\",\"conLoanIniRate\",\"conLoanAmt\",\"conRepeatDueDay\",\"conMinPay\",\"conFirstDue\" from thcap_contract where \"contractID\"='$contractID'");
if($rescon=pg_fetch_array($qrycon))
{
	$conDate = $rescon["conDate"];
	$conLoanIniRate = $rescon["conLoanIniRate"];
	$conLoanAmt = $rescon["conLoanAmt"]; // จำนวนเงินที่กู้ยืม
	$conRepeatDueDay = $rescon["conRepeatDueDay"]; // จ่ายทุกๆวันที่
	$conMinPay = $rescon["conMinPay"]; // ยอดผ่อนต่อเดือน
	$conFirstDue = $rescon["conFirstDue"]; // Due แรก
}

//------------------- PDF -------------------//
require('../../../thaipdfclass.php');

class PDF extends ThaiPDF {
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();

$Cus_all_forPage_array = split(",",$Cus_all_forPage);
for($Cus_all_array=1; $Cus_all_array<=count($Cus_all_forPage_array); $Cus_all_array++)
{

$pdf->AddPage();

$cline = 34;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(140,$cline);
$title=iconv('UTF-8','windows-874',"ที่  555 ถนน นวมินทร์ แขวง คลองกุ่ม");
$pdf->MultiCell(60,6,$title,0,'L',0);

$cline += 6;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(140,$cline);
$title=iconv('UTF-8','windows-874',"เขต บึงกุ่ม   กรุงเทพมหานคร");
$pdf->MultiCell(60,6,$title,0,'L',0);

$cline += 6;

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"วันที่ $nowdatethai");
$pdf->MultiCell(159,6,$title,0,'C',0);

$cline += 10;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"เรื่อง");
$pdf->MultiCell(15,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"ให้ชำระหนี้เงินกู้และบอกเลิกสัญญากู้ยืมเงิน");
$pdf->MultiCell(159,6,$title,0,'L',0);

$cline += 10;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"เรียน ");
$pdf->MultiCell(15,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',$Cus_all_forPage_array[$Cus_all_array-1]);
$pdf->MultiCell(159,6,$title,0,'L',0);

$cline += 10;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"อ้างถึง");
$pdf->MultiCell(15,6,$title,0,'L',0);

$qrydatethai_conDate=pg_query("select get_date_thai_format('$conDate')");
list($nowdatethai_conDate)=pg_fetch_array($qrydatethai_conDate);

$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"หนังสือสัญญากู้เงินสินเชื่อส่วนบุคคล  เลขที่  $contractID  ฉบับลงวันที่ $nowdatethai_conDate");
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline += 15;

$pdf->SetXY(40,$cline);
$title=iconv('UTF-8','windows-874',"ตามที่ ท่านได้ทำสัญญากู้ยืมเงินจำนวน ");
$pdf->MultiCell(70,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(92,$cline);
$title=iconv('UTF-8','windows-874',number_format($conLoanAmt,2)." บาท");
$pdf->MultiCell(28,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(120,$cline);
$title=iconv('UTF-8','windows-874',"จาก");
$pdf->MultiCell(10,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(126,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท  ไทยเอซ  แคปปิตอล  จำกัด  ");
$pdf->MultiCell(52,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(175,$cline);
$title=iconv('UTF-8','windows-874',"ตามหนังสือ");
$pdf->MultiCell(50,6,$title,0,'L',0);

$cline += 6;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"สัญญากู้เงินสินเชื่อส่วนบุคคลที่อ้างถึง โดยท่านสัญญาว่าจะชำระคืนเงินกู้ทุกวันที่  $conRepeatDueDay ของทุกๆ   เดือนไม่น้อยกว่าเดือนละ");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',number_format($conMinPay,2)." บาท");
$pdf->MultiCell(28,6,$title,0,'L',0);

// วันที่งวดแรกภาษาไทย
$qrydatethai_conFirstDue = pg_query("select get_date_thai_format('$conFirstDue')");
list($dateThai_conFirstDue) = pg_fetch_array($qrydatethai_conFirstDue);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(47,$cline);
$title=iconv('UTF-8','windows-874',"โดยเริ่มชำระงวดแรกวันที่  $dateThai_conFirstDue  เป็นต้นไปจนกว่าจะครบถ้วน");
$pdf->MultiCell(190,6,$title,0,'L',0);

//----- งวดที่เริ่มค้าง
	// งวดที่
	$qry_firstDue = pg_query("select \"ta_array1d_get\"('$arrayfirst_unpaid', 0) ");
	$firstDue = pg_fetch_result($qry_firstDue,0);
	
	// วันที่
	$qry_firstDueDate = pg_query("select \"ta_array1d_get\"('$arrayfirst_unpaid', 1) ");
	$firstDueDate = pg_fetch_result($qry_firstDueDate,0);
	
	//วันที่ภาษาไทย
	$qrydebtDueDate=pg_query("select get_date_thai_format('$firstDueDate')");
	list($s_debtDueDate)=pg_fetch_array($qrydebtDueDate);

//----- งวดสุดท้ายที่ค้าง
	// งวดที่
	$qry_endDue = pg_query("select \"ta_array1d_get\"('$arrayend_unpaid', 0) ");
	$endDue = pg_fetch_result($qry_endDue,0);
	
	// วันที่
	$qry_endDueDate = pg_query("select \"ta_array1d_get\"('$arrayend_unpaid', 1) ");
	$endDueDate = pg_fetch_result($qry_endDueDate,0);

	//วันที่ภาษาไทย
	$qrydebtDueDate=pg_query("select get_date_thai_format('$endDueDate')");
	list($e_debtDueDate)=pg_fetch_array($qrydebtDueDate);

$cline += 6;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(40,$cline);
$title=iconv('UTF-8','windows-874',"บัดนี้   ปรากฏว่าท่านได้ผิดนัดชำระเงินกู้ดังกล่าว  ตั้งแต่งวดที่  $firstDue   ถึงงวดที่  $endDue   คืองวดประจำวันที่ $s_debtDueDate");
$pdf->MultiCell(170,6,$title,0,'L',0);

$cline += 6;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ถึงงวดประจำวันที่  $e_debtDueDate");
$pdf->MultiCell(190,6,$title,0,'L',0);

//วันที่จะครบกำหนดชำระถัดไป
//----- หาข้อมูลงวดถัดไป
	// งวดที่
	$qry_nextDue = pg_query("select \"ta_array1d_get\"('$arraypay_next', 0) ");
	$typePayRefValue = pg_fetch_result($qry_nextDue,0);
	
	// วันที่
	$qry_nextDueDate = pg_query("select \"ta_array1d_get\"('$arraypay_next', 1) ");
	$nextDueDate = pg_fetch_result($qry_nextDueDate,0);
	
	// วันที่งวดถัดไป ภาษาไทย
	$qrydatethai_conDatenext=pg_query("select get_date_thai_format('$nextDueDate')");
	list($nowdatethai_conDatenext)=pg_fetch_array($qrydatethai_conDatenext);

if($typePayRefValue != "")
{
	$cline += 6;
	
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(20,$cline);
	$title=iconv('UTF-8','windows-874',"และจะถึงกำหนดชำระงวดที่ $typePayRefValue ประจำวันที่ $nowdatethai_conDatenext อีก 1 งวด จำนวน ".number_format($nextDueAmt,2)." บาท");
	$pdf->MultiCell(190,6,$title,0,'L',0);
}

$cline += 6;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"รวม เป็นเงินทั้งสิ้นจำนวน");
$pdf->MultiCell(40,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(55,$cline);
$title=iconv('UTF-8','windows-874',number_format($pay_amt+$nextDueAmt,2)." บาท");
$pdf->MultiCell(30,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(85,$cline);
$title=iconv('UTF-8','windows-874',"การกระทำดังกล่าวของท่าน เป็นการทำผิดสัญญากู้ยืมเงินที่อ้างถึงในข้อ 2.  และข้อ 3.");
$pdf->MultiCell(115,6,$title,0,'L',0);

$cline += 6;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ทำให้ผู้ให้กู้ได้รับความเสียหาย");
$pdf->MultiCell(190,6,$title,0,'L',0);


$cline += 6;

$pdf->SetXY(40,$cline);
$title=iconv('UTF-8','windows-874',"ข้าพเจ้า ในฐานะทนายความผู้รับมอบอำนาจจากผู้ให้กู้ จึงขอให้ท่านชำระเงินกู้ให้ทันงวดเป็นเงินจำนวน");
$pdf->MultiCell(159,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(175,$cline);
$title=iconv('UTF-8','windows-874',number_format($pay_amt+$nextDueAmt,2)." บาท");
$pdf->MultiCell(30,6,$title,0,'L',0);

//----- ค่าอื่น ๆ
	$txt_paydetail = "";
	
	// หาจำนวนค่าใช้จ่ายอื่นๆ
	$qry_count_array_otherPay = pg_query("select \"ta_array_count\"('$arrayunpaid_detailall') ");
	$count_array_otherPay = pg_fetch_result($qry_count_array_otherPay,0);
	
	for($i=2; $i<=$count_array_otherPay; $i++)
	{
		// ชื่อค่าใช้จ่าย
		$qry_array_otherPay = pg_query("SELECT \"ta_array_get_pos\"('$arrayunpaid_detailall', $i, 1) ");
		$tpDesc = pg_fetch_result($qry_array_otherPay,0);
		
		// จำนวนเงินค่าใช้จ่าย
		$qry_array_otherPay = pg_query("SELECT \"ta_array_get_pos\"('$arrayunpaid_detailall', $i, 2) ");
		$typePayAmt = pg_fetch_result($qry_array_otherPay,0);
		
		$txt_paydetail .= $tpDesc.'  '. number_format($typePayAmt,2).'  '." บาท    ";
	}

	$txt_s2= " รวมเป็นเงิน  ". number_format($sum_typePayAmt,2) ." บาท ";

$txt_s3 = "  ภายใน  30  วัน นับแต่วันที่ท่านได้รับหรือถือว่าได้รับหนังสือฉบับนี้";

$cline += 6;

$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',$txt_paydetail.$txt_s1.$txt_s2.$txt_s3);

$pdf->MultiCell(159,6,$title,0,'L',0);
$len=strlen($txt_paydetail.$txt_s1.$txt_s2.$txt_s3);
$cn=($len/159);

$cline = $cline + ($cn * 4) + 4;

$pdf->SetXY(40,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"หากพ้นกำหนดนี้แล้ว   ท่านคงเพิกเฉยอยู่อีกคือ ไม่ชำระ  หรือชำระไม่ครบถ้วนทันงวด  หรือชำระเพียงบางส่วนแล้ว");
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(20,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"ข้าพเจ้ามีความเสียใจอย่างยิ่งให้ถือเอาหนังสือฉบับนี้");
$pdf->MultiCell(70,6,$title,0,'L',0);

$pdf->SetXY(90,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"เป็นหนังสือแสดงเจตนาบอกเลิกสัญญากู้ยืมเงินฉบับอ้างถึง นับแต่วันที่ถัด");
$pdf->MultiCell(100,6,$title,B,'L',0);

$cline += 6;

$pdf->SetXY(20,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"จากวันครบกำหนดวันสุดท้ายให้ชำระตามหนังสือบอกกล่าวนี้ทันที ซึ่งจะมีผลให้ท่านต้องชำระหนี้ตามสัญญากู้ยืมเงิน");
$pdf->MultiCell(155,6,$title,B,'L',0);

$cline += 6;

$pdf->SetXY(20,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"ทั้งหมดในคราวเดียว พร้อมด้วยดอกเบี้ย ค่าใช้จ่าย  ค่าติดตามทวงถาม   ค่าทนายความทั้งหมด  และค่าปรับผิดสัญญา ณ.วันที่ทำ");
$pdf->MultiCell(168,6,$title,B,'L',0);

$cline += 6;

$pdf->SetXY(20,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"สัญญาเพิ่มขึ้นอีกส่วนหนึ่ง (ปิดบัญชี)  และข้าพเจ้ามีความเสียใจที่จะต้องฟ้องร้องดำเนินคดีกับท่านตามกฎหมายต่อไป");
$pdf->MultiCell(158,6,$title,B,'L',0);

$cline += 6;

$pdf->SetXY(40,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"ข้าพเจ้าหวังเป็นอย่างยิ่งว่าจะได้รับความร่วมมือจากท่านด้วยดี  ขอขอบคุณ");
$pdf->MultiCell(140,6,$title,0,'L',0);

$cline += 15;
$pdf->SetXY(40,$cline);
$title=iconv('UTF-8','windows-874',"จึงเรียนมาเพื่อทราบและดำเนินการ");
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline += 10;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ขอแสดงความนับถือ");
$pdf->MultiCell(180,6,$title,0,'C',0);

$cline += 20;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ทนายความผู้รับมอบอำนาจ");
$pdf->MultiCell(180,6,$title,0,'C',0);


$pdf->SetXY(20,260);	
$title=iconv('UTF-8','windows-874',"ติดต่อฝ่ายกฎหมาย");
$pdf->MultiCell(159,6,$title,0,'L',0);

$pdf->SetXY(20,265);
$title=iconv('UTF-8','windows-874',"โทร.  02-7442325  วันจันทร์ -  เสาร์  เวลา  08.30  – 17.00 น.");
$pdf->MultiCell(180,6,$title,0,'L',0);

// เลขที่ใบแจ้งเตือน
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(20,24);
$title=iconv('UTF-8','windows-874',"$NT_ID");
$pdf->MultiCell(150,6,$title,0,'L',0);

}

//บันทึกข้อมูล
$ins = "UPDATE \"thcap_history_nt\"
		SET \"NT_isprint\" = \"NT_isprint\" + 1
		WHERE \"NT_ID\" = '$NT_ID' ";
if($resin=pg_query($ins)){
	$ntid = pg_fetch_result($resin,0); // NT
}else{
	$status++;
}

if($status==0)
{ 
	$pdf->Output();
	pg_query("COMMIT");
}
else
{
	pg_query("ROLLBACK");
	echo iconv('UTF-8','windows-874',"เกิดข้อผิดพลาด");
}
?>