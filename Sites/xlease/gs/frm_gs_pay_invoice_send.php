<?php
include("../config/config.php");

$id = pg_escape_string($_POST['id']);
$invoice = pg_escape_string($_POST['invoice']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>

</head>
<body>    
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
    <tr>
        <td>

<fieldset><legend><B>เพิ่มข้อมูลการชำระเิงิน</B></legend>
<div align="center">
<?php
$in_sql="UPDATE \"GasPo\" SET \"invoice\"='$invoice' WHERE \"poid\"='$id'";
if($result=pg_query($in_sql)){
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    echo "<u>ไม่</u>สามารถบันทึกข้อมูลได้";
}
?>

<br>
<br>
<input type="button" value="  Back  " onclick="location.href='frm_gs_pay.php'">
</div>
</fieldset>


        </td>
    </tr>
</table>

</body>
</html>