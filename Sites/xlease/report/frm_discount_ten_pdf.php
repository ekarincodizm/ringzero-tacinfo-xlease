<?php
include("../config/config.php");

$yy = $_GET['yy'];
$nowdate = date("Y/m/d");
$nowyear = date('Y');
$yearlater = 10;

$nowyear2 = date("Y")+543;
$nowdate = date("d-m-")."$nowyear2";

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
        $this->MultiCell(280,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('L' ,'mm','a4');
$pdf->AliasNbPages('tp');
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',17);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานส่วนลดจ่าย");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(280,4,$buss_name,0,'R',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำปี $show_yy");
$pdf->MultiCell(280,4,$buss_name,0,'L',0);

$pdf->SetXY(10,26);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(10,32);
$buss_name=iconv('UTF-8','windows-874',"เดือน");
$pdf->MultiCell(23,4,$buss_name,0,'C',0);

$ln = 33;
for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
    $pdf->SetXY($ln,32);
    $buss_name=iconv('UTF-8','windows-874',"ปี ".($i+543));
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    $ln += 23;
}

$pdf->SetXY($ln,32);
$buss_name=iconv('UTF-8','windows-874',"รวม/เดือน");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(10,33);
$buss_name=iconv('UTF-8','windows-874',"_______________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$cline = 38;

$qry_in=pg_query("SELECT * FROM \"Fp\" where \"P_SL\" <> '0' AND EXTRACT(YEAR FROM \"P_CLDATE\")='$yy' ORDER BY \"IDNO\" ");
while($res_in=pg_fetch_array($qry_in)){
    $j+=1;
    $IDNO = $res_in["IDNO"];
    $P_CLDATE = $res_in["P_CLDATE"];
    $P_CustByYear = $res_in["P_CustByYear"];
    $P_SL = $res_in["P_SL"];

    list($n_year,$n_month,$n_day) = split('-',$P_CLDATE);
    $n_month = number_format($n_month);
    $sum[$P_CustByYear][$n_month] += $P_SL;
}//end while


for($d=1; $d<=12; $d++){
    
    $pdf->SetXY(10,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$d");
    $pdf->MultiCell(23,4,$buss_name,0,'C',0);

    $ln = 33;
    for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
        $money = number_format($sum[$i][$d],2);
        $sum_vertical[$i] += $sum[$i][$d];
        $sum_horizontal += $sum[$i][$d];

        $pdf->SetXY($ln,$cline);
        $buss_name=iconv('UTF-8','windows-874',"$money");
        $pdf->MultiCell(23,4,$buss_name,0,'R',0);
        $ln += 23;
    }
    $sum_fm_horizontal = number_format($sum_horizontal,2);
    
    $pdf->SetXY($ln,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$sum_fm_horizontal");
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);
    
    $sum_horizontal = 0;
    
    $cline+=6;
}

    $pdf->SetXY(10,$cline);
    $buss_name=iconv('UTF-8','windows-874',"รวม/ปี");
    $pdf->MultiCell(23,4,$buss_name,0,'C',0);
    
    $ln = 33;
    $sum_all = array_sum($sum_vertical);
    for($i=$nowyear; $i>($nowyear-$yearlater); $i--){
        $sum_fm_vertical = number_format($sum_vertical[$i],2);
        $pdf->SetXY($ln,$cline);
        $buss_name=iconv('UTF-8','windows-874',"$sum_fm_vertical");
        $pdf->MultiCell(23,4,$buss_name,0,'R',0);
        $ln += 23;
    }    

    $sum_all = number_format($sum_all,2);
    $pdf->SetXY($ln,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$sum_all");
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);

$pdf->Output();
?>