<?php
session_start();
$conn_string = "host=172.16.2.251 port=5432 dbname=devxleasenw21 user=dev password=nextstep";
$db_connect = pg_connect($conn_string) or die("Can't Connect !");

$schema = "application_form";

$seed = "S4fdL73GpdY90etP2Rwc";

$mail_host="mail.thaiace.co.th";	//e-mail server
$mail_encode="utf-8";	//e-mail encodding
$mail_usr="nattawut.sri@thaiace.co.th";	//username for authen
$mail_pwd="Thaiace99910";	//password for authen
$mail_sender="do_not_reply@thaiacemarket.com";	//sender e-mail
$sender="Thai Ace Capital Co.,Ltd."; //sender name
$html_email="true";	//email type html
$verify_url = "http://localhost/xlease-nw/xlease/nw/app_form/proc/verify.php";;	//verify register url
$verify_subject = "ยืนยันการสมัคสมาชิก - ระบบฟอร์มขอสินเชื่อออนไลน์::Thai Ace Capital Co.,Ltd.";

function chk_null($val){
	if($val=="")
	{
		$val="null";
	}
	else
	{
		$val="'".$val."'";
	}
	return $val;
}

//ฟังก์ชั่นสำหรับตั้งค่าการส่งอีเมล์
function smtpmail($email, $subject, $body, $mail_host, $mail_encode, $mail_usr, $mail_pwd, $mail_sender, $sender, $html_email)
{
	$mail = new PHPMailer();
	$mail->IsSMTP();         
	$mail->CharSet = $mail_encode;  // ในส่วนนี้ ถ้าระบบเราใช้ tis-620 หรือ windows-874 สามารถแก้ไขเปลี่ยนได้                        
	$mail->Host     = $mail_host; //  mail server ของเรา
	$mail->SMTPAuth = true;     //  เลือกการใช้งานส่งเมล์ แบบ SMTP
	$mail->Username = $mail_usr;   //  account e-mail ของเราที่ต้องการจะส่ง
	$mail->Password = $mail_pwd;  //  รหัสผ่าน e-mail ของเราที่ต้องการจะส่ง

	$mail->From     = $mail_sender;  //  account e-mail ของเราที่ใช้ในการส่งอีเมล
	$mail->FromName = $sender; //  ชื่อผู้ส่งที่แสดง เมื่อผู้รับได้รับเมล์ของเรา
	$mail->AddAddress($email);            // Email ปลายทางที่เราต้องการส่ง(ไม่ต้องแก้ไข)
	$mail->IsHTML(true);                  // ถ้า E-mail นี้ มีข้อความในการส่งเป็น tag html ต้องแก้ไข เป็น true
	$mail->Subject     =  $subject;        // หัวข้อที่จะส่ง(ไม่ต้องแก้ไข)
	$mail->Body     = $body;                   // ข้อความ ที่จะส่ง(ไม่ต้องแก้ไข)
	$result = $mail->send();       
	return $result;
}

function gen_msg($fname,$verify_url,$member_id,$verify_code)
{
	$msg = "<b>เรียน คุณ $fname</b><br><br>ขอบคุณที่สมัครสมาชิกกับ \"ระบบขอสินเชื่อออนไลน์\"<br><br>กรุณาคลิกลิงค์ด้านล่างเพื่อยืนยันการสมัครสมาชิก :<br><br><a href=\"{$verify_url}?member_id={$member_id}&member_verify_code={$verify_code}\">{$verify_url}?member_id={$member_id}&member_verify_code={$verify_code}</a><br><br>ขอแสดงความนับถือ<br><br>Thai Ace Capital Co.,Ltd.";
	return $msg;
}

function gen_rand_code($max_length)
{
	$arr_a_z = range("a","z");
	$arr_A_Z = range("A","Z");
	$arr_0_9 = range(0,9);
	$arr_a_9 = array_merge($arr_a_z, $arr_A_Z, $arr_0_9) ; 
	$str_a_9 = implode($arr_a_9);
	$str_a_9 = str_shuffle($str_a_9);
	$rand_code = substr($str_a_9,0,$max_length);
	return $rand_code;
}
?>