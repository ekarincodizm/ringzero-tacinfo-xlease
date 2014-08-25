<?php
include("../../config/config.php");


$yy = $_GET['year'];
$mm = $_GET['month'];

$month = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $month[$mm];

$show_yy = $yy+543;

if($yy != "" && $mm != ""){
	$showdetaildate = "ประจำเดือน ".$show_month." ปี ".$show_yy;
}else if($yy != "" && $mm == ""){
	$showdetaildate = "ประจำปี ".$show_yy;
}else{
	$showdetaildate = "แสดงรายการทั้งหมด";
}


$yar = date('Y')+543;
// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{

    function Header()    {
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(10,18); 
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

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"(THCAP) รายงานรับใบภาษีหัก ณ ที่จ่าย");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14); 
$pdf->SetXY(4,27); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".date('d-m-').$yar);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,18);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(4,27);
$gmm=iconv('UTF-8','windows-874',$showdetaildate);
$pdf->MultiCell(280,4,$gmm,0,'L',0);

$pdf->SetXY(4,28); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(35,8,$buss_name,0,'C',0);

$pdf->SetXY(40,32); 
$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
$pdf->MultiCell(60,8,$buss_name,0,'C',0);

$pdf->SetXY(100,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(130,32); 
$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(160,32); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(190,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบภาษี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	$pdf->SetXY(190,36); 
	$buss_name=iconv('UTF-8','windows-874',"หัก ณ ที่จ่าย");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(220,32); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินภาษี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	$pdf->SetXY(220,36); 
	$buss_name=iconv('UTF-8','windows-874',"หัก ณ ที่จ่าย");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(250,32); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(40,8,$buss_name,0,'C',0);


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,36); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 41;
$i = 1;
$j = 0;  
                        
if($yy != "" && $mm != ""){
		$qry_in=pg_query("SELECT \"receiptID\", \"receiveDate\", \"whtRef\", \"sumdebtAmt\", \"sumWht\", 
		\"receiveUser\", \"recUser\", \"statusReceiptID\",\"thcap_receiptIDToContractID\"(\"receiptID\") AS \"contractID\",thcap_fullname
		FROM vthcap_wht 
		LEFT JOIN \"vthcap_ContactCus_detail\" b ON b.\"contractID\"=\"thcap_receiptIDToContractID\"(\"receiptID\")
		where EXTRACT(MONTH FROM \"receiveDate\") = '$mm' and EXTRACT(YEAR FROM \"receiveDate\") = '$yy' AND \"CusState\"=0");
	}else if($yy != "" && $mm == ""){		
		$qry_in=pg_query("SELECT \"receiptID\", \"receiveDate\", \"whtRef\", \"sumdebtAmt\", \"sumWht\", 
		\"receiveUser\", \"recUser\", \"statusReceiptID\",\"thcap_receiptIDToContractID\"(\"receiptID\") AS \"contractID\",thcap_fullname
		FROM vthcap_wht 
		LEFT JOIN \"vthcap_ContactCus_detail\" b ON b.\"contractID\"=\"thcap_receiptIDToContractID\"(\"receiptID\")
		where EXTRACT(YEAR FROM \"receiveDate\") = '$yy' AND \"CusState\"=0");
	}else{
		$qry_in=pg_query("SELECT \"receiptID\", \"receiveDate\", \"whtRef\", \"sumdebtAmt\", \"sumWht\", 
		\"receiveUser\", \"recUser\", \"statusReceiptID\",\"thcap_receiptIDToContractID\"(\"receiptID\") AS \"contractID\",thcap_fullname
		FROM vthcap_wht 
		LEFT JOIN \"vthcap_ContactCus_detail\" b ON b.\"contractID\"=\"thcap_receiptIDToContractID\"(\"receiptID\")
		WHERE \"CusState\"=0");
	}
	
while($res_in=pg_fetch_array($qry_in)){

			
   

if($i > 30){ 
    $pdf->AddPage(); 
    $cline = 41; 
    $i=1; 

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"(THCAP) รายงานรับใบภาษีหัก ณ ที่จ่าย");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14); 
$pdf->SetXY(4,27); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,18);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$pdf->SetXY(4,27);
$gmm=iconv('UTF-8','windows-874',"ประจำเดือน $show_month ปี $show_yy");
$pdf->MultiCell(280,4,$gmm,0,'L',0);

$pdf->SetXY(4,28); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(35,8,$buss_name,0,'C',0);

$pdf->SetXY(40,32); 
$buss_name=iconv('UTF-8','windows-874',"ผู้กู้หลัก");
$pdf->MultiCell(60,8,$buss_name,0,'C',0);

$pdf->SetXY(100,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(130,32); 
$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(160,32); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(190,32); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบภาษี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	$pdf->SetXY(190,36); 
	$buss_name=iconv('UTF-8','windows-874',"หัก ณ ที่จ่าย");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(220,32); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินภาษี");
$pdf->MultiCell(30,4,$buss_name,0,'C',0);
	$pdf->SetXY(220,36); 
	$buss_name=iconv('UTF-8','windows-874',"หัก ณ ที่จ่าย");
	$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(250,32); 
$buss_name=iconv('UTF-8','windows-874',"สถานะ");
$pdf->MultiCell(40,8,$buss_name,0,'C',0);


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,36); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);
	
}


$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['contractID']);
$pdf->MultiCell(35,4,$buss_name,0,'C',0);

$pdf->SetXY(40,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['thcap_fullname']);
$pdf->MultiCell(60,4,$buss_name,0,'L',0);

$pdf->SetXY(100,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['receiptID']);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(130,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['receiveDate']);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_in['sumdebtAmt'],2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(190,$cline); 
$buss_name=iconv('UTF-8','windows-874',$res_in['whtRef']);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(220,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($res_in['sumWht'],2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

if($res_in['recUser'] == ""){ $status='ยังไม่ได้รับ'; }else{ $status='ได้รับแล้ว'; } 
$pdf->SetXY(250,$cline); 
$buss_name=iconv('UTF-8','windows-874',$status);
$pdf->MultiCell(40,4,$buss_name,0,'C',0);
  

// -----------

$cline+=5; 
$i+=1; 
   
$sum1 = $sum1+$res_in['sumdebtAmt'];
$sum2 = $sum2+$res_in['sumWht'];  
}  

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,4,$buss_name,B,'C',0);

$pdf->SetXY(220,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,4,$buss_name,B,'C',0);

$cline += 6;
$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,4,$buss_name,B,'C',0);

$pdf->SetXY(220,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,4,$buss_name,B,'C',0);

$cline += 1;
$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,4,$buss_name,B,'C',0);

$pdf->SetXY(220,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(30,4,$buss_name,B,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$cline -= 2;
$pdf->SetXY(130,$cline); 
$buss_name=iconv('UTF-8','windows-874','รวม');
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(190,$cline); 
$buss_name=iconv('UTF-8','windows-874','รวม');
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(160,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum1,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(220,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($sum2,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$cline += 5;
$pdf->SetXY(5,$cline); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->Output();
?>