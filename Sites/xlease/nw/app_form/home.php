<?php
	include("config/config.php");
	if($_SESSION['app_username']=="")
	{
		echo "<script type=\"text/javascript\">window.location.href = 'index.php';</script>";
	}
	else
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>หน้าของฉัน</title>
<link href="css/home.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div align="center">
	<?php require("proc/top.php"); ?>
	<div class="container">
    	<div class="title"><h1>แบบฟอร์มที่อนุมัติแล้ว</h1></div>
        <div class="content">
        	<ul class="list">
            	<li class="row darkgray">
                    <div class="name bold center inline">หัวข้อเรื่อง</div>
                    <div class="state bold center inline">สถานะ</div>
                    <div class="state bold center inline">ผู้ทำรายการ</div>
                    <div class="date bold center inline">วันที่</div>
                    <div class="form bold center inline">รับทราบ</div>
                </li>
                <?php
					$userid = $_SESSION['app_userid'];
					$q = "select \"formID\",\"company_name\",\"approve_user\",\"approve_stamp\",\"form_state\" from $schema.\"app_frm\" where (\"form_state\"='3' or \"form_state\"='4') and \"doer\"='$userid' and \"accepted\"='0' order by \"formID\" desc";
					$qr = pg_query($q);
					$i = 0;
					while($rs = pg_fetch_array($qr))
					{
						$id = $rs['formID'];
						$title = $rs['company_name'];
						$state = $rs['form_state'];
						$q1 = "select \"status_name\" from $schema.\"status\" where \"statusID\"='$state'";
						$qr1 = pg_query($q1);
						if($q1)
						{
							$rs1 = pg_fetch_array($qr1);
							$state = $rs1['status_name'];
						}
						$approve_userid = $rs['approve_user'];
						
						$q_user = "select \"app_fullname\" from $schema.\"app_member\" where \"memberID\"='$approve_userid'";
						$qr_user = pg_query($q_user);
						$rs_user = pg_fetch_array($qr_user);
						$approve_user = $rs_user['app_fullname'];
						
						$approve_stamp = $rs['approve_stamp'];
						if($i%2==0)
						{
							echo "<li class=\"row lightgray\">";
						}
						else
						{
							echo "<li class=\"row\">";
						}
						$i++;
						echo "<div class=\"name left inline\"><a class=\"link\" href=\"show_form.php?id=$id\">$title</a></div>";
						echo "<div class=\"state center inline\">$state</div>";
						echo "<div class=\"state center inline\">$approve_user</div>";
						echo "<div class=\"date center inline\">$approve_stamp</div>";
						echo "<div class=\"form center inline\"><span class=\"orange_botton\" style=\"margin-left:10px;\" onclick=\"mark('$id','3')\">รับทราบ</span></div>";
						echo "</li>";
					}
				?>
            </ul>
        </div>
        <!-- block 2 -->
    	<div class="title"><h1>แบบฟอร์มที่ส่งกลับ</h1></div>
        <div class="content">
        	<ul class="list">
            	<li class="row darkgray">
                    <div class="name bold center inline">หัวข้อเรื่อง</div>
                    <div class="state bold center inline">สถานะ</div>
                    <div class="state bold center inline">ผู้ทำรายการ</div>
                    <div class="date bold center inline">วันที่</div>
                    <div class="form bold center inline">ตรวจสอบ</div>
                </li>
                <?php
					$q = "select \"formID\",\"company_name\",\"doer\",\"approve_stamp\",\"form_state\" from $schema.\"app_frm\" where \"form_state\"='5' and \"doer\"='$userid' order by \"formID\" desc";
					$qr = pg_query($q);
					$i = 0;
					while($rs = pg_fetch_array($qr))
					{
						$id = $rs['formID'];
						$title = $rs['company_name'];
						$state = $rs['form_state'];
						$q1 = "select \"status_name\" from $schema.\"status\" where \"statusID\"='$state'";
						$qr1 = pg_query($q1);
						if($q1)
						{
							$rs1 = pg_fetch_array($qr1);
							$state = $rs1['status_name'];
						}
						$doerid = $rs['doer'];
						
						$q_user = "select \"app_fullname\" from $schema.\"app_member\" where \"memberID\"='$doerid'";
						$qr_user = pg_query($q_user);
						$rs_user = pg_fetch_array($qr_user);
						$doer = $rs_user['app_fullname'];
						
						$doer_time = $rs['doer_time'];
						if($i%2==0)
						{
							echo "<li class=\"row lightgray\">";
						}
						else
						{
							echo "<li class=\"row\">";
						}
						$i++;
						echo "<div class=\"name left inline\">$title</div>";
						echo "<div class=\"state center inline\">$state</div>";
						echo "<div class=\"state center inline\">$doer</div>";
						echo "<div class=\"date center inline\">$doer_time</div>";
						echo "<div class=\"form center inline\"><span class=\"orange_botton\" onclick=\"window.location.href='edit_frm.php?id=$id'\">ตรวจสอบ</span></div>";
						echo "</li>";
					}
				?>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript">
function mark(id,type) {
	$.post('proc/mark_frm.php',{id:id,type:type},function(data){
		if(data=='1')
		{
			window.location.reload();
		}
		else
		{
			alert('ไม่สามารถดำเนินการได้');
		}
	});
}
</script>
</body>
</html>
<?php
	}
?>