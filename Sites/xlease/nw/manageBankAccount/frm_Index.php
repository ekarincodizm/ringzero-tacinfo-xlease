<?php
include("../../config/config.php");
$BCompany_search=$_POST["BCompany_search"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>จัดการบัญชีธนาคารบริษัท</title>
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
			<div style="padding-bottom: 10px;text-align:center;"><h2>จัดการบัญชีธนาคารบริษัท</h2></div>
			<!-- <form method="post" name="form2" action="frm_Index.php">
			<fieldset><legend><B>ค้นหา</B></legend>
				<div class="ui-widget" align="center" style="padding: 10px;">
					<div style="margin:0">
						<b>ค้นหาชื่อเจ้าของบัญชี</b>&nbsp;
						<input id="BCompany_search" name="BCompany_search" size="60" />&nbsp;
						<input type="submit" value="ค้นหา"/>
					</div>
				</div>
			</fieldset>
			</form> -->
			<div id="panel" style="padding-top: 20px;">
				<table width="100%" align="center" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#D0D0D0">
					<tr height="25" bgcolor="#79BCFF">
						<th>เลขที่บัญชี</th>
						<th>ชื่อธนาคาร</th>
						<th>สาขา</th>
						<th>ชื่อเจ้าของบัญชี</th>
						<th>ประเภทบัญชี</th>
						<th>สถานะ</th>
						<th>แก้ไข</th>
					</tr>
					<?php
						if($BCompany_search==""){
							$query=pg_query("select * from public.\"BankInt\" order by \"BAccount\" ");
						}else{
							$query=pg_query("select * from public.\"BankInt\" where \"BCompany\" like '%$BCompany_search%' order by \"BAccount\" ");
						}
						$numrow=pg_num_rows($query);
						while($result=pg_fetch_array($query)){
							$BAccount=$result["BAccount"];
							$BName=$result["BName"];
							$BBranch=$result["BBranch"];
							$BID=$result["BID"];
							//$BCompany=$result["BCompany"];
							
							
							$qryname=pg_query("select * from \"VSearchCusCorp\" where \"CusID\"='$result[BCompany]'");
							$numrowsname=pg_num_rows($qryname);
							$resname=pg_fetch_array($qryname);
							
							if($numrowsname>0){
								$BCompany=$resname["full_name"];
							}else{
								$BCompany='THCAP';
							}
							
							
							$BType=$result["BType"];
							if($BType=="1"){
								$BType="กระแสรายวัน";
							}else if($BType=="2"){
								$BType="ออมทรัพย์";
							}
							$BActive=$result["BActive"];
							echo "<tr align=center bgcolor=#FFFFFF>";
								echo "<td>$BAccount</td>";
								echo "<td align=left>$BName</td>";
								echo "<td align=left>$BBranch</td>";
								echo "<td align=left>$BCompany</td>";
								echo "<td align=center>$BType</td>";
								//echo "<td><a href=\"#\" onclick=\"javascript:popU('frm_detailCredit.php?creditID=$creditID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=600,height=300')\"><img src=\"images/detail.gif\" width=19 height=19 style=\"cursor:pointer;\"></a></td>";
								echo "<td>$BActive</td>";
								echo"<td><a onclick=\"popU('frm_EditAccount.php?BID=$BID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\">
								<img src=\"../thcap_edit_newcon/images/edit_pa1.png\" width=\"25px;\" height=\"25px;\" style=\"cursor:pointer;\">
								</a></td>";
							echo "</tr>";
						}
						if($numrow==0){
							echo "<tr height=50><td colspan=7 align=center bgcolor=#FFFFFF><b>ไม่พบข้อมูล</b></td></tr>";
						}
					?>
					<form method="post" name="form1" action="frm_AddAccount.php">
					<tr height="50" bgcolor="#FFFFFF">
						<td colspan="7" align="right"><input type="submit" value="เพิ่มบัญชีธนาคารบริษัท"><input type="button" value="  Close  " onclick="javascript:window.close();"></td>
					</tr>
					</form>
				</table>
			</div>
        </td>
    </tr>
</table>
</body>
</html>