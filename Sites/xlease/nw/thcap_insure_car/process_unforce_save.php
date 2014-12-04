<?php
session_start();
include("../../config/config.php");

$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$get_id_user = $_SESSION["av_iduser"];

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

$select_insid = pg_query("SELECT COUNT(\"TempInsID\") AS c_tempid FROM \"insure\".\"InsureUnforce\" WHERE \"TempInsID\"='$tempinsid';");
$res_insid=pg_fetch_result($select_insid,0);
if($res_insid > 0){
    echo '<div align="center">พบข้อมูลซ้ำ!<br>เลขรับแจ้ง '.$tempinsid.' ได้ถูกเพิ่มไปแล้ว';
    echo '<br><input name="button" type="button" onclick="javascript:history.back();" value=" กลับ " /></div>';
    exit();
}

pg_query("BEGIN WORK");
$status = 0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>บันทึก ข้อมูล ประกันภัย ภาคสมัครใจ</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>
	<center>
	<div class="header"><h2>บันทึก ข้อมูล ประกันภัย ภาคสมัครใจ</h2></div>
	<div class="wrapper">
		<?php
		// ตรวจสอบก่อนว่า มีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
		$qry_check = pg_query("
								SELECT
									\"appvStatus\"
								FROM
									\"insure\".\"thcap_InsureUnforce_request\"
								WHERE
									\"contractID\" = '$contractID' AND
									\"assetDetailID\" = '$assetDetailID' AND
									\"editTime\" = '0' AND
									\"appvStatus\" in('1','9')
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
							\"appvStatus\"
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
							'$nowdate',
							'$discount',
							'$collectcus',
							'$insuser',
							'$get_id_user',
							'$datelog',
							'9'
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
					$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_id_user', '(THCAP) บันทึก ข้อมูล ประกันภัย ภาคสมัครใจ', '$datelog')");
				//ACTIONLOG---
			echo "<font color=\"#0000FF\">เพิ่มข้อมูลเรียบร้อยแล้ว</font><br/><br/><input type=\"button\" value=\"ตกลง\" style=\"cursor:pointer;\" onClick=\"window.close();\" />";
		}
		else
		{
			pg_query("ROLLBACK");
			echo "<font color=\"red\">ไม่สามารถเพิ่มข้อมูลได้!! $error</font><br/><br/><input type=\"button\" value=\"ปิด\" style=\"cursor:pointer;\" onClick=\"window.close();\" />";
		}
		?>
		<br>
	</div>
	</center>
</body>
</html>