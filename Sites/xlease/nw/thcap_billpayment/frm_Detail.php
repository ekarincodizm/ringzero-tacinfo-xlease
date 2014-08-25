<?php
session_start();
include("../../config/config.php");
$filename=$_GET["filename"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) Load Bill Payment</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<div style="text-align:center;"><h2>แสดงรายการที่ Load เข้าระบบ</h2></div>
<div style="padding:0 0 2px;"><b>หมายเหตุ</b> <span style="background-color:#FFE4E1;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <font color=red> คือ รายการ bankrevref1 และ bankrevref2 ไม่สมบูรณ์</font></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
   <td>วันที่โอน</td>
    <td>เวลาที่โอน</td>
	<td>จำนวนเงิน</td>
    <td>สาขาที่จ่าย</td>
	<td>terminal_id</td>
    <td>terminal_sq_no</td>
    <td>bankrevref1</td>
    <td>bankrevref2</td>
	<td>ชื่อลูกค้า</td>
	<td>รหัสธนาคาร</td>
	<td>เลขบัญชีธนาคาร</td>
	<td>tran_type</td>
	<td>เลขที่เช็ค</td>	
	<td>สถานะรายการ</td>
</tr>
<?php
$query=pg_query("select * from finance.\"Vthcap_receive_billpayment\" WHERE filename = '$filename'");
$numrows=pg_num_rows($query);
while($resvc=pg_fetch_array($query)){
    $n++;
    $tranfer_date = $resvc['tranfer_date'];
	$tranfer_time = $resvc['tranfer_time'];
	$pay_bank_branch = $resvc['pay_bank_branch'];
	$terminal_id = trim($resvc['terminal_id']);
	$terminal_sq_no = trim($resvc['terminal_sq_no']);
	$bankrevref1 = trim($resvc['bankrevref1']);
	$bankrevref2 = $resvc['bankrevref2'];
	$amt=$resvc['amt'];
	$cusname=$resvc['cusname'];
	$bank_no=$resvc['bank_no'];
	$tran_type=$resvc['tran_type'];
	$pay_cheque_no=$resvc['pay_cheque_no'];
	$statusname=$resvc['statusname'];
	$bank_acc=$resvc['bank_acc'];
	$statusupload=$resvc['statusupload']; //สถานะความสมบูรณ์ของข้อมูล
	
    $i+=1;
	if($i%2==0){
		echo "<tr class=\"odd\" align=\"center\">";
	}else{
		echo "<tr class=\"even\" align=\"center\">";
	}
	if($statusupload==0){
		echo "<tr bgcolor=\"#FFE4E1\" align=\"center\">";
	}
?> 
    <td><?php echo $tranfer_date; ?></td>
        <td align="center"><?php echo $tranfer_time; ?></td>
        <td align="center"><?php echo number_format($amt,2);; ?></td>
        <td><?php echo $pay_bank_branch; ?></td>
        <td><?php echo $terminal_id; ?></td>
        <td><?php echo $terminal_sq_no; ?></td>
        <td><?php echo $bankrevref1; ?></td>
		<td><?php echo $bankrevref2; ?></td>
		<td><?php echo $cusname; ?></td>
		<td><?php echo $bank_no; ?></td>
		<td><?php echo $bank_acc; ?></td>
		<td><?php echo $tran_type; ?></td>
		<td><?php echo $pay_cheque_no; ?></td>
		<td><?php echo $statusname; ?></td>
		
    </tr>
<?php
}
if($numrows==0){
	echo "<tr><td colspan=14 height=50 align=center><b>--ไม่มีข้อมูล--</b></td></tr>";
}
?>
</table>
<div style="padding-top:50px;text-align:center;"><input type="button" value="ปิด" onclick="window.close();"></div>

</body>
</html>