<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>
<html>
<title>AV.LEASING</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<frameset cols="220,*" border=0>
<frame src="menu_notice_month.php" scrolling=no>
<frame src="frm_notice_force_month.php" name="frm_r">
</frameset>

<body>
</body>
</html>