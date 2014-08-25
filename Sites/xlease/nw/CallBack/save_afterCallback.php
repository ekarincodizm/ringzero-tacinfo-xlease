<?php
include("../../config/config.php");

$id_user=$_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$CallBackID = $_POST["CallBackID"];
$DetailCallBack = $_POST["DetailCallBack"];
$show = $_POST["show"];

$callResult=$_POST["callResult"];  //สถานะการปิดงานว่ามีการบังคับปิดงานหรือไม่
$close=$_POST["close"];  //บอกว่าปิดงานหรือยัง
$stsResult=$_POST["stsResult"];  //บอกว่าสำเร็จหรือไม่สำเร็จ
$resultno = $_POST["rej"]; //เหตุผลที่ปฏิเสธ

$TimeCallBack_select = $_POST["TimeCallBack_select"]; // สถานะวันเวลาที่สะดวกให้ติดต่อกลับ
$datepicker = $_POST["datepicker"]; // วันที่สะดวกให้ติดต่อกลับ
$time_h = $_POST["time_h"]; // ชั่วโมงที่สะดวกให้ติดต่อกลับ
$time_m = $_POST["time_m"]; // นาทีที่สะดวกให้ติดต่อกลับ


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

if($callResult==1){ //ถ้าเท่ากับ 1 แสดงว่ามีการบังคับปิดงาน
	if($close==1){ //ปิดงานแล้วให้ update ข้อมูลในตาราง  callback
		$in_sql="update public.\"callback\" set \"TalkDetail\" = '$DetailCallBack' , \"callback_id_user\" = '$id_user' , \"callback_Stamp\" = '$logs_any_time' , \"CallBackStatus\" = '2' where \"CallBackID\" = '$CallBackID' ";
		if($result=pg_query($in_sql)){
		}else{
			$status++;
		}
		
		//ถ้าปิดงาน เลือกไม่สำเร็จ ให้บันทึกเหตุผลในการปฏิเสธด้วย
		if($stsResult==0){
			for($t=0;$t<sizeof($resultno);$t++){
				$ins="INSERT INTO callback_details_reject(
				\"CallBackID\", \"callRejID\") VALUES ('$CallBackID', '$resultno[$t]')";
				
				if($result2=pg_query($ins)){
				}else{
					$status++;
				}
			}
		}
	}else{ //ยังไม่ปิดงาน ให้ insert การสนทนาในตาราง callback_details_additional
		$ins="INSERT INTO callback_details_additional(
            callback_id_user, callback_stamp, \"TalkDetail\", 
            \"TimeCallBack\", \"CallBackID\")
		VALUES ('$id_user','$logs_any_time', '$DetailCallBack', 
            $TimeCallBack, '$CallBackID');";
			
			
		if($result2=pg_query($ins)){
		}else{
			$status++;
		}
		
		//update ว่าอยู่ระหว่างการติดต่อ
		$in_sql="update public.\"callback\" set \"CallBackStatus\" = '3' where \"CallBackID\" = '$CallBackID' ";
		if($result=pg_query($in_sql)){
		}else{
			$status++;
		}
	}

}else{ //กรณีข้อมูลธรรมดาที่ไม่มีการบังคับปิดงาน
	$in_sql="update public.\"callback\" set \"TalkDetail\" = '$DetailCallBack' , \"callback_id_user\" = '$id_user' , \"callback_Stamp\" = '$logs_any_time' , \"CallBackStatus\" = '2' where \"CallBackID\" = '$CallBackID' ";
	if($result=pg_query($in_sql)){
	}else{
		$status++;
	}
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) ติดต่อกลับลูกค้า', '$logs_any_time')");
	//ACTIONLOG---
	pg_query("COMMIT");
	//pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	echo "<meta http-equiv='refresh' content='2; URL=my_callback.php?show=$show'>";
	//echo "<form method=\"post\" name=\"form1\" action=\"my_callback.php\">";
	//echo "<center><input type=\"submit\" value=\"ตกลง\"></center></form>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_IndexAdd.php'>";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_callback.php\">";
	echo "<input type=\"hidden\" name=\"CallBackID\" value=\"$CallBackID\">";
	echo "<input type=\"hidden\" name=\"DetailCallBack\" value=\"$DetailCallBack\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}
?>