<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
</head>
<body>
<form method="post" action="frm_update_year_process.php">
<table align="center" width="30%" border="0">
<tr>
	<td>ชื่อ Schemas : <input type="text" name="schemas"></td>
</tr>
<tr>
	<td>ชื่อ Table : <input type="text" name="table"></td>
</tr>
<tr>
	<td>ชื่อ Column : <input type="text" name="column"></td>
</tr>
<tr>
	<td><input type="submit" value="submit"></td>
</tr>
</table>
</form>
</body>
</html>