<fieldset>
	<legend><B>คำนวนยอดปิดบัญชี</B></legend>
	<div align="center">
		<div align="left" style="margin-top:10px">	
			<?php
			if($contractID != "") // ถ้ามีการส่งค่ามา // ตารางด้านล่าง
			{	// ==================================================================================
				// รับค่าที่ POST มาต่างๆ
				// ==================================================================================
				
				$chktest = $_POST["chkcloseaccount"]; // ถ้าเป็น 1 แสดงว่ามีการคลิกปุ่มคำนวน
				$signDate = $_POST["signDate"]; //วันที่เลือก
				if($signDate=="") $signDate=nowDate();
				$costclose = $_POST["costclose"]; //รวมค่าบริการปิดบัญชี ถ้าเลือกจะเป็น on
				$costcloseCHK = $_POST["costclose"]; //รวมค่าบริการปิดบัญชี ถ้าเลือกจะเป็น on ใช้ในการเช็ค checkbox
				
				if($costclose == "on"){$costclose = 2000;}else{$costclose = 0;}
				
				//หาวันที่ชำระค่างวดล่าสุด  เพื่อนำไปตรวจสอบการปิดบัญชี เพราะจะต้องปิดหลังจากที่ชำระล่าสุด
				$qrydate=pg_query("select max(date(\"receiveDate\")) from thcap_v_receipt_otherpay  
				where \"contractID\"='$contractID' and \"typePayID\"=account.\"thcap_mg_getMinPayType\"(\"contractID\")
				group by \"contractID\" ");
				list($maxdate)=pg_fetch_array($qrydate);

				if($maxdate==""){
					$maxdate='0000-00-00 00:00:00';
				}
				
				// ==================================================================================
				// นำข้อมูลต่างๆของสัญญาที่เป็นการตั้งค่าัปัจจุบัน
				// ==================================================================================
				$sql_chkdate = pg_query("select * from public.\"thcap_lease_contract\" where \"contractID\" = '$contractID' ");
				while($resultchkdate=pg_fetch_array($sql_chkdate))
				{
					$conDate = $resultchkdate["conDate"]; // วันที่ทำสัญญา
				}
				
				// ==================================================================================
				// หายอดปิดสัญญาลงทุน 
				// ==================================================================================
				$qrymoneyclose = pg_query("SELECT \"thcap_cal_close_guaranteed_investment\"('$contractID','$signDate')");
				list($LastLeftPrinciple)=pg_fetch_array($qrymoneyclose);
				
				// ==================================================================================
				// หายอดหนี้ค้างชำระอื่นๆ
				// ==================================================================================
				$sql_other = pg_query("select * from account.\"thcap_typePay\" where \"isSubsti\" <> '1' ");
				while($resultother=pg_fetch_array($sql_other))
				{
					$tpID_other = $resultother["tpID"];
					
					$sql_Sother = pg_query("select sum(\"typePayAmt\") as \"sumone\" from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\" = '$contractID' and \"typePayID\" = '$tpID_other' and \"debtStatus\" = '1' ");
					while($resultSother=pg_fetch_array($sql_Sother))
					{
						 $sumone = $resultSother["sumone"];
						
						$plusone += $sumone; // ยอดหนี้ค้างชำระอื่นๆ
					}
					$sumone = 0;
				}
				
				// ==================================================================================
				// หายอดรับจ่ายแทนค่าประกันภัย-อื่นๆ
				// ==================================================================================
				$sql_other2 = pg_query("select * from account.\"thcap_typePay\" where \"isSubsti\" = '1' ");
				while($resultother2=pg_fetch_array($sql_other2))
				{
					 $tpID_other2 = $resultother2["tpID"];
					
					$sql_Sother2 = pg_query("select sum(\"typePayAmt\") as \"sumone\" from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\" = '$contractID' and \"typePayID\" = '$tpID_other2' and \"debtStatus\" = '1' ");
					while($resultSother2=pg_fetch_array($sql_Sother2))
					{
						$sumone = $resultSother2["sumone"];
						
						$plustwo += $sumone; // ยอดรับจ่ายแทนค่าประกันภัย-อื่นๆ
					}
					$sumone = 0;
				}
				
				if($chktest == 1) // ถ้าเป็น 1 แสดงว่ามีการคลิกปุ่มคำนวน
				{
					// หาค่าเบี้ยปรับ ณ วันที่เลือก
					if($credit_type=="HIRE_PURCHASE" || $credit_type=="LEASING" || $credit_type=="GUARANTEED_INVESTMENT" || $credit_type=="FACTORING")
					{
						$qr_get_cloce_fine = pg_query("select \"thcap_get_lease_fine\"('$contractID','$signDate')");
						$cloce_fine = pg_fetch_result($qr_get_cloce_fine,0);
					}
					else
					{
						$qr_get_cloce_fine = pg_query("select \"thcap_get_loan_fine\"('$contractID','$signDate')");
						$cloce_fine = pg_fetch_result($qr_get_cloce_fine,0);
					}
					if($cloce_fine == ""){$cloce_fine = "0.00";}
				}
				
				// ==================================================================================
				// ยอดรวมรับชำระ = เงินต้นคงเหลือ + ค่าปิดบัญชี + ค่าเสียหาย + ดอกเบี้ย + ค่าอื่นๆ + รับแทนอื่นๆ + เบี้ยปรับ
				// ==================================================================================
				$sum_close_account = $LastLeftPrinciple + $costclose + $plusone + $plustwo + $cloce_fine; // รวมเงิน
				
				if($chktest == 1) // ถ้าเป็น 1 แสดงว่ามีการคลิกปุ่มคำนวน
				{
					if($signDate." 23:59:59" < $maxdate or $signDate < $conDate)
					{
						$lastmoney = "ไม่สามารถคำนวณได้ เนื่องจากวันที่ต้องการจะคำนวณเป็นวันที่ก่อนเปิดสัญญา หรือก่อนการชำระครั้งล่าสุด";
					}
					else
					{
						$lastmoney = "<b>ยอดปิดบัญชี ณ วันที่ $signDate คือ ".number_format($sum_close_account,2)." บาท</b>";
					}
				}
				
			?>
				<form name="frm_fuc2" method="post" action="frm_Index.php?idno=<?php echo "$contractID"; ?>">
					<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#E0E0E0" align="center">
						<tr bgcolor="#E6FFE6" align="left" valign="top">
							<td align="left" valign="middle" colspan="5">
								<b>คำนวณยอดปิดบัญชี ณ วันที่</b>								
								<input type="text" size="12" readonly="true" style="text-align:center;" id="signDate" name="signDate" value="<?php echo $ssdate; ?>" onchange="chkdate()"/>
								<input type="checkbox" name="costclose" id="costclose" <?php if($costcloseCHK == "on"){echo "checked=\"checked\"";} ?> >รวมค่าบริการปิดบัญชี
								<input name="btnButton" id="btnButton" type="button" value="คำนวณ" onclick="document.frm_fuc2.submit()" />
								<input type="hidden" name="chkcloseaccount" value="1">
							</td>
						</tr>
						<tr bgcolor="#E6FFE6" align="left" valign="top">
							<td align="left" valign="middle" colspan="5">
								<?php if($chktest == 1)
								{
									echo "<br>";
									echo $lastmoney;
									$Installment = $LastLeftPrinciple+$money_function;
									$otherpay = $plusone+$plustwo+$costclose+$cloce_fine;
									if($signDate." 23:59:59" >= $maxdate and $signDate >= $conDate)
									{
										echo "<br><br>";
										echo "-==========  โดยมาจาก ============-";
										echo "<br>";
										echo "<br>";
										echo "___ <b>ยอดปิดสัญญาลงทุน  (  จำนวน: ".number_format($Installment,2)." )</b>__________________________________";
										echo "<br>";	
										echo "<br>";										
										echo "___ <b>ค่าอื่นๆ (  รวม: ".number_format($otherpay,2)." )</b> ___________________________________";
										echo "<br>";
										echo "•ยอดหนี้ค้างชำระอื่นๆ : ".number_format($plusone,2)." บาท";
										echo "<br>";
										echo "•ยอดรับจ่ายแทนค่าประกันภัย-อื่นๆ : ".number_format($plustwo,2)." บาท";
										echo "<br>";
										echo "•ยอดเงินบริการปิดบัญชี : ".number_format($costclose,2)." บาท";
										echo "<br>";
										
										if($cloce_fine != "" && $cloce_fine > 0.00)
										{
											echo "•ยอดเบี้ยปรับ : ".number_format($cloce_fine,2)." บาท";
											echo "<br>";
										}
										
										?>
											<br>
											<input type="button" value="พิมพ์" onclick="javascript:popU('frm_guaranteed_pdf.php?idno=<?php echo "$contractID"; ?>&signDate=<?php echo $signDate; ?>&costclose=<?php echo $costclose; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
										<?php
									}
								}
								?>
							</td>
						</tr>
					</table>
				</form>
			<?php
			}
			?>
		</div>
	</div>
</fieldset>