<?php
include("../../config/config.php");
session_start();
pg_query("BEGIN");	
$id_user = $_SESSION["av_iduser"];
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
?>

<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<?php
$tagID = pg_escape_string((string)($_POST["idapp"]));
$bt_okpg = pg_escape_string($_POST["bt_ok"]);
if($bt_okpg=="รับทราบ"){
	$check="agree";//รับทราบ
}else{
	$check="del";//ยกเลิก
}
$date=date("Y-m-d H:i:s");
$status = 0;
$status1 = 0;
if($tagID != ""){
	
  if($check == "agree"){
	for($i=0;$i< count(pg_escape_string((string)$_POST["idapp"]));$i++)
	{
		$tagID = pg_escape_string($_POST["idapp"][$i]);		
		$sql = "update  public.\"fu_tag\" SET \"status_alert\" = '1',\"tag_status\" = '2' where \"tagID\" = '$tagID' ";
		$results3=pg_query($sql);
	
		if($results3)
		{}
		else{
		$status++;
		}
	}
	if($status == 0){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) Follow Up ลูกค้า', '$date')");
		//ACTIONLOG---
		pg_query("COMMIT");
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_alert.php\">";
		echo "<script type='text/javascript'>alert('Save done')</script>";
		exit();
	}else{
	
		pg_query("ROLLBACK");
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_alert.php\">";
		echo "<script type='text/javascript'>alert('Error')</script>";
		echo "Error Save $sql2";
		exit();
	}
	
 }else if($check == 'del'){
	for($i=0;$i< count(pg_escape_string((string)$_POST["idapp"]));$i++)
	{
		$tagID = pg_escape_string($_POST["idapp"][$i]);	
		$sql = "update  public.\"fu_tag\" SET \"status_alert\" = '1',\"tag_status\" = '3' where \"tagID\" = '$tagID' ";
		$results3=pg_query($sql);
	
		if($results3)
		{}
		else{
		$status++;
			}
	}
	if($status == 0){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(ALL) Follow Up ลูกค้า', '$date')");
		//ACTIONLOG---
		pg_query("COMMIT");
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_alert.php\">";
		echo "<script type='text/javascript'>alert('Save done')</script>";
		// echo "<script language="JavaScript">";
		// echo "RefreshMe()";
		// echo "</script>";
		exit();
	}else{
		pg_query("ROLLBACK");
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=fu_alert.php\">";
		echo "<script type='text/javascript'>alert('Error')</script>";
		// echo "<script language="JavaScript">";
		// echo "RefreshMe()";
		// echo "</script>";
		echo "Error Save $sql2";
		exit();
	}
 }
}
?>
			

