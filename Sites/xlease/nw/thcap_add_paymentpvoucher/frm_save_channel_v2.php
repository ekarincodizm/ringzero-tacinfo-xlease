<script language="JavaScript" type="text/JavaScript">
var gFiles_C=0;
var show_show11='0';
var sum = 0.00;
$(document).ready(function(){
	$("#show11").hide(); //ซ่อนส่วนที่ต้องเลือกให้แสดงหลังจากเลือก ช่องทางการจ่าย  แล้ว isTranPay=1
	$("#show21").hide(); //ซ่อนส่วนที่ต้องกรอกเพิ่มให้แสดงหลังจากเลือก ช่องทางการจ่าย  แล้ว isTranPay=1 และเลือกคืนเช็ค
	$("#divTranToCus_have").hide();
	$("#divTranToCus_no").hide();
	$("#divChqCus_have").hide();
	$("#divChqCus_no").hide();	

	$("#returnChqDate").datepicker({
        showOn: 'button',
        buttonImage: '<?php echo $rootpath; ?>'+'nw/thcap/images/calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	
	$("#proviso1").click(function(){
		$("#show21").hide(); 
		$("#show31").show();
		$('#returnTranToCus').focus();
	});
	
	$("#proviso2").click(function(){
		$("#show21").show(); 
		$("#show31").hide(); 
		$('#returnChqNo').focus();
	});
	
});
function highlightautocomplete() {
 
    var oldFn = $.ui.autocomplete.prototype._renderItem;
	$.ui.autocomplete.prototype._renderItem = function( ul, item) {
	var t = String(item.value).replace(
    new RegExp(this.term, "gi"),"<span class='ui-state-highlight'>$&</span>");
	return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + t + "</a>" )
            .appendTo( ul );
    };
 }
  
$(function() { 
    function log( message ) {
        $( "<div>" ).text( message ).prependTo( "#log" );
        $( "#log" ).scrollTop( 0 );
        }
	highlightautocomplete();
	//ค้นหารหัสและชื่อลูกค้า สำหรับ คืนเงินบุคคลภายนอก คืนโดยโอนธนาคาร
    $( "#returnTranToCus_have" ).autocomplete({
        source: '<?php echo $rootpath; ?>'+"nw/thcap_dncn/s_customer.php",
        minLength: 1,
        select: function( event, ui ) {
            log( ui.item ?
            "Selected: " + ui.item.value + " aka " + ui.item.id :
            "Nothing selected, input was " + this.value );
        }
    });
	
	//ค้นหารหัสและชื่อลูกค้า สำหรับ คืนเงินบุคคลภายนอก คืนโดยเช็ค
	$("#returnChqCus_have").autocomplete({
		source: '<?php echo $rootpath; ?>'+"nw/thcap_dncn/s_customer.php",
        minLength:1
    });
	
});

//function สำหรับตรวจสอบว่า "public"."BankInt"."isTranPay" = 1 หรือไม่
function checkTranPay(){
	//กรณีมีการเลือกช่องทางการจ่าย
	if($("#byChannel").val() != "" ){		
		//ส่งช่องทางที่เลือกไปตรวจสอบ
		$.get('<?php echo $rootpath; ?>'+'nw/thcap_dncn/process_chktranpay.php?BID='+ $("#byChannel").val(), function(data){
			if(data==1){
				show_show11='show';
				$("#show11").show(); 
				$("#show31").show();
				$("#show21").hide(); //ยังไม่ให้โชว์ส่วนระบุเลขที่เช็คต้องรอให้เลือกก่อน
				$('#proviso1').attr('checked', 'checked'); //defult ให้เลือก "คืนโดยโอนธนาคาร"
				$('#returnTranToCus').focus();
				
				//เคลียร์ค่าที่กรอกค้างไว้ (เลขที่เช็คและวันที่บนเช็ค)
				$('#returnChqNo').val(''); //เลขที่เช็ค
				$('#returnChqDate').val(''); //วันที่บนเช็ค
				$('#returnTranToCus').val(''); //เจ้าของบัญชี
				$('#returnTranToBank').val(''); //รหัสธนาคาร
				$('#returnTranToBank').val(''); //เลขที่บัญชีปลายทาง
			}else{
				show_show11='hide';
				$("#show11").hide();
				$("#show21").hide(); //ยังไม่ให้โชว์ส่วนระบุเลขที่เช็คต้องรอให้เลือกก่อน
				
				//เคลียร์ค่าที่กรอกค้างไว้ (เลขที่เช็คและวันที่บนเช็ค)
				$('#returnChqNo').val('');
				$('#returnChqDate').val('');
			}
			$('#tranpay').val(data);
		});
	}else{
		$("#show11").hide();
		$("#show21").hide();
		$('#tranpay').val('');
	}
}
function check_customer(){
	var arr =$('#returnTranToCus').val();
    var id=arr.split("#"); 

	$('#chkcustomer').load('<?php echo $rootpath ?>'+'nw/thcap/check.php?chk=customer&id='+id[0]);
}
function chkCus1()
{
	if(document.getElementById('someOne11').checked == true)
	{	
		$("#divTranToCus_have").show();
		$("#divTranToCus_no").hide();
	}
	else if(document.getElementById('someOne12').checked == true)
	{
		$("#divTranToCus_have").hide();
		$("#divTranToCus_no").show();
	}
	else
	{
		$("#divTranToCus_have").hide();
		$("#divTranToCus_no").hide();
	}
}
//คืนโดยเช็ค เมื่อกด radio ออกเช็คให้ :
function chkCus2()
{
	if(document.getElementById('someOne21').checked == true)
	{
		$("#divChqCus_have").show();
		$("#divChqCus_no").hide();
	}
	else if(document.getElementById('someOne22').checked == true)
	{
		$("#divChqCus_have").hide();
		$("#divChqCus_no").show();
	}
	else
	{
		$("#divChqCus_have").hide();
		$("#divChqCus_no").hide();
	}
}
function chk_inputdata_save_channel_v2(){
	var theMessage="";
	if(document.getElementById("noaddFile").value < 1 ){	
		theMessage ="\n -->  กรุณา ทำรายการ ช่องทางการจ่าย";
	}
	return theMessage;
}

function chk_addFile_C(){
		var theMessage="";		
		//ส่วนของ ช่องทางการจ่าย 
		if(document.getElementById("byChannel").value==""){			
			theMessage = theMessage + "\n -->  กรุณาระบุช่องทางการจ่าย";
			
		}
		//จำนวนเงินที่จ่ายผ่านช่องทางนี้ :
		if($('#payamt').val()==""){
			theMessage = theMessage + "\n -->  กรุณาระบุจำนวนเงินที่จ่ายผ่านช่องทางนี้";					
		}
		else{
			if (parseFloat($('#payamt').val())<=0)
			{ 	theMessage = theMessage + "\n -->  กรุณาระบุจำนวนเงินที่จ่ายผ่านช่องทางนี้ ตั้งแต่ 0 บาทเป็นต้นไป";	
			}
		}
		//กรณี "isTranPay" = 1 ต้องตรวจสอบเพิ่มเติม
		if($('#tranpay').val()==1)
		{   
			//ถ้าเลือกคืนโดยเงินโอนจะต้องกรอกข้อมูลให้สมบูรณ์
			if($('input:radio[name=proviso_return]:checked').val() == 1)
			{
			
				if(document.getElementById("someOne11").checked==false && document.getElementById("someOne12").checked==false)
				{
					theMessage = theMessage + "\n -->  กรุณาระบุเจ้าของบัญชี";
					
				}
				else if(document.getElementById("someOne11").checked == true && document.getElementById("returnTranToCus_have").value == "")
				{
					theMessage = theMessage + "\n -->  กรุณาระบุชื่อเจ้าของบัญชี";
					
				}
				else if(document.getElementById("someOne12").checked == true && document.getElementById("returnTranToCus_no").value == "")
				{
					theMessage = theMessage + "\n -->  กรุณาระบุชื่อเจ้าของบัญชี";
					
				}
				else
				{
					//ถ้าไม่ว่างต้องตรวจสอบข้อมูลว่างทีระบุนั้นถูกต้องตามระบบกำหนดหรือไม่
					if($('#cusid').val()=='no'){
						theMessage = theMessage + "\n -->  กรุณาระบุเจ้าของบัญชีให้ถูกต้องตามที่ระบบกำหนด";
					
					}
				}
				
				if($('#returnTranToBank').val()==""){
					theMessage = theMessage + "\n -->  กรุณาระบุรหัสธนาคาร";
					
				}else{
					//ถ้าไม่ว่างต้องตรวจสอบข้อมูลว่างทีระบุนั้นถูกต้องตามระบบกำหนดหรือไม่
					if($('#bankid').val()=='no'){
						theMessage = theMessage + "\n -->  กรุณาระบุรหัสธนาคารให้ถูกต้องตามที่ระบบกำหนด";
						
					}
				}
				
				if($('#returnTranToAccNo').val()==""){
					theMessage = theMessage + "\n -->  กรุณาระบุเลขที่บัญชีปลายทาง";
					
				}
			}
			else
			{ //ถ้าเลือกคืนโดยเช็คจะต้องกรอกเลขที่เช็คและวันที่บนเช็คให้สมบูรณ์
				if($('#returnChqNo').val()==""){
					theMessage = theMessage + "\n -->  กรุณาระบุเลขที่เช็ค";
					
				}
				
				if($('#returnChqDate').val()==""){
					theMessage = theMessage + "\n -->  กรุณาระบุวันที่บนเช็ค";
					
				}
				
				if(document.getElementById("someOne21").checked==false && document.getElementById("someOne22").checked==false)
				{
					theMessage = theMessage + "\n -->  กรุณาระบุออกเช็คให้";
					
				}
				else if(document.getElementById("someOne21").checked == true && document.getElementById("returnChqCus_have").value == "")
				{
					theMessage = theMessage + "\n -->  กรุณาระบุออกเช็คให้ใคร";
					
				}
				else if(document.getElementById("someOne22").checked == true && document.getElementById("returnChqCus_no").value == "")
				{
					theMessage = theMessage + "\n -->  กรุณาระบุออกเช็คให้ใคร";
					
				}
			}	
		}
	return theMessage;

}





function addFile_C(){	
	var theMessage=chk_addFile_C();
	
	//1.ตรวจสอบว่าคีย์ข้อมูลครบหรือไม่
	if(theMessage==""){
		//2.ทำการเพิ่มข้อมูลในตาราง
		gFiles_C++;
		var li = document.createElement('div');
		li.setAttribute('id', 'file_C-' + gFiles_C);
		li.innerHTML = '<table><tr height="22" bgcolor="#6699FF"><td width="1430"><B>  ช่องทางการจ่าย ที่ '+ gFiles_C+'.</B></td></tr></table><div align="left"><input type="text" size="40" value="ช่องทางการจ่าย:" ><input type="text" name="array_fromChannel[]" id=array_fromChannel'+ gFiles_C+' size="40" readonly><input type="text" size="40" value="คืนโดย:" ><input type="text" name="array_proviso_return[]" id=array_proviso_return'+ gFiles_C+' size="40" readonly><input type="text" size="40" value="เจ้าของบัญชี:" ><input type="text" name="array_returnTranToCus[]" id=array_returnTranToCus'+ gFiles_C+' size="40" readonly><input type="text" size="40" value="รหัสธนาคาร:"><input type="text" name="array_returnTranToBank[]" id=array_returnTranToBank'+ gFiles_C+' size="40" readonly><input type="text" size="40" value="เลขที่บัญชีปลายทาง :" ><input type="text"name="array_returnTranToAccNo[]"id=array_returnTranToAccNo'+ gFiles_C+' size="40" readonly><input type="text" size="40" value="ออกเช็คให้:" ><input type="text" name="array_returnChqCus[]" id=array_returnChqCus'+ gFiles_C+' size="40" readonly><input type="text" size="40" value="เลขที่เช็ค:" ><input type="text" name="array_returnChqNo[]" id=array_returnChqNo'+ gFiles_C+' size="40" readonly><input type="text" size="40" value="วันที่บนเช็ค:" ><input type="text" name="array_returnChqDate[]" id=array_returnChqDate'+ gFiles_C+' size="40" readonly><input type="text" size="40" value="จำนวนเงิน:" ><input type="text" name="array_payamt[]" id=array_payamt'+ gFiles_C+' size="40" readonly><span onclick="removeFile_C(\'file_C-' + gFiles_C + '\')" style="cursor:pointer;"><i><font color="red">-ลบ-</font></i></span></div>';
		document.getElementById('ch').appendChild(li);		
		insert_tb(gFiles_C);
    }
	else{
		alert(theMessage);
	}	
}
function clean_key(){	
	//$('#byChannel').val(''); //ช่องทางการจ่าย 
	$('#fromChannelType').val(''); //คืนโดย
	$('#someOne11').val(''); // radio เจ้าของบัญชี  มี
	$('#someOne12').val(''); // radio เจ้าของบัญชี ไม่ มี
	$('#returnTranToCus_have').val(''); // txt เจ้าของบัญชี  มี
	$('#returnTranToCus_no').val(''); 	// txt เจ้าของบัญชี  มี
	$('#returnTranToBank').val(''); //รหัสธนาคาร  โอน
	$('#returnTranToAccNo').val(''); //เลขที่บัญชีปลายทาง
	
	$('#someOne21').val(''); // radio ออกเช็คให้  มี
	$('#someOne22').val(''); // radio ออกเช็คให้  ไม่ มี
	$('#returnChqCus_have').val(''); 
	$('#returnChqCus_no').val(''); 
	
 	$('#returnChqNo').val(''); //เลขที่เช็ค
	$('#returnChqDate').val(''); //วันที่บนเช็ค
	$('#payamt').val(''); //จำนวนเงินที่จ่ายผ่านช่องทางนี้
	if('<?php echo $frm_send;?>'=='payment_debtor_creditors_cost'){

		if(gFiles_C==1){			
			$("#btn_addchan").hide();			
		}
		else{
			$("#btn_addchan").show();	
		}
	
	}
}
function removeFile_C(aId) {
//กด ลบรายการนี้ ที่ตารางที่ ch	
	--gFiles_C;
	document.getElementById("noaddFile").value = gFiles_C;
    var obj = document.getElementById(aId);
    obj.parentNode.removeChild(obj);
	if('<?php echo $frm_send;?>'=='payment_debtor_creditors_cost'){

		if(gFiles_C==1){			
			$("#btn_addchan").hide();			
		}
		else{
			$("#btn_addchan").show();	
		}	
	}
	
	sum=0.00;
	for (i=1;i<=gFiles_C;i++){	
		
		//เก็บข้อมูลรวม
		sum = sum + parseFloat($("#array_payamt"+i).val());		
		
	}
	
	document.getElementById("txt_amountaddchan").value = sum;
}
function insert_tb(c_no){
	document.getElementById("noaddFile").value = c_no;
	$("#array_fromChannel"+c_no).val($("#byChannel").val());	
	if(show_show11 =='show'){
	if(document.getElementById("proviso1").checked==true){
		$("#array_proviso_return"+c_no).val($("#proviso1").val());
	}
	else if(document.getElementById("proviso2").checked==true){
		$("#array_proviso_return"+c_no).val($("#proviso2").val());
	}
	}
	
    if(document.getElementById("someOne11").checked==true){
		$("#array_returnTranToCus"+c_no).val($("#returnTranToCus_have").val());
	}
	else if(document.getElementById("someOne12").checked==true){
		$("#array_returnTranToCus"+c_no).val($("#returnTranToCus_no").val());
	}	
	
	
	$("#array_returnTranToBank"+c_no).val($("#returnTranToBank").val());
	$("#array_returnTranToAccNo"+c_no).val($("#returnTranToAccNo").val());
	
	if(document.getElementById("someOne21").checked==true){
		$("#array_returnChqCus"+c_no).val($("#returnChqCus_have").val());	
	}
	else if(document.getElementById("someOne22").checked==true){
		$("#array_returnChqCus"+c_no).val($("#returnChqCus_no").val());	
	}	
	
	
	$("#array_returnChqNo"+c_no).val($("#returnChqNo").val());
	$("#array_returnChqDate"+c_no).val($("#returnChqDate").val());
	
	$("#array_payamt"+c_no).val($("#payamt").val());
	
	//เก็บข้อมูลรวม
	sum = sum + parseFloat($("#payamt").val());
	document.getElementById("txt_amountaddchan").value = sum;
}
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

</script>

<table  width="40%" border="0" cellSpacing="1" cellPadding="2" align="center">
<tr id="loadrefund">
	<td colspan="2">
		<div><b>ช่องทางการจ่าย : </b>
		<select name="byChannel" id="byChannel" onchange="javascript:checkTranPay()">
			<option value="">--เลือก--</option>
			<?php
			$qry=pg_query("select \"BID\",\"BAccount\"||'-'||\"BName\" from \"BankInt\" where \"isPayChannel\"='1' order by \"BID\"");
			while($res=pg_fetch_array($qry)){
				list($bid,$bankname)=$res;
				echo "<option value=$bid>$bankname</option>";
			}
			?>
		</select><span style="color:red;"><b>*</b></span>
		<input type="hidden" name="tranpay" id="tranpay">
		</div>
		<div id="show11" style="background-color:#FFCCCC;padding:5px;width:450px;border:1px dashed #FF6A6A">
			<div><b>ระบุข้อมูลเพิ่มเติม</b>
			</div>
			<div><input type="radio" name="proviso_return" id="proviso1" value="1" checked>คืนโดยโอนธนาคาร</div>
			<div id="show31" style="background-color:#FFFFE0;padding:5px;width:430px;font-weight:bold;border:1px dashed #FF6A6A">
			<div>
				เจ้าของบัญชี : <span style="color:red;">*</span>
				<input type="radio" name="someOne1" id="someOne11" value="have" onChange="chkCus1()"> มีข้อมูลในระบบ 
				<input type="radio" name="someOne1" id="someOne12" value="no" onChange="chkCus1()"> ไม่มีข้อมูลในระบบ 
				<div id="divTranToCus_have"><input type="text" name="returnTranToCus_have" id="returnTranToCus_have" size="55" onfocus="check_customer();" onblur="check_customer();" onkeypress="check_customer();"><span id="chkTranHave" style="color:red;">*</span></div>
				<div id="divTranToCus_no"><input type="text" name="returnTranToCus_no" id="returnTranToCus_no" size="55"><span id="chkTranNo" style="color:red;">*</span></div>
			</div>
			<div id="chkcustomer"></div>
			<div>
				รหัสธนาคาร : 				
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
			<div id="show21" style="background-color:#FFFFE0;padding:5px;width:430px;font-weight:bold;border:1px dashed #FF6A6A">
			<div>
				ออกเช็คให้ : <span style="color:red;">*</span>
				<input type="radio" name="someOne2" id="someOne21" value="have" onChange="chkCus2()"> มีข้อมูลในระบบ <input type="radio" name="someOne2" id="someOne22" value="no" onChange="chkCus2()"> ไม่มีข้อมูลในระบบ 
				<div id="divChqCus_have"><input type="text" name="returnChqCus_have" id="returnChqCus_have" size="55" onfocus="check_customer();" onblur="check_customer();" onkeypress="check_customer();"><span style="color:red;">*</span></div>
				<div id="divChqCus_no"><input type="text" name="returnChqCus_no" id="returnChqCus_no" size="55"><span style="color:red;">*</span></div>
			</div>
			<div id="chkcustomer_ChqCus"></div>
			<div>เลขที่เช็ค : <input type="text" name="returnChqNo" id="returnChqNo"> วันที่บนเช็ค : <input type="text" name="returnChqDate" id="returnChqDate" size="10"><font color="red"><b>*</b></font></div>
			</div>
		</div>
		<div>จำนวนเงินที่จ่ายผ่านช่องทางนี้ :<input type="text" id="payamt" name="payamt" value="" onkeypress="check_num(event);" size="45"><span style="color:red;">*</span> บาท</div>
	</td>
	<td>
		<input type="button" value="เพิ่มรายการ"  id="btn_addchan" name="btn_addchan" onclick= "addFile_C();clean_key();">
		<input name="txt_amountaddchan"  id="txt_amountaddchan" size="30" hidden>
	</td>
</tr>
</table>

