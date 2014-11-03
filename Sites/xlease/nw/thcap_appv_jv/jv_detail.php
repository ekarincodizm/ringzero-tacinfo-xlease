<?php
include("../../config/config.php");
include("../function/emplevel.php");

$user_id = $_SESSION["av_iduser"];
$app_page = pg_escape_string($_GET['page']);
$emplevel = emplevel($user_id); // ระดับพนักงาน

// ตรวจสอบสิทธิ์ ว่าสามารถใช้งานเมนู "(THCAP) อนุมัติยกเลิกใบสำคัญรายวันทั่วไป" ได้หรือไม่
/*$qry_canUseMenuAP82 = pg_query("select ta_get_usermenu_rights('AP82','$user_id')");
$canUseMenuAP82 = pg_result($qry_canUseMenuAP82,0);*/

$autoID = pg_escape_string($_GET['autoID']);

$qry_pre = pg_query("select * from \"thcap_temp_voucher_pre_details\" where \"prevoucherdetailsid\" = '$autoID' ");

	$detail = pg_fetch_array($qry_pre);
	
	$prevoucherdetailsid = $detail["prevoucherdetailsid"];
	$voucherDate = $detail["voucherDate"];
	$voucherTime = $detail["voucherTime"];
	$payID = $detail["payID"];
	$payFull = $detail["payFull"];
	$voucherRemark = $detail["voucherRemark"];
	$fromChannelDetails = $detail["fromChannelDetails"];
	$doerID = $detail["doerID"];
	$doerStamp = $detail["doerStamp"];
	$arrayaccbookserial = $detail["arrayaccbookserial"];
	$arrayabd_booktype = $detail["arrayabd_booktype"];
	$arrayabd_amount = $detail["arrayabd_amount"];
	//จุดประสงค์
	$voucherPurpose = $detail["voucherPurpose"];
	if($voucherPurpose !=""){			
		$qry_purpose_name = pg_query("select \"thcap_purpose_name\" from account.\"thcap_purpose\" where thcap_purpose_id = '$voucherPurpose' ");
		$purpose_name = pg_fetch_result($qry_purpose_name,0);
	}else{
		$purpose_name="";
	}
	
	// หาชื่อเต็มพนักงานที่ทำรายการ
	$qry_doerFull = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerID'");
	$doerFull = pg_result($qry_doerFull,0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติใบสำคัญรายวันทั่วไป</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
     <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
	<script language="javascript">
		function appv(no)
		{	
			if($('#appv_remark').val()==""){
				alert("กรุณาระบุเหตุผล!");
				return false;
			}
			else{
				if(no==1){//อนุมัติ
					$('#appv_status').val('1');
				}
				else if(no==0){//ไม่อนุมัติ
					$('#appv_status').val('0');
				}
				frm.submit();
			}
		}
	</script>
</head>
<body>
<div style="width:80%;margin-left:auto;margin-right:auto;">

	<div style="margin-top:10px;" align="center"><h1>อนุมัติใบสำคัญรายวันทั่วไป</h1></div>
<table width="100%">
	<tr >
		<td align="left">
			<div style="margin-top:10px;">
				<table align="left">
					<tr>
						<td align="right"><b>วันที่มีผล :</b></td>
						<td><font color="red"><?php echo $voucherDate; ?></font></td>
					</tr>
					<tr>
						<td align="right"><b>เวลาที่ีผล :</b></td>
						<td><?php echo $voucherTime; ?></td>
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
						<td align="right"><b>ผู้ทำรายการ:</b></td>
						<td ><?php echo $doerFull; ?></td>
					</tr>
					<tr>
						<td align="right"><b>วันเวลาที่ทำรายการ :</b></td>
						<td ><?php echo $doerStamp; ?></td>
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
						<td><b>รายการ </b></td>
						<td><b>เลขสมุดบัญชี</b></td>
						<td><b>ชื่อสมุดบัญชี </b></td>
						<td><b>เดบิต </b></td>
						<td><b>เครดิต </b></td>
					</tr>
		<?php 
		if(!empty($arrayaccbookserial) and !empty($arrayabd_booktype) and !empty($arrayabd_amount))	{
		
			// get accbookserial
			$count_array_serial = pg_query("select ta_array1d_count('$arrayaccbookserial')");
			$res_count_serial = pg_fetch_result($count_array_serial,0);
		
			$get_array_serial = pg_query("select ta_array1d_get('$arrayaccbookserial',0,$res_count_serial) as bookserial");
			
			while($get_serial = pg_fetch_array($get_array_serial)){
				
				$bookserial[] = $get_serial['bookserial'];
				
			}
			//get booktype
			$count_array_type = pg_query("select ta_array1d_count('$arrayabd_booktype')");
			$res_count_type = pg_fetch_result($count_array_type,0);
		
			$get_array_type = pg_query("select ta_array1d_get('$arrayabd_booktype',0,$res_count_type) as booktype");
			
			while($get_type = pg_fetch_array($get_array_type)){
				
				$booktype[] = $get_type['booktype'];
				
			}
			
			//get booktype
			$count_array_amount = pg_query("select ta_array1d_count('$arrayabd_amount')");
			$res_count_amount = pg_fetch_result($count_array_amount,0);
		
			$get_array = pg_query("select ta_array1d_get('$arrayabd_amount',0,$res_count_amount) as amount");
			
			while($get_amount = pg_fetch_array($get_array)){
				
				$amount[] = $get_amount['amount'];
				
			}
			$n=0;
			for($i=0;$i<sizeof($bookserial);$i++){
				$n++;
					if($i%2==0){	
						echo "<tr bgcolor=\"#EDF8FE\" >";
					}else{
						echo "<tr >";
					}
					
					if($booktype[$i] == 1){
						$txtdebit = number_format($amount[$i],2);
						$debit = $amount[$i];
					}else{
						$txtcredit = number_format($amount[$i],2);
						$credit = $amount[$i];
					}
				$qry_accName = pg_query("select \"accBookName\",\"accBookID\" from account.\"all_accBook\" where \"accBookserial\"='$bookserial[$i]'");
				$accbookName = pg_fetch_result($qry_accName,0);
				$accBookID = pg_fetch_result($qry_accName,1);
				
					echo "<td align=\"center\">$n</td>";
					echo "<td align=\"center\">$accBookID</td>";
					echo "<td>$accbookName</td>";
					echo "<td align=\"right\">$txtdebit</td>";
					echo "<td align=\"right\">$txtcredit</td>";
				echo "</tr>";
				
				$total_debit += $debit;
				$total_credit += $credit;
				
				$debit = "";
				$credit = "";
				$txtdebit = "";
			}
		}
		?>
					<tr bgcolor="#BEBEBE">
						<td colspan="3" align="right"><b>Total</b></td>
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
						<td><b>หมายเหตุใบสำคัญรายวันทั่วไป</b></td>
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
							<td><b>หมายเหตุการอนุมัติ</b></td>
						</tr>
						<form name="frm"  action="process_appv.php" method="post">
						<tr>						
							<td><textarea cols="70" rows="4" id="appv_remark" name="appv_remark"></textarea></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	
	<tr>
		<td align="center" colspan="2">
		<input hidden name ="prevoucherdetailsid" id ="prevoucherdetailsid" value="<?php echo $prevoucherdetailsid; ?>"/>
			<input hidden name="appv_status" id="appv_status" />
			
			<?php
			if($user_id != $doerID || $emplevel <= 1)
			{
				$canAppv = ""; // สามารถทำรายการอนุมัติได้
			}
			else
			{
				$canAppv = "title=\"คุณไม่มีสิทธิอนุมัติรายการที่ตนเองเป็นคนทำ\" disabled";
			}
			?>
			<input type="button" value="อนุมัติ" onclick="appv('1');" <?php echo $canAppv; ?> />
			<input type="button" value="ไม่อนุมัติ" onclick="appv('0');" <?php echo $canAppv; ?> />
			
			<input type="button" name="close" value="  ปิด   "onclick="window.close();">
			</form>
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