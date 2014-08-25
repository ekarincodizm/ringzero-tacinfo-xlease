<?php
include("../../config/config.php");

$contractID = pg_escape_string($_GET["conID"]); // เลขที่สัญญา
$isClose = pg_escape_string($_GET["isClose"]); // ปิดสัญญาหรือยัง

// หา contractType
$qry_contractType = pg_query("select \"thcap_get_contractType\"('$contractID')");
$contractType = pg_result($qry_contractType,0);

//----- ตรวจสอบหาเงื่อนไขการ where
	// ตรวจสอบว่า ประเภทสัญญาที่หา อยู่ใน view thcap_mg_contract หรือไม่
	$qry_chk = pg_query("select count(*) from thcap_mg_contract where \"conType\" = '$contractType' ");
	$row_chk = pg_result($qry_chk,0);

	// ถ้ามีข้อมูลใน view thcap_mg_contract
	if($row_chk > 0)
	{
		$where_view = "thcap_mg_contract";
		$where_column = "a.\"conLoanAmt\"";
	}
	else
	{
		$where_view = "thcap_lease_contract";
		$where_column = "a.\"conFinanceAmount\" as \"conLoanAmt\"";
	}
	
	// ถ้าเอาที่ปิดบัญชีแล้ว
	if($isClose == "close")
	{
		$where_close = "thcap_checkcontractcloseddate(a.\"contractID\") is not null";
	}
	else
	{
		$where_close = "thcap_checkcontractcloseddate(a.\"contractID\") is null";
	}
//----- จบการตรวจสอบหาเงื่อนไขการ where

// สร้าง query string
$qry_str = pg_query("
select \"dataTable\".\"DebtorName\", \"dataTable\".\"yearMonthStartDate\"
, (select sum(\"subTable\".\"conLoanAmt\") as \"sumLoanAmt\" from (
	select a.\"contractID\", a.\"conDate\", a.\"conStartDate\", c.\"conEndDate\", $where_column, b.\"arrayFaBill\", thcap_checkcontractcloseddate(a.\"contractID\") as \"conCloseDate\"
	,(SELECT distinct (select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = (select \"userDebtor\" from thcap_fa_prebill where \"prebillID\" = \"ta_array_list\"::bigint)) as \"DebtorName\"
	from ta_array_list(b.\"arrayFaBill\"))
	,substring(a.\"conStartDate\"::text from 1 for 7) as \"yearMonthStartDate\"
	,c.\"conEndDate\" - a.\"conStartDate\" as \"crEnd\"
	,thcap_checkcontractcloseddate(a.\"contractID\") - a.\"conStartDate\" as \"crClose\"
	from thcap_contract a, thcap_contract_fa_bill b, $where_view c
	where a.\"contractID\" = b.\"contractID\" and a.\"contractID\" = c.\"contractID\"
	and a.\"conCreditRef\" is not null
	and ta_array_check(a.\"conCreditRef\", '$contractID') = 1
	and $where_close) as \"subTable\"
	where \"subTable\".\"DebtorName\" = \"dataTable\".\"DebtorName\"
	and substring(\"subTable\".\"conStartDate\"::text from 1 for 7) = \"dataTable\".\"yearMonthStartDate\"
	)
, (select ceil(sum(\"subTable\".\"crEnd\")::numeric / count(*)) as \"avgEnd\" from (
	select a.\"contractID\", a.\"conDate\", a.\"conStartDate\", c.\"conEndDate\", $where_column, b.\"arrayFaBill\", thcap_checkcontractcloseddate(a.\"contractID\") as \"conCloseDate\"
	,(SELECT distinct (select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = (select \"userDebtor\" from thcap_fa_prebill where \"prebillID\" = \"ta_array_list\"::bigint)) as \"DebtorName\"
	from ta_array_list(b.\"arrayFaBill\"))
	,substring(a.\"conStartDate\"::text from 1 for 7) as \"yearMonthStartDate\"
	,c.\"conEndDate\" - a.\"conStartDate\" as \"crEnd\"
	,thcap_checkcontractcloseddate(a.\"contractID\") - a.\"conStartDate\" as \"crClose\"
	from thcap_contract a, thcap_contract_fa_bill b, $where_view c
	where a.\"contractID\" = b.\"contractID\" and a.\"contractID\" = c.\"contractID\"
	and a.\"conCreditRef\" is not null
	and ta_array_check(a.\"conCreditRef\", '$contractID') = 1
	and $where_close) as \"subTable\"
	where \"subTable\".\"DebtorName\" = \"dataTable\".\"DebtorName\"
	and substring(\"subTable\".\"conStartDate\"::text from 1 for 7) = \"dataTable\".\"yearMonthStartDate\"
	)
, (select ceil(sum(\"subTable\".\"crClose\")::numeric / count(*)) as \"avgClose\" from (
	select a.\"contractID\", a.\"conDate\", a.\"conStartDate\", c.\"conEndDate\", $where_column, b.\"arrayFaBill\", thcap_checkcontractcloseddate(a.\"contractID\") as \"conCloseDate\"
	,(SELECT distinct (select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = (select \"userDebtor\" from thcap_fa_prebill where \"prebillID\" = \"ta_array_list\"::bigint)) as \"DebtorName\"
	from ta_array_list(b.\"arrayFaBill\"))
	,substring(a.\"conStartDate\"::text from 1 for 7) as \"yearMonthStartDate\"
	,c.\"conEndDate\" - a.\"conStartDate\" as \"crEnd\"
	,thcap_checkcontractcloseddate(a.\"contractID\") - a.\"conStartDate\" as \"crClose\"
	from thcap_contract a, thcap_contract_fa_bill b, $where_view c
	where a.\"contractID\" = b.\"contractID\" and a.\"contractID\" = c.\"contractID\"
	and a.\"conCreditRef\" is not null
	and ta_array_check(a.\"conCreditRef\", '$contractID') = 1
	and $where_close) as \"subTable\"
	where \"subTable\".\"DebtorName\" = \"dataTable\".\"DebtorName\"
	and substring(\"subTable\".\"conStartDate\"::text from 1 for 7) = \"dataTable\".\"yearMonthStartDate\"
	)
from
(
select a.\"contractID\", a.\"conDate\", a.\"conStartDate\", c.\"conEndDate\", $where_column, b.\"arrayFaBill\", thcap_checkcontractcloseddate(a.\"contractID\") as \"conCloseDate\"
,(SELECT distinct (select \"full_name\" from \"VSearchCusCorp\" where \"CusID\" = (select \"userDebtor\" from thcap_fa_prebill where \"prebillID\" = \"ta_array_list\"::bigint)) as \"DebtorName\"
from ta_array_list(b.\"arrayFaBill\"))
,substring(a.\"conStartDate\"::text from 1 for 7) as \"yearMonthStartDate\"
,c.\"conEndDate\" - a.\"conStartDate\" as \"crEnd\"
,thcap_checkcontractcloseddate(a.\"contractID\") - a.\"conStartDate\" as \"crClose\"
from thcap_contract a, thcap_contract_fa_bill b, $where_view c
where a.\"contractID\" = b.\"contractID\" and a.\"contractID\" = c.\"contractID\"
and a.\"conCreditRef\" is not null
and ta_array_check(a.\"conCreditRef\", '$contractID') = 1
and $where_close
) as \"dataTable\"
group by \"DebtorName\", \"yearMonthStartDate\"
order by \"DebtorName\", \"yearMonthStartDate\"
");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>วิเคราะห์ลูกหนี้การค้า</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
</head>
<body>
<center>
	<h1>วิเคราะห์ลูกหนี้การค้า</h1>
	<h2>ที่ใช้วงเงินของเลขที่สัญญา <?php echo $contractID; if($isClose == "open"){echo " ที่ยังไม่ปิดบัญชี";}elseif($isClose == "close"){echo " ที่ปิดบัญชีไปแล้ว";} ?></h2>
	<table width="100%" border="0" cellspacing="1" cellpadding="1">
		<tr align="center" bgcolor="#79BCFF">
			<th>ชื่อลูกหนี้การค้า</th>
			<th>ปี-เดือน ที่เริ่มกู้</th>
			<th>ยอดกู้รวม</th>
			<th>ค่าเฉลี่ยวันที่ครบกำหนดชำระ(วัน)</th>
			<th>ค่าเฉลี่ยวันที่ปิดบัญชี(วัน)</th>
		</tr>
		<?php
		$i = 0;
		$m = 0;
		while($res_str = pg_fetch_array($qry_str))
		{
			$i++;
			$DebtorName = $res_str["DebtorName"];
			$yearMonthStartDate = $res_str["yearMonthStartDate"];
			$sumLoanAmt = $res_str["sumLoanAmt"];
			$avgEnd = $res_str["avgEnd"];
			$avgClose = $res_str["avgClose"];
			
			if($m == 0)
			{
				$m++;
				$sumLoanAmtSum = $sumLoanAmt;
				$avgEndSum = $avgEnd;
				$avgCloseSum = $avgClose;
				
				$avgEndAll = ceil($avgEndSum / $i);
				$avgCloseAll = ceil($avgCloseSum / $i);
			}
			elseif($DebtorName_old != $DebtorName)
			{
				$m++;
			}
			else
			{
				$sumLoanAmtSum += $sumLoanAmt;
				$avgEndSum += $avgEnd;
				$avgCloseSum += $avgClose;
				
				$avgEndAll = ceil($avgEndSum / $i);
				$avgCloseAll = ceil($avgCloseSum / $i);
			}
			
			// ถ้าคนละคนกัน และไม่ใช่รอบแรก
			if($m != 1 && $DebtorName_old != $DebtorName)
			{
				echo "<tr class=\"sum\" height=20 align=\"center\">";
				echo "<td colspan=\"2\" align=\"right\"><b>รวม : $DebtorName_old</b></td>";
				echo "<td align=\"right\"><b>".number_format($sumLoanAmtSum,2)."</b></td>";
				echo "<td><b>(เฉลี่ย $avgEndAll วัน)</b></td>";
				echo "<td><b>(เฉลี่ย $avgCloseAll วัน)</b></td>";
				echo "</tr>";
				
				// กำหนดค่าใหม่
				$i = 1;
				$sumLoanAmtSum = $sumLoanAmt;
				$avgEndSum = $avgEnd;
				$avgCloseSum = $avgClose;
				
				$avgEndAll = ceil($avgEndSum / $i);
				$avgCloseAll = ceil($avgCloseSum / $i);
			}
			
			if($i%2==0){
				echo "<tr class=\"odd\" height=20 align=\"center\">";	
			}else{
				echo "<tr class=\"even\" height=20 align=\"center\">";
			}
			
			echo "<td>$DebtorName</td>";
			echo "<td>$yearMonthStartDate</td>";
			echo "<td align=\"right\">".number_format($sumLoanAmt,2)."</td>";
			echo "<td>$avgEnd</td>";
			echo "<td>$avgClose</td>";
			
			echo "</tr>";
			
			$DebtorName_old = $DebtorName;
		}
		
		echo "<tr class=\"sum\" height=20 align=\"center\">";
		echo "<td colspan=\"2\" align=\"right\"><b>รวม : $DebtorName</b></td>";
		echo "<td align=\"right\"><b>".number_format($sumLoanAmtSum,2)."</b></td>";
		echo "<td><b>(เฉลี่ย $avgEndAll วัน)</b></td>";
		echo "<td><b>(เฉลี่ย $avgCloseAll วัน)</b></td>";
		echo "</tr>";
		?>
	</table>
</center>
</body>
</html>