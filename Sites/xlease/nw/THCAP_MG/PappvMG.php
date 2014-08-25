<?php
include("../../config/config.php");

$c1 = $_GET["a"];
$c2 = $_GET["b"];

//c1 คือ อนุมัติ หรือ ยกเลิก
//c2 คือ รหัสที่ต้องการจะดำเนินการ

if($c1==1)
{
	echo "<br><center><h3>คุณกำลังจะ <u>อนุมัติ</u> รหัส running ที่ \"$c2\" แน่ใจหรือไม่</h3></center><br>";
	echo "<form method=\"post\" name=\"form1\" action=\"appvMG.php\">";
	echo "<input type=\"hidden\" name=\"c1\" value=\"$c1\">";
	echo "<input type=\"hidden\" name=\"c2\" value=\"$c2\">";
	echo "<center><input type=\"submit\" value=\" ยืนยัน \"> <input name=\"button\" type=\"button\" onclick=\"window.location='appvMG.php'\" value=\" ยกเลิก \" /></center>";
	echo "</form>";
}

if($c1==2)
{
	echo "<br><center><h3>คุณกำลังจะ <u>ยกเลิก</u> รหัส running ที่ \"$c2\" แน่ใจหรือไม่</h3></center><br>";
	echo "<form method=\"post\" name=\"form2\" action=\"appvMG.php\">";
	echo "<input type=\"hidden\" name=\"c1\" value=\"$c1\">";
	echo "<input type=\"hidden\" name=\"c2\" value=\"$c2\">";
	echo "<center><input type=\"submit\" value=\" ยืนยัน \"> <input name=\"button\" type=\"button\" onclick=\"window.location='appvMG.php'\" value=\" ยกเลิก \" /></center>";
	echo "</form>";
}
?>