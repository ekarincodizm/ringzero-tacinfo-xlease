<!--- (เช่าซื้อ)คำนวณหายอดผ่อนต่อเดือน ---->
	<table width="450" border="0" cellspacing="1" cellpadding="1" align="center" name="cal1" id="cal1">
		<tr>
				<tr>
					<td align="right">วันที่ทำสัญญา :</td><td> <input type="text" id="datestart" name="datestart" autocomplete="off" OnKeyPress="check_num(event)" onchange="calvat();" />  </td>
				</tr>
				<tr>
					<td align="right">ยอดจัด/ยอดลงทุน :</td><td> <input type="text" id="investment" name="investment" autocomplete="off" OnKeyPress="check_num(event)" /> บาท </td>
				</tr>
				<tr>
					<td align="right"></td>
					<td>
						<input type="radio" name="vatchk" id="sumvat" value="sumvat" checked onchange="checkvat();">รวม VAT
						<input type="radio" name="vatchk" id="notvat" value="notvat" onchange="checkvat();">ยังไม่รวม  VAT
					</td>
				</tr>
				<tr id="showvat">
					<td align="right">VAT :</td>
					<td>
						<input type="text" name="vatcal" id="vatcal" readonly style="background-color:#EEEEEE;" size="5" style="text-align:right">
					</td>
				</tr>
				<tr>
					<td align="right">อัตราดอกเบี้ยต่อปี  : </td><td><input type="text" name="interest" id="interest" autocomplete="off" OnKeyPress="check_num(event)" size="5"> %</td>
				</tr>
				<tr>
					<td align="right">จำนวนเดือน  : </td><td><input type="text" name="month" id="month" autocomplete="off" OnKeyPress="check_num(event)" size="5"> เดือน</td>
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
	
	if(a >= 1){
		
		alert(errormessage);
	}
	
	actionfat(a,frmapp);
}
</script>	