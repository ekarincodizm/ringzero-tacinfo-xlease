<?php
session_start();
include("../config/config.php");

pg_query("BEGIN WORK");
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$nowdate = nowDate();//ดึง วันที่จาก server
$user_id = $_SESSION["av_iduser"];

$InsFIDNO = pg_escape_string($_POST['InsFIDNO']);//
$company = pg_escape_string($_POST['company']);//
$insid = pg_escape_string($_POST['insid']);//
$insmark = pg_escape_string($_POST['insmark']);//
$code = pg_escape_string($_POST['code']);//
$date_start = pg_escape_string($_POST['date_start']);//
$date_end = pg_escape_string($_POST['date_end']);//
$discount = pg_escape_string($_POST['discount']);//
$capa = pg_escape_string($_POST['capa']);//

//===================== OLD DATA =========================//
$qry_in=pg_query("select * from insure.\"InsureForce\" WHERE (\"InsFIDNO\"='$InsFIDNO')");
if($res_in=pg_fetch_array($qry_in)){
    $o_InsID = $res_in["InsID"];
    $o_InsMark = $res_in["InsMark"];
    $o_Company = $res_in["Company"];
    $o_StartDate = $res_in["StartDate"];
    $o_EndDate = $res_in["EndDate"];
    $o_Code = $res_in["Code"];
    $o_Discount = $res_in["Discount"];
    $o_Premium = $res_in["Premium"];
    $o_capa = $res_in["Capacity"];
    
    $o_CollectCus = $res_in["CollectCus"];
    $o_NetPremium = $res_in["NetPremium"];
    $o_TaxStamp = $res_in["TaxStamp"];
    $o_Vat = $res_in["Vat"];
    
    list($st_year,$st_month,$st_day) = split('-',$o_StartDate);
    $o_StartDate = $st_year."/".$st_month."/".$st_day;

    list($ed_year,$ed_month,$ed_day) = split('-',$o_EndDate);
    $o_EndDate = $ed_year."/".$ed_month."/".$ed_day;
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
(\"id\",\"do_date\",\"marker_id\",\"type\",\"InsID\",\"Company\",\"StartDate\",\"EndDate\",\"Code\",\"Discount\",\"InsMark\",\"Capacity\",\"Premium\",\"CollectCus\",\"NetPremium\",\"TaxStamp\",\"Vat\") values 
('$InsFIDNO','$nowdate','$user_id','O','$o_InsID','$o_Company','$o_StartDate','$o_EndDate','$o_Code','$o_Discount','$o_InsMark','$o_capa','$o_Premium','$o_CollectCus','$o_NetPremium','$o_TaxStamp','$o_Vat')";
if(!$result=pg_query($sql_old)){
    $status++;
}


$sql = "INSERT INTO insure.\"batch\" 
(\"id\",\"do_date\",\"marker_id\",\"type\",\"InsID\",\"Company\",\"StartDate\",\"EndDate\",\"Code\",\"Discount\",\"InsMark\",\"Capacity\",\"Premium\",\"CollectCus\",\"NetPremium\",\"TaxStamp\",\"Vat\") values 
('$InsFIDNO','$nowdate','$user_id','N',";

if($insid != $o_InsID){
    $sql .= "'$insid',"; $update_stat++;
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
    $p_date_start = "1";
}else{
    $sql .= "DEFAULT,";
}

if("$date_end" != "$o_EndDate"){
    $sql .= "'$date_end',"; $update_stat++;
    $p_date_end = "1";
}else{
    $sql .= "DEFAULT,";
}

if($code != $o_Code){
    $sql .= "'$code',"; $update_stat++;
    $p_code = "1";
}else{
    $sql .= "DEFAULT,";
}

if($discount != $o_Discount){
    $sql .= "'$discount',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if($insmark != $o_InsMark){
    $sql .= "'$insmark',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if($capa != $o_capa){
    $sql .= "'$capa',"; $update_stat++;
}else{
    $sql .= "DEFAULT,";
}

if( $p_code!="1" AND $p_date_start!="1" AND $p_date_end!="1" AND $discount == $o_Discount){
	// จะต้องไม่มีการเปลี่ยนแปลง DISCOUNT ด้วยจึงจะใช้ค่าเดิม ถ้าไม่อย่างนั้นจะต้อง UPDATE เงินที่จะเก็บจากลูกค้าใหม่
    $sql .= "DEFAULT,";
    $sql .= "DEFAULT,";
    $sql .= "DEFAULT,";
    $sql .= "DEFAULT,";
    $sql .= "DEFAULT);";
}
else if( $p_code!="1" AND $p_date_start!="1" AND $p_date_end!="1" AND $discount != $o_Discount){
	// อัพเดทค่าประกัน และเงินที่จะเก็บจากลูกค้าใหม่
    $crif=pg_query("select insure.cal_rate_insforce('$code','$date_start','$date_end')");
    $res_crif=pg_fetch_result($crif,0);
    $res_crif = preg_replace('/[^a-z0-9,.]/i', '', $res_crif);
    $pieces = explode(",", $res_crif);
    $gpremium = $pieces[0]+$pieces[1]+$pieces[2];
    $CollectCus = $gpremium-$discount;
    $sql .= "DEFAULT,";
    $sql .= "'$CollectCus',";
    $sql .= "DEFAULT,";
    $sql .= "DEFAULT,";
    $sql .= "DEFAULT);";
} else{
    $crif=pg_query("select insure.cal_rate_insforce('$code','$date_start','$date_end')");
    $res_crif=pg_fetch_result($crif,0);
    $res_crif = preg_replace('/[^a-z0-9,.]/i', '', $res_crif);
    $pieces = explode(",", $res_crif);
    $gpremium = $pieces[0]+$pieces[1]+$pieces[2];
    $CollectCus = $gpremium-$discount;
    $sql .= "'$gpremium',";
    $sql .= "'$CollectCus',";
    $sql .= "'$pieces[0]',";
    $sql .= "'$pieces[1]',";
    $sql .= "'$pieces[2]');";
}



if( $update_stat > 0 ){
    if(!$result=pg_query($sql)){
        $status++;
    }
    
    if( $status == 0 ){
		//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขข้อมูลประกันภัย พรบ.', '$datelog')");
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
<input type="button" value="  Back  " onclick="location.href='frm_insure_force_edit.php'">

</div>

        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>