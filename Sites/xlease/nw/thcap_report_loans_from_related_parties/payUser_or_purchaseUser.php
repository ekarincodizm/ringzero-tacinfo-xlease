<?php
include("../../config/config.php");

$frame = pg_escape_string($_GET["frame"]); // ค้นหากรอบใด payUser(กรอบผู้กู้)  |  purchaseUser(กรอบผู้ให้กู้)
$year = pg_escape_string($_GET["year"]);
$ticketStatus = pg_escape_string($_GET["ticketStatus"]);

if($frame == "payUser")
{
	$textFrame = "ผู้กู้";
	$color1 = "#579AFF";
	$color2 = "#79BCFF";
	$color3 = "#9BDEFF";
}
elseif($frame == "purchaseUser")
{
	$textFrame = "ผู้ให้กู้";
	$color1 = "#FF9A57";
	$color2 = "#FFBC79";
	$color3 = "#FFDE9B";
}

// ถ้าระบุปี
if($year != "all")
{
	$whereYear = "and substring(\"payDate\"::text from 1 for 4) = '$year'";
	$textYear = "ปีที่เกิดของตั๋วเงิน($year)";
}
else
{
	$textYear = "ปีที่เกิดของตั๋วเงิน(ทั้งหมด)";
}

// ถ้าระบุสถานะตั๋ว
if($ticketStatus == "closed")
{
	$whereStatus = "and \"statusTicket\" = 'f'";
	$textStatus = "	สถานะตั๋ว(ปิดตั๋วแล้ว)";
}
elseif($ticketStatus == "notClose")
{
	$whereStatus = "and \"statusTicket\" = 't'";
	$textStatus = "	สถานะตั๋ว(ยังไม่ปิดตั๋ว)";
}
else
{
	$textStatus = "	สถานะตั๋ว(ทั้งหมด)";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) รายงานการกู้เงินจากกิจการที่เกี่ยวข้องกัน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
	<fieldset><legend><B><?php echo $textFrame; ?></B><font color="#555555"> :: <?php echo $textYear; ?> :: <?php echo $textStatus; ?></font></legend>
		<table width="100%">
			<!--tr bgcolor="#3578FF">
				<th>เลขที่ตั๋ว</th>
				<th>ผู้กู้</th>
				<th>ผู้ให้กู้</th>
				<th>จำนวนเงิน</th>
				<th>อัตราดอกเบี้ย</th>
				<th>วันที่ให้กู้</th>
				<th>วันที่ชำระคืน</th>
				<th>จำนวนเงินที่ชำระคืน</th>
				<th>ภาษีหัก ณ ที่จ่าย</th>
				<th>เลขที่ใบภาษีหัก ณ ที่จ่าย</th>
				<th>เลขที่ใบสำคัญของ THCAP ในการให้กู้ หรือกู้</th>
				<th>เลขที่ใบสำคัญของ THCAP ในการรับคืนเงินกู้ หรือจ่ายคืนเงินกู้</th>
			</tr-->
			
			<?php
			$qry_mainData = pg_query("select \"boeID\", \"boeNumber\", \"payUser\", \"purchaseUser\", \"loan_amount\", \"interest\", \"payDate\",
										\"returnDate\", \"receivepaybackamt\", \"receivewhtamt\", \"receivewhtref\", \"paydate_voucher\", \"returndate_voucher\"
									from account.\"boe\" where \"$frame\" = 'THCAP' $whereYear $whereStatus ");
			$row_mainData = pg_num_rows($qry_mainData);
			while($res_mainData = pg_fetch_array($qry_mainData))
			{
				$boeID = $res_mainData["boeID"];
				
				//-- ข้อมูลที่เป็นจำนวนเงิน
				if($res_mainData["loan_amount"] != ""){$loan_amount = number_format($res_mainData["loan_amount"],2);}else{$loan_amount = "";}
				if($res_mainData["interest"] != ""){$interest = number_format($res_mainData["interest"],2);}else{$interest = "";}
				if($res_mainData["receivepaybackamt"] != ""){$receivepaybackamt = number_format($res_mainData["receivepaybackamt"],2);}else{$receivepaybackamt = "";}
				if($res_mainData["receivewhtamt"] != ""){$receivewhtamt = number_format($res_mainData["receivewhtamt"],2);}else{$receivewhtamt = "";}
				
				//---------- รายการหลัก
					echo "<tr bgcolor=\"$color1\">";
					echo "<th>เลขที่ตั๋ว</th>";
					echo "<th>ผู้กู้</th>";
					echo "<th>ผู้ให้กู้</th>";
					echo "<th>จำนวนเงิน</th>";
					echo "<th>อัตราดอกเบี้ย</th>";
					echo "<th>วันที่ให้กู้</th>";
					echo "<th>วันที่ชำระคืน</th>";
					echo "<th>จำนวนเงินที่ชำระคืน</th>";
					echo "<th>ภาษีหัก ณ ที่จ่าย</th>";
					echo "<th>เลขที่ใบภาษีหัก ณ ที่จ่าย</th>";
					echo "<th>เลขที่ใบสำคัญของ THCAP ในการให้กู้ หรือกู้</th>";
					echo "<th>เลขที่ใบสำคัญของ THCAP ในการรับคืนเงินกู้ หรือจ่ายคืนเงินกู้</th>";
					echo "</tr>";
					
					echo "<tr bgcolor=\"$color2\">";
					echo "<td align=\"center\">$res_mainData[boeNumber]</td>";
					echo "<td align=\"center\">$res_mainData[payUser]</td>";
					echo "<td align=\"center\">$res_mainData[purchaseUser]</td>";
					echo "<td align=\"right\">$loan_amount</td>";
					echo "<td align=\"right\">$interest</td>";
					echo "<td align=\"center\">$res_mainData[payDate]</td>";
					echo "<td align=\"center\">$res_mainData[returnDate]</td>";
					echo "<td align=\"right\">$receivepaybackamt</td>";
					echo "<td align=\"right\">$receivewhtamt</td>";
					echo "<td align=\"center\">$res_mainData[receivewhtref]</td>";
					echo "<td align=\"center\">$res_mainData[paydate_voucher]</td>";
					echo "<td align=\"center\">$res_mainData[returndate_voucher]</td>";
					echo "</tr>";
				//---------- จบรายการหลัก
				
				//---------- รายการย่อย
					echo "<tr>";
					echo "<td colspan=\"12\">";
					echo "<table width=\"100%\">";
					
					echo "<tr bgcolor=\"$color2\">";
					echo "<th>รายละเอียดดอกเบี้ย</th>";
					echo "<th>จำนวน</th>";
					echo "<th>เลขที่ใบสำคัญ</th>";
					echo "</tr>";
					
					$qry_subData = pg_query("select \"startincacc_int2555_accured\", \"int_acc_2556\", \"int_acc_2556_jv\", \"int_in_2557_q1\", \"int_in_2557_q1_jv\", \"int_acc_2557_q1\", \"int_acc_2557_q1_jv\"
											from account.\"boe\" where \"boeID\" = '$boeID' ");
					$res_subData = pg_fetch_array($qry_subData);
					
					$startincacc_int2555_accured = $res_subData["startincacc_int2555_accured"];
					$int_acc_2556 = $res_subData["int_acc_2556"];
					$int_acc_2556_jv = $res_subData["int_acc_2556_jv"];
					$int_in_2557_q1 = $res_subData["int_in_2557_q1"];
					$int_in_2557_q1_jv = $res_subData["int_in_2557_q1_jv"];
					$int_acc_2557_q1 = $res_subData["int_acc_2557_q1"];
					$int_acc_2557_q1_jv = $res_subData["int_acc_2557_q1_jv"];
					
					//-- ข้อมูลที่เป็นจำนวนเงิน
					if($startincacc_int2555_accured != ""){$startincacc_int2555_accured = number_format($startincacc_int2555_accured,2);}else{$startincacc_int2555_accured = "";}
					if($int_acc_2556 != ""){$int_acc_2556 = number_format($int_acc_2556,2);}else{$int_acc_2556 = "";}
					if($int_in_2557_q1 != ""){$int_in_2557_q1 = number_format($int_in_2557_q1,2);}else{$int_in_2557_q1 = "";}
					if($int_acc_2557_q1 != ""){$int_acc_2557_q1 = number_format($int_acc_2557_q1,2);}else{$int_acc_2557_q1 = "";}
					
					echo "<tr bgcolor=\"$color3\">";
					echo "<td align=\"left\">ดอกเบี้ยทั้งหมดที่ยกมาในปี 2555</td>";
					echo "<td align=\"right\">$startincacc_int2555_accured</td>";
					echo "<td align=\"center\"></td>";
					echo "</tr>";
					
					echo "<tr bgcolor=\"$color3\">";
					echo "<td align=\"left\">ดอกเบี้ยที่เกิดขึ้นระหว่างปี 2556</td>";
					echo "<td align=\"right\">ข้อมูลอยู่ในตั๋วเงิน</td>";
					echo "<td align=\"center\">ข้อมูลอยู่ในตั๋วเงิน</td>";
					echo "</tr>";
					
					echo "<tr bgcolor=\"$color3\">";
					echo "<td align=\"left\">ดอกเบี้ยยกไปในปี 2556</td>";
					echo "<td align=\"right\">$int_acc_2556</td>";
					echo "<td align=\"center\">$int_acc_2556_jv</td>";
					echo "</tr>";
					
					echo "<tr bgcolor=\"$color3\">";
					echo "<td align=\"left\">ดอกเบี้ยที่เกิดขึ้นระหว่างปี 2557</td>";
					echo "<td align=\"right\">$int_in_2557_q1</td>";
					echo "<td align=\"center\">$int_in_2557_q1_jv</td>";
					echo "</tr>";
					
					echo "<tr bgcolor=\"$color3\">";
					echo "<td align=\"left\">ดอกเบี้ยยกไปในปี 2557 - ไตรมาส 1</td>";
					echo "<td align=\"right\">$int_acc_2557_q1</td>";
					echo "<td align=\"center\">$int_acc_2557_q1_jv</td>";
					echo "</tr>";
					
					echo "</table>";
					echo "</td>";
					echo "</tr>";
				//---------- จบรายการย่อย
				
			?>
			<tr><td><br></td></tr>
			<?php
			}
			
			if($row_mainData == 0)
			{
				echo "<tr><td align=\"center\">-- ไม่พบข้อมูล --</td></tr>";
			}
			?>
		</table>
	</fieldset>
</body>
</html>