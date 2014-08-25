<?php
include("../../config/config.php");
session_start();
$idnow = $_SESSION["av_iduser"];
$datenow = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}



$recmenuidlog = $_POST['recmenuidlog'];
$path = $_POST['path'];
$k = $_POST['k'];
$code = $_POST['code'];
$idmenu = $_POST['id_menu'];
//log user
		$data="Read";
		$logsql = pg_query("INSERT INTO f_menu_manual_user_log(recmenuid, id_user, datetime)VALUES ('$recmenuidlog', '$idnow', '$datenow')");
		$sqllistnum = pg_query("select \"recmenuid\" FROM \"f_menu_manual\" where id_menu = '$idmenu' and \"appstatus\" = '1' and \"show_login\" = 'TRUE' order by  \"recmenuid\" asc");
		$rowlist = pg_num_rows($sqllistnum);
		$nn=0;
		while($resultlist = pg_fetch_array($sqllistnum ))
		{	//ตรวจสอบว่า แต่ละคำแนะนำเปิดอ่านหรือยัง
			$recmenuid = $resultlist['recmenuid'];
			$sqlselectuser = pg_query("SELECT  recmenuid FROM f_menu_manual_user_log where recmenuid = '$recmenuid' and id_user = '$idnow'");
			$rowuser = pg_num_rows($sqlselectuser);	
			if($rowuser>0){
				//อ่านคำแนะนำนั้นแล้ว
			}
			else
			{
				$data="noRead";break;//ยังไม่อ่านคำแนะนำนั้นแล้ว 
			}
		}
		if($data=="Read"){
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=../../$path\">";
		}
		else{
			echo "<script type=\"text/javascript\">self.close();</script>"; 
		}
		
			
?>