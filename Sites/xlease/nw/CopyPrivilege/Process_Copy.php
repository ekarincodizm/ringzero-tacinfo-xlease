<?php
session_start();
include("../../config/config.php");
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$cid=$_POST["cid"]; //พนักงานที่เลือกให้รับสิทธิ์ตามต้นฉบับ
$type_copy=$_POST["type_copy"]; // c1 คือ เหมือนกับสิทธิต้นฉบับ  c2 คือ เพิ่มสิทธิที่ต้นฉบับมี
$user_origin = $_POST["user_origin"];


pg_query("BEGIN WORK");
$status = 0;

if(sizeof($cid) == 0) // ถ้ายังไม่ได้เลือกพนักงานที่จะรัีบมอบสิทธิ์
{
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>ยังไม่ได้เลือกพนักงานที่จะรัีบมอบสิทธิ์!!</b></font><br><br>";
	echo "<form method=\"post\" name=\"form3\" action=\"frm_SelectCopy.php\">";
	echo "<input type=\"hidden\" name=\"user_origin\" value=\"$user_origin\">";
	echo "<input type=\"hidden\" name=\"type_copy\" value=\"$type_copy\">";
	echo "<input type=\"submit\" value=\"ย้อนกลับ\">";
	echo "</form></div>";
}
else
{	
	if($type_copy == "c1")
	{
		for($i=0;$i<sizeof($cid);$i++) //id ของพนักงานที่จะรับมอบสิทธิ์
		{
			$qry_del="delete from public.\"f_usermenu\" where \"id_user\" = '$cid[$i]' ";
			if($resultD=pg_query($qry_del)){
			}else{
				$status++;
			}
	
			$qry_origin=pg_query("select * from public.\"f_usermenu\" where \"id_user\" = '$user_origin' order by \"id_menu\" ");
			while($res_origin=pg_fetch_array($qry_origin))
			{
				$id_menu = $res_origin["id_menu"];
				$status_menu = $res_origin["status"];
				$qry_ins="insert into public.\"f_usermenu\"(\"id_menu\",\"id_user\",\"status\") values ('$id_menu','$cid[$i]','$status_menu')";
				if($resultS=pg_query($qry_ins)){
				}else{
					$status++;
				}
			}
		}
	}
	elseif($type_copy == "c2")
	{
		for($i=0;$i<sizeof($cid);$i++) //id ของพนักงานที่จะรับมอบสิทธิ์
		{
			$qry_origin=pg_query("select * from public.\"f_usermenu\" where \"id_user\" = '$user_origin' order by \"id_menu\" ");
			while($res_origin=pg_fetch_array($qry_origin))
			{
				$id_menu = $res_origin["id_menu"];
				$status_menu = $res_origin["status"];
				
				$qry_copy=pg_query("select * from public.\"f_usermenu\" where \"id_user\" = '$cid[$i]' and \"id_menu\" = '$id_menu' "); // เช็คก่อนว่ามีข้อมูลหรือยัง
				$numcopy = pg_num_rows($qry_copy);
				if($numcopy == 0) // ถ้ายังไม่มีข้อมล
				{
					$qry_ins="insert into public.\"f_usermenu\"(\"id_menu\",\"id_user\",\"status\") values ('$id_menu','$cid[$i]','$status_menu')";
					if($resultS=pg_query($qry_ins)){
					}else{
						$status++;
					}
				}
			}
		}
	}
}

if(sizeof($cid) > 0)
{
	if($status == 0)
	{
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) คัดลอกสิทธิ', '$add_date')");
		//ACTIONLOG---
		pg_query("COMMIT");
		echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
		echo "<form method=\"post\" name=\"form1\" action=\"frm_Index.php\">";
		echo "<input type=\"submit\" value=\"ตกลง\">";
		echo "</form></div>";
	}
	else
	{
		pg_query("ROLLBACK");
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font><br><br>";
		echo "<form method=\"post\" name=\"form2\" action=\"frm_Index.php\">";
		echo "<input type=\"submit\" value=\"กลับหน้าแรก\">";
		echo "</form></div>";
	}
}

?>