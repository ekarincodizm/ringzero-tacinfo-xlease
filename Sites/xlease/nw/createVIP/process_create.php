<?php
session_start();
include("../../config/config.php");
$IDNO = $_POST["IDNO"];
$id_user = $_SESSION["av_iduser"];
$createDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$method = $_POST["method"];

pg_query("BEGIN WORK");
$status = 0;

if($method == "add"){
	$ins="insert into \"nw_createVIP\" (\"IDNO\",\"id_user\",\"createDate\") values ('$IDNO','$id_user','$createDate')";
	if($result=pg_query($ins)){
	}else{
		$status=$status+1;
	}
}elseif($method == "delete"){
	$delete="delete from \"nw_createVIP\" where \"IDNO\" = '$IDNO'";
	if($result=pg_query($delete)){
	}else{
		$status=$status+1;
	}
}

if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 20px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเีรียบร้อยแล้ว</b></font></div>";
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}
?>
<br>
<div style="padding:5px;text-align:center;">
<input type="button" value="  กลับ  " onclick="javascript:location='frm_index.php'">
</div>