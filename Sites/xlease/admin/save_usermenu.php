<?php
session_start();
include("../config/config.php");
$id_user=$_SESSION["av_iduser"];
$currentdate=nowDateTime();
 
 $v_idmenu=pg_escape_string($_GET["f_idmenu"]);
 $v_status=pg_escape_string($_GET["f_status"]);
 $v_uid=pg_escape_string($_GET["f_uid"]);


pg_query("BEGIN WORK");
$status=0;

$query=pg_query("select * from \"f_usermenu\" where \"id_menu\"='$v_idmenu' and \"id_user\"='$v_uid'");
$numrows=pg_num_rows($query);
if($numrows>0){
	$status="ข้อมูลซ้ำ กรุณาเลือกใหม่!!!";
}else{
	$in_sql="insert into f_usermenu(id_menu,id_user,status)
								values
								('$v_idmenu','$v_uid','$v_status')";
			  
	 if($result=pg_query($in_sql))
	 {
		$txtstatus ="insert ข้อมูล $v_idmenu ให้ $v_uid แล้ว";
	 }
	 else
	 {
		$status++;
		$txtstatus ="error insert  f_usermenu ".$in_sql;
	 }
	 
	//เำก็บว่ามีการเพิ่มเมนู
	$sql="insert into \"nw_changemenu\" (\"id_menu\",\"id_user\",\"status\",\"add_user\",\"add_date\",\"approve_user\",\"approve_date\",\"statusApprove\",\"statusOKapprove\") 
								values ('$v_idmenu','$v_uid','$v_status','$id_user','$currentdate','$id_user','$currentdate','2','FALSE')";
	if($db_query=pg_query($sql)){
	}else{
		$status++;
	}
	
	//update log ว่ามีการเพิ่มข้อมูล
	$uplog="INSERT INTO nw_changemenu_log(
	id_menu, id_user, \"statusRequest\", statusmenu, \"statusApp\", 
	app_user, app_stamp)
	VALUES ('$v_idmenu', '$v_uid', '1', '$v_status', 'TRUE', 
			'$id_user', '$currentdate')";
	if($ins=pg_query($uplog)){
	}else{
		$status++;
	}
	 
}
if($status==0){
	pg_query("COMMIT");
}else{
	pg_query("ROLLBACK");
}
echo $txtstatus;



?>