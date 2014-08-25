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
$title=iconv('UTF-8','windows-874',"ยกเลิก NT");
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
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(40,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(70,4,$buss_name,0,'C',0);

$pdf->SetXY(110,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอด NT");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"เงินรับฝาก");
$pdf->MultiCell(28,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;
                  
$qry_fr=pg_query("select * from \"NTHead\" WHERE cancel='false' AND \"CusState\"='0' ORDER BY \"IDNO\" ASC;");
while($res_fr=pg_fetch_array($qry_fr)){
    $NTID = $res_fr["NTID"];
    $IDNO = $res_fr["IDNO"];
    
    $nub+=1;
    
    $qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_vc=pg_fetch_array($qry_vc)){
        $full_name = $res_vc["full_name"];
        $asset_type = $res_vc["asset_type"];
        $C_REGIS = $res_vc["C_REGIS"];
        $car_regis = $res_vc["car_regis"];
        if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
    }
    
    $qry_amt=pg_query("select SUM(\"Amount\") as amtmoney from \"NTDetail\" WHERE \"NTID\"='$NTID' ");
    if($res_amt=pg_fetch_array($qry_amt)){
        $amtmoney = $res_amt["amtmoney"]; $amtmoney = round($amtmoney,2);
    }
    
    $qry_vc=pg_query("select \"dp_balance\" from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
    if($res_vc=pg_fetch_array($qry_vc)){
        $dp_balance = $res_vc["dp_balance"]; $dp_balance = round($dp_balance,2);
    }

if($i > 45){ 

$pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"ยกเลิก NT");
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
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(40,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ");
$pdf->MultiCell(70,4,$buss_name,0,'C',0);

$pdf->SetXY(110,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(140,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอด NT");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"เงินรับฝาก");
$pdf->MultiCell(28,4,$buss_name,1,'C',0);

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
$pdf->MultiCell(70,4,$buss_name,0,'L',0);

$pdf->SetXY(110,$cline); 
$buss_name=iconv('UTF-8','windows-874',$show_regis);
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($amtmoney,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(170,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($amtmoney,2));
$pdf->MultiCell(28,4,$buss_name,0,'R',0);

$cline+=5; 
$i+=1;       
}

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(5,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>