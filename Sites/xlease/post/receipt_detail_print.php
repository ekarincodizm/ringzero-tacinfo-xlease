<?php
session_start();
include("../config/config.php");

$bank = $_GET['bank'];
$datepicker = $_GET['datepicker'];

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
$title=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานเงินโอน");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ธนาคาร $bank วันที่ $datepicker");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"PostID");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,32);
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อ/สกุล");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(85,32);
$buss_name=iconv('UTF-8','windows-874',"ReceiptNo");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(110,32);
$buss_name=iconv('UTF-8','windows-874',"ประเภท");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(135,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(160,32);
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(185,32);
$buss_name=iconv('UTF-8','windows-874',"ช่องทาง");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//
$pdf->SetFont('AngsanaNew','',12);
$cline = 39;
$query=pg_query("select * from \"vtranpay2\" WHERE (\"bank\"='$bank' AND \"rec_date\"='$datepicker') ORDER BY \"memo\",\"PostID\" ASC");
$num_row = pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $nub+=1;
    $PostID = $resvc['PostID'];
    $IDNO = $resvc['IDNO'];
    $Cusid = $resvc['name'];
    $ReceiptNo = $resvc['ReceiptNo'];
    $Amount = $resvc['amount']; $Amount = round($Amount,2);
    $TypePay = $resvc['TypePay'];
    $memo = $resvc['memo'];
    $Amount_all += $Amount;
	
	$query_namecus = pg_query("SELECT full_name, \"CusID\" FROM \"VSearchCus\" where \"CusID\" = '$Cusid'");
	$result_namecus=pg_fetch_array($query_namecus);
	$name = $result_namecus['full_name'];
    
    $query_tname=pg_query("select \"TName\" from \"TypePay\" WHERE \"TypeID\"='$TypePay';");
    if($resvc_tname=pg_fetch_array($query_tname)){
        $TName = $resvc_tname['TName'];
    }
    
    if($nub!=1){
        if($old_id != $PostID){
            
            $pdf->SetXY(5,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$tmp_PostID");
            $pdf->MultiCell(20,4,$buss_name,0,'C',0);

            $pdf->SetXY(25,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$tmp_IDNO");
            $pdf->MultiCell(30,4,$buss_name,0,'L',0);

            $pdf->SetXY(50,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$tmp_name");
            $pdf->MultiCell(40,4,$buss_name,0,'L',0);

            $pdf->SetXY(85,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$tmp_ReceiptNo");
            $pdf->MultiCell(25,4,$buss_name,0,'C',0);

            $pdf->SetXY(110,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$tmp_TName");
            $pdf->MultiCell(30,4,$buss_name,0,'L',0);

            $pdf->SetXY(135,$cline);
            $buss_name=iconv('UTF-8','windows-874',number_format($tmp_Amount,2));
            $pdf->MultiCell(25,4,$buss_name,0,'R',0);

            $pdf->SetXY(160,$cline);
            $buss_name=iconv('UTF-8','windows-874',number_format($tmp_sum_rows,2));
            $pdf->MultiCell(25,4,$buss_name,0,'R',0);

            $pdf->SetXY(185,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$tmp_memo");
            $pdf->MultiCell(20,4,$buss_name,0,'L',0);
            
            $cline += 5;
        }else{
            
            $pdf->SetXY(5,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$tmp_PostID");
            $pdf->MultiCell(20,4,$buss_name,0,'C',0);

            $pdf->SetXY(25,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$tmp_IDNO");
            $pdf->MultiCell(30,4,$buss_name,0,'L',0);

            $pdf->SetXY(50,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$tmp_name");
            $pdf->MultiCell(40,4,$buss_name,0,'L',0);

            $pdf->SetXY(85,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$tmp_ReceiptNo");
            $pdf->MultiCell(25,4,$buss_name,0,'C',0);

            $pdf->SetXY(110,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$tmp_TName");
            $pdf->MultiCell(30,4,$buss_name,0,'L',0);

            $pdf->SetXY(135,$cline);
            $buss_name=iconv('UTF-8','windows-874',number_format($tmp_Amount,2));
            $pdf->MultiCell(25,4,$buss_name,0,'R',0);

            $pdf->SetXY(185,$cline);
            $buss_name=iconv('UTF-8','windows-874',"$tmp_memo");
            $pdf->MultiCell(20,4,$buss_name,0,'L',0);
            
            $cline += 5;
        }
    }

    if($memo != $old_memo AND $nub!=1){
        
        $pdf->SetXY(110,$cline);
        $buss_name=iconv('UTF-8','windows-874',"ผลรวม $old_memo");
        $pdf->MultiCell(50,4,$buss_name,0,'R',0);

        $pdf->SetXY(160,$cline);
        $buss_name=iconv('UTF-8','windows-874', number_format($Amount_memo,2) );
        $pdf->MultiCell(25,4,$buss_name,0,'R',0);
        
        $cline += 5;
        
        $Amount_memo = 0;
        $Amount_memo += $Amount;
        $old_memo = $memo;
    }else{
        $Amount_memo += $Amount;
        $old_memo = $memo;
    }

    if($old_id != $PostID){
        $old_id = $PostID;
        
        if($nub == 1){
            $sum_rows += $Amount;
        }else{
            $sum_rows = 0;
            $sum_rows += $Amount;
        }
    }else{
        $old_id = $old_id;
        $sum_rows += $Amount;
    }
    
    $tmp_PostID = $PostID;
    $tmp_IDNO = $IDNO;
    $tmp_name = $name;
    $tmp_ReceiptNo = $ReceiptNo;
    $tmp_Amount = $Amount;
    $tmp_TName = $TName;
    $tmp_sum_rows = $sum_rows;
    $tmp_memo = $memo;
}

if($num_row!=0){
    
    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tmp_PostID");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(25,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tmp_IDNO");
    $pdf->MultiCell(30,4,$buss_name,0,'L',0);

    $pdf->SetXY(50,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tmp_name");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);

    $pdf->SetXY(85,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tmp_ReceiptNo");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(110,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tmp_TName");
    $pdf->MultiCell(30,4,$buss_name,0,'L',0);

    $pdf->SetXY(135,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($tmp_Amount,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(160,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($tmp_sum_rows,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(185,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tmp_memo");
    $pdf->MultiCell(20,4,$buss_name,0,'L',0);
    
}

$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(110,$cline+5);
$buss_name=iconv('UTF-8','windows-874',"ผลรวม $old_memo");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);

$pdf->SetXY(160,$cline+5);
$buss_name=iconv('UTF-8','windows-874', number_format($Amount_memo,2) );
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,$cline+6);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(135,$cline+12);
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(160,$cline+12);
$buss_name=iconv('UTF-8','windows-874', number_format($Amount_all,2) );
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->Output();
?>