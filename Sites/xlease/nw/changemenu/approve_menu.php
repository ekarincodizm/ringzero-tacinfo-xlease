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
<title>อนุมัติเปลี่ยนแปลงสิทธิ์การทำงาน</title>
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
<div  align="center"><h2>อนุมัติเปลี่ยนแปลงสิทธิ์การทำงาน</h2></div>
<div id="panel" style="padding-top: 10px;">
<table align="center" width="60%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0">
	<tr align="center" bgcolor="#79BCFF">
		<th height="30">รายการที่</th>
		<th>ชื่อ-สกุลพนักงาน</th>
		<th>ฝ่าย</th>
		<th>แผนก</th>
		<th>ตรวจสอบ</th>
	</tr>
	<?php 
	$query = pg_query("select distinct(a.\"id_user\"),\"fullname\",\"dep_name\",\"fdep_name\" from \"nw_changemenu\" a
	left join \"Vfuser\" b on a.\"id_user\"=b.\"id_user\"
	left join \"department\" c on b.\"user_group\"=c.\"dep_id\" 
	left join \"f_department\" d on b.\"user_dep\"=d.\"fdep_id\" 
	WHERE \"statusApprove\" = '0'"); 

	$numrows = pg_num_rows($query);
	$i=1;
	while($result = pg_fetch_array($query)){
		$id_user=$result["id_user"];
		$fullname=$result["fullname"];
		$dep_name=$result["dep_name"]; if($dep_name=="") $dep_name="-";
		$fdep_name=$result["fdep_name"]; if($fdep_name=="") $fdep_name="-";
		
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		echo "<td align=center valign=top height=25>$i</td>";
		echo "<td valign=top>$fullname</td>";
		echo "<td valign=top>$dep_name</td>";
		echo "<td valign=top align=center>$fdep_name</td>";
		echo "<td valign=top align=center><a href=\"frm_approvemenu.php?id_user=$id_user\">ตรวจสอบ</a></td>";
		echo "</tr>";	
		$i++;
	} //end while

	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=5 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		$i=$i-1;
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=5><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
</div>
</body>
</html>