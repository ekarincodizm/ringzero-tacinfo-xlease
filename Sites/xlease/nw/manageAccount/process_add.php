<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$accBookComp = $_POST["accBookComp"]; // บริษัทเจ้าของบัญชี
$accBookID = $_POST["accBookID"]; // เลขที่สมุดบัญชี
$accBookName = $_POST["accBookName"]; // ชื่อสมุดบัญชี
$accBookType = $_POST["accBookType"]; // ประเภทสมุดบัญชี
$accBookNameFS = $_POST["accBookNameFS"];
$accBookStatus = $_POST["accBookStatus"]; // สถานะบัญชี
$accBookableFS = $_POST["accBookableFS"];

$accBookGroup = $_POST["accBookGroup"]; //ประเภทกลุ่ม
$accBookCustom = $_POST["accBookCustom"];//ประเภทชนิด
$accBookUnit = $_POST["accBookUnit"]; //ประเภทย่อย
$accBookRealiseType = checknull($_POST["accBookRealiseType"]);//รูปแบบการรับรู้รายได้   1-CASH BASIS / 2-CASH ACCRUAL //null=ไม่ระบุ
$accBookTypeFS = checknull($_POST["accBookTypeFS"]); //1 - งบดุล ,2 - งบกำไรขาดทุนเบ็ดเสร็จ

//ตรวจสอบ level ของ ผู้ใช้งาน  เนื่องจาก <=1 จะเพิ่ม/แก้ไขได้
$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$user_id' ");
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

	$sql_add = "insert into account.\"all_accBook\" (\"accBookComp\",\"accBookID\",\"accBookName\",\"accBookType\"
	,\"accBookGroup\",\"accBookCustom\",\"accBookUnit\",\"accBookRealiseType\"	
	,\"accBookNameFS\",\"accBookStatus\",\"accBookableFS\",\"accBookTypeFS\")
				values ('$accBookComp','$accBookID','$accBookName','$accBookType'
				,'$accBookGroup','$accBookCustom' ,'$accBookUnit' ,$accBookRealiseType				
				,'$accBookNameFS','$accBookStatus','$accBookableFS',$accBookTypeFS) ";
	if($result_add = pg_query($sql_add))
	{}
	else
	{
		$status++;
	}

if($status == 0)
{
	pg_query("COMMIT");
	//pg_query("ROLLBACK"); // test
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
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
	echo "<center><input type=\"submit\" value=\"กลับ\"></center></form>";
}
?>