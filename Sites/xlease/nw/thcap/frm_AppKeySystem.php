<?php
session_start();
include("../../config/config.php");

//user ที่ทำรายการ
$user_id = $_SESSION["av_iduser"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติคีย์เงินโอนผ่านระบบ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script>
 function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
function confirmappv(no){
	if(no=='1'){
		if(confirm('ยืนยันการอนุมัติ')==true){
			return true;
		}else{return false;}
	}
	else if(no=='0'){
		if(confirm('ยืนยันการไม่อนุมัติ!!')==true){
			return true;
		}else{return false;}
	}else{	
		return false;
	}
} 
</script> 
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>
    
</head>
<body id="mm">

<table width="1100" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>       
			<div style="float:left">&nbsp;</div>
			<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();" class="ui-button"></div>
			<div style="clear:both;"></div>
			<fieldset><legend><B>(THCAP) อนุมัติคีย์เงินโอนผ่านระบบ</B></legend>
				<div align="center">
					<div class="ui-widget" style="padding-top:20px;">
						<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
						<tr style="font-weight:bold;color:#FFFFFF;" valign="top" bgcolor="#8B7765" align="center">
							<td>วันที่โอน</td>
							<td>เวลาที่โอน</td>
							<td>รหัสรายการเงินโอน</td>
							<td>ประเภทการนำเข้า</td>
							<td>เลขที่บัญชี</td>
							<td>สาขา</td>
                            <td>ผู้ทำรายการ</td>
							<td>วันที่ทำรายการ</td>
							<td>จำนวนเงิน</td>
							<td>ไฟล์แนบ</td>
							<td></td>
							<td></td>
						</tr>
						<?php 
						$qry_acc = pg_query("select * from \"BankInt\" where \"isTranPay\" = 1");
						while($re_acc = pg_fetch_array($qry_acc)){
							$BID2 = $re_acc['BID'];
							$BAccount2 = $re_acc['BAccount'];
							$BName2 = $re_acc['BName'];
						
							//แสดงชื่อธนาคาร
							echo "<tr bgcolor=\"#EECBAD\"><td colspan=\"12\"><span style=\"background-color:#CDAF95;\"><b>$BAccount2-$BName2</b></span></td></tr>";
						
							//หาวันที่ที่ต้องแสดง
							$querydate=pg_query("select date(\"bankRevStamp\") as \"bankRevStamp\" from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"revTranStatus\"='5' 
							and \"bankRevAccID\"='$BID2' group by date(\"bankRevStamp\") ORDER BY date(\"bankRevStamp\") ASC");
							
							$nub=0;
							while($resdate=pg_fetch_array($querydate)){
								$nub++;
								$datemain=$resdate["bankRevStamp"];
								
								//แสดงวันที่
								echo "<tr bgcolor=\"#B4CDCD\"><td colspan=\"12\"><b>วันที่โอน : <span onclick=\"javascript:popU('frm_Showbankeach.php?BID=$BID2&datemain=$datemain','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor: pointer;\"><u>$datemain</u></span></b></td></tr>";
							
								//ค้นหารายการตามธนาคารและวันที่
								$query=pg_query("select * from \"finance\".\"V_thcap_receive_transfer_tsfAppv\" WHERE \"revTranStatus\"='5'
								and \"bankRevAccID\" = '$BID2' and date(\"bankRevStamp\")='$datemain' ORDER BY \"bankRevStamp\",\"doerStamp\" ASC");							
								
								while($resvc=pg_fetch_array($query)){
									$revTranID = $resvc['revTranID'];
									$cnID = $resvc['cnID'];
									$BID = $resvc['bankRevAccID'];
									$bankRevBranch = trim($resvc['bankRevBranch']);
									$bankRevStamp = trim($resvc['bankRevStamp']);
									$bankRevAmt = trim($resvc['bankRevAmt']);
									$doerID = $resvc['doerID'];
									$doerStamp = $resvc['doerStamp'];
									$tranActionID = $resvc['tranActionID'];
									$BAccount = $resvc['BAccount'];
									$appvXID = $resvc['appvXID']; //ฝ่ายบัญชีที่อนุมัติจะใช้สำหรับตรวจสอบในส่วนการเงินว่าไม่ให้เป็นคนเดียวกันกับคนอนุมัติครั้งแรก
									$revTranStatus = $resvc['revTranStatus']; //สถานะสำหรับตรวจสอบกรณีให้แสดงปุ่ม "เลือกใช้รายการนี้"
									$contractID = $resvc['contractID']; //เลขที่สัญญาที่โอน
									
									$dateRevStamp=trim(substr($bankRevStamp,0,10)); //วันที่โอน
									$timeRevStamp=trim(substr($bankRevStamp,10)); //เวลาที่โอน
								
									$pictran=trim($resvc['pictran']);
									$realpath = redirect($_SERVER['PHP_SELF'],'nw/thcap/upload/addcheque/'.$pictran);
									
									$qr_doer = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$doerID'");
									$rs_doer = pg_fetch_array($qr_doer);
									$doerName = $rs_doer['fullname'];
									
									$i+=1;
									if($i%2==0){
										echo "<tr bgcolor=#D1EEEE align=\"center\">";
									}else{
										echo "<tr bgcolor=#E0FFFF align=\"center\">";
									}
										
									?>
									<td height="30"><?php echo $dateRevStamp; ?></td>
									<td><?php echo $timeRevStamp; ?></td>
									<td><?php echo $revTranID; ?></td>
									<td><?php echo $cnID; ?></td>
									<td><?php echo $BAccount; ?></td>
									<td><?php echo $bankRevBranch; ?></td>
                                    <td><?php echo $doerName; ?></td>
									<td><?php echo $doerStamp; ?></td>
									<td align="right"><?php echo number_format($bankRevAmt,2); ?></td>
									<?php
									if($pictran==""){
										echo "<td>-</td>";
									}else{
										echo "<td><a href=\"$realpath\" target=\"_blank\"><img src=\"images/open.png\" width=18 heigh=18></a></td>";
									}?>
									<?php /*echo "<td><a href=\"process_appkeysystem.php?revTranID=$revTranID&app=1\" onclick=\"return confirm('ยืนยันการอนุมัติ')\">อนุมัติ</a></td>";
									echo "<td><a href=\"process_appkeysystem.php?revTranID=$revTranID&app=0\" onclick=\"return confirm('ยืนยันการไม่อนุมัติ')\">ไม่อนุมัติ</a></td>";*/
									?>
									<form name="my<?php echo $i; ?>" method="post" action="process_appkeysystem.php">
										<input type="hidden" name="revTranID" id="revTranID" value="<?php echo $revTranID; ?>">
										<input hidden name="appv" value="อนุมัติ" type="submit"/>
										<td ><a href ="#" style="cursor:pointer;"  onclick=" if (confirmappv('1')){ 
										document.forms['my<?php echo $i; ?>'].appv.click();document.forms['my<?php echo $i; ?>'].submit();return false;} "><font color="#0000FF"><u>อนุมัติ</u></font></a></td></td>
										<td ><a style="cursor:pointer;" onclick=" if (confirmappv('0')){ document.forms['my<?php echo $i; ?>'].submit(); return false;}"><font color="#0000FF"><u>ไม่อนุมัติ</u></font></a></td></td>
									</form>
									</tr>
								<?php
								}
							}
							
							if($nub==0){
								echo "<tr><td colspan=\"12\" height=\"30\" align=center><b>-- ไม่พบข้อมูล --</b></td></tr>";
							}
						}
						?>
						</table>
					</div>
				</div>
			</fieldset>
        </td>
    </tr>
</table>

</body>
</html>