<?php
include("../../config/config.php");
$s=mssql_select_db("Taxiacc") or die("Can't select database");

$tac_nt_running=$_GET["tac_nt_running"];
$nowdate = Date('Y-m-d');
$year=substr($nowdate,0,4) + 543;
$qry_fr=pg_query("select * from tac_old_nt where tac_nt_running='$tac_nt_running'");
if($res_fr=pg_fetch_array($qry_fr)){
    $tac_nt_running=trim($res_fr["tac_nt_running"]); 
	$tac_cusid=trim($res_fr["tac_cusid"]); 
	$tac_nt_date=trim($res_fr["tac_nt_date"]);
	$tac_nt_start=trim($res_fr["tac_nt_start"]); 
	$tac_mount_start=substr($tac_nt_start,5,2);
	$tac_year_start=substr($tac_nt_start,0,4);
	$year1=$tac_year_start+543;
			
	$tac_nt_end=trim($res_fr["tac_nt_end"]); 
	$tac_mount_end=substr($tac_nt_end,5,2);
	$tac_year_end=substr($tac_nt_end,0,4);
	$year2=$tac_year_end+543;
	
	$tac_nt_amount2=trim($res_fr["tac_nt_amount"]); 
	$tac_nt_amount=number_format($tac_nt_amount2,2);
	
	if($tac_mount_start=="01" || $tac_mount_start=="02" || $tac_mount_start=="03" || $tac_mount_start=="04" || $tac_mount_start=="05" || 
	   $tac_mount_start=="06" || $tac_mount_start=="07" || $tac_mount_start=="08" || $tac_mount_start=="09"){
		$m1=substr($tac_mount_start,1);
	}else{
		$m1=$tac_mount_start;
	}

	if($tac_mount_end=="01" || $tac_mount_end=="02" || $tac_mount_end=="03" || $tac_mount_end=="04" || $tac_mount_end=="05" || 
	   $tac_mount_end=="06" || $tac_mount_end=="07" || $tac_mount_end=="08" || $tac_mount_end=="09"){
		$m2=substr($tac_mount_end,1);
	}else{
		$m2=$tac_mount_end;
	}
	
	if($tac_year_end == $tac_year_start){
		$m=($m2 - $m1)+1;
		$y=0;
	}else{
		$m=((12-$m1)+1)+$m2;
		$y=$tac_year_end-$tac_year_start;
		if($y==1){
			$y=0;
		}else{
			$y=$y-1;
		}
	}
	$nummonth=$m+($y*12);
	
	if($tac_mount_start=="01"){
		$month1="มกราคม";
	}else if($tac_mount_start=="02"){
		$month1="กุมภาพันธ์";
	}else if($tac_mount_start=="03"){
		$month1="มีนาคม";
	}else if($tac_mount_start=="04"){
		$month1="เมษายน";
	}else if($tac_mount_start=="05"){
		$month1="พฤษภาคม";
	}else if($tac_mount_start=="06"){
		$month1="มิถุนายน";
	}else if($tac_mount_start=="07"){
		$month1="กรกฎาคม";
	}else if($tac_mount_start=="08"){
		$month1="สิงหาคม";
	}else if($tac_mount_start=="09"){
		$month1="กันยายน";
	}else if($tac_mount_start=="10"){
		$month1="ตุลาคม";
	}else if($tac_mount_start=="11"){
		$month1="พฤศจิกายน";
	}else if($tac_mount_start=="12"){
		$month1="ธันวาคม";
	}
	
	if($tac_mount_end=="01"){
		$month2="มกราคม";
	}else if($tac_mount_end=="02"){
		$month2="กุมภาพันธ์";
	}else if($tac_mount_end=="03"){
		$month2="มีนาคม";
	}else if($tac_mount_end=="04"){
		$month2="เมษายน";
	}else if($tac_mount_end=="05"){
		$month2="พฤษภาคม";
	}else if($tac_mount_end=="06"){
		$month2="มิถุนายน";
	}else if($tac_mount_end=="07"){
		$month2="กรกฎาคม";
	}else if($tac_mount_end=="08"){
		$month2="สิงหาคม";
	}else if($tac_mount_end=="09"){
		$month2="กันยายน";
	}else if($tac_mount_end=="10"){
		$month2="ตุลาคม";
	}else if($tac_mount_end=="11"){
		$month2="พฤศจิกายน";
	}else if($tac_mount_end=="12"){
		$month2="ธันวาคม";
	}
}
//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF {
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$cline = 54;

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"ที่ $tac_nt_running");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 12;

$nowdate_thai=pg_query("select conversiondatetothaitext('$tac_nt_date')");
$nowdate_thai_show=pg_fetch_result($nowdate_thai,0);

$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"วันที่ $nowdate_thai_show");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 12;

$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"เรื่อง เตือนให้ชำระค่าบริการวิทยุสื่อสาร (เตือนครั้งสุดท้าย)");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;
$sql=mssql_query("select a.PreName,a.Name,a.SurName,a.CarRegis,b.RadioID from TacCusDtl a 
left join TacRadio b on a.CusID=b.CusID
where a.CusID='$tac_cusid'",$conn); 
if($res = mssql_fetch_array($sql)){
	$PreName=trim(iconv('WINDOWS-874','UTF-8',$res["PreName"]));
	$Name=trim(iconv('WINDOWS-874','UTF-8',$res["Name"]));
	$SurName=trim(iconv('WINDOWS-874','UTF-8',$res["SurName"]));
	$fullname=$PreName.$Name." ".$SurName;
	$CarRegis=trim(iconv('WINDOWS-874','UTF-8',$res["CarRegis"]));
	$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res["RadioID"]));
}
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"เรียน $fullname");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 8;
$tac_nt_amountmix=$tac_nt_amount2+1000;
$tac_nt_amountmix=number_format($tac_nt_amountmix,2);
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"                       ตามที่ท่านได้ทำสัญญาใช้บริการวิทยุสื่อสารรถแท็กซี่ กับ ศูนย์วิทยุ TAXI RADIO 1681 รหัสเรียกขาน $RadioID");
$pdf->MultiCell(185,6,$title,0,'L',0);

$cline += 6;
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"เลขทะเบียนรถ     $CarRegis    โดยท่านสัญญาว่าจะชำระค่าใช้บริการวิทยุสื่อสารเป็นรายเดือนๆ ละ    342.40   บาท  บัดนี้ท่านผิดนัด ไม่ชำระค่าบริการวิทยุสื่อสารเป็นจำนวน  $nummonth  เดือน ติดต่อกันตั้งแต่เดือน");
$pdf->MultiCell(185,6,$title,0,'L',0);

$cline += 6;
$pdf->SetXY(117,$cline);
$title=iconv('UTF-8','windows-874',"$month1 $year1  ถึง เดือน$month2 $year2");
$pdf->MultiCell(65,6,$title,0,'C',0);

$pdf->SetXY(178,$cline);
$title=iconv('UTF-8','windows-874',"รวมเป็นเงิน");
$pdf->MultiCell(19,6,$title,0,'L',0);

$cline += 6;
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"$tac_nt_amount บาท  การที่ท่านผิดนัดไม่ชำระค่าบริการดังกล่าวเป็นการทำผิดสัญญาในข้อสาระสำคัญทำให้บริษัทเสียหาย  ต้องเสียค่าใช้จ่าย ในการติดตาม  และค่าทนายความ  1,000.00 บาท  รวมเป็นจำนวนเงิน ทั้งสิ้น  $tac_nt_amountmix บาท");
$pdf->MultiCell(180,6,$title,0,'L',0);


$cline += 12;
$pdf->SetXY(25,$cline);

$title=iconv('UTF-8','windows-874',"                       เนื่องจาก  บริษัทมีภาระที่จะต้องชำระค่าบริการที่ท่านค้างชำระ ให้กับ  สำนักงานคณะกรรมการกิจการโทรคมนาคม                 แห่งชาติและต้องมีค่าใช้จ่ายในการบริหารจัดการระบบศูนย์วิทยุเป็นจำนวนมาก  ดังนั้น  โดยหนังสือนี้ ข้าพเจ้าในฐานะทนายความ ผู้รับมอบอำนาจจากบริษัท  จึงขอให้ท่านชำระจำนวนเงินดังกล่าวข้างต้นให้ครบถ้วนทันงวดทั้งหมด ณ ที่ทำการบริษัทภายใน 7 วัน                      นับจากวันที่ ที่ท่านได้รับหนังสือนี้ หรือถือว่าได้รับหนังสือนี้โดยชอบ  หากพ้นกำหนดนี้แล้วท่านคงเพิกเฉย ข้าพเจ้ามีความเสียใจที่ จะต้องให้ถือหนังสือนี้เป็นหนังสือบอกเลิกสัญญาให้บริการและจะดำเนินการปิดวิทยุสื่อสาร นับถัดจากวันที่พ้นกำหนดตามหนังสือ บอกกล่าวฉบับนี้ บริษัทจะแจ้งการยกเลิกสัญญาการใช้บริการวิทยุไปยังกรมการขนส่งทางบก และเพิกถอนใบอนุญาตตั้ง และใช้วิทยุ สื่อสารของรถแท็กซี่คันดังกล่าวอีกต่อไป ต่อ สำนักงาน กทช. ซึ่งจะทำให้รถยนต์ของท่านหมดสภาพความเป็นรถแท็กซี่อีกตลอดไป และเป็นรถยนต์รับจ้างที่ไม่ถูกต้องตามกฎหมาย  นอกจากนั้นยังจะทำให้ท่านต้องชำระค่าฤชาธรรมเนียมศาล ค่าใช้จ่ายอื่นๆ อีกมาก              โดยไม่จำเป็น");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 54;

$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"                       อนึ่ง  หากท่านชำระค่าบริการค้างชำระและค่าติดตาม ค่าทนายความข้างต้นภายหลังจากสัญญาสิ้นสุดลง   และปิดวิทยุ                       สื่อสารแล้ว และภายหลังหากท่านประสงค์ที่จะให้เปิดสัญญาณวิทยุใหม่ ท่านจะต้องชำระค่าเปิดสัญญาณเพิ่มอีก 1,000.00 บาท ด้วย");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 12;

$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"                       ข้าพเจ้าหวังว่าจะได้รับความร่วมมือจากท่าน ด้วยดี  ขอขอบคุณ");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 6;
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"                       จึงเรียนมาเพื่อทราบ");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 18;
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"ขอแสดงความนับถือ");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 28;

$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"(นายธวัตรไชย  ฤทธิ์หนู)");
$pdf->MultiCell(190,6,$title,0,'C',0);

$cline += 6;

$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"ทนายความผู้รับมอบอำนาจ");
$pdf->MultiCell(190,6,$title,0,'C',0);

// TA-NV added
$cline += 12;
$pdf->SetXY(25,$cline);
$title=iconv('UTF-8','windows-874',"หมายเหตุ  ติดต่อเร่งรัดหนี้สิน  โทร 02-744-2222  ต่อ 2297  คุณจันทร์เพ็ญ   มูลศรีนวล  ");
$pdf->MultiCell(190,6,$title,0,'L',0);

$cline += 12;
$pdf->SetXY(25,$cline);
$pdf->SetFont('AngsanaNew','',10);
$title=iconv('UTF-8','windows-874',"$tac_cusid");
$pdf->MultiCell(170,6,$title,0,'R',0);


$pdf->Output();
?>