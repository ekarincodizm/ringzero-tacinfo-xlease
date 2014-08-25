<?php
session_start();
include("../config/config.php");  
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$chkdate = $_POST['chkdate'];
$signDate = $_POST['signDate'];
$idno = $_POST['idno'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<fieldset><legend><B>Stop VAT</B></legend>
<div align="center">
<?php
if($chkdate!=""){
	list($n_year,$n_month,$n_day) = split('-',$chkdate);
	list($b_year,$b_month,$b_day) = split('-',$signDate);

	$date_1 = mktime(0, 0, 0, $n_month, $n_day, $n_year);
	$date_2 = mktime(0, 0, 0, $b_month, $b_day, $b_year);

	$result_date = $date_2 - $date_1;
}else{
	$result_date = 1;
}
if($result_date>0){

$in_sql="UPDATE \"Fp\" SET \"P_StopVat\"='true',\"P_StopVatDate\"='$signDate' WHERE \"IDNO\"='$idno';";
if($result=pg_query($in_sql)){

//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) STOP VAT', '$add_date')");
//ACTIONLOG---
    echo "<b>วันที่หยุด VAT คือ : $signDate</b><br><br>";
	echo "แก้ไขข้อมูลเรียบร้อยแล้ว<br><br><input type=\"button\" onclick=\"javascript:window.close();\" value=\"   ปิด   \">";
}else{
    echo "<u>ไม่</u>สามารถแก้ไขข้อมูลได้<br><input type=\"button\" onclick=\"window.location='stop_vat_date.php?idno=$idno&date=$chkdate'\" value=\"   กลับ   \">";
}

}else{
    echo "วันที่ไม่ถูกต้อง วันที่ต้องมากกว่าวันที่จ่าย VAT ล่าสุด<br><input type=\"button\" onclick=\"window.location='stop_vat_date.php?idno=$idno&date=$chkdate'\" value=\"   กลับ   \">";
}
?>

</div>
</fieldset> 


</body>
</html>