<?php
session_start();
include("../../config/config.php");
if( empty($_SESSION["av_iduser"]) ){
    header("Location:../../index.php");
    exit;
}
//comment ไว้เนื่องจาก ตรวจสอบดูแล้วว่า หน้านี้ถูกเรียกใช้ จาก seize_approve.php ไว้ที่เดียว 
/*$idno = $_GET['idno'];
$ntid = $_GET['ntid'];
$sizeID = $_GET['sizeID'];
$statusapp = $_GET['statusapp'];*/
$idno = $_POST['idno'];
$ntid = $_POST['ntid'];
$sizeID = $_POST['sizeID'];

$get_userid = $_SESSION["av_iduser"];
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$nowdate = Date('Y-m-d');
//ตรวจสอบว่ากด ปุ่มใด
if(isset($_POST["seize_appv"])){
		$statusapp='2';//อนุมัติ
}else if(isset($_POST["seize_unappv"])){
		$statusapp='5';//ไม่อนุมัติ
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    </head>
<body>

<div class="header"><h1><?php echo $_SESSION['session_company_name']; ?></h1></div>

<table width="600" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
<fieldset>
<legend><b>Approve Create งานยึด</b></legend>
<div align="center">
<?php
pg_query("BEGIN WORK");
$status = 0;

//ตรวจสอบก่อนว่ารายการที่ต้องการอนุมัติได้อนุมัติก่อนหน้านี้หรือไม่
$qrycheck=pg_query("select * from \"nw_seize_car\" WHERE \"status_approve\" in('2','5') and \"seizeID\"='$sizeID'");
$numcheck=pg_num_rows($qrycheck);
if($numcheck>0){ //แสดงว่าอนุมัติแล้วก่อนหน้านี้
	echo "<h2>รายการนี้ได้อนุมัติก่อนหน้านี้แล้วค่ะ</h2><br /><br /><input type=\"button\" name=\"fd\" id=\"fdf\" value=\"  กลับ  \" onclick=\"opener.location.reload(true);self.close();\">";
}else{
	$result1=pg_query("Update \"nw_seize_car\" SET \"status_approve\"='$statusapp',\"approve_user\"='$get_userid',\"approve_date\"='$nowdate' WHERE \"seizeID\"='$sizeID'");
	if(!$result1){
		$status++;
	}
	
	if($status == 0){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', '(TAL) อนุมัติการแจกงานยึด', '$datelog')");
		//ACTIONLOG---
		pg_query("COMMIT");
		//pg_query("ROLLBACK");
		echo "บันทึกข้อมูลเรียบร้อยแล้ว<br /><br /><input type=\"button\" name=\"fd\" id=\"fdf\" value=\"  กลับ  \" onclick=\"opener.location.reload(true);self.close();\">";
	}else{
		pg_query("ROLLBACK");
		echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง<br /><br /><input type=\"button\" name=\"fd\" id=\"fdf\" value=\"  กลับ  \" onclick=\"opener.location.reload(true);self.close();\">";
	}
}
?>
</div>
</fieldset>
        </td>
    </tr>
</table>

</body>
</html>