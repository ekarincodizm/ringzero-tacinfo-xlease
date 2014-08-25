<?php
require_once("../../config/config.php");

$tab_id = $_GET["tabid"];
$selectMonth = $_GET["month"]; // เดือนที่เลือก
$selectYear = $_GET["year"]; // ปีที่เลือก

echo "
<table frame=\"box\" width=\"100%\" align=\"center\" border=\"0\" cellSpacing=\"1\" cellPadding=\"1\" bgcolor=\"#EEEED1\">
	<tr align=\"center\" bgcolor=\"#79BCFF\">
		<th>เลขที่สัญญา</th>
		<th>ประเภทสินเชื่อ</th>
		<th>ชื่อนามสกุลผู้กู้หลัก</th>
		<th>ยอดดอกเบี้ยที่เกิดขึ้นทั้งหมด</th>
	</tr>
";	
		
	if($tab_id==0){	
		$allsumInterest = 0; // ดอกเบี้ยรวมทุกประเภท
		$qry_year=pg_query("SELECT distinct(b.\"contractYear\") FROM thcap_temp_int_201201 a 
		left join thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
		where substr(a.\"receiveDate\"::character varying,'1','4')::integer = '$selectYear'
		and substr(a.\"receiveDate\"::character varying,'6','2')::integer = '$selectMonth'
		and \"thcap_getInterestGainOverMonth\"(a.\"contractID\", '$selectYear', '$selectMonth') > '0.00'
		ORDER BY b.\"contractYear\"");
		while($resyear=pg_fetch_array($qry_year)){
			list($contractyear)=$resyear;
			
			echo "<tr bgcolor=\"#FFCCCC\" align=\"center\" height=\"30\"><td colspan=7><b>-- ปี $contractyear --</b></td></tr>";
			$qry_main=pg_query("SELECT distinct a.\"contractID\",
								(select b.\"conType\" from \"thcap_contract\" b where b.\"contractID\" = a.\"contractID\") as \"conType\",
								\"thcap_getInterestGainOverMonth\"(a.\"contractID\", '$selectYear', '$selectMonth') as \"newInterest\"
								FROM \"thcap_temp_int_201201\" a
								LEFT JOIN thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
								where substr(a.\"receiveDate\"::character varying,'1','4')::integer = '$selectYear'
								and substr(a.\"receiveDate\"::character varying,'6','2')::integer = '$selectMonth'
								and \"thcap_getInterestGainOverMonth\"(a.\"contractID\", '$selectYear', '$selectMonth') > '0.00'and b.\"contractYear\"='$contractyear' order by \"conType\", \"contractID\" ");
			$row_main = pg_num_rows($qry_main);
			if($row_main > 0)
			{
				$i = 0;
				$sumInterest = 0; // ยอดรวมของดอกเบี้ยแต่ละประเภท
				$allInterest = 0; // ดอกเบี้ยรวมทุกประเภท
				while($res = pg_fetch_array($qry_main))
				{
					$i++;
					$contractID = $res["contractID"]; // เลขที่สัญญา
					$conType = $res["conType"]; // ประเภทสินเชื่อ
					$newInterest = $res["newInterest"]; // ยอดดอกเบี้ยที่เกิดขึ้นทั้งหมด ของเดือนและปีที่เลือก
					
					if($i == 1){$spitConType = $conType;}
					
					//ค้นหาชื่อผู้กู้หลัก
					$qry_namemain = pg_query("select * from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" ='0'");
					if($resnamemain = pg_fetch_array($qry_namemain)){
						$name3 = trim($resnamemain["thcap_fullname"]);
					}
					
					if($spitConType != $conType)
					{
						echo "<tr bgcolor=\"#CCCCFF\" style=\"font-size:11px;\">";
						echo "<td colspan=\"3\" align=\"right\"><b>ดอกเบี้ยที่เกิดขึ้นรวมของประเภทสินเชื่อ $spitConType ของลูกหนี้ปี $contractyear</b></td>";
						echo "<td align=\"right\"><b>".number_format($sumInterest,2)."</b></td>";
						echo "</tr>";
						$sumInterest = 0;
						$spitConType = $conType;
					}
					
					if($i%2==0){
						echo "<tr class=\"odd\">";
					}else{
						echo "<tr class=\"even\">";
					}
					
					echo "<td align=\"center\">$contractID</td>";
					echo "<td align=\"center\">$conType</td>";
					echo "<td align=\"left\">$name3</td>";
					echo "<td align=\"right\">".number_format($newInterest,2)."</td>";
					echo "</tr>";
					
					$sumInterest += $newInterest;
					$allInterest += $newInterest;
					$allsumInterest += $newInterest;
				}
				
				// ดอกเบี้ยรวม ของประเภทสินเชื่อสุดท้าย
				echo "<tr bgcolor=\"CCCCFF\" style=\"font-size:11px;\">";
				echo "<td colspan=\"3\" align=\"right\"><b>ดอกเบี้ยที่เกิดขึ้นรวมของประเภทสินเชื่อ $spitConType ของลูกหนี้ปี $contractyear</b></td>";
				echo "<td align=\"right\"><b>".number_format($sumInterest,2)."</b></td>";
				echo "</tr>";
				
				// ดอกเบี้ยรวมทุกประเภท
				echo "<tr bgcolor=\"#CD96CD\" style=\"font-size:13px;\">";
				echo "<td colspan=\"3\" align=\"right\"><b>รวมดอกเบี้ยที่เกิดขึ้นทุกประเภท ของลูกหนี้ปี $contractyear</b></td>";
				echo "<td align=\"right\"><b>".number_format($allInterest,2)."</b></td>";
				echo "</tr>";
			}
			else
			{
				echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"4\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
			}
		}
		// ดอกเบี้ยรวมทั้งหมด
		echo "<tr bgcolor=\"#FFCCCC\" style=\"font-size:13px;\">";
		echo "<td colspan=\"3\" align=\"right\"><b>รวมดอกเบี้ยที่เกิดขึ้นทั้งหมด</b></td>";
		echo "<td align=\"right\"><b>".number_format($allsumInterest,2)."</b></td>";
		echo "</tr>";
	}else{
		$qry_main=pg_query("SELECT distinct a.\"contractID\",
								(select b.\"conType\" from \"thcap_contract\" b where b.\"contractID\" = a.\"contractID\") as \"conType\",
								\"thcap_getInterestGainOverMonth\"(a.\"contractID\", '$selectYear', '$selectMonth') as \"newInterest\"
								FROM \"thcap_temp_int_201201\" a
								LEFT JOIN thcap_mg_contract b on a.\"contractID\" = b.\"contractID\"
								where substr(a.\"receiveDate\"::character varying,'1','4')::integer = '$selectYear'
								and substr(a.\"receiveDate\"::character varying,'6','2')::integer = '$selectMonth'
								and \"thcap_getInterestGainOverMonth\"(a.\"contractID\", '$selectYear', '$selectMonth') > '0.00' and b.\"contractYear\"='$tab_id' order by \"conType\", \"contractID\" ");
		$row_main = pg_num_rows($qry_main);
		if($row_main > 0)
		{
			$i = 0;
			$sumInterest = 0; // ยอดรวมของดอกเบี้ยแต่ละประเภท
			$allInterest = 0; // ดอกเบี้ยรวมทุกประเภท
			
			while($res = pg_fetch_array($qry_main))
			{
				$i++;
				$contractID = $res["contractID"]; // เลขที่สัญญา
				$conType = $res["conType"]; // ประเภทสินเชื่อ
				$newInterest = $res["newInterest"]; // ยอดดอกเบี้ยที่เกิดขึ้นทั้งหมด ของเดือนและปีที่เลือก
				
				if($i == 1){$spitConType = $conType;}
				
				//ค้นหาชื่อผู้กู้หลัก
				$qry_namemain = pg_query("select * from \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" ='0'");
				if($resnamemain = pg_fetch_array($qry_namemain)){
					$name3 = trim($resnamemain["thcap_fullname"]);
				}
				
				if($spitConType != $conType)
				{
					echo "<tr bgcolor=\"CCCCFF\" style=\"font-size:11px;\">";
					echo "<td colspan=\"3\" align=\"right\"><b>ดอกเบี้ยที่เกิดขึ้นรวมของประเภทสินเชื่อ $spitConType</b></td>";
					echo "<td align=\"right\"><b>".number_format($sumInterest,2)."</b></td>";
					echo "</tr>";
					$sumInterest = 0;
					$spitConType = $conType;
				}
				
				if($i%2==0){
					echo "<tr class=\"odd\">";
				}else{
					echo "<tr class=\"even\">";
				}
				
				echo "<td align=\"center\">$contractID</td>";
				echo "<td align=\"center\">$conType</td>";
				echo "<td align=\"left\">$name3</td>";
				echo "<td align=\"right\">".number_format($newInterest,2)."</td>";
				echo "</tr>";
				
				$sumInterest += $newInterest;
				$allInterest += $newInterest;
			}
			
			// ดอกเบี้ยรวม ของประเภทสินเชื่อสุดท้าย
			echo "<tr bgcolor=\"CCCCFF\" style=\"font-size:11px;\">";
			echo "<td colspan=\"3\" align=\"right\"><b>ดอกเบี้ยที่เกิดขึ้นรวมของประเภทสินเชื่อ $spitConType</b></td>";
			echo "<td align=\"right\"><b>".number_format($sumInterest,2)."</b></td>";
			echo "</tr>";
			
			// ดอกเบี้ยรวมทุกประเภท
			echo "<tr bgcolor=\"#CD96CD\" style=\"font-size:13px;\">";
			echo "<td colspan=\"3\" align=\"right\"><b>รวมดอกเบี้ยที่เกิดขึ้นทุกประเภท</b></td>";
			echo "<td align=\"right\"><b>".number_format($allInterest,2)."</b></td>";
			echo "</tr>";
		}
		else
		{
			echo "<tr bgcolor=\"#FFCCCC\"><td colspan=\"4\" align=\"center\">ไม่พบข้อมูล!!</td></tr>";
		}	
	}
echo "</table>";
?>