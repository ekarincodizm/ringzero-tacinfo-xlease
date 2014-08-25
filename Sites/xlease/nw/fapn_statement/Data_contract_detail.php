<?php 
$cur_path = redirect($_SERVER['PHP_SELF'],'nw/thcap');
if($contractID != "") // ถ้ามีการส่งค่ามา  // header
{

?>
<fieldset>
	<legend><B>ข้อมูลวงเงิน</B></legend>
	<div align="center">
		<div id="panel1" align="left" style="margin-top:10px">		
		<?php
			$sql_head1=pg_query("select * from \"vthcap_contract_creditline\" where \"contractID\" = '$contractID' ");
			$rowhead=pg_num_rows($sql_head1);

			while($result=pg_fetch_array($sql_head1))
			{
				$conIntRate = $result["conLoanIniRate"]; // INT.ปกติ (ดอกเบี้ยเริ่มแรก)
				$conMaxRate = $result["conLoanMaxRate"]; // INT.ผิดนัด (ดอกเบี้ยสูงสุด)
				$conDate = $result["conDate"]; //วันทำสัญญา
				$conCredit = $result["conCredit"]; //วงเงินสินเชื่อ
			}
			
			//path เริ่มที่ root สำหรับ link ไปหน้าตรวจสอบข้อมูลลูกค้า
			$pathroot=redirect($_SERVER['PHP_SELF'],'nw/search_cusco');
	
			//หาชื่อผู้กู้หลัก (บางกรณีมีได้หลายคน)
			$qry_cus0=pg_query("select * from \"vthcap_ContactCus_detail\"
			where  \"contractID\" = '$contractID' and \"CusState\" = '0'");
			$numcus0=pg_num_rows($qry_cus0);
			$i=1;
			$namecus0="";
			
			while($resadd=pg_fetch_array($qry_cus0)){
				$cus0=trim($resadd["thcap_fullname"]);
				$cusidmain=trim($resadd["CusID"]);
				$pathmain = "(<a style=\"cursor:pointer;\" onclick=\"javascipt:popU('$pathroot/index.php?cusid=$cusidmain','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');\"><font color=\"#FF1493\"><u>$cusidmain</u></font></a>)";

				if($numcus0==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
					$namecus0=$pathmain." ".$cus0;
				}else{
					if($i==$numcus0){
						$namecus0=$namecus0.$pathmain." ".$cus0;
					}else{
						$namecus0=$namecus0.$pathmain." ".$cus0.", ";
					}
				}
				$i++;
			}

			//หาชื่อผู้กู้ร่วม
			$qry_cus1=pg_query("select * from \"vthcap_ContactCus_detail\"
			where \"contractID\" = '$contractID' and \"CusState\" = '1'");
			$numcus1=pg_num_rows($qry_cus1);
			$i=1;
			$namecus1="";
			while($resco=pg_fetch_array($qry_cus1)){
				$cus1=trim($resco["thcap_fullname"]);
				$cusidco=trim($resco["CusID"]);
				$pathco = "(<a style=\"cursor:pointer;\" onclick=\"javascipt:popU('$pathroot/index.php?cusid=$cusidco','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');\"><font color=\"#FF1493\"><u>$cusidco</u></font></a>)";

				if($numcus1==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
					$namecus1=$pathco." ".$cus1;
				}else{
					if($i==$numcus1){
						$namecus1=$namecus1.$pathco." ".$cus1;
					}else{
						$namecus1=$namecus1.$pathco." ".$cus1.", ";
					}
				}
				$i++;
			}
			
			//หาผู้ค้ำประกัน
			$qry_cus2=pg_query("select * from \"vthcap_ContactCus_detail\"
			where \"contractID\" = '$contractID' and \"CusState\" = '2'");
			$numcus2=pg_num_rows($qry_cus2);
			$i=1;
			$namecus2="";
			while($resGua=pg_fetch_array($qry_cus2)){
				$cus2=trim($resGua["thcap_fullname"]);
				$cusidgua=trim($resGua["CusID"]);
				$pathgua = "(<a style=\"cursor:pointer;\" onclick=\"javascipt:popU('$pathroot/index.php?cusid=$cusidgua','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');\"><font color=\"#FF1493\"><u>$cusidgua</u></font></a>)";

				if($numcus2==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
					$namecus2=$pathgua." ".$cus2;
				}else{
					if($i==$numcus2){
						$namecus2=$namecus2.$pathgua." ".$cus2;
					}else{
						$namecus2=$namecus2.$pathgua." ".$cus2.", ";
					}
				}
				$i++;
			}
			
			//หาวงเงินสินเชื่อใช้ไป 		
			$qryref=pg_query("select a.\"contractID\" from \"vthcap_contract_creditRef_all\" a
				left join \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\" where \"contractCredit\"='$contractID'");
				while($result=pg_fetch_array($qryref)){
					$contractID1 = $result["contractID"];	

					//หากสัญญาที่ปิดบัญชีแล้วจะไม่นำมาคิด
					$qryconclose=pg_query("SELECT thcap_checkcontractcloseddate('$contractID1')");
					$reconclose=pg_fetch_array($qryconclose);
					$conclosestae=$reconclose["thcap_checkcontractcloseddate"];
					if($conclosestae == "")
					{
						// ชื่อประเภทสินเชื่อแบบเต็ม
						$qry_chk_con_type = pg_query("select \"thcap_get_creditType\"('$contractID1') ");
						$chk_con_type = pg_fetch_result($qry_chk_con_type,0);
						
						if($chk_con_type == "HIRE_PURCHASE" || $chk_con_type == "LEASING" || $chk_con_type == "GUARANTEED_INVESTMENT" || $chk_con_type == "FACTORING")
						{
							$qry_getloan=pg_query("SELECT \"thcap_get_lease_totalleft\"('$contractID1') ");
							list($loanbalanceamt) = pg_fetch_array($qry_getloan);
						}
						else
						{
							$qry_getloan=pg_query("SELECT \"thcap_getLoanBalanceAmt\"('$contractID1') ");
							list($loanbalanceamt) = pg_fetch_array($qry_getloan);
						}
							
						$loanbalanceamtsum += $loanbalanceamt;
					}		
				}
				
			//วงเงินสินเชื่อคงเหลือ 
			$conCreditbalance = $conCredit - $loanbalanceamtsum;
			
			// วันที่จ่ายล่าสุด
			$qry_maxReceiveDate = pg_query("select max(\"receiveDate\") from \"thcap_v_receipt_details\"
											where \"contractID\" in(select distinct a.\"contractID\"
											from \"vthcap_contract_creditRef_all\" a
											left join \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\" 
											where \"contractCredit\"='$contractID')");
			$maxReceiveDate = pg_fetch_result($qry_maxReceiveDate,0);
		?>
		<?php  //คำนวนหาค่าเฉลี่ยระยะตั๋วเงิน
				$i=0;
				$c=0;
				$nowdate=nowDate();
				$qryref1=pg_query("select a.\"contractID\",\"conStartDate\"
				from \"vthcap_contract_creditRef_all\" a
				left join \"thcap_contract\" b on a.\"contractID\"=b.\"contractID\" 
				where \"contractCredit\"='$contractID'");
				$numref1=pg_num_rows($qryref1);
				$sumconLoanAmt=0; //รวมยอดกู้
				$sumconMinPay=0; //รวมยอดค่าใช้จ่ายขั้นต่ำ
				$Result_SToE=0;//ระยะห่างระหว่างวันเริ่มกู้ถึงวันที่ครบกำหนดสัญญาทั้งหมด
				$ResultClose_SToE=0;//ระยะห่างระหว่างวันเริ่มกู้ถึงวันที่ครบกำหนดสัญญาทั้งหมด (ปิดบัญชี)
				while($result=pg_fetch_array($qryref1)){
					$contractID2 = $result["contractID"]; //สัญญาที่ใช้วงเงินนั้นๆอยู่
					$conStartDate = $result["conStartDate"]; //วันที่เริ่มกู้
					//หากสัญญาที่ปิดบัญชีแล้วจะเป็นแถบสีเทา
					$qryconclose=pg_query("SELECT thcap_checkcontractcloseddate('$contractID2')");
					$reconclose=pg_fetch_array($qryconclose);
					$conclosestae=$reconclose["thcap_checkcontractcloseddate"];

					//วันที่ครบกำหนดชำระ
					$qryenddate=pg_query("select \"conEndDate\" from \"thcap_mg_contract\" where \"contractID\"='$contractID2'");
					$resenddate=pg_fetch_array($qryenddate);
					$conEndDate=$resenddate["conEndDate"];
					$rowchk=pg_num_rows($qryenddate);
					if($conEndDate==""){//ถ้าไม่พบวันที่ครบกำหนดชำระ ให้ ค้นหา จาก  thcap_lease_contract แทน
						$qryenddate=pg_query("select \"conEndDate\" from \"thcap_lease_contract\" where \"contractID\"='$contractID2'");
						$resenddate=pg_fetch_array($qryenddate);
						$conEndDate=$resenddate["conEndDate"];
					}
					
					
					if($conclosestae != ""){
						$i++;
						if($conEndDate !=""){
						//หาระยะห่างระหว่างวันของวันที่เริ่มกู้ถึงวันที่ครบกำหนดชำระสัญญาที่ปิดบัญชีแล้ว
						$dayStartToEndClose= DateDiff_2P($conStartDate,$conEndDate);
						$Result_SToE += $dayStartToEndClose; 
						}else{
							$numrowchk ++;
						}
						//หาระยะห่างระหว่างวันของวันที่เริ่มกู้ถึงวันทีี่ปิดบัญชี
						$dayStartToClose = DateDiff_2P($conStartDate,$conclosestae);
						$Result_SToC += $dayStartToClose;
					}
					if($conclosestae == ""){
						$c++;
						if($conEndDate !=""){
						//หาระยะห่างระหว่างวันของวันที่เริ่มกู้ถึงวันที่ครบกำหนดชำระสัญญา
						$dayStartToEnd = DateDiff_2P($conStartDate,$conEndDate);
						$ResultClose_SToE += $dayStartToEnd; 
						}
						else{
							$numrowchk ++;
						}
					}
				}
				if($i>0 or $c>0){
					 if(($numref1>0) and($numref1==$numrowchk)){
						$AverageDayStartToEnd = "ไม่สามารถคำนวณได้ เนื่องจาก ไม่มีวันที่ครบกำหนดชำระ";
					}else{
						$AverageDayStartToEnd = ceil(($Result_SToE+$ResultClose_SToE)/($i+$c))." วัน"; //ค่าเฉลี่ยระยะตั๋วเงินระหว่าง วันที่เริ่มกู้ถึงวันที่ครบกำหนดชำระ
					}
				}else {
					$AverageDayStartToEnd = "ไม่มีการคำนวณ";
				}
				if($i>0){
					$AverageDayStartToClose = ceil($Result_SToC/$i)." วัน"; //ค่าเฉลี่ยระยะตั๋วเงินระหว่าง วันที่เริ่มกู้ถึงวันที่ปิดบัญชี
				} else {
					$AverageDayStartToClose = "ไม่มีการคำนวณ";
				}
	//หารหัสเงินพัก
	$holdmoney_qry = pg_query("select account.\"thcap_getHoldMoneyType\"('$contractID','1')");
	list($holdmoney) = pg_fetch_array($holdmoney_qry);
	
	//หารหัสเงินค้ำ
	$securmoney_qry = pg_query("select account.\"thcap_getSecureMoneyType\"('$contractID','1')");
	list($securmoney) = pg_fetch_array($securmoney_qry);
	
	//หาเงินค้ำประกัน
	$sqlguan = pg_query("SELECT \"contractBalance\" FROM vthcap_contract_money where \"moneyType\" = account.\"thcap_getSecureMoneyType\"('$contractID','1')::smallint and \"contractID\" = '$contractID'");
	list($moneyguan) = pg_fetch_array($sqlguan);
	
	//เงินพักรอตัดรายการ
	$sqlcut = pg_query("SELECT \"contractBalance\" FROM vthcap_contract_money where \"moneyType\" = account.\"thcap_getHoldMoneyType\"('$contractID','1')::smallint and \"contractID\" = '$contractID'");
	list($moneycut) = pg_fetch_array($sqlcut);
	
		?>
		<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr height="20">
			<td align="right" bgcolor="#79BCFF" width="250"><b>เลขที่สัญญา</b></td>
			<td bgcolor="#D5EFFD" width="10" align="center">:</td><td bgcolor="#D5EFFD" colspan="7">
			<?php echo $contractID; ?></td>
		</tr>
		<tr height="20">
			<td align="right" bgcolor="#79BCFF"><b>ชื่อผู้กู้หลัก</b></td><td bgcolor="#D5EFFD" align="center">:</td><td bgcolor="#D5EFFD" colspan="7"><?php echo $namecus0; ?></td>
		</tr>
		<tr height="20">
			<td align="right" bgcolor="#79BCFF"><b>ผู้กู้ร่วม</b></td><td bgcolor="#D5EFFD" align="center">:</td><td bgcolor="#D5EFFD" colspan="7"><?php echo $namecus1; ?></td>
		</tr>
		<tr height="20">
			<td align="right" bgcolor="#79BCFF"><b>ผู้ค้ำประกัน</b></td><td bgcolor="#D5EFFD" align="center">:</td><td bgcolor="#D5EFFD" colspan="7"><?php echo $namecus2; ?></td>
		</tr>
		<tr height="20">
			<td align="right" bgcolor="#FFC1C1"><b>INT.ปกติ (ดอกเบี้ยเริ่มแรก%)</b></td><td bgcolor="#FFEAEA" align="center">:</td><td bgcolor="#FFEAEA" width="250"><?php echo $conIntRate; ?></td>
			<td align="right" bgcolor="#FFC1C1" width="150"><b>วันที่ทำสัญญา</b></td><td bgcolor="#FFEAEA" align="center" width="10">:</td><td bgcolor="#FFEAEA" colspan="4"><?php echo $conDate; ?></td>
		</tr>
		<tr height="20">
			<td align="right" bgcolor="#FFC1C1"><b>INT.ผิดนัด (ดอกเบี้ยสูงสุด%)</b></td><td bgcolor="#FFEAEA" align="center">:</td><td bgcolor="#FFEAEA"><?php echo $conMaxRate; ?></td>
			<td align="right" bgcolor="#FFC1C1"><b>วงเงินสินเชื่อ (บาท)</b></td><td bgcolor="#FFEAEA" align="center">:</td><td bgcolor="#FFEAEA" colspan="4"><?php echo number_format($conCredit,2); ?></td>
		</tr>
		<tr height="20">
			<td align="right" bgcolor="#FFFF33"><b>วงเงินสินเชื่อคงเหลือ (บาท)</b></td><td bgcolor="#FFFF99" align="center">:</td><td bgcolor="#FFFF99"><?php echo number_format($conCreditbalance,2);; ?></td>
			<td align="right" bgcolor="#FFFF33"><b>วงเงินสินเชื่อใช้ไป รวมดอกเบี้ยถึงวันนี้(บาท)</b></td><td bgcolor="#FFFF99" align="center">:</td><td bgcolor="#FFFF99" colspan="4"><?php echo number_format($loanbalanceamtsum,2); ?></td>
		</tr>
		<tr height="20">
			<td align="right" bgcolor="#FFC1C1"><b>ค่าเฉลี่ยระยะตั๋วเงิน <br>(วันที่เริ่มกู้-วันที่ครบกำหนดชำระ)</b></td><td bgcolor="#FFEAEA" align="center">:</td><td bgcolor="#FFEAEA"><?php echo $AverageDayStartToEnd; ?></td>
			<td align="right" bgcolor="#FFC1C1"><b>ค่าเฉลี่ยระยะตั๋วเงิน <br>(วันที่เริ่มกู้-วันที่ปิดบัญชี)</b></td><td bgcolor="#FFEAEA" align="center">:</td><td bgcolor="#FFEAEA" colspan="4"><?php echo $AverageDayStartToClose; ?></td>
		</tr>
		<tr height="20">
			<td align="right" bgcolor="#79BCFF"><b>วันเวลาที่รับชำระล่าสุด</b></td><td bgcolor="#D5EFFD" align="center">:</td><td bgcolor="#D5EFFD"><?php echo $maxReceiveDate; ?></td>
			<td align="right" bgcolor="#fcd432"><b>เงินค้ำประกันสัญญา</b></td><td bgcolor="#fdea9c">:</td><td bgcolor="#fdea9c"><?php echo number_format($moneyguan,2); ?> บาท <img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('<?php echo $cur_path; ?>/show_money_balance_history.php?contractID=<?php echo $contractID; ?>&moneyType=<?php echo $securmoney; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=700')" style="cursor:pointer;" /></td>
			<td align="right" bgcolor="#fcd432"><b>เงินพักรอตัดรายการ</b></td><td bgcolor="#fdea9c">:</td><td bgcolor="#fdea9c"><?php echo number_format($moneycut,2); ?> บาท <img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('<?php echo $cur_path; ?>/show_money_balance_history.php?contractID=<?php echo $contractID; ?>&moneyType=<?php echo $holdmoney; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=700')" style="cursor:pointer;" /></td>
		</tr>
		</table>
		</div>
	</div>
</fieldset>
<?php } ?>

