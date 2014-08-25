<?php
session_start();
include("../../config/config.php");
include("../../core/core_functions.php");
include("../function/nameMonth.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$month = $_GET["month"];
$year = $_GET["year"];

$nameMonthTH = nameMonthTH($month);
$yearTH = $year+543;

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(200,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานเงินต้นดอกเบี้ยรับ");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//----- หัวเลขที่สัญญา
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $nameMonthTH ปี พ.ศ. $yearTH");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);
//----- จบหัวเลขที่สัญญา

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(35,33);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(80,33);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(110,33);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(140,33);
$buss_name=iconv('UTF-8','windows-874',"เงินต้นรับชำระ");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(170,33);
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยรับชำระ");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 40;
$nub = 1;
$a=0;

$qry = pg_query("select * from \"thcap_temp_int_201201\" where substr(\"receiveDate\"::character varying,6,2) = '$month'
				and substr(\"receiveDate\"::character varying,1,4) = '$year' and \"isReceiveReal\" = '1' order by \"receiptID\"");
$num_row = pg_num_rows($qry);
$i = 1;
$sumAmountAll = 0; // จำนวนเงินที่รับชำระ รวมทั้งหมด
$sumPricipleAll = 0; // เงินต้นรับชำระ รวมทั้งหมด
$sumInterestAll = 0; // ดอกเบี้ยรับชำระ รวมทั้งหมด
$sumAmountOne += $receiveAmount; // ผลรวม จำนวนเงินที่รับชำระ ของแต่ละหน้า
$sumPricipleOne += $receivePriciple; // ผลรวม เงินต้นรับชำระ ของแต่ละหน้า
$sumInterestOne += $receiveInterest; // ผลรวม ดอกเบี้ยรับชำระ ของแต่ละหน้า

while($res = pg_fetch_array($qry))
{
	$receiveDate = $res["receiveDate"]; // วันที่รับชำระ
	$contractID = $res["contractID"]; // เลขที่สัญญา
	$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
	$receiveAmount = $res["receiveAmount"]; // จำนวนเงินที่รับชำระ
	$receivePriciple = $res["receivePriciple"]; // เงินต้นรับชำระ
	$receiveInterest = $res["receiveInterest"]; // ดอกเบี้ยรับชำระ
	
	$sumAmountOne += $receiveAmount; // ผลรวม จำนวนเงินที่รับชำระ ของแต่ละหน้า
	$sumPricipleOne += $receivePriciple; // ผลรวม จำนวนเงินที่รับชำระ ของแต่ละหน้า
	$sumInterestOne += $receiveInterest; // ผลรวม จำนวนเงินที่รับชำระ ของแต่ละหน้า
	
	$sumAmountAll += $receiveAmount; // จำนวนเงินที่รับชำระ รวมทั้งหมด
	$sumPricipleAll += $receivePriciple; // เงินต้นรับชำระ รวมทั้งหมด
	$sumInterestAll += $receiveInterest; // ดอกเบี้ยรับชำระ รวมทั้งหมด
	
	if($nub == 46)
	{ // ขึ้นหน้าใหม่
		$nub = 1;
		$cline = 40;
		$pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานเงินต้นดอกเบี้ยรับ");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		//----- หัวเลขที่สัญญา
		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $nameMonthTH ปี พ.ศ. $yearTH");
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);
		//----- จบหัวเลขที่สัญญา

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(35,33);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(80,33);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(110,33);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(140,33);
		$buss_name=iconv('UTF-8','windows-874',"เงินต้นรับชำระ");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(170,33);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยรับชำระ");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	}
	
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$receiveDate");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

	$pdf->SetXY(35,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$contractID");
	$pdf->MultiCell(40,4,$buss_name,0,'C',0);

	$pdf->SetXY(80,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$receiptID");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

	$pdf->SetXY(110,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($receiveAmount,2));
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(140,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($receivePriciple,2));
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(170,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($receiveInterest,2));
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	
	if($nub == 45)
	{ // ผลรวมของแต่ละหน้า
		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
		
		$cline += 5;
		
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(80,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ผลรวมเฉพาะหน้านี้");
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);

		$pdf->SetXY(110,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumAmountOne,2));
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(140,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumPricipleOne,2));
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(170,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumInterestOne,2));
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);
		
		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
		
		$sumAmountOne = 0;
		$sumPricipleOne = 0;
		$sumInterestOne = 0;
	}
	
	if($num_row == $i && $nub != 45)
	{ // ผลรวมเฉพาะหน้าสุดท้าย
		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
		
		$cline += 5;
		
		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(80,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ผลรวมเฉพาะหน้านี้");
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);

		$pdf->SetXY(110,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumAmountOne,2));
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(140,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumPricipleOne,2));
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(170,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumInterestOne,2));
		$pdf->MultiCell(30,4,$buss_name,0,'R',0);
		
		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
		
		$sumAmountOne = 0;
		$sumPricipleOne = 0;
		$sumInterestOne = 0;
	}

	/*
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,$cline+1);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	*/
    
	$cline += 5;
	$nub+=1;
	$a += 1;
	$i++;
}

if($num_row > 0)
{ // ผลรวมทั้งหมด	
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(80,$cline);
	$buss_name=iconv('UTF-8','windows-874',"ผลรวมทั้งหมด");
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);

	$pdf->SetXY(110,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sumAmountAll,2));
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(140,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sumPricipleAll,2));
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(170,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sumInterestAll,2));
	$pdf->MultiCell(30,4,$buss_name,0,'R',0);
	
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,$cline+1);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
}

$pdf->Output();
?>