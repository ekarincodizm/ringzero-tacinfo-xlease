<?php
session_start();
set_time_limit(0);
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
header("Location:thcap_nt/frm_Index_nt1.php");
