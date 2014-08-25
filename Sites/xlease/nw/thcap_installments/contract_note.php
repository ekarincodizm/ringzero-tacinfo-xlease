<?php 
if($contractID != "") // ถ้ามีการส่งค่ามา  // header
{
	$qry_note_invoice = pg_query("select \"noteDetail\" from \"thcap_contract_note\" where \"contractID\" = '$contractID' and \"noteType\" = '1'
									and \"noteID\" = (select max(\"noteID\") from \"thcap_contract_note\" where \"contractID\" = '$contractID' and \"Approved\" = 'TRUE') ");
	$noteDetail = pg_fetch_result($qry_note_invoice,0);
if($frm_add_call==true){
	$call_edit = "../../thcap_installments/frm_edit_note_invoice.php";
	$call_detail = "../../thcap_installments/frm_note_invoice_history.php";
} else {
	$call_edit = "frm_edit_note_invoice.php";
	$call_detail = "frm_note_invoice_history.php";
}
?>
	<fieldset>
		<legend><B>หมายเหตุ</B></legend>
		<center>
			<table>
				<tr>
					<td><b>หมายเหตุการวางบิล/ใบแจ้งหนี้ :</b></td>
					<td><textarea rows="3" cols="70" readOnly style="background-color:#CCCCCC;"><?php echo $noteDetail; ?></textarea></td>
					<td>
						<table>
							<tr bgcolor="#79BCFF">
								<td align="center"><b>แก้ไข</b></td>
								<td align="center"><b>ประวัติ</b></td>
							</tr>
							<tr bgcolor="#D5EFFD">
								<td align="center"><img src="images/edit.png" width="19" height="19" onclick="javascript:popU('<?php echo $call_edit;?>?contractID=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=500')" style="cursor:pointer;" /></td>
								<td align="center"><img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('<?php echo $call_detail;?>?contractID=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=700')" style="cursor:pointer;" /></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</center>
	</fieldset>
<?php
}
?>