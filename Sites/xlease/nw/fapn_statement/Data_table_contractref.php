<?php if($contractID != ""){
// หา contractType
$qry_contractType = pg_query("select \"thcap_get_contractType\"('$contractID')");
$contractType = pg_result($qry_contractType,0);
 ?>
<fieldset>
	<legend><B>สัญญาที่เกี่ยวข้อง</B></legend>
	<div align="center">
		<div id="panel2" align="left" style="margin-top:10px">
			<?php if($contractType == "FA" || $contractType == "FI")
			{
			?>
				<input type="button" value="วิเคราะห์ลูกหนี้การค้า ที่ยังไม่ปิดบัญชี" onClick="javascript:popU('analyzeDebtor.php?conID=<?php echo $contractID;?>&isClose=open','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=500')">
			<?php
			}
			?>
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr align="center" bgcolor="#79BCFF">
					<th>เลขที่สัญญา</th>
					<?php if($contractType == "FA" || $contractType == "FI"){echo "<th>ลูกหนี้การค้า</th>";} ?>
					<th>วันที่ทำสัญญา</th>
					<th>วันที่เริ่มกู้</th>
					<th>วันที่ครบ<br>กำหนดชำระ</th>
					<th>ระยะเวลาหนี้<br>(วัน)</th>
					<th>ยอดกู้</th>
					<th>จำนวนงวด</th>
					<th>อัตรดอกเบี้ย<br>ปัจจุบัน(%)</th>
					<th>ยอดจ่ายขั้นต่ำ/เดือน</th>
					<th>วันที่เริ่มค้างชำระ</th>
					<th>ยอดค้างชำระปัจจุบัน</th>
				</tr>
				<?php
				$i=0;
				$nowdate=nowDate();
				$qryref=pg_query("select a.\"contractID\",\"creditLine\",\"conDate\",\"conStartDate\",\"conLoanAmt\",\"conTerm\",\"conMinPay\",\"conFinanceAmount\"
				from \"vthcap_contract_creditRef_all\" a
				left join \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\" 
				where \"contractCredit\"='$contractID'");
				$numref=pg_num_rows($qryref);
				$sumconLoanAmt=0; //รวมยอดกู้
				$sumconMinPay=0; //รวมยอดค่าใช้จ่ายขั้นต่ำ
				while($result=pg_fetch_array($qryref)){
					$contractID1 = $result["contractID"]; //สัญญาที่ใช้วงเงินนั้นๆอยู่
					$creditLine = $result["creditLine"]; //ใช้วงเงินอยู่เท่าไหร่
					$conDate = $result["conDate"]; //วันที่ทำสัญญา
					$conStartDate = $result["conStartDate"]; //วันที่เริ่มกู้
					$conLoanAmt = $result["conLoanAmt"]; //ยอดกู้
					$conTerm = $result["conTerm"]; //จำนวนงวด
					$conMinPay = $result["conMinPay"]; //ยอดจ่ายขั้นต่ำ/ต่อเดือน
					$interestRate = $result["interestRate"]; //อัตราดอกเบี้ยปัจจุบัน
					$conFinanceAmount = $result["conFinanceAmount"]; // ยอดจัด/ยอดลงทุน
					
					if($conLoanAmt == ""){$conLoanAmt = $conFinanceAmount;} // ถ้าเป็นสัญญา FI
					
					//ยอดค้างชำระปัจจุบัน
					$backAmt = pg_query("select \"thcap_backAmt\"('$contractID1','$nowdate')");
					$backAmt = pg_fetch_result($backAmt,0);
					
					//วันที่เริ่มค้างชำระ
					$backDueDate = pg_query("select \"thcap_backDueDate\"('$contractID1','$nowdate')");
					$backDueDate = pg_fetch_result($backDueDate,0);
					
					//อัตรดอกเบี้ยปัจจุบัน
					$qryintcur=pg_query("select \"conIntCurRate\" from \"thcap_mg_contract_current\" where \"contractID\"='$contractID1'");
					$resintcur=pg_fetch_array($qryintcur);
					$conIntCurRate=$resintcur["conIntCurRate"];
					
					//หากสัญญาที่ปิดบัญชีแล้วจะเป็นแถบสีเทา
					$qryconclose=pg_query("SELECT thcap_checkcontractcloseddate('$contractID1')");
					$reconclose=pg_fetch_array($qryconclose);
					$conclosestae=$reconclose["thcap_checkcontractcloseddate"];

					//วันที่ครบกำหนดชำระ
					$qryenddate=pg_query("select \"conEndDate\" from \"thcap_mg_contract\" where \"contractID\"='$contractID1'");
					$resenddate=pg_fetch_array($qryenddate);
					$conEndDate=$resenddate["conEndDate"];
					if($conEndDate==""){//ถ้าไม่พบวันที่ครบกำหนดชำระ ให้ ค้นหา จาก  thcap_lease_contract แทน
						$qryenddate=pg_query("select \"conEndDate\" from \"thcap_lease_contract\" where \"contractID\"='$contractID1'");
						$resenddate=pg_fetch_array($qryenddate);
						$conEndDate=$resenddate["conEndDate"];
					}
					
					// หาระยะเวลาหนี้
					if($conStartDate != "" && $conEndDate != "")
					{
						$qry_periodDue = pg_query("select '$conEndDate'::date - '$conStartDate'::date ");
						$periodDue = pg_result($qry_periodDue,0);
					}
					else
					{
						$periodDue = "";
					}
					
					// หาบิลที่ผูกกับสัญญา
					$qry_arrayFaBill = pg_query("select \"arrayFaBill\" from thcap_contract_fa_bill where \"contractID\" = '$contractID1'");
					$arrayFaBill = pg_result($qry_arrayFaBill,0);
					
					// หาชื่อลูกหนี้การค้า
					$DebtorName = "";
					if($arrayFaBill != "")
					{
						$qry_Debtor = pg_query("SELECT distinct (select \"userDebtor\" from thcap_fa_prebill where \"prebillID\" = \"ta_array_list\"::bigint) as \"DebtorID\",
												(select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = (select \"userDebtor\" from thcap_fa_prebill where \"prebillID\" = \"ta_array_list\"::bigint)) as \"DebtorName\"
												from ta_array_list('$arrayFaBill')");
						while($Debtor = pg_fetch_array($qry_Debtor))
						{
							if($DebtorName == "")
							{
								$DebtorName = $Debtor["DebtorName"];
							}
							else
							{
								$DebtorName = $DebtorName.", ".$Debtor["DebtorName"];
							}
						}
					}
					
					if($conclosestae == ""){
						$i++;
						$sumconLoanAmt+=$conLoanAmt; //ยอดรวมกู้
						$sumconMinPay+=$conMinPay; //รวมยอดค่าใช้จ่ายขั้นต่ำ
						if($i%2==0){
							echo "<tr class=\"odd\" height=20 align=\"center\">";
							
						}else{
							echo "<tr class=\"even\" height=20 align=\"center\">";
						}
						
		?>
					<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID1;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><u><?php echo $contractID1; ?></u></span></td>
					<?php if($contractType == "FA" || $contractType == "FI"){echo "<td>$DebtorName</td>";} ?>
					<td><?php echo $conDate; ?></td>
					<td><?php echo $conStartDate; ?></td>
					<td><?php echo $conEndDate; ?></td>
					<td><?php echo $periodDue; ?></td>
					<td align="right"><?php echo number_format($conLoanAmt,2); ?></td>
					<td><?php echo $conTerm; ?></td>
					<td><?php echo number_format($conIntCurRate,2);?></td>
					<td align="right"><?php echo number_format($conMinPay,2); ?></td>
					<td style="color:#0000FF;"><?php echo $backDueDate;?></td>
					<td align="right"><?php echo number_format($backAmt,2);?></td>
					
				</tr>
				
				<?php
					}
				}
				if($numref==0){
				?>
				<tr>
					<td colspan="11" height="50" align="center" class="even">ไม่พบข้อมูล!!</td>
				</tr>
				<?php
				}else{
					if($contractType == "FA" || $contractType == "FI")
					{
						echo "<tr style=\"background:#FFCC99;\"><td colspan=6 align=right><font color=black><b>รวม</b></font></td><td align=right><font color=black><b>".number_format($sumconLoanAmt,2)."</b></font></td><td colspan=2 align=right><font color=black><b>รวม</b></font></td><td align=right><font color=black><b>".number_format($sumconMinPay,2)."</b></font></td><td></td><td></td></tr>";
						echo "<tr><td colspan=12 align=right><font color=red><b>รวมทั้งหมด $i สัญญา</b></font></td><tr>";
					}
					else
					{
						echo "<tr style=\"background:#FFCC99;\"><td colspan=5 align=right><font color=black><b>รวม</b></font></td><td align=right><font color=black><b>".number_format($sumconLoanAmt,2)."</b></font></td><td colspan=2 align=right><font color=black><b>รวม</b></font></td><td align=right><font color=black><b>".number_format($sumconMinPay,2)."</b></font></td><td></td><td></td></tr>";
						echo "<tr><td colspan=11 align=right><font color=red><b>รวมทั้งหมด $i สัญญา</b></font></td><tr>";
					}
					
				}
				?>
			</table>
		</div>
	</div>
</fieldset>
<?php
}
?>