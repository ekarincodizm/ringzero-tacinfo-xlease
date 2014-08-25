<?php
session_start();
include("../config/config.php");

$payid = pg_escape_string($_POST['payid']);
pg_query("BEGIN WORK");
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<fieldset><legend><B>ยกเลิกรายการ</B></legend>

<div align="center">

<?php

$in_sql="UPDATE gas.\"PayToGas\" SET \"cash\"=default,\"CQBank\"=default,\"CQID\"=default,\"CQDate\"=default,\"CQAmt\"=default,\"idauthority\"=default,\"Remark\"=default WHERE \"payid\"='$payid' ";
if($result=pg_query($in_sql)){
    $status = 0;
}else{
    $status = 1;
}

$in_sql2="UPDATE gas.\"PoGas\" SET \"status_approve\"='false' WHERE \"payid\"='$payid' ";
if($result=pg_query($in_sql2)){
    $status = 0;
}else{
    $status = 1;
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) อนุมัติเรื่อง Gas', '$datelog')");
	//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

<br>
<input type="button" value="  Back  " onclick="location.href='frm_gs_approve_cl.php'">

</div>

</fieldset>

        </td>
    </tr>
</table>

</body>
</html>