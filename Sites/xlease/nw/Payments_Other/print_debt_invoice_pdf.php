<?php
session_start();
include("../../config/config.php");
require('../../thaipdfclass.php');
include("../../pChart/class/pDraw.class.php");
include("../../pChart/class/pBarcode128.class.php");
include("../../pChart/class/pImage.class.php");


$invoiceID=pg_escape_string($_GET["debtInvID"]); //ตัวแปรมาจากเมนู "(THCAP) ส่งใบแจ้งหนี้เงินกู้-ค่าเช่า "
$date = nowDateTime();
$doer = $_SESSION['av_iduser'];

pg_query("BEGIN WORK");
$status = 0;

$array_invoiceID=explode(",",$invoiceID);
if (count($array_invoiceID)==0 ){
	$array_invoiceID[0]=$invoiceID;
}

if($invoiceID==""){
	$debtinvoiceID=pg_escape_string($_GET["invoiceID"]); //ตัวแปรกรณีมาจากเมนู "พิมพ์ใบแจ้งหนี้"
	$chk=1;
	$debtinvoiceID=explode(",",$debtinvoiceID);
	$cinvoiceID=sizeof($debtinvoiceID);
}
else{
//$invoiceID=unserialize($invoiceID); //แปลง array กลับเป็นสตริงเมื่อส่งค่าแบบ GET มา
$invoiceID=explode(",",$invoiceID);
$cinvoiceID=sizeof($invoiceID);
}

/* Create the barcode 128 object */
$Barcode = new pBarcode128("../../pChart/");
$pdf=new ThaiPDF('P' ,'mm','a4');  

$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();

for($i=0;$i<$cinvoiceID;$i++){	
	
	//ค้นหาข้อมูล
	if($chk==1){
		$qr = "insert into \"thcap_print_debt_invoice_log\"(\"thcap_debt_invoice_id\",\"doer\",\"do_time\") values('$debtinvoiceID[$i]','$doer','$date')";
		if($ins=pg_query($qr)){
		}else{
			$status++;
		}

		$qry_conid=pg_query("select \"debtInvID\",\"debtDueDate\",\"debtDueDate\",\"typePayRefDate\",\"contractID\",
		\"thcap_fullname\",\"thcap_address\",\"addrSend\",\"debtRemark\",\"fullname\",
		\"invoiceRef1\",\"invoiceRef2\" from \"Vthcap_debt_invoice\" where \"debtInvID\" = '$debtinvoiceID[$i]'");
	}else{
		$qr = "insert into \"thcap_print_debt_invoice_log\"(\"thcap_debt_invoice_id\",\"doer\",\"do_time\") values('$invoiceID[$i]','$doer','$date')";
		if($ins=pg_query($qr)){
		}else{
			$status++;
		}
		
		$qry_conid=pg_query("select \"debtInvID\",\"debtDueDate\",\"debtDueDate\",\"typePayRefDate\",\"contractID\",
		\"thcap_fullname\",\"thcap_address\",\"addrSend\",\"debtRemark\",\"fullname\",
		\"invoiceRef1\",\"invoiceRef2\" from \"Vthcap_debt_invoice\" where \"debtInvID\" = '$invoiceID[$i]'");
	}
	if($result=pg_fetch_array($qry_conid))
	{	
		$debtInvID=trim($result["debtInvID"]); // กำหนดชำระเงิน
		$debtDueDate=trim($result["debtDueDate"]); // กำหนดชำระเงิน
		$debtDueDate1=trim($result["debtDueDate"]); // กำหนดชำระเงิน
		$typePayRefDate=trim($result["typePayRefDate"]); // วันที่ตั้งหนี้
		$contractID=trim($result["contractID"]); // เลขที่สัญญา
		$thcap_fullname=trim($result["thcap_fullname"]); // ชื่อลูกค้า
		$thcap_address=trim($result["thcap_address"]); // ที่อยู่บนใบเสร็จ		
		$addrSend=trim($result["addrSend"]); // ที่อยู่ส่งใบแจ้งหนี้		
		$debtRemark=trim($result["debtRemark"]); // หมายเหตุ
		$fullname=trim($result["fullname"]); // ผู้ออกใบแจ้งหนี้
		$REF1=trim($result["invoiceRef1"]); // ref1
		$REF2=trim($result["invoiceRef2"]); // ref2
		//$intFineAmt=trim($result["intFineAmt"]); // เบี้ยปรับที่แสดงบนใบแจ้งหนี้
		
		//หาว่ารหัสเบี้ยปรับของเลขที่สัญญานี้อะไร
		$qryfine=pg_query("select account.\"thcap_getIntFineType\"('$contractID')");
		list($typefine)=pg_fetch_array($qryfine);
		
		// หาจำนวนวันที่ผ่อนผันเรื่องค่าติดตามทวงถาม และการปรับอัตราดอกเบี้ย นับจาก Due
		$qry_conNumExceptDays = pg_query("select thcap_get_mg_contract_current('$contractID','conNumExceptDays','$typePayRefDate')");
		$conNumExceptDays = pg_fetch_result($qry_conNumExceptDays,0);
		
		// หาอัตราดอกเบี้ยที่จะคิด หลังเกินกำหนด
		$qry_MaxRate = pg_query("select thcap_get_mg_contract_current('$contractID','conFineRate','$typePayRefDate')");
		$MaxRate = pg_fetch_result($qry_MaxRate,0); // ได้อัตราต่อปี
		$MaxRate = number_format($MaxRate/12,2) ;  // คำนวณเป็นอัตราต่อเดือน

		/* String to be written on the barcode */
		$String = "$debtInvID";

		/* Retrieve the barcode projected size */
		$Settings = array("ShowLegend"=>TRUE,"DrawArea"=>TRUE);

		$Size = $Barcode->getSize($String,$Settings);

		/* Create the pChart object */
		$myPicture = new pImage($Size["Width"],$Size["Height"]);

		/* Set the font to use */
		$myPicture->setFontProperties(array("FontName"=>"../../pChart/fonts/tahoma.ttf"));

		/* Render the barcode */
		$Barcode->draw($myPicture,$String,10,10,$Settings);
			
		/* Render the picture (choose the best way) */
		$myPicture->render("../barcode/$debtInvID.png");
		//จบการสร้าง barcode
		
		IF($REF1 != "" && $REF2 != ""){
			//สร้าง Ref 1 เป็น barcode
			$barref1 = "$REF1";
			$mybarref1 = new pImage($Size["Width"],$Size["Height"]);
			$mybarref1->setFontProperties(array("FontName"=>"../../pChart/fonts/tahoma.ttf"));
			$Barcode->draw($mybarref1,$barref1,10,10,$Settings);
			$mybarref1->render("../barcode/ref1/$REF1.png");
			
			//สร้าง Ref 2 เป็น barcode
			$barref2 = "$REF2";
			$mybarref2 = new pImage($Size["Width"],$Size["Height"]);
			$mybarref2->setFontProperties(array("FontName"=>"../../pChart/fonts/tahoma.ttf"));
			$Barcode->draw($mybarref2,$barref2,10,10,$Settings);
			$mybarref2->render("../barcode/ref2/$REF2.png");
			
			

			
			
		}
		

		$pdf->AddPage();

			
		$txth="(ต้นฉบับ)";

		$pdf->SetFont('AngsanaNew','B',20);  

		$txtreceipt=iconv('UTF-8','windows-874',$txth);
		$pdf->Text(177,14.4,$txtreceipt); //ใบแจ้งหนี้ (ต้นฉบับ)

		$pdf->SetFont('AngsanaNew','',14);  
		$debtInvID=iconv('UTF-8','windows-874',$debtInvID);
		$pdf->Text(165,28.5,$debtInvID); //ใบแจ้งหนี้เลขที่

		$pdf->SetFont('AngsanaNew','B',15);
		$typePayRefDate=iconv('UTF-8','windows-874',$typePayRefDate);
		$pdf->Text(165,36.5,$typePayRefDate); //วันที่ตั้งหนี้

		$pdf->SetFont('AngsanaNew','',12);  
		$name=iconv('UTF-8','windows-874',$thcap_fullname);
		$pdf->Text(38,45.3,$name); //ชื่อลูกค้า

		$contractID=iconv('UTF-8','windows-874',$contractID);
		$pdf->Text(138,45.3,$contractID); //เลขที่สัญญา

		$pdf->SetFont('AngsanaNew','',14); 
		$pdf->SetXY(33,49);
		$address2=iconv('UTF-8','windows-874',$thcap_address);
		$pdf->MultiCell(100,5,$address2,0,'L',0); //ที่อยู่
		
		$pdf->Image("../barcode/$debtInvID.png",145,48,42.33,13.57);  //barcode
		
		
		/*
		IF($REF1 != "" && $REF2 != ""){
			$pdf->SetXY(20,175);//$pdf->SetXY(20,187);
			$address2=iconv('UTF-8','windows-874',"REF1:");
			$pdf->MultiCell(100,5,$address2,0,'L',0); //ที่อยู่
			
			$pdf->SetXY(20,187);//$pdf->SetXY(72,187);
			$address2=iconv('UTF-8','windows-874',"REF2:");
			$pdf->MultiCell(100,5,$address2,0,'L',0); //ที่อยู่
		
			$pdf->Image("../barcode/ref1/$REF1.png",30,173,42.33,13.57);  //ref1
			$pdf->Image("../barcode/ref2/$REF2.png",30,185,42.33,13.57);  //ref2
		}	
		
		*/
		
		
		
	}
	
	
	
	
	
	
	$pdf->SetFont('AngsanaNew','',12); 
		//##########################ค่าในตาราง
	$row = 76; // ตำแหน่งแนวนอน	
	$qry_debt=pg_query("select \"namedetail\",\"debtNet\",\"debtVat\",\"debtWht\",\"typePayID\" from \"Vthcap_debt_invoice\" where \"debtInvID\" = '$debtInvID'");
	$p=0;
	$debtVat=0;
	$debtVat=0;
	$debtAmt=0;
	while($res_debt=pg_fetch_array($qry_debt)){
		$p+=1;
		$namedetail=trim($res_debt["namedetail"]); // รายละเอียดการรับชำระ
		$debtNet=trim($res_debt["debtNet"]); // จำนวนเงินไม่รวม VAT
		$debtVat=trim($res_debt["debtVat"]); // จำนวน VAT
		$debtWht=trim($res_debt["debtWht"]); // ภาษีหัก ณ ที่จ่าย
		$typePayID=trim($res_debt["typePayID"]); // ประเภทการจ่าย
		$debtAmt=$debtNet+$debtVat;	

		if($typefine==$typePayID){
			$p=0;
		}
		
		$pdf->SetXY(16,$row);
		$no=iconv('UTF-8','windows-874',$p);
		$pdf->MultiCell(15,3,$no,0,'C',0); //no

		$pdf->SetXY(31,$row);
		$detail=iconv('UTF-8','windows-874',$namedetail);
		$pdf->MultiCell(60,3,$detail,0,'L',0); //รายละเอียดการรับชำระ

		$pdf->SetXY(95,$row);
		$amount=iconv('UTF-8','windows-874',number_format($debtNet,2));
		$pdf->MultiCell(24,3,$amount,0,'R',0); //จำนวนเงิน

		$pdf->SetXY(122,$row);
		if($debtVat > 0){$vat=iconv('UTF-8','windows-874',number_format($debtVat,2));}
		else{$vat=iconv('UTF-8','windows-874','-');}
		$pdf->MultiCell(23,3,$vat,0,'C',0); //ภาษีมูลค่าเพิ่ม

		$pdf->SetXY(145,$row);
		if($debtWht > 0){$vat2=iconv('UTF-8','windows-874',number_format($debtWht,2));}
		else{$vat2=iconv('UTF-8','windows-874','-');}
		$pdf->MultiCell(28,3,$vat2,0,'C',0); //ภาษีหัก ณ ที่จ่าย

		$pdf->SetXY(170,$row);
		$total=iconv('UTF-8','windows-874',number_format($debtAmt,2));
		$pdf->MultiCell(25,3,$total,0,'R',0); //รวม
		
		$sum_netAmt += $debtNet; //รวมจำนวนเงิน
		$sum_vatAmt += $debtVat; //รวมภาษีมูลค่าเพิ่ม
		$sum_whtAmt += $debtWht; //รวมภาษีหัก ณ ที่จ่าย
		$sum_debtAmt += $debtAmt; //รวม

		unset($debtNet); 
		unset($debtVat); 
		unset($debtWht); 
		unset($debtAmt); 
		
		$row += 8;
	}
		//##########################รวมด้านล่าง
		$pdf->SetXY(95,116);
		$amount=iconv('UTF-8','windows-874',number_format($sum_netAmt+$intFineAmt,2));
		$pdf->MultiCell(24,3,$amount,0,'R',0); //จำนวนเงิน

		$pdf->SetXY(122,116);
		if($sum_vatAmt > 0){$vat=iconv('UTF-8','windows-874',number_format($sum_vatAmt,2));}
		else{$vat=iconv('UTF-8','windows-874','-');}
		$pdf->MultiCell(23,3,$vat,0,'C',0); //รวมภาษีมูลค่าเพิ่ม

		$pdf->SetXY(145,116);
		if($sum_whtAmt > 0){$vat2=iconv('UTF-8','windows-874',number_format($sum_whtAmt,2));}
		else{$vat2=iconv('UTF-8','windows-874','-');}
		$pdf->MultiCell(28,3,$vat2,0,'C',0); //รวมภาษีหัก ณ ที่จ่าย

		$pdf->SetXY(170,116);
		$total=iconv('UTF-8','windows-874',number_format($sum_debtAmt+$intFineAmt,2));
		$pdf->MultiCell(25,3,$total,0,'R',0); //รวมทั้งหมด

		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetTextColor(0); 

		$pdf->SetXY(45,125);
		$debtDueDate=iconv('UTF-8','windows-874',$debtDueDate);
		$pdf->MultiCell(80,4,$debtDueDate,0,'L',0); //กำหนดชำระภายในวันที่

		$pdf->SetFont('AngsanaNew','',10);
		$pdf->SetXY(38,139);
		$debtDueDate=iconv('UTF-8','windows-874',"$MaxRate %");
		$pdf->MultiCell(10,4,$debtDueDate,0,'R',0); //บริษัทจะคิดดอกเบี้ย....

		$pdf->SetXY(83,139);
		$debtDueDate=iconv('UTF-8','windows-874',$conNumExceptDays);
		$pdf->MultiCell(5,4,$debtDueDate,0,'R',0); //เมื่อครบกำหนดชำระแล้ว....วัน

		$pdf->SetFont('AngsanaNew','',14);
		//##########################หมายเหตุ
		$pdf->SetXY(20,154);
		$nowtext=iconv('UTF-8','windows-874',$debtRemark);
		$pdf->MultiCell(80,4,$nowtext,0,'L',0); //หมายเหตุ
			
		$pdf->SetXY(135,184);
		$co=iconv('UTF-8','windows-874',$fullname);
		$pdf->MultiCell(50,2.6,$co,0,'C',0); //ผู้รับเงิน

		//###########ชื่อ-ที่อยู่
		$pdf->SetXY(45,206);
		$name=iconv('UTF-8','windows-874',$thcap_fullname);
		$pdf->MultiCell(155,3,$name,0,'L',0); //ชื่อลูกค้า

		$pdf->SetXY(45,214);
		$address2=iconv('UTF-8','windows-874',$addrSend);
		$pdf->MultiCell(150,5,$address2,0,'L',0); //ที่อยู่

			
		//###########ในตาราง
		$pdf->SetXY(20,243);
		$debtDueDate=iconv('UTF-8','windows-874',$debtDueDate1);
		$pdf->MultiCell(80,4,$debtDueDate1,0,'L',0); //กำหนดชำระภายในวันที่

		
		$pdf->SetXY(37,243);
		$total=iconv('UTF-8','windows-874',number_format($sum_debtAmt+$intFineAmt,2));
		$pdf->MultiCell(25,3,$total,0,'R',0); //รวมทั้งหมด

		//###########barcode ด้านล่าง
		IF($REF1 != "" && $REF2 != ""){
			/* $pdf->SetXY(20,259);//$pdf->SetXY(20,187);
			$address2=iconv('UTF-8','windows-874',"REF1:");
			$pdf->MultiCell(100,5,$address2,0,'L',0); 

			$pdf->SetXY(20,271);//$pdf->SetXY(72,187);
			$address2=iconv('UTF-8','windows-874',"REF2:");
			$pdf->MultiCell(100,5,$address2,0,'L',0); 
*/   


		//########### พิมพ์ Ref1
		
		$pdf->SetFont('helvetica','',6);
		$pdf->SetXY(17,257);
		$txtRef1=iconv('UTF-8','windows-874',"REF1: ".$REF1);
		$pdf->MultiCell(100,0.5,$txtRef1,0,'L',0); //Ref1

		//########### พิมพ์ Ref2
		$pdf->SetXY(45,257);
		$txtRef2=iconv('UTF-8','windows-874',"REF2: ".$REF2);
		$pdf->MultiCell(100,0.5,$txtRef2,0,'L',0); //Ref1



// สั่งพิมพ์แบบ Offline

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
		$txtdata = "$companytaxid".$cr."$REF1".$cr."$REF2".$cr."0";
		
		$pdf->SetXY(19,175);  
		$pdf->write1DBarcode("|".$txtdata, 'C128', '', '', '100', '15', 1, $styleBarcode, 'N');    
		
		$pdf->SetXY(97,258);  
		$pdf->write1DBarcode("|".$txtdata, 'C128', '', '', '100', '15', 1, $styleBarcode, 'N');    

	
// จบการพิมพ์ Offline	
	


/*
//  สั่งพิมพ์ Barcode Online  จาก web
		
		$total_sumdebtAmt=number_format($sum_debtAmt+$intFineAmt,2);
		$str_total_sumdebtAmt =  str_replace(".","",$total_sumdebtAmt);
		$str_total_sumdebtAmt =  str_replace(",","",$str_total_sumdebtAmt);
		$companytaxid= "|010555313699600";
		//$txtdata = "$companytaxid %0D $REF1 %0D $REF2 %0D $str_total_sumdebtAmt";
		$txtdata = "$companytaxid%0D$REF1%0D$REF2%0D0";
		
		
		$string_txtdata = 'http://barcode.tec-it.com/barcode.ashx?code=Code128&modulewidth=fit&data='.$txtdata.'&dpi=600&imagetype=gif&rotation=0&color=&bgcolor=&fontcolor=&quiet=0&qunit=mm' ;
		
		$image = file_get_contents($string_txtdata);
		$txtoutput = "../barcode/$REF1.gif";
		//file_put_contents('../barcode/barcode.ashx.gif', $image); //Where to save the image on your server
		file_put_contents($txtoutput, $image); //Where to save the image on your server
		//$pdf->Image("../barcode/barcode.ashx.gif",100,243,100,15);  //ref1
		$pdf->Image($txtoutput,97,260,95,15);  //ref1
		//$pdf->Image($txtoutput,97,260,90,13);  //ref1
		//วาดรูป สี่เหลี่ยมเปล่า ทับ รหัส ที่มา กับ รูป web
		$pdf->Image("../barcode/blankbarcode.png",97,271,95,5);  //ref1
		
		//########### พิมพ์ ตัวอักษร บาร์โค้ด
		$pdf->SetFont('helvetica','',8);
		$txtdata3 = "$companytaxid  $REF1  $REF2  0";
		$pdf->SetXY(100,273);
		$txtRef3=iconv('UTF-8','windows-874',$txtdata3);
		$pdf->MultiCell(90,1,$txtRef3,0,'C',0); 
//จบการสั่งพิมพ์ Online จากเว็บ
		
*/		
		
		}
		
	unset($sum_netAmt);
	unset($intFineAmt);
	unset($sum_vatAmt);
	unset($sum_whtAmt);
	unset($sum_debtAmt);
}
if($status == 0){
	pg_query("COMMIT");
}else{
	pg_query("ROLLBACK");
}

$pdf->Output(); //open pdf
?>
