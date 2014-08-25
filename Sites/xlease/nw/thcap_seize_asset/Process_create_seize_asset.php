<?php
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$contractID = pg_escape_string($_POST["ConID"]);
$txtRemark = pg_escape_string($_POST["txtRemark"]);

pg_query("BEGIN WORK");
$status = 0;

// ตรวจสอบก่อนว่า มีการขอ Create งานยึด
$qry_chk_create = pg_query("select \"createStatus\" from \"thcap_create_seize_asset\" where \"contractID\" = '$contractID' and \"createStatus\" in('9','1') ");
$chk_create = pg_fetch_result($qry_chk_create,0);
if($chk_create == "9")
{
	$status++;
	$error = "เลขที่สัญญา $contractID อยู่ระหว่างรออนุมัติ Create งานยึด";
}
elseif($chk_create == "1")
{
	$status++;
	$error = "เลขที่สัญญา $contractID Create งานยึด แล้ว";
}

// Create งานยึด
$sql_in = "insert into \"thcap_create_seize_asset\"(\"contractID\", \"doerID\", \"doerStamp\", \"doerNote\", \"createStatus\")
			values('$contractID', '$id_user', '$logs_any_time', '$txtRemark', '9') ";
if(!$qry_in = pg_query($sql_in))
{
	$status++;
}

if($status == 0)
{
	pg_query("COMMIT");	
	echo "<br><center><font color=\"#0000FF\"><h3>บันทึกสมบูรณ์</h3></font></center>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
}
else
{
	pg_query("ROLLBACK");
	echo "<br><center><font color=\"#FF0000\"><h2>บันทึกผิดพลาด!! </h2></font>$error</center>";
	echo "<meta http-equiv='refresh' content='5; URL=frm_Index.php'>";
}
?>