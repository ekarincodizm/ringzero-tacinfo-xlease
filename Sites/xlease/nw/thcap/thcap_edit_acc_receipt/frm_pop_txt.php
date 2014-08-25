<?php
include("../../../config/config.php");

$revID = $_GET['revID'];

//กรณีมาจากเมนู (THCAP) ตรวจสอบประวัติทำรายการเงินโอน
$auto_id = $_GET['auto_id'];
$stsshow = $_GET['stsshow'];

if($stsshow==1){
	$sql2 = pg_query("SELECT \"remark\",\"revTranID\" FROM finance.thcap_receive_transfer_log where \"auto_id\" = '$auto_id' ");
	list($result2,$revID) = pg_fetch_array($sql2);
}else{
	$sql2 = pg_query("SELECT \"appvXRemask\" FROM finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\" = '$revID' ");
	list($result2) = pg_fetch_array($sql2);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>
<body bgcolor="#DDDDDD">
	<table align="center" width="500px" >
		<tr>
			<td align="center">
				<font size="5px"><b><?php echo $revID ?></b></font>
				<br>
				<?php
				if($stsshow!=1){
				?>
				เหตุผลการไม่อนุมัติ..
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td align="center">
				<textarea cols="70" rows="5" Readonly><?php echo $result2 ?></textarea>
			</td>
		</tr>
	</table>	
</body>
</html>