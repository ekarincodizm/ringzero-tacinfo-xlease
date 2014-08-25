<?php
session_start();	
include("../../config/config.php");
include("../../core/core_functions.php");

pg_query("BEGIN");
$status = 0;

$sql2= pg_query("select * from public.\"GroupCus\" ");
$numrow=pg_num_rows($sql2);
$numrow2 = $numrow + 1;
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$theradio=$_POST["theradio"];
$radiocode=$_POST["radiocode"];
$car=$_POST["car"];
$my=$_POST["s_idno"];

$sql3= pg_query("select * from public.\"Fa1\" where \"CusID\" = '$my' ");
$rownum=pg_num_rows($sql3);
if($rownum == 1)
{}
else
{
	$status++;
}

$tid = "G".core_generate_frontzero($numrow2, 10);

$id_user=$_SESSION["av_iduser"];
$logs_any_time_close = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

/*echo "สัญญาวิทยุ = ".$theradio."test<br>";
echo "รหัสวิทยุ = ".$radiocode."<br>";
echo "ทะเบียนรถ = ".$car."<br>";
echo "เจ้าของวิทยุ = ".$my."test";
echo "<br>จำนวนแถวขณะนี้ = ".$numrow;
echo "<br>จำนวนแถวใหม่= ".$numrow2;
echo "<br>จำนวนแถวใหม่กว่า= ".$tid;*/

$in_sql="insert into public.\"GroupCus\" (\"GroupCusID\",\"GStatus\",\"GType\") values ('$tid','ACTIVE','RadioContract')";
if($result=pg_query($in_sql))
{}
else
{
	$status++;
}

$in_sql2="insert into public.\"GroupCus_Active\" (\"GroupCusID\",\"CusState\",\"CusID\") values ('$tid','0','$my')";
if($result2=pg_query($in_sql2))
{}
else
{
	$status++;
}

$in_sql3="insert into public.\"RadioContract\" (\"COID\",\"RadioNum\",\"RadioCar\",\"RadioRelationID\",\"ContractStatus\",\"DoerID\",\"DoerStamp\") values ('$theradio','$radiocode','$car','$tid','0','$id_user','$logs_any_time_close')";
if($result3=pg_query($in_sql3))
{}
else
{
	$status++;
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) เพิ่มสัญญาวิทยุ(ลูกค้านอก)', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2>บันทึกสมบูรณ์ (สัญญาวิทยุ=".$theradio." รหัสวิทยุ=".$radiocode." ทะเบียนรถ=".$car." เจ้าของวิทยุ=".$my.") GropCusID=".$tid."</h2></center>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
	echo "<form method=\"post\" name=\"form1\" action=\"fAddRadioContract.php\">";
	echo "<center><input type=\"submit\" value=\"ตกลง\"></center></form>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2>บันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php'>";
	echo "<form method=\"post\" name=\"form2\" action=\"fAddRadioContract.php\">";
	echo "<input type=\"hidden\" name=\"theradio2\" value=\"$theradio\">";
	echo "<input type=\"hidden\" name=\"radiocode2\" value=\"$radiocode\">";
	echo "<input type=\"hidden\" name=\"car2\" value=\"$car\">";
	echo "<input type=\"hidden\" name=\"my2\" value=\"$my\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}
?>
