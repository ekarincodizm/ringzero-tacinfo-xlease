<?php
	session_start();
	include("config.php");
	$sql="select * from \"TrStatistic\"";
	$dbquery=pg_query($sql);
	$rows=pg_num_rows($dbquery);
	$visitTotal=$rows;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TR Member</title>
<link href="info.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="800" border="0" cellpadding="0" cellspacing="0">
	<tr>
    	<td></td>
    </tr>
    <tr>
        <td id="footer" align="center" valign="middle"><div id="statistic">ผู้เยี่ยมชม : <?php echo $visitTotal; ?></div>บริษัท ไทยเอซลิสซิ่ง จำกัด - THAIACE LEASING COMPANY LIMITED</td>
    </tr>
</table>
</body>
</html>