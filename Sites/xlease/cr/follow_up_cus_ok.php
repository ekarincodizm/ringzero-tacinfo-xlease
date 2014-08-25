<?php
session_start();
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AV. leasing co.,ltd</title>

<style type="text/css">
<!--
BODY{
	font-family: Tahoma;
	font-size: 11px;
}
TEXTAREA,SELECT,INPUT{
	font-family: Tahoma;
	font-size: 11px;
	color: #3A3A3A;
}
legend{
	font-family: Tahoma;
	font-size: 12px;	
	color: #0000CC;
}
fieldset{
	padding:3px;
}
-->
</style>

</head>

<body style="background-color:#ffffff; margin-top:0px;">

<div id="wmax" style="width:500px; border:#666666 solid 0px; margin-top:0px;">
<div style="height:50px; width:auto; text-align:center; opacity:20;"><h1>รายละเอียดการติดต่อ</h1></div>
<div style="height:50px; width:500px; text-align:left; margin:0px auto;">

<?php
$curdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$get_groupid = $_SESSION["av_usergroup"];
$get_userid = $_SESSION["av_iduser"];
$sescr_idno = $_SESSION["sescr_idno"];
$sescr_scusid = $_SESSION["sescr_scusid"];
$followdetail = pg_escape_string($_POST['followdetail']);
	$followdetail= str_replace("\n", "<br>\n", "$followdetail");

if(empty($get_groupid) OR empty($get_userid)){ 
    echo "<div align=center>ผิดผลาด ไม่พบข้อมูล แผนกหรือผู้ใช้งาน</div>"; 
}else{
    $in_sql="insert into \"FollowUpCus\" (\"FollowDate\",\"GroupID\",\"userid\",\"IDNO\",\"CusID\",\"FollowDetail\") values  ('$curdate','$get_groupid','$get_userid','$sescr_idno','$sescr_scusid','$followdetail')";
    if($result=pg_query($in_sql)) {
	    echo "<div align=center>บันทึกข้อมูลเรียบร้อยแ้ล้ว</div>";
    }else{
	    echo "<div align=center>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</div>";
    }
}

?>
<div align="center">
<FORM METHOD=GET ACTION="follow_up_cus.php">
<input type="submit" value="BACK"  />
</FORM>
</div>

</div>
</div>

</body>
</html>