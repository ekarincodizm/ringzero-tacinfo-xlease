<?php
if($limit==""){
	include("../../config/config.php");
	$txthead="ประวัติการอนุมัติทั้งหมด";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ประวัติอนุมัติแก้ไขการผูกคนกับสัญญา</title>
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
				<td>รายละเอียด</td>
			</tr>
			<?php
			$qry_app=pg_query("select min(\"contempID\") as \"contempID\",a.\"IDNO\",b.\"fullname\" as \"userRequest\",a.\"userStamp\",c.\"fullname\" as \"appUser\",
			a.\"appStamp\",a.\"statusApp\",\"resultcancel\" from \"ContactCus_Temp\" a
			left join \"Vfuser\" b on a.\"userRequest\"=b.\"id_user\"
			left join \"Vfuser\" c on a.\"appUser\"=c.\"id_user\"
			where \"statusApp\" IN ('0','1') 
			group by a.\"IDNO\",b.\"fullname\",a.\"userStamp\",c.\"fullname\",a.\"appStamp\",a.\"statusApp\",\"resultcancel\"
			order by \"appStamp\" DESC $limit");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$contempID=$res_app["contempID"]; //รหัสอ้างอิง
				$IDNO=$res_app["IDNO"]; //เลขที่สัญญา
				$addUser=$res_app["userRequest"]; //ชื่อผู้ทำการแก้ไข
				$addStamp=$res_app["userStamp"]; //วันเวลาที่แก้ไข
				$appUser=$res_app["appUser"]; //ชื่อผู้อนุมัติ
				$appStamp=$res_app["appStamp"]; //วันเวลาที่อนุมัติ
				$appStatus=$res_app["statusApp"]; //สถานะการอนุมัติ
				
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#DDDDDD align=center onmouseover=\"javascript:this.bgColor = '#EECBAD';\" onmouseout=\"javascript:this.bgColor = '#DDDDDD';\">";
				}else{
					echo "<tr bgcolor=#EEEEEE align=center onmouseover=\"javascript:this.bgColor = '#EECBAD';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\">";
				}
				
			?>
				<td><span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ"><font color="red"><u><?php echo $IDNO;?></u></font></span></td>
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
					<img src="images/detail.gif" onclick="javascript:popU('show_history.php?contempID=<?php echo $contempID?>&IDNO=<?php echo $IDNO;?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=500')" style="cursor:pointer;">
				</td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=7 align=center height=50><b>- ไม่พบประวัติ -</b></td></tr>";
			}else{
				echo "<tr bgcolor=\"#CCCCCC\"><td colspan=78><b><b>มีทั้งหมด $nub รายการ</b></b></td></tr>";				
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>