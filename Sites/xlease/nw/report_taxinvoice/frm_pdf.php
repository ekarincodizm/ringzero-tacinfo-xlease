<?php
include("../../config/config.php");

$date = $_GET['date'];
$cancel = $_GET["cancel"]; //หากมีค่าเป็น 't' แสดงว่าให้แสเงข้อมูลของใบกำกับที่ถูกยกเลิกแล้ว
list($yy,$mm) = explode("-",$date);

$nowyear = date("Y")+543;
$nowdate = date("d-m-")."$nowyear";

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];

$show_yy = $yy+543;


$show_month.$show_yy;

   
IF($cancel == 't'){
		$qry_in=pg_query("			SELECT distinct * FROM \"thcap_v_taxinvoice_otherpay_cancel\" 
									where cast(\"taxpointDate\" as varchar) like '$date%'
									ORDER BY \"taxpointDate\"
						");
		$header = "(THCAP)รายงานภาษีขายที่ถูกยกเลิก";				
}else{   
		$qry_in=pg_query("			SELECT distinct * FROM \"thcap_v_taxinvoice_otherpay\" 
									where cast(\"taxpointDate\" as varchar) like '$date%'
									ORDER BY \"taxpointDate\"
						");
		$header = "(THCAP)รายงานภาษีขาย";				
}

// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(280,4,$buss_name,0,'R',0);
    }
 
}


$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true,0);

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',$header);
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ออก");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบกำกับภาษี");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(60,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(125,30); 
$buss_name=iconv('UTF-8','windows-874',"รหัสรายการ");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(175,30); 
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดรายการ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);
  
$pdf->SetXY(232,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(252,30); 
$buss_name=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
 
$pdf->SetXY(272,30); 
$buss_name=iconv('UTF-8','windows-874',"รวมรับชำระ");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;  

while($res_in=pg_fetch_array($qry_in)){
   

if($i > 30){ 
    $pdf->AddPage(); 
    $cline = 37; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',$header);
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetXY(5,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ออก");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(30,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบกำกับภาษี");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(60,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(95,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(125,30); 
$buss_name=iconv('UTF-8','windows-874',"รหัสรายการ");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(175,30); 
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดรายการ");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);
  
$pdf->SetXY(232,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(252,30); 
$buss_name=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
 
$pdf->SetXY(272,30); 
$buss_name=iconv('UTF-8','windows-874',"รวมรับชำระ");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
  

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

}

// -----------

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['taxpointDate']);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(30,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['taxinvoiceID']);
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(60,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['contractID']);
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',11);
$pdf->SetXY(90,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['cusFullname']);
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(125,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['typePayID']);
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(157,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['tpDesc']." ".$res_in['tpFullDesc']." ".$res_in['typePayRefValue']);
$pdf->MultiCell(75,4,$buss_name,0,'L',0);
  
$pdf->SetXY(232,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_in['netAmt'], 2, '.', ','));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(250,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_in['vatAmt'], 2, '.', ','));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
 
$pdf->SetXY(270,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_in['debtAmt'], 2, '.', ','));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$sum_money = $sum_money + $res_in['netAmt'];
$sum_VatValue = $sum_VatValue + $res_in['vatAmt'];
$sum_amt = $sum_amt + $res_in['debtAmt'];
// -----------

$cline+=5; 
$i+=1;       
}  

$pdf->SetFont('AngsanaNew','B',10);
$pdf->SetXY(220,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"_________           __________            ___________");
$pdf->MultiCell(70,4,$buss_name,0,'R',0);

$pdf->SetXY(175,$cline+1.7); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
$pdf->MultiCell(55,4,$buss_name,0,'R',0);

$pdf->SetXY(232,$cline+1.5); 
$s_down=iconv('UTF-8','windows-874',number_format($sum_money,2));
$pdf->MultiCell(20,4,$s_down,0,'R',0);

$pdf->SetXY(250,$cline+1.5); 
$s_P_BEGINX=iconv('UTF-8','windows-874',number_format($sum_VatValue,2));
$pdf->MultiCell(20,4,$s_P_BEGINX,0,'R',0);
  
$pdf->SetXY(270,$cline+1.5); 
$s_intall=iconv('UTF-8','windows-874',number_format($sum_amt,2));
$pdf->MultiCell(20,4,$s_intall,0,'R',0);

$pdf->SetXY(220,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"_________           __________            ___________");
$pdf->MultiCell(70,4,$buss_name,0,'R',0);

$pdf->SetXY(220,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"_________           __________            ___________");
$pdf->MultiCell(70,4,$buss_name,0,'R',0);

$pdf->Output();
?>