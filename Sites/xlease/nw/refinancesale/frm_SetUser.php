<?php
session_start();
include("../../config/config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>จัดการพนักงานที่ชักชวน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
			<div class="header"><h1></h1></div>
			<div class="wrapper">
				<div style="float:left"><input type="button" value="  กลับ  " onclick="window.location='frm_Setup.php'"></div> 
				<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
				<div style="clear:both; padding: 10px;"></div>   
				
				<fieldset><legend><B>จัดการพนักงานที่ชักชวนลูกค้า</B></legend>
					<div style="padding:20px;">
					<table width="90%" cellpadding="1" cellspacing="1" border="0" bgcolor="#CCCCCC" align="center">
						<tr align="center" height="25">
							<td width="10%"><b>ลำดับที่</b></td>
							<td width="20%"><b>รหัสพนักงาน</b></td>
							<td><b>ชื่อ - นามสกุล</b></td>
							<td width="10%"><b>ลบ</b></td>
						</tr>
						
						<?php
							$i=0;
							$qry_user=pg_query("select a.\"id_user\",b.\"fullname\" from refinance.\"user_invite\" a 
							left join \"Vfuser\" b on a.\"id_user\" = b.\"id_user\" where a.\"status_use\" = 'TRUE'");
							while($res_user=pg_fetch_array($qry_user)){
								$i++;
								$id_user=$res_user["id_user"];
								$fullname = $res_user["fullname"];
								echo "<tr height=25 bgcolor=#FFFFFF><td align=center>$i</td>";
								echo "<td align=center>$id_user</td>";
								echo "<td>$fullname</td><td  align=center><img src=\"images/delete.gif\" width=\"10\" height=\"10\" onclick=\"if(confirm('คุณยืนยันที่จะลบรายการนี้!!')){location.href='process_userinvite.php?id_user=$id_user&method=delete'}\" style=\"cursor:pointer;\"></td></tr>";
							
							}
							if($i == 0){
								echo "<tr height=50 bgcolor=#FFFFFF><td align=center colspan=4><b>ยังไม่มีพนักงานที่ชักชวนลูกค้า</b></td></tr>";
							}
						?>
					</table>
					<table width="90%" border="0" align="center">
						<tr height="50" align="right"><td><input type="button"value="เพิ่มพนักงาน" onclick="window.location='frm_AddUser.php'"></td></tr>
					</table>
					</div>
				</fieldset>
			</div>
        </td>
    </tr>
</table>          

</body>
</html>