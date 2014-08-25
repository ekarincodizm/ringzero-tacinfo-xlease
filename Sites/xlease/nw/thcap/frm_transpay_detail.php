<?php
session_start();
include("../../config/config.php");
$revTranID=pg_escape_string($_GET['revTranID']);



//ดึงข้อมูลรายละเอียดเงินโอน
	$query=pg_query("	SELECT a.*,b.\"fullname\" AS \"doername\" 
						FROM \"finance\".\"V_thcap_receive_transfer_tsfAppv\" a
						LEFT JOIN \"Vfuser\" b ON a.\"doerID\" = b.\"id_user\"
						WHERE  \"revTranID\" = '$revTranID' ");
	$resvc=pg_fetch_array($query);
				
				$revTranID = $resvc['revTranID'];
				$cnID = $resvc['cnID'];
				$revTranStatus = $resvc['revTranStatus'];
				$appvXStatus = $resvc['appvXStatus'];
				$appvYStatus = $resvc['appvYStatus'];
				if($revTranStatus==9){
					if($appvXStatus == "" && $appvYStatus == ""){
						$txtstatus="รอบัญชีอนุมัติ";
					}else if($appvXStatus != "" && $appvYStatus == ""){
						$txtstatus="รอการเงินอนุมัติ";
					}
					echo "<tr class=\"wait\" align=\"center\">";
				}else if($revTranStatus==4){
					$txtstatus="<font color=blue>ไม่อนุมัติและรอการแก้ไข</font>";
					echo "<tr class=\"wait\" align=\"center\">";
				}else if($revTranStatus==2){
					$txtstatus="<font color=blue>เงินที่ไม่ใช่ค่าสินค้าหรือบริการ</font>";
					echo "<tr class=\"wait\" align=\"center\">";
				}else if($revTranStatus==1){
					$txtstatus="<font color=blue>รอนำเงินไปใช้</font>";
					$i+=1;
					if($i%2==0){
						echo "<tr class=\"odd\" align=\"center\">";
					}else{
						echo "<tr class=\"even\" align=\"center\">";
					}
				}else if($revTranStatus==3){
					$txtstatus="<font color=blue><a style=\"cursor:pointer \" onclick=\"popU('popup_trans_receipt.php?revTranID=$revTranID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><u>ใช้เงินแล้ว</u></a></font>";
					$i+=1;
					if($i%2==0){
						echo "<tr class=\"odd\" align=\"center\">";
					}else{
						echo "<tr class=\"even\" align=\"center\">";
					}
				}else if($revTranStatus==5){
					$txtstatus="<font color=blue>รออนุมัติคีย์ผ่านระบบ</font>";
					echo "<tr class=\"wait\" align=\"center\">";
				}else if($revTranStatus==6){
					$txtstatus="<a style=\"cursor:pointer \" onclick=\"popU('popup_trans_receipt.php?revTranID=$revTranID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><font color=\"red\"><u>ใช้เงินแล้วแต่ยังไม่ครบ</u></font></a>";
					$i+=1;
					if($i%2==0){
						echo "<tr class=\"odd\" align=\"center\">";
					}else{
						echo "<tr class=\"even\" align=\"center\">";
					}
				}else{
					$txtstatus = "";
				}
				$BAccount = $resvc['BAccount'];
				$bankRevBranch = trim($resvc['bankRevBranch']);
				$bankRevStamp = trim($resvc['bankRevStamp']);
				$bankRevAmt = trim($resvc['bankRevAmt']);
				$doerStamp = $resvc['doerStamp'];				
				$fullnameX = $resvc['fullnameX'];
				$fullnameY = $resvc['fullnameY'];
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
				}else if($appvYStatus==0){
					$txty="ไม่อนุมัติ";
				}else if($appvYStatus==1){
					$txty="อนุมัติ";
				}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<title>รายละเอียดเงินโอน</title>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>

<div style="text-align:center"><h2>รายละเอียดเงินโอน</h2></div>
<div><b>รหัสรายการเงินโอน : <font color="red"><?php echo $revTranID; ?></font></b></div>
<div><b>ประเภทการนำเข้า : </b><?php echo $cnID; ?></div> 
<div><b>สถานะเงินโอน : </b><?php echo $txtstatus; ?></div>
<div><b>ผู้ตรวจสอบด้านบัญชี :</b> <?php echo $fullnameX;?></div>
<div><b>ผู้ตรวจสอบด้านการเงิน :</b> <?php echo $fullnameY;?></div>
<div style="padding-top:15px;"></div>
<table width="100%" cellSpacing="1" cellPadding="1" bgcolor="#EEEED1" align="center">
<tr bgcolor="#CDC1C5" style="font-weight:bold;"><th>เลขที่บัญชี</th><th>รหัสสาขาที่โอน</th><th>วันที่และเวลาที่นำเงินเข้าธนาคาร</th><th>จำนวนเงิน</th><th>วันเวลาที่บันทึกรายการ VAT</th></tr>
<tr align="center" bgcolor="#EEE0E5">
	<td  ><?php echo $BAccount; ?></td>
	<td  ><?php echo $bankRevBranch; ?></td>
	<td  ><?php echo $bankRevStamp; ?></td>
	<td align="right" ><?php echo number_format($bankRevAmt,2); ?></td>
	<td  ><?php echo $doerStamp; ?></td>
</tr>
<table>
<br>
<div><b>ผู้บันทึกเงินโอน :</b> <?php echo $resvc['doername'];?></div>
<div><b>วันเวลาที่บันทึกเงินโอน :</b> <?php echo $resvc['doerStamp'];?></div>
<div style="text-align:center;padding:20px"><input type="button" onclick="window.close();" value="ปิดหน้านี้" style="width:70px;height:50px;"></div>

</body>
</html>