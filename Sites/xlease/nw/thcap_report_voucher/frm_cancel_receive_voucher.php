<?php
session_start();

include("../../config/config.php");
require('../../thaipdfclass.php');

//----------------- รับข้อมูล ----------------------------------
$doer = $_SESSION['av_iduser'];

$pop = $_GET['pop'];

if($pop == "Y"){
	$select_print_RV[] = $_REQUEST["select_print_RV"];
}else{
	$select_print_RV = $_REQUEST["select_print_RV"];
}

unset($pop);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใบสำคัญรับ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
</head>
<body>
<div style="width:80%;margin-left:auto;margin-right:auto;">

<div align="center"><h1>ยืนยันยกเลิกรายการใบสำคัญรับ</h1></div>
<form name="frm" action="process_cancel_rv.php" method="post" >
<?php

	$counter = 0;
	for($i=0;$i<count($select_print_RV);$i++){
	$counter++;
	$voucherID[$i]=$select_print_RV[$i];
	
	if($i==0){
		$alldata = $voucherID[$i];
	}else{
		$alldata = $alldata.",".$voucherID[$i];
	}
			
	$qry = "select * from v_thcap_temp_voucher_details_receive where \"voucherID\"='$voucherID[$i]'";
	
	if($detail = pg_query($qry)){
		
		    $res_detail = pg_fetch_array($detail);
			
			$voucherDate = $res_detail['voucherDate'];
			$voucherTime = $res_detail['voucherTime'];
			$doerFull = $res_detail['doerFull'];
			$doerStamp = $res_detail['doerStamp'];
			$appvFull = $res_detail['appvFull'];
			$appvStamp = $res_detail['appvStamp'];
			$auditFull = $res_detail['auditFull'];
			$auditStamp = $res_detail['auditStamp'];
			$voucherRemark = $res_detail['voucherRemark'];
			$fromChannelDetails = $res_detail['fromChannelDetails'];
			$abh_id = $res_detail['abh_id'];
			//จุดประสงค์
			$voucherPurpose = $res_detail["voucherPurpose"];
			if($voucherPurpose !=""){			
				$qry_purpose_name = pg_query("select \"thcap_purpose_name\" from account.\"thcap_purpose\" where thcap_purpose_id = '$voucherPurpose' ");
				$purpose_name = pg_fetch_result($qry_purpose_name,0);
			}else{
				$purpose_name="";
			}
			
			$qry_bookhead = pg_query("select abh_autoid from account.\"all_accBookHead\" where abh_refid = '$voucherID[$i]'");
			$abh_autoid = pg_fetch_result($qry_bookhead,0);
			
	}else{
		echo "Query Error!";
	}
?>
<div style="margin-top:20px;">
<table width="100%" bgcolor="#CAE1FF">
	<tr>
		<td><font color="blue"><b>รายการที่ : <?php echo $counter;?></b></font></td>
	</tr>
	<tr >
		<td align="left">
			<div>
				<table align="left">
					<tr>
						<td align="right"><b>รหัสใบสำคัญรับ:</b></td>
						<td align="right"><b>วันที่/เวลาใบสำคัญรับ :</b></td>
						<td align="right"><b>เลขที่บันทึกบัญชี :</b></td>
					</tr>
					<tr>
						<td><font color="red"><?php echo $voucherID[$i]; ?></font></td>
						<td><?php echo $voucherDate." / ".$voucherTime; ?></td>
						<td><font color="blue"><u><a onclick="javascript:popU('../accountEdit/frm_account_show.php?abh_autoid=<?php echo $abh_autoid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=550');" style="cursor:pointer;"><?php echo $abh_id; ?></a></u></font></td>
					</tr>
					<tr>
						<td align="right"><b>จุดประสงค์ :</b></td><td><?php echo $purpose_name; ?></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	
	<tr>
		<td align="left">
			<div>
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
		
		$qry_detail = pg_query("select * from account.\"all_accBookDetail\" where abd_autoidabh='$abh_autoid' order by  \"abd_autoid\" asc");
		$n = 0;
		$total_debit = 0;
		$total_credit = 0;
		while($res_detail = pg_fetch_array($qry_detail)){
			$n++;
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
				
					if($n%2==0){	
						echo "<tr bgcolor=\"#EDF8FE\" >";
					}else{
						echo "<tr >";
					}
					echo "<td>$abd_accBookID</td>";
					echo "<td>$accBookName</td>";
					echo "<td align=\"right\">$txtdebit</td>";
					echo "<td align=\"right\">$txtcredit</td>";
				echo "</tr>";
				
				$total_debit += $debit;
				$total_credit += $credit;
				
				$debit = 0;
				$credit = 0;
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
			<div >
				<table>
					<tr>
						<td><b>หมายเหตุ :</b></td>
						<td><?php echo $fromChannelDetails; ?></td>	
					</tr>
				</table>
			</div>
		</td>
	</tr>
	
</table>
</div>
<?php
} // end loop
?>
	<input type="hidden" name="set_voucherID" value="<?php echo $alldata; ?>"/>
	<div align="center" style="margin-top:20px;">
		<table>
			<tr>
				<td><b><font color="red">*</font>หมายเหตุที่ขอยกเลิก :</b> </td>
			</tr>
			<tr>
				<td><textarea name="note" id="note" cols="40" rows="5"></textarea></td>
			</tr>
		</table>
	</div>


	<div align="center" style="margin-top:20px;">
		<input type="submit" name="confirm_cancel" id="confirm_cancel" value="ยืนยันยกเลิกรายการรับ" onclick="return validate();">
		<input type="button" name="close" value=" ปิด " onclick="window.close();"/>
	</div>
	

</div>
</form>
</body>
<script>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

function validate(frm){
	var note = $('#note').val();
	var errorMes = "Error Message! \n";
	var chk = 0;
	
	if(note == ""){
		errorMes += "กรุณาระบุเหตุผลที่ข้อยกเลิกด้วย \n";
		chk++;
	}
	
	if(chk>0){
		alert(errorMes);
		return false;
	}
}
</script>
</html>