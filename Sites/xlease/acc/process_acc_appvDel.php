<?php
include('../config/config.php');

$appv = pg_escape_string($_GET["ap"]);
$autoID = pg_escape_string($_GET["delid"]);
if($autoID==""){
	$autoID = $_POST["delid"];
	if(isset($_POST["appv"])){
		$appv="1";//อนุมัติ
	}else{
		$appv="0";//ไม่อนุมัติ
	}
}
$id_user = $_SESSION["av_iduser"];
$logs_any_time = nowDateTime();
?>

<script language="JavaScript" type="text/javascript">
function mainReload()
{
	opener.location.reload(true);
	self.close();
}
</script>

<?php
pg_query("BEGIN WORK");
$status = 0;

// หาค่า id_tranpay และผู้ทำรายการ
$qry_id_tranpay = pg_query("select \"id_tranpay\", \"doerID\" from \"TranPay_Request_Cancel\" where \"autoID\" = '$autoID' ");
$id_tranpay = pg_fetch_result($qry_id_tranpay,0);
$doerID = pg_fetch_result($qry_id_tranpay,1);

// หาค่า emplevel
$qry_emplevel = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$id_user' ");
$emplevel = pg_fetch_result($qry_emplevel,0);

if($emplevel <= 1 || $doerID != $id_user)
{
	if($appv == 1)
	{ // ถ้าอนุมัติ	
		// หาค่า id_tranpay
		$qry_id_tranpay = pg_query("select \"id_tranpay\" from \"TranPay_Request_Cancel\" where \"autoID\" = '$autoID' ");
		$id_tranpay = pg_fetch_result($qry_id_tranpay,0);
		
		$qry_updateDel = "UPDATE \"TranPay_Request_Cancel\" SET \"appvID\" = '$id_user', \"appvStamp\" = '$logs_any_time', \"Approved\" = '1'
							WHERE \"autoID\" = '$autoID' and \"Approved\" = '9' ";
		if($result=pg_query($qry_updateDel)){}else{$status++;}
		
		// ลบข้อมูลทิ้ง
		$qry_Del = "DELETE FROM \"TranPay\" WHERE \"id_tranpay\" = '$id_tranpay' ";
		if($result=pg_query($qry_Del)){}else{$status++;}
	}
	else
	{ // ถ้าไม่อนุมัติ
		$qry_updateDel = "UPDATE \"TranPay_Request_Cancel\" SET \"appvID\" = '$id_user', \"appvStamp\" = '$logs_any_time', \"Approved\" = '0'
							WHERE \"autoID\" = '$autoID' and \"Approved\" = '9' ";
		if($result=pg_query($qry_updateDel)){}else{$status++;}
	}
}
else
{
	$status++;
	echo "<br><center><h2><font color=\"#FF0000\">ผู้ขอและผู้อนุมัติ ต้องเป็นคนละคนกันเท่านั้น!!</font></h2></center><br>";
}

if($status == 0)
{
	pg_query("COMMIT");
	
	echo "<br><center><h2><font color=\"#0000FF\">บันทึกสมบูรณ์</font></h2></center>";
	echo "<br><br><center><input type=\"button\" value=\"ตกลง\" onClick=\"mainReload();\"><center>";
}
else
{
	pg_query("ROLLBACK");
	
	echo "<br><center><h2><font color=\"#FF0000\">บันทึกผิดพลาด!!</font></h2></center>";
	echo "<br><br><center><input type=\"button\" value=\"ตกลง\" onClick=\"mainReload();\"><center>";
}
?>