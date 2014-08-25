<?php
// ส่วนติดต่อกับฐานข้อมูล    
include("../../config/config.php");
$contractID = $_GET["contractID"]; // เลขที่สัญญา
$conType = $_GET["conType"]; // เลขที่สัญญา
$main = $_GET["main"]; // เลขที่สัญญา
?>
	
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css"></link>

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<?php
if($conType == "FA" || $conType == "PN")
{
	$qry_chkContractRef = pg_query("SELECT * FROM \"vthcap_ContactCus_detail\" WHERE \"contractID\" = '$contractID' and (\"CusState\" = '0' or \"CusState\" = '1') and \"CusID\" = '$main' ");
}
else
{
	$qry_chkContractRef = pg_query("SELECT * FROM \"vthcap_ContactCus_detail\" WHERE \"contractID\" = '$contractID' and \"CusState\" = '0' and \"CusID\" = '$main' ");
}
// ตรวจสอบก่อนว่าสัญญาดังกล่าวใช้ได้หรือไม่
$row_chkContractRef = pg_num_rows($qry_chkContractRef);

if($conType == "")
{
	echo "<center><font color=\"#FF0000\">กรุณาเลือกประเภทสินเชื่อก่อน!!</font></center>";
	echo "<input type=\"text\" name=\"chkContractRef\" id=\"chkContractRef\" value=\"2\">";
}
elseif($contractID == "")
{
	echo "<input type=\"text\" name=\"chkContractRef\" id=\"chkContractRef\" value=\"2\" readonly>";
}
elseif($row_chkContractRef == 0)
{
	echo "<center><font color=\"#FF0000\">สัญญาวงเงินที่เลือกไม่สามารถใช้ได้!! กรุณาเลือกสัญญาวงเงินที่จะใช้ใหม่</font></center>";
	echo "<input type=\"text\" name=\"chkContractRef\" id=\"chkContractRef\" value=\"3\" readonly>";
}
else
{
?>
	<table align="center">
		<tr>
			<td colspan="2">
				<table width="850"  cellspacing="2" cellpadding="2" style="margin-top:1px" align="center" bgcolor="#DDFFAA" id="tableadd">
					<?php // หา ผู้กู้ร่วม
						if($conType == "FA" || $conType == "PN") // ถ้าเป็นสินชื่อประเภท FA หรือ PN ไม่ต้องเอาผู้กู้ร่วมไป
						{
							echo "<tr><td align=\"right\" width=\"35%\">ผู้กู้ร่วม : </td>";
							echo "<td><input NAME=\"join[]\" ID=\"join1\" type=\"text\" size=\"50\" readonly style=\"background:#CCCCCC;\"></td></tr>";
						}
						else
						{
							$qry_cusJoin = pg_query("SELECT * FROM \"vthcap_ContactCus_detail\" WHERE \"contractID\" = '$contractID' and \"CusState\" = '1' ");
							$row_cusJoin = pg_num_rows($qry_cusJoin);
							if($row_cusJoin > 0)
							{
								$J = 0;
								while($res_cusJoin = pg_fetch_array($qry_cusJoin))
								{
									$J++;
									$CusID = $res_cusJoin["CusID"];
									$thcap_fullname = $res_cusJoin["thcap_fullname"];
									
									echo "<tr><td align=\"right\" width=\"35%\">ผู้กู้ร่วม คนที่ $J : </td><td><input NAME=\"join[]\" ID=\"join$J\" type=\"text\" value=\"$CusID#$thcap_fullname\" size=\"50\" readonly style=\"background:#CCCCCC;\"></td></tr>";
								}
							}
							else
							{
								echo "<tr><td align=\"right\" width=\"35%\">ผู้กู้ร่วม : </td>";
								echo "<td><input NAME=\"join[]\" ID=\"join1\" type=\"text\" size=\"50\" readonly style=\"background:#CCCCCC;\"></td></tr>";
							}
						}
					?>
					<?php // หา ผู้ค้ำประกัน
						$qry_cusGuarantor = pg_query("SELECT * FROM \"vthcap_ContactCus_detail\" WHERE \"contractID\" = '$contractID' and \"CusState\" = '2' ");
						$row_cusGuarantor = pg_num_rows($qry_cusGuarantor);
						if($row_cusGuarantor > 0)
						{
							$G = 0;
							while($res_cusGuarantor = pg_fetch_array($qry_cusGuarantor))
							{
								$G++;
								$CusID = $res_cusGuarantor["CusID"];
								$thcap_fullname = $res_cusGuarantor["thcap_fullname"];
								
								echo "<tr><td align=\"right\" width=\"35%\">ผู้ค้ำประกัน คนที่ $G : </td><td><input NAME=\"guarantor[]\" ID=\"guarantor$G\" type=\"text\" value=\"$CusID#$thcap_fullname\" size=\"50\" readonly style=\"background:#CCCCCC;\"></td></tr>";
							}
						}
						else
						{
							echo "<tr><td align=\"right\" width=\"35%\">ผู้ค้ำประกัน : </td>";
							echo "<td><input NAME=\"guarantor[]\" ID=\"guarantor1\" type=\"text\" size=\"50\" readonly style=\"background:#CCCCCC;\"></td></tr>";
						}
					?>
				</table>
			</td>
		</tr>
	</table>
<?php
	echo "<input type=\"text\" name=\"chkContractRef\" id=\"chkContractRef\" value=\"1\" readonly>";
}
?>

<script>
	document.getElementById("chkContractRef").style.visibility = 'hidden';
</script>