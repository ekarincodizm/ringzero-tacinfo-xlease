<?php
if(($status==1)or($chkstatus=="1")){
	$limit="limit 30";
	$txthead="ประวัติการอนุมัติ 30 รายการล่าสุด (<a style=\"color:#0099FF;cursor:pointer;\" onclick=\"javascript:popU('join_add_money_approve_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')\"><u>ทั้งหมด</u></a>)";
}else{
	require_once("../../sys_setup.php");
	include("../../../../../config/config.php");
	$limit="";
	$txthead="ประวัติการอนุมัติทั้งหมด";
	
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html>
	<head>
		<title>ประวัติการอนุมัติเพิ่มเงินเข้าระบบเข้าร่วมทั้งหมด</title>
		<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
		<script src="../<?php echo $lo_ext_current_temp ?>scripts/jquery-1.3.2.min.js" type="text/javascript"></script>
		<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-1.7.1.min.js" type="text/javascript"></script>
		<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-ui-1.8.19.custom.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="../<?php echo $lo_ext_current_temp ?>scripts/css/ui-lightness/jquery-ui-1.8.1.custom.css" />
		<link type="text/css" rel="stylesheet" href="act.css"></link>

		
	</head>
	<body>
		<style type="text/css">
		table.t2 tr:hover td {
			background-color:pink;
		}
		</style>
	<?php
}

$qry_fr=pg_query("SELECT f.id,f.\"amount\",f.pay_date,f.approve_status,f.create_by,f.create_datetime,f.\"memo_app\",m.car_license,m.cpro_name,m.idno,m.id as tt,
n.fullname,f.approve_dt
FROM \"ta_join_add_money_app\" f 
left join \"VJoinMain\" m on m.id = f.\"id_main\"
left join \"Vfuser\" n on f.approver=n.\"id_user\"
WHERE f.approve_status!=0 order by f.approve_dt desc $limit");
$nub=pg_num_rows($qry_fr);

echo "<center><h3>$txthead</center>"; ?>
<table class="t2" width="95%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
	<td align="center">ลำดับ</td>
	<td align="center">เลขที่สัญญา</td>
	<td align="center">เลขทะเบียนรถ</td>
	<td align="center">ชื่อลูกค้า</td>
	<td align="center">วันที่ชำระ</td>
	<td align="center">จำนวนเงิน</td>
	<td align="center">ผู้ขออนุมัติ</td>
	<td align="center">วันเวลาที่ขอ</td>
	<td align="center">ผู้อนุมัติ</td>
	<td align="center">วันเวลาที่อนุมัติ</td>
	<td>รายละเอียด</td>
	<td>สถานะ</td>
</tr>
<?php
while($sql_row4=pg_fetch_array($qry_fr)){
	$id = $sql_row4['id'];
	$tt = $sql_row4['tt'];
	$cpro_name = $sql_row4['cpro_name'];
	$pay_date = $sql_row4['pay_date'];
	$car_license = $sql_row4['car_license'];
	$create_datetime =$sql_row4['create_datetime']; 
	$memo_app =$sql_row4['memo_app']; 
	$approve_status = $sql_row4['approve_status'];
	$O_MONEY =$sql_row4['amount']; 
	$create_by = $sql_row4['create_by'];//เอาไปหา id_card ด้วย
	$idno = trim($sql_row4['idno']);
	$approve_user = trim($sql_row4['fullname']);
	$approve_dt = trim($sql_row4['approve_dt']);

	if($memo_app!="")$memo_app = " - ".$memo_app ;
	$dt = $create_datetime;
	$by = $create_by;

	if($approve_status=='1'){$st_txt = "อนุมัติแล้ว $memo_app";}
	else if($approve_status=='2'){$st_txt = "ไม่อนุมัติ $memo_app";}

	$res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$by'");
	$res_userprofile=pg_fetch_array($res_profile);
	$by=  $by."-".$res_userprofile["fullname"];

	$i2+=1;
	if($i2%2==0){
		echo "<tr class=\"odd\" align=center>";
	}else{
		echo "<tr class=\"even\" align=center>";
	}
	?>
		<td align="center"><?php echo $i2; ?></td>
		<td><u><a href="javascript:popU('ta_join_payment_view_new.php?id=<?php print $tt ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=768')"><?Php print $idno ?></a></u></td>
		<td align="left"><?php echo $car_license; ?></td>
		<td align="left"><?php echo $cpro_name; ?></td>
		<td><?php echo $pay_date; ?></td>
		<td align="right"><?php echo number_format($O_MONEY); ?></td>
		<td align="left"><?php echo $by; ?></td>
		<td><?php echo $dt; ?></td>
		<td align="left"><?php echo $approve_user; ?></td>
		<td><?php echo $approve_dt; ?></td>
		<?php if($chkstatus=="1"){?>
		<td><img src="../images/detail.gif" style="cursor:pointer" onclick="javascript:show_p('<?php echo $id; ?>','1')" height="19" width="19" border="0">
		<?php }else {?>
		<td><img src="../images/detail.gif" style="cursor:pointer" onclick="javascript:show_p1('<?php echo $id; ?>','1')" height="19" width="19" border="0">
		<?php }?>
		<td align="left"><?php echo $st_txt; ?></td>
	</tr>
<?php
}
if($nub == 0){
	echo "<tr><td colspan=10 align=center ><b>- ไม่พบข้อมูล -</b></td></tr>";
}
?>
</table>
<script language=javascript>
			function popU(U,N,T){
				newWindow = window.open(U, N, T);
			}
			function show_p1(id,f_d){
				var h ;
				if(f_d=='')h=355;else h=270;
					$('body').append('<div id="dialog"></div>');

					$('#dialog').load('add_money_app_popup.php?id='+id+'&f_d='+f_d);
					$('#dialog').dialog({
						title: 'รายละเอียด ',
						resizable: true,
						modal: true,  
						width: 650,
						height: h,
						close: function(ev, ui){
							$('#dialog').remove();
						}
					});	
			}			
			
		</script>
</body>
</html>