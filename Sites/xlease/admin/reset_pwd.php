<?php
session_start();
include("../config/config.php");
$i_id=pg_escape_string($_POST["h_id"]);

$id_user=$_SESSION["av_iduser"];
$logs_any_time = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

include("../company.php");
    foreach($company as $v){
			$_SESSION["session_company_seed"]=$v['seed'];
						
            break;
    }
$seed = $_SESSION["session_company_seed"];

$i_pwd=md5(md5($_POST["update_pwd"]).$seed);

pg_query("BEGIN");
$status = 0;

$str_update="UPDATE  \"fuser\" SET \"password\" = '$i_pwd' , \"password_status\" = '2'  WHERE id_user='$i_id' ";
 if($result=pg_query($str_update))
 {}
 else
 {
	$status++;
 }
 
 $sql_last="UPDATE  \"fuser_detail\" SET \"user_keylast\"='$id_user' , \"keydatelast\"='$logs_any_time' WHERE \"id_user\"='$i_id' ";
 if($result2=pg_query($sql_last))
 {}
 else
 {
	$status++;
 }
?> 
<center>
<?php
if($status == 0)
{
	pg_query("COMMIT");
	echo "<br>บันทึกเรียบร้อยแล้ว<br><br>";
	echo "<button onclick=\"window.close();\">CLOSE</button>";
}
else
{
	pg_query("ROLLBACK");
	echo "<br>บันทึกผิดพลาด กรุณาลองใหม่อีกครั้ง!!<br><br>";
	echo "<button onclick=\"window.close();\">CLOSE</button>";
}
?>
</center>