<?php
session_start();
$get_userid = $_SESSION["av_iduser"];
if(empty($get_userid)){ 
    header("Refresh: 0; url=../index.php");
    exit();
}
?>

<html>
<title>AV.LEASING</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<frameset cols="220,*" border=0>
<frame src="menu_tranfer.php" scrolling=no>
<frame src="frm_tranfer.php" name="frm_r">
</frameset>

<body>
</body>
</html>
