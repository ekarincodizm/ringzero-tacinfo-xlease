<?php
session_start();
include("../config/config.php");

$id = pg_escape_string($_POST['id']);
$invoice = pg_escape_string($_POST['invoice']);

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
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

<fieldset><legend><B>แก้ไขข้อมูล</B></legend>
<div align="center">
<?php
$in_sql="UPDATE gas.\"PoGas\" SET \"invoice\"='$invoice' WHERE \"poid\"='$id'";
if($result=pg_query($in_sql)){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id','(TAL) แก้ไขเลขที่ใบกำกับ/ใบเสร็จ Gas', '$datelog')");
	//ACTIONLOG---
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    echo "<u>ไม่</u>สามารถบันทึกข้อมูลได้";
}
?>

<br>
<br>
<input type="button" value="  Back  " onclick="location.href='frm_gs_edit.php'">
</div>
</fieldset>


        </td>
    </tr>
</table>

</body>
</html>