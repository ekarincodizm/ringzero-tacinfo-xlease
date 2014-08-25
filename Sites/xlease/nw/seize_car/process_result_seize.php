<?php
session_start();
include("../../config/config.php");  
$get_userid = $_SESSION["av_iduser"];
$officeid=$_SESSION["av_officeid"];
$IDNO = $_POST['idno'];
$NTID = $_POST['ntid'];
$seize_result = $_POST['seize_result'];
$yellow_date = $_POST['yellow_date'];
$nowdate = Date('Y-m-d');
$method = $_POST['method'];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>        

</head>
<body>

<fieldset><legend><B>ส่งเรื่องยึดรถ</B></legend>
<div align="center">
<?php
pg_query("BEGIN WORK");

$status = 0;
if($method == "add"){
	$result2="insert into \"nw_seize_car\" (\"IDNO\",\"yellow_date\",\"seize_result\",\"send_user\",\"send_date\",\"status_approve\",\"NTID\") 
								 values ('$IDNO','$yellow_date','$seize_result','$get_userid','$nowdate','1','$NTID')";

	if($result=pg_query($result2)){
			
	}else{
		$status += 1;
	}
}else{
	$result2="update  \"nw_seize_car\" set \"yellow_date\" = '$yellow_date',
										\"seize_result\" ='$seize_result',
										\"send_user\" = '$get_userid',
										\"send_date\" = '$nowdate'
										where \"IDNO\" = '$IDNO' and \"NTID\" = '$NTID'";
	if($result=pg_query($result2)){
			
	}else{
		$status += 1;
	}
}
if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', '(TAL) สร้างงานยึด', '$add_date')");
	//ACTIONLOG---
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}
?>

<div><br><input type="button" value="     ปิดหน้านี้     " onclick="javascript:RefreshMe();"></div>

</div>
</fieldset> 

</body>
</html>