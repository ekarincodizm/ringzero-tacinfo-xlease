<!--- ยอดจ่ายขั้นต่ำ ---->
	<table width="350" border="0" cellspacing="1" cellpadding="1" align="center" name="cal1" id="cal1">
		<tr>
				<tr>
					<td align="right">จำนวนเงินต้น :</td><td> <input type="text" id="tbmoney" name="tbmoney" autocomplete="off" OnKeyPress="check_num(event)" /> บาท </td>
				</tr>
				<tr>
					<td align="right">อัตราดอกเบี้ย :</td><td> <input type="text" id="interest" name="interest" autocomplete="off" OnKeyPress="check_num(event)" size="5"/> % </td>
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
					<td align="right">% ค่าใช้จ่าย : </td><td><input type="text" name="percentpayother" id="percentpayother" autocomplete="off" OnKeyPress="check_num(event)" size="5"> %</td>
				</tr>
				<tr>
					<td align="right">ค่าใช้จ่ายอื่นๆ : </td><td><input type="text" name="payother" id="payother" autocomplete="off" OnKeyPress="check_num(event)" size="5"> บาท</td>
				</tr>
				<tr>
					<td align="right">ระยะเวลา : </td><td><input type="text" name="month" id="month" autocomplete="off" OnKeyPress="check_num(event)" size="5"> เดือน</td>
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
	if($('#tbmoney').attr('value') == ''){	
		errormessage += "---- กรุณากรอก จำนวนเงินต้น\n";
		a++;
	}
	if($('#interest').attr('value') == ''){
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
	if($('#month').attr('value') == ''){
		errormessage += "---- กรุณากรอก ระยะเวลา\n";
		a++;
	}
	if($('#payother').attr('value') == ''){
			errormessage += "---- กรุณากรอก ค่าใช้จ่ายอื่นๆ\n";
			a++;
	}
	if($('#percentpayother').attr('value') == ''){
		errormessage += "---- กรุณากรอก % ค่าใช้จ่าย\n";
		a++;
	}
	if($('#datestart').attr('value') <= $('#datestartcon').attr('value')){
		errormessage += "---- วันที่เริ่มจ่ายต้องมากกว่าวันที่เริ่มทำสัญญา \n";
		a++;
	}
	if($('#month').attr('value') > 360){
		errormessage += "---- จำนวนเดือนต้องไม่เกิน 360\n";
		a++;
	}
	if(parseFloat($('#month').attr('value')) / parseFloat($('#month').attr('value')).toFixed(0) != 1){
		errormessage += "---- จำนวนเดือน ต้องเป็นตัวเลขจำนวนเต็มเท่านั้น \n";
		a++;
	}
	if($('#payday').attr('value') > 31){	
		errormessage += "---- วันที่ผิดรูปแบบ \n";
		a++;
	}
	if(a >= 1){
		
		alert(errormessage);
	}
	
	actionfat(a,frmapp);
}
</script>	