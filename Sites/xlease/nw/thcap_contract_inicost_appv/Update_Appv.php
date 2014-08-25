<script>
function refres(){
	window.opener.location.reload();
	window.close();
}
</script>
<?php

include('../../config/config.php');
$datenow = nowDateTime();
$user_id = $_SESSION["av_iduser"];
$StrAutoID = $_GET["auto_id"];
$ExtendStrID = "'$StrAutoID'";
$NewStrAutoID = str_replace(",","','",$ExtendStrID);
$status=0;
$statusAppv=$_GET["statusAppv"];
//ตรวจสอบว่าเป็นการส่งค่าแบบ Get หรือไม่
if($StrAutoID==""){ 
	$StrAutoID = $_POST["auto_id"]; //ส่งค่าแบบ POST 
	if(isset($_POST["appv"])){   //กด อนุมัติ
		$statusAppv="1";}
	else{
		$statusAppv="0";}       //กด ไม่อนุมัติ
}
pg_query("Begin");

$qry_update="update thcap_contract_inicost 
set \"ini_appv_status\"='$statusAppv',ini_appv_user='$user_id',ini_appv_stamp='$datenow'
where \"inicost_autoID\" in ($StrAutoID) "; 
						
	if(pg_query($qry_update)){
	} else {
		$status++;
	}
	
	if($status == 0){
	pg_query("COMMIT");
	$alert="บันทึกข้อมูลสำเร็จแล้ว";
	}else{
	pg_query("ROLLBACK");
	$alert="บันทึกข้อมูลล้มเหลว";
	}
?>
<html>
	<form action="frm_Request.php" method="post">
		<center>
			<H1><?php echo $alert ?></H1><br>
			<input type="submit" name="OK" value="OK" onclick="refres();">
		</center>
	</form>
</html>