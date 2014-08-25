<?php
include("../../config/config.php");
include("../function/nameMonth.php");
set_time_limit(120);

$nowDate = nowDate(); // วันปัจจุบัน

$arrayDate = explode("-",$nowDate);					
$plusDate = mktime(0,0,0,$arrayDate[1]-1,$arrayDate[2],$arrayDate[0]); // เวลา เดือน วัน ปี
$nowDate = date("Y-m-d",$plusDate); // วันที่จะครบกำหนดชำระ แบบ ปี-เดือน-วัน
			
$nowDay = substr($nowDate,8,2); // วันที่ปัจจุบัน
$nowMonth = substr($nowDate,5,2); // เดือนปัจจุบัน
$nowYear = substr($nowDate,0,4); // ปีปัจจุบัน

$click = pg_escape_string($_GET["click"]);
$selectType = pg_escape_string($_GET["selectType"]); // ประเภทการค้นหา

if($selectType == "selectDay")
{ // ถ้าเลือกแบบ ช่วง
	$Sdate = pg_escape_string($_GET["Sdate"]); // วันที่เริ่ม
	$Edate = pg_escape_string($_GET["Edate"]); // วันที่สิ้นสุด
}
elseif($selectType == "selectMonth")
{ // ถ้าเลือกแบบ รายเดือน
	$selectDay = pg_escape_string($_GET["Sdate"]); // วันที่เลือก
	$selectMonth = pg_escape_string($_GET["month2"]); // เดือนที่เลือก
	$selectYear = pg_escape_string($_GET["year2"]); // ปีที่เลือก
}
elseif($selectType == "selectYear")
{ // ถ้าเลือกแบบ รายปี
	$selectDay = pg_escape_string($_GET["Sdate"]); // วันที่เลือก
	$selectMonth = pg_escape_string($_GET["month2"]); // เดือนที่เลือก
	$selectYear = pg_escape_string($_GET["year3"]); // ปีที่เลือก
}

if($selectType == ""){$selectType = "selectYear";} // ประเภทการค้นหา Default ให้เป็นการเลือกแบบ วัน/เดือน/ปี
if($selectDay == ""){$selectDay = $nowDay;} // ถ้ายังไม่ได้เลือกวัน ให้ใช้วันที่ Default
if($selectMonth == ""){$selectMonth = $nowMonth;} // ถ้ายังไม่ได้เลือกเดือน ให้ใช้เดือน Default
if($selectYear == ""){$selectYear = $nowYear;} // ถ้ายังไม่ได้เลือกปี ให้ใช้ปี Default
if($Sdate == ""){$Sdate = substr($nowDate,0,10);}
if($Edate == ""){$Edate = substr($nowDate,0,10);}

$selectStyle = pg_escape_string($_GET["selectStyle"]); // รูปแบบการแสดง
if($selectStyle == ""){$selectStyle = "allStyle";} // ถ้ายังไม่ได้เลือก รูปแบบการแสดง ให้เลือก แสดงการตั้งหนี้ทั้งหมด

$selectCon = pg_escape_string($_GET["selectCon"]); // ประเภทการดูเลขที่สัญญา
$txtContract = pg_escape_string($_GET["txtContract"]); // เลขที่สัญญา
if($selectCon == ""){$selectCon = "allCon";} // ถ้ายังไม่ได้เลือก ให้เลือกแสดงทั้งหมด

//----------------------------------------------------------------------------------
if($selectCon == "someCon")
{
	$where_other = "and \"contractID\" = '$txtContract' ";
}
else
{
	$where_other = "";
}

if($selectStyle == "receiptStyle")
{
	$where_other .= "and \"isReceiveReal\" > '0' ";
}
elseif($selectStyle == "autoStyle")
{
	$where_other .= "and \"isReceiveReal\" = '0' ";
}

if($selectType == "selectDay")
{
	$qry_main = pg_query("select * from \"vthcap_interestGain\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and \"genDate\" >= '$Sdate'
						and \"genDate\" <= '$Edate'
						$where_other
						order by \"genDate\" ");
}
elseif($selectType == "selectMonth")
{
	$qry_main = pg_query("select * from \"vthcap_interestGain\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and substr(\"genDate\"::character varying,6,2) = '$selectMonth'
						and substr(\"genDate\"::character varying,1,4) = '$selectYear'
						$where_other
						order by \"genDate\" ");
}
elseif($selectType == "selectYear")
{
	$qry_main = pg_query("select * from \"vthcap_interestGain\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and substr(\"genDate\"::character varying,1,4) = '$selectYear'
						$where_other
						order by \"genDate\" ");
}

$row_main = pg_num_rows($qry_main); // จำนวนข้อมูล
?>
<table width="100%">
	<?php
		if($selectType == "selectYear" && $row_main > 0)
		{
	?>
			<tr>
				<td colspan="10" align="right">
					<input type="button" id="btnPrint3" value="พิมพ์ PDF" onclick="javascript:popU('print_pdf.php?type=year&year=<?php echo $selectYear; ?>&selectStyle=<?php echo $selectStyle ?>&whereContract=<?php echo $txtContract; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
					<input type="button" id="btnPrint33" value="พิมพ์ EXCEL" onclick="javascript:popU('print_excel.php?type=year&year=<?php echo $selectYear; ?>&selectStyle=<?php echo $selectStyle ?>&whereContract=<?php echo $txtContract; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
					<input type="button" id="btnPrint333" value="แสดงตามปีลูกหนี้" onclick="javascript:popU('frm_showgroup.php?type=year&year=<?php echo $selectYear; ?>&selectStyle=<?php echo $selectStyle ?>&whereContract=<?php echo $txtContract; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=800')" style="cursor:pointer;">
				</td>
			</tr>
	<?php
		}
		elseif($selectType == "selectMonth" && $row_main > 0)
		{
	?>
			<tr>
				<td colspan="10" align="right">
					<input type="button" id="btnPrint2" value="พิมพ์ PDF" onclick="javascript:popU('print_pdf.php?type=month&month=<?php echo $selectMonth; ?>&year=<?php echo $selectYear; ?>&selectStyle=<?php echo $selectStyle ?>&whereContract=<?php echo $txtContract; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
					<input type="button" id="btnPrint22" value="พิมพ์ EXCEL" onclick="javascript:popU('print_excel.php?type=month&month=<?php echo $selectMonth; ?>&year=<?php echo $selectYear; ?>&selectStyle=<?php echo $selectStyle ?>&whereContract=<?php echo $txtContract; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
					<input type="button" id="btnPrint222" value="แสดงตามปีลูกหนี้" onclick="javascript:popU('frm_showgroup.php?type=month&month=<?php echo $selectMonth; ?>&year=<?php echo $selectYear; ?>&selectStyle=<?php echo $selectStyle ?>&whereContract=<?php echo $txtContract; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=800')" style="cursor:pointer;">
				</td>
			</tr>
	<?php
		}
		elseif($selectType == "selectDay" && $row_main > 0)
		{
	?>
			<tr>
				<td colspan="10" align="right">
					<input type="button" id="btnPrint1" value="พิมพ์ PDF" onclick="javascript:popU('print_pdf.php?type=between&Sdate=<?php echo $Sdate; ?>&Edate=<?php echo $Edate; ?>&selectStyle=<?php echo $selectStyle ?>&whereContract=<?php echo $txtContract; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
									<input type="button" id="btnPrint11" value="พิมพ์ EXCEL" onclick="javascript:popU('print_excel.php?type=between&Sdate=<?php echo $Sdate; ?>&Edate=<?php echo $Edate; ?>&selectStyle=<?php echo $selectStyle ?>&whereContract=<?php echo $txtContract; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
									<input type="button" id="btnPrint111" value="แสดงตามปีลูกหนี้" onclick="javascript:popU('frm_showgroup.php?type=between&Sdate=<?php echo $Sdate; ?>&Edate=<?php echo $Edate; ?>&selectStyle=<?php echo $selectStyle ?>&whereContract=<?php echo $txtContract; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=800')" style="cursor:pointer;">
				</td>
			</tr>
	<?php
		}
	?>
	<tr align="center" bgcolor="#79BCFF">
		<th>วันที่ตั้งหนี้</th>
		<th>เลขที่สัญญา</th>
		<th>ชื่อผู้กู้หลัก</th>
		<th>เงินต้น</th>
		<th>อัตราดอกเบี้ย</th>
		<th>วันที่เริ่มคิด<br>ดอกเบี้ยรายการนี้</th>
		<th>วันที่สิ้นสุดการคิด<br>ดอกเบี้ยรายการนี้</th>
		<th>จำนวนวันที่คิด<br>ดอกเบี้ยเพิ่ม</th>
		<th>โดย</th>
		<th>จำนวนดอกเบี้ยที่ถูกตั้ง</th>
	</tr>
	<?php
		if($row_main > 0)
		{
			$i = 0;
			$allNewInterest = 0; // ยอดรวมทั้งหมด
			$sunNewInterestForMonth = 0; // ยอดรวมของแต่ละเดือน
			
			while($res = pg_fetch_array($qry_main))
			{
				$i++;
				$genDate = $res["genDate"]; // วันที่ตั้งหนี้
				$contractID = $res["contractID"]; // เลขที่สัญญา
				$MainCusName = $res["MainCusName"]; // ชื่อผู้กู้หลัก
				$lastPrinciple = $res["lastPrinciple"]; // เงินต้น
				$interestRate = $res["interestRate"]; // อัตราดอกเบี้ย
				$startIntDate = $res["startIntDate"]; // วันที่เริ่มคิดดอกเบี้ยรายการนี้
				$endIntDate = $res["endIntDate"]; //วันที่สิ้นสุดการคิดดอกเบี้ยรายการนี้
				$numIntDays = $res["numIntDays"]; // จำนวนวันที่คิดดอกเบี้ยเพิ่ม
				$isReceiveReal = $res["isReceiveReal"]; // ถ้า isReceiveReal > 0 คือ ด้วยใบเสร็จ = 0 คือด้วยระบบ
				$newInterest = $res["newInterest"]; // จำนวนดอกเบี้ยที่ถูกตั้ง
				
				$allNewInterest += $newInterest; // ยอดรวมทั้งหมด
				
				if($i == 1){$nowMonth = substr($genDate,5,2);}
				
				if($isReceiveReal == 0)
				{
					$txt_isReceiveReal = "สร้างอัตโนมัติโดยระบบ";
				}
				elseif($isReceiveReal > 0)
				{
					$txt_isReceiveReal = "ออกโดยใบเสร็จ";
				}
				else
				{
					$txt_isReceiveReal = "";
				}
				
				// ถ้าเลือกแบบ ปี ให้แสดงยอดรวมของแต่ละเดือนด้วย
				if($selectType == "selectYear" && $nowMonth != substr($genDate,5,2))
				{
					echo "<tr bgcolor=\"#BBBBFF\">";
					echo "<td align=\"right\" colspan=\"9\"><b>รวมของเดือน ".nameMonthTH($nowMonth)."</b></td>";
					echo "<td align=\"right\"><b>".number_format($sunNewInterestForMonth,2)."</b></td>";
					echo "</tr>";
					
					$sunNewInterestForMonth = 0;
				}
				
				$sunNewInterestForMonth += $newInterest; // ยอดรวมของแต่ละเดือน
				
				if($isReceiveReal == 0)
				{
					echo "<tr style=\"font-size:11px; background-color:#CCCCCC;\">";
				}
				else
				{
					if($i%2==0){
						echo "<tr class=\"odd\">";
					}else{
						echo "<tr class=\"even\">";
					}
				}
				
				echo "<td align=\"center\">$genDate</td>";
				echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>
						$contractID</u></span></td>";
				echo "<td align=\"left\">$MainCusName</td>";
				echo "<td align=\"right\">".number_format($lastPrinciple,2)."</td>";
				echo "<td align=\"right\">$interestRate</td>";
				echo "<td align=\"center\">$startIntDate</td>";
				echo "<td align=\"center\">$endIntDate</td>";
				echo "<td align=\"center\">$numIntDays</td>";
				echo "<td align=\"center\">$txt_isReceiveReal</td>";
				echo "<td align=\"right\">".number_format($newInterest,2)."</td>";
				echo "</tr>";
				
				$nowMonth = substr($genDate,5,2); // เดือนที่แสดงข้อมูล
			}
			
			if($selectType == "selectYear")
			{
				echo "<tr bgcolor=\"#BBBBFF\">";
				echo "<td align=\"right\" colspan=\"9\"><b>รวมของเดือน ".nameMonthTH($nowMonth)."</b></td>";
				echo "<td align=\"right\"><b>".number_format($sunNewInterestForMonth,2)."</b></td>";
				echo "</tr>";
				
				$sunNewInterestForMonth = 0;
			}
			
			echo "<tr bgcolor=\"#CCFFCC\">";
			echo "<td align=\"right\" colspan=\"9\"><b>ยอดรวมทั้งสิ้น</b></td>";
			echo "<td align=\"right\"><b>".number_format($allNewInterest,2)."</b></td>";
			echo "</tr>";
			
			echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"10\" align=\"center\">ข้อมูลทั้งหมดจำนวน $row_main รายการ *โดยข้อมูลที่แสดงจะแสดงเฉพาะรายการตั้งแต่ปี 2012 เป็นต้นไป</td></tr>";
		}
		else
		{
			echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"10\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
		}
	?>
</table>