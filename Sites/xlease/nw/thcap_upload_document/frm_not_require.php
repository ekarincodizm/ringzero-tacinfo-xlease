<?php
if($conid!=""){
		$qry_con = pg_query(
							"select a.*,b.\"doc_docName\",b.\"doc_statusDoc\" from thcap_contract as a inner join thcap_contract_doc_config as b on a.\"conType\" = b.\"doc_conTypeName\" 
							where (a.\"contractID\" = '$conid') and (\"doc_statusDoc\"='2')
							and
							(a.\"contractID\" not in (select \"contractID\" from thcap_upload_document) 
							or (a.\"contractID\" in (select \"contractID\" from thcap_upload_document) and  b.\"doc_docName\" not in (select \"docTypename\" from thcap_upload_document where \"contractID\" = '$conid'))  
							or (a.\"contractID\" in (select \"contractID\" from thcap_upload_document) and  (b.\"doc_docName\" not in (select \"docTypename\" from thcap_upload_document where \"contractID\" = '$conid'and \"Approved\" in ('1','2') )) ))
							order by b.\"doc_Ranking\" ASC " 
							);
		$count = pg_num_rows($qry_con);
}	?>
<script language=javascript>
function validateFileExtension(fld) {
    if(!/(\.pdf)$/i.test(fld.value)) {
        alert("upload ได้เฉพาะไฟล์ pdf เท่านั้น");      
        fld.form.reset();
        fld.focus();        
        return false;   
    }   
    return true; 
}
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<script>
function checkfile(){
	var allrow = document.getElementById("countrow").value;
	var countfill = document.getElementById("countfill").value;
	var reason = 0;	
		for(var i=1;i<=allrow;i++){
			if(document.getElementById("file"+i).value!=""){
				countfill++;
				if(document.getElementById("note"+i).value!=""){
				reason++;
				}
			} 
		}
		if(countfill==0){
			alert('กรุณาเลือกไฟล์ upload ด้วย');
			return false;
		} else if(reason==0){
			alert('กรุณาระบุเหตุผลด้วย');
			return false;
		} else {
			document.getElementById("countfill").value=countfill;
		}
}
</script>
	<fieldset>
	<legend>รายการเอกสารที่สามารถ upload ได้เพิ่มเติม</legend>
		<form name="frmUpload" action="process_insert.php" method="post" enctype="multipart/form-data" onsubmit="return checkfile();">
		<table width="95%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td width="10%">ลำดับที่</td>
				<td width="30%">ชื่อเอกสาร</td>
				<td width="50%">หมายเหตุ</td>
				<td width="10%">ค้นหาไฟล์</td>
			</tr>
			<?php
				
				$nub_row=0;
				if($count>0){
					while($res_doc=pg_fetch_array($qry_con)){
					$nub_row++;
					$docName = $res_doc["doc_docName"];
					$conType = $res_doc["conType"];
					if($nub_row%2==0){
						echo "<tr class=\"odd\" align=center>";
					} else {
						echo "<tr class=\"even\" align=center>";
					}
						echo "<td>$nub_row</td>";
						echo "<td>$docName</td>";
						echo "<td><input type=\"text\" name=\"note$nub_row\" id=\"note$nub_row\" size=\"50\"></td>";
						echo "<td><input type=\"file\" name=\"file$nub_row\" id=\"file$nub_row\" size=\"20\" onchange=\"return validateFileExtension(this)\"></td>";
						echo "<input type=\"hidden\" name=\"docName$nub_row\" value=\"$docName\">";
						echo "<input type=\"hidden\" name=\"conType$nub_row\" value=\"$conType\">";
					echo "</tr>";
					}//while
				} else {
					echo "<tr>";
						echo "<td colspan=\"4\" align=center><b>ไม่พบรายการ <br></b></td>";
					echo "</tr>";
				}
			?>
			<tr>
				<input type="hidden" name="countrow" id="countrow" value="<?php echo $nub_row; ?>">
				<input type="hidden" name="countfill" id="countfill" value="0">
				<input type="hidden" name="contractID"  value="<?php echo $conid; ?>">
				<input type="hidden" name="notRequire"  value="Y">
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="submit" name="upload" value="upload"> 
				</td>
			</tr>
		</table>
		</form>
	</fieldset>
