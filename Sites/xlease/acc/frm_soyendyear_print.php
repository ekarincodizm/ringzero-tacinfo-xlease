<?php
include("../config/config.php");

$mm = pg_escape_string($_GET['mm']);
$yy = pg_escape_string($_GET['yy']);
$nowdate = date("Y/m/d");

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];

$show_yy = $yy+543;

$qry_name=pg_query("SELECT \"acclosedate\" FROM account.\"VSOYEndYear\" where EXTRACT(YEAR FROM \"acclosedate\")='$yy' ORDER BY \"idno\" ASC ");
if($res_name=pg_fetch_array($qry_name)){
    $acclosedate = $res_name["acclosedate"];
}

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
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

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานแสดงการรับรู้รายได้ตามงวดค้าง ประจำปี $show_yy");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(282,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"รายการที่เกิดขึ้น ณ วันที่ ".$acclosedate);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,35); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,35); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(45,4,$buss_name,0,'C',0);

$pdf->SetXY(70,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดที่ค้าง");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(100,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดต้องชำระ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(118,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดใช้รับรู้รายได้");
$pdf->MultiCell(22,4,$buss_name,0,'C',0);

$pdf->SetXY(140,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผลรับรู้");
$pdf->MultiCell(17,4,$buss_name,0,'C',0);

$pdf->SetXY(157,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผลคงเหลือ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(177,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผลทั้งหมด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(197,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้คงเหลือ");
$pdf->MultiCell(19,4,$buss_name,0,'C',0);

$pdf->SetXY(216,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้สุทธิ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(231,31); 
$buss_name=iconv('UTF-8','windows-874',"มูลค่าลูกหนี้สุทธิ
หลังหักหลักประกัน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(256,35); 
$buss_name=iconv('UTF-8','windows-874',"อัตราสำรอง");
$pdf->MultiCell(16,4,$buss_name,0,'C',0);

$pdf->SetXY(272,35); 
$buss_name=iconv('UTF-8','windows-874',"หนี้สงสัยจะสูญ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,37); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 45;
$i = 1;
$j = 0;                           

$nub = 0; //------

$t_overdue = 0;
$qry_name=pg_query("SELECT * FROM account.\"VSOYEndYear\" where EXTRACT(YEAR FROM \"acclosedate\")='$yy' ORDER BY overdue,\"idno\" ASC ");
$rows = pg_num_rows($qry_name);  
while($res_name=pg_fetch_array($qry_name)){
    $idno= $res_name["idno"];
    $customer_name = $res_name["customer_name"];
    $effpay = $res_name["effpay"];
    $overdue = $res_name["overdue"];  
    $paid = $res_name["paid"];
    $mustpay = $res_name["mustpay"];
    $rlthisy = $res_name["rlthisy"];
    $rltothisy = $res_name["rltothisy"];
    $rlremain = $res_name["rlremain"];
    $rlall = $res_name["rlall"];
    $urtotal = $res_name["urtotal"];
    
    $aroutstanding = $res_name["aroutstanding"];
    $aroutafterguarantee = $res_name["aroutafterguarantee"];
    $writeoffrate = $res_name["writeoffrate"];
    $backupwriteoff = $res_name["backupwriteoff"];
    
    $sum_rlthisy += $rlthisy;
    $sum_rlremain += $rlremain;
    $sum_rlall += $rlall;
    $sum_aroutstanding += $aroutstanding;
    $sum_urtotal += $aroutstanding-$urtotal;
    $sum_aroutafterguarantee += $aroutafterguarantee;
    $sum_backupwriteoff += $backupwriteoff;
    
    if($t_overdue != $overdue){
        $pdf->SetFont('AngsanaNew','B',10);

        $pdf->SetXY(5,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"รวม");
        $pdf->MultiCell(130,4,$buss_name,0,'R',0);

        $pdf->SetXY(140,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlthisy,2));
        $pdf->MultiCell(17,4,$buss_name,0,'R',0);

        $pdf->SetXY(157,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlremain,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);

        $pdf->SetXY(177,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlall,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);

        $pdf->SetXY(197,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_aroutstanding,2));
        $pdf->MultiCell(19,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(216,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_urtotal,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);

        $pdf->SetXY(231,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_aroutafterguarantee,2));
        $pdf->MultiCell(25,4,$buss_name,0,'R',0);

        $pdf->SetXY(272,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_backupwriteoff,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);
        
        $cline+=5; 
        
        $t_sum_rlthisy = 0;
        $t_sum_rlremain = 0;
        $t_sum_rlall = 0;
        $t_sum_aroutstanding = 0;
        $t_sum_urtotal = 0;
        $t_sum_aroutafterguarantee = 0;
        $t_sum_backupwriteoff = 0;
    }

    $t_sum_rlthisy += $rlthisy;
    $t_sum_rlremain += $rlremain;
    $t_sum_rlall += $rlall;
    $t_sum_aroutstanding += $aroutstanding;
    $t_sum_urtotal += $aroutstanding-$urtotal;
    $t_sum_aroutafterguarantee += $aroutafterguarantee;
    $t_sum_backupwriteoff += $backupwriteoff;


if($i > 26){ 
    $pdf->AddPage();
    $cline = 41; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานแสดงการรับรู้รายได้ตามงวดค้าง ประจำปี $show_yy");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(282,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"รายการที่เกิดขึ้น ณ วันที่ ".$acclosedate);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,35); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,35); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(45,4,$buss_name,0,'C',0);

$pdf->SetXY(70,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดที่ค้าง");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(100,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดต้องชำระ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(118,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดใช้รับรู้รายได้");
$pdf->MultiCell(22,4,$buss_name,0,'C',0);

$pdf->SetXY(140,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผลรับรู้");
$pdf->MultiCell(17,4,$buss_name,0,'C',0);

$pdf->SetXY(157,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผลคงเหลือ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(177,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผลทั้งหมด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(197,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้คงเหลือ");
$pdf->MultiCell(19,4,$buss_name,0,'C',0);

$pdf->SetXY(216,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้สุทธิ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(231,31); 
$buss_name=iconv('UTF-8','windows-874',"มูลค่าลูกหนี้สุทธิ
หลังหักหลักประกัน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(256,35); 
$buss_name=iconv('UTF-8','windows-874',"อัตราสำรอง");
$pdf->MultiCell(16,4,$buss_name,0,'C',0);

$pdf->SetXY(272,35); 
$buss_name=iconv('UTF-8','windows-874',"หนี้สงสัยจะสูญ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,37); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10);


if($nub<1){
    $pdf->SetXY(5,$cline-3); 
    $buss_name=iconv('UTF-8','windows-874',"ลูกค้าประจำปี ".$show_yy);
    $pdf->MultiCell(30,4,$buss_name,0,'L',0);
    $nub+=1;
}

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$idno);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',$customer_name);
$pdf->MultiCell(45,4,$buss_name,0,'L',0);

$pdf->SetXY(70,$cline); 
$buss_name=iconv('UTF-8','windows-874',$overdue);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,$cline); 
$buss_name=iconv('UTF-8','windows-874',$paid);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(100,$cline); 
$buss_name=iconv('UTF-8','windows-874',$mustpay);
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(118,$cline); 
$buss_name=iconv('UTF-8','windows-874',$effpay);
$pdf->MultiCell(22,4,$buss_name,0,'C',0);

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($rlthisy,2));
$pdf->MultiCell(17,4,$buss_name,0,'R',0);

$pdf->SetXY(157,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($rlremain,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(177,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($rlall,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(197,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($aroutstanding,2));
$pdf->MultiCell(19,4,$buss_name,0,'R',0);

$pdf->SetXY(216,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($aroutstanding-$urtotal,2));
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(231,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($aroutafterguarantee,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(256,$cline); 
$buss_name=iconv('UTF-8','windows-874',$writeoffrate);
$pdf->MultiCell(16,4,$buss_name,0,'C',0);

$pdf->SetXY(272,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($backupwriteoff,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$cline+=5; 
$i+=1; 
$t_overdue = $overdue;
}  


        $pdf->SetFont('AngsanaNew','B',10);
        
        $pdf->SetXY(5,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"รวม");
        $pdf->MultiCell(130,4,$buss_name,0,'R',0);

        $pdf->SetXY(140,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlthisy,2));
        $pdf->MultiCell(17,4,$buss_name,0,'R',0);

        $pdf->SetXY(157,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlremain,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);

        $pdf->SetXY(177,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlall,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);

        $pdf->SetXY(197,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_aroutstanding,2));
        $pdf->MultiCell(19,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(216,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_urtotal,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);

        $pdf->SetXY(231,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_aroutafterguarantee,2));
        $pdf->MultiCell(25,4,$buss_name,0,'R',0);

        $pdf->SetXY(272,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_backupwriteoff,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);
        
        $cline+=5;

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,$cline-3); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(4,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);
        
$pdf->SetFont('AngsanaNew','B',10);

$pdf->SetXY(5,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"สรุปยอดสำหรับลูกค้าประจำปี");
$pdf->MultiCell(130,4,$buss_name,0,'R',0);

$pdf->SetXY(140,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_rlthisy,2));
$pdf->MultiCell(17,4,$buss_name,0,'R',0);

$pdf->SetXY(157,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_rlremain,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(177,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_rlall,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(197,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutstanding,2));
$pdf->MultiCell(19,4,$buss_name,0,'R',0);

$pdf->SetXY(216,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_urtotal,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(231,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutafterguarantee,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(272,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_backupwriteoff,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->Output();
?>