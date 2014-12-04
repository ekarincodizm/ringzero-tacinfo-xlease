<?php
session_start();
include("../../config/config.php");
$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$contractID = pg_escape_string($_POST['contractID']);
$assetDetailID = pg_escape_string($_POST['assetDetailID']);
$company = pg_escape_string($_POST['company']);
$code = pg_escape_string($_POST['code']);
$date_start = pg_escape_string($_POST['date_start']);
$date_end = pg_escape_string($_POST['date_end']);
$discount = pg_escape_string($_POST['discount']);
$capa = pg_escape_string($_POST['capa']);
$nowdate = nowDate();

pg_query("BEGIN WORK");
$status=0;

$crif=pg_query("select \"insure\".cal_rate_insforce('$code','$date_start','$date_end')");
$res_crif=pg_fetch_result($crif,0);
$res_crif = preg_replace('/[^a-z0-9,.]/i', '', $res_crif);
$pieces = explode(",", $res_crif);

$gpremium = $pieces[0]+$pieces[1]+$pieces[2];
$col_cus = $gpremium-$discount; 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>บันทึก ข้อมูล ประกันภัย ภาคบังคับ (พรบ.)</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../thcap/act.css"></link>
</head>
<body>
	<center>
		<div class="header"><h2>บันทึก ข้อมูล ประกันภัย ภาคบังคับ (พรบ.)</h2></div>
		<div class="wrapper">
			<br>
			<?php
			// ตรวจสอบก่อนว่า มีการทำรายการไปก่อนหน้านี้แล้วหรือยัง
			$qry_check = pg_query("
									SELECT
										\"appvStatus\"
									FROM
										\"insure\".\"thcap_InsureForce_request\"
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
				$taxstampint = (int) $pieces[1];
				$in_sql = "
							INSERT INTO \"insure\".\"thcap_InsureForce_request\"(
								\"contractID\",
								\"assetDetailID\",
								\"Company\",
								\"StartDate\",
								\"EndDate\",
								\"Code\",
								\"Capacity\",
								\"Premium\",
								\"NetPremium\",
								\"Vat\",
								\"TaxStamp\",
								\"Discount\",
								\"CollectCus\",
								\"doerID\",
								\"doerStamp\",
								\"appvStatus\"
							)
							values(
								'$contractID',
								'$assetDetailID',
								'$company',
								'$date_start',
								'$date_end',
								'$code',
								'$capa',
								'$gpremium',
								'$pieces[0]',
								'$pieces[2]',
								'$taxstampint',
								'$discount',
								'$col_cus',
								'$add_user',
								'$add_date',
								'9'
							)";
				if($result=pg_query($in_sql)){ 
				}else{
					$status++;
				}
			}

			if($status==0){
				pg_query("COMMIT");
				//ACTIONLOG
						$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$add_user','(THCAP) บันทึก ข้อมูล ประกันภัย ภาคบังคับ (พรบ.)', '$add_date')");
				//ACTIONLOG---
				echo "<font color=\"#0000FF\">เพิ่มข้อมูลเรียบร้อยแล้ว</font><br/><br/><input type=\"button\" value=\"ตกลง\" style=\"cursor:pointer;\" onClick=\"window.close();\" />";
			}else{
				pg_query("ROLLBACK");
				echo "<font color=\"red\">ไม่สามารถเพิ่มข้อมูลได้!! $error</font><br/><br/><input type=\"button\" value=\"ปิด\" style=\"cursor:pointer;\" onClick=\"window.close();\" />";
			}	
			?>
		</div>
	</center>
</body>
</html>