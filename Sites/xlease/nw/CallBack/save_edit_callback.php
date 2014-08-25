<?php
include("../../config/config.php");
?>

<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<?php
$id_user=$_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$CallBackID = $_POST["CallBackID"];

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

$callTypeID = $_POST["calltypeID"]; //ประเภทการติดต่อที่เลือก
$callFromID = $_POST["callFromID"]; //แหล่งที่มาที่เลือก

if($TimeCallBack_select == "t1") // ถ้าเลือกแบบสะดวกให้ติดต่อกลับทันที
{
	$TimeCallBack = "NULL";
}
else // ถ้าเลือกแบบสะดวกให้ติดต่อตามวันเวลาที่กำหนด
{
	$TimeCallBack = "'$datepicker $time_h:$time_m:00'";
}

// เช็คก่อนว่ามีการโทรกลับไปแล้วหรือยัง
$query_chk = pg_query("select * from public.\"callback\" where \"CallBackID\" = '$CallBackID' ");
while($result_chk = pg_fetch_array($query_chk))
{
	$CallBackStatus = $result_chk["CallBackStatus"]; // สถานะของการติดต่อ
	$callFromIDold = $result_chk["callFromID"]; // สถานะของการติดต่อ
}

if($CallBackStatus == "1")
{
	pg_query("BEGIN");
	$status = 0;
	$error = "";

	if($calltype == "1")
	{ // ถ้าเลือกบันทึกแบบ ติดต่อแผนก
		$in_sql="update public.\"callback\" set \"CusName\"='$CusName', \"CusPhone\"='$CusPhone', \"CallTitle\"='$TitleCall', \"CallDetial\"='$DetailCall', \"Want_dep_id\"='$dep' , \"Want_id_user\"=NULL , \"TimeCallBack\"=$TimeCallBack,\"callTypeID\"='$callTypeID' where \"CallBackID\"='$CallBackID' ";
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
			$in_sql="update public.\"callback\" set \"CusName\"='$CusName', \"CusPhone\"='$CusPhone', \"CallTitle\"='$TitleCall', \"CallDetial\"='$DetailCall', \"Want_dep_id\"=NULL , \"Want_id_user\"='$user' , \"TimeCallBack\"=$TimeCallBack,\"callTypeID\"='$callTypeID' where \"CallBackID\"='$CallBackID' ";
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
	
	//บันทึกแหล่งที่มาในตาราง  callback_details_from ด้วย
	if($callTypeID == $callTypeIDold){ //ถ้ามีค่าเดิมไม่ต้องทำอะไร	
	}else{ //ค่าใหม่ต้องเคลียร์ของเก่าออกแล้วบันทึกใหม่
		$delfrom="delete from callback_details_from where \"CallBackID\"='$CallBackID'";
		if($resdel=pg_query($delfrom)){
		}else{
			$status++;
		}
		
		for($p=0;$p<sizeof($callFromID);$p++){
			$insfrom="INSERT INTO callback_details_from(
					\"CallBackID\", \"callFromID\")
			VALUES ('$CallBackID', '$callFromID[$p]')";
			if($resfrom=pg_query($insfrom)){
			}else{
				$status++;
			}
		}
		
		//update ค่า  callTypeID ใหม่
		$up="update public.\"callback\" set \"callTypeID\"='$callTypeID' where \"CallBackID\"='$CallBackID' ";
		if($result=pg_query($in_sql))
		{}
		else
		{
			$status++;
		}
	}
	

	if($status == 0)
	{
		pg_query("COMMIT");
		echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
		echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
	}
	else
	{
		pg_query("ROLLBACK");
		echo "<center><h2><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด $error กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
		echo "<form method=\"post\" name=\"form2\" action=\"edit_CallBack.php\">";
		echo "<input type=\"hidden\" name=\"CallBackID\" value=\"$CallBackID\">";
		echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
	}
}
else
{
	echo "<center><h2><font color=\"#FF0000\">ไม่สามารถแก้ไขรายการได้ เนื่องจากรายการนี้ได้รับการติดต่อกลับไปแล้ว</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
}
?>