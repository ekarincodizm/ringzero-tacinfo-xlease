<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
include("../function/emplevel.php");

$appvID = $_SESSION["av_iduser"];
$nowDateTime = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$nowDate = nowDate();
?>

<title>อนุมัติ ประกันภัย ภาคสมัครใจ</title>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<?php
$requestUnforceID = pg_escape_string($_POST["requestID"]); // รหัสรายการ
$appvNote = pg_escape_string($_POST["appvNote"]); // หมายเหตุการอนุมัติ

$appvNote = checknull($appvNote);

$emplevel = emplevel($appvID); // ระดับสิทธิพนักงานที่ทำรายการอนุมัติ

// หารหัสพนักงานที่ขอทำรายการ และสถานะปัจจุบัน
$qry_doer = pg_query("select \"doerID\", \"appvStatus\", \"editTime\", \"UnforceID\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID'");
$doerID = pg_fetch_result($qry_doer,0); // รหัสพนักงานที่ทำรายการ
$appvStatus_old = pg_fetch_result($qry_doer,1); // สถานะปัจจุบัน
$editTime = pg_fetch_result($qry_doer,2); // ขอแก้ไขครั้งที่
$UnforceID = pg_fetch_result($qry_doer,3); // รายการที่ขอแก้ไข

// หาสาขาของผู้ทำรายการ
$qry_office_id = pg_query("select \"office_id\" from \"fuser\" where \"id_user\" = '$doerID' ");
$office_id = pg_fetch_result($qry_office_id,0);
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

if($appvStatus_old == "0")
{
	$status++;
	$error = "<br/>มีการปฏิเสธรายการไปก่อนหน้านี้แล้ว";
}
elseif($appvStatus_old == "1")
{
	$status++;
	$error = "<br/>มีการอนุมัติรายการไปก่อนหน้านี้แล้ว";
}
elseif($appvStatus_old == "9")
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
						insure.\"thcap_InsureUnforce_request\"
					SET
						\"appvStatus\" = '$appvStatus',
						\"appvID\" = '$appvID',
						\"appvStamp\" = '$nowDateTime',
						\"appvNote\" = $appvNote
					WHERE
						\"requestUnforceID\" = '$requestUnforceID' AND
						\"appvStatus\" = '9'
				";
		if($result_up = pg_query($sql_up)){
		}
		else{
			$status++;
		}
		
		// ถ้าอนุมัติ
		if($appvStatus == "1")
		{
			if($editTime == "0") // ถ้าเป็นการขอเพิ่มข้อมูล
			{
				$sql_in = "
							INSERT INTO insure.\"thcap_InsureUnforce\"(
								\"UnforceID\",
								\"contractID\",
								\"assetDetailID\",
								\"TempInsID\",
								\"Company\",
								\"StartDate\",
								\"Code\",
								\"Kind\",
								\"Invest\",
								\"Premium\",
								\"ConfirmDate\",
								\"Discount\",
								\"CollectCus\",
								\"InsUser\",
								\"Cancel\",
								\"EndDate\"
							)
							SELECT
								insure.thcap_gen_co_insid('$nowDate', '$office_id', '2'),
								\"contractID\",
								\"assetDetailID\",
								\"TempInsID\",
								\"Company\",
								\"StartDate\",
								\"Code\",
								\"Kind\",
								\"Invest\",
								\"Premium\",
								\"ConfirmDate\",
								\"Discount\",
								\"CollectCus\",
								\"InsUser\",
								\"Cancel\",
								\"EndDate\"
							FROM
								insure.\"thcap_InsureUnforce_request\"
							WHERE
								\"requestUnforceID\" = '$requestUnforceID' AND
								\"appvStatus\" = '1'
							RETURNING
								\"UnforceID\"
						";
				if($result_in = pg_query($sql_in)){
					// ให้นำ ID ที่ได้ จากตารางจริง ไปไว้ในตาราง temp ด้วย
					$UnforceID = pg_fetch_result($result_in,0);
					$sql_up = "
								UPDATE
									insure.\"thcap_InsureUnforce_request\"
								SET
									\"UnforceID\" = '$UnforceID'
								WHERE
									\"requestUnforceID\" = '$requestUnforceID' AND
									\"appvStatus\" = '1'
							";
					if($result_up = pg_query($sql_up)){
					}
					else{
						$status++;
					}
				}
				else{
					$status++;
				}
			}
			elseif($editTime > 0) // ถ้าเป็นการแก้ไขข้อมูล
			{
				if($UnforceID != "")
				{
					$sql_up = "
								UPDATE
									insure.\"thcap_InsureUnforce\"
								SET
									\"contractID\" = (select \"contractID\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"assetDetailID\" = (select \"assetDetailID\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"TempInsID\" = (select \"TempInsID\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"Company\" = (select \"Company\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"StartDate\" = (select \"StartDate\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"Code\" = (select \"Code\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"Kind\" = (select \"Kind\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"Invest\" = (select \"Invest\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"Premium\" = (select \"Premium\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"ConfirmDate\" = (select \"ConfirmDate\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"Discount\" = (select \"Discount\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"CollectCus\" = (select \"CollectCus\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"InsUser\" = (select \"InsUser\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"Cancel\" = (select \"Cancel\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"EndDate\" = (select \"EndDate\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"InsID\" = (select \"InsID\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"InsDate\" = (select \"InsDate\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1'),
									\"NetPremium\" = (select \"NetPremium\" from insure.\"thcap_InsureUnforce_request\" where \"requestUnforceID\" = '$requestUnforceID' and \"appvStatus\" = '1')
								WHERE
									\"UnforceID\" = '$UnforceID'
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
					$error = "<br/>ไม่พบรายการที่ขอแก้ไข";
				}
			}
			else
			{
				$status++;
				$error = "<br/>ไม่สามารถระบุได้ว่าเป็นการขอเพิ่มหรือแก้ไขข้อมูล";
			}
		}
	}
	else
	{
		$status++;
		$error = "<br/>คุณไม่มีสิทธิ อนุมัติ/ไม่อนุมัติ รายการที่ตนเองเป็นคนทำได้";
	}
}
else
{
	$status++;
	$error = "<br/>ไม่พบสถานะปัจจุบันของรายการ";
}

if($status == 0)
{
	pg_query("COMMIT");
	
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$appvID', '(THCAP) อนุมัติ ประกันภัย ภาคสมัครใจ', '$nowDateTime')");
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