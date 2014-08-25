<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');
include("../../pChart/class/pDraw.class.php");
include("../../pChart/class/pBarcode128.class.php");
include("../../pChart/class/pImage.class.php");
 
$checktype = $_POST['check']; 
$receiptID=$_POST["receiptID"];
$contractID=$_POST["contractID"];
$receiveDate=$_POST["receiveDate"];
$name3=$_POST["name3"];
$address=$_POST["address"];
$no1=$_POST["no1"];
$no2=$_POST["no2"];
$no3=$_POST["no3"];
$detail1=$_POST["detail1"];
$detail2=$_POST["d2"];
$detail3=$_POST["d3"];
$receiveAmount1=$_POST["receiveAmount1"];
$receiveAmount2=$_POST["receiveAmount2"];
$receiveAmount3=$_POST["receiveAmount3"];
$tax1=$_POST["tax1"];
$tax2=$_POST["tax2"];
$tax3=$_POST["tax3"];
$taxdel1=$_POST["taxdel1"];
$taxdel2=$_POST["taxdel2"];
$taxdel3=$_POST["taxdel3"];
$reftaxdel=$_POST["reftaxdel"];
$byChannel=$_POST["byChannel"];
$money=$_POST["money"];
$WHT=$_POST["WHT"];
$ChannelAmtWHT=$_POST["ChannelAmtWHT"];
$fullname=$_POST["fullname"];


/* Create the barcode 128 object */
$Barcode = new pBarcode128("../../pChart/");

/* String to be written on the barcode */
$String = "$receiptID";

/* Retrieve the barcode projected size */
$Settings = array("ShowLegend"=>TRUE,"DrawArea"=>TRUE);

$Size = $Barcode->getSize($String,$Settings);

/* Create the pChart object */
$myPicture = new pImage($Size["Width"],$Size["Height"]);

/* Set the font to use */
$myPicture->setFontProperties(array("FontName"=>"../../pChart/fonts/GeosansLight.ttf"));

/* Render the barcode */
$Barcode->draw($myPicture,$String,10,10,$Settings);
	
/* Render the picture (choose the best way) */
$myPicture->render("../barcode/$receiptID.png");
//จบการสร้าง barcode

$pdf=new ThaiPDF('P' ,'mm','a4');  

$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();

$pdf->SetFont('AngsanaNew','B',20);  
//$pdf->SetXY(15,);
$txtreceipt=iconv('UTF-8','windows-874',"(ต้นฉบับ)");
$pdf->Text(175,11.4,$txtreceipt); //ใบเสร็จรับเงินต้นฉบับ

$pdf->SetFont('AngsanaNew','',14);  
$receiptID=iconv('UTF-8','windows-874',$receiptID);
$pdf->Text(165,25.5,$receiptID); //ใบเสร็จรับเงินเลขที่

$pdf->SetFont('AngsanaNew','B',15);
$receiveDate=iconv('UTF-8','windows-874',$receiveDate);
$pdf->Text(155,32.2,$receiveDate); //วันที่ชำระ

$pdf->SetFont('AngsanaNew','',14);  
$name=iconv('UTF-8','windows-874',$name3);
$pdf->Text(33,35.2,$name); //ชื่อลูกค้า

$contractID=iconv('UTF-8','windows-874',$contractID);
$pdf->Text(102,35.2,$contractID); //เลขที่สัญญา

$pdf->SetXY(33,38);
$address2=iconv('UTF-8','windows-874',$address);
$pdf->MultiCell(100,5,$address2,0,'L',0); //ที่อยู่


$pdf->Image("../barcode/$receiptID.png",145,39,42.33,13.57);  //barcode
//##########################ค่าในตาราง
$pdf->SetXY(16.3,68);
$no=iconv('UTF-8','windows-874',$no1);
$pdf->MultiCell(15,6,$no,0,'C',0); //no1
$pdf->SetXY(16.3,80);
$no=iconv('UTF-8','windows-874',$no2);
$pdf->MultiCell(15,6,$no,0,'C',0); //no2
$pdf->SetXY(16.3,86);
$no=iconv('UTF-8','windows-874',$no3);
$pdf->MultiCell(15,6,$no,0,'C',0); //no3

$pdf->SetXY(31,68);
$detail=iconv('UTF-8','windows-874',$detail1);
$pdf->MultiCell(60,6,$detail,0,'L',0); //รายละเอียดการรับชำระ1
$pdf->SetXY(31,80);
$detail=iconv('UTF-8','windows-874',$detail2);
$pdf->MultiCell(60,6,$detail,0,'L',0); //รายละเอียดการรับชำระ2
$pdf->SetXY(31,86);
$detail=iconv('UTF-8','windows-874',$detail3);
$pdf->MultiCell(60,6,$detail,0,'L',0); //รายละเอียดการรับชำระ3

$pdf->SetXY(95,68);
$amount=iconv('UTF-8','windows-874',number_format($receiveAmount1,2));
$pdf->MultiCell(24,6,$amount,0,'R',0); //จำนวนเงิน1
if($receiveAmount2!=0){
	$pdf->SetXY(95,80);
	$amount=iconv('UTF-8','windows-874',number_format($receiveAmount2,2));
	$pdf->MultiCell(24,6,$amount,0,'R',0); //จำนวนเงิน2
}
if($receiveAmount3!=0){
	$pdf->SetXY(95,86);
	$amount=iconv('UTF-8','windows-874',number_format($receiveAmount3,2));
	$pdf->MultiCell(24,6,$amount,0,'R',0); //จำนวนเงิน3
}

$pdf->SetXY(119,68);
$vat=iconv('UTF-8','windows-874',number_format($tax1,2));
$pdf->MultiCell(23,6,$vat,0,'R',0); //ภาษีมูลค่าเพิ่ม1

if($tax2!=0){
	$pdf->SetXY(119,80);
	$vat=iconv('UTF-8','windows-874',number_format($tax2,2));
	$pdf->MultiCell(23,6,$vat,0,'R',0); //ภาษีมูลค่าเพิ่ม2
}
if($tax3!=0){
	$pdf->SetXY(119,86);
	$vat=iconv('UTF-8','windows-874',number_format($tax3,2));
	$pdf->MultiCell(23,6,$vat,0,'R',0); //ภาษีมูลค่าเพิ่ม3
}
$pdf->SetXY(142,68);
$vat2=iconv('UTF-8','windows-874',number_format($taxdel1,2));
$pdf->MultiCell(28,6,$vat2,0,'R',0); //ภาษีหัก ณ ที่จ่าย
if($taxdel2!=0){
	$pdf->SetXY(142,80);
	$vat2=iconv('UTF-8','windows-874',number_format($taxdel2,2));
	$pdf->MultiCell(28,6,$vat2,0,'R',0); //ภาษีหัก ณ ที่จ่าย
}
if($taxdel3!=0){
	$pdf->SetXY(142,86);
	$vat2=iconv('UTF-8','windows-874',number_format($taxdel3,2));
	$pdf->MultiCell(28,6,$vat2,0,'R',0); //ภาษีหัก ณ ที่จ่าย
}
$sum1=$receiveAmount1+$tax1;
$pdf->SetXY(170,68);
$total=iconv('UTF-8','windows-874',number_format($sum1,2));
$pdf->MultiCell(25,6,$total,0,'R',0); //รวม1

$sum2=$receiveAmount2+$tax2;
if($sum2!=0){
	$pdf->SetXY(170,80);
	$total=iconv('UTF-8','windows-874',number_format($sum2,2));
	$pdf->MultiCell(25,6,$total,0,'R',0); //รวม2
}
$sum3=$receiveAmount3+$tax3;
if($sum3!=0){
	$pdf->SetXY(170,86);
	$total=iconv('UTF-8','windows-874',number_format($sum3,2));
	$pdf->MultiCell(25,6,$total,0,'R',0); //รวม3
}
//##########################รวมด้านล่าง
$sumreceive=$receiveAmount1+$receiveAmount2+$receiveAmount3;
$pdf->SetXY(95,110);
$amount=iconv('UTF-8','windows-874',number_format($sumreceive,2));
$pdf->MultiCell(24,4,$amount,0,'R',0); //จำนวนเงิน


$sumtax=$tax1+$tax2+$tax3;
$pdf->SetXY(119,110);
$vat=iconv('UTF-8','windows-874',number_format($sumtax,2));
$pdf->MultiCell(23,3,$vat,0,'R',0); //รวมภาษีมูลค่าเพิ่ม


$sumtaxdel=$taxdel1+$taxdel2+$taxdel3;
$pdf->SetXY(142,110);
$vat2=iconv('UTF-8','windows-874',number_format($sumtaxdel,2));
$pdf->MultiCell(28,3,$vat2,0,'R',0); //รวมภาษีหัก ณ ที่จ่าย

$sumall=$sum1+$sum2+$sum3;
$pdf->SetXY(170,110);
$total=iconv('UTF-8','windows-874',number_format($sumall,2));
$pdf->MultiCell(25,3,$total,0,'R',0); //รวมทั้งหมด


//##########################อ้างอิงใบภาษีหัก ณ ที่จ่ายเลขที่
$numvat=iconv('UTF-8','windows-874',$reftaxdel);
$pdf->Text(70,121.5,$numvat); 

//##########################วันที่ออกใบเสร็จรับเงิน
$receiveDate=iconv('UTF-8','windows-874',$receiveDate);
$pdf->Text(55,127.5,$receiveDate);

//##########################ยอดค้างชำระคงเหลือที่จะครบกำหนด วันที่
if($nextDueDate==""){
	$nextDueDate="-";
}

$date=iconv('UTF-8','windows-874',$nextDueDate);
$pdf->Text(82,139.2,$date);

//##########################จำนวนเงิน
$money1=iconv('UTF-8','windows-874',number_format($nextDueAmt,2));
$pdf->Text(40,145,$money1);

//##########################รับชำระเป็น
if($byChannel=="1"){
	//ติ๊กถูกเงินสด
	$pdf->SetFont('AngsanaNew','B',14); 
	$check=iconv('UTF-8','windows-874',"X");
	$pdf->Text(116.5,127.8,$check);
	
	//จำนวนเงิน
	$pdf->SetFont('AngsanaNew','',14); 
	$money2=iconv('UTF-8','windows-874',number_format($money,2)." บาท");
	$pdf->Text(135,127.8,$money2);
}else if($byChannel=="2" || $byChannel=="4"){
	//ติ๊กถูกเงินโอนผ่านธนาคาร
	$pdf->SetFont('AngsanaNew','B',14); 
	$check=iconv('UTF-8','windows-874',"X");
	$pdf->Text(116.5,145.4,$check);
	
	//จำนวนเงิน
	$pdf->SetFont('AngsanaNew','',14); 
	$money2=iconv('UTF-8','windows-874',"$txtchan ".number_format($receiveAmount,2)." บาท");
	$pdf->Text(153,145.4,$money2);
}else if($byChannel=="3"){
	//ติ๊กถูกเงินโอนผ่านธนาคาร
	$pdf->SetFont('AngsanaNew','B',14); 
	$check=iconv('UTF-8','windows-874',"X");
	$pdf->Text(116.5,145.4,$check);
	
	//จำนวนเงิน
	$pdf->SetFont('AngsanaNew','',14); 
	$money2=iconv('UTF-8','windows-874',"$txtchan ".number_format($receiveAmount,2)." บาท");
	$pdf->Text(153,145.4,$money2);
}else if($byChannel=="5"){
	//ติ๊กเช็ค
	$pdf->SetFont('AngsanaNew','B',14); 
	$check=iconv('UTF-8','windows-874',"X");
	$pdf->Text(116.5,135,$check);
	
	//จำนวนเงิน
	$pdf->SetFont('AngsanaNew','',14); 
	$money2=iconv('UTF-8','windows-874',number_format($money,2)." บาท");
	$pdf->Text(135,135,$money2);
}

if($WHT == 1)
{
	//ติ๊กถูกหักภาษี ณใ ที่จ่าย
	$pdf->SetFont('AngsanaNew','B',14); 
	$check=iconv('UTF-8','windows-874',"X");
	$pdf->Text(116.5,157.50,$check);
	
	//จำนวนเงิน
	$pdf->SetFont('AngsanaNew','',14); 
	$money2=iconv('UTF-8','windows-874',number_format($ChannelAmtWHT,2)." บาท");
	$pdf->Text(153,157.50,$money2);
}
//##################ผู้กู้ร่วม############################
if($nameco != ""){
	$pdf->SetXY(20,160);
	$co=iconv('UTF-8','windows-874',"ผู้กู้ร่วม");
	$pdf->MultiCell(15,3,$co,0,'L',0);
	
	$pdf->SetXY(30,160);
	$nameco=iconv('UTF-8','windows-874',$nameco);
	$pdf->MultiCell(150,3,$nameco,0,'L',0); //รายชื่อผู้กู้ร่วม
}

$pdf->SetXY(136,185.8);
$co=iconv('UTF-8','windows-874',$fullname);
$pdf->MultiCell(50,4,$co,0,'C',0); //ผู้รับเงิน

//###########กรุณาส่ง
$pdf->SetXY(50,241);
$name=iconv('UTF-8','windows-874',$name3);
$pdf->MultiCell(150,3,$name,0,'L',0); //ชื่อลูกค้า

$pdf->SetXY(50,246);
$address2=iconv('UTF-8','windows-874',$address);
$pdf->MultiCell(60,5,$address2,0,'L',0); //ที่อยู่
  
/*
if (!file_exists($_SESSION["session_path_save_pdf"].$rec_id.".pdf")) { //check file exists
$pdf->Output($_SESSION["session_path_save_pdf"].$rec_id.".pdf", "F"); // save pdf
}
*/
$pdf->Output(); //open pdf






$sqll = "SELECT * FROM \"FA_pirnt_temp\" where \"reno\" = '$receiptID'";
$queryy = pg_query($sqll);
$rowss = pg_num_rows($queryy);


			$reno = $_POST['receiptID'];			
			$conno = $_POST['contractID']; 
			$date = $_POST['receiveDate']; 
			$cusname = $_POST['name3'];
			$address = $_POST['address']; 
			$NO1 = $_POST['no1'];
			$NO2 = $_POST['no2']; 
			$NO3 = $_POST['no3']; 
			$detail1 = $_POST['detail1']; 
            $detail2 = $_POST['d2'];
			$detail3 = $_POST['d3']; 
			$money1 = $_POST['receiveAmount1']; 
			$money2 = $_POST['receiveAmount2']; 
			$money3 = $_POST['receiveAmount3']; 
			$vat1 = $_POST['tax1']; 
			$vat2 = $_POST['tax2']; 
			$vat3 = $_POST['tax3']; 
			$vat_pay1 = $_POST['taxdel1']; 
            $vat_pay2 = $_POST['taxdel2']; 
			$vat_pay3 = $_POST['taxdel3']; 
			$refer = $_POST['reftaxdel']; 
			$typepay = $_POST['byChannel']; 
			$type_pay_many = $_POST['money']; 
			$vat_pay_check = $_POST['WHT']; 
            $vat_pay_check_many = $_POST['ChannelAmtWHT']; 
			$user = $_POST['fullname']; 


           
			if($reno  == ""){
			$reno = "null";
			}else{ 
			$reno  = "'".$reno."'";}	
			if($conno  == ""){
			$conno = "null";
			}else{ 
			$conno  = "'".$conno."'";}
			if($date  == ""){
			$date = "null";
			}else{ 
			$date  = "'".$date."'";}
			if($cusname  == ""){
			$cusname = "null";
			}else{ 
			$cusname  = "'".$cusname."'";}
			if($address  == ""){
			$address = "null";
			}else{ 
			$address  = "'".$address."'";}
			if($NO1  == ""){
			$NO1 = "null";
			}else{ 
			$NO1  = "'".$NO1."'";}
			if($NO2  == ""){
			$NO2 = "null";
			}else{
			$NO2  = "'".$NO2."'";}
			if($NO3  == ""){
			$NO3 = "null";
			}else{ 
			$NO3  = "'".$NO3."'";}
			if($detail1  == ""){
			$detail1 = "null";
			}else{
			$detail1  = "'".$detail1."'";}
            if($detail2  == ""){
			$detail2 = "null";
			}else{
			$detail2  = "'".$detail2."'";}
			if($detail3  == ""){
			$detail3 = "null";
			}else{ 
			$detail3  = "'".$detail3."'";}
			if($money1  == ""){
			$money1 = "null";
			}else{ 
			$money1  = "'".$money1."'";}
			if($money2  == ""){
			$money2 = "null";
			}else{ 
			$money2  = "'".$money2."'";}
			if($money3  == ""){
			$money3 = "null";
			}else{ 
			$money3  = "'".$money3."'";}
			if($vat1  == ""){
			$vat1 = "null";
			}else{ 
			$vat1  = "'".$vat1."'";}
			if($vat2  == ""){
			$vat2 = "null";
			}else{ 
			$vat2  = "'".$vat2."'";}
			if($vat3  == ""){
			$vat3 = "null";
			}else{ 
			$vat3  = "'".$vat3."'";}
			if($vat_pay1  == ""){
			$vat_pay1 = "null";
			}else{ 
			$vat_pay1  = "'".$vat_pay1."'";}
            if($vat_pay2  == ""){
			$vat_pay2 = "null";
			}else{ 
			$vat_pay2  = "'".$vat_pay2."'";}
			if($vat_pay3  == ""){
			$vat_pay3 = "null";
			}else{ 
			$vat_pay3  = "'".$vat_pay3."'";}
			if($refer  == ""){
			$refer = "null";
			}else{ 
			$refer  = "'".$refer."'";}
			if($typepay  == ""){
			$typepay = "null";
			}else{ 
			$typepay  = "'".$typepay."'";}
			if($type_pay_many  == ""){
			$type_pay_many = "null";
			}else{ 
			$type_pay_many  = "'".$type_pay_many."'";}
			if($vat_pay_check  == ""){
			$vat_pay_check = "null";
			}else{ 
			$vat_pay_check  = "'".$vat_pay_check."'";}
            if($vat_pay_check_many  == ""){
			$vat_pay_check_many = "null";
			}else{ 
			$vat_pay_check_many  = "'".$vat_pay_check_many."'";}
			if($user  == ""){
			$user = "null";
			}else{ 
			$user  = "'".$user."'";}
			
$status = 0;
pg_query("BEGIN");
if($rowss == 0){
	
		$sql = "INSERT INTO \"FA_pirnt_temp\"(
					reno, conno, date, cusname, address, \"NO1\", \"NO2\", \"NO3\", detail1, 
					detail2, detail3, money1, money2, money3, vat1, vat2, vat3, vat_pay1, 
					vat_pay2, vat_pay3, refer, typepay, type_pay_many, vat_pay_check, 
					vat_pay_check_many, \"user\")
			VALUES ($reno , $conno , $date , $cusname , $address , $NO1 , $NO2 , $NO3 , $detail1 , 
					$detail2 , $detail3 , $money1 , $money2 , $money3 , $vat1 , $vat2 , $vat3 , $vat_pay1 , 
					$vat_pay2 , $vat_pay3 , $refer , $typepay , $type_pay_many , $vat_pay_check , 
					$vat_pay_check_many , $user)";
					
$query = pg_query($sql);			


		if($query){}
		else{
			$status++;
		}

				if($stauts == 0){
					pg_query("COMMIT");

				}else{
					pg_query("ROLLBACK");

				}
}else if($rowss != 0){

			$sql = "UPDATE \"FA_pirnt_temp\"
					   SET  conno=$conno, date= $date, cusname= $cusname, address= $address, \"NO1\"= $NO1, \"NO2\"= $NO2, 
						   \"NO3\"= $NO3, detail1= $detail1, detail2= $detail2, detail3= $detail3, money2= $money2, money3= $money3, 
						   vat1= $vat1, vat2= $vat2, vat3= $vat3, vat_pay1= $vat_pay1, vat_pay2= $vat_pay2, vat_pay3= $vat_pay3, refer= $refer, 
						   typepay= $typepay, type_pay_many= $type_pay_many, vat_pay_check= $vat_pay_check, vat_pay_check_many= $vat_pay_check_many, 
						   \"user\"= $user, money1= $money1
					 WHERE reno='$receiptID'";



$query = pg_query($sql);			


		if($query){}
		else{
			$status++;
		}

				if($stauts == 0){
					pg_query("COMMIT");

				}else{
					pg_query("ROLLBACK");

				}


}
?>
