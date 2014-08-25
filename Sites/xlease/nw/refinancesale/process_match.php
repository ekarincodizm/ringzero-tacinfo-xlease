<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
$current = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$idno_old=$_POST["idno_old"];
$asset_id=$_POST["asset_id"];
$CusID=$_POST["CusID"];
$idno_new=trim($_POST["idno_new"]);
$idno_new = substr($idno_new,1,9);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">

<div id="wmax" style="width:700px; border:#666666 solid 0px; margin-top:0px;">


<div style="height:50px; width:700px; text-align:left; margin:0px auto;">
<?php
// ตรวจสอบข้อมูลที่เลือกว่ามีในฐานข้อมูลหรือไม่ 

$query_check = pg_query("select * from \"VContact\" WHERE \"IDNO\" = '$idno_new' ");
$num_check=pg_num_rows($query_check);

if($res = pg_fetch_array($query_check)){
	$IDNO_NEW = $res["IDNO"];
	$CusID_NEW = $res["CusID"];
	$asset_new = $res["asset_id"];
}

if($num_check == 0){
	$status = 1;
}else{
	if(($CusID == $CusID_NEW) and ($asset_id == $asset_new) and ($idno_old != $IDNO_NEW)){
		$query_invite = pg_query("select * from refinance.\"invite\" WHERE \"IDNO\" = '$idno_old' and \"id_user\" = '$id_user' order by \"inviteDate\" limit (1)");
		if($res_invite = pg_fetch_array($query_invite)){
			$inviteID = $res_invite["inviteID"];
		}

		pg_query("BEGIN WORK");
		$status = 0;

		$ins = "insert into refinance.\"match_invite\"(\"inviteID\",\"IDNO\",\"matchDate\") values ('$inviteID','$idno_new','$current')";
		if($result=pg_query($ins)){
			
		}else{
			$status += 1;
		}
		
		$updateinvite = "update refinance.\"invite\" set \"ActiveMatch\" = 'TRUE' where \"IDNO\" = '$idno_old' and \"CusID\" = '$CusID' and \"asset_id\" = '$asset_id'";
		if($res_up=pg_query($updateinvite)){
			
		}else{
			$status += 1;
		} 
		$echotxt = "บันทึกข้อมูลเรียบร้อยแล้ว";
	}else{
		$status = 1;
	}
}
if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) จับคู่ลูกค้าที่ชักชวนลูกค้า Ref', '$current')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>$echotxt</b></font></div>";
	?>
	<div align="center">
	<FORM METHOD=GET ACTION="#">
	<input type="submit" value="  ปิด  " onclick="javascript:RefreshMe();" />
	</FORM>
	</div>
	<?php
}else{
	pg_query("ROLLBACK");
	echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font></div>";
	echo "<meta http-equiv='refresh' content='2; URL=frm_Match_inviteSell.php?idno=$idno_old&cusid=$CusID&asset_id=$asset_id'>";
}		  
?>

</div>
</div>

</body>
</html>