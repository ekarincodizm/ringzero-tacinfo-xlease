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

<frameset cols="220,*" border=0>
<frame src="menu.php" scrolling=no>
<frame src="frm_maker_select_force.php" name="frm_r">
</frameset>

<body>
</body>
</html>
