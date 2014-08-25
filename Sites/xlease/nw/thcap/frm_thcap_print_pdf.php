<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',10);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(290,5,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();



		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"จัดการประเภทค่าใช้จ่าย");
		$pdf->MultiCell(290,5,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',9);
		$pdf->SetXY(5,19);
		$title=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,5,$title,'B','C',0);


		$pdf->SetFont('AngsanaNew','B',8);
		$pdf->SetXY(3,24);
		$title=iconv('UTF-8','windows-874',"รหัสประเภท\nค่าใช้จ่าย");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(14,24);
		$title=iconv('UTF-8','windows-874',"รหัสประเภท\nบริษัท");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(26,24);
		$title=iconv('UTF-8','windows-874',"รหัสประเภท\nสัญญา");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(42,24);
		$title=iconv('UTF-8','windows-874',"ชื่อประเภท\nค่าใช้จ่าย");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(61,24);
		$title=iconv('UTF-8','windows-874',"คำอธิบาย\nประเภท\nค่าใช้จ่าย");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(75,24);
		$title=iconv('UTF-8','windows-874',"สามารถ\nบันทึก\nลงสมุด");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(83,24);
		$title=iconv('UTF-8','windows-874',"สามารถ\nทำส่วน\nลด");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(92,24);
		$title=iconv('UTF-8','windows-874',"สามารถ\nยกเว้น");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(101,24);
		$title=iconv('UTF-8','windows-874',"มี VAT");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(110,24);
		$title=iconv('UTF-8','windows-874',"มีภาษีหัก\nณ ที่จ่าย");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(122,24);
		$title=iconv('UTF-8','windows-874',"สามารถข้าม\nลำดับ\nการจ่าย");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(133,24);
		$title=iconv('UTF-8','windows-874',"สามารถจ่าย\nบางส่วน");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(145,24);
		$title=iconv('UTF-8','windows-874',"อัตราภาษีหัก ณ \nที่จ่ายปัจจุบัน\nของค่าใช้จ่าย");
		$pdf->MultiCell(17,5,$title,0,'C',0);

		$pdf->SetXY(160,24);
		$title=iconv('UTF-8','windows-874',"เป็นสินค้า\nหรือบริการ");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(176,24);
		$title=iconv('UTF-8','windows-874',"อันดับการจ่าย");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(189,24);
		$title=iconv('UTF-8','windows-874',"เงื่อนไข\nในการเก็บ");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(200,24);
		$title=iconv('UTF-8','windows-874',"การเข้าถึง\nข้อมูล");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(210,24);
		$title=iconv('UTF-8','windows-874',"ประเภท\nที่ใช้\nอ้างอิง");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(220,24);
		$title=iconv('UTF-8','windows-874',"การรับ\nแทน");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(232,24);
		$title=iconv('UTF-8','windows-874',"เช่า\nทรัพย์สิน");
		$pdf->MultiCell(15,5,$title,0,'C',0);
		
		$pdf->SetXY(243,24);
		$title=iconv('UTF-8','windows-874',"อัตรา\nSCB\nปัจจุบัน");
		$pdf->MultiCell(15,5,$title,0,'C',0);
		
		$pdf->SetXY(253,24);
		$title=iconv('UTF-8','windows-874',"Lock\nค่า VAT");
		$pdf->MultiCell(15,5,$title,0,'C',0);
		
		$pdf->SetXY(263,24);
		$title=iconv('UTF-8','windows-874',"สามารถ\nตั้งหนี้");
		$pdf->MultiCell(15,5,$title,0,'C',0);
		
		$pdf->SetXY(273,24);
		$title=iconv('UTF-8','windows-874',"อัตรา\nLT\nปัจจุบัน");
		$pdf->MultiCell(15,5,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','B',9);
$pdf->SetXY(5,35);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,5,$title,'B','C',0);


$rows = 42;
$line = 1;

$qry_name=pg_query("SELECT * FROM account.\"thcap_typePay\" ORDER BY \"tpID\",\"tpRanking\" ");
while($res_name=pg_fetch_array($qry_name)){
    $tpID = $res_name["tpID"];
    $tpCompanyID = $res_name["tpCompanyID"];
    $tpConType = $res_name["tpConType"];  
    $tpDesc = $res_name["tpDesc"];
    $tpFullDesc = $res_name["tpFullDesc"];
	$ableB = $res_name["ableB"];
    $ableDiscount = $res_name["ableDiscount"];
    $ableWaive = $res_name["ableWaive"];  
    $ableVAT = $res_name["ableVAT"];
    $ableWHT = $res_name["ableWHT"];
	
	//By Por
	$ableSkip = $res_name["ableSkip"];
	$ablePartial = $res_name["ablePartial"];
	$curWHTRate = $res_name["curWHTRate"];
	$isServices = $res_name["isServices"];
	$tpSort = $res_name["tpSort"]; //ลำดับการแสดงผล
	$tpRanking = $res_name["tpRanking"]; //อันดับการจ่าย
	
	$curSBTRate= $res_name["curSBTRate"];
	$isLockedVat= $res_name["isLockedVat"];
	$ableInvoice= $res_name["ableInvoice"];
	$curLTRate= $res_name["curLTRate"];
	
	if($isServices=="0"){
		$txtservice="ไม่เข้าข่ายทั้งสอง";
	}else if($isServices=="1"){
		$txtservice="บริการ";
	}else if($isServices=="2"){
		$txtservice="สินค้า";
	}

	//End By Por
	
	//By Boz (เลียนแบบข้างบน)
	$tpType = trim($res_name["tpType"]); // เงื่อนไขในการเก็บ
	$whoSeen = $res_name["whoSeen"]; //ALL-เปิดให้เห็นทุกส่วนงาน
	$tpRefType = trim($res_name["tpRefType"]); //รูปแบบ Ref
	$isSubsti = $res_name["isSubsti"]; //substitutional - รับแทน เช่น รับแทนค่าประกัน
	$isLeasing = $res_name["isLeasing"];
	
	//ตรวจสอบเงิ่อนไข
	if($tpType == 'NONE'){
		$Typedesc = 'ไม่มีเงื่อนไขในการเก็บ';
	}else if($tpType == 'LOCKED'){
		$Typedesc = 'ไม่มีเงื่อนไขในการเก็บ แต่ว่าไม่ให้เพิ่มหนี้เข้าไปได้โดยทั่วไป';
	}else if($tpType == 'FIXED'){
		$Typedesc = 'เก็บค่าตายตัวทุกสัญญาเหมือนกันหมด';
	}else if($tpType == 'VAR'){
		$Typedesc = 'เก็บค่าไม่เหมือนกันแปรผันตามสัญญา';
	}else if($tpType == 'PER'){
		$Typedesc = 'เก็บค่าเป็น percent จากยอดที่สนใจ';
	}
	
	if($whoSeen == 'ALL'){
		$whoSeendesc = 'เปิดให้เห็นทุกส่วนงาน';
	}
	
	if($tpRefType == 'D'){
		$tpRefTypedesc = 'วันที่';
	}else if($tpRefType == 'W'){
		$tpRefTypedesc = 'สัปดาห์';
	}else if($tpRefType == 'M'){
		$tpRefTypedesc = 'รายเดือน';
	}else if($tpRefType == 'Y'){
		$tpRefTypedesc = 'รายปี';
	}else if($tpRefType == 'L'){
		$tpRefTypedesc = 'ช่วงใดๆ';
	}else if($tpRefType == 'RUNNING'){
		$tpRefTypedesc = 'ครั้งที่';
	}else if($tpRefType == 'ID'){
		$tpRefTypedesc = 'ตามหนังสือหรือรหัสใบ';
	}else if($tpRefType == 'DUE'){
		$tpRefTypedesc = 'Due หรือ งวดที่กำหนด ';
	}
	
	if($isSubsti == '0'){
		$isSubstidesc = 'ทั่วไป';
	}else if($isSubsti == '1'){
		$isSubstidesc = 'รับแทน';
	}
	//End By Boz 
	
	
	
	IF($line == 15){
	
		$pdf->AddPage();
		$page = $pdf->PageNo();
		
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"จัดการประเภทค่าใช้จ่าย");
		$pdf->MultiCell(290,5,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',9);
		$pdf->SetXY(5,19);
		$title=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,5,$title,'B','C',0);


		$pdf->SetFont('AngsanaNew','B',8);
		$pdf->SetXY(3,24);
		$title=iconv('UTF-8','windows-874',"รหัสประเภท\nค่าใช้จ่าย");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(14,24);
		$title=iconv('UTF-8','windows-874',"รหัสประเภท\nบริษัท");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(26,24);
		$title=iconv('UTF-8','windows-874',"รหัสประเภท\nสัญญา");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(42,24);
		$title=iconv('UTF-8','windows-874',"ชื่อประเภท\nค่าใช้จ่าย");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(61,24);
		$title=iconv('UTF-8','windows-874',"คำอธิบาย\nประเภท\nค่าใช้จ่าย");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(75,24);
		$title=iconv('UTF-8','windows-874',"สามารถ\nบันทึก\nลงสมุด");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(83,24);
		$title=iconv('UTF-8','windows-874',"สามารถ\nทำส่วน\nลด");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(92,24);
		$title=iconv('UTF-8','windows-874',"สามารถ\nยกเว้น");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(101,24);
		$title=iconv('UTF-8','windows-874',"มี VAT");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(110,24);
		$title=iconv('UTF-8','windows-874',"มีภาษีหัก\nณ ที่จ่าย");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(122,24);
		$title=iconv('UTF-8','windows-874',"สามารถข้าม\nลำดับ\nการจ่าย");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(133,24);
		$title=iconv('UTF-8','windows-874',"สามารถจ่าย\nบางส่วน");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(145,24);
		$title=iconv('UTF-8','windows-874',"อัตราภาษีหัก ณ \nที่จ่ายปัจจุบัน\nของค่าใช้จ่าย");
		$pdf->MultiCell(17,5,$title,0,'C',0);

		$pdf->SetXY(160,24);
		$title=iconv('UTF-8','windows-874',"เป็นสินค้า\nหรือบริการ");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(176,24);
		$title=iconv('UTF-8','windows-874',"อันดับการจ่าย");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(189,24);
		$title=iconv('UTF-8','windows-874',"เงื่อนไข\nในการเก็บ");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(200,24);
		$title=iconv('UTF-8','windows-874',"การเข้าถึง\nข้อมูล");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(210,24);
		$title=iconv('UTF-8','windows-874',"ประเภท\nที่ใช้\nอ้างอิง");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(220,24);
		$title=iconv('UTF-8','windows-874',"การรับ\nแทน");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(232,24);
		$title=iconv('UTF-8','windows-874',"เช่า\nทรัพย์สิน");
		$pdf->MultiCell(15,5,$title,0,'C',0);
		
		$pdf->SetXY(243,24);
		$title=iconv('UTF-8','windows-874',"อัตรา\nSCB\nปัจจุบัน");
		$pdf->MultiCell(15,5,$title,0,'C',0);
		
		$pdf->SetXY(253,24);
		$title=iconv('UTF-8','windows-874',"Lock\nค่า VAT");
		$pdf->MultiCell(15,5,$title,0,'C',0);
		
		$pdf->SetXY(263,24);
		$title=iconv('UTF-8','windows-874',"สามารถ\nตั้งหนี้");
		$pdf->MultiCell(15,5,$title,0,'C',0);
		
		$pdf->SetXY(273,24);
		$title=iconv('UTF-8','windows-874',"อัตรา\nLT\nปัจจุบัน");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',9);
		$pdf->SetXY(5,35);
		$title=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,5,$title,'B','C',0);


		$rows = 42;
		$line = 1;
	
	
	
	}
	
	
	
	
		$pdf->SetFont('AngsanaNew','B',9);
		$pdf->SetXY(3,$rows);
		$title=iconv('UTF-8','windows-874',"$tpID");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(14,$rows);
		$title=iconv('UTF-8','windows-874',"$tpCompanyID");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(26,$rows);
		$title=iconv('UTF-8','windows-874',"$tpConType");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(38,$rows);
		$title=iconv('UTF-8','windows-874',"$tpDesc");
		$pdf->MultiCell(25,5,$title,0,'C',0);

		$pdf->SetXY(61,$rows);
		$title=iconv('UTF-8','windows-874',"$tpFullDesc");
		$pdf->MultiCell(18,5,$title,0,'C',0);

		$pdf->SetXY(75,$rows);
		$title=iconv('UTF-8','windows-874',"$ableB");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(83,$rows);
		$title=iconv('UTF-8','windows-874',"$ableDiscount");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(92,$rows);
		$title=iconv('UTF-8','windows-874',"$ableWaive");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(101,$rows);
		$title=iconv('UTF-8','windows-874',"$ableVAT");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(110,$rows);
		$title=iconv('UTF-8','windows-874',"$ableWHT");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(122,$rows);
		$title=iconv('UTF-8','windows-874',"$ableSkip");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(133,$rows);
		$title=iconv('UTF-8','windows-874',"$ablePartial");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(145,$rows);
		$title=iconv('UTF-8','windows-874',"$curWHTRate");
		$pdf->MultiCell(17,5,$title,0,'C',0);

		$pdf->SetXY(160,$rows);
		$title=iconv('UTF-8','windows-874',"$txtservice");
		$pdf->MultiCell(18,5,$title,0,'C',0);

		$pdf->SetXY(176,$rows);
		$title=iconv('UTF-8','windows-874',"$tpRanking");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(189,$rows);
		$title=iconv('UTF-8','windows-874',"$tpType");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(200,$rows);
		$title=iconv('UTF-8','windows-874',"$whoSeen");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(210,$rows);
		$title=iconv('UTF-8','windows-874',"$tpRefType");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(220,$rows);
		$title=iconv('UTF-8','windows-874',"$isSubsti");
		$pdf->MultiCell(15,5,$title,0,'C',0);

		$pdf->SetXY(232,$rows);
		$title=iconv('UTF-8','windows-874',"$isLeasing");
		$pdf->MultiCell(15,5,$title,0,'C',0);
	
		$pdf->SetXY(243,$rows);
		$title=iconv('UTF-8','windows-874',"$curSBTRate");
		$pdf->MultiCell(15,5,$title,0,'C',0);
		
		$pdf->SetXY(253,$rows);
		$title=iconv('UTF-8','windows-874',"$isLockedVat");
		$pdf->MultiCell(15,5,$title,0,'C',0);
		
		$pdf->SetXY(263,$rows);
		$title=iconv('UTF-8','windows-874',"$ableInvoice");
		$pdf->MultiCell(15,5,$title,0,'C',0);
		
		$pdf->SetXY(273,$rows);
		$title=iconv('UTF-8','windows-874',"$curLTRate");
		$pdf->MultiCell(15,5,$title,0,'C',0);
	
	
	
		$rows += 10;
		$line++;
}

$pdf->Output();

?>