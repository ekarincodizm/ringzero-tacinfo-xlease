<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$id_tranpay=$_POST["id_tranpay"]; 
$result=$_POST["result"];
$remark=$_POST["remark"];
$curdate = date('Y-m-d H:m:s');
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

$ins="insert into \"TranPay_audit\" (\"id_tranpay\",\"result\",\"auditorID\",\"auditStamp\",\"auditRemask\",\"auditNum\") values ('$id_tranpay','$result','$id_user','$curdate','$remark','1')";
if($resins=pg_query($ins)){
}else{
	$status++;
}

if($status == 0){
	pg_query("COMMIT");
	echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}else{
	pg_query("ROLLBACK");
	echo $ins_error."<br>";
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}
?>
</td>
</tr>
</table>
</body>
</html>