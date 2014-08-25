<?php
include("../config/config.php");

$nowdate = Date('Y-m-d');

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
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
$title=iconv('UTF-8','windows-874',"รายงาน ค้างค่างวด");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(40,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(60,4,$buss_name,0,'C',0);

$pdf->SetXY(100,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"สีรถ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนงวดที่ค้าง");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนวันที่ค้างถึงปัจจุบัน");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;
                  
$qry_fr=pg_query("select \"IDNO\",COUNT(\"DueNo\") as \"SumDueNo\",MAX(\"daydelay\") as \"daydelay\" from \"VRemainPayment\" GROUP BY \"IDNO\" ORDER BY \"SumDueNo\" DESC,\"daydelay\" DESC ");
while($res_fr=pg_fetch_array($qry_fr)){
    
    $IDNO = $res_fr["IDNO"];
    $SumDueNo = $res_fr["SumDueNo"];
    $DueDate = $res_fr["DueDate"];
    $V_Receipt = $res_fr["V_Receipt"];
    $V_Date = $res_fr["V_Date"];
    $daydelay = $res_fr["daydelay"];
    
    $qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_vc=pg_fetch_array($qry_vc)){
        $full_name = $res_vc["full_name"];
        $C_COLOR = $res_vc["C_COLOR"];
        $asset_type = $res_vc["asset_type"];
        $C_REGIS = $res_vc["C_REGIS"];
        $car_regis = $res_vc["car_regis"];
        if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
    }

if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงาน เช็คหมด");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท $_SESSION[session_company_thainame]");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(40,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(60,4,$buss_name,0,'C',0);

$pdf->SetXY(100,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"สีรถ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนงวดที่ค้าง");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนวันที่ค้างถึงปัจจุบัน");
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10); 

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(40,$cline); 
$buss_name=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(60,4,$buss_name,0,'L',0);

$pdf->SetXY(100,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_regis);
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(120,$cline); 
$buss_name=iconv('UTF-8','windows-874',$C_COLOR);
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',$SumDueNo);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(165,$cline); 
$buss_name=iconv('UTF-8','windows-874',$daydelay);
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$cline+=5; 
$i+=1;       
}

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(5,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>