<?php
include("../../config/config.php");

$debtID = pg_escape_string($_GET["debtID"]);

?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<div style="width:520px; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:520px; height:auto;">
		<div class="style1" align="center" id="menu" style="height:30px; padding-left:10px; padding-top:10px; padding-right:10px;">รายละเอียด<hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<?php
			$qry_fr=pg_query("
								SELECT a.*,to_char(a.\"doerStamp\", 'yyyy-mm-dd HH24:MI:SS') as \"doerStamp1\",to_char(a.\"appvStamp\", 'yyyy-mm-dd HH24:MI:SS') as \"appvStamp1\",b.\"fullname\" as \"fullname_doer\",d.\"fullname\" as \"fullname_appv\"  
								FROM \"thcap_temp_otherpay_debt\" a 
								LEFT JOIN \"Vfuser\" b on a.\"doerID\" = b.\"id_user\"
								LEFT JOIN \"Vfuser\" d on a.\"appvID\" = d.\"id_user\"
								WHERE a.\"debtID\" = '$debtID' ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$remark=$res_fr["debtRemark"];
				$doerUser=$res_fr["fullname_doer"]; // ผู้ขอยกเว้นหนี้
				$doerStamp=$res_fr["doerStamp1"]; // วันเวลาขอยกเว้นหนี้
				$appvUser=$res_fr["fullname_appv"]; // ผู้ทำรายการอนุมัติ
				$appvStamp=$res_fr["appvStamp1"]; // วันเวลาทำรายการอนุมัติ
				$contractID = $res_detail["contractID"]; // เลขที่สัญญา
			}
			
			
			echo "<table>";
			echo "<tr><td align=\"right\">ผู้ขอตั้งหนี้ : </td><td align=\"left\">$doerUser</td></tr>";
			echo "<tr><td align=\"right\">วันเวลาที่ขอตั้งหนี้ : </td><td align=\"left\">$doerStamp</td></tr>";
			echo "<tr><td align=\"right\">ผู้อนุมัติตั้งหนี้ : </td><td align=\"left\">$appvUser</td></tr>";
			echo "<tr><td align=\"right\">วันเวลาที่อนุมัติตั้งหนี้ : </td><td align=\"left\">$appvStamp</td></tr>";
			echo "<tr><td align=\"right\" valign=\"top\">เหตุผลในการขอตั้งหนี้ : </td><td align=\"left\"><textarea name=\"textdetail\" cols=\"45\" rows=\"7\" readonly>$remark</textarea></td></tr>";
			?>
			</table>
			<br><br><center><input type="button" value="    ปิด    " onclick="window.close();"></center>
		</div>
	</div>
</div>