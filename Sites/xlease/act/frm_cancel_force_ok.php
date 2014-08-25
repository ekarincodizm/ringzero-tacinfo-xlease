<?php
include("../config/config.php");
session_start();
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$iduser = $_SESSION["av_iduser"];
$insid = pg_escape_string($_POST['insid']);
$remark = pg_escape_string($_POST['remark']);
$hiddenremark = pg_escape_string($_POST['hiddenremark']);
$CoPayInsReady = pg_escape_string($_POST['CoPayInsReady']);
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

<?php

$nowdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

if($CoPayInsReady == 't'){
    if(!empty($remark)){
        $remark = "\nวันที่ $nowdate; โดย $iduser;\n".$remark."\n-----------------------------------\n".$hiddenremark;
        $in_sql="UPDATE \"insure\".\"InsureForce\" SET \"Remark\"='$remark',\"Cancel\"='TRUE' WHERE \"InsFIDNO\"='$insid'";
        if($result=pg_query($in_sql)){
		
			//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$iduser', '(TAL) ขอยกเลิกกรมธรรม์', '$datelog')");
			//ACTIONLOG---
            echo "ยกเลิกกรมธรรม์เรียบร้อยแล้ว";
        }else{
            echo "<u>ไม่</u>สามารถยกเลิกกรมธรรม์ได้";
        }
    }else{
        echo "กรุณาใส่หมายเหตุที่ยกเลิก";
    }
}else{
    $remark = "\nวันที่ $nowdate; โดย $iduser;\nERROR:รอการหักคืน\n".$remark."\n-----------------------------------\n".$hiddenremark;
    $in_sql="UPDATE \"insure\".\"InsureForce\" SET \"Remark\"='$remark',\"Cancel\"='TRUE' WHERE \"InsFIDNO\"='$insid'";
    if($result=pg_query($in_sql)){
			
			//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$iduser', '(TAL) ขอยกเลิกกรมธรรม์', '$datelog')");
			//ACTIONLOG---
        echo "ยกเลิกกรมธรรม์เรียบร้อยแล้ว";
    }else{
        echo "<u>ไม่</u>สามารถยกเลิกกรมธรรม์ได้";
    }
}
?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_cancel_force.php'">

</div>

        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>