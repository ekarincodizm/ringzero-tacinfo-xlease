<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$id_user = $_SESSION["av_iduser"];
$datenow = date("Y-m-d H:i:s");
$idmenu = $_POST['idmenu']; //เมนู


$typechk = $_POST['hdtype'];




$status = 0;
pg_query("BEGIN");

if($typechk == "insert"){
	if($idmenu == ""){$status++;}
	$datelimitstart = $_POST['datelimitstart']; //วันที่ปิดใช้
	$hourstart = $_POST['hourstart']; //ชั่วโมงที่ปิด
	$minutsstart = $_POST['minutsstart']; //นาทีที่ปิด
	$datelimitend = $_POST['datelimitend'];	//วันที่เปิดใช้
	$hourend = $_POST['hourend'];	//ชั่วโมงที่เปิดใช้
	$minutsend = $_POST['minutsend'];	//นาทีที่เปิดใช้
	$text = checknull($_POST['textdetail']);
	$stime = $datelimitstart." ".$hourstart.":".$minutsstart.":00";
	$etime = $datelimitend." ".$hourend.":".$minutsend.":00";


	for($i =0 ;$i < sizeof($idmenu);$i++){

		$sql = pg_query("INSERT INTO f_menu_warning(id_menu, s_time, e_time, id_user, datetime_submit,appstatus, detail_warning)
		VALUES ('$idmenu[$i]', '$stime','$etime', '$id_user', '$datenow', '0', $text)");
		if($sql){}else{$status++; echo $sql;}
	}
}else if($typechk == "approve"){
	$appchk = $_POST['appchk'];
	if($appchk == ""){$status++;}
	
	for($i =0 ;$i < sizeof($appchk);$i++){
		$sql = pg_query("UPDATE f_menu_warning SET  appstatus='1', id_appuser='$id_user', appdatetime='$datenow' WHERE \"fmenuwarID\" ='$appchk[$i]'");
		if($sql){}else{$status++; echo $sql;}
	}
}else if($typechk == "notapp"){
	$appchk = $_POST['appchk'];
	$reasonnotapp = $_POST['reasonnotapp'];
	if($appchk == ""){$status++;}
	
	for($i =0 ;$i < sizeof($appchk);$i++){
		$sql = pg_query("UPDATE f_menu_warning SET  appstatus='2', id_appuser='$id_user', appdatetime='$datenow', reason_notapp='$reasonnotapp' WHERE \"fmenuwarID\" ='$appchk[$i]'");
		if($sql){}else{$status++; echo $sql;}
	}
}

if($status==0){
	pg_query("COMMIT");
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_manage.php\">";
	echo "<script type='text/javascript'>alert('Successful')</script>";
}else{
	pg_query("ROLLBACK");
	
	//echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_manage.php\">";
	echo "<script type='text/javascript'>alert('Error')</script>";
}
?>