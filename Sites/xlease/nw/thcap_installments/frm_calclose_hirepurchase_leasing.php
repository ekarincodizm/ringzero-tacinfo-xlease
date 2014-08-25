<script type="text/javascript">
function check_num(e)
{ // ให้พิมพ์ได้เฉพาะตัวเลขและจุด
    var key;
    if(window.event)
	{
        key = window.event.keyCode; // IE
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			window.event.returnValue = false;
		}
    }
	else
	{
        key = e.which; // Firefox       
		if(key <= 57 && key != 33 && key != 34 && key != 35 && key != 36 && key != 37 && key != 38 && key != 39 && key != 40 && key != 41 && key != 42
			&& key != 43 && key != 44 && key != 45 && key != 47)
		{
			// ถ้าเป็นตัวเลขหรือจุดสามารถพิมพ์ได้
		}
		else
		{
			key = e.preventDefault();
		}
	}
}

function check_casa()
{ // ตรวจสอบการติ๊กค่าซาก
	if(document.getElementById("casa").checked == true)
	{
		//alert('test');
		document.getElementById("casaValue").readOnly = false;
		document.getElementById("casaValue").style.backgroundColor='#FFFFFF';
	}
	else
	{
		document.getElementById("casaValue").value = '';
		document.getElementById("casaValue").readOnly = true;
		document.getElementById("casaValue").style.backgroundColor='#CCCCCC';
	}
}
</script>

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
				if($signDate==""){$signDate=nowDate();}
				
				$casaCHK = $_POST["casa"]; // ถ้าเป็น on แสดงว่ามีการกำหนดอัตราค่าซาก
				$casaValuePost = $_POST["casaValue"]; // % ค่าซากที่รับมา
				
				//หาวันที่ชำระค่างวดล่าสุด  เพื่อนำไปตรวจสอบการปิดบัญชี เพราะจะต้องปิดหลังจากที่ชำระล่าสุด
				$qrydate=pg_query("select max(date(\"receiveDate\")) from thcap_v_receipt_otherpay  
				where \"contractID\"='$contractID' and \"typePayID\"=account.\"thcap_mg_getMinPayType\"(\"contractID\")
				group by \"contractID\" ");
				list($maxdate)=pg_fetch_array($qrydate);

				if($maxdate==""){
					$maxdate='0000-00-00 00:00:00';
				}
				
				// ==================================================================================
				// นำข้อมูลต่างๆของสัญญาที่เป็นการตั้งค่าปัจจุบัน
				// ==================================================================================
				$sql_chkdate = pg_query("select * from public.\"thcap_lease_contract\" where \"contractID\" = '$contractID' ");
				while($resultchkdate=pg_fetch_array($sql_chkdate))
				{
					$conDate = $resultchkdate["conDate"]; // วันที่ทำสัญญา
				}
				
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
				
				// หาค่างวด
				$qry_Installment = pg_query("select \"thcap_amountown\"('$contractID','$signDate','3')");
				$Installment = pg_fetch_result($qry_Installment,0);
				if($Installment == ""){$Installment = 0.00;}
				
				// หาค่าซาก
				$qer_casa = pg_query("select \"conResidualValue\" from \"thcap_mg_contract_current\" where \"contractID\" = '$contractID'
									and \"rev\" = (select max(\"rev\") from \"thcap_mg_contract_current\" where \"contractID\" = '$contractID'
									and \"effectiveDate\"::date <= '$signDate'::date)");
				$casaValue = pg_fetch_result($qer_casa,0);
				$casaValueFromDataBase = pg_fetch_result($qer_casa,0);
				
				// ถ้าระบุอัตราค่าซาก
				if($casaValuePost != "" && $casaValuePost > 0)
				{
					$qry_conFinanceAmount = pg_query("select \"conFinanceAmount\" from \"thcap_contract\" where \"contractID\" = '$contractID' ");
					$conFinanceAmount = pg_result($qry_conFinanceAmount,0);
					
					if($conFinanceAmount != "" && $conFinanceAmount > 0)
					{
						$casaValue = $conFinanceAmount * ($casaValuePost / 100);
					}
					else
					{
						$casaValue = 0.00;
					}
				}
				
				if($casaValue == ""){$casaValue = 0.00;}
				
				// ==================================================================================
				// ยอดรวมรับชำระ = เงินต้นคงเหลือ + ค่าอื่นๆ + รับแทนอื่นๆ + ค่าซาก
				// ==================================================================================
				$sum_close_account = $Installment + $plusone + $plustwo + $casaValue; // รวมเงิน
				
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
								<?php
								if($casaValueFromDataBase == "" || $casaValueFromDataBase == 0)
								{
								?>
									<input type="checkbox" name="casa" id="casa" <?php if($casaCHK == "on"){echo "checked=\"checked\"";} ?> onChange="check_casa();" >ค่าซาก <input type="textbox" name="casaValue" id="casaValue" style="WIDTH:20px; background-color:#CCCCCC;" <?php if($casaValuePost != ""){echo "value=\"$casaValuePost\"";} ?> onkeypress="check_num(event);" readOnly> %
								<?php
								}
								else
								{
								?>
									<input type="checkbox" name="casa" id="casa" hidden ><input type="text" name="casaValue" id="casaValue" hidden>
								<?php
								}
								?>
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
									
									$otherpay = $plusone+$plustwo+$casaValue;
									if($signDate." 23:59:59" >= $maxdate and $signDate >= $conDate)
									{
										echo "<br><br>";
										echo "-==========  โดยมาจาก ============-";
										echo "<br>";
										echo "<br>";
										echo "___ <b>ค่างวด  (  รวม: ".number_format($Installment,2)." )</b>__________________________________";
										echo "<br>";	
										echo "<br>";										
										echo "___ <b>ค่าอื่นๆ (  รวม: ".number_format($otherpay,2)." )</b> ___________________________________";
										echo "<br>";
										echo "•ยอดหนี้ค้างชำระอื่นๆ : ".number_format($plusone,2)." บาท";
										echo "<br>";
										echo "•ยอดรับจ่ายแทนค่าประกันภัย-อื่นๆ : ".number_format($plustwo,2)." บาท";
										echo "<br>";
										echo "•ยอดเงินค่าซาก : ".number_format($casaValue,2)." บาท";
										echo "<br>";
										
										?>
											<br>
											<input type="button" value="พิมพ์" onclick="javascript:popU('frm_hirepurchase_leasing_pdf.php?idno=<?php echo "$contractID"; ?>&signDate=<?php echo $signDate; ?>&casa=<?php echo $casaValue; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
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

<script>
check_casa();
</script>