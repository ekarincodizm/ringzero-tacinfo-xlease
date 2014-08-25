<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$auto_id=$_REQUEST["auto_id"]; 
$statusapp=$_REQUEST["stsapp"];  

if($auto_id==""){
	$auto_id=$_POST["auto_id"];
}
if(isset($_POST["appv"])){
	$statusapp="1";//อนุมัติ
}else{
	$statusapp="0";//ไม่อนุมัติ
}
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">
 <?php
pg_query("BEGIN WORK");
$status = 0;

//ตรวจสอบรายการว่าอนุมัติไปก่อนหน้านี้หรือยัง
$qrycheck=pg_query("select \"securID\",\"returnDate\",\"CusIDReceiveReturn\"
	from \"temp_securities_reqreturns\"
	WHERE auto_id='$auto_id' and \"statusApp\"='2'");
$numrowchk = pg_num_rows($qrycheck);

$res=pg_fetch_array($qrycheck);
$securID = $res["securID"];
$CusID = $res["CusIDReceiveReturn"];
$returnDate = $res["returnDate"];

if($numrowchk>0){
	if($statusapp==1){ //กรณีอนุมัติ ต้องดูก่อนว่าอนุมัติเพิ่มข้อมูลหรือแก้ไข
		//update ว่าได้คืนหลักทรัพย์นี้ืแล้วในตาราง nw_securities
		$upnw="update nw_securities set cancel='TRUE',\"returnDate\"='$returnDate',\"CusIDReceiveReturn\"='$CusID' where \"securID\"='$securID'";
		if($res_upnw=pg_query($upnw)){
		}else{
			$status++;
		}
	}
	//กรณีอนุมัติหรือไม่อนุมัติให้อัพเดทด้วยว่าได้อนุมัติแล้ว
	$up_temp="update \"temp_securities_reqreturns\" set \"appUser\"='$app_user', 
				\"appDate\"='$app_date', 
				\"statusApp\"='$statusapp'
				where \"auto_id\" = '$auto_id'";
	if($res_temp=pg_query($up_temp)){
	}else{
		$error4=$up_temp;
		$status++;
	}	

}else{
	echo "<div align=center><h2>รายการนี้ได้รับการอนุมัติไปก่อนหน้านี้แล้ว กรุณาตรวจสอบ!!</h2></div>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}
if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '(ALL) อนุมัติการคืนหลักทรัพย์ค้ำประกัน', '$app_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}else{
	pg_query("ROLLBACK");
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	echo "<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" />";
}
?>
</td>
</tr>
</table>
</body>
</html>