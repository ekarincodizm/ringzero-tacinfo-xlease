<?php
session_start();
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$assetsType = $_POST["assetsType"];
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
?>

<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}

function updateOpener() {
window.opener.document.forms[0].updatelistbox.click();
window.close();
}
</script>

<?php
pg_query("BEGIN");
$status = 0;

$query_chk = pg_query("select * from public.\"thcap_asset_biz_astype\" where \"astypeName\" = '$assetsType' ");
$row_chk = pg_num_rows($query_chk);
if($row_chk > 0)
{
	$status++;
	$error = "มีประเภทสินทรัพย์นี้แล้ว";
}
else
{
	$sql_add = "insert into public.\"thcap_asset_biz_astype\" (\"astypeName\") values ('$assetsType') ";
	if($result_add = pg_query($sql_add))
	{}
	else
	{
		$status++;
	}
}

if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) เพิ่มประเภทสินทรัพย์', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:updateOpener();\"></center>";
	//echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกผิดพลาด $error กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	echo "<br>$corpID";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_addAssetsType.php\">";
	echo "<input type=\"hidden\" name=\"assetsType\" value=\"$assetsType\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}
?>