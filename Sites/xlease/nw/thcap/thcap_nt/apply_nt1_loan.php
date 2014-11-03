<?php
include("../../../config/config.php");
$contractID = pg_escape_string($_GET["contractID"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) Create NT</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
    
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#proctor").autocomplete({
        source: "s_user.php",
        minLength:2
    });
	
	$("#startdate").datepicker({
        showOn: 'button',
        buttonImage: '../images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
			
    });
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function checkvalue(){
	if(document.frm1.guaranID.value==""){
		alert('กรุณาเลือกประเภทสินทรัพย์ที่จำนอง');
		return false;
	} 
	if(document.frm1.startdate.value==""){
		alert('กรุณาระบุวันที่ทำสัญญาจำนอง');
		document.frm1.startdate.focus();
		return false;
	}
	if(document.frm1.proctor.value==""){
		alert('กรุณาระบุทนายความผู้รับมอบอำนาจ');
		document.frm1.proctor.focus();
		return false;
	}
	
	if(document.frm1.proctor.value!=""){
		if($('#proctorid').val()=='yes'){
			return true;
		}else{
			alert('กรุณาระบุทนายความผู้รับมอบอำนาจให้ถูกต้องตามที่ระบบกำหนด');
			document.frm1.proctor.select();
			return false;
		}
	}
	return true;
//process_nt1_loan.php
}
function checkproctorid(){
	var arr =$('#proctor').val();
    var id=arr.split("-"); //รหัสพนักงาน

	$('#chkpeople').load('check.php?chk=proctor&id='+id[0]);
}	
</script>   
</head>
<body>
<div align="center"><h2>(THCAP) Create NT</h2></div>
<form method="post" name="frm1" action="process_nt1_loan.php"> 
<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
			<div style="clear:both;"></div>
			<div style="margin-top:0px;"><?php include('../../thcap/Data_contract_detail.php'); //ข้อมูล สัญญา ?></div>
			
			<!--ข้อมูลเพิ่มเติม-->
			<div style="padding-top:10px;">
			<fieldset><legend><b>ข้อมูลเพิ่มเติม</b></legend>
				<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr height="25">
					<td width="160">ประเภทสินทรัพย์ที่จำนอง </td>
					<td>
						<select name="guaranID">
							<option value="">--------เลือก--------</option>
							<option value="ที่ดิน">ที่ดิน</option>
							<option value="ที่ดินพร้อมสิ่งปลูกสร้าง">ที่ดินพร้อมสิ่งปลูกสร้าง</option>
							<option value="รถจักรยานยนต์">รถจักรยานยนต์</option>
							<option value="ห้องชุด">ห้องชุด</option>
						</select><font color="red"><b>*</b></font>
					</td>
				</tr>
				<tr height="25">
					<td>วันที่ทำสัญญาจำนอง  </td>
					<td>
						<input type="text" name="startdate" id="startdate" value="" size="10" readonly><font color="red"><b>*</b></font>
					</td>
				</tr>
				<tr height="25">
					<td>ทนายความผู้รับมอบอำนาจ  </td>
					<td>
						<div id="chkpeople"></div>
						<input type="text" name="proctor" id="proctor"size="40" onfocus="checkproctorid();" onblur="checkproctorid();" onkeypress="checkproctorid();"><font color="red"><b>*</b></font>
					</td>
				</tr>
				<tr height="25">
					<td>จำนวนวันที่ชำระภายใน  </td>
					<td>
						<input type="text" name="withInDay" id="withInDay" size="3" value="30"> วัน <font color="red"><b>*</b></font>
					</td>
				</tr>
				</table>
			</fieldset>
			</div>
			
			<!--หนี้ที่ต้องการเรียบเก็บ-->
			<div style="padding-top:10px;">
			<fieldset><legend><b>หนี้ที่ต้องการเรียบเก็บ</b></legend>
				<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr height="25">
					<td width="160">ค่างวด </td>
					<td>
					<?php
					//หาว่าเป็นสัญญาประเภทใด
					$qrytype=pg_query("select \"thcap_get_creditType\"('$contractID')");
					list($contype)=pg_fetch_array($qrytype);
					
					if($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN')
					{
						//หางวดเริ่มต้นและสิ้นสุดค้าง
						$qrystartend=pg_query("SELECT min(\"ptNum\"),max(\"ptNum\")
						FROM account.\"thcap_loan_payTerm_left\" 
						WHERE \"contractID\"='$contractID' AND \"ptDate\"<=current_date and \"ptMinPayLeft\">0");
						list($startnum,$endnum)=pg_fetch_array($qrystartend);
						echo "<input type=\"hidden\" name=\"startnum\" id=\"startnum\" value=\"$startnum\" >";
						echo "<input type=\"hidden\" name=\"endnum\" id=\"endnum\" value=\"$endnum\" >";

						//หาค่างวดที่ต้องเรียกเก็บ
						$qrypayment=pg_query("SELECT \"contractID\", sum(\"ptMinPayLeft\") as payleft
						FROM account.\"thcap_loan_payTerm_left\"
						WHERE \"ptDate\"<=current_date and \"contractID\"='$contractID' 
						GROUP BY \"contractID\"");
						if($respayment=pg_fetch_array($qrypayment)){
							$payleft=$respayment["payleft"];
						}
						echo "<input type=\"hidden\" name=\"payleft\" id=\"payleft\" value=\"$payleft\" >";
						echo "<input type=\"text\" value=".number_format($payleft,2)." readonly> (งวดที่ $startnum - งวดที่ $endnum)";
					}
					else
					{
						//หางวดเริ่มต้นและสิ้นสุดค้าง
						$qrystartend=pg_query("SELECT min(\"typePayRefValue\"::integer), max(\"typePayRefValue\"::integer)
						FROM \"thcap_temp_otherpay_debt\" 
						WHERE \"contractID\"='$contractID' AND \"debtIsOther\" = '0' AND \"debtDueDate\" <= current_date AND \"typePayLeft\" > 0 ");
						list($startnum,$endnum)=pg_fetch_array($qrystartend);
						echo "<input type=\"hidden\" name=\"startnum\" id=\"startnum\" value=\"$startnum\" >";
						echo "<input type=\"hidden\" name=\"endnum\" id=\"endnum\" value=\"$endnum\" >";

						//หาค่างวดที่ต้องเรียกเก็บ
						$qrypayment=pg_query("SELECT \"contractID\", sum(\"typePayLeft\") as payleft
						FROM \"thcap_temp_otherpay_debt\"
						WHERE \"contractID\"='$contractID' AND \"debtIsOther\" = '0' AND \"debtDueDate\" <= current_date AND \"typePayLeft\" > 0
						GROUP BY \"contractID\"");
						if($respayment=pg_fetch_array($qrypayment)){
							$payleft=$respayment["payleft"];
						}
						echo "<input type=\"hidden\" name=\"payleft\" id=\"payleft\" value=\"$payleft\" >";
						echo "<input type=\"text\" value=".number_format($payleft,2)." readonly> (งวดที่ $startnum - งวดที่ $endnum)";
					}
					?>
					</td>
				</tr>
				<tr height="25">
					<td>ค่าติดตามทวงถาม   </td>
					<td>
					<?php	
						//หารหัสประเภทค่าติดตามของสัญญานี้
						$qrytypepay=pg_query("SELECT substring(account.\"thcap_mg_getMinPayType\"('$contractID'),1,1)||'003'");
						list($typePayID)=pg_fetch_array($qrytypepay);
						
						//หาค่าติดตามที่ค้างชำระ
						$qry_follow = pg_query("select sum(\"typePayLeft\") from public.\"thcap_v_otherpay_debt_realother_current\" where \"contractID\"='$contractID' 
						and \"debtStatus\"='1' and \"typePayID\"='$typePayID' ");
						list($paytag)=pg_fetch_array($qry_follow);

						echo "<input type=\"hidden\" name=\"paytag\" id=\"paytag\" value=\"$paytag\" >";
						echo "<input type=\"text\" value=".number_format($paytag,2)." readonly>";
					?>
					</td>
				</tr>
				<tr height="25">
					<td>ค่าหนังสือเตือนโดยทนาย   </td>
					<td>
					<?php
						//$proctor_nt=1500;
						
						//หาจำนวนเงิน ค่าหนังสือเตือน ที่จะตั้งหนี้ใหม่ครั้งนี้
						$qrydebt_new = pg_query("SELECT thcap_get_config('nt1_rate',\"thcap_get_contractType\"('$contractID'))");
						list($proctor_new) = pg_fetch_array($qrydebt_new);
						
						// หา ค่าหนังสือเตือนโดยทนาย เดิมที่ค้างอยู่
						$qrydebt_old = pg_query("SELECT SUM(\"typePayLeft\")
											FROM \"thcap_v_otherpay_debt_realother_current\"
											WHERE \"contractID\" = '$contractID' AND \"typePayID\" like '%004' AND \"debtStatus\" = '1' ");
						list($proctor_old) = pg_fetch_array($qrydebt_old);
						
						$proctor_nt = $proctor_new + $proctor_old;
						
						echo "<input type=\"hidden\" name=\"proctor_nt\" id=\"proctor_nt\" value=\"$proctor_nt\">";
						echo "<input type=\"text\" value=".number_format($proctor_nt,2)." readonly>";
					?>
					</td>
				</tr>
				<?php
					//----- หาหนี้อื่นๆ
					
					// function ที่ใช้หาเบี้ยปรับ
					if($credit_type=="HIRE_PURCHASE" || $credit_type=="LEASING" || $credit_type=="GUARANTEED_INVESTMENT" || $credit_type=="FACTORING")
					{
						$function_get_fine = "thcap_get_lease_fine";
					}
					else
					{
						$function_get_fine = "thcap_get_loan_fine";
					}
					
					$qry_other = pg_query("SELECT
											\"typePayID\",
											\"tpDesc\",
											SUM(\"typePayLeft\") AS \"typePayLeft\"
										FROM
											(SELECT
												a.\"typePayID\",
												b.\"tpDesc\",
												a.\"typePayLeft\"
											FROM
												\"thcap_v_otherpay_debt_realother_current\" a,
												account.\"thcap_typePay\" b
											WHERE
												a.\"typePayID\" = b.\"tpID\" AND
												a.\"contractID\" = '$contractID' AND
												a.\"typePayID\" not like '%003' AND -- ไม่เอาค่าติดตามทวงถาม
												a.\"typePayID\" not like '%004' AND -- ไม่เอาค่าหนังสือเตือนโดยทนาย
												(a.\"debtDueDate\" IS NULL OR a.\"debtDueDate\" < current_date) AND
												\"debtStatus\" = '1'

											UNION

											-- ค่าติดตามทวงถาม ล่วงหน้า 45 วัน
											SELECT
												\"tpID\" AS \"typePayID\",
												\"tpDesc\",
												\"$function_get_fine\"('$contractID', current_date + 45) AS \"typePayLeft\"
											FROM
												account.\"thcap_typePay\"
											WHERE
												\"tpConType\" = \"thcap_get_contractType\"('$contractID') AND
												\"tpID\" LIKE '%500'
											) AS \"tabletemp\"
										GROUP BY 1, 2
										HAVING SUM(\"typePayLeft\") > 0.00
										ORDER BY 2 ");
					$other = 0; // จำนวนหนี้อื่นๆ
					while($res_other = pg_fetch_array($qry_other))
					{
						$other++;
						
						$typePayID = $res_other["typePayID"]; // รหัสค่าใช้จ่าย
						$tpDesc = $res_other["tpDesc"]; // ชื่อค่าใช้จ่าย
						$typePayLeft = $res_other["typePayLeft"]; // จำนวนเงินคงเหลือ
						
						if($other == 1)
						{
							$otherArray = "{".$typePayID.",".$typePayLeft."}";
						}
						else
						{
							$otherArray .= ",{".$typePayID.",".$typePayLeft."}";
						}
						
						echo "<tr height=\"25\">";
						echo "<td>$tpDesc   </td>";
						echo "<td><input type=\"textbox\" value=".number_format($typePayLeft,2)." readOnly></td>";
						echo "</tr>";
					}
					
					// ถ้ามีหนี้อื่นๆ
					if($other > 0)
					{
						echo "<input type=\"hidden\" name=\"otherPayDebt\" value=\"$otherArray\" />";
					}
				?>
				</table>
			</fieldset>
			</div>
			
			<!--หนี้ในอนาคต-->
			<div style="padding-top:10px;">
			<fieldset><legend><b>หนี้ในอนาคต</b></legend>
				<table width="500" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr height="25">
					<td width="160">ค่างวด </td>
					<td>
					<?php
					$querypaymentnex=pg_query("SELECT \"ptNum\", \"ptDate\", \"ptMinPayLeft\"
					FROM account.\"thcap_loan_payTerm_left\" 
					WHERE \"contractID\"='$contractID' AND \"ptDate\">current_date order by \"ptNum\" limit 1;");
					list($ptNum,$ptDate,$ptMinPay)=pg_fetch_array($querypaymentnex);
					
					echo "<input type=\"hidden\" name=\"duenext\" value=\"$ptNum\">"; //งวดถัดไป
					echo "<input type=\"hidden\" name=\"paynext\" value=\"$ptMinPay\">"; //ค่างวดถัดไป
					echo "งวดที่ $ptNum  จำนวนเงิน ".number_format($ptMinPay,2)." บาท ครบกำหนดวันที่ $ptDate";
					?>
					</td>
				</tr>
				<tr height="25">
					<td>ค่าติดตามทวงถาม   </td>
					<td>
					<?php
					$qrypaytagnext=pg_query("SELECT max(\"conCurPenalty\")
					FROM thcap_mg_contract_current 
					WHERE \"effectiveDate\"<=current_date AND \"contractID\"='$contractID'");
					list($paytagnext)=pg_fetch_array($qrypaytagnext);
					
					echo "<input type=\"hidden\" name=\"paytagnext\" id=\"paytagnext\" value=\"$paytagnext\" >";
					echo "<input type=\"text\" value=".number_format($paytagnext,2)." readonly>";
					?>
					</td>
				</tr>
				</table>
			</fieldset>
			</div>
			<div style="padding-top:5px;"><b>บัญชีธนาคาร : </b>
			<?php
			include("default_nt1_transaccount.php");
			?> 
			</div>
			<div style="padding-top:5px;"><b>รายละเอียดการติดต่อ : </b>
			<?php
			$detailcontact="โทร 0-2744-2325 วันจันทร์ - เสาร์ เวลา 08.30-17.00 น.";
			echo "<input type=\"text\" name=\"detailcontact\" id=\"detailcontact\" value=\"$detailcontact\" size=\"100\" readonly>";
			?> 
			</div>
			
			<div><b>หมายเหตุ : </b></div>
			<div><textarea name="result" id="result" cols="40" rows="4"></textarea></div>
			<div style="padding:15px;text-align:center;">
			<input type="hidden" name="method" value="add">
			<input type="hidden" name="contractID" value="<?php echo $contractID;?>">
			<hr><input type="submit" value="บันทึก" onclick="return checkvalue();"><input type="reset" value="ยกเลิก"></div>
		</td>
	</tr>
</table>
</form>
</html>