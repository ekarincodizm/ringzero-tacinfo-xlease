<?php
include("../../config/config.php");
set_time_limit(120);
?>

<script>
	function popU(U,N,T)
	{
		newWindow = window.open(U, N, T);
	}
</script>

<?php
$focusDate = pg_escape_string($_GET["focusDate"]); // วันที่สนใจ
$CusID_array = pg_escape_string($_GET["CusID_array"]); // รหัสลูกค้า
$annuities = pg_escape_string($_GET["annuities"]); // ติดตามค่างวด ถ้าเลือก จะเป็น checked
$charges = pg_escape_string($_GET["charges"]); // ติดตามค่าใช้จ่ายอื่นๆ ถ้าเลือก จะเป็น checked
$note = pg_escape_string($_GET["note"]); // หมายเหตุ

$CusID_split = split(",", $CusID_array);

$noteShow = str_replace("codeEnter","\r\n",$note);
$noteShow = str_replace("codeSpace"," ",$noteShow);

// ถ้าเลือกให้แสดง ติดตามค่างวด หรือ ติดตามค่าใช้จ่ายอื่นๆ
if($annuities == "checked" || $charges == "checked")
{
?>
	<br/>
	<table width="100%">
		<tr>
			<td align="right">
				<input type="button" value="พิมพ์ PDF" onclick="javascript:popU('frm_PDF.php?CusID_array=<?php echo "$CusID_array"; ?>&focusDate=<?php echo "$focusDate"; ?>&annuities=<?php echo $annuities; ?>&charges=<?php echo $charges; ?>&note=<?php echo $note; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
			</td>
		</tr>
	</table>
<?php
	// ถ้าเลือกให้แสดง ติดตามค่างวด
	if($annuities == "checked")
	{
?>
		<fieldset><legend><B>ผลการค้นหา ค่างวด ถึงวันที่ <?php echo $focusDate; ?></B></legend>
			<center>
				<?php
				for($c=1; $c<=count($CusID_split); $c++)
				{
					$CusID = $CusID_split[$c-1];
					
					// หาชื่อลูกค้า
					$qry_cusName = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID' ");
					$cusName = pg_fetch_result($qry_cusName,0);
				?>
					<table width="100%" bgcolor="#000000">
						<tr bgcolor="#FFFFFF" style="font-size:18px;">
							<td colspan="6"><b><?php echo "ค่างวด ของ $cusName"; ?></b></td>
						</tr>
						<tr bgcolor="#79BCFF" style="font-size:18px;">
							<th>ลำดับ</th>
							<th>ทะเบียน</th>
							<th>เลขที่สัญญา</th>
							<th>ครบชำระ</th>
							<th>งวดที่</th>
							<th>งวดละ</th>
						</tr>
						<?php
						$qry_contract = pg_query("select \"IDNO\", \"DueNo\", \"DueDate\"
										from  \"VCusPayment\"
										WHERE \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')
										AND \"R_Receipt\" is null AND \"DueDate\" <= '$focusDate'
										ORDER BY \"IDNO\", \"DueDate\" ");
						$i = 0;
						$sum_P_MONTH = 0;
						while($res_contract = pg_fetch_array($qry_contract))
						{
							$i++;
							
							$IDNO = $res_contract["IDNO"];
							$DueNo = $res_contract["DueNo"];
							$DueDate = $res_contract["DueDate"];
							
							// หาข้อมูลอื่นๆ
							$qry_other = pg_query("SELECT \"C_REGIS\", \"P_MONTH\", \"C_CARNAME\", \"P_TOTAL\" FROM \"VContact\" where \"IDNO\" = '$IDNO' ");
							$C_REGIS = pg_fetch_result($qry_other,0); // ทะเบียน
							$P_MONTH = pg_fetch_result($qry_other,1); // งวดล่ะ
							$C_CARNAME = pg_fetch_result($qry_other,2); // ประเภทรภ
							$P_TOTAL = pg_fetch_result($qry_other,3); // จำนนวนงวด
							
							if($C_REGIS == "" || $C_REGIS == "-")
							{
								$C_REGIS = $C_CARNAME;
							}
							
							if($i%2==0){
								echo "<tr class=\"odd\" style=\"font-size:15px;\">";
							}else{
								echo "<tr class=\"even\" style=\"font-size:15px;\">";
							}
							
							echo "<td align=\"center\">$i</td>";
							echo "<td align=\"center\">$C_REGIS</td>";
							echo "<td align=\"center\">$IDNO</td>";
							echo "<td align=\"center\">$DueDate</td>";
							echo "<td align=\"center\">$DueNo/$P_TOTAL</td>";
							echo "<td align=\"right\">".number_format($P_MONTH,2)."</td>";
							echo "</tr>";
							
							$sum_P_MONTH += $P_MONTH;
						}
						
						if($i > 0)
						{
							echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
							echo "<td colspan=\"5\" align=\"right\"><b>รวม</b></td>";
							echo "<td colspan=\"5\" align=\"right\"><b>".number_format($sum_P_MONTH,2)."</b></td>";
							echo "</tr>";
						}
						else
						{
							echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
							echo "<td colspan=\"6\" align=\"center\"><b>-- ไม่พบยอดค่าชำระ --</b></td>";
							echo "</tr>";
						}
						?>
					</table>
					<br/><br/><br/>
				<?php
					$dataArray[1][$c][1] = $CusID;
					$dataArray[1][$c][2] = $cusName;
					$dataArray[1][$c][3] = $sum_P_MONTH;
				}
				?>
				
				<table width="100%" bgcolor="#000000">
					<tr bgcolor="#FFFFFF" style="font-size:18px;">
						<td colspan="2"><b><?php echo "ยอดรวม ค่างวด"; ?></b></td>
					</tr>
					<tr bgcolor="#79BCFF" style="font-size:18px;">
						<th>ชื่อ</th>
						<th>จำนวนเงิน</th>
					</tr>
					<?php
					$sum_annuities = 0;
					for($d=1; $d<=count($CusID_split); $d++)
					{
						if($d%2==0){
							echo "<tr class=\"odd\" style=\"font-size:15px;\">";
						}else{
							echo "<tr class=\"even\" style=\"font-size:15px;\">";
						}
							
						echo "<td align=\"left\">".$dataArray[1][$d][2]."</td>";
						echo "<td align=\"right\">".number_format($dataArray[1][$d][3],2)."</td>";
						echo "</tr>";
						
						$sum_annuities += $dataArray[1][$d][3];
					}
					
					echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
					echo "<td align=\"right\"><b>รวม</b></td>";
					echo "<td align=\"right\"><b>".number_format($sum_annuities,2)."</b></td>";
					echo "</tr>";
					
					echo "<tr bgcolor=\"#FFFFFF\" style=\"font-size:15px;\">";
					echo "<td align=\"left\" colspan=\"2\"><b>หมายเหตุ : </b>$noteShow</td>";
					echo "</tr>";
					?>
				</table>
			</center>
		</fieldset>
		<br/><br/><br/><br/>
	<?php
	}

	// ถ้าเลือกให้แสดง ติดตามค่าใช้จ่ายอื่นๆ
	if($charges == "checked")
	{
	?>
		<fieldset><legend><B>ผลการค้นหา ค่าใช้จ่ายอื่นๆ</B></legend>
			<center>
				<?php
				for($c=1; $c<=count($CusID_split); $c++)
				{
					$CusID = $CusID_split[$c-1];
					
					// หาชื่อลูกค้า
					$qry_cusName = pg_query("select \"full_name\" from \"VSearchCus\" where \"CusID\" = '$CusID' ");
					$cusName = pg_fetch_result($qry_cusName,0);
				?>
					<table width="100%" bgcolor="#000000">
						<tr bgcolor="#FFFFFF" style="font-size:18px;">
							<td colspan="6"><b><?php echo "ค่าใช้จ่ายอื่นๆ ของ $cusName"; ?></b></td>
						</tr>
						<tr bgcolor="#79BCFF" style="font-size:18px;">
							<th>ลำดับ</th>
							<th>ทะเบียน</th>
							<th>เลขที่สัญญา</th>
							<th>รอบภาษี+มิเตอร์</th>
							<th>ค่าใช้จ่าย</th>
							<th>ยอดเงิน</th>
						</tr>
						<?php
						$qry_contract = pg_query("SELECT a.\"IDNO\", a.\"TypeDep\", b.\"TName\", a.\"TaxDueDate\", a.\"CusAmt\"
												FROM carregis.\"CarTaxDue\" a, \"TypePay\" b
												WHERE a.\"TypeDep\" = b.\"TypeID\"
												AND a.\"cuspaid\" = false AND a.\"CusAmt\" > 0
												AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')
												
												UNION

												SELECT \"IDNO\", NULL, 'ประกันภัยภาคบังคับ (พรบ.)', \"StartDate\", \"outstanding\"
												FROM insure.\"VInsForceDetail\"
												WHERE \"outstanding\" >= '0.01'
												AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')
												
												UNION

												SELECT \"IDNO\", NULL, 'ประกันภัยภาคสมัครใจ', \"StartDate\", \"outstanding\"
												FROM insure.\"VInsUnforceDetail\"
												WHERE \"outstanding\" >= '0.01'
												AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')

												UNION

												SELECT \"IDNO\", NULL, 'ประกันภัยคุ้มครองหนี้', \"StartDate\", \"outstanding\"
												FROM insure.\"VInsLiveDetail\"
												WHERE \"outstanding\" >= '0.01'
												AND \"IDNO\" IN(select distinct \"IDNO\" from \"ContactCus\" where \"CusState\" = '0' and \"CusID\" = '$CusID')

												ORDER BY 1 ");
						$i = 0;
						$sum_CusAmt = 0;
						while($res_contract = pg_fetch_array($qry_contract))
						{
							$i++;
							
							$IDNO = $res_contract["IDNO"]; // เลขที่สัญญา
							$TName = $res_contract["TName"]; // ชื่อค่าใช้จ่าย
							$TaxDueDate = $res_contract["TaxDueDate"]; // วันครอบกำหนดตรวจมิเตอร์หรือภาษีรถ
							$CusAmt = $res_contract["CusAmt"]; // จำนวนเงิน
							
							// หาข้อมูลอื่นๆ
							$qry_other = pg_query("SELECT \"C_REGIS\", \"C_CARNAME\" FROM \"VContact\" where \"IDNO\" = '$IDNO' ");
							$C_REGIS = pg_fetch_result($qry_other,0); // ทะเบียน
							$C_CARNAME = pg_fetch_result($qry_other,1); // ประเภทรภ
							
							if($C_REGIS == "" || $C_REGIS == "-")
							{
								$C_REGIS = $C_CARNAME;
							}
							
							if($i%2==0){
								echo "<tr class=\"odd\" style=\"font-size:15px;\">";
							}else{
								echo "<tr class=\"even\" style=\"font-size:15px;\">";
							}
							
							echo "<td align=\"center\">$i</td>";
							echo "<td align=\"center\">$C_REGIS</td>";
							echo "<td align=\"center\">$IDNO</td>";
							echo "<td align=\"center\">$TaxDueDate</td>";
							echo "<td align=\"center\">$TName</td>";
							echo "<td align=\"right\">".number_format($CusAmt,2)."</td>";
							echo "</tr>";
							
							$sum_CusAmt += $CusAmt;
						}
						
						if($i > 0)
						{
							echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
							echo "<td colspan=\"5\" align=\"right\"><b>รวม</b></td>";
							echo "<td colspan=\"5\" align=\"right\"><b>".number_format($sum_CusAmt,2)."</b></td>";
							echo "</tr>";
						}
						else
						{
							echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
							echo "<td colspan=\"6\" align=\"center\"><b>-- ไม่พบยอดค่าชำระ --</b></td>";
							echo "</tr>";
						}
						?>
					</table>
					<br/><br/><br/>
				<?php
					$dataArray[2][$c][1] = $CusID;
					$dataArray[2][$c][2] = $cusName;
					$dataArray[2][$c][3] = $sum_CusAmt;
				}
				?>
				
				<table width="100%" bgcolor="#000000">
					<tr bgcolor="#FFFFFF" style="font-size:18px;">
						<td colspan="2"><b><?php echo "ยอดรวม ค่าใช้จ่ายอื่นๆ"; ?></b></td>
					</tr>
					<tr bgcolor="#79BCFF" style="font-size:18px;">
						<th>ชื่อ</th>
						<th>จำนวนเงิน</th>
					</tr>
					<?php
					$sum_charges = 0;
					for($d=1; $d<=count($CusID_split); $d++)
					{
						if($d%2==0){
							echo "<tr class=\"odd\" style=\"font-size:15px;\">";
						}else{
							echo "<tr class=\"even\" style=\"font-size:15px;\">";
						}

						echo "<td align=\"left\">".$dataArray[2][$d][2]."</td>";
						echo "<td align=\"right\">".number_format($dataArray[2][$d][3],2)."</td>";
						echo "</tr>";
						
						$sum_charges += $dataArray[2][$d][3];
					}
					
					echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
					echo "<td align=\"right\"><b>รวม</b></td>";
					echo "<td align=\"right\"><b>".number_format($sum_charges,2)."</b></td>";
					echo "</tr>";
					
					echo "<tr bgcolor=\"#FFFFFF\" style=\"font-size:15px;\">";
					echo "<td align=\"left\" colspan=\"2\"><b>หมายเหตุ : </b>$noteShow</td>";
					echo "</tr>";
					?>
				</table>
			</center>
		</fieldset>
		<br/><br/><br/><br/>
<?php
	}
?>
	<fieldset><legend><B>รวมทั้งหมด</B></legend>
		<center>
			<table width="100%" bgcolor="#000000">
					<tr bgcolor="#FFFFFF" style="font-size:18px;">
						<td colspan="4"><b><?php echo "ยอดรวม ค่างวด และ ค่าใช้จ่ายอื่นๆ"; ?></b></td>
					</tr>
					<tr bgcolor="#79BCFF" style="font-size:18px;">
						<th>ชื่อ</th>
						<th>ค่างวด</th>
						<th>ค่าใช้จ่ายอื่นๆ</th>
						<th>รวม</th>
					</tr>
					<?php
					$sumI = 0; // รวมค่างวดทั้งหมด
					$sumO = 0; // รวมค่าใช้จ่ายอื่นๆทั้งหมด
					$SumAll = 0; // รวมทั้งหมด
					for($d=1; $d<=count($CusID_split); $d++)
					{
						// ชื่อ
						if($dataArray[1][$d][2] != ""){$cusNameSum = $dataArray[1][$d][2];}
						else{$cusNameSum = $dataArray[2][$d][2];}
						
						// รวมของแต่ละคน
						$cusSumAll = $dataArray[1][$d][3] + $dataArray[2][$d][3];
						
						if($d%2==0){
							echo "<tr class=\"odd\" style=\"font-size:15px;\">";
						}else{
							echo "<tr class=\"even\" style=\"font-size:15px;\">";
						}

						echo "<td align=\"left\">".$cusNameSum."</td>";
						echo "<td align=\"right\">".number_format($dataArray[1][$d][3],2)."</td>";
						echo "<td align=\"right\">".number_format($dataArray[2][$d][3],2)."</td>";
						echo "<td align=\"right\">".number_format($cusSumAll,2)."</td>";
						echo "</tr>";
						
						$sumI += $dataArray[1][$d][3]; // รวมค่างวดทั้งหมด
						$sumO += $dataArray[2][$d][3]; // รวมค่าใช้จ่ายอื่นๆทั้งหมด
						$SumAll += $cusSumAll; // รวมทั้งหมด
					}
					
					echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:18px;\">";
					echo "<td align=\"right\"><b>รวมทั้งหมด</b></td>";
					echo "<td align=\"right\"><b>".number_format($sumI,2)."</b></td>";
					echo "<td align=\"right\"><b>".number_format($sumO,2)."</b></td>";
					echo "<td align=\"right\"><b>".number_format($SumAll,2)."</b></td>";
					echo "</tr>";
					
					echo "<tr bgcolor=\"#FFFFFF\" style=\"font-size:15px;\">";
					echo "<td align=\"left\" colspan=\"4\"><b>หมายเหตุ : </b>$noteShow</td>";
					echo "</tr>";
					?>
				</table>
		</center>
	</fieldset>
<?php
}
?>