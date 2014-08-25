<?php 
include("../../config/config.php");
$contractID = trim($_GET["contractID"]);
if($contractID != ""){

// หา contractType
$qry_contractType = pg_query("select \"thcap_get_contractType\"('$contractID')");
$contractType = pg_result($qry_contractType,0); 
?>
<fieldset>
	<legend><B>สัญญาที่เกี่ยวข้อง (สัญญาที่ปิดบัญชีไปแล้ว )</B></legend> 
	<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#DDDDDD">
				<td colspan="10" align="left" height="25"><u><b>หมายเหตุ</b></u>
					<div style="padding-top:5px;"><font color="red">
						<span style="background-color:#FFF0F5;">&nbsp;&nbsp;&nbsp;</span>
						<span style="background-color:#EEE0E5;">&nbsp;&nbsp;&nbsp;</span> คือ ลูกค้าชำระปกติ</font>
						</div>
					<div style="padding-top:5px;"><font color="red">
						<span style="background-color:#FF3030  ;">&nbsp;&nbsp;&nbsp;</span>
						<span >&nbsp;&nbsp;&nbsp;</span> คือ ลูกค้าค้างชำระหนี้เกิน 30 วัน</font></div>
					<div style="padding-top:5px;"><font color="red">
						<span style="background-color:#FF7F24;">&nbsp;&nbsp;&nbsp;</span>
						<span >&nbsp;&nbsp;&nbsp;</span> คือ ลูกค้าค้างชำระตามกำหนดแต่ไม่เกิน 30 วัน</font></div>
				</td>
			</tr>
	</table>

	<div align="center">
		<div id="panel2" align="left" style="margin-top:10px">
			<?php if($contractType == "FA" || $contractType == "FI")
			{
			?>
				<input type="button" value="วิเคราะห์ลูกหนี้การค้า ที่ปิดบัญชีแล้ว" onClick="javascript:popU('analyzeDebtor.php?conID=<?php echo $contractID;?>&isClose=close','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=500')">
			<?php
			}
			?>
			<table width="100%" border="0" cellspacing="1" cellpadding="1">
				<tr align="center" bgcolor="#CDC1C5">
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
					<th>วันที่ปิดบัญชี</th>
					
				</tr>
				<?php
				$i=0;
				$nowdate=nowDate();
				$qryref1=pg_query("select a.\"contractID\",\"creditLine\",\"conDate\",\"conStartDate\",\"conLoanAmt\",\"conTerm\",\"conMinPay\",\"conFinanceAmount\"
				from \"vthcap_contract_creditRef_all\" a
				left join \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\" 
				where \"contractCredit\"='$contractID'");
				$numref1=pg_num_rows($qryref1);
				$sumconLoanAmt=0; //รวมยอดกู้
				$sumconMinPay=0; //รวมยอดค่าใช้จ่ายขั้นต่ำ
				while($result=pg_fetch_array($qryref1)){
					$contractID2 = $result["contractID"]; //สัญญาที่ใช้วงเงินนั้นๆอยู่
					$creditLine = $result["creditLine"]; //ใช้วงเงินอยู่เท่าไหร่
					$conDate = $result["conDate"]; //วันที่ทำสัญญา
					$conStartDate = $result["conStartDate"]; //วันที่เริ่มกู้
					$conLoanAmt = $result["conLoanAmt"]; //ยอดกู้
					$conTerm = $result["conTerm"]; //จำนวนงวด
					$conMinPay = $result["conMinPay"]; //ยอดจ่ายขั้นต่ำ/ต่อเดือน
					$interestRate = $result["interestRate"]; //อัตราดอกเบี้ยปัจจุบัน
					$conFinanceAmount = $result["conFinanceAmount"]; // ยอดจัด/ยอดลงทุน
					
					if($conLoanAmt == ""){$conLoanAmt = $conFinanceAmount;} // ถ้าเป็นสัญญา FI

					//อัตรดอกเบี้ยปัจจุบัน
					$qryintcur=pg_query("select \"conIntCurRate\" from \"thcap_mg_contract_current\" where \"contractID\"='$contractID2'");
					$resintcur=pg_fetch_array($qryintcur);
					$conIntCurRate=$resintcur["conIntCurRate"];
					
					//หากสัญญาที่ปิดบัญชีแล้วจะเป็นแถบสีเทา
					$qryconclose=pg_query("SELECT thcap_checkcontractcloseddate('$contractID2')");
					$reconclose=pg_fetch_array($qryconclose);
					$conclosestae=$reconclose["thcap_checkcontractcloseddate"];

					//วันที่ครบกำหนดชำระ
					$qryenddate=pg_query("select \"conEndDate\" from \"thcap_mg_contract\" where \"contractID\"='$contractID2'");
					$resenddate=pg_fetch_array($qryenddate);
					$conEndDate=$resenddate["conEndDate"];
					
					if($conEndDate==""){//ถ้าไม่พบวันที่ครบกำหนดชำระ ให้ ค้นหา จาก  thcap_lease_contract แทน
						$qryenddate=pg_query("select \"conEndDate\" from \"thcap_lease_contract\" where \"contractID\"='$contractID2'");
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
					$qry_arrayFaBill = pg_query("select \"arrayFaBill\" from thcap_contract_fa_bill where \"contractID\" = '$contractID2'");
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
					
					if($conclosestae != ""){
						$i++;
						$sumconLoanAmt+=$conLoanAmt; //ยอดรวมกู้
						$sumconMinPay+=$conMinPay; //รวมยอดค่าใช้จ่ายขั้นต่ำ
					
					if($conEndDate !=""){
					//หาระยะห่างระหว่างวันของ วันที่ปิดบัญชี-วันที่ครบกำหนดชำระ
					$dayCloseEnd = DateDiff_2P($conEndDate,$conclosestae);
					  
						if($i%2==0){
							if($dayCloseEnd>0 and $dayCloseEnd<=30){
								echo "<tr bgcolor=\"#FF7F24\" height=20 align=\"center\">";
							} else if($dayCloseEnd>30) {
								echo "<tr bgcolor=\"#FF3030\" height=20 align=\"center\">";
							} else {
								echo "<tr bgcolor=\"#EEE0E5\" height=20 align=\"center\">";
							}
						}else{
							if($dayCloseEnd>0 and $dayCloseEnd<=30){
								echo "<tr bgcolor=\"#FF7F24\" height=20 align=\"center\">";
							} else if($dayCloseEnd>30) {
								echo "<tr bgcolor=\"#FF3030\" height=20 align=\"center\">";
							} else {
								echo "<tr bgcolor=\"#FFF0F5\" height=20 align=\"center\">";
							}
						}
					}
		?>
					<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID2;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><u><?php echo $contractID2; ?></u></span></td>
					<?php if($contractType == "FA" || $contractType == "FI"){echo "<td>$DebtorName</td>";} ?>
					<td><?php echo $conDate; ?></td>
					<td><?php echo $conStartDate; ?></td>
					<td><?php echo $conEndDate; ?></td>
					<td><?php echo $periodDue; ?></td>
					<td align="right"><?php echo number_format($conLoanAmt,2); ?></td>
					<td><?php echo $conTerm; ?></td>
					<td><?php echo number_format($conIntCurRate,2);?></td>
					<td align="right"><?php echo number_format($conMinPay,2); ?></td>
					<td align="center"><?php echo $conclosestae;?></td>
				</tr>
				
				<?php
					}
				}
				if($numref1==0){
				?>
				<tr>
					<td colspan="11" height="50" align="center" class="even">ไม่พบข้อมูล!!</td>
				</tr>
				<?php
				}else{
					if($contractType == "FA" || $contractType == "FI")
					{
						echo "<tr style=\"background:#FFCC99;\"><td colspan=6 align=right><font color=black><b>รวม</b></font></td><td align=right><font color=black><b>".number_format($sumconLoanAmt,2)."</b></font></td><td colspan=2 align=right><font color=black><b>รวม</b></font></td><td align=right><font color=black><b>".number_format($sumconMinPay,2)."</b></font></td><td></td></tr>";
						echo "<tr><td colspan=12 align=right><font color=red><b>รวมทั้งหมด $i สัญญา</b></font></td><tr>";
					}
					else
					{
						echo "<tr style=\"background:#FFCC99;\"><td colspan=5 align=right><font color=black><b>รวม</b></font></td><td align=right><td align=right><font color=black><b>".number_format($sumconLoanAmt,2)."</b></font></td><td colspan=2 align=right><font color=black><b>รวม</b></font></td><td align=right><font color=black><b>".number_format($sumconMinPay,2)."</b></font></td><td></td></tr>";
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