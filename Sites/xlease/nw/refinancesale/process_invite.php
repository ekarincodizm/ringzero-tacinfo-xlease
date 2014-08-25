<?php
session_start();
include("../../config/config.php");
$id_user = $_SESSION["av_iduser"];
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

<div id="wmax" style="width:500px; border:#666666 solid 0px; margin-top:0px;">


<div style="height:50px; width:500px; text-align:left; margin:0px auto;">


<?php
$KeyDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$IDNO = $_POST['u_idno'];
$CusID = $_POST['u_cusid'];
$asset_id = $_POST['asset_id'];
$CusTel = $_POST['CusTel'];

$datepicker = $_POST['datepicker'];
$invite_h = $_POST['invite_h'];
$invite_m = $_POST['invite_m'];
$invite_s = $_POST['invite_s'];
$inviteDate = $datepicker." ".$invite_h.":".$invite_m.":".$invite_s;

$invite_detail = $_POST['invite_detail']; 
//$invite_detail= str_replace("\n", "<br>\n", "$invite_detail"); 

pg_query("BEGIN WORK");
$status = 0;
 
$in_sql="insert into refinance.\"invite\" (\"IDNO\",\"CusID\",\"asset_id\",\"CusTel\",\"KeyDate\",\"inviteDate\",\"id_user\",\"ActiveMatch\",\"invite_detail\") values ('$IDNO','$CusID','$asset_id','$CusTel','$KeyDate','$inviteDate','$id_user','FALSE','$invite_detail')";
if($result=pg_query($in_sql)){
		
}else{
	$status += 1;
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ชักชวนลูกค้า Ref', '$KeyDate')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>บันทึกข้อมูลเีรียบร้อยแล้ว</b></font></div>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_SetTerm.php'>";
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}		

?>
<div align="center">
<FORM METHOD=GET ACTION="#">
<input type="submit" value="  ปิด  " onclick="javascript:RefreshMe();" />
</FORM>
</div>

</div>
</div>

</body>
</html>