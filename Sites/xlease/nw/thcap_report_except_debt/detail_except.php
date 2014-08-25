<?php
include("../../config/config.php");

$debtID = $_GET["debtID"];

?>

<div style="width:520px; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:520px; height:auto;">
		<div class="style1" align="center" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">รายละเอียด<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<?php
			$qry_fr=pg_query("select * from \"thcap_temp_except_debt\" where \"debtID\" = '$debtID' ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$remark=$res_fr["remark"];
				$doerUser=$res_fr["doerUser"]; // ผู้ขอยกเว้นหนี้
				$doerStamp=$res_fr["doerStamp"]; // วันเวลาขอยกเว้นหนี้
				$appvUser=$res_fr["appvUser"]; // ผู้ทำรายการอนุมัติ
				$appvStamp=$res_fr["appvStamp"]; // วันเวลาทำรายการอนุมัติ
			}
			
			$qry_detail=pg_query("select * from \"thcap_temp_otherpay_debt\" where \"debtID\" = '$debtID' ");
			while($res_detail=pg_fetch_array($qry_detail))
			{
				$contractID = $res_detail["contractID"]; // เลขที่สัญญา
			}
			
			echo "<table>";
			echo "<tr><td align=\"right\">ผู้ขอยกเว้นหนี้ : </td><td align=\"left\">$doerUser</td></tr>";
			echo "<tr><td align=\"right\">วันเวลาที่ขอยกเว้นหนี้ : </td><td align=\"left\">$doerStamp</td></tr>";
			echo "<tr><td align=\"right\">ผู้อนุมัติยกเว้นหนี้ : </td><td align=\"left\">$appvUser</td></tr>";
			echo "<tr><td align=\"right\">วันเวลาที่อนุมัติยกเว้นหนี้ : </td><td align=\"left\">$appvStamp</td></tr>";
			echo "<tr><td align=\"right\" valign=\"top\">เหตุผลในการขอยกเว้นหนี้ : </td><td align=\"left\"><textarea name=\"textdetail\" cols=\"45\" rows=\"7\" readonly>$remark</textarea></td></tr>";
			?>
			</table>
			<br><br><center><input type="button" value="    ปิด    " onclick="window.close();"></center>
		</div>
	</div>
</div>