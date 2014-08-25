<?php
session_start();
include('../../config/config.php');
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php $recid = $_POST['idapp'];
$datenow = Date('Y-m-d H:i:s');
$user_id = $_SESSION["av_iduser"];
$status = 0;
$appstatus = $_POST['appstate'];
$text = $_POST['reasonnotapp'];

pg_query("BEGIN");
if($appstatus == 'allow'){
	for($i = 0;$i<sizeof($recid);$i++){

		$sql = "UPDATE f_menu_manual SET appuser='$user_id', app_date='$datenow', appstatus='1' WHERE recmenuid = '$recid[$i]'";
		if($sqlinert = pg_query($sql)){}else{$status++; echo $sql."<p>";}
		
	}
}else if($appstatus == 'not'){	
	for($i = 0;$i<sizeof($recid);$i++){

		$sql = "UPDATE f_menu_manual SET appuser='$user_id', app_date='$datenow', appstatus='2',reason_notapp='$text' WHERE recmenuid = '$recid[$i]'";
		if($sqlinert = pg_query($sql)){}else{$status++; echo $sql."<p>";}
		
	}


}


if($status ==0){
	pg_query("COMMIT");
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_approve.php\">";
	echo "<script type='text/javascript'>alert('อนุมัติสำเร็จ')</script>";
}else{
	pg_query("ROLLBACK");

	echo "<script type='text/javascript'>alert('ล้มเหลว ! เกิดความผิดพลาด')</script>";
}
?>