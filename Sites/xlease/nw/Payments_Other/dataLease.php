<?php
// ส่วนติดต่อกับฐานข้อมูล    
include("../../config/config.php");
$currentDate=nowDate();

$rowHP = $_GET["amount"];
$conIDHP = $_GET["conIDHP"];
$haveWht = $_GET["haveWht"];
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<table align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF" id="tb1">
	<tr bgcolor="#4399c3" style="color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:14px;" height="25">
		<th width="80">งวดที่</th>
		<th width="150">วันที่ครบกำหนดชำระ</th>
		<th width="150">จำนวนเงินก่อน VAT</th>
		<th width="150">จำนวน VAT</th>
		<th width="180">จำนวนเงินรวม</th>
		<th width="120" id="moneyth">ภาษีหัก ณ ที่จ่าย</th>
	</tr>
	<?php
		$qry_HP = pg_query("select * from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$conIDHP' and \"debtStatus\"='1' and \"debtIsOther\" = '0' order by \"typePayRefValue\"::integer limit $rowHP ");
		$numrowsHP = pg_num_rows($qry_HP);
		$arrayHP = "{"; // งวดที่จะชำระทั้งหมด
		$sumMoney = 0; // จำนวนค่างวดที่จะชำระรวม
		$numhp = 0;
		while($dueHP = pg_fetch_array($qry_HP))
		{
			$numhp++;
			$debtID = $dueHP["debtID"];
			$typePayRefValue = $dueHP["typePayRefValue"]; // งวดที่
			$debtDueDate = $dueHP["debtDueDate"]; // วันที่ครบกำหนดชำระ
			$debtNet = $dueHP["debtNet"]; // จำนวนเงินก่อน VAT
			$debtVat = $dueHP["debtVat"]; // จำนวน VAT
			$typePayLeft = $dueHP["typePayLeft"]; // จำนวนเงินรวม
			
			$arrayHP = $arrayHP."$typePayRefValue"; // งวดที่จะชำระทั้งหมด
			$sumMoney += $typePayLeft; // จำนวนค่างวดที่จะชำระรวม
			
			// หาภาษีหัก ณ ที่จ่าย
			$whtAmtfuncHP = pg_query("SELECT \"thcap_checkdebtwht\"('$debtID','$currentDate')");					
			$whtAmtHP = pg_fetch_array($whtAmtfuncHP);
			$whtAmtHP = $whtAmtHP['thcap_checkdebtwht'];
			
			echo "<tr bgcolor=\"#DBF2FD\"style=\"color:#444; font-family:Arial, Helvetica, sans-serif; font-size:14px;\">";
			echo "<td align=\"center\">$typePayRefValue</td>";
			echo "<td align=\"center\">$debtDueDate</td>";
			echo "<td align=\"right\">".number_format($debtNet,2)."</td>";
			echo "<td align=\"right\">".number_format($debtVat,2)."</td>";
			echo "<td align=\"right\">".number_format($typePayLeft,2)."</td>";
			if($haveWht == "no"){echo "<td align=center id=\"whtHP$numhp\"><input id=\"whtHPtxt$numhp\" name=\"whtHPtxt$numhp\" type=\"textbox\" value=\"$whtAmtHP\" size=\"12\" size=\"30\" style=\"text-align:right; color:#FFFFFF;\" oncontextmenu=\"return false\" onkeypress=\"check_num(event);\" onKeyUp=\"chkOverWhtHP(); calWhtHP(); calculate(); truemoney();\" readOnly></td>";}
			else{echo "<td align=center id=\"whtHP$numhp\"><input id=\"whtHPtxt$numhp\" name=\"whtHPtxt$numhp\" type=\"textbox\" value=\"$whtAmtHP\" size=\"12\" size=\"30\" style=\"text-align:right; color:#000000;\" oncontextmenu=\"return false\" onkeypress=\"check_num(event);\" onKeyUp=\"chkOverWhtHP(); calWhtHP(); calculate(); truemoney();\"></td>";}
			echo "</tr>";
			
			echo "<input type=\"hidden\" name=\"debtIDHP$numhp\" id=\"debtIDHP$numhp\" value=\"$debtID\" style=\"visibility:hidden\">";
			echo "<input type=\"hidden\" name=\"moneyHP$numhp\" id=\"moneyHP$numhp\" value=\"$typePayLeft\" style=\"visibility:hidden\">";
			echo "<input type=\"hidden\" name=\"CHKwhtHPtxt$numhp\" id=\"CHKwhtHPtxt$numhp\" value=\"$whtAmtHP\" style=\"visibility:hidden\">";
		}
		$arrayHP = $arrayHP."}";
	?>
</table>
<input type="hidden" name="rowHP" id="rowHP" value="<?php echo $numrowsHP; ?>">
<input type="hidden" name="sumMoneyLease" id="sumMoneyLease" value="<?php echo $sumMoney; ?>">