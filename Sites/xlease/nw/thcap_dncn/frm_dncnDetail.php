<?php
include("../../config/config.php");
$contractID=$_GET["contractID"];

$currentDate = nowDate(); // วันที่ปัจจุบัน

if($contractID!=""){
	//ตรวจสอบว่าได้รออนุมัติอยู่หรือไม่
	$qry_waitapp = pg_query("SELECT * from account.thcap_dncn_payback where \"dcNoteStatus\" = '9' AND \"dcType\" = '2' and \"contractID\"='$contractID'");
	if(pg_num_rows($qry_waitapp)>0){
		echo "<div align=\"center\"><h2>--พบรายการนี้รออนุมัติอยู่ กรุณาตรวจสอบ--</h2></div>";
		exit;
	}
?>

<div style="margin-top:0px;"><?php include('../thcap/Data_contract_detail.php'); //ข้อมูล สัญญา ?></div>
<div style="padding:10px 0px"><?php include('../thcap/Data_other_debt.php'); //หนี้อื่นๆที่ค้างชำระ ?></div>

<form method="post" action="process_dncn.php">
<fieldset><legend><B>รายการเงินที่มีอยู่ในระบบ</B></legend>
<table width="100%" cellSpacing="1" cellPadding="1" bgcolor="#FFF8DC" align="center">
	<span style="color:red;">* กรณีคืนเงินจากเงินมัดจำ หรือเงินประกันใดๆ ปัจจุบันสามารถคืนได้เต็มจำนวนเท่ากับตอนที่รับเงินเท่านั้น ไม่สามารถคืนบางส่วนได้</span>
	<tr bgcolor="#8B7765" height="25" style="color:#FFFFFF;">
		<th>เลือกทำรายการ</th>
		<th>รหัสรายการ</th>
		<th>ชื่อรายการ</th>
		<th>จำนวนเงิน(บาท)</th>
		<th>จำนวนเงินที่ต้องการคืน(บาท)</th>
	</tr>
	<?php
	// ================================================================================================================
	// Initialized ตัวแปร
	// ================================================================================================================
	$numrows = 0;
	
	// ================================================================================================================
	// ข้อมูลที่จะใช้ในการคืนเงินพัก และเงินค้ำประกัน(ไม่มีภาษีมูลค่าเพิ่มเสมอ)
	// ================================================================================================================
	$qry=pg_query("	SELECT 
						\"moneyType\", 
						\"BAccount\", 
						\"contractBalance\" 
					FROM 
						vthcap_contract_money 
					WHERE 
						\"contractID\"='$contractID'
	");
	$numrows += pg_num_rows($qry);
	$i=0;
	while($res=pg_fetch_array($qry)){
		$i++;
		list($typemoney,$name,$money2)=$res;
		$money=number_format($money2,2);
		
		if($i%2==0){
            echo "<tr bgcolor=\"#FFDAB9\" align=\"center\">";
        }else{
            echo "<tr bgcolor=\"#EECBAD\" align=\"center\">";
        }
		// การคืนเงินกรณีนี้ไม่มี vat จึง fix ดังนี้
		// ค่า typemoney ที่จะส่งไป $typemoney (ตามที่ผู้ใช้เลือกว่าจะคืนเงินพัก หรือเงินค้ำประกัน) / ค่า vat ที่จะส่งไป = 0 /  ค่า type ที่จะส่งไป = 1
		echo "
			<td><input type=radio name=typemoney value=$typemoney id=\"typemoney$i\" onclick=\"processclick('$i')\"></td>
			<td>$typemoney</td>
			<td align=left>$name</td>
			<td>$money<input type=\"hidden\" id=\"mon$i\" value=\"$money2\"></td>
			<input type=\"hidden\" name=vat[] id=\"vat$i\" value=\"0\" disabled=\"true\">
			<input type=\"hidden\" name=type[] id=\"type$i\" value=\"1\" disabled=\"true\">
			<input type=\"hidden\" name=debtid[] id=\"debtid$i\" value=\"\" disabled=\"true\">
			<td><input type=text name=amt[] id=\"amt$i\" style=\"text-align:right;\" onKeyPress=\"checknumber(event);\" disabled=\"true\"></td>
		";
	}
	
	// ================================================================================================================
	// ข้อมูลที่จะใช้ในการคืนเงินประกันสัญญา เงินมัดจำ หรือที่มีลักษณะทำนองเดียวกัน (มีภาษีมูลค่าเพิ่ม หรืออาจไม่มีภาษีมูลค่าเพิ่ม)
	// ================================================================================================================
	/* 
		**todo 1. หาทางป้องกันในอนาคตที่อาจจะเกิดขึ้นได้ในกรณีที่ใบเสร็จที่ีรับชำระเงินที่จะคืนตอนแรกไม่ถูกต้อง
			1.1. มีการรับชำระเงินประกัน / มัดจำ 
			1.2. ต่อมาทำเรื่องคืนเงิน และอนุมัติการคืน 
			1.3. ต่อมาพบว่ารายการรับชำระเงินประกัน / มัดจำ ตาม 1. ไม่ถูกต้อง
			1.4. ยกเลิก และอนุมัติ 1.
			1.5. ปรากฎว่ารายการที่คืนเงิน 2. ไม่ถูกต้อง 
			
		** todo 2. ยังไม่สามารถคืนเงินบางส่วนได้จาก Query และ Code ในส่วนนี้ แต่ได้ออกแบบฐานข้อมูลไว้ให้รองรับ
		** todo 3. กรณีที่หนี้ debtID ดังกล่าวมีรายการทั้งลดหนี้ และคืนเงิน อาจทะให้มีปัญหา ควรจะกำหนดเลยว่า  "account"."thcap_typePay"."isForSecurity" = 1 ห้ามลดหนี้
	*/
	$qry=pg_query("	SELECT
						a.\"typePayID\",
						a.\"tpDesc\" || ' ' || a.\"tpFullDesc\" || ' ' || a.\"typePayRefValue\",
						a.\"debtAmt\",
						a.\"netAmt\",
						a.\"vatAmt\",
						a.\"debtID\"
					FROM 
						\"public\".\"thcap_temp_receipt_otherpay\" a
					LEFT JOIN
						\"account\".\"thcap_typePay\" b ON a.\"typePayID\" = b.\"tpID\"
					WHERE 
						b.\"isForSecurity\" = '1' AND -- เป็นเงินประกัน / มัดจำ (สามารถคืนได้ หรือไม่คืนก็ได้)
						\"thcap_receiptIDToContractID\"(a.\"receiptID\") = '$contractID' -- ต้องเป็นเลขที่สัญญาที่สนใจ
	");
	$numrows += pg_num_rows($qry);
	while($res=pg_fetch_array($qry)){
		// เก็บข้อมูลจากที่ query ได้เข้าตัวแปร
		list($typemoney,$name,$money2,$net,$vat,$debtid) = $res;

		// ตรวจสอบว่ารายการดังกล่าว เคยคืนไปแล้วและอนุมัติเรียบร้อย หรือไม่
		$qry_amtdeduct = pg_query("	
			SELECT
				SUM(\"dcNoteAmtNET\")
			FROM
				\"account\".\"thcap_dncn\"
			WHERE
				\"debtID\" = '$debtid' AND
				\"dcNoteStatus\" = '1' -- นำเฉพาะรายการที่อนุมัติและมีผลแล้วมาคำนวณ
		");
		list($amtdeduct)=pg_fetch_array($qry_amtdeduct);

		// กรณีที่คืนครบแล้ว
		if($amtdeduct == $net){
			// กรณีที่คืนครบแล้ว ไม่ต้องดำเนินการใดๆ
		}
		// กรณีที่คืนเงินเกินกว่าที่มี (เป็นไปไม่ได้)
		elseif($amtdeduct > $net){
			echo "ตรวจสอบพบมีเงื่อนไขไม่ถูกต้อง เนื่องจากมีการคืนเงิน หรือให้ส่วนลด มากกว่าเงินที่เป็นหนี้ที่ต้องจ่าย ของเลขที่สัญญา $contractID";
		}
		// กรณีที่ยังไม่คิน หรือคืนไม่ครบ ($amtdeduct < $net) โดยจำนวนเงินที่เหลือคืนได้คือ $net - $amtdeduct
		else {
			$i++; // ใช้ $i เดิม เนื่องจากมีผลกับตัวแปรที่จะส่งไปหน้า process
			$money = $net - $amtdeduct;
			$money = number_format($money2,2);
			
			if($i%2==0){
				echo "<tr bgcolor=\"#FFDAB9\" align=\"center\">";
			}else{
				echo "<tr bgcolor=\"#EECBAD\" align=\"center\">";
			}
			// todo* การคืนเงินกรณีนี้อาจมี vat จึง fix ดังนี้ (ยังไม่รองรับในกรณีที่ VAT มีค่าเปลี่ยนแปลงไปจากวันที่รับชำระตอนแรก)
			// ค่า typemoney ที่จะส่งไป '' (ค่าว่าง) / ค่า vat ที่จะส่งไป = ตาม vat ที่พบ /  ค่า type ที่จะส่งไป = 2
			echo "
				<td><input type=radio name=typemoney value=\"\" id=\"typemoney$i\" onclick=\"processclick('$i')\"></td>
				<td>$typemoney</td>
				<td align=left>$name</td>
				<td>$money<input type=\"hidden\" id=\"mon$i\" value=\"$money2\"></td>
				<input type=\"hidden\" name=vat[] id=\"vat$i\" value=\"$vat\" disabled=\"true\">
				<input type=\"hidden\" name=type[] id=\"type$i\" value=\"2\" disabled=\"true\">
				<input type=\"hidden\" name=debtid[] id=\"debtid$i\" value=\"$debtid\" disabled=\"true\">
				<td><input type=text name=amt[] id=\"amt$i\" style=\"text-align:right;\" onKeyPress=\"checknumber(event);\" disabled=\"true\"></td>
			";
		}
	}
	
	// ================================================================================================================
	// ปิดตารางสำหรับให้เลือกสิ่งที่จะคืน
	// ================================================================================================================
	echo "
		</tr>
	";
	
	// ================================================================================================================
	// กรณีไม่มีรายการเพื่อที่จะให้เลือกคืน
	// ================================================================================================================
	if($numrows==0){
		echo "<tr align=center><td colspan=5><h2>--ไม่พบข้อมูล--</h2></td></tr>";
	}
	?>
</table>
<?php
if($numrows>0){
?>
<div style="padding:20px 0px 10px"><b>วันที่รายการออกมีผล : </b><input type="text" name="dcNoteDate" id="dcNoteDate" onChange="checkDateSelect()" readonly="true" size="15"><span style="color:red;font-weight:bold;">*</span></div>
<div><b>ช่องทางการจ่ายคืน : </b>
<select name="byChannel" id="byChannel" onchange="javascript:checkTranPay()">
	<option value="">--เลือก--</option>
	<?php
	$qry=pg_query("select \"BID\",\"BAccount\"||'-'||\"BName\" from \"BankInt\" where \"isReturnChannel\"='1' order by \"BID\"");
	while($res=pg_fetch_array($qry)){
		list($bid,$bankname)=$res;
		echo "<option value=$bid>$bankname</option>";
	}
	?>
</select><span style="color:red;"><b>*</b></span>
<input type="hidden" name="tranpay" id="tranpay">
</div>
<div id="show1" style="background-color:#FFCCCC;padding:5px;width:450px;border:1px dashed #FF6A6A">
	<div><b>ระบุข้อมูลเพิ่มเติม</b></div>
	<div><input type="radio" name="proviso_return" id="proviso1" value="1" checked>คืนโดยโอนธนาคาร</div>
	<div id="show3" style="background-color:#FFFFE0;padding:5px;width:430px;font-weight:bold;border:1px dashed #FF6A6A">
	<div>
		เจ้าของบัญชี : 
		<!--input type="text" name="returnTranToCus" id="returnTranToCus" size="55" onfocus="check_customer();" onblur="check_customer();" onkeypress="check_customer();"><span style="color:red;">*</span-->
		<select name="returnTranToCus" id="returnTranToCus" onChange="check_customer();">
			<option value="">--เลือกเจ้าของบัญชี--</option>
			<?php
			$qry_contractCus = pg_query("select * from \"thcap_ContactCus\" where \"contractID\" = '$contractID' order by \"CusState\", \"FullName\" ");
			while($res_contractCus = pg_fetch_array($qry_contractCus))
			{
				$CusID = $res_contractCus["CusID"]; // รหัสลูกค้า
				$FullName = $res_contractCus["FullName"]; // ชื่อเต็มลูกค้า
				$CusState = $res_contractCus["CusState"]; // ประเภทลูกค้าของสัญญานั้นๆ (ผู้กู้-ผู้เช่าซื้อ / ผู้กู้ร่วม-ผู้เช่าซื้อร่วม / ผู้ค้ำประกัน)
				
				if($CusState == "0")
				{
					$CusStateText = "(ผู้กู้-ผู้เช่าซื้อ)";
				}
				elseif($CusState == "1")
				{
					$CusStateText = "(ผู้กู้ร่วม-ผู้เช่าซื้อร่วม)";
				}
				elseif($CusState == "2")
				{
					$CusStateText = "(ผู้ค้ำประกัน)";
				}
				else
				{
					$CusStateText = "";
				}
				
				echo "<option value=\"$CusID#$FullName\">$CusID#$FullName $CusStateText</option>";
			}
			?>
		</select><span style="color:red;">*</span>
	</div>
	<div id="chkcustomer"></div>
	<div>
		รหัสธนาคาร : 
		<!--input type="text" name="returnTranToBank" id="returnTranToBank" size="50" onfocus="check_bank();" onblur="check_bank();" onkeypress="check_bank();"><span style="color:red;">*</span-->
		<select name="returnTranToBank" id="returnTranToBank" onChange="check_bank();">
			<option value="">--เลือกรหัสธนาคาร--</option>
			<?php
			$qry_BankProfile = pg_query("select * from \"BankProfile\" order by \"sort\",\"bankName\" ");
			while($res_BankProfile = pg_fetch_array($qry_BankProfile))
			{
				$bankID = $res_BankProfile["bankID"]; // รหัสธนาคาร
				$bankName = trim($res_BankProfile["bankName"]); // ชื่อธนาคาร
				
				echo "<option value=\"$bankID#$bankName\">$bankID#$bankName</option>";
			}
			?>
		</select><span style="color:red;">*</span>
	</div>
	<div id="chkbank"></div>
	<div>เลขที่บัญชีปลายทาง : <input type="text" name="returnTranToAccNo" id="returnTranToAccNo"><span style="color:red;">*</span></div>
	</div>
	<div><input type="radio" name="proviso_return" id="proviso2" value="2">คืนโดยเช็ค</div>
	<div id="show2" style="background-color:#FFFFE0;padding:5px;width:430px;font-weight:bold;border:1px dashed #FF6A6A">
	<div>
		ออกเช็คให้: 		
		<select name="returnChqCus" id="returnChqCus" onChange="check_customer_ChqCus();">
			<option value="">--เลือก--</option>
			<?php
			$qry_contractCus = pg_query("select * from \"thcap_ContactCus\" where \"contractID\" = '$contractID' order by \"CusState\", \"FullName\" ");
			while($res_contractCus = pg_fetch_array($qry_contractCus))
			{
				$CusID = $res_contractCus["CusID"]; // รหัสลูกค้า
				$FullName = $res_contractCus["FullName"]; // ชื่อเต็มลูกค้า
				$CusState = $res_contractCus["CusState"]; // ประเภทลูกค้าของสัญญานั้นๆ (ผู้กู้-ผู้เช่าซื้อ / ผู้กู้ร่วม-ผู้เช่าซื้อร่วม / ผู้ค้ำประกัน)
				
				if($CusState == "0")
				{
					$CusStateText = "(ผู้กู้-ผู้เช่าซื้อ)";
				}
				elseif($CusState == "1")
				{
					$CusStateText = "(ผู้กู้ร่วม-ผู้เช่าซื้อร่วม)";
				}
				elseif($CusState == "2")
				{
					$CusStateText = "(ผู้ค้ำประกัน)";
				}
				else
				{
					$CusStateText = "";
				}
				
				echo "<option value=\"$CusID#$FullName\">$CusID#$FullName $CusStateText</option>";
			}
			?>
		</select><span style="color:red;">*</span>
	</div>
	<div id="chkcustomer_ChqCus"></div>
	<div>เลขที่เช็ค : <input type="text" name="returnChqNo" id="returnChqNo"> วันที่บนเช็ค : <input type="text" name="returnChqDate" id="returnChqDate" size="10"><font color="red"><b>*</b></font></div>
	</div>
</div>
<div><b>::เหตุผล::</b><span style="color:red;font-weight:bold;">*</span></div>
<div><textarea name="dcNoteDescription" id="dcNoteDescription" cols="50" rows="5"></textarea></div>
<div>
	<input type="hidden" id="num" value="<?php echo $numrows;?>">
	<input type="hidden" name="contractID" value="<?php echo $contractID;?>">
	<input type="hidden" name="method" value="add">
	<input type="submit" id="submitbutton" value="บันทึก" onclick="return checkDataSubmit()">
	<input type="reset" value="ยกเลิก">
</div>
<?php
}
?>
</fieldset>
</form>
<?php
}else{
	exit;
}
?>
<script>
$(document).ready(function(){
	$("#show1").hide(); //ซ่อนส่วนที่ต้องเลือกให้แสดงหลังจากเลือก ช่องทางการจ่าย  แล้ว isTranPay=1
	$("#show2").hide(); //ซ่อนส่วนที่ต้องกรอกเพิ่มให้แสดงหลังจากเลือก ช่องทางการจ่าย  แล้ว isTranPay=1 และเลือกคืนเช็ค
	$("#dcNoteDate").datepicker({
		showOn: 'button',
		buttonImage: './images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});

	$("#returnChqDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#proviso1").click(function(){
		$("#show2").hide(); 
		$("#show3").show();
		$('#returnTranToCus').focus();
	});
	
	$("#proviso2").click(function(){
		$("#show2").show(); 
		$("#show3").hide(); 
		$('#returnChqNo').focus();
	});
	
	//ค้นหารหัสและชื่อลูกค้า
	/*$("#returnTranToCus").autocomplete({
		source: "s_customer.php",
        minLength:1
    });*/
	
	//ค้นหารหัสธนาคาร
	/*$("#returnTranToBank").autocomplete({
		source: "s_bank.php",
        minLength:1
    });*/
});

var num=document.getElementById("num").value;
var currentDate = '<?php echo $currentDate; ?>';

function processclick(a){
	for(i=1;i<=num;i++){
		if(document.getElementById("typemoney"+i).checked){	
			document.getElementById("amt"+i).disabled=false;
			document.getElementById("vat"+i).disabled=false;
			document.getElementById("type"+i).disabled=false;
			document.getElementById("debtid"+i).disabled=false;
			document.getElementById("amt"+i).focus();
		}else{
			document.getElementById("amt"+i).disabled=true;	
			document.getElementById("vat"+i).disabled=true;
			document.getElementById("type"+i).disabled=true;
			document.getElementById("debtid"+i).disabled=true;
			document.getElementById("amt"+i).value='';			
		}
	}
}

$("#submitbutton").click(function(){	
	var stsclick;
	stsclick=0;
	for(i=1;i<=num;i++){
		if(document.getElementById("typemoney"+i).checked){	
			if(document.getElementById("amt"+i).value=="" || document.getElementById("amt"+i).value==0){
				alert("กรุณาระบุจำนวนเงิน");
				document.getElementById("amt"+i).select();
				return false;
			}else{
				if(parseFloat(document.getElementById("amt"+i).value) > parseFloat(document.getElementById("mon"+i).value)){
					alert("จำนวนเงินที่คืนจะต้องน้อยกว่าจำนวนเงินที่มี");
					document.getElementById("amt"+i).select();
					return false;
				}
			}
			
			stsclick=stsclick+1;		//บอกให้รู้ว่ามีการเลือกข้อมูล	
		}
	}
	if(stsclick==0){
		alert("กรุณาเลือกรายการที่ต้องการคืนเงิน");
		return false;
	}
	if(document.getElementById("dcNoteDate").value==""){
		alert("กรุณาระบุวันที่รายการออกมีผล");
		return false;
	}
	
	if(document.getElementById("byChannel").value==""){
		alert("กรุณาระบุช่องทางการจ่าย");
		return false;
	}
	
	//กรณี "isTranPay" = 1 ต้องตรวจสอบเพิ่มเติม
	if($('#tranpay').val()==1){
		//ถ้าเลือกคืนโดยเงินโอนจะต้องกรอกข้อมูลให้สมบูรณ์
		if($('input:radio[name=proviso_return]:checked').val() == 1){
			if($('#returnTranToCus').val()==""){
				alert("กรุณาระบุเจ้าของบัญชี");
				$('#returnTranToCus').focus();
				return false
			}else{
				//ถ้าไม่ว่างต้องตรวจสอบข้อมูลว่างทีระบุนั้นถูกต้องตามระบบกำหนดหรือไม่
				if($('#cusid').val()=='no'){
					alert('กรุณาระบุเจ้าของบัญชีให้ถูกต้องตามที่ระบบกำหนด');
					$('#returnTranToCus').select();
					return false;
				}
			
			}
			
			if($('#returnTranToBank').val()==""){
				alert("กรุณาระบุรหัสธนาคาร");
				$('#returnTranToBank').focus();
				return false
			}else{
				//ถ้าไม่ว่างต้องตรวจสอบข้อมูลว่างทีระบุนั้นถูกต้องตามระบบกำหนดหรือไม่
				if($('#bankid').val()=='no'){
					alert('กรุณาระบุรหัสธนาคารให้ถูกต้องตามที่ระบบกำหนด');
					$('#returnTranToBank').select();
					return false;
				}
			}
			
			if($('#returnTranToAccNo').val()==""){
				alert("กรุณาระบุเลขที่บัญชีปลายทาง");
				$('#returnTranToAccNo').focus();
				return false
			}
		}else{ //ถ้าเลือกคืนโดยเช็คจะต้องกรอกเลขที่เช็คและวันที่บนเช็คให้สมบูรณ์
			if($('#returnChqNo').val()==""){
				alert("กรุณาระบุเลขที่เช็ค");
				$('#returnChqNo').focus();
				return false
			}
			
			if($('#returnChqDate').val()==""){
				alert("กรุณาระบุวันที่บนเช็ค");
				$('#returnChqDate').focus();
				return false
			}
			if($('#returnChqCus').val()==""){
				alert("กรุณาระบุออกเช็คให้");
				$('#returnChqCus').focus();
				return false
			}
		}	
	}
	
	if(document.getElementById("dcNoteDescription").value==""){
		alert("กรุณาระบุเหตุผล");
		document.getElementById("dcNoteDescription").focus();
		return false;
	}
	
});
function check_customer(){
	var arr =$('#returnTranToCus').val();
    var id=arr.split("#"); 

	$('#chkcustomer').load('check.php?chk=customer&id='+id[0]);
}
function check_customer_ChqCus(){
	var arr =$('#returnChqCus').val();
    var id=arr.split("#"); 

	$('#chkcustomer_ChqCus').load('check.php?chk=customer&id='+id[0]);
}	
function check_bank(){
	var arr =$('#returnTranToBank').val();
    var id=arr.split("#"); 

	$('#chkbank').load('check.php?chk=bank&id='+id[0]);
}

function checkDateSelect()
{
	if(document.getElementById('dcNoteDate').value > currentDate)
	{
		alert('ห้ามเลือก วันที่รายการออกมีผล มากกว่า วันที่ปัจจุบัน');
		document.getElementById('dcNoteDate').value = '';
	}
}

function checkDataSubmit()
{
	if(document.getElementById('dcNoteDate').value > currentDate)
	{
		alert('ห้ามเลือก วันที่รายการออกมีผล มากกว่า วันที่ปัจจุบัน');
		return false;
	}
}

//function สำหรับตรวจสอบว่า "public"."BankInt"."isTranPay" = 1 หรือไม่
function checkTranPay(){
	//กรณีมีการเลือกช่องทางการจ่าย
	if($("#byChannel").val() != "" ){
		//ส่งช่องทางที่เลือกไปตรวจสอบ
		$.get('process_chktranpay.php?BID='+ $("#byChannel").val(), function(data){
			if(data==1){
				$("#show1").show(); 
				$("#show3").show();
				$("#show2").hide(); //ยังไม่ให้โชว์ส่วนระบุเลขที่เช็คต้องรอให้เลือกก่อน
				$('#proviso1').attr('checked', 'checked'); //defult ให้เลือก "คืนโดยโอนธนาคาร"
				$('#returnTranToCus').focus();
				
				//เคลียร์ค่าที่กรอกค้างไว้ (เลขที่เช็คและวันที่บนเช็ค)
				$('#returnChqNo').val(''); //เลขที่เช็ค
				$('#returnChqDate').val(''); //วันที่บนเช็ค
				$('#returnTranToCus').val(''); //เจ้าของบัญชี
				$('#returnTranToBank').val(''); //รหัสธนาคาร
				$('#returnTranToBank').val(''); //เลขที่บัญชีปลายทาง
			}else{
				$("#show1").hide();
				$("#show2").hide(); //ยังไม่ให้โชว์ส่วนระบุเลขที่เช็คต้องรอให้เลือกก่อน
				
				//เคลียร์ค่าที่กรอกค้างไว้ (เลขที่เช็คและวันที่บนเช็ค)
				$('#returnChqNo').val('');
				$('#returnChqDate').val('');
			}
			$('#tranpay').val(data);
		});
	}else{
		$("#show1").hide();
		$("#show2").hide();
		$('#tranpay').val('');
	}
}
</script>