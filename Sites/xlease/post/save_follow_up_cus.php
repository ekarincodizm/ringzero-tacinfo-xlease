<?php
session_start();
include("../config/config.php");
$get_groupid = $_SESSION["av_usergroup"];
$get_userid = $_SESSION["av_iduser"];
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

<div style="height:50px; width:auto; text-align:center; opacity:20;"><h1>บันทึกการติดตาม</h1></div>

<div style="height:50px; width:500px; text-align:left; margin:0px auto;">


<?php
$curdate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$IDNO = pg_escape_string($_POST['u_idno']);
$CusID = pg_escape_string($_POST['u_cusid']);
//$IDNO = $_SESSION["ses_idno"];
//$CusID = $_SESSION["ses_scusid"];
$followdetail = pg_escape_string($_POST['followdetail']); 
$followdetail= str_replace("\n", "<br>\n", "$followdetail"); 
$svaeType = pg_escape_string($_POST['svaeType']);
$selectCus = pg_escape_string($_POST['selectCus']);

if(empty($get_groupid) OR empty($get_userid))
{ 
    echo "<div align=center><h2><font color=\"#FF0000\">ผิดผลาด ไม่พบข้อมูล แผนกหรือผู้ใช้งาน</font></h2></div><br>"; 
}
elseif($followdetail == "") // ถ้าไม่ได้กรอกรายละเอียด
{
	echo "<div align=center><h2><font color=\"#FF0000\">ผิดผลาด กรุณาระบุรายละเอียด ด้วย</font></h2></div><br>";
}
elseif($svaeType == "2" && $selectCus == "") // ถ้าเลือก บันทึกข้อมูลเข้าข้อมูลลูกค้า แต่ไม่ได้เลือกลูกค้า
{
	echo "<div align=center><h2><font color=\"#FF0000\">ผิดผลาด กรณีบันทึกข้อมูลเข้าข้อมูลลูกค้า โปรดเลือกลูกค้าที่ต้องการบันทึก ด้วย</font></h2></div><br>";
}
else
{
	pg_query("BEGIN");
	$status = 0;
	
	if($svaeType == "1") // บันทึกข้อมูลเฉพาะในสัญญานี้ 
	{
		$in_sql="insert into \"FollowUpCus\" (\"FollowDate\",\"GroupID\",\"userid\",\"IDNO\",\"CusID\",\"FollowDetail\") values  ('$curdate','$get_groupid','$get_userid','$IDNO','$CusID','$followdetail')";
	}
	elseif($svaeType == "2") // บันทึกข้อมูลเข้าข้อมูลลูกค้า
	{
		$in_sql="insert into \"thcap_FollowUpCusCorp\" (\"FollowDate\",\"GroupID\",\"userid\",\"contractID\",\"CusCorpID\",\"FollowDetail\") values  ('$curdate','$get_groupid','$get_userid','$IDNO','$selectCus','$followdetail')";
	}
	else
	{
		$status++;
	}
	
	if($result=pg_query($in_sql))
	{
		// สำเร็จ
	}
	else
	{
		$status++;
	}
	
	if($status == 0)
	{
		pg_query("COMMIT");
		
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', '(TAL) ลงบันทึการติดตาม-สัญญา', '$curdate')");
		//ACTIONLOG---

		echo "<div align=center><font color=\"#0000FF\">บันทึกข้อมูลเรียบร้อยแล้ว</font></div>";
	}
	else
	{
		pg_query("ROLLBACK");
		
		echo "<center><div align=center><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด</font></div></center>";
	}
}

?>
<div align="center">
<FORM METHOD=GET ACTION="follow_up_cus.php">
	<input type="hidden" name="idno" value="<?php echo $IDNO; ?>">
	<input type="submit" value="BACK"  />
</FORM>
</div>

</div>
</div>

</body>
</html>