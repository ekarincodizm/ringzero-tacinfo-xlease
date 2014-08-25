<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
$ntid=$_GET['ntid'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>เหตุผลที่ไม่อนุมัติ</title>
</head>
<body>
<div  align="left"><h2>เหตุผลที่ไม่อนุมัติ</h2></div>
<div id="panel" style="padding-top: 10px;">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F0F0F0">
	<?php 
	$query = pg_query("select result_noapprove from \"nw_statusNT\" 
						where \"NTID\" = '$ntid'"); 

	$numrows = pg_num_rows($query);

	if($result = pg_fetch_array($query)){
		$result_noapprove = $result["result_noapprove"];
	} //end if
	?>
	<tr align="center" class="odd">
		<td>
			<textarea name="result" cols="60" rows="5"><?php echo $result_noapprove;?></textarea>
		</td>
	</tr>
	<tr><td align="center" bgcolor="#FFFFFF" height="50"><input type="button" onclick="window.close();" value="x ปิดหน้านี้"></td></tr>
</table>
</div>
</body>
</html>