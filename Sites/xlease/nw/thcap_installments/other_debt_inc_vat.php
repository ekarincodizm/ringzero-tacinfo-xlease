<?php
include("../../config/config.php");
$contractID = $_GET['contractid'];
$currentDate=nowDate();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>คำนวณภาษีหัก ณ ที่จ่าย</title>

<link type="text/css" rel="stylesheet" href="act.css"></link>
<style type="text/css">
tr {
	height:25px;
}
.even .hilight {
	background-color:#ffc68d;
}
.odd .hilight {
	background-color:#ffe5cb;
}
h1 {
	font-size:14px;
	font-weight:bold;
}
</style>

</head>

<body>
<div align="center">
	<h1>คำนวนภาษีหัก ณ ที่จ่าย :: เลขที่ใบเสร็จ <?php echo $contractID; ?></h1>
    <div style="width:1024px; text-align:right; margin-bottom:10px;"><input type="button" name="btn_print" id="btn_print" value="พิมพ์" onclick="print_pdf('<?php echo $contractID; ?>');" /></div>
	<table width="1024" align="center" border="0" cellspacing="1" cellpadding="5" bgcolor="#FFFFFF">
		<tr  align="center" bgcolor="#097AB0" style="color:#FFFFFF" height="25">
			<th>รหัสประเภท<br />ค่าใช้จ่าย</th>
			<th>รายการ</th>
			<th>ค่าอ้างอิง<br/>ของค่าใช้จ่าย</th>
			<th>วันที่ตั้งหนี้</th>
			<th>ผู้ตั้งหนี้</th>
			<th>วันเวลาตั้งหนี้</th>
            <th>จำนวนหนี้<br />(ไม่รวม vat)</th>
            <th>ภาษีมูลค่าเพิ่ม</th>
            <th>จำนวนหนี้<br />(รวม vat)</th>
            <th style="background-color:#F93">ภาษีหัก<br />ณ ที่จ่าย</th>
		</tr>
	<?php
	$qry_other = pg_query("select * from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\"='$contractID' and \"debtStatus\"='1' order by \"typePayRefDate\" ");
	$row_other = pg_num_rows($qry_other);
	if($row_other > 0)
	{
		$qry_sum_other = pg_query("select sum(\"typePayAmt\") as \"summoney\" from public.\"thcap_v_otherpay_debt_realother\" where \"contractID\"='$contractID' and \"debtStatus\"='1' ");
		while($res_sum = pg_fetch_array($qry_sum_other))
		{
			$summoney = $res_sum["summoney"]; // เงินรวม
		}
		//echo "<b>รวมทั้งหมด ".number_format($summoney,2)." บาท</b>";
	}
	if($row_other > 0)
	{
		$t = 0;
		$all_typePayAmt = 0;
		$all_whtAmt = 0;
		$all_debtNet = 0;
		$all_debtVat = 0;
		while($res_name=pg_fetch_array($qry_other))
		{
			$typePayID=trim($res_name["typePayID"]); // รหัสประเภทค่าใช้จ่าย
			$typePayRefValue=trim($res_name["typePayRefValue"]);
			$typePayRefDate=trim($res_name["typePayRefDate"]);
			$typePayAmt=trim($res_name["typePayAmt"]);
			$doerID=trim($res_name["doerID"]); 
			$doerStamp=trim($res_name["doerStamp"]);
			$debtID=trim($res_name["debtID"]);
			$debtNet = trim($res_name['debtNet']);
			$debtVat = trim($res_name['debtVat']);
			//$contractID=trim($res_name["contractID"]);
			
			$whtAmtfunc = pg_query("SELECT \"thcap_checkdebtwht\"('$debtID','$currentDate')");					
			$whtAmt1 = pg_fetch_array($whtAmtfunc);
			$whtAmt = $whtAmt1['thcap_checkdebtwht'];
			
			$all_typePayAmt = $all_typePayAmt+$typePayAmt;
			$all_whtAmt = $whtAmt+$all_whtAmt;
			$all_debtNet = $all_debtNet+$debtNet;
			$all_debtVat = $all_debtVat+$debtVat;
				
			$doerStamp = substr($doerStamp,0,19); // ทำให้อยู่ในรูปแบบวันเวลาที่สวยงาม
				
			if($doerID == "000")
			{
				$doerName = "อัตโนมัติโดยระบบ";
			}
			else
			{
				$doerusername=pg_query("select * from public.\"Vfuser\" where \"id_user\"='$doerID'");
				while($res_username=pg_fetch_array($doerusername))
				{
					$doerName=$res_username["fullname"];
				}
			}
			
			$qry_type=pg_query("select * from account.\"thcap_typePay\" where \"tpID\"='$typePayID' ");
			while($res_type=pg_fetch_array($qry_type))
			{
				$tpDesc=trim($res_type["tpDesc"]); // รายละเอียดประเภทค่าใช้จ่าย
			}
			
			if($t%2==0){
				echo "<tr class=\"odd\">";
			}else{
				echo "<tr class=\"even\">";
			}
			
			$due = ""; // กำหนดวันดิวเป็นค่าว่าง เพื่อไม่ให้เก็บค่าเก่ามาใช้
			
			if($typePayID == "1003")
			{
				//-----------------ตัดส่วนเกินออก
				$search = strpos($typePayRefValue,"-");
				if($search)
				{
					$subtypePayRefValue = explode("-", $typePayRefValue);
					$typePayRefValue = $subtypePayRefValue[0];
				}
				//-----------------จบการตัดส่วนเกินออก
				
				$qry_due=pg_query("select * from account.\"thcap_mg_payTerm\" where \"contractID\"='$contractID' and \"ptNum\"='$typePayRefValue' ");
				while($res_due=pg_fetch_array($qry_due))
				{
					$ptDate=trim($res_due["ptDate"]); // วันดิว
					$due = "($ptDate)";
				}
			}
			else
			{
				$due = "";
			}
			
			//echo "<tr bgcolor=#DBF2FD>";
			echo "<td align=center>$typePayID</td>";
			echo "<td align=center>$tpDesc</td>";
			echo "<td align=center>$typePayRefValue $due</td>";
			echo "<td align=center>$typePayRefDate</td>";
			echo "<td align=center>$doerName</td>";
			echo "<td align=center>$doerStamp</td>";
			echo "<td align=right>".number_format($debtNet,2)."</td>";
			echo "<td align=right>".number_format($debtVat,2)."</td>";
			echo "<td align=right>".number_format($typePayAmt,2)."</td>";
			echo "<td align=\"right\" class=\"hilight\">".number_format($whtAmt,2,'.',',')."</td>";
			echo "</tr>";
			
			$t++;
		}
		if($row_other!=0)
		{
			echo "
				<tr align=\"center\" bgcolor=\"#097AB0\" style=\"color:#FFFFFF\" height=\"25\">
					<td colspan=\"6\" align=\"center\"><b>ยอดรวม</b></td>
					<td align=\"right\"><b>".number_format($all_debtNet,2,'.',',')."</b></td>
					<td align=\"right\"><b>".number_format($all_debtVat,2,'.',',')."</b></td>
					<td align=\"right\"><b>".number_format($all_typePayAmt,2,'.',',')."</b></td>
					<td align=\"right\" style=\"background-color:#F93\"><b>".number_format($all_whtAmt,2,'.',',')."</b></td>
				</tr>
			";
		}
	}
	else
	{
		echo "<tr><td align=\"center\" COLSPAN=\"8\" bgcolor=\"#33FFCC\"><b>ไม่พบหนี้อื่นๆที่ค้างชำระ</b></td></tr>";
	}
?>
	</table>
</div>
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
function print_pdf(contractID){
	if(contractID=='')
	{
		alert('ไม่สามารถทำรายการได้');
	}
	else
	{
		popU('print_result_pdf.php?contractid='+contractID,'','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1064,height=700');
	}
}
</script>
</body>
</html>