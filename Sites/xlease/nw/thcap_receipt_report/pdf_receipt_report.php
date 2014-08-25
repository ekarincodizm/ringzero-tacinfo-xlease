<?php
session_start();
include("../../config/config.php");

$datepicker = $_GET['date'];
$nowdate = nowDate();
$type_date = $_GET['type_date']; // check เช็คว่าเลือกจากวันที่ทำรายการหรือวันที่อนุมัติ
if($type_date == 1)
{
	$text_date = "โดยเลือกจากวันที่ทำรายการ";
	$view_date = "where \"doerStamp\" = '$datepicker'";
}
if($type_date == 2)
{
	$text_date = "โดยเลือกจากวันที่ออกใบเสร็จ";
	$view_date = "where \"receiveDate\" = '$datepicker'";
}


//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(290,4,$buss_name,0,'R',0);
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
//$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานใบเสร็จประจำวัน");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,21);
$buss_name=iconv('UTF-8','windows-874',"($text_date)");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(10,33);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่ออกใบเสร็จ");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(50,33);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(80,33);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(110,33);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ-นามสกุลลูกค้า");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(155,33);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินใบเสร็จ");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 40;
$nub = 1;
$a=0;

$query=pg_query("select * from account.\"V_thcap_receipt_report\" $view_date order by \"doerID\" ");
						
$num_row = pg_num_rows($query);					

$sum_receiveAmt = 0;

while($resvc=pg_fetch_array($query))
{
	$doerID = $resvc['doerID'];
    $receiveDate = $resvc['receiveDate'];
    $receiptID = $resvc['receiptID'];
    $contractID = $resvc['contractID'];
    $cusFullName = $resvc['cusFullName'];
    $receiveAmt = $resvc['receiveAmt'];
	
	//------หาชื่อผู้ทำรายการ
	$query_name=pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
	while($resvc_name=pg_fetch_array($query_name))
	{
		$fullname = $resvc_name['fullname'];
	}
	//----- จบการหาชื่อผู้ทำรายการ
	
	$sum_receiveAmt += $receiveAmt;
    
	/*
	if($receiveDate =="")
	{
		$receiveDate="-";
	}
	else
	{
		$receiveDate=substr($receiveDate,0,10);
	}
	*/
	
    if($nub == 46){
        $nub = 1;
        $cline = 40;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		//$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
		$title=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"รายงานตั้งหนี้ประจำวัน");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,21);
		$buss_name=iconv('UTF-8','windows-874',"($text_date $text_view)");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
		$pdf->MultiCell(50,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetXY(10,33);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาที่ออกใบเสร็จ");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(50,33);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(80,33);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(110,33);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อ-นามสกุลลูกค้า");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(155,33);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินใบเสร็จ");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
    }
	

$pdf->SetFont('AngsanaNew','',12);

//------- เช็ีคว่าใช่คนเดิมหรือไม่
	$checkIDone = $doerID;
	if($a==0)
	{
		$checkIDtwo = $checkIDone;
		
		$pdf->SetFont('AngsanaNew','B',13);
		$pdf->SetXY(7,$cline);
		$buss_name=iconv('UTF-8','windows-874',"ผู้ทำรายการ : $fullname ($checkIDone)");
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);
	
		$cline = $cline +5;
		$nub++;
	}
	else
	{
		if($checkIDone != $checkIDtwo)
		{			
					
			$pdf->SetFont('AngsanaNew','B',13);
			$pdf->SetXY(130,$cline);
			$buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sumone,2));
			$pdf->MultiCell(60,4,$buss_name,0,'R',0);
			
			$pdf->SetXY(5,$cline+1);
			$pdf->SetFont('AngsanaNew','',14);
			$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
			$pdf->MultiCell(200,4,$buss_name,0,'C',0);
		
			$cline = $cline +7;
		
			$pdf->SetFont('AngsanaNew','B',13);
			$pdf->SetXY(7,$cline);
			$buss_name=iconv('UTF-8','windows-874',"ผู้ทำรายการ : $fullname ($checkIDone)");
			$pdf->MultiCell(200,4,$buss_name,0,'L',0);
	
			$cline = $cline +5;
			$nub++;
		
			$checkIDtwo = $checkIDone;
			$sumone = 0;
		}
	}
//------- จบการเช็คคนเดิม

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"$receiveDate");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(50,$cline);
$buss_name=iconv('UTF-8','windows-874',"$receiptID");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(80,$cline);
$buss_name=iconv('UTF-8','windows-874',"$contractID");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(115,$cline);
$buss_name=iconv('UTF-8','windows-874',"$cusFullName");
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($receiveAmt,2));
$pdf->MultiCell(40,4,$buss_name,0,'R',0);
    
    $cline += 5;
    $nub+=1;
	$sumone += $receiveAmt;
	$a += 1;
	
	if($a == $num_row)
	{
		$pdf->SetFont('AngsanaNew','B',13);
		$pdf->SetXY(130,$cline);
		$buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sumone,2));
		$pdf->MultiCell(60,4,$buss_name,0,'R',0);
		
		$pdf->SetXY(5,$cline+1);
		$pdf->SetFont('AngsanaNew','',14);
		$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
	
		$cline = $cline +7;
		$a++;
		$nub++;
	}
	
}


if($num_row > 0){
    $pdf->SetFont('AngsanaNew','B',13);
    $pdf->SetXY(130,$cline);
    $buss_name=iconv('UTF-8','windows-874',"รวมเงินทั้งหมด ".number_format($sum_receiveAmt,2));
    $pdf->MultiCell(60,4,$buss_name,0,'R',0);

    $pdf->SetXY(5,$cline+1);
    $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
    $cline += 6;
    $nub+=1;
}

$pdf->Output();
?>