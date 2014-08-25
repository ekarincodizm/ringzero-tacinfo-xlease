<?php
include("../../config/config.php");
include("../../core/core_functions.php");
include("../function/nameMonth.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$contype = pg_escape_string($_GET["contype"]);

$checkoption = pg_escape_string($_GET["op1"]);
	
IF($checkoption == 'my')
{
	$month = pg_escape_string($_GET["month"]);
	$year = pg_escape_string($_GET["year"]);
	$where = " EXTRACT(MONTH FROM \"receiveDate\") = '$month' and EXTRACT(YEAR FROM \"receiveDate\") = '$year'";
	$show_month = nameMonthTH($month);
	$txtheader = 'ประจำเดือน ';
	
	// แสดงตามประเภทสัญญาที่เลือก
	if($contype != "")
	{
		$whereContype = str_replace("@","' or \"conType\" = '",$contype);
		$where .= " and (\"conType\" = '$whereContype')";
	}
}
else if($checkoption == 'y')
{
	$year = pg_escape_string($_GET["year"]);
	$where = " EXTRACT(YEAR FROM \"receiveDate\") = '$year'";
	$txtheader = 'ประจำปี  ';
	
	// แสดงตามประเภทสัญญาที่เลือก
	if($contype != "")
	{
		$whereContype = str_replace("@","' or \"conType\" = '",$contype);
		$where .= " and (\"conType\" = '$whereContype')";
	}
}





$show_yy = $year+543;


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

$page = $pdf->PageNo();

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,10);
$title=iconv('UTF-8','windows-874',"(THCAP)รายงานเงินต้นดอกเบี้ยรับ");
$pdf->MultiCell(280,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(4,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(285,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(280,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"$txtheader $show_month $show_yy");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetXY(15,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(50,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(90,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(127,30); 
$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
$pdf->MultiCell(70,4,$buss_name,0,'C',0);

$pdf->SetXY(200,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(230,30); 
$buss_name=iconv('UTF-8','windows-874',"เงินต้นรับชำระ");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);
  
$pdf->SetXY(260,30); 
$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยรับชำระ");
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(285,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;

$qry_in=pg_query("SELECT distinct DATE(\"receiveDate\") \"DATEE\",a.\"contractID\",\"receiptID\",\"receiveAmount\",\"receivePriciple\",\"receiveInterest\",\"conType\"
				FROM \"thcap_temp_int_201201\" a
				LEFT JOIN \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\"
				WHERE $where AND \"isReceiveReal\" = '1'

				union

				SELECT distinct DATE(\"receiveDate\") \"DATEE\",a.\"contractID\",\"receiptID\",\"debt_cut\",\"priciple_cut\",\"interest_cut\",\"conType\"
				FROM \"account\".\"thcap_acc_filease_realize_eff_present\" a
				LEFT JOIN \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\"
				WHERE $where

				order by \"conType\", \"contractID\", \"receiptID\" ");

$sumAmountAll = 0; // จำนวนเงินที่รับชำระ รวมทั้งหมด
$sumPricipleAll = 0; // เงินต้นรับชำระ รวมทั้งหมด
$sumInterestAll = 0; // ดอกเบี้ยรับชำระ รวมทั้งหมด
		
				
while($res=pg_fetch_array($qry_in)){
	
		$conid = $res["contractID"];
		
		$receiveDate = $res["DATEE"]; // วันที่รับชำระ
		$contractID = $res["contractID"]; // เลขที่สัญญา
		$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
		$receiveAmount = $res["receiveAmount"]; // จำนวนเงินที่รับชำระ
		$receivePriciple = $res["receivePriciple"]; // เงินต้นรับชำระ
		$receiveInterest = $res["receiveInterest"]; // ดอกเบี้ยรับชำระ
		
		$sumAmountAll += $receiveAmount; // จำนวนเงินที่รับชำระ รวมทั้งหมด
		$sumPricipleAll += $receivePriciple; // เงินต้นรับชำระ รวมทั้งหมด
		$sumInterestAll += $receiveInterest; // ดอกเบี้ยรับชำระ รวมทั้งหมด

		//--หาชื่อผู้กู้หลัก
		$qry_cusname = pg_query("SELECT \"thcap_fullname\" FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$conid' AND \"CusState\" = '0' ");
		list($fullcusname) = pg_fetch_array($qry_cusname);

		if($i > 30){ 
			$pdf->AddPage(); 
			$cline = 37; 
			$i=1; 

			$pdf->SetFont('AngsanaNew','B',15);
			$pdf->SetXY(10,10);
			$title=iconv('UTF-8','windows-874',"(THCAP)รายงานเงินต้นดอกเบี้ยรับ");
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

			$pdf->SetXY(15,30); 
			$buss_name=iconv('UTF-8','windows-874',"วันที่รับชำระ");
			$pdf->MultiCell(20,4,$buss_name,0,'C',0);

			$pdf->SetXY(50,30); 
			$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(90,30); 
			$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
			$pdf->MultiCell(25,4,$buss_name,0,'C',0);

			$pdf->SetXY(127,30); 
			$buss_name=iconv('UTF-8','windows-874',"ชื่อผู้กู้หลัก");
			$pdf->MultiCell(70,4,$buss_name,0,'C',0);

			$pdf->SetXY(200,30); 
			$buss_name=iconv('UTF-8','windows-874',"จำนวนเงินที่รับชำระ");
			$pdf->MultiCell(30,4,$buss_name,0,'R',0);

			$pdf->SetXY(230,30); 
			$buss_name=iconv('UTF-8','windows-874',"เงินต้นรับชำระ");
			$pdf->MultiCell(30,4,$buss_name,0,'R',0);
			  
			$pdf->SetXY(260,30); 
			$buss_name=iconv('UTF-8','windows-874',"ดอกเบี้ยรับชำระ");
			$pdf->MultiCell(30,4,$buss_name,0,'R',0);

			$pdf->SetFont('AngsanaNew','',12);
			$pdf->SetXY(4,32); 
			$buss_name=iconv('UTF-8','windows-874',"");
			$pdf->MultiCell(285,4,$buss_name,'B','L',0);

			$pdf->SetFont('AngsanaNew','',10);
			$cline = 37;
			$i = 1;
			$j = 0; 

	}

// -----------

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(15,$cline); 
$buss_name=iconv('UTF-8','windows-874',$receiveDate);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(47,$cline); 
$buss_name=iconv('UTF-8','windows-874',$receiptID);
$pdf->MultiCell(30,4,$buss_name,0,'C',0);

$pdf->SetXY(82,$cline); 
$buss_name=iconv('UTF-8','windows-874',$contractID);
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',11);
$pdf->SetXY(140,$cline); 
$buss_name=iconv('UTF-8','windows-874',$fullcusname);
$pdf->MultiCell(70,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(190,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($receiveAmount,2));
$pdf->MultiCell(40,4,$buss_name,0,'R',0);

$pdf->SetXY(230,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($receivePriciple,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);
  
$pdf->SetXY(260,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($receiveInterest,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

// -----------

$cline+=5; 
$i+=1;       
}  

$pdf->SetFont('AngsanaNew','B',10);
//ขีดเส้นขั้นรวม 3 เส้นแรก
$pdf->SetXY(210,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(240,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(270,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);



$pdf->SetXY(150,$cline+1.7); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
$pdf->MultiCell(55,4,$buss_name,0,'R',0);


// ผลรวมจำนวนเงิน
$pdf->SetXY(190,$cline+1.7); 
$s_down=iconv('UTF-8','windows-874',number_format($sumAmountAll,2));
$pdf->MultiCell(40,4,$s_down,0,'R',0);

$pdf->SetXY(230,$cline+1.7); 
$s_P_BEGINX=iconv('UTF-8','windows-874',number_format($sumPricipleAll,2));
$pdf->MultiCell(30,4,$s_P_BEGINX,0,'R',0);
  
$pdf->SetXY(260,$cline+1.7); 
$s_intall=iconv('UTF-8','windows-874',number_format($sumInterestAll,2));
$pdf->MultiCell(30,4,$s_intall,0,'R',0);

//ขีดเส้นขั้นรวม ใต้จำนวนเงินรวม
$pdf->SetXY(210,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(240,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(270,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(210,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(240,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(270,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->Output();
?>