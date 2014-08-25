<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>
<?php
include("../../config/config.php");

$tempid = $_GET['cartempid'];

	$sql2 = pg_query("SELECT \"reason_not_app\" FROM \"Fc_temp\" where \"CarIDtemp\" = '$tempid' ");
	list($note) = pg_fetch_array($sql2);
	 $textshow = 'เหตุผลที่ไม่อนุมัติ';


?>
<body bgcolor="#DDDDDD">
	<table align="center" width="100%" >
		<tr>
			<td align="center">
				<h2>
				<?php		
				echo $textshow;
				 ?>
				 </h2>
			</td>
		</tr>
		<tr>
			<td align="center">
				<textarea cols="50" rows="5" Readonly><?php echo $note ?></textarea>
			</td>
		</tr>
		<tr>
			<td align="center">
				<input type="button" value=" ปิด " onclick="window.close();" style="width:100px;height:50px;">
			</td>
		</tr>
	</table>	
</body>
</html>