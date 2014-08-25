<?php
include("../../config/config.php");
$qry_dc = pg_query("select * from thcap_check_acc_debit_credit_amt_data order by \"abh_id\" ");
$rows_dc = pg_num_rows($qry_dc);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

IF($rows_dc == 0){
	echo "<center><h2> ไม่พบข้อมูลที่ผิดปกติ </h2>";
	echo "<input type=\"button\" value=\" ปิด \"  onclick=\"window.close();\" style=\"width:70px;height:50px;\"></center>";
	exit();
}

?>

<title>(THCAP) ตรวจสอบรายการผิดปกติในระบบ</title>
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div align="center" >
	<div style="padding-top:10px;" align="right"><input type="button" value=" พิมพ์ " onclick="window.print();" style="width:70px;height:50px;"><input type="button" value=" ปิด " onclick="window.close();" style="width:70px;height:50px;"></div>
	<h3>ตรวจสอบตารางการจ่ายค่างวด วันที่สิ้นสุดสัญญา และตารางลูกหนี้</h3>
	<table frame="box" width="95%">
		<tr bgcolor="#CDC5BF" >
			<th>รายการที่</th>
			<th>เลขที่รายการ</th>
			<th>เลขที่เอกสารต้นทาง</th>
			<th>จำนวนเงินด้านเดบิต</th>
			<th>จำนวนเงินด้านเครดิต</th>
			<th>ยกเลิกการตรวจสอบรายการ</th>
		</tr>
		<?php
			$i = 0;			
				while($result_dc = pg_fetch_array($qry_dc))
				{
					$i++;
					$abh_autoid = $result_dc["abh_autoid"];
					
					$DCpopup = "<a onclick=\"javascript:popU('../accountEdit/frm_account_show.php?abh_autoid=".$result_dc["abh_autoid"]."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1150,height=650')\" style=\"cursor:pointer;\" ><u>".$result_dc["abh_id"]."</u></a>";
					$DELpopup = "<img src=\"../thcap/images/del.png\" width=\"19\" height=\"19\" onclick=\"javascript:popU('popup_15.php?abh_autoid=$result_dc[abh_autoid]','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=700')\" style=\"cursor:pointer;\" />";
					
					// หา เลขที่เอกสารต้นทาง
					$qry_m = pg_query("select \"abh_reftype\", \"abh_refid\"
										from account.\"all_accBookHead\" where  \"abh_autoid\" = '$abh_autoid'");
					$abh_reftype = pg_fetch_result($qry_m,0);
					$abh_refid = pg_fetch_result($qry_m,1);
					
					// กำหนด popup เลขที่เอกสารต้นทาง
					if($abh_reftype == '1')
					{
						$REFpopup = "<a onclick=\"javascript:popU('../thcap_payment_voucher/voucher_channel_detail.php?voucherID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720')\" style=\"cursor:pointer;\" ><u>$abh_refid</u></a>";
					}
					elseif($abh_reftype == '2')
					{
						$REFpopup = "<a onclick=\"javascript:popU('../thcap_receive_voucher/frm_voucher_channel_detail.php?voucherID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720')\" style=\"cursor:pointer;\" ><u>$abh_refid</u></a>";
					}
					elseif($abh_reftype == '3')
					{
						$REFpopup = "<a onclick=\"javascript:popU('../thcap_journal_voucher/voucher_channel_detail.php?voucherID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720')\" style=\"cursor:pointer;\" ><u>$abh_refid</u></a>";
					}
					elseif($abh_reftype == '0')
					{
						$REFpopup = "<a onclick=\"javascript:popU('../thcap/Channel_detail.php?receiptID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720')\" style=\"cursor:pointer;\" ><u>$abh_refid</u></a>";
					}
					elseif($abh_reftype == '998')
					{
						$REFpopup = "<a onclick=\"javascript:popU('../thcap/Channel_detail_v.php?receiptID=$abh_refid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720')\" style=\"cursor:pointer;\" ><u>$abh_refid</u></a>";
					}
					else
					{
						$REFpopup = $abh_refid;
					}
					
					if($i%2==0){
						echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
					}else{
						echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
					}
				
					echo "<td align=\"center\">".$i."</td>";
					echo "<td align=\"center\">".$DCpopup."</td>";
					echo "<td align=\"center\">".$REFpopup."</td>";
					echo "<td align=\"right\">".number_format($result_dc["debit"],2)."</td>";
					echo "<td align=\"right\">".number_format($result_dc["credit"],2)."</td>";
					echo "<td align=\"center\">".$DELpopup."</td>";
					echo "<tr>";
				}
			echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"6\">รวม ".number_format($rows_dc,0)." รายการ</td></tr>";
		?>
	</table>
</div>
</body>
</html>