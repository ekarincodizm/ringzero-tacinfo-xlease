<?php
include("../../config/config.php");

$corpID = $_GET["corpID"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายละเอียดบัญชีธนาคารเดิม</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
	
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<body>
<center>
<h2>บัญชีธนาคารเดิม</h2>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
						<tr align="center" bgcolor="#79BCFF">
							<th width="30"></th>
							<th>เลขที่บัญชี</th>
							<th>ชื่อบัญชี</th>
							<th>ธนาคาร</th>
							<th>สาขา</th>
							<th>ประเภทบัญชี</th>
						</tr>
						<?php
						$query = pg_query("select * from public.\"th_corp_acc\" where \"corpID\" = '$corpID' ");
						$numrows = pg_num_rows($query);
						$i=0;
						while($result = pg_fetch_array($query))
						{
							$i++;
							$acc_Number = $result["acc_Number"]; // เลขที่บัญชี
							$bankID = $result["bankID"]; // รหัสธนาคาร
							$acc_Name = $result["acc_Name"]; // ชื่อบัญชี
							$branch = $result["branch"]; // สาขา
							$acc_type = $result["acc_type"]; // ประเภทบัญชี
							
							$query_bank = pg_query("select * from public.\"BankProfile\" where \"bankID\" = '$bankID' ");
							while($resultBank = pg_fetch_array($query_bank))
							{
								$bankName = $resultBank["bankName"]; // ชื่อธนาคาร
							}
							
							if($i%2==0){
								echo "<tr class=\"odd\">";
							}else{
								echo "<tr class=\"even\">";
							}
							
							echo "<td align=\"center\">$i</td>";
							echo "<td align=\"center\">$acc_Number</td>";
							echo "<td>$acc_Name</td>";
							echo "<td>$bankName</td>";
							echo "<td>$branch</td>";
							echo "<td align=\"center\">$acc_type</td>";
							echo "</tr>";
						}
						
						if($numrows==0){
							echo "<tr bgcolor=#FFFFFF><td colspan=6 align=center><b>ไม่พบบัญชีธนาคาร</b></td><tr>";
						}
						?>
		</table>
		<br>
		<input type="button" value="ปิด" onclick="javascript:window.close();">
</center>
</body>
</html>