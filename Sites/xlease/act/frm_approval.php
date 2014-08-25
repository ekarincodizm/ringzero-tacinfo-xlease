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

    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(function(){
    $(window).bind("beforeunload",function(event){
        window.opener.$('div#div_admin_menu').load('list_admin_menu.php');
    });
});
</script>    

<frameset cols="220,*" border=0>
<frame src="menu_approval.php" scrolling=no>
<frame src="frm_approval_select_force.php" name="frm_r">
</frameset>

<body>
</body>
</html>