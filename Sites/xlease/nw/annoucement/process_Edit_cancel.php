<?php
set_time_limit(0);
include("../../config/config.php");
$annId=$_GET["annId"]; 
 
 pg_query("BEGIN WORK");
$status = 0;

	$del1="update \"nw_annoucement\" set \"statusCancel\"='t' where \"annId\"='$annId'";	
	if($redel1=pg_query($del1)){}else{$status++;}
	
	$del2="update \"nw_annouceuser\" set \"statusAccept\"='0' where \"annId\"='$annId'";	
	if($redel2=pg_query($del2)){}else{$status++;}
	
	$del3="update \"nw_annouceuser_newbie\" set \"statusAccept\"='3' where \"annId\"='$annId'";	
	if($redel3=pg_query($del3)){}else{$status++;}

if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font></div>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_annEdit_newbie.php'>";
}else{
	pg_query("ROLLBACK");
	echo $up_error."<br>";
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	echo "<meta http-equiv='refresh' content='4; URL=frm_annEdit_newbie.php'>";
}	
?>