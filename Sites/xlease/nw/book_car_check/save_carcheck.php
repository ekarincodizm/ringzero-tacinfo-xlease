<?php
include("../../config/config.php");
include("../function/checknull.php");
session_start();
$id_user = $_SESSION["av_iduser"];
$date = nowDate();
$datelog=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$IDNO =pg_escape_string($_POST['IDNO']);
$ID_register =pg_escape_string($_POST['ID_register']);
$check =pg_escape_string($_POST['check']);
$radio_month =pg_escape_string($_POST['radio_month']);
$radio_price =pg_escape_string($_POST['radio_price']);
$meter_price =pg_escape_string($_POST['meter_price']);
$meter_vat_price =pg_escape_string($_POST['meter_vat_price']);
$remark =checknull(pg_escape_string($_POST['remark'])); //หมายเหตุ

$check_start = pg_escape_string($_POST['participating_start']);
if(!empty($check_start)){
$participating_start =pg_escape_string($_POST['participating_start'])."  ".pg_escape_string($_POST['participating_year_start']);
}
$check_end = pg_escape_string($_POST['participating_end']);
if(!empty($check_end)){
$participating_end =pg_escape_string($_POST['participating_end'])."  ".pg_escape_string($_POST['participating_year_end']);
}


$participating_price =pg_escape_string($_POST['participating_price']);
$insurance_price =pg_escape_string($_POST['insurance_price']);
$insureance_act_price =pg_escape_string($_POST['insureance_act_price']);
$act_price =pg_escape_string($_POST['act_price']);
$other =pg_escape_string($_POST['other']);
$other_price =pg_escape_string($_POST['other_price']);
$sumprice =pg_escape_string($_POST['sumprice']);
$cusname =pg_escape_string($_POST['cusname']);
$gas_price =pg_escape_string($_POST['gas_price']);


$checkadd = pg_escape_string($_POST['add']);
if($checkadd == 1){
$address =pg_escape_string($_POST['address1']);
}else if($checkadd == 2){
$address =pg_escape_string($_POST['address2']);
}else if($checkadd == 3){
$address =pg_escape_string($_POST['address3']);
}else if($checkadd == 4){
$address =pg_escape_string($_POST['address4']);
}else if($checkadd == 5){
$address =pg_escape_string($_POST['address5']);
}else if($checkadd == 6){
$address =pg_escape_string($_POST['address6']);
}

if($id_user==""){
	$id_user = "null";
}else{
	$id_user = "'".$id_user."'";
}
if($IDNO==""){
	$IDNO = "null";
}else{
	$IDNO = "'".$IDNO."'";
}
if($date==""){
	$date = "null";
}else{
	$date = "'".$date."'";
}
if($ID_register==""){
	$ID_register = "null";
}else{
	$ID_register = "'".$ID_register."'";
}
if($radio_month==""){
	$radio_month = "null";
}
if($radio_price==""){
	$radio_price = 0;
}
if($meter_price==""){
	$meter_price = 0;
}
if($meter_vat_price==""){
	$meter_vat_price = 0;
}

if($participating_start==""){
	$participating_start = "null";
}else{
	$participating_start = "'".$participating_start."'";
}
if($participating_end==""){
	$participating_end = "null";
}else{
	$participating_end = "'".$participating_end."'";
}
if($participating_price==""){
	$participating_price = 0;
}
if($insurance_price==""){
	$insurance_price = 0;
}
if($insureance_act_price==""){
	$insureance_act_price = 0;
}
if($act_price==""){
	$act_price = 0;
}
if($other==""){
	$other = "null";
}else{
	$other = "'".$other."'";
}
if($other_price==""){
	$other_price = 0;
}
if($sumprice==""){
	$sumprice = 0;
}
if($gas_price==""){
	$gas_price = 0;
}
if($address==""){
	$address = "null";
}else{
	$address = "'".$address."'";
}
if($cusname==""){
	$cusname = "null";
}else{
	$cusname = "'".$cusname."'";
}





$status = 0;

pg_query('BEGIN');
	
	$sql = "INSERT INTO book_car_check(
			cusname, \"IDNO\", \"C_REGIS\", typecheck, radio_month, 
            radio_price, meter_price, meter_vat_price, participating_start, 
            participating_end, insurance_price, act_price, insureance_act_price, 
            other, other_price, sumprice, id_user, date, participating_price, 
            gas_price, address,remark)
    VALUES ($cusname, $IDNO, $ID_register, $check, $radio_month, 
            $radio_price, $meter_price, $meter_vat_price, $participating_start, 
            $participating_end, $insurance_price, $act_price, $insureance_act_price, 
            $other, $other_price, $sumprice, $id_user, $date, $participating_price, 
            $gas_price, $address,$remark)
			";

	$sql1 = pg_query($sql);
			
	if($sql1){
	}else{
	$status++;
	}
	
		if(status == 0){
				pg_query('COMMIT');
				//ACTIONLOG
					
					$sqlaction = "INSERT INTO action_log(id_user, action_desc, action_time) VALUES ($id_user, '(TAL) สร้างรายการนัดตรวจรถ', '$datelog') ";
					$sqlaction1 = pg_query($sqlaction);
				//ACTIONLOG---
				
				
				
				$sql1 = "SELECT MAX(\"bookcarID\") max FROM book_car_check";

				$sqlquery = pg_query($sql1);
				$result = pg_fetch_array($sqlquery);
				$bookcarid = $result['max'];
				
				echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
				echo "<script type='text/javascript'>alert(' บันทึกการนัดตรวจเสร็จสิ้น ')</script>";
				echo "<script type='text/javascript'>
				
				if(confirm('คุณต้องการ ปริ้นใบนัดตรวจรถ ใช่หรือไม่ ?')==true)
					{					
					window.location = 'PDF.php?bookcarid=$bookcarid';
					}
				else
					{
					window.location = 'index.php';
					}
	
				</script>";
				
				exit();
		}else{
				pg_query('ROLLBACK');
				echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
				echo "<script type='text/javascript'>alert(' ไม่สามารถบันทึกการนัดตรวจได้ ')</script>";
				echo $sql;
				exit();
		}	
?>