<?php
session_start();
$id_user = $_SESSION["av_iduser"];
include('../../config/config.php');
include('../function/checknull.php');
?>

<html xmlns="http://www.w3.org/1999/xhtml">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

$gender = checknull($_POST['gender']);
$regis_car = checknull($_POST['regis_car']);
$age = checknull($_POST['age']);
$phone = checknull($_POST['phone']);
$mail = checknull($_POST['mail']);
$type = checknull($_POST['type']);
$day = $_POST['day'];
$hour = $_POST['hour'];
$minute = $_POST['minute'];
$datetimenow = Date('Y-m-d H:i:s');

$dateservice = $day." ".$hour.":".$minute.":"."00";

$more5year = $_POST['more5year'];
if($more5year == 'YES'){
$more5year = 'TRUE';
}else{
$more5year = 'FALSE';
}

$empname = checknull($_POST['empname']);
$empnickname = checknull($_POST['empnickname']);
$identify_emp = checknull($_POST['identify_emp']);
$service_number = checknull($_POST['service_number']);
$poll1 = checknull($_POST['poll1']);
$poll2 = checknull($_POST['poll2']);
$poll3 = checknull($_POST['poll3']);
$poll4 = checknull($_POST['poll4']);
$poll5 = checknull($_POST['poll5']);
$poll6 = checknull($_POST['poll6']);
$recommend = checknull($_POST['recommend']);

$status = 0;


pg_query('BEGIN');


$sql = "INSERT INTO \"Poll_service\"(
            gender, regis_car, age, phone, email, type_customer, 
            date_service, cusmore5year, emp_name, emp_nickname, identify_emp, 
            service_number, poll1, poll2, poll3, poll4, poll5, poll6, recommend,id_user,datetime_record)
    VALUES ( $gender, $regis_car, $age, $phone, $mail, $type, 
            '$dateservice', $more5year, $empname, $empnickname, $identify_emp, 
            $service_number, $poll1, $poll2, $poll3, $poll4, $poll5, $poll6, $recommend,'$id_user','$datetimenow')";

$sql_query = pg_query($sql);
			
if($sql_query){}else{
	$status++;
}	

if($status == 0){

	pg_query('COMMIT');
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
	echo "<script type='text/javascript'>alert(' บันทึกแบบประเมินเรียบร้อยแล้ว ')</script>";
	

}else{
	
	pg_query('ROLLBACK');
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
	echo "<script type='text/javascript'>alert('ขออภัย ! ไม่สามารถลงแบบประเมินได้ กรุณาลองใหม่ ')</script>";
	echo $sql;
}		
			
?>