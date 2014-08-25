<?php
session_start();
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติจัดการรวมลูกค้าซ้ำ</title>
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

<table width="850" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="header"><h1>อนุมัติจัดการรวมลูกค้าซ้ำ</h1></div>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="11" align="left" style="font-weight:bold;">อนุมัติข้อมูลลูกค้า</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td align="center">ลูกค้าที่จะถูกทับ</td>
				<td align="center">ลูกค้าที่จะเป็นหลัก</td>
				<td align="center">ผู้ทำรายการ</td>
				<td align="center">วันเวลาทำรายการ</td>
				<td align="center">ตรวจสอบ</td>
			</tr>
			<?php
			$cur_path = redirect($_SERVER['PHP_SELF'],'nw/manageCustomer');
			
			$qry_fr=pg_query("select a.\"autoID\", a.\"Cus_old\", (select \"full_name\" from \"VSearchCus\" b where b.\"CusID\" = a.\"Cus_old\") as \"name_old\",
							a.\"Cus_new\", (select \"full_name\" from \"VSearchCus\" c where c.\"CusID\" = a.\"Cus_new\") as \"name_new\",
							a.\"doerID\", (select \"fullname\" from \"Vfuser\" d where d.\"id_user\" = a.\"doerID\") as \"name_user\", a.\"doerStamp\"
							from \"change_cus_temp\" a where a.\"appvStatus\" = '9'");
			$nub=pg_num_rows($qry_fr);
			while($res_fr=pg_fetch_array($qry_fr)){
				$autoID=$res_fr["autoID"];
				$Cus_old=$res_fr["Cus_old"]; // รหัสลูกค้าที่จะถูกทับ
				$name_old=$res_fr["name_old"]; // ชื่อเต็มลูกค้าที่จะถูกทับ
				$Cus_new = $res_fr["Cus_new"]; // รหัสลูกค้าที่จะใช้
				$name_new = $res_fr["name_new"]; // ชื่อเต็มลูกค้าที่จะใช้
				$doerID = $res_fr["doerID"]; // รหัสพนักงานที่ทำรายการ
				$name_user = $res_fr["name_user"]; // ชื่อพนักงานที่ทำรายการ
				$doerStamp = $res_fr["doerStamp"]; // วันเวลาที่ทำรายการ
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
			?>
				<td align="left"><span style="cursor:pointer; text-decoration:underline;" onclick="javascript:popU('<?php echo $cur_path; ?>/frm_ShowIndex.php?cusid=<?php echo $Cus_old."%23".$name_old; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1500,height=780')"><?php echo $Cus_old; ?></span> <?php echo $name_old; ?></td>
                <td align="left"><span style="cursor:pointer; text-decoration:underline;" onclick="javascript:popU('<?php echo $cur_path; ?>/frm_ShowIndex.php?cusid=<?php echo $Cus_new."%23".$name_new; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1500,height=780')"><?php echo $Cus_new; ?></span> <?php echo $name_new; ?></td>
				<td align="left"><?php echo $name_user; ?></td>
				<td align="center"><?php echo $doerStamp; ?></td>
				<td><span onclick="javascript:popU('showdetail.php?autoID=<?php echo $autoID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1500,height=780')" style="cursor: pointer;"><font color="#0000FF"><u>ตรวจสอบ</u></font></span></td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=5 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>