<?php
include("../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ยืนยันการสมัคสมาชิก</title>
</head>

<body>
<?php
$memberID = pg_escape_string($_GET['member_id']);
if($memberID=="")
{
	$memberID = 0;
}
$verify_code = $_GET['member_verify_code'];
$q = "select * from $schema.\"app_member\" where \"memberID\"='$memberID'";
$qr = pg_query($q);
$row = pg_num_rows($qr);
if($row!=0)
{
	$rs = pg_fetch_array($qr);
	$vri = $rs['verify_code'];
	$state = $rs['verify_status'];
	if($vri==$verify_code&&$state=='0')
	{
		$q1 = "update $schema.\"app_member\" set \"verify_code\"=null, \"verify_status\"='1' where \"memberID\"='$memberID'";
		if(pg_query($q1))
		{
			echo "
				<script type=\"text/javascript\">
					alert('ยืนยันการสมัครเรียบร้อยแล้วครับ');
					window.location.href='../index.php';
				</script>
			";
		}
		else
		{
			echo "
				<script type=\"text/javascript\">
					alert('ไม่สามารถทำรายการได้ กรุณาลองใหม่ภายหลังครับ');
					window.location.href='../index.php';
				</script>
			";
		}
	}
	else if($vri!=$verify_code)
	{
		echo "
			<script type=\"text/javascript\">
				alert('รหัสยืนยันตัวตนของคุณไม่ถูกต้องครับ');
				window.location.href='../index.php';
			</script>
		";
	}
	else if($state=='1')
	{
		echo "
			<script type=\"text/javascript\">
				alert('คุณเคยยืนยันตัวตนแล้วครับ');
				window.location.href='../index.php';
			</script>
		";
	}
}
else
{
	echo "
		<script type=\"text/javascript\">
			alert('ไม่พบสมาชิกที่ระบุกรุณาตรวจสอบลิงค์ว่าถูกต้องหรือไม่');
			window.location.href='../index.php';
		</script>
	";
}
?>
</body>
</html>