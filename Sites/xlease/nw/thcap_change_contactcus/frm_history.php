<?php
if($limit==""){
	include("../../config/config.php");
	$txthead="ประวัติการอนุมัติทั้งหมด";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ประวัติเปลี่ยนลำดับคนในสัญญา</title>
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
				<td colspan="8" align="left" style="font-weight:bold;"><?php echo $txthead;?> <?php if($limit=="limit 30"){?>(<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=650')"><u>ทั้งหมด</u></a>)<?php } ?></td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#CCCCCC" align="center">
				<td>เลขที่สัญญา</td>
				<td>ผู้แก้ไขรายการ</td>
				<td>วันเวลาที่แก้ไข</td>
				<td>ผู้อนุมัติ</td>
				<td>วันเวลาที่อนุมัติ</td>
				<td>สถานะอนุมัติ</td>
				<td>หมายเหตุ</td>
				<td>รายละเอียด</td>
			</tr>
			<?php
			$qry_app=pg_query("select min(auto_id) as \"auto_id\",a.\"contractID\",b.\"fullname\" as \"addUser\",a.\"addStamp\",c.\"fullname\" as \"appUser\",a.\"appStamp\",a.\"appStatus\",\"result\" from \"thcap_ContactCus_Temp\" a
			left join \"Vfuser\" b on a.\"addUser\"=b.\"id_user\"
			left join \"Vfuser\" c on a.\"appUser\"=c.\"id_user\"
			where \"appStatus\" IN ('0','1') 
			group by a.\"contractID\",b.\"fullname\",a.\"addStamp\",c.\"fullname\",a.\"appStamp\",a.\"appStatus\",\"result\"
			order by \"appStamp\" DESC $limit");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$auto_id=$res_app["auto_id"]; //เลขที่สัญญา
				$contractID=$res_app["contractID"]; //เลขที่สัญญา
				$addUser=$res_app["addUser"]; //ชื่อผู้ทำการแก้ไข
				$addStamp=$res_app["addStamp"]; //วันเวลาที่แก้ไข
				$appUser=$res_app["appUser"]; //ชื่อผู้อนุมัติ
				$appStamp=$res_app["appStamp"]; //วันเวลาที่อนุมัติ
				$appStatus=$res_app["appStatus"]; //สถานะการอนุมัติ
				$result=$res_app["result"]; //หมายเหตุหรือเหตุผลในการอนุมัติหรือไม่อนุมัติ
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#DDDDDD align=center>";
				}else{
					echo "<tr bgcolor=#EEEEEE align=center>";
				}
				
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td align="left"><?php echo $addUser; ?></td>
				<td><?php echo $addStamp; ?></td>			
				<td align="left"><?php echo $appUser; ?></td>			
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
				<td>
				<?php
				if($result==""){
					echo "-";
				}else{
				?>
				<img src="images/full_page.png" width="19" height="19" style="cursor:pointer;" onclick="javascript:popU('show_result.php?auto_id=<?php echo $auto_id?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=400,height=300')">
				<?php } ?>
				</td>
				<td>	
					<img src="images/detail.gif" onclick="javascript:popU('show_history.php?auto_id=<?php echo $auto_id?>&contractID=<?php echo $contractID;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor:pointer;">
				</td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=8 align=center height=50><b>- ไม่พบประวัติอนุมัติ -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>