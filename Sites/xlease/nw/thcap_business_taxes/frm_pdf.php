<?php
include("../../config/config.php");
include("../../core/core_functions.php");
include("../function/nameMonth.php");

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server


$checkoption = pg_escape_string($_GET["op1"]);
$selectSort = pg_escape_string($_GET["sort"]); // การเรียงข้อมูลที่เลือก
	
	IF($checkoption == 'my'){
		$month = pg_escape_string($_GET["month"]);
		$year = pg_escape_string($_GET["year"]);
		$whereOther = " and EXTRACT(MONTH FROM \"thcap_receiptIDToReceiveDate\"(\"receiptID\")) = '$month' and EXTRACT(YEAR FROM \"thcap_receiptIDToReceiveDate\"(\"receiptID\")) = '$year'";
		$show_month = nameMonthTH($month);
		$txtheader = 'ประจำเดือน ';
	}else if($checkoption == 'y'){
		$year = pg_escape_string($_GET["year"]);
		$whereOther = " and EXTRACT(YEAR FROM \"thcap_receiptIDToReceiveDate\"(\"receiptID\")) = '$year'";
		$txtheader = 'ประจำปี ค.ศ. ';
	}
	
	if($selectSort == "s1")
	{
		$mySort = "order by \"contractID\"";
	}
	elseif($selectSort == "s2")
	{
		$mySort = "order by \"receiptID\"";
	}
	elseif($selectSort == "s3")
	{
		$mySort = "order by \"receiveDate\"";
	}
	elseif($selectSort == "s4")
	{
		$mySort = "order by \"typePayID\"";
	}
	elseif($selectSort == "s5")
	{
		$mySort = "order by \"tpDesc\"";
	}

// ปีที่จะแสดงในรายงาน
//$show_yy = $year+543;
$show_yy = "$year";


// ------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header()
	{
        $this->SetFont('AngsanaNew','',12);
        $this->SetXY(5,16); 
        $buss_name=iconv('UTF-8','windows-874',"หน้า ".$this->PageNo()."/tp");
        $this->MultiCell(200,4,$buss_name,0,'R',0);
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
$title=iconv('UTF-8','windows-874',"(THCAP) รายงานภาษีธุรกิจเฉพาะ");
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',12); 
$pdf->SetXY(5,22); 
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetXY(10,15);
$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$gmm=iconv('UTF-8','windows-874',"$txtheader $show_month $show_yy");
$pdf->Text(5,26,$gmm);

$pdf->SetXY(4,24); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(200,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(3,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(28,4,$buss_name,0,'C',0);

$pdf->SetXY(31,30); 
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(51,30); 
$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
$pdf->MultiCell(23,4,$buss_name,0,'C',0);

$pdf->SetXY(74,30); 
$buss_name=iconv('UTF-8','windows-874',"รหัสค่าใช้จ่าย");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(89,30); 
$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
$pdf->MultiCell(38,4,$buss_name,0,'C',0);

$pdf->SetXY(127,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',8);
$pdf->SetXY(147,30); 
$buss_name=iconv('UTF-8','windows-874',"อัตราภาษีธุรกิจเฉพาะ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(167,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนภาษีธุรกิจเฉพาะ");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(187,30); 
$buss_name=iconv('UTF-8','windows-874',"จำนวนภาษีโรงเรือน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(4,32); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(200,4,$buss_name,'B','L',0);

$pdf->SetFont('AngsanaNew','',10);
$cline = 37;
$i = 1;
$j = 0;  

$qry_in=pg_query("
	select \"thcap_receiptIDToContractID\"(a.\"receiptID\") as \"contractID\", a.\"typePayRefValue\", a.\"receiptID\", \"thcap_receiptIDToReceiveDate\"(a.\"receiptID\") as \"receiveDate\",
		a.\"debtID\", a.\"typePayID\", a.\"tpDesc\", a.\"netAmt\", b.\"curSBTRate\", 
		(a.\"netAmt\"*b.\"curSBTRate\"/100)::numeric(15,2) as \"businessTaxes\", ((a.\"netAmt\"*b.\"curSBTRate\"/100)::numeric(15,2)*0.1)::numeric(15,2) as \"localTaxes\"
	from thcap_temp_receipt_otherpay a
	left join account.\"thcap_typePay\" b on a.\"typePayID\" = b.\"tpID\"
	left join thcap_temp_otherpay_debt c on a.\"debtID\" = c.\"debtID\"
	where b.\"curSBTRate\" is not null $whereOther

	union

	select d.\"contractID\", d.\"contractID\" as \"typePayRefValue\", d.\"receiptID\", d.\"receiveDate\",
		null as \"debtID\", e.\"tpID\" as \"typePayID\", e.\"tpDesc\", d.\"receiveInterest\" as \"netAmt\", e.\"curSBTRate\", 
		(d.\"receiveInterest\"*e.\"curSBTRate\"/100)::numeric(15,2) as \"businessTaxes\", ((d.\"receiveInterest\"*e.\"curSBTRate\"/100)::numeric(15,2)*0.1)::numeric(15,2) as \"localTaxes\"
	from thcap_temp_int_201201 d
	left join account.\"thcap_typePay\" e on e.\"tpID\" = account.\"thcap_mg_getInterestType\"(d.\"contractID\")
	where e.\"curSBTRate\" is not null and d.\"isReceiveReal\" = '1' and d.\"receiptID\" is not null and d.\"receiveInterest\" > 0.00 $whereOther
	
	union

	select f.\"contractID\", f.\"typePayRefValue\", f.\"receiptID\", f.\"receiveDate\", f.\"debtID\", f.\"typePayID\", f.\"tpDesc\", f.\"netAmt\", f.\"curSBTRate\", f.\"businessTaxes\", f.\"localTaxes\"
	from \"v_thcap_receive_factoring_facfee\" f
	where f.\"curSBTRate\" is not null $whereOther

	union

	select g.\"contractID\", g.\"typePayRefValue\", g.\"receiptID\", g.\"receiveDate\", g.\"debtID\", g.\"typePayID\", g.\"tpDesc\", g.\"netAmt\", g.\"curSBTRate\", g.\"businessTaxes\", g.\"localTaxes\"
	from \"v_thcap_receive_factoring_interest\" g
	where g.\"curSBTRate\" is not null $whereOther
	
	$mySort");

$sumNetAmtAll = 0; // จำนวนเงิน net รวมทั้งหมด
$sumBusinessTaxesAll = 0; // จำนวนภาษีธุรกิจเฉพาะ รวมทั้งหมด

while($res=pg_fetch_array($qry_in))
{
	$contractID = $res["contractID"]; // เลขที่สัญญา
	$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
	$receiveDate = $res["receiveDate"]; // วันที่รับชำระ
	$typePayRefValue = $res["typePayRefValue"]; // เลขที่อ้างอิง
	$typePayID = $res["typePayID"]; // รหัสประเภทค่าใช้จ่าย
	$tpDesc = $res["tpDesc"]; // ชื่อประเภทค่าใช้จ่าย
	$netAmt = $res["netAmt"]; // จำนวนเงิน net
	$curSBTRate = $res["curSBTRate"]; // อัตราภาษีธุรกิจเฉพาะ
	$businessTaxes = $res["businessTaxes"]; // จำนวนภาษีธุรกิจเฉพาะ
	$localTaxes = $res["localTaxes"]; // จำนวนภาษีท้องถิ่น
	
	$sumNetAmtAll += $netAmt; // จำนวนเงิน net รวมทั้งหมด
	$sumBusinessTaxesAll += $businessTaxes; // จำนวนภาษีธุรกิจเฉพาะ รวมทั้งหมด
	$sumLocalTaxes += $localTaxes;

	if($i > 46)
	{
		$pdf->AddPage(); 
		$cline = 37; 
		$i=1; 

		$pdf->SetFont('AngsanaNew','B',15);
		$pdf->SetXY(10,10);
		$title=iconv('UTF-8','windows-874',"(THCAP) รายงานภาษีธุรกิจเฉพาะ");
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12); 
		$pdf->SetXY(5,22); 
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ ".$nowdate);
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetXY(10,15);
		$buss_name=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION['session_company_thainame_thcap']);
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$gmm=iconv('UTF-8','windows-874',"$txtheader $show_month $show_yy");
		$pdf->Text(5,26,$gmm);

		$pdf->SetXY(4,24); 
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,'B','L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(3,30); 
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(28,4,$buss_name,0,'C',0);

		$pdf->SetXY(31,30); 
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบเสร็จ");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(51,30); 
		$buss_name=iconv('UTF-8','windows-874',"วันที่ชำระ");
		$pdf->MultiCell(23,4,$buss_name,0,'C',0);

		$pdf->SetXY(74,30); 
		$buss_name=iconv('UTF-8','windows-874',"รหัสค่าใช้จ่าย");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(89,30); 
		$buss_name=iconv('UTF-8','windows-874',"ประเภทค่าใช้จ่าย");
		$pdf->MultiCell(38,4,$buss_name,0,'C',0);

		$pdf->SetXY(127,30); 
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',8);
		$pdf->SetXY(147,30); 
		$buss_name=iconv('UTF-8','windows-874',"อัตราภาษีธุรกิจเฉพาะ");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(167,30); 
		$buss_name=iconv('UTF-8','windows-874',"จำนวนภาษีธุรกิจเฉพาะ");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(187,30); 
		$buss_name=iconv('UTF-8','windows-874',"จำนวนภาษีโรงเรือน");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(4,32); 
		$buss_name=iconv('UTF-8','windows-874',"");
		$pdf->MultiCell(200,4,$buss_name,'B','L',0);

		$pdf->SetFont('AngsanaNew','',10);
		$cline = 37;
		$i = 1;
		$j = 0;
	}

// -----------

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(3,$cline); 
$buss_name=iconv('UTF-8','windows-874',$contractID);
$pdf->MultiCell(28,4,$buss_name,0,'L',0);

$pdf->SetXY(31,$cline); 
$buss_name=iconv('UTF-8','windows-874',$receiptID);
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(51,$cline); 
$buss_name=iconv('UTF-8','windows-874',$receiveDate);
$pdf->MultiCell(23,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',11);
$pdf->SetXY(74,$cline); 
$buss_name=iconv('UTF-8','windows-874',$typePayID);
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(89,$cline); 
$buss_name=iconv('UTF-8','windows-874',$tpDesc);
$pdf->MultiCell(38,4,$buss_name,0,'L',0);

$pdf->SetXY(127,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($netAmt,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
  
$pdf->SetXY(147,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($curSBTRate,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(167,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($businessTaxes,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);

$pdf->SetXY(187,$cline); 
$buss_name=iconv('UTF-8','windows-874',number_format($localTaxes,2));
$pdf->MultiCell(20,4,$buss_name,0,'R',0);
// -----------

$cline+=5; 
$i+=1;       
}  

$pdf->SetFont('AngsanaNew','B',10);
//ขีดเส้นขั้นรวม 3 เส้นแรก
$pdf->SetXY(127,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(167,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(187,$cline-2); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);


$pdf->SetXY(89,$cline+1.7); 
$buss_name=iconv('UTF-8','windows-874',"รวมทั้งสิ้น");
$pdf->MultiCell(38,4,$buss_name,0,'C',0);


// ผลรวมจำนวนเงิน
$pdf->SetXY(127,$cline+1.7); 
$s_down=iconv('UTF-8','windows-874',number_format($sumNetAmtAll,2));
$pdf->MultiCell(20,4,$s_down,0,'R',0);

$pdf->SetXY(167,$cline+1.7); 
$s_P_BEGINX=iconv('UTF-8','windows-874',number_format($sumBusinessTaxesAll,2));
$pdf->MultiCell(20,4,$s_P_BEGINX,0,'R',0);

$pdf->SetXY(187,$cline+1.7); 
$s_P_BEGINX=iconv('UTF-8','windows-874',number_format($sumLocalTaxes,2));
$pdf->MultiCell(20,4,$s_P_BEGINX,0,'R',0);

//ขีดเส้นขั้นรวม ใต้จำนวนเงินรวม
$pdf->SetXY(127,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(167,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(187,$cline+3); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(127,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(167,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->SetXY(187,$cline+3.5); 
$buss_name=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(20,4,$buss_name,'B','R',0);

$pdf->Output();
?>