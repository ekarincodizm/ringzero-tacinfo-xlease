

<!--- จำนวนเดือนที่ต้องชำระ ---->

<table width="350" border="0" cellspacing="1" cellpadding="1" align="center" name="cal2" id="cal2">
	<tr>
			<tr>
				<td align="right">จำนวนเงินกู้ :</td><td> <input type="text" id="tbmoney1" name="tbmoney1" autocomplete="off" OnKeyPress="check_num(event)"/> บาท </td>
			</tr>
			<tr>
				<td align="right">อัตราดอกเบี้ย :</td><td> <input type="text" id="interest1" autocomplete="off" name="interest1" OnKeyPress="check_num(event)" /> % </td>
			</tr>
				<tr>
					<td align="right">ชำระทุกวันที่ : </td><td>
						<select name="payday" id="payday">
							<?php
									for($i=1;$i<=28;$i++){
										if($i<10){
											$value = "0".$i;
										}else{
											$value = $i;
										}
										echo "<option value=\"$value\">$value</option>";
									}
							?>
						</select>
					</td>
			</tr>
			<tr>
				<td align="right">วันที่ทำสัญญา : </td><td><input type="text" name="datestartcon" id="datestartcon" autocomplete="off" OnKeyPress="check_num(event)" size="10"></td>
			</tr>	
			<tr>
				<td align="right">วันที่เริ่มจ่าย : </td><td><input type="text" name="datestart" id="datestart" autocomplete="off" OnKeyPress="check_num(event)" size="10"></td>
			</tr>
			<tr>
				<td align="right">ผ่อนได้ :</td><td> <input type="text" id="moneypay" autocomplete="off" name="moneypay" OnKeyPress="check_num(event)" /> บาท/เดือน </td>
			</tr>
	
	</tr>
</table>
<script type="text/javascript">	
$(document).ready(function(){	
	$("#datestartcon").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	$("#datestart").datepicker({
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
	if($('#tbmoney1').attr('value') == ''){	
		errormessage += "---- กรุณากรอก จำนวนเงินกู้\n";
		a++;
	}
	if($('#interest1').attr('value') == ''){
		errormessage += "---- กรุณากรอก อัตราดอกเบี้ย\n";
		a++;
	}
	if($('#datestartcon').attr('value') == ''){	
		errormessage += "---- กรุณากรอก วันที่ทำสัญญา\n";
		a++;
	}	
	if($('#payday').attr('value') == ''){	
		errormessage += "---- กรุณากรอก ชำระทุกวันที่\n";
		a++;
	}	
	if($('#datestart').attr('value') == ''){
		errormessage += "---- กรุณากรอก วันที่เริ่มจ่าย\n";
		a++;
	}
	if($('#moneypay').attr('value') == ''){
		errormessage += "---- กรุณากรอก จำนวนเงินในการผ่อน\n";
		a++;
	}
	if($('#datestart').attr('value') <= $('#datestartcon').attr('value')){
		errormessage += "---- วันที่เริ่มจ่ายต้องมากกว่าวันที่เริ่มทำสัญญา \n";
		a++;
	}
	if(a >= 1){
		
		alert(errormessage);
	}	
	actionfat(a,frmapp);
}
</script>