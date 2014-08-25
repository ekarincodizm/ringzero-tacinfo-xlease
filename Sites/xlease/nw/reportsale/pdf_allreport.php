<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');

$SelectChart=$_POST["SelectChart"];
$year = $_POST["year"];

if($SelectChart=="a1"){
	$conmonth="";
	$txtcon="ประจำเดือนมกราคม-ธันวาคม";
}else{
	$month = $_POST["month"];
	$txtcon="ประจำเดือน";
	$txtmonth = $_POST["txtmonth"];
	$conmonth="AND (EXTRACT(MONTH FROM a.\"startDate\")='$month')";
};

$nowdate = Date('Y-m-d');

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
$title=iconv('UTF-8','windows-874',"รายงานพนักงานขายแบบรวม");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$monthyear=iconv('UTF-8','windows-874',"$txtcon$txtmonth ค.ศ. $year");
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
$buss_name=iconv('UTF-8','windows-874',"รหัสพนักงาน");
$pdf->MultiCell(20,6,$buss_name,1,'C',0);

$pdf->SetXY(50,35);
$buss_name=iconv('UTF-8','windows-874',"ชื่อพนักงาน");
$pdf->MultiCell(60,6,$buss_name,1,'C',0);

$pdf->SetXY(110,35);
$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่ทำได้");
$pdf->MultiCell(36,6,$buss_name,1,'C',0);

$pdf->SetXY(146,35);
$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อที่ปล่อย");
$pdf->MultiCell(30,6,$buss_name,1,'C',0);



$cline = 41;

//ดึงค่างวดสูงสุด ต่ำสุด และ limit ล่าสุดขึ้นมาเพื่อนำมาตรวจสอบตามเงื่อนไข
$query=pg_query("select a.\"id_user\",b.\"fullname\",count(a.\"IDNO\") as numidno,sum(c.\"P_BEGIN\") as sumbeginx from \"nw_startDateFp\" a
left join \"Vfuser\" b on a.\"id_user\" = b.\"id_user\"
left join \"Fp\" c on a.\"IDNO\" = c.\"IDNO\" where EXTRACT(YEAR FROM a.\"startDate\")='$year' $conmonth
group by a.\"id_user\",b.\"fullname\" order by a.\"id_user\" ");
$numrows=pg_num_rows($query);
$sumidno=0;
$sumbegin=0;
$nub=0;
while($result=pg_fetch_array($query)){
	$id_user=$result["id_user"];
	$fullname=$result["fullname"];
	$numidno=$result["numidno"];
	$beginx =$result["sumbeginx"];
	$sumbeginx=number_format($beginx,2);

	if($nub > 36){
		$nub = 0;
		$cline = 41;
		$pdf->AddPage();
		
		/*Header of Table*/
		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(30,35);
		$buss_name=iconv('UTF-8','windows-874',"รหัสพนักงาน");
		$pdf->MultiCell(20,6,$buss_name,1,'C',0);

		$pdf->SetXY(50,35);
		$buss_name=iconv('UTF-8','windows-874',"ชื่อพนักงาน");
		$pdf->MultiCell(60,6,$buss_name,1,'C',0);

		$pdf->SetXY(110,35);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนสัญญาที่ทำได้");
		$pdf->MultiCell(36,6,$buss_name,1,'C',0);

		$pdf->SetXY(146,35);
		$buss_name=iconv('UTF-8','windows-874',"ยอดสินเชื่อที่ปล่อย");
		$pdf->MultiCell(30,6,$buss_name,1,'C',0);
	}
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetXY(30,$cline);
	$buss_name=iconv('UTF-8','windows-874',$id_user);
	$pdf->MultiCell(20,6,$buss_name,1,'C',0);

	$pdf->SetXY(50,$cline);
	$buss_name=iconv('UTF-8','windows-874',$fullname);
	$pdf->MultiCell(60,6,$buss_name,1,'L',0);

	$pdf->SetXY(110,$cline);
	$buss_name=iconv('UTF-8','windows-874',$numidno);
	$pdf->MultiCell(36,6,$buss_name,1,'C',0);

	$pdf->SetXY(146,$cline);
	$buss_name=iconv('UTF-8','windows-874',"$sumbeginx");
	$pdf->MultiCell(30,6,$buss_name,1,'R',0);
	
	$cline = $cline +6;								
	$sumidno = $sumidno+$numidno;
	$sumbegin = $sumbegin+$beginx;
	$nub++;
}
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(30,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(80,6,$buss_name,1,'R',0);

$pdf->SetXY(110,$cline);
$buss_name=iconv('UTF-8','windows-874',$sumidno);
$pdf->MultiCell(36,6,$buss_name,1,'C',0);

$pdf->SetXY(146,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sumbegin,2));
$pdf->MultiCell(30,6,$buss_name,1,'R',0);
	
$pdf->Output();
?>