<?php
include("../../config/config.php");

$datepicker=$_POST["datepicker"];
if($datepicker==""){
	$datepicker=nowDate();
	$arrayDatepicker = explode("-",$datepicker);						
	$plusDatepicker = mktime(0,0,0,$arrayDatepicker[1],$arrayDatepicker[2]-1,$arrayDatepicker[0]); // เวลา เดือน วัน ปี
	$datepicker = date("Y-m-d",$plusDatepicker); // วันที่ย้อนหลังวันปัจจุบัน 1 วัน แบบ ปี-เดือน-วัน
}
$val=$_POST["val"]; // เช็คว่ามีการกดปุ่มค้นหาหรือไม่ ถ้ากดปุ่มค้นหาจะเท่ากับ 1
$contypechk = $_POST['contype']; //ประเภทสัญญาที่จะให้แสดง

// ============================================================================================
// นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อรอการส่งค่าแบบ GET	(ในอนาคต)
// ============================================================================================
for($con = 0;$con < sizeof($contypechk) ; $con++){
	if($sendarray==""){
		$sendarray=$contypechk[$con];
	}else{
		$sendarray = $sendarray."@".$contypechk[$con];
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
	<link type="text/css" href="../../jqueryui-1.10.2/css/ui-lightness/jquery-ui-1.10.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="../../jqueryui-1.10.2/js/jquery-ui-1.10.2.custom.min.js"></script>
	
	<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script type="text/javascript">
		var jQuery_old = $.noConflict(true);
	</script>

<script type="text/javascript">
$(document).ready(function(){
    $("#datepicker").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
	
	//เมื่อกด ข้อความ  "แสดงเฉพาะ :" 
	
	jQuery_old("#selectcontype").click(function(){

		var ele_contype = jQuery_old("input[name=contype[]]");

		if($("#clear").val()== 'Y'){
			$("#clear").val('N');
		}
		else{
			$("#clear").val('Y');
		}
		if($("#clear").val() == 'Y')
		{  	var num=0;
			//ติ้ก ถูกทั้งหมด
			for (i=0; i< ele_contype.length; i++)
			{
				jQuery_old(ele_contype[i]).attr ( "checked" ,"checked" );
			}
		}
		else
		{ 	//เอาติ้ก ถูก ออก ทั้งหมด
			for (i=0; i< ele_contype.length; i++)
			{
				jQuery_old(ele_contype[i]).removeAttr('checked');
			}
		}
	
	});
});

function popU(U,N,T){
    newWindow = window.open(U, N, T);
}

function chkDate()
{	
	var chkDateNow = '<?php echo nowDate(); ?>';
	var datepicker = '<?php echo $datepicker; ?>';
	
	if(document.getElementById("datepicker").value < chkDateNow)
	{
		return true;
	}
	else
	{
		alert(document.getElementById("datepicker").value + ' ไม่สามารถค้นหาข้อมูลได้!! จะสามารถแสดงข้อมูลได้ต่อเมื่อ ผ่านพ้นวันนั้นไปแล้ว ');
		document.getElementById("datepicker").value = datepicker;
		return false;
	}
}
</script>
    
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
.sumall{
    background-color:#C0FFC0;
    font-size:12px
}
</style>
    
</head>
<body id="mm">
<form method="post" name="form1" action="frm_Index_acc.php">
<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="text-align:center"><h2>(THCAP) รายงานยอดหนี้ที่จะครบกำหนดชำระ (บัญชี)</h2></div>       
			<div style="float:right"><input type="button" value="  Close  " onclick="window.close();"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>(THCAP) รายงานยอดหนี้ที่จะครบกำหนดชำระ (บัญชี)</B></legend>
				<div align="center">
					<div class="ui-widget">
						<input type="hidden" id="clear" value="Y"/>
						<p>
						<span id="selectcontype" style="cursor:pointer;"><u><font color="#0000CC"><B>แสดงเฉพาะ :</B></font></u></span>
							<?php 
							//แสดงประเภทสัญญา
							$qry_contype = pg_query("SELECT \"conType\" as contype FROM thcap_contract_type ORDER BY contype ASC");
							$con=0;
							while($re_contype = pg_fetch_array($qry_contype)){
								$con++;
								$contype = $re_contype['contype'];
								
								if($contypechk != ""){
									if(in_array($contype,$contypechk)){ $checked = "checked"; }else{ $checked = "";}
								}else{
									$checked = "checked";
								}
								echo "<input type=\"checkbox\" name=\"contype[]\" id=\"contype$con\" value=\"$contype\" $checked>$contype ";
							}			
							?>								
						</p>
						<p align="center">
							<label><b>วันที่</b></label>
							<input type="text" id="datepicker" name="datepicker" value="<?php echo $datepicker; ?>" size="15" style="text-align:center">
							<input type="hidden" name="val" value="1"/>
							<input type="submit" id="btn00" value="เริ่มค้น" onclick="return chkDate();"/>
						</p>
						
						<?php
						if($val=="1")
						{
							// ==========================================================================================
							// กำหนดค่าเริ่มต้น ของผลรวม
							// ==========================================================================================
							$sumall_Overdue = 0.00;
							$sumall_ptMinPay_1 = 0.00;
							$sumall_ptMinPay_2 = 0.00;
							$sumall_ptMinPay_3 = 0.00;
							$sumall_restructure = 0.00;
							$sumall_sue = 0.00;
							$sumall_money_function = 0.00;
							
							// ==========================================================================================
							// หาวันสำหรับใช้ในเงื่อนไขการแบ่งจำนวนเงินที่ครบกำหนดชำระลงช้องต่างๆ
							// ==========================================================================================
							$nextday = date("Y-m-d", strtotime("+1 day", strtotime($datepicker))); // วันต่อไป
							$nextyear = date("Y-m-d", strtotime("+1 year", strtotime($datepicker))); // ปีต่อไป
							$next_oneyear_oneday = date("Y-m-d", strtotime("+1 day", strtotime($nextyear))); // ถัดไป 1 ปี 1 วัน
							$nextfiveyear = date("Y-m-d", strtotime("+5 year", strtotime($datepicker))); // 5 ปีต่อไป
							$next_fiveyear_oneday = date("Y-m-d", strtotime("+1 day", strtotime($nextfiveyear))); // ถัดไป 5 ปี 1 วัน
?>
							<div>
							<div align="right"><a href="debt_due_acc_excel.php?datepicker=<?php echo "$datepicker"; ?>&contype=<?php echo $sendarray; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์ EXCEL)</span></a><a href="debt_due_acc_pdf.php?datepicker=<?php echo "$datepicker"; ?>&contype=<?php echo $sendarray; ?>" target="_blank"><span style="font-size:15px; color:#0000FF;">(พิมพ์ PDF)</span></a><input type="button" value="แสดงตามปีลูกหนี้" onclick="javascript:popU('frm_showgroup.php?datepicker=<?php echo "$datepicker"; ?>&contype=<?php echo $sendarray; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1160,height=800')" style="cursor:pointer;"></div>
							<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#999999">
								<thead>
									<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
										<th rowspan="2">ลำดับที่</th>
										<th rowspan="2">เลขที่สัญญา</th>
										<th rowspan="2">รายชื่อลูกหนี้</th>
										<th colspan="2">ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี</th>
										<th rowspan="2">ลูกหนี้ที่จะครบกำหนดชำระเกิน 1 ปี แต่ไม่เกิน 5 ปี</th>
										<th rowspan="2">ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป</th>
										<th rowspan="2">ปรับโครงสร้างหนี้</th>
										<th rowspan="2">อยู่ระหว่างดำเนินคดี</th>
										<th rowspan="2">รวมหนี้คงเหลือทั้งสัญญา</th>
									</tr>
									<tr bgcolor="#FFCCCC">
										<th>คงค้างชำระ</th>
										<th>ยังไม่ครบกำหนดชำระ</th>
									</tr>
								</thead>
								
<?php	
								for($con = 0;$con < sizeof($contypechk) ; $con++){
								
									// ==========================================================================================
									// ล้างค่าของประเภทสัญญา
									// ==========================================================================================
									$sum_Overdue = 0.00;
									$sum_ptMinPay_1 = 0.00;
									$sum_ptMinPay_2 = 0.00;
									$sum_ptMinPay_3 = 0.00;
									$sum_restructure = 0.00;
									$sum_sue = 0.00;
									$sum_money_function = 0.00;
									
									$vwaitint_overdue = 0.00;
									$vwaitint_ptMinPay_1 = 0.00;
									$vwaitint_ptMinPay_2 = 0.00;
									$vwaitint_ptMinPay_3 = 0.00;
									$vwaitint_restructure = 0.00;
									$vwaitint_sue = 0.00;
									$vwaitint_money_function = 0.00;

									echo "<tr bgcolor=#FFE4B5><td colspan=10><b>ประเภทสัญญา $contypechk[$con]</b></td></tr>"; //แสดง header ว่าเป็นสัญญาประเภทใด
						
									// ==========================================================================================
									// นำทุกสัญญาขึ้นมา โดยให้ check ว่าวันที่เลือกดังกล่าวปิดบัญชีแล้วหรือไม่ด้วย ให้แสดงเฉพาะสัญญาที่ยังไม่ปิดบัญชี
									// ==========================================================================================
									$qry_debt_due = pg_query("	select \"contractID\"
																from 
																	public.\"thcap_contract\" 
																where 
																	\"conType\" = '$contypechk[$con]' AND
																	\"conStartDate\" <= '$datepicker' AND
																	\"conCredit\" IS NULL AND
																	(\"thcap_get_all_isSold\"(\"contractID\", '$datepicker') IS NULL OR
																	\"thcap_get_all_isSold\"(\"contractID\", '$datepicker') = 0::smallint) AND
																	\"thcap_checkcontractcloseddate\"(\"contractID\", '$datepicker') IS NULL
																order by \"contractID\"
									");
									$row_debt_due = pg_num_rows($qry_debt_due);

									// ==========================================================================================
									// กรณีไม่พบข้อมูลที่จะแสดงรายงาน
									// ==========================================================================================
									if($row_debt_due == 0)
									{
										echo "<tr><td colspan=10 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบข้อมูล-</b></td></tr>";
									}
									else
									{	
										// ==========================================================================================
										// กรณีพบข้อมูลที่จะแสดงรายงาน
										// ==========================================================================================
										$i = 0;
										while($res = pg_fetch_array($qry_debt_due))
										{
											$i++;
											$contractID = $res["contractID"];
											
											// ==========================================================================================
											// ล้างค่าของรายการ
											// ==========================================================================================
											$money_function = 0.00;
											$Overdue = 0.00;
											$ptMinPay_1 = 0.00;
											$ptMinPay_2 = 0.00;
											$ptMinPay_3 = 0.00;
											$amtrestructure = 0.00;
											$amtsue = 0.00;
											
											$vwaitint_overdue_result = 0.00;
											$vwaitint_ptMinPay_1_result = 0.00;
											$vwaitint_ptMinPay_2_result = 0.00;
											$vwaitint_ptMinPay_3_result = 0.00;
											$vwaitint_restructure_result = 0.00;
											$vwaitint_sue_result = 0.00;
											$vwaitint_money_function_result = 0.00;
											
											// ==========================================================================================
											// หาเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก
											// ==========================================================================================
											$inter=pg_query("SELECT \"thcap_get_all_debtamt_acc\"('$contractID','$datepicker')");
											$resin=pg_fetch_array($inter);
											list($money_function)=$resin;
											
											// ==========================================================================================
											// ถ้า amountown น้อยกว่าหรือเท่ากับ 0 ให้ข้าม loop นี้ไปเลย ให้ไป loop ต่อไปทันที
											// ==========================================================================================
											if($money_function <= 0.00){ $i--; continue; }
											
											// ==========================================================================================
											// ค้นหาชื่อผู้กู้หลัก
											// ==========================================================================================
											$qry_namemain=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\" where \"contractID\"='$contractID' and \"CusState\"='0'");
											if($resnamemain=pg_fetch_array($qry_namemain)){
												$name3=trim($resnamemain["thcap_fullname"]);
											}
											else{
												$name3 = ""; // ถ้าไม่พบชื่อลูกค้า ให้เป็นค่าว่าง
											}
											
											// ==========================================================================================
											//หาว่าอยู่ระหว่างดำเนินคดีหรือไม่จาก function "thcap_get_all_isSue" ถ้าได้ TRUE แสดงว่า เป็นระหว่างคดี ถ้าได้ FALSE แสดงว่าไม่อยู่
											// ==========================================================================================
											$qryissue=pg_query("select \"thcap_get_all_isSue\"('$contractID','$datepicker')");
											list($issue)=pg_fetch_array($qryissue);

											
											// ==========================================================================================
											//หาว่าปรับโครงสร้างหรือไม่จาก function "thcap_get_all_isRestructure" ถ้าได้ TRUE แสดงว่า เป็นปรับโครงสร้างหนี้ ถ้าได้ FALSE แสดงว่าไม่อยู่
											// ==========================================================================================
											$qrystructure=pg_query("select \"thcap_get_all_isRestructure\"('$contractID','$datepicker')");
											list($isrestructure)=pg_fetch_array($qrystructure);
											
											
											
											// ==========================================================================================
											//ตรวจสอบเงื่อนไขว่าเงินอยู่ในช่องใด
											// ==========================================================================================
											if($issue==1 && $isrestructure!=1) { // อยู่ระหว่างฟ้อง และไม่ใช่ปรับโครงสร้างหนี้
												$amtsue = $money_function;
												
												// -----------------------------------------------
												// $vwaitint_sue - ฟ้อง
												// -----------------------------------------------

												// กรณีฟ้องให้เอาทั้งหมด
												$vwaitint_sue_result=pg_query("							
														SELECT 
															SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่ยังไม่ได้ถูกรับรู้รายได้
														FROM
															\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
														LEFT JOIN 
															\"public\".\"thcap_temp_voucher_details\" ON \"voucherID\" = \"voucherID_realize\"
														WHERE 
															\"contractID\"='$contractID' AND
															\"accdate\" >= '2013-01-01' AND
															(
																(\"voucherID_realize\" IS NULL) OR -- หาจากรายการที่ไม่มีการบันทึกการรับรู้รายได้โดยใบสำคัญ
																(\"voucherID_realize\" IS NOT NULL AND \"voucherDate\" > '$datepicker'::date) -- หาจากรายการที่มีการบันทึกการรับรู้รายได้ โดยใบสำคัญ แต่เป็นอนาคตกว่าวันที่สนใจ
															)
												");
												list($vwaitint_sue_result)=pg_fetch_array($vwaitint_sue_result);
												// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0 หรือในกรณีที่ SUM แล้วได้ผลลัพท์เป็น NULL
												if ($vwaitint_sue_result=='') {
													$vwaitint_sue_result = 0.00;
												}
												$vwaitint_sue += $vwaitint_sue_result;
												
											} else { // ลูกหนี้ปกติ
											
												// กำหนดให้ vwaitint ของปรับโครงสร้างหนี้ หรือ ฟ้อง = 0.00 ในกรณีนี้
												$vwaitint_restructure += 0.00;
												$vwaitint_sue += 0.00;


												// ==========================================================================================
												// ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (คงค้างชำระ)
												// ==========================================================================================
												$sql_str_func = pg_query("select \"thcap_get_all_backamt_db\"('$contractID', '$datepicker',2)");
												$str_func = pg_fetch_array($sql_str_func);
												list($Overdue) = $str_func;
												
												// ==========================================================================================
												// ตรวจสอบประเภทสัญญาและกำหนด QUERY ที่จะใช้หายอดหนี้ที่จะครบกำหนดชำระ
												// ==========================================================================================
												$sql_tpye_func = pg_query("select \"thcap_get_creditType\"('$contractID')");
												$type_func = pg_fetch_array($sql_tpye_func);
												list($credittype) = $type_func;
												if ($credittype == "LOAN" OR $credittype == "JOINT_VENTURE" OR $credittype == "FACTORING" OR $credittype == "GUARANTEED_INVESTMENT" OR $credittype == "PERSONAL_LOAN") {
													$queryfind = "select sum(\"ptMinPay\") as \"ptMinPay\" from account.\"thcap_mg_payTerm\"";
												} else if (
															$credittype == "HIRE_PURCHASE" OR $credittype == "LEASING" OR
															$credittype == "SALE_ON_CONSIGNMENT" OR $credittype == "PROMISSORY_NOTE") {
													$queryfind = "select sum(\"debtnet\") as \"ptMinPay\" from account.\"thcap_acc_filease_realize_eff_acc_present_y\"";
												}
												
												// ==========================================================================================
												// ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ)
												// ==========================================================================================
												$qry_ptMinPay_1 = pg_query("$queryfind where \"contractID\" = '$contractID' and \"duedate\" >= '$nextday' and \"duedate\" <= '$nextyear' ");
												while($res_ptMinPay_1 = pg_fetch_array($qry_ptMinPay_1))
												{
													$ptMinPay_1 = $res_ptMinPay_1["ptMinPay"];
												}
													
												// ==========================================================================================
												// ลูกหนี้ที่จะครบกำหนดชำระเกิน 1 ปี แต่ไม่เกิน 5 ปี
												// ==========================================================================================
												$qry_ptMinPay_2 = pg_query("$queryfind where \"contractID\" = '$contractID' and \"duedate\" >= '$next_oneyear_oneday' and \"duedate\" <= '$nextfiveyear' ");
												while($res_ptMinPay_2 = pg_fetch_array($qry_ptMinPay_2))
												{
													$ptMinPay_2 = $res_ptMinPay_2["ptMinPay"];
												}
													
												// ==========================================================================================
												// ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป
												// ==========================================================================================
												$qry_ptMinPay_3 = pg_query("$queryfind where \"contractID\" = '$contractID' and\"duedate\" >= '$next_fiveyear_oneday' ");
												while($res_ptMinPay_3 = pg_fetch_array($qry_ptMinPay_3))
												{
													$ptMinPay_3 = $res_ptMinPay_3["ptMinPay"];
												}
												
												// ********************************* คำนวณให้ลงรายการยอดจะครบกำหนดอย่างถูกต้อง *********************************
												
												// ==========================================================================================
												// กำหนดค่าให้รายการที่ไม่มีค่า = 0 (ที่ต้องกำหนดใหม่เนื่องจาก ไปเอาจาก base มา ได้ค่าเป็น null)
												// ==========================================================================================
												if($Overdue=="") 		$Overdue = 0.00;
												if($ptMinPay_1=="") 	$ptMinPay_1 = 0.00;
												if($ptMinPay_2=="") 	$ptMinPay_2 = 0.00;
												if($ptMinPay_3=="") 	$ptMinPay_3 = 0.00;
												if($amtrestructure=="")	$amtrestructure = 0.00;
												if($amtsue=="") 		$amtsue = 0.00;
												if($money_function=="")	$money_function = 0.00;
												
												// ==========================================================================================
												// นำข้อมูลเข้าช่องโดยสำหรับ LOAN นี้ที่ต้องจ่ายต่อปีเท่าเดิม แต่จ่ายล่วงหน้ามีผลหมดเร็วขึ้น แต่สำหรับ HIRE_PURCHASE / LEASING / GUARANTEED_INVESTMENT / FACTORING / SALE_ON_CONSIGNMENT / PROMISSORY_NOTE หนี้คงที่
												// ==========================================================================================
												if ($money_function > $Overdue + $ptMinPay_1 + $ptMinPay_2 && $ptMinPay_1 > 0 && $ptMinPay_2 > 0) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ เกิน 5 ปี
													if ($ptMinPay_3 == 0.00) { // ถ้างวด 3 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบใน ช่วง 2
														$ptMinPay_2 = $money_function - $Overdue  - $ptMinPay_1;
													} else {
														$ptMinPay_3 = $money_function - $Overdue - $ptMinPay_1 - $ptMinPay_2;
													}
												} else if ($money_function > $Overdue + $ptMinPay_1 && $ptMinPay_1 > 0) {  // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ แต่เกิน 1 ปี แต่ไม่ถึง 5 ปี
													if ($ptMinPay_2 == 0.00) { // ถ้างวด 2 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบในงวด ช่วง 1
														$ptMinPay_1 = $money_function - $Overdue;
													} else {
														$ptMinPay_2 = $money_function - $Overdue - $ptMinPay_1;
													}
													$ptMinPay_3 = 0.00;
												} else if ($money_function > $Overdue) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ มากกว่าค้างชำระ แต่ไม่เกิน 1 ปี
													if ($ptMinPay_1 == 0.00) { // ถ้างวด 1 ไม่มีให้ผ่อนอยู่แล้ว ก็ต้องจบในงวด ช่วง Overdue
														$Overdue = $money_function;
													} else {
														$ptMinPay_1 = $money_function - $Overdue;
													}
													$ptMinPay_2 = 0.00;
													$ptMinPay_3 = 0.00;
												} else if ($money_function <= $Overdue) { // จำนวนเงินต้นรวมดอกเบี้ยค้างรับ น้อยกว่าที่ค้างชำระ
													$Overdue = $money_function;
													$ptMinPay_1 = 0.00;
													$ptMinPay_2 = 0.00;
													$ptMinPay_3 = 0.00;
												}
											}
											
											// ==========================================================================================
											// รวมจำนวนเงินลูกหนี้ทั้งหมดที่จะนำไปแสดง
											// ==========================================================================================
											$sum_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ประเภทสัญญา]
											$sumall_money_function += $money_function; // รวมเงินต้นรวมดอกเบี้ยทั้งหมดถึงวันที่ user เลือก [ทั้งหมด]
											
											$sum_Overdue += $Overdue; // รวม Overdue ของทั้งประเภทสัญญา
											$sumall_Overdue += $Overdue; // รวม Overdue ของทั้งหมด
											
											$sum_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ประเภทสัญญา]
											$sumall_ptMinPay_1 += $ptMinPay_1; // รวม ลูกหนี้ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) [ทั้งหมด]

											$sum_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 1 ปี แต่ไม่เกิน 5 ปี [ประเภทสัญญา]
											$sumall_ptMinPay_2 += $ptMinPay_2; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 1 ปี แต่ไม่เกิน 5 ปี [ทั้งหมด]
											
											$sum_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ประเภทสัญญา]
											$sumall_ptMinPay_3 += $ptMinPay_3; // รวม ลูกหนี้ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป [ทั้งหมด]
											
											$sum_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ประเภทสัญญา]
											$sumall_restructure += $amtrestructure; // รวมปรับโครงสร้างหนี้ [ทั้งหมด]

											$sum_sue += $amtsue; // รวมฟ้อง [ประเภทสัญญา]
											$sumall_sue += $amtsue; // รวมฟ้อง [ทั้งหมด]
											
											// ==========================================================================================
											// รวมจำนวนเงินทรายได้ตัดพักรอการรับรู้รายได้
											// ==========================================================================================
											
											// ถ้าเป็นสัญญาที่ฟ้อง และไม่ใช่สัญญาปรับโครงสร้างหนี้ ไม่ต้องรอหารายงวด
											if (!($issue==1 && $isrestructure==0)){
												// -----------------------------------------------
												// $vwaitint_overdue - Overdue
												// -----------------------------------------------
												$vwaitint_overdue_result=pg_query("							
														SELECT 
															SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่ยังไม่ได้ถูกรับรู้รายได้
														FROM
															\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
														LEFT JOIN 
															\"public\".\"thcap_temp_voucher_details\" ON \"voucherID\" = \"voucherID_realize\"
														WHERE 
															\"contractID\"='$contractID' AND
															\"accdate\" >= '2013-01-01' AND
															\"accdate\" < '$nextday' AND
															(
																(\"voucherID_realize\" IS NULL) OR -- หาจากรายการที่ไม่มีการบันทึกการรับรู้รายได้โดยใบสำคัญ
																(\"voucherID_realize\" IS NOT NULL AND \"voucherDate\" > '$datepicker'::date) -- หาจากรายการที่มีการบันทึกการรับรู้รายได้ โดยใบสำคัญ แต่เป็นอนาคตกว่าวันที่สนใจ
															)
												");
												list($vwaitint_overdue_result)=pg_fetch_array($vwaitint_overdue_result);
												// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0 หรือในกรณีที่ SUM แล้วได้ผลลัพท์เป็น NULL
												if ($vwaitint_overdue_result==''){
													$vwaitint_overdue_result = 0.00;
												}
												$vwaitint_overdue += $vwaitint_overdue_result;
												
												// -----------------------------------------------
												// $vwaitint_ptMinPay_1 - ที่จะครบกำหนดชำระภายใน 1 ปี (ที่จะถึงกำหนดชำระ) 
												// -----------------------------------------------
												$vwaitint_ptMinPay_1_result=pg_query("							
														SELECT 
															SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่ยังไม่ได้ถูกรับรู้รายได้
														FROM
															\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
														LEFT JOIN 
															\"public\".\"thcap_temp_voucher_details\" ON \"voucherID\" = \"voucherID_realize\"
														WHERE 
															\"contractID\"='$contractID' AND
															\"accdate\" >= '$nextday' AND
															\"accdate\" <= '$nextyear' AND
															(
																(\"voucherID_realize\" IS NULL) OR -- หาจากรายการที่ไม่มีการบันทึกการรับรู้รายได้โดยใบสำคัญ
																(\"voucherID_realize\" IS NOT NULL AND \"voucherDate\" > '$datepicker'::date) -- หาจากรายการที่มีการบันทึกการรับรู้รายได้ โดยใบสำคัญ แต่เป็นอนาคตกว่าวันที่สนใจ
															)
												");
												list($vwaitint_ptMinPay_1_result)=pg_fetch_array($vwaitint_ptMinPay_1_result);
												// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0 หรือในกรณีที่ SUM แล้วได้ผลลัพท์เป็น NULL
												if ($vwaitint_ptMinPay_1_result=='') {
													$vwaitint_ptMinPay_1_result = 0.00;
												}
												$vwaitint_ptMinPay_1 += $vwaitint_ptMinPay_1_result;
												
												// -----------------------------------------------
												// $vwaitint_ptMinPay_2 - ที่จะครบกำหนดชำระเกิน 1 ปี แต่ไม่เกิน 5 ปี
												// -----------------------------------------------
												$vwaitint_ptMinPay_2_result=pg_query("							
														SELECT 
															SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่ยังไม่ได้ถูกรับรู้รายได้
														FROM
															\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
														LEFT JOIN 
															\"public\".\"thcap_temp_voucher_details\" ON \"voucherID\" = \"voucherID_realize\"
														WHERE 
															\"contractID\"='$contractID' AND
															\"accdate\" >= '$next_oneyear_oneday' AND 
															\"accdate\" <= '$nextfiveyear' AND
															(
																(\"voucherID_realize\" IS NULL) OR -- หาจากรายการที่ไม่มีการบันทึกการรับรู้รายได้โดยใบสำคัญ
																(\"voucherID_realize\" IS NOT NULL AND \"voucherDate\" > '$datepicker'::date) -- หาจากรายการที่มีการบันทึกการรับรู้รายได้ โดยใบสำคัญ แต่เป็นอนาคตกว่าวันที่สนใจ
															)
												");
												list($vwaitint_ptMinPay_2_result)=pg_fetch_array($vwaitint_ptMinPay_2_result);
												// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0 หรือในกรณีที่ SUM แล้วได้ผลลัพท์เป็น NULL
												if ($vwaitint_ptMinPay_2_result=='') {
													$vwaitint_ptMinPay_2_result = 0.00;
												}
												$vwaitint_ptMinPay_2 += $vwaitint_ptMinPay_2_result;

												// -----------------------------------------------
												// $vwaitint_ptMinPay_3 - ที่จะครบกำหนดชำระเกิน 5 ปี ขึ้นไป
												// -----------------------------------------------
												
												// กรณีเกิน 5 ปี ขึ้น ให้ใช้เงื่อนไขที่จะครบใน 5 ปีข้างหน้า ยกเว้นแต่จะชำระล่วงหน้าถึงขนาดนั้นแล้ว
												$vwaitint_ptMinPay_3_result=pg_query("							
														SELECT 
															SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่ยังไม่ได้ถูกรับรู้รายได้
														FROM
															\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
														LEFT JOIN 
															\"public\".\"thcap_temp_voucher_details\" ON \"voucherID\" = \"voucherID_realize\"
														WHERE 
															\"contractID\"='$contractID' AND
															\"accdate\" >= '$next_fiveyear_oneday' AND
															(
																(\"voucherID_realize\" IS NULL) OR -- หาจากรายการที่ไม่มีการบันทึกการรับรู้รายได้โดยใบสำคัญ
																(\"voucherID_realize\" IS NOT NULL AND \"voucherDate\" > '$datepicker'::date) -- หาจากรายการที่มีการบันทึกการรับรู้รายได้ โดยใบสำคัญ แต่เป็นอนาคตกว่าวันที่สนใจ
															)
												");
												list($vwaitint_ptMinPay_3_result)=pg_fetch_array($vwaitint_ptMinPay_3_result);
												// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0 หรือในกรณีที่ SUM แล้วได้ผลลัพท์เป็น NULL
												if ($vwaitint_ptMinPay_3_result=='') {
													$vwaitint_ptMinPay_3_result = 0.00;
												}
												$vwaitint_ptMinPay_3 += $vwaitint_ptMinPay_3_result;
											}

											// ==========================================================================================
											// Process ในการตรวจสอบค่า หากมีค่าไม่สอดคล้องในการแสดง ให้เป็น -999
											// ==========================================================================================
											// สาเหตุที่ใช้ postgres ในการรวมค่าเนื่องจาก เมื่อมี Operation เยอะๆ จะเกิด Bug เศษส่วนไกลๆ ทำให้ ไม่ลงตัว
											$pgcal=pg_query("select 
											CASE WHEN ('$Overdue'::numeric(15,2) + '$ptMinPay_1'::numeric(15,2) + '$ptMinPay_2'::numeric(15,2) + '$ptMinPay_3'::numeric(15,2) + '$amtrestructure'::numeric(15,2) + '$amtsue'::numeric(15,2))<>'$money_function'::numeric(15,2) THEN '1' ELSE '0' END as money_function,
											CASE WHEN ('$sum_Overdue'::numeric(15,2) + '$sum_ptMinPay_1'::numeric(15,2) + '$sum_ptMinPay_2'::numeric(15,2) + '$sum_ptMinPay_3'::numeric(15,2) + '$sum_restructure'::numeric(15,2) + '$sum_sue'::numeric(15,2))<>'$sum_money_function'::numeric(15,2) THEN '1' ELSE '0' END as sum_money_function,
											CASE WHEN ('$sumall_Overdue'::numeric(15,2) + '$sumall_ptMinPay_1'::numeric(15,2) + '$sumall_ptMinPay_2'::numeric(15,2) + '$sumall_ptMinPay_3'::numeric(15,2) + '$sumall_restructure'::numeric(15,2) + '$sumall_sue'::numeric(15,2))<>'$sumall_money_function'::numeric(15,2) THEN '1' ELSE '0' END as sumall_money_function");
											list($cmoney_function,$csum_money_function,$csumall_money_function)=pg_fetch_array($pgcal);
											
											if($cmoney_function=='1'){
												$money_function = -999;
											}
											
											if($csum_money_function=='1'){
												$sum_money_function = -999;
											}
											
											if($csumall_money_function=='1'){
												$sumall_money_function = -999;
											}
											
											// ==========================================================================================
											// สลับสีในการแสดงผล
											// ==========================================================================================
											if($i%2==0){
												echo "<tr class=\"odd\">";
											}else{
												echo "<tr class=\"even\">";
											}

											// ==========================================================================================
											// แสดงผลข้อมูลแต่ละรายการตามสัญญา
											// ==========================================================================================
											$vwaitint_money_function_result = $vwaitint_overdue_result + $vwaitint_ptMinPay_1_result + $vwaitint_ptMinPay_2_result + $vwaitint_ptMinPay_3_result + $vwaitint_restructure_result + $vwaitint_sue_result;
											
											$vwaitint_overdue_result = number_format($vwaitint_overdue_result, 2);
											$vwaitint_ptMinPay_1_result = number_format($vwaitint_ptMinPay_1_result, 2);
											$vwaitint_ptMinPay_2_result = number_format($vwaitint_ptMinPay_2_result, 2);
											$vwaitint_ptMinPay_3_result = number_format($vwaitint_ptMinPay_3_result, 2);
											$vwaitint_restructure_result = number_format($vwaitint_restructure_result, 2);
											$vwaitint_sue_result = number_format($vwaitint_sue_result, 2);
											$vwaitint_money_function_result = number_format($vwaitint_money_function_result, 2);
											
											$color_indicate_restructure = "";
											if($isrestructure==1) $color_indicate_restructure = "bgcolor=\"#F5BCA9\"";
																						
											echo "<td align=\"center\" $color_indicate_restructure>$i</td>";
											echo "<td align=\"center\" $color_indicate_restructure><a onClick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\" style=\"cursor:pointer;\"><FONT COLOR=#0000FF><u>$contractID</u></FONT></a></td>";
											echo "<td $color_indicate_restructure >$name3</td>";
											echo "<td align=\"right\" $color_indicate_restructure title=\"ดอกเบี้ยที่ตั้งพัก: $vwaitint_overdue_result\">".number_format($Overdue,2)."</td>";
											echo "<td align=\"right\" $color_indicate_restructure title=\"ดอกเบี้ยที่ตั้งพัก: $vwaitint_ptMinPay_1_result\">".number_format($ptMinPay_1,2)."</td>";
											echo "<td align=\"right\" $color_indicate_restructure title=\"ดอกเบี้ยที่ตั้งพัก: $vwaitint_ptMinPay_2_result\">".number_format($ptMinPay_2,2)."</td>";
											echo "<td align=\"right\" $color_indicate_restructure title=\"ดอกเบี้ยที่ตั้งพัก: $vwaitint_ptMinPay_3_result\">".number_format($ptMinPay_3,2)."</td>";
											echo "<td align=\"right\" $color_indicate_restructure title=\"ดอกเบี้ยที่ตั้งพัก: $vwaitint_restructure_result\">".number_format($amtrestructure,2)."</td>";
											echo "<td align=\"right\" $color_indicate_restructure title=\"ดอกเบี้ยที่ตั้งพัก: $vwaitint_sue_result\">".number_format($amtsue,2)."</td>";
											echo "<td align=\"right\" $color_indicate_restructure title=\"ดอกเบี้ยที่ตั้งพัก: $vwaitint_money_function_result\">".number_format($money_function,2)."</td>";
											echo "</tr>";
										}
										
										// ==========================================================================================
										// แสดงข้อมูลผลรวมรวมประเภทสัญญา
										// ==========================================================================================
										echo "<tr bgcolor=\"#FFCCCC\">";
										echo "<td COLSPAN=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; รวมประเภทสัญญา $contypechk[$con]</th>";
										echo "<td align=\"right\">".number_format($sum_Overdue,2)."</th>";
										echo "<td align=\"right\">".number_format($sum_ptMinPay_1,2)."</th>";
										echo "<td align=\"right\">".number_format($sum_ptMinPay_2,2)."</th>";
										echo "<td align=\"right\">".number_format($sum_ptMinPay_3,2)."</th>";
										echo "<td align=\"right\">".number_format($sum_restructure,2)."</th>";
										echo "<td align=\"right\">".number_format($sum_sue,2)."</th>";
										echo "<td align=\"right\">".number_format($sum_money_function,2)."</th>";
										echo "</tr>";
										
										// ==========================================================================================
										// แสดงข้อมูลรายได้ตั้งพักรอการรับรู้รายได้ส่วนที่ครบกำหนดในแต่ละช่วงเวลา
										// ==========================================================================================
										$vwaitint_money_function = $vwaitint_overdue + $vwaitint_ptMinPay_1 + $vwaitint_ptMinPay_2 + $vwaitint_ptMinPay_3 + $vwaitint_restructure + $vwaitint_sue;
										
										echo "<tr bgcolor=\"#F5DA81\">";
										echo "<td COLSPAN=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; รายได้ตั้งพักรอการรับรู้ $contypechk[$con]</th>";
										echo "<td align=\"right\">".number_format($vwaitint_overdue,2)."</th>";
										echo "<td align=\"right\">".number_format($vwaitint_ptMinPay_1,2)."</th>";
										echo "<td align=\"right\">".number_format($vwaitint_ptMinPay_2,2)."</th>";
										echo "<td align=\"right\">".number_format($vwaitint_ptMinPay_3,2)."</th>";
										echo "<td align=\"right\">".number_format($vwaitint_restructure,2)."</th>";
										echo "<td align=\"right\">".number_format($vwaitint_sue,2)."</th>";
										echo "<td align=\"right\">".number_format($vwaitint_money_function,2)."</th>";
										echo "</tr>";
										
										// ==========================================================================================
										// แสดงข้อมูลลูกหนี้ตามสัญญาหลังหักรายได้ตั้งพักรอการรับรู้รายได้แล้ว
										// ==========================================================================================
										echo "<tr bgcolor=\"#A9F5BC\">";
										echo "<td COLSPAN=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ลูกหนี้หลังหักรายได้ตั้งพักรอการรับรู้รายได้ $contypechk[$con]</th>";
										echo "<td align=\"right\">".number_format($sum_Overdue - $vwaitint_overdue,2)."</th>";
										echo "<td align=\"right\">".number_format($sum_ptMinPay_1 - $vwaitint_ptMinPay_1,2)."</th>";
										echo "<td align=\"right\">".number_format($sum_ptMinPay_2 - $vwaitint_ptMinPay_2,2)."</th>";
										echo "<td align=\"right\">".number_format($sum_ptMinPay_3 - $vwaitint_ptMinPay_3,2)."</th>";
										echo "<td align=\"right\">".number_format($sum_restructure - $vwaitint_restructure,2)."</th>";
										echo "<td align=\"right\">".number_format($sum_sue - $vwaitint_sue,2)."</th>";
										echo "<td align=\"right\">".number_format($sum_money_function - $vwaitint_money_function,2)."</th>";
										echo "</tr>";
									}
								}
								
								// ==========================================================================================
								// แสดงข้อมูลผลรวมทั้งหมด
								// ==========================================================================================
								echo "<tr bgcolor=\"#ffb0e3\" style=\"font-weight:bold;\">";
								echo "<td COLSPAN=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; รวมทั้งสิ้น</th>";
								echo "<td align=\"right\">".number_format($sumall_Overdue,2)."</th>";
								echo "<td align=\"right\">".number_format($sumall_ptMinPay_1,2)."</th>";
								echo "<td align=\"right\">".number_format($sumall_ptMinPay_2,2)."</th>";
								echo "<td align=\"right\">".number_format($sumall_ptMinPay_3,2)."</th>";
								echo "<td align=\"right\">".number_format($sumall_restructure,2)."</th>";
								echo "<td align=\"right\">".number_format($sumall_sue,2)."</th>";
								echo "<td align=\"right\">".number_format($sumall_money_function,2)."</th>";
								echo "</tr>";
								?>
							</table>
							</div>
						<?php
						}
						?>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
</table>
</form>
</body>
</html>