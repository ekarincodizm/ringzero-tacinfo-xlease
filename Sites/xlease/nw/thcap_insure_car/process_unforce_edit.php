<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$get_id_user = $_SESSION["av_iduser"];

$UnforceID = pg_escape_string($_POST['UnforceID']);
$contractID = pg_escape_string($_POST['contractID']);
$assetDetailID = pg_escape_string($_POST['assetDetailID']);
$tempinsid = pg_escape_string($_POST['tempinsid']);
$company = pg_escape_string($_POST['company']);
$date_start = pg_escape_string($_POST['date_start']);
$date_end = pg_escape_string($_POST['date_end']);
$code = pg_escape_string($_POST['code']);
$kind = pg_escape_string($_POST['kind']);
$invest = pg_escape_string($_POST['invest']);
$premium = pg_escape_string($_POST['premium']);
$nowdate = nowDate();
$discount = pg_escape_string($_POST['discount']);
$insuser = pg_escape_string($_POST['insuser']);
$collectcus = pg_escape_string($_POST['collectcus']);
$InsID = pg_escape_string($_POST['InsID']); // เลขที่ของกรมธรรม์
$InsDate = pg_escape_string($_POST['InsDate']); // วันที่รับกรมธรรม์
$NetPremium = pg_escape_string($_POST['NetPremium']); // เบี้ยสุทธิ

$InsID_checknull = checknull($InsID);
$InsDate_checknull = checknull($InsDate);
$NetPremium_checknull = checknull($NetPremium);

pg_query("BEGIN WORK");
$status = 0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>แก้ไข ข้อมูล ประกันภัย ภาคสมัครใจ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
	<center>
	<div class="header"><h2>แก้ไข ข้อมูล ประกันภัย ภาคสมัครใจ</h2></div>
	<div class="wrapper">
		<?php
		// ตรวจสอบก่อนว่า มีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
		$qry_check = pg_query("
								SELECT
									\"appvStatus\"
								FROM
									\"insure\".\"thcap_InsureUnforce_request\"
								WHERE
									\"UnforceID\" = '$UnforceID' AND
									\"editTime\" > '0' AND
									\"appvStatus\" = '9'
							");
		$row_check = pg_num_rows($qry_check);
		if($row_check > 0)
		{
			$status++;
			$error = "มีการทำรายการไปก่อนหน้านี้แล้ว";
		}
		else
		{
			$in_sql = "
						INSERT INTO \"insure\".\"thcap_InsureUnforce_request\"(
							\"contractID\",
							\"assetDetailID\",
							\"TempInsID\",
							\"Company\",
							\"StartDate\",
							\"EndDate\",
							\"Code\",
							\"Kind\",
							\"Invest\",
							\"Premium\",
							\"ConfirmDate\",
							\"Discount\",
							\"CollectCus\",
							\"InsUser\",
							\"doerID\",
							\"doerStamp\",
							\"appvStatus\",
							\"editTime\",
							\"UnforceID\",
							\"InsID\",
							\"InsDate\",
							\"NetPremium\"
						)values(
							'$contractID',
							'$assetDetailID',
							'$tempinsid',
							'$company',
							'$date_start',
							'$date_end',
							'$code',
							'$kind',
							'$invest',
							'$premium',
							(select \"ConfirmDate\" from \"insure\".\"thcap_InsureUnforce\" where \"UnforceID\" = '$UnforceID'),
							'$discount',
							'$collectcus',
							'$insuser',
							'$get_id_user',
							'$datelog',
							'9',
							(select count(*) from \"insure\".\"thcap_InsureUnforce_request\" where \"UnforceID\" = '$UnforceID' and \"editTime\" > '0') + 1,
							'$UnforceID',
							$InsID_checknull,
							$InsDate_checknull,
							$NetPremium_checknull
						)
					";
			if($result=pg_query($in_sql)){
			}else{
				$status++;
			}
		}
		
		if($status == 0)
		{
			pg_query("COMMIT");
			//ACTIONLOG
					$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_id_user', '(THCAP) แก้ไข ข้อมูล ประกันภัย ภาคสมัครใจ', '$datelog')");
				//ACTIONLOG---
			echo "<font color=\"#0000FF\">แก้ไขข้อมูลเรียบร้อยแล้ว</font><br/><br/><input type=\"button\" value=\"ตกลง\" style=\"cursor:pointer;\" onClick=\"window.close();\" />";
		}
		else
		{
			pg_query("ROLLBACK");
			echo "<font color=\"red\">ไม่สามารถแก้ไขข้อมูลได้!! $error</font><br/><br/><input type=\"button\" value=\"ปิด\" style=\"cursor:pointer;\" onClick=\"window.close();\" />";
		}
		?>
		<br>
	</div>
	</center>
</body>
</html>