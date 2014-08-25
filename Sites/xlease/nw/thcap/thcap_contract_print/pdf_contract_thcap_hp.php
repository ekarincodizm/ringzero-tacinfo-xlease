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
	$var5 = '750,000.00';   //ราคาเช่าซื้อไม่รวมเงินดาวน์
	$var6 = '20/12/2555';  //เริ่มชำระงวดแรก
	$var7 = '48';  //แบ่งชำระเป็น
	$var8 = '02'; //ชำระถายในวันที่
	
	$var9_0 = '*****';  		//งวดที่ block 1
	$var9_1 = '*****';		//ค่างวด block 1
	$var9_2 = '*****';		//ภาษีมูลค่าเพิ่ม block 1
	$var9_3 = '*****';  		//รวมชำระสุทธิงวดละ block 1
	
	$var10_0 = '*****'; 		//งวดที่ block 2
	$var10_1 = '*****';		//ค่างวด block 2
	$var10_2 = '*****';		//ภาษีมูลค่าเพิ่ม block 2
	$var10_3 = '*****';  	//รวมชำระสุทธิงวดละ block 2
	
	$var11_0 = '*****'; 		//งวดที่ block 3
	$var11_1 = '*****';		//ค่างวด block 3
	$var11_2 = '*****';		//ภาษีมูลค่าเพิ่ม block 3
	$var11_3 = '*****';  	//รวมชำระสุทธิงวดละ block 3
	
	$var12_0 = '*****'; 		//งวดที่ block 4
	$var12_1 = '*****';		//ค่างวด block 4
	$var12_2 = '*****';		//ภาษีมูลค่าเพิ่ม block 4
	$var12_3 = '*****';  	//รวมชำระสุทธิงวดละ block 4
	
	$var13_0 = '*****'; 		//งวดที่ block 5
	$var13_1 = '*****';		//ค่างวด block 5
	$var13_2 = '*****';		//ภาษีมูลค่าเพิ่ม block 5
	$var13_3 = '*****';  	//รวมชำระสุทธิงวดละ block 5
	
	$var14 = 'xxx-xxxxxx-xxxxxxxxxx';   //ชื่อผู้ทำสัญญา ( ลายเซ็น)
	
	
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

$page = $pdf->PageNo();	

$Y = 42.5;	
//เลขที่สัญญา	
$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(155,$Y);
$title=iconv('UTF-8','windows-874',$var1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 7.5;	
//วันที่ทำสัญญา	
$pdf->SetXY(165,$Y);
$title=iconv('UTF-8','windows-874',$var2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 15;	
//ผู้เช่าซื้อ	
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',$var3);
$pdf->MultiCell(160,4,$title,0,'C',0);

$Y += 50;
//รายละเอียดทรัพย์สินที่เช่าซื้อ	
$pdf->SetXY(25,$Y);
$title=iconv('UTF-8','windows-874',$var4);
$pdf->MultiCell(160,4,$title,0,'C',0);

$Y = 150;
//ราคาไม่รวม VAT
$pdf->SetXY(105,$Y);
$title=iconv('UTF-8','windows-874',$var5);
$pdf->MultiCell(70,4,$title,0,'L',0);

//วันที่เริ่มชำระ
$pdf->SetXY(171,$Y);
$title=iconv('UTF-8','windows-874',$var6);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 7;
//แบ่งชำระเป็น
$pdf->SetXY(60,$Y);
$title=iconv('UTF-8','windows-874',$var7);
$pdf->MultiCell(70,4,$title,0,'L',0);

//ชำทุกวันที่ 
$pdf->SetXY(110,$Y);
$title=iconv('UTF-8','windows-874',$var8);
$pdf->MultiCell(70,4,$title,0,'L',0);


$Y += 23.5;
//ตารางค่างวดช่องที่ 1
$pdf->SetXY(45,$Y);
$title=iconv('UTF-8','windows-874',$var9_0);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$var9_1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(135,$Y);
$title=iconv('UTF-8','windows-874',$var9_2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(167,$Y);
$title=iconv('UTF-8','windows-874',$var9_3);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 9;
//ตารางค่างวดช่องที่ 2
$pdf->SetXY(45,$Y);
$title=iconv('UTF-8','windows-874',$var10_0);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$var10_1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(135,$Y);
$title=iconv('UTF-8','windows-874',$var10_2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(167,$Y);
$title=iconv('UTF-8','windows-874',$var10_3);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 9;
//ตารางค่างวดช่องที่ 3
$pdf->SetXY(45,$Y);
$title=iconv('UTF-8','windows-874',$var11_0);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$var11_1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(135,$Y);
$title=iconv('UTF-8','windows-874',$var11_2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(167,$Y);
$title=iconv('UTF-8','windows-874',$var11_3);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 9;
//ตารางค่างวดช่องที่ 4
$pdf->SetXY(45,$Y);
$title=iconv('UTF-8','windows-874',$var12_0);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$var12_1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(135,$Y);
$title=iconv('UTF-8','windows-874',$var12_2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(167,$Y);
$title=iconv('UTF-8','windows-874',$var12_3);
$pdf->MultiCell(70,4,$title,0,'L',0);

$Y += 9;
//ตารางค่างวดช่องที่ 5
$pdf->SetXY(45,$Y);
$title=iconv('UTF-8','windows-874',$var13_0);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(95,$Y);
$title=iconv('UTF-8','windows-874',$var13_1);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(135,$Y);
$title=iconv('UTF-8','windows-874',$var13_2);
$pdf->MultiCell(70,4,$title,0,'L',0);

$pdf->SetXY(167,$Y);
$title=iconv('UTF-8','windows-874',$var13_3);
$pdf->MultiCell(70,4,$title,0,'L',0);
	
$Y += 30.5;	
//ลงชื่อผู้เช่าซื้อ
$pdf->SetXY(70,$Y);
$title=iconv('UTF-8','windows-874',$var14);
$pdf->MultiCell(80,4,$title,0,'C',0);
	
$pdf->Output();	

?>



