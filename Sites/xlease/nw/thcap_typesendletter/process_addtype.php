<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server


$accname = pg_escape_string($_POST["accname"]);
$method = pg_escape_string($_POST["method"]);
$autoid = pg_escape_string($_POST["autoid"]);
$show = pg_escape_string($_POST["show"]);

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
$query_1 = pg_query("select \"sendName\" from \"thcap_letter_head_temp\" where \"sendName\" = '$accname' and \"status\"='9'");
$numrows_1 = pg_num_rows($query_1);
if($numrows_1>0){$status++;}

$query_2 = pg_query("select \"sendName\" from \"thcap_letter_head\" where \"sendName\" = '$accname' ");
$numrows_2 = pg_num_rows($query_2);
if($numrows_2>0){$status++;}
if($status==0){

	$sql_add = "insert into \"thcap_letter_head_temp\" (\"sendName\",\"addUser\",\"addStamp\",\"status\")
				values ('$accname','$user_id','$add_date','9') RETURNING \"auto_id\" ";
	if($result_add = pg_query($sql_add))
	{	
		if(list($auto_id_temp)=pg_fetch_array($result_add)){
			if($method=="edit"){
				$qryupdate="UPDATE  thcap_letter_head  SET \"ref_temp\" ='$auto_id_temp' where \"auto_id\"='$autoid' RETURNING \"auto_id\"";
				if($result_update = pg_query($qryupdate))
				{	
					if(list($auto_id_update)=pg_fetch_array($result_update)){
					}else {
						$status++;				
					}
				}
				else
				{	$status++; }
			}
		}else 
		{	$status++;	}
		
	}
	else
	{
		$status++;
	}
}
if($status == 0)
{
	pg_query("COMMIT");	
	echo "<center><h2><font color=\"#0000FF\">บันทึกสำเร็จ</font></h2></center>";
	if($show=="1"){echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"self.close();\"></center>";}
	else{echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";}
}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">บันทึกผิดพลาด กรุณาลองใหม่อีกครั้ง!!</font></h2></center>";
	if($show=="1"){echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"self.close();\"></center>";}
	else{echo "<center><input type=\"button\" value=\"ตกลง\" onclick=\"javascript:RefreshMe();\"></center>";}
}
?>