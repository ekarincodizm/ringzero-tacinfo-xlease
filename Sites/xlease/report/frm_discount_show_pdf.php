<?php
include("../config/config.php");

$yy = $_GET['yy'];

$nowyear = date("Y")+543;
$nowdate = date("d-m-")."$nowyear";

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];

$show_yy = $yy+543;

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',13);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(190,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',17);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานส่วนลดจ่าย");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำปี $show_yy");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(10,26);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(10,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(30,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(70,4,$buss_name,0,'C',0);

$pdf->SetXY(100,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่ปิดบัญชี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(130,32);
$buss_name=iconv('UTF-8','windows-874',"ลูกค้าปี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(160,32);
$buss_name=iconv('UTF-8','windows-874',"ยอดส่วนลดปิดบัญชี");
$pdf->MultiCell(38,4,$buss_name,0,'C',0);

$pdf->SetXY(10,33);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$cline = 38;
$j = 0;
$qry_in=pg_query("SELECT * FROM \"Fp\" where \"P_SL\" <> '0' AND EXTRACT(YEAR FROM \"P_CLDATE\")='$yy' ORDER BY \"IDNO\" ");
while($res_in=pg_fetch_array($qry_in)){
    $j+=1;
    $IDNO = $res_in["IDNO"];
    $P_CLDATE = $res_in["P_CLDATE"];
    $P_CustByYear = $res_in["P_CustByYear"];
    $P_SL = $res_in["P_SL"];
    
    $sum_sl += $P_SL;
    
    $fullname = "";
    $qry_in1=pg_query("SELECT \"full_name\" FROM \"UNContact\" where \"IDNO\" = '$IDNO' ");
    if($res_in2=pg_fetch_array($qry_in1)){
        $fullname = $res_in2["full_name"];
    }
	
	// checkSL
	//$qry_checksl=pg_query("SELECT \"gen_deductInterestByIDNO\"('$IDNO')");
    //if($res_checksl=pg_fetch_array($qry_checksl)){
    //    $csl = $res_checksl["gen_deductInterestByIDNO"];
    //}
	$csl = '';
	

if($j == 40){
$cline = 38;
$j = 0;
$pdf->AddPage();

$pdf->SetFont('AngsanaNew','B',17);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานส่วนลดจ่าย");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำปี $show_yy");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(10,26);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(10,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(30,32);
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(70,4,$buss_name,0,'C',0);

$pdf->SetXY(100,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่ปิดบัญชี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(130,32);
$buss_name=iconv('UTF-8','windows-874',"ลูกค้าปี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(160,32);
$buss_name=iconv('UTF-8','windows-874',"ยอดส่วนลดปิดบัญชี");
$pdf->MultiCell(38,4,$buss_name,0,'C',0);

$pdf->SetXY(10,33);
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);
}

    $qry_date_number=@pg_query("select \"c_date_number\"('$P_CLDATE')");
    $P_CLDATE=@pg_fetch_result($qry_date_number,0);

    $pdf->SetXY(10,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$IDNO");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(35,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$fullname");
    $pdf->MultiCell(65,4,$buss_name,0,'L',0);

    $pdf->SetXY(100,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$P_CLDATE");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

    $pdf->SetXY(130,$cline);
    $buss_name=iconv('UTF-8','windows-874',($P_CustByYear+543));
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

	
	
	$checkSL = number_format($P_SL,2).$csl;
	
    $pdf->SetXY(160,$cline);
    $buss_name=iconv('UTF-8','windows-874',$checkSL);
    $pdf->MultiCell(38,4,$buss_name,0,'R',0);
    
    $cline+=6;
}


    $pdf->SetXY(10,$cline-5); 
    $buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
    $pdf->MultiCell(190,4,$buss_name,0,'C',0);
    
    $pdf->SetXY(138,$cline+1); 
    $buss_name=iconv('UTF-8','windows-874',"ยอดรวมส่วนลดจ่าย ".number_format($sum_sl,2));
    $pdf->MultiCell(60,4,$buss_name,0,'R',0);

$pdf->Output();
?>