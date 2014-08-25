<?php
session_start();
include("../config/config.php");

pg_query("BEGIN WORK");
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$nowdate = nowDate();//ดึง วันที่จาก server
$user_id = $_SESSION["av_iduser"];

$InsUFIDNO = pg_escape_string($_POST['InsUFIDNO']);
$company = pg_escape_string($_POST['company']);//
$code = pg_escape_string($_POST['code']);//
$kind = pg_escape_string($_POST['kind']);//
$date_start = pg_escape_string($_POST['date_start']);//
$date_end = pg_escape_string($_POST['date_end']);//
$invest = pg_escape_string($_POST['invest']);//
$premium = pg_escape_string($_POST['premium']);//
$discount = pg_escape_string($_POST['discount']);//
$collectcus = pg_escape_string($_POST['collectcus']);//
$tempinsid = pg_escape_string($_POST['tempinsid']);//
$insuser = pg_escape_string($_POST['insuser']);//

 //===================== OLD DATA =========================//
$qry_in=pg_query("select * from insure.\"InsureUnforce\" WHERE (\"InsUFIDNO\"='$InsUFIDNO')");
if($res_in=pg_fetch_array($qry_in)){
    $o_TempInsID = $res_in["TempInsID"];//
    $o_Company = $res_in["Company"];//
    $o_StartDate = $res_in["StartDate"];//
    $o_EndDate = $res_in["EndDate"];//
    $o_Code = $res_in["Code"];//
    $o_Kind = $res_in["Kind"];//
    $o_Invest = $res_in["Invest"];//
    $o_Premium = $res_in["Premium"];//
    $o_Discount = $res_in["Discount"];//
    $o_CollectCus = $res_in["CollectCus"];//
    $o_InsUser = $res_in["InsUser"];//
    /*
    list($st_year,$st_month,$st_day) = split('-',$o_StartDate);
    $o_StartDate = $st_year."/".$st_month."/".$st_day;

    list($ed_year,$ed_month,$ed_day) = split('-',$o_EndDate);
    $o_EndDate = $ed_year."/".$ed_month."/".$ed_day;*/
}
//==================== END OLD DATA =======================//

$update_stat = 0;
$status = 0;
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
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
$sql_old = "INSERT INTO insure.\"batch\" 
(\"id\",\"do_date\",\"marker_id\",\"type\",\"InsID\",\"Company\",\"StartDate\",\"EndDate\",\"Code\",\"Kind\",\"Invest\",\"Premium\",\"Discount\",\"CollectCus\",\"InsUser\") values 
('$InsUFIDNO','$nowdate','$user_id','O','$o_TempInsID','$o_Company','$o_StartDate','$o_EndDate','$o_Code','$o_Kind','$o_Invest','$o_Premium','$o_Discount','$o_CollectCus','$o_InsUser')";
if(!$result=pg_query($sql_old)){
    $status++;
}

$sql = "INSERT INTO insure.\"batch\" 
(\"id\",\"do_date\",\"marker_id\",\"type\",\"InsID\",\"Company\",\"StartDate\",\"EndDate\",\"Code\",\"Kind\",\"Invest\",\"Premium\",\"Discount\",\"CollectCus\",\"InsUser\") values 
('$InsUFIDNO','$nowdate','$user_id','N',";

if($tempinsid != $o_TempInsID){
    $sql .= "'$tempinsid',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if($company != $o_Company){
    $sql .= "'$company',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if("$date_start" != "$o_StartDate"){
    $sql .= "'$date_start',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if("$date_end" != "$o_EndDate"){
    $sql .= "'$date_end',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if($code != $o_Code){
    $sql .= "'$code',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if($kind != $o_Kind){
    $sql .= "'$kind',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if($invest != $o_Invest){
    $sql .= "'$invest',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if($premium != $o_Premium){
    $sql .= "'$premium',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if($discount != $o_Discount){
    $sql .= "'$discount',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if($collectcus != $o_CollectCus){
    $sql .= "'$collectcus',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if($insuser != $o_InsUser){
    $sql .= "'$insuser');"; $update_stat++;
}else{
    $sql .= "DEFAULT);";
}

if( $update_stat > 0 ){
    if(!$result=pg_query($sql)){
        $status++;
    }
    
    if( $status == 0 ){
			//ACTIONLOG
				$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขข้อมูลประกันภัยสมัครใจ', '$datelog')");
			//ACTIONLOG---
        pg_query("COMMIT");
        echo "ส่งคำขอเพื่อแก้ไขข้อมูลเรียบร้อยแล้ว";
    }else{
        pg_query("ROLLBACK");
        echo "<u>ไม่</u>สามารถส่งคำขอเพื่อแก้ไขข้อมูลได้";
    }
}else{
    pg_query("ROLLBACK");
    echo "ไม่พบข้อมูลที่เปลี่ยนแปลง กรุณาตรวจสอบอีกครั้ง";
}
?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_insure_unforce_edit.php'">

</div>

        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>