<?php
session_start();
include("../config/config.php");
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$get_id_user = $_SESSION["av_iduser"];

$infidno = pg_escape_string($_POST['infidno']);
$insid = pg_escape_string($_POST['insid']);
$insmark = pg_escape_string($_POST['insmark']);
$company = pg_escape_string($_POST['company']);
$date_start = pg_escape_string($_POST['date_start']);
$date_end = pg_escape_string($_POST['date_end']);

$nowdate = date("Y/m/d");

pg_query("BEGIN WORK");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
    </tr>
    <tr>
        <td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบประกันภัย</h1></div>
<div class="wrapper">
<br>
<?php

$select_insid = pg_query("SELECT COUNT(\"InsID\") AS c_indis FROM \"insure\".\"InsureForce\" WHERE \"InsID\"='$insid';");
$res_insid=pg_fetch_result($select_insid,0);
if($res_insid > 0){
    echo '<div align="center" style="color:red; font-weight:bold;">พบข้อมูลซ้ำ!<br>เลขกรมธรรม์ '.$insid.' ได้ถูกเพิ่มไปแล้ว';
}else{

$in_sql="UPDATE insure.\"InsureForce\" SET \"InsID\"='$insid', \"InsMark\"='$insmark', \"DoDate\"='$nowdate' WHERE \"InsFIDNO\"='$infidno' ";
if($result=pg_query($in_sql)){
		

    if($company=='CPY'){
        pg_query("COMMIT");
        echo "บันทึกข้อมูลเรียบร้อยแล้ว<br><br><input name=\"button\" type=\"button\" onClick=\"window.open('frm_print_cpy.php?start_date=$date_start&end_date=$date_end&insid=$infidno','mywindow','')\" value=\"  Print  \" />";
    }else{
        pg_query("COMMIT");
        echo "บันทึกข้อมูลเรียบร้อยแล้ว<br><br><input name=\"button\" type=\"button\" onClick=\"window.open('frm_print_smk.php?start_date=$date_start&end_date=$date_end&insid=$infidno','mywindow','')\" value=\"  Print  \" />";
    }
	//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_id_user', '(TAL) พิมพ์ พรบ.', '$datelog')");
	//ACTIONLOG---
   
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้<br><br><INPUT TYPE=\"BUTTON\" VALUE=\"Back\" ONCLICK=\"history.go(-1)\">"; 
}

}
?>
<br>
</div>
        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>