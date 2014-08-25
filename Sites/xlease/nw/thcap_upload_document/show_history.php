<fieldset>
	<legend>
		<b>ประวัติตรวจสอบเอกสารสัญญา 30 รายการล่าสุด </b>
		<input type="button" value="ทั้งหมด" onclick="javascript:popU('show_all_history.php?','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650');" style="cursor:pointer">
	</legend>
	
	<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
				<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
					<td width="4%">ลำดับที่</td>
					<td width="12%">เลขที่สัญญา</td>
					<td width="8%">ประเภทสัญญา</td>
					<td width="8%">ชื่อเอกสาร</td>
					<td width="8%">ผู้ทำรายการ</td>
					<td width="8%">วันที่ทำรายการ</td>
					<td width="8%">ผู้ตรวจสอบ</td>
					<td width="8%">วันที่ตรวจสอบ</td>
					<td width="5%">ผลการอนุมัติ</td>
					<td width="5%">หมายเหตุ</td>
				</tr>
				
			<?php
				$qry_his = pg_query("select a.\"up_autoID\",a.\"contractID\",a.\"docTypename\",a.\"conType\",a.\"up_doerID\",a.\"up_doerStamp\",a.\"up_appvID\",a.\"up_appvStamp\",a.\"Approved\",b.\"conType\" from thcap_upload_document as a
									left join thcap_contract as b on a.\"contractID\" = b.\"contractID\"
									where a.\"Approved\" <> '2' 
									order by a.\"up_appvStamp\" DESC limit 30");
				$nubhis=0;
				while($res_his=pg_fetch_array($qry_his)){
					$nubhis++;
						$up_autoID = $res_his['up_autoID'];
						$contractID = $res_his['contractID'];
						$conType = $res_his['conType'];
						$docTypename = $res_his['docTypename'];
						$up_doerID = $res_his['up_doerID'];
						$up_doerStamp = $res_his['up_doerStamp'];
						$up_appvID = $res_his['up_appvID'];
						$up_appvStamp = $res_his['up_appvStamp'];
						$Approved = $res_his['Approved'];
						
					
						//ผลการอนุมัติ
						if($Approved==0){
							$textAppv = "ไม่อนุมัติ";
							$colorSF="#FF000";
						}else{
							$textAppv = "อนุมัติ";
							$colorSF="#00FF00";
						}
						//ชื่อผู้ทำรายการ
						$qry_doername = pg_query("select fullname from \"Vfuser\" where id_user = '$up_doerID' ");
						$up_DoerName=pg_fetch_result($qry_doername,0);
						//ชื่อผู้อนุมัติ
						$qry_doername = pg_query("select fullname from \"Vfuser\" where id_user = '$up_appvID' ");
						$up_appvName=pg_fetch_result($qry_doername,0);
						
						if($nubhis%2==0){
						echo "<tr class=\"odd\" align=\"center\">";
						} else {
						echo "<tr class=\"even\" align=\"center\">";
						}
							echo "<td>$nubhis</td>";
							echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$contractID</u></font></td>";
							echo "<td>$conType</td>";
							echo "<td>$docTypename</td>";
							echo "<td>$up_DoerName</td>";
							echo "<td>$up_doerStamp</td>";
							echo "<td>$up_appvName</td>";
							echo "<td>$up_appvStamp</td>";
							echo "<td><font color=\"$colorSF\">$textAppv</font></td>";
							echo "<td><a onclick=\"javascript:popU('detail_appv.php?autoID=$up_autoID&menu=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=480')\"style=\"cursor:pointer\"><img src=\"images/detail.gif\"/></a></td>";
						echo "</tr>";
				} //end while
				if($nubhis == 0){
						echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
						}
			?>
							<tr bgcolor="#6699FF">
								<td colspan="10" align="left"><b>รายการทั้งหมด <?php echo $nubhis;?> รายการ<b></td>
							</tr>
			</table>
</fieldset>