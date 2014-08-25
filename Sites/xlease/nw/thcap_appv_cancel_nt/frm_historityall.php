<?php
include("../../config/config.php");
$sort = pg_escape_string($_GET["descOrascby"]);
$orderby = pg_escape_string($_GET["orderby"]);

if($sort == ""){
	$sort = "DESC";
}
if($orderby == ""){
	$orderby = "\"appvstamp\" ";
}
$query = pg_query("select * from \"thcap_cost_type_temp\" 
	where \"approved\"!='9' order by  $orderby $sort ");
$sortnew = $sort == 'DESC' ? 'ASC' : 'DESC';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>ประวัติการอนุมัติ Approved Cancel NT ทั้งหมด</title>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<link type="text/css" rel="stylesheet" href="act.css" />
<link type="text/css" rel="stylesheet" href="styles/style.css" />

<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  

<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="scripts/jquery.tablesorter.js"></script>
</head>
<script type="text/javascript">

$(document).ready(function(){  
	window.opener.location.reload();});
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<body>
<center><h1>ประวัติการอนุมัติ Approved Cancel NT ทั้งหมด</h1></center>
<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
		<th>รายการที่</th>		
		<th>เลขที่สัญญา</th>
		<th>วันที่ออก NT</th>
		<th>เลขที่ NT</th>
		<th>วันที่ขอยกเลิก NT</th>
		<th>จำนวนเงินรวม</th>
		<th>ผู้ทำรายการ </th>	
		<th>วันเวลาที่ทำรายการ </th>
		<th>ผลการทำรายการ </th>	
	</tr>
	<?php	
	$i=0;
	$costold="";	
	$query = pg_query("SELECT a.\"auto_id\" as \"auto_id\",a.\"NT_ID\" as \"NT_ID\",a.\"NT_enddate\"::date as \"NT_enddate\",a.\"appvid\" as \"appvid\",a.\"appvstamp\" as \"appvstamp\",b.\"NT_Date\"::date as \"NT_Date\",c.\"amountpay_all\" as \"amountpay_all\",b.\"contractID\" as \"contractID\" ,a.\"status\" as \"status\"
	from \"thcap_cancel_nt_temp\" a
	left join \"thcap_history_nt\" b on a.\"NT_ID\"=b.\"NT_ID\"
	left join \"thcap_pdf_nt\" c on a.\"NT_ID\"=c.\"NT_ID\"
	where a.\"status\" <>'9' order by a.\"appvstamp\" desc ");
	$numrows = pg_num_rows($query);
	while(($res_main = pg_fetch_array($query)))
	{
		$auto_id = $res_main["auto_id"];
			$contractID = $res_main["contractID"];
			$NT_Date = $res_main["NT_Date"];
			$NT_ID = $res_main["NT_ID"];
			$NT_enddate = $res_main["NT_enddate"];
			$amountpay_all = $res_main["amountpay_all"];			
			$doerid = $res_main["appvid"];		
			$doerstamp = $res_main["appvstamp"];
			$status = $res_main["status"];
			if($status=='1'){$status="อนุมัติ";}
			else if($status=='0'){$status="ไม่อนุมัติ";}
			$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerid' ");
			$fullnameuser = pg_fetch_array($query_fullnameuser);
			$empfullname=$fullnameuser["fullname"];
			
			$no+=1;
			if($no%2==0)
			{
				echo "<tr bgcolor=\"#EEE9E9\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE9E9';\">";
			}
			else
			{
				echo "<tr bgcolor=\"#FFFAFA\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFFAFA';\">";
			}
			echo "<td align=\"center\">$no</td>";
			echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a></td>";
			echo "<td align=\"center\">$NT_Date</td>";
			echo "<td align=\"center\">$NT_ID</td>";
			echo "<td align=\"center\">$NT_enddate</td>";
			echo "<td align=\"right\">".number_format($amountpay_all,2)."</td>";
			echo "<td align=\"center\">$empfullname</td>";	
			echo "<td align=\"center\">$doerstamp</td>";
			echo "<td align=\"center\">$status</td>";
			echo "</tr>";
	  
	}
	if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=11 align=center><b>ไม่พบรายการ</b></td><tr>";
		}else{
			echo "<tr bgcolor=\"#CDC9C9\" height=25><td colspan=11><b>ข้อมูลทั้งหมด $no รายการ</b></td><tr>";
	}
	?>
</table>


