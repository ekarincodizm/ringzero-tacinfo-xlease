<?php
session_start();
include("../config/config.php");
$datepicker=$_POST['datepicker'];
$idno=$_POST['idno'];
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server


$qry_check = pg_query("select * from \"nw_seize_car\" WHERE \"IDNO\"='$idno' AND \"seizeID\" = (select max(\"seizeID\") from \"nw_seize_car\" where \"IDNO\" = '$idno')");
$numrowscheck = pg_num_rows($qry_check);
$statuquery="true";
if($numrowscheck > 0){ //ตรวจสอบดูว่ามีข้อมูล ในตาราง nw_seize_car ใช้เก็บข้อมูลการยึดรถ อยู่จริง
	$result = pg_fetch_array($qry_check);
	$status_approve=$result["status_approve"]; //สถานะในการยึดรถ
	if($status_approve=='3'){
		$qry_fp=@pg_query("UPDATE \"Fp\" SET \"repo\"='TRUE', \"repo_date\"='$datepicker' WHERE \"IDNO\"='$idno' ");
		$qry_nw=@pg_query("UPDATE \"nw_seize_car\" SET \"status_approve\"='4'  WHERE \"IDNO\"='$idno'");		
	}
	else{//มีข้อมูลจริงแต่ไม่ได้อยู่ใน สถานะ  อยู่ระหว่างยึด
		 $statuquery="nostatus";	
	}	
}
else{ //ไม่มีข้อมูล ในตาราง nw_seize_car 
	$statuquery="nodata";
}


//$qry_fp=@pg_query("UPDATE \"Fp\" SET \"repo\"='TRUE', \"repo_date\"='$datepicker' WHERE \"IDNO\"='$idno' ");
//$qry_nw=@pg_query("UPDATE \"nw_seize_car\" SET \"status_approve\"='4'  WHERE \"IDNO\"='$idno'");
if($statuquery=="true")
{
	if($qry_fp and $qry_nw){
	//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) บันทึกการยึดรถ', '$add_date')");
	//ACTIONLOG---
		$data['success'] = true;
		$data['message'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
	}else{
		$data['success'] = false;
		$data['message'] = "ไม่สามารถบันทึกข้อมูลได้ !";
	}
}
else if($statuquery=="nostatus"){
	$data['success'] = false;
	$data['message'] = "ไม่สามารถบันทึกข้อมูลได้ เนื่องจาก เลขที่สัญญานี้ ไม่ได้อยู่ในสถานะ  \"อยู่ระหว่างยึด \" !";
}
else if($statuquery=="nodata"){
	$data['success'] = false;
	$data['message'] = "ไม่สามารถบันทึกข้อมูลได้ เนื่องจาก เลขที่สัญญานี้ ไม่ได้อยู่ในขั้นตอนการยึดรถ  !";
}

echo json_encode($data);
?>