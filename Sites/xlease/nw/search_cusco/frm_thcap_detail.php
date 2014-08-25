<?php
include("../../config/config.php");
set_time_limit(0);
$CusID = pg_escape_string($_GET['CusID']);


//function --================================--
function  behindhand($con){ //หาข้อมูลการค้างชำระ
	unset($code);
	//นำ IDNO ที่ได้ มาตรวจสอบดูว่าค้างกี่เดือน
			$qry_frback=pg_query("select \"thcap_backDueDate\"('$con','now')");
			list($backDueDate1) = pg_fetch_array($qry_frback);
			
			
			if($backDueDate1 == ""){
				$code[0]="สัญญาปกติ";
				$code[1]="#00DDDD";					
			}else{
			
				$nowdate = nowDate();				
				$backDueDate = (strtotime($nowdate) - strtotime($backDueDate1))/(60*60*24);
				
				if($backDueDate == 0){
					$code[0]="สัญญาปกติ";
					$code[1]="#00DDDD";						
				}else if($backDueDate > 0 && $backDueDate < 31){
					$code[0]="ค้าง ".$backDueDate." วัน";
					$code[1]='#9933FF';					
				}else if($backDueDate > 30 && $backDueDate < 61){
					$code[0]="ค้าง ".$backDueDate." วัน";
					$code[1]='ORANGE';					
				}else if($backDueDate > 60){
					$code[0]="ค้าง ".$backDueDate." วัน";
					$code[1]='RED';				
				}
			}
		return $code;	
}
function checknull($value){
	if($value == ""){
		$data = '-';
	}else{
		$data = $value;
	}
	return $data;
}

function paymentlatebox($conid){ //หาวันที่จ่ายล่าช้าของแต่ละสัญญา สามารถดูได้ที่ไฟล์ แสดงตารางผ่อนชำระ

	$qry_rowloop=pg_query("SELECT count(*) as MAXperiod FROM account.\"thcap_mg_payTerm\"  where \"contractID\" = '$conid' and \"ptDate\" <= 'now' "); //หาวันที่จ่ายครบของงวดนั้นๆ
	list($period) = pg_fetch_array($qry_rowloop);
	for($i=1;$i<=$period;$i++){
		$qry_latedays=pg_query("select \"thcap_completeDueDateNum\"('$conid','$i')");
		list($numlatedays) = pg_fetch_array($qry_latedays);			
			
		if($numlatedays==""){
			$result[] = 'nothing';
		}else{
			$result[] = $numlatedays;
		}
	}	
		
		return $result;
}
//enc function --=============================--
?>
<div class="ui-widget" align="left">
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<?php
if($CusID != ""){
//ค้นหาชื่อ-นามสกุล
$qry_name=pg_query("select \"full_name\",a.\"CusID\",b.\"N_IDCARD\",b.\"N_CARDREF\" from \"VSearchCus\" a
LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"
WHERE a.\"CusID\" = '$CusID'");
$qry_rows = pg_num_rows($qry_name);
if($qry_rows == 0){ echo "<center>ไม่พบรหัสลูกค้ารายนี้ กรุณาค้นหาใหม่ </center>"; exit();}
$result=pg_fetch_array($qry_name);
$name=trim($result["full_name"]);
$CusID=trim($result["CusID"]);
$N_IDCARD=trim($result["N_IDCARD"]);
if($N_IDCARD == ""){
$N_IDCARD=trim($result["N_CARDREF"]);
}
//ค้นหาว่าเป็นผู้เช่าซื้อเลขที่สัญญาใดบ้าง
$query_name2 = pg_query("
							select a.\"contractID\" from \"thcap_ContactCus\" a
							LEFT JOIN \"thcap_contract\" b on a.\"contractID\" = b.\"contractID\"
							WHERE \"CusID\"='$CusID' and \"CusState\" = '0'
							AND \"thcap_get_creditType\"(a.\"contractID\") != 'HIRE_PURCHASE'
							AND \"thcap_get_creditType\"(a.\"contractID\") != 'LEASING'
						");
$num_name2 = pg_num_rows($query_name2);

$nub = 1;


//ค้นหาว่าเป็นผู้ค้ำเลขที่สัญญาใดบ้าง
$query_name3 = pg_query("
							select a.\"contractID\" from \"thcap_ContactCus\" a
							LEFT JOIN \"thcap_contract\" b on a.\"contractID\" = b.\"contractID\"
							WHERE \"CusID\"='$CusID' and \"CusState\" = '2'
							AND \"thcap_get_creditType\"(a.\"contractID\") != 'HIRE_PURCHASE'
							AND \"thcap_get_creditType\"(a.\"contractID\") != 'LEASING'
						");
$num_name3 = pg_num_rows($query_name3);

//ค้นหาว่าเป็นกู้ร่วมเลขที่สัญญาใดบ้าง
$query_name5 = pg_query("
							select a.\"contractID\" from \"thcap_ContactCus\" a
							LEFT JOIN \"thcap_contract\" b on a.\"contractID\" = b.\"contractID\"
							WHERE \"CusID\"='$CusID' and \"CusState\" = '1'
							AND \"thcap_get_creditType\"(a.\"contractID\") != 'HIRE_PURCHASE'
							AND \"thcap_get_creditType\"(a.\"contractID\") != 'LEASING'

						");
$num_name5 = pg_num_rows($query_name5);

$nub2 = 1;

?>

<hr width="1150">
<div align="center" style="padding:5px;"><font size="3px;"><b>---- THCAP ----</b></font></div>
<div style="background-color:#FFFFCC;width:1140px;margin:0px auto;padding:5px;"><b>ความหมายของสี LINK:</b>
<span style="background-color:black;">&nbsp;&nbsp;&nbsp;</span> สัญญาปิดแล้ว&nbsp;
<span style="background-color:#00DDDD;">&nbsp;&nbsp;&nbsp;</span> สัญญาปกติ&nbsp;
<span style="background-color:#9933FF;">&nbsp;&nbsp;&nbsp;</span> ค้างไม่เกิน 30 วัน&nbsp;
<span style="background-color:orange;">&nbsp;&nbsp;&nbsp;</span> ค้างไม่เกิน 60 วัน&nbsp;
<span style="background-color:red;">&nbsp;&nbsp;&nbsp;</span> ค้างตั้งแต่ 61 วันขึ้นไป&nbsp;
</div>
<table width="1150" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" align="right"><b>ชื่อ/สกุล :</b></td>
    <td bgcolor="#FFFFFF"><font color="#0000FF"><b><?php echo "$name (รหัสลูกค้่า $CusID)"; ?></b></font><br>
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เลขที่สัญญา(ผู้กู้หลัก) :</b></td>
    <td bgcolor="#FFFFFF">
		<table width="100%">
	<?php 
	if($num_name2 == 0){
		echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
	}else{	
		echo "<tr><td>
			<span id=\"paymentlatesumshow\" style=\"font-size:14px; color:#FF0000;\"></span>
			<span id=\"minpaymentsumshow\" style=\"font-size:14px; color:#CE0000;\"></span>
			<span id=\"Debtsumshow\" style=\"font-size:14px; color:#006600;\"></span>
			</td></tr>";
		while($res_name2=pg_fetch_array($query_name2)){
			$contractID=$res_name2["contractID"]; 			
			
			//หาจำนวนวันจ่ายย้อนหลัง
				$colorboxlist = paymentlatebox($contractID);
				$sizeofrow = sizeof($colorboxlist);
				if($sizeofrow > 36){
					$limit = $sizeofrow - 36;
				}else{
					$limit = 0;
				}
					
			//จบหาจำนวนวันจ่ายย้อนหลัง
			
			
			//ยอดหนี้ค้างชำระรวม 
			$qry_fr=pg_query("SELECT \"thcap_getLoanBalanceAmt\"('$contractID','Now')");
			list($paymentlate)=pg_fetch_array($qry_fr);
			//ยอดภาระผ่อนต่อเดือน 
			$qry_fr=pg_query("SELECT \"contractID\",\"conMinPay\",\"conStartDate\",\"conLoanAmt\",\"conIntCurRate\" from thcap_mg_contract where \"contractID\" = '$contractID' ");
			while($re_fr=pg_fetch_array($qry_fr)){
				$conid=$re_fr["contractID"];
				$minpayment=$re_fr["conMinPay"];
				$conStartDate = $re_fr["conStartDate"];
				$conLoanAmt = $re_fr["conLoanAmt"];
				$conStartDate = $re_fr["conStartDate"];
				$conIntCurRate = $re_fr["conIntCurRate"];
			}			
			
			$conStartDate = checknull($conStartDate);
			$conLoanAmt = number_format(checknull($conLoanAmt),2);
			$conIntCurRate = checknull($conIntCurRate)."%";
			
			
			if($paymentlate > 0){
				list($txtclose,$color) = behindhand($contractID);
				$minpaymentsum += $minpayment;
				$colorstatefield = '';	
			}else{
				$txtclose="สัญญาปิดแล้ว";
				$color='#000000';
				$colorstatefield = '#E0E0E0';	
			}
			
			$paymentlate = checknull($paymentlate);
			$paymentlate1 = number_format($paymentlate,2);
			
			if($color=="#000000"&&$txtclose=="สัญญาปิดแล้ว")
			{
				$showcon = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color><U><span title=\"$txtclose\"><img src=\"images/paper.png\" style=\"border:none\" />$contractID</span></U></font></a>";
			}
			else
			{
				$showcon = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color><U><span title=\"$txtclose\">$contractID</span></U></font></a>";
			}
			echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
			echo "<tr><td>$showcon<font color=\"$color\"> วันที่เริ่มกู้: $conStartDate จำนวนเงินกู้เริ่มแรก: $conLoanAmt อัตราดอกเบี้ย: $conIntCurRate  ยอดเงินคงเหลือ: $paymentlate1</td></tr>"; 
			echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
					<tr>";
				for($i = $sizeofrow-1;$i>=$limit;$i--){
					
					if($colorboxlist[$i] == 'nothing'){ //แดงเข้มปี๊ดดด
						$colorbox = '#CC0000';
						$numshow = 'N';
					}else{
					
						$numshow = ceil(($colorboxlist[$i] - 7)/30);
						if($numshow <= 0){ $numshow = 0; }
						if($colorboxlist[$i]<= 7){ //เขียว
							$colorbox = '#00AA00';
						}else if($colorboxlist[$i] <= 37){ //แดงอ่อน
							$colorbox = '#FF6666';
						}else if($colorboxlist[$i] <= 67){ //แดง
							$colorbox = '#FF3333';
						}else if($colorboxlist[$i] <= 97){ //แดงเข้ม
							$colorbox = '#FF0000';
						}else if($colorboxlist[$i] > 97){ //แดงเข้มปี๊ดดด
							$colorbox = '#CC0000';
						}else{
							$colorbox = '';
						}
					}
					echo "
						<td>
							<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
								<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
							</table>
						</td>	
						";
				}
				echo "</tr></table>
					</td></tr>
					</table></td></tr>
					";
			
			
			$paymentlatesum += $paymentlate;
			$paymentlate = "";
			
			$minpayment = "";
			unset($colorbox);
			unset($numshow);
			unset($limit);
			unset($sizeofrow);
		}
		//ยอดหนี้เฉลี่ยต่อสัญญา 
		$avgpercon = $paymentlatesum/$num_name2;
		
		$paymentlatesum = @number_format($paymentlatesum,2);		
		$minpaymentsum = @number_format($minpaymentsum,2);
		$avgperconsum = @number_format($avgpercon,2);	
		echo "<input type=\"hidden\" id=\"paymentlatesum\" value=\"$paymentlatesum\">";
		echo "<input type=\"hidden\" id=\"minpaymentsum\" value=\"$minpaymentsum\">";
		echo "<input type=\"hidden\" id=\"avgperconsum\" value=\"$avgperconsum\">";
		?>
		<script>	
						if($("#paymentlatesum").val()!=""){
							$("#paymentlatesumshow").html(" ยอดหนี้ค้างชำระรวม   "+$("#paymentlatesum").val()+" บาท");
						}else{
							$("#paymentlatesumshow").html(" ไม่มียอดหนี้ค้างชำระ  ");
						}						
						if($("#minpaymentsum").val()!=""){
							$("#minpaymentsumshow").html(" ยอดภาระผ่อนต่อเดือน    "+$("#minpaymentsum").val()+" บาท");
						}else{
							$("#minpaymentsumshow").html(" ไม่มียอดภาระผ่อนต่อเดือน  ");
						}
						if($("#avgperconsum").val()!=""){
							$("#Debtsumshow").html(" ยอดหนี้เฉลี่ยต่อสัญญา     "+$("#avgperconsum").val()+" บาท");
						}else{
							$("#Debtsumshow").html(" ไม่มียอดหนี้เฉลี่ยต่อสัญญา   ");
						}
		</script>	
	<?php				
		echo "<tr><td><span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name2 สัญญา)</b></font></span></td></tr>";
		$paymentlatesum = "";
		$minpaymentsum = "";
		$avgpercon ="";
		$avgperconsum="";
	} ?>		
		</table>
	</td>
</tr>






<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เลขที่สัญญา(ผู้กู้ร่วม) :</b></td>
    <td bgcolor="#FFFFFF">
		<table width="100%">
	<?php 
	if($num_name5 == 0){
		echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
	}else{	
		echo "<tr><td>
			<span id=\"paymentlatesumshow5\" style=\"font-size:14px; color:#FF0000;\"></span>
			<span id=\"minpaymentsumshow5\" style=\"font-size:14px; color:#CE0000;\"></span>
			<span id=\"Debtsumshow5\" style=\"font-size:14px; color:#006600;\"></span>
			</td></tr>";
		while($res_name5=pg_fetch_array($query_name5)){
			$contractID5=$res_name5["contractID"]; 			
			
			//หาจำนวนวันจ่ายย้อนหลัง
				$colorboxlist = paymentlatebox($contractID5);
				$sizeofrow = sizeof($colorboxlist);
				if($sizeofrow > 36){
					$limit = $sizeofrow - 36;
				}else{
					$limit = 0;
				}
					
			//จบหาจำนวนวันจ่ายย้อนหลัง
			
			//ยอดหนี้ค้างชำระรวม 
			$qry_fr=pg_query("SELECT \"thcap_getLoanBalanceAmt\"('$contractID5','Now') ");
			list($paymentlate5)=pg_fetch_array($qry_fr);
			//ยอดภาระผ่อนต่อเดือน 
			$qry_fr=pg_query("SELECT \"contractID\",\"conMinPay\",\"conStartDate\",\"conLoanAmt\",\"conIntCurRate\" from thcap_mg_contract where \"contractID\" = '$contractID5' ");
			while($re_fr=pg_fetch_array($qry_fr)){
				$conid5=$re_fr["contractID"];
				$minpayment5=$re_fr["conMinPay"];
				$conStartDate5 = $re_fr["conStartDate"];
				$conLoanAmt5 = $re_fr["conLoanAmt"];
				$conStartDate5 = $re_fr["conStartDate"];
				$conIntCurRate5 = $re_fr["conIntCurRate"];
			}			
			
			$conStartDate5 = checknull($conStartDate5);
			$conLoanAmt5 = number_format(checknull($conLoanAmt5),2);
			$conIntCurRate5 = checknull($conIntCurRate5);
			
						
			
			if($paymentlate5 > 0){
				list($txtclose5,$color5) = behindhand($contractID5);
				$minpaymentsum5 += $minpayment5;
				$colorstatefield = '';	
			}else{
				$txtclose5="สัญญาปิดแล้ว";
				$color5='#000000';	
				$colorstatefield = '#E0E0E0';		
			}
			
			$paymentlate5 = checknull($paymentlate5);			
			$paymentlate15 = number_format($paymentlate5,2);
			
			if($color5=="#000000"&&$txtclose5=="สัญญาปิดแล้ว")
			{
				$showcon5 = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID5','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color5><U><span title=\"$txtclose5\"><img src=\"images/paper.png\" />$contractID5</span></U></font></a>";
			}
			else
			{
				$showcon5 = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID5','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color5><U><span title=\"$txtclose5\">$contractID5</span></U></font></a>";
			}
			echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
			echo "<tr><td>$showcon5<font color=\"$color5\"> วันที่เริ่มกู้: $conStartDate5 จำนวนเงินกู้เริ่มแรก: $conLoanAmt5 อัตราดอกเบี้ย: $conIntCurRate5 %  ยอดเงินคงเหลือ:  $paymentlate15</td></tr>"; 
			echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
					<tr>";
				for($i = $sizeofrow-1;$i>=$limit;$i--){
				
					if($colorboxlist[$i] == 'nothing'){ //แดงเข้มปี๊ดดด
						$colorbox = '#CC0000';
						$numshow = 'N';
					}else{
						
						$numshow = ceil(($colorboxlist[$i] - 7)/30);
						if($numshow <= 0){ $numshow = 0; }
						
						if($colorboxlist[$i]<= 7){ //เขียว
							$colorbox = '#00AA00';
						}else if($colorboxlist[$i] <= 37){ //แดงอ่อน
							$colorbox = '#FF6666';
						}else if($colorboxlist[$i] <= 67){ //แดง
							$colorbox = '#FF3333';
						}else if($colorboxlist[$i] <= 97){ //แดงเข้ม
							$colorbox = '#FF0000';
						}else if($colorboxlist[$i] > 97){ //แดงเข้มปี๊ดดด
							$colorbox = '#CC0000';
						}else{
							$colorbox = '';
						}
					}
					echo "
						<td>
							<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
								<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
							</table>
						</td>	
						";
				}
				echo "</tr></table>
					</td></tr>
					</table></td></tr>";
			
			$paymentlatesum5 += $paymentlate5;
			$paymentlate5 = "";					
			$minpayment5 = "";
			unset($colorbox);
			unset($numshow);
			unset($limit);
			unset($sizeofrow);
			
		}
		//ยอดหนี้เฉลี่ยต่อสัญญา 
		$avgpercon5 = $paymentlatesum5/$num_name5;
		
		$paymentlatesum5 = @number_format($paymentlatesum5,2);		
		$minpaymentsum5 = @number_format($minpaymentsum5,2);
		$avgperconsum5 = @number_format($avgpercon5,2);	
		echo "<input type=\"hidden\" id=\"paymentlatesum5\" value=\"$paymentlatesum5\">";
		echo "<input type=\"hidden\" id=\"minpaymentsum5\" value=\"$minpaymentsum5\">";
		echo "<input type=\"hidden\" id=\"avgperconsum5\" value=\"$avgperconsum5\">";
		?>
		<script>	
						if($("#paymentlatesum5").val()!=""){
							$("#paymentlatesumshow5").html(" ยอดหนี้ค้างชำระรวม   "+$("#paymentlatesum5").val()+" บาท");
						}else{
							$("#paymentlatesumshow5").html(" ไม่มียอดหนี้ค้างชำระ  ");
						}						
						if($("#minpaymentsum5").val()!=""){
							$("#minpaymentsumshow5").html(" ยอดภาระผ่อนต่อเดือน    "+$("#minpaymentsum5").val()+" บาท");
						}else{
							$("#minpaymentsumshow5").html(" ไม่มียอดภาระผ่อนต่อเดือน  ");
						}
						if($("#avgperconsum5").val()!=""){
							$("#Debtsumshow5").html(" ยอดหนี้เฉลี่ยต่อสัญญา     "+$("#avgperconsum5").val()+" บาท");
						}else{
							$("#Debtsumshow5").html(" ไม่มียอดหนี้เฉลี่ยต่อสัญญา   ");
						}
		</script>	
	<?php				
		echo "<tr><td><span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name5 สัญญา)</b></font></span></td></tr>";
		$paymentlatesum5 = "";
		$minpaymentsum5 = "";
		$avgpercon5 ="";
		$avgperconsum5="";
	} ?>		
		</table>
	</td>
</tr>



<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เลขที่สัญญา(ผู้ค้ำ) :</b></td>
    <td bgcolor="#FFFFFF">
		<table width="100%">
	<?php 
	if($num_name3 == 0){
		echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
	}else{	
		echo "<tr><td>
			<span id=\"paymentlatesumshow2\" style=\"font-size:14px; color:#FF0000;\"></span>
			<span id=\"minpaymentsumshow2\" style=\"font-size:14px; color:#CE0000;\"></span>
			<span id=\"Debtsumshow2\" style=\"font-size:14px; color:#006600;\"></span>
			</td></tr>";
		while($res_name3=pg_fetch_array($query_name3)){
			$contractID2=$res_name3["contractID"]; 			
			
			//หาจำนวนวันจ่ายย้อนหลัง
				$colorboxlist = paymentlatebox($contractID2);
				$sizeofrow = sizeof($colorboxlist);
				if($sizeofrow > 36){
					$limit = $sizeofrow - 36;
				}else{
					$limit = 0;
				}
					
			//จบหาจำนวนวันจ่ายย้อนหลัง
			
			//ยอดหนี้ค้างชำระรวม 
			$qry_fr=pg_query("SELECT \"thcap_getLoanBalanceAmt\"('$contractID2','Now') ");
			list($paymentlate2)=pg_fetch_array($qry_fr);
			//ยอดภาระผ่อนต่อเดือน 
			$qry_fr=pg_query("SELECT \"contractID\",\"conMinPay\",\"conStartDate\",\"conLoanAmt\",\"conIntCurRate\" from thcap_mg_contract where \"contractID\" = '$contractID2' ");
			while($re_fr=pg_fetch_array($qry_fr)){
				$conid2=$re_fr["contractID"];
				$minpayment2=$re_fr["conMinPay"];
				$conStartDate2 = $re_fr["conStartDate"];
				$conLoanAmt2 = $re_fr["conLoanAmt"];
				$conStartDate2 = $re_fr["conStartDate"];
				$conIntCurRate2 = $re_fr["conIntCurRate"];
			}			
			
			$conStartDate2 = checknull($conStartDate2);
			$conLoanAmt2 = number_format(checknull($conLoanAmt2),2);
			$conIntCurRate2 = checknull($conIntCurRate2);
			
						
			
			if($paymentlate2 > 0){
				list($txtclose2,$color2) = behindhand($contractID2);
				$minpaymentsum2 += $minpayment2;
				$colorstatefield = '';		
			}else{
				$txtclose2="สัญญาปิดแล้ว";
				$color2='#000000';
				$colorstatefield = '#E0E0E0';	
			}
			
			$paymentlate2 = checknull($paymentlate2);			
			$paymentlate12 = number_format($paymentlate2,2);
			
			if($color2=="#000000"&&$txtclose2=="สัญญาปิดแล้ว")
			{
				$showcon2 = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color2><U><span title=\"$txtclose2\"><img src=\"images/paper.png\" />$contractID2</span></U></font></a>";
			}
			else
			{
				$showcon2 = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID2','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color2><U><span title=\"$txtclose2\">$contractID2</span></U></font></a>";
			}
			echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
			echo "<tr><td>$showcon2<font color=\"$color2\"> วันที่เริ่มกู้: $conStartDate2 จำนวนเงินกู้เริ่มแรก: $conLoanAmt2 อัตราดอกเบี้ย: $conIntCurRate2 %  ยอดเงินคงเหลือ:  $paymentlate12</td></tr>"; 
			echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
					<tr>";
				for($i = $sizeofrow-1;$i>=$limit;$i--){
					if($colorboxlist[$i] == 'nothing'){ //แดงเข้มปี๊ดดด
						$colorbox = '#CC0000';
						$numshow = 'N';
					}else{
					
					
						$numshow = ceil(($colorboxlist[$i] - 7)/30);
						if($numshow <= 0){ $numshow = 0; }
						
						if($colorboxlist[$i]<= 7){ //เขียว
							$colorbox = '#00AA00';
						}else if($colorboxlist[$i] <= 37){ //แดงอ่อน
							$colorbox = '#FF6666';
						}else if($colorboxlist[$i] <= 67){ //แดง
							$colorbox = '#FF3333';
						}else if($colorboxlist[$i] <= 97){ //แดงเข้ม
							$colorbox = '#FF0000';
						}else if($colorboxlist[$i] > 97){ //แดงเข้มปี๊ดดด
							$colorbox = '#CC0000';
						}else{
							$colorbox = '';
						}
					}
					echo "
						<td>
							<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
								<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
							</table>
						</td>	
						";
				}
				echo "</tr></table>
					</td></tr>
					</table></td></tr>";
			
			$paymentlatesum2 += $paymentlate2;
			$paymentlate2 = "";					
			$minpayment2 = "";
			unset($colorbox);
			unset($numshow);
			unset($limit);
			unset($sizeofrow);
			
		}
		//ยอดหนี้เฉลี่ยต่อสัญญา 
		$avgpercon2 = $paymentlatesum2/$num_name3;
		
		$paymentlatesum2 = @number_format($paymentlatesum2,2);		
		$minpaymentsum2 = @number_format($minpaymentsum2,2);
		$avgperconsum2 = @number_format($avgpercon2,2);	
		echo "<input type=\"hidden\" id=\"paymentlatesum2\" value=\"$paymentlatesum2\">";
		echo "<input type=\"hidden\" id=\"minpaymentsum2\" value=\"$minpaymentsum2\">";
		echo "<input type=\"hidden\" id=\"avgperconsum2\" value=\"$avgperconsum2\">";
		?>
		<script>	
						if($("#paymentlatesum2").val()!=""){
							$("#paymentlatesumshow2").html(" ยอดหนี้ค้างชำระรวม   "+$("#paymentlatesum2").val()+" บาท");
						}else{
							$("#paymentlatesumshow2").html(" ไม่มียอดหนี้ค้างชำระ  ");
						}						
						if($("#minpaymentsum2").val()!=""){
							$("#minpaymentsumshow2").html(" ยอดภาระผ่อนต่อเดือน    "+$("#minpaymentsum2").val()+" บาท");
						}else{
							$("#minpaymentsumshow2").html(" ไม่มียอดภาระผ่อนต่อเดือน  ");
						}
						if($("#avgperconsum2").val()!=""){
							$("#Debtsumshow2").html(" ยอดหนี้เฉลี่ยต่อสัญญา     "+$("#avgperconsum2").val()+" บาท");
						}else{
							$("#Debtsumshow2").html(" ไม่มียอดหนี้เฉลี่ยต่อสัญญา   ");
						}
		</script>	
	<?php				
		echo "<tr><td><span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name3 สัญญา)</b></font></span></td></tr>";
		$paymentlatesum2 = "";
		$minpaymentsum2 = "";
		$avgpercon2 ="";
		$avgperconsum2="";
	} ?>		
		</table>
	</td>
</tr>
</table>
<br>




<!---- HIRE_PURCHASE / LEASING -->

<?php

//ค้นหาชื่อ-นามสกุล
$qry_name=pg_query("select \"full_name\",a.\"CusID\",b.\"N_IDCARD\",b.\"N_CARDREF\" from \"VSearchCus\" a
LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"
WHERE a.\"CusID\" = '$CusID'");
$qry_rows = pg_num_rows($qry_name);
if($qry_rows == 0){ echo "<center>ไม่พบรหัสลูกค้ารายนี้ กรุณาค้นหาใหม่ </center>"; exit();}
$result=pg_fetch_array($qry_name);
$name=trim($result["full_name"]);
$CusID=trim($result["CusID"]);
$N_IDCARD=trim($result["N_IDCARD"]);
if($N_IDCARD == ""){
$N_IDCARD=trim($result["N_CARDREF"]);
}

//ค้นหาว่าเป็นผู้เช่าซื้อเลขที่สัญญาใดบ้าง
$query_name2 = pg_query("
							select a.\"contractID\" from \"thcap_ContactCus\" a
							LEFT JOIN \"thcap_contract\" b on a.\"contractID\" = b.\"contractID\"
							WHERE \"CusID\"='$CusID' and \"CusState\" = '0'
							AND (\"thcap_get_creditType\"(a.\"contractID\") = 'HIRE_PURCHASE'
							OR \"thcap_get_creditType\"(a.\"contractID\") = 'LEASING')
						");
$num_name2 = pg_num_rows($query_name2);

$nub = 1;


//ค้นหาว่าเป็นผู้ค้ำเลขที่สัญญาใดบ้าง
$query_name3 = pg_query("
							select a.\"contractID\" from \"thcap_ContactCus\" a
							LEFT JOIN \"thcap_contract\" b on a.\"contractID\" = b.\"contractID\"
							WHERE \"CusID\"='$CusID' and \"CusState\" = '2'
							AND (\"thcap_get_creditType\"(a.\"contractID\") = 'HIRE_PURCHASE'
							OR \"thcap_get_creditType\"(a.\"contractID\") = 'LEASING')
						");
$num_name3 = pg_num_rows($query_name3);

//ค้นหาว่าเป็นกู้ร่วมเลขที่สัญญาใดบ้าง
$query_name5 = pg_query("
							select a.\"contractID\" from \"thcap_ContactCus\" a
							LEFT JOIN \"thcap_contract\" b on a.\"contractID\" = b.\"contractID\"
							WHERE \"CusID\"='$CusID' and \"CusState\" = '1'
							AND (\"thcap_get_creditType\"(a.\"contractID\") = 'HIRE_PURCHASE'
							OR \"thcap_get_creditType\"(a.\"contractID\") = 'LEASING')

						");
$num_name5 = pg_num_rows($query_name5);

$nub2 = 1;

?>

<hr width="1150">
<div align="center" style="padding:5px;"><font size="3px;"><b>---- HIRE_PURCHASE / LEASING ----</b></font></div>
<div style="background-color:#FFFFCC;width:1140px;margin:0px auto;padding:5px;"><b>ความหมายของสี LINK:</b>
<span style="background-color:black;">&nbsp;&nbsp;&nbsp;</span> สัญญาปิดแล้ว&nbsp;
<span style="background-color:#00DDDD;">&nbsp;&nbsp;&nbsp;</span> สัญญาปกติ&nbsp;
<span style="background-color:#9933FF;">&nbsp;&nbsp;&nbsp;</span> ค้าง 1 งวด&nbsp;
<span style="background-color:orange;">&nbsp;&nbsp;&nbsp;</span> ค้าง 2 งวด&nbsp;
<span style="background-color:red;">&nbsp;&nbsp;&nbsp;</span> ค้างตั้งแต่ 3 งวดขึ้นไป&nbsp;
</div>
<table width="1150" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
<tr bgcolor="#BCE6FC">
    <td width="150" align="right"><b>ชื่อ/สกุล :</b></td>
    <td bgcolor="#FFFFFF"><font color="#0000FF"><b><?php echo "$name (รหัสลูกค้่า $CusID)"; ?></b></font><br>
	</td>
</tr>
<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เลขที่สัญญา(ผู้เช่าซื้อ) :</b></td>
    <td bgcolor="#FFFFFF">
		<table width="100%">
	<?php 
	if($num_name2 == 0){
		echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
	}else{	
		echo "<tr><td>
			<span id=\"paymentlatesumshow_h1\" style=\"font-size:14px; color:#FF0000;\"></span>
			<span id=\"minpaymentsumshow_h1\" style=\"font-size:14px; color:#CE0000;\"></span>
			<span id=\"Debtsumshow_h1\" style=\"font-size:14px; color:#006600;\"></span>
			</td></tr>";
		while($res_name2=pg_fetch_array($query_name2)){
			$contractID=$res_name2["contractID"]; 						
	
			//หายอด ทั้งหมดที่ต้องจ่ายไม่รวม VAT
			$qrysum2=pg_query("select sum(\"debtNet\"),\"contractID\" from public.\"thcap_v_lease_table\" where \"contractID\" = '$contractID' and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID') group by \"contractID\""); 
			list($sumamtnovat,$contact2)=pg_fetch_array($qrysum2);
			$sumamtnovatshow = number_format($sumamtnovat,2);
			
			//ยอดภาระผ่อนต่อเดือน 
			$qry1=pg_query("select * from public.\"thcap_v_lease_table\" where \"contractID\" = '$contractID' and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID')"); 
			$numrows=pg_num_rows($qry1);
			$res1=pg_fetch_array($qry1);
			
				$alldebt=trim($res1["typePayAmt"]); //ยอดที่ต้องชำระ			
				$alldebtshow = number_format($alldebt,2);
				
				
			//หาว่าค้างชำระกี่งวด 
			$qry_latepay=pg_query("select * from public.\"thcap_v_lease_table\" where \"contractID\" = '$contractID'
							and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID')
							and \"debtDueDate\" <= current_date 
							and \"receiptID\" is null
							"); 
			$rows_latepay=pg_num_rows($qry_latepay);
			
			
				$alldebt=trim($res1["typePayAmt"]); //ยอดที่ต้องชำระ			
				$alldebtshow = number_format($alldebt,2);	
				
			//หาวันที่ปิดบัญชี
			$dateclosesql = pg_query("SELECT thcap_checkcontractcloseddate('$contractID')");
			list($dateclose) = pg_fetch_array($dateclosesql);

			if($dateclose != ""){		
				$txtclose="สัญญาปิดแล้ว";
				$color='#000000';
				$colorstatefield = '#E0E0E0';
				$latetitle = "";				
			}else{
				$colorstatefield = "";
				$txtclose = "";
				if($rows_latepay=="1"){ //ค้าง 1 งวด
					$color='#9933FF';
					$latetitle = 'ค้าง 1 งวด';
				}else if($rows_latepay=="2"){ // ค้าง 2 งวด
					$color='ORANGE';
					$latetitle = 'ค้าง 2 งวด';
				}else if($rows_latepay>="3"){ // ค้าง 3 งวดขึ้นไป
					$color='RED';
					$latetitle = 'ค้าง 3 งวดขึ้นไป';
				}else{ // สัญญาปกติ
					$color="#00DDDD";
					$latetitle = 'สัญญาปกติ';
				}
			
			}

			//contractid
			if($color=="#000000"&&$txtclose=="สัญญาปิดแล้ว")
			{
				$showcon = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color><U><span title=\"$txtclose\"><img src=\"images/paper.png\" />$contractID</span></U></font></a>";
			}
			else
			{
				$showcon = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color><U><span title=\"$txtclose\">$contractID</span></U></font></a>";
			}
			//รายละเอียดสินค้า
			$showproduct = "<img src=\"images/detail.gif\" style=\"cursor:pointer;\" onclick=\"popU('../thcap_installments/show_list_product.php?contractID=$contractID&showall=true','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" >";
			
			echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
			echo "<tr><td>$showcon<font color=\"$color\">  รายละเอียดสินค้า $showproduct  ยอดภาระผ่อนต่อเดือน: $alldebtshow ยอดเช่าซื้อคงเหลือ: $sumamtnovatshow</td></tr>"; 
			echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
					<tr>";
					
			$qry1=pg_query("select \"delay\",\"receiptID\" from public.\"thcap_v_lease_table\" where \"contractID\" = '$contractID' 
											and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID')
											and \"debtDueDate\" <= current_date
											order by \"debtDueDate\" DESC limit 36
											"); 
			while($res1=pg_fetch_array($qry1)){

				$delay =trim($res1["delay"]); //วันที่จ่ายล่าช้า
				$receiptID =trim($res1["receiptID"]); //รหัสใบเสร็จ หากไม่มีแสดงว่ายังไมจ่าย
			
					
					if($receiptID == ''){ //แดงเข้มปี๊ดดด
						$colorbox = '#CC0000';
						$numshow = 'N';
					}else{

						if($delay<= 7){ //เขียว
							$colorbox = '#00AA00';
						}else if($delay <= 37){ //แดงอ่อน
							$colorbox = '#FF6666';
						}else if($delay <= 67){ //แดง
							$colorbox = '#FF3333';
						}else if($delay <= 97){ //แดงเข้ม
							$colorbox = '#FF0000';
						}else if($delay > 97){ //แดงเข้มปี๊ดดด
							$colorbox = '#CC0000';
						}else{
							$colorbox = '';
						}
						$numshow = $delay;
					}
					echo "
						<td>
							<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
								<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
							</table>
						</td>	
						";
			}
				echo "</tr></table>
					</td></tr>
					</table></td></tr>
					";
			
			
			$paymentlatesum += $sumamtnovat;
			$alldebtsum += $alldebt;
			
			unset($colorbox);
			unset($numshow);
			unset($limit);
			unset($sizeofrow);
			unset($dateclose);
		}
		//ยอดหนี้เฉลี่ยต่อสัญญา 
		$avgpercon = $paymentlatesum/$num_name2;
		
		$paymentlatesum = @number_format($paymentlatesum,2);		
		$minpaymentsum = @number_format($alldebtsum,2);
		$avgperconsum = @number_format($avgpercon,2);	
		echo "<input type=\"hidden\" id=\"paymentlatesum_h1\" value=\"$paymentlatesum\">";
		echo "<input type=\"hidden\" id=\"minpaymentsum_h1\" value=\"$minpaymentsum\">";
		echo "<input type=\"hidden\" id=\"avgperconsum_h1\" value=\"$avgperconsum\">";
		?>
		<script>	
						if($("#paymentlatesum_h1").val()!=""){
							$("#paymentlatesumshow_h1").html(" ยอดเช่าซื้อคงเหลือรวม   "+$("#paymentlatesum_h1").val()+" บาท");
						}else{
							$("#paymentlatesumshow_h1").html(" ไม่มียอดเช่าซื้อคงเหลือ  ");
						}						
						if($("#minpaymentsum_h1").val()!=""){
							$("#minpaymentsumshow_h1").html(" ยอดภาระผ่อนต่อเดือนรวม    "+$("#minpaymentsum_h1").val()+" บาท");
						}else{
							$("#minpaymentsumshow_h1").html(" ไม่มียอดภาระผ่อนต่อเดือน  ");
						}
						if($("#avgperconsum_h1").val()!=""){
							$("#Debtsumshow_h1").html(" ยอดเช่าซื้อคงเหลือเฉลี่ย     "+$("#avgperconsum_h1").val()+" บาท");
						}else{
							$("#Debtsumshow_h1").html(" ไม่มียอดเช่าซื้อคงเหลือเฉลี่ย   ");
						}
		</script>	
	<?php				
		echo "<tr><td><span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name2 สัญญา)</b></font></span></td></tr>";
		$paymentlatesum = "";
		$minpaymentsum = "";
		$avgpercon ="";
		$avgperconsum="";
	} ?>		
		</table>
	</td>
</tr>




<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เลขที่สัญญา(ผู้เช่าซื้อร่วม) :</b></td>
    <td bgcolor="#FFFFFF">
		<table width="100%">
	<?php 
	if($num_name5 == 0){
		echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
	}else{	
		echo "<tr><td>
			<span id=\"paymentlatesumshow_h2\" style=\"font-size:14px; color:#FF0000;\"></span>
			<span id=\"minpaymentsumshow_h2\" style=\"font-size:14px; color:#CE0000;\"></span>
			<span id=\"Debtsumshow_h2\" style=\"font-size:14px; color:#006600;\"></span>
			</td></tr>";
		while($res_name2=pg_fetch_array($query_name5)){
			$contractID=$res_name2["contractID"]; 						
	
			//หายอด ทั้งหมดที่ต้องจ่ายไม่รวม VAT
			$qrysum2=pg_query("select sum(\"debtNet\"),\"contractID\" from public.\"thcap_v_lease_table\" where \"contractID\" = '$contractID' and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID') group by \"contractID\""); 
			list($sumamtnovat,$contact2)=pg_fetch_array($qrysum2);
			$sumamtnovatshow = number_format($sumamtnovat,2);
			
			//ยอดภาระผ่อนต่อเดือน 
			$qry1=pg_query("select \"typePayAmt\" from public.\"thcap_v_lease_table\" where \"contractID\" = '$contractID' and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID')"); 
			$numrows=pg_num_rows($qry1);
			$res1=pg_fetch_array($qry1);
			
				$alldebt=trim($res1["typePayAmt"]); //ยอดที่ต้องชำระ			
				$alldebtshow = number_format($alldebt,2);
				
				
			//หาว่าค้างชำระกี่งวด 
			$qry_latepay=pg_query("select \"debtID\"  from public.\"thcap_v_lease_table\" where \"contractID\" = '$contractID'
							and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID')
							and \"debtDueDate\" <= current_date 
							and \"receiptID\" is null
							"); 
			$rows_latepay=pg_num_rows($qry_latepay);
			
			
				$alldebt=trim($res1["typePayAmt"]); //ยอดที่ต้องชำระ			
				$alldebtshow = number_format($alldebt,2);	
				
			//หาวันที่ปิดบัญชี
			$dateclosesql = pg_query("SELECT thcap_checkcontractcloseddate('$contractID')");
			$dateclosere = pg_fetch_array($dateclosesql);
			$dateclose = $dateclosere['thcap_checkcontractcloseddate'];	
			
			if($dateclose != ""){		
				$txtclose="สัญญาปิดแล้ว";
				$color='#000000';
				$colorstatefield = '#E0E0E0';
				$latetitle = "";		
			}else{
				$colorstatefield = "";
				$txtclose = "";
				if($rows_latepay=="1"){ //ค้าง 1 งวด
					$color='#9933FF';
					$latetitle = 'ค้าง 1 งวด';
				}else if($rows_latepay=="2"){ // ค้าง 2 งวด
					$color='ORANGE';
					$latetitle = 'ค้าง 2 งวด';
				}else if($rows_latepay>="3"){ // ค้าง 3 งวดขึ้นไป
					$color='RED';
					$latetitle = 'ค้าง 3 งวดขึ้นไป';
				}else{ // สัญญาปกติ
					$color="#00DDDD";
					$latetitle = 'สัญญาปกติ';
				}
			
			}

			//contractid
			if($color=="#000000"&&$txtclose=="สัญญาปิดแล้ว")
			{
				$showcon = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color><U><span title=\"$txtclose\"><img src=\"images/paper.png\" />$contractID</span></U></font></a>";
			}
			else
			{
				$showcon = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color><U><span title=\"$txtclose\">$contractID</span></U></font></a>";
			}
			//รายละเอียดสินค้า
			$showproduct = "<img src=\"images/detail.gif\" style=\"cursor:pointer;\" onclick=\"popU('../thcap_installments/show_list_product.php?contractID=$contractID&showall=true','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" >";
			echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
			echo "<tr><td>$showcon<font color=\"$color\">  รายละเอียดสินค้า $showproduct  ยอดภาระผ่อนต่อเดือน: $alldebtshow ยอดเช่าซื้อคงเหลือ: $sumamtnovatshow</td></tr>"; 
			echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
					<tr>";
					
			$qry1=pg_query("select \"delay\",\"receiptID\" from public.\"thcap_v_lease_table\" where \"contractID\" = '$contractID' 
											and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID')
											and \"debtDueDate\" <= current_date
											order by \"debtDueDate\" DESC limit 36
											"); 
			while($res1=pg_fetch_array($qry1)){

				$delay =trim($res1["delay"]); //วันที่จ่ายล่าช้า
				$receiptID =trim($res1["receiptID"]); //รหัสใบเสร็จ หากไม่มีแสดงว่ายังไมจ่าย
			
					
					if($receiptID == ''){ //แดงเข้มปี๊ดดด
						$colorbox = '#CC0000';
						$numshow = 'N';
					}else{

						if($delay<= 7){ //เขียว
							$colorbox = '#00AA00';
						}else if($delay <= 37){ //แดงอ่อน
							$colorbox = '#FF6666';
						}else if($delay <= 67){ //แดง
							$colorbox = '#FF3333';
						}else if($delay <= 97){ //แดงเข้ม
							$colorbox = '#FF0000';
						}else if($delay > 97){ //แดงเข้มปี๊ดดด
							$colorbox = '#CC0000';
						}else{
							$colorbox = '';
						}
						
						$numshow = $delay;
					}
					echo "
						<td>
							<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
								<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
							</table>
						</td>	
						";
			}
				echo "</tr></table>
					</td></tr>
					</table></td></tr>
					";
			
			
			$paymentlatesum += $sumamtnovat;
			$alldebtsum += $alldebt;
			
			unset($colorbox);
			unset($numshow);
			unset($limit);
			unset($sizeofrow);
			unset($dateclose);
		}
		//ยอดหนี้เฉลี่ยต่อสัญญา 
		$avgpercon = $paymentlatesum/$num_name5;
		
		$paymentlatesum = @number_format($paymentlatesum,2);		
		$minpaymentsum = @number_format($alldebtsum,2);
		$avgperconsum = @number_format($avgpercon,2);	
		echo "<input type=\"hidden\" id=\"paymentlatesum_h2\" value=\"$paymentlatesum\">";
		echo "<input type=\"hidden\" id=\"minpaymentsum_h2\" value=\"$minpaymentsum\">";
		echo "<input type=\"hidden\" id=\"avgperconsum_h2\" value=\"$avgperconsum\">";
		?>
		<script>	
						if($("#paymentlatesum_h2").val()!=""){
							$("#paymentlatesumshow_h2").html(" ยอดเช่าซื้อคงเหลือรวม   "+$("#paymentlatesum_h2").val()+" บาท");
						}else{
							$("#paymentlatesumshow_h2").html(" ไม่มียอดเช่าซื้อคงเหลือ  ");
						}						
						if($("#minpaymentsum_h2").val()!=""){
							$("#minpaymentsumshow_h2").html(" ยอดภาระผ่อนต่อเดือนรวม    "+$("#minpaymentsum_h2").val()+" บาท");
						}else{
							$("#minpaymentsumshow_h2").html(" ไม่มียอดภาระผ่อนต่อเดือน  ");
						}
						if($("#avgperconsum_h2").val()!=""){
							$("#Debtsumshow_h2").html(" ยอดเช่าซื้อคงเหลือเฉลี่ย     "+$("#avgperconsum_h2").val()+" บาท");
						}else{
							$("#Debtsumshow_h2").html(" ไม่มียอดเช่าซื้อคงเหลือเฉลี่ย   ");
						}
		</script>	
	<?php				
		echo "<tr><td><span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name5 สัญญา)</b></font></span></td></tr>";
		$paymentlatesum = "";
		$minpaymentsum = "";
		$avgpercon ="";
		$avgperconsum="";
	} ?>		
		</table>
	</td>
</tr>




<tr bgcolor="#BCE6FC">
    <td valign="top" align="right"><b>เลขที่สัญญา(ผู้ค้ำประกัน) :</b></td>
    <td bgcolor="#FFFFFF">
		<table width="100%">
	<?php 
	if($num_name3 == 0){
		echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
	}else{	
		echo "<tr><td>
			<span id=\"paymentlatesumshow_h3\" style=\"font-size:14px; color:#FF0000;\"></span>
			<span id=\"minpaymentsumshow_h3\" style=\"font-size:14px; color:#CE0000;\"></span>
			<span id=\"Debtsumshow_h3\" style=\"font-size:14px; color:#006600;\"></span>
			</td></tr>";
		while($res_name2=pg_fetch_array($query_name3)){
			$contractID=$res_name2["contractID"]; 						
	
			//หายอด ทั้งหมดที่ต้องจ่ายไม่รวม VAT
			$qrysum2=pg_query("select sum(\"debtNet\"),\"contractID\" from public.\"thcap_v_lease_table\" where \"contractID\" = '$contractID' and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID') group by \"contractID\""); 
			list($sumamtnovat,$contact2)=pg_fetch_array($qrysum2);
			$sumamtnovatshow = number_format($sumamtnovat,2);
			
			//ยอดภาระผ่อนต่อเดือน 
			$qry1=pg_query("select \"typePayAmt\" from public.\"thcap_v_lease_table\" where \"contractID\" = '$contractID' and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID')"); 
			$numrows=pg_num_rows($qry1);
			$res1=pg_fetch_array($qry1);
			
				$alldebt=trim($res1["typePayAmt"]); //ยอดที่ต้องชำระ			
				$alldebtshow = number_format($alldebt,2);
				
				
			//หาว่าค้างชำระกี่งวด 
			$qry_latepay=pg_query("select \"debtID\" from public.\"thcap_v_lease_table\" where \"contractID\" = '$contractID'
							and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID')
							and \"debtDueDate\" <= current_date 
							and \"receiptID\" is null
							"); 
			$rows_latepay=pg_num_rows($qry_latepay);
			
			
				$alldebt=trim($res1["typePayAmt"]); //ยอดที่ต้องชำระ			
				$alldebtshow = number_format($alldebt,2);	
				
			//หาวันที่ปิดบัญชี
			$dateclosesql = pg_query("SELECT thcap_checkcontractcloseddate('$contractID')");
			$dateclosere = pg_fetch_array($dateclosesql);
			$dateclose = $dateclosere['thcap_checkcontractcloseddate'];	
			
			if($dateclose != ""){		
				$txtclose="สัญญาปิดแล้ว";
				$color='#000000';
				$colorstatefield = '#E0E0E0';
				$latetitle = "";		
			}else{
				$colorstatefield = "";
				$txtclose = "";
				if($rows_latepay=="1"){ //ค้าง 1 งวด
					$color='#9933FF';
					$latetitle = 'ค้าง 1 งวด';
				}else if($rows_latepay=="2"){ // ค้าง 2 งวด
					$color='ORANGE';
					$latetitle = 'ค้าง 2 งวด';
				}else if($rows_latepay>="3"){ // ค้าง 3 งวดขึ้นไป
					$color='RED';
					$latetitle = 'ค้าง 3 งวดขึ้นไป';
				}else{ // สัญญาปกติ
					$color="#00DDDD";
					$latetitle = 'สัญญาปกติ';
				}
			
			}

			//contractid
			if($color=="#000000"&&$txtclose=="สัญญาปิดแล้ว")
			{
				$showcon = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color><U><span title=\"$txtclose\"><img src=\"images/paper.png\" />$contractID</span></U></font></a>";
			}
			else
			{
				$showcon = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color><U><span title=\"$txtclose\">$contractID</span></U></font></a>";
			}
			//รายละเอียดสินค้า
			$showproduct = "<img src=\"images/detail.gif\" style=\"cursor:pointer;\" onclick=\"popU('../thcap_installments/show_list_product.php?contractID=$contractID&showall=true','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" >";
			echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
			echo "<tr><td>$showcon<font color=\"$color\">  รายละเอียดสินค้า $showproduct  ยอดภาระผ่อนต่อเดือน: $alldebtshow ยอดเช่าซื้อคงเหลือ: $sumamtnovatshow</td></tr>"; 
			echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
					<tr>";
					
			$qry1=pg_query("select \"delay\",\"receiptID\" from public.\"thcap_v_lease_table\" where \"contractID\" = '$contractID' 
											and \"typePayID\"=account.\"thcap_mg_getMinPayType\"('$contractID')
											and \"debtDueDate\" <= current_date
											order by \"debtDueDate\" DESC limit 36"); 
			while($res1=pg_fetch_array($qry1)){

				 $delay =trim($res1["delay"]); //วันที่จ่ายล่าช้า
				$receiptID =trim($res1["receiptID"]); //รหัสใบเสร็จ หากไม่มีแสดงว่ายังไมจ่าย
			
					
					if($receiptID == ''){ //แดงเข้มปี๊ดดด
						$colorbox = '#CC0000';
						$numshow = 'N';
					}else{

						if($delay<= 7){ //เขียว
							$colorbox = '#00AA00';
						}else if($delay <= 37){ //แดงอ่อน
							$colorbox = '#FF6666';
						}else if($delay <= 67){ //แดง
							$colorbox = '#FF3333';
						}else if($delay <= 97){ //แดงเข้ม
							$colorbox = '#FF0000';
						}else if($delay > 97){ //แดงเข้มปี๊ดดด
							$colorbox = '#CC0000';
						}else{
							$colorbox = '';
						}
						$numshow = $delay;
					}

					echo "
						<td>
							<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
								<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
							</table>
						</td>	
						";
			}
				echo "</tr></table>
					</td></tr>
					</table></td></tr>
					";
			
			
			$paymentlatesum += $sumamtnovat;
			$alldebtsum += $alldebt;
			
			unset($colorbox);
			unset($numshow);
			unset($limit);
			unset($sizeofrow);
			unset($dateclose);
		}
		//ยอดหนี้เฉลี่ยต่อสัญญา 
		$avgpercon = $paymentlatesum/$num_name3;
		
		
		$paymentlatesum = @number_format($paymentlatesum,2);		
		$minpaymentsum = @number_format($alldebtsum,2);
		$avgperconsum = @number_format($avgpercon,2);	
		echo "<input type=\"hidden\" id=\"paymentlatesum_h3\" value=\"$paymentlatesum\">";
		echo "<input type=\"hidden\" id=\"minpaymentsum_h3\" value=\"$minpaymentsum\">";
		echo "<input type=\"hidden\" id=\"avgperconsum_h3\" value=\"$avgperconsum\">";
		?>
		<script>	
						if($("#paymentlatesum_h3").val()!=""){
							$("#paymentlatesumshow_h3").html(" ยอดเช่าซื้อคงเหลือรวม   "+$("#paymentlatesum_h3").val()+" บาท");
						}else{
							$("#paymentlatesumshow_h3").html(" ไม่มียอดเช่าซื้อคงเหลือ  ");
						}						
						if($("#minpaymentsum_h3").val()!=""){
							$("#minpaymentsumshow_h3").html(" ยอดภาระผ่อนต่อเดือนรวม    "+$("#minpaymentsum_h3").val()+" บาท");
						}else{
							$("#minpaymentsumshow_h3").html(" ไม่มียอดภาระผ่อนต่อเดือน  ");
						}
						if($("#avgperconsum_h3").val()!=""){
							$("#Debtsumshow_h3").html(" ยอดเช่าซื้อคงเหลือเฉลี่ย     "+$("#avgperconsum_h3").val()+" บาท");
						}else{
							$("#Debtsumshow_h3").html(" ไม่มียอดเช่าซื้อคงเหลือเฉลี่ย   ");
						}
		</script>	
	<?php				
		echo "<tr><td><span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name3 สัญญา)</b></font></span></td></tr>";
		$paymentlatesum = "";
		$minpaymentsum = "";
		$avgpercon ="";
		$avgperconsum="";
	} ?>		
		</table>
	</td>
</tr>



</table>



<!---- จบการหาประเภทสัญญา HIRE_PURCHASE / LEASING -->



<br>
<?php 
////*********************ลูกค้าที่อาจจะใช่คนเดียวกัน*********************************/////////////////////////

//ค้นหาลูกค้าที่อาจจะใช่คนเดียวกัน
$N_IDCARD=strtr($N_IDCARD, "-", " "); //แปลงค่าที่คีย์ - ให้เป็นช่องว่าง
$N_IDCARD=ereg_replace('[[:space:]]+', '', trim($N_IDCARD)); //ตัดช่องว่างออก


$qry_check=pg_query("select a.\"CusID\",b.\"full_name\" from \"Fn\" a
LEFT JOIN \"VSearchCus\" b on a.\"CusID\"=b.\"CusID\" 
WHERE (replace(replace(\"N_IDCARD\",' ',''),'-','') = '$N_IDCARD' OR
replace(replace(\"N_CARDREF\",' ',''),'-','') = '$N_IDCARD') and a.\"CusID\" NOT IN (select \"CusID\" from \"Fa1\" where \"CusID\"='$CusID')");
$numcheck=pg_num_rows($qry_check);
if($numcheck>0){
	echo "<div style=\"margin:0px auto;width:1140px;background-color:#FFFFCC;padding:5px;\"><b>::ลูกค้าที่อาจจะใช่บุคคลเดียวกัน::</b></div>";
	while($rescheck=pg_fetch_array($qry_check)){
	
	$CusIDclone = $rescheck['CusID'];
	//ค้นหาชื่อ-นามสกุล
		$qry_name=pg_query("select \"full_name\",a.\"CusID\",b.\"N_IDCARD\",b.\"N_CARDREF\" from \"VSearchCus\" a
		LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"
		WHERE a.\"CusID\" = '$CusIDclone'");
		$result=pg_fetch_array($qry_name);
		$name=trim($result["full_name"]);
		$CusID=trim($result["CusID"]);

	
		//ค้นหาว่าเป็นผู้เช่าซื้อเลขที่สัญญาใดบ้าง
		$query_name22 = pg_query("
										select a.\"contractID\" from \"thcap_ContactCus\" a
										LEFT JOIN \"thcap_contract\" b on a.\"contractID\" = b.\"contractID\"
										WHERE \"CusID\"='$CusID' and \"CusState\" = '0'
										AND \"thcap_get_creditType\"(a.\"contractID\") != 'HIRE_PURCHASE'
										AND \"thcap_get_creditType\"(a.\"contractID\") != 'LEASING'	
		
									");
		$num_name22 = pg_num_rows($query_name22);


		//ค้นหาว่าเป็นผู้ค้ำเลขที่สัญญาใดบ้าง
		$query_name33 = pg_query("
										select a.\"contractID\" from \"thcap_ContactCus\" a
										LEFT JOIN \"thcap_contract\" b on a.\"contractID\" = b.\"contractID\"
										WHERE \"CusID\"='$CusID' and \"CusState\" = '2'
										AND \"thcap_get_creditType\"(a.\"contractID\") != 'HIRE_PURCHASE'
										AND \"thcap_get_creditType\"(a.\"contractID\") != 'LEASING'
									");
		$num_name33 = pg_num_rows($query_name33);
		
		//ค้นหาว่าเป็นผู้กู้ร่วมเลขที่สัญญาใดบ้าง
		$query_name6 = pg_query("
										select a.\"contractID\" from \"thcap_ContactCus\" a
										LEFT JOIN \"thcap_contract\" b on a.\"contractID\" = b.\"contractID\"
										WHERE \"CusID\"='$CusID' and \"CusState\" = '1'
										AND \"thcap_get_creditType\"(a.\"contractID\") != 'HIRE_PURCHASE'
										AND \"thcap_get_creditType\"(a.\"contractID\") != 'LEASING'	
						
		
									");
		$num_name6 = pg_num_rows($query_name6);


		?>
	
		<table width="1150" cellSpacing="1" cellPadding="3" border="0" bgcolor="#D7F0FD" align="center">
		<tr bgcolor="#FFDDDD">
			<td width="150" align="right"><b>ชื่อ/สกุล :</b></td>
			<td bgcolor="#FFFFFF"><font color="#0000FF"><b><?php echo "$name (รหัสลูกค้่า $CusID)"; ?></b></font><br>
			</td>
		</tr>
		<tr bgcolor="#FFDDDD">
			<td valign="top" align="right"><b>เลขที่สัญญา(ผู้กู้หลัก) :</b></td>
			<td bgcolor="#FFFFFF">
				<table width="100%">
			<?php 
			if($num_name22 == 0){
				echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
			}else{	
				echo "<tr><td>
					<span id=\"paymentlatesumshow3\" style=\"font-size:14px; color:#FF0000;\"></span>
					<span id=\"minpaymentsumshow3\" style=\"font-size:14px; color:#CE0000;\"></span>
					<span id=\"Debtsumshow3\" style=\"font-size:14px; color:#006600;\"></span>
					</td></tr>";
				while($res_name22=pg_fetch_array($query_name22)){
					$contractID3=$res_name22["contractID"]; 

				//หาจำนวนวันจ่ายย้อนหลัง
					$colorboxlist = paymentlatebox($contractID3);
					$sizeofrow = sizeof($colorboxlist);
					if($sizeofrow > 36){
						$limit = $sizeofrow - 36;
					}else{
						$limit = 0;
					}
					
				//จบหาจำนวนวันจ่ายย้อนหลัง					
					
					//ยอดหนี้ค้างชำระรวม 
					$qry_fr=pg_query("SELECT \"thcap_getLoanBalanceAmt\"('$contractID3','Now') ");
					list($paymentlate3)=pg_fetch_array($qry_fr);
					//ยอดภาระผ่อนต่อเดือน 
					$qry_fr=pg_query("SELECT \"contractID\",\"conMinPay\",\"conStartDate\",\"conLoanAmt\",\"conIntCurRate\" from thcap_mg_contract where \"contractID\" = '$contractID3' ");
					while($re_fr=pg_fetch_array($qry_fr)){
						$conid3=$re_fr["contractID"];
						$minpayment3=$re_fr["conMinPay"];
						$conStartDate3 = $re_fr["conStartDate"];
						$conLoanAmt3 = $re_fr["conLoanAmt"];
						$conStartDate3 = $re_fr["conStartDate"];
						$conIntCurRate3 = $re_fr["conIntCurRate"];
					}			
					
					$conStartDate3 = checknull($conStartDate3);
					$conLoanAmt3 = number_format(checknull($conLoanAmt3),2);
					$conIntCurRate3 = checknull($conIntCurRate3)."%";
													

					if($paymentlate3 > 0){
						list($txtclose3,$color3) = behindhand($contractID3);
						$minpaymentsum3 += $minpayment3;
						$colorstatefield = '';		
					}else{
						$txtclose3="สัญญาปิดแล้ว";
						$color3='#000000';
						$colorstatefield = '#E0E0E0';	
					}	
					
					$paymentlate3 = checknull($paymentlate3);			
					$paymentlate13 = number_format($paymentlate3,2);
					
					if($color3=="#000000"&&$txtclose3=="สัญญาปิดแล้ว")
					{
						$showcon3 = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID3','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color3><U><span title=\"$txtclose3\"><img src=\"images/paper.png\" />$contractID3</span></U></font></a>";
					}
					else
					{
						$showcon3 = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID3','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color3><U><span title=\"$txtclose3\">$contractID3</span></U></font></a>";
					}
					echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
					echo "<tr><td>$showcon3<font color=\"$color3\"> วันที่เริ่มกู้: $conStartDate3 จำนวนเงินกู้เริ่มแรก: $conLoanAmt3 อัตราดอกเบี้ย: $conIntCurRate3  ยอดเงินคงเหลือ: $paymentlate13</td></tr>"; 
					echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
					<tr>";
				for($i = $sizeofrow-1;$i>=$limit;$i--){
					if($colorboxlist[$i] == 'nothing'){ //แดงเข้มปี๊ดดด
						$colorbox = '#CC0000';
						$numshow = 'N';
					}else{
					
						$numshow = ceil(($colorboxlist[$i] - 7)/30);
						if($numshow <= 0){ $numshow = 0; }
						
						if($colorboxlist[$i]<= 7){ //เขียว
							$colorbox = '#00AA00';
						}else if($colorboxlist[$i] <= 37){ //แดงอ่อน
							$colorbox = '#FF6666';
						}else if($colorboxlist[$i] <= 67){ //แดง
							$colorbox = '#FF3333';
						}else if($colorboxlist[$i] <= 97){ //แดงเข้ม
							$colorbox = '#FF0000';
						}else if($colorboxlist[$i] > 97){ //แดงเข้มปี๊ดดด
							$colorbox = '#CC0000';
						}else{
							$colorbox = '';
						}
					}
					echo "
						<td>
							<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
								<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
							</table>
						</td>	
						";
				}
				echo "</tr></table>
					</td></tr>
					</table></td></tr>";
					
					
					$paymentlatesum3 += $paymentlate3;
					$paymentlate3 = "";					
					$minpayment3 = "";
					unset($colorbox);
					unset($numshow);
					unset($limit);
					unset($sizeofrow);
					
				}
				//ยอดหนี้เฉลี่ยต่อสัญญา 
				$avgpercon3 = $paymentlatesum3/$num_name22;
				
				$paymentlatesum3 = @number_format($paymentlatesum3,2);		
				$minpaymentsum3 = @number_format($minpaymentsum3,2);
				$avgperconsum3 = @number_format($avgpercon3,2);	
				echo "<input type=\"hidden\" id=\"paymentlatesum3\" value=\"$paymentlatesum3\">";
				echo "<input type=\"hidden\" id=\"minpaymentsum3\" value=\"$minpaymentsum3\">";
				echo "<input type=\"hidden\" id=\"avgperconsum3\" value=\"$avgperconsum3\">";
				?>
				<script>	
								if($("#paymentlatesum3").val()!=""){
									$("#paymentlatesumshow3").html(" ยอดหนี้ค้างชำระรวม   "+$("#paymentlatesum3").val()+" บาท");
								}else{
									$("#paymentlatesumshow3").html(" ไม่มียอดหนี้ค้างชำระ  ");
								}						
								if($("#minpaymentsum").val()!=""){
									$("#minpaymentsumshow3").html(" ยอดภาระผ่อนต่อเดือน    "+$("#minpaymentsum3").val()+" บาท");
								}else{
									$("#minpaymentsumshow3").html(" ไม่มียอดภาระผ่อนต่อเดือน  ");
								}
								if($("#avgperconsum").val()!=""){
									$("#Debtsumshow3").html(" ยอดหนี้เฉลี่ยต่อสัญญา     "+$("#avgperconsum3").val()+" บาท");
								}else{
									$("#Debtsumshow3").html(" ไม่มียอดหนี้เฉลี่ยต่อสัญญา   ");
								}
				</script>	
			<?php				
				echo "<tr><td><span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name22 สัญญา)</b></font></span></td></tr>";
				$paymentlatesum3 = "";
				$minpaymentsum3 = "";
				$avgpercon3 ="";
				$avgperconsum3="";
				
			} ?>		
				</table>
			</td>
		</tr>

		
		
		<tr bgcolor="#FFDDDD">
			<td valign="top" align="right"><b>เลขที่สัญญา(ผู้กู้ร่วม) :</b></td>
			<td bgcolor="#FFFFFF">
				<table width="100%">
			<?php 
			if($num_name6 == 0){
				echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
			}else{	
				echo "<tr><td>
					<span id=\"paymentlatesumshow4\" style=\"font-size:14px; color:#FF0000;\"></span>
					<span id=\"minpaymentsumshow4\" style=\"font-size:14px; color:#CE0000;\"></span>
					<span id=\"Debtsumshow4\" style=\"font-size:14px; color:#006600;\"></span>
					</td></tr>";
				while($res_name6=pg_fetch_array($query_name6)){
					$contractID6=$res_name6["contractID"]; 			
					
					//หาจำนวนวันจ่ายย้อนหลัง
						$colorboxlist = paymentlatebox($contractID6);
						$sizeofrow = sizeof($colorboxlist);
						if($sizeofrow > 36){
							$limit = $sizeofrow - 36;
						}else{
							$limit = 0;
						}
							
					//จบหาจำนวนวันจ่ายย้อนหลัง
					
					//ยอดหนี้ค้างชำระรวม 
					$qry_fr=pg_query("SELECT \"thcap_getLoanBalanceAmt\"('$contractID6','Now') ");
					list($paymentlate6)=pg_fetch_array($qry_fr);
					//ยอดภาระผ่อนต่อเดือน 
					$qry_fr=pg_query("SELECT \"contractID\",\"conMinPay\",\"conStartDate\",\"conLoanAmt\",\"conIntCurRate\" from thcap_mg_contract where \"contractID\" = '$contractID4' ");
					while($re_fr=pg_fetch_array($qry_fr)){
						$conid6=$re_fr["contractID"];
						$minpayment6=$re_fr["conMinPay"];
						$conStartDate6 = $re_fr["conStartDate"];
						$conLoanAmt6 = $re_fr["conLoanAmt"];
						$conStartDate6 = $re_fr["conStartDate"];
						$conIntCurRate6 = $re_fr["conIntCurRate"];
					}			
					
					$conStartDate6 = checknull($conStartDate6);
					$conLoanAmt6 = number_format(checknull($conLoanAmt6),2);
					$conIntCurRate6 = checknull($conIntCurRate6);						
				
					if($paymentlate6 > 0){
						list($txtclose6,$color6) = behindhand($contractID6);
						$minpaymentsum6 += $minpayment6;
						$colorstatefield = '';	
					}else{
						$txtclose6="สัญญาปิดแล้ว";
						$color6='#000000';
						$colorstatefield = '#E0E0E0';		
					}	
					
					$paymentlate6 = checknull($paymentlate6);					
					$paymentlate16 = number_format($paymentlate6,2);
					
					if($color6=="#000000"&&$txtclose6=="สัญญาปิดแล้ว")
					{
						$showcon6 = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID6','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color6><U><span title=\"$txtclose6\"><img src=\"images/paper.png\" />$contractID6</span></U></font></a>";
					}
					else
					{
						$showcon6 = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID6','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color6><U><span title=\"$txtclose6\">$contractID6</span></U></font></a>";
					}
					echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
					echo "<tr><td>$showcon6<font color=\"$color6\"> วันที่เริ่มกู้: $conStartDate6 จำนวนเงินกู้เริ่มแรก: $conLoanAmt6 อัตราดอกเบี้ย: $conIntCurRate6 %  ยอดเงินคงเหลือ:  $paymentlate16</td></tr>"; 
					echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
					<tr>";
				for($i = $sizeofrow-1;$i>=$limit;$i--){
					if($colorboxlist[$i] == 'nothing'){ //แดงเข้มปี๊ดดด
						$colorbox = '#CC0000';
						$numshow = 'N';
					}else{
					
						$numshow = ceil(($colorboxlist[$i] - 7)/30);
						if($numshow <= 0){ $numshow = 0; }
						
						if($colorboxlist[$i]<= 7){ //เขียว
							$colorbox = '#00AA00';
						}else if($colorboxlist[$i] <= 37){ //แดงอ่อน
							$colorbox = '#FF6666';
						}else if($colorboxlist[$i] <= 67){ //แดง
							$colorbox = '#FF3333';
						}else if($colorboxlist[$i] <= 97){ //แดงเข้ม
							$colorbox = '#FF0000';
						}else if($colorboxlist[$i] > 97){ //แดงเข้มปี๊ดดด
							$colorbox = '#CC0000';
						}else{
							$colorbox = '';
						}
					}
					echo "
						<td>
							<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
								<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
							</table>
						</td>	
						";
				}
				echo "</tr></table>
					</td></tr>
					</table></td></tr>";
					
					$paymentlatesum6 += $paymentlate6;
					$paymentlate6 = "";					
					$minpayment6 = "";
					unset($colorbox);
					unset($numshow);
					unset($limit);
					unset($sizeofrow);
					
				}
				//ยอดหนี้เฉลี่ยต่อสัญญา 
				$avgpercon6 = $paymentlatesum6/$num_name6;
				
				$paymentlatesum6 = @number_format($paymentlatesum6,2);		
				$minpaymentsum6 = @number_format($minpaymentsum6,2);
				$avgperconsum6 = @number_format($avgpercon6,2);	
				echo "<input type=\"hidden\" id=\"paymentlatesum6\" value=\"$paymentlatesum6\">";
				echo "<input type=\"hidden\" id=\"minpaymentsum6\" value=\"$minpaymentsum6\">";
				echo "<input type=\"hidden\" id=\"avgperconsum6\" value=\"$avgperconsum6\">";
				?>
				<script>	
								if($("#paymentlatesum6").val()!=""){
									$("#paymentlatesumshow6").html(" ยอดหนี้ค้างชำระรวม   "+$("#paymentlatesum6").val()+" บาท");
								}else{
									$("#paymentlatesumshow6").html(" ไม่มียอดหนี้ค้างชำระ  ");
								}						
								if($("#minpaymentsum6").val()!=""){
									$("#minpaymentsumshow6").html(" ยอดภาระผ่อนต่อเดือน    "+$("#minpaymentsum6").val()+" บาท");
								}else{
									$("#minpaymentsumshow6").html(" ไม่มียอดภาระผ่อนต่อเดือน  ");
								}
								if($("#avgperconsum6").val()!=""){
									$("#Debtsumshow6").html(" ยอดหนี้เฉลี่ยต่อสัญญา     "+$("#avgperconsum6").val()+" บาท");
								}else{
									$("#Debtsumshow6").html(" ไม่มียอดหนี้เฉลี่ยต่อสัญญา   ");
								}
				</script>	
			<?php				
				echo "<tr><td><span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name6 สัญญา)</b></font></span></td></tr>";
				$paymentlatesum6 = "";
				$minpaymentsum6 = "";
				$avgpercon6 ="";
				$avgperconsum6="";
			} ?>		
				</table>
			</td>
		</tr>
		
		
		
		
		
		
		<tr bgcolor="#FFDDDD">
			<td valign="top" align="right"><b>เลขที่สัญญา(ผู้ค้ำ) :</b></td>
			<td bgcolor="#FFFFFF">
				<table width="100%">
			<?php 
			if($num_name33 == 0){
				echo "<tr><td>-- ไม่พบข้อมูล --</td></tr>";
			}else{	
				echo "<tr><td>
					<span id=\"paymentlatesumshow4\" style=\"font-size:14px; color:#FF0000;\"></span>
					<span id=\"minpaymentsumshow4\" style=\"font-size:14px; color:#CE0000;\"></span>
					<span id=\"Debtsumshow4\" style=\"font-size:14px; color:#006600;\"></span>
					</td></tr>";
				while($res_name33=pg_fetch_array($query_name33)){
					$contractID4=$res_name33["contractID"]; 			
					
					//หาจำนวนวันจ่ายย้อนหลัง
						$colorboxlist = paymentlatebox($contractID4);
						$sizeofrow = sizeof($colorboxlist);
						if($sizeofrow > 36){
							$limit = $sizeofrow - 36;
						}else{
							$limit = 0;
						}
							
					//จบหาจำนวนวันจ่ายย้อนหลัง
					
					//ยอดหนี้ค้างชำระรวม 
					$qry_fr=pg_query("SELECT \"thcap_getLoanBalanceAmt\"('$contractID4','Now') ");
					list($paymentlate4)=pg_fetch_array($qry_fr);
					//ยอดภาระผ่อนต่อเดือน 
					$qry_fr=pg_query("SELECT \"contractID\",\"conMinPay\",\"conStartDate\",\"conLoanAmt\",\"conIntCurRate\" from thcap_mg_contract where \"contractID\" = '$contractID4' ");
					while($re_fr=pg_fetch_array($qry_fr)){
						$conid4=$re_fr["contractID"];
						$minpayment4=$re_fr["conMinPay"];
						$conStartDate4 = $re_fr["conStartDate"];
						$conLoanAmt4 = $re_fr["conLoanAmt"];
						$conStartDate4 = $re_fr["conStartDate"];
						$conIntCurRate4 = $re_fr["conIntCurRate"];
					}			
					
					$conStartDate4 = checknull($conStartDate4);
					$conLoanAmt4 = number_format(checknull($conLoanAmt4),2);
					$conIntCurRate4 = checknull($conIntCurRate4);						
				
					if($paymentlate4 > 0){
						list($txtclose4,$color4) = behindhand($contractID4);
						$minpaymentsum4 += $minpayment4;
						$colorstatefield = '';	
					}else{
						$txtclose4="สัญญาปิดแล้ว";
						$color4='#000000';
						$colorstatefield = '#E0E0E0';
					}	
					
					$paymentlate4 = checknull($paymentlate4);					
					$paymentlate14 = number_format($paymentlate4,2);
					
					if($color4=="#000000"&&$txtclose4=="สัญญาปิดแล้ว")
					{
						$showcon4 = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID4','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color4><U><span title=\"$txtclose4\"><img src=\"images/paper.png\" />$contractID4</span></U></font></a>";
					}
					else
					{
						$showcon4 = "<a style=\"cursor:pointer\" onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID4','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" title=\"ดูตารางผ่อนชำระ\"><font color=$color4><U><span title=\"$txtclose4\">$contractID4</span></U></font></a>";
					}
					echo "<tr><td><table width=\"100%\" cellspacing=\"1\" bgcolor=\"$colorstatefield\">";
					echo "<tr><td>$showcon4<font color=\"$color4\"> วันที่เริ่มกู้: $conStartDate4 จำนวนเงินกู้เริ่มแรก: $conLoanAmt4 อัตราดอกเบี้ย: $conIntCurRate4 %  ยอดเงินคงเหลือ:  $paymentlate14</td></tr>"; 
					echo "<tr><td><table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#E0E0E0\">
					<tr>";
				for($i = $sizeofrow-1;$i>=$limit;$i--){
					if($colorboxlist[$i] == 'nothing'){ //แดงเข้มปี๊ดดด
						$colorbox = '#CC0000';
						$numshow = 'N';
					}else{
					
						$numshow = ceil(($colorboxlist[$i] - 7)/30);
						if($numshow <= 0){ $numshow = 0; }
						
						if($colorboxlist[$i]<= 7){ //เขียว
							$colorbox = '#00AA00';
						}else if($colorboxlist[$i] <= 37){ //แดงอ่อน
							$colorbox = '#FF6666';
						}else if($colorboxlist[$i] <= 67){ //แดง
							$colorbox = '#FF3333';
						}else if($colorboxlist[$i] <= 97){ //แดงเข้ม
							$colorbox = '#FF0000';
						}else if($colorboxlist[$i] > 97){ //แดงเข้มปี๊ดดด
							$colorbox = '#CC0000';
						}else{
							$colorbox = '';
						}
					}
					echo "
						<td>
							<table width=\"20px\" height=\"10px;\" frame=\"box\" bgcolor=\"$colorbox\">
								<tr><td align=\"center\" ><font size=\"2px;\">$numshow</font></td></tr>
							</table>
						</td>	
						";
				}
				echo "</tr></table>
					</td></tr>
					</table></td></tr>";
					
					$paymentlatesum4 += $paymentlate4;
					$paymentlate4 = "";					
					$minpayment4 = "";
					unset($colorbox);
					unset($numshow);
					unset($limit);
					unset($sizeofrow);
					
				}
				//ยอดหนี้เฉลี่ยต่อสัญญา 
				$avgpercon4 = $paymentlatesum4/$num_name33;
				
				$paymentlatesum4 = @number_format($paymentlatesum4,2);		
				$minpaymentsum4 = @number_format($minpaymentsum4,2);
				$avgperconsum4 = @number_format($avgpercon4,2);	
				echo "<input type=\"hidden\" id=\"paymentlatesum4\" value=\"$paymentlatesum4\">";
				echo "<input type=\"hidden\" id=\"minpaymentsum4\" value=\"$minpaymentsum4\">";
				echo "<input type=\"hidden\" id=\"avgperconsum4\" value=\"$avgperconsum4\">";
				?>
				<script>	
								if($("#paymentlatesum4").val()!=""){
									$("#paymentlatesumshow4").html(" ยอดหนี้ค้างชำระรวม   "+$("#paymentlatesum4").val()+" บาท");
								}else{
									$("#paymentlatesumshow4").html(" ไม่มียอดหนี้ค้างชำระ  ");
								}						
								if($("#minpaymentsum4").val()!=""){
									$("#minpaymentsumshow4").html(" ยอดภาระผ่อนต่อเดือน    "+$("#minpaymentsum4").val()+" บาท");
								}else{
									$("#minpaymentsumshow4").html(" ไม่มียอดภาระผ่อนต่อเดือน  ");
								}
								if($("#avgperconsum4").val()!=""){
									$("#Debtsumshow4").html(" ยอดหนี้เฉลี่ยต่อสัญญา     "+$("#avgperconsum4").val()+" บาท");
								}else{
									$("#Debtsumshow4").html(" ไม่มียอดหนี้เฉลี่ยต่อสัญญา   ");
								}
				</script>	
			<?php				
				echo "<tr><td><span style=\"background-color:yellow;\"><font size=2 color=red><b>(รวม $num_name33 สัญญา)</b></font></span></td></tr>";
				$paymentlatesum4 = "";
				$minpaymentsum4 = "";
				$avgpercon4 ="";
				$avgperconsum4="";
			} ?>		
				</table>
			</td>
		</tr>
		</table>
		<br>
<?php 
		}
	}
}else{
	echo "<hr width=850>";
	echo "<center><h1>ไม่พบข้อมูล</h1></center>";
}
?>