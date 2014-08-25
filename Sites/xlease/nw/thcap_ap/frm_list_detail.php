<table border="0" width="80%" cellSpacing="1" cellPadding="2" align="center">
		<tr>
			<td align="right">วันที่ใบกำกับภาษี:</td>
			<td colspan="1"><input type="text" id="date_invoice" name="date_invoice" title="วันที่ในเอกสารใบกำกับภาษี ที่เจ้าหน้านำมาเรียกเก็บเงิน" value="<?php echo nowDate(); ?>" size="20"><font color="red">*</font>
			<font color="red"><b>     วันที่บนใบกำกับภาษี โดยวันที่ที่ได้รับสินค้าหรือบริการต้องตรงกับวันที่บนใบกำกับภาษี</b></font></td>
		</tr>
		
		<tr>
			<td align="right">ตั้งเจ้าหนี้จากเอกสาร:</td>
			<td>
				<input type="radio" id="purchase_order" name="creditor" onchange="" value="1" <?php if($creditor=="" || $creditor=="1"){ echo "checked"; }?>  />ใบสั่งซื้อ
				<input type="radio" id="quotation" name="creditor" onchange="" value="2" <?php if($creditor=="2"){ echo "checked"; }?>/>ใบเสนอราคา 
				<input type="radio" id="letter" name="creditor" onchange="" value="3" <?php if($creditor=="3"){ echo "checked"; }?>/>หนังสือสัญญา
			</td> 				
		</tr>
		<tr>
			<td align="right">เลขอ้างอิงเอกสาร :</td><td><input type="text" id="ref_no" name="ref_no" title="เลขที่ของเอกสาร ใบสั่งซื้อของบริษัท / ใบเสนอราคาของเจ้าหนี้ / หนังสือสัญญาระหว่างบริษัทกับเจ้าหนี้"  size="50"><font color="red">*</font></td>			
		</tr>
		<tr>
			<td align="right">วันที่ของเอกสารอ้างอิง :</td><td><input type="text" id="date_ref_no" name="date_ref_no"  value="<?php echo nowDate(); ?>" title="วันที่ของเอกสาร ใบสั่งซื้อของบริษัท / ใบเสนอราคาของเจ้าหนี้ / หนังสือสัญญาระหว่างบริษัทกับเจ้าหนี้" size="20"><font color="red">*</font></td>			
		</tr>
		<tr>
			<td align="right"> เจ้าหนี้:</td><td>
			<input type="radio" id="creditor0" name="rdo_creditor" onchange="chk_creditor('0');" value="0" <?php if($to=="" || $to=="0"){ echo "checked"; }?>  />บุคคลภายนอก			
			<input type="radio" id="creditor1" name="rdo_creditor" onchange="chk_creditor('1');" value="1" <?php if($to=="1"){ echo "checked"; }?>/>ลูกค้าบุคคล
			<input type="radio" id="creditor2" name="rdo_creditor" onchange="chk_creditor('2');" value="2" <?php if($to=="2"){ echo "checked"; }?>/>ลูกค้านิติบุคคล
			<input type="radio" id="creditor3" name="rdo_creditor" onchange="chk_creditor('3');" value="3" <?php if($to=="3"){ echo "checked"; }?>/>พนักงานบริษัท</td><tr>
		<tr>
			<td></td>
			<td colspan="1">
				<span id="span_creditor"><input type="text" id="txt_creditor" name="txt_creditor" size="50" onkeyup="KeyData();" onblur="KeyData(); class="tooltipped_creditor" title="เลือกเจ้าหนี้ที่บริษัทจะต้องชำระเงินตามเครดิตจากข้อมูลในระบบ"><font color="red">*</font></span>
				<span id="span_creditor_out"><input type="text" id="txt_creditor_out" name="txt_creditor_out" size="50" class="tooltipped_creditor" title="กรอกข้อมูลชื่อ บุคคลภายนอก"><font color="red">*</font></span>
			</td>						
		</tr>	
		<tr>
			<td align="right">วันที่ครบกำหนดชำระ :</td><td><input type="text" id="date_due" name="date_due"  value="<?php echo nowDate(); ?>" title="วันที่ครบกำหนดชำระ" size="20"><font color="red">*</font></td>			
		</tr>		
		<tr>
			<td align="right">
			<input type =checkbox name="chk_debtor"  onchange="show_span_conid()"></td><td> เป็นการซื้อสินค้าเพื่อให้เกิดลูกหนี้</td>
		</tr>
		<tr>
			<td align="right"><span id="span_conid_name">เลขที่สัญญา:</span></td>
			<td><span id="span_conid"><input  name="txt_conid" title="ให้ใส่เลขที่สัญญา (สามารถใส่เลขที่สัญญาที่ยังไม่มีในระบบได้)" id="txt_conid" size="54" ><font color="red">*</font></span></td>	
		</tr>
</table>
<script type="text/javascript">
$(document).ready(function(){	

	document.getElementById('span_creditor_out').style.display = 'block';	
	document.getElementById('span_creditor').style.display='none';
});
function  chk_find(){
	var find;
	if(document.getElementById("creditor3").checked){
		find = "emp";	
	} else if (document.getElementById("creditor1").checked){
		find = "cus";
	} else if (document.getElementById("creditor2").checked){
		find = "cus_corp";
	} 
	return find; 
}
function KeyData(){
	var  cf = chk_find();
	$("#txt_creditor").autocomplete({
			source: "../thcap_add_paymentpvoucher/s_officer.php?find="+cf,
			minLength:1
	});
}
function chk_creditor(no){
	document.getElementById("txt_creditor").value= '';
	document.getElementById("txt_creditor_out").value= '';	
	if(no=='0'){
		document.getElementById('span_creditor_out').style.display = 'block';
		document.getElementById('span_creditor').style.display = 'none';
	}else{
		document.getElementById('span_creditor').style.display = 'block';
		document.getElementById('span_creditor_out').style.display = 'none';
	}
}
function chk_inputdata_list_detail(){
	var theMessage="";
	var ele=$('input[name="chk_debtor"]');  
	if(($(ele).is(':checked')))
	{  
		if(document.getElementById('txt_conid').value == ""){
			theMessage = theMessage + "\n -->  กรุณาป้อนเลขที่สัญญา";		
			
		}
	}
	if(document.getElementById('date_invoice').value == ""){
		theMessage = theMessage + "\n -->  กรุณาป้อนวันที่ใบกำกับภาษี";		
		
	}
	if(document.getElementById('ref_no').value == ""){
		theMessage = theMessage + "\n -->  กรุณาป้อนเลขอ้างอิงเอกสาร";		
		
	}
	if(document.getElementById('date_ref_no').value == ""){
		theMessage = theMessage + "\n -->  กรุณาป้อนวันที่ของเอกสารอ้างอิง";		
		
	}

	if(document.getElementById('date_due').value == ""){
		theMessage = theMessage + "\n -->  กรุณาป้อนวันที่ครบกำหนดชำระ ";		
		
	}
	if(document.getElementById("creditor0").checked==true){
		if(document.getElementById('txt_creditor_out').value == ""){
			theMessage = theMessage + "\n -->  กรุณาป้อนเจ้าหนี้";			
		}
	}
	else{
		if(document.getElementById('txt_creditor').value == ""){
			theMessage = theMessage + "\n -->  กรุณาป้อนเจ้าหนี้";			
		}	
	}
	return theMessage;	
	
}
</script>