<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
</head>
<?php
include("../../../config/config.php");

$fapk = $_GET['fapk'];
$type = $_GET['type'];

if($type == 'note'){
	$sql2 = pg_query("SELECT note FROM thcap_financial_amount_add_temp where financial_amount_serial = '$fapk' ");
	list($note) = pg_fetch_array($sql2);
	 $textshow = 'หมายเหตุ';
}else if($type == 'notapp'){
	$sql2 = pg_query("SELECT app_not_note FROM thcap_financial_amount_add_temp where financial_amount_serial = '$fapk' ");
	list($note) = pg_fetch_array($sql2);
	$textshow = 'เหตุผลที่ไม่อนุมัติ';
}	

?>
<body bgcolor="#DDDDDD">
	<table align="center" width="500px" >
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
				<textarea cols="70" rows="5" Readonly><?php echo $note ?></textarea>
			</td>
		</tr>
	</table>	
</body>
</html>