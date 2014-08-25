<?php
include("config/config.php");
$str = $_POST['md5_hash'];
if($str!="")
{
	$md5 = md5($seed.$str);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div class="file_box">
	<form action="test.php" name="gen_md5_frm" id="gen_md5_frm" method="post">
        <input type="text" name="md5_hash" id="md5_hash" />
        <input type="submit" name="hash_btn" id="hash_btn" value="Generate" />
    </form>
</div>
<div id="md5_str"><?php echo $md5; ?></div>
</body>
</html>