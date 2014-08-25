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
<title>รายงานการแก้ไข NT</title>
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
<div  align="center"><h2>อนุมัติ NT</h2></div>
<div id="panel" style="padding-top: 10px;">
<table align="center" width="60%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0">
	<tr align="center" bgcolor="#79BCFF">
		<th height="30">รายการที่</th>
		<th>IDNO</th>
		<th>ผู้เช่าซื้อ</th>
		<th>วันที่ออก NT</th>
		<th>ผู้ออก NT</th>
		<th>เลือก</th>
	</tr>
	<?php 
	$query = pg_query("select a.\"NTID\",a.\"IDNO\",d.\"do_date\",c.\"A_FIRNAME\",c.\"A_NAME\",c.\"A_SIRNAME\",f.\"fullname\" from \"nw_statusNT\" a
						left join \"Fp\" b on a.\"IDNO\" = b.\"IDNO\"
						left join \"Fa1\" c on b.\"CusID\" = c.\"CusID\"
						left join \"NTHead\" d on a.\"NTID\" = d.\"NTID\"
						left join \"logs_NTDetail\" e on a.\"NTID\" = e.\"NTID\"
						left join \"Vfuser\" f on e.\"id_user\" = f.\"id_user\"
						where d.cancel='FALSE' and d.\"remark\" is null and d.\"CusState\"='0' and a.\"statusNT\" ='0' group by a.\"NTID\",a.\"IDNO\",d.\"do_date\",c.\"A_FIRNAME\",c.\"A_NAME\",c.\"A_SIRNAME\",f.\"fullname\" order by d.\"do_date\""); 

	$numrows = pg_num_rows($query);
	$i=1;
	while($result = pg_fetch_array($query)){
		$NTID = $result["NTID"];
		$IDNO = $result["IDNO"]; 
		$cusname = trim($result["A_FIRNAME"]).trim($result["A_NAME"])."  ".trim($result["A_SIRNAME"]); 
		$do_date = $result["do_date"];
		$fullname = $result["fullname"];
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		echo "<td align=center valign=top height=25>$i</td>";
		echo "<td valign=top align=center><span onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor: pointer;\" title=\"ดูตารางการชำระ\"><u>$IDNO</u></span> </td>";
		echo "<td valign=top>$cusname</td>";
		echo "<td valign=top align=center>$do_date</td>";
		echo "<td valign=top>$fullname</td>";
		echo "<td valign=top align=center><input type=\"button\" onclick=\"javascript:popU('compare_nt.php?IDNO=$IDNO&NTID=$NTID&cusname=$cusname','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" value=\"ตรวจสอบ\"></td>";
		echo "</tr>";	
		$i++;
	} //end while

	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=6 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		$i=$i-1;
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=6><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
</div>
<div id="panel" style="padding-top: 50px;">
<table align="center" width="80%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0">
	<tr bgcolor="#FFF"><td colspan="9" align="left">ประวัติการอนุมัติ NT 30 รายการล่าสุด<input type="button" value="แสดงประวัติทั้งหมด" onclick="javascript:popU('frm_history.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')" style="cursor:pointer;"></td></tr>
	<tr align="center" bgcolor="#B5B5B5">
		<th height="30">รายการที่</th>
		<th>IDNO</th>
		<th>ผู้เช่าซื้อ</th>
		<th>วันที่ออก NT</th>
		<th>ผู้ออก NT</th>
		<th>วันที่อนุมัติ</th>
		<th>ผู้อนุมัติ</th>
		<th>สถานะ</th>
		<th>รายละเอียด</th>
	</tr>
	<?php 
	$query = pg_query("select a.\"NTID\",a.\"IDNO\",d.\"do_date\",c.\"A_FIRNAME\",c.\"A_NAME\",c.\"A_SIRNAME\",f.\"fullname\",a.\"date_approve\",g.\"fullname\" as \"appfullname\",a.\"statusNT\" from \"nw_statusNT\" a
						left join \"Fp\" b on a.\"IDNO\" = b.\"IDNO\"
						left join \"Fa1\" c on b.\"CusID\" = c.\"CusID\"
						left join \"NTHead\" d on a.\"NTID\" = d.\"NTID\"
						left join \"logs_NTDetail\" e on a.\"NTID\" = e.\"NTID\"
						left join \"Vfuser\" f on e.\"id_user\" = f.\"id_user\"
						left join \"Vfuser\" g on a.\"user_approve\" = g.\"id_user\"
						where d.\"CusState\"='0' and a.\"statusNT\" != 0 group by a.\"NTID\",a.\"IDNO\",d.\"do_date\",c.\"A_FIRNAME\",c.\"A_NAME\",c.\"A_SIRNAME\",f.\"fullname\",a.\"date_approve\",g.\"fullname\",a.\"statusNT\"
						order by a.\"date_approve\" DESC limit 30"); 

	$numrows = pg_num_rows($query);
	$i=1;
	while($result = pg_fetch_array($query)){
		$NTID = $result["NTID"];
		$IDNO = $result["IDNO"]; 
		$cusname = trim($result["A_FIRNAME"]).trim($result["A_NAME"])."  ".trim($result["A_SIRNAME"]); 
		$do_date = $result["do_date"];
		$fullname = $result["fullname"];
		$appfullname = $result["appfullname"];
		$date_approve = $result["date_approve"];
		$statusNT = $result["statusNT"];
		if($statusNT == '2'){
			$statusapp = 'ไม่อนุมัติ';
		}else{
			$statusapp = 'อนุมัติ';
		}
		
		if($i%2==0){
			echo "<tr bgcolor=#CFCFCF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#CFCFCF';\" align=center>";
		}else{
			echo "<tr bgcolor=#E8E8E8 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#E8E8E8';\" align=center>";
		}
		
		echo "<td align=center valign= height=25>$i</td>";
		echo "<td valign= align=center><span onclick=\"javascript:popU('../../post/frm_viewcuspayment.php?idno_names=$IDNO&type=outstanding','$IDNO','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor: pointer;\" title=\"ดูตารางการชำระ\"><u>$IDNO</u></span></td>";
		echo "<td valign=>$cusname</td>";
		echo "<td valign= align=center>$do_date</td>";
		echo "<td valign=>$fullname</td>";
		echo "<td valign= align=center>$date_approve</td>";
		echo "<td valign=>$appfullname</td>";
		echo "<td valign=>$statusapp</td>";
		echo "<td valign= align=center><img src=\"../thcap/images/detail.gif\" style=\"cursor:pointer\" title=\"รายละเอียด\" onclick=\"javascript:popU('compare_nt_detail.php?IDNO=$IDNO&NTID=$NTID&cusname=$cusname','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" \"></td>";
		echo "</tr>";	
		$i++;
	} //end while

	if($numrows==0){
		echo "<tr bgcolor=#B5B5B5 height=50><td colspan=9 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		$i=$i-1;
		echo "<tr bgcolor=\"#B5B5B5\" height=30><td colspan=9><b>ข้อมูลทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
</div>
</body>
</html>