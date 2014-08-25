<?php
include("../config/config.php");

$tday=pg_escape_string($_GET["tday"]);
$type=pg_escape_string($_GET["type"]);
$nowdate = date('Y/m/d');

$trndate=pg_query("select conversiondatetothaitext('$tday')");  
$restrn=pg_fetch_result($trndate,0);

if($type == 1){
    $pagename = "เงินสด";
    //$qry_fr=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_BANK\"='CA') AND (\"PayType\"='OC') ORDER BY \"O_RECEIPT\" ASC ");
    $qry_fr=pg_query("select \"O_RECEIPT\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_BANK\"='CA' OR \"O_BANK\"='CCA') AND (\"PayType\"='OC') GROUP BY \"O_RECEIPT\" ORDER BY \"O_RECEIPT\" ASC ");
}elseif($type == 2){
    $pagename = "เช็ค";
    //$qry_fr=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_BANK\"='CU') AND (\"PayType\"='OC') ORDER BY \"O_RECEIPT\" ASC ");
    $qry_fr=pg_query("select \"O_RECEIPT\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_BANK\"='CU') AND (\"PayType\"='OC') GROUP BY \"O_RECEIPT\" ORDER BY \"O_RECEIPT\" ASC ");
}elseif($type == 3){
    $pagename = "จากที่อื่น";
    $qry_fr=pg_query("select \"O_RECEIPT\",\"PayType\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_memo\"<>'TR-ACC' OR \"O_memo\" is null OR \"O_memo\"='') AND (\"PayType\"<>'OC') GROUP BY \"O_RECEIPT\",\"PayType\" ORDER BY \"PayType\" ASC ");
}elseif($type == 4){
    $pagename = "เงินโอน";
    //$qry_fr=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_memo\" ='TR-ACC') AND (\"PayType\"<>'OC') ORDER BY \"PayType\" ASC ");
    $qry_fr=pg_query("select \"O_RECEIPT\",\"PayType\",sum(\"O_MONEY\") as \"O_MONEY\" from \"VFOtherpayEachDay\" WHERE (\"O_PRNDATE\"='$tday') AND (\"O_memo\" ='TR-ACC') AND (\"PayType\"<>'OC') GROUP BY \"O_RECEIPT\",\"PayType\" ORDER BY \"PayType\" ASC ");
}

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
$title=iconv('UTF-8','windows-874',"รายงาน รับค่างวด");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"($pagename) วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"O_Receipt");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"O_Date");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"Bank");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(55,30); 
$buss_name=iconv('UTF-8','windows-874',"PayType");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(70,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,30); 
$buss_name=iconv('UTF-8','windows-874',"full_name");
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(125,30); 
$buss_name=iconv('UTF-8','windows-874',"assetname");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(155,30); 
$buss_name=iconv('UTF-8','windows-874',"TName");
$pdf->MultiCell(18,4,$buss_name,0,'L',0);

$pdf->SetXY(173,30); 
$buss_name=iconv('UTF-8','windows-874',"regis");
$pdf->MultiCell(13,4,$buss_name,0,'L',0);

$pdf->SetXY(186,30); 
$buss_name=iconv('UTF-8','windows-874',"money");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;
                         
while($res_if=pg_fetch_array($qry_fr)){
    
    $qry_cl=pg_query("select * from \"FOtherpay\" WHERE (\"O_RECEIPT\"='$res_if[O_RECEIPT]') ");
    $res_cl=pg_fetch_array($qry_cl);
    $cancel = $res_cl["Cancel"];
    
    if($cancel == 'f'){
    
    $qry_ss=pg_query("select * from \"VFOtherpayEachDay\" WHERE (\"O_RECEIPT\"='$res_if[O_RECEIPT]') ");
    $res_ss=pg_fetch_array($qry_ss);
    
    $j+=1;
    $aa+=1;
        $R_Receipt = $res_if["O_RECEIPT"];
        $R_Date = $res_ss["O_DATE"];
        $R_Bank = $res_ss["O_BANK"];
        $PayType = $res_ss["PayType"];
        $IDNO = $res_ss["IDNO"];
        $full_name = $res_ss["full_name"];
        $assetname = $res_ss["assetname"];
        $typepay_name = $res_ss["TName"];
        $regis = $res_ss["regis"];
        $money = $res_if["O_MONEY"];
        
        $moneys += $money;
    
    $money = number_format($money,2);

if($i > 45){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงาน รับค่างวด");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"($pagename) วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"O_Receipt");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"O_Date");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"Bank");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(55,30); 
$buss_name=iconv('UTF-8','windows-874',"PayType");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(70,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,30); 
$buss_name=iconv('UTF-8','windows-874',"full_name");
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(125,30); 
$buss_name=iconv('UTF-8','windows-874',"assetname");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(155,30); 
$buss_name=iconv('UTF-8','windows-874',"TName");
$pdf->MultiCell(18,4,$buss_name,0,'L',0);

$pdf->SetXY(173,30); 
$buss_name=iconv('UTF-8','windows-874',"regis");
$pdf->MultiCell(13,4,$buss_name,0,'L',0);

$pdf->SetXY(186,30); 
$buss_name=iconv('UTF-8','windows-874',"money");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10); 
   
   
$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$aa);
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,$cline); 
$buss_name=iconv('UTF-8','windows-874',$R_Receipt);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(30,$cline); 
$buss_name=iconv('UTF-8','windows-874',$R_Date);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(45,$cline); 
$buss_name=iconv('UTF-8','windows-874',$R_Bank);
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(55,$cline); 
$buss_name=iconv('UTF-8','windows-874',$PayType);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(70,$cline); 
$buss_name=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(85,$cline); 
$buss_name=iconv('UTF-8','windows-874',$full_name);
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(125,$cline); 
$buss_name=iconv('UTF-8','windows-874',$assetname);
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(155,$cline); 
$buss_name=iconv('UTF-8','windows-874',$typepay_name);
$pdf->MultiCell(18,4,$buss_name,0,'L',0);

$pdf->SetXY(173,$cline); 
$buss_name=iconv('UTF-8','windows-874',$regis);
$pdf->MultiCell(13,4,$buss_name,0,'L',0);

$pdf->SetXY(186,$cline); 
$buss_name=iconv('UTF-8','windows-874',$money);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);   

$cline+=5; 
$i+=1;       
}
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