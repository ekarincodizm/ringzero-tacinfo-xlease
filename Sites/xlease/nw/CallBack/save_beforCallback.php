<?php
session_start();
include("../../config/config.php");

$id_user=$_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$CusName = $_POST["CusName"]; // ชื่อลูกค้า
$CusPhone = $_POST["CusPhone"]; // เบอร์ติดต่อกลับ
$calltype = $_POST["calltype"]; // ประเภทที่ต้องการติดต่อ 1-แผนก , 2-พนักงาน
$dep = $_POST["dep"]; // รหัสแผนก
$userfull = $_POST["user"]; // พนักงานที่ต้องการจะติดต่อ
$TitleCall = $_POST["TitleCall"]; // ชื่อเรื่องที่จะติดต่อ
$DetailCall = $_POST["DetailCall"]; // รายละเอียดที่จะติดต่อ
$TimeCallBack_select = $_POST["TimeCallBack_select"]; // สถานะวันเวลาที่สะดวกให้ติดต่อกลับ
$datepicker = $_POST["datepicker"]; // วันที่สะดวกให้ติดต่อกลับ
$time_h = $_POST["time_h"]; // ชั่วโมงที่สะดวกให้ติดต่อกลับ
$time_m = $_POST["time_m"]; // นาทีที่สะดวกให้ติดต่อกลับ


$calltypeID = $_POST["calltypeID"]; //ประเภทการติดต่อที่เลือก
$callFromID = $_POST["callFromID"]; //แหล่งที่มาที่เลือก


if($TimeCallBack_select == "t1") // ถ้าเลือกแบบสะดวกให้ติดต่อกลับทันที
{
	$TimeCallBack = "NULL";
}
else // ถ้าเลือกแบบสะดวกให้ติดต่อตามวันเวลาที่กำหนด
{
	$TimeCallBack = "'$datepicker $time_h:$time_m:00'";
}

pg_query("BEGIN");
$status = 0;
$error = "";

if($calltype == "1")
{ // ถ้าเลือกบันทึกแบบ ติดต่อแผนก
	$in_sql="insert into public.\"callback\" (\"CusName\",\"CusPhone\",\"CallTitle\",\"CallDetial\",\"doerID\",\"doerStamp\",\"Want_dep_id\",\"CallBackStatus\",\"TimeCallBack\",\"callTypeID\")
			values ('$CusName','$CusPhone','$TitleCall','$DetailCall','$id_user','$logs_any_time','$dep','1',$TimeCallBack,'$calltypeID')";
	if($result=pg_query($in_sql))
	{}
	else
	{
		$status++;
	}
	
}
elseif($calltype == "2")
{ // ถ้าเลือกบันทึกแบบ ติดต่อพนักงาน
	$user = substr($userfull,0,3); // ตัดเฉพาะรหัสพนักงานออกมา
	
	$sql_chk_user = pg_query("select * from public.\"Vfuser\" where \"id_user\" = '$user' ");
	$numrow_user = pg_num_rows($sql_chk_user);
	if($numrow_user == 1)
	{
		$in_sql="insert into public.\"callback\" (\"CusName\",\"CusPhone\",\"CallTitle\",\"CallDetial\",\"doerID\",\"doerStamp\",\"Want_id_user\",\"CallBackStatus\",\"TimeCallBack\",\"callTypeID\")
				values ('$CusName','$CusPhone','$TitleCall','$DetailCall','$id_user','$logs_any_time','$user','1',$TimeCallBack,'$calltypeID')";
		if($result=pg_query($in_sql))
		{}
		else
		{
			$status++;
		}
	}
	else
	{ // ถ้าไม่พบพนักงาน จะไม่สามารถบันทึกได้
		$status++;
		$error = "ไม่พบพนักงาน";
	}
}

//ดึง CallBackID ล่าสุดขึ้นมาเพื่อนำมาบันทึก
$qrymax=pg_query("SELECT max(\"CallBackID\") as \"CallBackID\" FROM callback");
list($CallBackID)=pg_fetch_array($qrymax);

//บันทึกแหล่งที่มาในตาราง  callback_details_from ด้วย
for($p=0;$p<sizeof($callFromID);$p++){
	$insfrom="INSERT INTO callback_details_from(
            \"CallBackID\", \"callFromID\")
    VALUES ('$CallBackID', '$callFromID[$p]')";
	if($resfrom=pg_query($insfrom)){
	}else{
		$status++;
	}
}
if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) ลงบันทึกการติดต่อจากลูกค้า', '$logs_any_time')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
	echo "<form method=\"post\" name=\"form1\" action=\"frm_Index.php\">";
	echo "<center><input type=\"submit\" value=\"ตกลง\"></center></form>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด $error กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php'>";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_Index.php\">";
	echo "<input type=\"hidden\" name=\"CusName\" value=\"$CusName\">";
	echo "<input type=\"hidden\" name=\"CusPhone\" value=\"$CusPhone\">";
	echo "<input type=\"hidden\" name=\"calltype\" value=\"$calltype\">";
	echo "<input type=\"hidden\" name=\"dep\" value=\"$dep\">";
	echo "<input type=\"hidden\" name=\"user\" value=\"$userfull\">";
	echo "<input type=\"hidden\" name=\"TitleCall\" value=\"$TitleCall\">";
	echo "<input type=\"hidden\" name=\"DetailCall\" value=\"$DetailCall\">";
	echo "<input type=\"hidden\" name=\"TimeCallBack_select\" value=\"$TimeCallBack_select\">";
	echo "<input type=\"hidden\" name=\"datepicker\" value=\"$datepicker\">";
	echo "<input type=\"hidden\" name=\"time_h\" value=\"$time_h\">";
	echo "<input type=\"hidden\" name=\"time_m\" value=\"$time_m\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}
?>