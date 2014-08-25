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
$buss_name=iconv('UTF-8','windows-874',"Policy
Number");
$pdf->MultiCell(15,4,$buss_name,1,'C',0);

$pdf->SetXY(20,28); 
$buss_name=iconv('UTF-8','windows-874',"
 ");
$pdf->MultiCell(40,4,$buss_name,1,'C',0);

    $pdf->SetXY(20,30); 
    $buss_name=iconv('UTF-8','windows-874',"Name");
    $pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(60,28); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนงวดของ");
$pdf->MultiCell(60,4,$buss_name,1,'C',0);

    $pdf->SetXY(60,32); 
    $buss_name=iconv('UTF-8','windows-874',"Paid");
    $pdf->MultiCell(12,4,$buss_name,1,'C',0);

    $pdf->SetXY(72,32); 
    $buss_name=iconv('UTF-8','windows-874',"ค้าง");
    $pdf->MultiCell(12,4,$buss_name,1,'C',0);

    $pdf->SetXY(84,32); 
    $buss_name=iconv('UTF-8','windows-874',"Next");
    $pdf->MultiCell(12,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(96,32); 
    $buss_name=iconv('UTF-8','windows-874',"Other");
    $pdf->MultiCell(12,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(108,32); 
    $buss_name=iconv('UTF-8','windows-874',"Total");
    $pdf->MultiCell(12,4,$buss_name,1,'C',0);

$pdf->SetXY(120,28); 
$buss_name=iconv('UTF-8','windows-874',"
 ");
$pdf->MultiCell(15,4,$buss_name,1,'C',0);

    $pdf->SetXY(120,30); 
    $buss_name=iconv('UTF-8','windows-874',"ค่างวด");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(135,28); 
$buss_name=iconv('UTF-8','windows-874',"Account Receivable");
$pdf->MultiCell(64,4,$buss_name,1,'C',0);

    $pdf->SetXY(135,32);
    $buss_name=iconv('UTF-8','windows-874',"Over Due");
    $pdf->MultiCell(16,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(151,32);
    $buss_name=iconv('UTF-8','windows-874',"Next Year");
    $pdf->MultiCell(16,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(167,32);
    $buss_name=iconv('UTF-8','windows-874',"Over 1 Year");
    $pdf->MultiCell(16,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(183,32);
    $buss_name=iconv('UTF-8','windows-874',"Total");
    $pdf->MultiCell(16,4,$buss_name,1,'C',0);


$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;

$j=0;
$t_custyear = 0;

$select_date = $yy."-12-31";

$qry_name=pg_query("SELECT * FROM account.\"effsoyaddcom\" where  \"acclosedate\"='$select_date' ORDER BY custyear,\"idno\" ASC ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $j+=1;
    $i+=1;
    $idno = $res_name["idno"];
    $cusid = $res_name["cusid"];
    $custyear = $res_name["custyear"];
    $paid = $res_name["paid"];
    $overdue = $res_name["overdue"];
    $nextydue = $res_name["nextydue"];
    $otherydue = $res_name["otherydue"];
    $totaldue = $res_name["totaldue"];
    $monthly = $res_name["monthly"]; $monthly = round($monthly,2);
    $aroverdue = $res_name["aroverdue"]; $aroverdue = round($aroverdue,2);
    $arnextydue = $res_name["arnextydue"]; $arnextydue = round($arnextydue,2);
    $arotherydue = $res_name["arotherydue"]; $arotherydue = round($arotherydue,2);
    $artotal = $res_name["artotal"]; $artotal = round($artotal,2);
    $aroutstanding = $res_name["aroutstanding"]; $aroutstanding = round($aroutstanding,2);
    
    $qry_fullname=pg_query("SELECT full_name FROM \"VContact\" where  \"IDNO\"='$idno'");
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
    $buss_name=iconv('UTF-8','windows-874',"Policy
Number");
    $pdf->MultiCell(15,4,$buss_name,1,'C',0);

    $pdf->SetXY(20,28); 
    $buss_name=iconv('UTF-8','windows-874',"
     ");
    $pdf->MultiCell(40,4,$buss_name,1,'C',0);

        $pdf->SetXY(20,30); 
        $buss_name=iconv('UTF-8','windows-874',"Name");
        $pdf->MultiCell(40,4,$buss_name,0,'C',0);

    $pdf->SetXY(60,28); 
    $buss_name=iconv('UTF-8','windows-874',"จำนวนงวดของ");
    $pdf->MultiCell(60,4,$buss_name,1,'C',0);

        $pdf->SetXY(60,32); 
        $buss_name=iconv('UTF-8','windows-874',"Paid");
        $pdf->MultiCell(12,4,$buss_name,1,'C',0);

        $pdf->SetXY(72,32); 
        $buss_name=iconv('UTF-8','windows-874',"ค้าง");
        $pdf->MultiCell(12,4,$buss_name,1,'C',0);

        $pdf->SetXY(84,32); 
        $buss_name=iconv('UTF-8','windows-874',"Next");
        $pdf->MultiCell(12,4,$buss_name,1,'C',0);
        
        $pdf->SetXY(96,32); 
        $buss_name=iconv('UTF-8','windows-874',"Other");
        $pdf->MultiCell(12,4,$buss_name,1,'C',0);
        
        $pdf->SetXY(108,32); 
        $buss_name=iconv('UTF-8','windows-874',"Total");
        $pdf->MultiCell(12,4,$buss_name,1,'C',0);

    $pdf->SetXY(120,28); 
    $buss_name=iconv('UTF-8','windows-874',"
     ");
    $pdf->MultiCell(15,4,$buss_name,1,'C',0);

        $pdf->SetXY(120,30); 
        $buss_name=iconv('UTF-8','windows-874',"ค่างวด");
        $pdf->MultiCell(15,4,$buss_name,0,'C',0);

    $pdf->SetXY(135,28); 
    $buss_name=iconv('UTF-8','windows-874',"Account Receivable");
    $pdf->MultiCell(64,4,$buss_name,1,'C',0);

        $pdf->SetXY(135,32);
        $buss_name=iconv('UTF-8','windows-874',"Over Due");
        $pdf->MultiCell(16,4,$buss_name,1,'C',0);
        
        $pdf->SetXY(151,32);
        $buss_name=iconv('UTF-8','windows-874',"Next Year");
        $pdf->MultiCell(16,4,$buss_name,1,'C',0);
        
        $pdf->SetXY(167,32);
        $buss_name=iconv('UTF-8','windows-874',"Over 1 Year");
        $pdf->MultiCell(16,4,$buss_name,1,'C',0);
        
        $pdf->SetXY(183,32);
        $buss_name=iconv('UTF-8','windows-874',"Total");
        $pdf->MultiCell(16,4,$buss_name,1,'C',0);
}// end new page
    
if($t_custyear != $custyear AND $j != 1){
    $pdf->SetFont('AngsanaNew','B',10); 
    
    $pdf->SetXY(115,$cline); 
    $buss_name=iconv('UTF-8','windows-874',number_format($s_monthly,2));
    $pdf->MultiCell(17,4,$buss_name,0,'R',0);

    $pdf->SetXY(132,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_aroverdue,2));
    $pdf->MultiCell(17,4,$buss_name,0,'R',0);

    $pdf->SetXY(149,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_arnextydue,2));
    $pdf->MultiCell(17,4,$buss_name,0,'R',0);

    $pdf->SetXY(167,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_arotherydue,2));
    $pdf->MultiCell(17,4,$buss_name,0,'R',0);

    $pdf->SetXY(183,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_artotal,2));
    $pdf->MultiCell(17,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(115,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'R',0);

        $pdf->SetXY(132,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'R',0);

        $pdf->SetXY(149,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'C',0);

        $pdf->SetXY(166,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'C',0);

        $pdf->SetXY(183,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'C',0);
    
    
    $cline+=5;
        
    $s_monthly=0;
    $s_aroverdue=0;
    $s_arnextydue=0;
    $s_arotherydue=0;
    $s_artotal=0;
    $s_aroutstanding=0;
}
    
    $s_monthly += $monthly;
    $s_aroverdue += $aroverdue;
    $s_arnextydue += $arnextydue;
    $s_arotherydue += $arotherydue;
    $s_artotal += $artotal;
    $s_aroutstanding += $aroutstanding;
    
    $s_monthly_all += $monthly;
    $s_aroverdue_all += $aroverdue;
    $s_arnextydue_all += $arnextydue;
    $s_arotherydue_all += $arotherydue;
    $s_artotal_all += $artotal;
    $s_aroutstanding_all += $aroutstanding;
    
    if($t_custyear != $custyear){
        $pdf->SetFont('AngsanaNew','B',10); 
        $pdf->SetXY(20,$cline); 
        $buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ปี $custyear");
        $pdf->MultiCell(40,4,$buss_name,0,'L',0);
        $cline+=5;
    }


$pdf->SetFont('AngsanaNew','',10); 
 
$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$idno);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);
    
$pdf->SetXY(20,$cline); 
$buss_name=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(60,$cline); 
$buss_name=iconv('UTF-8','windows-874',$paid);
$pdf->MultiCell(12,4,$buss_name,0,'C',0);

$pdf->SetXY(72,$cline); 
$buss_name=iconv('UTF-8','windows-874',$overdue);
$pdf->MultiCell(12,4,$buss_name,0,'C',0);

$pdf->SetXY(84,$cline); 
$buss_name=iconv('UTF-8','windows-874',$nextydue);
$pdf->MultiCell(12,4,$buss_name,0,'C',0);

$pdf->SetXY(96,$cline); 
$buss_name=iconv('UTF-8','windows-874',$otherydue);
$pdf->MultiCell(12,4,$buss_name,0,'C',0);

$pdf->SetXY(108,$cline); 
$buss_name=iconv('UTF-8','windows-874',$totaldue);
$pdf->MultiCell(12,4,$buss_name,0,'C',0);

$pdf->SetXY(120,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($monthly,2));
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($aroverdue,2));
$pdf->MultiCell(16,4,$buss_name,0,'R',0);

$pdf->SetXY(151,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($arnextydue,2));
$pdf->MultiCell(16,4,$buss_name,0,'R',0);

$pdf->SetXY(167,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($arotherydue,2));
$pdf->MultiCell(16,4,$buss_name,0,'R',0);

$pdf->SetXY(183,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($artotal,2));
$pdf->MultiCell(16,4,$buss_name,0,'R',0);

$cline+=5;    
$t_custyear = $custyear;
}

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(115,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($s_monthly,2));
$pdf->MultiCell(17,4,$buss_name,0,'R',0);

$pdf->SetXY(132,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($s_aroverdue,2));
$pdf->MultiCell(17,4,$buss_name,0,'R',0);

$pdf->SetXY(149,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($s_arnextydue,2));
$pdf->MultiCell(17,4,$buss_name,0,'R',0);

$pdf->SetXY(167,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($s_arotherydue,2));
$pdf->MultiCell(17,4,$buss_name,0,'R',0);

$pdf->SetXY(183,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($s_artotal,2));
$pdf->MultiCell(17,4,$buss_name,0,'R',0);
        
        $pdf->SetXY(115,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'R',0);

        $pdf->SetXY(132,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'R',0);

        $pdf->SetXY(149,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'C',0);

        $pdf->SetXY(166,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'C',0);

        $pdf->SetXY(183,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'C',0);

$cline+=5;

$pdf->SetXY(95,$cline); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น ");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(115,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($s_monthly_all,2));
$pdf->MultiCell(17,4,$buss_name,0,'R',0);

$pdf->SetXY(132,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($s_aroverdue_all,2));
$pdf->MultiCell(17,4,$buss_name,0,'R',0);

$pdf->SetXY(149,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($s_arnextydue_all,2));
$pdf->MultiCell(17,4,$buss_name,0,'R',0);

$pdf->SetXY(166,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($s_arotherydue_all,2));
$pdf->MultiCell(17,4,$buss_name,0,'R',0);

$pdf->SetXY(183,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($s_artotal_all,2));
$pdf->MultiCell(17,4,$buss_name,0,'R',0);
            
        $pdf->SetXY(115,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'R',0);

        $pdf->SetXY(132,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'R',0);

        $pdf->SetXY(149,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'C',0);

        $pdf->SetXY(166,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'C',0);

        $pdf->SetXY(183,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"____________");
        $pdf->MultiCell(17,4,$buss_name,0,'C',0);

$pdf->Output();
?>