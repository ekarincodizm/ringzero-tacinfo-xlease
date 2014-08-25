<?php
include("../../config/config.php");
$credit_search=$_POST["credit_search"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>จัดการ Template สิทธิ์</title>
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

<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
		<td>
			<div style="padding-bottom: 10px;text-align:center;"><h2>จัดการ Template สิทธิ์</h2></div>
			<form method="post" name="form2" action="frm_Index.php">
			<fieldset><legend><B>ค้นหา</B></legend>
				<div class="ui-widget" align="center" style="padding: 10px;">
					<div style="margin:0">
						<b>ชื่อ Template</b>&nbsp;
						<input id="credit_search" name="credit_search" size="60" />&nbsp;
						<input type="submit" value="ค้นหา"/>
					</div>
				</div>
			</fieldset>
			</form>
			<div id="panel" style="padding-top: 20px;">
			
				<table width="100%" align="center" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0">
				<form method="post" name="form1" action="frm_IndexAdd.php">
					<tr height="50" bgcolor="#FFFFFF">
						<td colspan="5" align="right"><input type="submit" value="เพิ่ม Template"><input type="button" value="  Close  " onclick="javascript:window.close();"></td>
					</tr>
					</form>
					<tr height="25" bgcolor="#79BCFF">
						<th width="50">ลำดับที่</th>
						<th>ชื่อ Template</th>
						<th width="80">รายละเอียด</th>
						<th width="100">สถานะการใช้งาน</th>
						<th width="50">แก้ไข</th>
					</tr>
					<?php
						if($credit_search==""){
							$query=pg_query("select * from \"nw_template\" order by \"createDate\" DESC");
						}else{
							$query=pg_query("select * from \"nw_template\" where \"tempName\" like '%$credit_search%' order by \"createDate\" DESC");
						}
						$numrow=pg_num_rows($query);
						$i=1;
						while($result=pg_fetch_array($query)){
							$tempID=$result["tempID"];
							$tempName=$result["tempName"];
							$tempStatus=$result["tempStatus"];
							if($tempStatus=="f"){
								$txtstatus="ไม่เปิดใช้งาน";
							}else{
								$txtstatus="เปิดใช้งาน";
							}
							echo "<tr align=center bgcolor=#FFFFFF>";
								echo "<td align=center>$i</td>";
								echo "<td align=left>$tempName</td>";
								echo "<td><a href=\"#\" onclick=\"javascript:popU('frm_DetailTemplate.php?tempID=$tempID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=650,height=600')\"><img src=\"images/detail.gif\" width=19 height=19 style=\"cursor:pointer;\" border=0></a></td>";
								echo "<td>$txtstatus</td>";
								echo "<td><a href=\"frm_IndexAdd.php?tempID=$tempID&method=edit\"><img src=\"images/edit.png\" width=16 height=16 style=\"cursor:pointer;\" border=0></a></td>";
							echo "</tr>";
							$i++;
						}
						if($numrow==0){
							echo "<tr height=50><td colspan=5 align=center bgcolor=#FFFFFF><b>ไม่พบข้อมูล</b></td></tr>";
						}
					?>
					
				</table>
			</div>
        </td>
    </tr>
</table>
</body>
</html>