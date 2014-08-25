<?php
if($limit==""){
	include("../../../../../config/config.php");
	require_once("../../sys_setup.php");
	$txthead="ประวัติการอนุมัติทั้งหมด";
	$realpath = redirect($_SERVER['PHP_SELF'],'');
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ประวัติการอนุมัติยกเลิกสัญญาเข้าร่วม</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="10" align="left" style="font-weight:bold;"><?php echo $txthead;?> <?php if($limit=="limit 30"){?>(<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=650')"><u>ทั้งหมด</u></a>)<?php } ?></td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#CCCCCC" align="center">
				<td>เลขที่สัญญา</td>
				<td>ชื่อลูกค้า</td>
				<td>ทะเบียนรถยนต์</td>
				<td>ผู้ขอยกเลิก</td>
				<td>วันเวลาที่ขอยกเลิก</td>
				<td>ผู้อนุมัติ</td>
				<td>วันเวลาที่อนุมัติ</td>
				<td>สถานะอนุมัติ</td>
				<td>ข้อมูลเข้าร่วม</td>
				<td>รายละเอียด</td>
			</tr>
			<?php
			$qry_app=pg_query("select a.\"deleteid\",b.\"cpro_name\",b.\"idno\",b.\"id\",b.\"car_license\",c.\"fullname\" as \"userRequest\",
			a.\"userStamp\",d.\"fullname\" as \"appUser\",a.\"appStamp\",a.\"appStatus\" from \"ta_join_main_delete_temp\" a
			left join \"VJoinMain\" b on a.\"id\"=b.\"id\"
			left join \"Vfuser\" c on a.\"userRequest\"=c.\"id_user\"
			left join \"Vfuser\" d on a.\"appUser\"=d.\"id_user\"
			where \"appStatus\" in('0','1') order by \"appStamp\"  DESC $limit");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$deleteid=$res_app["deleteid"]; //รหัสรายการที่ยกเลิก
				$IDNO=$res_app["idno"]; //เลขที่สัญญา
				$ID=$res_app["id"]; //เลขที่สัญญา
				$cpro_name=$res_app["cpro_name"]; //ชื่อลูกค้า
				$car_license = $res_app['car_license']; //ทะเบียนรถยนต์
				$addUser=$res_app["userRequest"]; //ชื่อผู้ทำการขอยกเลิก
				$addStamp=$res_app["userStamp"]; //วันเวลาที่ขอยกเลิก
				$appUser=$res_app["appUser"]; //ผู้อนุมัติรายการ
				$appStamp=$res_app["appUser"]; //วันเวลาที่ขอนุมัติ
				$appStatus=$res_app["appStatus"]; //สถานะการอนุมัติ
				
				$sql_query5=pg_query("select v.full_name,v.\"C_REGIS\",v.\"P_FDATE\",v.\"P_TOTAL\",v.\"P_ACCLOSE\" from \"VJoin\" v WHERE v.\"IDNO\" = '$IDNO' ");
				if($sql_row5 = pg_fetch_array($sql_query5))
				{	
					$start_contract_date  = date_ch_form_c($sql_row5['P_FDATE']);
					
					if($cancel==0 && $P_ACCLOSE=='f'){	 
						$car_license=$sql_row5["C_REGIS"]; //ทะเบียนรถยนต์
						$cpro_name= $sql_row5['full_name'];	 //ชื่อลูกค้า
					}
				}
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#DDDDDD align=center>";
				}else{
					echo "<tr bgcolor=#EEEEEE align=center>";
				}
				
			?>
				<td><span onclick="javascript:popU('<?php echo $realpath; ?>post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><font color="red"><u><?php echo $IDNO;?></u></font></span></td>				
				<td align="left"><?php echo $cpro_name; ?></td>
				<td><?php echo $car_license; ?></td>
				<td align="left"><?php echo $addUser; ?></td>
				<td><?php echo $addStamp; ?></td>			
				<td><?php echo $appUser; ?></td>			
				<td><?php echo $appStamp; ?></td>			
				<td>	
					<?php 
					if($appStatus==0){
						echo "ไม่อนุมัติ";
					}else{
						echo "อนุมัติ";
					}
					?>
				</td>
				<td><img src="../images/open.png" width="16" height="16" onclick="javascript:popU('<?php echo $realpath; ?>nw/join_payment/extensions/ta_join_payment/pages/ta_join_payment_view_new.php?id=<?php echo $ID; ?>&readonly=t&deleteid=<?php echo $deleteid; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1050,height=600')" style="cursor:pointer"></td>
				<td>	
					<img src="../images/detail.gif" onclick="javascript:popU('show_approve.php?deleteid=<?php echo $deleteid?>&readonly=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=450')" style="cursor:pointer;">
				</td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=10 align=center height=50><b>- ไม่พบประวัติอนุมัติ -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>