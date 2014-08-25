<?php 
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติอนุมัติยกเลิกใบเสร็จสินทรัพย์ </title>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css" />
<link type="text/css" rel="stylesheet" href="styles/style.css" />

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<style type="text/css">
.sortable {
	color: #000000;
	cursor:pointer;
	text-decoration:underline;
}
</style>
  
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.tablesorter.js"></script>
<!--<script type="text/javascript" src="scripts/jquery.tableSort.js"></script>-->
	
<script type="text/javascript">
$(document).ready(function(){
	$("#tb_approved").tablesorter();
});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

</head>

<body>
<center><h1>ประวัติอนุมัติยกเลิกใบเสร็จสินทรัพย์ </h1></center>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th>เลขที่ใบเสร็จ</th>
		<th>เลขที่ใบสั่งซื้อ</th>
		<th>ราคารวม VAT</th>
		<th>ผู้ขาย</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>
		<th>วันเวลาที่อนุมัติรายการ</th>
		<th>เหตุผลในการขอยกเลิก</th>
		<th>ผลการตรวจสอบ</th>
	</tr>
	<?php
	
	$query_all = pg_query("select * from public.\"thcap_asset_cancel\" where \"Approved\" is not null order by \"appvStamp\" desc ");
	$numrows = pg_num_rows($query_all);
	$no=0;
	while($result_all = pg_fetch_array($query_all))
	{
		$no++;
		$cancel_all = $result_all["cancelID"]; // รหัสการขอยกเลิก
		$assetID_all = $result_all["assetID"]; // รหัส ใบเสร็จ/ใบสั่งซื้อ
		$doerID_all = $result_all["doerID"]; // ผู้ทำรายการ
		$doerStamp_all = $result_all["doerStamp"]; // วันเวลาที่ทำรายการ
		$reasonl_all= $result_all["reason"]; // เหตุผล
		$appvStamp_all = $result_all["appvStamp"]; // วันเวลาที่ทำการอนุมัติ/ไม่อนุมัติ
		$approved_all = $result_all["Approved"]; // ผลการอนุมัติ
		
		$qry_asset_data= pg_query("select * from \"thcap_asset_biz\" where \"assetID\" = '$assetID_all' ");
		while($result_asset_data= pg_fetch_array($qry_asset_data))
		{
			$receipt_all = $result_asset_data["receiptNumber"]; // เลขที่ใบเสร็จ
			$PurchaseOrder_all= $result_asset_data["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
			$afterVat_all= $result_asset_data["afterVat"]; // ราคารวม VAT
			$corpID_all = $result_asset_data["corpID"]; // รหัสผู้ขาย
		}
		// หาชื่อผู้ขาย
		$qry_CusName= pg_query("select * from public.\"VSearchCusCorp\" where \"CusID\" = '$corpID_all' ");
		while($result_name = pg_fetch_array($qry_CusName))
		{
			$CusName= $result_name["full_name"]; // ชื่อของผู้ขาย
		}
		// หาชื่อผู้ทำรายการ
		$qry_namedoerID = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID_all' ");
		while($result_namedoerID = pg_fetch_array($qry_namedoerID))
		{
			$fullname = $result_namedoerID["fullname"]; // ชื่อของผู้ที่ทำรายการ
		}
		if($no%2==0){
			echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
		}else{
			echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
		}
		//เลขที่ใบเสร็จ
		$qry_idreceipt_all = pg_query("select \"assetID\",\"receiptNumber\" from \"thcap_asset_biz\" where \"receiptNumber\" like '$receipt_all' ");
		$result_idreceipt_all = pg_fetch_array($qry_idreceipt_all);
		$idreceipt= $result_idreceipt_all["assetID"];
		//เลขที่ใบสั่งซื้อ 
		$qry_purchaseorder= pg_query("select \"assetID\",\"PurchaseOrder\" from \"thcap_asset_biz\" where \"PurchaseOrder\" like '$PurchaseOrder_all' and \"receiptNumber\" like '$receipt_all'");
		$result_purchaseorder = pg_fetch_array($qry_purchaseorder);
		$idpurchaseorder = $result_purchaseorder["assetID"];
		
		echo "<td align=\"center\">$no</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('../view_assets_for_rent_sale/view_appvDetail.php?bill_id=$idreceipt','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$receipt_all</u></font></a></td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('../view_assets_for_rent_sale/view_appvDetail.php?bill_id=$idpurchaseorder','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$PurchaseOrder_all</u></font></a></td>";
		echo "<td align=\"right\">".number_format($afterVat_all,2)."</td>";
		echo "<td align=\"left\">$CusName</td>";
		echo "<td align=\"left\">$fullname</td>";
		echo "<td align=\"center\">$doerStamp_all</td>";
		echo "<td align=\"center\">$appvStamp_all</td>"; 
		echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_reason.php?cancelID=$cancel_all ','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\"></td>";
		
		
		//ผลการอนุมัติ
		if($approved_all=='t'){ 
			echo "<td align=\"center\"><font color=\"green\"> อนุมัติ</font></td>";
		}
		else{
			echo "<td align=\"center\"><font color=\"red\"> ไม่อนุมัติ</font></td>";
		}		
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=10 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#CDC9C9\" height=30><td colspan=10><b>ข้อมูลทั้งหมด $no รายการ</b></td><tr>";
	}
	?>
	</table>
</body>

