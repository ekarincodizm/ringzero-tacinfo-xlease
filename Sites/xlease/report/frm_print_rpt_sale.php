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
$title=iconv('UTF-8','windows-874',"สมุดรายวันขาย");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23); 
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(5,23); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(195,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(11,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(33,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(60,30); 
$buss_name=iconv('UTF-8','windows-874',"ยี่ห้อรถยนต์");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(85,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(85,35); 
$buss_name=iconv('UTF-8','windows-874',"รถยนต์");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(98,30); 
$buss_name=iconv('UTF-8','windows-874',"เงินดาวน์");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(112,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(128,30); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(128,35); 
$buss_name=iconv('UTF-8','windows-874',"รอตัด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(146,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเช่าซื้อ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(146,35); 
$buss_name=iconv('UTF-8','windows-874',"ไม่รวม VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอด VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(165,35); 
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเช่าซื้อ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,35); 
$buss_name=iconv('UTF-8','windows-874',"รวม VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,37); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 42;
$i = 1;
$j = 0;                           
$qry_in=pg_query("SELECT * FROM \"VRptSale\" where EXTRACT(MONTH FROM \"P_STDATE\")='$mm' AND EXTRACT(YEAR FROM \"P_STDATE\")='$yy' ORDER BY \"P_STDATE\" ");
while($res_in=pg_fetch_array($qry_in)){
    //ต้องตรวจสอบระหว่างสัญญาเก่ากับใหม่ก่อน ถ้าเก่าสัญญาเป็นดังนี้ เช่น 114-22 ถ้าใหม่ จะเป็น 12-22 ดังนั้นต้อง substring คนละที่
	if(substr($res_in["IDNO"],3,1)=="-"){ //เลขที่สัญญาเก่า
		$condition=substr($res_in["IDNO"],4,2) != 22;
	}else if(substr($res_in["IDNO"],2,1)=="-"){ //เลขที่สัญญาใหม่
		$condition=substr($res_in["IDNO"],3,2) != 22;
	}
	if($condition){
    $j+=1;
    $P_STDATE = $res_in["P_STDATE"];
    $IDNO = $res_in["IDNO"];
    $fullname = $res_in["fullname"];
    $asset_name = $res_in["asset_name"];
    $asset_regis = $res_in["asset_regis"];
    $P_DOWN = $res_in["P_DOWN"];
    $P_BEGINX = $res_in["P_BEGINX"];
    $intall = $res_in["intall"];
    $hpnonvat = $res_in["hpnonvat"];
    $vatall = $res_in["vatall"];
    $hpall = $res_in["hpall"];
    
    $a_P_DOWN = number_format($P_DOWN,2);
    $a_P_BEGINX = number_format($P_BEGINX,2);
    $a_intall = number_format($intall,2);
    $a_hpnonvat = number_format($hpnonvat,2);
    $a_vatall = number_format($vatall,2);
    $a_hpall = number_format($hpall,2);

    $strDate = date("d",strtotime($P_STDATE));
    
    $sum_down = $P_DOWN+$sum_down;
    $sum_begin = $P_BEGINX+$sum_begin;
    $sum_intall = $intall+$sum_intall;
    $sum_hpnonvat = $hpnonvat+$sum_hpnonvat;
    $sum_vatall = $vatall+$sum_vatall;
    $sum_hpall = $hpall+$sum_hpall;
    
    $b_sum_down = number_format($sum_down,2);
    $b_sum_begin = number_format($sum_begin,2);
    $b_sum_intall = number_format($sum_intall,2);
    $b_sum_hpnonvat = number_format($sum_hpnonvat,2);
    $b_sum_vatall = number_format($sum_vatall,2);
    $b_sum_hpall = number_format($sum_hpall,2);

if($i > 45){ 
    $pdf->AddPage(); $cline = 42; $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"สมุดรายวันขาย");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(10,16);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame']);
$pdf->MultiCell(190,4,$buss_name,0,'C',0);

$pdf->SetXY(5,23); 
$buss_name=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(5,23); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(195,4,$buss_name,0,'R',0);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่");
$pdf->MultiCell(10,4,$buss_name,0,'C',0);

$pdf->SetXY(11,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(33,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้เช่าซื้อ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(60,30); 
$buss_name=iconv('UTF-8','windows-874',"ยี่ห้อรถยนต์");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(85,30); 
$buss_name=iconv('UTF-8','windows-874',"ทะเบียน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(85,35); 
$buss_name=iconv('UTF-8','windows-874',"รถยนต์");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(98,30); 
$buss_name=iconv('UTF-8','windows-874',"เงินดาวน์");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(112,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดจัด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(128,30); 
$buss_name=iconv('UTF-8','windows-874',"ดอกผล");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(128,35); 
$buss_name=iconv('UTF-8','windows-874',"รอตัด");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(146,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเช่าซื้อ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(146,35); 
$buss_name=iconv('UTF-8','windows-874',"ไม่รวม VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(165,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอด VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(165,35); 
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,30); 
$buss_name=iconv('UTF-8','windows-874',"ยอดเช่าซื้อ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,35); 
$buss_name=iconv('UTF-8','windows-874',"รวม VAT");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(4,37); 
$buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,4,$buss_name,0,'C',0);

}

$pdf->SetFont('AngsanaNew','',10);    
$pdf->SetXY(5,$cline); 
$s_date=iconv('UTF-8','windows-874',$strDate);
$pdf->MultiCell(10,4,$s_date,0,'C',0);
    
$pdf->SetXY(11,$cline); 
$idno=iconv('UTF-8','windows-874',$IDNO);
$pdf->MultiCell(25,4,$idno,0,'C',0);   

$pdf->SetXY(32,$cline); 
$s_name=iconv('UTF-8','windows-874',$fullname);
$pdf->MultiCell(35,4,$s_name,0,'L',0);

$pdf->SetXY(63,$cline); 
$s_asset_name=iconv('UTF-8','windows-874',$asset_name);
$pdf->MultiCell(32,4,$s_asset_name,0,'L',0);

$pdf->SetXY(92,$cline); 
$s_asset_regis=iconv('UTF-8','windows-874',$asset_regis);
$pdf->MultiCell(12,4,$s_asset_regis,0,'L',0);

$pdf->SetXY(99,$cline); 
$s_down=iconv('UTF-8','windows-874',$a_P_DOWN);
$pdf->MultiCell(15,4,$s_down,0,'R',0);

$pdf->SetXY(113,$cline); 
$s_P_BEGINX=iconv('UTF-8','windows-874',$a_P_BEGINX);
$pdf->MultiCell(15,4,$s_P_BEGINX,0,'R',0);
  
$pdf->SetXY(129,$cline); 
$s_intall=iconv('UTF-8','windows-874',$a_intall);
$pdf->MultiCell(15,4,$s_intall,0,'R',0);

$pdf->SetXY(149,$cline); 
$s_hpnonvat=iconv('UTF-8','windows-874',$a_hpnonvat);
$pdf->MultiCell(15,4,$s_hpnonvat,0,'R',0);

$pdf->SetXY(167,$cline); 
$s_vatall=iconv('UTF-8','windows-874',$a_vatall);
$pdf->MultiCell(15,4,$s_vatall,0,'R',0);
 
$pdf->SetXY(182,$cline); 
$s_hpall=iconv('UTF-8','windows-874',$a_hpall);
$pdf->MultiCell(15,4,$s_hpall,0,'R',0);

$cline+=5; 
$i+=1;       
}  
}

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(102,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"_________     __________     ___________     ______________    ______________    ___________");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(60,$cline+2); 
$buss_name=iconv('UTF-8','windows-874',"มีลูกค้าทั้งหมด (ราย) : $j");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(97,$cline+2); 
$s_down=iconv('UTF-8','windows-874',$b_sum_down);
$pdf->MultiCell(17,4,$s_down,0,'R',0);

$pdf->SetXY(112,$cline+2); 
$s_P_BEGINX=iconv('UTF-8','windows-874',$b_sum_begin);
$pdf->MultiCell(17,4,$s_P_BEGINX,0,'R',0);
  
$pdf->SetXY(128,$cline+2); 
$s_intall=iconv('UTF-8','windows-874',$b_sum_intall);
$pdf->MultiCell(17,4,$s_intall,0,'R',0);

$pdf->SetXY(147,$cline+2); 
$s_hpnonvat=iconv('UTF-8','windows-874',$b_sum_hpnonvat);
$pdf->MultiCell(17,4,$s_hpnonvat,0,'R',0);

$pdf->SetXY(165,$cline+2); 
$s_vatall=iconv('UTF-8','windows-874',$b_sum_vatall);
$pdf->MultiCell(17,4,$s_vatall,0,'R',0);
 
$pdf->SetXY(180,$cline+2); 
$s_hpall=iconv('UTF-8','windows-874',$b_sum_hpall);
$pdf->MultiCell(17,4,$s_hpall,0,'R',0);

$pdf->SetXY(102,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"_________     __________     ___________     ______________    ______________    ___________");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(102,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"_________     __________     ___________     ______________    ______________    ___________");
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->Output();
?>