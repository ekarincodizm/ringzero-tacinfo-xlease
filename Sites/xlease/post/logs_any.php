<?php
include("../config/config.php");

$idno = pg_escape_string($_REQUEST['idno']);
$idmenu = pg_escape_string($_REQUEST['idmenu']);
$logs_any_time_close = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

if(empty($idno) OR empty($idmenu) OR empty($_SESSION["logs_any_time_open"]) OR empty($logs_any_time_close)) exit;

$insert_logs="insert into \"LogsAnyFunction\" 
(\"id_menu\",\"user_id\",\"time_open\",\"ref_id\",\"time_close\") values 
('$idmenu','$_SESSION[logs_any_id_user]','$_SESSION[logs_any_time_open]','$idno','$logs_any_time_close')";

if($result=pg_query($insert_logs)){
    $_SESSION['logs_any_time_open'] = "";
}else{
    //Insert Logs Error !!!
}
?>