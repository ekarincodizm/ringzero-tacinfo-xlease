<?php 
include("../../config/config.php");
include("../function/nameMonth.php");

$acctype = pg_escape_string($_GET['acctype']);
$option = pg_escape_string($_GET['option']);

if($option==1){//เมื่อเลือก วันที่นำเงินเข้าธนาคาร
	$datepicker = pg_escape_string($_GET['date']);
	$condition = "AND date(\"bankRevStamp\")='$datepicker' ";
	$txthead="ประจำวันที่ $datepicker";
}else if($option==2){//เมื่อเลือก เดือน-ปี ที่นำเงินเข้าธนาคาร
	$yy = pg_escape_string($_GET["yy"]);
	$mm = pg_escape_string($_GET["mm"]);
	$month=nameMonthTH($mm);
	$condition = "AND EXTRACT(MONTH FROM \"bankRevStamp\") = '$mm' AND EXTRACT(YEAR FROM \"bankRevStamp\") = '$yy' ";
	$txthead="ประจำเดือน $month ปี ค.ศ.$yy";
}else if($option==3){
	$yy = pg_escape_string($_GET["yy"]);//เมื่อเลือก ปี ที่นำเงินเข้าธนาคาร
	$condition = " AND EXTRACT(YEAR FROM \"bankRevStamp\") = '$yy' ";
	$txthead="ประจำ ปี ค.ศ.$yy";
}
?>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
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
.wait{
	background-color:#DDDDDD;
	font-size:12px
}
</style>
<div style="float:left;"><b><?php echo $txthead;?></b></div>
<div style="float:right;">
	<img src="thcap_capital_interest_lastweek/images/excel.png" width="15px;" height="15px;"><a href="javascript:popU('frm_report_trans_excel.php?datepicker=<?php echo $datepicker; ?>&acctype=<?php echo $acctype;?>&option=<?php echo $option;?>&yy=<?php echo $yy;?>&mm=<?php echo $mm;?>')"><b><u> พิมพ์รายงาน Excel</u></b></a>
	<img src="images/icoPrint.png"><a href="javascript:popU('frm_report_trans_pdf.php?datepicker=<?php echo $datepicker; ?>&acctype=<?php echo $acctype;?>&option=<?php echo $option;?>&yy=<?php echo $yy;?>&mm=<?php echo $mm;?>')"><b><u> พิมพ์รายงาน PDF</u></b></a>
</div>
<div style="clear:both;"></div>
<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
<tr><td colspan="13" bgcolor="#FFFFFF"><span style="background-color:#FFCCCC;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> รายงานเช็คคืน &nbsp;&nbsp;&nbsp;<span style="background-color:#DDDDDD;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> รายงานเช็คคืน</td></tr>
<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
    <td>รหัสรายการเงินโอน</td>
    <td>ประเภทการนำเข้า</td>
    <td>สถานะการอนุมัติ</td>
    <td>เลขที่บัญชี</td>
	<td>รหัสสาขาที่โอน</td>
    <td>วันที่และเวลาที่นำเงินเข้าธนาคาร</td>
    <td>จำนวนเงิน</td>
	<td>เลขที่สัญญาที่ใช้เงิน</td>
	<td>รายละเอียดเช็ค(ถ้ามี)</td>
	<td>เพิ่มเติม</td>
</tr>

<?php
$acctypeloop = explode("@",$acctype);

for($loop = 0;$loop<sizeof($acctypeloop);$loop++){
	
	if($acctypeloop[$loop] != "" ){

		$qry_acc = pg_query("select * from \"BankInt\" where \"isTranPay\" = 1 and \"BID\" = '$acctypeloop[$loop]'");
		while($re_acc = pg_fetch_array($qry_acc)){
			$BAccount = $re_acc['BAccount'];
			$BName = $re_acc['BName'];
		}	
		
		echo "<tr bgcolor=\"#7AC5CD\"><td colspan=\"13\"><b>$BAccount-$BName</b></td></tr>";
		$query=pg_query("select * from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"bankRevAccID\" = '$acctypeloop[$loop]' $condition  ORDER BY \"revTranID\" ASC");
		$nub = pg_num_rows($query);
		if($nub==0){
			echo "<tr><td height=30 align=center colspan=13>---- ไม่พบข้อมูล  ----</td></tr>";
		}else{
			while($resvc=pg_fetch_array($query)){
				$n++;
				$revTranID = $resvc['revTranID'];
				$cnID = $resvc['cnID'];
				$revTranStatus = $resvc['revTranStatus'];
				$appvXStatus = $resvc['appvXStatus'];
				$appvYStatus = $resvc['appvYStatus'];
				$contractID = $resvc['contractID']; // เลขที่สัญญา
				$namestatus = $resvc['namestatus']; // ชื่อสถานะ
				$revChqID = $resvc['revChqID']; // รหัสเช็ค
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=\"center\">";
				}else{
					echo "<tr class=\"even\" align=\"center\">";
				}
					
				if($revTranStatus==9 and $appvXStatus==""){
					$txtstatus=$namestatus;
					echo "<tr class=\"wait\" align=\"center\">";
				}else if($revTranStatus==4){
					$txtstatus="<font color=blue>$namestatus</font>";
					echo "<tr class=\"wait\" align=\"center\">";
				}else if($revTranStatus==2){
					$txtstatus="<font color=blue>$namestatus</font>";
					echo "<tr class=\"wait\" align=\"center\">";
				}else if($revTranStatus==1){
					$txtstatus="<font color=blue>$namestatus</font>";
				}else if($revTranStatus==3){
					$txtstatus="<font color=blue><a style=\"cursor:pointer \" onclick=\"popU('popup_trans_receipt.php?revTranID=$revTranID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><u>$namestatus</u></a></font>";
				}else if($revTranStatus==5){
					$txtstatus="<font color=blue>$namestatus</font>";
					echo "<tr class=\"wait\" align=\"center\">";
				}else if($revTranStatus==6){
					$txtstatus="<a style=\"cursor:pointer \" onclick=\"popU('popup_trans_receipt.php?revTranID=$revTranID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\"><font color=\"red\"><u>$namestatus</u></font></a>";
				}else if($revTranStatus==7){ //กรณีรายการเช็คคืน
					echo "<tr bgcolor=\"#FFCCCC\" align=\"center\">";
					$txtstatus="<font color=red><b>$namestatus</b>";
				}else{
					$txtstatus = $namestatus;
				}
				$BAccount = $resvc['BAccount'];
				$bankRevBranch = trim($resvc['bankRevBranch']);
				$bankRevStamp = trim($resvc['bankRevStamp']);
				$bankRevAmt = trim($resvc['bankRevAmt']);				
			?>
					<td align="center"><?php echo $revTranID; ?></td>
					<td align="center"><?php echo $cnID; ?></td>
					<td align="center"><?php echo $txtstatus; ?></td>
					<td align="center"><?php echo $BAccount; ?></td>
					<td align="center"><?php echo $bankRevBranch; ?></td>
					<td align="center"><?php echo $bankRevStamp; ?></td>
					<td align="right"><?php echo number_format($bankRevAmt,2); ?></td>
					<td align="center"><a onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800');" style="cursor:pointer;"  ><font color="#0000FF"><u><?php echo $contractID; ?></u></font></a></td>
					<td align="center"><a onclick="javascript:popU('Channel_detail_chq.php?revChqID=<?php echo $revChqID; ?>&tranid=<?php echo $revTranID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=550');" style="cursor:pointer;"  ><font color="#0000FF"><u><?php echo $revChqID; ?></u></font></a></td>
					<td align="center"><a onclick="javascript:popU('popup_trans_show.php?revTranID=<?php echo $revTranID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');" style="cursor:pointer;"  ><img src="images/open.png" width="16" height="16"></a></td>
				</tr>
			<?php
			$nub++;
			$sumbankRevAmt += $bankRevAmt;
			}
		$sumbankall += $sumbankRevAmt;	
		$sumbankRevAmt = number_format($sumbankRevAmt,2);
		echo "<tr><td colspan=\"6\" align=\"right\"><b>รวม :</b></td><td align=\"right\"><b>$sumbankRevAmt</b></td><td colspan=\"2\"></td></tr>";		
		unset($sumbankRevAmt);
		}
		
	}	
}
$sumbankall = number_format($sumbankall,2);
echo "<tr bgcolor=\"#DEB887\"><td colspan=\"6\" align=\"right\"><b>รวมทั้งหมด :</b></td><td align=\"right\"><b>$sumbankall</b></td><td colspan=\"6\"></td></tr>";
	?>
</table>