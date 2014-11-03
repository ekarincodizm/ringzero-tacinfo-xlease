<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php"); // ไฟล์ function chacknull ใช้เพื่อตรวจสอบค่าว่างของตัวแปรนั้นๆ วิธีใช้คือ $A = checknull($A); หาก $A เป็นค่าว่างจะส่งค่า "null" กลับมา หากไม่ใช่จะส่งค่า '$A' กลับมา...

$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime();
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php
if($sql_check_user = pg_query("select \"username\",\"fullname\" from \"Vfuser\" where \"id_user\" = '$id_user'")); else $status++;
while($res_uesr = pg_fetch_array($sql_check_user))
{
	$username = $res_uesr["username"]; //username ของคนที่ทำรายการ
	$userfullname = $res_uesr["fullname"]; //fullname ของคนที่ทำรายการ
}

function insertZero($inputValue , $digit )
{
	$str = "" . $inputValue;
	while (strlen($str) < $digit){
		$str = "0" . $str;
	}
	return $str;
}

$money_Deposit = pg_escape_string($_POST["money_Deposit"]); // เงินพักรอตัดรายการ ( เงินรับฝาก )
$money_Guarantee = pg_escape_string($_POST["money_Guarantee"]); // เงินค้ำประกันการชำระหนี้
$printvat=pg_escape_string($_POST["printvat"]); //สถานะ print ใบกำกับภาษี  1= print
?>

<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<?php
pg_query("BEGIN WORK");
$status = 0;

$receiveDate = pg_escape_string($_POST["receiveDatePost"]); // วันที่เลือก

$statusLock = pg_escape_string($_POST["statusLock"]); // สถานะการ Lock ถ้า 1 แสดงว่าเป็นการจ่ายที่ Post มาจากหน้าอื่น
$statusPay = pg_escape_string($_POST["statusPay"]); // สถานะการจ่าย

$codePay = pg_escape_string($_POST["revTranID"]); // รหัสที่โอน
$codePay = checknull($codePay);

$contractUseMoney = pg_escape_string($_POST["contractUseMoney"]); // เลขที่สัญญาที่ใช้เงิน

if(pg_escape_string($_POST["revTranID"]!="")){
	$revTranID=pg_escape_string($_POST["revTranID"]);
	//ตรวจสอบว่ามีการล้างรายการหรือนำเงินไปใช้ไปก่อนหน้านี้หรือไม่ 1=ล้างรายการ 6=สามารถใช้เงินได้
	
	$qrychk=pg_query("select \"balanceAmt\" from finance.thcap_receive_transfer where \"revTranID\"='$revTranID' AND \"revTranStatus\" IN ('1','6')");
	$numchk=pg_num_rows($qrychk); //กรณีที่ revTranStatus เท่ากับ 3 แสดงว่ามีการนำเงินไปใช้แล้ว แต่ถ้าเป็น 6 แสดงว่ายังสามารถใช้ได้อยู่
	
	if($numchk==0){
		echo "<div align=\"center\"><h2>รายการนี้ได้ล้างรายการ หรือได้ทำรายการไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ!!</h2></div>";
		echo "<meta http-equiv='refresh' content='5; URL=../thcap/frm_Index_finance.php'>";
		exit;
	}else{
		$chk_balanceAmt = pg_fetch_result($qrychk,0); // เงินคงเหลือในบัญชี
		$chk_money = pg_escape_string($_POST["receiveAmountPost3"]); // เงินที่จะชำระ
		
		if($chk_balanceAmt < $chk_money){
			echo "<div align=\"center\"><h2>ยอดเงินคงเหลือไม่เพียงพอ กรุณาตรวจสอบ!!</h2></div>";
			echo "<meta http-equiv='refresh' content='5; URL=../thcap/frm_Index_finance.php'>";
			exit;
		}
	}
}

echo $codePay;
$timeStamp=pg_escape_string($_POST["timeStamp"]);
$receiveDate = pg_escape_string($_POST["receiveDatePost"])." ".$timeStamp; // วันเวลาที่เลือก ปัจจุบัน

$contractID = pg_escape_string($_POST["ConID3"]);
$byChannel = pg_escape_string($_POST["byChannelPost"]);
list($byChannelPost,$istranpay)=explode(",",$byChannel);
$chk = $_POST["chk"]; // รายการค่าอื่นๆที่เลือก
//$debtIDchk = $_POST["debtIDchk"];
$interestRatePost = pg_escape_string($_POST["interestRatePost"]); // ภาษีหัก ณ ที่จ่าย ใช่หรือไม่ ถ้าใช่จะเป็น on (ของค่าอื่นๆ)
$interestRatePost_Payment = pg_escape_string($_POST["interestRatePost_Payment"]); // ภาษีหัก ณ ที่จ่าย ใช่หรือไม่ ถ้าใช่จะเป็น on (ของค่างวด)
$receiveAmountPost = pg_escape_string($_POST["receiveAmountPost"]); // จำนวนเงินทั้งหมด
$InterestWhtMoney = pg_escape_string($_POST["sum3"]); // ภาษีหัก ณ ที่จ่ายของค่างวด

//หมายเหตุ เงินกู้และชำระค่าอื่นๆ
$chkreasonother = pg_escape_string($_POST['chkreasonother']); // ติ๊กถูก หมายเหตุของค่าอื่นๆ

if($chkreasonother == "1"){
	$reasontextother = pg_escape_string($_POST['reasontextother']);
		if($reasontextother == ""){		
			$reasontextother = "'-'";
		}else{
		
			$reasontextother = "'".$reasontextother."'";
		}
}else{
	$reasontextother = "null";
}

$chkreasonappent = pg_escape_string($_POST['chkreasonappent']); // ติ๊กถูก หมายเหตุของเงินกู้

if($chkreasonappent == "1"){
	$reasontextappent = pg_escape_string($_POST['reasontextappent']);
		if($reasontextappent == ""){		
			$reasontextappent = "'-'";
		}else{
			$reasontextappent = "'".$reasontextappent."'";
		}
}else{
	$reasontextappent = "null";
	//$reasontextappent = "";
}
/////////

$contractID2 = $contractID;

$minpay=pg_getminpaytype($contractID);
	
$receiveVice=pg_escape_string($_POST["receiveVice"]); //check เป็นใบเสร็จออกแทน ของค่าอื่นๆ
$selectVice=pg_escape_string($_POST["selectVice"]); //เลือกว่าออกแทนใบเสร็จอะไร ของค่าอื่นๆ
$viceDetail=pg_escape_string($_POST["viceDetail"]); //เลขที่ใบเสร็จที่ออกแทน ของค่าอื่นๆ
$conType = pg_escape_string($_POST["myVConType"]); // ประเภทสัญญา

// ชื่อประเภทสินเชื่อแบบเต็ม
$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$contractID') ");
$chk_con_type = pg_fetch_result($qry_chk_con_type,0);

if($receiveVice=="1"){
	$selectVice2="'".$selectVice."'";
	$viceDetail2="'".$viceDetail."'";
	if($selectVice2 == 'ใบเสร็จที่ยกเลิกเลขที่'){
		$ref_receipIDfunc = pg_query("SELECT \"thcap_receiptIDTotaxinvoiceID\"('$viceDetail')");
		$ref_receipID = pg_fetch_array($ref_receipIDfunc);
		list($ref_receipID) = $ref_receipID;
	}	
	
}else{
	$receiveVice = 0;
	$selectVice2="null";
	$viceDetail2="null";
}

$interestRatePost = pg_escape_string($_POST["interestRatePost"]); // check ว่ามีภาษีหัก ณ ที่จ่าย ของค่าอื่นๆ
$whtDetail = pg_escape_string($_POST["whtDetail"]); // เลขที่ภาษีหัก ณ ที่จ่าย ของค่าอื่นๆ

if($interestRatePost == "on"){
	$haveinterestrate_other = 1; // บอกว่ามี ภาษีหัก ณ ที่จ่ายของค่าอื่นๆด้วย
	if($whtDetail == "")
	{
		$whtDetail = "'ไม่ระบุ'";
	}
	else
	{
		$whtDetail = "'".$whtDetail."'";
	}
}else{
	$haveinterestrate_other = 1; // บอกว่าไม่มี ภาษีหัก ณ ที่จ่ายของค่าอื่นๆ
	$whtDetail = "null";
}


$receiveVice_Payment = pg_escape_string($_POST["receiveVice_Payment"]); //check เป็นใบเสร็จออกแทน ของค่างวด
$selectVice_Payment = pg_escape_string($_POST["selectVice_Payment"]); //เลือกว่าออกแทนใบเสร็จอะไร ของค่างวด
$viceDetail_Payment = pg_escape_string($_POST["viceDetail_Payment"]); //เลขที่ใบเสร็จที่ออกแทน ของค่างวด

if($receiveVice_Payment == "1"){
	/*$selectVice_Payment2="'".$selectVice_Payment."'";
	$viceDetail_Payment2="'".$viceDetail_Payment."'";*/
}else{
	$receiveVice_Payment = 0;
	$selectVice_Payment="";
	$viceDetail_Payment="";
	/*$selectVice_Payment2="null";
	$viceDetail_Payment2="null";*/
}

$interestRatePost_Payment = pg_escape_string($_POST["interestRatePost_Payment"]); // check ว่ามีภาษีหัก ณ ที่จ่าย ของค่างวด
$whtDetail_Payment = pg_escape_string($_POST["whtDetail_Payment"]); // เลขที่ภาษีหัก ณ ที่จ่าย ของค่างวด

if($interestRatePost_Payment == "on"){
	$haveinterestrate = 1; // บอกว่ามีภาษีหัก ณ ที่จ่ายด้วย
	if($whtDetail_Payment == "")
	{
		$whtDetail_Payment = "'ไม่ระบุ'";
		//$whtDetail_Payment = "ไม่ระบุ";
	}
	else
	{
		$whtDetail_Payment = "'".$whtDetail_Payment."'";
	}
	if($InterestWhtMoney == ""){$InterestWhtMoney = 0.00;}
}else{
	$haveinterestrate = 0; // บอกว่าไม่มีภาษีหัก ณ ที่จ่าย
	$whtDetail_Payment = "null";
	//$whtDetail_Payment = "";
	$InterestWhtMoney = 0.00;
}


//  เบี้ยปรับ
$payPenalty = pg_escape_string($_POST["payPenalty"]); // รับชำระเบี้ยปรับด้วยหรือไม่
$amtPenalty = pg_escape_string($_POST["amtPenalty"]); // จำนวนเบี้ยปรับ

if($payPenalty == "on" && $amtPenalty > 0.00) // ถ้ามีการชำระเบี้ยปรับด้วย
{
	$fpayrefvalue = $receiveDate; // กำหนดเลขที่อ้างอิงเป็นวันเวลาที่จ่าย
	
	// เช็คก่อนว่า เลขที่อ้างอิงดังกล่าว ของเลขที่สัญญานั้นๆ มีแล้วหรือยัง
	$qry_con_refvalue = pg_query("select \"debtID\" from \"thcap_temp_otherpay_debt\" where \"contractID\" = '$contractID' and \"typePayRefValue\" = '$fpayrefvalue' ");
	$row_con_refvalue = pg_num_rows($qry_con_refvalue);
	if($row_con_refvalue == 0)
	{
		$use_fpayrefvalue = $fpayrefvalue; // เลขที่อ้างอิงที่จะใช้
	}
	else
	{
		$rv = "000";
		do{
			$rv++;
			while(strlen($rv) < 3)
			{
				$rv = "0".$rv;
			}
			$use_fpayrefvalue = $use_fpayrefvalue.".$rv";
			$qry_con_refvalueNew = pg_query("select \"debtID\" from \"thcap_temp_otherpay_debt\" where \"contractID\" = '$contractID' and \"typePayRefValue\" = '$use_fpayrefvalue' ");
			$row_con_refvalueNew = pg_num_rows($qry_con_refvalueNew);
		}while($row_con_refvalueNew > 0);
	}
	
	// หาประเภทค่าใช้จ่าย
	$qry_getIntFineType = pg_query(" select account.\"thcap_getIntFineType\"('$contractID')");
	$res_getIntFineType = pg_fetch_result($qry_getIntFineType,0);
	
	// วันที่ตั้งหนี้
	$dateDebt = substr($receiveDate,0,10);
	
	/*
	select thcap_process_setdebtloan(เลขที่สัญญา, รหัสประเภทค่าใช้จ่าย, ค่าอ้างอิงของค่าใช้จ่ายนั้นๆ, วันที่ตั้งหนี้นั้นๆ, จำนวนหนี้, เหตุผล, ผู้ตั้งหนี้,
								รหัสกำหนดว่าจะให้อนุมัติโดยระบบหรือรอนุมัติหรือมาจากการกดอนุมัติ 0 = อนุมัติโดยระบบ user "000" 1 = รออนุมัติปกติ  2 = อนุมัติจากเมนูอนุมัติ, รหัสหนี้ที่จะอนุมัติ, สถานะการอนุมัติ, ผู้อนุมัติ)
	*/
	
	// ตั้งหนี้ และอนุมัติอัตโนมัติ
	if($qrySetDebt = pg_query("SELECT \"thcap_process_setdebtloan\"('$contractID','$res_getIntFineType', '$use_fpayrefvalue', '$dateDebt', '$amtPenalty', null, '$id_user', '0')")); else $status++;
	$paySetDebt = pg_fetch_array($qrySetDebt);
	
	// หารหัสหนี้ของเบี้ยปรับ ที่ถูกตั้งขึ้น
	$qry_DebtPenalty = pg_query("select \"debtID\" from \"thcap_temp_otherpay_debt\" where \"contractID\" = '$contractID' and \"typePayRefValue\" = '$use_fpayrefvalue' ");
	$res_DebtPenalty = pg_fetch_result($qry_DebtPenalty,0);
}
//PPPPPPPPPPPPPPPPPPPP






//AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
// ถ้ามีการชำระเงินกู้จำนองชั่วคราวด้วย

$appent = pg_escape_string($_POST["appent"]); // เงินกู้
$payAdviser = pg_escape_string($_POST["payAdviser"]); // ค่าที่ปรึกษา
if($appent == "on" || $payAdviser == "on")
{
	//$contractID = $_POST["sendID"]; มีแล้ว
	//$receiveDate = $_POST["receiveDatePost"]; มีแล้ว
	$receiveAmount = pg_escape_string($_POST["t2"]); // จำนวนเงิน
	$interestRate = pg_escape_string($_POST["t3"]); // อัตราดอกเบี้ยที่จ่าย ของค่างวด
	//$byChannel = $_POST["byChannelPost"];
	$byChannel = $byChannelPost;

	$contractID2 = $contractID;
	
	// ถ้ามีเลขที่สัญญาที่ใช้เงิน ให้ใช้เลขที่สัญญานี้อ้างอิง
	if($contractUseMoney != "")
	{
		$ChannelRefToFunction = $contractUseMoney;
		$ChannelRefToFunction = checknull($ChannelRefToFunction);
	}
	else
	{
		$ChannelRefToFunction = $codePay;
	}
	
	//--- เช็คค่าว่างก่อน (สำหรับค่าอื่นๆ และค่าเช่า/เช่าซื้อ)
	$viceDetail_Payment = checknull($viceDetail_Payment);
	$selectVice_Payment = checknull($selectVice_Payment);
	
	if($chk_con_type == "HIRE_PURCHASE" || $chk_con_type == "LEASING" || $chk_con_type == "GUARANTEED_INVESTMENT" || $chk_con_type == "FACTORING" || $chk_con_type == "SALE_ON_CONSIGNMENT")
	{
		
		$rowHP = pg_escape_string($_POST["rowHP"]); // จำนวนงวดที่จะชำระ
		
		$arrayHP = "{";
		for($i=1; $i<=$rowHP; $i++)
		{
			$debtIDHP[$i] = pg_escape_string($_POST["debtIDHP$i"]);
			$whtHPtxt[$i] = pg_escape_string($_POST["whtHPtxt$i"]);
			
			if($i == 1)
			{
				$arrayHP = $arrayHP."{".$debtIDHP[$i].",".$whtHPtxt[$i]."}";
			}
			else
			{
				$arrayHP = $arrayHP.",{".$debtIDHP[$i].",".$whtHPtxt[$i]."}";
			}
			
			// ตรวจสอบหนี้ตัวนี้ก่อนว่า มีการขอยกเว้นหนี้อยู่หรือไม่
			$qry_discount = pg_query("select \"dcNoteID\" from account.\"thcap_dncn\" where \"subjectStatus\" = '2' and \"dcNoteStatus\" = '8' and \"debtID\" = '$debtIDHP[$i]' ");
			$row_discount = pg_num_rows($qry_discount);
			if($row_discount > 0)
			{ // ถ้ามีรายการดังกล่าวขอส่วนลดอยู่
				$error_discount = "ไม่สามารถทำรายการได้ เนื่องจากมีบางรายการถูกขอส่วนลดอยู่";
				$status++;
			}
		}
		$arrayHP = $arrayHP."}";
		
		/* SELECT "thcap_process_receiveLease"(เลขที่สัญญา, วันที่จ่าย แบบ วัน และ เวลา, ช่องทางการจ่าย, จำนวนเงินที่จ่าย, ภาษีหัก ณ ที่จ่าย, บอกว่ามีภาษีหัก ณ ที่จ่ายหรือไม่ ถ้ามี เป็น 1 ไม่มีเป็น 0, รหัสพนักงาน ของคนที่ทำรายการ,
													เลขที่อ้างอิงภาษีหัก ณ ที่จ่าย, หมายเหตุ, บอกว่ามีใบเสร็จออกแทนหรือไม่ ถ้าใช่เป็น 1 ไม่ใช่เป็น 0, ชื่อประเภทของใบเสร็จออกแทน, เลขที่ใบเสร็จออกแทน, อัตราดอกเบี้ย, arrayรหัสหนี้และwht, ChannelRef);*/
													
		if($qryPay = pg_query("SELECT \"thcap_process_receiveLease\"('$contractID','$receiveDate', '$byChannel', '$receiveAmount', '$InterestWhtMoney', '$haveinterestrate', '$id_user', $whtDetail_Payment, $reasontextappent, '$receiveVice_Payment', $selectVice_Payment, $viceDetail_Payment, '0.00', '$arrayHP', $ChannelRefToFunction)")); else $status++;
		$resPay = pg_fetch_array($qryPay);
		list($newreceipt2) = $resPay; // insert ข้อมูลลง database พร้อม return เลขที่ใบเสร็จ กลับมา
		
		if($newreceipt2 == "" || $newreceipt2 == "null" || $newreceipt2 == null)
		{
			$status++;
		}
	}
	elseif($chk_con_type == "JOINT_VENTURE")
	{
		if($payAdviser == "on")
		{ // มีการกำหนดค่าที่ปรึกษางวดที่จะชำระ
			$rowHP = pg_escape_string($_POST["rowHP"]); // จำนวนงวดที่จะชำระ
			
			$arrayHP = "{";
			for($i=1; $i<=$rowHP; $i++)
			{
				$debtIDHP[$i] = pg_escape_string($_POST["debtIDHP$i"]);
				
				if($i == 1)
				{
					$arrayHP = $arrayHP.$debtIDHP[$i];
				}
				else
				{
					$arrayHP = $arrayHP.",".$debtIDHP[$i];
				}
				
				// ตรวจสอบหนี้ตัวนี้ก่อนว่า มีการขอยกเว้นหนี้อยู่หรือไม่
				$qry_discount = pg_query("select \"dcNoteID\" from account.\"thcap_dncn\" where \"subjectStatus\" = '2' and \"dcNoteStatus\" = '8' and \"debtID\" = '$debtIDHP[$i]' ");
				$row_discount = pg_num_rows($qry_discount);
				if($row_discount > 0)
				{ // ถ้ามีรายการดังกล่าวขอส่วนลดอยู่
					$error_discount = "ไม่สามารถทำรายการได้ เนื่องจากมีบางรายการถูกขอส่วนลดอยู่";
					$status++;
				}
			}
			$arrayHP = $arrayHP."}";
		}
		else
		{
			$arrayHP = "{}";
		}
		
		/* SELECT "thcap_process_receiveInstallment"(เลขที่สัญญา, วันที่จ่าย แบบ วัน และ เวลา, ช่องทางการจ่าย, จำนวนเงินที่จ่าย, ภาษีหัก ณ ที่จ่าย, บอกว่ามีภาษีหัก ณ ที่จ่ายหรือไม่ ถ้ามี เป็น 1 ไม่มีเป็น 0, รหัสพนักงาน ของคนที่ทำรายการ,
													เลขที่อ้างอิงภาษีหัก ณ ที่จ่าย, หมายเหตุ, บอกว่ามีใบเสร็จออกแทนหรือไม่ ถ้าใช่เป็น 1 ไม่ใช่เป็น 0, ชื่อประเภทของใบเสร็จออกแทน, เลขที่ใบเสร็จออกแทน, อัตราดอกเบี้ย, array 1d หนี้ที่ปรึกษาที่จะจ่าย, ChannelRef);*/
													
		if($qryPay = pg_query("SELECT \"thcap_process_receiveJointVenture\"('$contractID','$receiveDate', '$byChannel', '$receiveAmount', '$InterestWhtMoney', '$haveinterestrate', '$id_user', $whtDetail_Payment, $reasontextappent, '$receiveVice_Payment', $selectVice_Payment, $viceDetail_Payment, '$interestRate', '$arrayHP', $ChannelRefToFunction)")); else $status++;
		$resPay = pg_fetch_array($qryPay);
		list($newreceipt2) = $resPay; // insert ข้อมูลลง database พร้อม return เลขที่ใบเสร็จ กลับมา
		
		if($newreceipt2 == "" || $newreceipt2 == "null" || $newreceipt2 == null)
		{
			$status++;
		}
	}
	else
	{
		/* SELECT "thcap_process_receiveInstallment"(เลขที่สัญญา, วันที่จ่าย แบบ วัน และ เวลา, ช่องทางการจ่าย, จำนวนเงินที่จ่าย, ภาษีหัก ณ ที่จ่าย, บอกว่ามีภาษีหัก ณ ที่จ่ายหรือไม่ ถ้ามี เป็น 1 ไม่มีเป็น 0, รหัสพนักงาน ของคนที่ทำรายการ,
													เลขที่อ้างอิงภาษีหัก ณ ที่จ่าย, หมายเหตุ, บอกว่ามีใบเสร็จออกแทนหรือไม่ ถ้าใช่เป็น 1 ไม่ใช่เป็น 0, ชื่อประเภทของใบเสร็จออกแทน, เลขที่ใบเสร็จออกแทน, อัตราดอกเบี้ย, ChannelRef);*/
													
		if($qryPay = pg_query("SELECT \"thcap_process_receiveInstallment\"('$contractID','$receiveDate', '$byChannel', '$receiveAmount', '$InterestWhtMoney', '$haveinterestrate', '$id_user', $whtDetail_Payment, $reasontextappent, '$receiveVice_Payment', $selectVice_Payment, $viceDetail_Payment, '$interestRate',$ChannelRefToFunction)")); else $status++;
		$resPay = pg_fetch_array($qryPay);
		list($newreceipt2) = $resPay; // insert ข้อมูลลง database พร้อม return เลขที่ใบเสร็จ กลับมา
		
		if($newreceipt2 == "" || $newreceipt2 == "null" || $newreceipt2 == null)
		{
			$status++;
		}
	}
	
	$newreceipt2p = split("#",$newreceipt2);
	$newreceipt2 = $newreceipt2p[0]; // เลขที่ใบเสร็จ
	$depositFromJV = $newreceipt2p[1]; // จำนวนเงินที่จะเอาเข้าเงินพัก
}

//AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA

if($depositFromJV != "" && $depositFromJV > 0.00)
{
	$money_Deposit = $money_Deposit + $depositFromJV;
}

//OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
if($chk != "" && $chk[0] != "") // ถ้ามีการทำรายการชำระค่าอื่นๆด้วย
{
	$otherAmount = pg_escape_string($_POST["sum1"]); // จำนวนเงินของค่าอื่นๆ
	$InterestWhtOther = pg_escape_string($_POST["sum2"]); // ภาษีหัก ณ ที่จ่ายของค่าอื่นๆ

	echo "<input type=\"hidden\" name=\"CONID\" id=\"CONID\" value=\"$contractID\">";

	if($byChannelPost == pg_getsecuremoneytype($contractID, 1) ||
		$byChannelPost == pg_getholdmoneytype($contractID, 1))
	{
		$contractID3 = "'".$contractID."'";
	}else{
		$contractID3 = "null";
	}
	
	//กรณีเป็นค่าที่ถูก Post มา จะต้องเก็บค่า ref ด้วย
	if($statusLock==1){
		$contractID3=$codePay;
	}
	
	// ถ้ามีเลขที่สัญญาที่ใช้เงิน ให้ใช้เลขที่สัญญานี้อ้างอิง
	if($contractUseMoney != "")
	{
		$ChannelRefToFunction = $contractUseMoney;
		$ChannelRefToFunction = checknull($ChannelRefToFunction);
	}
	else
	{
		$ChannelRefToFunction = $codePay;
	}
	
	$arrayOTHER = "{";
	for($i=0;$i<sizeof($chk);$i++)
	{
		$debtIDchk = split(" ",$chk[$i]); // [0]=จำนวนหนี้  /[1]=คีย์หลักรหัสหนี้  / [2]=รหัสประเภทค่าใช้จ่าย / [3]=หมายเลขแถวของรายการที่ชำระ
		
		$typePayID = "$debtIDchk[2]";
		$numwhtOtherRow = "$debtIDchk[3]";
		
		if($interestRatePost == "on")
		{
			$whtAmt = pg_escape_string($_POST["moneytxt$numwhtOtherRow"]); // ภาษีหัก ณ ที่จ่าย ของค่าอื่นๆ
			if($whtAmt == "")
			{
				$whtAmt = 0.00;
			}
		}
		else
		{
			$whtAmt = 0.00;
		}
		
		if($i == 0)
		{
			$arrayOTHER = $arrayOTHER."{".$debtIDchk[1].",".$whtAmt."}";
		}
		else
		{
			$arrayOTHER = $arrayOTHER.",{".$debtIDchk[1].",".$whtAmt."}";
		}
		
		// ตรวจสอบหนี้ตัวนี้ก่อนว่า มีการขอยกเว้นหนี้อยู่หรือไม่
		$qry_discount = pg_query("select \"dcNoteID\" from account.\"thcap_dncn\" where \"subjectStatus\" = '2' and \"dcNoteStatus\" = '8' and \"debtID\" = '$debtIDchk[1]' ");
		$row_discount = pg_num_rows($qry_discount);
		if($row_discount > 0)
		{ // ถ้ามีรายการดังกล่าวขอส่วนลดอยู่
			$error_discount = "ไม่สามารถทำรายการได้ เนื่องจากมีบางรายการถูกขอส่วนลดอยู่";
			$status++;
		}
	}
	if($res_DebtPenalty != "")
	{ // ถ้ามีการชำระเบี้ยปรับด้วย
		$arrayOTHER = $arrayOTHER.",{".$res_DebtPenalty.",0.00}";
		$otherAmount += $amtPenalty; // จำนวนเงินที่จ่ายทั้งหมดของค่าอื่นๆ รวมเบี้ยปรับล่าช้า
	}
	$arrayOTHER = $arrayOTHER."}";
	
	/* SELECT "thcap_process_receiveOther"(เลขที่สัญญา, วันที่จ่าย แบบ วัน และ เวลา, ช่องทางการจ่าย, จำนวนเงินที่จ่าย, ภาษีหัก ณ ที่จ่าย, บอกว่ามีภาษีหัก ณ ที่จ่ายหรือไม่ ถ้ามี เป็น 1 ไม่มีเป็น 0, รหัสพนักงาน ของคนที่ทำรายการ,
		เลขที่อ้างอิงภาษีหัก ณ ที่จ่าย, หมายเหตุ, บอกว่ามีใบเสร็จออกแทนหรือไม่ ถ้าใช่เป็น 1 ไม่ใช่เป็น 0, ชื่อประเภทของใบเสร็จออกแทน, เลขที่ใบเสร็จออกแทน, arrayรหัสหนี้และwht, อัตราดอกเบี้ย(ไม่ได้ใช้) , เงินพัก , เงินค้ำ, รหัส temp การย้ายเงินข้ามสัญญา ถ้าเป็น 0 คือไม่ใช่รายการย้ายเงินข้ามสัญญา, ChannelRef);*/
												
	if($qryPay = pg_query("SELECT \"thcap_process_receiveOther\"('$contractID','$receiveDate', '$byChannelPost', '$otherAmount', '$InterestWhtOther', '$haveinterestrate_other', '$id_user', $whtDetail, $reasontextother, '$receiveVice', $selectVice2, $viceDetail2, '0.00', '$arrayOTHER', '$money_Deposit', '$money_Guarantee', '0', $ChannelRefToFunction)")); else $status++;
	$resPay = pg_fetch_array($qryPay);
	list($newreceipt) = $resPay; // insert ข้อมูลลง database พร้อม return เลขที่ใบเสร็จ กลับมา
	
	if($newreceipt == "" || $newreceipt == "null" || $newreceipt == null)
	{
		$status++;
	}
}
//OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
else{ // จบการชำระค่าอื่นๆ

// ----------------------------------------------------------------------------------------------------------------
// NOTE:
// [2014-08-13] kanitchet.vai - จากการตรวจสอบเบื้องต้นเข้าใจว่าในส่วนนี้เป็นการรับชำระเฉพาะ เงินพักรอตัดรายการ / เงินค้ำประกันชำระหนี้ อย่างไรก็ดี โปรดตรวจสอบอีกครั้ง
// ----------------------------------------------------------------------------------------------------------------

$money_Deposit = pg_escape_string($_POST["money_Deposit"]); // เงินพักรอตัดรายการ ( เงินรับฝาก )
$money_Guarantee = pg_escape_string($_POST["money_Guarantee"]); // เงินค้ำประกันการชำระหนี้

if($depositFromJV != "" && $depositFromJV > 0.00)
{
	$money_Deposit = $money_Deposit + $depositFromJV;
}

if($money_Deposit != '0' || $money_Guarantee != '0' || $res_DebtPenalty != ""){

	if($money_Guarantee != '0' || $res_DebtPenalty != "")
	{ // ถ้ามีการรับชำระเงินค้ำหรือเบี้ยปรับ ให้เช็คก่อนว่าจ่ายเงินวันนี้ได้หรือไม่
		
		// หาวันที่ lock ห้ามชำระเงินน้อยกว่าหรือเท่ากับวันนั้น
		$qry_lockdate = pg_query("SELECT \"lockdate\" FROM \"thcap_contract_lock_acc\" WHERE \"contractID\" = '$contractID' ");
		$lockdate = pg_fetch_result($qry_lockdate,0);
		
		// ตรวจสอบก่อนว่าวันที่จ่ายเงินน้อยกว่าหรือเท่ากันวันที่ห้ามจ่ายหรือไม่
		if($lockdate != NULL) {
			$qry_chkLockDate = pg_query("select '$receiveDate'::date <= '$lockdate'::date");
			$chkLockDate = pg_fetch_result($qry_chkLockDate,0);
		}
		// หากชำระภายหลังจากปิดระบบบัญชี และเงินจำนวนดังกล่าวไม่ใช่เงินที่เดิมไม่รู้ว่าใครแล้ว จะต้องรับชำระไม่ได้
		if($chkLockDate == "t" &&  $v_chkanonymous != 1)
		{
			$status++;
			echo "<br>ไม่สามารถรับชำระได้ เนื่องจากมีการปิดระบบบัญชี ประจำวันที่ $lockdate ไปแล้ว แต่ผู้ใช้งานยังสามารถรับเป็นเงินพักรอตัดรายการได้อยู่<br>";
		}
	}

	if($receiveVice=="1"){
		$selectVice2="'".$selectVice."'";
		$viceDetail2="'".$viceDetail."'";	
	}else{
		$selectVice2="null";
		$viceDetail2="null";
	}

// ค้นหาผู้กู้หลักและที่อยู่
if($qry_namemain = pg_query("select \"thcap_fullname\", \"thcap_address\" from \"vthcap_ContactCus_detail\"
where \"contractID\" = '$contractID' and \"CusState\" = '0'")); else $status++;
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);
	$address=trim($resnamemain["thcap_address"]);
}

// ค้นหาผู้กู้ร่วม
if($qry_name=pg_query("select \"thcap_get_coborrower_details\"('$contractID')")); else $status++;
list($nameco) = pg_fetch_array($qry_name);
$numco=pg_num_rows($qry_name);
	
//ดึงที่อยู่ส่งจดหมาย
if($qry_addr = pg_query("SELECT concat(COALESCE(btrim(\"A_NO\"), ''), '', COALESCE(
CASE
	WHEN \"A_SUBNO\" IS NULL OR \"A_SUBNO\" = '-' OR \"A_SUBNO\" = '--' THEN ''
	ELSE concat(' หมู่ ', btrim(\"A_SUBNO\"))
END, ''), '', COALESCE(
CASE
	WHEN \"A_SOI\" IS NULL OR \"A_SOI\" = '-' OR \"A_SOI\" = '--' THEN ''
	ELSE concat(' ซอย', btrim(\"A_SOI\"))
END, ''), '', COALESCE(
CASE
	WHEN \"A_RD\" IS NULL OR \"A_RD\" = '-' OR \"A_RD\" = '--' THEN ''
	ELSE concat(' ถนน', btrim(\"A_RD\"))
END, ''), '', COALESCE(
CASE
	WHEN \"A_TUM\" IS NULL OR \"A_TUM\" = '-' OR \"A_TUM\" = '--' THEN ''
	ELSE 
		CASE
			WHEN \"A_PRO\" = 'กรุงเทพ' OR \"A_PRO\" = 'กรุงเทพฯ' OR \"A_PRO\" = 'กรุงเทพมหานคร' OR \"A_PRO\" = 'กทม' OR \"A_PRO\" = 'กทม.' THEN concat(' แขวง', btrim(\"A_TUM\"))
			ELSE concat(' ตำบล', btrim(\"A_TUM\"))
		END
END, ''), '', COALESCE(
CASE
	WHEN \"A_AUM\" IS NULL OR \"A_AUM\" = '-' OR \"A_AUM\" = '--' THEN ''
	ELSE 
		CASE
			WHEN \"A_PRO\" = 'กรุงเทพ' OR \"A_PRO\" = 'กรุงเทพฯ' OR \"A_PRO\" = 'กรุงเทพมหานคร' OR \"A_PRO\" = 'กทม' OR \"A_PRO\" = 'กทม.' THEN concat(' เขต', btrim(\"A_AUM\"), ' ')
			ELSE concat(' อำเภอ', btrim(\"A_AUM\"), ' ')
		END
END, ''), '', COALESCE(
CASE
	WHEN \"A_PRO\" IS NULL THEN ''
	ELSE 
		CASE
			WHEN \"A_PRO\" = 'กรุงเทพ' OR \"A_PRO\" = 'กรุงเทพฯ' OR \"A_PRO\" = 'กรุงเทพมหานคร' OR \"A_PRO\" = 'กทม' OR \"A_PRO\" = 'กทม.' THEN btrim(\"A_PRO\")
			ELSE concat('จังหวัด', btrim(\"A_PRO\"))
		END
END, ''), ' ', COALESCE(
CASE
	WHEN \"A_POST\" IS NULL OR \"A_POST\" = '-' OR \"A_POST\" = '--' OR \"A_POST\" = '0' THEN ''
	ELSE btrim(\"A_POST\")
END, ''), '', '') AS sentaddress
FROM \"thcap_addrContractID\"
where \"contractID\" = '$contractID' and \"addsType\" = '3'")); else $status++;
list($sentaddress)=pg_fetch_array($qry_addr);	

// ============================================================================================================
// ตรวจสอบว่าเป็นรายการเงินโอน ที่ไม่รู้ว่าใครเป็นผู้โอนชำระหรือไม่ (หากใช่ ทุกอย่างเหมือนเดิม แต่วันที่รับชำระจะเป็นวันที่นำเงินมาออกใบเสร็จ)
// ============================================================================================================

	// Initial ตัวแปรในส่วนงานนี้
	$v_chkanonymous = NULL;

	// กรณีที่เป็นการรับชำระโดย lock จำนวนเงิน ช่องทาง มาจากยืนยันการรับชำระเงินโอน (การเงิน)
	if($statusLock==1){
	
		// หาว่ารายการรับชำระดังกล่าว เป็นการชำระ
		$qry_chkanonymous = pg_query("
			SELECT 
				\"isAnonymous\" 
			FROM 
				finance.thcap_receive_transfer 
			WHERE
				\"revTranID\"=$codePay"
		);
		list($v_chkanonymous)=pg_fetch_array($qry_chkanonymous);
	}
	
	// ถ้าเป็นเงินที่เคยถูกระบุว่าเป็นเงินที่ไม่รู้ว่าใครโอนชำระเข้ามา
	if($v_chkanonymous == 1){
		/* todo ยกเลิกในส่วนนี้ โดยต่อให้เป็นเงินที่เคยถูกระบุว่าเป็นเงินที่ไม่รู้ว่าใครโอนชำระเข้ามา ก็ให้กำหนด วันที่ใบเสร็จ ตรงกับวันที่รับชำระ ตามเลขงาน #7461
			// วันที่ใบเสร็จ ตรง วันที่ปัจจุบัน (วันที่รับชำระเงิน ยังคงเดิม เนื่องจากเป็นเงินที่รับชำระมาก่อนหน้า)
			$receiptDate = $logs_any_time;
		*/
		
		// วันที่ใบเสร็จ ตรงกับวันที่รับชำระ #7461
		$receiptDate = $receiveDate;
	}
	// ถ้าเป็นเงินตามปกติทั่วไป
	else{
		// วันที่ใบเสร็จ ตรง กับวันที่รับชำระ
		$receiptDate = $receiveDate;
	}
	
//---------------------------------------------------------------------- หาเลขที่ใบเสร็จ
$newreceipt = gen_receiptID($receiptDate, $contractID); // ใช้วันที่ออกใบเสร็จ $receiptDate
if($newreceipt == "error"){$status++;}
//-------------------------------------------------------------------------- จบการหาเลขที่ใบเสร็จ
	
//เช็คค่าว่างของตัวแปร เพื่อใช้ในการ insert ลงฐานข้อมูล
$nameco = checknull($nameco); // ชื่อผู้กู้ร่วม
$sentaddress = checknull($sentaddress); // ที่อยู่

$ChannelAmt = $money_Guarantee + $money_Deposit + $amtPenalty;
	//------------------------- หากมีการใส่ เงินพักรอตัดรายการ ( เงินรับฝาก ) หรือ เงินค้ำประกันการชำระหนี้  หรือ  เบี้ยปรับล่าช้า

	if($money_Deposit != '0' || $money_Guarantee != '0' || $res_DebtPenalty != ""){
		if($byChannelPost == pg_getsecuremoneytype($contractID, 1) || $byChannelPost == pg_getholdmoneytype($contractID, 1)){
		
			$contractID3 = "'".$contractID."'";
			
		} else{
		
			$contractID3 = "null";
			
		}
		
		//กรณีเป็นค่าที่ถูก Post มา จะต้องเก็บค่า ref ด้วย
		if($statusLock==1){
			$contractID3=$codePay;
		}
		
		// ถ้ามีเลขที่สัญญาที่ใช้เงิน ให้ใช้เลขที่สัญญานี้อ้างอิง
		if($contractUseMoney != "")
		{
			$ChannelRefToFunction = $contractUseMoney;
			$contractID3 = checknull($ChannelRefToFunction);
		}

		$qry_in2="insert into public.\"thcap_temp_receipt_channel\" (\"receiptID\",\"byChannel\",\"byChannelRef\",\"ChannelAmt\",\"receiveDate\") 
		values ('$newreceipt','$byChannelPost',$contractID3,'$ChannelAmt','$receiveDate') "; // ใช้วันที่รับเงินจริง $receiveDate
		if($resultD=pg_query($qry_in2)){}else{ $status++;}
		
		
		//############################หาประเภทการจ่ายแบบเต็ม
		//หาว่าเป็นการจ่ายธนาคารอะไร
		$qrybankname=pg_query("SELECT \"BAccount\"||'-'||\"BName\" FROM \"BankInt\" where \"BID\"='$byChannelPost'");
		list($bankname)=pg_fetch_array($qrybankname);
		
		if($byChannelPost=='1'){
			$byChannelDetails='เงินสด '.number_format($ChannelAmt,2).' บาท';
		}else if($byChannelPost=='2' OR $byChannelPost=='3' OR $byChannelPost=='4'){
				//ตรวจสอบว่าเป็นการจ่ายผ่านช่องทางเช็คหรือไม่
				if($contractID3!="null" OR $contractID3!=""){
					$qrychkchq=pg_query("select \"thcap_revTranIDToCheque\"($contractID3,'1')");
					list($chkchq)=pg_fetch_array($qrychkchq);
				}
				if($chkchq!=""){ //กรณีจ่ายผ่านเช็ค
					$byChannelDetails="เช็ค $chkchq ".number_format($ChannelAmt,2).' บาท';
				}else{
					//ตรวจสอบว่ามา Bill Payment หรือไม่
					$qrychkbill=pg_query("select \"revTranID\" from finance.thcap_receive_transfer where \"cnID\"='BILL' and \"revTranID\"=$contractID3");
					$numchkbill=pg_num_rows($qrychkbill);
					if($numchkbill>0){
						$byChannelDetails= "ใบนำฝากชำระ (Bill Payment $bankname) ".number_format($ChannelAmt,2).' บาท';
					}else{
						//กรณีเป็นเงินโอน
						$byChannelDetails="เงินโอนผ่านธนาคาร $bankname ".number_format($ChannelAmt,2).' บาท';
					}
				}
		}else if(($contractID3!="null" OR $contractID3!="") and $byChannelPost == '997'){
			//หาชื่อประเภทการจ่ายแบบเต็มของเงินค้ำ
			$qrynamesec=pg_query("SELECT pg_catalog.concat(COALESCE(\"tpFullDesc\", ''::character varying),' ',COALESCE($contractID3, ''::character varying)) FROM account.\"thcap_typePay\" 
			where \"tpID\" = account.\"thcap_getSecureMoneyType\"('$contractID','2')");
			list($namesec)=pg_fetch_array($qrynamesec);
			$byChannelDetails="$bankname ".number_format($ChannelAmt,2)." $namesec";  
		}else if(($contractID3!="null" OR $contractID3!="") and $byChannelPost == '998'){	
			//หาชื่อประเภทการจ่ายแบบเต็มของเงินพัก
			$qrynamesec=pg_query("SELECT pg_catalog.concat(COALESCE(\"tpFullDesc\", ''::character varying),' ',COALESCE($contractID3, ''::character varying)) FROM account.\"thcap_typePay\" 
			where \"tpID\" = account.\"thcap_getHoldMoneyType\"('$contractID','2')");
			list($namesec)=pg_fetch_array($qrynamesec);
			$byChannelDetails="$bankname ".number_format($ChannelAmt,2)." $namesec";	
		}else{
			if($contractID3!="null" OR $contractID3!=""){
				//ตรวจสอบว่ามา Bill Payment หรือไม่
				$qrychkbill=pg_query("select \"revTranID\" from finance.thcap_receive_transfer where \"cnID\"='BILL' and \"revTranID\"=$contractID3");
				$numchkbill=pg_num_rows($qrychkbill);
				if($numchkbill>0){
					$byChannelDetails= "ใบนำฝากชำระ (Bill Payment $bankname) ".number_format($ChannelAmt,2).' บาท';
				}else{
					$byChannelDetails= "$bankname ".number_format($ChannelAmt,2).' บาท ';
				}
			}else{
				$byChannelDetails= "$bankname ".number_format($ChannelAmt,2).' บาท ';
			}
		}
		
		// ถ้าชำระด้วย "เงินสด-STM" หรือ "เงินสด-ADV"
		if($byChannelPost == '990' || $byChannelPost == '991')
		{
			$byChannelDetails = "$byChannelDetails เลขที่สัญญาที่อ้างอิง $ChannelRefToFunction";
		}
		
		$byChannelDetails=checknull($byChannelDetails);
		//############################จบการหาประเภทการจ่ายแบบเต็ม
		
		$in_log="insert into public.\"thcap_temp_receipt_details\" (\"receiptID\",\"doerID\",\"doerStamp\",\"backAmt\",\"nextDueAmt\",\"cusFullname\",\"cusCoFullname\",\"userFullname\",\"addrFull\",\"addrSend\",\"typeReceive\",\"typeDetail\",\"whtRef\",\"byChannelDetails\",\"receiptDate\",\"receiptTime\", \"receiptRemark\") 
		values  ('$newreceipt','$username','$logs_any_time',null,null,'$name3',$nameco,'$userfullname','$address',$sentaddress,$selectVice2,$viceDetail2,null,$byChannelDetails,'$receiptDate'::date,'$receiptDate'::time without time zone, $reasontextother)"; // ใช้วันที่ออกใบเสร็จ $receiptDate
		if($resultLog=pg_query($in_log)); else $status++;
	}	

	if($money_Deposit != '0'){ //เงินพักรอการตัด	
	
		if($money_Deposit1 = pg_query("select account.\"thcap_getHoldMoneyType\"('$contractID')")); else $status++;
		$money_Deposit1 = pg_fetch_result($money_Deposit1,0);
		if($typesql = pg_query("SELECT \"tpDesc\", \"tpFullDesc\" FROM account.\"thcap_typePay\" where \"tpID\" = '$money_Deposit1'")); else $status++;
		$retype = pg_fetch_array($typesql);
		$tpDesc = $retype['tpDesc'];
		$tpFullDesc = $retype['tpFullDesc'];
		
		$inser_money_Deposit = "INSERT INTO thcap_temp_receipt_otherpay(
				\"receiptID\", \"debtID\", \"netAmt\", \"vatAmt\", \"debtAmt\", \"whtAmt\", 
				\"typePayID\", \"typePayRefValue\", \"tpDesc\", \"tpFullDesc\")
		VALUES ('$newreceipt',null,'$money_Deposit','0','$money_Deposit','0', 
				'$money_Deposit1','$contractID','$tpDesc','$tpFullDesc')";
		if($reinser_money_Deposit = pg_query($inser_money_Deposit)){}else{ $status++ ;}

	}		
	
	if($money_Guarantee != '0'){ //เงินค้ำประกัน	

		if($money_Guarantee1 = pg_query("select account.\"thcap_getSecureMoneyType\"('$contractID')")){}else{$status++;}
		$money_Guarantee1 = pg_fetch_result($money_Guarantee1,0);
		if($typesql = pg_query("SELECT \"tpDesc\", \"tpFullDesc\" FROM account.\"thcap_typePay\" where \"tpID\" = '$money_Guarantee1'")); else $status++;
		$retype = pg_fetch_array($typesql);
		$tpDesc = $retype['tpDesc'];
		$tpFullDesc = $retype['tpFullDesc'];
		
		$inser_money_Guarantee = "INSERT INTO thcap_temp_receipt_otherpay(
				\"receiptID\", \"debtID\", \"netAmt\", \"vatAmt\", \"debtAmt\", \"whtAmt\", 
				\"typePayID\", \"typePayRefValue\", \"tpDesc\", \"tpFullDesc\")
		VALUES ('$newreceipt',null,'$money_Guarantee','0','$money_Guarantee','0', 
				'$money_Guarantee1','$contractID','$tpDesc','$tpFullDesc')";
		if($reinser_money_Guarantee = pg_query($inser_money_Guarantee)){}else{$status++;}	
	}
	
	if($res_DebtPenalty != "")
	{ // เบี้ยปรับล่าช้า
		if($typesql = pg_query("SELECT \"tpDesc\", \"tpFullDesc\" FROM account.\"thcap_typePay\" where \"tpID\" = '$res_getIntFineType'")); else $status++;
		$retype = pg_fetch_array($typesql);
		$tpDesc = $retype['tpDesc'];
		$tpFullDesc = $retype['tpFullDesc'];
		
		$inser_money_DebtPenalty = "INSERT INTO thcap_temp_receipt_otherpay(
				\"receiptID\", \"debtID\", \"netAmt\", \"vatAmt\", \"debtAmt\", \"whtAmt\", 
				\"typePayID\", \"typePayRefValue\", \"tpDesc\", \"tpFullDesc\")
		VALUES ('$newreceipt','$res_DebtPenalty','$amtPenalty','0','$amtPenalty','0', 
				'$res_getIntFineType','$use_fpayrefvalue','$tpDesc','$tpFullDesc')";
		if($reinser_money_DebtPenalty = pg_query($inser_money_DebtPenalty)){}else{$status++;}	
	}
}
}
    
if($status == 0)
{
	//ACTIONLOG
		if($sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) รับชำระเงิน', '$logs_any_time')")); else $status++;
	//ACTIONLOG---
	
	pg_query("COMMIT");
	
	if($chk != "" || $appent == "on" || $money_Deposit != '0' || $money_Guarantee != '0')
	{
		echo "<center>บันทึกสมบูรณ์</center>";
	}
	else
	{
		echo "<center>กรุณาเลือกรายการก่อน!!</center>";
	}
	
	//print ใบกำกับด้วย ค้นหาใบกำกับ (ของค่าอื่นๆ)
	if($qryvat=pg_query("SELECT \"thcap_receiptIDTotaxinvoiceID\"('$newreceipt')")){}else{ $status++; };
	list($vatreceipt)=pg_fetch_array($qryvat);
	
	// ให้ตรวจสอบก่อนว่าเคยส่งจดหมายไปแล้วหรือยัง
	$qry_sentVat = pg_query("select \"contractID\" from \"vthcap_letter\" where \"detailRef\" = '$vatreceipt' ");
	$sentVat = pg_num_rows($qry_sentVat);
	
	if($chk != "" && $chk[0] != "")
	{
		echo "<script type=\"text/javascript\">";
		echo "javascript:popU('print_receipt_pdf.php?receiptID=$newreceipt&contractID=$contractID2&typepdf=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";
		
		if($vatreceipt!="" && $printvat==1 && $sentVat==0){ // ถ้ามีเลขที่ใบกำกับ และติ๊กเลือกว่าจะให้ปริ้น และยังไม่เคยส่งจดหมายมาก่อน
			echo "javascript:popU('../thcap/print_receipt_v_inv_pdf.php?receiptID=$vatreceipt','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";		
		}
		echo "</script>";
	}else if($money_Guarantee != '0' || $money_Deposit != '0' || $res_DebtPenalty != ""){ //หากเป็นการเพิ่มเงินรอตัดหรือเงินค้ำประกัน หรือ เบี้ยปรับล่าช้า ให้แสดง PDF ด้วย
		echo "<script type=\"text/javascript\">";
		echo "javascript:popU('print_receipt_pdf.php?receiptID=$newreceipt&contractID=$contractID2&typepdf=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";
		if($vatreceipt!="" and $printvat==1){
			echo "javascript:popU('../thcap/print_receipt_v_inv_pdf.php?receiptID=$newreceipt','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";		
		}
		echo "</script>";
	}
	
	if($appent == "on" || $payAdviser == "on")
	{
		//print ใบกำกับด้วย ค้นหาใบกำกับ (ค่างวด)
		if($qryvat2=pg_query("SELECT \"thcap_receiptIDTotaxinvoiceID\"('$newreceipt2')")){}else{ $status++; };
		list($vatreceipt2)=pg_fetch_array($qryvat2);
		
		// ให้ตรวจสอบก่อนว่าเคยส่งจดหมายไปแล้วหรือยัง
		$qry_sentVat2 = pg_query("select \"contractID\" from \"vthcap_letter\" where \"detailRef\" = '$vatreceipt2' ");
		$sentVat2 = pg_num_rows($qry_sentVat2);
	
		if($chk_con_type == "HIRE_PURCHASE") // ถ้าเป็นสัญญา HIRE_PURCHASE ต้องหาเลขที่ใบกำกับใหม่ เนื่องจากไม่มีตัวเชื่อมเหมือนสัญญาประเภทอื่น
		{
			$vatreceipt2 = $newreceipt2; // ถ้าเป็น HIRE_PURCHASE ให้ส่งเลขที่ใบเสร็จไปก่อน แล้วไฟล์ใบกำกับภาษีจะหาเองว่าต้องมีใบกำกับภาษีของงวดอะไรบ้าง
			
			// งวดที่ชำระ
			$qry_taxinvoice = pg_query("select \"debtID\",\"typePayRefValue\" from \"thcap_temp_receipt_otherpay\" where \"receiptID\" = '$vatreceipt2' ");
			
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
			
			// หาใบกำกับในกลุ่มทั้งหมด
			$qry_allTax = pg_query("select a.\"taxinvoiceID\" as \"taxinvoiceID\" from \"thcap_temp_taxinvoice_otherpay\" a, \"thcap_temp_taxinvoice_details\" b, \"thcap_temp_otherpay_debt\" c
									where a.\"taxinvoiceID\" = b.\"taxinvoiceID\" and a.\"debtID\" = c.\"debtID\"
									and c.\"contractID\" = '$contractID' and a.\"typePayID\" = account.\"thcap_mg_getMinPayType\"(c.\"contractID\") $wherePeriod ");
			$row_allTax = pg_num_rows($qry_allTax);
			
			if($row_allTax == 1)
			{ // ถ้ามี 1 งวด
				// เลขที่ใบกำกับ
				$vatreceiptTrue = pg_fetch_result($qry_allTax,0);
				
				// ให้ตรวจสอบก่อนว่าเคยส่งจดหมายไปแล้วหรือยัง
				$qry_sentVat2 = pg_query("select \"contractID\" from \"vthcap_letter\" where \"detailRef\" = '$vatreceiptTrue' ");
				$sentVat2 = pg_num_rows($qry_sentVat2);
			}
			elseif($row_allTax > 1)
			{ // ถ้ามากกว่า 1 งวด
				$sentVat2 = 0;
				while($rec_allTax = pg_fetch_array($qry_allTax))
				{
					$vatreceiptTrue = $rec_allTax["taxinvoiceID"];
					
					// ให้ตรวจสอบก่อนว่าเคยส่งจดหมายไปแล้วหรือยัง
					$qry_sentVat2 = pg_query("select \"contractID\" from \"vthcap_letter\" where \"detailRef\" = '$vatreceiptTrue' ");
					$chkSent = pg_num_rows($qry_sentVat2);
					
					if($chkSent != 0)
					{ // ถ้าเคยส่งจดหมายแล้ว
						$sentVat2++;
					}
				}
				
				if($row_allTax != $sentVat2)
				{ // ถ้ายังส่งใบแจ้งหนี้ไม่ครบ
					$sentVat2 = 0;
				}
			}
			else
			{
				$sentVat2 = "";
			}
			
			echo "<script type=\"text/javascript\">";
			echo "javascript:popU('print_receipt_pdf.php?receiptID=$newreceipt2&contractID=$contractID2&typepdf=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";
			if($vatreceipt2!="" && $printvat==1 && $sentVat2=="0"){ // ถ้ามีเลขที่ใบกำกับ และติ๊กเลือกว่าจะให้ปริ้น และยังไม่เคยส่งจดหมายมาก่อน
				echo "javascript:popU('../thcap/print_receipt_v_inv_pdf.php?receiptID=$vatreceipt2&grouptax=yes&notSentLetterOnly=yes','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";		
			}
			echo "</script>";
		}
		elseif($chk_con_type == "LEASING")
		{
			echo "<script type=\"text/javascript\">";
			echo "javascript:popU('print_receipt_pdf.php?receiptID=$newreceipt2&contractID=$contractID2&typepdf=2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";
			if($vatreceipt2!="" && $printvat==1 && $sentVat2==0){ // ถ้ามีเลขที่ใบกำกับ และติ๊กเลือกว่าจะให้ปริ้น และยังไม่เคยส่งจดหมายมาก่อน
				echo "javascript:popU('../thcap/print_receipt_v_inv_pdf.php?receiptID=$vatreceipt2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";		
			}
			echo "</script>";
		}
		else
		{
			echo "<script type=\"text/javascript\">";
			if($newreceipt2 != ""){echo "javascript:popU('print_receipt_pdf.php?receiptID=$newreceipt2&contractID=$contractID2&typepdf=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";}
			if($vatreceipt2!="" && $printvat==1 && $sentVat2==0){ // ถ้ามีเลขที่ใบกำกับ และติ๊กเลือกว่าจะให้ปริ้น และยังไม่เคยส่งจดหมายมาก่อน
				echo "javascript:popU('../thcap/print_receipt_v_inv_pdf.php?receiptID=$vatreceipt2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740');";		
			}
			echo "</script>";
		}
	}
	
	//echo "<meta http-equiv='refresh' content='2; URL=Payments_history.php?ConID=$contractID2'>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
}
else
{
	pg_query("ROLLBACK");
	echo "<br><center>บันทึกผิดพลาด</center>";
	if($error_discount != ""){echo "<br><br><center>$error_discount</center>";}
	//echo "<meta http-equiv='refresh' content='5; URL=Payments_history.php?ConID=$contractID2'>";
	//echo "<meta http-equiv='refresh' content='10; URL=frm_Index.php'>";
}
?>