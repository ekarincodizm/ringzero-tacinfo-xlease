<?php
session_start();
include("../config/config.php");

$datepicker = pg_escape_string($_GET['date']);
$nowdate = nowDate();//ดึง วันที่จาก server

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(280,4,$buss_name,0,'R',0);
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
$title=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"สรุปใบเสร็จประจำวัน");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"R_Date");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(20,32);
$buss_name=iconv('UTF-8','windows-874',"R_Receipt");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(40,32);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(70,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อสกุล");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(110,32);
$buss_name=iconv('UTF-8','windows-874',"assetname");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(140,32);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(160,32);
$buss_name=iconv('UTF-8','windows-874',"value");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,32);
$buss_name=iconv('UTF-8','windows-874',"vat");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(200,32);
$buss_name=iconv('UTF-8','windows-874',"money");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(220,32);
$buss_name=iconv('UTF-8','windows-874',"Type");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(240,32);
$buss_name=iconv('UTF-8','windows-874',"PayType");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(260,32);
$buss_name=iconv('UTF-8','windows-874',"R_Bank");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(275,32);
$buss_name=iconv('UTF-8','windows-874',"R_memo");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 39;
$nub=0;
$query=pg_query("select * from \"VFrEachDay\" WHERE \"R_Prndate\"='$datepicker' ORDER BY \"R_Receipt\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $nub+=1;
    $R_Date = $resvc['R_Date'];
    $R_Receipt = $resvc['R_Receipt'];
    $IDNO = $resvc['IDNO'];
    $full_name = $resvc['full_name'];
    $assetname = $resvc['assetname'];
    $regis = $resvc['regis'];
    $value = $resvc['value'];
    $vat = $resvc['vat'];
    $money = $resvc['money'];
    $typepay_name = $resvc['typepay_name'];
    $PayType = $resvc['PayType'];
    $R_Bank = $resvc['R_Bank'];
    $R_memo = $resvc['R_memo']; if(empty($R_memo)){ $R_memo = "-"; }
    
    if($nub == 29){
        $nub = 0;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
        $pdf->MultiCell(290,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,16);
        $buss_name=iconv('UTF-8','windows-874',"สรุปใบเสร็จประจำวัน");
        $pdf->MultiCell(290,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
        $pdf->MultiCell(50,4,$buss_name,0,'L',0);

        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
        $pdf->MultiCell(290,4,$buss_name,0,'R',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(290,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"R_Date");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(20,32);
		$buss_name=iconv('UTF-8','windows-874',"R_Receipt");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(40,32);
		$buss_name=iconv('UTF-8','windows-874',"IDNO");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(70,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อสกุล");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(110,32);
		$buss_name=iconv('UTF-8','windows-874',"assetname");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(140,32);
		$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(160,32);
		$buss_name=iconv('UTF-8','windows-874',"value");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(180,32);
		$buss_name=iconv('UTF-8','windows-874',"vat");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(200,32);
		$buss_name=iconv('UTF-8','windows-874',"money");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(220,32);
		$buss_name=iconv('UTF-8','windows-874',"Type");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(240,32);
		$buss_name=iconv('UTF-8','windows-874',"PayType");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(260,32);
		$buss_name=iconv('UTF-8','windows-874',"R_Bank");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(275,32);
		$buss_name=iconv('UTF-8','windows-874',"R_memo");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(290,4,$buss_name,0,'C',0);
    }


    $pdf->SetFont('AngsanaNew','',13);
    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$R_Date");
    $pdf->MultiCell(18,4,$buss_name,0,'L',0);

    $pdf->SetXY(20,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$R_Receipt");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(40,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$IDNO");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

    $pdf->SetFont('AngsanaNew','',11);
    $pdf->SetXY(70,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$full_name");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);

    $pdf->SetXY(110,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$assetname");
    $pdf->MultiCell(35,4,$buss_name,0,'L',0);

    $pdf->SetFont('AngsanaNew','',13);
    $pdf->SetXY(140,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$regis");
    $pdf->MultiCell(20,4,$buss_name,0,'L',0);

    $pdf->SetXY(160,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($value,2));
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);

    $pdf->SetXY(180,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($vat,2));
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);

    $pdf->SetXY(200,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($money,2));
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);

    $pdf->SetXY(220,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typepay_name");
    $pdf->MultiCell(20,4,$buss_name,0,'L',0);

    $pdf->SetXY(240,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$PayType");
    $pdf->MultiCell(20,4,$buss_name,0,'L',0);

    $pdf->SetXY(260,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$R_Bank");
    $pdf->MultiCell(15,4,$buss_name,0,'L',0);

    $pdf->SetXY(275,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$R_memo");
    $pdf->MultiCell(18,4,$buss_name,0,'L',0);
    
    $cline += 5;
}

//----------------------  VFOtherpayEachDay -----------------------//

$pdf->AddPage();
$nub = 0;
$cline = 39;
$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"สรุปใบเสร็จประจำวัน");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"O_DATE");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(20,32);
$buss_name=iconv('UTF-8','windows-874',"O_RECEIPT");
$pdf->MultiCell(22,4,$buss_name,0,'C',0);

$pdf->SetXY(40,32);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(70,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อสกุล");
$pdf->MultiCell(50,4,$buss_name,0,'C',0);

$pdf->SetXY(120,32);
$buss_name=iconv('UTF-8','windows-874',"assetname");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(160,32);
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,32);
$buss_name=iconv('UTF-8','windows-874',"TName");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(210,32);
$buss_name=iconv('UTF-8','windows-874',"O_MONEY");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(230,32);
$buss_name=iconv('UTF-8','windows-874',"PayType");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(250,32);
$buss_name=iconv('UTF-8','windows-874',"O_BANK");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(270,32);
$buss_name=iconv('UTF-8','windows-874',"O_memo");
$pdf->MultiCell(23,4,$buss_name,0,'C',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

//=========================//
$pdf->SetFont('AngsanaNew','',13);
$query=pg_query("select * from \"VFOtherpayEachDay\" WHERE \"O_PRNDATE\"='$datepicker' ORDER BY \"O_RECEIPT\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $nub+=1;
    $O_DATE = $resvc['O_DATE'];
    $O_RECEIPT = $resvc['O_RECEIPT'];
    $IDNO = $resvc['IDNO'];
    $full_name = $resvc['full_name'];
    $assetname = $resvc['assetname'];
    $regis = $resvc['regis'];
    $TName = $resvc['TName'];
    $O_MONEY = $resvc['O_MONEY'];
    $PayType = $resvc['PayType'];
    $O_BANK = $resvc['O_BANK'];
    $O_memo = $resvc['O_memo']; if(empty($O_memo)){ $O_memo = "-"; }
    
    if($nub == 29){
        $nub = 0;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
        $pdf->MultiCell(290,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,16);
        $buss_name=iconv('UTF-8','windows-874',"สรุปใบเสร็จประจำวัน");
        $pdf->MultiCell(290,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
        $pdf->MultiCell(50,4,$buss_name,0,'L',0);

        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
        $pdf->MultiCell(290,4,$buss_name,0,'R',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(290,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"O_DATE");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(20,32);
		$buss_name=iconv('UTF-8','windows-874',"O_RECEIPT");
		$pdf->MultiCell(22,4,$buss_name,0,'C',0);

		$pdf->SetXY(40,32);
		$buss_name=iconv('UTF-8','windows-874',"IDNO");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(70,32);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อสกุล");
		$pdf->MultiCell(50,4,$buss_name,0,'C',0);

		$pdf->SetXY(120,32);
		$buss_name=iconv('UTF-8','windows-874',"assetname");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(160,32);
		$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(180,32);
		$buss_name=iconv('UTF-8','windows-874',"TName");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(210,32);
		$buss_name=iconv('UTF-8','windows-874',"O_MONEY");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(230,32);
		$buss_name=iconv('UTF-8','windows-874',"PayType");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(250,32);
		$buss_name=iconv('UTF-8','windows-874',"O_BANK");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(270,32);
		$buss_name=iconv('UTF-8','windows-874',"O_memo");
		$pdf->MultiCell(23,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(290,4,$buss_name,0,'C',0);
    }
    
    $pdf->SetFont('AngsanaNew','',13);
    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$O_DATE");
    $pdf->MultiCell(18,4,$buss_name,0,'L',0);

    $pdf->SetXY(20,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$O_RECEIPT");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(40,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$IDNO");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

    $pdf->SetFont('AngsanaNew','',11);
    $pdf->SetXY(70,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$full_name");
    $pdf->MultiCell(50,4,$buss_name,0,'L',0);

    $pdf->SetXY(120,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$assetname");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);

    $pdf->SetFont('AngsanaNew','',13);
    $pdf->SetXY(160,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$regis");
    $pdf->MultiCell(20,4,$buss_name,0,'L',0);

    $pdf->SetXY(180,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$TName");
    $pdf->MultiCell(30,4,$buss_name,0,'L',0);

    $pdf->SetXY(210,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($O_MONEY,2));
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);

    $pdf->SetXY(230,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$PayType");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(250,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$O_BANK");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(270,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$O_memo");
    $pdf->MultiCell(23,4,$buss_name,0,'L',0);
    
    $cline += 5;
}

$pdf->Output();
?>