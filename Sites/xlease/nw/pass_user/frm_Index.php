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
<title>ตั้งรหัสผ่านผู้ใช้</title>
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

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

</script>
</head>
<body>
<div  align="center"><h2>ตั้งรหัสผ่านผู้ใช้</h2></div>
<div id="panel" style="padding-top: 10px;">
<table align="center" width="60%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0">
	<tr align="center" bgcolor="#79BCFF">
		<th>ตั้งรหัสเริ่มต้น</th>
		<th height="30">รายการที่</th>
		<th>รหัสพนักงาน</th>
		<th>username</th>
		<th>คำนำหน้า - ชื่อ - นามสกุล</th>
		<th>ฝ่าย</th>
		<th>แผนก</th>
	</tr>
	<?php 
	$query = pg_query("select * from public.\"fuser\" where \"password\" is null order by \"id_user\" "); 

	$numrows = pg_num_rows($query);
	$i=1;
	while($result = pg_fetch_array($query)){
		$id_user=$result["id_user"];
		$username=$result["username"];
		$title=trim($result["title"]);
		$fname=trim($result["fname"]);
		$lname=trim($result["lname"]);
		$full_name=$title.$fname." ".$lname;
		$user_group=$result["user_group"];
		$user_dep=$result["user_dep"];
		
		if($i%2==0){
			echo "<tr class=\"odd\">";
		}else{
			echo "<tr class=\"even\">";
		}
		echo "<td>"; ?><center><button onclick="MM_openBrWindow('chpwd.php?iduser=<?php echo $id_user;?>&fullname=<?php echo $full_name; ?>&uname=<?php echo $username; ?>','','width=600,height=300')" >ตั้งรหัสเริ่มต้น</button></center><?php echo "</td>";
		echo "<td align=center height=25>$i</td>";
		echo "<td align=center>$id_user</td>";
		echo "<td>$username</td>";
		echo "<td>$full_name</td>";
		echo "<td>$user_group</td>";
		echo "<td>$user_dep</td>";
		echo "</tr>";	
		$i++;
	} //end while

	if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=7 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		$i=$i-1;
		echo "<tr bgcolor=\"#79BCFF\" height=30><td colspan=7><b>ทั้งหมด $i รายการ</b></td><tr>";
	}
	?>
</table>
</div>


<!-- ประวัติการทำรายการ 30 รายการล่าสุด -->
<div style="padding-top:50px;"></div>
<center>
	<fieldset style="width:80%;" ><legend><font color="black"><b>ประวัติการตั้งรหัสผ่านผู้ใช้ 30 รายการล่าสุด (<font color="blue"><u><a onclick="MM_openBrWindow('frm_historyapp.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor:pointer">ทั้งหมด</a></u></font>)</b></font></legend>
		<div id="panel" style="padding-top: 10px;">
			<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0">
				<tr align="center" bgcolor="#B5B5B5">
					<th >รายการที่</th>
					<th>รหัสพนักงาน</th>
					<th>username</th>
					<th>คำนำหน้า - ชื่อ - นามสกุล</th>
					<th>ฝ่าย</th>
					<th>แผนก</th>
					<th>ผู้ทำรายการ</th>
					<th>วันเวลาที่ทำรายการ</th>
				</tr>
				<?php 
				$query_setPassword = pg_query("select * from \"change_password_user_log\" where \"old_password\" is null and \"Approved\" = true order by \"appvStamp\" desc limit 30");
				$numrows = pg_num_rows($query_setPassword);
				$i=0;
				while($res_setPassword = pg_fetch_array($query_setPassword))
				{
					$i++;
					$idSetPass = $res_setPassword["id_user"]; // รหัสพนักงานที่ถูกตั้ง password
					$user_keylast=$res_setPassword["appvID"]; // รหัสผู้ทำ/อนุมัติรายการ
					$keydatelast=$res_setPassword["appvStamp"]; // วันเวลาที่ทำ/อนุมัติรายการ
					
					$userkey_qry = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\" = '$user_keylast'");
					list($fullnamekey) = pg_fetch_array($userkey_qry);
						
					$query = pg_query("select * from public.\"fuser\" where \"id_user\" = '$idSetPass' "); 
					while($result = pg_fetch_array($query))
					{
						$id_user=$result["id_user"];
						$username=$result["username"];
						$title=trim($result["title"]);
						$fname=trim($result["fname"]);
						$lname=trim($result["lname"]);
						$full_name=$title.$fname." ".$lname;
						$user_group=$result["user_group"];
						$user_dep=$result["user_dep"];
					}
					
					if($i%2==0){
						echo "<tr bgcolor=#CFCFCF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#CFCFCF';\" align=center>";
					}else{
						echo "<tr bgcolor=#E8E8E8 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#E8E8E8';\" align=center>";
					}
					echo "<td align=center >$i</td>";
					echo "<td align=center>$id_user</td>";
					echo "<td>$username</td>";
					echo "<td>$full_name</td>";
					echo "<td>$user_group</td>";
					echo "<td>$user_dep</td>";
					echo "<td>$fullnamekey</td>";
					echo "<td>$keydatelast</td>";
					echo "</tr>";
				} //end while

				if($numrows==0){
					echo "<tr bgcolor=#CFCFCF height=50><td colspan=8 align=center><b>ไม่พบรายการ</b></td><tr>";
				}else{
					echo "<tr bgcolor=\"#B5B5B5\" height=30><td colspan=8><b>ทั้งหมด $i รายการ</b></td><tr>";
				}
				?>
			</table>
		</div>
	</fieldset>
</center>

</body>
</html>