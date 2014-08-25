<?php
session_start();
include("../../config/config.php");
$sbj_serial=$_GET["sbj_serial"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) LOAD STATEMENT BANK</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>

<script language="javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div style="text-align:center;"><h2>แสดงรายการที่ Load เข้าระบบ</h2></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
   <td>วันที่รายการมีผล</td>
    <td>รายละเอียดการทำรายการ</td>
	<td>เลขที่เช็ค</td>
	<td>จำนวนเงินที่หักบัญชี</td>
    <td>จำนวนเงินเข้าบัญชี</td>
    <td>ยอดคงเหลือ</td>
    <td>หมายเลข</td>
	<td>สาขาที่ให้บริการ</td>
	<td>วันที่สร้างรายการ</td>
	<td>รหัสเงินโอน</td>
</tr>
<?php
$query=pg_query("select *,date(sbr_bankcreate) as datecreate from finance.thcap_statement_bank_raw WHERE sbr_refjob = '$sbj_serial' ORDER BY sbr_serial");
$numrows=pg_num_rows($query);
$sumwith=0;
$sumdep=0;
while($resvc=pg_fetch_array($query)){
    $n++;
    $sbr_receivedate = $resvc['sbr_receivedate']; //วันที่รายการมีผล
	$sbr_details = $resvc['sbr_details']; //รายละเอียดการทำรายการ
	$sbr_chqno = $resvc['sbr_chqno']; //เลขที่เช็ค
	$sbr_amtwithdraw = $resvc['sbr_amtwithdraw'];//จำนวนเงินที่หักบัญชี
	$sbr_amtdeposit = $resvc['sbr_amtdeposit']; //จำนวนเงินเข้าบัญชี
	$sbr_amtoutstanding = $resvc['sbr_amtoutstanding']; //ยอดคงเหลือ
	$sbr_counterservice = $resvc['sbr_counterservice']; //หมายเลข
	$sbr_bankbranch=$resvc['sbr_bankbranch']; //สาขาที่ให้บริการ
	$datecreate=$resvc['datecreate']; //วันที่สร้างรายการ
	$revTranID=$resvc['revtranferID']; //รหัสเงินโอน
		
	$sumwith+=$sbr_amtwithdraw;
	$sumdep+=$sbr_amtdeposit;
	
    $i+=1;
	if($i%2==0){
		echo "<tr class=\"odd\" align=\"center\">";
	}else{
		echo "<tr class=\"even\" align=\"center\">";
	}
?> 
		<td><?php echo $sbr_receivedate; ?></td>
        <td align="left"><?php echo $sbr_details; ?></td>
        <td><?php echo $sbr_chqno; ?></td>
        <td align="right"><?php echo number_format($sbr_amtwithdraw,2); ?></td>
        <td align="right"><?php echo number_format($sbr_amtdeposit,2); ?></td>
        <td><?php echo number_format($sbr_amtoutstanding,2); ?></td>
        <td><?php echo $sbr_counterservice; ?></td>
		<td><?php echo $sbr_bankbranch; ?></td>
		<td><?php echo $datecreate; ?></td>
		<td><?php echo "<a onclick=\"javascript:popU('../thcap/frm_transpay_detail.php?revTranID=$revTranID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=350')\" style=\"cursor:pointer;\" ><u>$revTranID</u></a>"; ?></td>
    </tr>
<?php
}
if($numrows==0){
	echo "<tr><td colspan=10 height=50 align=center><b>--ไม่มีข้อมูล--</b></td></tr>";
}else{
	echo "<tr style=\"font-weight:bold\" bgcolor=\"#FFCCCC\" align=\"right\">
		<td colspan=3>รวม</td>
        <td>".number_format($sumwith,2)."</td>
        <td>".number_format($sumdep,2)."</td>
        <td colspan=5></td>
		</tr>";
}
?>
</table>
<div style="padding-top:50px;text-align:center;"><input type="button" value="ปิด" onclick="window.close();"></div>

</body>
</html>