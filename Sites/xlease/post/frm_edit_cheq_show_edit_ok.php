<?php
session_start();
include("../config/config.php");
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
$edit_cheq = $_POST['edit_cheq'];
$newmemo = $_POST['newmemo'];
$oldmemo = $_POST['oldmemo'];
$edit_postid = $_POST['edit_postid'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1></h1></div>

<div class="wrapper">

<fieldset><legend><B>ทำรายการเช็คคืน</B></legend>

<div align="center">
<?php

if(!empty($newmemo)){
    $iduser = $_SESSION["av_iduser"];
    $nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
    $memo = "\nวันที่ $nowdate; โดย $iduser;\n".$newmemo."\n-----------------------------------\n".$oldmemo;
    $in_sql="UPDATE \"FCheque\" SET \"IsReturn\"='TRUE',\"memo\"='$memo' WHERE \"ChequeNo\"='$edit_cheq' ";
}else{
    $in_sql="UPDATE \"FCheque\" SET \"IsReturn\"='TRUE' WHERE \"ChequeNo\"='$edit_cheq' ";
}

if($result=pg_query($in_sql)){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ทำรายการเช็คคืน', '$add_date')");
	//ACTIONLOG---
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    echo "<u>ไม่</u>สามารถบันทึกข้อมูลได้";
}
?>
</div>

</div>
        </td>
    </tr>
</table>

<div align="center">
<input name="button" type="button" onclick="window.location='frm_edit_cheq.php'" value="  ย้อนกลับ  " />
</div>

</body>
</html>