<?php
session_start();
include("../config/config.php");

$mm = $_GET['mm'];
$yy = $_GET['yy'];
$nowdate = Date('Y-m-d');

$result=pg_query("select account.\"CheckVatInMonth\"('$mm','$yy')");
$return_data=pg_fetch_result($result,0);

$rt=explode(",",$return_data);

$rs = str_replace("(","",$rt[0]);
$rs = str_replace('"',"",$rs);

$rend = str_replace(")","",$rt[7]);
$rend = str_replace('"',"",$rend);

$rend = nl2br($rend);
$rend = str_replace("<br />","
",$rend);

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(195,4,$buss_name,0,'R',0);
 
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
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"ตรวจสอบการส่ง VAT");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,20);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"Result : $rs");
$pdf->MultiCell(198,7,$buss_name,0,'L',0);

$pdf->SetXY(6,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนลูกค้าทั้งหมด : $rt[1] ราย");
$pdf->MultiCell(198,7,$buss_name,0,'L',0);

$pdf->SetXY(6,39);
$buss_name=iconv('UTF-8','windows-874',"จำนวนลูกค้าที่มีงวดแรกในเดือนหน้า : $rt[2] ราย");
$pdf->MultiCell(198,7,$buss_name,0,'L',0);

$pdf->SetXY(6,46);
$buss_name=iconv('UTF-8','windows-874',"จำนวนลูกค้าที่จ่ายล้วงหน้า : $rt[4] ราย");
$pdf->MultiCell(198,7,$buss_name,0,'L',0);

$pdf->SetXY(6,53);
$buss_name=iconv('UTF-8','windows-874',"จำนวนลูกค้าที่ซื้อสด : $rt[5] ราย");
$pdf->MultiCell(198,7,$buss_name,0,'L',0);

$pdf->SetXY(6,60);
$buss_name=iconv('UTF-8','windows-874',"จำนวนข้อมูลที่ผิดผลาด : $rt[6] รายการ");
$pdf->MultiCell(198,7,$buss_name,0,'L',0);

$pdf->SetXY(5,63);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(6,68);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดข้อมูลที่ผิดผลาด");
$pdf->MultiCell(198,7,$buss_name,0,'L',0);

$pdf->SetXY(6,68);
$buss_name=iconv('UTF-8','windows-874',"$rend");
$pdf->MultiCell(198,7,$buss_name,0,'L',0);

$pdf->Output();
?>