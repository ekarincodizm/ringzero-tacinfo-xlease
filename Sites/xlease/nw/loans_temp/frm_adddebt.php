<?php
?>
<fieldset align="center" style="width:70%"><legend><B>รายการตั้งหนี้</B></legend>

<center>
	<div align="center"><font color="red"><b>หมายเหตุ </b>: รายการที่ไม่มี VAT ระบบจะจัดสรรเรื่อง VAT ให้อัตโนมัติ </font><br><br></div>
	<input  type="button" value="+ เพิ่ม" id="addShare" >
	<input  type="button" value="- ลบ" id="removeShare"></center>	
	<table width="100%" align="center" bgcolor="#F0F0F0">
	<tr bgcolor="#FFFFFF">			
		<div id="ShareGroup">
			<div id='ShareDiv'>
			</div>
		</div>
	</tr>
	</table>
	<input type="hidden" name="rowShare" id="rowShare" value="0">
<center>
<script type="text/javascript">
function changbox(no){
	if(document.getElementById('chk'+no).checked == false)
	{	document.getElementById('chk'+no).checked = false;
		var newShareBoxDiv = $(document.createElement('div')).attr("id", 'ShareDiv' + no);
			table1 =  '<input type="text" id="datepicker2'+ no +'" name="datepicker2'+ no +'" value="<?php echo nowDate()?>" size="15" readonly="true" style="text-align:center">';
			newShareBoxDiv.html(table1);
			newShareBoxDiv.appendTo("#datepk"+no);
			$("#datepicker2" + no).datepicker({
				showOn: 'button',
				buttonImage: 'images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
			});			
	}
	else{
	document.getElementById('chk'+no).checked = true;
	var data ="";	
	$("#datepk"+no).html(data);
	}
}
	var nubShare = 0;	
	//กดปุ่ม ลบ
	$("#removeShare").click(function(){
		if(nubShare==0){
            document.getElementById("tableShare").style.visibility = 'hidden';
			document.frm1.ShareName1.value = "";
			document.frm1.ShareSen1.value = "";
        }
        if(nubShare==0){
			document.getElementById("rowShare").value = nubShare;
            return false;
        }
        $("#ShareDiv" + nubShare).remove();
        nubShare--;
        console.log(nubShare);
        updateSummary();		
		document.getElementById("rowShare").value = nubShare;
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
			var datepk="datepk"+nubShare;
			table =  '<table width="70%" id="tableShare" cellSpacing="1" cellPadding="2" border="0" bgcolor="#FFCCCC" align="center" >'
					
			+ '<tr bgcolor="#FFCECE">'
			+ '		<td width="100" align="right"><b>ประเภทหนี้</b><font color="red" >*</font></td>'
			+ '		<td width="10"><b>:</b></td>'
			+ '		<td bgcolor="#FFE8E8">'			
			+ '			<select name="fpayid'+ nubShare +'" id="fpayid'+ nubShare +'">'
			+ '			<option value="">-เลือกประเภท-</option>'
			+ '			<?php
							
							$qrytype=pg_query("select * from account.\"thcap_typePay\" where \"tpConType\"='$contype' and \"ableInvoice\"='1' ");
							while($restype=pg_fetch_array($qrytype)){
								$tpID=$restype["tpID"];
								$tpDesc=$restype["tpDesc"];
							echo "<option value=$tpID>$tpDesc</option>";
						}?>'
			+ '			</select>'
			+ '		</td>'
			+ '	<td align="right"><b>เลขอ้างอิงหนี้</b><font color="red" >*</font></td>'
			+ '	<td width="10"><b>:</b></td>'
			+ '	<td bgcolor="#FFE8E8"><input type="text" name="fpayrefvalue'+ nubShare +'" id="fpayrefvalue'+ nubShare +'"></td>'
			+ '	<td align="right"><b>วันที่ครบกำหนด</b></td>'
			+ '	<td width="10"><b>:</b></td>'
			+ '	<td bgcolor="#FFE8E8"><div id="'+datepk+'"><input type="text" id="datepicker2'+ nubShare +'" name="datepicker2'+ nubShare +'" value="<?php echo nowDate()?>" size="15" readonly="true" style="text-align:center"></div></td>'
			+ '	<tr bgcolor="#FFCECE">'
			+ '		<td align="right"><b>จำนวนเงิน</b></td>'
			+ '		<td width="10"><b>:</b></td>'
			+ '		<td bgcolor="#FFE8E8" width="150"><input type="text" name="fpayamp'+ nubShare +'" id="fpayamp'+ 
			nubShare+ '" onKeyUp="dokeyup(this,event);" '
			+ '	    onChange="dokeyup(this,event);" value="0.00"></td>'
			+ '		<td colspan="3"><input type="radio" name="vat_inc'+ nubShare +'" id="vat_inc1'+ nubShare +'" value="1" checked>รวม VAT '
			+ '		<input type="radio" name="vat_inc'+ nubShare +'" id="vat_inc2'+ nubShare +'" value="2" >ไม่รวม VAT</td>'
			+ '		<input type="hidden" name="countrow" id="countrow" value="'+nubShare+'" >'
			+ '	<td></td><td><input type=\"checkbox\" name="chk'+ nubShare +'" id="chk'+ nubShare +'" onChange=changbox('+nubShare+')></td><td>ไม่ระบุวันที่ครบกำหนด</td></tr>'
			+ '	<td align="right"><b>เหตุผล</b></td>'
			+ '		<td width="10"><b>:</b></td>'
			+ '		<td colspan="7" bgcolor="#FFE8E8" ><textarea cols="110" rows="2" name="remark'+ nubShare +'" id="remark'+ nubShare +'" ></textarea></td><table bgcolor="#FFFFFF" height="20"><tr></tr></table>'
			+ '	</table>'
			newShareBoxDiv.html(table);
			newShareBoxDiv.appendTo("#ShareGroup");				
			document.getElementById("rowShare").value = nubShare;
			
			$("#datepicker2" + nubShare).datepicker({
				showOn: 'button',
				buttonImage: 'images/calendar.gif',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd'
			});
		}
    });
</script>
</html>		
	