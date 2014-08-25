<?php
	include("config/config.php");
	if($_SESSION['app_username']=="")
	{
		echo "<script type=\"text/javascript\">window.location.href = 'index.php';</script>";
	}
	else
	{
		$id = $_GET['id'];
		$q = "select * from $schema.\"reply\" where \"replyID\"='$id'";
		$qr = pg_query($q);
		$rs = pg_fetch_array($qr);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายละเอียดการแจ้งปรับปรุงฟอร์ม</title>
<link href="css/show_reply.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div align="center">
	<?php require('proc/top.php'); ?>
	<div class="container">
    	<div class="title bold"><h1>รายละเอียดการแจ้งปรับปรุงฟอร์ม</h1></div>
        <div class="reply_box">
        	<div class="row bold big" id="title"><?php echo $rs['title']; ?></div>
            <div class="row bottom_dot content"><?php echo $rs['content']; ?></div>
            <?php
				$q1 = "select * from $schema.\"answer\" where \"replyID\"='$id' order by \"answerID\" asc";
				$qr1 = pg_query($q1);
				$row = pg_num_rows($qr1);
				if($row!=0)
				{
					echo "<div class=\"white\">";
					while($rs1 = pg_fetch_assoc($qr1))
					{
						echo "<div class=\"row bold\">ตอบกลับโดย : ".$rs1['answer_by']." เมื่อวันที่ : ".$rs1['ans_date']."</div>";
						echo "<div class=\"row bottom_dot content\">".$rs1['answer']."</div>";
						
					}
					echo "</div>";
				}
			?>
        </div>
        <div class="row bold">ตอบกลับ : </div>
        <div class="row">
        	<form name="answer" method="post" action="proc/reanswer_process.php">
            	<input type="hidden" name="reply_id" id="reply_id" value="<?php echo $id; ?>" />
        		<textarea name="answer" id="answer" class="full"></textarea>
            </form>
        </div>
        <div class="row"><span class="btn save" onclick="save();">บันทึก</span></div>
    </div>
</div>
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript">
function save(){
	if($('#answer').val()=='')
	{
		alert('กรุณาระบุข้อมูลด้วยครับ');
	}
	else
	{
		document.answer.submit();
	}
}
</script>
</body>
</html>
<?php
}
?>