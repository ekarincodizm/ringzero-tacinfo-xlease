<?php
	echo "<div class=\"headbar\">";
    echo "<div class=\"top_menu\">";
	if($_SESSION['app_user_type']=="user")
	{
		echo "<div class=\"menu\"><a href=\"home.php\">หน้าของฉัน</a></div>";
		echo "<div class=\"menu\"><a href=\"application_frm.php\">เขียนแบบฟอร์ม</a></div>";
		echo "<div class=\"menu\"><a href=\"my_form.php\">แบบฟอร์มทั้งหมด</a></div>";
	}
	else
	{
		echo "<div class=\"menu\"><a href=\"admin.php\">หน้าของฉัน</a></div>";
		echo "<div class=\"menu\"><a href=\"answer.php\">ตอบกลับ</a></div>";
		echo "<div class=\"menu\"><a href=\"approved.php\">พิจารณาแล้ว</a></div>";
	}
    echo "<div class=\"top_right_menu\">";
    echo "<div class=\"my_menu\">ยินดีต้อนรับคุณ ".$_SESSION['app_username']."</div>";
    echo "<div class=\"my_menu link\"><a href=\"profile.php\">แก้ไขโปรไฟล์</a></div>";
    echo "<div class=\"my_menu link\"><a href=\"proc/logout.php\">ออกจากระบบ</a></div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
?>