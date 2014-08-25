<?php
include("../../config/config.php");
include("../function/emplevel.php");

$debtID = pg_escape_string($_GET["debtID"]);
$show = pg_escape_string($_GET["show"]);

$id_user = $_SESSION["av_iduser"];

$id_level = emplevel($id_user);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>

<script language=javascript>
function popU(U,N,T) {
	newWindow = window.open(U, N, T);
}
</script>

</head>
<div style="width:95%; height:auto; margin-left:auto; margin-right:auto;">
	<div id="warppage" style="width:100%; height:auto;">
		<div id="headerpage" style="height:10px; text-align:center"></div>
		<div align="center"><h2>เหตุผลในการขอยกเลิก</h2><hr/></div>
		<div style="height:auto; padding-left:10px; padding-right:10px;"><br />
			<?php
			$qry_fr=pg_query("select * from \"thcap_temp_except_debt\" where \"debtID\" = '$debtID' ");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr))
			{
				$debtID=$res_fr["debtID"];
				$doerUser=$res_fr["doerUser"];
				$doerStamp=$res_fr["doerStamp"];
				$remark=$res_fr["remark"];
			}
			
			// หาชื่อผู้ทำรายการขอยกเว้นหนี้
			$sqlNameUser = pg_query("SELECT  fullname  FROM \"Vfuser\" where username = '$doerUser'");
			$fullnameUser = pg_fetch_result($sqlNameUser,0);
			
			$qry_detail=pg_query("select * from \"thcap_v_otherpay_debt_realother\" where \"debtID\" = '$debtID' ");
			while($res_detail=pg_fetch_array($qry_detail))
			{
				$typePayID = $res_detail["typePayID"];
				$typePayRefValue = $res_detail["typePayRefValue"];
				$typePayRefDate = $res_detail["typePayRefDate"];
				$typePayAmt = $res_detail["typePayAmt"];
				$typePayLeft = $res_detail["typePayLeft"]; // หนี้ค้างชำระปัจจุบัน
				$contractID = $res_detail["contractID"];
			}
			
			// หารายละเอียดค่าใช้จ่ายนั้นๆ
			$qry_tpDesc = pg_query("select * from account.\"thcap_typePay\" where \"tpID\" = '$typePayID' ");
			while($res_tpDesc = pg_fetch_array($qry_tpDesc))
			{
				$tpDescShow = $res_tpDesc["tpDesc"];
			}
			
			echo "<center><table>";
			echo "<tr><td align=\"right\"><b>เลขที่สัญญา : </b></td><td align=\"left\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>$contractID</u></font></span></td></tr>";
			echo "<tr><td align=\"right\"><b>รหัสประเภทค่าใช้จ่าย : </b></td><td align=\"left\">$typePayID</td></tr>";
			echo "<tr><td align=\"right\"><b>รายละเอียดค่าใช้จ่าย : </b></td><td align=\"left\">$tpDescShow</td></tr>";
			echo "<tr><td align=\"right\"><b>ค่าอ้างอิงของค่าใช้จ่าย : </b></td><td align=\"left\">$typePayRefValue</td></tr>";
			echo "<tr><td align=\"right\"><b>วันที่ตั้งหนี้ : </b></td><td align=\"left\">$typePayRefDate</td></tr>";
			echo "<tr><td align=\"right\"><b>จำนวนหนี้ : </b></td><td align=\"left\">".number_format($typePayLeft,2)."</td></tr>";
			echo "<tr><td align=\"right\"><b>ผู้ขอยกเว้นหนี้ : </b></td><td align=\"left\">$fullnameUser</td></tr>";
			echo "<tr><td align=\"right\"><b>วันเวลาขอยกเว้นหนี้ : </b></td><td align=\"left\">$doerStamp</td></tr>";
			echo "<tr><td align=\"right\" valign=\"top\"><b>เหตุผล : </b></td><td align=\"left\"><textarea name=\"textdetail\" cols=\"45\" rows=\"7\" readonly>$remark</textarea></td></tr>";
			echo "</table></center>";
			
			// หา tpAccrual และ tpAmortize
			$query_tp = pg_query("select \"tpAccrual\", \"tpAmortize\" from account.\"thcap_typePay_acc\" where \"tpID\" = '$typePayID' ");
			$tpAccrual = pg_result($query_tp,0);
			$tpAmortize = pg_result($query_tp,1);
			
			if($show=='1')
			{ ?>
			<form method="post" action="process_approve.php">
				<center>
					<input type="hidden" name="debtID" id="debtID" value="<?php echo $debtID; ?>">
					<br>
					<?php if($tpAccrual != "" || $tpAmortize != "") { ?>
						<font color="red">!โปรดระวัง : การตั้งหนี้รายการนี้มีการบันทึกเข้าระบบบัญชีเรียบร้อยแล้ว กรุณาติดต่อผู้ดูแลระบบ หรือ ฝ่ายบัญชีก่อนทำรายการ</font>
						<br><br>
					<?php } ?>
					<input name="appv" type="submit" value="อนุมัติ" <?php if(($tpAccrual != "" || $tpAmortize != "") && $id_level > 1) {echo "disabled title=\"คุณไม่มีสิทธิอนุมัติรายการ ต้องมี level <= 1\" ";} ?> />
					<input name="unappv" type="submit" value="ไม่อนุมัติ" />
				</center>
			</form>
			<?php }?>
			<br><center><input type="button" value="    ปิด    " onclick="window.close();"></center>
		</div>
	</div>
</div>