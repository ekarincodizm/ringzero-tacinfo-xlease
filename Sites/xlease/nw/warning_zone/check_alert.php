<?php
session_start();
include("../../config/config.php");
$idmenu = $_POST['brand'];
$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$sql = pg_query("SELECT e_time,detail_warning FROM f_menu_warning where id_menu = '$idmenu' and s_time < '$datenow' and e_time > '$datenow' and appstatus = '1'");
$rowchk = pg_num_rows($sql);
if($rowchk > 0){
echo "closed";
}else{
echo "nothing";
}

?>