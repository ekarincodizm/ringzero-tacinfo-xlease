<?php
session_start();
include("../../config/config.php");

$type = $_GET['type'];
$datepicker = $_GET['date'];
$nowdate = nowDate();
$check_pdf = $_GET['pdf']; // check เช็คว่าเลือกจากวันที่ทำรายการหรือวันที่อนุมัติ
if($check_pdf == 1)
{
	$text_date = "โดยเลือกจากวันที่ทำรายการ";
}
else
{
	$text_date = "โดยเลือกจากวันที่อนุมัติรายการ";
}

$type_view = $_GET['type_view'];
if($type_view == 1) // แสดงเฉพาะอนุมัติ
{
	$view = "and \"thcap_invoice_action\".\"appvXStatus\" = '1' and \"thcap_invoice_action\".\"appvYStatus\" = '1' ";
	$text_view = "และแสดงรายการเฉพาะอนุมัติ";
}
elseif($type_view == 2) // แสดงเฉพาะไม่อนุมัติ
{
	$view = "and (\"thcap_invoice_action\".\"appvXStatus\" = '0' or \"thcap_invoice_action\".\"appvYStatus\" = '0')";
	$text_view = "และแสดงรายการเฉพาะไม่อนุมัติ";
}
elseif($type_view == 3) // แสดงเฉพาะรออนุมัติ
{
	$view = "and  ((\"appvXStatus\" is NULL and \"appvYStatus\" ='1') or (\"appvYStatus\" is NULL and \"appvXStatus\" ='1') or (\"appvXStatus\" is NULL and \"appvYStatus\" is null )) ";
	$text_view = "และแสดงรายการเฉพาะรออนุมัติ";
}
if($type_view == 4) // แสดงเฉพาะอนุมัติ
{
	$view = "";
	$text_view = "และแสดงรายการทั้งหมด";
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

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(5,10);
$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
$pdf->MultiCell(200,4,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',15);
$pdf->SetXY(5,16);
$buss_name=iconv('UTF-8','windows-874',"รายงานตั้งหนี้ประจำวัน");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,21);
$buss_name=iconv('UTF-8','windows-874',"($text_date $text_view)");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
$pdf->MultiCell(50,4,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',12);
$pdf->SetXY(5,25);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(200,4,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(5,26);
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

$pdf->SetXY(5,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบแจ้งหนี้");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(24,32);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(45,32);
$buss_name=iconv('UTF-8','windows-874',"วันที่ออกใบแจ้งหนี้");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(68,32);
$buss_name=iconv('UTF-8','windows-874',"รายการค่าใช้จ่าย");
$pdf->MultiCell(19,4,$buss_name,0,'C',0);

$pdf->SetXY(82,32);
$buss_name=iconv('UTF-8','windows-874',"Ref");
$pdf->MultiCell(25,4,$buss_name,0,'C',0);

$pdf->SetXY(100,32);
$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(123,32);
$buss_name=iconv('UTF-8','windows-874',"ผู้ทำรายการ");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(148,32);
$buss_name=iconv('UTF-8','windows-874',"ผู้อนุมัติคนที่ 1");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(178,32);
$buss_name=iconv('UTF-8','windows-874',"ผู้อนุมัติคนที่ 2");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(5,35);
$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
$pdf->MultiCell(200,4,$buss_name,0,'C',0);

//=========================//

$pdf->SetFont('AngsanaNew','',13);
$cline = 39;
$nub = 1;

if($check_pdf == 1) // เลือกจากวันที่ทำรายการ
{
$query=pg_query("select \"thcap_invoice\".\"invoiceID\" , \"thcap_invoice\".\"contractID\" , \"thcap_invoice\".\"invoiceDate\" , \"thcap_invoice\".\"invoiceTypePay\"
				, \"thcap_invoice\".\"invoiceAmt\" , \"thcap_invoice\".\"invoiceVATRate\" , \"thcap_invoice\".\"invoiceAmtVAT\" , \"thcap_invoice\".\"invoiceWHTRate\"
				, \"thcap_invoice_action\".\"doerID\" , \"thcap_invoice_action\".\"doerStamp\" , \"thcap_invoice_action\".\"appvXID\" , \"thcap_invoice_action\".\"appvYID\"
				, \"thcap_invoice_action\".\"appvXStamp\" , \"thcap_invoice_action\".\"appvYStamp\" , \"thcap_typePay\".\"tpDesc\" , \"thcap_invoice\".\"invoiceTypePayRef\"
				from account.\"thcap_invoice\" , account.\"thcap_invoice_action\" , account.\"thcap_typePay\"
				WHERE \"thcap_invoice\".\"invoiceID\" = \"thcap_invoice_action\".\"invoiceID\"
						and \"thcap_invoice\".\"invoiceTypePay\" = \"thcap_typePay\".\"tpID\"
						and \"thcap_invoice_action\".\"doerStamp\" = '$datepicker'
						and \"thcap_invoice_action\".\"invActionType\" = 'I' $view
						order by \"thcap_invoice\".\"invoiceID\" ");
$num_row = pg_num_rows($query);
}

if($check_pdf == 2) // เลือกจากวันที่อนุมัติ
{
$query=pg_query("select \"thcap_invoice\".\"invoiceID\" , \"thcap_invoice\".\"contractID\" , \"thcap_invoice\".\"invoiceDate\" , \"thcap_invoice\".\"invoiceTypePay\"
				, \"thcap_invoice\".\"invoiceAmt\" , \"thcap_invoice\".\"invoiceVATRate\" , \"thcap_invoice\".\"invoiceAmtVAT\" , \"thcap_invoice\".\"invoiceWHTRate\"
				, \"thcap_invoice_action\".\"doerID\" , \"thcap_invoice_action\".\"doerStamp\" , \"thcap_invoice_action\".\"appvXID\" , \"thcap_invoice_action\".\"appvYID\"
				, \"thcap_invoice_action\".\"appvXStamp\" , \"thcap_invoice_action\".\"appvYStamp\" , \"thcap_typePay\".\"tpDesc\" , \"thcap_invoice\".\"invoiceTypePayRef\"
				from account.\"thcap_invoice\" , account.\"thcap_invoice_action\" , account.\"thcap_typePay\"
				WHERE \"thcap_invoice\".\"invoiceID\" = \"thcap_invoice_action\".\"invoiceID\"
					and \"thcap_invoice\".\"invoiceTypePay\" = \"thcap_typePay\".\"tpID\"
					and \"thcap_invoice_action\".\"appvYStamp\" <= \"thcap_invoice_action\".\"appvXStamp\"
					and \"thcap_invoice_action\".\"appvXStamp\"='$datepicker'
					and \"thcap_invoice_action\".\"invActionType\" = 'I' $view
					order by \"thcap_invoice\".\"invoiceID\" ");
$num_row = pg_num_rows($query);

if($num_row == 0) // ถ้าไม่เจอลองเช็คอีกแบบ
{
$query=pg_query("select \"thcap_invoice\".\"invoiceID\" , \"thcap_invoice\".\"contractID\" , \"thcap_invoice\".\"invoiceDate\" , \"thcap_invoice\".\"invoiceTypePay\"
				, \"thcap_invoice\".\"invoiceAmt\" , \"thcap_invoice\".\"invoiceVATRate\" , \"thcap_invoice\".\"invoiceAmtVAT\" , \"thcap_invoice\".\"invoiceWHTRate\"
				, \"thcap_invoice_action\".\"doerID\" , \"thcap_invoice_action\".\"doerStamp\" , \"thcap_invoice_action\".\"appvXID\" , \"thcap_invoice_action\".\"appvYID\"
				, \"thcap_invoice_action\".\"appvXStamp\" , \"thcap_invoice_action\".\"appvYStamp\" , \"thcap_typePay\".\"tpDesc\" , \"thcap_invoice\".\"invoiceTypePayRef\"
				from account.\"thcap_invoice\" , account.\"thcap_invoice_action\" , account.\"thcap_typePay\"
				WHERE \"thcap_invoice\".\"invoiceID\" = \"thcap_invoice_action\".\"invoiceID\"
					and \"thcap_invoice\".\"invoiceTypePay\" = \"thcap_typePay\".\"tpID\"
					and \"thcap_invoice_action\".\"appvYStamp\" >= \"thcap_invoice_action\".\"appvXStamp\"
					and \"thcap_invoice_action\".\"appvYStamp\"='$datepicker'
					and \"thcap_invoice_action\".\"invActionType\" = 'I' $view
					order by \"thcap_invoice\".\"invoiceID\" ");
$num_row = pg_num_rows($query);

}

}

while($resvc=pg_fetch_array($query)){	
    $invoiceID = $resvc['invoiceID'];
    $contractID = $resvc['contractID'];
    $invoiceDate = $resvc['invoiceDate'];
    $invoiceAmt = $resvc['invoiceAmt'];
    $invoiceVATRate = $resvc['invoiceVATRate'];
    $invoiceAmtVAT = $resvc['invoiceAmtVAT'];
    $doerID = $resvc['doerID'];
	$doerStamp = $resvc['doerStamp'];
	$appvXID = $resvc['appvXID'];
	$appvYID = $resvc['appvYID'];
	$appvXStamp = $resvc['appvXStamp'];
	$appvYStamp = $resvc['appvYStamp'];
	$tpDesc = $resvc['tpDesc'];
	$invoiceTypePayRef = $resvc['invoiceTypePayRef'];
	
	$query_doer=pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
	while($resvc=pg_fetch_array($query_doer)){
    $fullname_doer = $resvc['fullname'];
	}
	
	if($appvXID == ""){$appvXID = "";}
	else{
	$query_appvX=pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$appvXID' ");
	while($resvcX=pg_fetch_array($query_appvX)){
    $appvXID = $resvcX['fullname'];
	}
	}
	
	if($appvYID == ""){$appvYID = "";}
	else{
	$query_appvY=pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$appvYID' ");
	while($resvcY=pg_fetch_array($query_appvY)){
    $appvYID = $resvcY['fullname'];
	}
	}
    
	if($invoiceDate =="")
	{
		$invoiceDate="-";
	}
	else
	{
		$invoiceDate=substr($invoiceDate,0,10);
	}

	if($doerStamp =="")
	{
		$doerStamp="-";
	}
	else
	{
		$doerStamp=substr($doerStamp,0,10);
	}
	
    $sum_amt+=$invoiceAmt;
    $sum_amt_all+=$invoiceAmt;
   
    if($nub == 46){
        $nub = 1;
        $cline = 39;
        $pdf->AddPage();
        
        $pdf->SetFont('AngsanaNew','B',18);
		$pdf->SetXY(5,10);
		$title=iconv('UTF-8','windows-874',"บริษัท ".$_SESSION["session_company_thainame"]);
		$pdf->MultiCell(200,4,$title,0,'C',0);

		$pdf->SetFont('AngsanaNew','',15);
		$pdf->SetXY(5,16);
		$buss_name=iconv('UTF-8','windows-874',"รายงานตั้งหนี้ประจำวัน");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,21);
		$buss_name=iconv('UTF-8','windows-874',"($text_date $text_view)");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"ประจำวันที่ $datepicker");
		$pdf->MultiCell(50,4,$buss_name,0,'L',0);

		$pdf->SetFont('AngsanaNew','',12);
		$pdf->SetXY(5,25);
		$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
		$pdf->MultiCell(200,4,$buss_name,0,'R',0);

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(5,26);
		$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่ใบแจ้งหนี้");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(24,32);
		$buss_name=iconv('UTF-8','windows-874',"เลขที่สัญญา");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(45,32);
		$buss_name=iconv('UTF-8','windows-874',"วันที่ออกใบแจ้งหนี้");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);

		$pdf->SetXY(68,32);
		$buss_name=iconv('UTF-8','windows-874',"รายการค่าใช้จ่าย");
		$pdf->MultiCell(19,4,$buss_name,0,'C',0);

		$pdf->SetXY(82,32);
		$buss_name=iconv('UTF-8','windows-874',"Ref");
		$pdf->MultiCell(25,4,$buss_name,0,'C',0);
	
		$pdf->SetXY(100,32);
		$buss_name=iconv('UTF-8','windows-874',"จำนวนเงิน");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(123,32);
		$buss_name=iconv('UTF-8','windows-874',"ผู้ทำรายการ");
		$pdf->MultiCell(15,4,$buss_name,0,'C',0);

		$pdf->SetXY(148,32);
		$buss_name=iconv('UTF-8','windows-874',"ผู้อนุมัติคนที่ 1");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(178,32);
		$buss_name=iconv('UTF-8','windows-874',"ผู้อนุมัติคนที่ 2");
		$pdf->MultiCell(20,4,$buss_name,0,'C',0);

		$pdf->SetXY(5,35);
		$buss_name=iconv('UTF-8','windows-874',"________________________________________________________________________________________________________________________________________________________________________");
		$pdf->MultiCell(200,4,$buss_name,0,'C',0);
    }
	

$pdf->SetFont('AngsanaNew','',10);
    
$pdf->SetXY(5,$cline);
$buss_name=iconv('UTF-8','windows-874',"$invoiceID");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(26,$cline);
$buss_name=iconv('UTF-8','windows-874',"$contractID");
$pdf->MultiCell(25,4,$buss_name,0,'L',0);

$pdf->SetXY(47,$cline);
$buss_name=iconv('UTF-8','windows-874',"$invoiceDate");
$pdf->MultiCell(20,4,$buss_name,0,'C',0);

$pdf->SetXY(57,$cline);
$buss_name=iconv('UTF-8','windows-874',"$tpDesc");
$pdf->MultiCell(40,4,$buss_name,0,'C',0);

$pdf->SetXY(87,$cline);
$buss_name=iconv('UTF-8','windows-874',"$invoiceTypePayRef");
$pdf->MultiCell(15,4,$buss_name,0,'C',0);

$pdf->SetXY(87,$cline);
$buss_name=iconv('UTF-8','windows-874',number_format($invoiceAmt,2));
$pdf->MultiCell(30,4,$buss_name,0,'R',0);

$pdf->SetXY(120,$cline);
$buss_name=iconv('UTF-8','windows-874',"$fullname_doer");
$pdf->MultiCell(30,4,$buss_name,0,'L',0);

$pdf->SetXY(147,$cline); // นำคนที่อนุมัติคนแรกขึ้นก่อน
if($appvXStamp < $appvYStamp || $appvYStamp == ""){$buss_name=iconv('UTF-8','windows-874',"$appvXID");}else{$buss_name=iconv('UTF-8','windows-874',"$appvYID");}
$pdf->MultiCell(40,4,$buss_name,0,'L',0);

$pdf->SetXY(177,$cline); // นำคนที่อนุมัติคนที่สองมาทีหลัง
if($appvXStamp != "" && $appvYStamp != ""){ if($appvXStamp < $appvYStamp){$buss_name=iconv('UTF-8','windows-874',"$appvYID");}else{$buss_name=iconv('UTF-8','windows-874',"$appvXID");} }
else{$buss_name=iconv('UTF-8','windows-874',"");}
$pdf->MultiCell(40,4,$buss_name,0,'L',0);
    
    $cline += 5;
    $nub+=1;
}


if($num_row > 0){
    $pdf->SetFont('AngsanaNew','B',13);
    $pdf->SetXY(0,$cline);
    $buss_name=iconv('UTF-8','windows-874',"รวมเงิน ".number_format($sum_amt,2));
    $pdf->MultiCell(117,4,$buss_name,0,'R',0);

    $pdf->SetXY(5,$cline+1);
    $buss_name=iconv('UTF-8','windows-874',"__________________________________________________________________________________________________________________________________");
    $pdf->MultiCell(200,4,$buss_name,0,'C',0);
    
    $cline += 6;
    $nub+=1;
}

$pdf->Output();
?>