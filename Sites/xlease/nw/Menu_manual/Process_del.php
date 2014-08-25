<?php
session_start();
include('../../config/config.php');
$recid = $_GET['recid'];
$datenow = Date('Y-m-d H:i:s');
$user_id = $_SESSION["av_iduser"];
$status = 0;
$stype=$_GET['type'];

pg_query("BEGIN");
if($stype == 'del'){
		$sql = "UPDATE f_menu_manual SET appstatus='3' WHERE recmenuid = '$recid'";
		if($sqlinert = pg_query($sql)){}else{$status++; echo $sql."<p>";}
}else if($stype == 'reuse'){
		$sql = "UPDATE f_menu_manual SET appstatus='0', appuser= null, app_date=null,reason_notapp = null WHERE recmenuid = '$recid'";
		if($sqlinert = pg_query($sql)){}else{$status++; echo $sql."<p>";}
}else{
$status++;
}

if($status ==0){
	pg_query("COMMIT");
	if($stype == 'del'){
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=Manage_rec.php\">";
	}else if($stype == 'reuse'){
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_del.php\">";
	}
}else{
	pg_query("ROLLBACK");

	echo "<script type='text/javascript'>alert('ล้มเหลว ! เกิดความผิดพลาด')</script>";
}
?>