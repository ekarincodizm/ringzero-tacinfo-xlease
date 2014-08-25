<?php
session_start();
$_SESSION["ses_idno"] = "";
$get_userid = $_SESSION["av_iduser"];
if(empty($get_userid)){ 
    header("Refresh: 0; url=../index.php");
    exit();
}
?>

<html>
<title>AV.LEASING</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php 
if($_GET['type'] == 'outstanding'){
?>
<frameset ROWS="60,*" border=0>
<frame src="menu_outstanding.php" scrolling=no>
<frame src="frm_viewcuspayment.php?idno_names=<?php echo $_GET['idno_names']; ?>" name="frm_r" scrolling=no>
</frameset>
<?php
}else{
?>
<frameset ROWS="60,*" border=0>
<frame src="menu.php" scrolling=no>
<frame src="frm_cuspayment.php" name="frm_r" scrolling=no>
</frameset>
<?php } ?>

<body>
</body>
</html>
