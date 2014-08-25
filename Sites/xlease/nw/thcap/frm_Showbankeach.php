<?php
session_start();
include("../../config/config.php");

$BID=$_GET["BID"];
$datemain=$_GET["datemain"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แสดงรายการที่บัญชีอนุมัติแล้ว</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<style type="text/css">
.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
.sum{
    background-color:#FFC0C0;
    font-size:12px
}
</style>
</head>
<body id="mm">
<?php
$qry_acc = pg_query("select \"BAccount\",\"BName\" from \"BankInt\" where \"BID\"='$BID'");
list($BAccount,$BName)=pg_fetch_array($qry_acc);
?>
<div align="center"><h2>-รายการที่เกี่ยวข้อง-</h2></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr><td colspan="7"><?php echo "<b>$BAccount-$BName วันที่ : $datemain</b>";?></td></tr>
<tr style="font-weight:bold;" valign="top" bgcolor="#B4CDCD" align="center">
	<td>เวลาที่โอน</td>
	<td>รหัสรายการเงินโอน</td>
    <td>ประเภทการนำเข้า</td>
	<td>สาขา</td>
    <td>วันที่ทำรายการ</td>
    <td>จำนวนเงิน</td>
    <td>สถานะ</td>
</tr>

<?php
		
//ค้นหารายการที่โอนเข้าธนาคาร
$query=pg_query("select * from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"appvXID\" is not null
and \"bankRevAccID\"='$BID' and date(\"bankRevStamp\")='$datemain' ORDER BY \"bankRevStamp\",\"revTranID\" ASC");
$dateRevStamp_old="";
$nub=0;
while($resvc=pg_fetch_array($query)){
	$revTranID = $resvc['revTranID'];
	$cnID = $resvc['cnID'];
	$bankRevBranch = trim($resvc['bankRevBranch']);
	$bankRevStamp = trim($resvc['bankRevStamp']);
			
	$dateRevStamp=trim(substr($bankRevStamp,0,10)); //วันที่โอน
	$timeRevStamp=trim(substr($bankRevStamp,10)); //เวลาที่โอน
			
	$bankRevAmt = trim($resvc['bankRevAmt']);
	$doerID = $resvc['doerID'];
	$doerStamp = $resvc['doerStamp'];
	$tranActionID = $resvc['tranActionID'];
	$appvXID = $resvc['appvXID']; //ฝ่ายบัญชีที่อนุมัติจะใช้สำหรับตรวจสอบในส่วนการเงินว่าไม่ให้เป็นคนเดียวกันกับคนอนุมัติครั้งแรก
	$revTranStatus=$resvc['revTranStatus'];
	
	if($revTranStatus==1){
		$txtstatus="รอนำเงินไปใช้";
	}else if($revTranStatus==2){
		$txtstatus="รายการนี้เป็นเงินที่ไม่ใช่ชำระค่าสินค้าหรือบริการ";
	}else if($revTranStatus==3){
		$txtstatus="รายการนี้ถูกนำเงินไปใช้แล้ว";
	}else if($revTranStatus==6){
		$txtstatus="ยังใช้เงินไม่ครบ";
	}else if($revTranStatus==9){
		$txtstatus="รอการเงินอนุมัติ";
	}
	//กำหนดให้อนุมัติได้เฉพาะคนที่ไม่ได้ทำรายการและคนละคนกับคนอนุมัติคนแรก หรืออนุมัติมัติได้เฉพาะผู้ที่มีึสิทธิ์เท่านั้น (กรณีฝ่ายบัญชีอนุมัติ appvXID จะเป็นค่า null)
	if($doerID!=$user_id || $emplevel<=1){
		$i+=1;
		if($i%2==0){
			echo "<tr bgcolor=#D1EEEE align=\"center\">";
		}else{
			echo "<tr bgcolor=#E0FFFF align=\"center\">";
		}
				
		?>
		<td><?php echo $timeRevStamp; ?></td>
		<td height="30"><?php echo $revTranID; ?></td>
		<td><?php echo $cnID; ?></td>
		<td><?php echo $bankRevBranch; ?></td>
		<td><?php echo $doerStamp; ?></td>
		<td align="right"><?php echo number_format($bankRevAmt,2); ?></td>
		<?php
		echo "<td align=left>$txtstatus</td>";
		?>
		</tr>
		<?php
		$nub++;
		$dateRevStamp_old=$dateRevStamp;
		$sumbankRevAmt += $bankRevAmt;
	}
	$sumbank = number_format($sumbankRevAmt,2);
}
if($nub==0){
	echo "<tr><td height=50 align=center colspan=7><b>---ไม่พบข้อมูล---</b></td></tr>";
}else{
	//แสดงผลรวม record สุดท้ายของแต่ละธนาคาร
	echo "<tr><td colspan=\"5\" align=\"right\"><b>รวม </b></td><td align=\"right\"><b>$sumbank</b></td><td align=center></td></tr>";					
	unset($sumbankRevAmt);
}

?>
</table>
<div align="center" style="padding-top:50px"><input type="button" value="X ปิด" onclick="window.close();"></div>

</body>
</html>