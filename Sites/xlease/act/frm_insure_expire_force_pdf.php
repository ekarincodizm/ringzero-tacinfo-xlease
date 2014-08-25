<?php
include("../config/config.php");

$nowdate = date("Y/m/d");
$mm = pg_escape_string($_GET['mm']);
$yy = pg_escape_string($_GET['yy']);
//$yy_lob = $yy-1;

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
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

$pdf->SetFont('AngsanaNew','B',17);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"กรมธรรม์หมดอายุ");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"เดือน ".$mm." ปี ".$yy);
$pdf->Text(6,26,$gmm);

$pdf->SetXY(5,23); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(194,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"ID");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(60,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขกรมธรรม์");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(100,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่คุ้มครอง");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30);
$buss_name=iconv('UTF-8','windows-874',"วันสิ้นสุด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30);
$buss_name=iconv('UTF-8','windows-874',"Code");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(170,30);
$buss_name=iconv('UTF-8','windows-874',"ค่าเบิ้ย");
$pdf->MultiCell(28,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32);
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);


$pdf->SetFont('AngsanaNew','',10);
$cline = 37;  


$qry_if=pg_query("select * from \"insure\".\"InsureForce\" WHERE EXTRACT(MONTH FROM \"EndDate\")='$mm' AND EXTRACT(YEAR FROM \"EndDate\")='$yy' AND \"Cancel\"='FALSE'
 ORDER BY \"Company\",\"InsFIDNO\" ASC ");
$rows = pg_num_rows($qry_if);
while($res_if=pg_fetch_array($qry_if)){
    $InsFIDNO = $res_if["InsFIDNO"];
    $IDNO = $res_if["IDNO"];
    $InsID = $res_if["InsID"];
    $StartDate = $res_if["StartDate"];
    $EndDate = $res_if["EndDate"];
    $Code = $res_if["Code"];
    $Premium = $res_if["Premium"]; $Premium = round($Premium,2);
    $Company = $res_if["Company"];
    
    $sumall_Premium += $Premium;
    
    $nub += 1;
    if( ($Company != $old_Company) AND $nub!=1 ){
        $pdf->SetFont('AngsanaNew','B',13);
        $pdf->SetXY(140,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sum_company_Premium,2));
        $pdf->MultiCell(58,4,$buss_name,0,'R',0);
        
        $i+=1;
        $cline+=5;

        $sum_company_Premium = 0;
        $sum_company_Premium += $Premium;
        $old_Company = $Company;
    }else{
        $sum_company_Premium += $Premium;
        $old_Company = $Company;
    }
    
    $i+=1;
    if($i > 45){
        $pdf->AddPage();
        $cline = 37;
        $i=1;
        
        $pdf->SetFont('AngsanaNew','B',17);
        $pdf->SetXY(10,10);
        $title=iconv('UTF-8','windows-874',"กรมธรรม์หมดอายุ");
        $pdf->MultiCell(190,4,$title,0,'C',0);

        $pdf->SetFont('AngsanaNew','',13);
        $pdf->SetXY(10,16);
        $buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
        $pdf->MultiCell(190,4,$buss_name,0,'C',0);

        $gmm=iconv('UTF-8','windows-874',"เดือน ".$mm." ปี ".$yy);
        $pdf->Text(6,26,$gmm);

        $pdf->SetXY(5,23); 
        $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
        $pdf->MultiCell(194,4,$buss_name,0,'R',0);

        $pdf->SetXY(4,24); 
        $buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(196,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,30); 
        $buss_name=iconv('UTF-8','windows-874',"ID");
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);

        $pdf->SetXY(30,30); 
        $buss_name=iconv('UTF-8','windows-874',"IDNO");
        $pdf->MultiCell(30,4,$buss_name,0,'C',0);

        $pdf->SetXY(60,30); 
        $buss_name=iconv('UTF-8','windows-874',"เลขกรมธรรม์");
        $pdf->MultiCell(40,4,$buss_name,0,'C',0);

        $pdf->SetXY(100,30);
        $buss_name=iconv('UTF-8','windows-874',"วันที่คุ้มครอง");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(120,30);
        $buss_name=iconv('UTF-8','windows-874',"วันสิ้นสุด");
        $pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(140,30);
        $buss_name=iconv('UTF-8','windows-874',"Code");
        $pdf->MultiCell(30,4,$buss_name,0,'C',0);

        $pdf->SetXY(170,30);
        $buss_name=iconv('UTF-8','windows-874',"ค่าเบิ้ย");
        $pdf->MultiCell(28,4,$buss_name,0,'C',0);

        $pdf->SetXY(4,32);
        $buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(196,4,$buss_name,0,'C',0);
        
    }
    
    $pdf->SetFont('AngsanaNew','',13);
    $pdf->SetXY(5,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"$InsFIDNO");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(30,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"$IDNO");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

    $pdf->SetXY(60,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"$InsID");
    $pdf->MultiCell(40,4,$buss_name,0,'C',0);

    $pdf->SetXY(100,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$StartDate");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);
    
    $pdf->SetXY(120,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$EndDate");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);
    
    $pdf->SetXY(140,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$Code");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

    $pdf->SetXY(170,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($Premium,2));
    $pdf->MultiCell(28,4,$buss_name,0,'R',0);

    $cline+=5;

}

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sum_company_Premium,2));
$pdf->MultiCell(58,4,$buss_name,0,'R',0);
$cline+=5;

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น ".number_format($sumall_Premium,2));
$pdf->MultiCell(58,4,$buss_name,0,'R',0);
$cline+=5;

$pdf->Output();
?>