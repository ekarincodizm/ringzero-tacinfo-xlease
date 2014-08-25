<?php
session_start();
include("../../config/config.php");
$id_user=$_SESSION["av_iduser"];
$cid=$_POST["cid"]; 
$result=$_POST["result"];
$giveTakerDate=nowDate();


$quryuser=pg_query("select \"ta_get_user_emplevel\"('$id_user') ");
list($leveluser)=pg_fetch_array($quryuser);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) แสดงรายการนำเช็คเข้าธนาคาร</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	<?php if($leveluser <= '1'){ ?>
    $("#giveTakerDate").datepicker({
        showOn: 'button',
        buttonImage: 'calendar.gif',
        buttonImageOnly: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });
	<?php } ?>	
});

function checkdata(){
	if(document.form1.giveTakerID.value==""){
		alert("กรุณาเลือกพนักงานที่นำเช็คไปเข้า");
		document.form1.giveTakerID.focus();
		return false;
	}else if(document.form1.giveTakerDate.value==""){
		alert("กรุณาระบุวันที่มอบเช็คให้พนักงาน");
		document.form1.giveTakerDate.focus();
		return false;
	}else if(document.form1.giveTakerToBankAcc.value==""){
		alert("กรุณาเลือกบัญชีธนาคาร");
		document.form1.giveTakerToBankAcc.focus();
		return false;
	}
}
</script>
</head>
<body>
<?php
if(sizeof($cid)==0){
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>คุณไม่ได้เลือกรายการ กรุณาลองใหม่อีกครั้ง!!!</b></font></div>";
	echo "<meta http-equiv='refresh' content='3; URL=frm_chequetobank.php'>";
}else{
?>
<form method="post" name="form1" action="process_chequetobank.php">
<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
	<div class="header"><h2>ตรวจสอบและกรอกรายละเอียดการนำเช็คเข้าธนาคาร</h2></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr height="30">
				<td align="right"><b>พนักงานที่นำเช็คไปเข้า :</b></td>
				<td>
					<select name="giveTakerID">
					<option value="">---เลือก---</option>
					<?php
					$qryuser=pg_query("select * from \"Vfuser\" order by \"fullname\"");
					while($resuser=pg_fetch_array($qryuser)){
						$id_user=$resuser["id_user"];
						$fullname=$resuser["fullname"];
					
					?>
					<option value="<?php echo $id_user;?>"><?php echo $fullname;?></option>
					<?php }?>
					</select>
				</td>
			</tr>
			<tr height="30">
				<td align="right"><b>วันที่มอบเช็คให้พนักงาน :</b></td>
				<td><input type="text" id="giveTakerDate" name="giveTakerDate" value="<?php echo $giveTakerDate; ?>" size="15" <?php if($leveluser > '1'){ echo "Readonly"; } ?>></td>
			</tr>
			<tr height="30">
				<td align="right"><b>เลือกบัญชีธนาคาร :</b></td>
				<td>
					<select name="giveTakerToBankAcc">
					<option value="">---เลือก---</option>
					<?php
					$qryuser=pg_query("select * from \"BankInt\" where \"isTranPay\" = '1' order by \"BAccount\"");
					while($resuser=pg_fetch_array($qryuser)){
						$BAccount=$resuser["BAccount"];
						$BName=$resuser["BName"];
						$BID=$resuser["BID"];
					
					?>
					<option value="<?php echo $BID;?>"><?php echo $BAccount.",".$BName;?></option>
					<?php }?>
					</select>
				</td>
			</tr>
			</table>
		</div>
		<div style="padding-top:10px;"><u><b>หมายเหตุ</b></u><font color="red"> <span style="background-color:#e5cdf9;">&nbsp;&nbsp;&nbsp;</span> รายการสีม่วง คือ เช็คค้ำประกันหนี้ FACTORING ในกรณีที่ ลูกหนี้ไม่จ่าย จะนำเช็คผู้ขายบิลเข้า ถ้าลูกหนี้จ่ายมาปกติ ก็จะคืนเช็คให้ลูกค้า</font></div>
		
		<?php 
		$qry_outbank = pg_query("SELECT \"bankID\", \"bankName\", sort FROM \"BankProfile\" order by \"bankID\""); 
		while($re_outbank = pg_fetch_array($qry_outbank)){
			$outbankid = $re_outbank["bankID"];
			$outbankName = $re_outbank["bankName"];
			$chkrows = 0;
			
			for($i=0;$i<sizeof($cid);$i++){ 
				$chqKeeperID = $cid[$i];
				
				$qry_fr=pg_query("select \"revChqID\" from finance.\"V_thcap_receive_cheque_chqManage\" a
					left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
					WHERE \"chqKeeperID\"='$chqKeeperID' and a.\"bankOutID\" = '$outbankid' and \"revChqStatus\" in ('2','8')");
				$rows_fr=pg_num_rows($qry_fr);

				if($rows_fr > 0){
					list($chk_revChqID)=pg_fetch_array($qry_fr);
				
					//ตรวจสอบว่ามีรายการที่กำลังคืนเช็คหรือไม่
					$qrychkapp=pg_query("select * from finance.thcap_receive_cheque_return where \"statusChq\"='2' and \"revChqID\"='$chk_revChqID'");
					$numchkapp=pg_num_rows($qrychkapp);
					if($numchkapp>0){ //แสดงว่ามีรายการที่รอคืนเช็คอยู่
						echo "<div align=\"center\"><h2>มีบางรายการกำลังรออนุมัติคืนเช็คอยู่ กรุณาทำรายการใหม่อีกครั้ง</h2></div>";
						echo "<div align=\"center\"><input type=\"button\" value=\"กลับไปทำรายการใหม่\" onclick=\"location.href='frm_chequetobank.php'\"></div>";
						exit();
					}else{
						$chkrows += 1; 
					}
				}
			}	

			if($chkrows > 0){
			?>
				<div>
					<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
					<tr bgcolor="#FFFFFF"><td height="25" colspan="6"><b><?php echo $outbankName; ?></b></td></tr>
					<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
						<td width="100">เลขที่เช็ค</td>
						<td width="100">วันที่บนเช็ค</td>
						<td width="200">ธนาคารที่ออกเช็ค</td>
						<td width="100">สาขา</td>
						<td width="100">จ่ายบริษัท</td>
						<td width="150">ยอดเช็ค(บาท)</td>
					</tr>
					<?php
					$rows_s = 0;
					$money_s = 0;
					for($i=0;$i<sizeof($cid);$i++){ //id ของพนักงานที่ใช้เมนูนี้
						$chqKeeperID = $cid[$i];
						$qry_fr=pg_query("select * from finance.\"V_thcap_receive_cheque_chqManage\" a
										  left join \"BankProfile\" b on a.\"bankOutID\"=b.\"bankID\"
										  WHERE \"chqKeeperID\"='$chqKeeperID' and a.\"bankOutID\" = '$outbankid'");
						$rows_fr=pg_num_rows($qry_fr);
						if($rows_fr > 0){
							if($res_fr=pg_fetch_array($qry_fr)){
								$revChqID = $res_fr["revChqID"];
								$bankChqNo=$res_fr["bankChqNo"];
								$bankChqDate = $res_fr["bankChqDate"]; 
								$bankName = $res_fr["bankName"]; 
								$bankOutBranch = $res_fr["bankOutBranch"]; 
								$bankChqToCompID = $res_fr["bankChqToCompID"]; 
								$bankChqAmt = $res_fr["bankChqAmt"]; 
								$revChqStatus=$res_fr["revChqStatus"];
								$isInsurChq = $res_fr["isInsurChq"];
							}
							$n+=1;
							if($i%2==0){
								if($isInsurChq==1){
									echo "<tr bgcolor=\"#e5cdf9\" align=center>";
								}else{
									echo "<tr class=\"odd\" align=center>";
								}			
							}else{
								if($isInsurChq==1){
									echo "<tr bgcolor=\"#e5cdf9\" align=center>";
								}else{
									echo "<tr class=\"even\" align=center>";
								}
							}
							echo "<td >$bankChqNo<input type=\"hidden\" id=\"chqID\" name=\"chqID[]\" value=\"$chqKeeperID\"></td>";
							echo "<td >$bankChqDate<input type=\"hidden\" id=\"revID\" name=\"revID[]\" value=\"$revChqID\"></td>";
							echo "<td align=\"left\">$bankName<input type=\"hidden\" name=\"res[]\" value=\"$result[$i]\"></td>";
							echo "<td >$bankOutBranch</td>";
							echo "<td >$bankChqToCompID</td>";
							$bankChqAmt2=number_format($bankChqAmt,2);
							echo "<td align=\"right\">$bankChqAmt2</td>";
							echo "</tr>";
							$rows_s += $rows_fr;
							$money_s += $bankChqAmt;
						}								
					}
					$rows_sum += $rows_s;
					$money_sum += $money_s;
					$money_ss  = number_format($money_s,2);
					echo "<tr><td>รวม: $rows_s รายการ</td><td colspan=\"4\" align=\"right\">รวมเงิน: </td><td align=\"right\"> $money_ss</td></tr>";  
			}
		}
					echo "<tr bgcolor=\"#FFFFFF\"><td height=\"25\" colspan=\"6\"><b><u>สรุปยอด</u></b></td></tr>";
					$money_sum = number_format($money_sum,2);
					echo "<tr bgcolor=\"#99FFFF\"><td colspan=\"2\">รวมทั้งหมด: $rows_sum รายการ</td><td colspan=\"3\" align=\"right\">รวมเงินทั้งหมด: </td><td align=\"right\"> $money_sum</td></tr>";  	
					?>
					<tr><td colspan="6" align="center" height="50" bgcolor="#FFFFFF"><input type="submit" value="บันทึก" onclick="return checkdata();"><input type="button" value="  กลับ  " onclick="window.location='frm_chequetobank.php';"></td></tr>
					</table>
				</div>	
	</td>
</tr>
<tr>
	<td>
		<?php
		// for($i=0;$i<sizeof($cid);$i++){
			// echo "<input type=\"hidden\" name=\"res[]\" value=\"$result[$i]\">";
		// }
		?>
	</td>
</tr>
</table>
</form>
<?php } ?>
</body>
</html>