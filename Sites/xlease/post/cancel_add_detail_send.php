<?php
session_start();
include("../config/config.php");  
$get_userid = $_SESSION["av_iduser"];
$officeid=$_SESSION["av_officeid"];
$idno = $_POST['idno'];
$remark = $_POST['remark'];
$nowdate = Date('Y-m-d');
$nowdatetime = Date('Y-m-d H:i:s');
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

<fieldset><legend><B>ออก NT</B></legend>
<div align="center">
<?php
pg_query("BEGIN WORK");

$status = 0;

$result2=pg_query("Update \"NTHead\" SET \"remark\"='$remark', \"cancelid\"='$get_userid', \"cancel_date\"='$nowdate' WHERE \"IDNO\"='$idno' and cancel='FALSE'");
if(!$result2){
    $status++;
}

//update สถานะใน nw_statusNT ด้วยว่าได้ยกเลิกไปแล้ว
$qry_notice=pg_query("select * from \"NTHead\" where \"IDNO\" = '$idno' and \"cancel\"='FALSE' ");
while($res_notice=pg_fetch_array($qry_notice)){
	$NTID=$res_notice["NTID"];
	
	//ให้ค้นหาดูก่อนว่าในตาราง nw_statusNT มีข้อมูลหรือยัง ถ้ายังไม่มีให้ Add ข้อมูลแต่ถ้ามีแล้วให้ update
	$qry_nwstatus=pg_query("select \"statusNT\" from \"nw_statusNT\" where \"NTID\"='$NTID'");
	list($statusNT)=pg_fetch_array($qry_nwstatus);
	$num_nw=pg_num_rows($qry_nwstatus);
	if($num_nw>0){
		$result2=pg_query("Update \"nw_statusNT\" SET \"statusNT\"='5',\"statusOld\"='$statusNT' WHERE \"NTID\"='$NTID'");
		if(!$result2){
			$status++;
		}
	}
	/*
	else{
		$result2=pg_query("insert into \"nw_statusNT\" (\"NTID\",\"IDNO\",\"statusNT\",\"user_approve\",\"date_approve\",\"statusOld\") values ('$NTID','$idno','5','000','$nowdatetime','1')");
		if(!$result2){
			$status++;
		}
	}
	*/
}
if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', '(TAL) ขอปลด NT', '$nowdatetime')");
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