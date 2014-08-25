<?php
include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>บันทึกข้อมูล::Application Form</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center">
	<div class="main_container">
    	<ul>
        	<li class="login_title bold">การแจ้งเตือน :: Application Form</li>
<?php
$formID = $_GET['id'];
$content = $_POST['txtarea_edit_details'];
$post_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$poster = $_SESSION['app_userid'];
pg_query('BEGIN');
$status = 0;

$q_ins = "insert into $schema.\"questions\"(\"formID\",\"content\",\"post_time\",\"poster\") values('$formID','$content','$post_time','$poster')";
if(!pg_query($q_ins))
{
	$status++;
}
$q_upd = "update $schema.\"app_frm\" set \"form_state\"='5' where \"formID\"='$formID'";
if(!pg_query($q_upd))
{
	$status++;
}

if($status!=0)
{
	pg_query("ROLLBACK");
	echo "<li class=\"alert\">ไม่สามารถบันทึกรายการได้</li>";
	echo "<li class=\"alert\"><span class=\"btn primary\" onclick=\"window.location.href = 'admin.php';\">ตกลง</span></li>";
}
else
{
	pg_query("COMMIT");
	echo "<li class=\"alert\">บันทึกข้อมูลเรียบร้อยแล้ว</li>";
	echo "<li class=\"alert\"><span class=\"btn primary\" onclick=\"window.location.href = 'admin.php';\">ตกลง</span></li>";
}
?>
		</ul>
	</div>
</div>
</body>
</html>