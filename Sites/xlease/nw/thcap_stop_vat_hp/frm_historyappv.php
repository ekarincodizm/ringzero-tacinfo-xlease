<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติการอนุมัติผูกสัญญา</title>

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
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<center><h1>ประวัติการอนุมัติ STOP VAT HP</h1></center>
<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<thead>
        <tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>
		<th>เลขที่สัญญา</th>
		<th>ชื่อผู้กู้หลัก/ผู้เช่าซื้อหลัก</th>
		<th>ประเภทสัญญา</th>
		<th>วันที่เริ่มค้างชำระ</th>	
		<th>ผู้ที่ทำรายการ</th>
		<th>วันที่ทำรายการ</th>					
		<th>ยอดมูลหนี้  ณ วันที่ stop vat</th>
		<th>ผู้ที่ทำการอนุมัติ</th>
		<th>วันที่ทำการอนุมัติ</th>	
		<th>หมายเหตุ</th>
		<th>ผลการอนุมัติ</th>
		
        </tr>
    </thead>
<?php
$qry_showall = pg_query("select * from \"thcap_contract_vatcontrol\"  where \"status\"='1'  order by \"doerstamp\" desc ");
$i=0;	
$numrows = pg_num_rows($qry_showall);
while($res_showall= pg_fetch_array($qry_showall))
	{	$i+=1;
		$autoID = $res_showall["autoID"];
		$contractIDlimit = $res_showall["contractID"];
		$datebehindhandlimit = $res_showall["datebehindhand"];
		$payamtlimit = $res_showall["payamt"];
		$doeridlimit = $res_showall["doerid"];
		$doerstamplimit = $res_showall["doerstamp"];	
		$appvidlimit = $res_showall["appvid"];
		$appvstamplimit = $res_showall["appvstamp"]; 
		$notelimit = $res_showall["note"];
		$approvelimit= $res_showall["approve"];
		$conType="HP";
		//ชื่อผู้ที่ทำรายการ
		$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doeridlimit' ");
		$fullnameuser = pg_fetch_array($query_fullnameuser);
		$doerfullname=$fullnameuser["fullname"];
		//ชื่อผู้ทำการอนุมัติ
		$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$appvidlimit' ");
		$fullnameuser = pg_fetch_array($query_fullnameuser);
		$empfullname=$fullnameuser["fullname"];
		//
		$qry_name = pg_query("select a.\"FullName\" as \"FullName\"
				from \"thcap_ContactCus\" a
				LEFT JOIN \"thcap_contract_vatcontrol\" b on a.\"contractID\" = b.\"contractID\"				
				where \"CusState\" = '0' and b.\"contractID\"='$contractIDlimit'
				");
		$res_name = pg_fetch_array($qry_name);
		$nameCus=$res_name ["FullName"];
		
		
		if($i%2==0)
		{
			echo "<tr  bgcolor=\"#EEE9E9\" align=\"center\" height=25>";
		}
		else
		{
			echo "<tr  bgcolor=\"#FFFAFA\" align=\"center\" height=25>";
		}
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractIDlimit','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractIDlimit</u></font></a></td>";	
		echo "<td align=\"left\">$nameCus</td>";
		echo "<td align=\"center\">$conType</td>";//ประเภทสัญญา
		echo "<td align=\"center\">$datebehindhandlimit</td>";//ันที่เริ่มค้างชำระ
		echo "<td align=\"center\">$doerfullname</td>";//ผู้ที่ทำรายการ
		echo "<td align=\"center\">$doerstamplimit</td>";//วันที่ทำรายการ
		echo "<td align=\"right\">".number_format($payamtlimit,2)."</td>";//ยอดมูลหนี้  ณ วันที่ stop vat
		echo "<td align=\"center\">$empfullname</td>";//ผู้ที่การอนุมัติ
		echo "<td align=\"center\">$appvstamplimit</td>";//วันที่ทำการอนุมัติ
		echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_notedetail.php?idno=$autoID ','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=400');\" style=\"cursor:pointer;\"></td>";
		
		
		//ผลการ อนุมัติ
		if($approvelimit=='1'){
		echo "<td align=\"center\"><font color=\"green\">อนุมัติ</td>";}
		elseif($approvelimit=='0'){
		echo "<td align=\"center\"><font color=\"red\">ไม่อนุมัติ</td>";}
		echo "</tr>";
	}
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=12 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=12><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	
?>
</table>	
</body>

