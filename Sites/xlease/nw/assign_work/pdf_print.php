<?php
session_start();
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
include("../../config/config.php");
include("../../core/core_functions.php");

/*-============================================================================-
								   สัญญาเช่าซื้อ	
								รับข้อมูลจาก Session
-============================================================================-*/
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$AssignNo=pg_escape_string($_GET['AssignNo']);

$qry_detail = pg_query("select * from assign_work_detail where \"AssignNo\"='$AssignNo'");
if($res=pg_fetch_array($qry_detail)){
	$AssignDate = $res["AssignDate"];
	$Institution = $res["Institution"];
	$str = substr($res["Subject"],1,count($res["Subject"])-2);
	$Subject = explode(",",$str);
	$Place = $res["Place"];
	$CusID = $res["CusID"];
	$DebtorName = $res["DebtorName"];
	$PhoneNo = $res["PhoneNo"];
	$DeadlineDate = substr($res["DeadlineDate"],0,10);
	
	$AssignName = $res["AssignName"];
	$Note = $res["Note"];
} 

$cusname_qry = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\"='$CusID'");
$cusname = pg_fetch_result($cusname_qry,0);

for($i=0;$i<count($Subject);$i++){

	if($Subject[$i]=="1"){
		$subname = "รับเช็ค";
	} else if($Subject[$i]=="2"){
		$subname = "เอกสารรับกลับ";
	}else if($Subject[$i]=="3"){
		$subname = "ตรวจรับ/นับสินค้าบริการ";
	} else {
		$subname = "ไม่ระบุเรื่อง";
	}
	
	if($i==0){
		$allSubname = $subname;
	}else{
		$allSubname = $allSubname." , ".$subname;
	}
}

if($DebtorName==""){
	$NewDebtorNam = "-";
} else {
	$NewDebtorNam = $DebtorName;
}
/*-============================================================================-	
								กำหนดรายละเอียด
-============================================================================-*/
$address = "บริษัท ไทยเอซ แคปปิตอล จำกัด
		555 ถนนนวมินทร์ แขวงคลองกุ่ม เขตบึงกุ่ม กรุงเทพมหานคร 
		โทรศัพท์ 02-744-2222 โทรสาร 02-379-1111 www.tcapital.co.th";
$header = "ใบสั่งงาน 
		    Checker";
$issue = "รายละเอียดงาน";
$line = "___________________________________________________________________________________________________________________________________";
$date = "วันที่ : ".Substr($AssignDate,0,10);
$assignNo = "เลขที่สั่งงาน : ".$AssignNo;
$institute = "หน่วยงาน : ".$Institution;
$subject = "เรื่อง : ".$allSubname;
$custormerName = "ชื่อลูกค้า : ".$cusname;
$debtorName = "ชื่อลูกหนี้ : ".$NewDebtorNam;
$place = "สถานที่ : ".$Place;
$phone = "เบอร์โทรศัพท์/ผู้ติดต่อ : ".$PhoneNo;
$deadline = "กำหนดส่งงาน : ".$DeadlineDate;
$assignName = "ผู้สั่งงาน : ".$AssignName;
$note = "หมายเหตุ : ".$Note;

// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

}

$pdf=new PDF('P','mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();
$pdf->SetAutoPageBreak(-3);
$page = $pdf->PageNo();	

$Y = 4;	
//ที่อยู่	
$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(35,$Y);
$title=iconv('UTF-8','windows-874',$address);
$pdf->MultiCell(120,4,$title,0,'L',0);

$Y += 8;	
//รายละเอียด	
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$header);
$pdf->MultiCell(44,4,$title,0,'C',0);

$Y += 6;
//เส้นคัน
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(10,$Y);
$title=iconv('UTF-8','windows-874',$line);
$pdf->MultiCell(200,4,$title,0,'C',0);


$Y += 6;	
//รายละเอียดงาน	
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$issue);
$pdf->MultiCell(44,4,$title,0,'C',0);

$Y += 6;
//วันที่ 
$pdf->SetXY(140,$Y);
$title=iconv('UTF-8','windows-874',$date);
$pdf->MultiCell(61,5,$title,0,'R',0);

$Y += 6;
//เลขที่งาน
$pdf->SetXY(140,$Y);
$title=iconv('UTF-8','windows-874',$assignNo);
$pdf->MultiCell(61,5,$title,0,'R',0);

$Y += 6;
//หน่วยงาน
$pdf->SetXY(140,$Y);
$title=iconv('UTF-8','windows-874',$institute);
$pdf->MultiCell(61,5,$title,0,'L',0);

//ส่วนเจ้าของงาน
$pdf->SetXY(20,$Y);
$title=iconv('UTF-8','windows-874',"(ส่วนเจ้าของงาน)");
$pdf->MultiCell(61,5,$title,0,'L',0);

$Y += 6;
//เรื่อง 
$pdf->SetXY(20,$Y);
$title=iconv('UTF-8','windows-874',$subject);
$pdf->MultiCell(80,5,$title,0,'L',0);

$Y += 6;
//ลูกค้า,ลูกหนี้
$pdf->SetXY(20,$Y);
$title=iconv('UTF-8','windows-874',$custormerName);
$pdf->MultiCell(61,5,$title,0,'L',0);

$pdf->SetXY(75,$Y);
$title=iconv('UTF-8','windows-874',$debtorName);
$pdf->MultiCell(61,5,$title,0,'L',0);

$Y += 6;
//สถานที่
$pdf->SetXY(20,$Y);
$title=iconv('UTF-8','windows-874',$place);
$pdf->MultiCell(61,5,$title,0,'L',0);
//เบอร์โทรติดต่อ
$pdf->SetXY(140,$Y);
$title=iconv('UTF-8','windows-874',$phone);
$pdf->MultiCell(61,5,$title,0,'L',0);

$Y += 6;
//กำหนดส่งงาน
$pdf->SetXY(20,$Y);
$title=iconv('UTF-8','windows-874',$deadline);
$pdf->MultiCell(61,5,$title,0,'L',0);
//ผู้สั่งงาน
$pdf->SetXY(140,$Y);
$title=iconv('UTF-8','windows-874',$assignName);
$pdf->MultiCell(61,5,$title,0,'L',0);

$Y += 6;
//หมายเหตุ
$pdf->SetXY(20,$Y);
$title=iconv('UTF-8','windows-874',$note);
$pdf->MultiCell(61,5,$title,0,'L',0);

$Y += 6;
//เส้นคัน
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(10,$Y);
$title=iconv('UTF-8','windows-874',$line);
$pdf->MultiCell(200,4,$title,0,'C',0);

$Y += 6;	
//รายละเอียด	
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',"(ส่วนเจ้าของงาน)");
$pdf->MultiCell(44,4,$title,0,'C',0);

$Y += 6;	
//รับเช็ค
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(45,$Y);
$title=iconv('UTF-8','windows-874',"รับเช็ค");
$pdf->MultiCell(44,4,$title,0,'C',0);
		
//เอกสารรับกลับ
$pdf->SetXY(155,$Y);
$title=iconv('UTF-8','windows-874',"เอกสารรับกลับ");
$pdf->MultiCell(44,4,$title,0,'C',0);

$chequ_qry = pg_query("select * from assign_work_owner where \"AssignNo\"='$AssignNo'");
	while($resChq=pg_fetch_array($chequ_qry)){
		$ChequeAmt = $resChq["ChequeAmt"];
		$Datechq = $resChq["Date"];
		$Number = $resChq["Number"];
		$ChqBank = $resChq["ChqBank"];
		$CashAmt = $resChq["CashAmt"];
		$DocReturn = $resChq["DocReturn"];
		
		if($DocReturn==""){
			$newDoc = "-";
		}else {
			$newDoc = $DocReturn;
		}
		//กำหนดรายละเอียด
		$chqAmt = "เช็ค :  ".$ChequeAmt."  ฉบับ";
		$dateChq = "ลงวันที่ :   ".substr($Datechq,0,10);
		$number = "เลขที่ :    ".$Number;
		$chqBank = "เช็คธนาคาร :  ".$ChqBank;
		$cashAmt = "จำนวนเงิน :    ".number_format($CashAmt,2)."  บาท";
		$doc = "เอกสารระบุ :   ".$newDoc;
		
		$Y += 6;

		$pdf->SetXY(25,$Y);
		$title=iconv('UTF-8','windows-874',"/");
		$pdf->MultiCell(44,4,$title,0,'L',0);	
		
		//เช็ค
		$pdf->SetXY(25,$Y);
		$title=iconv('UTF-8','windows-874',$chqAmt);
		$pdf->MultiCell(44,4,$title,0,'L',0);
		
		//ลงวันที่
		$pdf->SetXY(50,$Y);
		$title=iconv('UTF-8','windows-874',$dateChq);
		$pdf->MultiCell(44,4,$title,0,'L',0);
		
		//เลขที่ 
		$pdf->SetXY(80,$Y);
		$title=iconv('UTF-8','windows-874',$number);
		$pdf->MultiCell(44,4,$title,0,'L',0);
		
		//เอกสารระบุ
		$pdf->SetXY(135,$Y);
		$title=iconv('UTF-8','windows-874',$doc);
		$pdf->MultiCell(44,4,$title,0,'L',0);
		
		$Y += 6;
		//เช็คธนาคาร
		$pdf->SetXY(25,$Y);
		$title=iconv('UTF-8','windows-874',$chqBank);
		$pdf->MultiCell(50,4,$title,0,'L',0);
		
		//จำนวนเงิน
		$pdf->SetXY(75,$Y);
		$title=iconv('UTF-8','windows-874',$cashAmt);
		$pdf->MultiCell(44,4,$title,0,'L',0);
		
		$Y += 4;
	}//endwhile

$Y += 6;
//เส้นคัน
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(10,$Y);
$title=iconv('UTF-8','windows-874',$line);
$pdf->MultiCell(200,4,$title,0,'C',0);

$Y += 6;
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',"(ส่วนเจ้าของงาน)");
$pdf->MultiCell(44,4,$title,0,'C',0);

$Y += 6;
$pdf->SetXY(20,$Y);
$title=iconv('UTF-8','windows-874',"ตรวจรับงาน");
$pdf->MultiCell(44,4,$title,0,'L',0);

$Y += 6;
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"[ ] ประเภทสินค้า/บริการ ................................................................................................................................................................................................................");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"[ ] ตรวจรับนับสินค้า");
$pdf->MultiCell(200,4,$title,0,'L',0);

$pdf->SetXY(55,$Y);
$title=iconv('UTF-8','windows-874',"[ ] ครบตามเอกสาร (ใบส่งของ,ใบกำกับ,ใบแจ้งหนี้,อื่นๆ.........................................)");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(55,$Y);
$title=iconv('UTF-8','windows-874',"[ ] ไม่ครบตามเอกสาร เนื่องจาก (ไม่ตรงสเป็ก,สินค้าชำรุด,อื่นๆ.......................................)");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"[ ] ตรวจรับการบริการ");
$pdf->MultiCell(200,4,$title,0,'L',0);

$pdf->SetXY(55,$Y);
$title=iconv('UTF-8','windows-874',"[ ] เรียบร้อย");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(55,$Y);
$title=iconv('UTF-8','windows-874',"[ ] ไม่เรียบร้อย เนื่องจาก (งานไม่สมบูรณ์  เป็นต้น)..............................................");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"[ ] คืนสินค้า (ถ้ามี) อ้างอิง Invoice No. ..........................................................................................................................................................................................");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(20,$Y);
$title=iconv('UTF-8','windows-874',"เอกสารนำกลับบริษัท");
$pdf->MultiCell(44,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"[ ] ใบกำกับภาษี");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"[ ] ใบส่งของ");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"[ ] ใบแจ้งหนี้");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"[ ] ใบเสร็จรับเงิน");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"[ ] อื่นๆ");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"[ ] เอกสารที่รับกลับจำนวน ..........ชุด จาก Invoice no. .................... - ....................");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"[ ] เอกสารที่ไม่ได้รับกลับจำนวน ..........ชุด จาก Invoice no. .................... - ....................");
$pdf->MultiCell(200,4,$title,0,'L',0);

$Y += 6;
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',"หมายเหตุ .................................................................................................................................................................................................");
$pdf->MultiCell(200,4,$title,0,'L',0);

$b=270;
//เส้นคัน
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(10,$b);
$title=iconv('UTF-8','windows-874',$line);
$pdf->MultiCell(200,4,$title,0,'C',0);

$b+= 6;
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(25,$b);
$title=iconv('UTF-8','windows-874',"* แนบรูปถ่ายประกอบมาด้วยทุกครั้งที่ส่งมอบงาน (พิมพ์เป็นเอกสาร หรือ ให้เป็นไฟล์รูปภาพ)");
$pdf->MultiCell(200,4,$title,0,'L',0);

$b+= 6;
$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(25,$b);
$title=iconv('UTF-8','windows-874',"** กรุณาระบุการตรวจนับรับสินค้า/บริการ ตามจริง และครบถ้วน");
$pdf->MultiCell(200,4,$title,0,'L',0);


$pdf->SetXY(140,$b);
$title=iconv('UTF-8','windows-874',"ผู้รับงาน ........................................................");
$pdf->MultiCell(65,4,$title,0,'R',0);

$b += 6;
$pdf->SetXY(140,$b);
$title=iconv('UTF-8','windows-874',"ลงวันที่ ........................................................");
$pdf->MultiCell(65,4,$title,0,'R',0);



$pdf->Output();	

?>



