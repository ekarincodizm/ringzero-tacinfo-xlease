<!---(แฟคตอริ่ง) คำนวณยอดตั๋ว---->
	<table width="450" border="0" cellspacing="1" cellpadding="1" align="center" name="cal1" id="cal1">
		<tr>
				<tr>
					<td align="right">วันที่รับเงิน :</td><td> <input type="text" id="datestart" name="datestart" autocomplete="off" OnKeyPress="check_num(event)" size="10"></td>
				</tr>
				<tr>
					<td align="right">จำนวนเงินที่ให้ลูกค้ารวม (เงินต้น+เงินค้ำ) :</td><td><input type="text" id="pay_cus" name="pay_cus" autocomplete="off" OnKeyPress="check_num(event)" size="10"> บาท</td>
				</tr>
				
				<tr>
					<td align="right">รูปแบบการเก็บค่า Factoring Fee :</td><td>
						<input type="radio" name="pay1" id="payout" value="payout" checked onchange="checkvat();">จ่ายนอกตั๋ว
						<input type="radio" name="pay1" id="payin" value="payin" onchange="checkvat();">รวมในยอดตั๋ว
					</td>
				</tr>
				<tr id="new_input">
					<td align="right">จำนวนเงินค่า Factoring Fee :</td>
					<td>
						<input type="text" name="factoring" id="factoring" size="10"> บาท
					</td>
				</tr>				
				<tr>
					<td align="right">วันที่ครบกำหนดตั๋ว :</td><td><input type="text" name="dateend" id="dateend" autocomplete="off" OnKeyPress="check_num(event)" size="10"></td>
				</tr>
				<tr>
					<td align="right">อัตราดอกเบี้ย  : </td><td><input type="text" name="interest" id="interest" autocomplete="off" OnKeyPress="check_num(event)" size="5"> %</td>
				</tr>
		</tr>
	</table>
<script type="text/javascript">	
$(document).ready(function(){
	$("#datestart").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	$("#dateend").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	$("#new_input").hide();
});

function checkvat(){
	if($("#payin").attr("checked")==true){
		$("#new_input").show();	
	}else{
		$("#new_input").hide();	
	}
}

function chkFact(frmapp){
var a= 0;
var errormessage = 'เกิดข้อผิดพลาด \n';
	if($('#datestart').attr('value') == ''){	
		errormessage += "---- กรุณากรอก วันที่รับเงิืน\n";
		a++;
	}
	if($('#pay_cus').attr('value') == ''){
		errormessage += "---- กรุณากรอก จำนวนเงินที่ให้ลูกค้ารวม (เงินต้น+เงินค้ำ)\n";
		a++;
	}
	if($('#payin').attr('checked') == true){
		if($('#factoring').attr('value')== ''){
		errormessage += "---- กรุณากรอก จำนวนเงินค่า Factoring Fee\n";
		a++;
		}
	}
	if($('#dateend').attr('value') == ''){	
		errormessage += "---- กรุณากรอก วันที่ครบกำหนดตั๋ว\n";
		a++;
	}		
	if($('#interest').attr('value') == ''){
		errormessage += "---- กรุณากรอก อัตราดอกเบี้ย\n";
		a++;
	}
	if($('#dateend').attr('value') < $('#datestart').attr('value')){
		errormessage += "---- กรุณากรอก วันที่ครบกำหนดตั๋วต้องไม่น้อยกว่าวันที่รัับเงิน\n";
		a++;
	}
	if(a >= 1){
		
		alert(errormessage);
	}
	
	actionfat(a,frmapp);
}
</script>	