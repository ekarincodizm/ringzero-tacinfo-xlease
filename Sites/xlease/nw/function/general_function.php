<?php
//เข้ารหัส " ให้เป็น unicode ก่อนบันทึกลงฐานข้อมูล
function sqlEscape($sql) { 
    $fix_str    = stripslashes($sql); 
	$fix_str    = str_replace('"',"&quot;",$sql); 
    return $fix_str; 
}

?>