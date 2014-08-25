<?php
require_once("../../sys_setup.php");
include("../../../../../config/config.php");

$id =$_REQUEST[id];
	 $query = "SELECT note FROM $dbtb_ta_join_payment WHERE id='$id' ";
	$sql=pg_query($query);
	$rs=pg_fetch_array($sql);
	
	$note=$rs['note'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head><title>:: หมายเหตุ ::</title>
<link rel="stylesheet" type="text/css" href="style.css"> 
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css"> 
</head>
<body>
<!-- ช่องกรอกข้อมูล -->
<table width="450"align="center" cellpadding="1" cellspacing="1" border="0" >
	<tr bgcolor="#DDDDDD"><td align="center"height="25"><h3>หมายเหตุ</h3></td></tr>
	<tr bgcolor="#DDDDDD">
		<td align="center"><textarea name="note" rows="5" style="background:#FEFBDA;width:95%"readonly><?php echo $rs['note'];?></textarea></td>
	</tr>
	<tr bgcolor="#DDDDDD"><td align="center"height="25"></td></tr>
</table><BR>
<table width="450"align="center" cellpadding="1" cellspacing="1" border="0" >
	<tr bgcolor="#FFFFFF"><td align="center"><input name="close" type="button"class="i_link" onClick="window.close()" value="ปิดหน้าต่างนี้"></td></tr>
</table>
</body>
</html>