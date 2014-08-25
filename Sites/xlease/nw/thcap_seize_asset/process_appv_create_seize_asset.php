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

$createID = pg_escape_string($_POST["createID"]);
$appvCreateText = pg_escape_string($_POST["appv"]);
$RemarkAppv = pg_escape_string($_POST["RemarkAppv"]);

if($appvCreateText == "อนุมัติ")
{ 
	$createStatus = 1; //กดอนุมัติ
}
else
{
	$createStatus = 0; //กดไม่อนุมัติ
}

pg_query("BEGIN WORK");
$status = 0;

$qry_chk_create = pg_query("select \"createStatus\", \"contractID\" from \"thcap_create_seize_asset\" where \"createID\" = '$createID'");
$chk_create = pg_fetch_result($qry_chk_create,0);
$contractID = pg_fetch_result($qry_chk_create,1);

// ตรวจสอบก่อนว่า มีการอนุมัติไปก่อนหน้านี้แล้วหรือยัง
if($chk_create == "0")
{
	$status++;
	$error = "$contractID ถูก ไม่อนุมติ ไปก่อนหน้านี้แล้ว";
}
elseif($chk_create == "1")
{
	$status++;
	$error = "$contractID ถูก อนุมัติ ไปก่อนหน้านี้แล้ว";
}
else
{
	// เก็บประวัติการอนุมัติ
	$sql_save = "update \"thcap_create_seize_asset\"
				set \"appvID\" = '$id_user', \"appvStamp\" = '$logs_any_time', \"appvNote\" = '$RemarkAppv', \"createStatus\" = '$createStatus'
				where \"createID\" = '$createID' and \"createStatus\" = '9' ";
	if(!$qry_save = pg_query($sql_save))
	{
		$status++;
	}

	// ถ้าอนุมัติ
	if($createStatus == 1)
	{
		$qry_allAsset = pg_query("select * from \"thcap_contract_asset\" where \"contractID\" = '$contractID'
								and \"assetDetailID\" in(select \"assetDetailID\" from \"thcap_asset_biz_detail\" where \"as_status_id\" in('2','3','4')) ");
		while($res_allAsset = pg_fetch_array($qry_allAsset))
		{
			$assetDetailID = $res_allAsset["assetDetailID"];
			
			// เก็บข้อมูลสินค้าที่จะยึด
			$sql_seize = "insert into \"thcap_seize_asset\"(\"createID\", \"assetDetailID\", \"seizeStatus\")
							values('$createID','$assetDetailID','9')";
			if(!$qry_seize = pg_query($sql_seize))
			{
				$status++;
			}
			
			// เปลี่ยนสถานะสินทรัพย์ เป็น อยู่ระหว่างยึด
			$sql_changeStatus = "update \"thcap_asset_biz_detail\"
								set \"as_status_id\" = '11'
								where \"assetDetailID\" = '$assetDetailID' and \"as_status_id\" in('2','3','4') ";
			if(!$qry_changeStatus = pg_query($sql_changeStatus))
			{
				$status++;
			}
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