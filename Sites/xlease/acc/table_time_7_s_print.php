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
$buss_name=iconv('UTF-8','windows-874',"Un-Realized");
$pdf->MultiCell(139,4,$buss_name,1,'C',0);

    $pdf->SetXY(60,32);
    $buss_name=iconv('UTF-8','windows-874',"Over Due");
    $pdf->MultiCell(34,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(94,32);
    $buss_name=iconv('UTF-8','windows-874',"Next Year");
    $pdf->MultiCell(34,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(128,32);
    $buss_name=iconv('UTF-8','windows-874',"Over 1 Year");
    $pdf->MultiCell(35,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(163,32);
    $buss_name=iconv('UTF-8','windows-874',"Total");
    $pdf->MultiCell(36,4,$buss_name,1,'C',0);


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
    
    $uroverdue = $res_name["uroverdue"]; $uroverdue = round($uroverdue,2);
    $urnexty = $res_name["urnexty"]; $urnexty = round($urnexty,2);
    $urothery = $res_name["urothery"]; $urothery = round($urothery,2);
    $urtotal = $res_name["urtotal"]; $urtotal = round($urtotal,2);
    
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
$buss_name=iconv('UTF-8','windows-874',"Un-Realized");
$pdf->MultiCell(139,4,$buss_name,1,'C',0);

    $pdf->SetXY(60,32);
    $buss_name=iconv('UTF-8','windows-874',"Over Due");
    $pdf->MultiCell(34,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(94,32);
    $buss_name=iconv('UTF-8','windows-874',"Next Year");
    $pdf->MultiCell(34,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(128,32);
    $buss_name=iconv('UTF-8','windows-874',"Over 1 Year");
    $pdf->MultiCell(35,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(163,32);
    $buss_name=iconv('UTF-8','windows-874',"Total");
    $pdf->MultiCell(36,4,$buss_name,1,'C',0);
}// end new page
    
if($t_custyear != $custyear AND $j != 1){
    $pdf->SetFont('AngsanaNew','',10); 
    
    $pdf->SetXY(5,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"         ลูกค้าประจำปี $tt_custyear");
    $pdf->MultiCell(55,4,$buss_name,0,'L',0);

    $pdf->SetXY(60,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_uroverdue,2));
    $pdf->MultiCell(34,4,$buss_name,0,'R',0);

    $pdf->SetXY(94,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_urnexty,2));
    $pdf->MultiCell(34,4,$buss_name,0,'R',0);

    $pdf->SetXY(128,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_urothery,2));
    $pdf->MultiCell(35,4,$buss_name,0,'R',0);

    $pdf->SetXY(163,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_urtotal,2));
    $pdf->MultiCell(36,4,$buss_name,0,'R',0);
    
    $cline+=5;


    
    $s_uroverdue=0;
    $s_urnexty=0;
    $s_urothery=0;
    $s_urtotal=0;
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
    $buss_name=iconv('UTF-8','windows-874',number_format($st_uroverdue,2));
    $pdf->MultiCell(34,4,$buss_name,0,'R',0);

    $pdf->SetXY(94,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_urnexty,2));
    $pdf->MultiCell(34,4,$buss_name,0,'R',0);

    $pdf->SetXY(128,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_urothery,2));
    $pdf->MultiCell(35,4,$buss_name,0,'R',0);

    $pdf->SetXY(163,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_urtotal,2));
    $pdf->MultiCell(36,4,$buss_name,0,'R',0);
        
    $st_uroverdue=0;
    $st_urnexty=0;
    $st_urothery=0;
    $st_urtotal=0;
    $t_custyear="";
        
        $cline+=5;
        $i++;
    }
    
    $s_uroverdue += $uroverdue;
    $s_urnexty += $urnexty;
    $s_urothery += $urothery;
    $s_urtotal += $urtotal;
    
    $st_uroverdue += $uroverdue;
    $st_urnexty += $urnexty;
    $st_urothery += $urothery;
    $st_urtotal += $urtotal;
    
    $s_uroverdue_all += $uroverdue;
    $s_urnexty_all += $urnexty;
    $s_urothery_all += $urothery;
    $s_urtotal_all += $urtotal;
    
    $t_overdue = $overdue;
    
}
    
    $pdf->SetFont('AngsanaNew','B',10); 
    
    $pdf->SetXY(5,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"สรุป ลูกหนี้ที่ค้างจำนวนงวด $t_overdue");
    $pdf->MultiCell(55,4,$buss_name,0,'L',0);

    $pdf->SetXY(60,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_uroverdue,2));
    $pdf->MultiCell(34,4,$buss_name,0,'R',0);

    $pdf->SetXY(94,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_urnexty,2));
    $pdf->MultiCell(34,4,$buss_name,0,'R',0);

    $pdf->SetXY(128,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_urothery,2));
    $pdf->MultiCell(35,4,$buss_name,0,'R',0);

    $pdf->SetXY(163,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($st_urtotal,2));
    $pdf->MultiCell(36,4,$buss_name,0,'R',0);
    
    
    $cline+=5;
    
    $pdf->SetXY(5,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
    $pdf->MultiCell(55,4,$buss_name,0,'R',0);

    $pdf->SetXY(60,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_uroverdue_all,2));
    $pdf->MultiCell(34,4,$buss_name,0,'R',0);

    $pdf->SetXY(94,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_urnexty_all,2));
    $pdf->MultiCell(34,4,$buss_name,0,'R',0);

    $pdf->SetXY(128,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_urothery_all,2));
    $pdf->MultiCell(35,4,$buss_name,0,'R',0);

    $pdf->SetXY(163,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_urtotal_all,2));
    $pdf->MultiCell(36,4,$buss_name,0,'R',0);
    
        $pdf->SetXY(60,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"_________________");
        $pdf->MultiCell(34,4,$buss_name,0,'R',0);

        $pdf->SetXY(94,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"_________________");
        $pdf->MultiCell(34,4,$buss_name,0,'R',0);

        $pdf->SetXY(128,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"_________________");
        $pdf->MultiCell(35,4,$buss_name,0,'R',0);

        $pdf->SetXY(163,$cline-3);
        $buss_name=iconv('UTF-8','windows-874',"_________________");
        $pdf->MultiCell(36,4,$buss_name,0,'R',0);

$pdf->Output();
?>