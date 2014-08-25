<?php
session_start();
include("../../config/config.php");

$id_user = $_SESSION["av_iduser"]; //พนักงานที่ทำรายการ
$revChqID = pg_escape_string($_GET['revChqID']); //รหัสเช็ค
$chqKeeperID = pg_escape_string($_GET['chqKeeperID']); //รหัสเช็ค

//ตรวจสอบว่ามีเลขที่เช็คใบนี้จริงหรือไม่
$qrychkrec=pg_query("select * from finance.thcap_receive_cheque where \"revChqID\"='$revChqID'");
if(pg_num_rows($qrychkrec)==0){ 
	echo "<div align=center><h2>---ไม่พบเลขที่เช็ค---</h2></div>";
	exit();
}
//################################เตรียมข้อมูลสำหรับตรวจสอบว่าสามารถขอยกเลิกใบเสร็จภายในหน้านี้ได้หรือไม่


$qrydata=pg_query("select \"revChqToCCID\", \"revChqDate\", \"bankChqNo\", \"bankChqDate\", \"bankOutID\", \"BankName\", \"bankOutBranch\", \"bankChqAmt\",
					\"result\", \"namebank\", \"givetakername\", \"giveTakerDate\", \"namestatus\", \"isPostChq\", \"isInsurChq\", \"receiverFullName\", \"receiverStamp\"
				from finance.\"V_thcap_receive_cheque_chqManage\" where \"chqKeeperID\"='$chqKeeperID'");
if($resdata=pg_fetch_array($qrydata)){
	$contractID=$resdata["revChqToCCID"]; //เลขที่สัญญา
	$revChqDate=$resdata["revChqDate"]; //วันที่รับเช็ค
	$bankChqNo=$resdata["bankChqNo"]; //เลขที่เช็ค
	$bankChqDate=$resdata["bankChqDate"]; //วันที่สั่งจ่าย/วันที่บนเช็ค
	$bankOutID=$resdata["bankOutID"]; //รหัสธนาคารที่ออกเช็ค
	$BankName=$resdata["BankName"]; //ชื่อธนาคารที่ออกเช็ค
	$bankOutBranch=$resdata["bankOutBranch"]; //สาขาที่ออกเช็ค
	$bankChqAmt=$resdata["bankChqAmt"]; //จำนวนเงิน
	$result=$resdata["result"]; //หมายเหตุ นำเช็คเข้าธนาคาร
	$namebank=$resdata["namebank"]; //เข้าบัญชี
	$givetakername=$resdata["givetakername"]; //พนักงานที่นำเช็คไปเข้า
	$giveTakerDate=$resdata["giveTakerDate"]; //วันที่มอบเช็คให้พนักงาน
	$namestatus=$resdata["namestatus"]; //สถานะเช็ค
	$isPostChq=$resdata["isPostChq"]; //เช็คชำระล่วงหน้า 0 คือไม่ใช่ 1 คือใช่
	$isInsurChq=$resdata["isInsurChq"]; //0 = ไม่ใช่เช็คค้ำประกันหนี้ 1 = เป็นเช็คค้ำประกันหนี้
	$receiverFullName=$resdata["receiverFullName"]; //ผู้รับเช็ค
	$receiverStamp=$resdata["receiverStamp"]; //วันเวลาที่รับเช็ค
	
	
	if($isPostChq==1){
		$txtchq="(เป็นเช็คชำระล่วงหน้า)";
	}else if($isInsurChq==1){
		$txtchq="(เป็นเช็คค้ำประกันหนี้)";
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<title>รายละเอียดการนำเช็คเข้าธนาคาร</title>
</head>
<body>

<div style="text-align:center;margin-bottom:20px;"><b><font size="4px">รายละเอียดการนำเช็คเข้าธนาคาร</font></b><br><font size="3px"><b>งานที่ : <?php echo $chqKeeperID; ?></b></font></div>
<div style="text-align:center;margin-bottom:20px;">
<table width="80%" cellSpacing="1" cellPadding="1" bgcolor="#C1CDC1" align="center">
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" width="30%" bgcolor="#EEEEE0"><b>เลขที่เช็ค :</b></td>
		<td>&nbsp;<font color="red"> <?php echo $bankChqNo; ?></font> <?php echo $txtchq;?></b></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>เลขที่สัญญา :</b></td>
		<td>&nbsp;<span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="blue"><u><?php echo $contractID; ?></u></b></font></span></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>วันที่สั่งจ่าย :</b></td>
		<td>&nbsp;<?php echo $bankChqDate; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>วันที่รับเช็ค :</b></td>
		<td>&nbsp;<?php echo $revChqDate; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>ธนาคารที่ออกเช็ค :</b></td>
		<td>&nbsp;<?php echo $BankName; ?> สาขา<?php echo $bankOutBranch; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>จำนวนเงิน :</b></td>
		<td> <?php echo number_format($bankChqAmt,2); ?> บาท</td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>พนักงานที่นำเช็คไปเข้า :</b></td>
		<td>&nbsp;<?php echo $givetakername; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>วันที่มอบเช็คให้พนักงาน :</b></td>
		<td>&nbsp;<?php echo $giveTakerDate; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>เข้าบัญชี :</b></td>
		<td>&nbsp;<?php echo $namebank; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>สถานะเช็ค :</b></td>
		<td>&nbsp;<?php echo $namestatus; ?></td>
	</tr>
	<tr bgcolor="#F5FFFA" height="25">
		<td align="right" bgcolor="#EEEEE0"><b>ผู้รับเช็ค :</b></td>
		<td>&nbsp;<?php echo $receiverFullName; ?>&nbsp;<b>วันที่</b> &nbsp;<?php echo $receiverStamp; ?></td>
	</tr>
	<tr>
		<td colspan="2">
			<fieldset><legend><b>หมายเหตุนำเข้าธนาคาร</b></legend>
				<textarea cols="60" rows="4" readonly><?php echo $result;?></textarea>
			</fieldset>
		</td>
	</tr>
</table>
</div>
<div style="margin-left:auto;margin-right:auto;width:60%;">
<fieldset><legend><B>ประวัติการนำเช็คเข้า - รหัสรายการเช็ค : <?php echo $revChqID; ?></B></legend>
				<table width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#F0F0F0">
							<tr style="font-weight:bold;color:#FFFFFF" valign="top" bgcolor="#8B8378" align="center">
								<td>ลำดับที่</td>
								<td>เลขที่เช็ค</td>
								<td>ผู้นำเช็คเข้าธนาคาร</td>
								<td>ครั้งที่นำเข้าธนาคาร</td>
								<td>ธนาคารที่นำเข้า</td>
								<td>รายละเอียด</td>			
							</tr>
							<?php
								$query_history_chq = pg_query(" select \"keeperID\",\"bankChqNo\",\"giveTakerID\",\"giveTakerDate\",\"chqSubmitTimes\",\"bankRevDate\",\"BID\",\"bankRevResult\",\"bankRevResult\",
									\"replyByTakerID\",\"replyByTakerStamp\"::date 
									from finance.\"V_thcap_receive_cheque_keeper_cheManage\"  
									WHERE \"revChqID\" ='$revChqID' 
									order by \"chqSubmitTimes\" ");
								$nub = 0;	
								while($res_his=pg_fetch_array($query_history_chq)){
								$nub++;
									$keeperID = $res_his['keeperID']; //รหัสผู้เก็บเช็ค
									$bankChqNo = $res_his['bankChqNo'];
									$giveTakerID = $res_his['giveTakerID'];
									$giveTakerDate = $res_his['giveTakerDate'];
									$chqSubmitTimes = $res_his['chqSubmitTimes'];
									$bankRevDate = $res_his['bankRevDate'];
									$BID = $res_his['BID'];
									$bankRevResult = $res_his['bankRevResult'];
									$replyByTakerID = $res_his['replyByTakerID'];
									$replyByTakerStamp = $res_his['replyByTakerStamp'];
									
									//หาชื่อธนาคาร
									
									$qry_ourbank = pg_query("SELECT \"BName\",\"BAccount\" FROM \"BankInt\" where \"BID\"::text = '$BID'");
									list($ourbankname,$BAccount) = pg_fetch_array($qry_ourbank);
									
									//หาชื่อผู้เก็บเช็ค
									$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$giveTakerID'");
									list($keeperfullname) = pg_fetch_array($qry_username);
									
									//หาชื่อผู้นำเข้า
									$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$giveTakerID'");
									list($givefullname) = pg_fetch_array($qry_username);
									
									//หาชื่อผู้ยืนยัน
									$qry_username = pg_query("SELECT fullname FROM \"Vfuser\" where id_user = '$replyByTakerID'");
									list($replyfullname) = pg_fetch_array($qry_username);
									
									//สถานะเช็ค
									switch($bankRevResult){
										case 1 :
											$RevResult = " ปกติ";
											break;
										case 2 :
											$RevResult = "too late";
											break;
										case 3 :
											$RevResult= "เช็คเด้ง";
											break;
										case 4 :
											$RevResult = "ยกเลิกนำเช็คเข้าธนาคาร";
											break;
									}
									
									echo "<tr align=\"center\">";
										echo "<td>$nub</td>";
										echo "<td>$bankChqNo</td>";
										echo "<td>$givefullname</td>";
										echo "<td>$chqSubmitTimes</td>";
										echo "<td>$ourbankname-$BAccount</td>";
										echo "<td><a onclick=\"javascript:popU('Channel_detail_chq.php?revChqID=$revChqID&chqKeeperID=$chqKeeperID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=600')\" style=\"cursor:pointer;\" ><img src=\"images/detail.gif\"/></a></td>";
									echo "</tr>";
								} //end while
							?>
								
							</table>
			</fieldset>
</div>
<div style="text-align:center;padding:20px"><input type="button" onclick="window.close();" value="ปิดหน้านี้"></div>
</body>
</html>