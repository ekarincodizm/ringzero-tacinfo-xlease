<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$id_user=$_SESSION["av_iduser"];
$quryuser=pg_query("select \"emplevel\" from \"fuser\" where \"id_user\"='$id_user' ");
list($leveluser)=pg_fetch_array($quryuser);
$app_date = Date('Y-m-d H:i:s');
$menu="Appv";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) อนุมัติยกเว้นเช็คค้ำตั๋วเงิน</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
<style>
#include
{
margin-left:auto;
margin-right:auto;
margin-top:20px;
width:80%;
}
</style>
</head>
<body>
	<div class="header" align="center"><h1>(THCAP)อนุมัติยกเว้นเช็คค้ำตั๋วเงิน</h1></div>
	<div id="include"><?php include("frm_listAppv.php");?></div> <!--ตรวจสอบยกเว้นเช็คค้ำตั๋วเงิน -->
	<div id="include"><?php include("frm_list_insur_con.php");?></div> <!--รายการสัญญาที่ต้องมีเช็คค้ำตั๋วเงิน -->
	<div id="include"><?php include("show_history.php");?></div> <!--ประวัติการอนุมัติล่าสุด 30 รายการ -->
</body>
</html>