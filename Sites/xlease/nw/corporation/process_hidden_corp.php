<?php
include("../../config/config.php");

$corp_regis = $_GET["corp_regis"];
?>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}

function updateOpener() {
	window.opener.document.getElementById("seeold").click();
	window.close();
}
</script>

<?php
pg_query("BEGIN");
$status = 0;

$sql_hidden_corp = "update public.\"th_corp_temp\" set \"hidden\" = 'true' where \"corp_regis\" = '$corp_regis' and \"hidden\" = 'false' and \"corpID\" = '0' ";
if($resultNO_corp = pg_query($sql_hidden_corp))
{}
else
{
	$status++;
}

$sql_hidden_corp = "update public.\"th_corp_adds_temp\" set \"hidden\" = 'true' where \"corp_regis\" = '$corp_regis' and \"hidden\" = 'false' and \"corpID\" = '0' ";
if($resultNO_corp = pg_query($sql_hidden_corp))
{}
else
{
	$status++;
}

$sql_hidden_corp = "update public.\"th_corp_acc_temp\" set \"hidden\" = 'true' where \"corp_regis\" = '$corp_regis' and \"hidden\" = 'false' and \"corpID\" = '0' ";
if($resultNO_corp = pg_query($sql_hidden_corp))
{}
else
{
	$status++;
}


if($status == 0)
{
	pg_query("COMMIT");
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	echo "<br><center><input type=\"button\" value=\"  ปิด  \" onclick=\"javascript:updateOpener();\"></center>";
}
else
{
	pg_query("ROLLBACK");
	$corp_regis = str_replace("'","",$corp_regis);
	echo "<center><h2><font color=\"#FF0000\">บันทึกข้อมูลผิดพลาด กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	echo "<br><center><input type=\"button\" value=\"  ปิด  \" onclick=\"javascript:updateOpener();\"></center>";
}
?>