<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$user_id = $_SESSION["av_iduser"];
$appv_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$autoid = pg_escape_string($_POST["autoid"]); 
if(isset($_POST["appv"])){
		$method="1";//อนุมัติ
	}else{
		$method="0";//ไม่อนุมัติ
}
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
	
	$up1_sql="UPDATE \"thcap_letter_head_temp\" SET \"status\"='$method',\"appvID\"='$user_id',\"appvStamp\"='$appv_date' WHERE \"auto_id\"='$autoid' RETURNING \"auto_id\" ";
	$result21 = pg_query($up1_sql);
	if($result21)
	{
		$check_update21 = pg_fetch_result($result21,0);
		if($check_update21 == ""){$status++;}
	}
	else
	{
	$status++;
	}
	if($method=='1'){
		//ตรวจสอบว่าเป็นรายการแก้ไข หรือเพิ่ม
		$qry_chk=pg_query("SELECT  \"auto_id\" FROM thcap_letter_head where \"ref_temp\"='$check_update21'");
		list($auto_id_chk)=pg_fetch_array($qry_chk);
		
		
		$numrows = pg_num_rows($qry_chk);
		//เป็นรายการแก้ไข
		if($numrows==1){
			//select ข้อมูล จาก thcap_letter_head_temp
			$qry_chk=pg_query("SELECT  \"sendName\",\"addUser\",\"addStamp\" FROM thcap_letter_head_temp where \"auto_id\"='$check_update21'");
			list($sendName,$addUser,$addStamp)=pg_fetch_array($qry_chk);
			//update ข้อมูลลง  thcap_letter_head
			$sql_add = "update \"thcap_letter_head\" SET \"sendName\"='$sendName',\"addUser\"='$addUser',\"addStamp\"='$addStamp' 
			where \"auto_id\"='$auto_id_chk'";
		}
		//เป็นรายการเพิ่ม
		else if($numrows==0)
		{
			$sql_add = "insert into \"thcap_letter_head\" (\"sendName\",\"addUser\",\"addStamp\",\"ref_temp\")
				select \"sendName\",\"addUser\",\"addStamp\",\"auto_id\" from \"thcap_letter_head_temp\" WHERE \"auto_id\"='$autoid' ";
		}
		else{ $status++;}
		if($result_add = pg_query($sql_add))
		{}
		else
		{
			$status++;
		}
	}

if($status == 0)
{
	pg_query("COMMIT");	
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกผิดพลาด กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";
}
?>