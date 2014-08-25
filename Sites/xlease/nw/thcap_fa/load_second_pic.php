<?php
include("../../config/config.php");
$last_msg_id=$_GET['last_msg_id'];

//ค้นหาไฟล์ scan จากตาราง thcap_fa_prebill_file 
if($statusApp=='2'){ //ถ้าสถานะอนุมัิติ จะยังไม่มีเลข prebillID
	$val="\"auto_temp\"";
}else{
	$val="\"prebillID\"";
}

$qryfile2=pg_query("SELECT \"file\" from thcap_fa_prebill_file  where $val='$prebillIDMaster' and \"edittime\"='$edittime' and \"file\" >'$last_msg_id' order by \"file\" limit 1");

$last_msg_id="";
while($resfile2=pg_fetch_array($qryfile2)){
	$file2=$resfile2["file"];	

	
	if($file2!=""){
		
		$realpath = redirect($_SERVER['PHP_SELF'],'nw/upload/fa_prebill/'.$file2);
		
		//เริ่มส่วนที่แสดงไฟล์ pdf
		echo "<div id=\"$file2\" align=center class=\"message_box\"><iframe src=\"$realpath\" marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scorlling=yes width=750 height=600></iframe></div>";
		//จบส่วนที่แสดงไฟล์ pdf				

	}else{
		echo "<div align=center><b>พบปัญหาในการอัพโหลด</b></div>";
	}	
	
} 
?>
