<?php
include("../../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) พิมพ์รายการส่ง NT</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
	
	<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<head>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div style="text-align:center"><h2>(THCAP) พิมพ์รายการส่ง NT</h2></div>
<table width="950" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
	<td>เลขที่สัญญา</td>
	<td>ประเภทสินทรัพย์ที่จำนอง</td>
	<td>วันที่ทำสัญญาจำนอง</td>
	<td>ทนายความผู้รับมอบอำนาจ</td>
	<td>ผู้ทำรายการ</td>
	<td>วันเวลาที่ทำรายการ</td>
	<td>ผู้อนุมัติรายการ</td>
	<td>วันเวลาที่อนุมัติ</td>
	<td>รายละเอียด</td>
</tr>
<?php
$qry_app=pg_query("SELECT distinct(\"contractID\"), \"NT_1_guaranID\", \"NT_1_Date\", \"NT_1_Lawyer_Name\",b.\"fullname\" as \"addUser\", \"NT_1_AddStamp\",
c.\"fullname\" as \"appUser\",\"NT_1_AppStamp\"
FROM \"thcap_NT1_temp\" a
LEFT JOIN \"Vfuser\" b on a.\"NT_1_AddUser\"=b.\"id_user\"
LEFT JOIN \"Vfuser\" c on a.\"NT_1_AppUser\"=c.\"id_user\"
WHERE \"NT_1_Status\"='1' and \"active\"='TRUE' order by \"NT_1_AppStamp\"");
$nub=pg_num_rows($qry_app);
while($res_app=pg_fetch_array($qry_app)){
	$contractID=$res_app["contractID"]; //เลขที่สัญญา
	$NT_1_guaranID=$res_app["NT_1_guaranID"]; //ประเภทสินทรัพย์ที่จำนอง
	$NT_1_Date=$res_app["NT_1_Date"];//วันที่ทำสัญญาจำนอง
	$NT_1_Lawyer_Name=$res_app["NT_1_Lawyer_Name"];//ทนายความผู้รับมอบอำนาจ
	$addUser=$res_app["addUser"];//พนักงานที่ทำรายการ
	$NT_1_AddStamp=$res_app["NT_1_AddStamp"];//วันเวลาที่ทำรายการ
	$appUser=$res_app["appUser"];//พนักงานอนุมัติรายการ
	$NT_1_AppStamp=$res_app["NT_1_AppStamp"];//วันเวลาที่อนุมัติ
	
	//หาว่าเป็นสัญญาประเภทใด
	$qrytype=pg_query("select \"thcap_get_creditType\"('$contractID')");
	list($contype)=pg_fetch_array($qrytype);
	
	$i+=1;
	if($i%2==0){
		echo "<tr class=\"odd\" align=center>";
	}else{
		echo "<tr class=\"even\" align=center>";
	}
	?>
	<td><span onclick="javascript:popU('../../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
	<td align="left"><?php echo $NT_1_guaranID; ?></td>
	<td><?php echo $NT_1_Date; ?></td>
	<td align="left"><?php echo $NT_1_Lawyer_Name; ?></td>
	<td align="left"><?php echo $addUser; ?></td>
	<td><?php echo $NT_1_AddStamp; ?></td>	
	<td align="left"><?php echo $appUser; ?></td>
	<td><?php echo $NT_1_AppStamp; ?></td>	
	<td>
		<?php
		if($contype=='LOAN' || $contype=='JOINT_VENTURE' || $contype=='PERSONAL_LOAN'){
			echo "<img src=\"../images/detail.gif\" onclick=\"javascript:popU('frm_Print_NT1.php?contractID=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor: pointer;\">";
		}
		?>
	</td>
	</tr>
<?php
}
if($nub == 0){
	echo "<tr><td colspan=9 align=center height=50><b>- ไม่พบข้อมูล -</b></td></tr>";
}
?>
</table>
</body>
</html>

