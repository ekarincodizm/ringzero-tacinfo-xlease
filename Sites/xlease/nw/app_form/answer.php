<?php
	include("config/config.php");
	if($_SESSION['app_username']==""||$_SESSION['app_user_type']=="user")
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
<link href="css/ans.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div align="center">
	<?php require("proc/top.php"); ?>
	<div class="container">
    	<div class="title"><h1>ตอบกลับ</h1></div>
        <div class="content">
        	<ul class="list">
            	<li class="row darkgray">
                    <div class="name bold center inline">หัวข้อเรื่อง</div>
                    <div class="state bold center inline">ตอบกลับโดย</div>
                    <div class="date bold center inline">วันที่</div>
                    <div class="form bold center inline">การดำเนินการ</div>
                </li>
                <?php
					$q = "select * from $schema.\"reply\" where \"reply_to\"='1' and \"state\"<>'7' order by \"replyID\" desc";
					$qr = pg_query($q);
					$i = 0;
					while($rs = pg_fetch_array($qr))
					{
						$id = $rs['replyID'];
						$title = $rs['title'];
						$doer = $rs['doer'];
						
						$q1 = "select \"ans_date\" from $schema.\"answer\" where \"replyID\"='$id' order by \"answerID\" desc";
						$qr1 = pg_query($q1);
						$rs1 = pg_fetch_array($qr1);
						$do_time = $rs1['ans_date'];
						
						$i++;
						echo "<li class=\"row\">";
						echo "<div class=\"name left inline\"><a class=\"link\" href=\"show_ans.php?id=$id\">$title</a></div>";
						echo "<div class=\"state center inline\">$doer</div>";
						echo "<div class=\"date center inline\">$do_time</div>";
						echo "<div class=\"form center inline\"><span class=\"orange_botton\" onclick=\"delete_reply('$id');\">ปิดประเด็น</span></div>";
						echo "</li>";
					}
				?>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript">
function delete_reply(id) {
	if(confirm('คุณต้องการปิดประเด็นนี้ใช่หรือไม่')==true)
	{
		$.post('proc/delete_frm.php',{id:id},function(data){
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
}
</script>
</body>
</html>
<?php
	}
?>