<?php
session_start();
include("../../config/config.php");
include("../../core/core_functions.php");
include("../function/nameMonth.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$month = $_GET["month"]; // เดือนที่เลือก
$year = $_GET["year"]; // ปีที่เลือก

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

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานดอกเบี้ยประจำเดือน");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//----- หัวเลขที่สัญญา
$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $nameMonthTH ปี พ.ศ. $yearTH");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);
//----- จบหัวเลขที่สัญญา

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(50,33);
$buss_name=iconv('UTF-8','windows-874',"ประเภทสินเชื่อ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(85,33);
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
$pdf->MultiCell(70,4,$buss_name,0,'C',0);

$pdf->SetXY(160,33);
$buss_name=iconv('UTF-8','windows-874',"ยอดดอกเบี้ยที่เกิดขึ้นทั้งหมด");
$pdf->MultiCell(45,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 40;
$nub = 1;
$a=0;

$qry = pg_query("select distinct a.\"contractID\",
				(select b.\"conType\" from \"thcap_contract\" b where b.\"contractID\" = a.\"contractID\") as \"conType\",
				\"thcap_getInterestGainOverMonth\"(a.\"contractID\", '$year', '$month') as \"newInterest\"
				from \"thcap_temp_int_201201\" a
				where substr(a.\"receiveDate\"::character varying,'1','4')::integer = '$year'
				and substr(a.\"receiveDate\"::character varying,'6','2')::integer = '$month'
				and \"thcap_getInterestGainOverMonth\"(a.\"contractID\", '$year', '$month') > '0.00'
				order by \"conType\", \"contractID\" ");
$num_row = pg_num_rows($qry);
$i = 1;

$sumInterest = 0; // ยอดรวมของดอกเบี้ยแต่ละประเภท
$allInterest = 0; // ดอกเบี้ยรวมทุกประเภท

while($res = pg_fetch_array($qry))
{
	$contractID = $res["contractID"]; // เลขที่สัญญา
	$conType = $res["conType"]; // ประเภทสินเชื่อ
	$newInterest = $res["newInterest"]; // ยอดดอกเบี้ยที่เกิดขึ้นทั้งหมด ของเดือนและปีที่เลือก
	
	if($i == 1){$spitConType = $conType;}
	
	//ค้นหาชื่อผู้กู้หลัก
	$qry_namemain = pg_query("select * from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" ='0'");
	if($resnamemain = pg_fetch_array($qry_namemain)){
		$name3 = trim($resnamemain["thcap_fullname"]);
	}
	
	if($nub == 46)
	{ // ขึ้นหน้าใหม่
		$nub = 1;
		$cline = 40;
		$pdf->AddPage();
		
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',16);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานดอกเบี้ยประจำเดือน");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		//----- หัวเลขที่สัญญา
		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $nameMonthTH ปี พ.ศ. $yearTH");
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);
		//----- จบหัวเลขที่สัญญา

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',16);
		$pdf->SetXY(5,33);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(50,33);
		$buss_name=iconv('UTF-8','windows-874',"ประเภทสินเชื่อ");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(85,33);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
		$pdf->MultiCell(70,4,$buss_name,0,'C',0);

		$pdf->SetXY(160,33);
		$buss_name=iconv('UTF-8','windows-874',"ยอดดอกเบี้ยที่เกิดขึ้นทั้งหมด");
		$pdf->MultiCell(45,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	}
	
	if($spitConType != $conType)
	{
		$pdf->SetFont('AngsanaNew','B',15);
		$pdf->SetXY(85,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้นรวมของประเภทสินเชื่อ $spitConType");
		$pdf->MultiCell(70,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(160,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumInterest,2));
		$pdf->MultiCell(45,4,$buss_name,0,'R',0);
		
		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,$cline);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
		
		$sumInterest = 0;
		$spitConType = $conType;
		
		$cline += 5;
		$nub+=1;
		$a += 1;
		$i++;
	}

	$pdf->SetFont('AngsanaNew','',15);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$contractID");
	$pdf->MultiCell(40,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(50,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$conType");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

	$pdf->SetXY(85,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$name3");
	$pdf->MultiCell(70,4,$buss_name,0,'L',0);

	$pdf->SetXY(160,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($newInterest,2));
	$pdf->MultiCell(45,4,$buss_name,0,'R',0);
	
	$sumInterest += $newInterest;
	$allInterest += $newInterest;
	
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
{
	$pdf->SetFont('AngsanaNew','B',15);
	$pdf->SetXY(85,$cline);
	$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยที่เกิดขึ้นรวมของประเภทสินเชื่อ $spitConType");
	$pdf->MultiCell(70,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(160,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sumInterest,2));
	$pdf->MultiCell(45,4,$buss_name,0,'R',0);
		
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	
	$cline += 5;
	$nub+=1;
	$a += 1;
	$i++;
	
	$pdf->SetFont('AngsanaNew','B',15);
	$pdf->SetXY(85,$cline);
	$buss_name=iconv('UTF-8','windows-874',"รวมดอกเบี้ยที่เกิดขึ้นทุกประเภท");
	$pdf->MultiCell(70,4,$buss_name,0,'R',0);
	
	$pdf->SetXY(160,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($allInterest,2));
	$pdf->MultiCell(45,4,$buss_name,0,'R',0);
		
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(5,$cline);
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
	$pdf->MultiCell(200,4,$buss_name,0,'C',0);
}

$pdf->Output();
?>