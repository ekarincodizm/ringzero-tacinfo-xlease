<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');

$y1 = $_POST["y1"];
$y1_1=$y1+543;

$y2 = $_POST["y2"];
$y2_1=$y2+543;

$m1 = $_POST["m1"];
$m2 = $_POST["m2"];

$nowdate = Date('Y-m-d');
$dd=substr($nowdate,8,2);
$mm=substr($nowdate,5,2);
$yy=substr($nowdate,0,4);
$yy=$yy+543;
$nowdate=$dd."-".$mm."-".$yy;

for($j=1;$j<=2;$j++){
	if($j==1){
		$month=$m1;
	}else{
		$month=$m2;
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
	if($j==1){
		$month1=$txtmonth;
	}else{
		$month2=$txtmonth;
	}
}	
//------------------- PDF -------------------//
class PDF extends ThaiPDF
{
    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(195,4,$buss_name,0,'R',0);
 
    }
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานสินเชื่อในช่วงปี");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$monthyear=iconv('UTF-8','windows-874',"ปี พ.ศ.$y1_1 - $y2_1 (เดือน$month1 - $month2)");
$pdf->MultiCell(190,4,$monthyear,0,'C',0);

$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','B',13);
$pdf->SetXY(6,25);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(150,4,$buss_name,0,'L',0);

$pdf->SetXY(155,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์: $nowdate");
$pdf->MultiCell(50,4,$buss_name,0,'R',0);

/*Header of Table*/
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(30,35);
$buss_name=iconv('UTF-8','windows-874',"ปี พ.ศ.");
$pdf->MultiCell(30,6,$buss_name,1,'C',0);

$pdf->SetXY(60,35);
$buss_name=iconv('UTF-8','windows-874',"ยอดสัญญารวม");
$pdf->MultiCell(30,6,$buss_name,1,'C',0);

$pdf->SetXY(90,35);
$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อรวม");
$pdf->MultiCell(36,6,$buss_name,1,'C',0);

$pdf->SetXY(126,35);
$buss_name=iconv('UTF-8','windows-874',"ยอดเฉลี่ยของสินเชื่อแต่ละสัญญา");
$pdf->MultiCell(50,6,$buss_name,1,'C',0);

$cline = 41;
for($y=$y1;$y<=$y2;$y++){
	$txty=$y+543; //ปี พ.ศ.ที่ต้องการให้แสดง
			
	//ค้นหาข้อมูลในแต่ละปี
	$query=pg_query("select count(\"IDNO\") as numidno,sum(\"P_BEGIN\") as sumbeginx from \"Fp\" 
	where ((EXTRACT(MONTH FROM \"P_STDATE\")between '$m1' and '$m2') AND EXTRACT(YEAR FROM \"P_STDATE\")='$y')");
	
	$numrow=pg_num_rows($query);
	$sumidno=0;
	$sumbegin=0;
	while($result=pg_fetch_array($query)){
		$numidno=$result["numidno"]; //จำนวนสัญญา
		$beginx =$result["sumbeginx"]; //ยอดสินเชื่อ
		$sumbeginx=number_format($beginx,2);
								
		$sumidno = $sumidno+$numidno; //รวมจำนวนสัญญา
		$sumbegin = $sumbegin+$beginx; //รวมยอดสินเชื่อ		
	}
	if($numrow==0){
		$sumidno=0;
		$sumbegin=0;
	}
			
	if($sumidno == 0){
		$avg="0.00";
	}else{
		$avg=number_format(($sumbegin/$sumidno),2);
	}
	
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(30,$cline);
	$buss_name=iconv('UTF-8','windows-874',$txty);
	$pdf->MultiCell(30,6,$buss_name,1,'C',0);

	$pdf->SetXY(60,$cline);
	$buss_name=iconv('UTF-8','windows-874',$sumidno);
	$pdf->MultiCell(30,6,$buss_name,1,'C',0);

	$pdf->SetXY(90,$cline);
	$buss_name=iconv('UTF-8','windows-874',number_format($sumbegin,2));
	$pdf->MultiCell(36,6,$buss_name,1,'R',0);

	$pdf->SetXY(126,$cline);
	$buss_name=iconv('UTF-8','windows-874',$avg);
	$pdf->MultiCell(50,6,$buss_name,1,'R',0);
	
	$cline = $cline +6;	
	$allidno=$allidno+$sumidno;
	$allbegin=$allbegin+$sumbegin;
}
	
if($allidno==0){
	$allavg="0.00";
}else{
	$allavg=$allbegin/$allidno;
}

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(30,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(30,6,$buss_name,1,'R',0);

$pdf->SetXY(60,$cline);
$buss_name=iconv('UTF-8','windows-874',$allidno);
$pdf->MultiCell(30,6,$buss_name,1,'C',0);

$pdf->SetXY(90,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($allbegin,2));
$pdf->MultiCell(36,6,$buss_name,1,'R',0);

$pdf->SetXY(126,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($allavg,2));
$pdf->MultiCell(50,6,$buss_name,1,'R',0);
	
$pdf->Output();
?>