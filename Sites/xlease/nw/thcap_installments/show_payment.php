<?php
include("../../config/config.php");
include("../../core/core_functions.php");
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
</head>
<body>

<?php

$contractID = $_GET["idno"];

$sql_head=pg_query("select * from public.\"thcap_mg_contract\" where \"contractID\" = '$contractID' ");
while($resultH=pg_fetch_array($sql_head))
{
	$conStartDate = $resultH["conStartDate"];
}

$sql_table=pg_query("select * from public.\"thcap_temp_int_201201\" where \"contractID\" = '$contractID' and \"isReceiveReal\" = '1' order by \"receiveDate\" ");
?>

<center>
<table>
	<tr align="center" bgcolor="#79BCFF">
		<th>วันที่จ่าย</th><th>ดอกเบี้ย ปัจจุบัน</th><th>จำนวนเงินที่จ่าย</th><th>จำนวนวัน</th><th>จำนวนเงินหักดอกเบี้ย</th>
		<th>จำนวนหักเงินต้น</th><th>ยอดเงินต้นคงเหลือ ณ วันจ่าย</th><th>ยอดดอกเบี้ยคงเหลือ ณ วันจ่าย</th><th>ช่องทางการจ่าย</th>
	</tr>
	
<?php

$i = 1;

while($result=pg_fetch_array($sql_table))
{
	$receiveDate[$i] = $result["receiveDate"];
	$interestRate = $result["interestRate"];
	$receiveAmount = $result["receiveAmount"];
	$receiveInterest = $result["receiveInterest"];
	$receivePriciple = $result["receivePriciple"];
	$LeftPrinciple = $result["LeftPrinciple"];
	$LeftInterest = $result["LeftInterest"];
	$byChannel  = $result["byChannel"];
	
	if($i == 1){$day = core_time_datediff($conStartDate, $receiveDate[$i]);}
	else{$day = core_time_datediff($receiveDate[$i-1], $receiveDate[$i]);}
	
	if($byChannel=="" || $byChannel=="0" || $byChannel=="999"){$byChannel="ไม่ระบุ";}
	else{
		//นำไปค้นหาในตาราง BankInt
		$qrysearch=pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$byChannel'");
		$ressearch=pg_fetch_array($qrysearch);
		list($BAccount,$BName)=$ressearch;
		$byChannel="$BAccount-$BName";
	}

	if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
?>
		<td align="center"><?php echo $receiveDate[$i]; ?></td><td align="right"><?php echo $interestRate."%"; ?></td><td align="right"><?php echo number_format($receiveAmount,2); ?></td>
		<td align="center"><?php echo $day ?></td><td align="right"><?php echo number_format($receiveInterest,2); ?></td><td align="right"><?php echo number_format($receivePriciple,2); ?></td>
		<td align="right"><?php echo number_format($LeftPrinciple,2); ?></td><td align="right"><?php echo number_format($LeftInterest,2); ?></td><td align="center"><?php echo $byChannel; ?></td>
	</tr>
<?php
	$i++;
}
?>
</table>
</center>

</body>
</html>