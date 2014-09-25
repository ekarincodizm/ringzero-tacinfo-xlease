<?php 
include("../../../config/config.php");

set_time_limit(0);

$datenow = nowDate();
list($nowy,$nowm,$nowd) = explode("-",$datenow);

//รับข้อมูล
	$month = pg_escape_string($_POST["month"]); //รับเดือน
	$year = pg_escape_string($_POST["year"]); //รับปี
	$showtable = pg_escape_string($_POST["showstate"]); //สถานะการแสดงตารางข้อมูล
	$contypechk = $_POST['contype']; //ประเภทสัญญาที่จะให้แสดง

//หากไม่มีการเลือกเดือนหรือเปิดหน้า page มาใหม่จะเลือกเดือนย้อนหลังไปจากเดือนปัจจุบัน 1 เดือน
	if($month == ""){
		$month = $nowm-1;
		$year = $nowy;
		if($month == '00' Or $month == '0'){
			$month = "12";
			$year = $nowy - 1;
		}
	}
//นำค่า array ของประเภทสัญญามาต่อกันเป็น string เพื่อรอการส่งค่าแบบ GET	
	for($con = 0;$con < sizeof($contypechk) ; $con++){
		if($sendpdf==""){
			$sendpdf = pg_escape_string($contypechk[$con]);
		}else{
			$sendpdf = $sendpdf."@".pg_escape_string($contypechk[$con]);
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>(THCAP) รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

	<link type="text/css" rel="stylesheet" href="act.css"></link>

	<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
	<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
				//เมื่อกด ข้อความ  "แสดงเฉพาะ :" 			
			$("#selectcontype").click(function(){			
			var ele_contype = $("input[name=contype[]]");
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
					$(ele_contype[i]).attr ( "checked" ,"checked" );
				}
			}
			else
			{ 	//เอาติ้ก ถูก ออก ทั้งหมด
				for (i=0; i< ele_contype.length; i++)
				{
					$(ele_contype[i]).removeAttr('checked');
				}
			}
		});
	});
	</script>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div style="padding:50px 0px 0px 0px;"></div>
<form name="frm" action="Index.php" method="POST">
<table width="1350" align="center" border="0">
<tr>
	<td>
		<table align="left" frame="box" height="50px" bgcolor="#6E7B8B"><tr><td><font size="5px" color="white">(THCAP) รายงานเงินต้นดอกเบี้ยคงเหลือสิ้นเดือน</font></td></tr></table>
	</td>
</tr>
<tr>
	<td>
		<table align="center" frame="box" width="100%" bgcolor="#A2B5CD">
			<tr>
				<td width="300"><font color="#BB0000">*จำนวนวันที่ค้าง คิดถึงสิ้นสุดของเดือนนั้นๆ</font></td>
				<td align="center">
					เดือน :
					<select name="month">
						<option value="01" <?php if($month=="01") echo "selected";?>>มกราคม</option>
						<option value="02" <?php if($month=="02") echo "selected";?>>กุมภาพันธ์</option>
						<option value="03" <?php if($month=="03") echo "selected";?>>มีนาคม</option>
						<option value="04" <?php if($month=="04") echo "selected";?>>เมษายน</option>
						<option value="05" <?php if($month=="05") echo "selected";?>>พฤษภาคม</option>
						<option value="06" <?php if($month=="06") echo "selected";?>>มิถุนายน</option>
						<option value="07" <?php if($month=="07") echo "selected";?>>กรกฎาคม</option>
						<option value="08" <?php if($month=="08") echo "selected";?>>สิงหาคม</option>
						<option value="09" <?php if($month=="09") echo "selected";?>>กันยายน</option>
						<option value="10" <?php if($month=="10") echo "selected";?>>ตุลาคม</option>
						<option value="11" <?php if($month=="11") echo "selected";?>>พฤศจิกายน</option>
						<option value="12" <?php if($month=="12") echo "selected";?>>ธันวาคม</option>
					</select>
				
					ปี :
					<select name="year">
						<?php 
							
							for($i=$nowy;$i>$nowy-50;$i--){
								echo "<option value=\"$i\" "; if($year == $i){ echo "selected";  } echo ">$i</option>";
							}
						?>
					</select>
					<input type="hidden" value="show" name="showstate">
					<input type="submit" value="ค้นหา">
				</td>
				<td align="right" width="300">
					<a style="cursor:pointer;" onclick="javascript:popU('frm_pdf.php?month=<?php echo $month; ?>&year=<?php echo $year ; ?>&contype=<?php echo $sendpdf ; ?>');">
						<img src="images/pdf.png" width="20px" height="25px">
							<b><u>พิมพ์ PDF</u></b></a>
					<a style="cursor:pointer;" onclick="javascript:popU('frm_excel.php?month=<?php echo $month; ?>&year=<?php echo $year ; ?>&contype=<?php echo $sendpdf ; ?>');">
						<img src="images/excel.png" width="20px" height="25px">
							<b><u>พิมพ์ EXCEL</u></b></a>
				</td>	
			</tr>
			<tr>
				<td colspan="4">
					<input type="hidden" id="clear" value="Y"/>
					<span id="selectcontype" style="cursor:pointer;"><u><font color="#0000CC"><B>แสดงเฉพาะ :</B></font></u></span>
					
						<?php 
						//แสดงประเภทสัญญา
							$qry_contype = pg_query("SELECT distinct(\"conType\") as contype FROM thcap_contract 
							ORDER BY contype");
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
				</td>				
			</tr>
			<tr>
				<td colspan="4">					
					<fieldset><legend><b><u>คำอธิบาย</u></b></legend>
						<div>"ยอดสินเชื่อเริ่มแรก" : ยอดเงินต้นสัญญากู้ หรือยอดสินค้าก่อนภาษีมูลค่าเพิ่มสำหรับสัญญาเช่า หรือเช่าซื้อ</div>
						<div>"เงินต้นคงเหลือ" : เงินต้นคงเหลือ ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก</div>
						<div>"ดอกเบี้ยรับ" : ดอกเบี้ยที่รับมาแล้วจริงจากที่ลูกค้าจ่ายทั้งหมดตั้งแต่เริ่มสัญญา</div>
						<div>"ดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระ" : ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว</div>
						<div>"ดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระ (ทางบัญชี)" : ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือกหรือถึงวันที่หยุดรับรู้รายได้ทางบัญชี หักด้วยที่ลูกค้าได้ชำระมาแล้ว เฉพาะสัญญา CG, FA, JV, LI, MG, PL, PN, SM</div>
						<div>"รายได้ดอกเบี้ยในรอบปี (รับรู้ไม่เกิน 3 เดือน)" : ดอกเบี้ยที่ถึงกำหนดชำระแล้วทั้งที่ลูกค้าชำระมาแล้ว และยังไม่ชำระ แต่รับไม่เกิน 3 เดือนจากวันที่เริ่มค้างชำระ (Default Date)</div>
						<div>"รวมคงเหลือที่จะต้องรับชำระ" : เงินที่ลูกค้าจะต้องชำระทั้งหมดหากต้องการปิดบัญชี (เฉพาะค่างวด หรือค่าใช้จ่ายตามสัญญาทั้งหมด)</div>
						<div>"ดอกเบี้ยคงเหลือทั้งสัญญา (การเงิน)" : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว (เฉพาะ สัญญาเช่า หรือเช่าซื้อ)</div>
						<div>"ดอกเบี้ยคงเหลือทั้งสัญญา (บัญชี)" : ดอกเบี้ยคงเหลือทั้งสัญญาหักด้วยดอกเบี้ยทั้งหมดที่ลูกค้าได้ชำระมาแล้ว หักด้วยดอกเบี้่ยค้างรับ (เท่ากับดอกเบี้ยตั้งพักรอรับรู้)</div>
					</fieldset>
				</td>
			</tr>
		</table>	
	</td>
</tr>
<?php if($showtable != ""){ //หากมีการกดปุ่มค้นหาจะแสดงข้อมูลเป็นตาราง?> 
<tr>
	<td>
		<input type="button" value="แสดงตามปีลูกหนี้" onclick="javascript:popU('frm_showgroup.php?month=<?php echo $month;?>&year=<?php echo $year; ?>&contype=<?php echo $sendpdf ; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1160,height=800')" style="cursor:pointer;">
		<table frame="box" width="100%" align="center">
			<tr bgcolor="#B4CDCD">
				<td align="center">เลขที่สัญญา</td>
				<td align="center">ชื่อผู้กู้หลัก</td>
				<td align="center">ยอดสินเชื่อ<br>เริ่มแรก</td>
				<td align="center">เงินต้นคงเหลือ</td>
				<td align="center">ดอกเบี้ยที่เกิดขึ้น<br>ที่ยังไม่ได้รับชำระ</td>
				<td align="center">ดอกเบี้ยที่เกิดขึ้น<br>ที่ยังไม่ได้รับชำระ (ทางบัญชี)</td>
				<td align="center">รายได้ดอกเบี้ยในรอบปี<br>(รับรู้ไม่เกิน 3 เดือน)</td>
				<td align="center">รวมคงเหลือ<br>ที่จะต้องรับชำระ</td>
				<td align="center">จำนวนวันที่ค้าง</td>
				<td align="center">ดอกเบี้ยคงเหลือ<br>ทั้งสัญญา<br>(การเงิน)</td>
				<td align="center">ดอกเบี้ยคงเหลือ<br>ทั้งสัญญา<br>(บัญชี)</td>
			</tr>
			
			<?php
			$i = 0;
			$allrows = 0; //จำนวนข้อมูลทั้งหมด
			
			// ============================================================================================
			//หาวันที่สุดท้ายของเดือน (ตรวจสอบแล้ว 2014-02-06)
			// ============================================================================================
			$qryday=pg_query("select \"gen_numDaysInMonth\"('$month','$year')");
			list($day)=pg_fetch_array($qryday);			
			//กำหนดวันที่สนใจเพื่อนำเข้า function
			$vfocusdate=$year.'-'.$month.'-'.$day;
			// วันแรกของปี สำหรับรายการที่ต้องการตัวเลขภายในปีที่เลือก ตั้งแต่ต้นปี
			$vfirstdateofyear=$year.'-01-01';
			
			// ============================================================================================
			//วนตามประเภทสัญญาที่เลือก (ตรวจสอบแล้ว 2014-02-06)
			// ============================================================================================
			for($con = 0;$con < sizeof($contypechk) ; $con++){
				$numall = 0;
				
				//แสดงประเภทอยู่ด้านบนข้อมูล
				echo "<tr bgcolor=\"#CDB79E\"><td colspan=\"12\"><b>$contypechk[$con]</b></td></tr>";
				
				// ============================================================================================
				//หาเลขที่สัญญาทั้งหมดที่เกิดขึ้นในช่วงเดือนปี ที่เลือกและเป็นประเภทสัญญาที่เลือกในเบื้องต้น (ตรวจสอบแล้ว 2014-02-06)
				// และสัญญาดังกล่าวจะต้องยังไม่ปิดบัญชี
				// ============================================================================================
				$qrycontract=pg_query("	select
											\"contractID\", \"thcap_checkcontractcloseddate\"(\"contractID\",'$vfocusdate') as conclosedate
										from
											\"thcap_contract\" 
										where
											\"conStartDate\" <= '$vfocusdate' and 
											\"conType\" = '$contypechk[$con]' 
										order by \"contractID\"
				");

				//นับจำนวนข้อมูลที่ค้นพบ	
				$rownum = pg_num_rows($qrycontract);
				
				// ============================================================================================
				//ดักการแสดงข้อมูล (ตรวจสอบแล้ว 2014-02-06)
				// ============================================================================================
				if($rownum > 0){ //หากจำนวนข้อมูลที่พบมากกว่าศูนย์
					$listallrows=0;
					while($rescon=pg_fetch_array($qrycontract)){
						$contractID=$rescon["contractID"];
						$conclosedate=$rescon["conclosedate"];
						
						// ============================================================================================
						// ตรวจสอบว่าถ้าสัญญาดังกล่าวปิดสัญญา ไม่ว่าจะด้วยชำระเสร็จสิ้น หรือด้วยขายหนี้ หรือถูกยึดแล้ว จะต้องปิดสัญญา (เฉพาะสัญญาที่ปิดในปีก่อนๆ สำหรับปิดในปีนี้ ยังต้องแสดงอยู่ เพราะต้องแสดงยอดรับรู้รายได้ปีนั้นๆ แต่ให้เงินต้น/ลูกหนี้ เหลือ = 0)
						// ============================================================================================
						if ($conclosedate <= $vfocusdate AND $conclosedate != '' AND $conclosedate < $vfirstdateofyear) continue;
						
						// ============================================================================================
						// หาประเภทสินเชื่อ (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						$qrytype=pg_query("select \"thcap_get_creditType\"('$contractID')");
						list($contype)=pg_fetch_array($qrytype);
						
						// ============================================================================================
						// หาชื่อลูกค้า (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						$sqlcus = pg_query("SELECT thcap_fullname from\"vthcap_ContactCus_detail\"
						where \"contractID\" = '$contractID' and \"CusState\" = '0' ");
						list($fullname) = pg_fetch_array($sqlcus);
						
						// ============================================================================================
						// ยอดสินเชื่อเริ่มแรก ตามประเภทสินเชื่อ (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){

							$qrystartamt=pg_query("	select 
														\"conLoanAmt\"
													from
														\"thcap_contract\"
													where
														\"contractID\"='$contractID'
							");
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){
									
							$qrystartamt=pg_query("	select 
														\"conFinAmtExtVat\"
													from
														\"thcap_contract\"
													where
														\"contractID\"='$contractID'
							");
						}
						list($conLoanAmt)=pg_fetch_array($qrystartamt);
						$conLoanAmt_prin = number_format($conLoanAmt,2);

						// ============================================================================================
						//จำนวนเงินต้นคงเหลือ (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){
							
							$sql1 = pg_query("SELECT \"thcap_getPrincipleOfGenCloseMonth\"('$contractID','$year','$month')");
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){
									
							$sql1 = pg_query("SELECT \"thcap_getPrinciple\"('$contractID','$vfocusdate')");	
						}
						
						list($getPrincipleOfGenCloseMonth) = pg_fetch_array($sql1);
						// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0
						if ($conclosedate <= $vfocusdate AND $conclosedate != '') {
							$getPrincipleOfGenCloseMonth = 0.00;
						}
						$getPrincipleOfGenCloseMonthshow = number_format($getPrincipleOfGenCloseMonth,2);

						// ============================================================================================
						// หาดอกเบี้ยที่เกิดขึ้นที่ยังไม่ได้รับชำระถึงสิ้นเดือนที่เลือก (ตรวจสอบแล้ว 2014-02-06)
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){
							
							// หายอดดอกเบี้ยค้างรับทาง ระบบ
							$sql1 = pg_query("SELECT \"thcap_getInterestOfGenCloseMonth\"('$contractID','$year','$month')");
							list($getInterestOfGenCloseMonth) = pg_fetch_array($sql1);
							
							// หายอดดอกเบี้ยค้างรับทาง บัญชี
							$sql1 = pg_query("SELECT \"thcap_getInterestOfGenCloseMonth\"('$contractID','$year','$month', 2)");
							list($getInterestOfGenCloseMonth_acc) = pg_fetch_array($sql1);
							
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){ //และดอกเบี้ยคงเหลือทั้งสัญญา
							
							// ===================================================================================================
							// หาหนี้ลูกหนี้คงเหลือทั้งสัญญา (เงินต้น + ดอกเบี้ย ก่อนภาษีมูลค่าเพิ่ม)
							// ===================================================================================================
								$sumin_prin1=""; // จำนวนดอกเบี้ยคงเหลือทางบัญชีทั้งหมด ณ วันที่รับชำระล่าสุด
								$sql1=pg_query("							
										SELECT
											MIN(\"totaldebt_left\") -- หนี้คงเหลือ
										FROM
											\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
										WHERE 
											\"receiveDate\"::date <= '$vfocusdate'::date AND
											\"contractID\" = '$contractID'
								");
								list($sumin_prin1)=pg_fetch_array($sql1);
							
								// ถ้าไม่มีข้อมูลใดๆเลยอาจจะยังไม่เคยจ่าย ให้ใช้ยอดเงินต้นคงเหลือแรกสุดก่อนรายการ
								if($sumin_prin1=="") {
									$sql1=pg_query("							
											SELECT
												MAX(\"totaldebt_before\"), -- หนี้คงเหลือ
												MAX(\"totalinterest_before\") -- ดอกเบี้ยเริ่มต้น
											FROM
												\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
											WHERE
												\"contractID\" = '$contractID'
									");
									list($sumin_prin1,$start_interest)=pg_fetch_array($sql1);
								}
							
								// เงินต้นทุกกรณี โดยเฉพาะ FL จะต้อง + ค่าซากเข้าไปด้วย
								$residue=""; // ค่าซาก
								$sql1=pg_query("							
										SELECT
											\"conResidualValue\" -- หนี้คงเหลือ
										FROM
											\"public\".\"thcap_lease_contract\"
										WHERE 
											\"contractID\" = '$contractID'
								");
								list($residue)=pg_fetch_array($sql1);
								
								// *todo ยังไม่รองรับการ update ค่าซากที่ปิดสัญญา หากชำระมาแล้ว
								// หากมีค่าซากจะต้องเพิ่มค่าซากลงไปในยอดลูกหนี้ด้วย
								if ($residue=="") 
									$residue = 0.00;
								else
									$sumin_prin1 += $residue;
							
							$getInterestALLacc_as_lastreceivedate=""; // จำนวนดอกเบี้ยคงเหลือทางบัญชีทั้งหมด ณ วันที่รับชำระล่าสุด
							$sql1=pg_query("							
									SELECT
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยคงเหลือทางบัญชีทั้งหมดหลังรับชำระ
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									WHERE 
										\"receiveDate\"::date <= '$vfocusdate'::date AND
										\"contractID\"= '$contractID'
							");
							list($getInterestALLacc_as_lastreceivedate)=pg_fetch_array($sql1);
							if ($getInterestALLacc_as_lastreceivedate == "") $getInterestALLacc_as_lastreceivedate = 0.00;

							$getInterestALLacc_as_focusdate=""; // จำนวนดอกเบี้ยคงเหลือทางบัญชีทั้งหมด ณ วันสิ้นเดือนที่ focus
							$sql1=pg_query("							
									SELECT 
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยคงเหลือทางบัญชีทั้งหมดหลังปิดยอดบัญชีรายการนี้
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									WHERE 
										\"contractID\"='$contractID' AND
										\"accdate\"<='$vfocusdate'
							");
							list($getInterestALLacc_as_focusdate)=pg_fetch_array($sql1);
							
							$getInterestALL_as_lastreceivedate=""; // จำนวนดอกเบี้ยคงเหลือจากการรับชำระทั้งหมด ณ วันสิ้นเดือนที่ focus
							$sql2=pg_query("							
									SELECT 
										MIN(\"totalinterest_left\") -- หาดอกเบี้ยคงเหลือทางบัญชีทั้งหมดหลังปิดยอดบัญชีรายการนี้
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									WHERE 
										\"receiveDate\"::date <= '$vfocusdate'::date AND
										\"contractID\"= '$contractID'
							");
							list($getInterestALL_as_lastreceivedate)=pg_fetch_array($sql2);
							if ($getInterestALL_as_lastreceivedate == "") $getInterestALL_as_lastreceivedate = $start_interest; // ถ้าไม่มีแสดงว่าอาจไม่เคยรับชำระ ดอกเบี้ยจะเท่ากับดอกเบี้ยเริ่มต้น

							
							// ดอกเบี้ยคำนวณถึง ณ สิ้นวันของวันที่สิ้นเดือนของเดือนและปีที่เลือก หักด้วยที่ลูกค้าได้ชำระมาแล้ว
							$getInterestOfGenCloseMonth = $getInterestALLacc_as_focusdate - $getInterestALLacc_as_lastreceivedate;
							
							// ถ้าดอกเบี้ยคงค้าง น้อยกว่า 0 แสดงว่าจ่ายถึงปัจจุบัน
							if($getInterestOfGenCloseMonth < 0){
								$getInterestOfGenCloseMonth = 0.00;
							}
							
							$getInterestOfGenCloseMonth_acc = '';
						}
						
						// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0
						if ($conclosedate <= $vfocusdate AND $conclosedate != '') {
							$getInterestOfGenCloseMonth = 0.00;
							$getInterestOfGenCloseMonth_acc = 0.00;
						}
						
						$getInterestOfGenCloseMonthshow = number_format($getInterestOfGenCloseMonth,2);
						$getInterestOfGenCloseMonth_accshow = number_format($getInterestOfGenCloseMonth_acc,2);
						$getInterestALL_format = number_format($getInterestALL_as_lastreceivedate,2);
						
						// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0 !!! NOTE: ถ้า comment ในส่วนนี้ออกระบบ จะแสดงยอดลูกหนี้คงเหลือของสัญญา EIR ประเภทนั้นๆ
						if ($conclosedate <= $vfocusdate AND $conclosedate != '') $sumin_prin1 = 0.00;
						
						// ============================================================================================
						// หาดอกเบี้ยที่รับรู้รายได้ไม่เกิน 3 เดือนถึงสิ้นเดือนที่เลือก
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){
							
							$sql1=pg_query("							
									SELECT 
										SUM(\"realize_amount\") -- หาดอกเบี้ยคงเหลือทางบัญชีทั้งหมดหลังปิดยอดบัญชีรายการนี้
									FROM
										\"public\".\"thcap_temp_int_acc\"
									WHERE 
										\"contractID\"='$contractID' AND
										\"intacc_date\"<='$vfocusdate' AND
										\"intacc_date\">='$vfirstdateofyear'
							");
							list($getAccruedInterest)=pg_fetch_array($sql1);
							
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){ //และดอกเบี้ยคงเหลือทั้งสัญญา
							
							/*
								-- todo Query ที่ถูกต้องตาม practice ที่ควรจะเป็น แต่เนื่องจากพบ bug ในการ gen บัญชี
							$sql1=pg_query("							
									SELECT 
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่รับรู้รายได้ไปแล้ว
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									WHERE 
										\"contractID\"='$contractID' AND
										(
											( \"voucherID_realize\" IS NOT NULL AND \"accdate\" >= '$vfirstdateofyear' AND \"accdate\" <= '$vfocusdate') OR -- รับรู้ถึงวันที่สนใจ ถ้ายังรับรู้อยู่
											( \"voucherID_realize\" IS NULL AND \"accdate\" >= '$vfirstdateofyear' AND \"accdate\" <= '2012-01-01') OR -- กรณีปี 2012 แม้ไม่มี voucher ก็ให้รับรู้ เพราะไม่มีใครในระบบผิดนัด จนไม่รับรู้
											( \"voucherID_realize\" IS NOT NULL AND \"receiveDate\" >= '$vfirstdateofyear' AND \"receiveDate\" <= '$vfocusdate') -- กรณีที่มีการจ่ายล่วงหน้าในปีนั้นๆ จะต้องรับรู้ด้วย
										)
							");
							*/
							
							// - todo แก้ไขเป็น Query ก่อนหน้า ตัวอย่างปัญหา BH-BK01-5500004
							$sql1=pg_query("							
									SELECT 
										SUM(\"recinterest_cut\") -- หาดอกเบี้ยทั้งหมดที่รับรู้รายได้ไปแล้วในปีนี้
									FROM
										\"account\".\"thcap_acc_filease_realize_eff_acc_present_y\"
									LEFT JOIN 
										\"public\".\"thcap_temp_voucher_details\" ON \"voucherID\" = \"voucherID_realize\"
									WHERE 
										\"contractID\"='$contractID' AND
										(
											( \"voucherID_realize\" IS NOT NULL AND \"voucherDate\" >= '$vfirstdateofyear'::date AND \"voucherDate\" <= '$vfocusdate'::date) OR
											( \"voucherID_realize\" IS NULL AND \"accdate\" >= '$vfirstdateofyear' AND \"accdate\" <= '2012-01-01')
										)
							");
							list($getAccruedInterest)=pg_fetch_array($sql1);
							
						}
						$getAccruedInterestshow = number_format($getAccruedInterest,2);
						
						// ============================================================================================
						// รวมคงเหลือที่จะต้องรับชำระ
						// ============================================================================================
						if(	$contype=='LOAN'or
							$contype=='JOINT_VENTURE' or
							$contype=='PERSONAL_LOAN'){
							
							// หนี้คงเหลือคือเงินต้น + ดอกเบี้ยถึงสิ้นเดือนที่ปิดบัญชี
							$sumin_prin1 = $getPrincipleOfGenCloseMonth + $getInterestOfGenCloseMonth;
							
						}else if(	$contype=='HIRE_PURCHASE' or 
									$contype=='LEASING' or
									$contype=='GUARANTEED_INVESTMENT' or
									$contype=='FACTORING' or
									$contype=='PROMISSORY_NOTE' or
									$contype=='SALE_ON_CONSIGNMENT' ){
							
							// หนี้คงเหลือ คือ ยอดผ่อนทั้งหมดทที่เหลือหลังรับชำระล่าสุด
							$sumin_prin1 = $sumin_prin1;
						}
						$sumin_prin = number_format($sumin_prin1,2);
						
						// ============================================================================================
						// จำนวนวันที่ค้าง			
						// ============================================================================================
						$sql1 = pg_query("SELECT \"thcap_get_all_backdays_db\"('$contractID','$vfocusdate',1)");
						list($thcap_backDueNumDays) = pg_fetch_array($sql1);
						if($thcap_backDueNumDays == ""){ $thcap_backDueNumDays = "-"; }
						
						// ============================================================================================
						// ดอกเบี้ยคงเหลือทั้งสัญญา (ทางการเงิน)
						// ============================================================================================
						if($getInterestALL_as_lastreceivedate==""){
							$getInterestALL_format="";
							$color="bgcolor=#DDDDDD";
						}else{
							$color="";
						}
						
						// ============================================================================================
						// ดอกเบี้ยคงเหลือทั้งสัญญา (ทางบัญชี)
						// ============================================================================================
						if(	$contype=='HIRE_PURCHASE' or 
							$contype=='LEASING' or
							$contype=='GUARANTEED_INVESTMENT' or
							$contype=='FACTORING' or
							$contype=='PROMISSORY_NOTE' or
							$contype=='SALE_ON_CONSIGNMENT' ) {
							
							$sql1=pg_query("							
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
											(\"voucherID_realize\" IS NOT NULL AND \"voucherDate\" > '$vfocusdate'::date) -- หาจากรายการที่มีการบันทึกการรับรู้รายได้ โดยใบสำคัญ แต่เป็นอนาคตกว่าวันที่สนใจ
										)
							");
							list($getInterestLeftAcc)=pg_fetch_array($sql1);
							// ถ้าบัญชีปิดแล้ว ไม่ว่าด้วยเหตุใดๆ หนี้ก็จะต้องเป็น 0
							if ($conclosedate <= $vfocusdate AND $conclosedate != '') {
								$getInterestLeftAcc = 0.00;
							}
							
							$getInterestLeftAccshow = number_format($getInterestLeftAcc,2); // ตัวเลขสำหรับนำไปแสดง
						}
						else{
							$color="";
						}
						
						//if($sumin_prin1 > 0){	//หากเงินคงเหลือมากกว่า ศูนย์จึงจะแสดงข้อมูล (แก้ไข 2014-03-10 comment ออก เนื่องจากเปลือง perf ที่มาตรวจสอบทั้งหมดว่าไม่ใช่ตอนสุดท้าย และไล่หายาก)
							//นับจำนวนแถวเพื่อสลับสีแถว ให้อ่านง่ายขึ้น
							if($i%2==0){
								echo "<tr bgcolor=#EED5B7 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EED5B7';\" align=center>";
							}ELSE{
								echo "<tr bgcolor=#FFE4C4 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFE4C4';\" align=center>";
							}
							
							if($contype=='HIRE_PURCHASE' || $contype=='LEASING' || $contype=='FACTORING' || $contype=='PROMISSORY_NOTE' || $contype=='SALE_ON_CONSIGNMENT')
							{
								$popupRevenue = "<img src=\"../images/detail.gif\" width=\"20px\" height=\"20px\" onclick=\"javascript:popU('../../thcap_installments/frm_Realize.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=800')\" style=\"cursor:pointer;\">";
							}
							else
							{
								$popupRevenue = "";
							}

							//แสดงข้อมูล
							echo "
								<td width=\"15%\" align=\"left\"><a onclick=\"javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\">
								<font color=\"red\"><u>$contractID</u></font></a> $popupRevenue</td>
								<td width=\"20%\" align=\"left\">$fullname</td>
								<td width=\"15%\"align=\"right\">$conLoanAmt_prin</td>
								<td width=\"15%\"align=\"right\">$getPrincipleOfGenCloseMonthshow</td>
								<td width=\"15%\"align=\"right\">$getInterestOfGenCloseMonthshow</td>
								<td width=\"15%\"align=\"right\">$getInterestOfGenCloseMonth_accshow</td>
								<td width=\"15%\"align=\"right\">$getAccruedInterestshow</td>
								<td width=\"15%\"align=\"right\">$sumin_prin</td>
								<td width=\"15%\"align=\"center\">$thcap_backDueNumDays</td>
								<td width=\"15%\"align=\"center\" $color>$getInterestALL_format</td>					
								<td width=\"15%\"align=\"center\" $color>$getInterestLeftAccshow</td>
							";
							$numall++;

							//หาผลรวมของจำนวนเงินแต่ละประเภทสัญญา
							$sum_conLoanAmt += $conLoanAmt; //รวมยอดสินเชื่อเริ่มแรก
							$listgetPrincipleOfGenCloseMonthsum += $getPrincipleOfGenCloseMonth; //รวมเงินต้นคงเหลือ
							$listgetInterestOfGenCloseMonthsum += $getInterestOfGenCloseMonth;  //ดอกเบี้ยตั้งพัก (ที่รับรู้และยังไม่ได้รับรู้)
							$listgetInterestOfGenCloseMonth_accsum += $getInterestOfGenCloseMonth_acc;  //ดอกเบี้ยตั้งพัก (ที่รับรู้บัญชี)
							$listgetAccruedInterestsum += $getAccruedInterest; // ดอกเบี้ยรับรู้รายได้ 3 เดือน
							$listgetAccruedAccInterestsum += $getAccruedInterest; // ดอกเบี้ยรับรู้รายได้ 3 เดือน
							$listsumin_prinsum += $sumin_prin1;	 //รวมคงเหลือ
							$listgetInterestALL += $getInterestALL_as_lastreceivedate;	 //ดอกเบี้ยคงเหลือทั้งสัญญา
							$listgetInterestLeftAcc += $getInterestLeftAcc; // ดอกเบี้ยตั้งพักคงเหลือทั้งสัญญา
							$listallrows += 1;

							//หาผลรวมของจำนวนเงินทั้งหมด
							$sum_conLoanAmt_all += $conLoanAmt;//รวมยอดสินเชื่อเริ่มแรกทั้งหมด
							$getPrincipleOfGenCloseMonthsum += $getPrincipleOfGenCloseMonth; // รวมเงินต้นคงเหลือ
							$getInterestOfGenCloseMonthsum += $getInterestOfGenCloseMonth;  // รวมดอกเบี้ยตั้งพัก (ที่รับรู้และยังไม่ได้รับรู้)
							$getInterestOfGenCloseMonth_accsum += $getInterestOfGenCloseMonth_acc;  //ดอกเบี้ยตั้งพัก (ที่รับรู้บัญชี)
							$getAccruedInterestsum += $getAccruedInterest; // รวมดอกเบี้ยรับรู้รายได้ 3 เดือน
							$getInterestLeftAccsum += $getInterestLeftAcc; // ดอกเบี้ยตั้งพักคงเหลือทั้งสัญญา
							$sumin_prinsum += $sumin_prin1;	 //รวมคงเหลือ
							$sumgetInterestALL += $getInterestALL_as_lastreceivedate;	 //ดอกเบี้ยคงเหลือทั้งสัญญา
							$allrows += 1; //จำนวนข้อมูลทั้งหมด 
						//}

						// ============================================================================================
						//เคลียร์ค่าตัวแปรทั้งหมด เพื่อป้องกันการนำมาใช้ซ้ำกันของข้อมูล	
						// ============================================================================================
						unset($sumin_prin);
						unset($thcap_backDueNumDays);
						unset($getInterestOfGenCloseMonthshow);
						unset($getPrincipleOfGenCloseMonthshow);
						unset($getInterestOfGenCloseMonth);
						unset($getInterestOfGenCloseMonth_acc);
						unset($getPrincipleOfGenCloseMonth);
						unset($sumin_prin1);
						unset($getInterestALLacc);
						unset($conLoanAmt);		
						unset($getInterestALLacc_as_lastreceivedate);
						unset($getInterestALLacc_as_focusdate);
						unset($getInterestALL_as_lastreceivedate);
						unset($getInterestLeftAcc);
						unset($getInterestLeftAccshow);
						$i++;
					} //end while แสดงเลขที่สัญญาที่เกี่ยวกับ type ที่เลือก

					if($numall > 0){
						// ============================================================================================
						//แสดงผลรวมของแต่ละประเภทสัญญา
						// ============================================================================================
						echo "
							<tr bgcolor=\"#FFC1C1\">
								<td>$listallrows รายการ</td>
								<td colspan=\"1\" align=\"right\"><b>รวม  $contypechk[$con]</b></td>
								<td align=\"right\">".number_format($sum_conLoanAmt,2)."</td>
								<td align=\"right\">".number_format($listgetPrincipleOfGenCloseMonthsum,2)."</td>
								<td align=\"right\">".number_format($listgetInterestOfGenCloseMonthsum,2)."</td>
								<td align=\"right\">".number_format($listgetInterestOfGenCloseMonth_accsum,2)."</td>
								<td align=\"right\">".number_format($listgetAccruedInterestsum,2)."</td>
								<td align=\"right\">".number_format($listsumin_prinsum,2)."</td>
								<td></td>
								<td align=\"right\">".number_format($listgetInterestALL,2)."</td>
								<td align=\"right\">".number_format($listgetInterestLeftAcc,2)."</td>
							</tr>
						";
					}else{
						echo "<tr bgcolor=\"#F8F8FF\"><td colspan=\"11\" align=\"center\">--- ไม่มีข้อมูล ---</td></tr>";
					}								

					// ============================================================================================
					//เคลียร์ค่าตัวแปรทั้งหมด เพื่อป้องกันการนำมาใช้ซ้ำกันของข้อมูล
					// ============================================================================================
					unset($sum_conLoanAmt);
					unset($listgetPrincipleOfGenCloseMonthsum);
					unset($listgetInterestOfGenCloseMonthsum);
					unset($listgetInterestOfGenCloseMonth_accsum);
					unset($listgetAccruedInterestsum);
					unset($listsumin_prinsum);		 
					unset($listgetInterestALL);	
					unset($listgetInterestLeftAcc);
				}else{ //end if กรณีที่พบข้อมูลจาก type ที่เลือก
				//หากจำนวนข้อมูลที่พบน้อยกว่าศูนย์
					echo "<tr bgcolor=\"#F8F8FF\"><td colspan=\"11\" align=\"center\">--- ไม่มีข้อมูล ---</td></tr>";
				}
			} //end for กรณีวนแสดง type ที่เลือก		
			?>
			<!-- แสดงผลรวมทั้งหมด -->
			<tr bgcolor="#33CC66">
				<td>รวม <?php echo $allrows; ?> รายการ</td>
				<td colspan="1" align="right"><b>รวมทั้งหมด </b></td>
				<td align="right"><?php echo number_format($sum_conLoanAmt_all,2); ?></td>
				<td align="right"><?php echo number_format($getPrincipleOfGenCloseMonthsum,2); ?></td>
				<td align="right"><?php echo number_format($getInterestOfGenCloseMonthsum,2); ?></td>
				<td align="right"><?php echo number_format($getInterestOfGenCloseMonth_accsum,2); ?></td>
				<td align="right"><?php echo number_format($getAccruedInterestsum,2); ?></td>
				<td align="right"><?php echo number_format($sumin_prinsum,2); ?></td>
				<td></td>
				<td align="right"><?php echo number_format($sumgetInterestALL,2); ?></td>
				<td align="right"><?php echo number_format($getInterestLeftAccsum,2); ?></td>
				
			</tr>	  
		</table>
	</td>	
</tr>
<?php } ?>
</table>
</form>	
</body>
</html>