<!--- (เช่าซื้อ)คำนวณหายอดผ่อนต่อเดือน ---->
	<table width="450" border="0" cellspacing="1" cellpadding="1" align="center" name="cal1" id="cal1">
		<tr>
				<tr>
					<td align="right">วันที่ทำสัญญา :</td><td> <input type="text" id="datestart" name="datestart" autocomplete="off" OnKeyPress="check_num(event)" onchange="calvat();hidepayterm();" />  </td>
				</tr>
				<tr>
					<td align="right">ยอดจัด/ยอดลงทุน :</td><td> <input type="text" id="investment" name="investment" autocomplete="off" OnKeyPress="check_num(event)" onchange="hidepayterm();"/> บาท </td>
				</tr>
				<tr>
					<td align="right"></td>
					<td>
						<input type="radio" name="vatchk" id="sumvat" value="sumvat" checked onchange="checkvat();hidepayterm();">รวม VAT
						<input type="radio" name="vatchk" id="notvat" value="notvat" onchange="checkvat();hidepayterm();">ยังไม่รวม  VAT
					</td>
				</tr>
				<tr>
					<td align="right">วันที่เริ่มผ่อนชำระ :</td><td> <input type="text" id="datestartpay" name="datestartpay" autocomplete="off" OnKeyPress="check_num(event)" onchange="hidepayterm();" />  </td>
				</tr>			
				<tr>
					<td align="right">ชำระทุกวันที่ : </td><td>
						<select name="payday" id="payday" onchange="hidepayterm();">
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
				<tr id="showvat">
					<td align="right">VAT :</td>
					<td>
						<input type="text" name="vatcal" id="vatcal" readonly style="background-color:#EEEEEE;" size="5" style="text-align:right" onchange="hidepayterm();">
					</td>
				</tr>
				<tr>
					<td align="right">อัตราดอกเบี้ยต่อปี  : </td><td><input type="text" name="interest" id="interest" autocomplete="off" OnKeyPress="check_num(event)" onchange="hidepayterm();" size="5"> %</td>
				</tr>
				<tr>
					<td align="right">จำนวนเดือน  : </td><td><input type="text" name="month" id="month" autocomplete="off" OnKeyPress="check_num(event)" onchange="hidepayterm();" size="5"> เดือน</td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="button" value="แสดงตารางการผ่อนชำระ" onclick="return chkFact(this.form);"></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="text" name="uesMoney" id="useMoney" style="visibility:hidden;"> <input id="btnUseMoney" name="btnUseMoney" type="button" value="ใช้ยอดนี้ทั้งหมด" onclick="return chkFactAll(this.form);" style="visibility:hidden;"></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><div id="genPayTerm"></div></td>
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
	$("#datestartpay").datepicker({
        showOn: 'button',
        buttonImage: 'images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
		
    });
	$("#showvat").hide();
});

function checkvat(){
	if($("#notvat").attr("checked")==true){
		if($('#datestart').val() == ""){
			alert(" กรุณาระบุวันที่ทำสัญญาก่อน ");
			$("#sumvat").attr("checked","checked");
		}else{
			$("#showvat").show();
			calvat();
		}
	}else{
		$("#showvat").val("");
		$("#showvat").hide();	
	}
}

function calvat(){
	
	$.post('cal_data_post.php',{
			date: $('#datestart').val(),
			type: 'VAT'
		},
		function(data){
			if(data == ""){
				 alert("การคำนวณ VAT ผิดพลาด");        
			}else{
				$("#vatcal").val(data);
			}
		});
			
}

function chkFact(frmapp){
var a= 0;
var errormessage = 'เกิดข้อผิดพลาด \n';
	if($('#datestart').attr('value') == ''){	
		errormessage += "---- กรุณากรอก วันที่ทำสัญญา\n";
		a++;
	}
	if($('#investment').attr('value') == ''){
		errormessage += "---- กรุณากรอก ยอดจัด/ยอดลงทุน\n";
		a++;
	}
	if($("#notvat").attr("checked")==true){
		if($('#vatcal').attr('value') == ''){	
			errormessage += "---- กรุณากรอก VAT\n";
			a++;
		}
	}
	if($('#interest').attr('value') == ''){
		errormessage += "---- กรุณากรอก อัตราดอกเบี้ยต่อปี\n";
		a++;
	}
	if($('#month').attr('value') == ''){
		errormessage += "---- กรุณากรอก จำนวนเดือน\n";
		a++;
	}
	if($('#datestartpay').attr('value') == ''){
		errormessage += "---- กรุณากรอก วันที่เริ่มผ่อนชำระ\n";
		a++;
	}
	if($('#payday').attr('value') == ''){
		errormessage += "---- กรุณากรอก ชำระทุกวันที่\n";
		a++;
	}
	
	if($('#datestart').attr('value') > $('#datestartpay').attr('value')){
		errormessage += "---- วันที่ทำสัญญาต้องน้อยกว่าหรือเท่ากับวันที่เริ่มผ่อนชำระ\n";
		a++;
	}
	if($('#month').attr('value') <= 0){
		errormessage += "---- จำนวนเดือนต้องมากกว่า 0\n";
		a++;
	}	
	
	if(a >= 1){		
		jAlert('error',errormessage,'Error!');
	}else{
		genpayterm(a,frmapp);
	}	
}

function genpayterm(value,frmapp){

	if(value > 0){
		return false;
	}else{
		if($("#notvat").attr("checked")==true){
				var vatchk = 'notvat';
		}else{
				var vatchk = 'sumvat';
		}
		
		$.post("cal_pay_form.php", { 
			pay: document.frm1.pay.value,
			datestart: document.frm1.datestart.value,
			investment: document.frm1.investment.value,
			vatcal: document.frm1.vatcal.value,
			interest: document.frm1.interest.value,
			month: document.frm1.month.value,
			vat : vatchk
		},					
		function(data){
			$("#genPayTerm").show();
			$("#genPayTerm").load("genPayterm.php?term="+document.frm1.month.value+"&conFirstDue="+document.frm1.datestartpay.value+"&conMinPay="+data+"&payday="+document.frm1.payday.value);       					
		});	
		document.getElementById('btn_cal').disabled = false;
		
		document.getElementById('useMoney').style.visibility = 'visible';
		document.getElementById('btnUseMoney').style.visibility = 'visible';
	}
}

function hidepayterm(){
	document.getElementById('btn_cal').disabled = true;
	$("#genPayTerm").hide();
	
	document.getElementById('useMoney').style.visibility = 'hidden';
	document.getElementById('btnUseMoney').style.visibility = 'hidden';
}


// ถ้าใช้ปุ่ม ใช้ยอดนี้ทั้งหมด
function chkFactAll(frmapp){
var a= 0;
var errormessage = 'เกิดข้อผิดพลาด \n';
	if($('#datestart').attr('value') == ''){	
		errormessage += "---- กรุณากรอก วันที่ทำสัญญา\n";
		a++;
	}
	if($('#investment').attr('value') == ''){
		errormessage += "---- กรุณากรอก ยอดจัด/ยอดลงทุน\n";
		a++;
	}
	if($("#notvat").attr("checked")==true){
		if($('#vatcal').attr('value') == ''){	
			errormessage += "---- กรุณากรอก VAT\n";
			a++;
		}
	}
	if($('#interest').attr('value') == ''){
		errormessage += "---- กรุณากรอก อัตราดอกเบี้ยต่อปี\n";
		a++;
	}
	if($('#month').attr('value') == ''){
		errormessage += "---- กรุณากรอก จำนวนเดือน\n";
		a++;
	}
	if($('#datestartpay').attr('value') == ''){
		errormessage += "---- กรุณากรอก วันที่เริ่มผ่อนชำระ\n";
		a++;
	}
	if($('#payday').attr('value') == ''){
		errormessage += "---- กรุณากรอก ชำระทุกวันที่\n";
		a++;
	}
	
	if($('#datestart').attr('value') > $('#datestartpay').attr('value')){
		errormessage += "---- วันที่ทำสัญญาต้องน้อยกว่าหรือเท่ากับวันที่เริ่มผ่อนชำระ\n";
		a++;
	}
	if($('#month').attr('value') <= 0){
		errormessage += "---- จำนวนเดือนต้องมากกว่า 0\n";
		a++;
	}
	
	if($('#useMoney').attr('value') == 0){
		errormessage += "---- กรุณาระบุจำนวนเงินที่จะกำหนดทั้งหมด";
		a++;
	}
	
	if(a >= 1){		
		jAlert('error',errormessage,'Error!');
	}else{
		genpaytermAll(a,frmapp);
	}	
}

function genpaytermAll(value,frmapp){

	if(value > 0){
		return false;
	}else{
		if($("#notvat").attr("checked")==true){
				var vatchk = 'notvat';
		}else{
				var vatchk = 'sumvat';
		}
		
		$.post("cal_lease_flattoeff_form.php", { 
			pay: document.frm1.pay.value,
			datestart: document.frm1.datestart.value,
			investment: document.frm1.investment.value,
			vatcal: document.frm1.vatcal.value,
			interest: document.frm1.interest.value,
			month: document.frm1.month.value,
			vat : vatchk
		},					
		function(data){
			$("#genPayTerm").show();
			$("#genPayTerm").load("genPayterm.php?term="+document.frm1.month.value+"&conFirstDue="+document.frm1.datestartpay.value+"&conMinPay="+$('#useMoney').attr('value')+"&payday="+document.frm1.payday.value);       					
		});	
		document.getElementById('btn_cal').disabled = false;
		
		document.getElementById('useMoney').style.visibility = 'visible';
		document.getElementById('btnUseMoney').style.visibility = 'visible';
	}
}
</script>	