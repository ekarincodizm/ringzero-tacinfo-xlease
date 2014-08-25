<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติยกเลิกใบเสร็จสินทรัพย์</title>
	
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="css/act.css"></link>
	
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
<center><h2>(THCAP) อนุมัติยกเลิกใบเสร็จสินทรัพย์</h2></center>
<br>
<table align="center" width="85%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>เลขที่ใบเสร็จ</th>
		<th>เลขที่ใบสั่งซื้อ</th>
		<th>ราคารวม VAT</th>
		<th>ผู้ขาย</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>
		<th>ตรวจสอบ</th>
	</tr>
	<?php
	
	$query = pg_query("select * from public.\"thcap_asset_cancel\" where \"Approved\" is null order by \"doerStamp\" ");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$cancelID = $result["cancelID"]; // รหัสการขอยกเลิก
		$assetID = $result["assetID"]; // รหัส ใบเสร็จ/ใบสั่งซื้อ
		$doerID = $result["doerID"]; // ผู้ทำรายการ
		$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
		$reason = $result["reason"]; // เหตุผล
		
		$qry_asset_data = pg_query("select * from \"thcap_asset_biz\" where \"assetID\" = '$assetID' ");
		while($result_asset_data = pg_fetch_array($qry_asset_data))
		{
			$receiptNumber = $result_asset_data["receiptNumber"]; // เลขที่ใบเสร็จ
			$PurchaseOrder = $result_asset_data["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
			$afterVat = $result_asset_data["afterVat"]; // ราคารวม VAT
			$corpID = $result_asset_data["corpID"]; // รหัสผู้ขาย
		}
		
		// หาชื่อผู้ขาย
		$qry_CusName = pg_query("select * from public.\"VSearchCusCorp\" where \"CusID\" = '$corpID' ");
		while($result_name = pg_fetch_array($qry_CusName))
		{
			$CusName = $result_name["full_name"]; // ชื่อของผู้ขาย
		}
		
		
		// หาชื่อผู้ทำรายการ
		$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
		while($result_name = pg_fetch_array($qry_name))
		{
			$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
		}
		
		if($i%2==0){
			echo "<tr bgcolor=\"#B2DFEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B2DFEE';\">";
		}else{
			echo "<tr bgcolor=\"#BFEFFF\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#BFEFFF';\">";
		}		
		//เลขที่ใบเสร็จ
		$qry_idreceiptnumber = pg_query("select \"assetID\",\"receiptNumber\" from \"thcap_asset_biz\" where \"receiptNumber\" like '$receiptNumber' ");
		$result_idreceiptnumber = pg_fetch_array($qry_idreceiptnumber);
		$id = $result_idreceiptnumber["assetID"];
		$receipt = $result_idreceiptnumber["receiptNumber"];
		$getreceiptNumber=$id."#เลขที่ใบเสร็จ ".$receipt;
		//เลขที่ใบสั่งซื้อ 
		$qry_idpurchaseorder = pg_query("select \"assetID\",\"PurchaseOrder\" from \"thcap_asset_biz\" where \"PurchaseOrder\" like '$PurchaseOrder' and \"receiptNumber\" like '$receiptNumber'");
		$result_idpurchaseorder = pg_fetch_array($qry_idpurchaseorder);
		$idpurchaseorder = $result_idpurchaseorder["assetID"];
		$purchase = $result_idpurchaseorder["PurchaseOrder"];
		$getidpurchaseorder=$idpurchaseorder ."#เลขที่ใบสั่งซื้อ  ".$purchase;
		
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('../view_assets_for_rent_sale/view_appvDetail.php?bill_id=$getreceiptNumber','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$receiptNumber</u></font></a></td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('../view_assets_for_rent_sale/view_appvDetail.php?bill_id=$getidpurchaseorder','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$PurchaseOrder</u></font></a></td>";
		echo "<td align=\"right\">".number_format($afterVat,2)."</td>";
		echo "<td align=\"left\">$CusName</td>";
		echo "<td align=\"left\">$fullname</td>";
		echo "<td align=\"center\">$doerStamp</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_Index.php?cancelID=$cancelID&typeUseMenu=appv','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=8 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=8><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
<br>
<fieldset><legend><B>ประวัติอนุมัติยกเลิกใบเสร็จสินทรัพย์ 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;"onclick="javascript:popU('frm_historyall.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')" ><u>ทั้งหมด</u></a>) </font></B></legend>
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
	
	$querylimit30 = pg_query("select * from public.\"thcap_asset_cancel\" where \"Approved\" is not null order by \"appvStamp\" desc limit 30 ");
	$numrows = pg_num_rows($querylimit30);
	$no=0;
	while($resultlimit30 = pg_fetch_array($querylimit30))
	{
		$no++;
		$cancelIDlimit30 = $resultlimit30["cancelID"]; // รหัสการขอยกเลิก
		$assetIDlimit30 = $resultlimit30["assetID"]; // รหัส ใบเสร็จ/ใบสั่งซื้อ
		$doerIDlimit30 = $resultlimit30["doerID"]; // ผู้ทำรายการ
		$doerStamplimit30 = $resultlimit30["doerStamp"]; // วันเวลาที่ทำรายการ
		$reasonlimit30 = $resultlimit30["reason"]; // เหตุผล
		$appvStamplimit30 = $resultlimit30["appvStamp"]; // วันเวลาที่ทำการอนุมัติ/ไม่อนุมัติ
		$approvedlimit30 = $resultlimit30["Approved"]; // ผลการอนุมัติ
		
		$qry_asset_datalimit30 = pg_query("select * from \"thcap_asset_biz\" where \"assetID\" = '$assetIDlimit30' ");
		while($result_asset_datalimit30 = pg_fetch_array($qry_asset_datalimit30))
		{
			$receiptNumberlimit30 = $result_asset_datalimit30["receiptNumber"]; // เลขที่ใบเสร็จ
			$PurchaseOrderlimit30 = $result_asset_datalimit30["PurchaseOrder"]; // เลขที่ใบสั่งซื้อ
			$afterVatlimit30 = $result_asset_datalimit30["afterVat"]; // ราคารวม VAT
			$corpIDlimit30 = $result_asset_datalimit30["corpID"]; // รหัสผู้ขาย
		}
		// หาชื่อผู้ขาย
		$qry_CusNamelimit30 = pg_query("select * from public.\"VSearchCusCorp\" where \"CusID\" = '$corpIDlimit30' ");
		while($result_namelimit30 = pg_fetch_array($qry_CusNamelimit30))
		{
			$CusNamelimit30 = $result_namelimit30["full_name"]; // ชื่อของผู้ขาย
		}
		// หาชื่อผู้ทำรายการ
		$qry_namedoerIDlimit30 = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerIDlimit30' ");
		while($result_namedoerIDlimit30 = pg_fetch_array($qry_namedoerIDlimit30))
		{
			$fullnamelimit30 = $result_namedoerIDlimit30["fullname"]; // ชื่อของผู้ที่ทำรายการ
		}
		if($no%2==0){
			echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
		}else{
			echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
		}
		//เลขที่ใบเสร็จ
		$qry_idreceiptlimit30 = pg_query("select \"assetID\",\"receiptNumber\" from \"thcap_asset_biz\" where \"receiptNumber\" like '$receiptNumberlimit30' ");
		$result_idreceiptlimit30 = pg_fetch_array($qry_idreceiptlimit30);
		$idreceiptlimit30 = $result_idreceiptlimit30["assetID"];
		//เลขที่ใบสั่งซื้อ 
		$qry_orderlimit30 = pg_query("select \"assetID\",\"PurchaseOrder\" from \"thcap_asset_biz\" where \"PurchaseOrder\" like '$PurchaseOrderlimit30' and \"receiptNumber\" like '$receiptNumberlimit30'");
		$result_orderlimit30 = pg_fetch_array($qry_orderlimit30);
		$orderlimit30 = $result_orderlimit30["assetID"];
		
		echo "<td align=\"center\">$no</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('../view_assets_for_rent_sale/view_appvDetail.php?bill_id=$idreceiptlimit30','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$receiptNumberlimit30</u></font></a></td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('../view_assets_for_rent_sale/view_appvDetail.php?bill_id=$orderlimit30','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$PurchaseOrderlimit30</u></font></a></td>";
		echo "<td align=\"right\">".number_format($afterVatlimit30,2)."</td>";
		echo "<td align=\"left\">$CusNamelimit30</td>";
		echo "<td align=\"left\">$fullnamelimit30</td>";		
		echo "<td align=\"center\">$doerStamplimit30</td>";
		echo "<td align=\"center\">$appvStamplimit30</td>";
		echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_reason.php?cancelID=$cancelIDlimit30 ','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\"></td>";
		
		//ผลการอนุมัติ
		if($approvedlimit30=='t'){ 
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
</fieldset>	
</body>
</html>