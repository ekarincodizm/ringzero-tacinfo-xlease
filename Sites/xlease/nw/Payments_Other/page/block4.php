<div class="maindiv2">
    <div class="maindivlabel13"><div class="divlabeltext"><span class="nonehilight">เพิ่มข้อมูลการชำระ</span></div></div>
    <center>
    <ul id="ul_tb2">
	<?php
	// หาค่าเบี้ยปรับ
	if($chk_con_type == "HIRE_PURCHASE" || $chk_con_type == "LEASING" || $chk_con_type == "GUARANTEED_INVESTMENT")
	{
		// ตรวจสอบก่อนว่ามีอัตราเบี้ยปรับหรือไม่
		$qry_chkFine = pg_query("select \"conFineRate\" from \"thcap_lease_contract\" where \"contractID\" = '$ConID' ");
		$conFineRate = pg_fetch_result($qry_chkFine,0); // อัตราเบี้ยปรับ
		
		if($conFineRate != "")
		{ // ถ้ามีอัตราเบี้ยปรับ
			$qry_Penalty = pg_query("select \"thcap_get_lease_fine\"('$ConID','$dateContact') ");
			$Penalty = pg_fetch_result($qry_Penalty,0);
		}
		
		if($Penalty != 0.00 && $Penalty != "" && $conFineRate != "")
		{
	?>
			<li>
				<ul>
					<li>
						<input type="checkbox" name="payPenalty" id="payPenalty" onChange="javascript:test();" checked hidden>
						ชำระเบี้ยปรับล่าช้า
						<input type="text" name="amtPenalty" id="amtPenalty" oncontextmenu="return false" onFocus="clearNum(id)" onBlur="checkNull(id)" onKeyUp="javascript:test();" onkeypress="check_num(event);" style="text-align: right;" value="0">
						จำนวนเบี้ยปรับค้างชำระ ณ วันที่ <?php echo $dateContact; ?> คือ <?php echo number_format($Penalty,2); ?> บาท
						<input type="hidden" name="fullPenalty" id="fullPenalty" value="<?php echo str_replace(",","",number_format($Penalty,2)); ?>">
						<input type="hidden" name="halfPenalty" id="halfPenalty" value="<?php echo str_replace(",","",number_format($Penalty/2,2)); ?>">
					</li>
					<br><br>
				</ul>
			</li>
	<?php
		}
		else
		{ // ถ้าไม่มี ให้สร้างไว้เฉยๆ ป้องกัน java scrip พัง
			echo "<input type=\"checkbox\" name=\"payPenalty\" id=\"payPenalty\" hidden>";
			echo "<input type=\"text\" name=\"amtPenalty\" id=\"amtPenalty\" value=\"0\" hidden>";
		}
	}
	else
	{
		// ตรวจสอบก่อนว่ามีอัตราเบี้ยปรับหรือไม่
		$qry_chkFine = pg_query("select \"conFineRate\" from \"thcap_mg_contract\" where \"contractID\" = '$ConID' ");
		$conFineRate = pg_fetch_result($qry_chkFine,0); // อัตราเบี้ยปรับ
		
		if($conFineRate != "")
		{ // ถ้ามีอัตราเบี้ยปรับ
			$qry_Penalty = pg_query("select \"thcap_get_loan_fine\"('$ConID','$dateContact') ");
			$Penalty = pg_fetch_result($qry_Penalty,0);
		}
		
		if($Penalty != 0.00 && $Penalty != "" && $conFineRate != "")
		{
	?>
			<li>
				<ul>
					<li>
						<input type="checkbox" name="payPenalty" id="payPenalty" onChange="javascript:test();" checked hidden>
						ชำระเบี้ยปรับล่าช้า
						<input type="text" name="amtPenalty" id="amtPenalty" oncontextmenu="return false" onFocus="clearNum(id)" onBlur="checkNull(id)" onKeyUp="javascript:test();" onkeypress="check_num(event);" style="text-align: right;" value="0">
						จำนวนเบี้ยปรับค้างชำระ ณ วันที่ <?php echo $dateContact; ?> คือ <?php echo number_format($Penalty,2); ?> บาท
						<input type="hidden" name="fullPenalty" id="fullPenalty" value="<?php echo str_replace(",","",number_format($Penalty,2)); ?>">
						<input type="hidden" name="halfPenalty" id="halfPenalty" value="<?php echo str_replace(",","",number_format($Penalty/2,2)); ?>">
					</li>
					<br><br>
				</ul>
			</li>
	<?php
		}
		else
		{ // ถ้าไม่มี ให้สร้างไว้เฉยๆ ป้องกัน java scrip พัง
			echo "<input type=\"checkbox\" name=\"payPenalty\" id=\"payPenalty\" hidden>";
			echo "<input type=\"text\" name=\"amtPenalty\" id=\"amtPenalty\" value=\"0\" hidden>";
		}
	}
	?>
        <li>
            <ul>
                <li class="short1">รับชำระ เงินพักรอตัดรายการ ( เงินรับฝาก )<br>เป็นจำนวนเงิน</li>
                <li class="short1">รับชำระ เงินค้ำประกันการชำระหนี้<br>เป็นจำนวนเงิน</li>
            </ul>
        </li>
        <li>
            <ul>
                <li class="short1">
                    <input type="text" name="money_Deposit" onKeyUp="javascript:test();" oncontextmenu="return false" onBlur="checkNull(id)" onFocus="clearNum(id)" id="money_Deposit" onkeypress="check_num(event);" <?php if($bankRevAccID=="998"){echo "readOnly style=\"text-align:right; background-Color:#CCCCCC;\"";}else{echo "style=\"text-align:right;\"";} ?> value="0">
                </li>
                <li class="short1">
                    <input type="text" name="money_Guarantee" id="money_Guarantee" oncontextmenu="return false" onkeypress="check_num(event);" onBlur="checkNull(id)" onFocus="clearNum(id)"  onkeyup="javascript:test();" <?php if($bankRevAccID=="997"){echo "readOnly style=\"text-align:right; background-Color:#CCCCCC;\"";}else{echo "style=\"text-align:right;\"";} ?> value="0">
                </li>
            </ul>
        </li>
        <li>
            <ul>
                <li class="short">ชำระผ่าน</li>
                <li class="short">วันที่จ่าย</li>
				<li class="short">เวลาที่จ่าย</li>
                <li class="short">จำนวนเงินที่ได้รับสุทธิ</li>
				<li class="short">จำนวนภาษีหัก ณ ที่จ่าย</li>
				<li class="short">จำนวนเงินรวม</li>
            </ul>
        </li>
        <li>
            <ul>
                <li class="short">
					<select name="byChannelPost" id="byChannelPost" class="textbox_tb3" onchange="checkdate(); chkChannel();">
                        <?php
							//ดึงข้อมูลจากฐานข้อมูล
							if($statusLock==1)
							{ // ถ้าเป็นเงินโอน
								$qrychannel=pg_query("select \"BID\",\"BAccount\",\"BName\",\"isTranPay\" from \"BankInt\" where \"BID\"='$bankRevAccID'");
							}
							elseif($statusLock==2)
							{ // ถ้าเป็นการค้นหาจากหน้าหลัก
								if($haveWait997 == "have"){$whereMoney = $whereMoney." and \"BID\" <> '997'";}
								if($haveWait998 == "have"){$whereMoney = $whereMoney." and \"BID\" <> '998'";}
								$qrychannel=pg_query("select \"BID\",\"BAccount\",\"BName\",\"isTranPay\" from \"BankInt\" where \"BID\"='$bankRevAccID' $whereMoney ");
							}
							else
							{
								// ตรวจสอบก่อนว่าใช้เงินพักงานค้ำได้หรือไม่
								if($haveWait997 == "have"){$whereMoney = $whereMoney." and \"BID\" <> '997'";}
								if($haveWait998 == "have"){$whereMoney = $whereMoney." and \"BID\" <> '998'";}
								$qrychannel=pg_query("select \"BID\",\"BAccount\",\"BName\",\"isTranPay\" from \"BankInt\" where \"BCompany\"='THCAP' and \"isChannel\"='1' and \"isSelectable\" = '1' $whereMoney order by \"BID\"");
                            }
							while($reschn=pg_fetch_array($qrychannel)){
                                list($BID,$BAccount,$BName,$isTranPay)=$reschn;
								?>
                                <option value=<?php echo $BID.",$isTranPay";?> <?php if($BID==$bankRevAccID){ echo "selected";} ?>><?php echo "$BAccount-$BName"; ?></option>
								<?php
                            }
                        ?>
                    </select>
                </li>
				<li class="short">
					<?php 
					
					if($statusLock==1)
					{ 
					?>
						<input type="text" id="receiveDatePost" name="receiveDatePost"  value="<?php echo $dateContact; ?>" size="15" style="text-align: center;" class="textbox_tb3" readonly="true">					
					<?php
					}
					elseif($statusLock==2)
					{ 
					?>
						<input type="text" id="receiveDatePost" name="receiveDatePost" onChange="CalWhtMain()"  value="<?php echo $dateContact; ?>" size="15" style="text-align: center;" class="textbox_tb3" readonly="true">					
					<?php
					}
					else
					{
					?>
						<input type="text" id="receiveDatePost" name="receiveDatePost" onChange="CalWhtMain()" value="<?php echo nowDate(); ?>" size="15" style="text-align: center;" class="textbox_tb3">
					<?php
					}
					?>
				</li>
                <li class="short">
					<?php 
					
					if($statusLock==1)
					{
						$datenow=nowDate();
					?>
						<input type="hidden" name="datenow" id="datenow" value="<?php echo $datenow;?>">
						<input type="text" name="timeStamp"  value="<?php echo $timeStamp; ?>" size="15" style="text-align: center;" class="textbox_tb3" readonly="true">					
					<?php
					}
					elseif($statusLock==2)
					{
						$datenow=nowDate();
					?>
						<input type="hidden" name="datenow" id="datenow" value="<?php echo $datenow;?>">
						
						<?php
						if($dateContact < $datenow)
						{ // ถ้าวันที่เลือก น้อยกว่าวันที่ปัจจุบัน
						?>
							<select id="timeStamp" name="timeStamp">
								<option value="00:00:00">00:00:00</option>
								<option value="23:59:59">23:59:59</option>
							</select>
						<?php
						}
						else
						{
						?>
							<input type="text" id="timeStamp" name="timeStamp" onChange="CalWhtMain()" onLoad="CalWhtMain()" value="<?php if($datenow != $dateContact){echo "23:59:59";} ?>" size="15" style="text-align: center;" class="textbox_tb3" readOnly>
						<?php
						}
					}
					else
					{
						$datenow=nowDate();
					?>
						<input type="hidden" name="datenow" id="datenow" value="<?php echo $datenow;?>">
						<input type="text" id="timeStamp" name="timeStamp" onChange="CalWhtMain()" value="" size="15" style="text-align: center;" class="textbox_tb3">
					<?php
					}
					?>
				</li>
				<li class="short">
					<input type="text" name="receiveAmountPost3" id="receiveAmountPost3" style="text-align: right;background-Color:#CCCCCC;" readonly value="0">
				</li>
				<li class="short">
					<input type="text" name="receiveAmountPost4" id="receiveAmountPost4" style="text-align: right;background-Color:#CCCCCC;" readonly value="0">
				</li>
                <li class="short">
                    <input type="hidden" name="receiveAmountPost" id="receiveAmountPost">
					<input type="hidden" name="bankRevAmt" id="bankRevAmt" value="<?php echo $bankRevAmt;?>">
					<input type="hidden" name="statusPay" id="statusPay" value="<?php echo $statusPay;?>">
					<input type="hidden" name="statusLock" id="statusLock" value="<?php echo $statusLock;?>">
					<input type="hidden" name="revTranID" id="revTranID" value="<?php echo $revTranID?>">
					<input type="hidden" name="contractUseMoney" id="contractUseMoney" value="<?php echo $contractUseMoney?>">
					<input type="text" name="receiveAmountPost2" id="receiveAmountPost2" style="text-align: right;background-Color:#CCCCCC;" readonly value="0">
				</li>
				<li class="shortp">
					<?php if($statusLock==1){ echo "(จำนวนเงินที่สามารถใช้ได้  ".number_format($bankRevAmt,2)." บาท)"; } ?>
                </li>
				<li class="shortp">				
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="balancefinance"></span>
                </li>
            </ul>
        </li>
		<li>
			<span id="chkChannel"></span>
		</li>
    </ul>
    <div class="divtb1_both33">
        <input type="hidden" id="statusOverWht" value="0">
		<input type="checkbox" name="printvat" value="1" <?php if($typecus==2){ echo "checked"; } ?>><font color="#000" size="2">พิมพ์ใบกำกับภาษี</font>
        <span id="tb1_chkbox1"><input type="button" value="รับชำระ" id="submitButton" onClick="showdetails()"></span>
        <span id="tb1_chkbox2"><input type="button" value="กลับไปหน้าค้นหา" onClick="window.location='frm_Index.php'"></span>
    </div>
    </center>
</div>

<script>
function chkChannel() // หาจำนวนเงินที่สามารถใช้ได้ของเงินพักและเงินค้ำ
{
	var getHoldMoneyType = '<?php echo $res_getHoldMoneyType; ?>'; // เงินพักรอตัดรายการ
	var getSecureMoneyType = '<?php echo $res_getSecureMoneyType; ?>'; // เงินค้ำประกัน
	
	if(document.getElementById("byChannelPost").value == getSecureMoneyType+',0' || document.getElementById("byChannelPost").value == getSecureMoneyType+',1')
	{
		$.post("../thcap/thcap_change_money/check_money.php",{
			id : '<?php echo $ConID; ?>',
			moneyType : getSecureMoneyType
		},
		function(dataMoney){
			$("#chkChannel").text("เงินค้ำประกัน ที่สามารถใช้ได้คือ "+dataMoney+" บาท");
		});
	}
	else if(document.getElementById("byChannelPost").value == getHoldMoneyType+',0' || document.getElementById("byChannelPost").value == getHoldMoneyType+',1')
	{
		$.post("../thcap/thcap_change_money/check_money.php",{
			id : '<?php echo $ConID; ?>',
			moneyType : getHoldMoneyType
		},
		function(dataMoney){
			$("#chkChannel").text("เงินพักรอตัดรายการ ที่สามารถใช้ได้คือ "+dataMoney+" บาท");
		});
	}
	else if(document.getElementById("contractUseMoney").value != '' || document.getElementById("contractUseMoney").value != '')
	{
		$("#chkChannel").text("เลขที่สัญญาที่ใช้เงิน คือ "+document.getElementById("contractUseMoney").value);
	}
	else
	{
		$("#chkChannel").text("");
	}
}
</script>