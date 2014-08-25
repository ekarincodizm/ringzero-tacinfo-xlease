<?php
include('config/config.php');
session_start();

$date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$id_user = $_SESSION["av_iduser"];
$menuname = pg_escape_string($_POST['id']);
$status = 0;
pg_query('BEGIN');
$sql = "INSERT INTO menu_log(
           \"menuID\", id_user, menu_date)
    VALUES ('$menuname','$id_user', '$date')";
	
$query = pg_query($sql);
if($query){}else{$status++;}
if($status==0){ pg_query('COMMIT');  }else{ pg_query('ROLLBACK');}
?>