<?php
session_start();
include("../config/config.php");
$fs_cusid=pg_escape_string($_POST["cusid"]);
$fs_name=pg_escape_string($_POST["f_name"]);
$fs_ads=pg_escape_string($_POST["f_ads"]);
$in_lt="Update letter.send_address SET \"name\"='$fs_name',dtl_ads='$fs_ads' WHERE \"CusLetID\"='$fs_cusid' ";
if($result=pg_query($in_lt))
 {
  $statuss ="OK update at Fn".$in_lt;
  $st="บันทึกข้อมูลเรียบร้อย";
 }
 else
 {
  $statuss ="error update  Fn Re".$in_lt;
   $st="เกิดข้อผิดพลาด";
 }	
echo $st; 
echo "<meta http-equiv=\"refresh\" content=\"1;URL=frm_edit_let.php?cusid=$fs_cusid\">";
 
 
?>