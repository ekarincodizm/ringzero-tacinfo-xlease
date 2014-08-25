<?php
session_start();
include("../../config/config.php");
set_time_limit(0);

$app_user = $_SESSION["av_iduser"];
$app_date = nowDateTime(); // วันเวลาปัจจุบันจาก Postgres

$submysearch = $_POST["submysearch"]; // ข้อมูลที่จะค้นหา
$subwhereData = $_POST["subwhereData"]; // รูปแบบในการค้นหา  1 = , 2 like
$subshowView = $_POST["subshowView"]; // การค้นหารวม VIEW หรือไม่  1 รวม 2 ไม่รวม
$subshowSerial = $_POST["subshowSerial"]; // การค้นหารวมฟิลด์ Serial หรือไม่  1 รวม 2 ไม่รวม
$textUpdate = $_POST["textUpdate"]; // ข้อความที่จะมาแทนที่
$updatelist = $_POST["updatelist"]; // ฟิลด์ที่จะ update ข้อมูล
?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<?php
pg_query("BEGIN WORK");
$status = 0;
$u = 0;

if($updatelist != "" && $updatelist[0] != "") // ถ้ามีการทำรายการ
{
	for($i=0;$i<sizeof($updatelist);$i++)
	{
		$fixUpdate = split("#",$updatelist[$i]); // [0]=SCHEMA  /[1]=ตาราง  / [2]=column
		
		if($subwhereData == "1"){$update="update $fixUpdate[0].\"$fixUpdate[1]\" set \"$fixUpdate[2]\" = '$textUpdate' where \"$fixUpdate[2]\" = '$submysearch'";}
		elseif($subwhereData == "2"){$update="update $fixUpdate[0].\"$fixUpdate[1]\" set \"$fixUpdate[2]\" = '$textUpdate' where \"$fixUpdate[2]\" like '%$submysearch%'";}
		if($result=pg_query($update)){
			$u++;
		}else{
			$status++;
		}
	}
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', 'ตรวจสอบข้อมูลในฐานข้อมูล', '$app_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2><font color=\"#0000FF\">แก้ไขข้อมูลเรียบร้อย จำนวน $u ฟิลด์</font></h2></center>";
	echo "<meta http-equiv='refresh' content='4; URL=frm_Index.php'>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">ผิดพลาด ไม่สามารถแก้ไขข้อมูลได้ กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	echo "<meta http-equiv='refresh' content='6; URL=frm_Index.php'>";
}
?>