<?php
include("config/config.php");
if($_SESSION['app_username']==""||$_SESSION['app_user_type']=="user")
{
	echo "<script type=\"text/javascript\">window.location.href = 'index.php';</script>";
}
else
{
	$id = $_GET['id'];
	$q = "select \"formID\",\"company_name\",\"doer_name\",\"doer_time\" from $schema.\"app_frm\" where \"formID\"='$id'";
	$qr = pg_query($q);
	$rs = pg_fetch_array($qr);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>แจ้งปรับปรุงแบบฟอร์ม</title>
<link href="css/reply.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div align="center">
	<?php require("proc/top.php"); ?>
	<div class="container">
    	<form name="reply" id="reply" method="post" action="proc/reply_process.php">
    	<input type="hidden" name="formID" id="formID" value="<?php echo $rs['formID']; ?>" />
        <input type="hidden" name="company_name" id="company_name" value="<?php echo $rs['company_name']; ?>" />
        <input type="hidden" name="doer_name" id="doer_name" value="<?php echo $rs['doer_name']; ?>" />
        <input type="hidden" name="doer_time" id="doer_time" value="<?php echo $rs['doer_time']; ?>" />
    	<div class="title bold big"><h1>แจ้งปรับปรุงฟอร์มขอสินเชื่อ</h1></div>
        <div class="row" id="form_info">
        	<div class="row">ฟอร์มไอดี : #<?php echo $rs['formID']; ?></div>
            <div class="row">ลูกค้า : <?php echo $rs['company_name']; ?></div>
            <div class="row">ผู้ทำรายการ : <?php echo $rs['doer_name']; ?></div>
            <div class="row">วันที่ทำรายการ : <?php echo mb_substr($rs['doer_time'],0,10); ?></div>
        </div>
    	<div class="row">
        	<div class="row bold">หัวเรื่อง : </div>
            <div class="row">
            	<input type="text" name="title" id="title" class="full" maxlength="150" />
            </div>
        </div>
        <div class="row">
        	<div class="row bold">สถานะ : </div>
            <div class="row">
            	<select name="pick_status" id="pick_status" class="middle_half">
                    <?php
					$q1 = "select * from $schema.\"status\" where \"status_type\"='2' order by \"statusID\"";
					$qr1 = pg_query($q1);
					if($qr1)
					{
						$row1 = pg_num_rows($qr1);
						if($row1!=0)
						{
							while($rs1 = pg_fetch_array($qr1))
							{
								echo "<option value=\"".$rs1['statusID']."\">".$rs1['status_name']."</option>";
							}
						}
					}
					?>
                </select>
            </div>
        </div>
        <div class="row">
        	<div class="row bold">รายละเอียด : </div>
            <div class="row">
            	<textarea name="content" id="content" class="full"></textarea>
            </div>
        </div>
        <div class="row">
        	<span class="btn save" onclick="save();">บันทึก</span>
        </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
<script type="text/javascript">
function save() {
	if($('#title').val()==''||$('#content').val()=='')
	{
		alert('กรุณาระข้อมูลให้ครบทุกช่องด้วยครับ');
	}
	else
	{
		document.reply.submit();
	}
}
</script>
</body>
</html>
<?php
}
?>