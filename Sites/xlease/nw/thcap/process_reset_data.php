<?php
include("../../config/config.php");
pg_query("BEGIN WORK");
$status=0;

$user_id = $_SESSION["av_iduser"];
$add_date = nowDateTime();//วันเวลาปัจจุบันจาก server
$tranid = pg_escape_string($_POST['tranid']);
$revChqID = pg_escape_string($_POST['revChqID']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script language="JavaScript" type="text/javascript">
window.onbeforeunload = WindowCloseHanlder;
function WindowCloseHanlder()
{    
     opener.location.reload(true);
}
</script> 
</head>

<body>
<?php
//ตรวจสอบว่า มี รหัสการโอนหรือไม่
$qry_have = pg_query("SELECT * FROM finance.\"thcap_receive_transfer\" 
WHERE  \"revTranID\"='$tranid'");
$numrows_have = pg_num_rows($qry_have);
if($numrows_have > 0){
	$qry_up = "UPDATE finance.thcap_receive_transfer SET \"revTranStatus\"='9', \"revChqID\"=null, \"chqKeeperID\" =null WHERE \"revTranID\"='$tranid' and \"revTranStatus\"='7' 
			RETURNING  \"revTranID\" ";			
	$result = pg_query($qry_up);
	if($result){
		$revTranID = pg_fetch_result($result,0);
	}else{
		$status++;
	}
	if($revTranID ==''){$status++;}				


	$up_action="UPDATE finance.thcap_receive_transfer_action
	SET \"appvYID\"=null, \"appvYStamp\"=null,\"appvYStatus\"=null
	WHERE \"revTranID\"='$tranid' and \"appvYStatus\"='2'
	RETURNING  \"tranActionID\" ";

	$resultup_action = pg_query($up_action);
	if($result){
		$tranActionID = pg_fetch_result($resultup_action,0);
	}else{
		$status++;
	}
	if($tranActionID ==''){$status++;}	

}
else{$status++;}
if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>สามารถบันทึกข้อมูลได้ สำเร็จ</b></font></div>";
}else{
	pg_query("ROLLBACK");		
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
}
?>
</body>
</html>