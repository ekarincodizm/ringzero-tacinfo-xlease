<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php"); // ไฟล์ function chacknull ใช้เพื่อตรวจสอบค่าว่างของตัวแปรนั้นๆ วิธีใช้คือ $A = checknull($A); หาก $A เป็นค่าว่างจะส่งค่า "null" กลับมา หากไม่ใช่จะส่งค่า '$A' กลับมา...
$i_id=$_POST["h_id"];

$id_user=$_SESSION["av_iduser"];
$logs_any_time = nowDateTime();

include("../../company.php");
    foreach($company as $v){
			$_SESSION["session_company_seed"]=$v['seed'];
						
            break;
    }
$seed = $_SESSION["session_company_seed"];

$i_pwd=md5(md5($_POST["update_pwd"]).$seed);

//ตรวจสอบเบื้องต้นว่ารายการนี้อนุมัติหรือยังเพื่อป้องกันการอนุมัติซ้ำ
$querychk = pg_query("select * from public.\"fuser\" where \"password\" is null and \"id_user\"='$i_id' ");
$numchk = pg_num_rows($querychk);
if($numchk>0){ //แสดงว่ายังไม่อนุมัติ

pg_query("BEGIN");
$status = 0;

// รหัสเดิม
$qry_old_password = pg_query("select \"password\" from \"fuser\" where \"id_user\" = '$i_id' ");
$old_password = pg_fetch_result($qry_old_password,0);

$old_password = checknull($old_password);

// เก็บประวัติการเปลี่ยนแปลง password
$qry_password_temp = "INSERT INTO \"change_password_user_log\"(\"id_user\", \"old_password\", \"new_password\", \"doerID\", \"doerStamp\", \"appvID\", \"appvStamp\", \"Approved\")
						VALUES('$i_id', $old_password, '$i_pwd', '$id_user', '$logs_any_time', '$id_user', '$logs_any_time', 'TRUE')";
if($result=pg_query($qry_password_temp))
{}
else
{
	$status++;
}

// update ข้อมูล
$str_update="UPDATE  fuser SET password='$i_pwd'  WHERE id_user='$i_id' ";
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
<script type="text/javascript">

function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>
<center>
<?php
if($status == 0)
{
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) ตั้งรหัสพนักงานใหม่', '$logs_any_time')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<br>บันทึกเรียบร้อยแล้ว<br><br>";
	echo "<button onclick=\"javascript:RefreshMe();\">CLOSE</button>";
}
else
{
	pg_query("ROLLBACK");
	echo "<br>บันทึกผิดพลาด กรุณาลองใหม่อีกครั้ง!!<br><br>";
	echo "<button onclick=\"javascript:RefreshMe();\">CLOSE</button>";
}

}else{ //กรณีมีการอนุมัติไปแล้วก่อนหน้านี้
	echo "<div style=\"text-align:center;padding:20px;\"><h1>รายการนี้ได้รับการอนุมัติไปแล้ว กรุณาตรวจสอบอีกครั้ง !!</h1>";
	echo "<input type=\"button\" value=\" ตกลง \"  onclick=\"javascript:RefreshMe();\"></div>";
}
?>
</center>