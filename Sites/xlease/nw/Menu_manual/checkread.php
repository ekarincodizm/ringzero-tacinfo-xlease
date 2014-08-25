<?php
include("../../config/config.php");
session_start();
$idnow = $_SESSION["av_iduser"];
$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server



$idmenu = $_POST['brand'];

$data= array();
//เลือกข้อมูลทั้งหมด ของเมนูนั้น ว่ามีคำแนะนำอะไรบ้าง
$sqllistnum = pg_query("select \"recmenuid\" FROM \"f_menu_manual\" where id_menu = '$idmenu' and \"appstatus\" = '1' and \"show_login\" = 'TRUE' order by  \"recmenuid\" asc");
$rowlist = pg_num_rows($sqllistnum);

while($resultlist = pg_fetch_array($sqllistnum ))
{	//ตรวจสอบว่า แต่ละคำแนะนำเปิดอ่านหรือยัง
	$recmenuid = $resultlist['recmenuid'];
	$sqlselectuser = pg_query("SELECT  recmenuid FROM f_menu_manual_user_log where recmenuid = '$recmenuid' and id_user = '$idnow'");
	$rowuser = pg_num_rows($sqlselectuser);	
	if($rowuser>0){
		$data[]='0' ;//อ่านคำแนะนำนั้นแล้ว
	}
	else
	{
		$data[]=$recmenuid; //ยังไม่อ่านคำแนะนำนั้นแล้ว 
	}
}
$i=0;
$result="";
while($i< count($data))
{	
	if($i==((count($data))-1))
	{	$result.=$data[$i];}
	else{ 
		$result.=$data[$i].",";
	}
	$i++;
}
echo ($result);
?>