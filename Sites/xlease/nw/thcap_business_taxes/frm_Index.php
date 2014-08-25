<?php
include("../../config/config.php");

/*
------------------------------------------------------------------------------------------
(THCAP) รายงานภาษีธุรกิจเฉพาะ
------------------------------------------------------------------------------------------
	สำหรับแสดงข้อมูลภาษีธุรกิจเฉพาะ ที่เกิดขึ้นใน เดือน ปี หรือ ในรอบปี ต่างๆ ตามที่ผู้ใช้งานเลือก
	
	1. รูปแบบการทำงาน
		Query ข้อมูล Real time มาแสดงผล โดยปัจจุบันแยกเป็น 2 ส่วนคือ
			1.1 ข้อมูลใบเสร็จค่าอื่นๆ ตามระบบตั้งหนี้ otherpay_debt
			1.2 ข้อมูลใบเสร็จค่าดอกเบี้ย ของเงินกู้
			1.3 ข้อมูลการรับชำระของ แฟคตอริ่ง ที่เป็นส่วนของค่าธรรมเนียม + ดอกเบี้ย
*/

$nowDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server // วันเวลาปัจจุบัน
$nowMonth = substr($nowDate,5,2); // เดือนปัจจุบัน
$nowYear = substr($nowDate,0,4); // ปีปัจจุบัน

$click = pg_escape_string($_POST["click"]);

IF($click == 'yes')
{
	$checkoption = pg_escape_string($_POST["op1"]);
	$selectSort = pg_escape_string($_POST["sort"]); // การเรียงข้อมูลที่เลือก
	
	IF($checkoption == 'my')
	{
		$checked1 = "checked";
		$selectMonth = pg_escape_string($_POST["month"]); // เดือนที่เลือก
		$selectYear = pg_escape_string($_POST["year"]); // ปีที่เลือก
		// ให้หาเฉพาะใบเสร็จเฉพาะตามเดือน และ ปี ที่ผู้ใช้งานเลือก
		$whereOther = " and EXTRACT(MONTH FROM \"thcap_receiptIDToReceiveDate\"(\"receiptID\")) = '$selectMonth' and EXTRACT(YEAR FROM \"thcap_receiptIDToReceiveDate\"(\"receiptID\")) = '$selectYear'";
	
	}
	else if($checkoption == 'y')
	{
		$checked2 = "checked";
		$selectYear = pg_escape_string($_POST["year"]); // ปีที่เลือก
		// ให้หาเฉพาะใบเสร็จเฉพาะ ปี ที่ผู้ใช้งานเลือก
		$whereOther = " and EXTRACT(YEAR FROM \"thcap_receiptIDToReceiveDate\"(\"receiptID\")) = '$selectYear'";
	}
	
	if($selectSort == "s1")
	{
		$mySort = "order by \"contractID\"";
	}
	elseif($selectSort == "s2")
	{
		$mySort = "order by \"receiptID\"";
	}
	elseif($selectSort == "s3")
	{
		$mySort = "order by \"receiveDate\"";
	}
	elseif($selectSort == "s4")
	{
		$mySort = "order by \"typePayID\"";
	}
	elseif($selectSort == "s5")
	{
		$mySort = "order by \"tpDesc\"";
	}
}
else
{
	if($selectMonth == ""){$selectMonth = $nowMonth;} // ถ้ายังไม่ได้เลือกเดือน ให้ใช้เดือนปัจจุบัน
	if($selectYear == ""){$selectYear = $nowYear;} // ถ้ายังไม่ได้เลือกปี ให้ใช้ปีปัจจุบัน
	$checked1 = "checked";
	// ให้หาเฉพาะใบเสร็จเฉพาะตามเดือน และ ปี ที่ผู้ใช้งานเลือก
	$whereOther = " and EXTRACT(MONTH FROM \"thcap_receiptIDToReceiveDate\"(\"receiptID\")) = '$selectMonth' and EXTRACT(YEAR FROM \"thcap_receiptIDToReceiveDate\"(\"receiptID\")) = '$selectYear'";

	$selectSort = "s1";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานภาษีธุรกิจเฉพาะ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			<?php if($checkoption == 'y'){ ?>
					$("#d1").hide();
					$("#d2").show();		
			<?php }else{ ?>
					$("#d1").show();
					$("#d2").show();
			<?php } ?>		
		});
		
		function popU(U,N,T){
			newWindow = window.open(U, N, T);
		}
		function option(){
			if(document.getElementById("op1").checked == true){	
				$("#d1").show();
				$("#d2").show();
			}else if(document.getElementById("op2").checked == true){	
				$("#d1").hide();
				$("#d2").show();		
			}
		}
	</script>
	
</head>
<body>

<center>
<h2>(THCAP) รายงานภาษีธุรกิจเฉพาะ</h2>
</center>

<form name="frm1" method="post" action="frm_Index.php">
<table width="950" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
			<fieldset><legend><B>เลือกช่วงเวลา</B></legend>
					<table  width="100%" >
						<tr>
							<td align="center" height="50">
								<input type="radio" id="op1" name="op1" value="my" <?php echo "$checked1";  ?> onchange="option();">แสดงเฉพาะเดือน-ปี						
								<input type="radio" id="op2" name="op1" value="y" <?php echo "$checked2"; ?>  onchange="option();">แสดงเฉพาะปี
							</td>
						</tr>
						<tr align="center" >						
							<td>
								<span  id="d1" >
									เดือน :
									<select name="month">
										<?php
											for($m=1; $m<=12; $m++)
											{
												if(strlen($m) == 1){$m = "0".$m;}
												if($m == $selectMonth)
												{
													echo "<option value=\"$m\" selected>$m</option>";
												}
												else
												{
													echo "<option value=\"$m\">$m</option>";
												}
											}
										?>
									</select>
									&nbsp;&nbsp;&nbsp;
								</span>
								<span  id="d2" >
									ปี :
									<select name="year">
										<?php
											for($y=$nowYear; $y>=2013; $y--)
											{
												if($y == $selectYear)
												{
													echo "<option value=\"$y\" selected>$y</option>";
												}
												else
												{
													echo "<option value=\"$y\">$y</option>";
												}
											}
										?>
									</select>
								</span>
							</td>					
						</tr>
						<tr>
							<td align="center">
								<b>เรียงข้อมูลตาม : </b>
								<input type="radio" name="sort" value="s1" <?php if($selectSort == "s1"){echo "checked";} ?>>เลขที่สัญญา					
								<input type="radio" name="sort" value="s2" <?php if($selectSort == "s2"){echo "checked";} ?>>เลขที่ใบเสร็จ
								<input type="radio" name="sort" value="s3" <?php if($selectSort == "s3"){echo "checked";} ?>>วันที่จ่าย					
								<input type="radio" name="sort" value="s4" <?php if($selectSort == "s4"){echo "checked";} ?>>รหัสค่าใช้จ่าย
								<input type="radio" name="sort" value="s5" <?php if($selectSort == "s5"){echo "checked";} ?>>ประเภทค่าใช้จ่าย
							</td>
						</tr>
						<tr>
							<td align="center">
								<div style="padding-top:10px;"></div>
								<input type="hidden" name="click" value="yes">
								<input type="submit" value="ค้นหา" style="width:70px;height:30px;">		
							</td>
						</tr>
					</table>
			</fieldset>
			
			<?php
				if($click == "yes")
				{
			?>
					<table width="100%">
						<tr>
							<td align="right" colspan="9">
								<input type="button" id="btnprint" value="พิมพ์ PDF" onclick="javascript:popU('frm_pdf.php?op1=<?php echo "$checkoption"; ?>&month=<?php echo "$selectMonth"; ?>&year=<?php echo $selectYear; ?>&sort=<?php echo $selectSort; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
								<input type="button" id="btnprintex" value="พิมพ์ EXCEL" onclick="javascript:popU('frm_excel.php?op1=<?php echo "$checkoption"; ?>&month=<?php echo "$selectMonth"; ?>&year=<?php echo $selectYear; ?>&sort=<?php echo $selectSort; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
							</td>
						</tr>
						<tr align="center" bgcolor="#79BCFF">
							<th>เลขที่สัญญา</th>
							<th>เลขที่ใบเสร็จ</th>
							<th>วันที่จ่าย</th>
							<th>รหัสค่าใช้จ่าย</th>
							<th>ประเภทค่าใช้จ่าย</th>
							<th>จำนวนเงิน</th>
							<th>อัตราภาษีธุรกิจเฉพาะ</th>
							<th bgcolor="#FFCCCC">จำนวนภาษีธุรกิจเฉพาะ</th>
							<th bgcolor="#FFCCCC">จำนวนภาษีโรงเรือน</th>
						</tr>
						<?php
							$qry_main = pg_query("
							
								-- หาข้อมูลใบเสร็จของค่าอื่นๆ otherpay_debt
								select \"thcap_receiptIDToContractID\"(a.\"receiptID\") as \"contractID\", a.\"typePayRefValue\", a.\"receiptID\", \"thcap_receiptIDToReceiveDate\"(a.\"receiptID\") as \"receiveDate\",
									a.\"debtID\", a.\"typePayID\", a.\"tpDesc\", a.\"netAmt\", b.\"curSBTRate\", 
									(a.\"netAmt\"*b.\"curSBTRate\"/100)::numeric(15,2) as \"businessTaxes\", ((a.\"netAmt\"*b.\"curSBTRate\"/100)::numeric(15,2)*0.1)::numeric(15,2) as \"localTaxes\"
								from thcap_temp_receipt_otherpay a
								left join account.\"thcap_typePay\" b on a.\"typePayID\" = b.\"tpID\"
								left join thcap_temp_otherpay_debt c on a.\"debtID\" = c.\"debtID\"
								where b.\"curSBTRate\" is not null $whereOther
			
								union

								-- หาข้อมูลใบเสร็จของส่วนที่รับชำระดอกเบี้ยเงินกู้
								select d.\"contractID\", d.\"contractID\" as \"typePayRefValue\", d.\"receiptID\", d.\"receiveDate\",
									null as \"debtID\", e.\"tpID\" as \"typePayID\", e.\"tpDesc\", d.\"receiveInterest\" as \"netAmt\", e.\"curSBTRate\", 
									(d.\"receiveInterest\"*e.\"curSBTRate\"/100)::numeric(15,2) as \"businessTaxes\", ((d.\"receiveInterest\"*e.\"curSBTRate\"/100)::numeric(15,2)*0.1)::numeric(15,2) as \"localTaxes\"
								from thcap_temp_int_201201 d
								left join account.\"thcap_typePay\" e on e.\"tpID\" = account.\"thcap_mg_getInterestType\"(d.\"contractID\")
								where e.\"curSBTRate\" is not null and d.\"isReceiveReal\" = '1' and d.\"receiptID\" is not null and d.\"receiveInterest\" > 0.00 $whereOther
								
								union

								select f.\"contractID\", f.\"typePayRefValue\", f.\"receiptID\", f.\"receiveDate\", f.\"debtID\", f.\"typePayID\", f.\"tpDesc\", f.\"netAmt\", f.\"curSBTRate\", f.\"businessTaxes\", f.\"localTaxes\"
								from \"v_thcap_receive_factoring_facfee\" f
								where f.\"curSBTRate\" is not null $whereOther

								union

								select g.\"contractID\", g.\"typePayRefValue\", g.\"receiptID\", g.\"receiveDate\", g.\"debtID\", g.\"typePayID\", g.\"tpDesc\", g.\"netAmt\", g.\"curSBTRate\", g.\"businessTaxes\", g.\"localTaxes\"
								from \"v_thcap_receive_factoring_interest\" g
								where g.\"curSBTRate\" is not null $whereOther
								
								$mySort
							");
							$row_main = pg_num_rows($qry_main);
							if($row_main > 0)
							{
								$i = 0;
								$sumNetAmt = 0; // จำนวนเงินที่รับชำระ รวม
								$sumBusinessTaxes = 0; // เงินต้นรับชำระ รวม
								
								
								while($res = pg_fetch_array($qry_main))
								{
									$i++;
									$contractID = $res["contractID"]; // เลขที่สัญญา
									$receiptID = $res["receiptID"]; // เลขที่ใบเสร็จ
									$receiveDate = $res["receiveDate"]; // วันที่รับชำระ
									$typePayRefValue = $res["typePayRefValue"]; // เลขที่อ้างอิง
									$typePayID = $res["typePayID"]; // รหัสประเภทค่าใช้จ่าย
									$tpDesc = $res["tpDesc"]; // ชื่อประเภทค่าใช้จ่าย
									$netAmt = $res["netAmt"]; // จำนวนเงิน net
									$curSBTRate = $res["curSBTRate"]; // อัตราภาษีธุรกิจเฉพาะ
									$businessTaxes = $res["businessTaxes"]; // จำนวนภาษีธุรกิจเฉพาะ
									$localTaxes = $res["localTaxes"]; // จำนวนภาษีท้องถิ่น
									
									$sumNetAmt += $netAmt;
									$sumBusinessTaxes += $businessTaxes;
									$sumLocalTaxes += $localTaxes;
									
									if($contractID != "")
									{
										$contractIDtext = $contractID;
									}
									else
									{
										$contractIDtext = $typePayRefValue;
									}
									
									if($i%2==0){
										echo "<tr class=\"odd\">";
									}else{
										echo "<tr class=\"even\">";
									}
									
									echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractIDtext','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractIDtext</u></font></span></td>";
									echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$receiptID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=850,height=450')\" style=\"cursor: pointer;\"><font color=\"#0000FF\"><u>$receiptID</u></font></span></td>";
									echo "<td align=\"center\">$receiveDate</td>";
									echo "<td align=\"center\">$typePayID</td>";
									echo "<td align=\"left\">$tpDesc</td>";
									echo "<td align=\"right\">".number_format($netAmt,2)."</td>";
									echo "<td align=\"right\">".number_format($curSBTRate,2)."</td>";
									echo "<td align=\"right\" bgcolor=\"#FFE4E1\">".number_format($businessTaxes,2)."</td>";
									echo "<td align=\"right\" bgcolor=\"#FFE4E1\">".number_format($localTaxes,2)."</td>";
									echo "</tr>";
								}
								echo "<tr bgcolor=\"#FFBBBB\">";
								echo "<td align=\"left\" colspan=\"4\">จำนวน $i รายการ</td>";
								echo "<td align=\"right\"><b>รวม</b></td>";
								echo "<td align=\"right\">".number_format($sumNetAmt,2)."</td>";
								echo "<td></td>";
								echo "<td align=\"right\">".number_format($sumBusinessTaxes,2)."</td>";
								echo "<td align=\"right\">".number_format($sumLocalTaxes,2)."</td>";
								echo "</tr>";
							}
							else
							{
								echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"8\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
							}
						?>
					</table>
			<?php
				}
			?>
		</td>
	</tr>
</table>
</form>

</body>

<script type="text/javascript">
$(document).ready(function(){
	var strRows = '<?php echo $row_main; ?>';
	if(parseInt(strRows) > 0){ // ถ้ามีข้อมูลที่ค้นหา ให้แสดงปุ่มพิมพ์
		document.getElementById("btnprint").style.visibility = 'visible';
		document.getElementById("btnprintex").style.visibility = 'visible';
	}
	else{ // ถ้าไม่มีข้อมูลที่ค้นหา ให้ซ้อนปุ่มพิมพ์
		document.getElementById("btnprint").style.visibility = 'hidden';
		document.getElementById("btnprintex").style.visibility = 'hidden';
	}
});
</script>

</html>