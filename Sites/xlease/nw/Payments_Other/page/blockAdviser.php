<script type="text/javascript">
	var rowHP; // จำนวนงวด
	var Countdebt; // จำนวนงวดทั้งหมดที่เหลือ
</script>

<?php
	$qry_hpDebt = pg_query("select sum(\"typePayLeft\") from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$ConID' and \"debtStatus\"='1' and \"debtIsOther\" = '0' and \"debtDueDate\" <= '$currentDate' and \"debtDueDate\" < '2014-02-01' ");
	$sumNowDebt = pg_fetch_result($qry_hpDebt,0);
	
	//-- หาจำนวนงวดทั้งหมดที่เหลืออยู่
	$qry_countdebt = pg_query("select * from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$ConID' and \"debtStatus\"='1' and \"debtIsOther\" = '0' and \"debtDueDate\" < '2014-02-01' ");
	$Countdebt = pg_num_rows($qry_countdebt);
?>

<div id="divfloatleft">
    <div class="maindivlabel2JV">
        <div class="divlabeltext">
            <span class="nonehilight"><input id="payAdviser" name="payAdviser" type="checkbox" onChange="chkAdviser(); ChkLoadDataLease(); calculate();" <?php if($haveCancelPayment > 0){echo "disabled";} ?>><label>ชำระค่าที่ปรึกษา (THCAP) ::: ยอดค้างชำระปัจจุบัน <?php echo number_format($sumNowDebt,2); ?> บาท</label></span>
        </div>
    </div>
</div>
<div id="divtb3" class="maindiv12">
    <center>
    <div class="divtb1_both22HP">
	
    </div><br>
    <ul id="ul_tb2">
		<input type="button" value="+ เพิ่ม" onclick="addDataTableHP(); calculate(); truemoney();"> <input type="button" value="- ลบ" onclick="deleteDataTableHP(); calculate(); truemoney();">
		<div id="showDataHP">
			<table align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF" id="tb1">
				<tr bgcolor="#4399c3" style="color:#FFFFFF; font-family:Arial, Helvetica, sans-serif; font-size:14px;" height="25">
					<th width="80">งวดที่</th>
					<th width="150">วันที่ครบกำหนดชำระ</th>
					<th width="150">จำนวนเงินก่อน VAT</th>
					<th width="150">จำนวน VAT</th>
					<th width="180">จำนวนเงินรวม</th>
				</tr>
				<?php
					$qry_HP = pg_query("select * from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$ConID' and \"debtStatus\"='1' and \"debtIsOther\" = '0' and \"debtDueDate\" <= '$dateContact' and \"debtDueDate\" < '2014-02-01' order by \"typePayRefValue\"::integer ");
					$numrowsHP = pg_num_rows($qry_HP);
					if($numrowsHP == 0)
					{
						$qry_HP = pg_query("select * from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$ConID' and \"debtStatus\"='1' and \"debtIsOther\" = '0' and \"debtDueDate\" < '2014-02-01' order by \"typePayRefValue\"::integer limit 1 ");
						$numrowsHP = pg_num_rows($qry_HP);
					}
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
						
						echo "<tr bgcolor=\"#DBF2FD\"style=\"color:#444; font-family:Arial, Helvetica, sans-serif; font-size:14px;\">";
						echo "<td align=\"center\">$typePayRefValue</td>";
						echo "<td align=\"center\">$debtDueDate</td>";
						echo "<td align=\"right\">".number_format($debtNet,2)."</td>";
						echo "<td align=\"right\">".number_format($debtVat,2)."</td>";
						echo "<td align=\"right\">".number_format($typePayLeft,2)."</td>";
						echo "</tr>";
						
						echo "<input type=\"hidden\" name=\"debtIDHP$numhp\" id=\"debtIDHP$numhp\" value=\"$debtID\" style=\"visibility:hidden\">";
						echo "<input type=\"hidden\" name=\"moneyHP$numhp\" id=\"moneyHP$numhp\" value=\"$typePayLeft\" style=\"visibility:hidden\">";
					}
					$arrayHP = $arrayHP."}"; // งวดที่จะชำระทั้งหมด
				?>
			</table>
			<input type="hidden" name="rowHP" id="rowHP" value="<?php echo $numrowsHP; ?>">
			<input type="hidden" name="sumMoneyLease" id="sumMoneyLease" value="<?php echo $sumMoney; ?>">
		</div>
    </ul>
    <div class="divtb1_both22HP">
        <span id="tb1_chkbox_Payment">
			รวมค่าที่ปรึกษาจะชำระ :<input type="textbox" id="sumPayAdviser" name="sumPayAdviser" style="text-align: right;background-Color:#CCCCCC;" value="0.00" readOnly="true" >
		</span>
    </div>
		<input type="hidden" id="at3" name="at3" style="visibility:hidden"> 
		<input type="hidden" name="myVConType" id="myVConType" value="<?php echo $conType; ?>">
    </center>
</div>
<br><br><br>

<script type="text/javascript">
rowHP = document.getElementById("rowHP").value;
Countdebt = '<?php echo $Countdebt; ?>';

function addDataTableHP()
{
	if(parseInt(rowHP) < parseInt(Countdebt))
	{
		rowHP++;
		dataJV();
		calculate();
	}
}

function deleteDataTableHP()
{
	rowHP--;
	if(rowHP < 1)
	{
		rowHP = 1;
	}
	else
	{
		dataJV();
		calculate();
	}
}

function dataJV()
{
	var conIDHP;
	conIDHP = '<?php echo $ConID; ?>';
	
	var payDate; // วันที่จ่าย
	payDate = '<?php echo $dateContact; ?>';
	
	var sumPayAdviser;
	
	var showDataHP = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร showDataHP  
		  url: "dataJV.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  data:"amount="+rowHP+"&conIDHP="+conIDHP+"&payDate="+payDate, // ส่งตัวแปร GET ชื่อ amount ให้มีค่าเท่ากับ ค่าของ amount
		  async: false  
	}).responseText;
	$("#showDataHP").html(showDataHP); // นำค่า showDataHP มาแสดงใน div ที่ชื่อ showDataHP
	
	calMoneyJV();
	calculate();
	
	sumPayAdviser = document.getElementById("sumMoneyLease").value;
	document.getElementById("sumPayAdviser").value = parseFloat(sumPayAdviser).toFixed(2);
	
	calculate();
}

function calMoneyJV()
{
	var sumMoneyHP;
	var tempHP;
	sumMoneyHP = 0;
	tempHP = 0;
	for(var i = 1; i <= document.getElementById("rowHP").value; i++)
	{
		tempHP = document.getElementById("moneyHP"+i).value;
		sumMoneyHP = parseFloat(sumMoneyHP) + parseFloat(tempHP);
	}
	document.getElementById("sumPayAdviser").value = parseFloat(sumMoneyHP).toFixed(2);
}

function ChkLoadDataLease() // เช็คว่าจะให้โหลดข้อมูลใหม่หรือไม่
{
	if(document.getElementById("payAdviser").checked == true)
	{
		dataJV();
	}
	else
	{
		document.getElementById("sum3").value = 0.00;
	}
}
</script>