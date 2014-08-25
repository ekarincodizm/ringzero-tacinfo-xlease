<?php 
include("../../config/config.php");
	//ค้นหาข้อมูล ทั้ง  jv,pv
	$qry = "select a.* from \"thcap_temp_voucher_details\" a
	left join \"thcap_temp_voucher_details_reprint\" b on a.\"voucherID\"=b.\"voucherID\"
	where a.\"voucherStatus\" <> '0' and b.\"voucherID\"  is  null order by a.\"appvStamp\" DESC";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใบสำคัญรอพิมพ์ส่ง</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
	<div style="margin-top:10px;" align="center"><h1>(THCAP) ใบสำคัญรอพิมพ์ส่ง</h1></div>
<form name="frm" action="process_pdf_voucher.php" method="post" target="_blank">
	<div style="margin-top:10px;"align="center">
		<table cellpadding="5" cellspacing="0" border="0" width="80%" bgcolor="#F0F0F0" align="center">
			<tr bgcolor="white">
				<td colspan="3" align="left"><font size="3" color="blue"><b>ใบสำคัญรอพิมพ์ส่ง</b></font></td>
				<td colspan="7" align="right">
					<input type="button" name="PrintPDF" id="PrintPDF" value="PrintPDF" onclick="validate(this.form,'PDF');"/>
				</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>ลำดับที่</td>
				<td>รหัสใบสำคัญ</td>
				<td>วันที่ใบสำคัญมีผล</td>
				<td>เวลาใบสำคัญมีผล</td>
				<td>เลขที่บันทึกบัญชี</td>
				<td>ผู้ำทำรายการ</td>
				<td>วันเวลาที่ทำรายการ</td>
				<td>ผู้ำอนุมัติรายการ</td>
				<td>วันเวลาที่ทำรายการ</td>
				<td><span id="selectAll" style="cursor:pointer;"><u><font color="blue">เลือกรายการ</font></u></span></td>				
			</tr>
			<?php 
				$query_list = pg_query($qry);
				$num_row = pg_num_rows($query_list);
				if($num_row>0){
					$i = 0;
					while($res_v = pg_fetch_array($query_list)){
						$i++;
						$voucherID = $res_v['voucherID'];
						$voucherDate = $res_v['voucherDate'];
						$doerFull = $res_v['doerFull'];
						$doerStamp = $res_v['doerStamp'];
						$abh_id = $res_v['abh_id'];
						$voucherTime = $res_v['voucherTime'];
						$voucherCancelRef = $res_v['voucherCancelRef'];
						$appvFull = $res_v['appvFull'];
						$appvStamp = $res_v['appvStamp'];
						$voucherType = $res_v['voucherType'];
						$qry_bookhead = pg_query("select abh_autoid from account.\"all_accBookHead\" where abh_refid = '$voucherID'");
						$abh_autoid = pg_fetch_result($qry_bookhead,0);
						

						
					if($i%2==0){	
						echo "<tr class=\"odd\" >";
						
					}else{
						echo "<tr class=\"even\">";						
					}
							echo "<td align=\"center\">$i</td>";
							if($voucherType =='1'){
								echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=$voucherID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');\" style=\"cursor:pointer;\">$voucherID</a></u></font></td>";
							}
							else if($voucherType =='2'){
								echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('../thcap_receive_voucher/frm_voucher_channel_detail.php?voucherID=$voucherID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');\" style=\"cursor:pointer;\">$voucherID</a></u></font></td>";
							}
							else if($voucherType =='3'){
								echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('../thcap_journal_voucher/voucher_channel_detail.php?voucherID=$voucherID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');\" style=\"cursor:pointer;\">$voucherID</a></u></font></td>";
							}
							
							echo "<td align=\"left\">$voucherDate</td>";
							echo "<td align=\"left\">$voucherTime</td>";
							echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('../accountEdit/frm_account_show.php?abh_autoid=$abh_autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=550');\" style=\"cursor:pointer;\">$abh_id</a></u></font></td>";
							echo "<td align=\"left\">$doerFull</td>";
							echo "<td align=\"left\">$doerStamp</td>";
							echo "<td align=\"left\">$appvFull</td>";
							echo "<td align=\"left\">$appvStamp</td>";							
							echo "<td align=\"center\"><input type=\"checkbox\" name=\"select_print[]\" id=\"select_print$i\" value=\"$voucherID\"></td>";
							
						echo "</tr>";
					}
				}else{
					echo "<tr><td colspan=\"10\" align=\"center\">ไม่พบข้อมูล</td></tr>";
				}
			?>
			<tr cellspacing="10px" bgcolor="#79BCFF">
				<td colspan="10" align="right">
					<input type="hidden" id="AllorClear" value="A"/>
					<input type="hidden" id="frompage" name="frompage" value="reprint"/>
					<input type="button" name="PrintPDF" id="PrintPDF" value="PrintPDF" onclick="validate(this.form,'PDF');"/>
				</td>
			</tr>
		</table>
	</div>
</form>

</body>
<script>
$("#selectAll").click(function(){
	var select = $("input[name=select_print[]]");
	var chkBT = $("#AllorClear").val();
	var num = 0;
	
	if(chkBT=="A"){
		for(i=0; i<select.length; i++){
			$(select[i]).attr("checked","checked");
		}
		$("#AllorClear").val('C');
	}else{
		for(i=0; i<select.length; i++){
			$(select[i]).removeAttr("checked");
		}
		$("#AllorClear").val('A');
	}
});

function validate(frm,method){
	
	var select = $("input[name=select_print[]]:checked");
	var ErrorMessage = "Error Message! \n";
	var Error = 0;
	if(select.length<1){
		ErrorMessage += "กรุณาเลือกรายการที่ต้องการ Print";
		Error++;
	}

	if(Error>0){
		alert(ErrorMessage);
		return false;
	}else{
		if(method == "PDF"){			
			frm.submit();
		}
	} 
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</html>	