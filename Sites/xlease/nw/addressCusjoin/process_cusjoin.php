<?php
session_start();
include("../../config/config.php");

$addr_user = $_SESSION["av_iduser"];
$addr_stamp = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$carid=$_POST["carid"]; 
$cusco=$_POST["cusco"];
list($cusid,$cusname)=explode("#",$cusco);  
$address=$_POST["address"];
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>บันทึกข้อมูลที่อยู่ลูกค้าโอนสิทธิ์เข้าร่วม</title>
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">
 <?php
//update ข้อมูลในตาราง ta_join_main
$up="UPDATE ta_join_main SET address='$address', addr_user='$addr_user' ,addr_stamp='$addr_stamp' WHERE car_license_seq='0' and deleted='0' and cancel='0' and  carid='$carid'";
if($resup=pg_query($up)){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$addr_user', '(TAL) จัดการที่อยู่ค่าเข้าร่วม', '$addr_stamp')");
	//ACTIONLOG---
	echo "<div style=\"text-align:center\"><h2>บันทึกข้อมูลเรียบร้อยแล้ว</h2></div>";
}else{
	echo "<div style=\"text-align:center\"><h2>การบันทึกข้อมูลผิดพลาด</h2></div>";
}


echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";


?>
</td>
</tr>
</table>
</body>
</html>