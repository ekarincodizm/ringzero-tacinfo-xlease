<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');

$cusname = $_POST["cus"];
$nowdate= nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

//------------------- PDF -------------------//
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
$title=iconv('UTF-8','windows-874',"แสดงรายชื่อลูกค้าที่ซ้ำใน PostgreSQL");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetXY(155,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);

/*Header of Table*/
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(55,35);
$buss_name=iconv('UTF-8','windows-874',"ลำดับที่");
$pdf->MultiCell(20,6,$buss_name,1,'C',0);

$pdf->SetXY(75,35);
$buss_name=iconv('UTF-8','windows-874',"รหัสลูกค้า");
$pdf->MultiCell(20,6,$buss_name,1,'C',0);

$pdf->SetXY(95,35);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ - นามสกุลลูกค้า");
$pdf->MultiCell(60,6,$buss_name,1,'C',0);

$cline = 41;
$nub=0;
$j=1;

foreach($cusname as $key => $value){
	$qryname=pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\"='$value'");
	list($fullname)=pg_fetch_array($qryname);
	
	if($nub > 46){
		$nub = 0;
		$cline = 41;
		$pdf->AddPage();
		$pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(10,10);
		$title=iconv('UTF-8','windows-874',"แสดงรายชื่อลูกค้าที่ซ้ำใน PostgreSQL");
		$pdf->MultiCell(190,4,$title,0,'C',0);

		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','B',13);
		$pdf->SetXY(6,25);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(150,4,$buss_name,0,'L',0);

		$pdf->SetXY(155,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
		$pdf->MultiCell(50,4,$buss_name,0,'R',0);

		/*Header of Table*/
		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(55,35);
		$buss_name=iconv('UTF-8','windows-874',"ลำดับที่");
		$pdf->MultiCell(20,6,$buss_name,1,'C',0);

		$pdf->SetXY(75,35);
		$buss_name=iconv('UTF-8','windows-874',"รหัสลูกค้า");
		$pdf->MultiCell(20,6,$buss_name,1,'C',0);

		$pdf->SetXY(95,35);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อ - นามสกุลลูกค้า");
		$pdf->MultiCell(60,6,$buss_name,1,'C',0);
	}
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(55,$cline);
	$buss_name=iconv('UTF-8','windows-874',$j);
	$pdf->MultiCell(20,5,$buss_name,1,'C',0);
		
	$pdf->SetXY(75,$cline);
	$buss_name=iconv('UTF-8','windows-874',$value);
	$pdf->MultiCell(20,5,$buss_name,1,'C',0);

	$pdf->SetXY(95,$cline);
	$buss_name=iconv('UTF-8','windows-874',$fullname);
	$pdf->MultiCell(60,5,$buss_name,1,'L',0);
	
	$cline+=5;
	$j++;
	$nub++;
}

$pdf->Output();
?>