<?php
session_start();
include("../../../config/config.php");
require('../../../thaipdfclass.php');

//-- ปริ้นใบรับเช็ค ให้รองรับการเรียกใช้จากหลายๆหน้า หลายๆรูปแบบ 



$maincon = $_GET["maincon"]; // รับเลขที่สัญญา
if($maincon == ""){
	$maincon = $_GET[""]; //หาไม่มีจะใช้เลขที่เช็ค
}
$typecon = $_GET["typecon"]; //ประเภทของวันที่ค้นหา 	วันที่บนเช็ค /	วันที่รับเช็ค 
$datecon = $_GET["datecon"]; // วันที่ ที่ต้องการค้นหา

//ต่อเงื่อนไข
$condition = "date(a.\"$typecon\") = '$datecon' AND a.\"revChqToCCID\" = '$maincon' ";


$qry_selcol = pg_query("SELECT  a.\"revChqToCCID\",date(a.\"revChqDate\") AS \"revChqDate\",a.\"bankChqToCompID\"
						FROM  \"finance\".\"thcap_receive_cheque\" a WHERE $condition ");
$resultselcol = pg_fetch_array($qry_selcol);
	$revChqToCCID = $resultselcol["revChqToCCID"]; //เลขที่สัญญาของเช็คใบนั้นๆ
	$revChqDate = $resultselcol["revChqDate"]; //วันที่รับเช็ค
	$bankChqToCompID = $resultselcol["bankChqToCompID"]; //จ่ายบริษัท
	
	
		//หาชื่อลูกค้า
		$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$revChqToCCID' and \"CusState\" = '0'");
		list($cusid,$fullname) = pg_fetch_array($qry_cusname);	


		//หาที่อยู่		
		$qry_add = pg_query("SELECT \"thcap_address\" FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$revChqToCCID' ");
		list($addresssend) = pg_fetch_array($qry_add);	
		
		//กรณีไม่เจอที่อยู่หรือเป็นนิติบุคคล
		if($addresssend==""){
			$qry_addr = pg_query("SELECT concat(COALESCE(btrim(\"A_NO\"), ''), '', COALESCE(
			CASE
				WHEN \"A_SUBNO\" IS NULL OR \"A_SUBNO\" = '-' OR \"A_SUBNO\" = '--' THEN ''
				ELSE concat(' หมู่ ', btrim(\"A_SUBNO\"))
			END, ''), '', COALESCE(
			CASE
				WHEN \"A_SOI\" IS NULL OR \"A_SOI\" = '-' OR \"A_SOI\" = '--' THEN ''
				ELSE concat(' ซอย', btrim(\"A_SOI\"))
			END, ''), '', COALESCE(
			CASE
				WHEN \"A_RD\" IS NULL OR \"A_RD\" = '-' OR \"A_RD\" = '--' THEN ''
				ELSE concat(' ถนน', btrim(\"A_RD\"))
			END, ''), '', COALESCE(
			CASE
				WHEN \"A_TUM\" IS NULL OR \"A_TUM\" = '-' OR \"A_TUM\" = '--' THEN ''
				ELSE 
					CASE
						WHEN \"A_PRO\" = 'กรุงเทพ' OR \"A_PRO\" = 'กรุงเทพฯ' OR \"A_PRO\" = 'กรุงเทพมหานคร' OR \"A_PRO\" = 'กทม' OR \"A_PRO\" = 'กทม.' THEN concat(' แขวง', btrim(\"A_TUM\"))
						ELSE concat(' ตำบล', btrim(\"A_TUM\"))
					END
			END, ''), '', COALESCE(
			CASE
				WHEN \"A_AUM\" IS NULL OR \"A_AUM\" = '-' OR \"A_AUM\" = '--' THEN ''
				ELSE 
					CASE
						WHEN \"A_PRO\" = 'กรุงเทพ' OR \"A_PRO\" = 'กรุงเทพฯ' OR \"A_PRO\" = 'กรุงเทพมหานคร' OR \"A_PRO\" = 'กทม' OR \"A_PRO\" = 'กทม.' THEN concat(' เขต', btrim(\"A_AUM\"), ' ')
						ELSE concat(' อำเภอ', btrim(\"A_AUM\"), ' ')
					END
			END, ''), '', COALESCE(
			CASE
				WHEN \"A_PRO\" IS NULL THEN ''
				ELSE 
					CASE
						WHEN \"A_PRO\" = 'กรุงเทพ' OR \"A_PRO\" = 'กรุงเทพฯ' OR \"A_PRO\" = 'กรุงเทพมหานคร' OR \"A_PRO\" = 'กทม' OR \"A_PRO\" = 'กทม.' THEN btrim(\"A_PRO\")
						ELSE concat('จังหวัด', btrim(\"A_PRO\"))
					END
			END, ''), ' ', COALESCE(
			CASE
				WHEN \"A_POST\" IS NULL OR \"A_POST\" = '-' OR \"A_POST\" = '--' OR \"A_POST\" = '0' THEN ''
				ELSE btrim(\"A_POST\")
			END, ''), '', '') AS sentaddress
			FROM \"thcap_addrContractID\"
			where \"contractID\" = '$contractID' and \"addsType\" = '3'");
	list($addresssend)=pg_fetch_array($qry_addr);
	}






		

//--== SETTING ==--\\	
$X = 35;
$Y = 36;
$rows = 3; //ความสูงของบรรทัดข้อมูลในตารางรายการเช็ค
$note = "*ใบเสร็จนี้ไม่ใช่ใบเสร็จรับเงิน  แต่เป็นใบแสดงว่าบริษัทได้รับเช็คของท่านเรียบร้อยแล้ว"; //ข้อความแสดงการเตือน
//--== SETTING ==--\\	
	
$pdf=new ThaiPDF('P' ,'mm','a4');  
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();

$pdf->SetFont('AngsanaNew','B',14);  
$txt=iconv('UTF-8','windows-874',$revChqDate);
$pdf->Text($X,$Y,$txt); //วันที่รับเช็ค

$X += 70;

$txt=iconv('UTF-8','windows-874',$fullname);
$pdf->Text($X,$Y,$txt); //ชื่อลูกค้า

$X -= 70;
$Y += 8;

$txt=iconv('UTF-8','windows-874',$revChqToCCID);
$pdf->Text($X,$Y,$txt); //เลขที่สัญญา

$X += 68;
$Y -= 5;

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',$addresssend);
$pdf->MultiCell(90,5,$txt,0,'L'); //เลขที่สัญญา

$X -= 90;
$Y += 19;



$pdf->SetFont('AngsanaNew','',14);

$sumbankChqAmt = 0; //เก็บค่าเงินรวม
$qry_selcol = pg_query("SELECT  a.\"revChqToCCID\",a.\"bankChqNo\",date(a.\"bankChqDate\") AS \"bankChqDate\",
								b.\"bankName\",a.\"bankChqToCompID\",a.\"bankChqAmt\"
						FROM    \"finance\".\"thcap_receive_cheque\" a 
						LEFT JOIN \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
						WHERE $condition ");
while($redetail = pg_fetch_array($qry_selcol)){
	$bankChqNo=$redetail["bankChqNo"]; //เลขที่เช็ค
	$bankChqDate = $redetail["bankChqDate"]; //วันที่บนเช็ค
	$bankName = $redetail["bankName"]; //ชื่อธนาคารที่ออกเช็ค 
	$bankChqAmt = $redetail["bankChqAmt"]; //ยอดเช็ค

$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',$bankChqNo);
$pdf->MultiCell(30,$rows,$txt,0,'C'); //เลขที่เช็ค

$X += 35;

$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',$bankChqDate);
$pdf->MultiCell(25,$rows,$txt,0,'C'); //วันที่สั่งจ่าย

$X += 30;

$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',$bankName);
$pdf->MultiCell(70,$rows,$txt,0,'L'); //ชื่อธนาคารที่ออกเช็ค

$X += 80;

$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',number_format($bankChqAmt,2));
$pdf->MultiCell(40,$rows,$txt,0,'R'); //ยอดเช็ค

$X -= 145;
$Y += 5;

$sumbankChqAmt += $bankChqAmt;
}

$pdf->SetFont('AngsanaNew','',16);
$X += 145;
$Y = 107;

$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',number_format($sumbankChqAmt,2));
$pdf->MultiCell(40,$rows,$txt,0,'R'); //รวมยอดเช็คทั้งหมด

$X -= 145;
$Y += 7;

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',$note);
$pdf->MultiCell(200,$rows,$txt,0,'L'); //หมายเหตุ




//----------------------------------------------------- สำเนา -----------------------------------------------------------------------\\
$X = 35;
$Y += 66;
$rows = 3;


$pdf->SetFont('AngsanaNew','B',14);  
$txt=iconv('UTF-8','windows-874',$revChqDate);
$pdf->Text($X,$Y,$txt); //วันที่รับเช็ค

$X += 70;

$txt=iconv('UTF-8','windows-874',$fullname);
$pdf->Text($X,$Y,$txt); //ชื่อลูกค้า

$X -= 70;
$Y += 8;

$txt=iconv('UTF-8','windows-874',$revChqToCCID);
$pdf->Text($X,$Y,$txt); //เลขที่สัญญา

$X += 68;
$Y -= 5;

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',$addresssend);
$pdf->MultiCell(90,5,$txt,0,'L'); //เลขที่สัญญา

$X -= 90;
$Y += 18;



$pdf->SetFont('AngsanaNew','',14);

$sumbankChqAmt = 0; //เก็บค่าเงินรวม
$qry_selcol = pg_query("SELECT  a.\"revChqToCCID\",a.\"bankChqNo\",date(a.\"bankChqDate\") AS \"bankChqDate\",
								b.\"bankName\",a.\"bankChqToCompID\",a.\"bankChqAmt\"
						FROM    \"finance\".\"thcap_receive_cheque\" a 
						LEFT JOIN \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
						WHERE $condition ");
while($redetail = pg_fetch_array($qry_selcol)){
	$bankChqNo=$redetail["bankChqNo"]; //เลขที่เช็ค
	$bankChqDate = $redetail["bankChqDate"]; //วันที่บนเช็ค
	$bankName = $redetail["bankName"]; //ชื่อธนาคารที่ออกเช็ค 
	$bankChqAmt = $redetail["bankChqAmt"]; //ยอดเช็ค

$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',$bankChqNo);
$pdf->MultiCell(30,$rows,$txt,0,'C'); //ชื่อลูกค้า

$X += 35;

$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',$bankChqDate);
$pdf->MultiCell(25,$rows,$txt,0,'C'); //วันที่สั่งจ่าย

$X += 30;

$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',$bankName);
$pdf->MultiCell(70,$rows,$txt,0,'L'); //ชื่อธนาคารที่ออกเช็ค

$X += 80;

$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',number_format($bankChqAmt,2));
$pdf->MultiCell(40,$rows,$txt,0,'R'); //ยอดเช็ค

$X -= 145;
$Y += 5;

$sumbankChqAmt += $bankChqAmt;
}

$pdf->SetFont('AngsanaNew','',14);
$X += 145;
$Y = 249;

$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',number_format($sumbankChqAmt,2));
$pdf->MultiCell(40,$rows,$txt,0,'R'); //รวมยอดเช็คทั้งหมด

$X -= 145;
$Y += 8;

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY($X,$Y);
$txt=iconv('UTF-8','windows-874',$note);
$pdf->MultiCell(200,$rows,$txt,0,'L');  //หมายเหตุ

$pdf->Output(); //open pdf
