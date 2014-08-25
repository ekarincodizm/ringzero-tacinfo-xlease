<?php
include("../../config/config.php");
$credit_search=$_POST["credit_search"];

$id_user = $_SESSION["av_iduser"];
$KeyDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$c1 = $_POST["c1"];
$c2 = $_POST["c2"];
//c1 คือ อนุมัติ หรือ ยกเลิก
//c2 คือ รหัสที่ต้องการจะดำเนินการ

pg_query("BEGIN WORK");
$status = 0;

if($c1==1)
{
	$appv_sql = "update public.\"thcap_mg_setting\" set \"appvID\" = '$id_user' , \"appvStamp\" = '$KeyDate' where \"mgSettingID\" = '$c2' ";
	//echo $appv_sql;
	if($result=pg_query($appv_sql)){		
		}else{
			$status += 1;
		}
		
	if($status == 0){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(THCAP) อนุมัติการตั้งค่า MG', '$KeyDate')");
		//ACTIONLOG---
		pg_query("COMMIT");
		}
	else
	{
		pg_query("ROLLBACK");
		echo "<br><h2><b><center>ไม่สามารถทำรายการได้ กรุณาลองใหม่อีกครั้ง</b></h2></center><br>";
	}
}

if($c1==2)
{
	$delete_sql = "delete from public.\"thcap_mg_setting\" where \"mgSettingID\" = '$c2' ";
	//echo $delete_sql;
	if($result2=pg_query($delete_sql)){		
		}else{
			$status += 1;
		}
		
	if($status == 0){
		pg_query("COMMIT");
		}
	else
	{
		pg_query("ROLLBACK");
		echo "<br><h2><b><center>ไม่สามารถทำรายการได้ กรุณาลองใหม่อีกครั้ง</b></h2></center><br>";
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติการตั้งค่า MG</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>

<table width="85%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
		<td>
			<div style="padding-bottom: 10px;text-align:center;"><h2>(THCAP) อนุมัติการตั้งค่า MG</h2></div>
			<!-- <form method="post" name="form2" action="frm_Index.php">
			<fieldset><legend><B>ค้นหา</B></legend>
				<div class="ui-widget" align="center" style="padding: 10px;">
					<div style="margin:0">
						<b>ค้นหาประเภท</b>&nbsp;
						<input id="credit_search" name="credit_search" size="60" />&nbsp;
						<input type="submit" value="ค้นหา"/>
					</div>
				</div>
			</fieldset>
			</form> -->
			<div id="panel" style="padding-top: 20px;">
				<table width="100%" align="center" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0">
					<tr height="25" bgcolor="#79BCFF">
						<th>รหัส running</th>
						<th>วันที่เริ่มบังคับใช้</th>
						<th>ผู้ทำรายการ</th>
						<th>วันเวลาที่ทำรายการ</th>
						<th>ผู้อนุมัติ</th>
						<th>วันเวลาที่ทำการอนุมัติ</th>
						<th>ทำรายการ</th>
					</tr>
					<?php
						$query=pg_query("select * from public.\"thcap_mg_setting\" where \"appvID\" is null order by \"mgSettingID\" ");
						$numrow=pg_num_rows($query);
						while($result=pg_fetch_array($query)){
							$mgSettingID=$result["mgSettingID"];
							$mgsActiveDate=$result["mgsActiveDate"];
							$doerID=$result["doerID"];
							$doerStamp=$result["doerStamp"];
							$appvID=$result["appvID"];
							$appvStamp=$result["appvStamp"];
							
							$query_user=pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$doerID' ");
							while($result2=pg_fetch_array($query_user)){
								$fullname=$result2["fullname"];
							}
							echo "<tr align=center bgcolor=#FFFFFF>";
								echo "<td>$mgSettingID</td>";
								echo "<td align=center>$mgsActiveDate</td>";
								echo "<td>$fullname</td>";
								echo "<td>$doerStamp</td>";
								echo "<td>$appvID</td>";
								echo "<td>$appvStamp</td>";
								echo "<td><a href=PappvMG.php?a=1&b=$mgSettingID>อนุมัต</a>  <a href=PappvMG.php?a=2&b=$mgSettingID>ยกเลิก</a></td>";
							echo "</tr>";
						}
						if($numrow==0){
							echo "<tr height=50><td colspan=7 align=center bgcolor=#FFFFFF><b>ไม่พบข้อมูล</b></td></tr>";
						}
					?>
				</table>
			</div>
        </td>
    </tr>
</table>
</body>
</html>