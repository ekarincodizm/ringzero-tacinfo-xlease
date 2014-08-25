<?php
include("../config/config.php");

//mailer
require("../PHPMailer_v5.1/class.phpmailer.php");

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
			$username = $_POST['tbx_user'];
			$passwd = $_POST['first_passwd'];
			$passwd = md5($seed.$passwd);
			$fullname = $_POST['fullname'];
			$email = $_POST['Email'];
			$telephone = $_POST['telephone'];
			$mobile = $_POST['mobile'];
			$date = date("Y-m-d H:i:s");;
			$status = 1;
			$user_type = 2;
			$verify_code = gen_rand_code(20);
			pg_query("BEGIN");
			$q = "insert into $schema.\"app_member\"(\"app_usr\",\"app_passwd\",\"app_fullname\",\"app_email\",\"app_telephone\",\"app_mobile\",\"regis_date\",\"status\",\"user_type\",\"verify_code\",\"verify_status\") values('$username','$passwd','$fullname','$email','$telephone','$mobile','$date','$status','$user_type','$verify_code','0') returning \"memberID\"";
			if($qr = pg_query($q))
			{
				$rs = pg_fetch_array($qr);
				$memberID = $rs['memberID'];
				if(!file_exists("../upload")&&!is_dir("../upload"))
				{
					mkdir("../upload", 0777);
				}
				if(!file_exists("../upload/member")&&!is_dir("../upload/member"))
				{
					mkdir("../upload/member", 0777);
				}
				mkdir("../upload/member/".$memberID,0777);
				if($_FILES["id_card"]["name"]!= "")
				{
					move_uploaded_file($_FILES["id_card"]["tmp_name"],"../upload/member/".$memberID."/".iconv("UTF-8","TIS-620",$_FILES["id_card"]["name"]));
				}
				$msg = gen_msg($fullname,$verify_url,$memberID,$verify_code);
				$mail_status=smtpmail($email,$verify_subject,$msg,$mail_host,$mail_encode,$mail_usr,$mail_pwd,$mail_sender,$sender,$html_email);
				if($mail_status)
				{
					pg_query("COMMIT");
					echo "<li class=\"alert\">ทำรายการเรียบร้อยแล้ว กรุณาตรวจสอบอีเมล์ของท่านเพื่อยืนยันการสมัครสมาชิกอีกครั้งครับ</li>";
					echo "<li class=\"alert\"><span class=\"btn primary\" onclick=\"window.location.href = '../index.php';\">ตกลง</span></li>";
				}
				else
				{
					pg_query("ROLLBACK");
					echo "<li class=\"alert\">ไม่สามารถส่งอีเมล์ยืนยันการสมัครสมาชิกให้ท่านได้ อาจมีการปรับปรุงระบบ กรุณาลองใหม่ภายหลังครับ</li>";
					echo "<li class=\"alert\"><span class=\"btn primary\" onclick=\"window.location.href = '../index.php';\">ตกลง</span></li>";
				}
			}
			else
			{
				echo "<li class=\"alert\">ไม่สามารถทำรายการได้ อาจมีการปรับปรุงระบบ กรุณาลองใหม่ภายหลังครับ</li>";
				echo "<li class=\"alert\"><span class=\"btn primary\" onclick=\"window.location.href = '../index.php';\">ตกลง</span></li>";
				
			}
			?>
		</ul>
	</div>
</div>
</body>
</html>