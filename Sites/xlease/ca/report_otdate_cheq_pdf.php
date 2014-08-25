<?php
include("../config/config.php");

$tday=pg_escape_string($_GET["tday"]);

$trndate=pg_query("select conversiondatetothaitext('$tday')");  
$restrn=pg_fetch_result($trndate,0);

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
$title=iconv('UTF-8','windows-874',"รายงาน รับเช็คจ่ายค่าอื่นๆ");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"O_Receipt");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"O_Date");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(55,30); 
$buss_name=iconv('UTF-8','windows-874',"Bank");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(65,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(80,30); 
$buss_name=iconv('UTF-8','windows-874',"full_name");
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"assetname");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(150,30); 
$buss_name=iconv('UTF-8','windows-874',"typepay");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"regis");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"money");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;
$qry_oq=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_BANK\"='CU') AND \"PayType\" = 'OC' ORDER BY \"O_RECEIPT\" ");                           
while($res_if=pg_fetch_array($qry_oq)){
    $j+=1;
    $aa+=1;
        $R_Receipt = $res_if["O_RECEIPT"];
        $R_Date = $res_if["O_DATE"];
        $R_Bank = $res_if["O_BANK"];
        $IDNO = $res_if["IDNO"];
        $full_name = $res_if["full_name"];
        $assetname = $res_if["assetname"];
        $typepay_name = $res_if["TName"];
        $regis = $res_if["regis"];
        $money = $res_if["O_MONEY"];
        
        $moneys += $money;
    
    $money = number_format($money,2);

if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงาน รับเช็คจ่ายค่าอื่นๆ");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"O_Receipt");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"O_Date");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(55,30); 
$buss_name=iconv('UTF-8','windows-874',"Bank");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(65,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(80,30); 
$buss_name=iconv('UTF-8','windows-874',"full_name");
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"assetname");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(150,30); 
$buss_name=iconv('UTF-8','windows-874',"typepay");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"regis");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"money");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10); 
   
$pdf->SetXY(5,$cline); 
$s_date=iconv('UTF-8','windows-874',$aa);
$pdf->MultiCell(10,4,$s_date,0,'C',0);
    
$pdf->SetXY(15,$cline); 
$idno=iconv('UTF-8','windows-874',$R_Receipt);
$pdf->MultiCell(20,4,$idno,0,'C',0);   

$pdf->SetXY(35,$cline); 
$s_name=iconv('UTF-8','windows-874',$R_Date);
$pdf->MultiCell(20,4,$s_name,0,'C',0);

$pdf->SetXY(55,$cline); 
$s_asset_name=iconv('UTF-8','windows-874',$R_Bank);
$pdf->MultiCell(10,4,$s_asset_name,0,'C',0);

$pdf->SetXY(65,$cline); 
$s_asset_regis=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(15,4,$s_asset_regis,0,'C',0);

$pdf->SetXY(80,$cline); 
$s_down=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(40,4,$s_down,0,'L',0);

$pdf->SetFont('AngsanaNew','',9); 
$pdf->SetXY(120,$cline); 
$s_down=iconv('UTF-8','windows-874',$assetname);
$pdf->MultiCell(30,4,$s_down,0,'L',0);

$pdf->SetFont('AngsanaNew','',10); 
$pdf->SetXY(150,$cline); 
$s_down=iconv('UTF-8','windows-874',$typepay_name);
$pdf->MultiCell(20,4,$s_down,0,'L',0);

$pdf->SetXY(170,$cline); 
$s_down=iconv('UTF-8','windows-874',$regis);
$pdf->MultiCell(15,4,$s_down,0,'L',0);

$pdf->SetXY(185,$cline); 
$s_down=iconv('UTF-8','windows-874',$money);
$pdf->MultiCell(15,4,$s_down,0,'R',0);

$cline+=5; 
$i+=1;       
}  

//--------------------------//

$qry_oq=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"PayType\" is null) ORDER BY \"O_BANK\" ASC,\"O_RECEIPT\" ASC");                          
while($res_if=pg_fetch_array($qry_oq)){
    $j+=1;
    $aa+=1;
        $R_Receipt = $res_if["O_RECEIPT"];
        $R_Date = $res_if["O_DATE"];
        $R_Bank = $res_if["O_BANK"];
        $IDNO = $res_if["IDNO"];
        $full_name = $res_if["full_name"];
        $assetname = $res_if["assetname"];
        $typepay_name = $res_if["TName"];
        $regis = $res_if["regis"];
        $money = $res_if["O_MONEY"];
        
        $moneys += $money;
    
    $money = number_format($money,2);

if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงาน รับเช็คจ่ายค่าอื่นๆ");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"O_Receipt");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"O_Date");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(55,30); 
$buss_name=iconv('UTF-8','windows-874',"Bank");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(65,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(80,30); 
$buss_name=iconv('UTF-8','windows-874',"full_name");
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(120,30); 
$buss_name=iconv('UTF-8','windows-874',"assetname");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(150,30); 
$buss_name=iconv('UTF-8','windows-874',"typepay");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(170,30); 
$buss_name=iconv('UTF-8','windows-874',"regis");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"money");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10); 
   
$pdf->SetXY(5,$cline); 
$s_date=iconv('UTF-8','windows-874',$aa);
$pdf->MultiCell(10,4,$s_date,0,'C',0);
    
$pdf->SetXY(15,$cline); 
$idno=iconv('UTF-8','windows-874',$R_Receipt);
$pdf->MultiCell(20,4,$idno,0,'C',0);   

$pdf->SetXY(35,$cline); 
$s_name=iconv('UTF-8','windows-874',$R_Date);
$pdf->MultiCell(20,4,$s_name,0,'C',0);

$pdf->SetXY(55,$cline); 
$s_asset_name=iconv('UTF-8','windows-874',$R_Bank);
$pdf->MultiCell(10,4,$s_asset_name,0,'C',0);

$pdf->SetXY(65,$cline); 
$s_asset_regis=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(15,4,$s_asset_regis,0,'C',0);

$pdf->SetXY(80,$cline); 
$s_down=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(40,4,$s_down,0,'L',0);

$pdf->SetFont('AngsanaNew','',9); 
$pdf->SetXY(120,$cline); 
$s_down=iconv('UTF-8','windows-874',$assetname);
$pdf->MultiCell(30,4,$s_down,0,'L',0);

$pdf->SetFont('AngsanaNew','',10); 
$pdf->SetXY(150,$cline); 
$s_down=iconv('UTF-8','windows-874',$typepay_name);
$pdf->MultiCell(20,4,$s_down,0,'L',0);

$pdf->SetXY(170,$cline); 
$s_down=iconv('UTF-8','windows-874',$regis);
$pdf->MultiCell(15,4,$s_down,0,'L',0);

$pdf->SetXY(185,$cline); 
$s_down=iconv('UTF-8','windows-874',$money);
$pdf->MultiCell(15,4,$s_down,0,'R',0);

$cline+=5; 
$i+=1;       
}  

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(5,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',10);

$moneys = number_format($moneys,2);

$pdf->SetXY(5,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"รวมยอดทั้งหมด : $moneys");
$pdf->MultiCell(196,4,$buss_name,0,'R',0); 

$pdf->Output();
?>