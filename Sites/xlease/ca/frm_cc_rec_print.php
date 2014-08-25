<?php
include("../config/config.php");

$id = pg_escape_string($_GET['id']);
$nowdate = date("Y-m-d");

$qry_cc=pg_query("select * from \"CancelReceipt\" WHERE c_receipt='$id' ");
$numrow_cc=pg_num_rows($qry_cc);
if($res_cc=pg_fetch_array($qry_cc)){
    $IDNO = $res_cc["IDNO"];
    $c_date = $res_cc["c_date"];
    $c_money = $res_cc["c_money"];
    $ref_prndate = $res_cc["ref_prndate"];
    $ref_recdate = $res_cc["ref_recdate"];
    $ref_receipt = $res_cc["ref_receipt"];
    $paytypefrom = $res_cc["paytypefrom"];
    $c_memo = $res_cc["c_memo"];
}

$qry_cc=pg_query("select \"full_name\" from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
if($res_cc=pg_fetch_array($qry_cc)){
    $full_name = $res_cc["full_name"];
}

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        //$buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        //$this->MultiCell(190,4,$buss_name,0,'R',0);
 
    }
 
}


$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',20);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายละเอียดการจ่ายเป็นเงินสด");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',18);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"จ่ายให้ $full_name");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(149,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"รหัสยกเลิกใบเสร็จ : $id");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(105,30);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา : $IDNO");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(5,35);
$buss_name=iconv('UTF-8','windows-874',"Ref_Printdate : $ref_prndate");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(105,35);
$buss_name=iconv('UTF-8','windows-874',"วันที่ยกเลิก : $c_date");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(5,40);
$buss_name=iconv('UTF-8','windows-874',"Ref_RecDate : $ref_recdate");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(105,40);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน : ".number_format($c_money,2)." บาท.");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(5,45);
$buss_name=iconv('UTF-8','windows-874',"Ref_Receipt : $ref_receipt");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(105,45);
$buss_name=iconv('UTF-8','windows-874',"PayType : $paytypefrom");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(4,46); 
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,51);
$buss_name=iconv('UTF-8','windows-874',"หมายเหตุ : $c_memo");
$pdf->MultiCell(195,4,$buss_name,0,'L',0);

$pdf->SetXY(25,65);
$buss_name=iconv('UTF-8','windows-874',"ผู้อนุมัติ ____________________________");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(125,65);
$buss_name=iconv('UTF-8','windows-874',"ผู้รับเงิน ____________________________");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(25,72);
$buss_name=iconv('UTF-8','windows-874',"             (____________________________)");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->SetXY(125,72);
$buss_name=iconv('UTF-8','windows-874',"             (____________________________)");
$pdf->MultiCell(80,4,$buss_name,0,'L',0);

$pdf->Output();
?>