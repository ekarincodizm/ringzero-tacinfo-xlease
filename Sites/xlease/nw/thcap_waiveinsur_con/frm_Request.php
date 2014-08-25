
<form name="frm" method="POST">
	<fieldset>
		<legend><b>รายการที่ไม่มีเช็คค้ำ</b></legend>
			<table width="1000" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="10" align="left" height="25"><u><b>หมายเหตุ</b></u>
					<div><font color="red"> <span style="background-color:#CCCCCC;">&nbsp;&nbsp;&nbsp;</span> รายการสีเทา คือ สัญญาที่ปิดบัญชีแล้ว</font></div>
				</td>
			</tr>
			<table>
			<table width="1000" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
				<tr>
					<td>
						<div class="wrapper">
							<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		
								<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">	
									<td><font color="black">รายการที่</font></td>
									<td><a href="Index.php?sort=contractID&order=<?php echo $NewStrorder;?>"><font color="black"><u>เลขที่สัญญา</u></font></td>
									<td><a href="Index.php?sort=conType&order=<?php echo $NewStrorder;?>"><font color="black"><u>ประ่เภทสัญญา</u></font></td>
									<td><a href="Index.php?sort=conDate&order=<?php echo $NewStrorder;?>"><font color="black"><u>วันที่ทำสัญญา</u></font></td>
									<td><a href="Index.php?sort=investment&order=<?php echo $NewStrorder;?>"><font color="black"><u>จำนวนเงินที่ลงทุน</u></font></td>
									<td><a href="Index.php?sort=conEndDate&order=<?php echo $NewStrorder;?>"><font color="black"><u>วันที่ครบกำหนดสัญญา</u></font></td>
									<td><font color="black">ทำรายการ</font></td>
								</tr>
								<?php
								$qry_con=pg_query("select *,thcap_get_iniinvestmentamt(\"contractID\") as investment,\"thcap_get_conEndDate\"(\"contractID\") as \"FconEndDate\" from \"thcap_contract\"
								where (\"conType\" = 'FA' or \"conType\" = 'FI' or \"conType\" = 'PN') and (\"conCredit\" is null) and \"contractID\" not in(select distinct \"revChqToCCID\" from finance.\"thcap_receive_cheque\")
								and \"contractID\" not in (select distinct \"contractID\" from thcap_contract_waive_fa_chqguaranteed) order by \"$Strsort\" $Strorder ");
			
								$num_con=pg_num_rows($qry_con);
								$i=0;
								$num=0;
								while($res_con=pg_fetch_array($qry_con)){
								$i++;
								$num++;
								$contractID=$res_con["contractID"];
								$contractType = $res_con["conType"]; 
								$contractDate = $res_con["conDate"]; 
								$conEndDate = $res_con["FconEndDate"];
								$autoID = $res_con["waive_auto_id"];
								$investmentAmt = $res_con["investment"];
				
								if($investmentAmt!=""){$TextInvest=number_format($investmentAmt,2);} else {$TextInvest="";}
								
								$qry_findClose = pg_query("select thcap_checkcontractcloseddate('$contractID')");
								$res_close = pg_fetch_result($qry_findClose,0);
									
								if($i%2==0){
									if($res_close!= null){
										echo "<tr bgcolor=\"#CCCCCC\" align=center>";
									} else {
										echo "<tr class=\"odd\" align=center>";
									}
								} else {
									if($res_close != null){
										echo "<tr bgcolor=\"#CCCCCC\" align=center>";
									} else {
										echo "<tr class=\"even\" align=center>";
									}
								}
									echo "<td>$num</td>";
									echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor:pointer\">$contractID</u></font></td>";
									echo "<td>$contractType</td>";
									echo "<td>$contractDate</td>";
									echo "<td align=\"right\">$TextInvest</td>";
									echo "<td>$conEndDate</td>";
									echo "<td><font color=\"#0000ff\"><u><a onclick=\"javascript:popU('Input_reason_Appv.php?contractID=$contractID&waive_auto_id=$autoID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=380,height=360')\"style=\"cursor:pointer\">ขอละเว้น</a></u></font></td>";
								echo "</tr>";
								} //endwhile
								?>
								<?php
			
								if($num_con == 0){
									echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
								}
								?>
								<tr bgcolor="#6699FF">
									<td colspan="9" align="left"><b>รายการทั้งหมด <?php echo $num;?><b></td>
								</tr>
							</table>
						</div>	
					</td>
				</tr>
			</table>
	</fieldset>
</form>