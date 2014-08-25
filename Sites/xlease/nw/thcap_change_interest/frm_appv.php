<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติปรับอัตราดอกเบี้ย</title>
	
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
<center><h2>(THCAP) อนุมัติปรับอัตราดอกเบี้ย</h2></center>
<br>
<table align="center" width="80%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>เลขที่สัญญา</th>
		<th>อัตราดอกเบี้ยปัจจุบัน</th>
		<th>อัตราดอกเบี้ยใหม่</th>
		<th>วันเวลาที่เริ่มมีผล</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>
		<th>รายละเอียด</th>
	</tr>
	<?php
	$query = pg_query("select * from public.\"thcap_changeRate_temp\" where \"Approved\" is null ");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$tempID = $result["tempID"];
		$contractID = $result["contractID"]; // เลขที่สัญญา
		$oldRate = $result["oldRate"]; // อัตราดอกเบี้ยปัจจุบัน
		$newRate = $result["newRate"]; // อัตราดอกเบี้ยใหม่
		$effectiveDate = $result["effectiveDate"]; // วันเวลาที่เริ่มมีผล
		$doerID = $result["doerID"]; // ผู้ทำรายการ
		$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
		
		$qry_name = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
		while($result_name = pg_fetch_array($qry_name))
		{
			$fullname = $result_name["fullname"]; // ชื่อของผู้ที่ทำรายการ
		}
		
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		
		echo "<td align=\"center\">$i</td>";
		echo "<td align=\"center\"><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
										<u>$contractID</u></font></span></td>";
		echo "<td align=\"center\">$oldRate %</td>";
		echo "<td align=\"center\">$newRate %</td>";
		echo "<td align=\"center\">$effectiveDate</td>";
		echo "<td>$fullname</td>";
		echo "<td align=\"center\">$doerStamp</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_appv_detail.php?tempID=$tempID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
		echo "</tr>";
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=9 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=9><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}	
	?>
</table>
<div style="padding-top:50px;"></div>
			<?php 
				$limit = "limit 30";
				include("frm_history.php");
			?>

</body>
</html>