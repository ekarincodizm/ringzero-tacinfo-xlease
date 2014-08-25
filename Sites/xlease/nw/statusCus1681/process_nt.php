<?php
session_start();
include("../../config/config.php");
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$tac_maker_id = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
$tac_maker_stamp = Date('Y-m-d H:i:s');

$tac_cusid=$_POST["CusID"];
$tac_nt_date=$_POST["tac_nt_date"];
$tac_month_start=$_POST["tac_month_start"]; 
$tac_month_end=$_POST["tac_month_end"]; 
$tac_year_start=$_POST["tac_year_start"]; 
$tac_year_end=$_POST["tac_year_end"]; 
$tac_nt_amount=$_POST["tac_nt_amount"];
$tac_nt_amount=str_replace(",","",$tac_nt_amount);

$tac_nt_start=$tac_year_start."-".$tac_month_start."-01"; 
$tac_nt_end=$tac_year_end."-".$tac_month_end."-01"; 



pg_query("BEGIN WORK");
$status = 0;

$nowyear=substr($tac_maker_stamp,0,4);
//$nowyear=$nowyear+543;
$nowyear=2554; // แก้ไขเนื่องจากปัจจุบัน พนักงานยังออกของปี 2554 ไม่เสร็จเลยยังต้องออกต่อ

$qrylastid=pg_query("select tac_nt_running from tac_old_nt where tac_nt_running like '%$nowyear' order by tac_nt_running DESC limit 1");
$numrow=pg_num_rows($qrylastid);

$restlastid=pg_fetch_array($qrylastid);
$tacIdYear=$restlastid["tac_nt_running"];
$tacId=substr($tacIdYear,0,5);
$tacYear=substr($tacIdYear,6,4);
if($nowyear == $tacYear){
	//เอาค่า tacId ที่ได้มาตัด 0 ออก แล้ว +1 เพื่อเป็น record ต่อไป
	$idplus=$tacId +1;
}else{
	$idplus=1;
}

function insertZero($inputValue ,$digit){
	$str = "" . $inputValue;
	while (strlen($str) < $digit){
		$str = "0" . $str;
	}
	return $str;
}
$id_plus=insertZero($idplus , 5);
$id_plus=$id_plus."/".$nowyear;

$ins="insert into tac_old_nt (tac_nt_running,tac_cusid,tac_nt_date,tac_nt_start,tac_nt_end,tac_nt_amount,tac_maker_id,tac_maker_stamp) values ('$id_plus','$tac_cusid','$tac_nt_date','$tac_nt_start','$tac_nt_end','$tac_nt_amount','$tac_maker_id','$tac_maker_stamp')";	
if($res_up=pg_query($ins)){
}else{
	$status++;
}
		
if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$tac_maker_id', '(TAL) ออก NT 1681', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2>อัพเดทข้อมูลเรียบร้อยแล้ว</h2></center>";
	echo "<center><br><input type=\"button\" value=\"Print NT\" onclick=\"window.open('pdf_print_nt.php?tac_nt_running=$id_plus')\"><input type=\"button\" value=\"   กลับ   \" onclick=\"window.location='frm_NT.php'\"></center>";
}else{
	pg_query("ROLLBACK");
	echo "<center><h2>แก้ไขข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</h2></center>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_NT.php'>";
}


