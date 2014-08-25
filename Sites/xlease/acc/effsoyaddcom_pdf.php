<?php
session_start();
include("../config/config.php");

$datepicker = pg_escape_string($_GET['datepicker']);
$yy = pg_escape_string($_GET['yy']);
$nowdate = nowDate();//ดึง วันที่จาก server

list($datepicker_y,$datepicker_m,$datepicker_d) = split('-',$datepicker);

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,10); 
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
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"ต้นทุนเริ่มแรก ลูกหนี้ประจำปี $yy");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,22);
$buss_name=iconv('UTF-8','windows-874',"ณ สิ้นปี $datepicker_y");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
$pdf->MultiCell(75,4,$buss_name,0,'C',0);

$pdf->SetXY(100,32);
$buss_name=iconv('UTF-8','windows-874',"สะสมปีก่อน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(125,32);
$buss_name=iconv('UTF-8','windows-874',"ตัดจ่ายปีนี้");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(150,32);
$buss_name=iconv('UTF-8','windows-874',"รอตัดยกไป");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(175,32);
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด");
$pdf->MultiCell(28,4,$buss_name,0,'C',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 39;

$nub = 0;
$query=pg_query("SELECT \"idno\",\"comlastyear\",\"comaccthisyear\",\"comnextyear\" FROM \"account\".\"effsoyaddcom\" WHERE \"acclosedate\"='$datepicker' AND \"custyear\"='$yy' 
AND (\"comlastyear\" <> '0' OR \"comaccthisyear\" <> '0' OR \"comnextyear\" <> '0') 
ORDER BY \"idno\" ASC ");
//$query=pg_query("SELECT \"idno\",\"comlastyear\",\"comaccthisyear\",\"comnextyear\" FROM \"account\".\"effsoyaddcom\" WHERE \"acclosedate\"='$datepicker' AND \"custyear\"='$yy' ORDER BY \"idno\" ASC ");
while($resvc=pg_fetch_array($query)){
    $nub++;
    $nub2++;
    $sum = 0;
    $idno = $resvc['idno'];
    $comlastyear = $resvc['comlastyear'];
    $comaccthisyear = $resvc['comaccthisyear'];
    $comnextyear = $resvc['comnextyear'];
    $sum = $comlastyear+($comaccthisyear-$comlastyear)+$comnextyear;
    
    $sum_comlastyear += $comlastyear;
    $sum_comaccthisyear += ($comaccthisyear-$comlastyear);
    $sum_comnextyear += $comnextyear;
    $sum_sum += $sum;
    
    $full_name = "";
    $query1=pg_query("SELECT \"full_name\" FROM \"VContact\" WHERE \"IDNO\"='$idno'");
    if($resvc1=pg_fetch_array($query1)){
        $full_name = $resvc1['full_name'];
    }


    if($nub2 == 46){
        $nub2 = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
        $pdf->SetXY(5,10);
        $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
        $pdf->MultiCell(200,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,16);
        $buss_name=iconv('UTF-8','windows-874',"ต้นทุนเริ่มแรก ลูกหนี้ประจำปี $yy");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,22);
        $buss_name=iconv('UTF-8','windows-874',"ณ สิ้นปี $datepicker_y");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
        $pdf->MultiCell(200,4,$buss_name,0,'R',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,32);
        $buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(25,32);
        $buss_name=iconv('UTF-8','windows-874',"ชื่อลูกค้า");
        $pdf->MultiCell(75,4,$buss_name,0,'C',0);

        $pdf->SetXY(100,32);
        $buss_name=iconv('UTF-8','windows-874',"สะสมปีก่อน");
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);

        $pdf->SetXY(125,32);
        $buss_name=iconv('UTF-8','windows-874',"ตัดจ่ายปีนี้");
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);

        $pdf->SetXY(150,32);
        $buss_name=iconv('UTF-8','windows-874',"รอตัดยกไป");
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);

        $pdf->SetXY(175,32);
        $buss_name=iconv('UTF-8','windows-874',"ทั้งหมด");
        $pdf->MultiCell(28,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    }

    $pdf->SetFont('AngsanaNew','',13);
    
    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$idno");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(25,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$full_name");
    $pdf->MultiCell(75,4,$buss_name,0,'L',0);

    $pdf->SetXY(100,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($comlastyear,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(125,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($comaccthisyear-$comlastyear,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(150,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($comnextyear,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(175,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($sum,2));
    $pdf->MultiCell(28,4,$buss_name,0,'R',0);

    $cline+=5;
}


$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,$cline-3);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);
$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(5,$cline+3);
$buss_name=iconv('UTF-8','windows-874',"จำนวน $nub รายการ");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetXY(35,$cline+3);
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
$pdf->MultiCell(65,4,$buss_name,0,'R',0);

$pdf->SetXY(100,$cline+3);
$buss_name=iconv('UTF-8','windows-874',number_format($sum_comlastyear,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(125,$cline+3);
$buss_name=iconv('UTF-8','windows-874',number_format($sum_comaccthisyear,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(150,$cline+3);
$buss_name=iconv('UTF-8','windows-874',number_format($sum_comnextyear,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(175,$cline+3);
$buss_name=iconv('UTF-8','windows-874',number_format($sum_sum,2));
$pdf->MultiCell(28,4,$buss_name,0,'R',0);

$pdf->Output();
?>