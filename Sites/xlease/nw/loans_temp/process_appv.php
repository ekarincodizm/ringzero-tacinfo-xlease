<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
require("../Cal_Installments/function/cal_tools.php");
header('Content-type:text/html; charset=utf-8');
?>

<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<?php
$id_user=$_SESSION["av_iduser"];
$logs_any_time = nowDateTime();
$statusAppv="check";
$query = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$id_user' ");
while($result = pg_fetch_array($query))
{
	$username = $result["username"]; // username ที่ทำรายการ
}

$query_level = pg_query("select * from public.\"fuser\" where \"id_user\" = '$id_user' ");
while($result_level = pg_fetch_array($query_level))
{
	$emplevel = $result_level["emplevel"]; // level ของ พนักงาน
}

$contractAutoID = pg_escape_string($_GET["contractAutoID"]);

$appv = pg_escape_string($_GET["appv"]);
$getDoerStamp = pg_escape_string($_GET["doerStamp"]); // วันเวลาที่ทำรายการขอเพิ่ม
if($contractAutoID=="")
{
	$contractAutoID = pg_escape_string($_POST["contractAutoID"]);
	$getDoerStamp = pg_escape_string($_POST["doerStamp"]);
	$note=pg_escape_string($_POST['note']);
	$appvchq=pg_escape_string($_POST["appv"]);
	if($appvchq=="อนุมัติ"){ 
		$appv ='1';//กดอนุมัติ
	}else{
		$appv ='2';//กดไม่อนุมัติ
	}
}

//ตรวจสอบเบื้องต้นว่ารายการนี้อนุมัติหรือยังเพื่อป้องกันการอนุมัติซ้ำ
$querychk = pg_query("select * from public.\"thcap_contract_temp\" where \"Approved\" is null and \"editNumber\" = '0' and \"autoID\" = '$contractAutoID'");
$numchk = pg_num_rows($querychk);
if($numchk>0)
{ //แสดงว่ายังไม่อนุมัติ

	// หาเลขที่สัญญา
	$qry_S_ContractID = pg_query("select \"contractID\",\"conType\",\"conCredit\" from public.\"thcap_contract_temp\" where \"autoID\" = '$contractAutoID' ");
	while($resultCon = pg_fetch_array($qry_S_ContractID))
	{
		$contractID = $resultCon["contractID"]; // เลขที่สัญญา
		$conType = $resultCon["conType"]; // รหัสประเภทสินเชื่อ
		$ISconCredit = $resultCon["conCredit"]; // ถ้ามีค่า แสดงว่าเป็นสัญญาวงเงิน
	}
	
	// ชื่อประเภทสินเชื่อแบบเต็ม
	$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$conType') ");
	$chk_con_type = pg_fetch_result($qry_chk_con_type,0);

	//--------------- เริ่มบันทึกข้อมูล
	pg_query("BEGIN");
	$status = 0;
	$note= checknull($note);
	if($appv == "2") // ถ้าไม่อนุมัติ
	{	
		if($chk_con_type == "FACTORING" && $ISconCredit == "")
		{
			$sql_no_appv = "select thcap_process_approve_factoring('$contractAutoID','$id_user','0',$note)";
			if($resultNO = pg_query($sql_no_appv))
			{}
			else
			{
				$status++;
			}
		}
		else
		{
			$resucheck="true";
			$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$id_user' ");
			$leveluser = pg_fetch_array($query_leveluser);
			$emplevelappv=$leveluser["emplevel"];
			if($emplevelappv<=1){}
			else
			{				
				$qryappvID=pg_query("select \"appvID\" from \"thcap_contract_check_temp\" where \"ID\"='$contractAutoID'");	
				while($re_ApprID = pg_fetch_array($qryappvID))
				{
					$appv=$re_ApprID["appvID"];
					if($appv==$id_user){$resucheck="false";}
				}
				if($resucheck=="true")
				{
					$query_doerUser = pg_query("select \"doerUser\" from public.\"thcap_contract_temp\" where \"autoID\" = '$contractAutoID' ");
					$re_doerUser = pg_fetch_array($query_doerUser);
					$doerUser=$re_doerUser["doerUser"];
					if($doerUser==$id_user){$resucheck="false1";}			
				}
			}
			
			if(($resucheck=="false")or ($resucheck=="false1")){ }
			else
			{	
				
				$sql_no_appv = "update public.\"thcap_contract_temp\" set \"Approved\" = 'false' , \"appvUser\" = '$id_user' , \"appvStamp\" = '$logs_any_time',
								\"conNote\"=$note where \"autoID\" = '$contractAutoID' and \"Approved\" is null ";
				if($resultNO = pg_query($sql_no_appv))
				{}
				else
				{
					$status++;
				}
			
				$query_main = pg_query("select * from public.\"thcap_contract_temp\" where \"autoID\" = '$contractAutoID' ");
				while($result = pg_fetch_array($query_main))
				{
					$addrTempID = $result["addrTempID"]; // รหัสที่อยู่ของตาราง thcap_addrContractID_temp
				}
			
				// ไม่อนุมัติที่อยู่ด้วย
				$sql_no_addr = "update public.\"thcap_addrContractID_temp\" set \"statusApp\" = '3' , \"appUser\" = '$id_user' , \"appStamp\" = '$logs_any_time' 
										where \"tempID\" = '$addrTempID' ";
				if($resultAddr = pg_query($sql_no_addr))
				{}
				else
				{
					$status++;
				}
			
				// หาเลขที่สัญญา
				$qry_S_ContractID = pg_query("select \"contractID\",\"conType\" from public.\"thcap_contract_temp\" where \"autoID\" = '$contractAutoID' ");
				while($resultCon = pg_fetch_array($qry_S_ContractID))
				{
					$contractID = $resultCon["contractID"]; // เลขที่สัญญา
					$conType = $resultCon["conType"]; // รหัสประเภทสินเชื่อ
				}
			
				// ชื่อประเภทสินเชื่อแบบเต็ม
				$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$conType') ");
				$chk_con_type = pg_fetch_result($qry_chk_con_type,0);
			
				// ไม่อนุมัติตารางการผ่อนชำระด้วย
				$sql_no_payTerm = "update account.\"thcap_payTerm_temp\" set \"Approved\" = 'false' , \"appvID\" = '$id_user' , \"appvStamp\" = '$logs_any_time' 
										where \"contractID\" = '$contractID' and \"doerStamp\" = '$getDoerStamp' and \"Approved\" is null ";
				if($resultPayTerm = pg_query($sql_no_payTerm))
				{}
				else
				{
					$status++;
				}
			
				// ไม่อนุมัติตารางการขอผูกบิลกับสัญญา FA ด้วย
				$sql_no_billFA = "update \"thcap_contract_fa_bill_temp\" set \"Approved\" = 'false' , \"appvID\" = '$id_user' , \"appvStamp\" = '$logs_any_time' 
										where \"contractID\" = '$contractID' and \"doerStamp\" = '$getDoerStamp' and \"Approved\" is null ";
				if($resultBillFA = pg_query($sql_no_billFA))
				{}
				else
				{
					$status++;
				}

				// ถ้าเป็นสัญญา HP,OL,FL
				//if($conType == "HP" OR $conType == "OL" OR $conType == "FL"){
				if($chk_con_type == "HIRE_PURCHASE" OR $chk_con_type == "LEASING")
				{
					$qry_asset = pg_query("select * from \"thcap_contract_asset_temp\" where \"contractID\" = '$contractID' and \"doerStamp\" = '$getDoerStamp' and \"Approved\" is null"); // ดึงข้อมูลของสินค้าที่ผูกกับสัญญา
					$row_asset = pg_num_rows($qry_asset);
					if($row_asset > 0)
					{ //ถ้ามีการผูกกันของสินค้ากับสัญญาจริง
						while($re_asset = pg_fetch_array($qry_asset))
						{
							$assetDetailID = $re_asset["assetDetailID"]; //รหัสสินค้า
							$autoID = $re_asset["autoID"]; //รหัสการผูกสัญญาเป็นเลข running
							
							//เปลี่ยนสถานะในตาราง temp ว่าไม่อนุมัติ
							$qry_up_asset_temp = pg_query("UPDATE thcap_contract_asset_temp 
															SET 
																\"appvID\"='$id_user', 
																\"appvStamp\"='$logs_any_time',
																\"Approved\"= 'FALSE'
															WHERE 
																\"autoID\"= '$autoID'");
							if($qry_up_asset_temp){}else{ $status++; echo $qry_up_asset_temp;}
							
							//เปลี่ยนสถานะสินค้าให้อยู่ในสถานะว่าง
							$qry_up_asset_biz = pg_query("UPDATE thcap_asset_biz_detail
															SET 
																\"materialisticStatus\" = '1',
																\"as_status_id\" = '1'
															WHERE 
																\"assetDetailID\" = '$assetDetailID'");
							if($qry_up_asset_biz){}else{ $status++; echo $qry_up_asset_biz;}
						}		
					}			
				}
			}
			
			//ทำการไม่อนุมัติ การตั้งหนี้ที่เกิดจาก เมนู การผูกสัญญา
			$qry_check=pg_query("select \"debtID\" from thcap_temp_otherpay_debt where \"contractID\"='$contractID' and \"ShowAppvStatus\"='0' and \"create_ref_contractID\" = '$contractAutoID' and \"debtStatus\" = '9' ");
			while($result = pg_fetch_array($qry_check))
			{
				$debtIDdebt=$result["debtID"];
				$insdebt=pg_query("SELECT thcap_process_setdebtloan(null,null,null,null,null,null,null,'2','$debtIDdebt','0','$id_user')");
				list($status1) = pg_fetch_array($insdebt);
				if($status1=='t'){}
				else{$status++;}
			}
		}
	}
	elseif($appv == "1") // ถ้าอนุมัติ
	{	
		//ตรวจสอบว่า  ถูกต้อง หรือไม่
		$qryCID=pg_query("select \"autoID\" from \"thcap_contract_check_temp\" where \"ID\"='$contractAutoID'");	
		$numCID = pg_num_rows($qryCID);
		if($numCID>0)
		{		
			$qryID=pg_query("select max(\"autoID\") as \"ID\" from \"thcap_contract_check_temp\" where \"ID\"='$contractAutoID'");
			$re_ID = pg_fetch_array($qryID);
			$id=$re_ID["ID"];
			$qryAppr=pg_query("select \"Approved\" from \"thcap_contract_check_temp\" where \"autoID\"='$id'");
			$re_Appr = pg_fetch_array($qryAppr);
			$Appr=$re_Appr["Approved"];
			if($Appr=="1"){}
			else{
			$statusAppv="checktruebutno";
			$status=-1;}
		}
		else
		{   
				$statusAppv="checkfalse";
				$status=-1;
		}	
		
		if($status==0)
		{
			if($chk_con_type == "FACTORING" && $ISconCredit == "")
			{	
				
				
				$sql_no_appv = "select thcap_process_approve_factoring('$contractAutoID','$id_user','1',$note)";
				if($resultNO = pg_query($sql_no_appv))
				{}
				else
				{
					//$status++;
				}
			}
			else
			{
				//level ของ คนอนุมัตูกสัญญา
				$resucheck="true";
				$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$id_user' ");
				$leveluser = pg_fetch_array($query_leveluser);
				$emplevelappv=$leveluser["emplevel"];
				if($emplevelappv<=1){}
				else
				{				
					$qryappvID=pg_query("select \"appvID\" from \"thcap_contract_check_temp\" where \"ID\"='$contractAutoID'");	
					while($re_ApprID = pg_fetch_array($qryappvID))
					{
						$appv=$re_ApprID["appvID"];
						if($appv==$id_user){$resucheck="false";}
					}
					
					if($resucheck=="true"){
						$query_doerUser = pg_query("select \"doerUser\" from public.\"thcap_contract_temp\" where \"autoID\" = '$contractAutoID' ");
						$re_doerUser = pg_fetch_array($query_doerUser);
						$doerUser=$re_doerUser["doerUser"];
						if($doerUser==$id_user){$resucheck="false1";}			
					}
				}
				
				if(($resucheck=="false")or($resucheck=="false1")){ }
				else
				{
					$sql_yes_appv = "update public.\"thcap_contract_temp\" set \"Approved\" = 'true' , \"appvUser\" = '$id_user' , \"appvStamp\" = '$logs_any_time', 
									\"conNote\"=$note	where \"autoID\" = '$contractAutoID' and \"Approved\" is null ";
					if($resultYES = pg_query($sql_yes_appv))
					{}
					else
					{
						$status++;
					}
			
					$query_main = pg_query("select * from public.\"thcap_contract_temp\" where \"autoID\" = '$contractAutoID' ");
					while($result = pg_fetch_array($query_main))
					{
						$contractID = $result["contractID"]; // เลขที่สัญญา
						$conType = $result["conType"]; // รหัสประเภทสินเชื่อ
						$conLoanAmt = $result["conLoanAmt"]; // จำนวนเงินกู้
						$conCredit = $result["conCredit"]; // วงเงินสินเชื่อ
						$doerUser = $result["doerUser"]; // ผู้ทำรายการ
						$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
						$conCompany = $result["conCompany"]; // รหัสบริษัท
						$conLoanIniRate = $result["conLoanIniRate"]; // อัตราดอกเบี้ยที่ตกลงตอนแรก
						$conLoanMaxRate = $result["conLoanMaxRate"]; // อัตราดอกเบี้ยสูงสุด
						$conInvoicePeriod = $result["conInvoicePeriod"]; // จำนวนวันที่ให้ส่งใบแจ้งหนี้ก่อนครบกำหนด
						$conTerm = $result["conTerm"]; // ระยะเวลาผ่อนชำระคืนเงินกู้ (เดือน)
						$conMinPay = $result["conMinPay"]; // จำนวนเงินผ่อนขั้นต่ำต่อ Due
						$conExtRentMinPay = $result["conExtRentMinPay"]; // จำนวนเงินผ่อนขั้นต่ำต่อ Due
						$conPenaltyRate = $result["conPenaltyRate"]; // ค่าติดตามทวงถามปัจจุบัน
						$conDate = $result["conDate"]; // วันที่ทำสัญญา
						$conStartDate = $result["conStartDate"]; // วันที่รับเงินที่ขอกู้
						$conEndDate = $result["conEndDate"]; // วันที่สิ้นสุดการกู้ที่ระบุไว้ในสัญญา
						$conFirstDue = $result["conFirstDue"]; // Due แรก
						$conRepeatDueDay = $result["conRepeatDueDay"]; // Due วันที่ชำระของทุกๆเดือน เช่น 01 หรือ 28
						$conFreeDate = $result["conFreeDate"]; // วันที่พ้นกำหนดห้ามปิดบัญชีก่อนกำหนด (Default = กึ่งหนึ่งของระยะเวลาทั้งสัญญา)
						$conClosedDate = $result["conClosedDate"]; // วันที่ปิดบัญชีจริง
						$conClosedFee = $result["conClosedFee"]; // % ค่าปรับปิดบัญชีก่อนกำหนด คิดจากยอดกู้
						$conStatus = $result["conStatus"]; // NCB...
						$conFlow = $result["conFlow"]; // สถานะสัญญา / internal
						$rev = $result["rev"]; // เปลี่ยนแปลงสัญญาครั้งที่
						$conCreditRef = $result["conCreditRef"]; // สัญญากู้นี้ใช้วงเงินไหน วงเงินเท่าไหร่
						$CusIDarray = $result["CusIDarray"]; // ประเภทลูกค้า และ รหัสลูกค้า
						$addrTempID = $result["addrTempID"]; // รหัสที่อยู่ของตาราง thcap_addrContractID_temp
						$editNumber = $result["editNumber"]; // จำนวนครั้งที่แก้ไข
						$conFinanceAmount = $result["conFinanceAmount"]; // ยอดจัด
						$conGuaranteeAmt = $result["conGuaranteeAmt"];	//จำนวนเงินค้ำประกันสัญญา
						$conDownToDealer = $result["conDownToDealer"];	//ชำระเงินดาวน์ให้ผู้ขาย
						$conDownToFinance = $result["conDownToFinance"];	//ชำระเงินดาวน์ให้ไฟแนนซ์
						$conDownToFinanceVat = $result["conDownToFinanceVat"]; //VAT ของเงินดาวน์
						$conFinAmtExtVat = $result["conFinAmtExtVat"]; // ยอดจัด/ยอดลงทุน (ก่อนภาษี)
						$conFineRate = $result["conFineRate"];	// % เบี้ยปรับผิดนัด
						$conSubType_serial = $result["conSubType_serial"]; // ประเภทสัญญาย่อย
						$conResidualValue = $result["conResidualValue"]; // ค่าซาก
						$conPLIniRate = $result["conPLIniRate"]; // ค่าธรรมเนียมการใช้วงเงินสินเชื่อส่วนบุคคล
						
						$conNumExceptDays = $result["conNumExceptDays"]; // จำนวนวันที่ผ่อนผันเรื่องค่าติดตามทวงถาม และการปรับอัตราดอกเบี้ย นับจาก Due
						$conNumNTDays = $result["conNumNTDays"]; // จำนวนวันที่ผ่อนผันการออกหนังสือเตือนหนี้ NT นับจาก Due
						$conNumSueDays = $result["conNumSueDays"]; // จำนวนวันที่ผ่อนผัน ก่อนการฟ้องร้อง นับจาก Due
						$sendNCB = $result["sendNCB"]; // รายการนี้ต้องส่ง NCB หรือไม่
						
						$conResidualValueIncVat = $result["conResidualValueIncVat"]; // ค่าซากรวมภาษีมูลค่าเพิ่ม
						$conLeaseIsForceBuyResidue = $result["conLeaseIsForceBuyResidue"]; // บังคับซื้อซาก
						$conLeaseBaseFinanceForCal = $result["conLeaseBaseFinanceForCal"]; // ยอดจัดที่ใช้ในการคิดดอกเบี้ย
					
						// ชื่อประเภทสินเชื่อแบบเต็ม
						$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$conType') ");
						$chk_con_type = pg_fetch_result($qry_chk_con_type,0);
					}
			
					$qry_chkContract = pg_query("select * from public.\"thcap_contract\" where \"contractID\" = '$contractID' ");
					$row_chkContract = pg_num_rows($qry_chkContract);
					if($row_chkContract > 0)
					{
						$status++;
						echo $contractID;
						echo "ไม่สามารถดำเนินการได้เนื่องจากมีเลขที่สัญญา $contractID อยู่แล้ว<br>";
					}
			
					$contractID_forBillFA = $contractID; // เลขที่สัญญาที่ใช้ในการเช็คเพิ่มบิล
			
					// อนุมัติตารางการผ่อนชำระด้วย
					$sql_yes_payTerm = "update account.\"thcap_payTerm_temp\" set \"Approved\" = 'true' , \"appvID\" = '$id_user' , \"appvStamp\" = '$logs_any_time' 
											where \"contractID\" = '$contractID' and \"doerStamp\" = '$getDoerStamp' and \"Approved\" is null ";
					if($resultPayTerm = pg_query($sql_yes_payTerm))
					{}
					else
					{
						$status++;
					}
			
					// เพิ่มข้อมูลการผ่อนชำระ
					$qry_S_payTerm = pg_query("select * from account.\"thcap_payTerm_temp\" where \"contractID\" = '$contractID' and \"doerStamp\" = '$getDoerStamp' and \"Approved\" = 'true' order by \"ptNum\" ");
					while($res_S_payTerm = pg_fetch_array($qry_S_payTerm))
					{
						$ptNum = $res_S_payTerm["ptNum"];// งวดที่
						$ptDate = $res_S_payTerm["ptDate"]; // วันที่กำหนดชำระ
						$ptMinPay = $res_S_payTerm["ptMinPay"]; // ขั้นต่ำที่จะต้องจ่ายในกำหนดชำระครั้งนี้
						
						$ptNum = checknull($ptNum);
						$ptDate = checknull($ptDate);
						$ptMinPay = checknull($ptMinPay);
						
						$sql_addPayTerm = "insert into account.\"thcap_payTerm\"(\"contractID\",\"ptNum\",\"ptDate\",\"ptMinPay\",\"ptRev\") values('$contractID', $ptNum, $ptDate, $ptMinPay, '1')";
						if($result = pg_query($sql_addPayTerm))
						{}
						else
						{
							$status++;
						}
					}
			
					if($chk_con_type == "JOINT_VENTURE")
					{
						$conFirstDue = str_replace("'","",$conFirstDue); // ตัวเครื่องหมาย ' วันที่จ่ายของเดือนแรก ออก
						$conDate = str_replace("'","",$conDate); // ตัวเครื่องหมาย ' วันที่ทำสัญญา ออก
						$contractID = str_replace("'","",$contractID); // ตัวเครื่องหมาย ' ในเลขที่สัญญาออก
						$conMinPay = str_replace("'","",$conMinPay); // ตัวเครื่องหมาย ' เงินประกัน (ค่างวด) ออก
						
						$length = str_replace("'","",$conTerm); // ระยะเวลา (เดือน)
						$payday = str_replace("'","",$conRepeatDueDay); // ชำระทุกวันที่
						$int_normal = str_replace("'","",$conLoanIniRate); // อัตราดอกเบี้ย
						$credit = str_replace("'","",$conLoanAmt); // จำนวนเงินต้น
						
						list($pay_year,$pay_month,$pay_date) = explode("-",$conFirstDue); //ตัดเอาวันที่จ่ายของเดือนแรก
						list($yy,$mm,$dd) = explode("-",$conDate); //ตัดเอาวันที่ทำสัญญา
						
						//-===================================================================================================-
						//			คำนวณหายอดจ่ายขั้นต่ำ
						//-===================================================================================================-
						$start =  MKTIME(0,0,0,$mm, $dd, $yy);
						$start_date = $yy."-".$mm."-".$dd;
						
						$last =  MKTIME(0,0,0,$mm+$length, $payday, $yy);
						$date1 = $last-$start;
						$date1 = round(($date1/60/60/24),4);
						$date1 = $date1/$length;
						$r = 1+(($int_normal/36500)*($date1));
						
						$min_pay =  round($credit*(pow($r,$length)*(1-$r))/(1-pow($r,$length)),2);
						$min_pay2 = $min_pay;
						$p = 0.1 ;  // %minpay ที่เพิ่ม
						$min_pay = $min_pay+$min_pay*($p/100);
						$last = func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay,$conFirstDue);

						while($last > 0){			
							$p=$p+0.1;
							$min_pay = $min_pay2+($min_pay2*($p/100));
							 $last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay,$conFirstDue);
						}
						
						$min_pay2 = floor($min_pay/10)*10 ;
						$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay2,$conFirstDue);
						
						$halfminpay = ($min_pay2/2)*(-1);
						$valueminus = (-5);
						$valueplus = 5;
						if($last < $halfminpay OR $last > 0){
							while($stop != 1){
								if($last < $halfminpay AND $last < 0){
									$valueminus = $valueminus - (-5);
									$min_pay2 = (ceil($min_pay/10)*10)-$valueminus;	
								}else if($last > 0 AND $last > $halfminpay ){
									$valueplus = $valueplus + 5;
									$min_pay2 = (ceil($min_pay/10)*10)+$valueplus;	
								}else{
									$stop = 1;
								}		
									$last =  func_mort_check_valid_accounting_test($credit,$int_normal,$start_date,$payday,$length,$min_pay2,$conFirstDue);	
							}
						}
						
						// min_pay2 คือ ยอดผ่อนจ่ายขั้นต่ำ
						
						$conMonthlyAdviserFee = $conMinPay - $min_pay2; // ค่าที่ปรึกษากิจการร่วมค้า
						
						//-===================================================================================================-
						//			จบการคำนวณหายอดจ่ายขั้นต่ำ
						//-===================================================================================================-
						
						// เพิ่มข้อมูล ตั้งหนี้ค่าที่ปรึกษากิจการร่วมค้า และ อนุมัติหนี้อัตโนมัติ
						$qry_S_payTerm = pg_query("select * from account.\"thcap_payTerm\" where \"contractID\" = '$contractID' order by \"ptNum\" ");			
						while($res_S_payTerm = pg_fetch_array($qry_S_payTerm))
						{
							$ptNum = $res_S_payTerm["ptNum"];// งวดที่
							$ptDate = $res_S_payTerm["ptDate"]; // วันที่กำหนดชำระ
							
							// ตรวจสอบก่อนว่า ต้องแยก vat หรือไม่
							$qry_ableVAT = pg_query("select \"ableVAT\" from account.\"thcap_typePay\" where \"tpID\" = 'D112' ");
							$res_ableVAT = pg_fetch_result($qry_ableVAT,0);
							
							if($res_ableVAT == "1")
							{ // ถ้าต้องแยก vat
								// หามูลค่า vat //$minpayVat = $conMonthlyAdviserFee * $res_S_vat / (100 + $res_S_vat);
								$qry_minpayVat = pg_query("select cal_rate_or_money('VAT', '$ptDate', '$conMonthlyAdviserFee', '1')");
								$minpayVat = pg_fetch_result($qry_minpayVat,0);
								
								// หาจำนวนเงินหนี้ (จำนวนเงินก่อน vat) //$minpayNet = $conMonthlyAdviserFee - $minpayVat;
								$qry_minpayNet = pg_query("select cal_rate_or_money('VAT', '$ptDate', '$conMonthlyAdviserFee', '2')");
								$minpayNet = pg_fetch_result($qry_minpayNet,0);
							}
							else
							{
								$minpayNet = $conMonthlyAdviserFee;
								$minpayVat = 0.00;
							}
							
							//หาประเภทการจ่ายที่เป็นเงินกู้
							$qrydesc=pg_query("select \"tpDesc\"||' '||\"tpFullDesc\" from account.\"thcap_typePay\"
							where \"tpID\" = 'D112' ");
							list($tpDesc)=pg_fetch_array($qrydesc);
							
							$debtRemark="$tpDesc $ptNum ของสัญญาเลขที่ $contractID";
								
							// อนุมัติ และ approve โดย user 000 วันเวลาตามที่ถูก gen และให้ debtIsOther = 0 หมายความว่าเป็นไม่ใช่หนี้อื่นๆ (หนี้หลัก)
							$sql_addDebt = "INSERT INTO public.\"thcap_temp_otherpay_debt\"(\"contractID\",\"typePayID\",\"typePayRefValue\",\"typePayRefDate\",\"typePayAmt\",\"typePayLeft\",\"doerID\",\"doerStamp\",\"appvID\",\"appvStamp\",\"debtStatus\",\"debtRemark\",\"debtDueDate\",\"debtIsOther\",\"debtNet\",\"debtVat\")
											VALUES ('$contractID', 'D112', '$ptNum', '$conDate', '$conMonthlyAdviserFee', '$conMonthlyAdviserFee', '000', '$logs_any_time', '000', '$logs_any_time', '1', '$debtRemark', '$ptDate', '0', '$minpayNet', '$minpayVat')";
							$qry_addDebt = pg_query($sql_addDebt);
							if($qry_addDebt){}else{$status++;}
						}
					}
			
					//$contractID = checknull($contractID);
					$conType = checknull($conType);
					$conCompany = checknull($conCompany);
					$conLoanAmt = checknull($conLoanAmt);
					$conLoanIniRate = checknull($conLoanIniRate);
					$conCredit = checknull($conCredit);
					$conLoanMaxRate = checknull($conLoanMaxRate);
					$conInvoicePeriod = checknull($conInvoicePeriod);
					$conTerm = checknull($conTerm);
					$conMinPay = checknull($conMinPay);
					$conExtRentMinPay = checknull($conExtRentMinPay);
					$conPenaltyRate = checknull($conPenaltyRate);
					$conDate = checknull($conDate);
					$conStartDate = checknull($conStartDate);
					$conEndDate = checknull($conEndDate);
					$conFirstDue = checknull($conFirstDue);
					$conRepeatDueDay = checknull($conRepeatDueDay);
					$conFreeDate = checknull($conFreeDate);
					$conClosedDate = checknull($conClosedDate);
					$conClosedFee = checknull($conClosedFee);
					$conStatus = checknull($conStatus);
					$conFlow = checknull($conFlow);
					$rev = checknull($rev);
					$conCreditRef = checknull($conCreditRef);
					$conFinanceAmount = checknull($conFinanceAmount);
					$conGuaranteeAmt = checknull($conGuaranteeAmt); //จำนวนเงินค้ำประกันสัญญา
					$conDownToDealer = checknull($conDownToDealer); //ชำระเงินดาวน์ให้ผู้ขาย
					$conDownToFinance = checknull($conDownToFinance); //ชำระเงินดาวน์ให้ไฟแนนซ์
					$conDownToFinanceVat = checknull($conDownToFinanceVat); //VAT ของเงินดาวน์
					$conFinAmtExtVat = checknull($conFinAmtExtVat);
					$conResidualValue = checknull($conResidualValue); // ค่าซาก
					$conPLIniRate = checknull($conPLIniRate); // ค่าธรรมเนียมการใช้วงเงินสินเชื่อส่วนบุคคล
					
					$conResidualValueIncVat = checknull($conResidualValueIncVat); // ค่าซากรวมภาษีมูลค่าเพิ่ม
					$conLeaseIsForceBuyResidue = checknull($conLeaseIsForceBuyResidue); // บังคับซื้อซาก
					$conLeaseBaseFinanceForCal = checknull($conLeaseBaseFinanceForCal); // ยอดจัดที่ใช้ในการคิดดอกเบี้ย
					
					$conNumExceptDays = checknull($conNumExceptDays); // จำนวนวันที่ผ่อนผันเรื่องค่าติดตามทวงถาม และการปรับอัตราดอกเบี้ย นับจาก Due
					$conNumNTDays = checknull($conNumNTDays); // จำนวนวันที่ผ่อนผันการออกหนังสือเตือนหนี้ NT นับจาก Due
					$conNumSueDays = checknull($conNumSueDays); // จำนวนวันที่ผ่อนผัน ก่อนการฟ้องร้อง นับจาก Due
					$sendNCB = checknull($sendNCB); // รายการนี้ต้องส่ง NCB หรือไม่
					
					$conMonthlyAdviserFee = checknull($conMonthlyAdviserFee); // ค่าที่ปรึกษากิจการร่วมค้า
					
					//checknull(conFineRate) ถ้าเป็นค่าว่าง ให้กำหนดเป็น 0 แทน
					if($conFineRate == "")
					{
						$conFineRate = "0.00";
					}
					
					$conSubType_serial = checknull($conSubType_serial);
				
					// ทำการ INSERT ลง ตาราง thcap_contract
					$sql_add = "insert into public.\"thcap_contract\" (\"contractID\",\"conType\",\"conCompany\",\"conLoanAmt\",\"conLoanIniRate\",\"conCredit\",\"conLoanMaxRate\",\"conTerm\"
																		,\"conMinPay\",\"conPenaltyRate\",\"conDate\",\"conStartDate\",\"conEndDate\",\"conFirstDue\",\"conRepeatDueDay\",\"conFreeDate\"
																		,\"conClosedDate\",\"conClosedFee\",\"conStatus\",\"conFlow\",\"rev\",\"conCreditRef\",\"conFinanceAmount\",\"conFinAmtExtVat\",\"conExtRentMinPay\"
																		,\"conDownToDealer\",\"conDownToFinance\",\"conDownToFinanceVat\",\"conSubType_serial\",\"conMonthlyAdviserFee\",\"conPLIniRate\")
													values ('$contractID',$conType,$conCompany,$conLoanAmt,$conLoanIniRate,$conCredit,$conLoanMaxRate,$conTerm
															,$conMinPay,$conPenaltyRate,$conDate,$conStartDate,$conEndDate,$conFirstDue,$conRepeatDueDay,$conFreeDate
															,$conClosedDate,$conClosedFee,$conStatus,$conFlow,$rev,$conCreditRef,$conFinanceAmount,$conFinAmtExtVat,$conExtRentMinPay
															,$conDownToDealer,$conDownToFinance,$conDownToFinanceVat,$conSubType_serial,$conMonthlyAdviserFee,$conPLIniRate)";
					if($result = pg_query($sql_add))
					{}
					else
					{
						$status++;
					}
				
					//บันทึกเลขที่สัญญาที่เป็น BH
					if($conType=="'BH'")
					{
						$qry_contract_edit = pg_query("insert into \"thcap_contract_edit\"(\"contractID\") values('$contractID')");
						if($qry_contract_edit){}else{ $status++; echo $qry_contract_edit;}
					}
					
					// อนุมัติที่อยู่ด้วย
					$sql_yes_addr = "update public.\"thcap_addrContractID_temp\" set \"statusApp\" = '4' , \"appUser\" = '$id_user' , \"appStamp\" = '$logs_any_time' 
											where \"tempID\" = '$addrTempID' ";
					if($resultAddr = pg_query($sql_yes_addr))
					{}
					else
					{
						$status++;
					}
				
					// เพิ่มที่อยู่
					$query_main = pg_query("select * from public.\"thcap_addrContractID_temp\" where \"tempID\" = '$addrTempID' ");
					while($result = pg_fetch_array($query_main))
					{
						$contractID = $result["contractID"];
						$addsType = $result["addsType"];
						$edittime = $result["edittime"];
						$A_NO = $result["A_NO"];
						$A_SUBNO = $result["A_SUBNO"];
						$A_BUILDING = $result["A_BUILDING"];
						$A_ROOM = $result["A_ROOM"];
						$A_FLOOR = $result["A_FLOOR"];
						$A_VILLAGE = $result["A_VILLAGE"];
						$A_SOI = $result["A_SOI"];
						$A_RD = $result["A_RD"];
						$A_TUM = $result["A_TUM"];
						$A_AUM = $result["A_AUM"];
						$A_PRO = $result["A_PRO"];
						$A_POST = $result["A_POST"];
					}
				
					$contractID = checknull($contractID);
					$addsType = checknull($addsType);
					$edittime = checknull($edittime);
					$A_NO = checknull($A_NO);
					$A_SUBNO = checknull($A_SUBNO);
					$A_BUILDING = checknull($A_BUILDING);
					$A_ROOM = checknull($A_ROOM);
					$A_FLOOR = checknull($A_FLOOR);
					$A_VILLAGE = checknull($A_VILLAGE);
					$A_SOI = checknull($A_SOI);
					$A_RD = checknull($A_RD);
					$A_TUM = checknull($A_TUM);
					$A_AUM = checknull($A_AUM);
					$A_PRO = checknull($A_PRO);
					$A_POST = checknull($A_POST);
				
					$sql_addr = "insert into public.\"thcap_addrContractID\" (\"contractID\",\"addsType\",\"A_NO\",\"A_SUBNO\",\"A_BUILDING\",\"A_ROOM\",\"A_FLOOR\",\"A_VILLAGE\"
																		,\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\")
													values ($contractID,$addsType,$A_NO,$A_SUBNO,$A_BUILDING,$A_ROOM,$A_FLOOR,$A_VILLAGE
															,$A_SOI,$A_RD,$A_TUM,$A_AUM,$A_PRO,$A_POST)";
					if($result = pg_query($sql_addr))
					{}
					else
					{
						$status++;
					}
				
					// หาและเพิ่มผู้กู้หลัก
					$qry_cusMain = pg_query("SELECT distinct ta_array_list(a.\"CusIDarray\") AS \"cusMainType\", a.\"contractID\"
											FROM thcap_contract_temp a
											WHERE a.\"autoID\" = '$contractAutoID'
											order by \"cusMainType\" ");
					while($res_cusMain = pg_fetch_array($qry_cusMain))
					{
						$cusMainType = $res_cusMain["cusMainType"]; // ประเภทลูกค้า
						if($cusMainType == "0") // ถ้าเป็นผู้กู้หลัก
						{
							$haveCusMain = "yes";
							$qry_cusMainID = pg_query("SELECT a.\"contractID\", ta_array_get(a.\"CusIDarray\", '0') AS \"cusMainID\"
														FROM thcap_contract_temp a
														WHERE a.\"autoID\" = '$contractAutoID' ");
							$p=1;
							while($res_cusMainID = pg_fetch_array($qry_cusMainID))
							{
								$cusMainID = $res_cusMainID["cusMainID"]; // รหัสผู้กู้หลัก
								
								// หาชื่อลูกค้า
								$qry_cusFullname = pg_query("select ta_array_get((select \"conCusFullnameArray\" from thcap_contract_temp where \"autoID\" = '$contractAutoID'),'$cusMainID')");
								$cusFullName = pg_fetch_result($qry_cusFullname,0);
								
								// หาที่อยู่ลูกค้า
								$qry_cusFullAddress = pg_query("select ta_array_get((select \"conCusAddressArray\" from thcap_contract_temp where \"autoID\" = '$contractAutoID'),'$cusMainID')");
								$cusFullAddress = pg_fetch_result($qry_cusFullAddress,0);
								$cusFullAddress=str_replace("&sbquo;",",",$cusFullAddress); //แทนที่ characters for HTML ด้วย , เพื่อแปลงกลับให้สามารถนำไป insert เป็นข้อมูลปกติ
							
								$sql_insertCusMain = "INSERT INTO \"thcap_ContactCus\"(\"contractID\", \"CusState\", \"CusID\", ranking, \"FullName\", \"FullAddress\") VALUES ($contractID,'0','$cusMainID', '$p', '$cusFullName', '$cusFullAddress')";
								$query_insertCusMain = pg_query($sql_insertCusMain);
								if($query_insertCusMain){}else{ $status++; echo $sql_insertCusMain;}
								$p++;
							}
						}
					}
				
					// หาและเพิ่มผู้กู้ร่วม
					$qry_cusJoin = pg_query("SELECT distinct ta_array_list(a.\"CusIDarray\") AS \"cusJoinType\", a.\"contractID\"
											FROM thcap_contract_temp a
											WHERE a.\"autoID\" = '$contractAutoID'
											order by \"cusJoinType\" ");
					while($res_cusJoin = pg_fetch_array($qry_cusJoin))
					{
						$cusJoinType = $res_cusJoin["cusJoinType"]; // ประเภทลูกค้า
						if($cusJoinType == "1") // ถ้าเป็นผู้กู้ร่วม
						{
							$qry_cusJoinID = pg_query("SELECT a.\"contractID\", ta_array_get(a.\"CusIDarray\", '1') AS \"cusJoinID\"
														FROM thcap_contract_temp a
														WHERE a.\"autoID\" = '$contractAutoID' ");
							$p=1;
							while($res_cusJoinID = pg_fetch_array($qry_cusJoinID))
							{
								$cusJoinID = $res_cusJoinID["cusJoinID"];
								
								// หาชื่อลูกค้า
								$qry_cusFullname = pg_query("select ta_array_get((select \"conCusFullnameArray\" from thcap_contract_temp where \"autoID\" = '$contractAutoID'),'$cusJoinID')");
								$cusFullName = pg_fetch_result($qry_cusFullname,0);
								
								// หาที่อยู่ลูกค้า
								$qry_cusFullAddress = pg_query("select ta_array_get((select \"conCusAddressArray\" from thcap_contract_temp where \"autoID\" = '$contractAutoID'),'$cusJoinID')");
								$cusFullAddress = pg_fetch_result($qry_cusFullAddress,0);
								$cusFullAddress=str_replace("&sbquo;",",",$cusFullAddress); //แทนที่ characters for HTML ด้วย , เพื่อแปลงกลับให้สามารถนำไป insert เป็นข้อมูลปกติ
						
								$sql_insertCusJoinID = "INSERT INTO \"thcap_ContactCus\"(\"contractID\", \"CusState\", \"CusID\", ranking, \"FullName\", \"FullAddress\") VALUES ($contractID,'1','$cusJoinID', '$p', '$cusFullName', '$cusFullAddress')";
								$query_insertCusJoinID = pg_query($sql_insertCusJoinID);
								if($query_insertCusJoinID){}else{ $status++; echo $sql_insertCusJoinID;}
								$p++;
							}
						}
					}
				
					// หาและเพิ่มผู้ค้ำประกัน
					$qry_cusGuarantor = pg_query("SELECT distinct ta_array_list(a.\"CusIDarray\") AS \"cusGuarantorType\", a.\"contractID\"
											FROM thcap_contract_temp a
											WHERE a.\"autoID\" = '$contractAutoID'
											order by \"cusGuarantorType\" ");
					while($res_cusGuarantor = pg_fetch_array($qry_cusGuarantor))
					{
						$cusGuarantorType = $res_cusGuarantor["cusGuarantorType"]; // ประเภทลูกค้า
						if($cusGuarantorType == "2") // ถ้าเป็นผู้ค้ำประกัน
						{
							$qry_cusGuarantorID = pg_query("SELECT a.\"contractID\", ta_array_get(a.\"CusIDarray\", '2') AS \"cusGuarantorID\"
														FROM thcap_contract_temp a
														WHERE a.\"autoID\" = '$contractAutoID' ");
							$p=1;
							while($res_cusGuarantorID = pg_fetch_array($qry_cusGuarantorID))
							{
								$cusGuarantorID = $res_cusGuarantorID["cusGuarantorID"];
								
								// หาชื่อลูกค้า
								$qry_cusFullname = pg_query("select ta_array_get((select \"conCusFullnameArray\" from thcap_contract_temp where \"autoID\" = '$contractAutoID'),'$cusGuarantorID')");
								$cusFullName = pg_fetch_result($qry_cusFullname,0);
								
								// หาที่อยู่ลูกค้า
								$qry_cusFullAddress = pg_query("select ta_array_get((select \"conCusAddressArray\" from thcap_contract_temp where \"autoID\" = '$contractAutoID'),'$cusGuarantorID')");
								$cusFullAddress = pg_fetch_result($qry_cusFullAddress,0);
								$cusFullAddress=str_replace("&sbquo;",",",$cusFullAddress); //แทนที่ characters for HTML ด้วย , เพื่อแปลงกลับให้สามารถนำไป insert เป็นข้อมูลปกติ
								
								$sql_insertCusGuarantorID = "INSERT INTO \"thcap_ContactCus\"(\"contractID\", \"CusState\", \"CusID\", ranking, \"FullName\", \"FullAddress\") VALUES ($contractID,'2','$cusGuarantorID','$p','$cusFullName','$cusFullAddress')";
								$query_insertCusGuarantorID = pg_query($sql_insertCusGuarantorID);
								if($query_insertCusGuarantorID){}else{ $status++; echo $sql_insertCusGuarantorID;}
								$p++;
							}
						}
					}
				
					if($conLoanAmt != "null"  || $conFinanceAmount != "null") // นำเข้า thcap_mg_contract_current
					{
						$sql_insert_thcap_mg_contract_current = "INSERT INTO thcap_mg_contract_current(
							\"contractID\",
							\"effectiveDate\",
							\"conIntCurRate\",
							\"conCurPenalty\",
							\"conCurVAT\",
							\"conCurSBT\",
							\"conCurLT\", 
							\"conIntMethod\",
							\"conNumExceptDays\",
							\"conNumNTDays\",
							\"conNumSueDays\",
							\"conAccMethod\",
							\"conCredit\",
							\"conMinPay\",
							\"conEndDate\",
							\"conTerm\",
							\"conClosedFee\",
							\"conFreeDate\",
							\"conPTRev\",
							\"doerID\",
							\"doerStamp\" ,
							\"appvID\",
							\"appvStamp\",
							\"auditorXID\",
							\"auditorStamp\" ,
							\"auditorYID\" ,
							\"auditorYStamp\",
							\"rev\",
							\"conInvoicePeriod\",
							\"conFineRate\",
							\"conExtRentMinPay\",
							\"conResidualValue\",
							\"sendNCB\",
							\"conResidualValueIncVat\",
							\"conLeaseIsForceBuyResidue\",
							\"conLeaseBaseFinanceForCal\",
							\"conPLCurRate\"
						)
						VALUES ($contractID,
							$conDate,
							$conLoanIniRate,
							$conPenaltyRate,
							'7.00',
							'3.00',
							'0.30', 
							'0',
							$conNumExceptDays,
							$conNumNTDays,
							$conNumSueDays,
							'1',
							$conCredit,
							$conMinPay,
							$conEndDate,
							$conTerm,
							$conClosedFee,
							$conFreeDate,
							'1',
							'$doerUser',
							'$doerStamp',
							'$id_user',
							'$logs_any_time',
							'$id_user',
							'$logs_any_time',
							'$id_user',
							'$logs_any_time',
							'1',
							$conInvoicePeriod,
							'$conFineRate',
							$conExtRentMinPay,
							$conResidualValue,
							$sendNCB,
							$conResidualValueIncVat,
							$conLeaseIsForceBuyResidue,
							$conLeaseBaseFinanceForCal,
							$conPLIniRate
						)";
						if($result = pg_query($sql_insert_thcap_mg_contract_current))
						{}
						else
						{
							$status++;
						}
					}
				
					// ถ้าเป็นสัญญา FA
					if($conType == "'FA'")
					{
						$qry_chkBillFA = pg_query("select * from \"thcap_contract_fa_bill_temp\" where \"contractID\" = '$contractID_forBillFA' and \"doerStamp\" = '$getDoerStamp' and \"Approved\" is null "); // ตรวจสอบก่อนว่ามีบิลที่จะผูกกับสัญญาหรือไม่
						$row_chkBillFA = pg_num_rows($qry_chkBillFA);
						if($row_chkBillFA > 0)
						{ // ถ้ามีบิลที่จะผูกกับสัญญา
						
							// อนุมัติตารางการขอผูกบิลกับสัญญา FA ด้วย
							$sql_yes_billFA = "update \"thcap_contract_fa_bill_temp\" set \"Approved\" = 'true' , \"appvID\" = '$id_user' , \"appvStamp\" = '$logs_any_time' 
													where \"contractID\" = '$contractID_forBillFA' and \"doerStamp\" = '$getDoerStamp' and \"Approved\" is null ";
							if($resultBillFA = pg_query($sql_yes_billFA))
							{}
							else
							{
								$status++;
							}
							
							while($res_chkBillFA = pg_fetch_array($qry_chkBillFA))
							{
								$arrayFaBill = $res_chkBillFA["arrayFaBill"];
								$ap_fac_amt = $res_chkBillFA["ap_fac_amt"];
							}
							
							// ตรวจสอบบิล
							$qry_chkBill = pg_query("select ta_array_list('$arrayFaBill') ");
							while($loop_chkBill = pg_fetch_array($qry_chkBill))
							{
								$arrayChkBill = $loop_chkBill["ta_array_list"];
								
								$qry_chkBillIDMaster = pg_query("select \"prebillIDMaster\" from \"thcap_fa_prebill\" where \"prebillID\" = '$arrayChkBill' ");
								$prebillIDMaster = pg_fetch_result($qry_chkBillIDMaster,0);
								
								//ตรวจสอบว่าบิลนี้ถูกยกเลิกหรือยัง
								$qrycheckbill=pg_query("select * from \"thcap_fa_prebill_temp\" where \"prebillIDMaster\"='$prebillIDMaster' and \"statusApp\" ='1'");
								$numbill=pg_num_rows($qrycheckbill); //ถ้ายังพบอยู่แสดงว่ายังไม่ยกเลิก
								if($numbill == 0)
								{
									$status++;
									$errorBill = "มีรายการบิลที่ถูกยกเลิกไปแล้ว ไม่สามารถอนุมัติได้";
								}
							} 
							
							$arrayFaBill = checknull($arrayFaBill);
							$ap_fac_amt = checknull($ap_fac_amt);
							
							$sql_insertBillFA = "INSERT INTO \"thcap_contract_fa_bill\"(\"contractID\", \"arrayFaBill\", \"ap_fac_amt\") VALUES ($contractID, $arrayFaBill, $ap_fac_amt)";
							$query_insertBillFA = pg_query($sql_insertBillFA);
							if($query_insertBillFA){}else{ $status++; echo $sql_insertBillFA;}
						}
					}
				
					if($chk_con_type == "HIRE_PURCHASE" || $chk_con_type == "LEASING" || $chk_con_type == "GUARANTEED_INVESTMENT" || $chk_con_type == "SALE_ON_CONSIGNMENT" || ($chk_con_type == "FACTORING" && $ISconCredit != ""))
					{
						// ตัวเครื่องหมาย ' ในเลขที่สัญญาออก
						$contractID = str_replace("'","",$contractID);
						
						$qry_asset = pg_query("select * from \"thcap_contract_asset_temp\" where \"contractID\" = '$contractID' and \"doerStamp\" = '$getDoerStamp' and \"Approved\" IS null"); // ดึงข้อมูลของสินค้าที่ผูกกับสัญญานี้เพื่อนำไปลงตารางจริง
						$row_asset = pg_num_rows($qry_asset);
						if($row_asset > 0)
						{ //ถ้ามีการผูกกันของสินค้ากับสัญญาจริง
							while($re_asset = pg_fetch_array($qry_asset))
							{
								$assetDetailID = $re_asset["assetDetailID"]; //รหัสสินค้า
								$autoID = $re_asset["autoID"]; //รหัสการผูกสัญญาเป็นเลข running
								$assetAddress = $re_asset["assetAddress"];
								if($assetAddress=="")
								{
									$assetAddress = "null";
								}
								else
								{
									$assetAddress = "'".$assetAddress."'";
								}
								
								//----- ตรวจสอบก่อนว่า สินทรัพย์ดังกล่าวถูกยกเลิก หรือถูกขอยกเลิกอยู่หรือไม่ -----
									// หารหัส ใบเสร็จ/ใบสั่งซื้อ
									$qry_sAssetID = pg_query("select \"assetID\" from \"thcap_asset_biz_detail\" where \"assetDetailID\" = '$assetDetailID' ");
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
											echo "ไม่สามารถทำรายการได้ เนื่องจาก สินทรัพย์รหัส $assetDetailID ถูกขอยกเลิกอยู่ในขณะนี้<br>";
										}
										else
										{
											$status++;
											echo "ไม่สามารถทำรายการได้ เนื่องจาก สินทรัพย์รหัส $assetDetailID ถูกยกเลิกไปแล้วในขณะนี้<br>";
										}
									}
								//----- จบการตรวจสอบก่อนว่า สินทรัพย์ดังกล่าวถูกยกเลิก หรือถูกขอยกเลิกอยู่หรือไม่ -----
								
								//เพิ่มข้อมูลจาก temp มาลงตารางจริง

								$qry_in_asset = pg_query("INSERT INTO thcap_contract_asset(
																				\"contractID\",
																				\"assetDetailID\",\"assetAddress\")
																		VALUES('$contractID',
																				'$assetDetailID',$assetAddress)
																");
								if($qry_in_asset){}else{ $status++; echo $qry_in_asset;};
							
								//เปลี่ยนสถานะในตาราง temp ว่าอนุมัติแล้ว
								$qry_up_asset_temp = pg_query("UPDATE thcap_contract_asset_temp 
																SET 
																	\"appvID\"='$id_user', 
																	\"appvStamp\"='$logs_any_time',
																	\"Approved\"= 'TRUE'
																WHERE 
																	\"contractID\"= '$contractID' AND \"autoID\"= '$autoID'");
								if($qry_up_asset_temp){}else{ $status++; echo $qry_up_asset_temp;};					
							}
						}
						
						// หา typePayID
						$qry_S_typePayID = pg_query("select account.\"thcap_mg_getMinPayType\"('$contractID');");
						$res_S_typePayID = pg_fetch_result($qry_S_typePayID,0);
						
						// หาอัตรา vat ปัจจุบัน
						/*$qry_S_vat = pg_query("select cal_rate_or_money('VAT')");
						$res_S_vat = pg_fetch_result($qry_S_vat,0);*/
						
						// เพิ่มข้อมูลการผ่อนชำระ
						$qry_S_payTerm = pg_query("select * from account.\"thcap_payTerm\" where \"contractID\" = '$contractID' order by \"ptNum\" ");
						while($res_S_payTerm = pg_fetch_array($qry_S_payTerm))
						{
							$ptNum = $res_S_payTerm["ptNum"];// งวดที่
							$ptDate = $res_S_payTerm["ptDate"]; // วันที่กำหนดชำระ
							$ptMinPay = $res_S_payTerm["ptMinPay"]; // ขั้นต่ำที่จะต้องจ่ายในกำหนดชำระครั้งนี้
							
							// ตรวจสอบก่อนว่า ต้องแยก vat หรือไม่
							$qry_ableVAT = pg_query("select \"ableVAT\" from account.\"thcap_typePay\" where \"tpID\" = account.\"thcap_mg_getMinPayType\"('$contractID') ");
							$res_ableVAT = pg_fetch_result($qry_ableVAT,0);
							
							if($res_ableVAT == "1")
							{ // ถ้าต้องแยก vat
								// หามูลค่า vat //$minpayVat = $ptMinPay * $res_S_vat / (100 + $res_S_vat);
								$qry_minpayVat = pg_query("select cal_rate_or_money('VAT', '$ptDate', '$ptMinPay', '1')");
								$minpayVat = pg_fetch_result($qry_minpayVat,0);
								
								// หาจำนวนเงินหนี้ (จำนวนเงินก่อน vat) //$minpayNet = $ptMinPay - $minpayVat;
								$qry_minpayNet = pg_query("select cal_rate_or_money('VAT', '$ptDate', '$ptMinPay', '2')");
								$minpayNet = pg_fetch_result($qry_minpayNet,0);
							}
							else
							{
								$minpayNet = $ptMinPay;
								$minpayVat = 0.00;
							}
							
							//หาประเภทการจ่ายที่เป็นเงินกู้
							$qrydesc=pg_query("select \"tpDesc\"||' '||\"tpFullDesc\" from account.\"thcap_typePay\"
							where \"tpID\"=account.\"thcap_mg_getMinPayType\"('$contractID')");
							list($tpDesc)=pg_fetch_array($qrydesc);
							
							$debtRemark="$tpDesc $ptNum ของสัญญาเลขที่ $contractID";
								
							// อนุมัติ และ approve โดย user 000 วันเวลาตามที่ถูก gen และให้ debtIsOther = 0 หมายความว่าเป็นไม่ใช่หนี้อื่นๆ (หนี้หลัก)
							$sql_addDebt = "INSERT INTO public.\"thcap_temp_otherpay_debt\"(\"contractID\",\"typePayID\",\"typePayRefValue\",\"typePayRefDate\",\"typePayAmt\",\"typePayLeft\",\"doerID\",\"doerStamp\",\"appvID\",\"appvStamp\",\"debtStatus\",\"debtRemark\",\"debtDueDate\",\"debtIsOther\",\"debtNet\",\"debtVat\")
											VALUES ('$contractID', '$res_S_typePayID', '$ptNum', $conDate, '$ptMinPay', '$ptMinPay', '000', '$logs_any_time', '000', '$logs_any_time', '1', '$debtRemark', '$ptDate', '0', '$minpayNet', '$minpayVat')";
							$qry_addDebt = pg_query($sql_addDebt);
							if($qry_addDebt){}else{$status++;}
						}
					}
				
					// ถ้ามี เงินค้ำประกัน ให้รับชำระเงินค้ำประกันเข้าเลขที่สัญญาดังกล่าวด้วย
						$contractID = str_replace("'","",$contractID); // ตัวเครื่องหมาย ' ในเลขที่สัญญาออก
						$conGuaranteeAmt = str_replace("'","",$conGuaranteeAmt); // ตัวเครื่องหมาย ' ในเงินค้ำออก
						
						//รับชำระเงินค้ำประกันที่หักจากยอดกู้
						if($conGuaranteeAmt != "" && $conGuaranteeAmt != "null" && $conGuaranteeAmt != "NULL" && $conGuaranteeAmt != "Null" && $conGuaranteeAmt > 0.00)
						{
							$qry_guarantee_appv = "select \"thcap_process_receiveOther\"('$contractID', $conDate, '990', '0.00', '0', '0', '$doerUser', null, null, '0', null, null, '0.00', '{}', '0', '$conGuaranteeAmt','0', '$contractID')";
							if($resultguarantee_appv = pg_query($qry_guarantee_appv))
							{}
							else
							{
								$status++;
							}
						}
					//=====================================================
				
					// ถ้ามี ชำระเงินดาวน์ให้ไฟแนนซ์
					$conDownToFinance = str_replace("'","",$conDownToFinance);
					$conDownToFinanceVat = str_replace("'","",$conDownToFinanceVat);
					
					// หารหัสค่าใช้จ่าย
					$qry_DownType = pg_query("select account.\"thcap_getDownType\"('$contractID')");
					$DownType = pg_fetch_result($qry_DownType,0);

					if($conDownToFinance != "" && $conDownToFinance != "null" && $conDownToFinance != "NULL" && $conDownToFinance != "Null" && $conDownToFinance > 0.00
						&& $conDownToFinanceVat != "" && $conDownToFinanceVat != "null" && $conDownToFinanceVat != "NULL" && $conDownToFinanceVat != "Null")
					{
						$conDownToFinanceSum = $conDownToFinance + $conDownToFinanceVat;
						
						$qry_setDebtDownToFinance = "INSERT INTO thcap_temp_otherpay_debt(\"contractID\", \"typePayID\", \"typePayRefValue\", \"typePayRefDate\", \"typePayAmt\", 
														\"typePayLeft\", \"doerID\", \"doerStamp\", \"debtRemark\", \"debtNet\", \"debtVat\")
													VALUES ('$contractID', '$DownType', '$contractID', $conDate, $conDownToFinanceSum, $conDownToFinanceSum, '$doerUser', '$logs_any_time', null,
														'$conDownToFinance', '$conDownToFinanceVat') returning \"debtID\" ";
						if($result_setDebtDownToFinance = pg_query($qry_setDebtDownToFinance))
						{
							$DebtDownToFinance = pg_fetch_result($result_setDebtDownToFinance,0); // รหัสหนี้
							
							// อนุมัติหนี้อัตโนมัติ
							$ins=pg_query("SELECT thcap_process_setdebtloan(null,null,null,null,null,null,null,'2','$DebtDownToFinance','1','000')");
							list($statusText) = pg_fetch_array($ins);
							
							if($statusText == 't')
							{}
							else
							{
								$status++;
							}
						}
						else
						{
							$status++;
						}
					}
					
					//=====================================================
				}
				
				//ทำการอนุมัติ การตั้งหนี้ที่เกิดจาก เมนู การผูกสัญญา
				$qry_check=pg_query("select \"debtID\" from thcap_temp_otherpay_debt where \"contractID\"='$contractID' and \"ShowAppvStatus\"='0' and \"create_ref_contractID\" = '$contractAutoID' and \"debtStatus\" = '9' ");
				while($result = pg_fetch_array($qry_check))
				{
					$debtIDdebt=$result["debtID"];
					$insdebt=pg_query("SELECT thcap_process_setdebtloan(null,null,null,null,null,null,null,'2','$debtIDdebt','1','$id_user')");
					list($status1) = pg_fetch_array($insdebt);
					if($status1=='t'){}
					else{$status++;}
				}
				
				// สร้างตาราง EIR
				// ถ้าเป็นสัญญาประเภท LEASING หรือ HIRE_PURCHASE หรือ SALE_ON_CONSIGNMENT และต้องไม่ใช่สัญญาวงเงิน
				if(($chk_con_type == "LEASING" || $chk_con_type == "HIRE_PURCHASE" || $chk_con_type == "SALE_ON_CONSIGNMENT") && $ISconCredit == "")
				{
					// ค่าซาก
					if($conResidualValue == "NULL" || $conResidualValue == "Null" || $conResidualValue == "null")
					{
						$conResidualValue = "'0.00'";
					}
					
					$qry_realize_eff = "select thcap_process_gen_acc_filease_realize_eff('$contractID', $conFinAmtExtVat, $conResidualValue, $conResidualValue); ";
					$qry_realize_eff_acc = "select thcap_process_gen_acc_filease_realize_eff_acc('$contractID', $conFinAmtExtVat, $conResidualValue, $conResidualValue); ";
					
					if($result_qry_realize_eff = pg_query($qry_realize_eff)){
					}else{
						$status++;
						echo "<br>query error : $qry_realize_eff<br>";
					}
					
					if($result_qry_realize_eff_acc = pg_query($qry_realize_eff_acc)){
					}else{
						$status++;
						echo "<br>query error : $qry_realize_eff_acc<br>";
					}
				}
			}
		}
	}
	
	if($status == 0)
	{	if($resucheck=="false"){ 
			pg_query("ROLLBACK");
			echo "<center><h2><font color=\"#FF0000\">ไม่สามารถบันทึกได้  เนื่องจากผู้ที่ตรวจสอบสัญญาไม่สามารถทำการอนุมัติสัญญาได้!</font></h2></center>";	
			echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
		}else if($resucheck=="false1"){
			pg_query("ROLLBACK");
			echo "<center><h2><font color=\"#FF0000\">ไม่สามารถบันทึกได้  เนื่องจากผู้ที่ผูกสัญญาไม่สามารถทำการอนุมัติสัญญาได้!</font></h2></center>";	
			echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
		}
		else{
		//ACTIONLOG
			$sqlaction = "INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) อนุมัติผูกสัญญา', '$logs_any_time')";
			if($result = pg_query($sqlaction)){}else{$status++;}
		//ACTIONLOG---
			pg_query("COMMIT");
		
			echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
			echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
		}
	}
	else if($status == -1)
	{	if($statusAppv=="checktruebutno"){
			echo "<center><h2><font color=\"#FF0000\">การอนุมัติผิดพลาด เนื่องจากสัญญานี้ตรวจสอบแล้ว ไม่ถูกต้อง!</font></h2></center>";
			echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";	

		}
		else if($statusAppv=="checkfalse"){		
			echo "<center><h2><font color=\"#FF0000\">การอนุมัติผิดพลาด เนื่องจากสัญญานี้ยังไม่ได้รับการตรวจสอบ</font></h2></center>";
			echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";	
		}
	}
	else
	{
		pg_query("ROLLBACK");
		echo "<center><h2><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด!!</font></h2></center>";
		echo "<center><h2><font color=\"#FF0000\">$errorBill</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ปิด\" onclick=\"javascript:RefreshMe();\"></center>";
	}
//--------------- จบการบันทึกข้อมูล
}
else
{ //กรณีมีการอนุมัติไปแล้วก่อนหน้านี้
	echo "<div style=\"text-align:center;padding:20px;\"><h1>รายการนี้ได้รับการอนุมัติไปแล้ว กรุณาตรวจสอบอีกครั้ง !!</h1>";
	echo "<input type=\"button\" value=\" ตกลง \"  onclick=\"javascript:RefreshMe();\"></div>";
}
?>