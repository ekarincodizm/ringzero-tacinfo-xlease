<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>
<?php
include("../../config/config.php");

$COID = $_GET['COID'];

	$sql2 = pg_query("select \"AppvRemask\" from public.\"RadioContract\" where \"COID\" = '$COID'");
	list($note) = pg_fetch_array($sql2);
	 $textshow = 'หมายเหตุ';
	

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
				<textarea cols="40" rows="5" Readonly><?php echo $note ?></textarea>
			</td>
		</tr>
		<tr>
			<td align="center">
				<input type="button" onclick="window.close();" value=" ปิด " style="width:50px;">
			</td>
		</tr>
	</table>	
</body>
</html>