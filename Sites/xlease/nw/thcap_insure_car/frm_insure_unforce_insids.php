<?php
session_start();
include("../../config/config.php");
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$get_id_user = $_SESSION["av_iduser"];

$UnforceID = pg_escape_string($_POST['UnforceID']);
$insid = pg_escape_string($_POST['insid']);
$netpremium = pg_escape_string($_POST['netpremium']);
$insdate = pg_escape_string($_POST['datepicker']);
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td background=><img src="../../images/bg_01.jpg" height="15" width="700"></td>
    </tr>
    <tr>
        <td align="center" valign="top" background="../../images/bg_02.jpg" style="background-repeat:repeat-y">

			<div class="header"><h1>ระบบประกันภัย</h1></div>
			<div class="wrapper">

			<?php
			pg_query("BEGIN");
			$status = 0;
			
			// ตรวจสอบก่อนว่า มีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
			$chk_qry = pg_query("
									SELECT
										\"UnforceID\"
									FROM
										insure.\"thcap_InsureUnforce\"
									WHERE
										\"UnforceID\" = '$UnforceID' AND
										\"InsID\" IS NULL AND
										\"NetPremium\" = '0.00' AND
										\"InsDate\" IS NULL
								");
			$chk_row = pg_num_rows($chk_qry);
			if($chk_row == 0)
			{
				$status++;
				$error = "มีการทำรายการไปก่อนหน้านี้แล้ว";
			}
			else
			{
				// เพิ่มประวัติการแก้ไข
				$in_sql = "
							INSERT INTO insure.\"thcap_InsureUnforce_request\"(
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
								\"EndDate\",
								\"InsID\",
								\"NetPremium\",
								\"InsDate\",
								\"doerID\",
								\"doerStamp\",
								\"appvStatus\",
								\"appvID\",
								\"appvStamp\",
								\"editTime\",
								\"UnforceID\"
							)
							SELECT
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
								\"EndDate\",
								'$insid',
								'$netpremium',
								'$insdate',
								'$get_id_user',
								'$datelog',
								'1',
								'$get_id_user',
								'$datelog',
								(select count(*) from \"insure\".\"thcap_InsureUnforce_request\" where \"UnforceID\" = '$UnforceID' and \"editTime\" > '0') + 1,
								\"UnforceID\"
							FROM
								insure.\"thcap_InsureUnforce\"
							WHERE
								\"UnforceID\" = '$UnforceID'
						";
				if($result=pg_query($in_sql)){
				}else{
					$status++;
				}

				// แก้ไขข้อมูลจริงทันที
				$up_sql="UPDATE \"insure\".\"thcap_InsureUnforce\" SET \"InsID\"='$insid',\"NetPremium\"='$netpremium',\"InsDate\" = '$insdate' WHERE \"UnforceID\"='$UnforceID'";
				if($result=pg_query($up_sql)){
				}else{
					$status++;
				}
			}

			if($status == 0)
			{
				pg_query("COMMIT");
				
				//ACTIONLOG
					$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_id_user', '(THCAP) ตรวจรับกรมธรรม์', '$datelog')");
				//ACTIONLOG---
				
				echo "<font color=\"#red\">เพิ่มข้อมูลเรียบร้อยแล้ว</font>";
			}
			else
			{
				pg_query("ROLLBACK");
				
				echo "<font color=\"#red\"><u>ไม่</u>สามารถเพิ่มข้อมูลได้!! $error</font>";
			}
			?>

			<br><br>
			<input type="button" value="  Back  " onclick="location.href='frm_insure_unforce_search_insid.php'" style="cursor:pointer;" />

			</div>

        </td>
    </tr>
    <tr>
        <td><img src="../../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>