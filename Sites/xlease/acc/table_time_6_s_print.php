<?php
include("../config/config.php");

$nowdate = date("Y/m/d");
$yy = pg_escape_string($_GET['yy']);

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,22); 
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
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"ตารางลูกหนี้ตามระยะเวลาครบกำหนดชำระ (สาขาจรัญสนิทวงศ์)");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(10,22);
$buss_name=iconv('UTF-8','windows-874',"ณ วันที่ 31 ธันวาคม ".$yy);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,28); 
$buss_name=iconv('UTF-8','windows-874',"
 ");
$pdf->MultiCell(55,4,$buss_name,1,'C',0);

    $pdf->SetXY(5,30); 
    $buss_name=iconv('UTF-8','windows-874',"Name");
    $pdf->MultiCell(55,4,$buss_name,0,'C',0);

$pdf->SetXY(60,28); 
$buss_name=iconv('UTF-8','windows-874',"Realized");
$pdf->MultiCell(139,4,$buss_name,1,'C',0);

    $pdf->SetXY(60,32);
    $buss_name=iconv('UTF-8','windows-874',"Previous Year");
    $pdf->MultiCell(23,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(83,32);
    $buss_name=iconv('UTF-8','windows-874',"ต้องชำระ");
    $pdf->MultiCell(23,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(106,32);
    $buss_name=iconv('UTF-8','windows-874',"รับรู้ไม่เกิน3");
    $pdf->MultiCell(23,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(129,32);
    $buss_name=iconv('UTF-8','windows-874',"Next Year");
    $pdf->MultiCell(23,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(152,32);
    $buss_name=iconv('UTF-8','windows-874',"ดอกผลทั้งหมด");
    $pdf->MultiCell(23,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(175,32);
    $buss_name=iconv('UTF-8','windows-874',"รับรู้ปีนี้");
    $pdf->MultiCell(24,4,$buss_name,1,'C',0);


$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;

$j=0;
$t_custyear = 0;
$select_date = $yy."-12-31";
$qry_name=pg_query("SELECT * FROM account.\"effsoyaddcom\" where \"acclosedate\"='$select_date' ORDER BY overdue,custyear ASC ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $j+=1;
    $idno = $res_name["idno"];
    $cusid = $res_name["cusid"];
    $custyear = $res_name["custyear"];
    $paid = $res_name["paid"];
    $overdue = $res_name["overdue"];
    $nextydue = $res_name["nextydue"];
    $otherydue = $res_name["otherydue"];
    $totaldue = $res_name["totaldue"];
    $monthly = $res_name["monthly"];
    $aroverdue = $res_name["aroverdue"]; $aroverdue = round($aroverdue,2);
    $arnextydue = $res_name["arnextydue"]; $arnextydue = round($arnextydue,2);
    $arotherydue = $res_name["arotherydue"]; $arotherydue = round($arotherydue,2);
    $artotal = $res_name["artotal"]; $artotal = round($artotal,2);
    
    $rlpreviousy = $res_name["rlpreviousy"]; $rlpreviousy = round($rlpreviousy,2);
    $rltothisy = $res_name["rltothisy"]; $rltothisy = round($rltothisy,2);
    $rlpayreal = $res_name["rlpayreal"]; $rlpayreal = round($rlpayreal,2);
    $rlnexty = $res_name["rlnexty"]; $rlnexty = round($rlnexty,2);
    $rlall = $res_name["rlall"]; $rlall = round($rlall,2);
    $rlthisy = $res_name["rlthisy"]; $rlthisy = round($rlthisy,2);
    
    $qry_fullname=pg_query("SELECT full_name FROM \"VContact\" where \"IDNO\"='$idno'");
    if($res_fullname=pg_fetch_array($qry_fullname)){
        $full_name = $res_fullname['full_name'];
    }

if($i > 44){ //new page
    $pdf->AddPage(); 
    $cline = 37; 
    $i=1;

    $pdf->SetFont('AngsanaNew','B',15);
    $pdf->SetXY(10,10);
    $title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
    $pdf->MultiCell(190,4,$title,0,'C',0);

    $pdf->SetFont('AngsanaNew','',12);
    $pdf->SetXY(10,15);
    $buss_name=iconv('UTF-8','windows-874',"ตารางลูกหนี้ตามระยะเวลาครบกำหนดชำระ (สาขาจรัญสนิทวงศ์)");
    $pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(10,22);
$buss_name=iconv('UTF-8','windows-874',"ณ วันที่ 31 ธันวาคม ".$yy);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,28); 
$buss_name=iconv('UTF-8','windows-874',"
 ");
$pdf->MultiCell(55,4,$buss_name,1,'C',0);

    $pdf->SetXY(5,30); 
    $buss_name=iconv('UTF-8','windows-874',"Name");
    $pdf->MultiCell(55,4,$buss_name,0,'C',0);

$pdf->SetXY(60,28); 
$buss_name=iconv('UTF-8','windows-874',"Realized");
$pdf->MultiCell(139,4,$buss_name,1,'C',0);

    $pdf->SetXY(60,32);
    $buss_name=iconv('UTF-8','windows-874',"Previous Year");
    $pdf->MultiCell(23,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(83,32);
    $buss_name=iconv('UTF-8','windows-874',"ต้องชำระ");
    $pdf->MultiCell(23,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(106,32);
    $buss_name=iconv('UTF-8','windows-874',"รับรู้ไม่เกิน3");
    $pdf->MultiCell(23,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(129,32);
    $buss_name=iconv('UTF-8','windows-874',"Next Year");
    $pdf->MultiCell(23,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(152,32);
    $buss_name=iconv('UTF-8','windows-874',"ดอกผลทั้งหมด");
    $pdf->MultiCell(23,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(175,32);
    $buss_name=iconv('UTF-8','windows-874',"รับรู้ปีนี้");
    $pdf->MultiCell(24,4,$buss_name,1,'C',0);
}// end new page
    
if($t_custyear != $custyear AND $j != 1){
    $pdf->SetFont('AngsanaNew','',10); 
    
    $pdf->SetXY(5,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"         ลูกค้าประจำปี $tt_custyear");
    $pdf->MultiCell(55,4,$buss_name,0,'L',0);

    $pdf->SetXY(60,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_rlpreviousy,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(83,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_rltothisy,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(106,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_rlpayreal,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(129,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_rlnexty,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(152,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_rlall,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(175,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_rlthisy,2));
    $pdf->MultiCell(24,4,$buss_name,0,'R',0);
    
    $cline+=5;
    
    $s_rlpreviousy=0;
    $s_rltothisy=0;
    $s_rlpayreal=0;
    $s_rlnexty=0;
    $s_rlall=0;
    $s_rlthisy=0;
    $i++;

}


$t_custyear = $custyear;
$tt_custyear = $custyear;

    if($t_overdue != $overdue AND $j != 1){
        $pdf->SetFont('AngsanaNew','B',10); 
        
        $pdf->SetXY(5,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"สรุป ลูกหนี้ที่ค้างจำนวนงวด $t_overdue");
        $pdf->MultiCell(55,4,$buss_name,0,'L',0);

    $pdf->SetXY(60,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_rlpreviousy,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(83,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_rltothisy,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(106,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_rlpayreal,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(129,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_rlnexty,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(152,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_rlall,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(175,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_rlthisy,2));
    $pdf->MultiCell(24,4,$buss_name,0,'R',0);
        
    $st_rlpreviousy=0;
    $st_rltothisy=0;
    $st_rlpayreal=0;
    $st_rlnexty=0;
    $st_rlall=0;
    $st_rlthisy=0;
    $t_custyear="";
        
        $cline+=5;
        $i++;
    }
    
    
    $s_rlpreviousy += $rlpreviousy;
    $s_rltothisy += $rltothisy;
    $s_rlpayreal += $rlpayreal;
    $s_rlnexty += $rlnexty;
    $s_rlall += $rlall;
    $s_rlthisy += $rlthisy;

    $st_rlpreviousy += $rlpreviousy;
    $st_rltothisy += $rltothisy;
    $st_rlpayreal += $rlpayreal;
    $st_rlnexty += $rlnexty;
    $st_rlall += $rlall;
    $st_rlthisy += $rlthisy;
    
    $s_rlpreviousy_all += $rlpreviousy;
    $s_rltothisy_all += $rltothisy;
    $s_rlpayreal_all += $rlpayreal;
    $s_rlnexty_all += $rlnexty;
    $s_rlall_all += $rlall;
    $s_rlthisy_all += $rlthisy;
    
    $t_overdue = $overdue;
}

    
    $pdf->SetFont('AngsanaNew','B',10); 
    
    $pdf->SetXY(5,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"สรุป ลูกหนี้ที่ค้างจำนวนงวด $t_overdue");
    $pdf->MultiCell(55,4,$buss_name,0,'L',0);

    $pdf->SetXY(60,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_rlpreviousy,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(83,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_rltothisy,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(106,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_rlpayreal,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(129,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_rlnexty,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(152,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_rlall,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(175,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_rlthisy,2));
    $pdf->MultiCell(24,4,$buss_name,0,'R',0);
    
    
    $cline+=5;
    
    $pdf->SetXY(5,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
    $pdf->MultiCell(55,4,$buss_name,0,'R',0);

    $pdf->SetXY(60,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_rlpreviousy_all,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(83,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_rltothisy_all,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(106,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_rlpayreal_all,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(129,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_rlnexty_all,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(152,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_rlall_all,2));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(175,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_rlthisy_all,2));
    $pdf->MultiCell(24,4,$buss_name,0,'R',0);
    
        $pdf->SetXY(60,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"_________________");
        $pdf->MultiCell(23,4,$buss_name,0,'R',0);

        $pdf->SetXY(83,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"_________________");
        $pdf->MultiCell(23,4,$buss_name,0,'R',0);

        $pdf->SetXY(106,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"_________________");
        $pdf->MultiCell(23,4,$buss_name,0,'R',0);

        $pdf->SetXY(129,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"_________________");
        $pdf->MultiCell(23,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(152,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"_________________");
        $pdf->MultiCell(23,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(175,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"_________________");
        $pdf->MultiCell(23,4,$buss_name,0,'R',0);

$pdf->Output();
?>