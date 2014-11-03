<?php 
include("../../config/config.php");
set_time_limit(0);

$typeGen = pg_escape_string($_POST["typeGen"]); // ประเภทเงิน 997 - secure เงินค้ำ 998 - hold เงินพัก
$dateGen = pg_escape_string($_POST["dateGen"]); // วันที่จะ GEN ข้อมูล
?>

<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />

<script type="text/javascript">
	function RefreshMe(){
		opener.location.reload(true);
		self.close();
	}
</script>

<?php
$str_gen = "SELECT \"thcap_gentable_thcap_temp_money_hold_secure\"('$typeGen', '$dateGen')";
$qry_gen = pg_query($str_gen);
$res_gen = pg_fetch_result($qry_gen,0);

if($res_gen == "t")
{
	echo "<center>";
	echo "<h1><font color=\"blue\">GEN ข้อมูลสำเร็จ</font></h1>";
	echo "<input type=\"button\" value=\"ตกลง\" style=\"cursor:pointer;\" onClick=\"RefreshMe();\" />";
	echo "</center>";
}
else
{
	echo "<center>";
	echo "<h1><font color=\"red\">GEN ไม่สำเร็จ!!</font></h1>";
	echo "<input type=\"button\" value=\"กลับ\" style=\"cursor:pointer;\" onClick=\"window.location='popup_gen_money_hold_secure.php?typeGen=$typeGen';\" />";
	echo "</center>";
}
?>