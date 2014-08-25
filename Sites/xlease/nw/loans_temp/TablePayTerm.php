<?php
include('../../config/config.php');

$contractID = $_GET["contractID"]; // เลขที่สัญญา
$StampAppv = $_GET['StampAppv']; // วันเวลาที่ทำรายการอนุมัติ
$AppvStatus = $_GET['AppvStatus']; // สถานะอนุมัติ 1 อนุมัติ 0 ไม่อนุมัติ
$getDoerStamp = $_GET['doerStamp']; // วันเวลาที่ทำรายการ

$conMinPay = $_GET['conMinPay'];
$conTerm = $_GET['conTerm'];
$conFirstDue = $_GET['conFirstDue'];
$conRepeatDueDay = $_GET['conRepeatDueDay']; // จ่ายทุกๆวันที่
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css"></link>

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<center>
<table width="auto" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#EEEEFF">
		<td colspan="3" align="center"><font size="4">การผ่อนชำระ</font></td>
	</tr>

	<tr align="center" bgcolor="#79BCFF">
		<th>&nbsp; งวดที่ &nbsp;</th>
		<th>&nbsp; วันครบกำหนดชำระ &nbsp;</th>
		<th>&nbsp;&nbsp;&nbsp; ยอดผ่อนชำระ &nbsp;&nbsp;&nbsp;</th>
	</tr>

	<?php
	if($AppvStatus == "1") // ถ้าเป็นรายการที่อนุมัติแล้ว
	{
		$qry_payTerm = pg_query("select * from account.\"thcap_payTerm\" where \"contractID\" = '$contractID' order by \"ptNum\" ");
	}
	elseif($AppvStatus == "0") // ถ้าเป็นรายการที่ไม่อนุมัติ
	{
		$qry_payTerm = pg_query("select * from account.\"thcap_payTerm_temp\" where \"contractID\" = '$contractID' and \"doerStamp\" = '$getDoerStamp' and \"Approved\" = 'false' and \"appvStamp\" = '$StampAppv' order by \"ptNum\" ");
	}
	else
	{
		$qry_payTerm = pg_query("select * from account.\"thcap_payTerm_temp\" where \"contractID\" = '$contractID' and \"doerStamp\" = '$getDoerStamp' and \"Approved\" is null order by \"ptNum\" ");
	}
	
	$row_payTerm = pg_num_rows($qry_payTerm);
	if($row_payTerm > 0)
	{
		$sum_ptMinPay = 0;
		$i = 1;
		while($res_payTerm = pg_fetch_array($qry_payTerm))
		{
			$ptNum = $res_payTerm["ptNum"]; // งวดที่
			$ptDate = $res_payTerm["ptDate"]; // วันที่ครบกำหนดชำระ
			$ptMinPay = $res_payTerm["ptMinPay"]; // ยอดผ่อนชำระ
			
			$sum_ptMinPay += $ptMinPay;
			
			if($i==1)
			{
				$nextConDue = $conFirstDue;
			}
			elseif($i==2)
			{
				$arrayConDue = explode("-",$nextConDue);
				$arrayConDue[2] = $conRepeatDueDay; // ถ้าเป็นรอบสอง ให้ใช้วันที่ จ่ายทุกๆวันที่
				$plusConDue = mktime(0,0,0,$arrayConDue[1]+1,$arrayConDue[2],$arrayConDue[0]); // เวลา เดือน วัน ปี
				$nextConDue = date("Y-m-d",$plusConDue); // วันที่จะครบกำหนดชำระ แบบ ปี-เดือน-วัน
			}
			else
			{
				$arrayConDue = explode("-",$nextConDue);
				$plusConDue = mktime(0,0,0,$arrayConDue[1]+1,$arrayConDue[2],$arrayConDue[0]); // เวลา เดือน วัน ปี
				$nextConDue = date("Y-m-d",$plusConDue); // วันที่จะครบกำหนดชำระ แบบ ปี-เดือน-วัน
			}
			
			if(trim($nextConDue) != trim($ptDate) || trim($conMinPay) != trim($ptMinPay))
			{
				echo "<tr bgcolor=\"#ffbaba\">";
			}
			else
			{
				if($ptNum%2==0){
					echo "<tr class=\"odd\">";
				}else{
					echo "<tr class=\"even\">";
				}
			}
			
			$i++;

			echo "<td align=\"center\">$ptNum</td>";
			echo "<td align=\"center\">$ptDate</td>";
			echo "<td align=\"right\">".number_format($ptMinPay,2)."</td>";
			echo "</tr>";
		}
		echo "<tr bgcolor=\"#8ACDFF\">";
		echo "<th align=\"right\" colspan=\"2\">รวมยอดผ่อนชำระทั้งหมด</th>";
		echo "<th align=\"right\">".number_format($sum_ptMinPay,2)."</th>";
		echo "</tr>";
	}
	else
	{
		echo "<tr bgcolor=\"#EEEEEE\"><th align=\"center\" colspan=\"3\" >ไม่พบตารางการผ่อนชำระ</th></tr>";
	}
	?>
</table>
</center>