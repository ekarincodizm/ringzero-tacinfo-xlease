<?php
include("../config/config.php");

$mm = $_GET['mm'];
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
$title=iconv('UTF-8','windows-874',"สมุดรายวันซื้อ");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(190,4,$buss_name,0,'L',0);

$pdf->SetXY(10,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(190,4,$buss_name,0,'R',0);

$pdf->SetXY(10,26); 
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(8,32); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(25,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,32); 
$buss_name=iconv('UTF-8','windows-874',"ซื้อมาจาก");
$pdf->MultiCell(45,4,$buss_name,0,'C',0);

$pdf->SetXY(90,32); 
$buss_name=iconv('UTF-8','windows-874',"ยี่ห้อรถยนต์");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(134,32); 
$buss_name=iconv('UTF-8','windows-874',"มูลค่า");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(159,32); 
$buss_name=iconv('UTF-8','windows-874',"VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(179,32); 
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(10,33); 
$buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$cline = 38;
$j = 0;
$j = 0;
$qry_in=pg_query("SELECT * FROM account.\"AccountBookHead\" where EXTRACT(MONTH FROM \"acb_date\")='$mm' AND EXTRACT(YEAR FROM \"acb_date\")='$yy' AND \"cancel\"='false' ORDER BY \"acb_date\" ");
while($res_in=pg_fetch_array($qry_in)){
    $auto_id = $res_in["auto_id"];
    $acb_detail = $res_in["acb_detail"];
    $acb_date = $res_in["acb_date"];
        $strDate = date("d",strtotime($acb_date));

    $qry_in3=pg_query("SELECT * FROM account.\"BookBuy\" where \"bh_id\"='$auto_id' ");
    if($res_in3=pg_fetch_array($qry_in3)){
        $buy_from = $res_in3["buy_from"];
        $buy_receiptno = $res_in3["buy_receiptno"];
        $pay_buy = $res_in3["pay_buy"];
        $to_hp_id = $res_in3["to_hp_id"];
    }else{
        continue;
    }
        
    $sum_AmtDr = 0;
    $qry_in2=pg_query("SELECT * FROM account.\"AccountBookDetail\" where \"autoid_abh\"='$auto_id' AND \"AcID\"='4700' AND \"AmtDr\" <> '0' AND \"AmtCr\" = '0' ORDER BY \"auto_id\" ");
    if($res_in2=pg_fetch_array($qry_in2)){
        $sum_AmtDr += $res_in2["AmtDr"];
    }
    
    if($sum_AmtDr == 0){
        continue;
    }
    
    $sum_vat = 0;
    $qry_in4=pg_query("SELECT \"AmtDr\" FROM account.\"AccountBookDetail\" where \"autoid_abh\"='$auto_id' AND \"AcID\"<>'4700' AND \"AmtDr\" <> '0' AND \"AmtCr\" = '0' ORDER BY \"auto_id\" ");
    if($res_in4=pg_fetch_array($qry_in4)){
        $sum_vat += $res_in4["AmtDr"];
    }

    $qry_in5=pg_query("SELECT \"C_CARNAME\" FROM \"UNContact\" where \"IDNO\"='$to_hp_id' ");
    if($res_in5=pg_fetch_array($qry_in5)){
        $C_CARNAME = $res_in5["C_CARNAME"];
    }
    
    $j++;
    
if($inub == 40){
    $inub = 1;
    $cline = 38;
    $pdf->AddPage();
    $pdf->SetFont('AngsanaNew','B',17);
    $pdf->SetXY(10,10);
    $title=iconv('UTF-8','windows-874',"สมุดรายวันซื้อ");
    $pdf->MultiCell(190,4,$title,0,'C',0);

    $pdf->SetFont('AngsanaNew','',15);
    $pdf->SetXY(10,16);
    $buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
    $pdf->MultiCell(190,4,$buss_name,0,'C',0);

    $pdf->SetXY(10,25);
    $buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
    $pdf->MultiCell(190,4,$buss_name,0,'L',0);

    $pdf->SetXY(10,25);
    $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
    $pdf->MultiCell(190,4,$buss_name,0,'R',0);

    $pdf->SetXY(10,26); 
    $buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
    $pdf->MultiCell(190,4,$buss_name,0,'C',0);

    $pdf->SetXY(8,32); 
    $buss_name=iconv('UTF-8','windows-874',"วันที่");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);

    $pdf->SetXY(25,32); 
    $buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(45,32); 
    $buss_name=iconv('UTF-8','windows-874',"ซื้อมาจาก");
    $pdf->MultiCell(45,4,$buss_name,0,'C',0);

    $pdf->SetXY(90,32); 
    $buss_name=iconv('UTF-8','windows-874',"ยี่ห้อรถยนต์");
    $pdf->MultiCell(40,4,$buss_name,0,'C',0);

    $pdf->SetXY(134,32); 
    $buss_name=iconv('UTF-8','windows-874',"มูลค่า");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(159,32); 
    $buss_name=iconv('UTF-8','windows-874',"VAT");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(179,32); 
    $buss_name=iconv('UTF-8','windows-874',"รวม");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(10,33); 
    $buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
    $pdf->MultiCell(190,4,$buss_name,0,'C',0);
}
    
	$pdf->SetFont('AngsanaNew','',12);
	
    $pdf->SetXY(8,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"$strDate");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);

    $pdf->SetXY(22,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"$to_hp_id");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(48,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"$buy_from");
    $pdf->MultiCell(45,4,$buss_name,0,'L',0);

    $pdf->SetXY(92,$cline); 
    $buss_name=iconv('UTF-8','windows-874',"$C_CARNAME");
    $pdf->MultiCell(40,4,$buss_name,0,'L',0);

    $pdf->SetXY(130,$cline); 
    $buss_name=iconv('UTF-8','windows-874',number_format($sum_AmtDr,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

    $pdf->SetXY(157,$cline); 
    $buss_name=iconv('UTF-8','windows-874',number_format($sum_vat,2));
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);

    $pdf->SetXY(175,$cline); 
    $buss_name=iconv('UTF-8','windows-874',number_format($sum_AmtDr+$sum_vat,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);
    
    $cline+=6;

    $sum1 += $sum_AmtDr;
    $sum2 += $sum_vat;
    $sum3 += ($sum_AmtDr+$sum_vat);
    $inub++;
}

if($inub == 40){
    $inub = 1;
    $cline = 38;
    $pdf->AddPage();
    $pdf->SetFont('AngsanaNew','B',17);
    $pdf->SetXY(10,10);
    $title=iconv('UTF-8','windows-874',"สมุดรายวันซื้อ");
    $pdf->MultiCell(190,4,$title,0,'C',0);

    $pdf->SetFont('AngsanaNew','',15);
    $pdf->SetXY(10,16);
    $buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
    $pdf->MultiCell(190,4,$buss_name,0,'C',0);

    $pdf->SetXY(10,25);
    $buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
    $pdf->MultiCell(190,4,$buss_name,0,'L',0);

    $pdf->SetXY(10,25);
    $buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
    $pdf->MultiCell(190,4,$buss_name,0,'R',0);

    $pdf->SetXY(10,26); 
    $buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
    $pdf->MultiCell(190,4,$buss_name,0,'C',0);

    $pdf->SetXY(8,32); 
    $buss_name=iconv('UTF-8','windows-874',"วันที่");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);

    $pdf->SetXY(25,32); 
    $buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(45,32); 
    $buss_name=iconv('UTF-8','windows-874',"ซื้อมาจาก");
    $pdf->MultiCell(45,4,$buss_name,0,'C',0);

    $pdf->SetXY(90,32); 
    $buss_name=iconv('UTF-8','windows-874',"ยี่ห้อรถยนต์");
    $pdf->MultiCell(40,4,$buss_name,0,'C',0);

    $pdf->SetXY(134,32); 
    $buss_name=iconv('UTF-8','windows-874',"มูลค่า");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(159,32); 
    $buss_name=iconv('UTF-8','windows-874',"VAT");
    $pdf->MultiCell(20,4,$buss_name,0,'C',0);

    $pdf->SetXY(179,32); 
    $buss_name=iconv('UTF-8','windows-874',"รวม");
    $pdf->MultiCell(25,4,$buss_name,0,'C',0);

    $pdf->SetXY(10,33); 
    $buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
    $pdf->MultiCell(190,4,$buss_name,0,'C',0);
}

	$pdf->SetFont('AngsanaNew','',15);
    $pdf->SetXY(10,$cline-5); 
    $buss_name=iconv('UTF-8','windows-874',"___________________________________________________________________________________________________________");
    $pdf->MultiCell(190,4,$buss_name,0,'C',0);
    
	$pdf->SetFont('AngsanaNew','',12);
    $pdf->SetXY(10,$cline+1); 
    $buss_name=iconv('UTF-8','windows-874',"มีลูกค้าทั้งหมด $j ราย");
    $pdf->MultiCell(190,4,$buss_name,0,'L',0);
    
    $pdf->SetXY(110,$cline+1); 
    $buss_name=iconv('UTF-8','windows-874',"รวมยอดเงิน");
    $pdf->MultiCell(20,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(130,$cline+1); 
    $buss_name=iconv('UTF-8','windows-874',number_format($sum1,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(152,$cline+1); 
    $buss_name=iconv('UTF-8','windows-874',number_format($sum2,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);
    
    $pdf->SetXY(175,$cline+1); 
    $buss_name=iconv('UTF-8','windows-874',number_format($sum3,2));
    $pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->Output();
?>