<?php
include("../../config/config.php");
$revTranID = $_GET["revtran"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>
    
<fieldset>
<form name="frm1" id="frm1" action="api.php" method="post">
<table width="450" cellpadding="0" cellspacing="1" border="0">
<?php 
$qry=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\"='$revTranID' and \"revTranStatus\"='9' ");
$numrow=pg_num_rows($qry);

if($numrow>0){ //แสดงว่าพบรายการนี้ในฐานข้อมูล
	if($resvc=pg_fetch_array($qry)){
		if($resvc["cancel"]=='f'){ //กรณีรายการนี้ถูกลบไปก่อนหน้านี้แล้ว
			echo "<tr><td align=\"left\" colspan=\"5\">รหัสรายการเงินโอน : <font color=red><b>$revTranID</b></font> ถูกลบไปก่อนหน้านี้แล้ว</td></tr>";
			?>
			<tr>
				<td colspan="5" align="center">
					<input type="button" id="cancelvalue" onclick="$('#dialog').remove();location.href = 'frm_Index_finance.php';" value="ปิด" style="width:100px">
				</td>
			</tr>
			<?php
		}else{ //กรณีรายการนี้ยังไม่ถูกลบ
			echo "
			<tr>
				<td align=\"left\" colspan=\"5\">รหัสรายการเงินโอน : <font color=red><b>$revTranID</b></font></td>
			</tr>
			<tr style=\"font-weight:bold;\" valign=\"top\" bgcolor=\"#79BCFF\" align=\"center\">
				<td>วันที่โอน</td>
				<td>ประเภทการนำเข้า</td>
				<td>เลขที่บัญชี</td>
				<td>สาขา</td>
				<td>จำนวนเงิน</td>
			</tr>
			<tr bgcolor=#FFFFFF>
				<td align=center>".trim(substr($resvc['bankRevStamp'],0,10))."</td>
				<td align=center>$resvc[cnID]</td>
				<td>$resvc[BAccount]</td>
				<td>".trim($resvc['bankRevBranch'])."</td>
				<td align=right>".number_format($resvc['bankRevAmt'],2)."</td>
			</tr>";
			?>
			<tr><td colspan="5"><br></td></tr>
			<tr>
				<td colspan="5" align="center">
					<input type="hidden" name="revTranID" id="revTranID" value="<?php echo $revTranID;?>">
					<input type="hidden" name="cmd" id="cmd" value="anonymous">
					<input type="submit" name="btn_save" id="btn_save" value="ยืนยัน"  style="width:100px"/>		  			
					<input type="button" id="cancelvalue" onclick="$('#dialog').remove()" value="ยกเลิก" style="width:100px">
				</td>
			</tr>
			<?php
		}
	}
	
}else{ //ไม่พบรายการนี้จากฐานข้อมูล อาจถูกลบออกจากฐานข้อมูลแล้ว
	echo "<tr><td align=\"left\" colspan=\"5\">ไม่พบรหัสรายการเงินโอน : <font color=red><b>$revTranID</b></font> อาจตรวจสอบรายการหรือถูกลบจากฐานข้อมูลแล้ว </td><tr>";
	?>
	<tr>
		<td colspan="5" align="center">
			<input type="button" id="cancelvalue" onclick="$('#dialog').remove();location.href = 'frm_Index_finance.php';" value="ปิด" style="width:100px">
		</td>
	</tr>
	<?php
}
?>
</table>
</form>
</fieldset>
</html>