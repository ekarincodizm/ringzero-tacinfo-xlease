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
$qrycon=pg_query("select \"conDate\" from thcap_contract where \"contractID\"='$contractID'");
if($rescon=pg_fetch_array($qrycon)){
	$conDate=$rescon["conDate"];
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
$title=iconv('UTF-8','windows-874',"ให้ชำระหนี้ตามสัญญาเช่าซื้อ");
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
$title=iconv('UTF-8','windows-874',"หนังสือสัญญาเช่าซื้อ  เลขที่  $contractID  ฉบับลงวันที่ $nowdatethai_conDate");
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline += 15;

$pdf->SetXY(30,$cline);
$title=iconv('UTF-8','windows-874',"ตามที่ ท่านได้ทำสัญญาเช่าซื้อตามที่อ้างถึงกับ   ");
$pdf->MultiCell(70,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(90,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท  ไทยเอซ  แคปปิตอล  จำกัด  ");
$pdf->MultiCell(52,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(138,$cline);
$title=iconv('UTF-8','windows-874',"นั้น  บัดนี้  ท่านได้ผิดนัดการชำระ");
$pdf->MultiCell(90,6,$title,0,'L',0);

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
	
	$Pay_1="หนี้เช่าซื้องวดที่  $firstDue  ถึงงวดที่   ";
	$Pay_2="คืองวดประจำวันที่  $s_debtDueDate";

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
	
	$Pay_1.=" $endDue ";
	$Pay_2.="ถึงงวดวันที่  $e_debtDueDate";

$numPay = $endDue - $firstDue +1;
$Pay_2 .= " รวม  $numPay งวด  เป็น";

$cline += 5;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',$Pay_1.$Pay_2);
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline += 5;
$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"เงินจำนวน ". number_format($pay_amt,2)." บาท  การกระทำดังกล่าว  ถือว่าท่านทำผิดสัญญาเช่าซื้อทำให้บริษัทได้รับความเสียหาย  บริษัทได้บอกกล่าว");
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline += 5;
$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ท่านชำระหลายครั้งแล้วแต่ท่าน ไม่ชำระแต่อย่างใด  ");
$pdf->MultiCell(180,6,$title,0,'L',0);
	
$txt_T="โดยหนังสือฉบับนี้  ข้าพเจ้าในฐานะทนายความผู้รับมอบอำนาจจากบริษัท จึงขอให้ท่านชำระหนี้เช่าซื้อจำนวน  $numPay งวด";	
$cline += 5;	
$pdf->SetXY(40,$cline);
$title=iconv('UTF-8','windows-874',$txt_T);
$pdf->MultiCell(159,6,$title,0,'L',0);

//----- ค่าอื่น ๆ
	$txt_paydetail="เดือน จำนวน ".number_format($pay_amt,2)." บาท  ";
	
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

	$txt_s2= " รวมเป็นเงิน  ". number_format($unpaid_detailall_amt,2) ." บาท ";



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

//$txt_s3= "  พร้อมด้วยดอกเบี้ย ตามตามสัญญาเช่าซื้อ  อัตราร้อยละ  $conLoanIniRate ต่อปี  ของต้นเงินค้างชำระจนถึงวันที่ท่านชำระ  ทั้งนี้  ข้าพเจ้าขอให้ท่านชำระเงินจำนวนดังกล่าวให้แก่บริษัท  ณ.ที่ทำการบริษัทหรือโอนเงินเข้าธนาคารกสิกรไทย จำกัด  (มหาชน) สาขาโลตัส  สุขาภิบาล 1  บัญชีออมทรัพย์ เลขที่บัญชี  773 2 26116 2   ชื่อบัญชี  บริษัท  ไทยเอซ  แคปปิตอล  จำกัด  ภายใน 15  วัน  นับแต่วันที่ท่านได้รับหรือถือว่าได้รับหนังสือนี้โดยชอบ  ในกรณีที่ระยะเวลาที่กำหนดชำระตามที่กล่าวหากค่างวด งวดที่ $typePayRefValue ประจำวันที่ $nowdatethai_conDatenext อยู่ในระยะเวลาบอกกล่าวนี้ ท่านยังต้องชำระเพิ่มอีก 1 งวด เป็นเงินอีก ".number_format($nextDueAmt,2)."รวมเป็นเงินทั้งสิ้น ".number_format($sum_typePayAmt,2)." บาท หากท่านเพิกเฉย  หรือชำระ  แต่ชำระไม่ครบถ้วนตามจำนวนดังกล่าว  จนพ้นกำหนดระยะเวลาที่กำหนดนี้  ให้ถือว่าสัญญาเช่าซื้อเป็นอันสิ้นสุดลงทันที  นับถัดจากวันครบระยะเวลาให้ชำระหนี้ตามหนังสือเตือนฉบับนี้  การชำระหนี้บางส่วนในระหว่างบอกกล่าวนี้  แม้ว่าบริษัทได้รับชำระหนี้บางส่วนไว้ก็ตาม  ไม่ถือว่าบริษัทยอมผ่อนผันการชำระ";
$txt_s3= "   ทั้งนี้  ข้าพเจ้าขอให้ท่านชำระเงินจำนวนดังกล่าวให้แก่บริษัท  ณ.ที่ทำการบริษัทหรือโอนเงินเข้าธนาคารกสิกรไทย จำกัด  (มหาชน) สาขาโลตัส  สุขาภิบาล 1  บัญชีออมทรัพย์ เลขที่บัญชี  773 2 26116 2   ชื่อบัญชี  บริษัท  ไทยเอซ  แคปปิตอล  จำกัด  ภายใน 15  วัน  นับแต่วันที่ท่านได้รับหรือถือว่าได้รับหนังสือนี้โดยชอบ  ในกรณีที่ระยะเวลาที่กำหนดชำระตามที่กล่าวหากค่างวด งวดที่ $typePayRefValue ประจำวันที่ $nowdatethai_conDatenext อยู่ในระยะเวลาบอกกล่าวนี้ ท่านยังต้องชำระเพิ่มอีก 1 งวด เป็นเงินอีก ".number_format($nextDueAmt,2)."   บาท รวมเป็นเงินทั้งสิ้น ".number_format($sum_typePayAmt,2)."   บาท หากท่านเพิกเฉย  หรือชำระ  แต่ชำระไม่ครบถ้วนตามจำนวนดังกล่าว  จนพ้นกำหนดระยะเวลาที่กำหนดนี้  ให้ถือว่าสัญญาเช่าซื้อเป็นอันสิ้นสุดลงทันที  นับถัดจากวันครบระยะเวลาให้ชำระหนี้ตามหนังสือเตือนฉบับนี้  การชำระหนี้บางส่วนในระหว่างบอกกล่าวนี้  แม้ว่าบริษัทได้รับชำระหนี้บางส่วนไว้ก็ตาม  ไม่ถือว่าบริษัทยอมผ่อนผันการชำระ";


$cline += 5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',$txt_paydetail.$txt_s1.$txt_s2.$txt_s3);

$pdf->MultiCell(159,6,$title,0,'L',0);
$len=strlen($txt_paydetail.$txt_s1.$txt_s2.$txt_s3);
$cn=($len/159);

$cline = $cline+($cn*4)+2;
$pdf->SetXY(40,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"ผลของการสิ้นสุดสัญญาเช่าซื้อ  และไม่ชำระหนี้ให้ครบถ้วนตามหนังสือนี้  ท่านต้องชำระหนี้ตามสัญญาเช่าซื้อตามที่ ");
$pdf->MultiCell(180,6,$title,0,'L',0);


$cline = $cline+5;
$pdf->SetXY(20,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"อ้างถึงทั้งหมดในคราวเดียวทันที  (ปิดบัญชี)  พร้อมด้วยค่าใช้จ่าย  ค่าดอกเบี้ย  ค่าติดตาม  ค่าเสียหาย  ค่าทนายความ  ค่าฤชาธรรมเนียม  เพิ่มขึ้นอีกมากมายโดยไม่จำเป็น  ข้าพเจ้าหวังว่าจะได้รับความร่วมมือจากท่านด้วยดี  ขอขอบคุณ");
$pdf->MultiCell(180,6,$title,0,'L',0);
/*
$cline = $cline+5;
$pdf->SetXY(20,$cline);
$pdf->SetFont('AngsanaNew','',14);
$title=iconv('UTF-8','windows-874',"ธรรมเนียม  เพิ่มขึ้นอีกมากมายโดยไม่จำเป็น  ข้าพเจ้าหวังว่าจะได้รับความร่วมมือจากท่านด้วยดี  ขอขอบคุณ");
$pdf->MultiCell(180,6,$title,0,'L',0);
*/

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

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(20,24);
$title=iconv('UTF-8','windows-874',"ที่ $NT_ID");
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