<?php
include("../../config/config.php");
$revTranID=$_GET["revTranID"];
$datepicker=$_GET["datepicker"];
$app=$_GET["app"];
$tranActionID=$_GET["tranActionID"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
   <td>รหัสรายการเงินโอน</td>
    <td>ประเภทการนำเข้า</td>
    <td>เลขที่บัญชี</td>
	<td>สาขา</td>
    <td>วันที่และเวลาที่นำเงินเข้าธนาคาร</td>
    <td>จำนวนเงิน</td>
    <td>วันเวลาที่บันทึกรายการ</td>
</tr>
<?php
$nub = 0;
$query=pg_query("select * from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE date(\"bankRevStamp\") = '$datepicker' and \"tranActionID\"='$tranActionID'");
while($resvc=pg_fetch_array($query)){
    $n++;
    $revTranID = $resvc['revTranID'];
	$cnID = $resvc['cnID'];
	$bankRevAccID = $resvc['bankRevAccID'];
	$bankRevBranch = trim($resvc['bankRevBranch']);
	$bankRevStamp = trim($resvc['bankRevStamp']);
	$bankRevAmt = trim($resvc['bankRevAmt']);
	$doerStamp = $resvc['doerStamp'];
	$revTranStatus=$resvc['revTranStatus'];
	
	if($app==1){
		$remark = $resvc['appvXRemask'];
		$status = $resvc['appvXStatus'];
		$time=$resvc['appvXStamp'];
	}else{
		$remark = $resvc['appvYRemask'];
		$status = $resvc['appvYStatus'];
		$time=$resvc['appvYStamp'];
	}
	

	if($status==0){
		$txtx="ไม่อนุมัติ";
	}else{
		$txtx="อนุมัติ";
	}

    $i+=1;
        if($i%2==0){
            echo "<tr class=\"odd\" align=\"center\">";
        }else{
            echo "<tr class=\"even\" align=\"center\">";
        }
?> 
    <td><?php echo $revTranID; ?></td>
        <td align="center"><?php echo $cnID; ?></td>
        <td align="center"><?php echo $bankRevAccID; ?></td>
        <td><?php echo $bankRevBranch; ?></td>
        <td><?php echo $bankRevStamp; ?></td>
        <td><?php echo $bankRevAmt; ?></td>
        <td><?php echo $doerStamp; ?></td>
    </tr>
<?php
$nub++;
$old_bank = $bank_no;
$old_bank_name = $bankname;
}
?>
</table><br>
<table width="600" border="0" cellSpacing="1" cellPadding="1" bgcolor="#F4FED6" align="center">
<tr><td bgcolor="#049746" height="25" colspan="4"><font color="#FFFFFF">&nbsp;<b>ผลการตรวจ</b></font></td></tr>
<tr>
	<td align="right" width="150"><b>ผลการตรวจ :</b></td>
	<td height="50"><?php echo $txtx;?></td>
	<td align="right" width="150"><b>อนุมัติวันที่ :</b></td>
	<td height="50"><?php echo $time;?></td>
</tr>
<!--
<tr height="130">
	<td align="right" valign="top"><b>Remark :</b></td>
	<td valign="top"><textarea name="remark" cols="50" rows="5" readonly="true"><?php// echo $remark;?></textarea></td>
</tr>
-->
<tr>
	<td colspan="4" align="center" bgcolor="#FFFFFF" height="50">
		<input type="button" value="ปิด" onclick="window.close();">
	</td>
</tr>
</table>

</body>
</html>