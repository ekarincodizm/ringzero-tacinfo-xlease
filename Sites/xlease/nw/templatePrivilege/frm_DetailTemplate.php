<?php
session_start();
include("../../config/config.php");
$f_tempID=$_GET["tempID"];

$query_main=pg_query("select * from \"nw_template\" where \"tempID\"= ' $f_tempID'");
$resultmain=pg_fetch_array($query_main);
$tempName=$resultmain["tempName"];
$createDate=$resultmain["createDate"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แสดงเมนูใน Template</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
<table width="600" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
    <td>      
		<div class="wrapper">
			<fieldset><legend><B>รายละเอียด Template</B></legend>	
				<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
				<tr align="left">
					<td valign="top" colspan="3"><b>ชื่อ Template : </b><?php echo $resultmain["tempName"];?></td>
				</tr>
				<tr align="left">
					<td valign="top" colspan="3"><b>วันที่สร้าง :</b><?php echo $resultmain["createDate"];?></td>
				</tr>
				
				<tr>
					<td colspan="3">
						<table width="100%" border="0" cellSpacing="1" cellPadding="5" align="center" bgcolor="#CCCCCC">
						<tr bgcolor="#D0DCA0"><td colspan="2"><b>ชื่อเมนู</b></td><td><b>สถานะเมนู</b></td></tr>
						<?php
						$qry_menu=pg_query("select a.\"id_menu\",b.\"name_menu\",b.\"status_menu\" from \"nw_templateDetail\" a 
						LEFT OUTER JOIN f_menu b on a.id_menu=b.id_menu
						where \"tempID\"='$f_tempID' order by b.name_menu ");
						$numrow_menu=pg_num_rows($qry_menu);
						while($resmenu=pg_fetch_array($qry_menu)){
							$stas=$resmenu['status_menu'];
							if($stas=='1'){
								$txtstas="ใช้งาน";
							}else{
								$txtstas="ระงับใช้งาน";
							}
							?>
							<tr bgcolor="#FFFFFF">    
								<td width="85" height="25"><?php echo $resmenu["id_menu"]; ?></td>
								<td width="545"><?php echo $resmenu["name_menu"]; ?></td>
								<td width="126"><?php echo $txtstas;?></td>
							</tr>
						<?php
						}
						if($numrow_menu ==0 || $f_tempID==""){
							echo "<tr height=50 bgcolor=#FFFFFF><td align=center colspan=3><b>ไม่มีรายการ</b></td></tr>";
						}
				?>
						</table>
					</td>
				</tr>
				<tr align="center">
				  <td colspan=3 height="50"><input name="button" type="button" onclick="javascript:window.close();" value=" Close " /></td>
				</tr>
				</table>
			</fieldset> 
		</div>
    </td>
</tr>
</table>         
</body>
</html>