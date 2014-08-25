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
$buss_name=iconv('UTF-8','windows-874',"
 ");
$pdf->MultiCell(20,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(60,30); 
    $buss_name=iconv('UTF-8','windows-874',"งวดค้าง");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(80,28);
$buss_name=iconv('UTF-8','windows-874',"
 ");
$pdf->MultiCell(20,4,$buss_name,1,'C',0);

$pdf->SetXY(80,30);
$buss_name=iconv('UTF-8','windows-874',"Security");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(100,28); 
$buss_name=iconv('UTF-8','windows-874',"A/C Receivable After security deducted");
$pdf->MultiCell(99,4,$buss_name,1,'C',0);

    $pdf->SetXY(100,32);
    $buss_name=iconv('UTF-8','windows-874',"Over Due");
    $pdf->MultiCell(25,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(125,32);
    $buss_name=iconv('UTF-8','windows-874',"Next Year");
    $pdf->MultiCell(25,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(150,32);
    $buss_name=iconv('UTF-8','windows-874',"Over 1 Year");
    $pdf->MultiCell(25,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(175,32);
    $buss_name=iconv('UTF-8','windows-874',"Total");
    $pdf->MultiCell(24,4,$buss_name,1,'C',0);


$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;

$j=0;
$t_custyear = 0;
$select_date = $yy."-12-31";
$qry_name=pg_query("SELECT * FROM account.\"effsoyaddcom\" where  \"acclosedate\"='$select_date' ORDER BY custyear,\"idno\" ASC ");
$rows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
    $i+=1;
    $j+=1;
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
    
    $uroverdue = $res_name["uroverdue"]; $uroverdue = round($uroverdue,2);
    $urnexty = $res_name["urnexty"]; $urnexty = round($urnexty,2);
    $urothery = $res_name["urothery"]; $urothery = round($urothery,2);
    $urtotal = $res_name["urtotal"]; $urtotal = round($urtotal,2);
    
    
    if($overdue<=6) $security = 0.2;
    elseif($overdue>6) $security = 1;
    
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
$buss_name=iconv('UTF-8','windows-874',"
 ");
$pdf->MultiCell(20,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(60,30); 
    $buss_name=iconv('UTF-8','windows-874',"งวดค้าง");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(80,28);
$buss_name=iconv('UTF-8','windows-874',"
 ");
$pdf->MultiCell(20,4,$buss_name,1,'C',0);

$pdf->SetXY(80,30);
$buss_name=iconv('UTF-8','windows-874',"Security");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(100,28); 
$buss_name=iconv('UTF-8','windows-874',"A/C Receivable After security deducted");
$pdf->MultiCell(99,4,$buss_name,1,'C',0);

    $pdf->SetXY(100,32);
    $buss_name=iconv('UTF-8','windows-874',"Over Due");
    $pdf->MultiCell(25,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(125,32);
    $buss_name=iconv('UTF-8','windows-874',"Next Year");
    $pdf->MultiCell(25,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(150,32);
    $buss_name=iconv('UTF-8','windows-874',"Over 1 Year");
    $pdf->MultiCell(25,4,$buss_name,1,'C',0);
    
    $pdf->SetXY(175,32);
    $buss_name=iconv('UTF-8','windows-874',"Total");
    $pdf->MultiCell(24,4,$buss_name,1,'C',0);

}// end new page
    
if($t_custyear != $custyear AND $j != 1){
    $pdf->SetFont('AngsanaNew','B',10); 

    $pdf->SetXY(100,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_aroverdue,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(125,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_arnextydue,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(150,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_arotherydue,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(175,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_artotal,2));
    $pdf->MultiCell(24,4,$buss_name,0,'R',0);
                
                $pdf->SetXY(100,$cline-3);
                $buss_name=iconv('UTF-8','windows-874',"___________");
                $pdf->MultiCell(25,4,$buss_name,0,'R',0);
                
                $pdf->SetXY(125,$cline-3);
                $buss_name=iconv('UTF-8','windows-874',"____________");
                $pdf->MultiCell(25,4,$buss_name,0,'R',0);

                $pdf->SetXY(150,$cline-3);
                $buss_name=iconv('UTF-8','windows-874',"____________");
                $pdf->MultiCell(25,4,$buss_name,0,'R',0);

                $pdf->SetXY(175,$cline-3);
                $buss_name=iconv('UTF-8','windows-874',"____________");
                $pdf->MultiCell(24,4,$buss_name,0,'R',0);
    
    $cline+=5;
        
    $s_aroverdue=0;
    $s_arnextydue=0;
    $s_arotherydue=0;
    $s_artotal=0;
    $s_aroutstanding=0;
}
    
    $s_aroverdue += ($aroverdue-$uroverdue)*$security;
    $s_arnextydue += ($arnextydue-$urnexty)*$security;
    $s_arotherydue += ($arotherydue-$urothery)*$security;
    $s_artotal += ($artotal-$urtotal)*$security;
    $s_aroutstanding += ($aroutstanding-$urtotal)*$security;

    $s_aroverdue_all += ($aroverdue-$uroverdue)*$security;
    $s_arnextydue_all += ($arnextydue-$urnexty)*$security;
    $s_arotherydue_all += ($arotherydue-$urothery)*$security;
    $s_artotal_all += ($artotal-$urtotal)*$security;
    $s_aroutstanding_all += ($aroutstanding-$urtotal)*$security;
    
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
$buss_name=iconv('UTF-8','windows-874',$overdue);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(80,$cline); 
$buss_name=iconv('UTF-8','windows-874',$security);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(100,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format(($aroverdue-$uroverdue)*$security,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(125,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format(($arnextydue-$urnexty)*$security,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(150,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format(($arotherydue-$urothery)*$security,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(175,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format(($artotal-$urtotal)*$security,2));
$pdf->MultiCell(24,4,$buss_name,0,'R',0);

$cline+=5;    
$t_custyear = $custyear;
}

    $pdf->SetFont('AngsanaNew','B',10); 

    $pdf->SetXY(100,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_aroverdue,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(125,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_arnextydue,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(150,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_arotherydue,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(175,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_artotal,2));
    $pdf->MultiCell(24,4,$buss_name,0,'R',0);
                
                $pdf->SetXY(100,$cline-3);
                $buss_name=iconv('UTF-8','windows-874',"___________");
                $pdf->MultiCell(25,4,$buss_name,0,'R',0);
                
                $pdf->SetXY(125,$cline-3);
                $buss_name=iconv('UTF-8','windows-874',"____________");
                $pdf->MultiCell(25,4,$buss_name,0,'R',0);

                $pdf->SetXY(150,$cline-3);
                $buss_name=iconv('UTF-8','windows-874',"____________");
                $pdf->MultiCell(25,4,$buss_name,0,'R',0);

                $pdf->SetXY(175,$cline-3);
                $buss_name=iconv('UTF-8','windows-874',"____________");
                $pdf->MultiCell(24,4,$buss_name,0,'R',0);

                $cline+=5;

    $pdf->SetXY(80,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น ");
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(100,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_aroverdue_all,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(125,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_arnextydue_all,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(150,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_arotherydue_all,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(175,$cline);
    $buss_name=iconv('UTF-8','windows-874',number_format($s_artotal_all,2));
    $pdf->MultiCell(24,4,$buss_name,0,'R',0);
    
                $pdf->SetXY(100,$cline-3);
                $buss_name=iconv('UTF-8','windows-874',"___________");
                $pdf->MultiCell(25,4,$buss_name,0,'R',0);
                
                $pdf->SetXY(125,$cline-3);
                $buss_name=iconv('UTF-8','windows-874',"____________");
                $pdf->MultiCell(25,4,$buss_name,0,'R',0);

                $pdf->SetXY(150,$cline-3);
                $buss_name=iconv('UTF-8','windows-874',"____________");
                $pdf->MultiCell(25,4,$buss_name,0,'R',0);

                $pdf->SetXY(175,$cline-3);
                $buss_name=iconv('UTF-8','windows-874',"____________");
                $pdf->MultiCell(24,4,$buss_name,0,'R',0);

$pdf->Output();
?>