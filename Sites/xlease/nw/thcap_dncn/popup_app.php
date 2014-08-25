<?php
include("../../config/config.php");
echo "<meta http-equiv=\"Content-Type\" content=\"txt/html; charset=utf-8\" />";

$dcNoteID = pg_escape_string($_GET['idapp']);
$appstatus = pg_escape_string($_GET['appstate']);
$print = pg_escape_string($_GET['print']);
$qry_waitapp = pg_query("SELECT  \"contractID\",\"doerStamp\",\"doerID\",\"dcNoteAmtALL\",\"dcNoteID\",\"dcNoteRev\",\"typeChannel\" as \"byChannel\",\"dcNoteDescription\",\"dcNoteDate\",
\"appvRemask\",\"dcNoteStatus\",\"typeChannelName\",\"byChannelName\",\"returnTranToCus\",\"returnTranToCusName\",\"returnTranToBank\",\"returnTranToBankName\",\"returnTranToAccNo\",
\"returnChqNo\",\"returnChqCusName\", \"byChannel\" as \"byChannelBankInt\", \"appvName\", \"appvStamp\"
						FROM account.thcap_dncn_payback
						where \"dcNoteID\" = '$dcNoteID'");
$re_waitapp = pg_fetch_array($qry_waitapp);
				//เลขที่สัญญา
						$conid = $re_waitapp["contractID"];
				// dcNoteRev
						$dcNoteRev = $re_waitapp["dcNoteRev"];
				//-- หาชื่อผู้กู้หลัก
						$qry_maincus = pg_query("SELECT \"dcMainCusName\" FROM account.\"thcap_dncn_details\" where \"dcNoteID\" = '$dcNoteID' AND \"dcNoteRev\" = '$dcNoteRev'");
						$maincus_fullname = pg_fetch_result($qry_maincus,0);
				//-- หาผู้กู้ร่วม
						$qry_cocus = pg_query("SELECT \"dcCoCusName\" FROM account.\"thcap_dncn_details\" where \"dcNoteID\" = '$dcNoteID' AND \"dcNoteRev\" = '$dcNoteRev'");
						$namecoopall = pg_fetch_result($qry_cocus,0);
				//วันที่ทำรายการ
						$doerStamp = $re_waitapp["doerStamp"];
				//ชื่อผู้ทำรายการ
						$doerID = $re_waitapp["doerID"];
						$qry_username = pg_query("SELECT \"fullname\" FROM \"Vfuser\" where \"id_user\" = '$doerID'");
						list($doer_fullname) = pg_fetch_array($qry_username);
				//ประเภทเงินที่ขอคืน
						$byChannel = $re_waitapp["byChannel"];	
						//เงินค้ำประกันการชำระหนี้
						$qry_chkchannel = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$conid','1')");
						list($chkbyChannelget) = pg_fetch_array($qry_chkchannel);
						//เงินพักรอตัดรายการ
						$qry_chkchannel = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$conid','1')");
						list($chkbyChannelhold) = pg_fetch_array($qry_chkchannel);
						//ตรวจสอบว่าเป้นประเภทใด						
						if($chkbyChannelget == $byChannel){	//ถ้าเป็น เงินค้ำประกันการชำระหนี้										
							$qry_channel = pg_query("SELECT account.\"thcap_getSecureMoneyType\"('$conid','$byChannel')");
							list($byChannel) = pg_fetch_array($qry_channel);
						}else if($chkbyChannelhold == $byChannel){ //ถ้าเป็น เงินพักรอตัดรายการ
							$qry_channel = pg_query("SELECT account.\"thcap_getHoldMoneyType\"('$conid','$byChannel')");
							list($byChannel) = pg_fetch_array($qry_channel);	
						}
						//รายละเอียดประเภทการขอคืน
						$qry_txtchannel = pg_query("SELECT \"tpDesc\" FROM account.\"thcap_typePay\" where  \"tpID\" = '$byChannel' ");
						list($tpDesc) = pg_fetch_array($qry_txtchannel);
						
						//กรณีขอคืนเิงินหลังจากปรับปรุงใหม่จะมี column แสดงชื่อรายการที่เลือกว่าคืนเงินพักหรือเงินค้ำ
						if($re_waitapp["typeChannelName"]!=""){
							$tpDesc=$re_waitapp["typeChannelName"];
						}
						
						//ช่องทางการคืนเงิน
						$byChannelName=$re_waitapp["byChannelName"];
				//จำนวนเงิน
						$dcNoteAmtALL = $re_waitapp["dcNoteAmtALL"];
				//เหตุผลการขอคืน
						$remark = $re_waitapp["dcNoteDescription"];
				//วันที่รายการออกมีผล
						$dcNoteDate = $re_waitapp["dcNoteDate"];
				//เหตุผลการอนุมัติ
						$appvRemask = $re_waitapp["appvRemask"];
				//สถานะการอนุมัติ
						$dcNoteStatus = $re_waitapp["dcNoteStatus"];
				// ชื่อเต็มผู้ทำรายการอนุมัติ
						$appvName = $re_waitapp["appvName"];
				// วันเวลาที่ทำรายการอนุมัติ
						$appvStamp = $re_waitapp["appvStamp"];
						
				// ข้อความผลการอนุมัติ
				if($dcNoteStatus == "0")
				{
					$dcNoteStatus_Text = "ไม่อนุมัติ";
				}
				elseif($dcNoteStatus == "1")
				{
					$dcNoteStatus_Text = "อนุมัติ";
				}
				else
				{
					$dcNoteStatus_Text = "";
				}
?>
    
<fieldset>
  
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}
</style>

<form name="frm1" id="frm1" action="process_appcn.php" method="post">

<table width="100%" cellpadding="0" cellspacing="1" border="0">
	<tr>
		<td width="30%" align="right" >เลขที่สัญญา : </td><td width="60%">&nbsp; <?php echo $conid ?> </td>
	</tr>
	<tr>   
		<td align="right">ชื่อผู้กู้หลัก : </td><td>&nbsp;  <?php echo $maincus_fullname ?></td>   
	</tr>
	<tr>	   
		<td align="right">ชื่อผู้กู้ร่วม : </td><td>&nbsp; <?php echo $namecoopall; ?></td>	   
	</tr>
	<tr>	   
		<td align="right">รหัส CreditNote : </td><td>&nbsp;  <?php echo $dcNoteID ?></td>			   
	</tr>
	<tr>	   
		<td align="right">วันที่รายการออกมีผล : </td><td>&nbsp;  <?php echo $dcNoteDate; ?></td>			   
	</tr>
	<tr bgcolor="#FFFACD">	   
		<td align="right">ช่องทางการคืนเงิน : </td><td>&nbsp;  <?php echo $byChannelName; ?></td>			   
	</tr>
	<?php
	//ถ้าตรวจสอบพบว่ามีรหัสเจ้าของบัญชีแสดงว่าเป็นการคืนแบบโอนให้แสดงข้อมูลส่วนนี้ด้วย
	if($re_waitapp["returnTranToCus"]!=""){
		echo "<tr bgcolor=#FFFACD><td align=\"right\">เจ้าของบัญชี : </td><td>&nbsp;  $re_waitapp[returnTranToCusName]</td></tr>";
		echo "<tr bgcolor=#FFFACD><td align=\"right\">รหัสธนาคาร : </td><td>&nbsp;  $re_waitapp[returnTranToBank]#$re_waitapp[returnTranToBankName]</td></tr>";
		echo "<tr bgcolor=#FFFACD><td align=\"right\">เลขที่บัญชีปลายทาง : </td><td>&nbsp;  $re_waitapp[returnTranToAccNo]</td></tr>";
	}
	
	//ถ้าตรวจสอบพบว่ามีเลขที่เช็คแสดงว่าเป็นการคืนแบบเช็คให้แสดงข้อมูลส่วนนี้ด้วย
	if($re_waitapp["returnChqNo"]!="")
	{
		// หาข้อมูลธนาคารที่ออกเช็ค
		$qry_bank = pg_query("select \"BAccount\"||'-'||\"BName\" as \"res_bank\" from \"BankInt\" where \"BID\" = '$re_waitapp[byChannelBankInt]' ");
		$res_bank = pg_result($qry_bank,0);
		
		echo "<tr bgcolor=#FFFACD><td align=\"right\">เช็คธนาคาร : </td><td>&nbsp;  $res_bank</td></tr>";
		echo "<tr bgcolor=#FFFACD><td align=\"right\">ออกเช็คให้กับ : </td><td>&nbsp;  $re_waitapp[returnChqCusName]</td></tr>";
	}
	?>
	<tr>
		<td align="right">ผู้ทำรายการ : </td><td>&nbsp;  <?php echo $doer_fullname; ?></td>			   
	</tr>
	<tr>	   
		<td align="right">วันที่ทำรายการ : </td><td>&nbsp;  <?php echo $doerStamp; ?></td>			   
	</tr>
	<tr>	  
		<td align="right">ประเภทเงินที่ขอคืน : </td><td>&nbsp; <?php echo $tpDesc; ?></td>
	</tr>
	<tr> 
		<td align="right">จำนวนเงิน : </td><td>&nbsp;  <b><font color=green><?php echo number_format($dcNoteAmtALL,2) ?></font></b></td>
	</tr>
	<tr>
		<td align="right">รายละเอียด : </td><td>&nbsp;  <textarea id="remark" name="remark" readOnly><?php echo $remark; ?></textarea></td>
	</tr>
<?php if($appstatus == '1' and $print!=1){ ?> 	
	<tr>
		<td colspan="2"><hr width="80%"></td>
	</tr>
		<tr>
		<td colspan="2" align="center"><b><u>การอนุมัติ</u><b><br></td>
	</tr>
	<tr>	
		<td align="right">เหตุผล : </td><td>&nbsp;  <textarea id="appremark" name="appremark" ></textarea></td>
	</tr>
<?php }else if($appstatus != '1' AND $dcNoteStatus != '9'){ ?>
	<tr>
		<td colspan="2"><hr width="80%"></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><b><u>เหตุผลการอนุมัติ</u><b><br></td>
	</tr>
	<tr>
		<td align="right">ผู้อนุมัติรายการ : </td><td>&nbsp; <?php echo $appvName; ?></td>
	</tr>
	<tr>
		<td align="right">วันเวลาที่อนุมัติรายการ : </td><td>&nbsp; <?php echo $appvStamp; ?></td>
	</tr>
	<tr>
		<td align="right">ผลการอนุมัติ : </td><td>&nbsp; <?php echo $dcNoteStatus_Text; ?></td>
	</tr>
	<tr>	
		<td align="right">เหตุผล : </td><td>&nbsp;  <textarea id="appremark" name="appremark" Readonly ><?php echo  $appvRemask; ?></textarea></td>
	</tr>

<?php } ?> 
</table>

<div style="text-align:right; margin-top:10px">
 <?php if($appstatus == '1'){ 
	if($print!='1'){
	?> 
	<!--input type="button" name="btn_app" id="btn_app" value="อนุมัติ" onclick="app();" />
	<input type="button" name="btn_notapp" id="btn_notapp" value="ไม่อนุมัติ" onclick="notapp();" /-->
	<input type="hidden" name="dcNoteID" id="dcNoteID" value="<?php echo $dcNoteID ?>">
	<input type="submit" name="btn_app"  value="อนุมัติ" onclick="return app();" />
	<input type="submit" name="btn_notapp" id="btn_notapp" value="ไม่อนุมัติ" onclick="return notapp();" />
	<?php
	}
	
	?>
	<input type="button" id="cancelvalue" onclick="$('#dialog').remove()" value="ปิด">
 <?php } ?>			
</div>
</form>
</fieldset>
<script type="text/javascript">
function app(){
		if(confirm("ยืนยันการอนุมัติ")==true){
			$("#btn_app").attr('disabled', true);
			return true;
		}
		else{
			return false;
		}
}
function notapp(){
	if($("#appremark").val() == ""){	
		alert("กรอกเหตุผลการปฎิเสธอนุมัติด้วยครับ");
		return false;
	}else{
		if(confirm("ปฎิเสธการอนุมัติ")==true){
			return true;
		}
		else{
			return false;
		}
	}	
};
</script>
