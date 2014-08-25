<?php
session_start();
require_once("../../sys_setup.php");
include("../../../../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

pg_query("BEGIN");
$status=0;

$id = pg_escape_string($_POST[id]);
$check_statuspg=pg_escape_string($_POST[check_status]);
if($check_statuspg==""){
	$appvpg=pg_escape_string($_POST["appv"]);
	if($appvpg=="อนุมัติ"){  //กดปุ่มอนุมัติ
		$check_statuspg='1';
	}
	else 
	{	$unappvpg=pg_escape_string($_POST["unappv"]);
		if($unappvpg=="ไม่อนุมัติ"){ //กดปุ่มไม่อนุมัติ
			$check_statuspg='0';
		}
	}
}

// ตรวจสอบก่อนว่ามีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
$qey_chk_status = pg_query("select \"approve_status\" from \"ta_join_main\" where id = '$id' and \"approve_status\" in('3','4') ");
$row_chk_status = pg_num_rows($qey_chk_status);
if($row_chk_status > 0)
{
	$status++;
	
	$chk_approve_status = pg_fetch_result($qey_chk_status,0);
	if($chk_approve_status == "3")
	{
		$error = "ผิดพลาด มีการอนุมัติไปก่อนหน้านี้แล้ว";
	}
	elseif($chk_approve_status == "4")
	{
		$error = "ผิดพลาด ไม่อนุมัติไปก่อนหน้านี้แล้ว";
	}
}
else
{
	$query =	"UPDATE ta_join_main  SET
						staff_check  ='".$check_statuspg."',";
						if($check_statuspg==0){
						$query .=	"approve_status  = '4',";
						}else{
						$query .=	"approve_status  = '3',";	
							
						}
						
						$query .=	"approver  = '".$_SESSION["av_iduser"]."',
						approve_dt  = '$info_currentdatetimesql2'
						where id = '$id' and \"approve_status\" not in('3','4') ";
						
	if($sql_query=pg_query($query)){
			//$sql_query = pg_query($query);
			//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) ยืนยันข้อมูลเข้าร่วม', '$datelog')");
			//ACTIONLOG---
	}else{
		$status++;
	}
}

$script= '<script language=javascript>';		
if($status==0){
	pg_query("COMMIT");
	if($check_statuspg=='1'){
		$script.= " alert('อนุมัติเรียบร้อยแล้ว');";
	}else{
		$script.= " alert('ทำรายการเรียบร้อยแล้ว');";
	}
}	
else{
	pg_query("ROLLBACK");
	if($error != "")
	{
		$script.= " alert('$error !');";
	}
	else
	{
		$script.= " alert('ผิดพลาดไม่สามารถบันทึกข้อมูลได้ !');";
	}
}  
$script.= 'window.opener.location.reload(true);
			window.close();';
$script.= '</script>';
echo $script;   
?>