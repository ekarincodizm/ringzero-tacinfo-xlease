<?php
session_start();
include("../../../config/config.php");
require('../../../thaipdfclass.php');
/*-============================================================================-
								   สัญญาเช่าซื้อ	
								ดึงข้อมูลจากตาราง
-============================================================================-*/
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$contractID=$_GET["contractID"];



/*-============================================================================-
								   สัญญาเช่าซื้อ	
								กำหนดรายละเอียด
-============================================================================-*/
	$var1 = $contractID;	//เลขที่สัญญา
	$var2 = Date("d/m/Y");       //วันที่ทำสัญญา
	$var3 = 'xxx-xxxxxx-xxxxxxxxxx';   //ชื่อผู้ทำสัญญา
	$var4 =  'รายละเอียดทรัพย์สิน';    //รายละเอียดทรัพย์สิน
	$var5 = 'xxx-xxxxxx-xxxxxxxxxx';   //ชื่อผู้ทำสัญญา ( ลายเซ็น)
	
	
/*-============================================================================-*/	
	

	
	
// ------------------- PDF -------------------//


class PDF extends ThaiPDF
{

}


$pdf=new PDF('P','mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();



$Y = 37;	
//เลขที่สัญญา	
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(150,$Y);
$title=iconv('UTF-8','windows-874',$var1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 11;	
//วันที่ทำสัญญา	
$pdf->SetXY(40,$Y);
$title=iconv('UTF-8','windows-874',$var2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 18;	
//ผู้เช่าซื้อ	
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',$var3);
$pdf->MultiCell(160,4,$title,0,'C',0);

$Y += 40;
//รายละเอียดทรัพย์สินที่เช่า
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',$var4);
$pdf->MultiCell(160,4,$title,0,'C',0);

$pdf->AddPage();

$Y = 236.5;	
//ลงชื่อผู้เช่าซื้อ
$pdf->SetXY(65,$Y);
$title=iconv('UTF-8','windows-874',$var5);
$pdf->MultiCell(80,4,$title,0,'C',0);
	
$pdf->Output();	

?>



