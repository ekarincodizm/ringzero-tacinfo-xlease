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
$Remark = pg_escape_string($_POST["Remark"]);
$appvCreateText = pg_escape_string($_POST["appv"]);

if($appvCreateText == "อนุมัติ")
{ 
	$appvStatus = 1; //กดอนุมัติ
}
else
{
	$appvStatus = 0; //กดไม่อนุมัติ
}

pg_query("BEGIN WORK");
$status = 0;

$qry_chk_seize = pg_query("select \"assetDetailID\", \"seizeStatus\" from \"thcap_seize_asset\" where \"seizeID\" = '$seizeID'");
$assetDetailID = pg_fetch_result($qry_chk_seize,0);
$chk_seizeStatus = pg_fetch_result($qry_chk_seize,1);

// ตรวจสอบก่อนว่า มีการอนุมัติไปก่อนหน้านี้แล้วหรือยัง
if($chk_seizeStatus == "0")
{
	$status++;
	$error = "$assetDetailID ไม่อนุมัติบันทึกยึดสินทรัพย์ ไปก่อนหน้านี้แล้ว";
}
elseif($chk_seizeStatus == "1")
{
	$status++;
	$error = "$assetDetailID อนุมัติบันทึกยึดสินทรัพย์ ไปก่อนหน้านี้แล้ว";
}
elseif($chk_seizeStatus == "9")
{
	$status++;
	$error = "$assetDetailID อยู่ระหว่าง รอบันทึกยึดสินทรัพย์";
}
else
{
	// เก็บประวัติการอนุมัติ
	$sql_save = "update \"thcap_seize_asset\"
				set \"appvID\" = '$id_user', \"appvStamp\" = '$logs_any_time', \"appvNote\" = '$Remark', \"seizeStatus\" = '$appvStatus'
				where \"seizeID\" = '$seizeID' and \"seizeStatus\" = '8' ";
	if(!$qry_save = pg_query($sql_save))
	{
		$status++;
	}
	
	if($appvStatus == 1) // ถ้าอนุมัติ
	{
		// เปลี่ยนสถานะสินทรัพย์ เป็น สินทรัพย์ถูกยึด
		$sql_changeStatus = "update \"thcap_asset_biz_detail\"
							set \"as_status_id\" = '5'
							where \"assetDetailID\" = '$assetDetailID' and \"as_status_id\" = '11' ";
		if(!$qry_changeStatus = pg_query($sql_changeStatus))
		{
			$status++;
		}
	}
	elseif($appvStatus == 0) // ถ้าไม่อนุมัติ
	{
		// เปลี่ยนสถานะสินทรัพย์ เป็น สินทรัพย์ถูกยึด
		$sql_new = "insert into \"thcap_seize_asset\"(\"createID\", \"assetDetailID\", \"seizeStatus\")
							select \"createID\", \"assetDetailID\", '9' from \"thcap_seize_asset\" where \"seizeID\" = '$seizeID' ";
		if(!$qry_new = pg_query($sql_new))
		{
			$status++;
		}
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