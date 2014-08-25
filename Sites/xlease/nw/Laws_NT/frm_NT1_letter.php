<?php
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}

include("../../config/config.php");
/*-============================================================================-
								กำหนดรายละเอียด
-============================================================================-*/
	$var1 = '29/2555';	
	$var2 = '17 พฤษภาคม พ.ศ. 2555';
	$var3 = 'ให้ชำระหนี้ตามสัญญากู้ยืมเงินและบอกเลิกสัญญากู้ยืมเงิน ( เตือนครั้งสุดท้าย)';
	$var4 = "นายสุนันท์ ผิวอ่อนดี  ผู้กู้/จำนอง\nนายเทวัญ ผิวอ่อนดี และนายสุวรรณ ผิวอ่อนดี  ผู้กู้";
	$var5 = 'MG-BK01-5500023';
	$var6 = '1 มีนาคม พ.ศ. 2555';
	$var7 = 'บริษัท ไทยเอซ แคปปิตอล จำกัด';
	$var8 = '180,000';
	$var9 = 'หนึ่งแสนแปดหมื่นบาทถ้วน';
	$var10 = '4,300';
	$var11 = '1';
	$var12 = '60';
	$var13 = '1 เมษายน พ.ศ. 2555';
	$var14 = '1';
	$var15 = '2';
	$var16 = '1 เมษายน พ.ศ. 2555';
	$var17 = '1 พฤษภาคม พ.ศ. 2555';
	$var18 = '2';
	$var19 = '8,600';
	$var20 = '800';
	$var21 = '1,500';
	$var22 = '10,900';
	$var23 = '15';
	$var24 = 'โอนเงินเข้าธนาคารกสิกรไทย จำกัด(มหาชน) สาขาถนนสุขาภิบาล 1 บางกะปิ บัญชีออมทรัพย์ เลขที่บัญชี 077 2 07121 5 ชื่อบัญชี บริษัท ไทยเอซ แคปปิตอล จำกัด';
	$var25 = '3';
	$var26 = '1 มิถุนายน พ.ศ. 2555';
	$var27 = '4,300';
	$var28 = '400';
	$var29 = 'นาย XXX XXXXX';
	$var30 = 'คุณ YYY YYYYY 0-2744-2222 (2329) วันจันทร์ - เสาร์ เวลา 08.30-17.00 น.';
	
/*-============================================================================-*/	
	
	
	
	
	
	
	
// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

}


$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();	

$Y = 20;	
	
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"NT");
$pdf->MultiCell(20,4,$title,0,'L',0);

$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',$var1);
$pdf->MultiCell(160,4,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(120,$Y);
$title=iconv('UTF-8','windows-874',"วันที่  ".$var2);
$pdf->MultiCell(160,4,$title,0,'L',0);

$Y += 10;
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"เรื่อง");
$pdf->MultiCell(20,4,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',$var3);
$pdf->MultiCell(160,4,$title,0,'L',0);

$Y += 7;

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"เรียน");
$pdf->MultiCell(20,4,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',$var4);
$pdf->MultiCell(160,6,$title,0,'L',0);
	
$Y += 15;

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"อ้างถึง");
$pdf->MultiCell(20,4,$title,0,'L',0);

$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',"หนังสือสัญญากู้ยืมเงิน  เลขที่ ".$var5." หนังสือสัญญาจำนองที่ดินและบันทึกข้อตกลงต่อท้ายสัญญาจำนองที่ดินเป็นประกัน");
$pdf->MultiCell(160,4,$title,0,'L',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"ฉบับลงวันที่ ".$var6);
$pdf->MultiCell(170,4,$title,0,'L',0);

$Y += 10;

$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',"ตามที่ ท่านได้ทำสัญญากู้ยืมเงินตามที่อ้างถึงกับ ".$var7." เป็นเงินจำนวน ".$var8." บาท (".$var9.")");
$pdf->MultiCell(170,4,$title,0,'L',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"และสัญญาว่าจะชำระคืนเงินกู้และดอกเบี้ยไม่น้อยกว่าเดือนละ ".$var10." บาท  ทุกวันที่ ".$var11." ของทุกๆเดือนติดต่อกันไปจนกว่าจะครบ ".$var12." งวดเดือน เริ่มชำระ");
$pdf->MultiCell(170,4,$title,0,'L',0);	

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"งวดแรกวันที่ ".$var13." เป็นต้นไป");
$pdf->MultiCell(170,4,$title,0,'L',0);

$Y += 7;

$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',"บัดนี้  ท่านได้ผิดนัดชำระเงินกู้ตั้งแต่งวดที่ ".$var14." ถึงงวดที่ ".$var15." คืองวดประจำวันที่ ".$var16." ถึงงวดประจำวันที่ ".$var17);
$pdf->MultiCell(170,4,$title,0,'L',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"รวม ".$var18." งวด รวมเป็นเงินจำนวน ".$var19." บาท การกระทำดังกล่าวถือว่าท่านทำผิดสัญญากู้เงินข้อ 5. ข้อ 10. และทำผิดสัญญาจำนอง  ทำให้บริษัทได้รับ");
$pdf->MultiCell(170,4,$title,0,'L',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"ความเสียหายบริษัทได้บอกกล่าวให้ท่านชำระหลายครั้งแล้ว แต่ท่านเพิกเฉย ไม่ชำระแต่อย่างใด");
$pdf->MultiCell(170,4,$title,0,'L',0);

$Y += 7;

$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',"โดยหนังสือฉบับนี้  ข้าพเจ้าในฐานะทนายความผู้รับมอบอำนาจจากบริษัท  จึงข้อให้ท่านชำระหนี้เงินกู้จำนวน ".$var18." งวดเดือนเป็นเงินจำนวน");
$pdf->MultiCell(170,4,$title,0,'L',0);	

$pdf->SetXY(35,$Y+0.3);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(155,4,$title,'B','L',0);		
	
$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',$var19." บาท  พร้อมด้วยค่าเสียหายจากค่าติดตามทวงถามเฉพาะก่อนมีหนังสือฉบับนี้จำนวน ".$var20." บาท และค่าทนายความ ".$var21." บาท ตามสัญญากู้ข้อ 4. ");
$pdf->MultiCell(170,4,$title,0,'L',0);

$pdf->SetXY(25,$Y+0.3);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(165,4,$title,'B','L',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"รวมเป็นเงิน ".$var22." บาท  พร้อมด้วยดอกเบี้ยตามสัญญากู้เงิน  อัตราร้อยละ ".$var23." ต่อปีของเงินต้นค้างชำระจนถึงวันที่ท่านชำระ  ทั้งนี้ ข้าพเจ้าขอให้ท่าน");
$pdf->MultiCell(170,4,$title,0,'L',0);

$pdf->SetXY(25,$Y+0.3);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,4,$title,'B','L',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"ชำระเงินจำนวนดังกล่าวให้แก่บริษัท  ณ.ที่ทำการบริษัทหรือ".$var24." ภายใน 30 วันนับแต่วันที่ท่านได้รับหรือถือว่าได้รับหนังสือนี้ โดยชอบ");
$pdf->MultiCell(170,6,$title,0,'L',0);

$Y += 20;

$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',"อนึ่ง ในระหว่างระยะเวลาที่กำหนดให้ท่านชำระหนี้ข้างต้น  หากระยะเวลาได้ครบกำหนดชำระเงินกู้อีก 1 งวด คืองวดที่ ".$var25." ประจำวันที่ ");
$pdf->MultiCell(170,5,$title,0,'L',0);

$pdf->SetXY(35,$Y+0.3);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(150,4,$title,'B','L',0);


$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',$var26." ท่านจะต้องชำระเงินกู้งวดดังกล่าวเพิ่มอีก 1 งวด  จำนวน ".$var27." บาท พร้อมด้วยค่าเสียหายจากการติดตามเพิ่มอีก ".$var28." บาท");
$pdf->MultiCell(170,5,$title,0,'L',0);

$pdf->SetXY(25,$Y+0.3);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(160,4,$title,'B','L',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"(หากมี) รวมกับยอดเงินที่แจ้งข้างต้น  เพื่อให้ชำระหนี้เงินกู้ตรงตามสัญญาครบถ้วนทันงวดทั้งหมด  หากท่านเพิกเฉยหรือชำระ แต่ชำระไม่ครบถ้วน");
$pdf->MultiCell(170,5,$title,0,'L',0);

$pdf->SetXY(25,$Y+0.3);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(42,4,$title,'B','L',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"ทันงวดหรือชำระเพียงบางส่วน  จนพ้นกำหนดระยะเวลาที่กำหนดนี้  ให้ถือว่าสัญญากู้ยืมเป็นอันสิ้นสุดลงทันที นับถัดจากวันครบระยะเวลาให้ชำระ");
$pdf->MultiCell(170,5,$title,0,'L',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"หนี้ตามหนังสือเตือนฉบับนี้  การชำระหนี้บางส่วนในระหว่างบอกกล่าวนี้  แม้ว่าบริษัทได้รับชำระหนี้บางส่วนไว้ก็ตาม ไม่ถือว่าบริษัทยอมผ่อนผัน");
$pdf->MultiCell(170,5,$title,0,'L',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"การชำระเงินกู้ ท่านยังต้องชำระให้ครบถ้วนตามจำนวนและตามกำหนดระยะเวลาที่บอกกล่าวตามหนังสือฉบับนี้  อย่างเคร่งครัด");
$pdf->MultiCell(170,5,$title,0,'L',0);

$Y += 7;

$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',"ผลของการสิ้นสุดสัญญากู้  และไม่ชำระหนี้ให้ครบถ้วนตามหนังสือนี้  จะทำให้ท่านต้องชำระหนี้ตามสัญญากู้เงินตามที่อ้างถึงทั้งหมดใน ");
$pdf->MultiCell(170,5,$title,0,'L',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"คราวเดียวทันที (ปิดบัญชี) พร้อมด้วยค่าใช้จ่าย ค่าดอกเบี้ย ค่าติดตาม ค่าเสียหาย ค่าทนายความ ค่าฤชาธรรมเนียม เพิ่มขึ้นอีกมากโดยไม่จำเป็น");
$pdf->MultiCell(170,5,$title,0,'L',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"ข้าพเจ้าหวังว่าจะได้รับความร่วมมือจากท่านด้วยดี  ขอขอบคุณ");
$pdf->MultiCell(170,5,$title,0,'L',0);

$Y += 10;

$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',"จึงเรียนมาเพื่อทราบ");
$pdf->MultiCell(170,5,$title,0,'L',0);

$Y += 7;

$pdf->SetXY(115,$Y);
$title=iconv('UTF-8','windows-874',"ขอแสดงความนับถือ");
$pdf->MultiCell(70,5,$title,0,'C',0);


$Y += 20;

$pdf->SetXY(115,$Y);
$title=iconv('UTF-8','windows-874',"(".$var29.")");
$pdf->MultiCell(70,5,$title,0,'C',0);

$Y += 7;

$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"ติดต่อฝ่ายกฎหมาย");
$pdf->MultiCell(70,5,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(115,$Y);
$title=iconv('UTF-8','windows-874',"ทนายความผู้รับมอบอำนาจ");
$pdf->MultiCell(70,5,$title,0,'C',0);

$Y += 7;

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',$var30);
$pdf->MultiCell(160,5,$title,0,'L',0);
	
$pdf->Output();	

?>



