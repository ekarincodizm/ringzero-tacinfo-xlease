<?php
include('config/config.php');
session_start();

// process สำหรับเก็บประวัติการเข้าใช้งานเมนูจากการกดคีย์ลัด (F1,F2)

$date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$id_user = $_SESSION["av_iduser"];
$menuname = pg_escape_string($_POST['id']);

$sqlF = pg_query("select * from \"f_menu\" where \"path_menu\" = '$menuname'");
$rumrowsF = pg_num_rows($sqlF);
while($resF = pg_fetch_array($sqlF))
{
	$id_menu = $resF["id_menu"];
}

$status = 0;
pg_query('BEGIN');
$sql = "INSERT INTO menu_log(
           \"menuID\", id_user, menu_date)
    VALUES ('$id_menu','$id_user', '$date')";
	
$query = pg_query($sql);
if($query){}else{$status++;}
if($status==0){ pg_query('COMMIT');  }else{ pg_query('ROLLBACK');}
?>