<?php
session_start();
include("../config/config.php");
$IDCarTax = pg_escape_string($_POST["IDCarTax"]);
$O_RECEIPT = pg_escape_string($_POST["O_RECEIPT"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">
<?php
pg_query("BEGIN WORK");
$status = 0;


$upf="update \"FOtherpay\" set \"RefAnyID\"='$IDCarTax' where \"O_RECEIPT\" = '$O_RECEIPT'";
if($result=pg_query($upf)){
}else{
	$status=$status+1;
}

$upc="update carregis.\"CarTaxDue\" set \"cuspaid\"='TRUE' where \"IDCarTax\" = '$IDCarTax'";
if($result=pg_query($upc)){
}else{
	$status=$status+1;
}

if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 20px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเีรียบร้อยแล้ว</b></font></div>";
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}
?>
<br>
<div style="padding:5px;text-align:center;">
<input type="button" value="  ปิด  " onclick="javascript:RefreshMe();">
</div>
</td>
</tr>
</table>
</body>
</html>