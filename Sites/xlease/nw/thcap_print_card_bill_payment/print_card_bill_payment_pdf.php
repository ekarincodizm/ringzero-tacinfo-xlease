<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');
include("../../pChart/class/pDraw.class.php");
include("../../pChart/class/pBarcode128.class.php");
include("../../pChart/class/pImage.class.php");

$autoID = pg_escape_string($_GET["autoID"]); // ลำดับรายการ PK ในตาราง

// หาข้อมูลของ Card
$qry_data = pg_query("select * from \"thcap_print_card_bill_payment\" where \"autoID\" = '$autoID' ");
$res_data = pg_fetch_array($qry_data);
$CusFullName = $res_data["CusFullName"]; // ชื่อลูกค้า
$contractID = $res_data["contractID"]; // เลขที่สัญญา
$minPayment = $res_data["minPayment"]; // ยอดผ่อนขั้นต่ำ
$payDay = $res_data["payDay"]; // จ่ายทุกวันที่
$firstDueDate = $res_data["firstDueDate"]; // วันที่ครบกำหนดชำระงวดแรก
$note = $res_data["note"]; // หมายเหตุ

// รูปแบบจำนวนเงิน ทศนิยม 2 ตำแหน่ง
if($minPayment != ""){$minPayment = number_format($minPayment,2);}

// แปลงลำดับให้เป็นเลข 6 หลัก เพื่อใช้แทนรหัสบัตร
$cardID = $autoID; // รหัสบัตร
for($i=0; $i<6; $i++)
{
	if(strlen($cardID) < 6)
	{
		$cardID = "0".$cardID;
	}
}

// แปลงหมายเหตุที่เป็นหลายบรรทัด ให้เป็นบรรทัดเดียวยาวๆ
$note = str_replace("\r\n", " ", $note);

/* Create the barcode 128 object */
$Barcode = new pBarcode128("../../pChart/");
$pdf=new ThaiPDF('P' ,'mm','a4');  

$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

// encoed เลขที่สัญญาเป็น REF1
$qry_REF1 = pg_query("select \"ta_array1d_get\"(\"thcap_encode_invoice_ref\"('$contractID','000000IMG-00000',false),0)");
$REF1 = pg_fetch_result($qry_REF1,0); // ref1

$REF2 = 0; // ref2 ให้ fix เป็น 0

// define barcode style  
$styleBarcode = array(
	'position' => '',
	'align' => 'L',
	'stretch' => true,
	'fitwidth' => true,
	'cellfitalign' => '',
	'border' => false,
	'hpadding' => 'auto',
	'vpadding' => 'auto',
	'fgcolor' => array(0,0,0),
	'bgcolor' => false, //array(255,255,255),
	'text' => true,  // แสดง ค่า ด้านล่างบาร์โค้ด
	'font' => 'helvetica',
	'fontsize' => 6 ,
	'stretchtext' => 4
);


$total_sumdebtAmt=number_format($sum_debtAmt+$intFineAmt,2);
$str_total_sumdebtAmt =  str_replace(".","",$total_sumdebtAmt);
$str_total_sumdebtAmt =  str_replace(",","",$str_total_sumdebtAmt);
$companytaxid= "010555313699600";
 
//เขียนบาร์โค้ด ใช้   chr(0x0D) = '%0D' = Carrign Return
$cr = chr(0x0D);
$txtdata = "$companytaxid".$cr."$REF1".$cr."0".$cr."0";

$pdf->AddPage();

//---------- หัวกระดาษ
	$pdf->SetFont('AngsanaNew','',13);
	$pdf->SetXY(158,23);
	$textShow = iconv('UTF-8','windows-874',$contractID);
	$pdf->MultiCell(38,0.5,$textShow,0,'L',0); // เลขที่สัญญา
	
	$pdf->SetXY(26,40);
	$textShow = iconv('UTF-8','windows-874',$CusFullName);
	$pdf->MultiCell(98,0.5,$textShow,0,'L',0); // ชื่อลูกค้า
	
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(158,51);
	$textShow = iconv('UTF-8','windows-874',$minPayment);
	$pdf->MultiCell(25,0.5,$textShow,0,'L',0); // ยอดผ่อนขั้นต่ำ
	
	$pdf->SetXY(158,55);
	$textShow = iconv('UTF-8','windows-874',$payDay);
	$pdf->MultiCell(25,0.5,$textShow,0,'L',0); // จ่ายทุกวันที่
	
	$pdf->SetXY(175,60);
	$textShow = iconv('UTF-8','windows-874',$firstDueDate);
	$pdf->MultiCell(20,0.5,$textShow,0,'L',0); // วันที่ครบกำหนดชำระงวดแรก
//---------- จบหัวกระดาษ

//---------- การ์ดด้านซ้าย
	$pdf->SetFont('AngsanaNew','',8);
	$pdf->SetXY(48,74);
	$textShow = iconv('UTF-8','windows-874',$cardID);
	$pdf->MultiCell(50,0.5,$textShow,0,'R',0); // ลำดับบัตร
	
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(27,92);
	$textShow = iconv('UTF-8','windows-874',$CusFullName);
	$pdf->MultiCell(30,2.5,$textShow,0,'L',0); // ชื่อลูกค้า

	$pdf->SetXY(72,92);
	$textShow = iconv('UTF-8','windows-874',$contractID);
	$pdf->MultiCell(27,2.5,$textShow,0,'L',0); // เลขที่สัญญา

	$pdf->SetXY(82,120);
	$textShow = iconv('UTF-8','windows-874',$minPayment);
	$pdf->MultiCell(17,0.5,$textShow,0,'L',0); // ยอดผ่อนขั้นต่ำ

	$pdf->SetXY(82,123);
	$textShow = iconv('UTF-8','windows-874',$payDay);
	$pdf->MultiCell(17,0.5,$textShow,0,'L',0); // จ่ายทุกวันที่

	$pdf->SetFont('helvetica','',5);
	$pdf->SetXY(20,116);
	$txtRef1=iconv('UTF-8','windows-874',"REF1: ".$REF1);
	$pdf->MultiCell(37,0.5,$txtRef1,0,'L',0); //Ref1
	
	$pdf->SetFont('helvetica','',5);
	$pdf->SetXY(57,116);
	$txtRef2=iconv('UTF-8','windows-874',"REF2: ".$REF2);
	$pdf->MultiCell(37,0.5,$txtRef2,0,'L',0); //Ref2
	
	$pdf->SetXY(20,99);
	$pdf->write1DBarcode("|".$txtdata, 'C128', '', '', '75', '12', 1, $styleBarcode, 'N'); // barcode
//---------- จบการ์ดด้านซ้าย

//---------- การ์ดด้านขวา
	$pdf->SetFont('AngsanaNew','',8);
	$pdf->SetXY(144,74);
	$textShow = iconv('UTF-8','windows-874',$cardID);
	$pdf->MultiCell(50,0.5,$textShow,0,'R',0); // ลำดับบัตร
	
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(123,92);
	$textShow = iconv('UTF-8','windows-874',$CusFullName);
	$pdf->MultiCell(30,2.5,$textShow,0,'L',0); // ชื่อลูกค้า
	
	$pdf->SetXY(168,92);
	$textShow = iconv('UTF-8','windows-874',$contractID);
	$pdf->MultiCell(27,2.5,$textShow,0,'L',0); // เลขที่สัญญา

	$pdf->SetXY(178,120);
	$textShow = iconv('UTF-8','windows-874',$minPayment);
	$pdf->MultiCell(17,0.5,$textShow,0,'L',0); // ยอดผ่อนขั้นต่ำ

	$pdf->SetXY(178,123);
	$textShow = iconv('UTF-8','windows-874',$payDay);
	$pdf->MultiCell(17,0.5,$textShow,0,'L',0); // จ่ายทุกวันที่

	$pdf->SetFont('helvetica','',5);
	$pdf->SetXY(116,116);
	$txtRef1=iconv('UTF-8','windows-874',"REF1: ".$REF1);
	$pdf->MultiCell(37,0.5,$txtRef1,0,'L',0); //Ref1
	
	$pdf->SetFont('helvetica','',5);
	$pdf->SetXY(153,116);
	$txtRef2=iconv('UTF-8','windows-874',"REF2: ".$REF2);
	$pdf->MultiCell(37,0.5,$txtRef2,0,'L',0); //Ref2
	
	$pdf->SetXY(116,99);
	$pdf->write1DBarcode("|".$txtdata, 'C128', '', '', '75', '12', 1, $styleBarcode, 'N'); // barcode
//---------- จบการ์ดด้านขวา

//---------- หมายเหตุ
	$pdf->SetFont('AngsanaNew','',10);
	$pdf->SetXY(30,136);
	$textShow = iconv('UTF-8','windows-874',$note);
	$pdf->MultiCell(160,2.5,$textShow,0,'L',0); // หมายเหตุ
//---------- จบหมายเหตุ

$pdf->Output(); //open pdf
?>