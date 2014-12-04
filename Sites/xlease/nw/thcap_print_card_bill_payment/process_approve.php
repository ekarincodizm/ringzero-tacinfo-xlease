<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
include("../function/emplevel.php");

$appvID = $_SESSION["av_iduser"];
$nowDateTime = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php
$autoID = pg_escape_string($_POST["autoID"]); // รหัสรายการ
$appvNote = pg_escape_string($_POST["appvNote"]); // หมายเหตุการอนุมัติ

$appvNote = checknull($appvNote);

$emplevel = emplevel($appvID); // ระดับสิทธิพนักงานที่ทำรายการอนุมัติ

// หารหัสพนักงานที่ขอทำรายการ และสถานะปัจจุบัน
$qry_doerID = pg_query("select \"doerID\", \"appvStatus\" from \"thcap_print_card_bill_payment\" where \"autoID\" = '$autoID'");
$doerID = pg_fetch_result($qry_doerID,0);
$appvStatus_old = pg_fetch_result($qry_doerID,1);
?>

<script type="text/javascript">
	function RefreshMe(){
		opener.location.reload(true);
		self.close();
	}

	function popU(U,N,T) {
		newWindow = window.open(U, N, T);
	}
</script>

<?php
pg_query("BEGIN");
$status = 0;

if($appvStatus_old == "0" || $appvStatus_old == "1")
{
	$status++;
	$error = "<br/>มีการทำรายการไปก่อนหน้านี้แล้ว";
}
else
{
	if($appvID != $doerID || $emplevel <= 1)
	{
		if(isset($_POST["appv"])){
			$appvStatus = "1"; //อนุมัติ
		}elseif(isset($_POST["unAppv"])){
			$appvStatus = "0"; //ไม่อนุมัติ
		}
		
		$sql_up = "
						UPDATE
							\"thcap_print_card_bill_payment\"
						SET
							\"appvStatus\" = '$appvStatus',
							\"appvID\" = '$appvID',
							\"appvStamp\" = '$nowDateTime',
							\"appvNote\" = $appvNote
						WHERE
							\"autoID\" = '$autoID' AND
							\"appvStatus\" = '9'
					";
		if($result_up = pg_query($sql_up)){
		}
		else{
			$status++;
		}
	}
	else
	{
		$status++;
		$error = "<br/>คุณไม่มีสิทธิ อนุมัติ/ไม่อนุมัติ รายการที่ตนเองเป็นคนทำได้";
	}
}

if($status == 0)
{
	pg_query("COMMIT");
	
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$appvID', '(THCAP) อนุมัติพิมพ์ Card Bill Payment', '$nowDateTime')");
	//ACTIONLOG---
	
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ตกลง\" onClick=\"RefreshMe();\" style=\"cursor:pointer;\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">ผิดพลาด!! $error</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ปิด\" onClick=\"RefreshMe();\" style=\"cursor:pointer;\"></center>";
}
?>