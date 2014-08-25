<?php
session_start();
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>
<html>
<title><?php echo $_SESSION['session_company_name']; ?></title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<frameset cols="230,*" border=0>
<frame src="menu_car.php" scrolling=no>
<frame src="frm_car_show.php" name="frm_r">
</frameset>

<body>
</body>
</html>