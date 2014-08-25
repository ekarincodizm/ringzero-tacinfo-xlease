<?php
include("../config/config.php");

$tday=pg_escape_string($_GET["tday"]);
$type=pg_escape_string($_GET["type"]);
$nowdate = date('Y/m/d');

$trndate=pg_query("select conversiondatetothaitext('$tday')");  
$restrn=pg_fetch_result($trndate,0);

if($type == 1){
    $pagename = "เงินสด";
    $qry_fr1=pg_query("select \"R_Receipt\",sum(value) as value,sum(vat) as vat,sum(money) as money,sum(discount) as discount from \"VFrEachDay\" WHERE (\"R_Prndate\"='$tday') AND (\"R_Bank\"='CA') AND (\"PayType\"='OC') GROUP BY \"R_Receipt\" ORDER BY \"R_Receipt\" ASC ");
    $qry_fr2=pg_query("select \"R_Receipt\",sum(value) as value,sum(vat) as vat,sum(money) as money,sum(discount) as discount from \"VFrEachDay\" WHERE (\"R_Prndate\"='$tday') AND (\"R_Bank\"='CCA') AND (\"PayType\"='OC') GROUP BY \"R_Receipt\" ORDER BY \"R_Receipt\" ASC ");
}elseif($type == 2){
    $pagename = "เช็ค";
    $qry_fr=pg_query("select \"R_Receipt\",sum(value) as value,sum(vat) as vat,sum(money) as money,sum(discount) as discount from \"VFrEachDay\" WHERE (\"R_Prndate\"='$tday') AND (\"R_Bank\"='CU')  AND (\"PayType\"='OC') GROUP BY \"R_Receipt\" ORDER BY \"R_Receipt\" ASC ");
}elseif($type == 3){
    $pagename = "จากที่อื่น";
    $qry_fr=pg_query("select \"R_Receipt\",\"PayType\",sum(value) as value,sum(vat) as vat,sum(money) as money,sum(discount) as discount from \"VFrEachDay\" WHERE (\"R_Prndate\"='$tday') AND (\"R_memo\"<>'TR-ACC' OR \"R_memo\" is null  OR \"R_memo\"='' OR \"R_Bank\"='CCA')  AND (\"PayType\"<>'OC') GROUP BY \"R_Receipt\",\"PayType\" ORDER BY \"PayType\" ASC ");
}elseif($type == 4){
    $pagename = "เงินโอน";
    $qry_fr=pg_query("select \"R_Receipt\",\"PayType\",sum(value) as value,sum(vat) as vat,sum(money) as money,sum(discount) from \"VFrEachDay\" WHERE (\"R_Prndate\"='$tday') AND (\"R_memo\" ='TR-ACC')  AND (\"PayType\"<>'OC') GROUP BY \"R_Receipt\",\"PayType\" ORDER BY \"PayType\" ASC ");
}

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(285,4,$buss_name,0,'R',0);
 
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
$title=iconv('UTF-8','windows-874',"รายงาน รับค่างวด");
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->MultiCell(285,4,$buss_name,0,'L',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"($pagename) วันที่พิมพ์ $nowdate");
$pdf->MultiCell(174,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"Receipt");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"Date");
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
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(135,30); 
$buss_name=iconv('UTF-8','windows-874',"assetname");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"typepay");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"regis");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"value");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(220,30); 
$buss_name=iconv('UTF-8','windows-874',"vat");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(235,30); 
$buss_name=iconv('UTF-8','windows-874',"money");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(255,30); 
$buss_name=iconv('UTF-8','windows-874',"discount");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(270,30); 
$buss_name=iconv('UTF-8','windows-874',"summary");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;

if($type == 1){

while($res_if=pg_fetch_array($qry_fr1)){
    
    $qry_ss=pg_query("select * from \"VFrEachDay\" WHERE (\"R_Receipt\"='$res_if[R_Receipt]') ORDER BY \"typepay_name\" DESC ");
    $res_ss=pg_fetch_array($qry_ss);
    
    $j+=1;
    $aa+=1;
        $R_Receipt = $res_if["R_Receipt"];
        $R_Date = $res_ss["R_Date"];
        $R_Bank = $res_ss["R_Bank"];
        $PayType = $res_ss["PayType"];
        $IDNO = $res_ss["IDNO"];
        $full_name = $res_ss["full_name"];
        $assetname = $res_ss["assetname"];
        $typepay_name = $res_ss["typepay_name"];
        $regis = $res_ss["regis"];
        
        $value = $res_if["value"];
        $vat = $res_if["vat"];
        $money = $res_if["money"];
        $discount = $res_if["discount"];
        $summary = $res_if["money"]-$res_if["discount"];
        
        $sum_value+=$value;
        $sum_vat+=$vat;
        $sum_money+=$money;
        $sum_discount+=$discount;
        $sum_summary+=$summary;
        
        $sum_value7+=$value;
        $sum_vat7+=$vat;
        $sum_money7+=$money;
        $sum_discount7+=$discount;
        $sum_summary7+=$summary;
        
        $value = number_format($value,2);
        $vat = number_format($vat,2);
        $money = number_format($money,2);
        $discount = number_format($discount,2);
        $summary = number_format($summary,2);
    
        
if($i > 30){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงาน รับค่างวด");
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->MultiCell(285,4,$buss_name,0,'L',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"($pagename) วันที่พิมพ์ $nowdate");
$pdf->MultiCell(174,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"Receipt");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"Date");
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
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(135,30); 
$buss_name=iconv('UTF-8','windows-874',"assetname");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"typepay");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"regis");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"value");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(220,30); 
$buss_name=iconv('UTF-8','windows-874',"vat");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(235,30); 
$buss_name=iconv('UTF-8','windows-874',"money");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(255,30); 
$buss_name=iconv('UTF-8','windows-874',"discount");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(270,30); 
$buss_name=iconv('UTF-8','windows-874',"summary");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

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
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(135,$cline); 
$buss_name=iconv('UTF-8','windows-874',$assetname);
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(165,$cline); 
$buss_name=iconv('UTF-8','windows-874',$typepay_name);
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(185,$cline); 
$buss_name=iconv('UTF-8','windows-874',$regis);
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',$value);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(220,$cline); 
$buss_name=iconv('UTF-8','windows-874',$vat);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(235,$cline); 
$buss_name=iconv('UTF-8','windows-874',$money);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',$discount);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(270,$cline); 
$buss_name=iconv('UTF-8','windows-874',$summary);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$cline+=5; 
$i+=1;       
}

$pdf->SetFont('AngsanaNew','B',11);

        $sum_value = number_format($sum_value,2);
        $sum_vat = number_format($sum_vat,2);
        $sum_money = number_format($sum_money,2);
        $sum_discount = number_format($sum_discount,2);
        $sum_summary = number_format($sum_summary,2);

$pdf->SetXY(151,$cline); 
$buss_name=iconv('UTF-8','windows-874',"รวมยอดเงิน");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);        
        
$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',$sum_value);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(220,$cline); 
$buss_name=iconv('UTF-8','windows-874',$sum_vat);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(235,$cline); 
$buss_name=iconv('UTF-8','windows-874',$sum_money);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',$sum_discount);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(270,$cline); 
$buss_name=iconv('UTF-8','windows-874',$sum_summary);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
$cline+=5; 
/* ================================== */

$sum_value = 0;
$sum_vat = 0;
$sum_money = 0;
$sum_discount = 0;
$sum_summary = 0;
while($res_if=pg_fetch_array($qry_fr2)){
    
    $qry_ss=pg_query("select * from \"VFrEachDay\" WHERE (\"R_Receipt\"='$res_if[R_Receipt]') ORDER BY \"typepay_name\" DESC ");
    $res_ss=pg_fetch_array($qry_ss);
    
    $j+=1;
    $aa+=1;
        $R_Receipt = $res_if["R_Receipt"];
        $R_Date = $res_ss["R_Date"];
        $R_Bank = $res_ss["R_Bank"];
        $PayType = $res_ss["PayType"];
        $IDNO = $res_ss["IDNO"];
        $full_name = $res_ss["full_name"];
        $assetname = $res_ss["assetname"];
        $typepay_name = $res_ss["typepay_name"];
        $regis = $res_ss["regis"];
        
        $value = $res_if["value"];
        $vat = $res_if["vat"];
        $money = $res_if["money"];
        $discount = $res_if["discount"];
        $summary = $res_if["money"]-$res_if["discount"];
        
        $sum_value+=$value;
        $sum_vat+=$vat;
        $sum_money+=$money;
        $sum_discount+=$discount;
        $sum_summary+=$summary;
        
        $sum_value7+=$value;
        $sum_vat7+=$vat;
        $sum_money7+=$money;
        $sum_discount7+=$discount;
        $sum_summary7+=$summary;
        
        $value = number_format($value,2);
        $vat = number_format($vat,2);
        $money = number_format($money,2);
        $discount = number_format($discount,2);
        $summary = number_format($summary,2);
    
        
if($i > 30){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงาน รับค่างวด");
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->MultiCell(285,4,$buss_name,0,'L',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"($pagename) วันที่พิมพ์ $nowdate");
$pdf->MultiCell(174,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"Receipt");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"Date");
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
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(135,30); 
$buss_name=iconv('UTF-8','windows-874',"assetname");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"typepay");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"regis");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"value");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(220,30); 
$buss_name=iconv('UTF-8','windows-874',"vat");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(235,30); 
$buss_name=iconv('UTF-8','windows-874',"money");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(255,30); 
$buss_name=iconv('UTF-8','windows-874',"discount");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(270,30); 
$buss_name=iconv('UTF-8','windows-874',"summary");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

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
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(135,$cline); 
$buss_name=iconv('UTF-8','windows-874',$assetname);
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(165,$cline); 
$buss_name=iconv('UTF-8','windows-874',$typepay_name);
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(185,$cline); 
$buss_name=iconv('UTF-8','windows-874',$regis);
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',$value);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(220,$cline); 
$buss_name=iconv('UTF-8','windows-874',$vat);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(235,$cline); 
$buss_name=iconv('UTF-8','windows-874',$money);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',$discount);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(270,$cline); 
$buss_name=iconv('UTF-8','windows-874',$summary);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$cline+=5; 
$i+=1;       
}


$pdf->SetFont('AngsanaNew','B',11);

        $sum_value = number_format($sum_value,2);
        $sum_vat = number_format($sum_vat,2);
        $sum_money = number_format($sum_money,2);
        $sum_discount = number_format($sum_discount,2);
        $sum_summary = number_format($sum_summary,2);

$pdf->SetXY(151,$cline); 
$buss_name=iconv('UTF-8','windows-874',"รวมยอดเงิน");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);        
        
$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',$sum_value);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(220,$cline); 
$buss_name=iconv('UTF-8','windows-874',$sum_vat);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(235,$cline); 
$buss_name=iconv('UTF-8','windows-874',$sum_money);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',$sum_discount);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(270,$cline); 
$buss_name=iconv('UTF-8','windows-874',$sum_summary);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$cline+=5; 
/* ================================== */

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(5,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',11);

        $sum_value7 = number_format($sum_value7,2);
        $sum_vat7 = number_format($sum_vat7,2);
        $sum_money7 = number_format($sum_money7,2);
        $sum_discount7 = number_format($sum_discount7,2);
        $sum_summary7 = number_format($sum_summary7,2);

$pdf->SetXY(151,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $j รายการ      รวมยอดเงิน");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);        
        
$pdf->SetXY(200,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',$sum_value7);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(220,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',$sum_vat7);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(235,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',$sum_money7);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(255,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',$sum_discount7);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(270,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',$sum_summary7);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

}else{
    
while($res_if=pg_fetch_array($qry_fr)){
    
    $qry_ss=pg_query("select * from \"VFrEachDay\" WHERE (\"R_Receipt\"='$res_if[R_Receipt]') ORDER BY \"typepay_name\" DESC ");
    $res_ss=pg_fetch_array($qry_ss);
    
    $j+=1;
    $aa+=1;
        $R_Receipt = $res_if["R_Receipt"];
        $R_Date = $res_ss["R_Date"];
        $R_Bank = $res_ss["R_Bank"];
        $PayType = $res_ss["PayType"];
        $IDNO = $res_ss["IDNO"];
        $full_name = $res_ss["full_name"];
        $assetname = $res_ss["assetname"];
        $typepay_name = $res_ss["typepay_name"];
        $regis = $res_ss["regis"];
        
        $value = $res_if["value"];
        $vat = $res_if["vat"];
        $money = $res_if["money"];
        $discount = $res_if["discount"];
        $summary = $res_if["money"]-$res_if["discount"];
        
        $sum_value+=$value;
        $sum_vat+=$vat;
        $sum_money+=$money;
        $sum_discount+=$discount;
        $sum_summary+=$summary;
        
        $value = number_format($value,2);
        $vat = number_format($vat,2);
        $money = number_format($money,2);
        $discount = number_format($discount,2);
        $summary = number_format($summary,2);
    
        
if($i > 30){ 
    $pdf->AddPage(); $cline = 37; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงาน รับค่างวด");
$pdf->MultiCell(285,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(285,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $restrn");
$pdf->MultiCell(285,4,$buss_name,0,'L',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"($pagename) วันที่พิมพ์ $nowdate");
$pdf->MultiCell(174,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"No.");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"Receipt");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"Date");
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
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(135,30); 
$buss_name=iconv('UTF-8','windows-874',"assetname");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"typepay");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(185,30); 
$buss_name=iconv('UTF-8','windows-874',"regis");
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"value");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(220,30); 
$buss_name=iconv('UTF-8','windows-874',"vat");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(235,30); 
$buss_name=iconv('UTF-8','windows-874',"money");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(255,30); 
$buss_name=iconv('UTF-8','windows-874',"discount");
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(270,30); 
$buss_name=iconv('UTF-8','windows-874',"summary");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

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
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetXY(135,$cline); 
$buss_name=iconv('UTF-8','windows-874',$assetname);
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(165,$cline); 
$buss_name=iconv('UTF-8','windows-874',$typepay_name);
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(185,$cline); 
$buss_name=iconv('UTF-8','windows-874',$regis);
$pdf->MultiCell(15,4,$buss_name,0,'L',0);

$pdf->SetXY(200,$cline); 
$buss_name=iconv('UTF-8','windows-874',$value);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(220,$cline); 
$buss_name=iconv('UTF-8','windows-874',$vat);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(235,$cline); 
$buss_name=iconv('UTF-8','windows-874',$money);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(255,$cline); 
$buss_name=iconv('UTF-8','windows-874',$discount);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(270,$cline); 
$buss_name=iconv('UTF-8','windows-874',$summary);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$cline+=5; 
$i+=1;       
}

$pdf->SetFont('AngsanaNew','',12);

$pdf->SetXY(5,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"______________________________________________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',11);

        $sum_value = number_format($sum_value,2);
        $sum_vat = number_format($sum_vat,2);
        $sum_money = number_format($sum_money,2);
        $sum_discount = number_format($sum_discount,2);
        $sum_summary = number_format($sum_summary,2);

$pdf->SetXY(151,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"ทั้งหมด $j รายการ      รวมยอดเงิน");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);        
        
$pdf->SetXY(200,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',$sum_value);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(220,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',$sum_vat);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(235,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',$sum_money);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(255,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',$sum_discount);
$pdf->MultiCell(15,4,$buss_name,0,'R',0);

$pdf->SetXY(270,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',$sum_summary);
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
    
}

$pdf->Output();
?>