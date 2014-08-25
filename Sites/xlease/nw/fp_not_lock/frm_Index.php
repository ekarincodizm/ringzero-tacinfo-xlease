<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>สัญญาเช่าซื้อที่ยังไม่ล็อก</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>

<center><h1>สัญญาเช่าซื้อที่ยังไม่ล็อก</h1></center>
<body >
<table align="center" width="85%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>IDNO</th>
		<th>ทะเบียน</th>
		<th>ชื่อ - นามสกุล</th>
		<th>ประเภท</th>
		<th>ทำรายการ</th>
	</tr>
	<?php
	$qry_main = pg_query("SELECT a.\"IDNO\", b.\"C_REGIS\", b.\"C_CARNUM\", ((btrim(d.\"A_FIRNAME\") || btrim(d.\"A_NAME\")) || ' ') || btrim(d.\"A_SIRNAME\") AS full_name, 
						a.asset_type, a.\"LockContact\",a.asset_id
						FROM \"Fp\" a
						LEFT JOIN \"VCarregistemp\" b on a.\"IDNO\" = b.\"IDNO\"
						LEFT JOIN \"Fa1\" d ON a.\"CusID\" = d.\"CusID\"
						LEFT JOIN \"FGas\" e ON a.asset_id = e.\"GasID\"
						where a.\"LockContact\" = false
						ORDER BY a.\"IDNO\" ");

	$no=0;	
	while($row = pg_fetch_array($qry_main))
	{
		$id = $row["IDNO"]; // ฟิลที่ต้องการส่งค่ากลับ
		$fullname =trim($row["full_name"]);
		$ass_id=trim($row["asset_id"]);
		$carn=trim($row["C_CARNUM"]);
		
		if($row["asset_type"]==1)
		{
			$regis=trim($row["C_REGIS"]);
			$article="CAR";
		}
		else
		{
			$qry_gas=pg_query("select \"GasID\",car_regis from \"FGas\" where \"GasID\"='$ass_id' ");
			$resgas=pg_fetch_array($qry_gas);
			$regis=$resgas["car_regis"];
			$article="GAS";
		}
	
		$no+=1;
		if($no%2==0)
		{
			echo "<tr class=\"odd\" align=\"center\" height=25>";
		}
		else
		{
			echo "<tr class=\"even\" align=\"center\" height=25>";
		}
		echo "<td align=\"center\">$no</td>";
		echo "<td align=\"center\">$id</td>";
		echo "<td align=\"center\">$regis</td>";
		echo "<td align=\"left\">$fullname</td>";
		echo "<td align=\"center\">$article</td>";
		echo "<td align=\"center\">";
		echo "<form method=\"post\" action=\"../../admin/confirm_fp_lock.php\">";
		echo "<input type=\"hidden\" name=\"sendid\" value=\"$id#$fullname\"/>";
		echo "<input type=\"submit\" value=\"LOCK\"/>";
		echo "</form>";
		echo "</td>";
		
		echo "</tr>";
	}
	?>
	
</table>
</body>
</html>