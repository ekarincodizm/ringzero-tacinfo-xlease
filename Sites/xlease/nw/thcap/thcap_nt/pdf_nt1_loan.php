<?php
session_start();
include("../../../config/config.php");
require_once("../../../settings.php");
include("../../function/currency_totext.php"); //function แปลงจำนวนเงินเป็นตัวหนังสือ

$nt_num = pg_escape_string($_GET['NTID1']); //เลขที่หนังสือ NT
if($nt_num==""){ //ถ้าไม่มีการส่งค่าแบบ GET แสดงว่าส่งแบบ  POST
	$nt_num = pg_escape_string($_POST['NTID1']); //เลขที่หนังสือ NT จากการส่งค่าแบบ POST
}

$nowdate=nowDateTime();

//วันที่ภาษาไทย
$qrydatethai=pg_query("select get_date_thai_format('$nowdate')");
list($nowdatethai)=pg_fetch_array($qrydatethai);

//หาข้อมูลที่จะนำมาแสดงในหนังสือ
$qrydetail=pg_query("select \"contractID\",\"CusState\",\"NT_1_cusname\",\"NT_1_guaranID\",\"NT_1_Lawyer_Name\",\"NT_1_startDue\",\"NT_1_endDue\"
,\"NT_1_Debtmore\",\"NT_1_Duenext\",\"NT_1_Paynext\",\"NT_1_Paytagnext\",\"NT_1_contact\",\"NT_1_bank\"
,get_date_thai_format(\"NT_1_Date\") as startdate from \"thcap_NT1\" where \"NTID1\"='$nt_num'");
if($resdt=pg_fetch_array($qrydetail)){
	$contractID=$resdt['contractID']; //เลขที่สัญญา
	$CusState=$resdt['CusState']; //สถานะลูกค้า
	if($CusState==0){
		$txtstatus="ผู้กู้/จำนอง";
	}else if($CusState==1){
		$txtstatus="ผู้กู้ร่วม";
	}else if($CusState==2){
		$txtstatus="ผู้ค้ำ";
	}
	$cusname=$resdt['NT_1_cusname']; //ชื่อลูกค้า
	$type_asset=$resdt['NT_1_guaranID']; //ประเภทสินทรัพย์ที่จำนอง
	$startdate=$resdt['startdate']; //วันที่ทำสัญญาจำนอง
	$laywer=$resdt['NT_1_Lawyer_Name']; //ทนายความผู้รับมอบอำนาจ
	$pnum_start_remain=$resdt['NT_1_startDue']; //งวดที่เริ่มค้าง
	$pnum_end_remain=$resdt['NT_1_endDue']; //งวดที่ค้างล่าสุด
	$CusState=$resdt['NT_1_Debtmore']; //หนี้ที่ต้องการเรียบเก็บเพิ่มเติม
	$pnum_next=$resdt['NT_1_Duenext']; //งาดที่ค้างในอนาคต
	$money_next=number_format($resdt['NT_1_Paynext'],2); //ค่างวดในอนาคต
	$damages=number_format($resdt['NT_1_Paytagnext'],2); //ค่าติดตามทวงถามอนาคต
	$contact=$resdt['NT_1_contact']; //รายละเอียดการติดต่อ
	$bank=$resdt['NT_1_bank']; //บัญชีธนาคาร
	
	//หาวันที่ของดิว จากวันที่เริ่มค้าง
	$qrydatest=pg_query("select get_date_thai_format(\"ptDate\") from account.\"thcap_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"='$pnum_start_remain'");
	list($date_start_remain)=pg_fetch_array($qrydatest);

	//หาวันที่ของดิว ที่ค้างล่าสุด
	$qrydateend=pg_query("select get_date_thai_format(\"ptDate\") from account.\"thcap_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"='$pnum_end_remain'");
	list($date_end_remain)=pg_fetch_array($qrydateend);
	
	//หาวันที่ค้างในอนาคต
	$qrydatenext=pg_query("select get_date_thai_format(\"ptDate\") from account.\"thcap_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"='$pnum_next'");
	list($txtdate_next)=pg_fetch_array($qrydatenext);
		
	$pnum_total_remain=($pnum_end_remain-$pnum_start_remain)+1; //หาวันรวมที่ผิดนัด
	
	//จำนวนค่างวดทั้งหมดที่ค้าง
	$qryallmoney=pg_query("select money from 
	(SELECT ta_array_list(\"NT_1_Debtmore\") as typepayid,ta_array_get(\"NT_1_Debtmore\", ta_array_list(\"NT_1_Debtmore\")) as money,\"contractID\" FROM \"thcap_NT1\" 
	WHERE \"NTID1\"='$nt_num' ) as tableb
	where typepayid=account.\"thcap_mg_getMinPayType\"(\"contractID\")");
	list($allmoney_remain)=pg_fetch_array($qryallmoney);
	$allmoney_remain=number_format($allmoney_remain,2);

	//ข้อมูลสัญญา
	$qrycon=pg_query("select \"conLoanAmt\",\"conMinPay\",\"conRepeatDueDay\",\"conTerm\",get_date_thai_format(\"conFirstDue\") as txtstartdate from thcap_contract where \"contractID\"='$contractID'");
	if($rescon=pg_fetch_array($qrycon)){
		$conLoanAmt=$rescon['conLoanAmt']; //จำนวนเงินกู้
		$txtmoney=bahtThai($conLoanAmt);//แปลงจำนวนเงินเป็นภาษาไทย
		$money=number_format($conLoanAmt,2); //จำนวนเงินกู้
		$moneypmonth=number_format($rescon['conMinPay'],2); //จำนวนเงินผ่อนขั้นต่ำต่อ Due
		$everyday=$rescon['conRepeatDueDay']; //Due วันที่ชำระของทุกๆเดือน เช่น 01 หรือ 28
		$pnum=$rescon['conTerm']; //ระยะเวลาผ่อนชำระคืนเงินกู้ (เดือน)
		$txtstartdate=$rescon['txtstartdate']; //Due แรก
		
	}
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
$pdf->AddPage();

$cline = 54;

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"วันที่ $nowdatethai");
$pdf->MultiCell(170,6,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"NT $nt_num");
$pdf->MultiCell(170,6,$title,0,'L',0);

$cline += 10;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"เรื่อง");
$pdf->MultiCell(15,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"ให้ชำระหนี้ตามสัญญากู้ยืมเงินและบอกเลิกสัญญากู้ยืมเงิน ( เตือนครั้งสุดท้าย )");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 5;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"เรียน ");
$pdf->MultiCell(15,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"$cusname $txtstatus");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 10;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"อ้างถึง");
$pdf->MultiCell(15,6,$title,0,'L',0);

$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"หนังสือสัญญากู้ยืมเงิน  เลขที่  $contractID, หนังสือสัญญาจำนอง $type_asset และบันทึกข้อตกลงต่อท้ายสัญญาจำนอง $type_asset เป็นประกันฉบับลงวันที่ $startdate");
$pdf->MultiCell(180,6,$title,0,'L',0);

$cline += 15;

$pdf->SetXY(30,$cline);
$title=iconv('UTF-8','windows-874',"ตามที่   ท่านได้ทำสัญญากู้ยืมเงินตามที่อ้างถึงกับ");
$pdf->MultiCell(70,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(98,$cline);
$title=iconv('UTF-8','windows-874',"บริษัท  ไทยเอซ  แคปปิตอล  จำกัด ");
$pdf->MultiCell(52,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(147,$cline);
$title=iconv('UTF-8','windows-874',"เป็นเงินจำนวน");
$pdf->MultiCell(22,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(169,$cline);
$title=iconv('UTF-8','windows-874',"$money บาท");
$pdf->MultiCell(25,6,$title,0,'C',0);

$cline += 5;
$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"($txtmoney) และสัญญาว่าจะชำระคืนเงินกู้และดอกเบี้ยไม่น้อยกว่าเดือนละ $moneypmonth บาท ทุกวันที่ $everyday ของทุกๆ เดือน");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ติดต่อกันไปจนกว่าจะครบ $pnum งวดเดือน เริ่มชำระงวดแรกวันที่  $txtstartdate เป็นต้นไป");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 5;
$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"บัดนี้ ท่านได้ผิดนัดชำระเงินกู้ตั้งแต่งวดที่  $pnum_start_remain ถึง $pnum_end_remain คืองวดประจำวันที่  $date_start_remain ถึงงวดประจำ");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"วันที่ $date_end_remain รวม $pnum_total_remain งวด รวมเป็นจำนวนเงิน $allmoney_remain บาท การกระทำดังกล่าว ถือว่าท่านทำผิดสัญญากู้เงิน");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ข้อ 5.  ข้อ 10.  และทำผิดสัญญาจำนอง   ทำให้บริษัทได้รับความเสียหายบริษัทได้บอกกล่าวให้ท่านชำระหลายครั้งแล้ว   แต่ท่าน");
$pdf->MultiCell(170,6,$title,0,'L',0);

$cline += 5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"เพิกเฉย ไม่ชำระแต่อย่างใด");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 5;
$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"โดยหนังสือฉบับนี้  ข้าพเจ้าในฐานะทนายความผู้รับมอบอำนาจจากบริษัท  จึงขอให้ท่านชำระหนี้เงินกู้ดังนี้");
$pdf->MultiCell(142,6,$title,B,'L',0);

//หนี้ที่ต้องการเรียบเก็บเพิ่มเติม
$qrymore=pg_query("SELECT ta_array_list(\"NT_1_Debtmore\"),ta_array_get(\"NT_1_Debtmore\", ta_array_list(\"NT_1_Debtmore\")) FROM \"thcap_NT1\" where \"NTID1\"='$nt_num'");
$allpayleft=0;
while($resmore=pg_fetch_array($qrymore)){
	list($paytype,$payleft)=$resmore;
	
	//หาชื่อหนี้
	$qrynametype=pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\"='$paytype'");
	list($tpDesc)=pg_fetch_array($qrynametype);
	
	$typemin=pg_getminpaytype($contractID); //ชื่อประเภทของค่างวด
					
	if($paytype==$typemin){
		$tpDesc="ค่างวด";
		$txtmore="(งวดที่ $pnum_start_remain - งวดที่ $pnum_end_remain)";
	}else{
		$txtmore="";
	}
	
	$cline += 5;
	
	$pdf->SetXY(40,$cline);
	$title=iconv('UTF-8','windows-874',"$tpDesc $txtmore");
	$pdf->MultiCell(100,6,$title,0,'L',0);
	
	$pdf->SetXY(140,$cline);
	$title=iconv('UTF-8','windows-874',number_format($payleft,2)."   บาท");
	$pdf->MultiCell(30,6,$title,0,'R',0);
	$allpayleft=$allpayleft+$payleft;
}
$cline += 5;
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(40,$cline);
$title=iconv('UTF-8','windows-874',"รวมทั้งหมด");
$pdf->MultiCell(100,6,$title,0,'L',0);

$pdf->SetXY(140,$cline);
$title=iconv('UTF-8','windows-874',number_format($allpayleft,2)."   บาท");
$pdf->MultiCell(30,6,$title,0,'R',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(145,$cline);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(25,6,$title,B,'C',0);
$pdf->SetXY(145,$cline+1);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(25,6,$title,B,'C',0);

$cline += 8;
$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"พร้อมด้วยดอกเบี้ยตามสัญญากู้เงิน   อัตราร้อยละ 15  ต่อปีของเงินต้นค้างชำระจนถึงวันที่ท่านชำระ  ทั้งนี้ ข้าพเจ้าขอให้");
$pdf->MultiCell(190,5,$title,0,'L',0);

$cline += 5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ท่านชำระเงินจำนวนดังกล่าวให้แก่บริษัท   ณ  ที่ทำการบริษัท   หรือโอนเงินเข้า $bank");
$pdf->MultiCell(190,5,$title,0,'L',0);

$cline += 5;
$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(95,$cline);
$title=iconv('UTF-8','windows-874',"ชื่อบัญชี  บริษัท  ไทยเอซ  แคปปิตอล  จำกัด ");
$pdf->MultiCell(60,5,$title,0,'L',0);

$pdf->SetXY(155,$cline);
$title=iconv('UTF-8','windows-874',"ภายใน  30 วันนับแต่วันที่ท่าน");
$pdf->MultiCell(42,5,$title,B,'L',0);

$cline +=5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ได้รับหรือถือว่าได้รับหนังสือนี้โดยชอบ");
$pdf->MultiCell(55,6,$title,B,'L',0);

$cline +=5;
$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"อนึ่ง   ในระหว่างระยะเวลาที่กำหนดให้ท่านชำระหนี้ข้างต้น   หากระยะเวลาได้ครบกำหนดชำระเงินกู้อีก   1  งวด  คือ");
$pdf->MultiCell(155,6,$title,B,'L',0);

$cline +=5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"งวดที่ $pnum_next ประจำวันที่  $txtdate_next ท่านจะต้องชำระเงินกู้งวดดังกล่าวเพิ่มอีก 1 งวด จำนวน $money_next บาท พร้อมด้วย");
$pdf->MultiCell(170,6,$title,B,'L',0);

$cline +=5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ค่าเสียหายจากการติดตามเพิ่มอีก  $damages บาท (หากมี) รวมกับยอดเงินที่แจ้งข้างต้น");
$pdf->MultiCell(110,6,$title,B,'L',0);

$pdf->SetXY(130,$cline);
$title=iconv('UTF-8','windows-874',"เพื่อให้ชำระหนี้เงินกู้ตรงตามสัญญาครบถ้วน");
$pdf->MultiCell(100,6,$title,0,'L',0);

$cline +=5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ทันงวดทั้งหมด   หากท่านเพิกเฉย   หรือชำระ   แต่ชำระไม่ครบถ้วนทันงวด   หรือชำระเพียงบางส่วน    จนพ้นกำหนดระยะเวลาที่");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline +=5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"กำหนดนี้   ให้ถือว่าว่าสัญญากู้เป็นอันสิ้นสุดลงทันที   นับถัดจากวันครบระยะเวลาให้ชำระหนี้ตามหนังสือเตือนฉบับนี้   การชำระ");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline +=5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"หนี้บางส่วนในระหว่างบอกกล่าวนี้   แม้ว่าบริษัทได้รับชำระหนี้บางส่วนไว้ก็ตาม   ไม่ถือเป็นว่าบริษัทยอมผ่อนผันการชำระเงินกู้");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline +=5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ท่านยังต้องชำระให้ครบถ้วนตามจำนวนเงินและตามกำหนดระยะเวลาที่บอกกล่าวตามหนังสือฉบับนี้ อย่างเคร่งครัด");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline +=5;
$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"ผลของการสิ้นสุดสัญญากู้ และไม่ชำระหนี้ให้ครบถ้วนตามหนังสือนี้ จะทำให้ท่านต้องชำระหนี้ตามสัญญากู้เงินตามที่");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline +=5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"อ้างถึงทั้งหมดในคราวเดียวทันที   (ปิดบัญชี)  พร้อมด้วยค่าใช้จ่าย    ค่าดอกเบี้ย    ค่าติดตาม   ค่าเสียหาย   ค่าทนายความ    ค่าฤชา");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline +=5;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ธรรมเนียม เพิ่มขึ้นอีกมากโดยไม่จำเป็น  ข้าพเจ้าหวังว่าจะได้รับความร่วมมือจากท่านด้วยดี  ขอขอบคุณ");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline +=5;

$pdf->SetXY(35,$cline);
$title=iconv('UTF-8','windows-874',"จึงเรียนมาเพื่อทราบ");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;

$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ขอแสดงความนับถือ");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 15;

$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"($laywer)");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 6;

$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ทนายความผู้รับมอบอำนาจ");
$pdf->MultiCell(190,6,$title,0,'C',0);

// TA-NV added
$cline += 8;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"ติดต่อฝ่ายกฎหมาย");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;
$pdf->SetXY(20,$cline);
$title=iconv('UTF-8','windows-874',"$contact");
$pdf->MultiCell(190,6,$title,0,'L',0);

$pdf->Output();
?>