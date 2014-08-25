<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ประวัติการอนุมัติใบสำคัญจ่าย</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>

	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
	<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		<tr bgcolor="#FFFFFF">
			<td colspan="11" align="left" style="font-weight:bold;">ประวัติการอนุมัติใบสำคัญจ่าย 30 รายการล่าสุด  <input type="button" value="แสดงประวัติทั้งหมด" onclick="javascript:popU('pv_temp.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')" style="cursor:pointer;"></td>
		</tr>
		<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
			<td>ลำดับ</td>
			<td>วันที่มีผล</td>
			<td>เวลาที่มีผล</td>
			<td>จุดประสงค์ </td>
			<td>จำนวนเงิน</td>
			<td>ผู้ขอทำรายการ</td>
			<td>วันเวลาที่ทำรายการ</td>
			<td>ผู้ทำรายการอนุมัติ</td>
			<td>วันเวลาที่ทำรายการอนุมัติ</td>
			<td>ผลการอนุมัติ</td>
			<td>รายละเอียด</td>
		</tr>
		<?php
		$qry_app = pg_query("select * from thcap_temp_voucher_pre_details where \"appvStatus\" <>'9' and \"voucherType\" ='1'  order by \"appvStamp\" DESC limit 30 ");
		$nub = pg_num_rows($qry_app);
		$i=0;
		while($res_app=pg_fetch_array($qry_app))
		{
			$prevoucherdetailsid = $res_app["prevoucherdetailsid"];
			$voucherDate = $res_app["voucherDate"];
			$voucherTime = $res_app["voucherTime"];
			$doerID = $res_app["doerID"];
			$doerStamp = $res_app["doerStamp"];
			$appvID = $res_app["appvID"];
			$appvStamp = $res_app["appvStamp"];
			$appvStatus = $res_app["appvStatus"];
			
			//จุดประสงค์
			$voucherPurpose = $res_app["voucherPurpose"];
			if($voucherPurpose !=""){			
				$qry_purpose_name = pg_query("select \"thcap_purpose_name\" from account.\"thcap_purpose\" where thcap_purpose_id = '$voucherPurpose' ");
				$purpose_name = pg_fetch_result($qry_purpose_name,0);
			}else{
				$purpose_name="";
			}
			
			$qry_doername = pg_query("select fullname from \"Vfuser\" where id_user = '$doerID' ");
			$doerName = pg_fetch_result($qry_doername,0);
			
			$qry_appv = pg_query("select fullname from \"Vfuser\" where id_user = '$appvID' ");
			$appvName = pg_fetch_result($qry_appv,0);
			
			if($appvStatus == 0)
			{
				$textStatus = "<font color=\"#FF0000\"><b>ไม่อนุมัติ</b></font>";
			}
			elseif($appvStatus == 1)
			{
				$textStatus = "<font color=\"#0000FF\"><b>อนุมัติโดยระบบ</b></font>";
			}
			elseif($appvStatus == 2)
			{
				$textStatus = "<font color=\"#00FF00\"><b>อนุมัติ</b></font>";
			}
			else
			{
				$textStatus = "";
			}
			
			//จำนวนเงิน
			if($appvStatus == 0){
				//จำนวนเงิน โดย หาผลรวม เดบิต (เนื่องจาก ต้อง รองรับในกรณีที่ไม่อนุมัติ ด้วย ทำให้  ไม่สามารถใช้ account.thcap_get_accbook_amt ได้)
				$query=("SELECT sum( \"arrayabd_amount\"[temp_table.c_type]::numeric(15,2))  as \"sum_debit\"  FROM (SELECT varray.a as \"id\" ,varray.type as \"c_type\",\"arrayabd_amount\" FROM (SELECT generate_subscripts(\"arrayabd_booktype\",1) as \"type\",\"prevoucherdetailsid\" as \"a\",\"arrayabd_booktype\",\"arrayabd_amount\"
				from \"thcap_temp_voucher_pre_details\" 
				where \"prevoucherdetailsid\"='$prevoucherdetailsid' ) varray 
				where \"arrayabd_booktype\"[varray.type] = '1' )temp_table 
				where temp_table.\"id\"='$prevoucherdetailsid'");
				$resold=pg_query($query);
				list($sum_debit)=pg_fetch_array($resold);
				$sum_debit = number_format($sum_debit,2);
			}
			elseif(($appvStatus == 1) or ($appvStatus == 2)){				
				$qry_abh_id = pg_query("select \"abh_id\" from \"thcap_temp_voucher_details\" where prevoucherdetailsid = '$prevoucherdetailsid'");
				$abh_id = pg_fetch_result($qry_abh_id,0);
				$qry_accbook_amt = pg_query("select account.\"thcap_get_accbook_amt\"('$abh_id')");
				$sum_debit = pg_fetch_result($qry_accbook_amt,0);
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
			<td align="center"><?php echo $voucherDate;?></td>
			<td align="center"><?php echo $voucherTime; ?></td>
			<td align="left"><?php echo $purpose_name; ?></td>
			<td align="right"><?php echo $sum_debit; ?></td>
			<td><?php echo $doerName; ?></td>
			<td align="center"><?php echo $doerStamp; ?></td>
			<td><?php echo $appvName; ?></td>
			<td align="center"><?php echo $appvStamp; ?></td>
			<td align="center"><?php echo $textStatus; ?></td>
			<td><span onclick="javascript:popU('pv_detail_view.php?autoID=<?php echo $prevoucherdetailsid?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700')" style="cursor:pointer;"><u>ตรวจสอบ</u></span></td>
		</tr>
		<?php
		} //end while
		
		if($nub == 0)
		{
			echo "<tr><td colspan=11 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
		}
		?>
	</table>
</body>
</html>