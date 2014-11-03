<?php
session_start();
include("../../config/config.php");

$user_id = $_SESSION["av_iduser"];
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$contractID = pg_escape_string($_POST["contractID"]); // เลขที่สัญญา
$doerID = pg_escape_string($_POST["doerID"]); // รหัสพนักงานที่ทำรายการ
$doerRemark = pg_escape_string($_POST["doerRemark"]); // หมายเหตุการขอปิดสัญญา
$contractcloseddate = pg_escape_string($_POST["contractcloseddate"]); // วันเวลาที่ปิดสัญญา
$appvRemark = pg_escape_string($_POST["appvRemark"]); // หมายเหตุการอนุมัติ
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<script type="text/javascript">
	function RefreshMe(){
		opener.location.reload(true);
		self.close();
	}
</script>

<?php
pg_query("BEGIN");
$status = 0;

$query_chk = pg_query("select * from \"thcap_contract_absclose_request\" where \"contractID\" = '$contractID' and \"conabsclose_status\" in('9', '1')");
$row_chk = pg_num_rows($query_chk);
if($row_chk > 0)
{
	$status++;
	$error = "มีการทำรายการไปก่อนหน้านี้แล้ว";	
}
else
{
	$sql_add = "INSERT INTO \"thcap_contract_absclose_request\"(
					\"contractID\", -- เลขที่สัญญา
					\"conabsclose_date\", -- วันที่ปิดสัญญา
					\"conabsclose_doerID\", -- รหัสพนักงานที่ทำรายการ
					\"conabsclose_doerStamp\", -- วันเวลาที่ทำรายการ
					\"conabsclose_doerRemark\", -- หมายเหตุการขอปิดสัญญา
					\"conabsclose_appvID\", -- รหัสผู้อนุมัติ
					\"conabsclose_appvStamp\", -- วันเวลาที่อนุมัติ
					\"conabsclose_appvRemark\", -- หมายเหตุการอนุมัติ
					\"conabsclose_status\" -- สถานะการอนุมัติ
				)
				VALUES(
					'$contractID',
					'$contractcloseddate',
					'$doerID',
					'$add_date',
					'$doerRemark',
					'$user_id',
					'$add_date',
					'$appvRemark',
					'1' -- ปัจจุบันให้ อนุมัติ เสมอ
				)";
	if($result_add = pg_query($sql_add))
	{}
	else
	{
		$status++;
	}
}

if($status == 0)
{
	pg_query("COMMIT");
	
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(THCAP) อนุมัติปิดสัญญา', '$add_date')");
	//ACTIONLOG---
	
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ตกลง\" style=\"cursor:pointer;\" onclick=\"javascript:RefreshMe();\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกผิดพลาด $error</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ปิด\" style=\"cursor:pointer;\" onclick=\"javascript:RefreshMe();\"></center>";
}
?>