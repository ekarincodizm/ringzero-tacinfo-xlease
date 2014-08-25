<?php
include("../../config/config.php");

$debtID = $_GET["debtID"];

?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<div style="width:450px; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:450px; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div class="style1" align="center" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">เหตุผลในการขอยกเลิก<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<?php
			$qry_fr=pg_query("select * from \"thcap_temp_except_debt\" where \"debtID\" = '$debtID' ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$remark=$res_fr["remark"];
			}
			
			$qry_detail=pg_query("select * from \"thcap_v_otherpay_debt_realother\" where \"debtID\" = '$debtID' ");
			while($res_detail=pg_fetch_array($qry_detail))
			{
				$contractID = $res_detail["contractID"];
			}
			
			echo "<table>";
			echo "<tr><td align=\"right\">เลขที่สัญญา : </td><td align=\"left\">$contractID</td></tr>";
			echo "<tr><td align=\"right\">รหัสหนี้ : </td><td align=\"left\">$debtID</td></tr>";
			echo "<tr><td align=\"right\" valign=\"top\">เหตุผล : </td><td align=\"left\"><textarea name=\"textdetail\" cols=\"45\" rows=\"7\" readonly>$remark</textarea></td></tr>";
			?>
			</table>
			<br><br><center><input type="button" value="    ปิด    " onclick="window.close();"></center>
		</div>
	</div>
</div>