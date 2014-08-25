<!--- คำนวณยอดเช็ค FACTORING ---->
	<table width="350" border="0" cellspacing="1" cellpadding="1" align="center" name="cal1" id="cal1">
		<tr>
				<tr>
					<td align="right">จำนวนเงินบนตั๋ว :</td><td> <input type="text" id="ticketmoney" name="ticketmoney" autocomplete="off" OnKeyPress="check_num(event)" />บาท </td>
				</tr>
				<tr>
					<td align="right">จำนวนเงินที่ลูกค้ารับจริง :</td><td> <input type="text" id="realmoney" name="realmoney" autocomplete="off" OnKeyPress="check_num(event)" />บาท</td>
				</tr>
				<tr>
					<td align="right">อัตราดอกเบี้ย : </td><td><input type="text" name="interestrate" id="interestrate" autocomplete="off" OnKeyPress="check_num(event)" >%</td>
				</tr>
				<tr>
					<td align="right">วันที่เริ่มตั๋ว : </td><td><input type="text" name="datestart" id="datestart" autocomplete="off"></td>
				</tr>
				<tr>
					<td align="right">วันที่สิ้นสุดตั๋ว : </td><td><input type="text" name="dateend" id="dateend" autocomplete="off" ></td>
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
});

function chkFact(frmapp){
var a= 0;
var errormessage = 'เกิดข้อผิดพลาด \n';
	if($('#ticketmoney').attr('value') == ''){	
		errormessage += "---- กรุณากรอก จำนวนเงินบนตั๋ว\n";
		a++;
	}
	if($('#realmoney').attr('value') == ''){
		errormessage += "---- กรุณากรอก จำนวนเงินที่ลูกค้ารับจริง\n";
		a++;
	}
	if($('#interestrate').attr('value') == ''){
		errormessage += "---- กรุณากรอก อัตราดอกเบี้ย\n";
		a++;
	}
	if($('#datestart').attr('value') == ''){
		errormessage += "---- กรุณากรอก วันที่เริ่มตั๋ว\n";
		a++;
	}
	if($('#dateend').attr('value') == ''){
		errormessage += "---- กรุณากรอก วันที่สิ้นสุดตั๋ว\n";
		a++;
	}
	if($('#dateend').attr('value') < $('#datestart').attr('value')){
		errormessage += "---- กรุณากรอก วันที่สิ้นสุดตั๋วห้ามน้อยกว่าวันที่เริ่มตั๋ว\n";
		a++;
	}
	
	if(a >= 1){
		
		alert(errormessage);
	}
	
	actionfat(a,frmapp);
}

</script>