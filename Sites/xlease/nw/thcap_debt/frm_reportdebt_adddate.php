<?php
session_start();
include("../../config/config.php");	

$contractID=$_POST["contractID"];
$debt = $_POST["debt"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script src="../../jqueryui/js/number.js" type="text/javascript"></script>

<title>ระบุวันที่ครบกำหนดชำระ</title>
</head>
<body style="background-color:#ffffff; margin-top:0px;">
<?php
//ตรวจสอบว่า ในกลุ่มมี "debtDueDate" ที่ไม่เป็น null หรือไม่
$p=0;
$arraydebt="";
for($i=0;$i<sizeof($debt);$i++){
	//ค้นหา debtDueDate
	$qrydebt=pg_query("select \"debtDueDate\" from \"thcap_temp_otherpay_debt\" where \"debtID\"='$debt[$i]'");
	list($debtDueDate)=pg_fetch_array($qrydebt);
	
	if($debtDueDate==""){
		$p++;
	}else{
		$p=0;
		break;
	}
}
for($t=0;$t<sizeof($debt);$t++){
	//ต่อ string เพื่อนำไปหาข้อมูลใน qry
	if($arraydebt==""){
		$arraydebt="'".$debt[$t]."'";
	}else{
		$arraydebt=$arraydebt.",'".$debt[$t]."'";
	}
}

if($p==0){
	//หาวัน max "debtDueDate"
	$qrymaxdate=pg_query("select max(\"debtDueDate\") from \"thcap_temp_otherpay_debt\" 
	where \"debtID\" IN ($arraydebt)");
	list($datepay)=pg_fetch_array($qrymaxdate);
}else{
	//หาวัน max "debtDueDate"
	$qrymaxdate=pg_query("select max(\"typePayRefDate\") from \"thcap_temp_otherpay_debt\" 
	where \"debtID\" IN ($arraydebt)");
	list($datepay)=pg_fetch_array($qrymaxdate);
	
	//บวกวันที่ที่ได้ไปอีก 15 วัน
	$qrydate=pg_query("select date('$datepay')+15");
	list($datechk)=pg_fetch_array($qrydate);
}

// หาหมายเหตุใบแจ้งหนี้
/*$qry_note_invoice = pg_query("select \"noteDetail\" from \"thcap_contract_note\" where \"contractID\" = '$contractID' and \"noteType\" = '1'
							and \"noteID\" = (select max(\"noteID\") from \"thcap_contract_note\" where \"contractID\" = '$contractID' and \"Approved\" = 'TRUE') ");
$noteDetail = pg_fetch_result($qry_note_invoice,0);*/

?>
<form method="post" name="frm" action="process_reportdebt.php"><!--process_reportdebt.php-->
<div style="width:800px;margin:0px auto;margin-top:20px;">
	<div align="center"><h2>ระบุวันที่ครบกำหนดชำระ</h2></div>
	<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#8B7765; margin-bottom:3px">
	<tr><td bgcolor="#FFCCCC"><b>เลขที่สัญญา : <?php echo $contractID; ?></b></td></tr>
	<tr height="50">
		<td align="center"><b>วันที่ครบกำหนดชำระ :</b>
		<input type="text" name="datepay" id="datepay" value="<?php echo $datepay;?>" style="text-align:center;" readonly="true" onchange="checkkeydate()">
		<b>วันที่ออกใบแจ้งหนี้ :</b><input type="text" name="dateinv" id="dateinv" value="<?php echo nowDate();?>" style="text-align:center;" readonly="true">
		<input type="hidden" id="chkdate" value="<?php echo $datechk; ?>">
		<input type="hidden" id="chkval" value="<?php echo $p; ?>">
		</td>
	</tr>
	<?php
		//หาเบี้ยปรับ
		$qrylease=pg_query("select thcap_get_lease_fine('$contractID','$datepay')");
		list($lease)=pg_fetch_array($qrylease);
	?>
	<tr><td><input type="checkbox" name="chklease" id="chklease" value="1" checked>แสดงเบี้ยปรับบนใบแจ้งหนี้ <input type="text" name="lease" id="lease" value="<?php echo $lease;?>" style="text-align:right;" onKeyPress="checknumber(event)"> บาท</td></tr>
	<tr>
		<td>
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr>
				<td bgcolor="#8B7E66" style="color:#FFFFFF;" colspan="7"><b>รายการที่เลือก</b></td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#CDBA96" align="center" height="25">
				<td>รหัสประเภทค่าใช้จ่าย</td>
				<td>รายละเอียดค่าใช้จ่าย</td>
				<td>ค่าอ้างอิงของค่าใช้จ่าย</td>
				<td>จำนวนเงินไม่รวม VAT</td>
				<td>VAT</td>
				<td>จำนวนเงินรวม VAT</td>
				<td>วันที่ครบกำหนดชำระ</td>
			</tr>
			
			<?php
			$sumnet=0;
			$sumvat=0;
			$sumamt=0;
			for($j=0;$j<sizeof($debt);$j++){
				$qry_fr=pg_query("select * from \"thcap_v_debt_free_to_make_invoice_all\" where \"debtID\"='$debt[$j]'");		
				
				if($res=pg_fetch_array($qry_fr)){
					
					$i+=1;
					if($i%2==0){
						echo "<tr bgcolor=\"#EED8AE\" align=center>";
					}else{
						echo "<tr bgcolor=\"#FFE7BA\" align=center>";
					}
				?>
					<td><?php echo $res['typePayID']; ?></td>
					<td><?php echo $res['tpDesc']; ?></td>
					<td><?php echo $res['typePayRefValue']; ?></td>
					<td align="right"><?php echo number_format($res['debtNet'],2); ?></td>
					<td align="right"><?php echo number_format($res['debtVat'],2); ?></td>
					<td align="right"><?php echo number_format($res['debtAmt'],2); ?></td>
					<td>
						<?php echo $res['debtDueDate']; ?>
						<input type="hidden" name="debtid[]" value="<?php echo $debt[$j];?>">
					</td>
				</tr>
				<?php
				} //end if
				$sumnet=$sumnet+$res['debtNet'];
				$sumvat=$sumvat+$res['debtVat'];
				$sumamt=$sumamt+$res['debtAmt'];
			}
			?>
			<tr align="right" style="font-weight:bold;">
				<td colspan="3" align="center"><b>รวม</b></td>
				<td><?php echo number_format($sumnet,2); ?></td>
				<td><?php echo number_format($sumvat,2); ?></td>
				<td><?php echo number_format($sumamt,2); ?></td>
				<td></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
	<tr><td bgcolor="#FFCCCC"><b>หมายเหตุในใบแจ้งหนี้</b></td></tr>
	<tr><td><textarea name="remark" cols="60" rows="5"></textarea></td></tr>
	</table>
	<div style="padding:10px;text-align:center;"><input type="hidden" name="contractID" value="<?php echo $contractID; ?>"><input type="submit" value="บันทึก" onclick="return checksave();"><input type="button" value="ย้อนกลับ" onclick="window.location='frm_reportdebt.php'"></div>
</div>
</form>
<script language=javascript>
$(document).ready(function(){
	//จะแสดงก็ต่อเมื่อกรณีที่ debDueDate เป็น null ทุกค่า
	if($("#chkval").val()>0){
		$("#datepay").datepicker({
			showOn: 'button',
			buttonImage: 'images/calendar.gif',
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
	}
	
	$("#dateinv").datepicker({
		showOn: 'button',
		buttonImage: 'images/calendar.gif',
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd'
	});
	
	$('#chklease').click(function(){
		if($("#chklease").is(':checked')){
			$('#lease').attr('readonly', false); 
		}else{
			$('#lease').attr('readonly', true); 
		}
	});
});
function checkkeydate(){
	if($("#datepay").val()>$("#chkdate").val()){
		alert("จะต้องระบุวันที่ไม่เกิน 15 วัน จากวันที่กำหนดให้");
		$("#datepay").val('<?php echo $datepay;?>');
	}
}
function checksave(){
	//ตรวจสอบกรณีเลือกแสดงเบี้ยปรับจำนวนเงินต้องมีค่า
	if($("#chklease").is(':checked')){
		if(parseFloat($('#lease').val())==0 || $('#lease').val()==""){
			alert("กรุณากรอกเบี้ยปรับ");
			$('#lease').focus();
			return false;
		}
	}
	if(confirm('ยืนยันการบันทึกข้อมูล')){
		return true;
	}else{
		return false;
	}
}
</script>
</body>
</html>
