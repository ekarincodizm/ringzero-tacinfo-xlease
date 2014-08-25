<?php
include("../../config/config.php");
$revTranID = $_GET["revtran"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>
    
<fieldset>
<form name="frm1" id="frm1" action="returnchq.php" method="post">
<table width="100%" cellpadding="0" cellspacing="1" border="0">
<?php 
$qry=pg_query("select * from finance.\"V_thcap_receive_transfer_tsfAppv\" where \"revTranID\"='$revTranID' and \"revTranStatus\"='9' ");
$numrow=pg_num_rows($qry);

if($numrow>0){ //แสดงว่ายังไม่มีเรียกใช้รายการนี้ ยังสามารถทำรายการต่อไปได้
	if($resvc=pg_fetch_array($qry)){
		if($resvc["cancel"]=='f'){ //กรณีรายการนี้ถูกลบไปก่อนหน้านี้แล้ว
			echo "<tr><td align=\"left\" colspan=\"5\">รหัสรายการเงินโอน : <font color=red><b>$revTranID</b></font> ถูกลบไปก่อนหน้านี้แล้ว</td></tr>";
			?>
			<tr>
				<td colspan="5" align="center">
					<input type="button" id="cancelvalue" onclick="$('#dialog').remove();location.href = 'frm_Index_finance.php';" value="ปิด" style="width:100px">
				</td>
			</tr>
			<?php
		}else{ //กรณีรายการนี้ยังไม่ถูกลบ
			echo "
			<tr>
				<td align=\"left\" colspan=\"5\"><b>รหัสรายการเงินโอน : <font color=red>$revTranID</font></b></td>
			</tr>
			<tr style=\"font-weight:bold;\" valign=\"top\" bgcolor=\"#79BCFF\" align=\"center\">
				<td>วันที่โอน</td>
				<td>ประเภทการนำเข้า</td>
				<td>เลขที่บัญชี</td>
				<td>สาขา</td>
				<td>จำนวนเงิน</td>
			</tr>
			<tr bgcolor=#FFFFFF>
				<td align=center>".trim(substr($resvc['bankRevStamp'],0,10))."</td>
				<td align=center>$resvc[cnID]</td>
				<td>$resvc[BAccount]</td>
				<td>".trim($resvc['bankRevBranch'])."</td>
				<td align=right>".number_format($resvc['bankRevAmt'],2)."</td>
			</tr>";
			$resChqvc = $resvc["bankRevAmt"]; //จำนวนเงิน
			?>
			<tr><td colspan="5" height="25" bgcolor="#FFE7BA"><b>เลือกเช็คที่เกี่ยวข้องกับรายการเช็คคืน</b></td></tr>
			<tr>
			<td colspan="5">
				<table width="100%" cellpadding="1" cellspacing="1" border="0" bgcolor="#D8BFD8">
				<tr style="font-weight:bold;color:#FFFFFF;" valign="top" bgcolor="#8B7B8B" align="center">
					<td>เลือก</td>
					<td>เลขที่เช็ค</td>
					<td>เลขที่สัญญา</td>
					<td width="120">ชื่อ-สกุลลูกค้า</td>
					<td>วันที่บนเช็ค</td>
					<td width="120">ธนาคารที่ออกเช็ค</td>
					<td>วันที่นำเช็คเข้าธนาคาร</td>
					<td>เช็คเด้งครั้งที่</td>
					<td>ผู้นำเช็คเข้าธนาคาร</td>
					<td>ยอดเช็ค</td>
					
				</tr>
				
				<?php
				
				$qrybadchq=pg_query("select * , '1'::integer as \"checkNum\"
									from finance.\"V_thcap_receive_cheque_keeper_cheManage\" 
									where \"bankRevResult\"='3' AND \"bankChqAmt\"= '$resChqvc' AND \"chqKeeperID\" not in(select \"chqKeeperID\" from finance.thcap_receive_transfer
										where \"chqKeeperID\" is not null and \"revTranStatus\" = '7')

									union

									select * , '2'::integer as \"checkNum\"
									from finance.\"V_thcap_receive_cheque_keeper_cheManage\" 
									where \"bankRevResult\"='3' AND \"bankChqAmt\" <> '$resChqvc' AND \"chqKeeperID\" not in(select \"chqKeeperID\" from finance.thcap_receive_transfer
										where \"chqKeeperID\" is not null and \"revTranStatus\" = '7')

									order by \"checkNum\",\"bankChqDate\",\"giveTakerDate\",\"chqSubmitTimes\" ");
				while($resbad=pg_fetch_array($qrybadchq)){
					$revChqID = $resbad["revChqID"]; //รหัสเช็ค
					$bankChqNo=$resbad["bankChqNo"]; //เลขที่เช็ค
					$revChqDate = $resbad["revChqDate"]; //วันที่รับเช็ค
					$bankName = $resbad["BankName"]; //ธนาคาร
					$bankOutBranch = $resbad["bankOutBranch"]; //สาขา
					$contractid = $resbad["revChqToCCID"]; //เลขที่สัญญา
					$bankChqAmt = $resbad["bankChqAmt"]; //จำนวนเงิน
					$revChqStatus=$resbad["revChqStatus"]; //สถานะเช็ค
					$bankChqDate=$resbad["bankChqDate"]; //วันที่สั่งจ่าย/วันที่บนเช็ค
					$chqSubmitTimes=$resbad["chqSubmitTimes"];//จำนวนครั้งที่เช็คเด้ง
					$giveTakerID=$resbad["giveTakerID"];//ผู้นำเช็คเข้าธนาคาร
					$giveTakerDate=$resbad["giveTakerDate"];//วันที่นำเช็คเข้าธนาคาร
					$chqKeeperID=$resbad["chqKeeperID"];
					
					//ผู้นำเช็คเข้า
					$query_fullname = pg_query("select \"fullname\"  from \"Vfuser\" where \"id_user\" = '$giveTakerID' ");
					$nameuser = pg_fetch_array($query_fullname);
					$givetakerName =$nameuser["fullname"];
					//หาชื่อลูกค้า
					$qry_cusname = pg_query("SELECT \"CusID\" ,thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractid' and \"CusState\" = '0'");
					list($cusid,$fullname) = pg_fetch_array($qry_cusname);
															
					$i+=1;
					if($i%2==0){
						echo "<tr bgcolor=\"#EED2EE\" align=center>";
					}else{
						echo "<tr bgcolor=\"#FFE1FF\" align=center>";
					}
					$nameradio="ChqID".$i;
					?>
					<td bgcolor="#FFFFFF">
					<input type="radio"  name="ChqID"  id=<?php echo $nameradio;?>  value="<?php echo $chqKeeperID; ?>"></td>
					<td><?php echo $bankChqNo; ?><input type="hidden" name="chqKeeperID[]" value="<?php echo $chqKeeperID;?>"></td>
					<td>
						<a style="cursor:pointer" onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" title="ดูตารางผ่อนชำระ">
						<font color="red"><U><?php echo $contractid; ?></U></font></a>
					</td>
					<td align="left"><?php echo $fullname; ?></td>
					<td><?php echo $bankChqDate; ?><input type="hidden" name="revChqID[]" id="t<?php echo $i; ?>" value="<?php echo $revChqID;?>"></td>
					<td align="left"><?php echo $bankName; ?></td>
					<td align="center"><?php echo $giveTakerDate; ?></td>
					<td align="center"><?php echo $chqSubmitTimes; ?></td>
					<td align="center"><?php echo $givetakerName; ?></td>
					<td align="right"><?php echo number_format($bankChqAmt,2); ?></td>
				</tr>
				<?php
				}
				?>
				</table>
				</td>
			</tr>
			<tr>
				<td colspan="5" align="center">
					<input type="hidden" name="cmd" id="cmd" value="returnchq">
					<input type="hidden" name="revTranID" id="revTranID" value="<?php echo $revTranID; ?>">					
					 <input type="submit" name="btn_save" id="btn_save" value="บันทึกเช็คคืน"  style="width:100px"/>		  			
					<input type="button" id="cancelvalue" onclick="$('#dialog2').remove()" value="ยกเลิก" style="width:100px">
				</td>
			</tr>
			<?php
		}
	}
	
}else{ //ไม่พบรายการนี้จากฐานข้อมูล อาจถูกลบออกจากฐานข้อมูลแล้ว
	echo "<tr><td align=\"left\" colspan=\"5\">ไม่พบรหัสรายการเงินโอนที่เป็นรายการเช็คคืน อาจตรวจสอบรายการหรือถูกลบจากฐานข้อมูลแล้ว </td><tr>";
	?>
	<tr>
		<td colspan="5" align="center">
			<input type="button" id="cancelvalue" onclick="$('#dialog').remove();location.href = 'frm_Index_finance.php';" value="ปิด" style="width:100px">
		</td>
	</tr>
	<?php
}
?>
</table>
</form>
</fieldset>
<script type="text/javascript">
$('#btn_save').click(function(){
	var sumall=$('input[name="ChqID"]:checked').length;
	
	if(sumall<1){
		alert('กรุณาเลือกรายการเช็คคืน');
		return false;
	}else{ 
		return true;		
	}
});
</script>
</html>