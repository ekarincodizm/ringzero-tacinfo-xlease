<?php
session_start();
$get_userid = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../index.php");
    exit;
}
?>

<html>
<title><?php echo $_SESSION["session_company_name"]; ?></title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<!--
<frameset cols="220,*" border=0>
<frame src="add_acc_menu.php" scrolling=no>
<frame src="add_acc_manual.php" name="frm_r">
</frameset>
-->

<frameset rows="50,*" cols="*" framespacing="0" frameborder="no" border="0">
  <frame src="add_acc_menu.php" name="frm_t" scrolling="NO" noresize title="frm_t" >
  <frame src="add_acc_manual.php" name="frm_r" marginwidth="1" title="frm_r">
</frameset>


<body>
</body>
</html>
