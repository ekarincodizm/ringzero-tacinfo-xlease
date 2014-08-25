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
<title>ประวัติการตั้งรหัสผ่านผู้ใช้</title>
</head>
<body>

<!-- ประวัติการทำรายการ 30 รายการล่าสุด -->
<div style="padding-top:50px;"></div>

<center>
<div><h1>ประวัติการตั้งรหัสผ่านผู้ใช้</h1></div>
	<fieldset style="width:80%;" >
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
				$query_setPassword = pg_query("select * from \"change_password_user_log\" where \"old_password\" is null and \"Approved\" = true order by \"appvStamp\" desc");
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