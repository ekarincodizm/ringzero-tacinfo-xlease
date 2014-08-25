<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) ตรวจสอบสินทรัพย์สำหรับเช่า-ขาย</title>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css"></link>

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
	
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>

<body>
<center>
<div style="width:1296px; text-align:center;">
<h2>(THCAP) ตรวจสอบสินทรัพย์สำหรับเช่า-ขาย</h2>
<br>
<table align="center" width="90%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>ผู้ซื้อ</th>
		<th>ผู้ขาย</th>
		<th>เลขที่ใบสั่งซื้อ<br /><span style="font-weight:bold; color:#ff0000;">(THCAP เป็นผู้ออก)</span></th>
		<th>เลขที่ใบเสร็จ<br /><span style="font-weight:bold; color:#ff0000;">(ผู้ขายเป็นผู้ออก)</span></th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>
		<th>รายละเอียด</th>
	</tr>
	<?php
	$query = pg_query("select * from public.\"thcap_asset_biz_temp\" where \"Approved\" is null and \"assetID\" = '0' ");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$tempID = $result["tempID"]; // รหัส temp
		$compID = $result["compID"]; // ID บริษัท (ผู้ซื้อ)
		$corpID = $result["corpID"]; // รหัสนิติบุคคล (ผู้ขาย)
		$PurchaseOrder = $result["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
		$receiptNumber = $result["receiptNumber"]; // เลขที่ใบเสร็จ
		$doerID = $result["doerID"]; // ผู้ทำรายการ
		$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
		
		$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
		while($result_name = pg_fetch_array($qry_name))
		{
			$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
		}
		
		// หาชื่อบริษัท (ผู้ซื้อ)
		$qry_nameCom = pg_query("select * from public.\"thcap_company\" where \"compID\" = '$compID' ");
		while($result_name = pg_fetch_array($qry_nameCom))
		{
			$compThaiName = $result_name["compThaiName"]; // ชื่อของ บริษัท (ผู้ซื้อ)
		}
		
		// หาชื่อบริษัท (ผู้ซื้อ)
		$qry_nameCorp = pg_query("select * from public.\"VSearchCusCorp\" where \"CusID\" = '$corpID' ");
		while($result_name = pg_fetch_array($qry_nameCorp))
		{
			$fullnameCorp = $result_name["full_name"]; // ชื่อของ นิติบุคคล (ผู้ขาย)
		}
		
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		
		echo "<td align=\"center\">$i</td>";
		echo "<td>$compThaiName</td>";
		echo "<td>$fullnameCorp</td>";
		echo "<td align=\"center\">$PurchaseOrder</td>";
		echo "<td align=\"center\">$receiptNumber</td>";
		echo "<td>$fullname</td>";
		echo "<td align=\"center\">$doerStamp</td>";
		//echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_appvDetail.php?corpID=$corpID&receiptNumber=$receiptNumber&doerID=$doerID&doerStamp=$doerStamp','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=750')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_appvDetail.php?tempID=$tempID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1300,height=750')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
		echo "</tr>";
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=8 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=8><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
<?php
include("frm_appv_history_limit.php");
?>
</div>
</center>
</body>
</html>