<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$annId=$_POST["annId"]; 
$nowdate = nowDateTime();

pg_query("BEGIN WORK");
$status = 0;

//ค้นหาประกาศว่ามีหรือไม่
$query_popup=pg_query("select *,d.\"fullname\" as author,e.\"fullname\" as approve from \"nw_annoucement\" a 
	left join \"nw_annouceuser\" b on a.\"annId\"=b.\"annId\"
	left join \"nw_annoucetype\" c on a.\"typeAnnId\"=c.\"typeAnnId\"
	left join \"Vfuser\" d on a.\"annAuthor\"=d.\"id_user\"
	left join \"Vfuser\" e on a.\"annApprove\"=e.\"id_user\"
	where \"statusApprove\"='TRUE' and b.\"id_user\"='$id_user' and \"statusAccept\"='1' order by \"approveDate\"");
$numrows_popup=pg_num_rows($query_popup);
if($numrows_popup>0){
	//ตรวจสอบว่าข้อมูลได้ถูก approve ไปหรือยัง
	$qry_check=pg_query("select * from nw_annouceuser where \"annId\"='$annId' and \"statusAccept\"='3' and \"id_user\"='$id_user'");
	$numcheck=pg_num_rows($qry_check);
	if($numcheck>0){  //รับทราบไปแล้วก่อนหน้านี้
		$status=-1;
	}else{
		$update="update \"nw_annouceuser\" set \"statusAccept\"='3', \"accepted_stamp\"='$nowdate' where \"id_user\"='$id_user' and \"annId\"='$annId'";
		if($resins=pg_query($update)){
		}else{
			$status++;
		}
	}
}else{
	$status=-2;
}
if($status=="0"){
	pg_query("COMMIT");
	echo "1"; //กรณีบันทึกผ่าน
}else if($status=="-1"){
	pg_query("ROLLBACK");
	echo "2"; //กรณีมีการบันทึกไปก่อนหนี้า
}else if($status=="-2"){
	pg_query("ROLLBACK");
	echo "3";	//กรณีไม่พบประกาศ
}else{
	pg_query("ROLLBACK");
	echo "4";
}

?>