<?php 
//include("../../config/config.php");
?>	
<script language="JavaScript" type="text/JavaScript">

function removeFile_C(aId) {
//กด ลบรายการนี้ ที่ตารางที่ ch
	--gFiles_C;
	document.getElementById("noaddFile").value = gFiles_C;
    var obj = document.getElementById(aId);
    obj.parentNode.removeChild(obj);
	
}
function chk_addFile_C(){
	var chk_false=0;
	if($("#fromChannel").val()=='')	{ chk_false++;}
	if($("#fromChannelType").val()=='')	{ chk_false++;}
	if($("#fromChannelRef").val()=='')	{ chk_false++;}
	if($("#withMedium").val()=='')	{ chk_false++;}
	if($("#withMediumType").val()=='')	{ chk_false++;}
	if($("#withMediumRef").val()=='')	{ chk_false++;}
	if($("#toChannel").val()=='')	{ chk_false++;}
	if($("#toChannelType").val()=='')	{ chk_false++;}	
	if($("#toChannelRef").val()=='')	{ chk_false++;}
	if($("#ChannelAmt").val()=='')	{ chk_false++;}
	if($("#voucherChannelSubGroup").val()=='')	{ chk_false++;}
	
	if(chk_false==0){ return true;}
	else{	return false;}
}

function addFile_C(){
	
	//กด เพิ่มรายการ ในการบันทึเอง
	//1.ตรวจสอบว่าคีย์ข้อมูลครบหรือไม่
	if(chk_addFile_C()){
		//2.ทำการเพิ่มข้อมูลในตาราง
		gFiles_C++;
		var li = document.createElement('div');
		li.setAttribute('id', 'file_C-' + gFiles_C);
		li.innerHTML = '<table><tr height="22" bgcolor="#6699FF"><td width="1220"><B>  Channel ที่ '+ gFiles_C+'.</B></td></tr></table><div align="left"><input type="text" size="22" value="ช่องทางที่เงินออก:" style="border: none"><input type="text" name="array_fromChannel[]" id=array_fromChannel'+ gFiles_C+' size="28" readonly><input type="text" size="22" value="ประเภทแหล่งเงินออก:" style="border: none"><input type="text" name="array_fromChannelType[]" id=array_fromChannelType'+ gFiles_C+' size="28" readonly><input type="text" size="22" value="ref ของช่องทาง(from):" style="border: none"><input type="text" name="array_fromChannelRef[]" id=array_fromChannelRef'+ gFiles_C+' size="28" readonly><input type="text" size="22" value="ช่องทางการคืนเงิน:" style="border: none"><input type="text" name="array_withMedium[]" id=array_withMedium'+ gFiles_C+' size="28" readonly><input type="text" size="22" value="ประเภทวิธีการชำระเงิน:" style="border: none"><input type="text"name="array_withMediumType[]"id=array_withMediumType'+ gFiles_C+' size="28" readonly><input type="text" size="22" value="Ref วิธีการคืน:" style="border: none"><input type="text" name="array_withMediumRef[]" id=array_withMediumRef'+ gFiles_C+' size="28" readonly><input type="text" size="22" value="ช่องทางการรับเงิน:" style="border: none"><input type="text" name="array_toChannel[]" id=array_toChannel'+ gFiles_C+' size="28" readonly><input type="text" size="22" value="ประเภทข้อมูลระบุประเภท:" style="border: none"><input type="text" name="array_toChannelType[]" id=array_toChannelType'+ gFiles_C+' size="28" readonly><input type="text" size="22" value="ref ของช่องทาง(to):" style="border: none"><input type="text" name="array_toChannelRef[]" id=array_toChannelRef'+ gFiles_C+' size="28" readonly><input type="text" size="22" value="จำนวนเงินที่จ่าย:" style="border: none"><input type="text" name="array_ChannelAmt[]" id=array_ChannelAmt'+ gFiles_C+' size="28" readonly><input type="text" size="22" value="กลุ่มรายการย่อย:" style="border: none"><input type="text" name="array_voucherChannelSubGroup[]" id=array_voucherChannelSubGroup'+ gFiles_C+' size="28" readonly><span onclick="removeFile_C(\'file_C-' + gFiles_C + '\')" style="cursor:pointer;"><i><font color="red">- ลบรายการนี้ -</font></i></span></div>';
		document.getElementById('ch').appendChild(li);		
		insert_tb(gFiles_C);
    }
	else{
		alert('กรุณากรอกข้อมูลให้ครบก่อนค่ะ');
	}
	
}

function chk_u_keyfrom(){
	//ข้อความสีแดงให้ user key
	var ukey = $('#fromChannelType').val();		
	if(ukey!=""){
		ukey_v = ukey.split("#");
		$("#u_keyfrom").css('color','#ff0000');
		$("#u_keyfrom").html('('+ ukey_v[1] +')');		
	}
}
function chk_u_keyto(){
	//ข้อความสีแดงให้ user key
	var to_ukey = $('#toChannelType').val();		
	if(to_ukey!=""){
		to_ukey = to_ukey.split("#");
		$("#u_keyto").css('color','#ff0000');
		$("#u_keyto").html('('+ to_ukey[1] +')');		
	}
}
function clean_key(){
	document.add_acc.fromChannelRef.value="";	
	document.add_acc.withMediumRef.value="";	
	document.add_acc.toChannelRef.value="";	
	document.add_acc.ChannelAmt.value="";	
	document.add_acc.voucherChannelSubGroup.value="";		
	$("#u_keyto").html('');
	$("#u_keyfrom").html('');
}

function insert_tb(c_no){
	document.getElementById("noaddFile").value = c_no;
	$("#array_fromChannel"+c_no).val($("#fromChannel").val());	
	$("#array_fromChannelType"+c_no).val($("#fromChannelType").val());
	$("#array_fromChannelRef"+c_no).val($("#fromChannelRef").val());
	$("#array_withMedium"+c_no).val($("#withMedium").val());
	$("#array_withMediumType"+c_no).val($("#withMediumType").val());
	$("#array_withMediumRef"+c_no).val($("#withMediumRef").val());	
	$("#array_toChannel"+c_no).val($("#toChannel").val());
	$("#array_toChannelType"+c_no).val($("#toChannelType").val());	
	$("#array_toChannelRef"+c_no).val($("#toChannelRef").val());
	$("#array_ChannelAmt"+c_no).val($("#ChannelAmt").val());
	$("#array_voucherChannelSubGroup"+c_no).val($("#voucherChannelSubGroup").val());
}
</script>
<table  width="100%" border="0" cellSpacing="1" cellPadding="2" align="center">
<tr><td align="right">ช่องทางที่เงินออก:</td>
        <td><select name="fromChannel" id="fromChannel" OnChange="selectChannel();">
			<option value="<?php echo ""; ?>" ><?php echo "-- กรุณาเลือกช่องทาง--"; ?></option>
			</select>
		</td>
</tr>
<tr><td align="right">ประเภทแหล่งเงินออก:</td><td>
<select   name="fromChannelType"  id="fromChannelType" OnChange="chk_u_keyfrom();">
	<option value="<?php echo ""; ?>" ><?php echo "-- กรุณาเลือกประเภทข้อมูล--"; ?></option>
</select></td>
</tr>
<tr><td align="right">Ref ของช่องทาง(from):</td><td><input  name="fromChannelRef"  id="fromChannelRef" size="54">
<span id="u_keyfrom" name="u_keyfrom"></span></td></tr>
<tr><td align="right">ช่องทางการคืนเงิน:</td>
<td>
<select name="withMedium" id="withMedium">

</select>
</td>
</tr>
		
<tr><td align="right">ประเภทวิธีการชำระเงิน:</td><td>
<select  name="withMediumType"  id="withMediumType">
<?php 	
$qry_GenType = pg_query("select * from \"thcap_temp_voucher_reftype\" order by \"voucher_reftype_id\" ");
while($res_gentype=pg_fetch_array($qry_GenType)){
$reftype_id = $res_gentype["voucher_reftype_id"];
$reftype_name= $res_gentype["voucher_reftype_name"];			
?>
<option value="<?php echo "$reftype_id"."#"."$reftype_name"; ?>"><?php echo "$reftype_id"."#"."$reftype_name"; ?></option>
<?php } 
?>
</select></td></tr>
<tr><td align="right">Ref วิธีการคืน:</td><td><input  name="withMediumRef"  id="withMediumRef" size="54"></td></tr>

<tr><td align="right">ช่องทางการรับเงิน:</td><td>
	<select  name="toChannel"  id="toChannel" OnChange="selecttoChannel();" >
		<option value="<?php echo ""; ?>" ><?php echo "-- กรุณาเลือกช่องทาง--"; ?></option>
	</select>
	</td>
</tr>
<tr><td align="right">ประเภทข้อมูลระบุประเภท:</td><td>
<select   name="toChannelType"  id="toChannelType" OnChange="chk_u_keyto();">
	<option value="<?php echo ""; ?>" ><?php echo "-- กรุณาเลือกประเภทข้อมูล--"; ?></option>
</select></td></tr>
<tr><td align="right">Ref ของช่องทาง(to):</td><td><input  name="toChannelRef"  id="toChannelRef" size="54"><span id="u_keyto" name="u_keyto"></span></td></tr>
<tr><td align="right">จำนวนเงินที่จ่าย:</td><td><input  name="ChannelAmt"  id="ChannelAmt" size="54"></td>	</tr>
<tr><td align="right">กลุ่มรายการย่อย:</td><td><input  name="voucherChannelSubGroup"  id="voucherChannelSubGroup" size="54"></td></tr>	
<tr><td colspan="2" align="right" ><input type="button" value="เพิ่มรายการ"  id="btn_addchan" name="btn_addchan" onclick= "addFile_C();changepurpose();"></td></tr>	
</table>


