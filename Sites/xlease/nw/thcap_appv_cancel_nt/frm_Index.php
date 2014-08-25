<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<title>(THCAP) Approved Cancel NT</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
<center><h2>(THCAP) Approved Cancel NT</h2></center>
<body >
<table align="center" width="85%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>เลขที่สัญญา</th>
		<th>วันที่ออก NT</th>
		<th>เลขที่ NT</th>
		<th>จำนวนเงินรวม</th>
		<th>ผู้ทำรายการ </th>	
		<th>วันเวลาที่ทำรายการ</th>
		<th>ทำรายการ </th>	
	</tr>
	<?php
	$qry_main = pg_query("SELECT a.\"auto_id\",a.\"NT_ID\",a.\"NT_enddate\",a.\"doerid\",a.\"doerstamp\",b.\"NT_Date\"::date,c.\"amountpay_all\",b.\"contractID\" 
	from \"thcap_cancel_nt_temp\" a
	left join \"thcap_history_nt\" b on a.\"NT_ID\"=b.\"NT_ID\"
	left join \"thcap_pdf_nt\" c on a.\"NT_ID\"=c.\"NT_ID\"
	where a.\"status\"='9'");
	$no=0;	
	
	while($res_main = pg_fetch_array($qry_main))
	{		
			$auto_id = $res_main["auto_id"];
			$contractID = $res_main["contractID"];
			$NT_Date = $res_main["NT_Date"];
			$NT_ID = $res_main["NT_ID"];
			$amountpay_all = $res_main["amountpay_all"];
			$doerid = $res_main["doerid"];
			$doerstamp = $res_main["doerstamp"];
			
			$query_fullnameuser = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$doerid' ");
			$fullnameuser = pg_fetch_array($query_fullnameuser);
			$empfullname=$fullnameuser["fullname"];
			
			$no+=1;
			if($no%2==0)
			{
				echo "<tr class=\"odd\" align=\"center\" height=25>";
			}
			else
			{
				echo "<tr class=\"even\" align=\"center\" height=25>";
			}
			echo "<td align=\"center\">$no</td>";
			echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$contractID</u></font></a></td>";
			echo "<td align=\"center\">$NT_Date</td>";
			echo "<td align=\"center\">$NT_ID</td>";
			echo "<td align=\"right\">".number_format($amountpay_all,2)."</td>";
			echo "<td align=\"center\">$empfullname</td>";	
			echo "<td align=\"center\">$doerstamp</td>";	
			echo "<td align=\"center\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" onclick=\"javascript:popU('frm_action_appv_cancel.php?auto_id=$auto_id ','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600');\" style=\"cursor:pointer;\"></td>";		
			echo "</tr>";
	}
	if($no==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=9 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=25><td colspan=9><b>ข้อมูลทั้งหมด $no รายการ</b></td><tr>";
	}	
	
	?>	
</table>
<?php include("frm_histority_limit.php");?>
</body>