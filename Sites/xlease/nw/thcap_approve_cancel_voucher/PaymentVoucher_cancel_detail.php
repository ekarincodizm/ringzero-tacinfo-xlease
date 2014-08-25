<?php
include("../../config/config.php");

$user_id = $_SESSION["av_iduser"];

// ตรวจสอบสิทธิ์ ว่าสามารถใช้งานเมนู "(THCAP) อนุมัติยกเลิกใบสำคัญรายวันทั่วไป" ได้หรือไม่
$qry_canUseMenuAP80 = pg_query("select ta_get_usermenu_rights('AP80','$user_id')");
$canUseMenuAP80 = pg_result($qry_canUseMenuAP80,0);

$autoID = pg_escape_string($_GET['autoID']);

$qry_voucherID = pg_query("select \"voucherID\", \"doerRemark\", \"doerName\", \"doerStamp\"
							from \"v_thcap_temp_voucher_payment_wait_cancel\" where \"autoID\" = '$autoID' ");
$voucherID = pg_result($qry_voucherID,0);
$doerRemark = pg_result($qry_voucherID,1);
$doerNameCancel = pg_result($qry_voucherID,2);
$doerStampCancel = pg_result($qry_voucherID,3);

$qry = "select * from v_thcap_temp_voucher_details_payment where \"voucherID\"='$voucherID'";

if($query_list = pg_query($qry))
{
	$res_v = pg_fetch_array($query_list);

	$voucherID = $res_v['voucherID'];
	$voucherDate = $res_v['voucherDate'];
	$doerFull = $res_v['doerFull'];
	$doerStamp = $res_v['doerStamp'];
	$appvFull = $res_v['appvFull'];
	$appvStamp  = $res_v['appvStamp'];
	$auditFull = $res_v['auditFull'];
	$auditStamp = $res_v['auditStamp'];
	$voucherRemark = $res_v['voucherRemark'];
	$fromChannelDetails = $res_v['fromChannelDetails'];
	$voucherTime = $res_v['voucherTime'];
	$voucherPurpose=$res_v["voucherPurpose"];
			
	if($voucherPurpose !=""){			
		$qry_purpose_name = pg_query("select \"thcap_purpose_name\" from account.\"thcap_purpose\" where thcap_purpose_id = '$voucherPurpose' ");
		$purpose_name = pg_fetch_result($qry_purpose_name,0);
	}else{
		$purpose_name="";
	}
					
	$qry_bookhead = pg_query("select abh_autoid,abh_id from account.\"all_accBookHead\" where abh_refid = '$voucherID'");
	$abh_autoid = pg_fetch_result($qry_bookhead,0);
	$abh_id = pg_fetch_result($qry_bookhead,1);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ยกเลิกใบสำคัญจ่าย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
     <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script language="javascript">
		function appv(no)
		{
			var appvremark = document.getElementById("appvremark").value;
			if(appvremark == "")
			{
				alert("กรุณาระบุ หมายเหตุการอนุมัติ");
			}
			else
			{
				$.post('process_approve.php',{
						autoID:<?php echo $autoID; ?>,
						appvStatus:no,
						appvremark:appvremark
				},function(data){		
					if(data == "1"){
						alert("บันทึกรายการเรียบร้อย");
					}else if(data == "2"){
						alert("ผิดผลาด ไม่สามารถบันทึกได้!");
					}else{
						alert(data);
					}
					window.opener.location.reload();
					window.close();	
				});
			}
		}
	</script>
</head>
<body>
<div style="width:80%;margin-left:auto;margin-right:auto;">

	<div style="margin-top:10px;" align="center"><h1>ยกเลิกใบสำคัญจ่าย</h1></div>
<table width="100%">
	<tr >
		<td align="left">
			<div style="margin-top:10px;">
				<table align="left">
					<tr>
						<td align="right"><b>รหัสใบสำคัญจ่าย :</b></td>
						<td>
						<a onclick="javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=<?php echo $voucherID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=550');" style="cursor:pointer;"><font color="red"><u><?php echo $voucherID; ?></a>						
						</u></font></td>
					</tr>
					<tr>
						<td align="right"><b>วันที่/เวลาใบสำคัญจ่าย :</b></td>
						<td><?php echo $voucherDate." / ".$voucherTime; ?></td>
					</tr>
					<tr>
						<td align="right"><b>เลขที่บันทึกบัญชี :</b></td>
						<td><a onclick="javascript:popU('../accountEdit/frm_account_show.php?abh_autoid=<?php echo $abh_autoid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=550');" style="cursor:pointer;"><font color="blue"><u><?php echo $abh_id; ?></a></u></font></td>
					</tr>
					<tr>
						<td align="right"><b>จุดประสงค์ :</b></td>
						<td><?php echo $purpose_name; ?></td>
					</tr>
				</table>
			</div>
		</td>
		<td align="right">
			<div style="margin-top:10px;">
				<table align="right">
					<tr>
						<td align="right"><b>ผู้ำทำรายการ:</b></td>
						<td ><?php echo $doerFull; ?></td>
					</tr>
					<tr>
						<td align="right"><b>วันเวลาที่ทำรายการ :</b></td>
						<td ><?php echo $doerStamp; ?></td>
					</tr>
					<tr>
						<td align="right"><b>ผู้อนุมัติ:</b></td>
						<td><?php echo $appvFull; ?></td>
					</tr>
					<tr>
						<td align="right"><b>วันเวลาที่อนุมัติ:</b></td>
						<td ><?php echo $appvStamp; ?></td>
					</tr>
					<tr>
						<td align="right"><b>ผู้ตรวจสอบ:</b></td>
						<td ><?php echo $auditFull; ?></td>
					</tr>
					<tr>
						<td align="right"><b>วันเวลาที่ตรวจสอบ:</b></td>
						<td ><?php echo $auditStamp; ?></td>
					</tr>
					<tr>
						<td align="right"><b>ผู้ขอยกเลิก:</b></td>
						<td ><?php echo $doerNameCancel; ?></td>
					</tr>
					<tr>
						<td align="right"><b>วันเวลาที่ขอยกเลิก:</b></td>
						<td ><?php echo $doerStampCancel; ?></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	
	<tr>
		<td align="left">
			<div style="margin-top:10px;">
				<table align="left">
					<tr>
						<td align="right"><b>รายละเอียด : </b></td>
						<td><?php echo $voucherRemark; ?></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	
	<tr>
		<td colspan="2" align="center">
			<div style="margin-top:10px;margin-left:auto;margin-right:auto;">
				<table style="margin-top:20px;" cellpadding="5" cellspacing="0" border="0" width="100%" bgcolor="#F0F0F0" align="center">
					<tr align="center" bgcolor="#BEBEBE">
						<td><b>รหัสบัญชี </b></td>
						<td><b>รายการ </b></td>
						<td><b>เดบิต </b></td>
						<td><b>เครดิต </b></td>
					</tr>
		<?php 
		if($abh_autoid!=""){
		
		$qry_detail = pg_query("select * from account.\"all_accBookDetail\" where abd_autoidabh='$abh_autoid'");
		$i = 0;
		while($res_detail = pg_fetch_array($qry_detail)){
			$i++;
			$abd_accBookID = $res_detail['abd_accBookID'];
			$accBookserial = $res_detail["accBookserial"];
			$abd_bookType = $res_detail["abd_bookType"];
			$abd_amount = $res_detail["abd_amount"];
			
			$qry_all = pg_query("select \"accBookName\" from account.\"all_accBook\" where \"accBookserial\" = '$accBookserial' ");
		
				$accBookName = pg_fetch_result($qry_all,0);
				
				if($abd_bookType == 1){
					$txtdebit = number_format($abd_amount,2);
					$debit = $abd_amount;
				}else{
					$txtcredit = number_format($abd_amount,2);
					$credit = $abd_amount;
				}
				
					if($i%2==0){	
						echo "<tr bgcolor=\"#EDF8FE\" >";
					}else{
						echo "<tr>";
					}
					echo "<td>$abd_accBookID</td>";
					echo "<td>$accBookName</td>";
					echo "<td align=\"right\">$txtdebit</td>";
					echo "<td align=\"right\">$txtcredit</td>";
				echo "</tr>";
				
				$total_debit += $debit;
				$total_credit += $credit;
				
				$debit = "";
				$credit = "";
				$txtdebit = "";
				$txtcredit = "";
		}
		
		}
		?>
					<tr bgcolor="#BEBEBE">
						<td colspan="2" align="right"><b>Total</b></td>
						<td align="right"><b><?php echo number_format($total_debit,2);?></b></td>
						<td align="right"><b><?php echo number_format($total_credit,2);?></b></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div style="margin-top:20px;">
				<table>
					<tr>
						<td><b>หมายเหตุใบสำคัญจ่าย</b></td>
					</tr>
					<tr>
						<td><textarea cols="70" rows="4" readonly style="background-color:#CCCCCC"><?php echo $fromChannelDetails; ?></textarea></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	</tr>
	<tr>
		<td>
			<div style="margin-top:20px;">
				<table>
					<tr>
						<td><b>หมายเหตุการขอยกเลิก</b></td>
					</tr>
					<tr>
						<td><textarea cols="70" rows="4" readonly style="background-color:#CCCCCC"><?php echo $doerRemark; ?></textarea></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<?php
	if($canUseMenuAP80 == 1)
	{
	?>
		<tr>
			<td>
				<div style="margin-top:20px;">
					<table>
						<tr>
							<td><b>หมายเหตุการอนุมัติ</b></td>
						</tr>
						<tr>
							<td><textarea cols="70" rows="4" id="appvremark"></textarea></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	<?php
	}
	?>
	<tr>
		<td align="center" colspan="2">
			<?php
			if($canUseMenuAP80 == 1)
			{
			?>
				<input type="button" value="อนุมัติ" onclick="appv('1');"/>
				<input type="button" value="ไม่อนุมัติ" onclick="appv('0');"/>
			<?php
			}
			?>
			<input type="button" name="close" value="  ปิด   "onclick="window.close();">
		</td>
	</tr>
</table>
</div>
</body>
<script>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</html>