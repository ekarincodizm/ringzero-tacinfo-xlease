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
$buss_name=iconv('UTF-8','windows-874',"รายงาน Post Cheque Today");
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
$buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(35,32);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(65,32);
$buss_name=iconv('UTF-8','windows-874',"สาขา");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(105,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(140,32);
$buss_name=iconv('UTF-8','windows-874',"ยอดของสัญญา");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(180,32);
$buss_name=iconv('UTF-8','windows-874',"ยอดบนเช็ค");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 39;
$nub = 0;
$query=pg_query("select \"ChequeNo\",\"PostID\",COUNT(\"PostID\") AS cpid from \"VPostChequeToday\" WHERE \"PrnDate\"='$datepicker' GROUP BY \"ChequeNo\",\"PostID\" ORDER BY \"ChequeNo\" ASC");
while($resvc=pg_fetch_array($query)){
    $n++;
    $ChequeNo = $resvc['ChequeNo'];
	$PostID = $resvc['PostID'];
    $cpid = $resvc['cpid'];
    
    $query_detail=pg_query("select * from \"VPostChequeToday\" WHERE \"PostID\"='$PostID' AND \"ChequeNo\"='$ChequeNo' ORDER BY \"IDNO\" ASC");
    while($resvc_detail=pg_fetch_array($query_detail)){
        $BankName = $resvc_detail['BankName'];
        $BankBranch = $resvc_detail['BankBranch'];
        $IDNO = $resvc_detail['IDNO'];
        $CusAmount = $resvc_detail['CusAmount'];
        $AmtOnCheque = $resvc_detail['AmtOnCheque'];
        
        $sum_CusAmount += $CusAmount;
        $chk_last_row++;
        $sum_CusAmount_all += $CusAmount;
    
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
        $buss_name=iconv('UTF-8','windows-874',"รายงาน Post Cheque Today");
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
        $buss_name=iconv('UTF-8','windows-874',"เลขที่เช็ค");
        $pdf->MultiCell(30,4,$buss_name,0,'C',0);

        $pdf->SetXY(35,32);
        $buss_name=iconv('UTF-8','windows-874',"ธนาคาร");
        $pdf->MultiCell(30,4,$buss_name,0,'C',0);

        $pdf->SetXY(65,32);
        $buss_name=iconv('UTF-8','windows-874',"สาขา");
        $pdf->MultiCell(40,4,$buss_name,0,'C',0);

        $pdf->SetXY(105,32);
        $buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
        $pdf->MultiCell(35,4,$buss_name,0,'C',0);

        $pdf->SetXY(140,32);
        $buss_name=iconv('UTF-8','windows-874',"ยอดของสัญญา");
        $pdf->MultiCell(40,4,$buss_name,0,'C',0);

        $pdf->SetXY(180,32);
        $buss_name=iconv('UTF-8','windows-874',"ยอดบนเช็ค");
        $pdf->MultiCell(25,4,$buss_name,0,'C',0);

        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    }

    $pdf->SetFont('AngsanaNew','',13);

    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$ChequeNo");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

    $pdf->SetXY(35,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$BankName");
    $pdf->MultiCell(30,4,$buss_name,0,'L',0);

    $pdf->SetXY(65,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$BankBranch");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);

    $pdf->SetXY(105,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$IDNO");
    $pdf->MultiCell(35,4,$buss_name,0,'L',0);

    $pdf->SetXY(140,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($CusAmount,2));
    $pdf->MultiCell(40,4,$buss_name,0,'R',0);

    if(($chk_last_row == $cpid) ){
        if($sum_CusAmount == $AmtOnCheque){
            $pdf->SetXY(180,$cline);
            $buss_name=iconv('UTF-8','windows-874',number_format($AmtOnCheque,2));
            $pdf->MultiCell(25,4,$buss_name,0,'R',0);
        }else{
            $pdf->SetFont('AngsanaNew','B',13);
            $pdf->SetXY(180,$cline);
            $buss_name=iconv('UTF-8','windows-874',number_format($AmtOnCheque,2));
            $pdf->MultiCell(25,4,$buss_name,0,'R',0);
            $pdf->SetFont('AngsanaNew','',13);
        }
        $sum_CusAmount = 0;
        $chk_last_row = 0;
    }else{
        //ไม่แสดงผล
    }
    
$cline += 5;
$nub++;
$nub2++;
    }
}


$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,$cline-3);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,$cline+3);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเช็คทั้งหมด $n รายการ");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetXY(5,$cline+3);
$buss_name=iconv('UTF-8','windows-874',"ยอดรวมทั้งหมด ".number_format($sum_CusAmount_all,2)." บาท");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->Output();
?>