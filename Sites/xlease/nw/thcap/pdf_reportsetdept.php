<?php
session_start();
include("../../config/config.php");

$datepicker = $_GET['datepicker'];
$condate = $_GET['condate'];
if($datepicker != ""){
	if($condate==1){
		$txtcondate="วันที่ทำรายการ";
		$conditiondate="date(a.\"doerStamp\")='$datepicker'";
	}else if($condate==2){
		$txtcondate="วันที่หนี้มีผล";
		$conditiondate="date(a.\"typePayRefDate\")='$datepicker'";
	}
	$datetext = "วันที่ ".$datepicker;
}else{
$yearsh = $_GET['yearsh'];
$monthsh = $_GET['monthsh'];
$monthtext = array('01'=>'มกราคม', '02'=>'กุมภาพันธ์', '03'=>'มีนาคม', '04'=>'เมษายน', '05'=>'พฤษภาคม', '06'=>'มิถุนายน', '07'=>'กรกฏาคม', '08'=>'สิงหาคม' ,'09'=>'กันยายน' ,'10'=>'ตุลาคม', '11'=>'พฤศจิกายน', '12'=>'ธันวาคม');
$show_month = $monthtext[$monthsh];
	if($monthsh == "not"){
			if($condate==1){
				$txtcondate="วันที่ทำรายการ";	
				$conditiondate="EXTRACT(YEAR FROM a.\"doerStamp\")='$yearsh'";
			}else if($condate==2){
				$txtcondate="วันที่หนี้มีผล";
				$conditiondate="EXTRACT(YEAR FROM a.\"typePayRefDate\"::date)='$yearsh'";
			}
		$yeartxt = $yearsh + 543;
		$datetext = "ปี ".$yeartxt;		
	}else{
			if($condate==1){
				$txtcondate="วันที่ทำรายการ";	
				$conditiondate="EXTRACT(MONTH FROM a.\"doerStamp\")='$monthsh' and EXTRACT(YEAR FROM a.\"doerStamp\")='$yearsh'";
			}else if($condate==2){
				$txtcondate="วันที่หนี้มีผล";
				$conditiondate="EXTRACT(MONTH FROM a.\"typePayRefDate\"::date)='$monthsh' and EXTRACT(YEAR FROM a.\"typePayRefDate\"::date)='$yearsh'";
				
			}
		$yeartxt = $yearsh + 543;
		
		$datetext = "เดือน ".$show_month." ปี ".$yeartxt;	
	}	
}

$nowdate = nowDate();

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

$pdf=new PDF('L' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานการตั้งหนี้เงินกู้");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำ$txtcondate $datetext");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"*สถานะของหนี้ : สถานะปัจจุบันของหนี้นั้นๆ ขณะเรียกสัญญา ซึ่งอาจไม่ตรงกับครั้งแรกที่ตั้ง");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,31);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,'B','C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(30,34);
$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

	// $pdf->SetXY(36,39);
	// $buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
	// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

$pdf->SetXY(65,34);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดค่าใช้จ่าย");
$pdf->MultiCell(55,8,$buss_name,0,'C',0);
	
$pdf->SetXY(125,34);
$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิงของค่าใช้จ่าย");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

	// $pdf->SetXY(85,39);
	// $buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
	// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

$pdf->SetXY(160,34);
$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
$pdf->MultiCell(15,8,$buss_name,0,'C',0);

$pdf->SetXY(180,34);
$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้(บาท)");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

	// $pdf->SetXY(125,39);
	// $buss_name=iconv('UTF-8','windows-874',"(บาท)");
	// $pdf->MultiCell(20,6,$buss_name,0,'C',0);

$pdf->SetXY(200,34);
$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
$pdf->MultiCell(45,8,$buss_name,0,'C',0);

$pdf->SetXY(235,34);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาตั้งหนี้");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(265,34);
$buss_name=iconv('UTF-8','windows-874',"สถานะของหนี้");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,8,$buss_name,'B','C',0);

//=========================// จบ header ของหน้าแรก

$pdf->SetFont('AngsanaNew','',12);
$cline = 43;
$nub = 1;
$qryreceipt=pg_query("select * from \"thcap_v_otherpay_debt_realother\" a
	left join \"Vfuser\" b on a.\"doerID\"=b.\"id_user\"
	where $conditiondate order by a.\"typePayRefDate\"");
$i=0;
$sum_amt = 0;
$sum_all = 0;
$old_doerID="";
while($result=pg_fetch_array($qryreceipt)){
    $contractID=$result["contractID"];
	$typePayID=$result["typePayID"];
	$typePayRefValue=$result["typePayRefValue"];
	$typePayRefDate=$result["typePayRefDate"];
	$typePayAmt=$result["typePayAmt"];
	$fullname=$result["fullname"];
	$doerStamp=$result["doerStamp"];
	$debtStatus=$result["debtStatus"];
	$doerID=$result["doerID"];
	
	if($doerID=="000"){
		$fullname="อัตโนมัติโดยระบบ";
	}
							
	if($debtStatus=="0"){
		$txtdeb="ยกเลิก";
	}else if($debtStatus=="1"){
		$txtdeb="ยังไม่ได้จ่าย/จ่ายไม่ครบ";
	}else if($debtStatus=="2"){
		$txtdeb="จ่ายครบแล้ว";
	}else if($debtStatus=="3"){
		$txtdeb="ยกเว้นหนี้";
	}else if($debtStatus == '5'){
		$txtdeb = 'ลดหนี้เป็น 0.00';
	}else if($debtStatus=="9"){
		$txtdeb="รออนุมัติ";
	}
	
	// หารายละเอียดค่าใช้จ่ายนั้นๆ
		$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
		while($res_tpDesc = pg_fetch_array($qry_tpDesc))
		{
			$tpDescShow = $res_tpDesc["tpDesc"];
		}
	
	
    $pdf->SetFont('AngsanaNew','B',12);
	
	//show only new page
    if($nub == 30){
        $nub = 1;
        $cline = 43;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
		$pdf->MultiCell(290,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานการตั้งหนี้เงินกู้");
		$pdf->MultiCell(290,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"ประจำ$txtcondate $datetext");
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','B',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"*สถานะของหนี้ : สถานะปัจจุบันของหนี้นั้นๆ ขณะเรียกสัญญา ซึ่งอาจไม่ตรงกับครั้งแรกที่ตั้ง");
		$pdf->MultiCell(200,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,30);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(290,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,31);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,4,$buss_name,'B','C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

		$pdf->SetXY(30,34);
		$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
		$pdf->MultiCell(30,8,$buss_name,0,'C',0);

			// $pdf->SetXY(36,39);
			// $buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
			// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

		$pdf->SetXY(65,34);
		$buss_name=iconv('UTF-8','windows-874',"รายละเอียดค่าใช้จ่าย");
		$pdf->MultiCell(55,8,$buss_name,0,'C',0);
			
		$pdf->SetXY(125,34);
		$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิงของค่าใช้จ่าย");
		$pdf->MultiCell(30,8,$buss_name,0,'C',0);

			// $pdf->SetXY(85,39);
			// $buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
			// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

		$pdf->SetXY(160,34);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
		$pdf->MultiCell(15,8,$buss_name,0,'C',0);

		$pdf->SetXY(180,34);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้(บาท)");
		$pdf->MultiCell(25,8,$buss_name,0,'C',0);

			// $pdf->SetXY(125,39);
			// $buss_name=iconv('UTF-8','windows-874',"(บาท)");
			// $pdf->MultiCell(20,6,$buss_name,0,'C',0);

		$pdf->SetXY(200,34);
		$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
		$pdf->MultiCell(45,8,$buss_name,0,'C',0);

		$pdf->SetXY(235,34);
		$buss_name=iconv('UTF-8','windows-874',"วันเวลาตั้งหนี้");
		$pdf->MultiCell(30,8,$buss_name,0,'C',0);

		$pdf->SetXY(265,34);
		$buss_name=iconv('UTF-8','windows-874',"สถานะของหนี้");
		$pdf->MultiCell(30,8,$buss_name,0,'C',0);

		$pdf->SetXY(5,34);
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(290,8,$buss_name,'B','C',0);
    
	}
	
//show all record
    $pdf->SetFont('AngsanaNew','',11);
    $pdf->SetXY(5,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$contractID");
    $pdf->MultiCell(30,4,$buss_name,0,'L',0);

    $pdf->SetXY(30,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typePayID");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(58,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$tpDescShow");
    $pdf->MultiCell(70,4,$buss_name,0,'C',0);

    $pdf->SetXY(125,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typePayRefValue");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);

    $pdf->SetXY(160,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typePayRefDate");
    $pdf->MultiCell(15,4,$buss_name,0,'C',0);
    
    $pdf->SetXY(180,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$typePayAmt");
    $pdf->MultiCell(23,4,$buss_name,0,'R',0);

 	$pdf->SetXY(200,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$fullname");
    $pdf->MultiCell(45,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(235,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$doerStamp");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
	
	$pdf->SetXY(265,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$txtdeb");
    $pdf->MultiCell(30,4,$buss_name,0,'C',0);
 
    $cline += 5;
    $nub+=1;
    $sum_amt+=$typePayAmt;
} //end while 

$cline += 6;
$nub+=1;

    if($nub == 30){
        $nub = 1;
        $cline = 30;
        $pdf->AddPage();
        
         $pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame_thcap"]);
$pdf->MultiCell(290,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"(THCAP) รายงานการตั้งหนี้เงินกู้");
$pdf->MultiCell(290,4,$buss_name,0,'C',0);

$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำ$txtcondate $datetext");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"*สถานะของหนี้ : สถานะปัจจุบันของหนี้นั้นๆ ขณะเรียกสัญญา ซึ่งอาจไม่ตรงกับครั้งแรกที่ตั้ง");
$pdf->MultiCell(200,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,30);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(290,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,31);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,'B','C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

$pdf->SetXY(30,34);
$buss_name=iconv('UTF-8','windows-874',"รหัสประเภทค่าใช้จ่าย");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

	// $pdf->SetXY(36,39);
	// $buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
	// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

$pdf->SetXY(65,34);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียดค่าใช้จ่าย");
$pdf->MultiCell(55,8,$buss_name,0,'C',0);
	
$pdf->SetXY(125,34);
$buss_name=iconv('UTF-8','windows-874',"ค่าอ้างอิงของค่าใช้จ่าย");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

	// $pdf->SetXY(85,39);
	// $buss_name=iconv('UTF-8','windows-874',"ค่าใช้จ่าย");
	// $pdf->MultiCell(18,6,$buss_name,0,'C',0);

$pdf->SetXY(160,34);
$buss_name=iconv('UTF-8','windows-874',"วันที่ตั้งหนี้");
$pdf->MultiCell(15,8,$buss_name,0,'C',0);

$pdf->SetXY(180,34);
$buss_name=iconv('UTF-8','windows-874',"จำนวนหนี้(บาท)");
$pdf->MultiCell(25,8,$buss_name,0,'C',0);

	// $pdf->SetXY(125,39);
	// $buss_name=iconv('UTF-8','windows-874',"(บาท)");
	// $pdf->MultiCell(20,6,$buss_name,0,'C',0);

$pdf->SetXY(200,34);
$buss_name=iconv('UTF-8','windows-874',"ผู้ตั้งหนี้");
$pdf->MultiCell(45,8,$buss_name,0,'C',0);

$pdf->SetXY(235,34);
$buss_name=iconv('UTF-8','windows-874',"วันเวลาตั้งหนี้");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(265,34);
$buss_name=iconv('UTF-8','windows-874',"สถานะของหนี้");
$pdf->MultiCell(30,8,$buss_name,0,'C',0);

$pdf->SetXY(5,34);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,8,$buss_name,'B','C',0);
    }

$pdf->SetFont('AngsanaNew','B',12);
$pdf->SetXY(180,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sum_amt,2));
$pdf->MultiCell(100,4,$buss_name,0,'L',0);

$pdf->SetXY(5,$cline+1);
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(290,4,$buss_name,'B','C',0);

$pdf->Output();
?>