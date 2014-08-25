<?php
session_start();
include("../../config/config.php");

$month = $_GET['month'];
$year = $_GET['year'];
$puruser = $_GET['puruser'];

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

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
//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(290,4,$buss_name,0,'R',0);
    }
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานตั๋วสัญญาใช้เงิน");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);


$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(5,25);
$buss_name1=iconv('UTF-8','windows-874',"ลูกหนี้ (ผู้ซื้อตั๋ว) : $puruser ");
$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(50,25);
$buss_name2=iconv('UTF-8','windows-874',"คืนตั๋วเดือน$txtmonth $year");
$pdf->MultiCell(200,4,$buss_name2,0,'L',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ตั๋วสัญญา");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,32);
$buss_name=iconv('UTF-8','windows-874',"เจ้าหนี้ (ผู้ออกตั๋ว)");
$pdf->MultiCell(35,4,$buss_name,0,'L',0);

$pdf->SetXY(60,32);
$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ (ผู้ซื้อตั๋ว)");
$pdf->MultiCell(35,4,$buss_name,0,'L',0);

$pdf->SetXY(95,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่ซื้อตั๋ว");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่จ่ายคืน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(135,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(160,32);
$buss_name=iconv('UTF-8','windows-874',"อัตราดอกเบี้ย");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(180,32);
$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ย");
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(5,33);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',10);
$cline = 39;
$nub = 1;
$qryboe=pg_query("SELECT \"boeID\", \"boeNumber\", \"payUser\", \"purchaseUser\",loan_amount,interest, \"returnDate\",\"payDate\",\"statusTicket\" FROM account.boe 
	where \"returnDate\" is not null and \"purchaseUser\"='$puruser' and EXTRACT(MONTH FROM \"returnDate\")='$month' and EXTRACT(YEAR FROM \"returnDate\")='$year' order by \"boeNumber\"");
$numboe=pg_num_rows($qryboe);
$i=0;
$sum=0;
while($result=pg_fetch_array($qryboe)){
	list($boeID,$boeNumber,$payUser,$purchaseUser,$loan_amount,$interest,$returnDate,$payDate,$statusTicket)=$result;
	
	//หารายได้ดอกเบี้ย
	$inter=pg_query("SELECT \"cal_interestTypeB\"($loan_amount,$interest,'$payDate','$returnDate')");
	$resin=pg_fetch_array($inter);
	list($boe_interest)=$resin;
	
	//show only new page
    if($nub == 36){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',16);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"รายงานตั๋วสัญญาใช้เงิน");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);


		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ลูกหนี้ (ผู้ซื้อตั๋ว) : $puruser ");
		$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(50,25);
		$buss_name2=iconv('UTF-8','windows-874',"คืนตั๋วเดือน$txtmonth $year");
		$pdf->MultiCell(200,4,$buss_name2,0,'L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ตั๋วสัญญา");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(25,32);
		$buss_name=iconv('UTF-8','windows-874',"เจ้าหนี้ (ผู้ออกตั๋ว)");
		$pdf->MultiCell(35,4,$buss_name,0,'L',0);

		$pdf->SetXY(60,32);
		$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ (ผู้ซื้อตั๋ว)");
		$pdf->MultiCell(35,4,$buss_name,0,'L',0);

		$pdf->SetXY(95,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ซื้อตั๋ว");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(115,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่จ่ายคืน");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(135,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetXY(160,32);
		$buss_name=iconv('UTF-8','windows-874',"อัตราดอกเบี้ย");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(180,32);
		$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ย");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);
		
        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
	}
	
//show all record
$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"$boeNumber");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(25,$cline);
$buss_name=iconv('UTF-8','windows-874',"$payUser");
$pdf->MultiCell(35,4,$buss_name,0,'L',0);

$pdf->SetXY(60,$cline);
$buss_name=iconv('UTF-8','windows-874',"$purchaseUser");
$pdf->MultiCell(35,4,$buss_name,0,'L',0);

$pdf->SetXY(95,$cline);
$buss_name=iconv('UTF-8','windows-874',"$payDate");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(115,$cline);
$buss_name=iconv('UTF-8','windows-874',"$returnDate");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(135,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($loan_amount,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);

$pdf->SetXY(160,$cline);
$buss_name=iconv('UTF-8','windows-874',"$interest");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);
    
$pdf->SetXY(180,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($boe_interest,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);
	
$cline += 5;
$nub+=1;
$sum=$sum+$boe_interest;
} //end while 

$nub+=1;
    if($nub == 36){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',16);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"รายงานตั๋วสัญญาใช้เงิน");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);


		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetXY(5,25);
		$buss_name1=iconv('UTF-8','windows-874',"ลูกหนี้ (ผู้ซื้อตั๋ว) : $puruser ");
		$pdf->MultiCell(200,4,$buss_name1,0,'L',0);

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetXY(50,25);
		$buss_name2=iconv('UTF-8','windows-874',"คืนตั๋วเดือน$txtmonth $year");
		$pdf->MultiCell(200,4,$buss_name2,0,'L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ตั๋วสัญญา");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(25,32);
		$buss_name=iconv('UTF-8','windows-874',"เจ้าหนี้ (ผู้ออกตั๋ว)");
		$pdf->MultiCell(35,4,$buss_name,0,'L',0);

		$pdf->SetXY(60,32);
		$buss_name=iconv('UTF-8','windows-874',"ลูกหนี้ (ผู้ซื้อตั๋ว)");
		$pdf->MultiCell(35,4,$buss_name,0,'L',0);

		$pdf->SetXY(95,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ซื้อตั๋ว");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(115,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่จ่ายคืน");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(135,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);

		$pdf->SetXY(160,32);
		$buss_name=iconv('UTF-8','windows-874',"อัตราดอกเบี้ย");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(180,32);
		$buss_name=iconv('UTF-8','windows-874',"รายได้ดอกเบี้ย");
		$pdf->MultiCell(25,4,$buss_name,0,'R',0);
		
        $pdf->SetXY(5,33);
        $buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________________________");
        $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    }
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);
$cline += 4;
$nub+=1;

$pdf->SetXY(160,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวม");
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(180,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($sum,2));
$pdf->MultiCell(25,4,$buss_name,0,'R',0);
$cline += 3;
$nub+=1;

$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);
$cline += 1;
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);
$pdf->Output();
?>