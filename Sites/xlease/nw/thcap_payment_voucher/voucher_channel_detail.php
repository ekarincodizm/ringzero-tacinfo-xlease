<?php
include("../../config/config.php");
	$rootpath = redirect($_SERVER['PHP_SELF'],'');	
	$voucherID = pg_escape_string($_GET['voucherID']);
	
	$qry = "select * from v_thcap_temp_voucher_details_payment where \"voucherID\"='$voucherID'";
	
	if($query_list = pg_query($qry)){
	
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
		$voucherStatus = $res_v['voucherStatus'];
		$voucherAdjustCancelFor = $res_v['voucherAdjustCancelFor'];//not null คือ ปรับปรุงยกเลิก
		
		//format เลขที่สัญญา xx-xxxx-xxxxxxx	และ เลขที่สัญญา xx-xxxx-xxxxxxx/xxxx
		$fromChannelDetails_format = '/(\w{2})-(\w{2})(\d{2})-(\d{7})(\/\d{4})?/';
		$fromChannelDetails_popup = "<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".'\1-\2\3-\4\5'."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>".'\1-\2\3-\4\5'."</u></font></span>";	
		//หมายเหตุ				
		$fromChannelDetails = preg_replace($fromChannelDetails_format,$fromChannelDetails_popup,$fromChannelDetails);
		//รายละเอียด
		$voucherRemark = preg_replace($fromChannelDetails_format,$fromChannelDetails_popup,$voucherRemark);
		
		
		//จุดประสงค์
			$voucherPurpose = $res_v["voucherPurpose"];
			if($voucherPurpose !=""){			
				$qry_purpose_name = pg_query("select \"thcap_purpose_name\" from account.\"thcap_purpose\" where thcap_purpose_id = '$voucherPurpose' ");
				$purpose_name = pg_fetch_result($qry_purpose_name,0);
			}else{
				$purpose_name="";
			}
		
		$qry_bookhead = pg_query("select abh_autoid,abh_id from account.\"all_accBookHead\" where abh_refid = '$voucherID'");
		$abh_autoid = pg_fetch_result($qry_bookhead,0);
		$abh_id = pg_fetch_result($qry_bookhead,1);
		
		if($voucherStatus=='0'){
			$textStatus = "ยกเลิกแล้ว";
			$Fcolor = "red";
			$hidden = "hidden";
		}elseif($voucherAdjustCancelFor != ""){
			$textStatus = "รายการปรับปรุงยกเลิก";
			$Fcolor = "#CD853F";
		}
		
		$qry_concurrent = pg_query("select \"voucherID\" from thcap_temp_voucher_cancel where \"voucherID\"='$voucherID' and \"appvStatus\"='9' ");
		$num = pg_num_rows($qry_concurrent);
						
		if($num>0){
			$textStatus = "รออนุมัติยกเลิก";
			$Fcolor = "FF8000";
			$hidden = "hidden";
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>รายละเอียดใบสำคัญจ่าย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
     <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
<div style="width:80%;margin-left:auto;margin-right:auto;">

	<div style="margin-top:10px;" align="center"><h1>รายละเอียดใบสำคัญจ่าย</h1></div>
<table width="100%">
	<tr >
		<td align="left">
			<div style="margin-top:10px;">
				<table align="left">
					<tr>
						<td align="right"><b>รหัสใบสำคัญจ่าย :</b></td>
						<td><font color="red"><?php echo $voucherID; ?></font></td>
					</tr>
					<tr>
						<td align="right"><b>วันที่/เวลาใบสำคัญจ่าย :</b></td>
						<td><?php echo $voucherDate." / ".$voucherTime; ?></td>
					</tr>
					<tr>
						<td align="right"><b>เลขที่บันทึกบัญชี :</b></td>
						<td><font color="blue"><u><a onclick="javascript:popU('../accountEdit/frm_account_show.php?abh_autoid=<?php echo $abh_autoid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=550');" style="cursor:pointer;"><?php echo $abh_id; ?></a></u></font></td>
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
						<td colspan="2" align="center"><font color="<?php echo $Fcolor; ?>" size="3"><b><?php echo $textStatus; ?></b></font></td>
					</tr>
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
		
		$qry_detail = pg_query("select * from account.\"all_accBookDetail\" where abd_autoidabh='$abh_autoid' order by abd_autoid");
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
						echo "<tr >";
					}
					//หา เดือน-ปี จาก วันที่
					list($year,$month,$day)=explode("-",$voucherDate);
					echo "<td align=\"center\"><a href=\"javascript:popU('../thcap_accbank_type/frm_Index.php?fromaccpaper=1&accserial=$accBookserial&date1=1&month1=$month&year1=$year','','toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no,location=no,width=1024,height=600')\"><u>$abd_accBookID</u></a></td>";
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
						<td><b>หมายเหตุ</b></td>
					</tr>
					<tr>
						<td bgcolor="#DDDDDD">
						<?php if($fromChannelDetails !=""){ echo $fromChannelDetails;} else {echo "ไม่ได้ระบุหมายเหตุ";}?>
						</td>
					</tr>
				</table>
			</div>		
		</td>
		<td align="right">
			<?php	include($rootpath."nw/thcap_appv/frm_add_tag.php"); ?>
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			<input type="button" name="cancel" id="cancel" value="ขอยกเลิก" onclick="javascript:popU('cancel_payment_voucher.php?pop=Y&select_print=<?php echo $voucherID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=680')" <?php echo $hidden;?> />
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