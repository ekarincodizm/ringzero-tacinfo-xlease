<?php
session_start();
include('../../config/config.php');
include('../function/checknull.php');

$add_date = nowDateTime();
$user_id = $_SESSION["av_iduser"];

$method=pg_escape_string($_POST['method']);
$contractID = trim(pg_escape_string($_POST['conid'])); // เลขที่สัญญา
$conType = trim(pg_escape_string($_POST['contype'])); // ประเภทสัญญา
$conCompany = trim(pg_escape_string($_POST['conCompany'])); // ชื่อบริษัท
$case_owners = trim(pg_escape_string($_POST['case_owners'])); // เจ้าของเคส
$conLoanAmt = trim(pg_escape_string($_POST['conloanamt'])); // จำนวนเงินกู้
$conguaranteeamt = checknull(trim(pg_escape_string($_POST['conguaranteeamt'])));  // จำนวนเงินค้ำประกัน
$conGuaranteeAmtForCredit = checknull(trim(pg_escape_string($_POST['conGuaranteeAmtForCredit'])));  // จำนวนเงินค้ำประกัน (สัญญาวงเงิน)
$conLoanIniRate = trim(pg_escape_string($_POST['conLoanIniRate'])); // % ดอกเบี้ยที่ตกลงต่อปี
$conInvoicePeriod = trim(pg_escape_string($_POST['conInvoicePeriod'])); // จำนวนวันที่ให้ส่งใบแจ้งหนี้ก่อนครบกำหนด
$conTerm = trim(pg_escape_string($_POST['conTerm'])); // จำนวนงวด
$conMinPay = trim(pg_escape_string($_POST['conMinPay'])); // ยอดผ่อนขั้นต่ำ
$conExtRentMinPay = trim(pg_escape_string($_POST['conExtRentMinPay'])); // เงินค่าเช่าขั้นต่ำ
$conPenaltyRate = trim(pg_escape_string($_POST['conPenaltyRate'])); // ค่าติดทางทวงถาม กรณีไม่จ่าย
$conDate = trim(pg_escape_string($_POST['conDate'])); // วันที่ทำสัญญากู้
$conDate1=checknull($conDate);
$conStartDate = trim(pg_escape_string($_POST['conStartDate'])); // วันที่รับเงินที่ขอกู้
//$conEndDate = trim(pg_escape_string($_POST['conEndDate']));
$conFirstDue = trim(pg_escape_string($_POST['conFirstDue'])); // วันที่ครบกำหนดชำระงวดแรก
$conRepeatDueDay = trim(pg_escape_string($_POST['conRepeatDueDay'])); // จ่ายทุกๆวันที่
$conFreeDate = trim(pg_escape_string($_POST['conFreeDate']));
$conClosedFee = trim(pg_escape_string($_POST['conClosedFee']));
$conCredit = trim(pg_escape_string($_POST['conCredit']));
$conCreditRef = trim(pg_escape_string($_POST['conCreditRef'])); // สัญญานี้ใช้วงเงินไหน
$downPayment = trim(pg_escape_string($_POST['downPayment'])); // เงินดาวน์
$downPaymentVat = trim(pg_escape_string($_POST['downPaymentVat'])); // Vat ของ เงินดาวน์
$downPaymentChoice = pg_escape_string($_POST['downPaymentChoice']); // ชำระเงินดาวน์ให้ใคร
$all_pick_itm = $_POST['all_pick_itm'];	//array รายการสินค้า
$sum_pick_itm = sizeof($all_pick_itm);

$conFinAmtExtVat = trim(pg_escape_string($_POST['conFinAmtExtVat'])); // ยอดจัด/ยอดลงทุน (ก่อนภาษี)
$conFineRate = checknull(trim(pg_escape_string($_POST['conFineRate']))); // % เบี้ยปรับผิดนัด
$conFacFee = trim(pg_escape_string($_POST['conFacFee'])); // ค่าธรรมเนียมรวมในตั๋ว
$conResidualValue = trim(pg_escape_string($_POST['conResidualValue'])); // ค่าซาก

$selectSubtype = pg_escape_string($_POST['selectSubtype']); // ประเภทสัญญาย่อย

$selectBillFA = $_POST['selectBillFA']; // บิลที่ผูกกับสัญญา

$calculateticket = pg_escape_string($_POST['select']); // การคำนวณยอดตั่ว 0=ก่อนหักดอกเบี้ย,1=หลังหักดอกเบี้ย

$conResidualValueIncVat = pg_escape_string($_POST['conResidualValueIncVat']); // ค่าซากรวมภาษีมูลค่าเพิ่ม
$conLeaseIsForceBuyResidue = pg_escape_string($_POST['conLeaseIsForceBuyResidue']); // บังคับซื้อซาก
$conLeaseBaseFinanceForCal = pg_escape_string($_POST['conLeaseBaseFinanceForCal']); // ยอดจัดที่ใช้ในการคิดดอกเบี้ย

$conPLIniRate = pg_escape_string($_POST['conPLIniRate']); // ค่าธรรมเนียมการใช้วงเงินสินเชื่อส่วนบุคคล

list($case_owners_id, $case_owners_name) = explode('#',$case_owners); // รหัส และ ชื่อ เจ้าของเคส
$case_owners_id_checknull = checknull($case_owners_id); // เช็คค่าว่างของรหัสพนักงานเจ้าของเคส

$date = nowDateTime();

$status = 0;
pg_query("BEGIN");

//แก้ไขที่อยู่สัญญา
$edit_addr_chkbx = trim(pg_escape_string($_POST['edit_addr_chkbx']));
$f_room = trim(pg_escape_string($_POST['f_room']));
$f_floor = trim(pg_escape_string($_POST['f_floor']));
$f_no = trim(pg_escape_string($_POST['f_no']));
$f_subno = trim(pg_escape_string($_POST['f_subno']));
$f_ban = trim(pg_escape_string($_POST['f_ban']));
$f_building = trim(pg_escape_string($_POST['f_building']));
$f_soi = trim(pg_escape_string($_POST['f_soi']));
$f_rd = trim(pg_escape_string($_POST['f_rd']));
$f_tum = trim(pg_escape_string($_POST['f_tum']));
$f_aum = trim(pg_escape_string($_POST['f_aum']));
$A_PRO1 = trim(pg_escape_string($_POST['A_PRO']));
$f_post = trim(pg_escape_string($_POST['f_post']));

// ชื่อประเภทสินเชื่อแบบเต็ม
$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$conType') ");
$chk_con_type = pg_fetch_result($qry_chk_con_type,0);

if($conTerm != "")
{
	$conTerm_temp = $conTerm; // จำนวนงวดที่จะใช้ในตาราง temp
}

$maincus = trim($_POST['main']);
list($mainID,$mainname) = explode('#',$maincus); // ผู้กู้หลัก

$cusadd = trim($_POST['cusadd']);
list($cusaddID,$cusaddname) = explode('#',$cusadd);
$cusaddID;
$joincus = $_POST['join']; // ผู้กู้ร่วม
$guarantorCus = $_POST['guarantor']; // ผู้ค้ำประกัน

$textCus = "{}"; // ประเภทลูกค้า และ รหัสลูกค้า
$conCusFullnameArray = "{}"; // ชื่อเต็มของลูกค้าทั้งหมด  {รหัสลูกค้า , ชื่อเต็มลูกค้า}
$conCusAddressArray = "{}"; // ที่อยู่ของลูกค้าทั้งหมด {รหัสลูกค้า , ที่อยู่}

// หาข้อมูลที่อยู่
$seadd = pg_query("SELECT \"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",
\"A_ROOM\" as \"room\",\"A_FLOOR\" as \"LiveFloor\",\"A_VILLAGE\" as \"Village\",\"A_BUILDING\" as \"Building\"
FROM \"Fa1\" where \"CusID\" = '$cusaddID' ");			
$numadd=pg_num_rows($seadd);

if($numadd==0){ // กรณีเป็นนิติบุคคล
	$seadd=pg_query("select \"HomeNumber\" as \"A_NO\",room,\"LiveFloor\",\"Moo\" as \"A_SUBNO\",
	\"Building\",\"Village\",\"Lane\" as \"A_SOI\",\"Road\" as \"A_RD\",\"District\" as \"A_TUM\",
	\"State\" as \"A_AUM\",g.\"proName\" as \"A_PRO\",\"Postal_code\" as \"A_POST\"
	from th_corp_adds 
	LEFT JOIN nw_province g ON th_corp_adds.\"ProvinceID\" = g.\"proID\"
	where \"corpID\"::text='$cusaddID'");
}

$sere = pg_fetch_array($seadd);	
			
$A_NO = trim($sere['A_NO']);
$A_SUBNO = trim($sere['A_SUBNO']);
$A_SOI = trim($sere['A_SOI']);
$A_RD = trim($sere['A_RD']);
$A_TUM = trim($sere['A_TUM']);
$A_AUM = trim($sere['A_AUM']);
$A_PRO = trim($sere['A_PRO']);
$A_POST = trim($sere['A_POST']);


$room = trim($sere['room']); //ห้อง
$LiveFloor = trim($sere['LiveFloor']); //ชั้น
$Building = trim($sere['Building']); //อาคาร/สถานที่
$Village = trim($sere['Village']); //หมู่บ้าน

if($edit_addr_chkbx!="")
{
	$A_NO = $f_no;
	$A_SUBNO = $f_subno;
	$A_SOI = $f_soi;
	$A_RD = $f_rd;
	$A_TUM = $f_tum;
	$A_AUM = $f_aum;
	$A_PRO = $A_PRO1;
	$A_POST = $f_post;
	
	$room = $f_room;
	$LiveFloor = $f_floor;
	$Building = $f_building;
	$Village = $f_ban;
}

$A_NO = checknull($A_NO);
$A_SUBNO = checknull($A_SUBNO);
$A_SOI = checknull($A_SOI);
$A_RD = checknull($A_RD);
$A_TUM = checknull($A_TUM);
$A_AUM = checknull($A_AUM);
$A_PRO = checknull($A_PRO);
$A_POST = checknull($A_POST);

$A_ROOM = checknull($room);
$A_FLOOR = checknull($LiveFloor);
$A_BUILDING = checknull($Building);
$A_VILLAGE = checknull($Village);

if($mainID != "")
{ // ใส่ผู้กู้หลักลงไปก่อน
	$qry_chkName = pg_query("select * from \"VSearchCusCorp\" where \"CusID\" = '$mainID' "); // ตรวจสอบก่อนว่ามีลูกค้าอยู่ในระบบแล้วหรือยัง
	$row_chkName = pg_num_rows($qry_chkName);
	if($row_chkName > 0) // ถ้ามีลูกค้าในระบบแล้ว
	{
		// เพิ่มผู้กู้หลักเข้าไปใน array โดยใช้ function จาก postgres
		$qry_textCus = pg_query("select ta_array_add('$textCus', '0', '$mainID')");
		$res_textCus = pg_fetch_array($qry_textCus);
		list($textCus) = $res_textCus;
		
		$conCusNameMain = pg_fetch_result($qry_chkName,"full_name"); // ชื่อผู้กู้หลัก ณ ขณะนั้น
		$conCusAddress = pg_fetch_result($qry_chkName,"full_address"); // ที่อยู่ผู้กู้หลัก ณ ขณะนั้น
		
		// เพิ่มชื่อเต็มผู้กู้หลักเข้าไปใน array โดยใช้ function จาก postgres
		$qry_conCusFullnameArray = pg_query("select ta_array_add('$conCusFullnameArray', '$mainID', '$conCusNameMain')");
		$conCusFullnameArray = pg_fetch_result($qry_conCusFullnameArray,0);
						
		// เพิ่มที่อยู่ผู้กู้หลักเข้าไปใน array โดยใช้ function จาก postgres
		$conCusAddress=str_replace(",","&sbquo;",$conCusAddress); //แทนที่ , ด้วย characters for HTML เพื่อให้ array มองเห็น , เป็นตัวอักษรตัวหนึ่งแทนการมองเห็นเป็น array
		$qry_conCusAddressArray = pg_query("select ta_array_add('$conCusAddressArray', '$mainID', '$conCusAddress')");
		$conCusAddressArray = pg_fetch_result($qry_conCusAddressArray,0);
	}
	else // ถ้ายังไม่มีลูกค้าอยู่ในระบบ
	{
		$status++;
		echo "ไม่พบชื่อลูกค้าในระบบ กรุณาทำรายการใหม่อีกครั้ง!!<br>";
	}
}

for($i=0;$i<sizeof($joincus);$i++) // หา ประเภทลูกค้า และ รหัสผู้กู้ร่วม
{
	list($joinID,$joinname) = explode('#',$joincus[$i]);
	$joinID = checknull($joinID);
	if($joinID == "null")
	{
		continue;
	}
	else
	{			
		$joinID = str_replace("'","",$joinID); // ตัดเครื่องหมาย ' ออกก่อน
		
		$qry_chkName = pg_query("select * from \"VSearchCusCorp\" where \"CusID\" = '$joinID' "); // ตรวจสอบก่อนว่ามีลูกค้าอยู่ในระบบแล้วหรือยัง
		$row_chkName = pg_num_rows($qry_chkName);
		if($row_chkName > 0) // ถ้ามีลูกค้าในระบบแล้ว
		{
			// เพิ่มผู้กู้ร่วมเข้าไปใน array โดยใช้ function จาก postgres
			$qry_textCus = pg_query("select ta_array_add('$textCus', '1', '$joinID', '1')"); // parameter '1' ตัวหลังสุดคือบอกว่าจะยอมใช้เพิ่มผู้กู้ร่วมได้หลายคน
			$res_textCus = pg_fetch_array($qry_textCus);
			list($textCus) = $res_textCus;
			
			$conCusNameJoin = pg_fetch_result($qry_chkName,"full_name"); // ชื่อผู้กู้ร่วม ณ วันทำสัญญา
			$conCusAddress = pg_fetch_result($qry_chkName,"full_address"); // ที่อยู่ผู้กู้ร่วม ณ ขณะนั้น
			
			// เพิ่มชื่อเต็มผู้กู้ร่วมเข้าไปใน array โดยใช้ function จาก postgres
			$qry_conCusFullnameArray = pg_query("select ta_array_add('$conCusFullnameArray', '$joinID', '$conCusNameJoin')");
			$conCusFullnameArray = pg_fetch_result($qry_conCusFullnameArray,0);
											
			// เพิ่มที่อยู่ผู้กู้ร่วมเข้าไปใน array โดยใช้ function จาก postgres
			$conCusAddress=str_replace(",","&sbquo;",$conCusAddress); //แทนที่ , ด้วย characters for HTML เพื่อให้ array มองเห็น , เป็นตัวอักษรตัวหนึ่งแทนการมองเห็นเป็น array
			$qry_conCusAddressArray = pg_query("select ta_array_add('$conCusAddressArray', '$joinID', '$conCusAddress')");
			$conCusAddressArray = pg_fetch_result($qry_conCusAddressArray,0);
		}
		else // ถ้ายังไม่มีลูกค้าอยู่ในระบบ
		{
			$status++;
			echo "ไม่พบชื่อลูกค้าในระบบ กรุณาทำรายการใหม่อีกครั้ง!!<br>";
		}
	}
}

for($i=0;$i<sizeof($guarantorCus);$i++) // หา ประเภทลูกค้า และ รหัสผู้ค้ำประกัน
{
	list($guarantorID,$guarantorName) = explode('#',$guarantorCus[$i]);
	$guarantorID = checknull($guarantorID);
	//echo $guarantorID;
	if($guarantorID == "null")
	{
		continue;
	}
	else
	{			
		$guarantorID = str_replace("'","",$guarantorID); // ตัดเครื่องหมาย ' ออกก่อน
		
		$qry_chkName = pg_query("select * from \"VSearchCusCorp\" where \"CusID\" = '$guarantorID' "); // ตรวจสอบก่อนว่ามีลูกค้าอยู่ในระบบแล้วหรือยัง
		$row_chkName = pg_num_rows($qry_chkName);
		if($row_chkName > 0) // ถ้ามีลูกค้าในระบบแล้ว
		{
			// เพิ่มผู้ค้ำประกันเข้าไปใน array โดยใช้ function จาก postgres
			$qry_textCus = pg_query("select ta_array_add('$textCus', '2', '$guarantorID', '1')"); // parameter '1' ตัวหลังสุดคือบอกว่าจะยอมใช้เพิ่มผู้ค้ำประกันได้หลายคน
			$res_textCus = pg_fetch_array($qry_textCus);
			list($textCus) = $res_textCus;
			
			$conCusNameGuarantor = pg_fetch_result($qry_chkName,"full_name"); // ชื่อผู้กู้ค้ำประกัน ณ วันทำสัญญา
			$conCusAddress = pg_fetch_result($qry_chkName,"full_address"); // ที่อยู่ผู้ค้ำประกัน ณ ขณะนั้น
			
			// เพิ่มชื่อเต็มผู้ค้ำประกันเข้าไปใน array โดยใช้ function จาก postgres
			$qry_conCusFullnameArray = pg_query("select ta_array_add('$conCusFullnameArray', '$guarantorID', '$conCusNameGuarantor')");
			$conCusFullnameArray = pg_fetch_result($qry_conCusFullnameArray,0);
			
			// เพิ่มที่อยู่ผู้ค้ำประกันเข้าไปใน array โดยใช้ function จาก postgres
			$conCusAddress=str_replace(",","&sbquo;",$conCusAddress); //แทนที่ , ด้วย characters for HTML เพื่อให้ array มองเห็น , เป็นตัวอักษรตัวหนึ่งแทนการมองเห็นเป็น array
			$qry_conCusAddressArray = pg_query("select ta_array_add('$conCusAddressArray', '$guarantorID', '$conCusAddress')");
			$conCusAddressArray = pg_fetch_result($qry_conCusAddressArray,0);
		}
		else // ถ้ายังไม่มีลูกค้าอยู่ในระบบ
		{
			$status++;
			echo "ไม่พบชื่อลูกค้าในระบบ กรุณาทำรายการใหม่อีกครั้ง!!<br>";
		}
	}
}

if($textCus == "{}")
{
	$textCus = "null";
}
else
{
	$textCus = "'$textCus'";
}

if($conCreditRef != "") // ถ้ามีการระบุว่าใช้วงเงินไหน (จากเมนู "(THCAP) ผูกสัญญาเงินกู้ชั่วคราว")
{
	$qry_conCreditRef = pg_query("select * from public.\"thcap_contract\" where \"contractID\" = '$conCreditRef' and \"conCredit\" is not null ");
	$numrows_conCreditRef = pg_num_rows($qry_conCreditRef);
	if($numrows_conCreditRef == 1)
	{
		while($res_conCreditRef = pg_fetch_array($qry_conCreditRef))
		{
			$conCreditRefID = $res_conCreditRef["contractID"];
			//$conCreditRefCredit = $res_conCreditRef["conCredit"]; // วงเงินสินเชื่อ
		}
		
		$qry_conCreditRefValue = pg_query("select ta_array_add('{}', '$conCreditRefID', '$conLoanAmt')");
		$res_conCreditRefValue = pg_fetch_array($qry_conCreditRefValue);
		list($conCreditRefValue) = $res_conCreditRefValue;
		
		$conCreditRefValue = "'$conCreditRefValue'";
	}
	else
	{
		$status++;
		echo "ไม่พบวงเงินที่จะใช้<br>";
	}
}
else
{
	$conCreditRefValue = "null";
}

if($chk_con_type == "FACTORING" && $conCredit == "")
{ // ถ้าเป็นสัญญาประเภท "FACTORING" และเป็นสัญญาเงินกู้ ไม่ใช่สัญญาวงเงิน ให่ใช้ function

	$conMinPay = checknull($conMinPay); // ยอดผ่อนขั้นต่ำ

	//--- ตารางการผ่อนชำระ
	if($conTerm_temp != "")
	{
		$tableMinPay = '{}';
		
		for($i=1; $i<=$conTerm_temp; $i++)
		{
			$genDate[$i] = pg_escape_string($_POST["genDate$i"]); // วันครบกำหนดชำระ
			$genMinPay[$i] = pg_escape_string($_POST["genMinPay$i"]); // ยอดจ่ายขั้นต่ำ
			
			$qry_tableMinPay = pg_query("select ta_array_add('$tableMinPay', '$genDate[$i]', '$genMinPay[$i]')");
			$tableMinPay = pg_fetch_result($qry_tableMinPay,0);
			
			if($i > 1)
			{ // เช็คว่า ยอดผ่อนแต่ละงวด จำนวนเงินเท่ากันหรือไม่
				if($genMinPay[$i] != $genMinPay[$i-1])
				{ // ถ้ายอดผ่อนแต่ละงวดไม่เท่ากัน จะกำหนดให้ ยอดผ่อนขั้นต่ำหลัก เปลี่ยนเป็น 0 ทันที
					$conMinPay = "'0'";
				}
			}
		}
		
		$tableMinPay = "'".$tableMinPay."'";
	}
	else
	{
		$tableMinPay = "null";
	}
	
	// บิลที่ผูกกับสัญญา
	$textBillFA = "{";
	for($i=0;$i<sizeof($selectBillFA);$i++)
	{
		list($prebillID) = explode('#',$selectBillFA[$i]);
		$prebillID = checknull($prebillID);
		if($prebillID == "null")
		{
			continue;
		}
		else
		{			
			$prebillID = str_replace("'","",$prebillID); // ตัดเครื่องหมาย ' ออกก่อน
			
			if($textBillFA == "{")
			{
				$textBillFA = $textBillFA.$prebillID;
			}
			else
			{
				$textBillFA = $textBillFA.",".$prebillID;
			}
		}
	}
	$textBillFA = "'".$textBillFA."}'";
	
	if($textBillFA == "{}")
	{
		$textBillFA = "NULL";
	}
	
	$contractID = checknull($contractID);
	$conType = checknull($conType);
	$conCompany = checknull($conCompany);
	$conLoanAmt = checknull($conLoanAmt); // จำนวนเงินกู้
	$conLoanIniRate = checknull($conLoanIniRate);
	$conInvoicePeriod = checknull($conInvoicePeriod); // จำนวนวันที่ให้ส่งใบแจ้งหนี้ก่อนครบกำหนด
	$conTerm = checknull($conTerm); // จำนวนงวด
	$conPenaltyRate =checknull($conPenaltyRate);
	$conDate = checknull($conDate);
	$conStartDate = checknull($conStartDate);
	$conFirstDue = checknull($conFirstDue); // วันที่ครบกำหนดชำระงวดแรก
	$conRepeatDueDay = checknull($conRepeatDueDay); // จ่ายทุกๆวันที่
	$conFacFee = checknull($conFacFee);
	
	// เข้า function เพื่อทำการขอผูกสัญญา
	$qry_FACTORING = "select thcap_process_preapprove_factoring($contractID, $conType, $conCompany, $conLoanAmt, $conguaranteeamt,
							$conFacFee, $conLoanIniRate, $conInvoicePeriod, $conTerm, $conMinPay, $conPenaltyRate, $conDate, $conStartDate,
							$conFirstDue, $conRepeatDueDay, $conCreditRefValue, $textCus, $A_NO, $A_SUBNO, $A_SOI, $A_RD, $A_TUM, $A_AUM, $A_PRO,
							$A_POST, $A_BUILDING, $A_ROOM, $A_FLOOR, $A_VILLAGE, $tableMinPay, '$user_id', $textBillFA, $conGuaranteeAmtForCredit, $conFineRate, '$calculateticket')";

	$query_FACTORING = pg_query($qry_FACTORING);
	if($query_FACTORING)
	{
		$create_ref_contractID = pg_fetch_result($query_FACTORING,0); // PK id ของรายการที่ขอผูกสัญญา
	}
	else
	{
		$status++;
		echo $qry_FACTORING;
	}
}
else
{
	if($chk_con_type == "GUARANTEED_INVESTMENT" || $chk_con_type == "JOINT_VENTURE" || $chk_con_type == "SALE_ON_CONSIGNMENT")
	{
		$conFinAmtExtVat = $conLoanAmt;
	}

	if($conTerm == "1")
	{
		if(substr($conFirstDue,8,2) > 28)
		{
			$conRepeatDueDay = "28";
		}
	}

	$contractID = checknull($contractID);
	$conType = checknull($conType);
	$conCompany = checknull($conCompany);
	$conLoanAmt = checknull($conLoanAmt); // จำนวนเงินกู้
	$conLoanIniRate = checknull($conLoanIniRate);
	$conInvoicePeriod = checknull($conInvoicePeriod); // จำนวนวันที่ให้ส่งใบแจ้งหนี้ก่อนครบกำหนด
	$conTerm = checknull($conTerm); // จำนวนงวด
	$conMinPay = checknull($conMinPay); // ยอดผ่อนขั้นต่ำ
	$conExtRentMinPay = checknull($conExtRentMinPay); // ยอดผ่อนขั้นต่ำ
	$conPenaltyRate =checknull($conPenaltyRate);
	$conDate = checknull($conDate);
	$conStartDate = checknull($conStartDate);
	//$conEndDate =checknull($conEndDate);
	$conFirstDue = checknull($conFirstDue); // วันที่ครบกำหนดชำระงวดแรก
	$conRepeatDueDay = checknull($conRepeatDueDay); // จ่ายทุกๆวันที่
	$conFreeDate = checknull($conFreeDate);
	$conClosedFee = checknull($conClosedFee);
	$conCredit = checknull($conCredit);
	$downPayment = checknull($downPayment); // เงินดาวน์
	$downPaymentVat = checknull($downPaymentVat); // Vat ของ เงินดาวน์
	$conResidualValue = checknull($conResidualValue); // ค่าซาก

	$conFinAmtExtVat = checknull($conFinAmtExtVat);
	//$conFineRate = checknull($conFineRate);
	
	$selectSubtype = checknull($selectSubtype); // ประเภทสัญญาย่อย
	
	$conResidualValueIncVat = checknull($conResidualValueIncVat); // ค่าซากรวมภาษีมูลค่าเพิ่ม
	$conLeaseIsForceBuyResidue = checknull($conLeaseIsForceBuyResidue); // บังคับซื้อซาก
	$conLeaseBaseFinanceForCal = checknull($conLeaseBaseFinanceForCal); // ยอดจัดที่ใช้ในการคิดดอกเบี้ย
	
	$conPLIniRate = checknull($conPLIniRate); // ค่าธรรมเนียมการใช้วงเงินสินเชื่อส่วนบุคคล

	// กำหนดว่าขำระเงินดาวน์ให้ใคร
	if($downPaymentChoice == "Finance")
	{ // ถ้าชำระเงินดาวน์ให้ไฟแนนซ์
		$conDown = ',"conDownToFinance" ,"conDownToFinanceVat"';
		$downPayment = ",$downPayment ,$downPaymentVat";
	}
	elseif($downPaymentChoice == "Dealer")
	{ // ถ้าชำระเงินดาวน์ให้ผู้ขาย
		$conDown = ',"conDownToDealer"';
		$downPayment = ",$downPayment";
	}
	else
	{ // ถ้าไม่ได้ระบุว่าชำระเงินดาวน์ให้ใคร
		$conDown = "";
		$downPayment = "";
	}

	if($method=="addcredit"){
		$txtfield=',"conCredit"';
		$txtadd=",$conCredit";
	}else{
		$txtfield="";
		$txtadd="";
	}

	/*
	$sql1 = "INSERT INTO \"thcap_ContactCus\"(\"contractID\", \"CusState\", \"CusID\")
					VALUES ($contractID,'0','$mainID')";

	$query1 = pg_query($sql1);


	if($query1){}else{ $status++; echo $sql1;}
	*/

	if($conCreditRef != "") // ถ้ามีการระบุว่าใช้วงเงินไหน (จากเมนู "(THCAP) ผูกสัญญาเงินกู้ชั่วคราว")
	{
		$qry_conCreditRef = pg_query("select * from public.\"thcap_contract\" where \"contractID\" = '$conCreditRef' and \"conCredit\" is not null ");
		$numrows_conCreditRef = pg_num_rows($qry_conCreditRef);
		if($numrows_conCreditRef == 1)
		{
			while($res_conCreditRef = pg_fetch_array($qry_conCreditRef))
			{
				$conCreditRefID = $res_conCreditRef["contractID"];
				//$conCreditRefCredit = $res_conCreditRef["conCredit"]; // วงเงินสินเชื่อ
			}
			
			$qry_conCreditRefValue = pg_query("select ta_array_add('{}', '$conCreditRefID', $conLoanAmt)");
			$res_conCreditRefValue = pg_fetch_array($qry_conCreditRefValue);
			list($conCreditRefValue) = $res_conCreditRefValue;
			
			$conCreditRefValue = "'$conCreditRefValue'";
		}
		else
		{
			$status++;
			echo "ไม่พบวงเงินที่จะใช้<br>";
		}
	}
	else
	{
		$conCreditRefValue = "null";
	}
	
	$conCusFullnameArray = checknull($conCusFullnameArray); // ชื่อเต็มผู้กู้หลัก ณ วันทำสัญญา
	$conCusAddressArray = checknull($conCusAddressArray); // ชื่อเต็มผู้กู้ร่วม ณ วันทำสัญญา
	
	// เพิ่มข้อมูลที่อยู่สัญญา
	$sql4 = "INSERT INTO \"thcap_addrContractID_temp\"(
            \"contractID\", \"addsType\", edittime, \"A_NO\", \"A_SUBNO\", 
            \"A_SOI\", \"A_RD\", 
            \"A_TUM\", \"A_AUM\", \"A_PRO\",\"A_POST\", \"addUser\", \"addStamp\", \"statusApp\", 
            \"A_BUILDING\",\"A_ROOM\",\"A_FLOOR\",\"A_VILLAGE\")
    VALUES ($contractID, '3', '0', $A_NO, $A_SUBNO, 
			$A_SOI, $A_RD , $A_TUM, $A_AUM , $A_PRO, $A_POST, '$user_id', 
            '$add_date','5', $A_BUILDING,$A_ROOM,$A_FLOOR,$A_VILLAGE)";
		
	$query4 = pg_query($sql4);
		if($query4){}else{ $status++; echo $sql4;}
		
		
	if($sum_pick_itm!=0)
	{
		foreach($all_pick_itm as $val)
		{
			$itm_data = split(",",$val);
			$rcid = $itm_data[0];
			$asset_id = $itm_data[1];
			$isSameContract = $itm_data[2];
			$addressid = $itm_data[3];
			$asset_address_id = "";
			if($isSameContract=="0")
			{
				$asset_address_id = "'".$addressid."'";
			}
			else if($isSameContract=="1")
			{
				$q_chk = str_replace("=null"," is null","select \"asset_addressID\" from \"thcap_contract_asset_address\" where \"Room\"=$A_ROOM and \"Floor\"=$A_FLOOR and \"HomeNumber\"=$A_NO and \"Building\"=$A_BUILDING and \"Moo\"=$A_SUBNO and \"Village\"=$A_VILLAGE and \"Soi\"=$A_SOI and \"Road\"=$A_RD and \"Tambon\"=$A_TUM and \"District\"=$A_AUM and \"Province\"=$A_PRO and \"Zipcode\"=$A_POST and \"customerID\"='$mainID'");
				$qr_chk = pg_query($q_chk);
				if($qr_chk)
				{
					$row_chk = pg_num_rows($qr_chk);
					if($row_chk==0)
					{
						$qr_ins_addr = pg_query("insert into \"thcap_contract_asset_address\"(\"Room\",\"Floor\",\"HomeNumber\",\"Building\",\"Moo\",\"Village\",\"Soi\",\"Road\",\"Tambon\",\"District\",\"Province\",\"Zipcode\",\"customerID\",\"doer\",\"doerStamp\") values($A_ROOM,$A_FLOOR,$A_NO,$A_BUILDING,$A_SUBNO,$A_VILLAGE,$A_SOI,$A_RD,$A_TUM,$A_AUM,$A_PRO,$A_POST,'$mainID','$user_id','$add_date') returning \"asset_addressID\"");
						if($qr_ins_addr)
						{
							$rs_ins_addr = pg_fetch_array($qr_ins_addr);
							$asset_address_id = "'".$rs_ins_addr['asset_addressID']."'";
						}
						else
						{
							$status++;
						}
					}
					else
					{
						$rs_addr = pg_fetch_array($qr_chk);
						$asset_address_id = "'".$rs_addr['asset_addressID']."'";
					}
				}
				else
				{
					$status++;
				}
			}
			else if($isSameContract=="")
			{
				$asset_address_id = "null";
			}
			
			//----- ตรวจสอบก่อนว่า สินทรัพย์ดังกล่าวถูกยกเลิก หรือถูกขอยกเลิกอยู่หรือไม่ -----
				// หารหัส ใบเสร็จ/ใบสั่งซื้อ
				$qry_sAssetID = pg_query("select \"assetID\" from \"thcap_asset_biz_detail\" where \"assetDetailID\" = '$asset_id' ");
				$sAssetID = pg_fetch_result($qry_sAssetID,0);
				
				// ตรวจสอบก่อนว่า สินทรัพย์ดังกล่าวถูกยกเลิก หรือถูกขอยกเลิกอยู่หรือไม่
				$qry_chkAssetCancel = pg_query("select \"Approved\" from \"thcap_asset_cancel\" where \"assetID\" = '$sAssetID' and (\"Approved\" is null or \"Approved\" = 't') ");
				$row_chkAssetCancel = pg_num_rows($qry_chkAssetCancel);
				if($row_chkAssetCancel > 0)
				{ // ถ้ามีการทำรายการยกเลิก
					$chkAssetCancel = pg_fetch_result($qry_chkAssetCancel,0);
					if($chkAssetCancel == "")
					{
						$status++;
						echo "ไม่สามารถทำรายการได้ เนื่องจาก สินทรัพย์รหัส $asset_id ถูกขอยกเลิกอยู่ในขณะนี้<br>";
					}
					else
					{
						$status++;
						echo "ไม่สามารถทำรายการได้ เนื่องจาก สินทรัพย์รหัส $asset_id ถูกยกเลิกไปแล้วในขณะนี้<br>";
					}
				}
			//----- จบการตรวจสอบก่อนว่า สินทรัพย์ดังกล่าวถูกยกเลิก หรือถูกขอยกเลิกอยู่หรือไม่ -----
			
			$q = "insert into \"thcap_contract_asset_temp\"(\"contractID\",\"assetDetailID\",\"doerID\",\"doerStamp\",\"assetAddress\") values($contractID,'$asset_id','$user_id','$date',$asset_address_id)";
			$qr = pg_query($q);
			if(!$qr)
			{
				echo $q;
				$status++;
			}
			
			// กำหนดการ update สถานะสินทรัพย์
			if($conType == "'BH'" || $conType == "'HP'"){$as_status_id = "2";}
			elseif($conType == "'FL'"){$as_status_id = "3";}
			elseif($conType == "'MG'" || $conType == "'JV'" || $conType == "'UF'" || $conType == "'CG'" || $conType == "'SM'"){$as_status_id = "4";}
			else{$status++; echo "ประเภทสัญญา $conType ยังไม่รองรับการกำหนดสถานะสินทรัพย์";}
			
			$q1 = "update \"thcap_asset_biz_detail\" set \"materialisticStatus\" = '2', \"as_status_id\" = '$as_status_id' where \"assetDetailID\"='$asset_id'";
			$qr1 = pg_query($q1);
			if(!$qr1)
			{
				echo $q1;
				$status++;
			}
		}
	}	
		
		
	//--- หาเลข ID ของที่อยู่ใน thcap_addrContractID_temp
		$qry_AddrTempID = pg_query("select max(\"tempID\") as \"tempID\" from public.\"thcap_addrContractID_temp\" where \"contractID\" = $contractID and \"addsType\" = '3' and \"edittime\" = '0' and \"statusApp\" = '5' ");
		$numrows_AddrTempID = pg_num_rows($qry_AddrTempID);
		if($numrows_AddrTempID == 1)
		{
			while($res_AddrTempID = pg_fetch_array($qry_AddrTempID))
			{
				$addrTempID = $res_AddrTempID["tempID"]; // เลข ID ของที่อยู่ใน thcap_addrContractID_temp
			}
		}
		else
		{
			$status++;
			echo "ไม่สามารถหารหัสที่อยู่ได้<br>";
		}
	//--- จบการหาเลข ID ของที่อยู่ใน thcap_addrContractID_temp
	
	
	//--- ถ้าเป็นการผูกสัญญาเงินกู้ชั่วคราว ให้เก็บประวัติการผ่อนชำระในตาราง temp ด้วย
	if($conTerm_temp != "")
	{
		for($i=1; $i<=$conTerm_temp; $i++)
		{
			$genDate[$i] = pg_escape_string($_POST["genDate$i"]); // วันครบกำหนดชำระ
			$genMinPay[$i] = pg_escape_string($_POST["genMinPay$i"]); // ยอดจ่ายขั้นต่ำ
			
			$strTerm = "insert into account.\"thcap_payTerm_temp\"(\"contractID\", \"ptNum\", \"ptDate\", \"ptMinPay\", \"doerID\", \"doerStamp\")
						values($contractID, '$i', '$genDate[$i]', '$genMinPay[$i]', '$user_id', '$add_date')";
						
			$qryTerm = pg_query($strTerm);
			if($qryTerm){}else{ $status++; echo $strTerm;}
			
			if($i > 1)
			{ // เช็คว่า ยอดผ่อนแต่ละงวด จำนวนเงินเท่ากันหรือไม่
				if($genMinPay[$i] != $genMinPay[$i-1])
				{ // ถ้ายอดผ่อนแต่ละงวดไม่เท่ากัน จะกำหนดให้ ยอดผ่อนขั้นต่ำหลัก เปลี่ยนเป็น 0 ทันที
					$conMinPay = "'0'";
				}
			}
		}
		$conEndDate = $genDate[$conTerm_temp]; // วันที่สิ้นสุดสัญญาจะเท่ากับวันที่ครบกำหนดชำระงวดสุดท้าย
	}
	//--- จบการเก็บประวัติการผ่อนชำระในตาราง temp
	
	// เช็คค่าว่างของวันสิ้นสุดสัญญา
	$conEndDate = checknull($conEndDate);
	
	//--- insert ข้อมูลสัญญา ลงตาราง thcap_contract_temp
		//if($conType == "'HP'" || $conType == "'FL'" || $conType == "'OL'")
		
		if($chk_con_type == "HIRE_PURCHASE" || $chk_con_type == "LEASING" || $chk_con_type == "GUARANTEED_INVESTMENT" || $chk_con_type == "FACTORING" || $chk_con_type == "SALE_ON_CONSIGNMENT")
		{ // ถ้าเป็นสัญญาประเภทการเช่า
			$sql3 = "INSERT INTO thcap_contract_temp(
						\"contractID\", \"conType\", \"conCompany\", \"conFinanceAmount\", \"conLoanIniRate\", \"case_owners_id\",
						\"conLoanMaxRate\", \"conTerm\", \"conMinPay\", \"conPenaltyRate\", 
						\"conDate\", \"conStartDate\", \"conEndDate\" ,\"conFirstDue\", \"conRepeatDueDay\",
						\"conFreeDate\", \"conClosedFee\", \"conStatus\", \"conInvoicePeriod\", \"conFinAmtExtVat\", \"conFineRate\", \"conExtRentMinPay\",
						\"conCusFullnameArray\", \"conCusAddressArray\", \"conResidualValue\",
						\"conFlow\", rev, \"CusIDarray\", \"conCreditRef\", \"addrTempID\", \"doerUser\", \"doerStamp\", \"editNumber\", \"conSubType_serial\",
						\"conNumExceptDays\", \"conNumNTDays\", \"conNumSueDays\", \"sendNCB\",
						\"conResidualValueIncVat\", \"conLeaseIsForceBuyResidue\", \"conLeaseBaseFinanceForCal\", \"conGuaranteeAmtForCredit\" $conDown $txtfield)
				VALUES ($contractID, $conType, $conCompany, $conLoanAmt, $conLoanIniRate, $case_owners_id_checknull,
						'15', $conTerm, $conMinPay, $conPenaltyRate,
						$conDate, $conStartDate, $conEndDate, $conFirstDue, $conRepeatDueDay,
						$conFreeDate, $conClosedFee, '10', $conInvoicePeriod, $conFinAmtExtVat, $conFineRate, $conExtRentMinPay,
						$conCusFullnameArray, $conCusAddressArray, $conResidualValue,
						'1', '1', $textCus, $conCreditRefValue, '$addrTempID', '$user_id', '$add_date', '0', $selectSubtype,
						thcap_get_config('contract_dayexceptdue', $conType), thcap_get_config('contract_nt1', $conType), thcap_get_config('contract_nt2', $conType), thcap_get_config('contract_sendncb', $conType),
						$conResidualValueIncVat, $conLeaseIsForceBuyResidue, $conLeaseBaseFinanceForCal, $conGuaranteeAmtForCredit $downPayment $txtadd)
				RETURNING \"autoID\" ";
		}
		else if($chk_con_type == "LOAN" || $chk_con_type == "JOINT_VENTURE" || $chk_con_type == "PERSONAL_LOAN")
		{
			if($chk_con_type == "PERSONAL_LOAN"){$conLoanMaxRate = "28";}else{$conLoanMaxRate = "15";}
			
			$sql3 = "INSERT INTO thcap_contract_temp(
						\"contractID\", \"conType\", \"conCompany\", \"conLoanAmt\", \"conLoanIniRate\", \"case_owners_id\",
						\"conLoanMaxRate\", \"conTerm\", \"conMinPay\", \"conPenaltyRate\", 
						\"conDate\", \"conStartDate\", \"conEndDate\" ,\"conFirstDue\", \"conRepeatDueDay\", 
						\"conFreeDate\", \"conClosedFee\", \"conStatus\", \"conInvoicePeriod\", \"conFineRate\", \"conExtRentMinPay\",
						\"conCusFullnameArray\", \"conCusAddressArray\", \"conPLIniRate\",
						\"conFlow\", rev, \"CusIDarray\", \"conCreditRef\", \"addrTempID\", \"doerUser\", \"doerStamp\", \"editNumber\",\"conGuaranteeAmt\",
						\"conNumExceptDays\", \"conNumNTDays\", \"conNumSueDays\", \"sendNCB\", \"conGuaranteeAmtForCredit\" $txtfield)
				VALUES ($contractID, $conType, $conCompany, $conLoanAmt, $conLoanIniRate, $case_owners_id_checknull,
						'$conLoanMaxRate', $conTerm, $conMinPay, $conPenaltyRate,
						$conDate, $conStartDate, $conEndDate, $conFirstDue, $conRepeatDueDay,
						$conFreeDate, $conClosedFee, '10',  $conInvoicePeriod, $conFineRate, $conExtRentMinPay,
						$conCusFullnameArray, $conCusAddressArray, $conPLIniRate,
						'1', '1', $textCus, $conCreditRefValue, '$addrTempID', '$user_id', '$add_date', '0',$conguaranteeamt,
						thcap_get_config('contract_dayexceptdue', $conType), thcap_get_config('contract_nt1', $conType), thcap_get_config('contract_nt2', $conType), thcap_get_config('contract_sendncb', $conType), $conGuaranteeAmtForCredit $txtadd)
				RETURNING \"autoID\" ";
		}
		else {
			$sql3 = "ROLLBACK";
		}
			
		$query3 = pg_query($sql3);
		if($query3)
		{
			// ถ้ามีการตั้งหนี้ตอนผูกสัญญาด้วย
			if($_POST["countrow"] >= 1)
			{
				$create_ref_contractID = pg_fetch_result($query3,0); // PK id ของรายการที่ขอผูกสัญญา
			}
		}
		else
		{
			$status++;
			echo $sql3;
		}
	//--- จบการ insert ข้อมูลสัญญา ลงตาราง thcap_contract_temp
	
	//--- ถ้าเป็นสัญญา FA ให้ดูการผูกบิลด้วย
	if($conType == "'FA'")
	{
		$textBillFA = "{}";
		for($i=0;$i<sizeof($selectBillFA);$i++)
		{
			list($prebillID) = explode('#',$selectBillFA[$i]);
			$prebillID = checknull($prebillID);
			if($prebillID == "null")
			{
				continue;
			}
			else
			{			
				$prebillID = str_replace("'","",$prebillID); // ตัดเครื่องหมาย ' ออกก่อน
				
				$qry_chkBillFA = pg_query("select * from \"thcap_fa_prebill\" where \"prebillID\" = '$prebillID' "); // ตรวจสอบก่อนว่ามีบิลอยู่ในระบบแล้วหรือยัง
				$row_chkBillFA = pg_num_rows($qry_chkName);
				if($row_chkBillFA > 0) // ถ้ามีบิลในระบบแล้ว
				{
					while($resFaBill = pg_fetch_array($qry_chkBillFA))
					{
						$numberInvoice = $resFaBill["numberInvoice"]; // เลขที่ใบแจ้งหนี้
						$totalTaxInvoice = $resFaBill["totalTaxInvoice"]; // ยอดในใบแจ้งหนี้รวมภาษี
						$taxInvoice = $resFaBill["taxInvoice"]; // จำนวนเงินที่นัดรับเช็คในแต่ละครั้ง
					}
					// เพิ่มบิลเข้าไปใน array โดยใช้ function จาก postgres
					$qry_textBillFA = pg_query("select ta_array_add('$textBillFA', '$prebillID', '$taxInvoice')");
					$res_textBillFA = pg_fetch_array($qry_textBillFA);
					list($textBillFA) = $res_textBillFA;
				}
				else // ถ้ายังไม่มีบิลอยู่ในระบบ
				{
					$status++;
					echo "ไม่พบบิลในระบบ กรุณาทำรายการใหม่อีกครั้ง!!<br>";
				}
			}
		}
		
		if($textBillFA != "{}")
		{
			$qey_sumInvoiceAmt = pg_query("SELECT sum(\"InvoiceAmt\") FROM (SELECT ta_array_get('$textBillFA'::character varying[], ta_array_list('$textBillFA'::character varying[]))::numeric(15,2) AS \"InvoiceAmt\") as tebletemp;");
			$sumInvoiceAmt = pg_result($qey_sumInvoiceAmt,0);
			$sumInvoiceAmt = $sumInvoiceAmt - str_replace("'","",$conMinPay);
			if($sumInvoiceAmt < 0){$sumInvoiceAmt = 0.00;}
			
			$strFaBill = "insert into \"thcap_contract_fa_bill_temp\"(\"contractID\", \"arrayFaBill\", \"doerID\", \"doerStamp\", \"edittime\", \"ap_fac_amt\")
						values($contractID, '$textBillFA', '$user_id', '$add_date', '0', '$sumInvoiceAmt')";
			$qryFaBill = pg_query($strFaBill);
			if($qryFaBill){}else{ $status++; echo $strFaBill;}
		}
	}
}

//การตั้งหนี้
$c = $_POST["countrow"]; // จำนวนหนี้ที่ตั้ง
$create_ref_contractID = checknull($create_ref_contractID); // PK id ของรายการที่ขอผูกสัญญา
if($c>=1)
{
	for($d=1;$d<=$c;$d++)
	{
		$AmtCusPay=0;
		$fpayrefvalue = $_POST["fpayrefvalue$d"];
		$fpayid = $_POST["fpayid$d"];
		$datepicker2 = checknull($_POST["datepicker2$d"]);
		$fpayamp = checknull($_POST["fpayamp$d"]);
		$vat_inc = $_POST["vat_inc$d"];
		$cre_fr=pg_query("select cal_rate_or_money('VAT',$datepicker2);"); 	
		$vat_rate=pg_fetch_result($cre_fr,0);  // vat %
		$remark = $_POST["remark$d"]; // เหตุผล		
		$remark = checknull($remark);
		
		$qrytype=pg_query("select \"tpDesc\",\"ableVAT\" from account.\"thcap_typePay\" where \"tpID\" = '$fpayid'");
		while($restype=pg_fetch_array($qrytype)){
			$ableVAT=$restype["ableVAT"];
			$tpDesc=$restype["tpDesc"];
		}	
		if($vat_inc==1 && $ableVAT==1){//รวม vat
			$vatAmt = ( $fpayamp * $vat_rate / (100+$vat_rate)) ; // ภาษีมูลค่าเพิ่ม 
			$vatAmt = round($vatAmt, 2); // ป้องกันเรื่อง ลบกันแล้วได้ x.5 ทั้งคู่ทำให้ปัดขึ้นทั้งคู่แล้วไม่ตรง
			$AmtExtVat = $fpayamp - $vatAmt;
			$AmtCusPay = $fpayamp; //จำนวนเงินที่ลูกค้าต้องจ่าย
		}
		else if($vat_inc==2 && $ableVAT==1){//ไม่รวม vat แต่ต้องคิด vat
			$vatAmt = ( $fpayamp * $vat_rate / 100) ; // ภาษีมูลค่าเพิ่ม 
			$vatAmt = round($vatAmt, 2); // ป้องกันเรื่อง ลบกันแล้วได้ x.5 ทั้งคู่ทำให้ปัดขึ้นทั้งคู่แล้วไม่ตรง
			$AmtExtVat = $fpayamp ;
			$AmtCusPay = $fpayamp + $vatAmt; //จำนวนเงินที่ลูกค้าต้องจ่าย
		}	 
		if($ableVAT==0){ //ไม่คิด Vat
			$vatAmt =0;
			$AmtExtVat = $fpayamp; //จำนวนเงินที่ลูกค้าต้องจ่าย
			$AmtCusPay = $fpayamp; //จำนวนเงินที่ลูกค้าต้องจ่าย
		}
		//ตรวจสอบข้อมูลการตั้งหนี้ซ้ำ
		$qry_chk = pg_query("	SELECT 	count(*) from public.\"thcap_temp_otherpay_debt\" 
								WHERE 	\"contractID\" = $contractID and 
										\"typePayID\" = '$fpayid' and 
										\"typePayRefValue\" = '$fpayrefvalue' and 
										\"debtStatus\" IN ('9','2','1')
						");
		list($row_chk) = pg_fetch_array($qry_chk);	
		IF($row_chk  > 0){
			$status++;			
		}else{	
			$ins=pg_query("SELECT thcap_process_setdebtloan($contractID,'$fpayid','$fpayrefvalue',$conDate1,$fpayamp,$remark,'$user_id','1',null,null,null,$datepicker2::date,'0',$create_ref_contractID)");
		
			list($return) = pg_fetch_array($ins);
			if($return == 't'){}else{ $status++; }
		}
	}//end for
}

if($status == 0)
{	
	if($method=="addcredit"){
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) ผูกสัญญาวงเงินชั่วคราว', '$add_date')");
		if($sqlaction){}else{$status++;}
		if($status == 0){pg_query("COMMIT");}
		//ACTIONLOG---
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_Index.php\">";
	}else{
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) ผูกสัญญาเงินกู้ชั่วคราว', '$add_date')");
		if($sqlaction){}else{$status++;}
		if($status == 0){pg_query("COMMIT");}
		//ACTIONLOG---
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
	}
	
	echo "<script type='text/javascript'>alert(' Success ')</script>";
	

}
else
{
	pg_query("ROLLBACK");
	echo "<script type='text/javascript'>alert(' error ')</script>";
	
	if($method=="addcredit"){
		echo "<input type=\"button\" name=\"back\" value=\" กลับ \" onclick=\"parent.location.href='frm_Index.php'\">";
	}else{
		echo "<input type=\"button\" name=\"back\" value=\" กลับ \" onclick=\"parent.location.href='index.php'\">";
	}
}	
?>