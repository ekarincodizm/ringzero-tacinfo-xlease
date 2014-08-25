<?php
$qryfile=pg_query("SELECT \"file\" from thcap_fa_prebill_file  where $val='$prebillIDMaster' and \"edittime\"='$edittime' order by \"file\" limit 1");
while($resfile=pg_fetch_array($qryfile)){
	$file22=$resfile["file"];	
	
	if($file22!=""){	
		$realpath = redirect($_SERVER['PHP_SELF'],'nw/upload/fa_prebill/'.$file22);
			
		//เริ่มส่วนที่แสดงไฟล์ pdf
		echo "<div id=\"$file22\" align=center class=\"message_box\"><iframe src=\"$realpath\" marginwidth=0 marginheight=0 hspace=0 vspace=0 frameborder=0 scorlling=yes width=750 height=600></iframe></div>";
		//จบส่วนที่แสดงไฟล์ pdf		
	}else{
		echo "<div align=center><b>พบปัญหาในการอัพโหลด</b></div>";
	}	
} 
?>