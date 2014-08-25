<?php
include("../../config/config.php");
$credit_search=$_POST["credit_search"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ตั้งค่า MG</title>
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
			<div style="padding-bottom: 10px;text-align:center;"><h2>(THCAP) ตั้งค่า MG</h2></div>
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
						<th>รายละเอียด</th>
					</tr>
					<?php
						$query=pg_query("select * from public.\"thcap_mg_setting\" order by \"mgSettingID\" ");
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
							
							$fullname_appv = "";
							if($appvID!="")
							{
								$query_appvUser=pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$appvID' ");
								while($result3=pg_fetch_array($query_appvUser)){
									$fullname_appv=$result3["fullname"];
								}
							}
							
							echo "<tr align=center bgcolor=#FFFFFF>";
								echo "<td>$mgSettingID</td>";
								echo "<td>$mgsActiveDate</td>";
								echo "<td>$fullname</td>";
								echo "<td>$doerStamp</td>";
								echo "<td>$fullname_appv</td>";
								echo "<td>$appvStamp</td>";
								echo "<td><a href=\"#\" onclick=\"javascript:popU('frm_detail.php?mgSettingID=$mgSettingID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=700,height=520')\"><img src=\"images/detail.gif\" width=19 height=19 style=\"cursor:pointer;\"></a></td>";
							echo "</tr>";
						}
						if($numrow==0){
							echo "<tr height=50><td colspan=7 align=center bgcolor=#FFFFFF><b>ไม่พบข้อมูล</b></td></tr>";
						}
					?>
					<form method="post" name="form1" action="frm_Add.php">
					<tr height="50" bgcolor="#FFFFFF">
						<td colspan="7" align="right"><input type="submit" value="    เพิ่ม    "><input type="button" value="  Close  " onclick="javascript:window.close();"></td>
					</tr>
					</form>
				</table>
			</div>
        </td>
    </tr>
</table>
</body>
</html>