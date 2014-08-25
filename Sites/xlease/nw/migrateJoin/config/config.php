<?php
session_start();
@ini_set('display_errors', '1');



// Database Connection (Postgres)
$conn_string = "host=172.16.2.251 port=5432 dbname=devxleasenw12 user=dev password=nextstep";
$db_connect = pg_connect($conn_string) or die("Can't Connect !");
//date_default_timezone_set('Asia/Bangkok');

$connect_db = mysql_connect("172.16.2.251","root","devbase")or die ("Cannot connect to MySQL Database"); 
mysql_query("SET NAMES 'UTF8'");

$mysql_db_select = 'ta_tal_1r4_mg';

//ฟังค์ชั่น ตั้งเวลาปิด session
function setSessionTime($_timeSecond){
    if(!isset($_SESSION['ses_time_life'])){
        $_SESSION['ses_time_life']=time();
    }
    if(isset($_SESSION['ses_time_life']) && time()-$_SESSION['ses_time_life']>$_timeSecond){
        if(count($_SESSION)>0){
            foreach($_SESSION as $key=>$value){
                unset($$key);
                unset($_SESSION[$key]);
            }
        }
    }else{
        $_SESSION['ses_time_life']=time();
    }
}
//จบฟังค์ชั่น ตั้งเวลาปิด session

setSessionTime(30*60); //เรียกใช้งาน การตั้งเวลาปิด session ไว้ 10 นาที หากไม่มีการใช้งาน ระบบจะปิดการใช้งาน ต้อง Login ใหม่



$digit_cusid=6;
$digit_carid=8;
?>
