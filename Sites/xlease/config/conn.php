<?php

//$connection = pg_connect("host=172.16.2.5 port=5432 dbname=AvLeasing user=av password=av") or die ("Not Connect PostGres");

session_start();
include("./config.php");
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

setSessionTime(10*60);

@ini_set('display_errors', '1');

?>
