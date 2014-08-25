<?php
session_start();
include("../config/config.php");

$get_id_user = $_SESSION["av_iduser"];

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

pg_query("BEGIN WORK");

$payid=pg_query("select gas.gen_id('$doDate',1,2)");
$res_payid=pg_fetch_result($payid,0);

$insert_sql="insert into gas.\"PayToGas\" (\"payid\",\"dodate\",\"idmaker\") values  ('$res_payid','$doDate','$get_id_user')";
if($result_insert=pg_query($insert_sql)){
    $check_status = 0;
}else{
    $check_status = 1;
}

$in_sql="UPDATE gas.\"PoGas\" SET \"invoice\"='$invoice' WHERE \"poid\"='$id'";
if($result=pg_query($in_sql)){
    $check_status = 0;
}else{
    $check_status = 1;
}

if($check_status == 0){
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
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