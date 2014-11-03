<?php
include("../../config/config.php");

$debtID = pg_escape_string($_GET["debtID"]);
$contractID = pg_escape_string($_GET["contractID"]);
$nowdate = nowDate();

// หาประเภทสัญญา
$qry_creditType = pg_query("SELECT \"thcap_get_creditType\"('$contractID')");
$creditType = pg_fetch_result($qry_creditType,0);

$qry_other = pg_query("select \"typePayID\",\"typePayRefValue\",\"typePayRefDate\",\"typePayAmt\",\"typePayLeft\",\"debtNet\",\"debtVat\",\"debtDueDate\" from public.\"vthcap_otherpay_debt_current\" where \"debtID\" = '$debtID' ");
while($res_name=pg_fetch_array($qry_other))
{
	$typePayID=trim($res_name["typePayID"]); // รหัสประเภทค่าใช้จ่าย
	$typePayRefValue=trim($res_name["typePayRefValue"]); // ค่าอ้างอิงของค่าใช้จ่าย
	$typePayRefDate=trim($res_name["typePayRefDate"]); // วันที่ตั้งหนี้
	$typePayAmt=trim($res_name["typePayAmt"]); // จำนวนหนี้
	$typePayLeft=trim($res_name["typePayLeft"]); // จำนวนหนี้ค้างจ่ายคงเหลือ
	$debtNet=trim($res_name["debtNet"]); // จำนวนหนี้ก่อน VAT
	$debtVat=trim($res_name["debtVat"]); // จำนวน VAT
	$debtDueDate=trim($res_name["debtDueDate"]); // วันครบกำหนดชำระ
}

$qry_type=pg_query("select \"tpDesc\",\"ableVAT\",\"isLockedVat\" from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
while($res_type=pg_fetch_array($qry_type))
{
	$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
	$ableVAT=trim($res_type["ableVAT"]); // มี vat หรือไม่
	$isLockedVat=trim($res_type["isLockedVat"]); // vat ที่กำหนดตอนแรก (ตั้งหนี้) lock ไว้หรือไม่ หาก vat locked เป็นหนึ่ง หนี้นั้นห้ามแก้ debtVat
}

// หาค่า vat
if($ableVAT == "1" && $isLockedVat != "1")
{
	$qry_vat = pg_query("select cal_rate_or_money('VAT')");
	$myVat = pg_fetch_result($qry_vat,0);
}
else
{
	$myVat = 0.00;
}
?>

<script type="text/javascript">
$(document).ready(function(){
    $("#dcNoteDate").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        //minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
});
</script>

<form method="post" name="myfrm" id="myfrm" action="process_dncn.php">
<fieldset><legend><B>ทำรายการ</B></legend>
	<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF">
		<tr  align="center" bgcolor="#097AB0" style="color:#FFFFFF" height="25">
			<th>รหัสประเภท<br>ค่าใช้จ่าย</th>
			<th>รายการ</th>
			<th>ค่าอ้างอิงของค่าใช้จ่าย</th>
			<th>วันที่ตั้งหนี้</th>
			<th>วันครบกำหนด</th>
			<th>จำนวนหนี้ก่อน VAT</th>
			<th>จำนวน VAT</th>
			<th>จำนวนหนี้</th>
			<th>จำนวนหนี้ค้างจ่าย</th>
		</tr>
		<tr class="even">
			<td align="center"><?php echo $typePayID; ?></td>
			<td align="left"><?php echo $tpDesc; ?></td>
			<td align="center"><?php echo $typePayRefValue; ?></td>
			<td align="center"><?php echo $typePayRefDate; ?></td>
			<td align="center"><?php echo $debtDueDate; ?></td>
			<td align="right"><?php echo number_format($debtNet,2); ?></td>
			<td align="right"><?php echo number_format($debtVat,2); ?></td>
			<td align="right"><?php echo number_format($typePayAmt,2); ?></td>
			<td align="right"><?php echo number_format($typePayLeft,2); ?></td>
		</tr>
	</table>
	
	<div style="padding:20px 0px 10px">
		<b>รูปแบบส่วนลด : </b>
		<select name="typeDiscount" id="typeDiscount">
			<option value="">--เลือกรูปแบบส่วนลด--</option>
			<option value="before">ส่วนลดก่อน VAT</option>
			<option value="after">ส่วนลดหลัง VAT</option>
		</select><span style="color:red;font-weight:bold;">*</span>
		&nbsp;&nbsp;&nbsp;
		<b>จำนวนเงินส่วนลด : </b><input type="text" name="discountAmt" id="discountAmt" oncontextmenu="return false" style="text-align:right" onkeypress="check_num(event);"><span style="color:red;font-weight:bold;">*</span>
		<br><br>
		<b>วันที่ส่วนลดมีผล : </b><input type="text" name="dcNoteDate" id="dcNoteDate" readonly="true" size="15" value="<?php echo $nowdate; ?>"><span style="color:red;font-weight:bold;">*</span>
	</div>
	<div><b>::เหตุผล::</b><span style="color:red;font-weight:bold;">*</span></div>
	<div><textarea name="dcNoteDescription" id="dcNoteDescription" cols="50" rows="5"></textarea></div>
	<div>
		<input type="hidden" name="contractID" value="<?php echo $contractID;?>">
		<input type="hidden" name="debtID" id="debtID" value="<?php echo $debtID;?>">
		<input type="hidden" name="debtNet" id="debtNet" value="<?php echo $debtNet;?>"> <!-- จำนวนหนี้ก่อน VAT -->
		<input type="hidden" name="debtVat" id="debtVat" value="<?php echo $debtVat;?>"> <!-- จำนวน VAT -->
		<input type="hidden" name="typePayAmt" id="typePayAmt" value="<?php echo $typePayAmt;?>"> <!-- จำนวนหนี้ -->
		<input type="hidden" name="typePayLeft" id="typePayLeft" value="<?php echo $typePayLeft;?>"> <!-- จำนวนหนี้ค้างจ่าย -->
		<input type="hidden" name="myVat" id="myVat" value="<?php echo $myVat;?>"> <!-- อัตรา vat -->
		<input type="hidden" name="method" value="add">
		<!--<input type="submit" id="submitbutton" value="บันทึก">-->
		<input type="button" id="submitbutton" value="บันทึก">
		<input type="reset" value="ยกเลิก">
	</div>
</fieldset>
</form>

<div name="panel_confirm_request" id="panel_confirm_request" style="padding:10px 0px"></div>

<script>
$("#submitbutton").click(function(){
	var typePayRefDate = '<?php echo $typePayRefDate ?>'; // วันที่ตั้งหนี้
	var creditType = '<?php echo $creditType; ?>'; // ประเภทสัญญา
	
	if(document.getElementById("typeDiscount").value=="")
	{
		alert("กรุณาเลือก รูปแบบส่วนลด");
		return false;
	}
	else if(document.getElementById("discountAmt").value=="")
	{
		alert("กรุณาระบุ จำนวนเงินส่วนลด");
		return false;
	}
	else if(document.getElementById("dcNoteDate").value=="")
	{
		alert("กรุณาระบุ วันที่ส่วนลดมีผล");
		return false;
	}
	else if(document.getElementById("dcNoteDescription").value=="")
	{
		alert("กรุณาระบุเหตุผล");
		return false;
	}
	else if(document.getElementById("dcNoteDate").value < typePayRefDate)
	{
		alert("วันที่ส่วนลดมีผล ห้ามน้อยกว่า วันที่ตั้งหนี้");
		return false;
	}
	else
	{
		var typeDiscount = document.getElementById('typeDiscount').value; // รูปแบบส่วนลด
		var discountAmt = document.getElementById('discountAmt').value; // จำนวนเงินส่วนลด
			discountAmt = parseFloat(discountAmt).toFixed(2);
		var myVat = document.getElementById('myVat').value; // อัตรา Vat
			myVat = parseFloat(myVat).toFixed(2);
			
		var discountDebtNet; // ส่วนลดก่อน vat
		var discountDebtVat; // ส่วนลด vat
		var sumDiscount; // รวมส่วนลด
			
		// ข้อมูลเดิม
		var debtNet = document.getElementById('debtNet').value; // จำนวนหนี้ก่อน VAT เดิม
			debtNet = parseFloat(debtNet).toFixed(2);
		var debtVat = document.getElementById('debtVat').value; // จำนวน VAT เดิม
			debtVat = parseFloat(debtVat).toFixed(2);
		var typePayAmt = document.getElementById('typePayAmt').value; // จำนวนหนี้ เดิม
			typePayAmt = parseFloat(typePayAmt).toFixed(2);
		var typePayLeft = document.getElementById('typePayLeft').value; // จำนวนหนี้ค้างชำระ เดิม
			typePayLeft = parseFloat(typePayLeft).toFixed(2);
			
		// ข้อมูลส่วนลด
		if(typeDiscount == "before")
		{ // ถ้าเป็นส่วนลดก่อน vat
			discountDebtNet = discountAmt; // ส่วนลดก่อน vat
			discountDebtNet = parseFloat(discountDebtNet).toFixed(2);
			
			discountDebtVat = parseFloat(discountDebtNet * myVat / 100).toFixed(2); // ส่วนลด vat
			discountDebtVat = parseFloat(discountDebtVat).toFixed(2);
			
			sumDiscount = parseFloat(discountDebtNet) + parseFloat(discountDebtVat); // รวมส่วนลด
			sumDiscount = parseFloat(sumDiscount).toFixed(2);
		}
		else if(typeDiscount == "after")
		{ // ถ้าเป็นส่วนลดหลัง vat
			sumDiscount = discountAmt; // รวมส่วนลด
			sumDiscount = parseFloat(sumDiscount).toFixed(2);
			
			discountDebtVat = parseFloat(sumDiscount * myVat / (parseFloat(100) + parseFloat(myVat))).toFixed(2); // ส่วนลด vat
			discountDebtVat = parseFloat(discountDebtVat).toFixed(2);
			
			discountDebtNet = sumDiscount - discountDebtVat; // ส่วนลดก่อน vat
			discountDebtNet = parseFloat(discountDebtNet).toFixed(2);
		}
		
		// ข้อมูลใหม่
		var debtNetNew = debtNet - discountDebtNet; // จำนวนหนี้ก่อน VAT ใหม่
			debtNetNew = parseFloat(debtNetNew).toFixed(2);
		var debtVatNew = debtVat - discountDebtVat; // จำนวน VAT ใหม่
			debtVatNew = parseFloat(debtVatNew).toFixed(2);
		var typePayAmtNew = typePayAmt - sumDiscount; // จำนวนหนี้ ใหม่
			typePayAmtNew = parseFloat(typePayAmtNew).toFixed(2);
		var typePayLeftNew = typePayLeft - sumDiscount; // จำนวนหนี้ค้างชำระ ใหม่
			typePayLeftNew = parseFloat(typePayLeftNew).toFixed(2);
			
		if(creditType == 'HIRE_PURCHASE' && parseFloat(sumDiscount) > parseFloat(debtNet))
		{
			alert("สัญญาประเภท HIRE_PURCHASE ห้ามลดราคาเกินจำนวนหนี้ก่อน VAT : จำนวนหนี้ก่อน VAT คือ "+debtNet+" จำนวนที่จะลดคือ "+sumDiscount);
			return false;
		}
		else if(parseFloat(sumDiscount) > parseFloat(typePayLeft))
		{
			alert("ห้ามลดราคาเกินจำนวนหนี้ที่ค้างชำระ จำนวนหนี้ค้างชำระคือ "+typePayLeft+" จำนวนที่จะลดคือ "+sumDiscount);
			return false;
		}
		else
		{
			var rstext='ข้อมูลเดิม\r\n\n\tจำนวนหนี้ก่อน VAT :\t'+debtNet+'\t\tบาท\r\n\tจำนวน VAT :\t\t'+debtVat+'\t\tบาท\r\n\tรวมจำนวนหนี้  :\t\t'+typePayAmt+'\t\tบาท\r\n\tจำนวนหนี้ค้างชำระ :\t'+typePayLeft+'\t\tบาท\r\n\nข้อมูลส่วนลด\r\n\n\tส่วนลดหนี้ก่อน VAT :\t'+discountDebtNet+'\t\tบาท\r\n\tส่วนลด VAT :\t\t'+discountDebtVat+'\t\tบาท\r\n\tรวมส่วนลด :\t\t\t'+sumDiscount+'\t\tบาท\r\n\nข้อมูลใหม่\r\n\n\tจำนวนหนี้ก่อน VAT :\t'+debtNetNew+'\t\tบาท\r\n\tจำนวน VAT :\t\t'+debtVatNew+'\t\tบาท\r\n\tรวมจำนวนหนี้  :\t\t'+typePayAmtNew+'\t\tบาท\r\n\tจำนวนหนี้ค้างชำระ :\t'+typePayLeftNew+'\t\tบาท';
			if(confirm(rstext)==true)
			{
				document.myfrm.submit();
			}
		}
	}
	
	//$("#panel_confirm_request").load("confirm_request.php?debtID="+$("#debtID").val()+"&discountAmt="+$("#dcNoteDescription").val());
});

function check_num(e)
{ // ให้พิมพ์ได้เฉพาะตัวเลขและจุด
    var key;
    if(window.event)
	{
        key = window.event.keyCode; // IE
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			window.event.returnValue = false;
		}
    }
	else
	{
        key = e.which; // Firefox       
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			key = e.preventDefault();
		}
	}
};
</script>