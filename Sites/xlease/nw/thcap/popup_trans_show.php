<?php 
include("../../config/config.php");
$revTranID = $_GET['revTranID']; //รหัสเงินโอน

$query=pg_query("select * from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"revTranID\" = '$revTranID'");
$nub = pg_num_rows($query);
if($resvc=pg_fetch_array($query)){
	$doerStamp = $resvc['doerStamp']; //วันเวลาที่ทำรายการ				
	$fullnameX = $resvc['fullnameX']; //ชื่อผู้อนุมัติบัญชี
	$appvXStamp = $resvc['appvXStamp']; //วันเวลาที่บัญชีอนุมัติ
	$fullnameY = $resvc['fullnameY']; //ชื่อผู้อนุมัติการเงิน
	$appvYStamp = $resvc['appvYStamp']; //วันเวลาที่การเงินอนุมัติ
	$appvXStatus = $resvc['appvXStatus'];
	if($fullnameX == ""){ $fullnameX = "-"; }
	if($fullnameY == ""){ $fullnameY = "-"; }
				
	if($appvXStatus==""){
		$appvXStatus=9;
	}else{
		$appvXStatus=$appvXStatus;
	}
				
	if($appvXStatus==9){
		$txtx="รออนุมัติ";
	}else if($appvXStatus==0){
		$txtx="ไม่อนุมัติ";
	}else if($appvXStatus==1){
		$txtx="อนุมัติ";
	}
	$appvYStatus = $resvc['appvYStatus'];
	if($appvYStatus==""){
		$appvYStatus=9;
	}else{
		$appvYStatus=$appvYStatus;
	}
	if($appvYStatus=="9"){
		$txty="รออนุมัติ";
	}else if($appvYStatus==1){
		$txty="อนุมัติ";
	}else if($appvYStatus==2){
		$txty="เป็นรายการเงินที่ไม่ใช่ชำระค่าสินค้าหรือบริการ";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>แสดงรายละเอียดเงินโอนเพิ่มเติม</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>

<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div style="text-align:center"><h2>รายละเอียดเพิ่มเติม</h2></div>
<table width="80%" cellSpacing="1" cellPadding="1" bgcolor="#C1CDC1" align="center">
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" width="30%" bgcolor="#EEEEE0"><b>วันเวลาที่บันทึกรายการ :</b></td>
		<td> <?php echo $doerStamp; ?></b></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>ผู้ตรวจสอบด้านบัญชี :</b></td>
		<td> <?php echo $fullnameX; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>สถานะการตรวจสอบฝ่ายบัญชี :</b></td>
		<td> <?php echo $txtx; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>วันเวลาที่ฝ่ายบัญชีตรวจสอบ :</b></td>
		<td> <?php echo $appvXStamp; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>ผู้ตรวจสอบด้านการเงิน :</b></td>
		<td> <?php echo $fullnameY; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>สถานะการตรวจสอบฝ่ายการเงิน :</b></td>
		<td> <?php echo $txty; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>วันเวลาที่ฝ่ายการเงินตรวจสอบ :</b></td>
		<td> <?php echo $appvYStamp; ?></td>
	</tr>
</table>
<div style="text-align:center;padding:20px"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>
</body>
</html>