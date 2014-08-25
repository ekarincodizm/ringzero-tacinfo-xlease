	<fieldset style="width:90%"><legend><B>หนี้ที่ต้องการจะตั้ง</B></legend>
	<div>
		<table border="0" "width:100%" align="left" >		
			<tr><td align="left" colspan="2"><B><font color="red">เพิ่มคำอธิบายดังนี้</font><B></td></tr>
			<tr><td align="left" colspan="2"><font color="red"><u>- กรณีระบบ VAT รวม</u></font></td><tr>
			<tr><td></td><td><font color="red">- ให้ใส่ '-' (ขีดกลาง) ในช่อง 'จำนวนภาษีมูลค่าเพิ่ม' ในรายการที่มี VAT</font></td><tr>
			<tr><td></td><td><font color="red">- ให้ใส่ '0' (ศูนย์) ในช่อง 'จำนวนภาษีมูลค่าเพิ่ม' ในรายการที่ไม่มี VAT</font></td><tr>
			<tr><td></td><td><font color="red">- ใส่ตัวเลขภาษีมูลค่าเพิ่ม ลงในช่อง "รวมยอดภาษีมูลค่าเพิ่ม"</font></td><tr>
			<tr><td align="left" colspan="2"><font color="red"><u>- กรณีระบบ VAT แยก</u></font></td><tr>
			<tr><td></td><td><font color="red">- ให้ใส่ จำนวนภาษีมูลค่าเพิ่ม ของรายการนั้น ในช่อง 'จำนวนภาษีมูลค่าเพิ่ม' ในรายการที่มี VAT</font></td><tr>
			<tr><td></td><td><font color="red">- ให้ใส่ '0' (ศูนย์) ในช่อง 'จำนวนภาษีมูลค่าเพิ่ม' ในรายการที่ไม่มี VAT</font></td><tr>
			<tr><td></td><td><font color="red">- ตรวจสอบความถูกต้องในช่อง "รวมยอดภาษีมูลค่าเพิ่ม"</font></td><tr> 
	</table>
	</div>	
	
	<table width="100%" align="center" border="0">
	<tr >
		<td align="center">
		<input  type="button" value="+ เพิ่ม" id="addShare" >
		<input  type="button" value="- ลบ" id="removeShare">
		<input  type="hidden" name="rowShare" id="rowShare" value="1">
		</td>		
	</tr>
	<tr bgcolor="#FFFFFF">
			<table border="0" width="60%" id="tableShare" cellSpacing="1" cellPadding="1"  bgcolor="#BBBBEE" align="center">
				<tr>
					<td bgcolor="#FFCECE" align="center"><b>ลำดับที่</b></td>				
					<td align="center" bgcolor="#FFCECE"><b>ชื่อรายการสินค้าหรือบริการ<font color="red">*<b></td>					
					<td align="center" bgcolor="#FFCECE"><b>จำนวนเงินค่าสินค้าหรือบริการ<font color="red">*<b></td>					
					<td align="center" bgcolor="#FFCECE"><b>จำนวนภาษีมูลค่าเพิ่ม<font color="red">*<b></td>
					<?php if($v_page=="billingthecreditors"){}
						else {?>
							<td align="center" bgcolor="#FFCECE"><b>จำนวนภาษีหัก ณ ที่จ่าย<b></td>
							<td align="center" bgcolor="#FFCECE"><b>เลขที่ใบภาษีหัก ณ ที่จ่าย<b></td>
					<?php } ?>				
				</tr>
				<input name="txt_amount"  id="txt_amount" size="30" hidden >
				<input name="txt_amountwithhol"  id="txt_amountwithhol" size="30" hidden >
				<tr bgcolor="#E8E8E8">

					<td align="center" width="5%" >1</td>	
					<td><input name="txt_namep_s1"  id="txt_namep_s1" size="50" title="เช่น ชำระค่าบริการโฆษณา ป้ายหน้ารถประจำทาง 601"></td>
					<td><input name="txt_amountp_s1"  id="txt_amountp_s1" size="30"  onkeypress="check_num(event);"  onkeyup="chk_sumvat();" onblur="chk_sumvat();" style="text-align:right;"></td>
					<td><input name="txt_vatp_s1"  id="txt_vatp_s1" size="30"  onkeypress="check_num(event);" onkeyup="chk_sumvat();" onblur="chk_sumvat();" style="text-align:right;"></td>
					<?php if($v_page=="billingthecreditors"){}
						else {?>
							<td><input  name="txt_amountwithhol_s1"  id="txt_amountwithhol_s1" size="30" value="0.00" onkeypress="check_num(event);" onkeyup="chk_sumvat();" onblur="chk_sumvat();" style="text-align:right;"></td>
							<td><input  name="txt_nowithhol_s1"  id="txt_nowithhol_s1" size="30" title="เลขที่ใบภาษีหัก ณ ที่จ่าย"></td>
					<?php } ?>	
				</tr>				
			</table>	
			<div id="ShareGroup">
				<div id='ShareDiv'>
				</div>
			</div>	
			<table border="0" width="100%" id="tableShare" cellSpacing="1" cellPadding="1" align="right">
				<tr>
					<td><input size="9.1" style="border: none"></td>	
					<td><input size="29" style="border: none"></td>
					<td><input size="30" style="border: none" value="รวมยอดภาษีมูลค่าเพิ่ม :" style="text-align:right;"></td>
					<td><input  name="txt_sumvat"  id="txt_sumvat" size="30" onkeypress="check_num(event);"  style="text-align:right;"></td>
					<?php if($v_page=="billingthecreditors"){?>					
					<td colspan="1"><input size="3" style="border: none"></td>
					<?php } else { ?>	
					<td colspan="2"><input size="49" style="border: none"></td>
					<?php }  ?>
				</tr>	
				
			</table>	
	</tr>
	</table>

	</fieldset>
	<fieldset style="width:90%"><legend><b>หมายเหตุ </b></legend>
				<textarea id="note" name="note" cols="150" rows="4"></textarea>
	</fieldset></center>
<script type="text/javascript">
var nubShare = 1;	
//กดปุ่ม ลบ
	$("#removeShare").click(function(){	
		if(nubShare==1){
           return false;		
        }
		$("#ShareDiv" + nubShare).remove();
		nubShare--; 
		document.getElementById("rowShare").value = nubShare;
		chk_sumvat();  
    });

//กดปุ่ม เพิ่ม
	$('#addShare').click(function()
	{ 	nubShare++;
		if(nubShare == 0)
		{
			document.getElementById("tableShare").style.visibility = 'visible';
			document.getElementById("rowShare").value = nubShare;
		}
		else if(nubShare > 0)
		{
			console.log(nubShare);
			var newShareBoxDiv = $(document.createElement('div')).attr("id", 'ShareDiv' + nubShare);
			
			table =  '<table width="60%" id="tableShare" cellSpacing="1" cellPadding="1" border="0" align="center"  bgcolor="#BBBBEE">'
			+ '<tr bgcolor="#E8E8E8">'
			+ '		<td><input  name="txt'+nubShare+'"  id="txt'+nubShare+'" size="2" style="border: none;text-align:center;background: #E8E8E8" value="'+nubShare+'" "></td>'
			+ '		<td><input  name="txt_namep_s'+nubShare+'"  id="txt_namep_s'+nubShare+'" size="50"  title="เช่น ชำระค่าบริการโฆษณา ป้ายหน้ารถประจำทาง 601"></td>'
			+ '		<td><input  name="txt_amountp_s'+nubShare+'"  id="txt_amountp_s'+nubShare+'" size="30"  onkeypress="check_num(event);" onkeyup="chk_sumvat();" onblur="chk_sumvat();" style="text-align:right;"></td>'
			+ '		<td><input  name="txt_vatp_s'+nubShare+'"  id="txt_vatp_s'+nubShare+'" size="30" onkeypress="check_num(event);" onkeyup="chk_sumvat();" onblur="chk_sumvat();" style="text-align:right;"></td>'
			
			<?php if($v_page=="billingthecreditors"){
			
 			}else { ?>
			
			+ '		<td><input  name="txt_amountwithhol_s'+nubShare+'"  id="txt_amountwithhol_s'+nubShare+'" size="30" value="0.00" onkeypress="check_num(event);"  onkeyup="chk_sumvat();" onblur="chk_sumvat();" style="text-align:right;"></td>'
			+ '		<td><input  name="txt_nowithhol_s'+nubShare+'"  id="txt_nowithhol_s'+nubShare+'"   size="30"  title=""></td>'
			<?php } ?>
			+ '</tr></table>'
			newShareBoxDiv.html(table);
			newShareBoxDiv.appendTo("#ShareGroup");				
			document.getElementById("rowShare").value = nubShare;	

		}
    });
	
function check_num(e)
{ // ให้พิมพ์ได้เฉพาะตัวเลขและจุด

    var key;
    if(window.event)
	{
        key = window.event.keyCode; // IE
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44  && key != 47)
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
			&& key != 43 && key != 44 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			key = e.preventDefault();
		}
	}
}
function chk_backAmtvat(no) { 
	var chk=true;
	var macth1=0;	
	var txt_vatp_s=document.getElementById('txt_vatp_s'+no).value;//จำนวนภาษีมูลค่าเพิ่ม
	var txt_amountp_s=parseFloat(document.getElementById('txt_amountp_s'+no).value);//จำนวนเงินค่าสินค้าหรือบริการ
	
	if((txt_vatp_s !='-')&&(txt_vatp_s !='0')){
		txt_vatp_s=parseFloat(txt_vatp_s);
		macth1= (txt_amountp_s * 7)/100;//ผลการคิด
		var add_5=macth1+5;;
		var sub_5=macth1-5;				
		if((txt_vatp_s >= sub_5) && (txt_vatp_s <= add_5)){}
		else{chk= false;}		
	}
	return chk;	
}
//ตรวจสอบการคืดรวมยอดภาษีมูลค่าเพิ่ม :
function chk_sumvat(){
	var no_rowShare=parseInt(document.getElementById('rowShare').value);
	var sum_vat=0;
	var sumamount=0;
	var sumamountwithhol=0;	
	var y=0;
	for (i=1;i<=no_rowShare;i++){		
		var txt_vatp_s=document.getElementById('txt_vatp_s'+i).value;	
		var txt_amountp_s=document.getElementById('txt_amountp_s'+i).value;
		var txt_amountwithhol_s=document.getElementById('txt_amountwithhol_s'+i).value;	
		
		if((txt_vatp_s !="-") &&(txt_vatp_s !="")){
			txt_vatp_s=parseFloat(txt_vatp_s);
			sum_vat +=txt_vatp_s;
		}
		if(txt_amountp_s !=""){
			sumamount+= parseFloat(txt_amountp_s);
		}
		if(txt_amountwithhol_s !=""){
			sumamountwithhol+= parseFloat(txt_amountwithhol_s);
		}
		if((txt_vatp_s =="-")||(y >0)){
			y++;
			$('#txt_sumvat').val('');
		}
		else{	
			$('#txt_sumvat').val(sum_vat);	
		}	
	document.getElementById('txt_amount').value=sumamount;
	document.getElementById('txt_amountwithhol').value=sumamountwithhol;		
	}
}

</script>
<script type="text/javascript">
function chk_inputdata_adddebt(){
	var theMessage="";
	/*ผลรวมที่ จำนวนภาษีมูลค่าเพิ่ม :*/
	if(document.getElementById('txt_sumvat').value == ""){
		theMessage = theMessage + "\n -->  กรุณาป้อนข้อมูลรวมยอดภาษีมูลค่าเพิ่ม" ;		
	}
	//ตรวจสอบ ข้อมูล ใน กรอบหนี้ที่ต้องการจะตั้ง
	var no_rowShare=parseInt(document.getElementById('rowShare').value);
	var theMessage0="";
	var j=0;
	var v=0;
	var sumvat=0;
	var sumamount=0;
	var sumamountwithhol=0;
	for (i=1;i<=no_rowShare;i++){
			var txt_namep_s=document.getElementById('txt_namep_s'+i).value;
			var txt_vatp_s=document.getElementById('txt_vatp_s'+i).value;
			var txt_amountp_s=document.getElementById('txt_amountp_s'+i).value;
			var txt_amountwithhol_s=document.getElementById('txt_amountwithhol_s'+i).value;
						
			if((txt_namep_s =="")||(txt_vatp_s=="")||(txt_amountp_s=="")){
					theMessage = theMessage + "\n -->  กรุณาป้อนข้อมูลให้ครบในลำดับที่" + i ;
			}
			if(txt_amountp_s !=""){
				sumamount+= parseFloat(txt_amountp_s);
			}
			if(txt_amountwithhol_s !=""){
				sumamountwithhol+= parseFloat(txt_amountwithhol_s);
			}
			if((txt_vatp_s != "-")){
					if((txt_vatp_s == 0)){v++;}
					else{					
						sumvat += parseFloat(txt_vatp_s);
						if(chk_backAmtvat(i)){ 	}
						else{						
							theMessage0 = theMessage0 + "\n -->  จำนวนภาษีมูลค่าเพิ่มที่ระบบคิดกับที่คีย์มีผลต่าง +-เกิน 5 บาทในลำดับที่ " + i ;						
						}
					}
			}
			else{
					j++;				
			}
			
	}
	document.getElementById('txt_amount').value=sumamount;
	document.getElementById('txt_amountwithhol').value=sumamountwithhol;
	if((j>0)&&((j+v)!=no_rowShare)){
		theMessage = theMessage + "\n -->  จำนวนภาษีมูลค่าเพิ่มจะต้องมีค่าเป็น  0 หรือ - เท่านั้น" ;	
	}
	else if(j==0){
		theMessage = theMessage + theMessage0;		
		if(sumvat != parseFloat(document.getElementById('txt_sumvat').value)){
			theMessage = theMessage + "\n -->   ผลรวมผลรวมที่ จำนวนภาษีมูลค่าเพิ่มไม่ถูกต้อง" ;
		}
	}
	return theMessage;
}
</script>
