<?php
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<script>
function RefreshMe()
{
    opener.location.reload(true);
    self.close();
}
</script>

<?php
$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$seizeID = pg_escape_string($_POST["seizeID"]);
$dateRecord = pg_escape_string($_POST["dateRecord"]); // วันที่ยึด
$Remark = pg_escape_string($_POST["Remark"]);

pg_query("BEGIN WORK");
$status = 0;

$qry_chk_seize = pg_query("select \"assetDetailID\", \"seizeStatus\" from \"thcap_seize_asset\" where \"seizeID\" = '$seizeID'");
$assetDetailID = pg_fetch_result($qry_chk_seize,0);
$chk_seizeStatus = pg_fetch_result($qry_chk_seize,1);

// ตรวจสอบก่อนว่า มีการอนุมัติไปก่อนหน้านี้แล้วหรือยัง
if($chk_seizeStatus == "0")
{
	$status++;
	$error = "$assetDetailID ไม่อนุมัติบันทึกยึดสินทรัพย์ แล้ว";
}
elseif($chk_seizeStatus == "1")
{
	$status++;
	$error = "$assetDetailID อนุมัติบันทึกยึดสินทรัพย์ แล้ว";
}
elseif($chk_seizeStatus == "8")
{
	$status++;
	$error = "$assetDetailID รออนุมัติบันทึกยึดสินทรัพย์ แล้ว";
}
else
{
	// เก็บประวัติการอนุมัติ
	$sql_save = "update \"thcap_seize_asset\"
				set \"seizeDate\" = '$dateRecord', \"doerID\" = '$id_user', \"doerStamp\" = '$logs_any_time', \"doerNote\" = '$Remark', \"seizeStatus\" = '8'
				where \"seizeID\" = '$seizeID' and \"seizeStatus\" = '9' ";
	if(!$qry_save = pg_query($sql_save))
	{
		$status++;
	}
}

if($status == 0)
{
	pg_query("COMMIT");	
	echo "<br><center>";
	echo "<font color=\"#0000FF\"><h3>บันทึกสมบูรณ์</h3></font>";
	echo "<input type=\"button\" value=\" ตกลง \"  onclick=\"javascript:RefreshMe();\">";
	echo "</center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<br><center>";
	echo "<font color=\"#FF0000\"><h2>บันทึกผิดพลาด!! </h2></font>$error";
	echo "<input type=\"button\" value=\" ตกลง \"  onclick=\"javascript:RefreshMe();\">";
	echo "</center>";
}
?>