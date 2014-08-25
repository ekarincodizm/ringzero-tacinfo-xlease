<?php
session_start();
include("../config/config.php");
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$get_id_user = $_SESSION["av_iduser"];

$InsLIDNO = pg_escape_string($_POST['InsLIDNO']);
$insid = pg_escape_string($_POST['insid']);
$netpremium = pg_escape_string($_POST['netpremium']);
$insdate = pg_escape_string($_POST['datepicker']);
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
        <td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
    </tr>
    <tr>
        <td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบประกันภัย</h1></div>
<div class="wrapper">

<?php
$in_sql="UPDATE \"insure\".\"InsureLive\" SET \"InsID\"='$insid',\"NetPremium\"='$netpremium',\"InsDate\" = '$insdate' WHERE \"InsLIDNO\"='$InsLIDNO'";

if($result=pg_query($in_sql)){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_id_user', '(TAL) ตรวจรับกรมธรรม์', '$datelog')");
		//ACTIONLOG---
    echo "แก้ไขข้อมูลเรียบร้อยแล้ว";
}else{
    echo "<u>ไม่</u>สามารถแก้ไขข้อมูลได้";
}
?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_insure_unforce_search_insid.php'">

</div>

        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

<div align="center"><br><input name="button" type="button" onclick="window.location='../list_menu.php'" value="กลับเมนูหลัก" /></div>

</body>
</html>