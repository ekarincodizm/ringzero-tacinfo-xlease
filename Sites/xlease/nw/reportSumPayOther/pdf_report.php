<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');

$year1=$_POST["year1"];
$y1=$year1+543;
$year2=$_POST["year2"];
$y2=$year2+543;
$nubyear=($year2-$year1)+1;
$clinehead=$nubyear * 30;

$TypeID=$_POST["TypeID"];

$query_type=pg_query("select \"TName\" from \"TypePay\" where \"TypeID\"='$TypeID'");
$res_type=pg_fetch_array($query_type);
$TName=$res_type["TName"];

$nowdate = Date('Y-m-d');
$d=substr($nowdate,8,2);
$m=substr($nowdate,5,2);
$y=substr($nowdate,0,4);
$y=$y+543;
$nowdate=$d."-".$m."-".$y;


//------------------- PDF -------------------//
class PDF extends ThaiPDF
{
  
}
if($nubyear <=5){
	$pdf=new PDF('P' ,'mm','a4');
	$col=190;
}else{
	$pdf=new PDF('L' ,'mm','a4');
	$col=270;
}
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานสรุปรายได้อื่นๆ ($TName)");
$pdf->MultiCell($col,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$monthyear=iconv('UTF-8','windows-874',"ตั้งแต่ปี พ.ศ.$y1 ถึงปี พ.ศ.$y2");
$pdf->MultiCell($col,4,$monthyear,0,'C',0);

$pdf->SetXY(10,26);
if($nubyear <=5){
	$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________");
}else{
	$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________________________________________________");
}
$pdf->MultiCell($col,4,$buss_name,0,'C',0);
$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(270,4,$buss_name,0,'L',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell($col,4,$buss_name,0,'R',0);

if($nubyear ==2){
	$xxx=65;
}else if($nubyear ==3){
	$xxx=50;
}else if($nubyear ==4){
	$xxx=30;
}else if($nubyear ==5){
	$xxx=20;
}else if($nubyear==6){
	$xxx=40;
}else if($nubyear==7){
	$xxx=30;
}else if($nubyear==8){
	$xxx=15;
}
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY($xxx,35);
$buss_name=iconv('UTF-8','windows-874',"เดือน");
$pdf->MultiCell(20,12,$buss_name,1,'C',0);

$pdf->SetXY($xxx+20,35);
$buss_name=iconv('UTF-8','windows-874',"รายได้/ปี พ.ศ.(บาท)");
$pdf->MultiCell($clinehead,6,$buss_name,1,'C',0);

$xx=0;
for($yy=$y1;$yy<=$y2;$yy++){
	$pdf->SetXY(($xxx+20)+$xx,41);
	$buss_name=iconv('UTF-8','windows-874',"$yy");
	$pdf->MultiCell(30,6,$buss_name,1,'C',0);
	$xx=$xx+30;
}
$cline = 47;	
for($month=1;$month<=12;$month++){
	if($month <= "9"){
		$month="0".$month;
	}
		
	if($month=="01"){
		$txtmonth="มกราคม";
	}else if($month=="02"){
		$txtmonth="กุมภาพันธ์";
	}else if($month=="03"){
		$txtmonth="มีนาคม";
	}else if($month=="04"){
		$txtmonth="เมษายน";
	}else if($month=="05"){
		$txtmonth="พฤษภาคม";
	}else if($month=="06"){
		$txtmonth="มิถุนายน";
	}else if($month=="07"){
		$txtmonth="กรกฎาคม";
	}else if($month=="08"){
		$txtmonth="สิงหาคม";
	}else if($month=="09"){
		$txtmonth="กันยายน";
	}else if($month=="10"){
		$txtmonth="ตุลาคม";
	}else if($month=="11"){
		$txtmonth="พฤศจิกายน";
	}else if($month=="12"){
		$txtmonth="ธันวาคม";
	}
	
	if($nubyear ==2){
		$xxx=65;
	}else if($nubyear ==3){
		$xxx=50;
	}else if($nubyear ==4){
		$xxx=30;
	}else if($nubyear ==5){
		$xxx=20;
	}else if($nubyear==6){
		$xxx=40;
	}else if($nubyear==7){
		$xxx=30;
	}else if($nubyear==8){
		$xxx=15;
	}
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY($xxx,$cline);
	$buss_name=iconv('UTF-8','windows-874',$txtmonth);
	$pdf->MultiCell(20,6,$buss_name,1,'L',0);//วนเดือน
	
	$sumx=0;	
	for($yy=$year1;$yy<=$year2;$yy++){
		$query=pg_query("select sum(\"O_MONEY\") as money from \"FOtherpay\" where \"O_Type\"='$TypeID' and (EXTRACT(MONTH FROM \"O_DATE\")='$month' AND EXTRACT(YEAR FROM \"O_DATE\")='$yy')");
		$num_sum=pg_num_rows($query);
		if($num_sum==0){
			$money=0;
		}else{
			$res_sum=pg_fetch_array($query);
			$money=$res_sum["money"];
		}
		$money=number_format($money,2);
			
		$pdf->SetXY(($xxx+20)+$sumx,$cline);
		$buss_name=iconv('UTF-8','windows-874',$money);
		$pdf->MultiCell(30,6,$buss_name,1,'R',0);
		$sumx=$sumx+30;
	}
	$cline = $cline +6;			
}
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY($xxx,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(20,6,$buss_name,1,'R',0);

$sumx=0;	
for($yy=$year1;$yy<=$year2;$yy++){
	$querysum=pg_query("select sum(\"O_MONEY\") as money2 from \"FOtherpay\" where \"O_Type\"='$TypeID' and  EXTRACT(YEAR FROM \"O_DATE\")='$yy'");
	$num_sum2=pg_num_rows($querysum);
	if($num_sum2==0){
		$money=0;
	}else{
		$res_sum2=pg_fetch_array($querysum);
		$money2=$res_sum2["money2"];
	}
	$money2=number_format($money2,2);
		
	$pdf->SetXY(($xxx+20)+$sumx,$cline);
	$buss_name=iconv('UTF-8','windows-874',$money2);
	$pdf->MultiCell(30,6,$buss_name,1,'R',0);
	$sumx=$sumx+30;
}

		
$pdf->Output();
?>