<?php
session_start();
require_once("../../sys_setup.php");
include("../../../../../config/config.php");
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];
$status =0;
if(isset($_POST["appv"])){
		$_POST[check_status]=1;//อนุมัติ
	}else{
		$_POST[check_status]=2;//ไม่อนุมัติ
	}
$query =	"UPDATE \"FOtherpayDiscount\"  SET
					approve_status  ='".$_POST[check_status]."',
					approver  = '".$_SESSION["av_iduser"]."',
					approve_dt  = '$info_currentdatetimesql2'
					where \"O_RECEIPT\" ='".$_POST[RECEIPT]."'";		
				
if($res_inss=pg_query($query)){	

	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) อนุมัติส่วนลดเข้าร่วม', '$datelog')");
	//ACTIONLOG---

		}else{
			$status=$status+1;
			//echo $query1;
		}

$script= '<script language=javascript>';
$script.= " alert('ทำรายการเรียบร้อยแล้ว');location.href='join_discount_approve.php';";
$script.= '</script>';
echo $script;

	     
?>