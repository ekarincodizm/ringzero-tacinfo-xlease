<?php
include("../../config/config.php");

$id = $_GET['id'];
$gid = $_GET['gid'];
$nowdate = date("Y-m-d");

$qry=pg_query("select * from gas.\"PoGas\" where poid='$id' ");
if( $res=pg_fetch_array($qry) ){
    $idcompany=$res["idcompany"];
    $idmodel=$res["idmodel"];
    $cost=$res["costofgas"];
    $vatofcost=$res["vatofcost"];
    $date_install=$res["date_install"];
    $memo=$res["memo"];
    
    $qry_com=pg_query("select * from gas.\"Company\" where coid='$idcompany' ");
    if( $res_com=pg_fetch_array($qry_com) ){
        $name=$res_com["coname"];
        $address=$res_com["address"];
        $phone=$res_com["phone"];
    }
    
}

$g_thai_cost=pg_query("select conversionnumtothaitext($cost)");
$res_thai_cost=pg_fetch_result($g_thai_cost,0);

$qry_gas=pg_query("select * from \"FGas\" where \"GasID\"='$gid'");
if( $res_gas=pg_fetch_array($qry_gas) ){
    $car_regis=$res_gas["car_regis"];
    $carnum=$res_gas["carnum"];
    $marnum=$res_gas["marnum"];
}

//------------------- PDF -------------------//
require('../../thaipdfclass.php');

class PDF extends ThaiPDF{
    
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$pdf->Image('avlogo.jpg',10,10,22,13);

$pdf->SetFont('AngsanaNew','B',20);
$pdf->SetXY(35,12);
$title=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(50,4,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(35,17);
$title=iconv('UTF-8','windows-874',"AV. LEASING CO., LTD.");
$pdf->MultiCell(50,4,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(10,23);
$title=iconv('UTF-8','windows-874',"สาขาจรัญสนิทวงศ์   : 667 ถนนจรัญสนิทวงศ์ แขวงอรุณอมรินทร์ เขตบางกอกน้อย กรุงเทพฯ 10700 โทร. 02 882 5533 โทรสาร 02 882 5530");
$pdf->MultiCell(180,4,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,38);
$title=iconv('UTF-8','windows-874',"ใบสั่งซื้อ");
$pdf->MultiCell(190,4,$title,0,'C',0);

$pdf->SetXY(10,43);
$title=iconv('UTF-8','windows-874',"Purchase Order");
$pdf->MultiCell(190,4,$title,0,'C',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,57);
$title=iconv('UTF-8','windows-874',"ผู้ติดตั้ง
Saller's Name");
$pdf->MultiCell(25,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(35,57);
$title=iconv('UTF-8','windows-874',$name);
$pdf->MultiCell(70,6,$title,0,'L',0);
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(115,57);
$title=iconv('UTF-8','windows-874',"เลขที่
No.");
$pdf->MultiCell(25,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(140,57);
$title=iconv('UTF-8','windows-874',$id);
$pdf->MultiCell(60,6,$title,0,'C',0);
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,69);
$title=iconv('UTF-8','windows-874',"ที่อยู่
Address");
$pdf->MultiCell(25,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(35,69);
$title=iconv('UTF-8','windows-874',$address);
$pdf->MultiCell(70,6,$title,0,'L',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(115,69);
$title=iconv('UTF-8','windows-874',"วันที่สั่ง
Order Date");
$pdf->MultiCell(25,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(140,69);
$title=iconv('UTF-8','windows-874',$nowdate);
$pdf->MultiCell(60,6,$title,0,'C',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,81);
$title=iconv('UTF-8','windows-874',"โทรศัพท์
Telephone");
$pdf->MultiCell(25,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(35,81);
$title=iconv('UTF-8','windows-874',$phone);
$pdf->MultiCell(70,6,$title,0,'L',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(115,81);
$title=iconv('UTF-8','windows-874',"วันที่ติดตั้ง
Set Date");
$pdf->MultiCell(25,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(140,81);
$title=iconv('UTF-8','windows-874',$date_install);
$pdf->MultiCell(60,6,$title,0,'C',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(10,100);
$title=iconv('UTF-8','windows-874',"เลขที่
No.");
$pdf->MultiCell(20,6,$title,1,'C',0);

$pdf->SetXY(30,100);
$title=iconv('UTF-8','windows-874',"รายการ
Description");
$pdf->MultiCell(90,6,$title,1,'C',0);

$pdf->SetXY(120,100);
$title=iconv('UTF-8','windows-874',"จำนวน
Quantity");
$pdf->MultiCell(20,6,$title,1,'C',0);

$pdf->SetXY(140,100);
$title=iconv('UTF-8','windows-874',"ราคาต่อหน่วย
Unit Price");
$pdf->MultiCell(30,6,$title,1,'C',0);

$pdf->SetXY(170,100);
$title=iconv('UTF-8','windows-874',"รวมเงิน (บาท)
Amount");
$pdf->MultiCell(30,6,$title,1,'C',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(10,112);
$title=iconv('UTF-8','windows-874',"1
















");
$pdf->MultiCell(20,6,$title,1,'C',0);

$pdf->SetXY(30,112);
$title=iconv('UTF-8','windows-874',"ติดตั้งอุปกรณ์ก๊าซ $model ครบชุด
รับประกัน 6 เดือน หรือ 100,000 กิโล แล้วแต่เงื่อนไขใดถึงก่อน
ในรถยนต์ TOYOTA
หมายเลขถัง $carnum
หมายเลขเครื่อง $marnum












");
$pdf->MultiCell(90,6,$title,1,'L',0);

$pdf->SetXY(120,112);
$title=iconv('UTF-8','windows-874',"1
















");
$pdf->MultiCell(20,6,$title,1,'C',0);

$pdf->SetXY(140,112);
$title=iconv('UTF-8','windows-874',number_format($cost,2)."
















");
$pdf->MultiCell(30,6,$title,1,'R',0);

$pdf->SetXY(170,112);
$title=iconv('UTF-8','windows-874',number_format($cost,2)."
















");
$pdf->MultiCell(30,6,$title,1,'R',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',11);
$pdf->SetXY(9,215);
$title=iconv('UTF-8','windows-874',"รวมจำนวนเงิน(ตัวอักษร)");
$pdf->MultiCell(30,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,220);
$title=iconv('UTF-8','windows-874',"$res_thai_cost");
$pdf->MultiCell(110,10,$title,1,'C',0);

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(140,216);
$title=iconv('UTF-8','windows-874',"ยอดรวมก่อนภาษี");
$pdf->MultiCell(30,4,$title,0,'R',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(170,216);
$title=iconv('UTF-8','windows-874',number_format($cost-$vatofcost,2));
$pdf->MultiCell(30,4,$title,0,'R',0);

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(140,224);
$title=iconv('UTF-8','windows-874',"ภาษีมูลค่าเพิ่ม 7 %");
$pdf->MultiCell(30,4,$title,0,'R',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(170,224);
$title=iconv('UTF-8','windows-874',number_format($vatofcost,2));
$pdf->MultiCell(30,4,$title,0,'R',0);

$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(140,232);
$title=iconv('UTF-8','windows-874',"ราคารวมภาษีสุทธิ");
$pdf->MultiCell(30,4,$title,0,'R',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(170,232);
$title=iconv('UTF-8','windows-874',number_format($cost,2));
$pdf->MultiCell(30,4,$title,0,'R',0);
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(10,256);
$title=iconv('UTF-8','windows-874',"____________________________________");
$pdf->MultiCell(100,6,$title,0,'C',0);

$pdf->SetXY(10,263);
$title=iconv('UTF-8','windows-874',"บริษัท เอวี.ลีสซิ่ง จำกัด");
$pdf->MultiCell(100,6,$title,0,'C',0);

$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(10,270);
$title=iconv('UTF-8','windows-874',"ผู้สั่งซื้อ");
$pdf->MultiCell(100,6,$title,0,'C',0);

//----------AddPage------------------------------AddPage----------------------------------------AddPage----------------------------------------------AddPage---------------------------------------AddPage------------------------------------AddPage------------------------//


$pdf->AddPage();

$pdf->Image('avlogo.jpg',10,10,22,13);

$pdf->SetFont('AngsanaNew','B',20);
$pdf->SetXY(35,12);
$title=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(50,4,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(35,17);
$title=iconv('UTF-8','windows-874',"AV. LEASING CO., LTD.");
$pdf->MultiCell(50,4,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(10,23);
$title=iconv('UTF-8','windows-874',"สาขาจรัญสนิทวงศ์   : 667 ถนนจรัญสนิทวงศ์ แขวงอรุณอมรินทร์ เขตบางกอกน้อย กรุงเทพฯ 10700 โทร. 02 882 5533 โทรสาร 02 882 5530");
$pdf->MultiCell(180,4,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',18);
$pdf->SetXY(10,40);
$title=iconv('UTF-8','windows-874',"ใบสั่งติดตั้ง");
$pdf->MultiCell(190,4,$title,0,'C',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,57);
$title=iconv('UTF-8','windows-874',"ผู้ติดตั้ง
Saller's Name");
$pdf->MultiCell(25,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(35,57);
$title=iconv('UTF-8','windows-874',$name);
$pdf->MultiCell(70,6,$title,0,'L',0);
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(115,57);
$title=iconv('UTF-8','windows-874',"เลขที่
No.");
$pdf->MultiCell(25,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(140,57);
$title=iconv('UTF-8','windows-874',$id);
$pdf->MultiCell(60,6,$title,0,'C',0);
//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,69);
$title=iconv('UTF-8','windows-874',"ที่อยู่
Address");
$pdf->MultiCell(25,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(35,69);
$title=iconv('UTF-8','windows-874',$address);
$pdf->MultiCell(70,6,$title,0,'L',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(115,69);
$title=iconv('UTF-8','windows-874',"วันที่สั่ง
Order Date");
$pdf->MultiCell(25,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(140,69);
$title=iconv('UTF-8','windows-874',$nowdate);
$pdf->MultiCell(60,6,$title,0,'C',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(10,81);
$title=iconv('UTF-8','windows-874',"โทรศัพท์
Telephone");
$pdf->MultiCell(25,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(35,81);
$title=iconv('UTF-8','windows-874',$phone);
$pdf->MultiCell(70,6,$title,0,'L',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','',16);
$pdf->SetXY(115,81);
$title=iconv('UTF-8','windows-874',"วันที่ติดตั้ง
Set Date");
$pdf->MultiCell(25,6,$title,0,'L',0);

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(140,81);
$title=iconv('UTF-8','windows-874',$date_install);
$pdf->MultiCell(60,6,$title,0,'C',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//
$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(10,100);
$title=iconv('UTF-8','windows-874',"เลขที่
No.");
$pdf->MultiCell(20,6,$title,1,'C',0);

$pdf->SetXY(30,100);
$title=iconv('UTF-8','windows-874',"รายการ
Description");
$pdf->MultiCell(150,6,$title,1,'C',0);

$pdf->SetXY(180,100);
$title=iconv('UTF-8','windows-874',"จำนวน
Quantity");
$pdf->MultiCell(20,6,$title,1,'C',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(10,112);
$title=iconv('UTF-8','windows-874',"1















");
$pdf->MultiCell(20,6,$title,1,'C',0);

$pdf->SetXY(30,112);
$title=iconv('UTF-8','windows-874',"ติดตั้งอุปกรณ์ก๊าซ $model ครบชุด
รับประกัน 6 เดือน หรือ 100,000 กิโล แล้วแต่เงื่อนไขใดถึงก่อน
ในรถยนต์ TOYOTA
หมายเลขถัง $carnum
หมายเลขเครื่อง $marnum
เลขทะเบียน $car_regis










");
$pdf->MultiCell(150,6,$title,1,'L',0);

$pdf->SetXY(180,112);
$title=iconv('UTF-8','windows-874',"1















");
$pdf->MultiCell(20,6,$title,1,'C',0);

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------//

$pdf->SetXY(10,208);
$title=iconv('UTF-8','windows-874',"หมายเหตุ : $memo");
$pdf->MultiCell(190,10,$title,1,'L',0);

$pdf->SetXY(10,234);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(57,40,$title,1,'C',0);

$pdf->SetXY(77,234);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(56,40,$title,1,'C',0);

$pdf->SetXY(143,234);
$title=iconv('UTF-8','windows-874',"");
$pdf->MultiCell(57,40,$title,1,'C',0);

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,244);
$title=iconv('UTF-8','windows-874',"____________________________
นายสุวรรณ   อัศวโรจน์พาณิช
ลงชื่อผู้สั่งติดตั้ง
บริษัท เอวี. ลีสซิ่ง จำกัด");
$pdf->MultiCell(57,6,$title,0,'C',0);

$pdf->SetXY(77,244);
$title=iconv('UTF-8','windows-874',"____________________________
บจก.เอวี.ลิสซิ่ง
รับการติดตั้ง (ผู้ขับรถ)");
$pdf->MultiCell(57,6,$title,0,'C',0);

$pdf->SetXY(143,244);
$title=iconv('UTF-8','windows-874',"____________________________
ลงชื่อผู้จำหน่าย
บริษัทสแกนอินเตอร์ จำกัด");
$pdf->MultiCell(57,6,$title,0,'C',0);

$pdf->Output();
?>