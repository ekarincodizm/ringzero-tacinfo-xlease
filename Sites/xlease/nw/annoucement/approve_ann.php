<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>อนุมัติ Annoucement</title>
<script language=javascript>
$(document).ready(function(){
	$('#btn1').click(function(){
		$("#btn1").attr('disabled',true);
		$("#panel").text('กำลังค้นหาข้อมูล...ระบบอาจจะใช้เวลาประมวลผลนาน 1-5 นาที');
		$("#panel").load("list_nt.php");
		$("#btn1").attr('disabled',false);
		
    });	
});

function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}

</script>
</head>
<body>
<div  align="center"><h2>อนุมัติ Annoucement</h2></div>
<div id="panel" style="padding-top: 10px;">
<table align="center" width="80%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0">
	<tr align="center" bgcolor="#79BCFF">
		<th height="30">รายการที่</th>
		<th>เรื่อง</th>
		<th>ประเภท</th>
		<th>ผู้ตั้งเรื่อง</th>
		<th>วันที่ตั้งเรื่อง</th>
		<th>รายละเอียด</th>
		<th>ตรวจสอบผู้รับข่าวสาร</th>
		<th>ยกเลิก</th>
	</tr>
	<?php 
	$query = pg_query("select *,d.\"fullname\" as author from \"nw_annoucement\" a 
	left join \"nw_annoucetype\" c on a.\"typeAnnId\"=c.\"typeAnnId\"
	left join \"Vfuser\" d on a.\"annAuthor\"=d.\"id_user\"
	where a.\"statusApprove\"='FALSE' and a.\"statusCancel\"='FALSE'  order by \"approveDate\" DESC "); 

	$numrows = pg_num_rows($query);
	$i=1;
	while($result = pg_fetch_array($query)){
		$annId=$result["annId"];
		$typeAnnName=$result["typeAnnName"];
		$annTitle=str_replaceout($result["annTitle"]);
		$user_author=$result["author"];
		$keyDate=$result["keyDate"];
		
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		echo "<td align=center valign=top height=25>$i</td>";
		echo "<td valign=top align=center>$annTitle</td>";
		echo "<td valign=top>$typeAnnName</td>";
		echo "<td valign=top>$user_author</td>";
		echo "<td valign=top align=center>$keyDate</td>";
		echo "<td valign=top align=center><a onclick=\"javascript:popU('frm_show_approve.php?annId=$annId','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\"><img src=\"images/detail.gif\" width=\"19\" height=\"19\" border=\"0\" style=\"cursor:pointer;\"></a></td>";
		echo "<td valign=top align=center><a href=\"frm_checkuser.php?annId=$annId&val=2\">ตรวจสอบ</a></td>";
		echo "<td valign=top align=center><span style=\"cursor:pointer;\" onclick=\"javascript:popU('process_approve.php?annId=$annId&val=1','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=400,height=250')\" style=\"cursor: pointer;\"><u>ยกเลิก</u></span></td>";
		echo "</tr>";	
		$i++;
	} //end while

	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=8 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		$i=$i-1;
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=8><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
</div>
</body>
</html>