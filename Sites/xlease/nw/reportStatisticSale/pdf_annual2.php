<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');

$y1=$_POST["y1"];
$y1_1=$y1+543;
$y2=$_POST["y2"];
$y2_1=$y2+543;

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

$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานสินเชื่อประจำปี");
$pdf->MultiCell(270,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$monthyear=iconv('UTF-8','windows-874',"เปรียบเทียบระหว่างปี พ.ศ.$y1_1 และ ปี พ.ศ.$y2_1");
$pdf->MultiCell(270,4,$monthyear,0,'C',0);

$pdf->SetXY(10,26);
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(270,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(270,4,$buss_name,0,'L',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(270,4,$buss_name,0,'R',0);

$sumx=0;
for($j=1;$j<=2;$j++){
	if($j==1){
		$year=$y1;
	}else{
		$year=$y2;
	}
	$txty=$year+543;
		
	/*Header of Table*/
	$pdf->SetFont('AngsanaNew','B',14);
	
	$pdf->SetXY(10+$sumx,35);
	$buss_name=iconv('UTF-8','windows-874',"ปี พ.ศ.$txty");
	$pdf->MultiCell(30,6,$buss_name,0,'L',0);
	
	$pdf->SetXY(10+$sumx,41);
	$buss_name=iconv('UTF-8','windows-874',"เดือน");
	$pdf->MultiCell(30,6,$buss_name,1,'C',0);

	$pdf->SetXY(40+$sumx,41);
	$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญา");
	$pdf->MultiCell(25,6,$buss_name,1,'C',0);

	$pdf->SetXY(65+$sumx,41);
	$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อ");
	$pdf->MultiCell(25,6,$buss_name,1,'C',0);

	$pdf->SetXY(90+$sumx,41);
	$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อเฉลี่ยต่อสัญญา");
	$pdf->MultiCell(45,6,$buss_name,1,'C',0);

	$cline = 47;
	$allidno=0;
	$allbegin=0;
	for($i=1;$i<=12;$i++){
		if($i < 10){
			$month="0".$i;
		}else{
			$month=$i;
		}
							
		$query=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
		where (EXTRACT(MONTH FROM \"P_STDATE\")='$month' AND EXTRACT(YEAR FROM \"P_STDATE\")='$year')");
		$sumidno=0;
		$sumbegin=0;
								
		while($result=pg_fetch_array($query)){
			$numidno=$result["numidno"]; //จำนวนสัญญา
			$beginx =$result["sumbeginx"]; //ยอดสินเชื่อ
			$sumbeginx=number_format($beginx,2);
								
			$sumidno = $sumidno+$numidno; //รวมจำนวนสัญญา
			$sumbegin = $sumbegin+$beginx; //รวมยอดสินเชื่อ		
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
			
		if($sumidno == 0){
			$avg="0.00";
		}else{
			$avg=number_format(($sumbegin/$sumidno),2);
		}

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(10+$sumx,$cline);
		$buss_name=iconv('UTF-8','windows-874',$txtmonth);
		$pdf->MultiCell(30,6,$buss_name,1,'L',0);

		$pdf->SetXY(40+$sumx,$cline);
		$buss_name=iconv('UTF-8','windows-874',$sumidno);
		$pdf->MultiCell(25,6,$buss_name,1,'C',0);

		$pdf->SetXY(65+$sumx,$cline);
		$buss_name=iconv('UTF-8','windows-874',number_format($sumbegin,2));
		$pdf->MultiCell(25,6,$buss_name,1,'R',0);

		$pdf->SetXY(90+$sumx,$cline);
		$buss_name=iconv('UTF-8','windows-874',$avg);
		$pdf->MultiCell(45,6,$buss_name,1,'R',0);
	
		$cline = $cline +6;								
		$allidno=$allidno+$sumidno;
		$allbegin=$allbegin+$sumbegin;
	} //จบลูป for   
	
	if($allidno==0){
		$allavg="0.00";
	}else{
		$allavg=$allbegin/$allidno;
	}

	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetXY(10+$sumx,$cline);
	$buss_name=iconv('UTF-8','windows-874',"รวม");
	$pdf->MultiCell(30,6,$buss_name,1,'R',0);

	$pdf->SetXY(40+$sumx,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($allidno));
	$pdf->MultiCell(25,6,$buss_name,1,'C',0);

	$pdf->SetXY(65+$sumx,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($allbegin,2));
	$pdf->MultiCell(25,6,$buss_name,1,'R',0);

	$pdf->SetXY(90+$sumx,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($allavg,2));
	$pdf->MultiCell(45,6,$buss_name,1,'R',0);
	$sumx=145;
}
	
$pdf->Output();
?>