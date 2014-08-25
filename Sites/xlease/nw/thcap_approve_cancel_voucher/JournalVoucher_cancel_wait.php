<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ใบสำคัญรายวันทั่วไปที่รออนุมัติยกเลิก</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>

	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
	<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		<tr bgcolor="#FFFFFF">
			<td colspan="7" align="left" style="font-weight:bold;">ใบสำคัญรายวันทั่วไปที่รออนุมัติยกเลิก</td>
		</tr>
		<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
			<td>ลำดับ</td>
			<td>รหัสใบสำคัญรายวันทั่วไป</td>
			<td>จุดประสงค์</td>
			<td>จำนวนเงิน</td>
			<td>ผู้ขอยกเลิก</td>
			<td>วันเวลาที่ขอยกเลิก</td>
			<td>รายละเอียด</td>
		</tr>
		<?php
		$qry_app = pg_query("select * from v_thcap_temp_voucher_journal_wait_cancel where \"appvStatus\" = '9' order by \"doerStamp\" ASC ");
		$nub = pg_num_rows($qry_app);
		$i=0;
		while($res_app=pg_fetch_array($qry_app))
		{
			$autoID = $res_app["autoID"];
			$voucherID = $res_app["voucherID"];
			$doerName = $res_app["doerName"];
			$doerStamp=$res_app["doerStamp"];
			$doerRemark=$res_app["doerRemark"];
			$voucherPurpose=$res_app["voucherPurpose"];
			
			if($voucherPurpose !=""){			
				$qry_purpose_name = pg_query("select \"thcap_purpose_name\" from account.\"thcap_purpose\" where thcap_purpose_id = '$voucherPurpose' ");
				$purpose_name = pg_fetch_result($qry_purpose_name,0);
			}else{
				$purpose_name="";
			}
			//จำนวนเงิน
			$qry_accbook_amt = pg_query("select \"thcap_get_voucher_amt\"('$voucherID')");
			$sum_debit = pg_fetch_result($qry_accbook_amt,0);
			if($sum_debit !=""){
				$sum_debit = number_format($sum_debit,2);
			}	
			$i++;
			if($i%2==0){
				echo "<tr class=\"odd\" align=center>";
			}else{
				echo "<tr class=\"even\" align=center>";
			}
		?>
			<td><?php echo $i; ?></td>
			<td align="center"><span onclick="javascript:popU('../thcap_journal_voucher/voucher_channel_detail.php?voucherID=<?php echo $voucherID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700')" style="cursor:pointer;"><font color="blue"><u><?php echo $voucherID;?></u></font></span></td>
			<td align="left"><?php echo $purpose_name; ?></td>
			<td align="right"><?php echo $sum_debit; ?></td>
			<td align="left"><?php echo $doerName; ?></td>
			<td><?php echo $doerStamp; ?></td>
			<td><span onclick="javascript:popU('../thcap_approve_cancel_voucher/JournalVoucher_cancel_detail.php?autoID=<?php echo $autoID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700')" style="cursor:pointer;"><u>ตรวจสอบ</u></span></td>
		</tr>
		<?php
		} //end while
		
		if($nub == 0)
		{
			echo "<tr><td colspan=7 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
		}
		?>
	</table>
</body>
</html>