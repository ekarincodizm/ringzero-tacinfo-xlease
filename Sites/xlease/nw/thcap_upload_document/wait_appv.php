<fieldset>
<legend><b>รายการรอตรวจสอบเอกสารสัญญาที่ upload</b></legend>
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
				<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
					<td>ลำดับที่</td>
					<td>เลขที่สัญญา</td>
					<td>ประเภทสัญญา</td>
					<td>ชื่อเอกสาร</td>
					<td>ผู้ทำรายการ</td>
					<td>วันที่ทำรายการ</td>
					<td>สถานะ</td>
					<td>ทำรายการ</td>
				<tr>
				<?php
					$qry_appv = pg_query("select a.*,b.\"conType\" from thcap_upload_document as a 
										left join thcap_contract as b on a.\"contractID\" = b.\"contractID\" 
										where \"Approved\" = '2' order by a.\"up_doerStamp\" ASC ");
					$nub=0;
					while($res_appv = pg_fetch_array($qry_appv)){
						$nub++;
						$up_autoID = $res_appv['up_autoID'];
						$contractId = $res_appv['contractID'];
						$docTypename = $res_appv['docTypename'];
						$conType = $res_appv['conType'];
						$up_doerID = $res_appv['up_doerID'];
						$up_doerStamp = $res_appv['up_doerStamp'];
						$pathfile = $res_appv['pathFile'];
						$add_or_edt = $res_appv['add_or_edit'];
						
						if($add_or_edt>0){
							$textstatus = "รออนุมัติ upload แก้ไข";
						} else {
							$textstatus = "รออนุมัติ upload ใหม่";
						}
						//ชื่อผู้ทำรายการ
						$qry_doername = pg_query("select fullname from \"Vfuser\" where id_user = '$up_doerID' ");
						$doc_DoerName=pg_fetch_result($qry_doername,0);
						//สลับสีแถว
						if($nub%2==0){
						echo "<tr class=\"odd\" align=center>";
						} else {
						echo "<tr class=\"even\" align=center>";
						}
							echo "<td>$nub</td>";
							echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractId','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$contractId</u></font></td>";
							echo "<td>$conType</td>";
							echo "<td>$docTypename</td>";
							echo "<td>$doc_DoerName</td>";
							echo "<td>$up_doerStamp</td>";
							echo "<td>$textstatus</td>";
							echo "<td><a href=\"../upload/document_contract/$pathfile\" TARGET=\"_blank\"><img src=\"images/detail.gif\"></a></td>";
						echo "</tr>";
					} // end while
					if($nub == 0){
						echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
						}
				?>
							<tr bgcolor="#6699FF">
								<td colspan="12" align="left"><b>รายการทั้งหมด <?php echo $nub;?> รายการ<b></td>
							</tr>
			</table>
</fieldset>