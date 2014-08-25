<fieldset>
	<legend><b>รายการรออนุมัติยกเว้นเช็คค้ำ</b></legend>
		<form name="frm" method="POST">
			<table width="1000" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
				<tr>
					<td>
						<div class="wrapper">		
							<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		
								<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">	
									<td><font color="black">รายการที่</font></td>
									<td><font color="black">เลขที่สัญญา</font></td>
									<td><font color="black">ประเภทสัญญา</font></td>
									<td><font color="black">วันที่ทำสัญญา</font></td>
									<td><font color="black">จำนวนเงินที่ลงทุน</font></td>
									<td><font color="black">วันที่ครบกำหนดสัญญา</font></td>
									<td><font color="black">ผู้ขอยกเว้น</font></td>
									<td><font color="black">วันเวลาที่ขอยกเว้น</font></td>
									<td><font color="black">ทำรายการ</font></td>
								</tr>
								<?php
								$qry_con=pg_query("select *,thcap_get_iniinvestmentamt(a.\"contractID\") as investment,\"thcap_get_conEndDate\"(a.\"contractID\") as \"FconEndDate\"  from \"thcap_contract\" a
								left join thcap_contract_waive_fa_chqguaranteed b on a.\"contractID\"= b.\"contractID\"
								where \"statusCon\"='2' order by b.\"waive_add_stamp\" ASC  ");
			
								$num_con=pg_num_rows($qry_con);
								$i=0;
								$num_App=0;
								while($res_con=pg_fetch_array($qry_con)){
									$i++;
									$num_App++;
									$contractID=$res_con["contractID"];
									$contractType = $res_con["conType"]; 
									$contractDate = $res_con["conDate"]; 
									$conFinanceAmt = $res_con["conFinanceAmount"]; 
									$conEndDate = $res_con["FconEndDate"];
									$addUser = $res_con["waive_add_user"];
									$addStamp = $res_con["waive_add_stamp"];
									$autoID = $res_con["waive_auto_id"];
									$investment = $res_con["investment"];
									
									$qry_chkStatus = pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
									$res_chkStatus = pg_fetch_array($qry_chkStatus);
				
									$conStatus = $res_chkStatus["conStatus"]; // สถานะของสัญญา
				
									if($investment!=""){$TextInvest=number_format($investment,2);} else {$TextInvest="";}
				
									$qry_fullname_adduser = pg_query("select fullname from \"Vfuser\" where id_user = '$addUser' ");
									$addFulname=pg_fetch_result($qry_fullname_adduser,0);
				
									if($i%2==0){
										if($conStatus == "11"){
											echo "<tr bgcolor=\"#CCCCCC\">";
										} else {
											echo "<tr class=\"odd\" align=center>";
										}
									} else {
										if($conStatus == "11"){
											echo "<tr bgcolor=\"#CCCCCC\">";
										} else {
											echo "<tr class=\"even\" align=center>";
										}
				
									} 
										echo "<td>$num_App</td>";
										echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$contractID</u></font></td>";
										echo "<td>$contractType</td>";
										echo "<td>$contractDate</td>";
										echo "<td align=\"right\">$TextInvest</td>";
										echo "<td>$conEndDate</td>";
										echo "<td>$addFulname</td>";
										echo "<td>$addStamp</td>";
									if($menu=="Appv"){
										echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('reason_Appv.php?contractID=$contractID&waive_auto_id=$autoID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=380,height=360')\"style=\"cursor:pointer\"><img src=\"images/detail.gif\"/></a></u></font></td>";
									} else {
										echo "<td>รออนุมัติ</td>";
									}
									echo "</tr>";
								} //endwhile
								?>
			
								<?php
								if($num_con == 0){
									echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
								}
								?>
								<tr bgcolor="#6699FF">
									<td colspan="9" align="left"><b>รายการทั้งหมด <?php echo $num_App;?><b></td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</form>
</fieldset>