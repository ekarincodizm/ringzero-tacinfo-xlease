<?php
session_start();
include("../config/config.php");

pg_query("BEGIN WORK");
$status = 0;

$get_id_user = $_SESSION["av_iduser"];
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$cid = pg_escape_string($_POST['cid']);
$nowdate = date("Y/m/d");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td background=><img src="../images/bg_01.jpg" height="15" width="700"></td>
    </tr>
    <tr>
        <td align="center" valign="top" background="../images/bg_02.jpg" style="background-repeat:repeat-y">

<div class="header"><h1>ระบบประกันภัย</h1></div>
<div class="wrapper">

<?php
$kind = pg_escape_string($_POST['hKind']);
$company = pg_escape_string($_POST['hCompany']);

$select_money = pg_escape_string($_POST['select_money']);
$cash_money = pg_escape_string($_POST['cash_money']);
$cheque_bank = pg_escape_string($_POST['cheque_bank']);
$cheque_number = pg_escape_string($_POST['cheque_number']);
$cheque_date = pg_escape_string($_POST['cheque_date']);
$cheque_money = pg_escape_string($_POST['cheque_money']);
$cheque_remark = pg_escape_string($_POST['cheque_remark']);
$CoPayInsID = pg_escape_string($_POST['CoPayInsID']);

if($select_money == 1){
    $in_sql="UPDATE \"insure\".\"PayToInsure\" SET \"Cash\"='$cash_money',\"idauthority\"='$get_id_user',\"Remark\"='$cheque_remark' WHERE \"PayID\"='$CoPayInsID' ";
    if(!$result=pg_query($in_sql)){
        $status++;
    }
}else{
    $in_sql="UPDATE \"insure\".\"PayToInsure\" SET \"CQBank\"='$cheque_bank',\"CQID\"='$cheque_number',\"CQDate\"='$cheque_date',\"CQAmt\"='$cheque_money',\"idauthority\"='$get_id_user',\"Remark\"='$cheque_remark' WHERE \"PayID\"='$CoPayInsID' ";
    if(!$result=pg_query($in_sql)){
        $status++;
    }
}

$in_sql3="UPDATE \"insure\".\"InsureUnforce\" SET \"CoPayInsReady\"='TRUE' WHERE \"CoPayInsID\"='$CoPayInsID' ";
if(!$result3=pg_query($in_sql3)){
    $status++;
}

if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_id_user', '(TAL) อนุมัติประกันภัยสมัครใจ', '$datelog')");
	//ACTIONLOG---
    pg_query("COMMIT");
    echo "อนุมัติเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถอนุมัติได้ กรุณาลองใหม่อีกครั้ง";
}
?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_approval_select_unforce.php'">

</div>

        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>


</body>
</html>