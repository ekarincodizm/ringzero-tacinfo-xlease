<?php
session_start();
include("../../config/config.php");
$id_user=$_SESSION["av_iduser"];
if( empty($id_user) ){
    header("Location:../../index.php");
    exit;
}

//หาว่าพนักงานมี emplevel เท่าไหร่
$qrylevel=pg_query("select ta_get_user_emplevel('$id_user')");
list($emplevel)=pg_fetch_array($qrylevel);

$app_date = nowDateTime();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติเปลี่ยนลำดับคนในสัญญา </title>
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
		<div class="header"><h2>(THCAP) อนุมัติเปลี่ยนลำดับคนในสัญญา </h2></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="4" align="left" style="font-weight:bold;">รายการที่รออนุมัติ</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>เลขที่สัญญา</td>
				<td>ผู้แก้ไขรายการ</td>
				<td>วันเวลาที่แก้ไข</td>
				<td>รายละเอียด</td>
			</tr>
			<?php
			$i=0;
			$qry_app=pg_query("select a.\"contractID\",a.\"addUser\" as \"id_user\",b.\"fullname\" as \"addUser\",a.\"addStamp\" from \"thcap_ContactCus_Temp\" a
			left join \"Vfuser\" b on a.\"addUser\"=b.\"id_user\"
			where \"appStatus\" ='2' group by a.\"contractID\",a.\"addUser\",b.\"fullname\",a.\"addStamp\" order by \"addStamp\" ");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$contractID=$res_app["contractID"]; //เลขที่สัญญา				
				$id_useradd=$res_app["id_user"]; //รหัสผู้ทำการแก้ไข
				$addUser=$res_app["addUser"]; //ชื่อผู้ทำการแก้ไข
				$addStamp=$res_app["addStamp"]; //วันเวลาที่แก้ไข
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
				
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td align="left"><?php echo $addUser; ?></td>
				<td><?php echo $addStamp; ?></td>
				<td>	
					<?php
					//ให้สามารถอนุมัติได้เฉพาะคนที่ไม่ใช่คนเดียวกันกับผู้แก้ไข หรือผู้ที่มี level <=1
					if(($id_user!=$id_useradd) or $emplevel<=1){
					?>
						<span onclick="javascript:popU('show_approve.php?contractID=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor:pointer;"><u>ตรวจสอบ</u></span>
					<?php
					}else{
						echo "ไม่มีสิทธิ์อนุมัติ";
					}
					?>
				</td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=4 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

<!--ประวัติการอนุมัติ-->
<div>
	<?php
	$limit="limit 30";
	$txthead="ประวัติการอนุมัติ 30 รายการล่าสุด";
	include("frm_history.php");
	?>
</div>

</body>
</html>