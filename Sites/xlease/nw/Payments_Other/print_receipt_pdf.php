<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
require('../../thaipdfclass.php');
include("../../pChart/class/pDraw.class.php");
include("../../pChart/class/pBarcode128.class.php");
include("../../pChart/class/pImage.class.php");

$receiptID=pg_escape_string($_GET["receiptID"]);
$db1="ta_mortgage_datastore";


$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$receipt_id =pg_escape_string($_GET["receiptID"]);
$reason = checknull(pg_escape_string(($_GET["reason"])));
$type = pg_escape_string($_GET["typeprint"]);
if(($receiptID=="")and ($receipt_id=="")){
	$receiptID = pg_escape_string($_POST["receiptID"]);
}

if($type == "all"){
	$typeprint = '{"1","2"}';
}else{
	$typeprint = "{".$type."}";
}

$query1 =	"INSERT INTO \"thcap_reprint_log\" (	
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



$typeprintin = pg_escape_string($_GET["typeprint"]); // ประเภทการปริ้น 1 = ปริ้นต้นฉบับ 2 = ปริ้นสำเนา 1,2 ปริ้นทั้งหมด
if($typeprintin==""){
	$typeprintin = pg_escape_string($_POST["typeprint"]);
}
if($typeprintin == '1'){
	$typeprint = '1';
	$chk = 'real';
}else if($typeprintin == '2'){
	$typeprint = '2';
}else{
	$typeprint = '1';
}


//$db2="ta_mortgage";

/*$nowdate = date('d-m');
$nowyear = date('Y');
$nowyear += 543;*/
$nowtext = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$contractID=pg_escape_string($_GET["contractID"]); // เลขที่สัญญา


if($contractID == "") // ถ้าไม่มีการส่งเลขที่สัญญามา ต้องหาเอง
{
	$qry_conid_spa = pg_query("select * from \"thcap_v_receipt_details\" WHERE \"receiptID\" = '$receiptID'");
	$numchk1=pg_num_rows($qry_conid_spa);
	if($numchk1==0){ //แสดงว่าใบเสร็จถูกยกเลิกแล้ว 
		$qry_conid_spa = pg_query("select * from \"thcap_v_receipt_details_cancel\" WHERE \"receiptID\" = '$receiptID'");
	}
	
	if($resultspa = pg_fetch_array($qry_conid_spa)){	
		$contractID = trim($resultspa["contractID"]);
	}
}

//--- หาประเภทสินเชื่อ
$qryConType = pg_query("select \"conType\" from \"thcap_contract\" where \"contractID\" = '$contractID' ");
$conType = pg_fetch_result($qryConType,0);

// ชื่อประเภทสินเชื่อแบบเต็ม
$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$contractID') ");
$chk_con_type = pg_fetch_result($qry_chk_con_type,0);

$typepdf = pg_escape_string($_GET["typepdf"]); // ประเภทของใบเสร็จ  1 คือ ค่างวด  2 คือ ค่าอื่นๆ


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

for($p_ja=$typeprint;$p_ja<=2;$p_ja++){ //ใส่ไว้เพื่อให้ print รายงานออกมา 2 ชุด

	if($chk == "real" && $p_ja == "2" ){break; exit;}
$pdf->AddPage();

if($p_ja!="1"){
	$pdf->Image("images/12.png",60,100,100);  //barcode
}
//ค้นหาข้อมูลใบเสร็จ
$qry_conid=pg_query("select * from \"thcap_temp_receipt_channel\" where \"receiptID\" = '$receiptID' and \"byChannel\" <> '999' ");
if($result=pg_fetch_array($qry_conid))
{	
	$receiveDate=trim($result["receiveDate"]); // วันที่รับชำระ
	$receiveDate=substr($receiveDate,0,19); // วันที่รับชำระรูปแบบ วัน-เดือน-ปี
	
}
	
$qry_conidWHT=pg_query("select * from \"thcap_temp_receipt_channel\" where \"receiptID\" = '$receiptID' and \"byChannel\" = '999' ");
$numrowWht = pg_num_rows($qry_conidWHT);
if($numrowWht == 1)
{
	$WHT = 1; // ถ้ามี WHT
	if($result_wht=pg_fetch_array($qry_conidWHT))
		{
			$ChannelAmtWHT=trim($result_wht["ChannelAmt"]); // จำนวน WHT
		}
}
else
{
	$WHT = 0; // ถ้าไม่มี WHT
}

$qry_next=pg_query("select * from \"thcap_v_receipt_details\" where \"receiptID\" = '$receiptID' ");
$numchk2=pg_num_rows($qry_next);
if($numchk2==0){ //แสดงว่าใบเสร็จถูกยกเลิกแล้ว 
	$qry_next=pg_query("select * from \"thcap_v_receipt_details_cancel\" where \"receiptID\" = '$receiptID' ");
}

if($result_next=pg_fetch_array($qry_next))
{
	$nextDueAmt=trim($result_next["nextDueAmt"]);
	$nextDueDate=trim($result_next["nextDueDate"]); // ยอดค้างชำระคงเหลือที่จะครบกำหนด วันที่
	
	$fullname=trim($result_next["userFullname"]); //พนักงาน
	$name3=trim($result_next["cusFullname"]); //คำนำหน้า-ชื่อ-นามสกุลลูกค้า
	$nameco=trim($result_next["cusCoFullname"]); //คำนำหน้า-ชื่อ-นามสกุลผู้กู้ร่วม
	$address=trim($result_next["addrFull"]); //ที่อยู่บนใบเสร็จ
	$addresssend=trim($result_next["addrSend"]); //ที่อยู่ส่งจดหมาย
	
	$typeReceive=trim($result_next["typeReceive"]); //ประเภทใบเสร็จออกแทน
	$typeDetail=trim($result_next["typeDetail"]); //รายละเอียดเลขที่ใบเสร็จออกแทน
	
	$y2=substr($nextDueDate,0,4);
	$y2=$y2+543;
	$m2=substr($nextDueDate,5,2);
	$d2=substr($nextDueDate,8,2);
	//$nextDueDate_text=$d2."-".$m2."-".$y2; // ยอดค้างชำระคงเหลือที่จะครบกำหนด วันที่ รูปแบบไทย
	$nextDueDate_text = $nextDueDate;
}
//จบการค้นหาข้อมูลใบเสร็จ

$pdf->SetFont('AngsanaNew','B',20);  
//$pdf->SetXY(15,);
if($p_ja=="1"){
	$txth="(ต้นฉบับ)";
}else{
	$txth="(สำเนา)";
}
$txtreceipt=iconv('UTF-8','windows-874',$txth);
$pdf->Text(180,12.2,$txtreceipt); //ใบเสร็จรับเงินต้นฉบับ

$pdf->SetFont('AngsanaNew','',14);  
$receiptID=iconv('UTF-8','windows-874',$receiptID);
$pdf->Text(165,26,$receiptID); //ใบเสร็จรับเงินเลขที่

$pdf->SetFont('AngsanaNew','B',15);
$receiveDate=iconv('UTF-8','windows-874',$receiveDate);
$pdf->Text(158,32,$receiveDate); //วันที่ชำระ

$pdf->SetFont('AngsanaNew','',12);  
$name=iconv('UTF-8','windows-874',$name3);
$pdf->Text(34,35.5,$name); //ชื่อลูกค้า

$contractID=iconv('UTF-8','windows-874',$contractID);
$pdf->Text(100,35.5,$contractID); //เลขที่สัญญา

$pdf->SetFont('AngsanaNew','',14); 
$pdf->SetXY(33,38);
$address2=iconv('UTF-8','windows-874',$address);
$pdf->MultiCell(100,5,$address2,0,'L',0); //ที่อยู่

$pdf->Image("../barcode/$receiptID.png",145,39,42.33,13.57);  //barcode

//############ใบเสร็จออกแทน
if($typeReceive != ""){
	$pdf->SetFont('AngsanaNew','B',14); 
	$pdf->SetXY(20,52);
	$no=iconv('UTF-8','windows-874',"* ออกแทน$typeReceive: $typeDetail *");
	$pdf->MultiCell(190,3,$no,0,'L',0); //no
}
$pdf->SetFont('AngsanaNew','',12); 
//##########################ค่าในตาราง
$row = 68; // ตำแหน่งแนวนอน

if($typepdf == 1 && $chk_con_type=='JOINT_VENTURE')
{
	$qry_table=pg_query("select sum(\"netAmt\") as \"netAmt\", sum(\"vatAmt\") as \"vatAmt\", sum(\"debtAmt\") as \"debtAmt\", sum(\"whtAmt\") as \"whtAmt\"
						from \"thcap_v_receipt_otherpay\" where \"receiptID\" = '$receiptID' and \"typePayID\" in('D000', 'D112') ");
	$numchk3=pg_num_rows($qry_table);
	
	if($numchk3==0)
	{ //แสดงว่าใบเสร็จถูกยกเลิกแล้ว 
		$qry_table=pg_query("select sum(\"netAmt\") as \"netAmt\", sum(\"vatAmt\") as \"vatAmt\", sum(\"debtAmt\") as \"debtAmt\", sum(\"whtAmt\") as \"whtAmt\"
							from \"thcap_v_receipt_otherpay_cancel\" where \"receiptID\" = '$receiptID' and \"typePayID\" in('D000', 'D112') ");
	}
}
else
{
	//thcap_v_receipt_otherpay จะเรียกจาก view ไม่ได้เนื่องจากใน view ไม่มีค่าบางค่า
	$qry_table=pg_query("select * from \"thcap_v_receipt_otherpay\" where \"receiptID\" = '$receiptID' order by \"typePayID\", \"typePayRefValue\" ");
	$numchk3=pg_num_rows($qry_table);
	if($numchk3==0){ //แสดงว่าใบเสร็จถูกยกเลิกแล้ว 
		$qry_table=pg_query("select * from \"thcap_v_receipt_otherpay_cancel\" where \"receiptID\" = '$receiptID' order by \"typePayID\", \"typePayRefValue\" ");
	}
}

while($resultTable=pg_fetch_array($qry_table))
{
	$debtID = $resultTable["debtID"]; // รหัสหนี้
	$netAmt = $resultTable["netAmt"]; // ค่าใช้จ่ายนั้นๆ ก่อนภาษีมูลค่าเพิ่ม
	$vatAmt = $resultTable["vatAmt"]; // ภาษีมูลค่าเพิ่ม
	$debtAmt = $resultTable["debtAmt"]; // netAmt+vatAmt
	$whtAmt = $resultTable["whtAmt"];
	
	if($typepdf == 1 && $chk_con_type=='JOINT_VENTURE')
	{
		$typePayID = "D000";
		$typePayRefValue = $contractID;
		
		$qry_debtDetail = pg_query("select \"tpDesc\", \"tpFullDesc\" from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
		$tpDesc = pg_fetch_result($qry_debtDetail,0);
		$tpFullDesc = pg_fetch_result($qry_debtDetail,1);
		
		$fulldesc = "$tpDesc $tpFullDesc $typePayRefValue"; //รายละเอียดการรับชำระ
	}
	else
	{
		$typePayID=$resultTable["typePayID"];
		$typePayRefValue=$resultTable["typePayRefValue"];
		$tpDesc=$resultTable["tpDesc"];
		$tpFullDesc=$resultTable["tpFullDesc"];
		
		$fulldesc = "$tpDesc $tpFullDesc $typePayRefValue"; //รายละเอียดการรับชำระ
	}
	
	$sum_netAmt += $netAmt; //รวมจำนวนเงิน
	$sum_vatAmt += $vatAmt; //รวมภาษีมูลค่าเพิ่ม
	$sum_whtAmt += $whtAmt; //รวมภาษีหัก ณ ที่จ่าย
	$sum_debtAmt += $debtAmt; //รวม

	
$pdf->SetXY(16,$row);
$no=iconv('UTF-8','windows-874',$typePayID);
$pdf->MultiCell(15,3,$no,0,'C',0); //no

$pdf->SetXY(31,$row);
$detail=iconv('UTF-8','windows-874',$fulldesc);
$pdf->MultiCell(60,3,$detail,0,'L',0); //รายละเอียดการรับชำระ

$pdf->SetXY(95,$row);
$amount=iconv('UTF-8','windows-874',number_format($netAmt,2));
$pdf->MultiCell(24,3,$amount,0,'R',0); //จำนวนเงิน

$pdf->SetXY(122,$row);
if($vatAmt > 0){$vat=iconv('UTF-8','windows-874',number_format($vatAmt,2));}
else{$vat=iconv('UTF-8','windows-874','-');}
$pdf->MultiCell(23,3,$vat,0,'C',0); //ภาษีมูลค่าเพิ่ม

$pdf->SetXY(145,$row);
if($whtAmt > 0){$vat2=iconv('UTF-8','windows-874',number_format($whtAmt,2));}
else{$vat2=iconv('UTF-8','windows-874','-');}
$pdf->MultiCell(28,3,$vat2,0,'C',0); //ภาษีหัก ณ ที่จ่าย

$pdf->SetXY(170,$row);
$total=iconv('UTF-8','windows-874',number_format($debtAmt,2));
$pdf->MultiCell(25,3,$total,0,'R',0); //รวม

$row += 8;

}

if($typepdf == 1 && ($chk_con_type=='LOAN' || $chk_con_type=='JOINT_VENTURE' || $chk_con_type=='PERSONAL_LOAN')) // ถ้าเป็นค่างวด
{
	if($chk_con_type != "JOINT_VENTURE" || ($chk_con_type == "JOINT_VENTURE" && $p_ja != "1"))
	{
		//####### แจกแจงรายการจ่าย
		// หาก่อนว่า ผู้กู้หลักของเลขที่สัญญานี้ใช่นิติบุคคลหรือไม่
		// $qry_selectShow = pg_query("select * from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = '0' and \"type\" = '2' ");
		// $row_selectShow = pg_num_rows($qry_selectShow);
		
		// if($row_selectShow > 0)
		// { // ถ้าเป็นนิติบุคคล
			$qry_detail = pg_query("select * from public.\"thcap_temp_int_201201\" where \"receiptID\"='$receiptID' ");
			$numchk4=pg_num_rows($qry_detail);
			if($numchk4==0){ //แสดงว่าใบเสร็จถูกยกเลิกแล้ว 
				$qry_detail = pg_query("select * from public.\"thcap_temp_cancel_int\" where \"receiptID\"='$receiptID' ");
			}
			while($resdetail = pg_fetch_array($qry_detail))
			{
				$receivePriciple = $resdetail["receivePriciple"]; // หักเงินต้น
				$receiveInterest = $resdetail["receiveInterest"]; // จ่ายดอกเบี้ย
			}

			//หาข้อความชำระเงินต้นจาก function ใน postges
			$qryPrinciple = pg_query("select account.\"thcap_mg_getPrincipleType\"('$contractID')");
			$resPrinciple = pg_fetch_array($qryPrinciple);
			list($PrincipleID) = $resPrinciple;
			$qry_PrincipleID = pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$PrincipleID' ");
			while($resPrincipleID = pg_fetch_array($qry_PrincipleID))
			{
				$txtPriciple = $resPrincipleID["tpDesc"]; // ข้อความของการหักเงินต้น
			}

			//หาข้อความหักดอกเบี้ยจาก function ใน postges
			$qryInterest = pg_query("select account.\"thcap_mg_getInterestType\"('$contractID')");
			$resInterest = pg_fetch_array($qryInterest);
			list($InterestID) = $resInterest;
			$qry_InterestID = pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$InterestID' ");
			while($resInterestID = pg_fetch_array($qry_InterestID))
			{
				$txtInterest = $resInterestID["tpDesc"]; // ข้อความของการหักดอกเบี้ย
			}
			
			//หาเงินต้นคงเหลือ
			$qryLeftPrinciple = pg_query("select \"LeftPrinciple\",\"LeftInterest\" from \"thcap_temp_int_201201\" where \"contractID\" = '$contractID' and \"receiptID\" = '$receiptID' ");
			$numchk5=pg_num_rows($qryLeftPrinciple);
			if($numchk5==0){ //แสดงว่าใบเสร็จถูกยกเลิกแล้ว 
				$qryLeftPrinciple = pg_query("select \"LeftPrinciple\",\"LeftInterest\" from \"thcap_temp_cancel_int\" where \"contractID\" = '$contractID' and \"receiptID\" = '$receiptID' ");
			}
			
			$resLeftPrinciple = pg_fetch_array($qryLeftPrinciple);
			$LeftPrinciple = $resLeftPrinciple["LeftPrinciple"];
			$LeftInterest = $resLeftPrinciple["LeftInterest"];
			
			if($typepdf == 1 && $chk_con_type=='JOINT_VENTURE')
			{
				$qry_table=pg_query("select sum(\"netAmt\") as \"netAmt\", sum(\"vatAmt\") as \"vatAmt\", sum(\"debtAmt\") as \"debtAmt\", sum(\"whtAmt\") as \"whtAmt\"
						from \"thcap_v_receipt_otherpay\" where \"receiptID\" = '$receiptID' and \"typePayID\" in('D112') ");
				$numchk3=pg_num_rows($qry_table);
				
				if($numchk3==0)
				{ //แสดงว่าใบเสร็จถูกยกเลิกแล้ว 
					$qry_table=pg_query("select sum(\"debtAmt\") as \"debtAmt\" from \"thcap_v_receipt_otherpay_cancel\" where \"receiptID\" = '$receiptID' and \"typePayID\" in('D112') ");
				}
				$numchk3=pg_num_rows($qry_table);
				
				if($numchk3 > 0)
				{
					$payAdviser = pg_fetch_result($qry_table,2);
				}
				
				$typePayID = "D112";
				
				$qry_debtDetail = pg_query("select \"tpDesc\" from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
				$tpDesc = pg_fetch_result($qry_debtDetail,0);
				
				$fulldesc = "$tpDesc"; //รายละเอียด
			}
			

			//บวกจำนวนแถวไปอีก 8 ในการเว้นบรรทัด
			$pdf->SetXY(36,$row);
			$detail=iconv('UTF-8','windows-874',"- $txtPriciple ".number_format($receivePriciple,2));
			$pdf->MultiCell(60,3,$detail,0,'L',0); //รายละเอียดการรับชำระ
			$row += 5.5;
			
			$pdf->SetXY(36,$row);
			$detail=iconv('UTF-8','windows-874',"- $txtInterest ".number_format($receiveInterest,2));
			$pdf->MultiCell(60,3,$detail,0,'L',0); //รายละเอียดการรับชำระ
			$row += 4.5;
			
			if($typepdf == 1 && $chk_con_type=='JOINT_VENTURE' && $numchk3 > 0)
			{
				$pdf->SetXY(36,$row);
				$detail=iconv('UTF-8','windows-874',"- $fulldesc ".number_format($payAdviser,2));
				$pdf->MultiCell(60,3,$detail,0,'L',0); //รายละเอียดการรับชำระ
				$row += 5.5;
			}
			
			$pdf->SetXY(36,$row);
			$detail=iconv('UTF-8','windows-874',"============================");
			$pdf->MultiCell(60,3,$detail,0,'L',0); //รายละเอียดการรับชำระ
			$row += 3.8;
			
			$pdf->SetXY(36,$row);
			$detail=iconv('UTF-8','windows-874',"- ดอกเบี้ยคงเหลือ ".number_format($LeftInterest,2));
			$pdf->MultiCell(60,3,$detail,0,'L',0); //รายละเอียดการรับชำระ
			$row += 5.5;
			
			$pdf->SetXY(36,$row);
			$detail=iconv('UTF-8','windows-874',"- เงินต้นคงเหลือ ".number_format($LeftPrinciple,2));
			$pdf->MultiCell(60,3,$detail,0,'L',0); //รายละเอียดการรับชำระ
			$row += 8;
		// }
		//####### จบการแจกแจงรายการจ่าย
	}
}


//##########################รวมด้านล่าง
$pdf->SetXY(95,110);
$amount=iconv('UTF-8','windows-874',number_format($sum_netAmt,2));
$pdf->MultiCell(24,3,$amount,0,'R',0); //จำนวนเงิน

$pdf->SetXY(122,110);
if($sum_vatAmt > 0){$vat=iconv('UTF-8','windows-874',number_format($sum_vatAmt,2));}
else{$vat=iconv('UTF-8','windows-874','-');}
$pdf->MultiCell(23,3,$vat,0,'C',0); //รวมภาษีมูลค่าเพิ่ม

$pdf->SetXY(145,110);
if($sum_whtAmt > 0){$vat2=iconv('UTF-8','windows-874',number_format($sum_whtAmt,2));}
else{$vat2=iconv('UTF-8','windows-874','-');}
$pdf->MultiCell(28,3,$vat2,0,'C',0); //รวมภาษีหัก ณ ที่จ่าย

$pdf->SetXY(170,110);
$total=iconv('UTF-8','windows-874',number_format($sum_debtAmt,2));
$pdf->MultiCell(25,3,$total,0,'R',0); //รวมทั้งหมด


//##################### แสดงข้อความ เอกสารเป็นชุด สีแดง

$sqldoc = pg_query("select \"thcap_receiptIDTotaxinvoiceID\"('$receiptID')");
$redoc = pg_fetch_result($sqldoc,0);
if(!empty($redoc) || $redoc != "" )
{ // ถ้ามีเลขที่ใบกำกับภาษี
	// ตรวจสอบก่อนว่า ใบกำกับภาษีดังกล่าวถูกยกเลิกไปแล้วหรือยัง
	$qry_taxcancel = pg_query("SELECT * FROM thcap_temp_taxinvoice_otherpay_cancel where \"taxinvoiceID\" = '$redoc'");
	$rows_taxcancel = pg_num_rows($qry_taxcancel);
	IF($rows_taxcancel > 0)
	{
		$taxtxtcancel = " ( ถูกยกเลิก )";
	}	
	
	$textdocdetail = " *เอกสารออกเป็นชุด   อ้างอิงใบกำกับภาษีเลขที่  ".$redoc.$taxtxtcancel;
	$pdf->SetFont('AngsanaNew','B',12);
	$pdf->SetTextColor(0); 
	$txtdoc=iconv('UTF-8','windows-874',$textdocdetail);
	$pdf->Text(19,119.5,$txtdoc);
	$txtline=iconv('UTF-8','windows-874',"________________");
	$pdf->Text(20,119.4,$txtline);
}

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetTextColor(0); 

//##########################อ้างอิงใบภาษีหัก ณ ที่จ่ายเลขที่

$refreciepsql = pg_query("SELECT \"whtRef\" FROM thcap_v_receipt_details where \"receiptID\" = '$receiptID'" );
$numchk6=pg_num_rows($refreciepsql);
if($numchk6==0){ //แสดงว่าใบเสร็จถูกยกเลิกแล้ว 
	$refreciepsql = pg_query("SELECT \"whtRef\" FROM thcap_v_receipt_details_cancel where \"receiptID\" = '$receiptID'" );
}
$refreciepresult = pg_fetch_array($refreciepsql);

$numvat=iconv('UTF-8','windows-874',$refreciepresult['whtRef']);
$pdf->Text(70,124.5,$numvat); 

//##########################วันที่ออกใบเสร็จรับเงิน
$nowtext=iconv('UTF-8','windows-874',$nowtext);
$pdf->Text(55,130,$nowtext);

//##########################ยอดค้างชำระคงเหลือที่จะครบกำหนด วันที่
//if($conType == "HP" || $conType == "FL" || $conType == "OL"){$date=iconv('UTF-8','windows-874',"------");}
if($chk_con_type == "HIRE_PURCHASE" || $chk_con_type == "LEASING"){$date=iconv('UTF-8','windows-874',"------");}
else{$date=iconv('UTF-8','windows-874',$nextDueDate_text);}
$pdf->Text(82,142,$date);

//##########################จำนวนเงิน
//if($conType == "HP" || $conType == "FL" || $conType == "OL"){$money=iconv('UTF-8','windows-874',"------");}
if($chk_con_type == "HIRE_PURCHASE" || $chk_con_type == "LEASING"){$money=iconv('UTF-8','windows-874',"------");}
else{$money=iconv('UTF-8','windows-874',number_format($nextDueAmt,2));}
$pdf->Text(40,148,$money);

//##########################รับชำระเป็น

//หาช่องทางการรับชำระ
$qrychannel=pg_query("select \"byChannelDetails\" from thcap_temp_receipt_details where \"receiptID\"='$receiptID'");
list($byChannelDetails)=pg_fetch_array($qrychannel);

$pdf->SetFont('AngsanaNew','B',14);
$pdf->SetXY(115.5,126.5);
$co=iconv('UTF-8','windows-874',"X");
$pdf->MultiCell(5,5,$co,0,'L',0);

$pdf->SetFont('AngsanaNew','',14);
$pdf->SetXY(120.5,126.5);
$co=iconv('UTF-8','windows-874',"$byChannelDetails");
$pdf->MultiCell(80,5,$co,0,'L',0);


if($nameco != ""){
	$pdf->SetXY(20,168);
	$co=iconv('UTF-8','windows-874',"ผู้กู้ร่วม");
	$pdf->MultiCell(15,3,$co,0,'L',0);
	
	$pdf->SetXY(30,168);
	$nameco=iconv('UTF-8','windows-874',$nameco);
	$pdf->MultiCell(150,3,$nameco,0,'L',0); //รายชื่อผู้กู้ร่วม
}
	

$pdf->SetXY(135,186);
$co=iconv('UTF-8','windows-874',$fullname);
$pdf->MultiCell(50,2.6,$co,0,'C',0); //ผู้รับเงิน

//###########กรุณาส่ง
$pdf->SetXY(50,240);
$name=iconv('UTF-8','windows-874',$name3);
$pdf->MultiCell(150,3,$name,0,'L',0); //ชื่อลูกค้า

$pdf->SetXY(50,245);
$address2=iconv('UTF-8','windows-874',$addresssend);
$pdf->MultiCell(60,5,$address2,0,'L',0); //ที่อยู่

$sum_netAmt="";
$sum_vatAmt="";
$sum_whtAmt="";
$sum_debtAmt="";
}  
/*
if (!file_exists($_SESSION["session_path_save_pdf"].$rec_id.".pdf")) { //check file exists
$pdf->Output($_SESSION["session_path_save_pdf"].$rec_id.".pdf", "F"); // save pdf
}
*/
$pdf->Output(); //open pdf
?>
