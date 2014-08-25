<?php
require_once("../../config/config.php");
include("../function/nameMonth.php");

$tab_id = $_GET["tabid"];
$type = $_GET["type"]; // ประเภท
$Sdate = $_GET["Sdate"]; // วันที่เริ่ม
$Edate = $_GET["Edate"]; // วันที่สิ้นสุด
$month = $_GET["month"]; // เดือนที่เลือก
$year = $_GET["year"]; // ปีที่เลือก
$whereContract = $_GET["whereContract"]; // เลขที่สัญญา
$selectStyle = $_GET["selectStyle"]; // รูปแบบการแสดง

if($whereContract != ""){
	$where_other = "and a.\"contractID\" = '$whereContract' ";
}else{
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

echo "
<table frame=\"box\" width=\"100%\" align=\"center\" border=\"0\" cellSpacing=\"1\" cellPadding=\"1\" bgcolor=\"#EEEED1\">
	<tr align=\"center\" bgcolor=\"#79BCFF\">
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
";	
		
	if($tab_id==0){
		$allrows = 0; //จำนวนข้อมูลทั้งหมด
		$allInterest=0; //จำนวนรวมทั้งหมด
		//วนแสดงปีทั้งหมด
		if($type == "between")
		{
			$qry_year = pg_query("select distinct(b.\"contractYear\") from \"vthcap_interestGain\" a
						left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and \"genDate\" >= '$Sdate'
						and \"genDate\" <= '$Edate'
						$where_other
						order by b.\"contractYear\" ");
		}
		elseif($type == "month")
		{
			$qry_year = pg_query("select distinct(b.\"contractYear\") from \"vthcap_interestGain\" a
					left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
					where \"newInterest\" > '0'
					and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
					and substr(\"genDate\"::character varying,6,2) = '$month'
					and substr(\"genDate\"::character varying,1,4) = '$year'
					$where_other
					order by b.\"contractYear\" ");
		}
		elseif($type == "year")
		{
			$qry_year = pg_query("select distinct(b.\"contractYear\") from \"vthcap_interestGain\" a
						left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and substr(\"genDate\"::character varying,1,4) = '$year'
						$where_other
						order by b.\"contractYear\" ");
		}
				
		while($resyear=pg_fetch_array($qry_year)){
			list($contractyear)=$resyear;
			echo "<tr bgcolor=\"#FFCCCC\" align=\"center\" height=\"25\"><td colspan=10><b>-- ปี $contractyear --</b></td></tr>";
			$i = 0;
			
			//วนแสดงข้อมูลตามปีที่ได้
			if($type == "between")
			{
				$qry_main = pg_query("select * from \"vthcap_interestGain\" a
							left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
							where \"newInterest\" > '0'
							and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
							and \"genDate\" >= '$Sdate'
							and \"genDate\" <= '$Edate'
							and b.\"contractYear\"='$contractyear'
							$where_other
							order by \"genDate\" ");
			}
			elseif($type == "month")
			{
				$qry_main = pg_query("select * from \"vthcap_interestGain\" a
						left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and substr(\"genDate\"::character varying,6,2) = '$month'
						and substr(\"genDate\"::character varying,1,4) = '$year'
						and b.\"contractYear\"='$contractyear'
						$where_other
						order by \"genDate\"");
			}
			elseif($type == "year")
			{
				$qry_main = pg_query("select * from \"vthcap_interestGain\" a
							left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
							where \"newInterest\" > '0'
							and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
							and substr(\"genDate\"::character varying,1,4) = '$year'
							and b.\"contractYear\"='$contractyear'
							$where_other
							order by \"genDate\" ");
			}
			
			$row_main = pg_num_rows($qry_main);
			if($row_main > 0)
			{
				$i = 0;
				$allrows+=$row_main;
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
					$allInterest += $newInterest; // ยอดรวมทั้งหมด
					
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
					if($type == "year" && $nowMonth != substr($genDate,5,2))
					{
						echo "<tr bgcolor=\"#BBBBFF\">";
						echo "<td align=\"right\" colspan=\"7\"><b>รวมของเดือน ".nameMonthTH($nowMonth)." ของลูกหนี้ปี $contractyear</b></td>";
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
				
				if($type == "year")
				{
					echo "<tr bgcolor=\"#BBBBFF\">";
					echo "<td align=\"right\" colspan=\"9\"><b>รวมของเดือน ".nameMonthTH($nowMonth)." ของลูกหนี้ปี $contractyear</b></td>";
					echo "<td align=\"right\"><b>".number_format($sunNewInterestForMonth,2)."</b></td>";
					echo "</tr>";
					
					$sunNewInterestForMonth = 0;
				}
				
				echo "<tr bgcolor=\"#CCFFCC\">";
				echo "<td align=\"right\" colspan=\"9\"><b>ยอดรวมทั้งสิ้น ของลูกหนี้ปี $contractyear</b></td>";
				echo "<td align=\"right\"><b>".number_format($allNewInterest,2)."</b></td>";
				echo "</tr>";
				
			}
			else
			{
				echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"10\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
			}
		}
		echo "<tr bgcolor=\"#79BCFF\">";
		echo "<td align=\"right\" colspan=\"9\"><b>ยอดรวมทุกรายการ</b></td>";
		echo "<td align=\"right\"><b>".number_format($allInterest,2)."</b></td>";
		echo "</tr>";
		
		echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"10\" align=\"center\">ข้อมูลทั้งหมดจำนวน $allrows รายการ *โดยข้อมูลที่แสดงจะแสดงเฉพาะรายการตั้งแต่ปี 2012 เป็นต้นไป</td></tr>";
	}else{
		$i = 0;
			
		//วนแสดงข้อมูลตามปีที่ได้
		if($type == "between")
		{
			$qry_main = pg_query("select * from \"vthcap_interestGain\" a
						left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and \"genDate\" >= '$Sdate'
						and \"genDate\" <= '$Edate'
						and b.\"contractYear\"='$tab_id'
						$where_other
						order by \"genDate\" ");
		}
		elseif($type == "month")
		{
			$qry_main = pg_query("select * from \"vthcap_interestGain\" a
					left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
					where \"newInterest\" > '0'
					and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
					and substr(\"genDate\"::character varying,6,2) = '$month'
					and substr(\"genDate\"::character varying,1,4) = '$year'
					and b.\"contractYear\"='$tab_id'
					$where_other
					order by \"genDate\"");
		}
		elseif($type == "year")
		{
			$qry_main = pg_query("select * from \"vthcap_interestGain\" a
						left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
						where \"newInterest\" > '0'
						and substr(\"genDate\"::character varying,1,4)::integer >= '2012'
						and substr(\"genDate\"::character varying,1,4) = '$year'
						and b.\"contractYear\"='$tab_id'
						$where_other
						order by \"genDate\" ");
		}
		
		$row_main = pg_num_rows($qry_main);
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
				if($type == "year" && $nowMonth != substr($genDate,5,2))
				{
					echo "<tr bgcolor=\"#BBBBFF\">";
					echo "<td align=\"right\" colspan=\"7\"><b>รวมของเดือน ".nameMonthTH($nowMonth)."</b></td>";
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
			
			if($type == "year")
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
	}
echo "</table>";
?>