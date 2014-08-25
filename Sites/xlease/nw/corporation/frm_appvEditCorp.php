<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>อนุมัติการแก้ไขข้อมูลลูกค้านิติบุคคล</title>
	
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
<center><h2>อนุมัติการแก้ไขข้อมูลลูกค้านิติบุคคล</h2></center>
<br>
<div style="width:80%;margin:0 auto;"><font size="3" color="red"><b>* ผู้อนุมัติจะต้องตรวจสอบข้อมูลที่อนุมัติกับเอกสารต้นฉบับ หรือสำเนาที่เชื่อได้ว่ามาจากเอกสารต้นฉบับจริงเท่านั้น จึงจะทำการอนุมัติ การอนุมัติใดๆจะมีการเก็บข้อมูลทั้งผู้ขออนุมัติและผู้อนุมัติด้วย</b></font></div>
<table align="center" width="80%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#79BCFF">
		<th>รายการที่</th>
		<th>เลขที่นิติบุคคล</th>
		<th>ชื่อนิติบุคคล<br>ภาษาไทย</th>
		<th>ชื่อนิติบุคคล<br>ภาษาอังกฤษ</th>
		<th>ชื่อย่อ/เครื่องหมาย<br>ทางการค้า</th>
		<th>ผู้ทำรายการ</th>
		<th>วันเวลาที่ทำรายการ</th>
		<th>รายละเอียด</th>
	</tr>
	<?php
	$query = pg_query("select * from public.\"th_corp_temp\" where \"Approved\" is null and \"hidden\" = 'false' and \"corpID\" <> '0' ");
	$numrows = pg_num_rows($query);
	$i=0;
	while($result = pg_fetch_array($query))
	{
		$i++;
		$corpID = $result["corpID"]; // รหัสนิติบุคคล
		$corp_regis = $result["corp_regis"]; // เลขทะเบียนนิติบุคคล
		$corpName_THA = $result["corpName_THA"]; // ชื่อนิติบุคคลภาษาไทย
		$corpName_ENG = $result["corpName_ENG"]; // ชื่อนิติบุคคลภาษาอังกฤษ
		$trade_name = $result["trade_name"]; // ชื่อย่อ/เครื่องหมายทางการค้า
		$doerUser = $result["doerUser"]; // ผู้ทำรายการ
		$doerStamp = $result["doerStamp"]; // วันเวลาที่ทำรายการ
		
		$qry_name = pg_query("select * from public.\"Vfuser\" where \"username\" = '$doerUser' ");
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
		echo "<td align=\"center\">$corp_regis</td>";
		echo "<td>$corpName_THA</td>";
		echo "<td>$corpName_ENG</td>";
		echo "<td>$trade_name</td>";
		echo "<td>$fullname</td>";
		echo "<td align=\"center\">$doerStamp</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('frm_detail_appvEditCorp.php?corpID=$corpID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=950,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>ตรวจสอบ</u></font></a></td>";
		echo "</tr>";
	}
	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=8 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=8><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
<div style="margin-top:50px;"></div>
<div align="center">
<?php
include("frm_history_editapp_limit.php");
?>
</div>
</body>
</html>