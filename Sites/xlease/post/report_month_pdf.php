<?php
include("../config/config.php");
set_time_limit (0); 
ini_set("memory_limit","256M"); 

$s_mon=$_GET["m"];
$s_yea=$_GET["y"];

if($s_mon == '1') { $s_c= "มกราคม"; } else
if($s_mon == '2') {  $s_c= "กุมพาพันธ์"; } else
if($s_mon == '3') { $s_c= "มีนาคม"; } else
if($s_mon == '4') {  $s_c= "เมษายน"; } else
if($$s_mon== '5') {  $s_c= "พฤษภาคม"; } else
if($s_mon == '6') {  $s_c= "มิถุนายน"; } else
if($s_mon == '7') {  $s_c= "กรกฏาคม"; } else
if($s_mon == '8') {  $s_c= "สิืงหาคม"; } else
if($s_mon == '9') {  $s_c= "กันยายน"; } else
if($s_mon == '10') {  $s_c= "ตุลาคม"; } else
if($s_mon == '11') {  $s_c= "พฤศจิกายน"; } else {  $s_c= "ธันวาคม"; }

$sthais_year=$s_yea+543;
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
$title=iconv('UTF-8','windows-874',"รายงานประจำเดือน");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"$s_c  พ.ศ.$sthais_year");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(120,23);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(80,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"no.");
$pdf->MultiCell(10,4,$buss_name,0,'L',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"IDNO");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(35,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อ - นามสกุล");
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(75,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"วันทำสัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(115,30); 
$buss_name=iconv('UTF-8','windows-874',"TranID Ref1");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(135,30); 
$buss_name=iconv('UTF-8','windows-874',"TranID Ref2");
$pdf->MultiCell(20,4,$buss_name,0,'L',0);

$pdf->SetXY(155,30); 
$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
$pdf->MultiCell(18,4,$buss_name,0,'L',0);

$pdf->SetXY(173,30); 
$buss_name=iconv('UTF-8','windows-874',"comefrom");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;

$qry_rpt=pg_query("SELECT A.*,B.\"TranIDRef1\",B.\"TranIDRef2\",B.\"ComeFrom\" FROM \"VContact\" A
                    INNER JOIN \"Fp\" B ON B.\"IDNO\"=A.\"IDNO\" 
					where   EXTRACT(MONTH FROM B.\"P_STDATE\")='$s_mon' AND EXTRACT(YEAR FROM B.\"P_STDATE\")='$s_yea' order by A.\"IDNO\" ");
$count=pg_num_rows($qry_rpt);
$a=0;
while($reslast=pg_fetch_array($qry_rpt)){
	if(substr($reslast["IDNO"],6,2) != 22){
		$trn_cdate=pg_query("select c_datethai('$reslast[P_STDATE]')");
		$res_cdate=pg_fetch_result($trn_cdate,0);    
        $a++;
		if($i > 45){ 
			$pdf->AddPage(); $cline = 37; $i=1; 

			$pdf->SetFont('AngsanaNew','B',15);
			$pdf->SetXY(10,10);
			$title=iconv('UTF-8','windows-874',"รายงาน  ออก NT");
			$pdf->MultiCell(190,4,$title,0,'C',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(10,16);
			$buss_name=iconv('UTF-8','windows-874',"$s_c  พ.ศ.$sthais_year");
			$pdf->MultiCell(190,4,$buss_name,0,'C',0);

			$pdf->SetXY(120,23);
			$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
			$pdf->MultiCell(80,4,$buss_name,0,'R',0);

			$pdf->SetXY(4,24); 
			$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(196,4,$buss_name,0,'C',0);

			$pdf->SetXY(5,30); 
			$buss_name=iconv('UTF-8','windows-874',"no.");
			$pdf->MultiCell(10,4,$buss_name,0,'L',0);

			$pdf->SetXY(15,30); 
			$buss_name=iconv('UTF-8','windows-874',"IDNO");
			$pdf->MultiCell(20,4,$buss_name,0,'L',0);

			$pdf->SetXY(35,30); 
			$buss_name=iconv('UTF-8','windows-874',"ชื่อ - นามสกุล");
			$pdf->MultiCell(40,4,$buss_name,0,'L',0);

			$pdf->SetXY(75,30); 
			$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
			$pdf->MultiCell(20,4,$buss_name,0,'L',0);

			$pdf->SetXY(95,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันทำสัญญา");
			$pdf->MultiCell(20,4,$buss_name,0,'L',0);

			$pdf->SetXY(115,30); 
			$buss_name=iconv('UTF-8','windows-874',"TranID Ref1");
			$pdf->MultiCell(20,4,$buss_name,0,'L',0);

			$pdf->SetXY(135,30); 
			$buss_name=iconv('UTF-8','windows-874',"TranID Ref2");
			$pdf->MultiCell(20,4,$buss_name,0,'L',0);

			$pdf->SetXY(155,30); 
			$buss_name=iconv('UTF-8','windows-874',"เงินต้น");
			$pdf->MultiCell(18,4,$buss_name,0,'L',0);

			$pdf->SetXY(173,30); 
			$buss_name=iconv('UTF-8','windows-874',"comefrom");
			$pdf->MultiCell(30,4,$buss_name,0,'L',0);
			
			$pdf->SetXY(4,32); 
			$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
			$pdf->MultiCell(196,4,$buss_name,0,'C',0);
		}

		$pdf->SetFont('AngsanaNew','',10); 

		$pdf->SetXY(5,$cline); 
		$buss_name=iconv('UTF-8','windows-874',$a);
		$pdf->MultiCell(10,4,$buss_name,0,'L',0);

		$pdf->SetXY(15,$cline); 
		$buss_name=iconv('UTF-8','windows-874',$reslast["IDNO"]);
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetXY(35,$cline); 
		$buss_name=iconv('UTF-8','windows-874',$reslast["full_name"]);
		$pdf->MultiCell(40,4,$buss_name,0,'L',0);

		if($reslast["C_REGIS"]==""){
			$rec_regis=$reslast["car_regis"];
		}else{
			$rec_regis=$reslast["C_REGIS"];
		}
		$pdf->SetXY(75,$cline); 
		$buss_name=iconv('UTF-8','windows-874',$rec_regis);
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetXY(95,$cline); 
		$buss_name=iconv('UTF-8','windows-874',$res_cdate);
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);

		$pdf->SetXY(115,$cline); 
		$buss_name=iconv('UTF-8','windows-874',$reslast["TranIDRef1"]);
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(135,$cline); 
		$buss_name=iconv('UTF-8','windows-874',$reslast["TranIDRef2"]);
		$pdf->MultiCell(20,4,$buss_name,0,'L',0);
		
		$pdf->SetXY(155,$cline); 
		$buss_name=iconv('UTF-8','windows-874',number_format($reslast["P_BEGINX"],2));
		$pdf->MultiCell(18,4,$buss_name,0,'L',0);

		$pdf->SetXY(173,$cline); 
		$buss_name=iconv('UTF-8','windows-874',$reslast["ComeFrom"]);
		$pdf->MultiCell(30,4,$buss_name,0,'L',0);

		$cline+=5; 
		$sum_begin=$sum_begin+$reslast["P_BEGINX"];  
	}
	$i++;
}
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"รวมยอด $a รายการ");
$pdf->MultiCell(196,4,$buss_name,0,'L',0);
		
$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->Output();
?>