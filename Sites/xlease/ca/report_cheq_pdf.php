<?php
include("../config/config.php");

$nowdate = date('Y/m/d');

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
$title=iconv('UTF-8','windows-874',"รายงาน เช็คหมด");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(75,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,30); 
$buss_name=iconv('UTF-8','windows-874',"ชำระเช็ค");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(145,30); 
$buss_name=iconv('UTF-8','windows-874',"ผ่านแล้ว");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"เหลืออีก");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;
                          
$qry_fr=pg_query("select \"IDNO\",count(\"ChequeNo\") as count_cheq ,sum(case when \"IsPass\"='TRUE' THEN 1 ELSE 0 END) as sum_ispass from \"VDetailCheque\" GROUP BY \"IDNO\" ORDER BY \"IDNO\" ");
while($res_fr=pg_fetch_array($qry_fr)){
    
    $IDNO = $res_fr["IDNO"];
    $count_cheq = $res_fr["count_cheq"];
    $sum_ispass = $res_fr["sum_ispass"];
    $summary = $count_cheq-$sum_ispass;
    
    $qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_vc=pg_fetch_array($qry_vc)){
        $full_name = $res_vc["full_name"];
        $P_TOTAL = $res_vc["P_TOTAL"];
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
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(75,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนงวด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,30); 
$buss_name=iconv('UTF-8','windows-874',"ชำระเช็ค");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(145,30); 
$buss_name=iconv('UTF-8','windows-874',"ผ่านแล้ว");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"เหลืออีก");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10); 
   
$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(75,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_regis);
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(95,$cline); 
$buss_name=iconv('UTF-8','windows-874',$P_TOTAL);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,$cline); 
$buss_name=iconv('UTF-8','windows-874',$count_cheq);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(145,$cline); 
$buss_name=iconv('UTF-8','windows-874',$sum_ispass);
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

if($summary < 2){
    
$pdf->SetXY(176,$cline); 
$buss_name=iconv('UTF-8','windows-874',$summary."     *");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);
    
}else{
    
$pdf->SetXY(176,$cline); 
$buss_name=iconv('UTF-8','windows-874',$summary);
$pdf->MultiCell(25,4,$buss_name,0,'L',0);
    
}



$cline+=5; 
$i+=1;       
}

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(5,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>