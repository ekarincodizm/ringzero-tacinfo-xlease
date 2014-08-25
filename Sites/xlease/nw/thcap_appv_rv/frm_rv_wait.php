<?php 
$sort = pg_escape_string($_GET["descOrascby"]);
$orderby = pg_escape_string($_GET["orderby"]);
if($orderby == ""){
	$orderby = "\"doerStamp\" ";
}
if($sort == ""){
	$sort = "DESC";
}
$qry_app = pg_query("select * from thcap_temp_voucher_pre_details where \"voucherType\" ='2' and \"appvStatus\" = '9' order by $orderby $sort ");
$sortnew = $sort == 'DESC' ? 'ASC' : 'DESC';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>ใบสำคัญรับที่รออนุมัติ</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="act.css"></link>

	<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>
	<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
		<tr bgcolor="#FFFFFF">
			<td colspan="11" align="left" style="font-weight:bold;">ใบสำคัญรับที่รออนุมัติ</td>
		</tr>
		<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
			<td>ลำดับ</td>
			<td><a href='frm_index.php?orderby=<?php echo "\"voucherDate\""?>&descOrascby=<?php echo $sortnew ?>'><u>วันที่มีผล</u></td>
			<td>เวลาที่มีผล </td>
			<td>จุดประสงค์</td>
			<td>จำนวนเงิน</td>
			<td>ผู้ทำรายการ</td>
			<td><a href='frm_index.php?orderby=<?php echo "\"doerStamp\""?>&descOrascby=<?php echo $sortnew ?>'><u>วันเวลาที่ทำรายการ</u></td>
			<td>รายละเอียด</td>
		</tr>
		<?php
		
		$nub = pg_num_rows($qry_app);
		$i=0;
		while($res_app=pg_fetch_array($qry_app))
		{
			$sum_debit=0;
			
			$prevoucherdetailsid = $res_app["prevoucherdetailsid"];
			$voucherDate = $res_app["voucherDate"];
			$voucherTime = $res_app["voucherTime"];
			$doerID = $res_app["doerID"];
			$doerStamp = $res_app["doerStamp"];
			$arrayaccbookserial = $res_app["arrayaccbookserial"];
			$arrayabd_booktype = $res_app["arrayabd_booktype"];
			$arrayabd_amount = $res_app["arrayabd_amount"];
			
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
			
			$i++;				
			//หาผลรวม เดบิต	
					
			$query=("SELECT sum( \"arrayabd_amount\"[temp_table.c_type]::numeric(15,2))  as \"sum_debit\"  FROM (SELECT varray.a as \"id\" ,varray.type as \"c_type\",\"arrayabd_amount\" FROM (SELECT generate_subscripts(\"arrayabd_booktype\",1) as \"type\",\"prevoucherdetailsid\" as \"a\",\"arrayabd_booktype\",\"arrayabd_amount\"
			from \"thcap_temp_voucher_pre_details\" 
			order by a DESC ) varray 
			where \"arrayabd_booktype\"[varray.type] = '1' )temp_table 
			where temp_table.\"id\"='$prevoucherdetailsid'");
			$resold=pg_query($query);
			list($sum_debit)=pg_fetch_array($resold);
			$sum_debit = number_format($sum_debit,2);
			
			
			
			if($i%2==0){
				echo "<tr class=\"odd\" align=center>";
			}else{
				echo "<tr class=\"even\" align=center>";
			}
		?>
			<td><?php echo $i; ?></td>
			<td align="center"><?php echo $voucherDate; ?></td>				
			<td align="center"><?php echo $voucherTime; ?></td>
			<td align="left"><?php echo $purpose_name; ?></td>
			<td align="right"><?php echo $sum_debit; ?></td>
			<td align="left"><?php echo $doerName; ?></td>
			<td><?php echo $doerStamp; ?></td>
			<?php 
			
			if($sendfrom=='1'){ ?>
				<td><span onclick="javascript:popU('../thcap_appv_rv/frm_rv_detail.php?page=Y&autoID=<?php echo $prevoucherdetailsid?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700')" style="cursor:pointer;"><u>ตรวจสอบ</u></span></td>
			<?php } else{?>
				<td><span onclick="javascript:popU('frm_rv_detail.php?page=Y&autoID=<?php echo $prevoucherdetailsid?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=700')" style="cursor:pointer;"><u>ตรวจสอบ</u></span></td>
			<?php }?>
		</tr>
		<?php
		} //end while
		
		if($nub == 0)
		{
			echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
		}
		?>
	</table>
</body>
</html>