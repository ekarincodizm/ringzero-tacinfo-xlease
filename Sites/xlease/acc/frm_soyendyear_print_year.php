<?php
include("../config/config.php");

$yy = pg_escape_string($_GET['yy']);
$sort = pg_escape_string($_GET['sort']);
$nowdate = date("Y/m/d");

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];

$show_yy = $yy+543;

$qry_name=pg_query("SELECT \"acclosedate\" FROM account.\"VSOYEndYear\" where \"acclosedate\"='$yy' ORDER BY \"idno\" ASC ");
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
$title=iconv('UTF-8','windows-874',"รายงานแสดงการรับรู้รายได้ตามปีสัญญา ประจำวันที่ $yy");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
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
$buss_name=iconv('UTF-8','windows-874',"ปีสัญญา");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,35); 
$buss_name=iconv('UTF-8','windows-874',"งวด
ที่ค้าง");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(100,35); 
$buss_name=iconv('UTF-8','windows-874',"งวด
ชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(115,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดต้อง
ชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(130,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดใช้
รับรู้รายได้");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(145,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
รับรู้");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(163,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
คงเหลือ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(181,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
ทั้งหมด");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(199,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้
คงเหลือ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(217,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้
สุทธิ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(232,35); 
$buss_name=iconv('UTF-8','windows-874',"มูลค่าลูกหนี้สุทธิ
หลังหักหลักประกัน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(262,35); 
$buss_name=iconv('UTF-8','windows-874',"อัตราสำรอง");
$pdf->MultiCell(16,4,$buss_name,0,'C',0);

$pdf->SetXY(278,35); 
$buss_name=iconv('UTF-8','windows-874',"หนี้สงสัย
จะสูญ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,40); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 47;
$i = 1;
$j = 0;                           

$nub = 0; //------

$t_overdue = 0;

if($sort == "custyear"){
    $qry_name=pg_query("SELECT * FROM account.\"VSOYEndYear\" where \"acclosedate\"='$yy' ORDER BY custyear,idno ASC ");
}elseif($sort == "overdue"){
    $qry_name=pg_query("SELECT * FROM account.\"VSOYEndYear\" where \"acclosedate\"='$yy' ORDER BY overdue DESC,custyear,idno ASC ");
}

$rows = pg_num_rows($qry_name);  
while($res_name=pg_fetch_array($qry_name)){
    $inub+=1;
    $idno= $res_name["idno"];
    $customer_name = $res_name["customer_name"];
    $custyear = $res_name["custyear"];
    $effpay = $res_name["effpay"];
    $overdue = $res_name["overdue"];  
    $paid = $res_name["paid"];
    $mustpay = $res_name["mustpay"];
    $rlthisy = $res_name["rlthisy"];
    $rltothisy = $res_name["rltothisy"];
    $rlremain = $res_name["rlremain"];
    $rlall = $res_name["rlall"];
    
    if($inub==1){
        $t_overdue = $custyear;
        $t_overdue2 = $overdue;
    }
    
    $aroutstanding = $res_name["aroutstanding"];
    $urtotal = $res_name["urtotal"];
    $aroutafterguarantee = $res_name["aroutafterguarantee"];
    $writeoffrate = $res_name["writeoffrate"];
    $backupwriteoff = $res_name["backupwriteoff"];
    
    $sum_rlthisy += $rlthisy;
    $sum_rltothisy += $rltothisy;
    $sum_rlremain += $rlremain;
    $sum_rlall += $rlall;
    $sum_aroutstanding += $aroutstanding;
    $sum_urtotal += ($aroutstanding-$urtotal);
    $sum_aroutafterguarantee += $aroutafterguarantee;
    $sum_backupwriteoff += $backupwriteoff;
    
    if($t_overdue != $custyear AND $sort == "custyear"){
        
        if($i > 26){
    $pdf->AddPage(); 
    $cline = 47; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานแสดงการรับรู้รายได้ตามปีสัญญา ประจำวันที่ $yy");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
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
$buss_name=iconv('UTF-8','windows-874',"ปีสัญญา");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,35); 
$buss_name=iconv('UTF-8','windows-874',"งวด
ที่ค้าง");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(100,35); 
$buss_name=iconv('UTF-8','windows-874',"งวด
ชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(115,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดต้อง
ชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(130,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดใช้
รับรู้รายได้");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(145,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
รับรู้");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(163,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
คงเหลือ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(181,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
ทั้งหมด");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(199,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้
คงเหลือ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(217,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้
สุทธิ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(232,35); 
$buss_name=iconv('UTF-8','windows-874',"มูลค่าลูกหนี้สุทธิ
หลังหักหลักประกัน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(262,35); 
$buss_name=iconv('UTF-8','windows-874',"อัตราสำรอง");
$pdf->MultiCell(16,4,$buss_name,0,'C',0);

$pdf->SetXY(278,35); 
$buss_name=iconv('UTF-8','windows-874',"หนี้สงสัย
จะสูญ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,40); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

}
        
        $pdf->SetFont('AngsanaNew','B',10);

        $pdf->SetXY(5,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"จำนวน $nub_show รายการ | รวมยอดเงิน");
        $pdf->MultiCell(140,4,$buss_name,0,'R',0);

        $pdf->SetXY(145,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlthisy,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(163,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlremain,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(181,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlall,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(199,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_aroutstanding,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(217,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_urtotal,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(242,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_aroutafterguarantee,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);

        $pdf->SetXY(273,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_backupwriteoff,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);
        
        $cline+=5;
        $i++;
        
        $nub_show = 0;
        $t_sum_rlthisy = 0;
        $t_sum_rltothisy = 0;
        $t_sum_rlremain = 0;
        $t_sum_rlall = 0;
        $t_sum_aroutstanding = 0;
        $t_sum_urtotal = 0;
        $t_sum_aroutafterguarantee = 0;
        $t_sum_backupwriteoff = 0;
    }
    
    if($t_overdue2 != $overdue AND $sort == "overdue"){
        
        if($i > 26){
    $pdf->AddPage(); 
    $cline = 47; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานแสดงการรับรู้รายได้ตามปีสัญญา ประจำวันที่ $yy");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
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
$buss_name=iconv('UTF-8','windows-874',"ปีสัญญา");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,35); 
$buss_name=iconv('UTF-8','windows-874',"งวด
ที่ค้าง");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(100,35); 
$buss_name=iconv('UTF-8','windows-874',"งวด
ชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(115,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดต้อง
ชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(130,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดใช้
รับรู้รายได้");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(145,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
รับรู้");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(163,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
คงเหลือ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(181,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
ทั้งหมด");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(199,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้
คงเหลือ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(217,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้
สุทธิ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(232,35); 
$buss_name=iconv('UTF-8','windows-874',"มูลค่าลูกหนี้สุทธิ
หลังหักหลักประกัน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(262,35); 
$buss_name=iconv('UTF-8','windows-874',"อัตราสำรอง");
$pdf->MultiCell(16,4,$buss_name,0,'C',0);

$pdf->SetXY(278,35); 
$buss_name=iconv('UTF-8','windows-874',"หนี้สงสัย
จะสูญ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,40); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

}
        
        $pdf->SetFont('AngsanaNew','B',10);

        $pdf->SetXY(5,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"จำนวน $nub_show รายการ | รวมยอดเงิน");
        $pdf->MultiCell(140,4,$buss_name,0,'R',0);

        $pdf->SetXY(145,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlthisy,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(163,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlremain,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(181,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlall,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(199,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_aroutstanding,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(217,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_urtotal,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(242,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_aroutafterguarantee,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);

        $pdf->SetXY(273,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_backupwriteoff,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);
        
        $cline+=5;
        $i++;
        
        $nub_show = 0;
        $t_sum_rlthisy = 0;
        $t_sum_rltothisy = 0;
        $t_sum_rlremain = 0;
        $t_sum_rlall = 0;
        $t_sum_aroutstanding = 0;
        $t_sum_urtotal = 0;
        $t_sum_aroutafterguarantee = 0;
        $t_sum_backupwriteoff = 0;
    }
    
    $t_sum_rlthisy += $rlthisy;
    $t_sum_rltothisy += $rltothisy;
    $t_sum_rlremain += $rlremain;
    $t_sum_rlall += $rlall;
    $t_sum_aroutstanding += $aroutstanding;
    $t_sum_urtotal += ($aroutstanding-$urtotal);
    $t_sum_aroutafterguarantee += $aroutafterguarantee;
    $t_sum_backupwriteoff += $backupwriteoff;


if($i > 26){
    $pdf->AddPage(); 
    $cline = 47; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานแสดงการรับรู้รายได้ตามปีสัญญา ประจำวันที่ $yy");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
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
$buss_name=iconv('UTF-8','windows-874',"ปีสัญญา");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,35); 
$buss_name=iconv('UTF-8','windows-874',"งวด
ที่ค้าง");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(100,35); 
$buss_name=iconv('UTF-8','windows-874',"งวด
ชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(115,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดต้อง
ชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(130,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดใช้
รับรู้รายได้");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(145,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
รับรู้");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(163,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
คงเหลือ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(181,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
ทั้งหมด");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(199,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้
คงเหลือ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(217,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้
สุทธิ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(232,35); 
$buss_name=iconv('UTF-8','windows-874',"มูลค่าลูกหนี้สุทธิ
หลังหักหลักประกัน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(262,35); 
$buss_name=iconv('UTF-8','windows-874',"อัตราสำรอง");
$pdf->MultiCell(16,4,$buss_name,0,'C',0);

$pdf->SetXY(278,35); 
$buss_name=iconv('UTF-8','windows-874',"หนี้สงสัย
จะสูญ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,40); 
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
$buss_name=iconv('UTF-8','windows-874',$custyear);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,$cline); 
$buss_name=iconv('UTF-8','windows-874',$overdue);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(100,$cline); 
$buss_name=iconv('UTF-8','windows-874',$paid);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(115,$cline); 
$buss_name=iconv('UTF-8','windows-874',$mustpay);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(130,$cline); 
$buss_name=iconv('UTF-8','windows-874',$effpay);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(145,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($rlthisy,2));
$pdf->MultiCell(18,4,$buss_name,0,'R',0);

$pdf->SetXY(163,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($rlremain,2));
$pdf->MultiCell(18,4,$buss_name,0,'R',0);

$pdf->SetXY(181,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($rlall,2));
$pdf->MultiCell(18,4,$buss_name,0,'R',0);

$pdf->SetXY(199,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($aroutstanding,2));
$pdf->MultiCell(18,4,$buss_name,0,'R',0);

$pdf->SetXY(217,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($aroutstanding-$urtotal,2));
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(232,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($aroutafterguarantee,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(262,$cline); 
$buss_name=iconv('UTF-8','windows-874',$writeoffrate);
$pdf->MultiCell(16,4,$buss_name,0,'C',0);

$pdf->SetXY(278,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($backupwriteoff,2));
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$cline+=5; 
$i++;
$nub_show++;
$t_overdue = $custyear;
$t_overdue2 = $overdue;
}

if($i > 26){
    $pdf->AddPage(); 
    $cline = 47; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานแสดงการรับรู้รายได้ตามปีสัญญา ประจำวันที่ $yy");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
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
$buss_name=iconv('UTF-8','windows-874',"ปีสัญญา");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,35); 
$buss_name=iconv('UTF-8','windows-874',"งวด
ที่ค้าง");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(100,35); 
$buss_name=iconv('UTF-8','windows-874',"งวด
ชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(115,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดต้อง
ชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(130,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดใช้
รับรู้รายได้");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(145,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
รับรู้");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(163,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
คงเหลือ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(181,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
ทั้งหมด");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(199,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้
คงเหลือ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(217,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้
สุทธิ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(232,35); 
$buss_name=iconv('UTF-8','windows-874',"มูลค่าลูกหนี้สุทธิ
หลังหักหลักประกัน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(262,35); 
$buss_name=iconv('UTF-8','windows-874',"อัตราสำรอง");
$pdf->MultiCell(16,4,$buss_name,0,'C',0);

$pdf->SetXY(278,35); 
$buss_name=iconv('UTF-8','windows-874',"หนี้สงสัย
จะสูญ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,40); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

}

        $pdf->SetFont('AngsanaNew','B',10);

        $pdf->SetXY(5,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"จำนวน $nub_show รายการ | รวมยอดเงิน");
        $pdf->MultiCell(140,4,$buss_name,0,'R',0);

        $pdf->SetXY(145,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlthisy,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(163,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlremain,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(181,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_rlall,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(199,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_aroutstanding,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(217,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_urtotal,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(242,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_aroutafterguarantee,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);

        $pdf->SetXY(273,$cline); 
        $buss_name=iconv('UTF-8','windows-874',number_format($t_sum_backupwriteoff,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);
        
        $cline+=5;
        $i++;

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,$cline-3); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(4,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);
        
        
if($i > 26){
    $pdf->AddPage(); 
    $cline = 47; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานแสดงการรับรู้รายได้ตามปีสัญญา ประจำวันที่ $yy");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
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
$buss_name=iconv('UTF-8','windows-874',"ปีสัญญา");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,35); 
$buss_name=iconv('UTF-8','windows-874',"งวด
ที่ค้าง");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(100,35); 
$buss_name=iconv('UTF-8','windows-874',"งวด
ชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(115,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดต้อง
ชำระ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(130,35); 
$buss_name=iconv('UTF-8','windows-874',"งวดใช้
รับรู้รายได้");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(145,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
รับรู้");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(163,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
คงเหลือ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(181,35); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล
ทั้งหมด");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(199,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้
คงเหลือ");
$pdf->MultiCell(18,4,$buss_name,0,'C',0);

$pdf->SetXY(217,35); 
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้
สุทธิ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(232,35); 
$buss_name=iconv('UTF-8','windows-874',"มูลค่าลูกหนี้สุทธิ
หลังหักหลักประกัน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(262,35); 
$buss_name=iconv('UTF-8','windows-874',"อัตราสำรอง");
$pdf->MultiCell(16,4,$buss_name,0,'C',0);

$pdf->SetXY(278,35); 
$buss_name=iconv('UTF-8','windows-874',"หนี้สงสัย
จะสูญ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,40); 
$buss_name=iconv('UTF-8','windows-874',"____________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

}
        
$pdf->SetFont('AngsanaNew','B',10);
/*
$pdf->SetXY(5,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $rows รายการ | สรุปยอดสำหรับลูกค้าประจำปี");
$pdf->MultiCell(145,4,$buss_name,0,'R',0);

$pdf->SetXY(145,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_rlthisy,2));
$pdf->MultiCell(18,4,$buss_name,0,'R',0);

$pdf->SetXY(163,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_rlremain,2));
$pdf->MultiCell(18,4,$buss_name,0,'R',0);

$pdf->SetXY(181,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_rlall,2));
$pdf->MultiCell(18,4,$buss_name,0,'R',0);

$pdf->SetXY(199,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutstanding,2));
$pdf->MultiCell(18,4,$buss_name,0,'R',0);

$pdf->SetXY(217,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_urtotal,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(232,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutafterguarantee,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(278,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum_backupwriteoff,2));
$pdf->MultiCell(15,4,$buss_name,0,'R',0);
*/

        $pdf->SetXY(5,$cline+2); 
        $buss_name=iconv('UTF-8','windows-874',"จำนวน $nub_show รายการ | รวมยอดเงิน");
        $pdf->MultiCell(140,4,$buss_name,0,'R',0);

        $pdf->SetXY(145,$cline+2); 
        $buss_name=iconv('UTF-8','windows-874',number_format($sum_rlthisy,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(163,$cline+2); 
        $buss_name=iconv('UTF-8','windows-874',number_format($sum_rlremain,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(181,$cline+2); 
        $buss_name=iconv('UTF-8','windows-874',number_format($sum_rlall,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(199,$cline+2); 
        $buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutstanding,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);

        $pdf->SetXY(217,$cline+2); 
        $buss_name=iconv('UTF-8','windows-874',number_format($sum_urtotal,2));
        $pdf->MultiCell(18,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(242,$cline+2); 
        $buss_name=iconv('UTF-8','windows-874',number_format($sum_aroutafterguarantee,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);

        $pdf->SetXY(273,$cline+2); 
        $buss_name=iconv('UTF-8','windows-874',number_format($sum_backupwriteoff,2));
        $pdf->MultiCell(20,4,$buss_name,0,'R',0);


$pdf->Output();
?>