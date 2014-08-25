<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
require('../../thaipdfclass.php');
include("../../pChart/class/pDraw.class.php");
include("../../pChart/class/pBarcode128.class.php");
include("../../pChart/class/pImage.class.php");
 
 
$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime();

$receipt_id =pg_escape_string($_GET["receiptID"]);
$reason = checknull($_GET["reason"]);
$type = pg_escape_string($_GET["typeprint"]);
if($type == "all"){
	$typeprint = '{"1","2"}';
}else{
	$typeprint = "{".$type."}";
}

$grouptax = pg_escape_string($_GET["grouptax"]); // ถ้าเป็น "yes" คือให้พิมพ์ใบกำกับภาษีที่อยู่ในกลุ่มเดียวกันทั้งหมด
$notSentLetterOnly = pg_escape_string($_GET["notSentLetterOnly"]); // ถ้าเป็น "yes" คือให้พิมพ์ใบกำกับภาษีที่ยังไม่ได้ส่งจดหมายเท่านั้น

if($notSentLetterOnly == "yes")
{ // ถ้าจะพิมพ์เฉพาะใบกำกับที่ยังไม่ส่งจดหมาย
	$whereNotSentLetterOnly = " and  a.\"taxinvoiceID\" not in(select \"detailRef\" from \"vthcap_letter\" where \"detailRef\" is not null)";
}

if($grouptax == "yes")
{
	$wherePeriod = ""; // เงื่อไขในการหาว่าจะเอางวดอะไรบ้าง
	$qry_taxinvoice = pg_query("select \"debtID\", \"typePayRefValue\" from \"thcap_temp_receipt_otherpay\" where \"receiptID\" = '$receipt_id' ");
	while($res_taxinvoice = pg_fetch_array($qry_taxinvoice))
	{
		$taxDebtID = $res_taxinvoice["debtID"]; // รหัสหนี้
		$Period = $res_taxinvoice["typePayRefValue"]; // งวดที่
		
		if($wherePeriod == "")
		{
			$wherePeriod = " and (a.\"typePayRefValue\" = '$Period'";
		}
		else
		{
			$wherePeriod = $wherePeriod." or a.\"typePayRefValue\" = '$Period'";
		}
	}
	if($wherePeriod != ""){$wherePeriod = $wherePeriod.")";} // เงื่อไขในการหาว่าจะเอางวดอะไรบ้าง
	
	// หาเลขที่สัญญา
	$qry_taxData = pg_query("select \"contractID\" from \"thcap_temp_otherpay_debt\"where \"debtID\" = '$taxDebtID'");
	$dataContractID = pg_fetch_result($qry_taxData,0);
	
	// หาใบกำกับในกลุ่มทั้งหมด
	$qry_allTax = pg_query("select a.\"taxinvoiceID\" as \"taxinvoiceID\" from \"thcap_temp_taxinvoice_otherpay\" a, \"thcap_temp_taxinvoice_details\" b, \"thcap_temp_otherpay_debt\" c
							where a.\"taxinvoiceID\" = b.\"taxinvoiceID\" and a.\"debtID\" = c.\"debtID\"
							and c.\"contractID\" = '$dataContractID' and a.\"typePayID\" = account.\"thcap_mg_getMinPayType\"(c.\"contractID\") $wherePeriod $whereNotSentLetterOnly ");
	$row_allTax = pg_num_rows($qry_allTax);
}

if($grouptax == "yes" && $row_allTax != "" && $row_allTax > 1)
{ // ถ้ามีมากกว่าหนึ่งใบกำกับ และจะพิมพ์เป็นกลุ่ม

	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '	(THCAP) พิมพ์ใบกำกับภาษี', '$app_date')");
	//ACTIONLOG---
		
	//$receiptID=$_GET["receiptID"];
	$db1="ta_mortgage_datastore";

	$pdf=new ThaiPDF('P' ,'mm','a4');  

	$pdf->SetLeftMargin(0);
	$pdf->SetTopMargin(0);
	$pdf->SetThaiFont();
	
	while($rec_allTax = pg_fetch_array($qry_allTax))
	{
		$receipt_id = $rec_allTax["taxinvoiceID"];
		$receiptID = $rec_allTax["taxinvoiceID"];
		
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
		
		$query1 = "INSERT INTO \"thcap_reprint_log\" (	
												\"receipt_id\",
												\"reprint_reason\",
												\"reprint_user\",
												\"reprint_datetime\",
												type_reprint
												) 
									VALUES(
											   '$receipt_id',
											   $reason,
											   '$app_user',
											   '$app_date',
											   '$typeprint')";

		$res_inss=pg_query($query1);

		for($p_ja=1;$p_ja<=2;$p_ja++){ //ใส่ไว้เพื่อให้ print รายงานออกมา 2 ชุด
		$pdf->AddPage();

		if($p_ja!="1"){
			$pdf->Image("images/12.png",60,100,100);  // ลายน้ำสำเนา
		}

		//ค้นหาข้อมูลใบเสร็จ
		$qry_conid=pg_query("select a.\"contractID\",a.\"taxinvoiceID\",a.\"taxpointDate\",
		a.\"nextDueDate\",a.\"nextDueAmt\",a.\"cusFullname\",a.\"cusCoFullname\",a.\"userFullname\",a.\"addrFull\",a.\"addrSend\",a.\"doerStamp\",a.\"branchName\",a.\"branchAdds\",a.\"replacetaxinvID\" from thcap_v_taxinvoice_details  a
		WHERE a.\"taxinvoiceID\" = '$receiptID' ");
		if($result=pg_fetch_array($qry_conid)){	
			$contractID=trim($result["contractID"]);
			$receiptID=trim($result["taxinvoiceID"]);
			$receiveDate=trim($result["taxpointDate"]);
			$nextDueDate=trim($result["nextDueDate"]);
			$nextDueAmt=trim($result["nextDueAmt"]);
			$receiveAmount=trim($result["receiveAmount"]);
			$byChannel=trim($result["byChannel"]);
			$fullname=trim($result["userFullname"]); //พนักงาน
			$name3=trim($result["cusFullname"]); //คำนำหน้า-ชื่อ-นามสกุลลูกค้า
			$nameco=trim($result["cusCoFullname"]); //คำนำหน้า-ชื่อ-นามสกุลผู้กู้ร่วม
			$address=trim($result["addrFull"]); //ที่อยู่บนใบเสร็จ
			$doerStamp=trim($result["doerStamp"]);
			$branchName=trim($result["branchName"]);
			$branchAdds=trim($result["branchAdds"]);
			$addrSend=trim($result["addrSend"]);
			$replacetaxinvID=trim($result["replacetaxinvID"]);
			
		}

		$h2 = -0.5;

		$pdf->SetFont('AngsanaNew','B',20);  
		//$pdf->SetXY(15,);
		if($p_ja=="1"){
			$txth="(ต้นฉบับ)";
		}else{
			$txth="(สำเนา)";
		}

		$txtreceipt=iconv('UTF-8','windows-874',$txth);
		$pdf->Text(170,11.4+$h2,$txtreceipt); //ใบเสร็จรับเงินต้นฉบับ

		$pdf->SetFont('AngsanaNew','',14);  
		$receiptID=iconv('UTF-8','windows-874',$receiptID);
		$pdf->Text(170,25.6+$h2,$receiptID); //ใบเสร็จรับเงินเลขที่

		$pdf->SetFont('AngsanaNew','B',15);
		$receiveDate=iconv('UTF-8','windows-874',$receiveDate);
		$pdf->Text(170,32.3+$h2,$receiveDate); //วันที่ชำระ

		 
		$pdf->SetFont('AngsanaNew','',12);  
		$name=iconv('UTF-8','windows-874',$name3);
		$pdf->Text(34,35.2+$h2,$name); //ชื่อลูกค้า

		$contractID=iconv('UTF-8','windows-874',$contractID);
		$pdf->Text(102,35.2+$h2,$contractID); //เลขที่สัญญา

		$pdf->SetFont('AngsanaNew','',14);  
		$pdf->SetXY(33,37.9+$h2);
		$address2=iconv('UTF-8','windows-874',$address);
		$pdf->MultiCell(99,5,$address2,0,'L',0); //ที่อยู่


		$pdf->Image("../barcode/$receiptID.png",145,39,42.33,13.57);  //barcode

		//$pdf->Image("open.png",145,39,32,32);  //ลายน้ำ x,y,w,h 

		if($replacetaxinvID != ""){
		//############ใบเสร็จออกแทน
			$pdf->SetFont('AngsanaNew','B',14); 
			$pdf->SetXY(20,52);
			$no=iconv('UTF-8','windows-874',"* ออกแทนใบกำกับภาษีที่ยกเลิก ".$replacetaxinvID);
			$pdf->MultiCell(190,3,$no,0,'L',0); //no

		$pdf->SetFont('AngsanaNew','',14);
		}
		//##########################ค่าในตาราง
		$row = 69; // ตำแหน่งแนวนอน

		$qry_conid2=pg_query("select \"typePayID\",\"tpDesc\",\"tpFullDesc\",\"typePayRefValue\",\"debtID\",\"netAmt\"
		\"vatAmt\",\"debtAmt\",\"whtAmt\"
		from thcap_v_taxinvoice_otherpay 
		WHERE \"taxinvoiceID\" = '$receiptID' ");
		while($result2=pg_fetch_array($qry_conid2)){	
			$typePayID = $result2["typePayID"]; 
			$tpDesc=trim($result2["tpDesc"]);
			$tpFullDesc=trim($result2["tpFullDesc"]);
			$typePayRefValue=trim($result2["typePayRefValue"]);
			$debtID = $result2["debtID"]; // รหัสหนี้
			$netAmt = $result2["netAmt"]; // ค่าใช้จ่ายนั้นๆ ก่อนภาษีมูลค่าเพิ่ม
			$vatAmt = $result2["vatAmt"]; // ภาษีมูลค่าเพิ่ม
			$debtAmt = $result2["debtAmt"]; // netAmt+vatAmt
			$whtAmt = $result2["whtAmt"];
			$fulldesc = "$tpDesc $tpFullDesc $typePayRefValue"; //รายละเอียดการรับชำระ

			
			$sum_netAmt += $netAmt; //รวมจำนวนเงิน
			$sum_vatAmt += $vatAmt; //รวมภาษีมูลค่าเพิ่ม
			$sum_whtAmt += $whtAmt; //รวมภาษีหัก ณ ที่จ่าย
			$sum_debtAmt += $debtAmt; //รวม

			
		$pdf->SetXY(18,$row);
		$no=iconv('UTF-8','windows-874',$typePayID);
		$pdf->MultiCell(15,3,$no,0,'C',0); //no

		$pdf->SetXY(31,$row);
		$detail=iconv('UTF-8','windows-874',$fulldesc);
		$pdf->MultiCell(60,3,$detail,0,'L',0); //รายละเอียดการรับชำระ

		$pdf->SetXY(95,$row);
		$amount=iconv('UTF-8','windows-874',number_format($netAmt,2));
		$pdf->MultiCell(24,3,$amount,0,'R',0); //จำนวนเงิน

		$pdf->SetXY(119,$row);
		if($vatAmt > 0){$vat=iconv('UTF-8','windows-874',number_format($vatAmt,2));}
		else{$vat=iconv('UTF-8','windows-874','-');}
		$pdf->MultiCell(23,3,$vat,0,'R',0); //ภาษีมูลค่าเพิ่ม

		$pdf->SetXY(142,$row);
		if($whtAmt > 0){$vat2=iconv('UTF-8','windows-874',number_format($whtAmt,2));}
		else{$vat2=iconv('UTF-8','windows-874','-');}
		$pdf->MultiCell(27,3,$vat2,0,'R',0); //ภาษีหัก ณ ที่จ่าย

		$pdf->SetXY(170,$row);
		$total=iconv('UTF-8','windows-874',number_format($debtAmt,2));
		$pdf->MultiCell(25,3,$total,0,'R',0); //รวม

		$row += 6;

		}


		//##########################รวมด้านล่าง
		$pdf->SetXY(95,110);
		$amount=iconv('UTF-8','windows-874',number_format($sum_netAmt,2));
		$pdf->MultiCell(24,3,$amount,0,'R',0); //จำนวนเงิน

		$pdf->SetXY(119,110);
		if($sum_vatAmt > 0){$vat=iconv('UTF-8','windows-874',number_format($sum_vatAmt,2));}
		else{$vat=iconv('UTF-8','windows-874','-');}
		$pdf->MultiCell(23,3,$vat,0,'R',0); //รวมภาษีมูลค่าเพิ่ม

		$pdf->SetXY(142,110);
		if($sum_whtAmt > 0){$vat2=iconv('UTF-8','windows-874',number_format($sum_whtAmt,2));}
		else{$vat2=iconv('UTF-8','windows-874','-');}
		$pdf->MultiCell(27,3,$vat2,0,'R',0); //รวมภาษีหัก ณ ที่จ่าย

		$pdf->SetXY(170,110);
		$total=iconv('UTF-8','windows-874',number_format($sum_debtAmt,2));
		$pdf->MultiCell(25,3,$total,0,'R',0); //รวมทั้งหมด




		//##################### แสดงข้อความ เอกสารเป็นชุด สีแดง

		$sqldoc = pg_query("select \"thcap_taxinvoiceIDToreceiptID\"('$receiptID')");
		$redoc = pg_fetch_result($sqldoc,0);
		if(!empty($redoc) || $redoc != "" ){

		$textdocdetail = " *เอกสารออกเป็นชุด   อ้างอิงใบเสร็จรับเงินเลขที่  ".$redoc;
		$pdf->SetFont('AngsanaNew','B',14);
		$pdf->SetTextColor(255,0,0); 
		$txtdoc=iconv('UTF-8','windows-874',$textdocdetail);
		$pdf->Text(21,121,$txtdoc);
		$txtline=iconv('UTF-8','windows-874',"________________");
		$pdf->Text(22,121.2,$txtline);
		}

		//##########################ยอดค้างชำระคงเหลือที่จะครบกำหนด วันที่
		if($doerStamp==""){
			$doerStamp="-";
		}
		$pdf->SetFont('AngsanaNew','',14);
		$pdf->SetTextColor(0); 
		$date=iconv('UTF-8','windows-874',$doerStamp);
		$pdf->Text(47,127.5,$date);


		$money=iconv('UTF-8','windows-874',$branchName);
		$pdf->Text(60,135.7,$money);

		$ba=iconv('UTF-8','windows-874',$branchAdds);
		$pdf->Text(21,142.7,$ba);


		//##################ผู้กู้ร่วม############################
		if($nameco != ""){
			$pdf->SetXY(21,163);
			$co=iconv('UTF-8','windows-874',"ผู้กู้ร่วม");
			$pdf->MultiCell(15,3,$co,0,'L',0);
			
			$pdf->SetXY(31,163);
			$nameco=iconv('UTF-8','windows-874',$nameco);
			$pdf->MultiCell(150,3,$nameco,0,'L',0); //รายชื่อผู้กู้ร่วม
		}

		$pdf->SetXY(136,183.6);
		$co=iconv('UTF-8','windows-874',$fullname);
		$pdf->MultiCell(50,4,$co,0,'C',0); //ผู้รับเงิน

		//###########กรุณาส่ง
		$pdf->SetXY(50,237.5);
		$name=iconv('UTF-8','windows-874',$name3);
		$pdf->MultiCell(150,3,$name,0,'L',0); //ชื่อลูกค้า

		$pdf->SetXY(50,242.5);
		$address2=iconv('UTF-8','windows-874',$addrSend);
		$pdf->MultiCell(60,5,$address2,0,'L',0); //ที่อยู่
		  
		/*
		if (!file_exists($_SESSION["session_path_save_pdf"].$rec_id.".pdf")) { //check file exists
		$pdf->Output($_SESSION["session_path_save_pdf"].$rec_id.".pdf", "F"); // save pdf
		}
		*/
		$sum_netAmt="";
		$sum_vatAmt="";
		$sum_whtAmt="";
		$sum_debtAmt="";
		}
	}
	$pdf->Output(); //open pdf
}
else
{
	$query1 = "INSERT INTO \"thcap_reprint_log\" (	
											\"receipt_id\",
											\"reprint_reason\",
											\"reprint_user\",
											\"reprint_datetime\",
											type_reprint
											) 
								VALUES(
										   '$receipt_id',
										   $reason,
										   '$app_user',
										   '$app_date',
										   '$typeprint')";

	$res_inss=pg_query($query1);
	 
	 
	 //ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '	(THCAP) พิมพ์ใบกำกับภาษี', '$app_date')");
	//ACTIONLOG---
		
	if($grouptax == "yes")
	{ // ถ้ามาจากหน้า (THCAP) รับชำระเงิน และเป็นสัญญา HIRE_PURCHASE
		$receiptID = pg_fetch_result($qry_allTax,0);
	}
	else
	{
		$receiptID=$_GET["receiptID"];
	}
	
	$db1="ta_mortgage_datastore";

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

	for($p_ja=1;$p_ja<=2;$p_ja++){ //ใส่ไว้เพื่อให้ print รายงานออกมา 2 ชุด
	$pdf->AddPage();

	if($p_ja!="1"){
		$pdf->Image("images/12.png",60,100,100);  // ลายน้ำสำเนา
	}

	//ค้นหาข้อมูลใบเสร็จ
	$qry_conid=pg_query("select a.\"contractID\",a.\"taxinvoiceID\",a.\"taxpointDate\",
	a.\"nextDueDate\",a.\"nextDueAmt\",a.\"cusFullname\",a.\"cusCoFullname\",a.\"userFullname\",a.\"addrFull\",a.\"addrSend\",a.\"doerStamp\",a.\"branchName\",a.\"branchAdds\",a.\"replacetaxinvID\" from thcap_v_taxinvoice_details  a
	WHERE a.\"taxinvoiceID\" = '$receiptID' ");
	if($result=pg_fetch_array($qry_conid)){	
		$contractID=trim($result["contractID"]);
		$receiptID=trim($result["taxinvoiceID"]);
		$receiveDate=trim($result["taxpointDate"]);
		$nextDueDate=trim($result["nextDueDate"]);
		$nextDueAmt=trim($result["nextDueAmt"]);
		$receiveAmount=trim($result["receiveAmount"]);
		$byChannel=trim($result["byChannel"]);
		$fullname=trim($result["userFullname"]); //พนักงาน
		$name3=trim($result["cusFullname"]); //คำนำหน้า-ชื่อ-นามสกุลลูกค้า
		$nameco=trim($result["cusCoFullname"]); //คำนำหน้า-ชื่อ-นามสกุลผู้กู้ร่วม
		$address=trim($result["addrFull"]); //ที่อยู่บนใบเสร็จ
		$doerStamp=trim($result["doerStamp"]);
		$branchName=trim($result["branchName"]);
		$branchAdds=trim($result["branchAdds"]);
		$addrSend=trim($result["addrSend"]);
		$replacetaxinvID=trim($result["replacetaxinvID"]);
		
	}



	//ถ้า cusfullname เป็นค่าว่างให้ไปค้นหาชื่อจาก mysql มีโอกาสพบค่าว่างได้เนื่องจากเลขที่ใบเสร็จเก่าอาจยังไม่ได้เก็บชื่อลูกค้าทำให้ไม่พบข้อมูลใน pg
	// if($name3==""){
		// $qryname = mysql_query("select cusname from $db1.vcustomerbycontract where contract_loans_code='$contractID' and cus_group_type_code='01'");
		// $resname=mysql_fetch_array($qryname);
		// $name3=$resname["cusname"];
	// }

	// if($name3==""){
		// $qryname = pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
		// $resname=pg_fetch_array($qryname);
		// $name3=$resname["thcap_fullname"];
	// }
	//กรณีค่าที่ได้เป็นค่าว่าง ค้นหาที่อยู่จาก mysql 
	// if($address==""){
		// $qry_add=mysql_query("select * from $db1.vaddrbycontract
		// where contract_loans_code='$contractID'");
		// if($resadd=mysql_fetch_array($qry_add)){
			// $address=trim($resadd["address"]);
		// }
	// }

	// if($address==""){
		// $qry_add=pg_query("select * from \"vthcap_ContactCus_detail\"
		// where \"contractID\"='$contractID'");
		// if($resadd=pg_fetch_array($qry_add)){
			// $address=trim($resadd["thcap_address"]);
		// }
	// }
	//กรณีค่าที่ได้เป็นค่าว่าง ค้นหาชื่อผู้กู้ร่วมจาก mysql
	// if($nameco==""){
		// $qry_name=mysql_query("select * from $db1.vcustomerbycontract
		// where contract_loans_code='$contractID' and cus_group_type_code<>'01'");
		// $numco=mysql_num_rows($qry_name);
		// $i=1;
		// $nameco="";
		// while($resco=mysql_fetch_array($qry_name)){
			// $name2=trim($resco["cusname"]);
			// if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
				// $nameco=$name2;
			// }else{ 
				// if($i==$numco){
					// $nameco=$nameco.$name2;
				// }else{
					// $nameco=$nameco.$name2.",";
				// }
			// }
			// $i++;
		// }
	// }

	// if($nameco==""){
		// $qry_name=pg_query("select * from \"vthcap_ContactCus_detail\"
		// where \"contractID\"='$contractID' and \"CusState\" = '1'");
		// $numco=pg_num_rows($qry_name);
		// $i=1;
		// $nameco="";
		// while($resco=pg_fetch_array($qry_name)){
			// $name2=trim($resco["thcap_fullname"]);
			// if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
				// $nameco=$name2;
			// }else{ 
				// if($i==$numco){
					// $nameco=$nameco.$name2;
				// }else{
					// $nameco=$nameco.$name2.",";
				// }
			// }
			// $i++;
		// }
	// }

	$h2 = -0.5;

	$pdf->SetFont('AngsanaNew','B',20);  
	//$pdf->SetXY(15,);
	if($p_ja=="1"){
		$txth="(ต้นฉบับ)";
	}else{
		$txth="(สำเนา)";
	}

	$txtreceipt=iconv('UTF-8','windows-874',$txth);
	$pdf->Text(170,11.4+$h2,$txtreceipt); //ใบเสร็จรับเงินต้นฉบับ

	$pdf->SetFont('AngsanaNew','',14);  
	$receiptID=iconv('UTF-8','windows-874',$receiptID);
	$pdf->Text(170,25.6+$h2,$receiptID); //ใบเสร็จรับเงินเลขที่

	$pdf->SetFont('AngsanaNew','B',15);
	$receiveDate=iconv('UTF-8','windows-874',$receiveDate);
	$pdf->Text(170,32.3+$h2,$receiveDate); //วันที่ชำระ

	 
	$pdf->SetFont('AngsanaNew','',12);  
	$name=iconv('UTF-8','windows-874',$name3);
	$pdf->Text(34,35.2+$h2,$name); //ชื่อลูกค้า

	$contractID=iconv('UTF-8','windows-874',$contractID);
	$pdf->Text(102,35.2+$h2,$contractID); //เลขที่สัญญา

	$pdf->SetFont('AngsanaNew','',14);  
	$pdf->SetXY(33,37.9+$h2);
	$address2=iconv('UTF-8','windows-874',$address);
	$pdf->MultiCell(99,5,$address2,0,'L',0); //ที่อยู่


	$pdf->Image("../barcode/$receiptID.png",145,39,42.33,13.57);  //barcode

	//$pdf->Image("open.png",145,39,32,32);  //ลายน้ำ x,y,w,h 

	if($replacetaxinvID != ""){
	//############ใบเสร็จออกแทน
		$pdf->SetFont('AngsanaNew','B',14); 
		$pdf->SetXY(20,52);
		$no=iconv('UTF-8','windows-874',"* ออกแทนใบกำกับภาษีที่ยกเลิก ".$replacetaxinvID);
		$pdf->MultiCell(190,3,$no,0,'L',0); //no

	$pdf->SetFont('AngsanaNew','',14);
	}
	//##########################ค่าในตาราง
	$row = 69; // ตำแหน่งแนวนอน

	$qry_conid2=pg_query("select \"typePayID\",\"tpDesc\",\"tpFullDesc\",\"typePayRefValue\",\"debtID\",\"netAmt\"
	\"vatAmt\",\"debtAmt\",\"whtAmt\"
	from thcap_v_taxinvoice_otherpay 
	WHERE \"taxinvoiceID\" = '$receiptID' ");
	
	while($result2=pg_fetch_array($qry_conid2)){	
			$typePayID = $result2["typePayID"]; 
		$tpDesc=trim($result2["tpDesc"]);
		$tpFullDesc=trim($result2["tpFullDesc"]);
		$typePayRefValue=trim($result2["typePayRefValue"]);
		$debtID = $result2["debtID"]; // รหัสหนี้
		$netAmt = $result2["netAmt"]; // ค่าใช้จ่ายนั้นๆ ก่อนภาษีมูลค่าเพิ่ม
		$vatAmt = $result2["vatAmt"]; // ภาษีมูลค่าเพิ่ม
		$debtAmt = $result2["debtAmt"]; // netAmt+vatAmt
		$whtAmt = $result2["whtAmt"];
		$fulldesc = "$tpDesc $tpFullDesc $typePayRefValue"; //รายละเอียดการรับชำระ

		
		$sum_netAmt += $netAmt; //รวมจำนวนเงิน
		$sum_vatAmt += $vatAmt; //รวมภาษีมูลค่าเพิ่ม
		$sum_whtAmt += $whtAmt; //รวมภาษีหัก ณ ที่จ่าย
		$sum_debtAmt += $debtAmt; //รวม

		
	$pdf->SetXY(18,$row);
	$no=iconv('UTF-8','windows-874',$typePayID);
	$pdf->MultiCell(15,3,$no,0,'C',0); //no

	$pdf->SetXY(31,$row);
	$detail=iconv('UTF-8','windows-874',$fulldesc);
	$pdf->MultiCell(60,3,$detail,0,'L',0); //รายละเอียดการรับชำระ

	$pdf->SetXY(95,$row);
	$amount=iconv('UTF-8','windows-874',number_format($netAmt,2));
	$pdf->MultiCell(24,3,$amount,0,'R',0); //จำนวนเงิน

	$pdf->SetXY(119,$row);
	if($vatAmt > 0){$vat=iconv('UTF-8','windows-874',number_format($vatAmt,2));}
	else{$vat=iconv('UTF-8','windows-874','-');}
	$pdf->MultiCell(23,3,$vat,0,'R',0); //ภาษีมูลค่าเพิ่ม

	$pdf->SetXY(142,$row);
	if($whtAmt > 0){$vat2=iconv('UTF-8','windows-874',number_format($whtAmt,2));}
	else{$vat2=iconv('UTF-8','windows-874','-');}
	$pdf->MultiCell(27,3,$vat2,0,'R',0); //ภาษีหัก ณ ที่จ่าย

	$pdf->SetXY(170,$row);
	$total=iconv('UTF-8','windows-874',number_format($debtAmt,2));
	$pdf->MultiCell(25,3,$total,0,'R',0); //รวม

	$row += 6;

	}


	//##########################รวมด้านล่าง
	$pdf->SetXY(95,110);
	$amount=iconv('UTF-8','windows-874',number_format($sum_netAmt,2));
	$pdf->MultiCell(24,3,$amount,0,'R',0); //จำนวนเงิน

	$pdf->SetXY(119,110);
	if($sum_vatAmt > 0){$vat=iconv('UTF-8','windows-874',number_format($sum_vatAmt,2));}
	else{$vat=iconv('UTF-8','windows-874','-');}
	$pdf->MultiCell(23,3,$vat,0,'R',0); //รวมภาษีมูลค่าเพิ่ม

	$pdf->SetXY(142,110);
	if($sum_whtAmt > 0){$vat2=iconv('UTF-8','windows-874',number_format($sum_whtAmt,2));}
	else{$vat2=iconv('UTF-8','windows-874','-');}
	$pdf->MultiCell(27,3,$vat2,0,'R',0); //รวมภาษีหัก ณ ที่จ่าย

	$pdf->SetXY(170,110);
	$total=iconv('UTF-8','windows-874',number_format($sum_debtAmt,2));
	$pdf->MultiCell(25,3,$total,0,'R',0); //รวมทั้งหมด




	//##################### แสดงข้อความ เอกสารเป็นชุด สีแดง

	$sqldoc = pg_query("select \"thcap_taxinvoiceIDToreceiptID\"('$receiptID')");
	$redoc = pg_fetch_result($sqldoc,0);
	if(!empty($redoc) || $redoc != "" ){

	$textdocdetail = " *เอกสารออกเป็นชุด   อ้างอิงใบเสร็จรับเงินเลขที่  ".$redoc;
	$pdf->SetFont('AngsanaNew','B',14);
	$pdf->SetTextColor(255,0,0); 
	$txtdoc=iconv('UTF-8','windows-874',$textdocdetail);
	$pdf->Text(21,121,$txtdoc);
	$txtline=iconv('UTF-8','windows-874',"________________");
	$pdf->Text(22,121.2,$txtline);
	}

	//##########################ยอดค้างชำระคงเหลือที่จะครบกำหนด วันที่
	if($doerStamp==""){
		$doerStamp="-";
	}
	$pdf->SetFont('AngsanaNew','',14);
	$pdf->SetTextColor(0); 
	$date=iconv('UTF-8','windows-874',$doerStamp);
	$pdf->Text(47,127.5,$date);


	$money=iconv('UTF-8','windows-874',$branchName);
	$pdf->Text(60,135.7,$money);

	$ba=iconv('UTF-8','windows-874',$branchAdds);
	$pdf->Text(21,142.7,$ba);


	//##################ผู้กู้ร่วม############################
	if($nameco != ""){
		$pdf->SetXY(21,163);
		$co=iconv('UTF-8','windows-874',"ผู้กู้ร่วม");
		$pdf->MultiCell(15,3,$co,0,'L',0);
		
		$pdf->SetXY(31,163);
		$nameco=iconv('UTF-8','windows-874',$nameco);
		$pdf->MultiCell(150,3,$nameco,0,'L',0); //รายชื่อผู้กู้ร่วม
	}

	$pdf->SetXY(136,183.6);
	$co=iconv('UTF-8','windows-874',$fullname);
	$pdf->MultiCell(50,4,$co,0,'C',0); //ผู้รับเงิน

	//###########กรุณาส่ง
	$pdf->SetXY(50,237.5);
	$name=iconv('UTF-8','windows-874',$name3);
	$pdf->MultiCell(150,3,$name,0,'L',0); //ชื่อลูกค้า

	$pdf->SetXY(50,242.5);
	$address2=iconv('UTF-8','windows-874',$addrSend);
	$pdf->MultiCell(60,5,$address2,0,'L',0); //ที่อยู่
	  
	/*
	if (!file_exists($_SESSION["session_path_save_pdf"].$rec_id.".pdf")) { //check file exists
	$pdf->Output($_SESSION["session_path_save_pdf"].$rec_id.".pdf", "F"); // save pdf
	}
	*/
	$sum_netAmt="";
	$sum_vatAmt="";
	$sum_whtAmt="";
	$sum_debtAmt="";
	}
	$pdf->Output(); //open pdf
}
?>