<?php
session_start();
include("../../../config/config.php");
include("../../function/checknull.php");
$id_user = $_SESSION["av_iduser"];
$nowdate=nowDateTime();

$method = $_POST['method'];
$recnum = $_POST['recnum'];
$result = checknull($_POST['result']);

pg_query("BEGIN WORK");
$status=0;

if($method=="save"){
	$ins="INSERT INTO finance.thcap_receive_cheque_print_log(
         \"revChqNum\", print_user, print_stamp, result)
    VALUES ('$recnum', '$id_user', '$nowdate', $result)";
	if($resin=pg_query($ins)){
	}else{
		$status++;
	}
	
}
if($status==0){
	pg_query("COMMIT");
	echo "1";
}else{
	pg_query("ROLLBACK");
	echo "2";
}
?>
