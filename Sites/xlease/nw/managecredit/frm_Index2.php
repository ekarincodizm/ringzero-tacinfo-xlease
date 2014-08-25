<?php
include("../../config/config.php");
$credit_search=$_POST["credit_search"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไขประเภทสินเชื่อเช่าซื้อ</title>
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
			<div style="padding-bottom: 10px;text-align:center;"><h2>จัดการประเภทสินเชื่อเช่าซื้อ</h2></div>
			<form method="post" name="form2" action="frm_Index2.php">
			<fieldset><legend><B>ค้นหา</B></legend>
				<div class="ui-widget" align="center" style="padding: 10px;">
					<div style="margin:0">
						<b>ค้นหาประเภท</b>&nbsp;
						<input id="credit_search" name="credit_search" size="60" />&nbsp;
						<input type="submit" value="ค้นหา"/>
					</div>
				</div>
			</fieldset>
			</form>
			<div id="panel" style="padding-top: 20px;">
				<table width="100%" align="center" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0">
					<tr height="25" bgcolor="#79BCFF">
						<th>รหัสสินเชื่อ</th>
						<th>ประเภทสินเชื่อ</th>
						<th>คำอธิบาย</th>
						<th>สถานะ</th>
						<th>แก้ไข</th>
					</tr>
					<?php
						if($credit_search==""){
							$query=pg_query("select * from \"nw_credit\" order by \"createDate\" DESC");
						}else{
							$query=pg_query("select * from \"nw_credit\" where \"creditType\" like '%$credit_search%' order by \"createDate\" DESC");
						}
						$numrow=pg_num_rows($query);
						while($result=pg_fetch_array($query)){
							$creditID=$result["creditID"];
							$creditType=$result["creditType"];
							$creditDetail=$result["creditDetail"];
							$statusUse=$result["statusUse"];
							if($statusUse=="f"){
								$txtstatus="ไม่เปิดใช้งาน";
							}else{
								$txtstatus="เปิดใช้งาน";
							}
							echo "<tr align=center bgcolor=#FFFFFF>";
								echo "<td>$creditID</td>";
								echo "<td align=left>$creditType</td>";
								echo "<td><a href=\"#\" onclick=\"javascript:popU('frm_detailCredit.php?creditID=$creditID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')\"><img src=\"images/detail.gif\" width=19 height=19 style=\"cursor:pointer;\"></a></td>";
								echo "<td>$txtstatus</td>";
								echo "<td><a href=\"frm_editCredit.php?creditID=$creditID\"><img src=\"images/edit.png\" width=16 height=16 style=\"cursor:pointer;\"></a></td>";
							echo "</tr>";
						}
						if($numrow==0){
							echo "<tr height=50><td colspan=5 align=center bgcolor=#FFFFFF><b>ไม่พบข้อมูล</b></td></tr>";
						}
					?>		
					<tr height="50" bgcolor="#FFFFFF">
						<td colspan="5" align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></td>
					</tr>
				</table>
			</div>
        </td>
    </tr>
</table>
</body>
</html>