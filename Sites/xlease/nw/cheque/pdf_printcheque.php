<?php
session_start();
include("../../config/config.php");

$chqpayID=pg_escape_string($_REQUEST["chqpayID"]);
$addcompany=pg_escape_string($_POST["addcompany"]);

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) พิมพ์เช็คจ่าย', '$add_date')");
//ACTIONLOG---
	
$qrychq=pg_query("select \"cusPay\",\"moneyPay\",\"datePay\",\"typeChq\" from cheque_pay where \"chqpayID\"='$chqpayID'");
$reschq=pg_fetch_array($qrychq);
list($cusPay,$moneyPay,$datePay,$typeChq)=$reschq;

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
   
}

function convert($number){ 
  $txtnum1 = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ'); 
  $txtnum2 = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน'); 
  $number = str_replace(",","",$number); 
  $number = str_replace(" ","",$number); 
  $number = str_replace("บาท","",$number); 
  $number = explode(".",$number); 
  if(sizeof($number)>2){ 
    return 'ทศนิยมหลายตัวนะจ๊ะ'; 
    exit; 
  } 
  $strlen = strlen($number[0]); 
  $convert = ''; 
  for($i=0;$i<$strlen;$i++){ 
    $n = substr($number[0], $i,1); 
    if($n!=0){ 
      if($i==($strlen-1) AND $n==1){ $convert .= 'เอ็ด'; } 
      elseif($i==($strlen-2) AND $n==2){ $convert .= 'ยี่'; } 
      elseif($i==($strlen-2) AND $n==1){ $convert .= ''; } 
      else{ $convert .= $txtnum1[$n]; } 
      $convert .= $txtnum2[$strlen-$i-1]; 
    } 
  } 
  $convert .= 'บาท'; 
  if($number[1]=='0' OR $number[1]=='00' OR $number[1]==''){ 
    $convert .= 'ถ้วน'; 
  }else{ 
    $strlen = strlen($number[1]); 
    for($i=0;$i<$strlen;$i++){ 
      $n = substr($number[1], $i,1); 
      if($n!=0){ 
        if($i==($strlen-1) AND $n==1){$convert .= 'เอ็ด';} 
        elseif($i==($strlen-2) AND $n==2){$convert .= 'ยี่';} 
        elseif($i==($strlen-2) AND $n==1){$convert .= '';} 
        else{ $convert .= $txtnum1[$n];} 
        $convert .= $txtnum2[$strlen-$i-1]; 
      } 
    } 
    $convert .= 'สตางค์'; 
  } 
  return $convert; 
} 

list($y,$m,$d)=explode("-",$datePay);
$date=$d.$m.$y;
$d1=substr($date,0,1);
$d2=substr($date,1,1);
$d3=substr($date,2,1);
$d4=substr($date,3,1);
$d5=substr($date,4,1);
$d6=substr($date,5,1);
$d7=substr($date,6,1);
$d8=substr($date,7,1);

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(136,5);
$title=iconv('UTF-8','windows-874',"$d1    $d2    $d3    $d4    $d5    $d6    $d7    $d8");
$pdf->MultiCell(50,4,$title,0,'R',0);

//เพิ่มชื่อบริษัท
if($addcompany=="1"){
	$pdf->SetFont('AngsanaNew','',12);
	$pdf->SetXY(65,5);
	$buss_name=iconv('UTF-8','windows-874',"บริษัท ไทยเอซ แคปปิตอล จำกัด");
	$pdf->MultiCell(150,4,$buss_name,0,'L',0);
}
$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(48,21);
$buss_name=iconv('UTF-8','windows-874',"$cusPay");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(48,28);
$buss_name=iconv('UTF-8','windows-874',convert($moneyPay));
$pdf->MultiCell(150,6,$buss_name,0,'L',0);


$pdf->SetFont('AngsanaNew','',12);

if($typeChq=="2"){
	$pdf->SetXY(85,52);
	$buss_name=iconv('UTF-8','windows-874',"A/C PAYEE ONLY");
	$pdf->MultiCell(26,4,$buss_name,1,'C',0);
}else if($typeChq=="3"){
	$pdf->SetXY(85,52);
	$buss_name=iconv('UTF-8','windows-874',"&Co.");
	$pdf->MultiCell(26,4,$buss_name,1,'C',0);
}

$pdf->SetFont('AngsanaNew','',22);
$pdf->SetXY(120,35);
$buss_name=iconv('UTF-8','windows-874',number_format($moneyPay,2));
$pdf->MultiCell(65,8,$buss_name,0,'R',0);

$pdf->Output();
?>