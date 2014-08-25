<script type="text/javascript">
	var rowHP; // จำนวนงวด
	var Countdebt; // จำนวนงวดทั้งหมดที่เหลือ
	var sumWhtHP; // รวมยอด wht
</script>

<?php
	$qry_hpDebt = pg_query("select sum(\"typePayLeft\") from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$ConID' and \"debtStatus\"='1' and \"debtIsOther\" = '0' and \"debtDueDate\" <= '$currentDate' ");
	$sumNowDebt = pg_fetch_result($qry_hpDebt,0);
	
	//-- หาจำนวนงวดทั้งหมดที่เหลืออยู่
	$qry_countdebt = pg_query("select * from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$ConID' and \"debtStatus\"='1' and \"debtIsOther\" = '0' ");
	$Countdebt = pg_num_rows($qry_countdebt);
?>

<div id="divfloatleft">
    <div class="maindivlabel2HP">
        <div class="divlabeltext">
            <span class="nonehilight"><input id="appent" name="appent" type="checkbox" onChange="chkAdd(); ChkLoadDataLease(); calculate();" <?php if($haveCancelPayment > 0){echo "disabled";} ?>><label>ชำระค่างวดเช่าซื้อ (THCAP) ::: ยอดค้างชำระปัจจุบัน <?php echo number_format($sumNowDebt,2); ?> บาท</label></span>
        </div>
    </div>
    <!--<div class="divchkboxcontrainer">
        <span><input id="appent" name="appent" type="checkbox" onchange="chkAdd()"><label>ชำระเงินกู้จำนองชั่วคราวด้วย(THCAP)</label></span>
    </div>-->
</div>
<div id="divtb2" class="maindiv12">
    <center>
    <div class="divtb1_both22HP">
        <!--<span id="tb1_chkbox1"><input type="checkbox" name="interestRatePost_Payment" id="interestRatePost_Payment" onChange="receivewhtchk_Payment()"><label>ภาษีหัก ณ ที่จ่าย</label></span>
        <span><font id="fontwht_Payment"> เลขที่อ้างอิง : </font><input type="text" name="whtDetail_Payment" id="whtDetail_Payment"></span>
        <span><font id="txtwhtmain"> จำนวนเงินภาษีหัก ณ ที่จ่าย : </font><input type="text" id="sum3" name="sum3" onKeyUp="javascript:chkOverWht();" style="text-align: right;"></span>
        <input type="hidden" id="CHKsum3">-->
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
					<th width="120" id="moneyth">ภาษีหัก ณ ที่จ่าย</th>
				</tr>
				<?php
					$qry_HP = pg_query("select * from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$ConID' and \"debtStatus\"='1' and \"debtIsOther\" = '0' and \"debtDueDate\" <= '$currentDate' order by \"typePayRefValue\" ");
					$numrowsHP = pg_num_rows($qry_HP);
					if($numrowsHP == 0)
					{
						$qry_HP = pg_query("select * from public.\"vthcap_otherpay_debt_current\" where \"contractID\"='$ConID' and \"debtStatus\"='1' and \"debtIsOther\" = '0' order by \"typePayRefValue\"::integer limit 1 ");
						$numrowsHP = pg_num_rows($qry_HP);
					}
					$arrayHP = "{"; // งวดที่จะชำระทั้งหมด
					$sumMoney = 0; // จำนวนค่างวดที่จะชำระรวม
					$sumWht = 0; // จำนวน wht รวม
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
						$resWhtAmtHP = pg_fetch_array($whtAmtfuncHP);
						$whtAmtHP = $resWhtAmtHP['thcap_checkdebtwht'];
						
						$sumWht += $whtAmtHP; // จำนวน wht รวม
						
						echo "<tr bgcolor=\"#DBF2FD\"style=\"color:#444; font-family:Arial, Helvetica, sans-serif; font-size:14px;\">";
						echo "<td align=\"center\">$typePayRefValue</td>";
						echo "<td align=\"center\">$debtDueDate</td>";
						echo "<td align=\"right\">".number_format($debtNet,2)."</td>";
						echo "<td align=\"right\">".number_format($debtVat,2)."</td>";
						echo "<td align=\"right\">".number_format($typePayLeft,2)."</td>";
						echo "<td align=center id=\"whtHP$numhp\"><input id=\"whtHPtxt$numhp\" name=\"whtHPtxt$numhp\" type=\"textbox\" value=\"$whtAmtHP\" size=\"12\" size=\"30\" style=\"text-align:right; color:#FFFFFF;\" oncontextmenu=\"return false\" onkeypress=\"check_num(event);\" onKeyUp=\"chkOverWhtHP(); calWhtHP(); calculate();\" readOnly></td>";
						echo "</tr>";
						
						echo "<input type=\"hidden\" name=\"debtIDHP$numhp\" id=\"debtIDHP$numhp\" value=\"$debtID\" style=\"visibility:hidden\">";
						echo "<input type=\"hidden\" name=\"moneyHP$numhp\" id=\"moneyHP$numhp\" value=\"$typePayLeft\" style=\"visibility:hidden\">";
						echo "<input type=\"hidden\" name=\"CHKwhtHPtxt$numhp\" id=\"CHKwhtHPtxt$numhp\" value=\"$whtAmtHP\" style=\"visibility:hidden\">";
					}
					$arrayHP = $arrayHP."}"; // งวดที่จะชำระทั้งหมด
				?>
			</table>
			<input type="hidden" name="rowHP" id="rowHP" value="<?php echo $numrowsHP; ?>">
			<input type="hidden" name="sumMoneyLease" id="sumMoneyLease" value="<?php echo $sumMoney; ?>">
		</div>
    </ul>
    <div class="divtb1_both22HP">
		<span id="tb1_chkbox1"><input type="checkbox" name="interestRatePost_Payment" id="interestRatePost_Payment" onChange="receivewhtchk_Lease(); truemoney();"><label>ภาษีหัก ณ ที่จ่าย</label></span>
        <span><font id="fontwht_Payment"> เลขที่อ้างอิง : </font><input type="text" name="whtDetail_Payment" id="whtDetail_Payment"></span>
        <span style="visibility:hidden"><font id="txtwhtmain"> จำนวนเงินภาษีหัก ณ ที่จ่าย : </font><input type="text" id="sum3old" name="sum3old" onKeyUp="javascript:chkOverWht(); truemoney();" style="text-align: right;"></span>
        <input type="hidden" id="CHKsum3">
		<input type="hidden" id="haveWht" value="no">
	</div>
	<div class="divtb1_both22HP">
        <span id="tb1_chkbox5"><input type="checkbox" name="receiveVice_Payment" id="receiveVice_Payment" value="1" onChange="receivevicechk_Payment()"><label>เป็นใบเสร็จออกแทน</label></span>
        <span id="tb1_chkbox_Payment">
            <select name="selectVice_Payment" id="selectVice_Payment">
            <?php 
                $sqlrein = pg_query("SELECT re_inname FROM thcap_receipt_instead_type"); 
                    while($reinre = pg_fetch_array($sqlrein)){ ?>
                        <option value="<?php echo $reinre['re_inname'] ?>"><?php echo  $reinre['re_inname'] ?></option>
                <?php	} ?>
            </select>
            &nbsp;<input type="text" name="viceDetail_Payment" id="viceDetail_Payment">
			&nbsp;| รวมค่างวดที่จะชำระ :<input type="textbox" id="t2" name="t2" style="text-align: right;background-Color:#CCCCCC;" value="0.00" readOnly="true" > | รวมภาษีหัก ณ ที่จ่ายค่างวด :<input type="textbox" id="sum3" name="sum3" style="text-align: right;background-Color:#CCCCCC;" value="<?php echo number_format($sumWht,2); ?>" readonly="true">
		</span>
    </div>
    <div class="divtb1_both22HP">
        <span id="tb1_chkbox5"><input type="checkbox" name="chkreasonappent" id="chkreasonappent" value="1" onChange="typereasonloan(id)" ><label>หมายเหตุ :</label></span>
        <span id="tb1_chkbox3"><input type="text" name="reasontextappent" id="reasontextappent" size="100"></span>
    </div>
		<!--<input type="text" id="t2" name="t2" onKeyUp="javascript:test();" onFocus="clearNum(id)" class="textbox_tb2" onBlur="checkNull(id)" value="0">
		<input type="text" id="t3" name="t3" class="textbox_tb2" onBlur="checkNull(id)" value="<?php echo $nowConIntCurRate; ?>" readonly style="background:#CCCCCC;">-->
		<input type="hidden" id="t3" name="t3" style="visibility:hidden"> 
		<input type="hidden" name="myVConType" id="myVConType" value="<?php echo $conType; ?>">
    </center>
</div>

<script type="text/javascript">
rowHP = document.getElementById("rowHP").value;
Countdebt = '<?php echo $Countdebt; ?>';

function addDataTableHP()
{
	if(parseInt(rowHP) < parseInt(Countdebt))
	{
		rowHP++;
		datalease();
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
		datalease();
		calculate();
	}
}

function datalease()
{
	var conIDHP;
	conIDHP = '<?php echo $ConID; ?>';
	
	var t2;
	
	var showDataHP = $.ajax({    // รับค่าจาก ajax เก็บไว้ที่ตัวแปร showDataHP  
		  url: "dataLease.php", // ไฟล์สำหรับการกำหนดเงื่อนไข  
		  data:"amount="+rowHP+"&conIDHP="+conIDHP+"&haveWht="+document.getElementById("haveWht").value, // ส่งตัวแปร GET ชื่อ amount ให้มีค่าเท่ากับ ค่าของ amount
		  async: false  
	}).responseText;
	$("#showDataHP").html(showDataHP); // นำค่า showDataHP มาแสดงใน div ที่ชื่อ showDataHP
	
	calMoneyHP();
	calWhtHP();
	calculate();
	
	//t2 = '<?php echo $sumMoney; ?>';
	t2 = document.getElementById("sumMoneyLease").value;
	document.getElementById("t2").value = parseFloat(t2).toFixed(2);
	
	calculate();
}

function calMoneyHP()
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
	document.getElementById("t2").value = parseFloat(sumMoneyHP).toFixed(2);
}

function calWhtHP()
{
	var tempWhtHP;
	sumWhtHP = 0;
	tempWhtHP = 0;
	for(var i = 1; i <= document.getElementById("rowHP").value; i++)
	{
		tempWhtHP = document.getElementById("whtHPtxt"+i).value;
		sumWhtHP = parseFloat(sumWhtHP) + parseFloat(tempWhtHP);
	}
	document.getElementById("sum3").value = parseFloat(sumWhtHP).toFixed(2);
	document.getElementById("CHKsum3").value = parseFloat(sumWhtHP).toFixed(2);
	document.getElementById("t3").value = parseFloat(sumWhtHP).toFixed(2);
	
	if(document.getElementById("interestRatePost_Payment").checked == false)
	{
		document.getElementById("sum3").value = 0.00;
		document.getElementById("CHKsum3").value = 0.00;
		document.getElementById("t3").value = 0.00;
	}
	
	calculate();
}

function receivewhtchk_Lease(){ // ภาษีหัก ณ ที่จ่าย ของค่าเช่า
	if(document.getElementById("interestRatePost_Payment").checked == true)
	{
		$("#whtDetail_Payment").show();
		$("#fontwht_Payment").show();
		$("#txtwhtmain").show();
		$("#sum3").show();
		document.getElementById("haveWht").value = 'yes';
		
		for(var i = 1; i <= document.getElementById("rowHP").value; i++)
		{
			document.getElementById("whtHPtxt"+i).readOnly = false;
			document.getElementById("whtHPtxt"+i).style.color = '#000000';
		}
		
		calWhtHP();
	}
	else
	{
		$("#whtDetail_Payment").hide();
		$("#fontwht_Payment").hide();
		$("#txtwhtmain").hide();
		$("#sum3").hide();
		document.getElementById("whtDetail_Payment").value = '';
		document.getElementById("sum3").value = 0.00;
		document.getElementById("CHKsum3").value = 0.00;
		document.getElementById("haveWht").value = 'no';
		for(var i = 1; i <= document.getElementById("rowHP").value; i++)
		{
			document.getElementById("whtHPtxt"+i).value = document.getElementById("CHKwhtHPtxt"+i).value;
			document.getElementById("whtHPtxt"+i).readOnly = true;
			document.getElementById("whtHPtxt"+i).style.color = '#FFFFFF';
		}
		chkOverWhtHP();
		calculate();
	}
}

function ChkLoadDataLease() // เช็คว่าจะให้โหลดข้อมูลใหม่หรือไม่
{
	if(document.getElementById("appent").checked == true)
	{
		datalease();
	}
	else
	{
		document.getElementById("sum3").value = 0.00;
		document.getElementById("CHKsum3").value = 0.00;
		
		receivewhtchk_Lease();
	}
}

function chkOverWhtHP()
{ // function สำหรับตวรจสอบว่า มีการเปลี่ยนจำนวนเงิน ภาษีหัก ณ ที่จ่าย ของ HP, FL, OL เกิน 5 บาท หรือไม่
	var rowHP = document.getElementById("rowHP").value;
	var deffWhtHP = 0;
	var statusOverWhtHP = 0;
	
	for(d=1;d<=rowHP;d++)
	{
		deffWhtHP = parseFloat($("#whtHPtxt"+d).val()) - parseFloat($("#CHKwhtHPtxt"+d).val());
		
		if(deffWhtHP >= -5.00 && deffWhtHP <= 5.00)
		{ // ถ้าไม่เกินค่าที่กำหนด
			document.getElementById("whtHPtxt"+d).style.backgroundColor = "#FFFFFF";
		}
		else
		{ // ถ้าเกินค่าที่กำหนด
			statusOverWhtHP++;
			document.getElementById("whtHPtxt"+d).style.backgroundColor = "#FF5555";
		}
	}
	
	document.getElementById("statusOverWhtHP").value = statusOverWhtHP;
}
</script>