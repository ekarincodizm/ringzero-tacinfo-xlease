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
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"รายงาน Post Tranpay Today");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่โอน");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(43,32);
$buss_name=iconv('UTF-8','windows-874',"เวลาที่โอน");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(61,32);
$buss_name=iconv('UTF-8','windows-874',"terminal_id");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(81,32);
$buss_name=iconv('UTF-8','windows-874',"ref1");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(96,32);
$buss_name=iconv('UTF-8','windows-874',"ref2");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(111,32);
$buss_name=iconv('UTF-8','windows-874',"ref_name");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(151,32);
$buss_name=iconv('UTF-8','windows-874',"idno");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(181,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 39;
$nub = 0;
$query=pg_query("select * from \"TranPay\" WHERE \"post_on_date\"='$datepicker' ORDER BY \"bank_no\",\"terminal_id\",\"tr_date\",\"tr_time\" ASC");
while($resvc=pg_fetch_array($query)){
    $n++;
    $nub2++;
    $bank_no = $resvc['bank_no'];
    $tr_date = $resvc['tr_date'];
    $tr_time = $resvc['tr_time'];
    $terminal_id = $resvc['terminal_id'];
    $ref1 = $resvc['ref1'];
    $ref2 = $resvc['ref2'];
    $ref_name = $resvc['ref_name'];
    $post_to_idno = $resvc['post_to_idno'];
    $amt = $resvc['amt'];
    
    if(($old_bank != $bank_no) && $n!=1){
        $pdf->SetXY(5,$cline);
        $buss_name=iconv('UTF-8','windows-874',"ธนาคาร $old_bank_name รวม $nub รายการ");
        $pdf->MultiCell(200,4,$buss_name,0,'R',0);
        
        $cline += 5;
        $pdf->SetXY(5,$cline);
        $buss_name=iconv('UTF-8','windows-874',"ธนาคาร $old_bank_name ยอดรวม ".number_format($sum_bank,2)." บาท");
        $pdf->MultiCell(200,4,$buss_name,0,'R',0);
        
        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,$cline);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
        
        $cline += 6;
        $nub = 0;
        $sum_bank = 0;
    }
    
    $bankname = "";
    $query2=pg_query("select \"bankname\" from \"bankofcompany\" WHERE \"bankno\"='$bank_no' ");
    if($resvc2=pg_fetch_array($query2)){
        $bankname = $resvc2['bankname'];
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
        $buss_name=iconv('UTF-8','windows-874',"รายงาน Post Tranpay Today");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
        $pdf->MultiCell(50,4,$buss_name,0,'L',0);

        $pdf->SetFont('AngsanaNew','',12);
        $pdf->SetXY(5,25);
        $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
        $pdf->MultiCell(200,4,$buss_name,0,'R',0);

        $pdf->SetFont('AngsanaNew','',15);
        $pdf->SetXY(5,26);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(25,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่โอน");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(43,32);
		$buss_name=iconv('UTF-8','windows-874',"เวลาที่โอน");
		$pdf->MultiCell(18,4,$buss_name,0,'C',0);

		$pdf->SetXY(61,32);
		$buss_name=iconv('UTF-8','windows-874',"terminal_id");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(81,32);
		$buss_name=iconv('UTF-8','windows-874',"ref1");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(96,32);
		$buss_name=iconv('UTF-8','windows-874',"ref2");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(111,32);
		$buss_name=iconv('UTF-8','windows-874',"ref_name");
		$pdf->MultiCell(40,4,$buss_name,0,'C',0);

		$pdf->SetXY(151,32);
		$buss_name=iconv('UTF-8','windows-874',"idno");
		$pdf->MultiCell(30,4,$buss_name,0,'C',0);

		$pdf->SetXY(181,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    }

    $pdf->SetFont('AngsanaNew','',13);

    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$bankname");
    $pdf->MultiCell(20,4,$buss_name,0,'L',0);

    $pdf->SetXY(25,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tr_date");
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);

    $pdf->SetXY(43,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tr_time");
    $pdf->MultiCell(18,4,$buss_name,0,'C',0);
    
    $pdf->SetXY(61,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$terminal_id");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(81,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$ref1");
    $pdf->MultiCell(18,4,$buss_name,0,'L',0);

    $pdf->SetXY(96,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$ref2");
    $pdf->MultiCell(18,4,$buss_name,0,'L',0);

    $pdf->SetXY(111,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$ref_name");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);

    $pdf->SetXY(151,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$post_to_idno");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

    $pdf->SetXY(181,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($amt,2));
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);
    
$cline += 5;
$nub++;
$old_bank = $bank_no;
$old_bank_name = $bankname;
$sum_bank += $amt;
$sum_all_bank += $amt;
}

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร $old_bank_name รวม $nub รายการ");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);
$cline += 5;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร $old_bank_name ยอดรวม ".number_format($sum_bank,2)." บาท");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);
$cline += 5;

$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดรวมทั้งหมด ".number_format($sum_all_bank,2)." บาท");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->Output();
?>