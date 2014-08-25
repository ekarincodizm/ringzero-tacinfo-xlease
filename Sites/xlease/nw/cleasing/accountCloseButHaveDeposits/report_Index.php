<?php
include("../../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(xlease) เลขที่สัญญาที่ปิดบัญชีแต่มีเงินรับฝาก</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
 
</head>
<body>
<center>
<br>
<h2><b>เลขที่สัญญาที่ปิดบัญชีแล้ว แต่มีเงินรับฝาก</b></h2>
<br>
<?php
$qry_main = pg_query("select a.\"IDNO\", a.\"P_ACCLOSE\", b.\"dp_balance\"
					from public.\"Fp\" a, public.\"VContact\" b
					where a.\"IDNO\" = b.\"IDNO\" and a.\"P_ACCLOSE\" = 'TRUE' and b.\"dp_balance\" > '0'
					order by a.\"IDNO\" ");
$nomrows_main = pg_num_rows($qry_main);
if($nomrows_main == 0) // ถ้าไม่พบข้อมูล
{
	echo "<center><b>ไม่พบรายการ</b></center>";
}
else // ถ้ามีข้อมูล
{
	$i = 0;
?>
	<table border="0" cellspacing="1" cellpadding="1" bgcolor="#FFFFFF">
		<tr align="center" bgcolor="#79BCFF">
			<th width="80">รายการที่</th>
			<th width="120">เลขที่สัญญา</th>
			<th width="120">จำนวนเงินรับฝาก</th>
		</tr>
		<tr><td colspan="3"><hr></td></tr>
<?php	
	while($res_main = pg_fetch_array($qry_main))
	{
		$i++;
		$IDNO = $res_main["IDNO"]; // เลขที่สัญญา
		$dp_balance = $res_main["dp_balance"]; // จำนวนเงินรับฝาก
		
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\">$IDNO</td>";
		echo "<td align=\"right\">".number_format($dp_balance,2)." บาท</td>";
		echo "</tr>";
		echo "<tr><td colspan=\"3\"><hr></td></tr>";
	}
	echo "</table>";
}
?>
</center>
</body>
</html>