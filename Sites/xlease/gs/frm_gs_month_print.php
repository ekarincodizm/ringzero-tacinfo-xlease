<?php
include("../config/config.php");

if(!empty($_GET['mm'])) { $mm = pg_escape_string($_GET['mm']);}
if(!empty($_GET['yy'])) { $yy = pg_escape_string($_GET['yy']);}
$nowdate = date("Y/m/d");

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];

$show_yy = $yy+543;

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
$title=iconv('UTF-8','windows-874',"รายงานสรุปค่าใช้จ่ายรายเดือน ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(159,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"รหัส");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(65,30); 
$buss_name=iconv('UTF-8','windows-874',"บริษัท");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(85,30); 
$buss_name=iconv('UTF-8','windows-874',"รุ่น/ประเภท");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(115,30); 
$buss_name=iconv('UTF-8','windows-874',"ใบเสร็จ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(135,30); 
$buss_name=iconv('UTF-8','windows-874',"ใบกำกับ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(155,30); 
$buss_name=iconv('UTF-8','windows-874',"ราคาต้นทุน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"Vat");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;                           


$qry_com=pg_query("select coid,coname FROM gas.\"Company\" ORDER BY \"coid\" ASC ");
while($res_com=pg_fetch_array($qry_com)){
    $id = $res_com["coid"];
    $name = $res_com["coname"];

$sum_cost = 0;
$sum_vat = 0;
$rows = 0;

$qry=pg_query("SELECT * FROM gas.\"PoGas\" where bill is not null AND status_pay = 't' AND idcompany = '$id' AND EXTRACT(MONTH FROM \"podate\")='$mm' AND EXTRACT(YEAR FROM \"podate\")='$yy' ORDER BY \"idno\" ASC ");
$rows = pg_num_rows($qry);
if($rows > 0){
while($res=pg_fetch_array($qry)){
    $id = $res["poid"];
    $idno = $res["idno"];
    $date = $res["podate"];
    $idcompany = $res["idcompany"];
    $idmodel = $res["idmodel"];
    $costofgas = $res["costofgas"];
    $vatofcost = $res["vatofcost"];
    $bill = $res["bill"]; if(empty($bill)) $bill = "-";
    $invoice = $res["invoice"]; if(empty($invoice)) $invoice = "-";
    
    $payid = $res["payid"];
    
    $qry_payid=pg_query("select \"Cancel\" FROM gas.\"PayToGas\" WHERE payid='$payid'");
    if($res_payid=pg_fetch_array($qry_payid)){
        $Cancel = $res_payid["Cancel"];
    }
    
    if($Cancel != 't'){

    $sum_cost += $costofgas;
    $sum_vat += $vatofcost;
    
    $qry_com2=pg_query("select modelname FROM gas.\"Model\" WHERE modelid='$idmodel'");
    if($res_com2=pg_fetch_array($qry_com2)){
        $modelname = $res_com2["modelname"];
    }

if($i > 45){
    $pdf->AddPage(); 
    $cline = 37; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"รายงานแสดงการรับรู้รายได้จากดอกผล และ การตั้งสำรองหนี้ ประจำปี $show_yy");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(159,22.5); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"รหัส");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ทำรายการ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(65,30); 
$buss_name=iconv('UTF-8','windows-874',"บริษัท");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(85,30); 
$buss_name=iconv('UTF-8','windows-874',"รุ่น/ประเภท");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(115,30); 
$buss_name=iconv('UTF-8','windows-874',"ใบเสร็จ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(135,30); 
$buss_name=iconv('UTF-8','windows-874',"ใบกำกับ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(155,30); 
$buss_name=iconv('UTF-8','windows-874',"ราคาต้นทุน");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"Vat");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10);

$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$id);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline); 
$buss_name=iconv('UTF-8','windows-874',$idno);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(45,$cline);
$buss_name=iconv('UTF-8','windows-874',$date);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(65,$cline);
$buss_name=iconv('UTF-8','windows-874',$idcompany);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(85,$cline);
$buss_name=iconv('UTF-8','windows-874',$modelname);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(115,$cline); 
$buss_name=iconv('UTF-8','windows-874',$bill);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(135,$cline); 
$buss_name=iconv('UTF-8','windows-874',$invoice);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(155,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($costofgas,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(180,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($vatofcost,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$cline+=5; 
$i+=1; 

    }
}//while

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(155,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sum_cost,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(180,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sum_vat,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,$cline+1); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$cline+=5; 


}//if
}//while

$pdf->Output();
?>