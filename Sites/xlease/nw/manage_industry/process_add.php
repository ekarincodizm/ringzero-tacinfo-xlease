<?php
session_start();
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php 
$language_user=$_SESSION['language'];
$industry = $_POST["industry"];
$type = $_POST["type"];
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$language_user=$_SESSION['language'];
if($language_user=='TH'){	
	include("../../language/landTH.php");
}
else if($language_user=='LO'){	
	include("../../language/landLO.php");
}
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

<form name="form">
<input type="hidden" name="industry" id="industry" value="<?php echo $industry; ?>">
</form>

<?php
pg_query("BEGIN");
$status = 0;

$query_chk = pg_query("select * from public.\"th_corp_industype\" where \"IndustypeName\" = '$industry' ");
$row_chk = pg_num_rows($query_chk);
if($row_chk > 0)
{
	$status++;
	$error = $land_industrial_pageprocess_error;	
}
else
{
	$sql_add = "insert into public.\"th_corp_industype\" (\"IndustypeName\") values ('$industry') ";
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
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(ALL) เพิ่มประเภทอุตสาหกรรม', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	
	echo "<center><h2><font color=\"#0000FF\">$land_global_show_success</font></h2></center>";
	if($type == 2)
	{ // ถ้าเป็นการเพิ่มจากหน้า "เพิ่มข้อมูลนิติบุตตล"
		echo "<center><input type=\"button\" value=\"$land_global_btn_OK\" onclick=\"javascript:updateOpener();\"></center>";
	}
	else
	{ // ถ้าเป็นการเพิ่มจากหน้า "จัดการอุตสาหกรรม"
		echo "<center><input type=\"button\" value=\"$land_global_btn_OK\" onclick=\"javascript:RefreshMe();\"></center>";

	}

}
else
{
	pg_query("ROLLBACK");
	echo "<center><h2><font color=\"#FF0000\">$land_global_show_unsuccess $error $land_global_show_tryagain!!</font></h2></center>";
	echo "<br>$corpID";
	echo "<form method=\"post\" name=\"form2\" action=\"frm_add.php\">";
	echo "<input type=\"hidden\" name=\"industry\" value=\"$industry\">";
	echo "<center><input type=\"submit\" value=\"$land_global_btn_back\"></center></form>";	
}
?>