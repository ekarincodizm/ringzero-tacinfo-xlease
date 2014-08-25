<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$accBookComp = pg_escape_string($_POST["accBookComp"]); // บริษัทเจ้าของบัญชี
$accBookID = pg_escape_string($_POST["accBookID"]); // เลขที่สมุดบัญชี
$accBookName = pg_escape_string($_POST["accBookName"]); // ชื่อสมุดบัญชี
$accBookType = pg_escape_string($_POST["accBookType"]); // ประเภทสมุดบัญชี
$accBookNameFS = pg_escape_string($_POST["accBookNameFS"]);
$accBookStatus = pg_escape_string($_POST["accBookStatus"]); // สถานะบัญชี
$accBookableFS = pg_escape_string($_POST["accBookableFS"]);

$accBookGroup = pg_escape_string($_POST["accBookGroup"]); //ประเภทกลุ่ม
$accBookCustom = pg_escape_string($_POST["accBookCustom"]);//ประเภทชนิด
$accBookUnit = pg_escape_string($_POST["accBookUnit"]); //ประเภทย่อย
$accBookRealiseType = checknull(pg_escape_string($_POST["accBookRealiseType"]));//รูปแบบการรับรู้รายได้   1-CASH BASIS / 2-CASH ACCRUAL //null=ไม่ระบุ
$accBookTypeFS = checknull(pg_escape_string($_POST["accBookTypeFS"])); //1 - งบดุล ,2 - งบกำไรขาดทุนเบ็ดเสร็จ
$accserial = pg_escape_string($_POST["accserial"]);


$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$iduser' ");
$leveluser = pg_fetch_array($query_leveluser);
$emplevel=$leveluser["emplevel"];

?>

<script type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<?php
pg_query("BEGIN");
$status = 0;
if($emplevel>1){$status++;}

	$sql_add = ("update account.\"all_accBook\" set 
	\"accBookComp\"='$accBookComp',
	\"accBookID\"='$accBookID',
	\"accBookName\"='$accBookName',
	\"accBookType\"='$accBookType',
	\"accBookGroup\"='$accBookGroup',
	\"accBookCustom\"='$accBookCustom',
	\"accBookUnit\"='$accBookUnit',
	\"accBookRealiseType\"=$accBookRealiseType,
	\"accBookNameFS\"='$accBookNameFS',
	\"accBookStatus\"='$accBookStatus',
	\"accBookableFS\"='$accBookableFS',
	\"accBookTypeFS\"=$accBookTypeFS
	WHERE \"accBookserial\"='$accserial'
	");
	if($result_add = pg_query($sql_add))
	{}
	else
	{
		$status++;
	}

if($status == 0)
{
	pg_query("COMMIT");
	echo "<center><h2><font color=\"#0000FF\">บันทึกการแก้ไขสำเร็จ</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกผิดพลาด กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_add.php\">";
	echo "<input type=\"hidden\" name=\"accBookID\" value=\"$accBookID\">";
	echo "<input type=\"hidden\" name=\"accBookName\" value=\"$accBookName\">";
	echo "<input type=\"hidden\" name=\"accBookType\" value=\"$accBookType\">";
	echo "<input type=\"hidden\" name=\"accBookNameFS\" value=\"$accBookNameFS\">";
	echo "<input type=\"hidden\" name=\"accBookStatus\" value=\"$accBookStatus\">";
	echo "<input type=\"hidden\" name=\"accBookableFS\" value=\"$accBookableFS\">";
	echo "<input type=\"hidden\" name=\"accBookserial\" value=\"$accserial\">";
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}
?>