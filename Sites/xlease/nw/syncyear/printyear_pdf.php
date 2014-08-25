<?php
session_start();
include("../../config/config.php");

$s=mssql_select_db("Thaiace") or die("Can't select database");

$IDNO=$_POST["IDNO"];
$IDNO=explode(".",$IDNO);

$CYEAR1=$_POST["CYEAR1"];
$CYEAR1=explode(".",$CYEAR1);

$CYEAR2=$_POST["CYEAR2"];
$CYEAR2=explode(".",$CYEAR2);

$nowdate = nowDate();

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(190,4,$buss_name,0,'R',0);
 
    }
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(5,10);
$buss_name=iconv('UTF-8','windows-874',"รายงานการแก้ไขปีรถจากระบบเก่ามายังระบบใหม่");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//
$pdf->SetFont('AngsanaNew','',13);
$cline = 32;
$nub = 0;
for($j=0;$j<sizeof($IDNO);$j++){
	if($nub>39){
		$pdf->AddPage(); 
		$cline = 26;
		$nub = 0;
	}
	$pdf->SetXY(60,$cline);
    $buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา  $IDNO[$j] จากเดิม  $CYEAR1[$j] -> $CYEAR2[$j]");
    $pdf->MultiCell(100,6,$buss_name,1,'C',0);
	$cline += 6;
    $nub++;
}

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(100,$cline);
$sizeidno=sizeof($IDNO);
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น  $sizeidno รายการ");
$pdf->MultiCell(100,4,$buss_name,0,'R',0);

$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->Output();

?>