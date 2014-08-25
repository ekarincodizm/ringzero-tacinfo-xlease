<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
$id_user = $_SESSION["av_iduser"];
$datenow = date("Y-m-d H:i:s");
$fmenuid = $_POST['hdmenuid'];
$typechk = $_POST['state'];

$datelimitstart = $_POST['datelimitstart']; //วันที่ปิดใช้
$hourstart = $_POST['hourstart']; //ชั่วโมงที่ปิด
$minutsstart = $_POST['minutsstart']; //นาทีที่ปิด
$datelimitend = $_POST['datelimitend'];	//วันที่เปิดใช้
$hourend = $_POST['hourend'];	//ชั่วโมงที่เปิดใช้
$minutsend = $_POST['minutsend'];	//นาทีที่เปิดใช้
$text = checknull($_POST['textdetail']);
$stime = $datelimitstart." ".$hourstart.":".$minutsstart.":00";
$etime = $datelimitend." ".$hourend.":".$minutsend.":00";
	
$status = 0;
pg_query("BEGIN");

if($typechk == "chgwait"){

	$sqlup = pg_query("UPDATE f_menu_warning SET s_time='$stime', e_time='$etime', id_user='$id_user',appstatus='0', detail_warning=$text WHERE \"fmenuwarID\"= '$fmenuid'");
	if($sqlup){}else{$status++; echo $sqlup;}

}else if($typechk == "chgapp"){

	$sqlup = pg_query("UPDATE f_menu_warning SET  e_time='$etime', id_user='$id_user',detail_warning=$text WHERE \"fmenuwarID\"= '$fmenuid'");
	if($sqlup){}else{$status++; echo $sqlup;}

}else{
	$status++;
}


if($status==0){
	pg_query("COMMIT");
	
	echo "<script type='text/javascript'>
		opener.location.reload(true);	
		self.close();
	
	</script>";
	echo "<script type='text/javascript'>alert('Successful')</script>";
}else{
	pg_query("ROLLBACK");
	
	//echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_manage.php\">";
	echo "<script type='text/javascript'>alert('Error')</script>";
}
?>