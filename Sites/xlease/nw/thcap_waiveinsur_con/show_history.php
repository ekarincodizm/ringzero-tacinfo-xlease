<fieldset>
	<legend><b>ประวัติการทำรายการ 30 รายการล่าสุด <a href="javascript:popU('show_all_history.php?','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><font color="#0000ff">(<u>ทั้งหมด</u>)</font></a></b></legend>
		<table width="1000" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
			<tr>
				<td>
					<div class="wrapper">		
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		
							<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">	
								<td>รายการที่<a href="show_history.php?sort=&order="></td>
								<td>เลขที่สัญญา</td>
								<td>ประเภทสัญญา</td>
								<td>วันที่ทำสัญญา</td>
								<td>จำนวนเงินที่ลงทุน</td>
								<td>วันที่ครบกำหนดสัญญา</td>
								<td>ผู้ขอยกเว้น</td>
								<td>วันเวลาที่ขอยกเว้น</td>
								<td>ผู้อนุมัติ</td>
								<td>วันเวลาที่อนุมัติ</td>
								<td>หมายเหตุ</td>
								<td>ผลการอนุมัติ</td>
							</tr>
							<?php
							$qry_con=pg_query("select *,thcap_get_iniinvestmentamt(a.\"contractID\") as investment,\"thcap_get_conEndDate\"(a.\"contractID\") as \"FconEndDate\" from \"thcap_contract\" a
							left join thcap_contract_waive_fa_chqguaranteed b on a.\"contractID\"= b.\"contractID\"
							where \"statusCon\"<>'2' order by b.waive_app_stamp DESC limit 30");
			
							$num_con=pg_num_rows($qry_con);
							$i=0;
							$num_his=0;
							while($res_con=pg_fetch_array($qry_con)){
							$i++;
							$num_his++;
							$contractID=$res_con["contractID"];
							$contractType = $res_con["conType"]; 
							$contractDate = $res_con["conDate"]; 
							$conFinanceAmt = $res_con["conFinanceAmount"]; 
							$conEndDate = $res_con["FconEndDate"];
							$addUser = $res_con["waive_add_user"];
							$addStamp = $res_con["waive_add_stamp"];
							$appUser = $res_con["waive_app_user"];
							$appStamp = $res_con["waive_app_stamp"];
							$statusCon = $res_con["statusCon"];
							$autoID = $res_con["waive_auto_id"];
							$investment = $res_con["investment"];
				
							$qry_chkStatus = pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
							while($res_chkStatus = pg_fetch_array($qry_chkStatus)){
								$conStatus = $res_chkStatus["conStatus"]; // สถานะของสัญญา
							}
						
							if($conStatus == "11")
							{
								echo "<tr bgcolor=\"#CCCCCC\">";
							}
							$qry_fullname_adduser = pg_query("select fullname from \"Vfuser\" where id_user = '$addUser' ");
							$addFullname=pg_fetch_result($qry_fullname_adduser,0);
				
							$qry_fullname_appuser = pg_query("select fullname from \"Vfuser\" where id_user = '$addUser' ");
							$appFullname=pg_fetch_result($qry_fullname_adduser,0);
				
							if($investment!=""){$TextInvest=number_format($investment,2);} else {$TextInvest="";}
				
							if($statusCon==0){
								$statusConName="ไม่อนุมัติ";
								$fontcolor="#ff0000";
							} else {
								$statusConName="อนุมัติ";
								$fontcolor="#00ff00";
							}
				
							if($i%2==0){
								echo "<tr class=\"odd\" align=center>";
							} else {
								echo "<tr class=\"even\" align=center>";
							}
									echo "<td>$num_his</td>";
									echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$contractID</u></font></td>";
									echo "<td>$contractType</td>";
									echo "<td>$contractDate</td>";
									echo "<td align=\"right\">$TextInvest</td>";
									echo "<td>$conEndDate</td>";
									echo "<td>$addFullname</td>";
									echo "<td>$addStamp</td>";
									echo "<td>$appFullname</td>";
									echo "<td>$appStamp</td>";
									echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('reason_Appv.php?contractID=$contractID&waive_auto_id=$autoID&statusHis=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=380,height=360')\"style=\"cursor:pointer\"><img src=\"images/detail.gif\"/></a></u></font></td>";
									echo "<td><font color=\"$fontcolor\">$statusConName</font></td>";
								echo "</tr>";
							} //endwhile
							?>
							<?php
							if($num_con == 0){
								echo "<tr><td colspan=12 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
							}
							?>
							<tr bgcolor="#6699FF">
									<td colspan="12" align="left"><b>รายการทั้งหมด <?php echo $num_his;?><b></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
</fieldset>