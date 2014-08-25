<?php
include("../../config/config.php");

$contractID = pg_escape_string($_GET["contractID"]);

$zz = 1;

function search_money($S_contractID , $S_ptNum_old ) // function ในการหาค่าติดตามทวงถาม
{
	// หาค่าติดตามทวงถาม
	$S_sql_collection=pg_query("select \"debtID\", \"debtStatus\", \"typePayAmt\" from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\" = '$S_contractID' and \"typePayID\" = '1003' and \"typePayRefValue\" = '$S_ptNum_old' and \"debtStatus\" in('1','2') ");
	$S_numrows_collection = pg_num_rows($S_sql_collection);
	if($S_numrows_collection > 0)
	{
		while($S_resultcollection = pg_fetch_array($S_sql_collection))
		{
			$S_debtID = $S_resultcollection["debtID"]; // เลขที่ค่าติดตามทวงถาม
			$S_debtStatus = $S_resultcollection["debtStatus"]; // สถานะว่าจ่ายหรือยัง
			$S_typePayAmt = $S_resultcollection["typePayAmt"]; // จำนวนเงิน
		}
		
		if($S_debtStatus == 1)
		{
			$S_collection = "ค้างชำระ"; // ค่าติดตามทวงถาม
		}
		elseif($S_debtStatus == 2)
		{
			$S_sql_pa = pg_query("select \"receiptID\" from public.\"thcap_v_receipt_otherpay\" where \"debtID\" = '$S_debtID' ");
			while($S_resultpa = pg_fetch_array($S_sql_pa))
			{
				$S_receiptID_pa = $S_resultpa["receiptID"]; // เลขที่ใบเสร็จของค่าติดตามทวงถาม
			}
			
			$S_collection = $S_typePayAmt." ($S_receiptID_pa)"; // ค่าติดตามทวงถาม
		}
	}
	else
	{
		$S_collection = ""; // ค่าติดตามทวงถาม
	}
	// จบการหาค่าติดตามทวงถาม
			
	return $S_collection;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<style type="text/css">
	.odd{
		background-color:#FFFFCF;
		font-size:12px
	}
	.even{
		background-color:#D5EFFD;
		font-size:12px
	}
	.sum{
		background-color:#FFC0C0;
		font-size:12px
	}
	</style>
</head>

<body>
<?php
$db1="ta_mortgage_datastore";

//ค้นหาชื่อผู้กู้หลักจาก mysql
$qry_namemain=pg_query("select \"thcap_fullname\" from \"vthcap_ContactCus_detail\"
where \"contractID\"='$contractID' and \"CusState\"='0'");
if($resnamemain=pg_fetch_array($qry_namemain)){
	$name3=trim($resnamemain["thcap_fullname"]);
}


$sql_head=pg_query("select \"conLoanIniRate\", \"conLoanMaxRate\", \"conDate\", \"conStartDate\", \"conRepeatDueDay\", \"conLoanAmt\", \"conTerm\", \"conMinPay\"
					from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
$rowhead=pg_num_rows($sql_head);
$i = 1;
while($result=pg_fetch_array($sql_head))
{
	$conLoanIniRate = $result["conLoanIniRate"];
	$conLoanMaxRate = $result["conLoanMaxRate"];
	$conDate = $result["conDate"];
	$conStartDate = $result["conStartDate"];
	$conRepeatDueDay = $result["conRepeatDueDay"];
	$conLoanAmt = $result["conLoanAmt"];
	$conTerm = $result["conTerm"];
	$conMinPay = $result["conMinPay"];
}
?>

<center>
<h2>
<br>
ตารางชำระเงินกู้
<br>
เลขที่สัญญา : <?php echo $contractID; ?>
<br>
<?php echo $name3; ?>
<br>
<?php echo "ยอดเงินกู้ : ".number_format($conLoanAmt,2)." บาท ($conTerm งวด)"; ?>
<br>
</h2>
<table width="100%" align="center" border="0" cellspacing="1" cellpadding="1" bgcolor="#000000">
	<tr align="center" bgcolor="#79BCFF">
		<th>งวดที่</th>
		<th>วันครบกำหนด</th>
		<th>วันที่ชำระ</th>
		<th>ยอดชำระขั้นต่ำ</th>
		<th>ยอดที่จ่าย</th>
		<th>เลขที่ใบเสร็จ</th>
		<th>ค่าติดตามทวงถาม</th>
	</tr>
<?php
	
	$sql_one=pg_query("select \"ptNum\", \"ptDate\", \"ptMinPay\"
					from account.\"thcap_mg_payTerm\" where \"contractID\" = '$contractID' order by \"ptNum\" ASC");
	$numrows_one = pg_num_rows($sql_one);
	while($resultone=pg_fetch_array($sql_one))
	{
		$i++;
		$ptNum_now = $resultone["ptNum"]; // งวดที่
		$ptDate_now = $resultone["ptDate"]; // วันครบกำหนด
		$ptMinPay_now = $resultone["ptMinPay"]; // ยอดจ่ายขั้นต่ำ
		
		$collection = search_money($contractID, $ptNum_now); // ค่าติดตามทวงถาม
		
		if($ptNum_now%2==0){
			echo "<tr class=\"odd\" align=\"center\">";
		}else{
			echo "<tr class=\"even\" align=\"center\">";
		}
		
		echo "<td>$ptNum_now</td>"; // งวดที่
		echo "<td>$ptDate_now</td>"; // วันที่ครบกำหนด
		echo "<td></td>"; // วันที่ชำระ
		echo "<td align=\"right\">".number_format($ptMinPay_now,2)."</td>"; // ยอดจ่ายขั้นต่ำ
		echo "<td align=\"right\"></td>"; // ยอดที่จ่าย
		echo "<td></td>"; // เลขที่ใบเสร็จ
		echo "<td>$collection</td>"; // ค่าติดตามทวงถาม
		echo "</tr>";
	}
?>
</table>
</center>
</body>
</html>