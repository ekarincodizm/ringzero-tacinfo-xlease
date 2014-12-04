<?php 
if($contractID != "") // ถ้ามีการส่งค่ามา  // header
{
	function mysort1($x, $y) // เรียงข้อมูล key1 จากน้อยไปมาก
	{
		return strcasecmp($x['key1'],$y['key1']);
	}
?>
<script type="text/javascript">
function popUPO(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<fieldset>
<?php 
//ใส่รูปมุมบนซ้ายของข้อมูลสัญญา
$sql_showPic = pg_query("select \"conSubType_serial\" from \"thcap_contract\" where \"contractID\" = '$contractID'");
$showPic = pg_fetch_result($sql_showPic,0);
	
	if($showPic != "")
	{	// หารูปภาพ
					$qry_imgSubtype = pg_query("select \"conSubType_name\", \"conSubType_iconpath\" from \"thcap_contract_subtype\" where \"conSubType_serial\" = '$showPic' ");
					while($res_Subtype = pg_fetch_array($qry_imgSubtype))
					{
						$conSubType_name = $res_Subtype["conSubType_name"]; // ชื่อ
						$conSubType_iconpath = $res_Subtype["conSubType_iconpath"]; // path file
					}
					
					if($conSubType_iconpath != "")
					{
						if(file_exists("../upload/consubtype_icon/$conSubType_iconpath"))
						{ // ถ้ามีไฟล์นั้นอยู่จริง
						  //echo "ประเภทสัญญาย่อย  : <img src=\"../upload/consubtype_icon/$conSubType_iconpath\" width=\"180\" height=\35\" >";
							$imgtexttype="<img src=\"../upload/consubtype_icon/$conSubType_iconpath\" width=\"180\" height=\35\" >";
						}
						else
						{ // ถ้าไม่พบไฟล์
							$imgtexttype="ประเภทสัญญาย่อย  : $conSubType_name";
						}
					}
					else
					{
						echo $conSubType_name;
					}
				
	}
?>	
	<legend><B>ข้อมูลสัญญา</B></legend>

	<div align="center">
		
		<div id="panel1" align="left" style="margin-top:10px">
<?php
$nowday = nowDate();
$cur_path = redirect($_SERVER['PHP_SELF'],'nw/thcap');

$sql_showPic = pg_query("select \"conSubType_serial\" from \"thcap_contract\" where \"contractID\" = '$contractID'");



//เบี้ยปรับปัจจุบัน
$qr_get_lease_fine=pg_query("select \"thcap_get_lease_fine\"('$contractID')");
$rs_get_lease_fine = pg_fetch_array($qr_get_lease_fine);
list($lease_fine) = $rs_get_lease_fine;

// ดอกเบี้ยถึงวันนี้
	$InterestInDay = pg_query("SELECT \"thcap_cal_InterestToDateFromLastPay\"('$contractID','$nowday')");
	$restInDay = pg_fetch_array($InterestInDay);
	list($restInDay_function) = $restInDay;

	$vfocusdate = nowDate();
	
	$sql_head1=pg_query("SELECT
							a.\"conLoanIniRate\",
							a.\"conLoanMaxRate\",
							a.\"conDate\",
							a.\"conStartDate\",
							a.\"conRepeatDueDay\",
							a.\"conTerm\",
							a.\"conMinPay\",
							a.\"conIntCurRate\",
							a.\"conFirstDue\",
							b.\"conFacFee\",
							b.\"conFinAmtExtVat\",
							b.\"case_owners_id\"
						FROM
							\"thcap_lease_contract\" a,
							\"thcap_contract\" b
						WHERE
							a.\"contractID\" = b.\"contractID\" AND
							a.\"contractID\" = '$contractID' ");
	$rowhead=pg_num_rows($sql_head1);
	$i = 1;
	while($result=pg_fetch_array($sql_head1))
	{
		$conLoanIniRate = $result["conLoanIniRate"]; // อัตราดอกเบี้ยเริ่มแรก
		$conLoanMaxRate = $result["conLoanMaxRate"]; // อัตราดอกเบี้ยสูงสุด
		$conDate = $result["conDate"];
		$conStartDate = $result["conStartDate"];
		$conRepeatDueDay = $result["conRepeatDueDay"];
		$conLoanAmt = $result["conFinAmtExtVat"];
		$conTerm = $result["conTerm"];
		$conMinPay = $result["conMinPay"];
		$conIntCurRate = $result["conIntCurRate"]; // อัตราดอกเบี้ยปัจจุบัน
		$conFacFee = $result["conFacFee"]; // ค่าธรรมเนียมในการจัดการตั๋วสัญญาใช้เงิน
		$conFirstDue = $result["conFirstDue"]; // วันที่ครบกำหนดชำระงวดแรก
		$case_owners_id = $result["case_owners_id"]; // รหัสพนักงานเจ้าของเคส
		
		// ชื่อพนักงานเจ้าของเคส
		$qry_case_owners_name = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$case_owners_id' ");
		$case_owners_name = pg_fetch_result($qry_case_owners_name,0);
	}
	
	$qry_add1=pg_query("select \"thcap_address\" from \"vthcap_ContactCus_detail\"
	where  \"contractID\" = '$contractID'");
	if($resadd=pg_fetch_array($qry_add1)){
		$address=trim($resadd["thcap_address"]);
	}

	//path เริ่มที่ root สำหรับ link ไปหน้าตรวจสอบข้อมูลลูกค้า
	$pathroot=redirect($_SERVER['PHP_SELF'],'nw/search_cusco');
	
	$qry_name1=pg_query("select \"thcap_fullname\", \"CusID\" from \"vthcap_ContactCus_detail\"
	where \"contractID\" = '$contractID' and \"CusState\" = '1'");
	$numco=pg_num_rows($qry_name1);
	$i=1;
	$nameco="";
	while($resco=pg_fetch_array($qry_name1)){
		$name2=trim($resco["thcap_fullname"]);
		$cusidco=trim($resco["CusID"]);
		$pathco = "(<a style=\"cursor:pointer;\" onclick=\"javascipt:popU('$pathroot/index.php?cusid=$cusidco','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');\"><font color=\"#FF1493\"><u>$cusidco</u></font></a>)";
		if($numco==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
			$nameco=$pathco." ".$name2;
		}else{
			if($i==$numco){
				$nameco=$nameco.$pathco." ".$name2;
			}else{
				$nameco=$nameco.$pathco." ".$name2.", ";
			}
		}
	$i++;
	}
	//หาผู้ค้ำประกัน
	$qry_name1=pg_query("select \"thcap_fullname\", \"CusID\" from \"vthcap_ContactCus_detail\"
	where \"contractID\" = '$contractID' and \"CusState\" = '2'");
	$numco1=pg_num_rows($qry_name1);
	$i=1;
	$nameGuarantee="";
	while($resGua=pg_fetch_array($qry_name1)){
		$name3=trim($resGua["thcap_fullname"]);
		$cusidguan=trim($resGua["CusID"]);
		$pathguan = "(<a style=\"cursor:pointer;\" onclick=\"javascipt:popU('$pathroot/index.php?cusid=$cusidguan','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');\"><font color=\"#FF1493\"><u>$cusidguan</u></font></a>)";		
		if($numco1==1){ //กรณีมีชื่อเดียวไม่ต้องใส่ comma
			$nameGuarantee=$pathguan." ".$name3;
		}else{
			if($i==$numco1){
				$nameGuarantee=$nameGuarantee.$pathguan." ".$name3;
			}else{
				$nameGuarantee=$nameGuarantee.$pathguan." ".$name3.", ";
			}
		}
	$i++;
	}
	
	//$nameGuarantee.=" FR% ".$conIntCurRate;


	//หาเงินค้ำประกัน
	$sqlguan = pg_query("SELECT \"contractBalance\" FROM vthcap_contract_money where \"moneyType\" = account.\"thcap_getSecureMoneyType\"('$contractID','1')::smallint and \"contractID\" = '$contractID'");
	list($moneyguan) = pg_fetch_array($sqlguan);
	
	//เงินพักรอตัดรายการ
	$sqlcut = pg_query("SELECT \"contractBalance\" FROM vthcap_contract_money where \"moneyType\" = account.\"thcap_getHoldMoneyType\"('$contractID','1')::smallint and \"contractID\" = '$contractID'");
	list($moneycut) = pg_fetch_array($sqlcut);
	
	
	//ค้นหาชื่อผู้กู้หลักจาก mysql
	$qry_namemain=pg_query("select \"thcap_fullname\", \"CusID\" from \"vthcap_ContactCus_detail\"
	where \"contractID\" = '$contractID' and \"CusState\" ='0'");
	if($resnamemain=pg_fetch_array($qry_namemain)){
		$name3=trim($resnamemain["thcap_fullname"]);
		$cusid3=trim($resnamemain["CusID"]);
	}
	
	$cur_date = nowDateTime();
	$qr_chq = pg_query("select count(a.*) as sum_chq from finance.thcap_receive_cheque a where a.\"revChqToCCID\"='$contractID' and a.\"bankChqDate\"<='$cur_date'
						and a.\"revChqID\" not in(select distinct b.\"revChqID\" from finance.thcap_receive_transfer b where b.\"revChqID\" is not null and b.\"revTranStatus\" = '3')
						and a.\"revChqID\" not in(select distinct c.\"revChqID\" from finance.thcap_receive_cheque_return c where c.\"revChqID\" is not null and c.\"statusChq\" = '1')
						and a.\"revChqStatus\" not in('0','3','4')");
	if($rs_chq = pg_fetch_array($qr_chq))
	{
		$sum_chq = $rs_chq['sum_chq'];
	}
	
	// หาค่าจาก function ใน postgres
	$backAmt = pg_query("select \"thcap_get_lease_backamt\"('$contractID','$vfocusdate')");
	$backAmt = pg_fetch_result($backAmt,0);
	
	$backDueDate = pg_query("select \"thcap_get_lease_backdate\"('$contractID','$vfocusdate')");
	$backDueDate = pg_fetch_result($backDueDate,0);
	
	$nextDueAmt = pg_query("select \"thcap_get_lease_nextamt\"('$contractID','$vfocusdate')");
	$nextDueAmt = pg_fetch_result($nextDueAmt,0);
	
	$nextDueDate = pg_query("select \"thcap_get_lease_nextdate\"('$contractID','$vfocusdate')");
	$nextDueDate = pg_fetch_result($nextDueDate,0);
	// จบการหาค่าจาก function ใน postgres
	
	
	//หาว่าเป็นสัญญาวงเงินหรือไม่
	$moneylimitsql = pg_query("select \"conCreditRef\" from \"thcap_lease_contract\" where \"contractID\" = '$contractID'");
	$moneylimitre = pg_fetch_result($moneylimitsql,0);
	if($moneylimitre != ""){
		
		$moneylimitre = "( สัญญาวงเงิน  -  ".number_format($moneylimitre,2)." )";
		
		$relpaths = redirect($_SERVER['PHP_SELF'],'nw/thcap');
		$limitlink = "<font color=\"red\"><b>  สัญญานี้มีการใช้วงเงิน <a onclick=\"javascript:popUPO('$relpaths/Data_financial_amount.php?conid=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=880,height=280')\" style=\"cursor:pointer\"><u>ดูรายละเอียด</u></a></b></font>";
	}
	
	
	//=============================================
	//  หาวันที่แจ้งเตือนต่างๆ
	//=============================================
		$arrayDateAlert = array(); // array วันที่ต่างๆที่จะแจ้งเตือนในตาราง

		// หา วันที่ปิดชำระบัญชีทั้งหมด (ไม่มีหนี้ใดๆต่อกัน)
		$dateclose_sql = pg_query("SELECT \"thcap_get_all_date_absclose\"('$contractID')");
		$dateclose = pg_fetch_result($dateclose_sql,0);
		if($dateclose != ""){array_push($arrayDateAlert, array('key1' => $dateclose, 'key2' => 'วันที่ปิดชำระบัญชีทั้งหมด (ไม่มีหนี้ใดๆต่อกัน)', 'key3' => '#EEAEEE'));}
		
		// หา วันที่ถูกขายโดยโอนสิทธิหนี้
		$date_sold_sql = pg_query("SELECT \"thcap_get_all_date_sold\"('$contractID')");
		$date_sold = pg_fetch_result($date_sold_sql,0);
		if($date_sold != ""){array_push($arrayDateAlert, array('key1' => $date_sold, 'key2' => 'วันที่ถูกขายโดยโอนสิทธิหนี้', 'key3' => '#CCCCFF'));}
		
		// หา วันที่เริ่มยึดทรัพย์
		$date_seize_sql = pg_query("SELECT \"thcap_get_all_date_seize\"('$contractID')");
		$date_seize = pg_fetch_result($date_seize_sql,0);
		if($date_seize != ""){array_push($arrayDateAlert, array('key1' => $date_seize, 'key2' => 'วันที่เริ่มยึดทรัพย์', 'key3' => '#FF6666'));}
		
		// หา วันที่ยึดทรัพย์ครบถ้วน
		$date_totalseize_sql = pg_query("SELECT \"thcap_get_all_date_totalseize\"('$contractID')");;
		$date_totalseize = pg_fetch_result($date_totalseize_sql,0);
		if($date_totalseize != ""){array_push($arrayDateAlert, array('key1' => $date_totalseize, 'key2' => 'วันที่ยึดทรัพย์ครบถ้วน', 'key3' => '#DD0000'));}
		
		// หา วันที่ฟ้อง
		$dateSue_sql = pg_query("SELECT \"thcap_get_all_dateSue\"('$contractID')");
		$dateSue = pg_fetch_result($dateSue_sql,0);
		if($dateSue != ""){array_push($arrayDateAlert, array('key1' => $dateSue, 'key2' => 'วันที่ฟ้อง', 'key3' => '#FFCC00'));}
		
		// หา วันที่ปรับโครงสร้างหนี้ หรือมีการพิพากษา
		$isRestructure_sql = pg_query("SELECT \"thcap_get_all_dateRestructure\"('$contractID')");
		$isRestructure = pg_fetch_result($isRestructure_sql,0);
		if($isRestructure != ""){array_push($arrayDateAlert, array('key1' => $isRestructure, 'key2' => 'วันที่ปรับโครงสร้างหนี้ หรือมีการพิพากษา', 'key3' => '#FFFF55'));}
		
		// หา วันที่ยกเลิกสัญญา
		$isCancel_sql = pg_query("SELECT \"thcap_get_all_date_cancel\"('$contractID')");
		$isCancel = pg_fetch_result($isCancel_sql,0);
		if($isCancel != ""){array_push($arrayDateAlert, array('key1' => $isCancel, 'key2' => 'วันที่ยกเลิกสัญญา', 'key3' => '#CCCCCC'));}
	//=============================================
	// จบการหาวันที่แจ้งเตือนต่างๆ
	//=============================================
	
	//หารหัสเงินพัก
	$holdmoney_qry = pg_query("select account.\"thcap_getHoldMoneyType\"('$contractID','1')");
	list($holdmoney) = pg_fetch_array($holdmoney_qry);
	//หารหัสเงินค้ำ
	$securmoney_qry = pg_query("select account.\"thcap_getSecureMoneyType\"('$contractID','1')");
	list($securmoney) = pg_fetch_array($securmoney_qry);
	
	//หาค่า ยอดจัด/ยอดลงทุน(ก่อนภาษี)
	$qry_conFinAmtExtVat = pg_query("select \"conFinAmtExtVat\" from thcap_contract where \"contractID\" ='$contractID'");
	$conFinAmtExtVatCal = pg_fetch_result($qry_conFinAmtExtVat,0);
	
	// หา ยอดค่าซื้อสิทธื
	$qry_thcap_get_factoring_buyprice = pg_query("select \"thcap_get_factoring_buyprice\"('$contractID')");
	$thcap_get_factoring_buyprice = pg_result($qry_thcap_get_factoring_buyprice,0);
	
	// หาข้อมูล REF1
	$qry_REF1 = pg_query("select ta_array1d_get(thcap_encode_invoice_ref('$contractID', '000000IMG-00000'),0)");
	$REF1 = pg_fetch_result($qry_REF1,0);
?>
	<center>
    <table>
	<?php
	// การแจ้งเตือนวันที่ต่างๆที่สำคัญ
	$img = redirect($_SERVER['PHP_SELF'],'nw/thcap/images/onebit_38.png');
	usort($arrayDateAlert, 'mysort1');
	foreach ($arrayDateAlert as $key => $value)
	{
	?>
		<tr bgcolor="<?php echo $value[key3]; ?>">
			<td colspan="12">
				<div style="width:100%">
					<div style="float:left">
						<img src="<?php echo $img; ?>" width="20px" height="20px"/>
						&nbsp;&nbsp;
						<b><span style="font-size:14px;"><?php echo "$value[key2] : $value[key1]"; ?></span></b>
					</div>
				</div><div style="clear:both;"></div> <!-- หาวันที่ปิดบัญชี -->
			</td>
		</tr>
	<?php
	}
	// จบการแจ้งเตือน
	?>
	<tr> <!--แสดง ประเภทสัญญาย่อย  ถ้าไม่มี path รูปภาพจะแสดงเป็นข้อความ -->
		<td colspan="3"><?php echo $imgtexttype;?>
		</td>
    	<td colspan="12" align="right">
        	<b><span style="font-size:16px; color:#FF0000;">เบี้ยปรับปัจจุบัน <?php echo number_format($lease_fine,2,".",","); ?> บาท</span></b>
			<input type="button" value="คำนวณเบี้ยปรับ"onclick="javascript:popUPO('<?php echo $cur_path; ?>/cal_lease_fine.php?contractID=<?php echo $contractID;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')" style="cursor:pointer">
		</td>
    </tr>
	<tr>
		<td align="right" bgcolor="#79BCFF"><b>เลขที่สัญญา</b></td>
		<td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD" colspan="10"><?php echo "$contractID <font color=\"red\">(REF1 : $REF1)</font>"; ?>&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo $limitlink; ?></td>
	</tr>
	<tr>
		<td align="right" bgcolor="#79BCFF"><b>เจ้าของเคส</b></td>
		<td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD" colspan="10"><?php echo $case_owners_name; ?></td>
	</tr>
	<tr>
		
		<td align="right" bgcolor="#79BCFF"><b>ผู้ขายบิล</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD" colspan="4">
		(<a style="cursor:pointer;" onclick="javascipt:popU('<?php echo $pathroot; ?>/index.php?cusid=<?php echo $cusid3; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');"><font color="#FF1493"><u><?php echo $cusid3; ?></u></font></a>)
		<?php echo $name3; ?></td>
		<td align="right" bgcolor="#79BCFF"><b>ผู้ขายบิลร่วม</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD" colspan="4"><?php echo $nameco; ?></td>
	</tr>
	<tr>
		<td align="right" bgcolor="#79BCFF"><b>ผู้ค้ำประกัน</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD" colspan="4"><?php echo $nameGuarantee; ?></td>
        <td align="right" bgcolor="#79BCFF"><b>อัตราซื้อลด</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD" colspan="4"><?php echo number_format($conIntCurRate,2,".",","); ?></td>
	</tr>
	<tr>
		<td align="right" bgcolor="#79BCFF"><b>วันที่ทำสัญญา</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD"><?php echo $conDate; ?></td>
		<td align="right" bgcolor="#79BCFF"><b>วันที่โอนสิทธิเรียกร้อง</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD"><?php echo $conStartDate; ?></td>
		<td align="right" bgcolor="#79BCFF" ><b>วันที่ครบกำหนดชำระงวดแรก</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD" colspan="4"><?php echo $conFirstDue; ?></td>
	</tr>
	<tr>
		<td align="right" bgcolor="#79BCFF" ><b>ยอดค่าซื้อสิทธิหลังหักกำไร<br>ซื้อลดและค่าธรรมเนียม</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD"><?php echo number_format($conLoanAmt,2); ?> บาท</td>
		<td align="right" bgcolor="#79BCFF" ><b>ยอดค่าซื้อสิทธิให้ล่วงหน้า</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD"><?php echo number_format($conMinPay,2); ?> บาท <?php if($conMinPay == 0){echo "<font color=\"#FF000\" size=\"1px\">(สัญญาไม่ได้กำหนดยอดผ่อนชำระ หรือยอดผ่อนแต่ละเดือนไม่เท่ากัน)</font>";} ?></td>
		<td align="right" bgcolor="#79BCFF" ><b>ยอดค่าซื้อสิทธิ</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD"><?php if($thcap_get_factoring_buyprice != ""){echo number_format($thcap_get_factoring_buyprice,2)." บาท";} ?></td>
		<td align="right" bgcolor="#79BCFF" ><b>ค่าธรรมเนียม</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD"><?php if($conFacFee != ""){echo number_format($conFacFee,2)." บาท";} ?></td>
	</tr>
	<tr>
		<td align="right" bgcolor="#79BCFF"><b>จ่ายทุกวันที่</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD"><?php echo $conRepeatDueDay; ?></td>
		<td align="right" bgcolor="#79BCFF"><b>จำนวนงวด</b></td><td bgcolor="#D5EFFD">:</td><td bgcolor="#D5EFFD"><?php echo $conTerm; ?> งวด</td>
		<td align="right" bgcolor="#FF6464"><b>ยอดค้างชำระปัจจุบัน</b></td><td bgcolor="#FFC6C6">:</td><td bgcolor="#FFC6C6"><?php echo number_format($backAmt,2); ?> บาท</td>
		<td align="right" bgcolor="#FF6464"><b>วันที่เริ่มค้างชำระ</b></td><td bgcolor="#FFC6C6">:</td><td bgcolor="#FFC6C6"><?php echo $backDueDate; ?></td>
		
	</tr>
	<tr>
		<td align="right" bgcolor="#79BCFF"><b>ยอดครบกำหนดในวันที่</b></td>
		<td bgcolor="#EDF8FE">:</td><td bgcolor="#EDF8FE"><?php echo $nextDueDate; ?></td>
		<td align="right" bgcolor="#79BCFF" width="150px"><b>จำนวนเงินที่จะครบกำหนด</b></td><td bgcolor="#EDF8FE">:</td><td bgcolor="#EDF8FE"><?php echo number_format($nextDueAmt,2); ?> บาท </td>
        <td align="right" bgcolor="#fcd432"><b>เงินค้ำประกันสัญญา</b></td><td bgcolor="#fdea9c">:</td><td bgcolor="#fdea9c"><?php echo number_format($moneyguan,2); ?> บาท <img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('<?php echo $cur_path; ?>/show_money_balance_history.php?contractID=<?php echo $contractID; ?>&moneyType=<?php echo $securmoney; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=700')" style="cursor:pointer;" /></td>
        <td align="right" bgcolor="#fcd432"><b>เงินพักรอตัดรายการ</b></td><td bgcolor="#fdea9c">:</td><td bgcolor="#fdea9c"><?php echo number_format($moneycut,2); ?> บาท <img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('<?php echo $cur_path; ?>/show_money_balance_history.php?contractID=<?php echo $contractID; ?>&moneyType=<?php echo $holdmoney; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=700')" style="cursor:pointer;" /></td>
	</tr>
	<?php
	//เพิ่ม เงื่อนไข revChqStatus ที่ไม่เท่ากับ  9 เพิ่มขึ้น
	$q = "select \"revChqID\" from finance.\"thcap_receive_cheque\" where \"revChqToCCID\"='$contractID' and \"isPostChq\"='1' and \"revChqStatus\" NOT IN ('4','9')";
	$qr = pg_query($q);	
	if($qr)
	{
		$row = pg_num_rows($qr);
		if($row!=0)
		{
			echo "
				<tr class=\"odd\">
					<td colspan=\"6\" align=\"center\">";
					if($sum_chq!=0)
					{
						echo "<b><a style=\"cursor:pointer; font-size:14px; color:#02b10a;\" onclick=\"javascipt:popU('$cur_path/all_chq_details.php?contractID=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');\">สัญญานี้มีเช็คที่ครบกำหนดแล้ว $sum_chq ใบ</a></b>";
					}					
					echo "</td>
					<td colspan=\"6\" align=\"center\">
						<b><a style=\"cursor:pointer; font-size:14px; color:#FF0000;\" onclick=\"javascipt:popU('$cur_path/chq_details.php?contractID=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=750');\">สัญญานี้มีการจ่ายเช็คล่วงหน้า</a></b>
					</td>
				</tr>
			";
			
		}
	}
	?>
	</table>
	</center>
		</div>
	</div>
</fieldset>
<?php } ?>