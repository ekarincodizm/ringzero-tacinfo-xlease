<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>บันทึกข้อมูล::Application Form</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center">
	<div class="main_container">
    	<ul>
        	<li class="login_title bold">การแจ้งเตือน :: Application Form</li>
			<?php
			$id = $_POST['formID'];
			$doer = $_POST['doer_name'];
			$do_time = $_POST['doer_time'];
			$title = $_POST['title'];
			$content = $_POST['content'];
			$date = date("Y-m-d H:i:s");
			$state = $_POST['pick_status'];
			$poster = $_SESSION['app_username'];
			$q = "insert into $schema.\"reply\"(\"formID\",\"doer\",\"do_time\",\"title\",\"content\",\"post_time\",\"poster\",\"state\",\"reply_to\") values('$id','$doer','$do_time','$title','$content','$date','$poster','$state','2')";
			$qr = pg_query($q);
			if($qr)
			{
				echo "<li class=\"alert\">ดำเนินการเรียบร้อยแล้ว</li>";
				echo "<li class=\"alert\"><span class=\"btn primary\" onclick=\"window.location.href = '../admin.php';\">ตกลง</span></li>";
			}
			else
			{
				echo "<li class=\"alert\">ไม่สามารถบันทึกข้อมูลได้</li>";
				echo "<li class=\"alert\"><span class=\"btn primary\" onclick=\"window.location.href = '../admin.php';\">ตกลง</span></li>";
			}
            ?>
		</ul>
	</div>
</div>
</body>
</html>