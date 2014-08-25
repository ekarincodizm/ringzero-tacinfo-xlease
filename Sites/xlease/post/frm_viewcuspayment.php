<?php
Ob_start();
session_start();
include("../config/config.php");  

// ประกาศตัวแปรต่างๆ
$asset_type = -1;

// รับค่ามาจากภายนอก
$sedt_idno=trim(pg_escape_string($_REQUEST["idno_names"]));

if($sedt_idno==""){$sedt_idno=trim(pg_escape_string($_REQUEST["idno_names2"]));}
// ตัด String เฉพาะ IDNO ไปใช้ และ asset_id
$edt_idno=substr($sedt_idno,0,11);
$edt_assetid = substr($sedt_idno,-8);


// ตรวจสอบว่าเป็นสัญญาระบบใหม่หรือไม่ เช่น 12-010001T-BK01 จะมี ขีด (-)อยู่ที่ [2] กับ [10] ถ้ามีแสดงว่าเป็นสัญญาแบบใหม่ ให้เอาเลข 15 หลัก => 0 - 15
if(substr($sedt_idno, 2,1) == "-" &&  substr($sedt_idno, 10,1) == "-")
	$edt_idno=substr($sedt_idno,0,15);

//$_SESSION["ses_idno"]=$edt_idno;

if(empty($edt_idno)){
    $edt_idnostr = pg_escape_string($_REQUEST["idno"]); //ค่า idno ที่ได้มาเบื้องต้นจะมีข้อความติดมาด้วย
	if($edt_idnostr==""){$edt_idnostr=pg_escape_string($_REQUEST["idno2"]);}
	//นำ idno ที่ได้มาตัดเอาเฉพาะ idno
	$edt_idno2=explode(":",$edt_idnostr);
	$edt_idno=trim($edt_idno2[0]);
	$edt_assetid = substr($edt_idnostr,-8);
}
$_SESSION["ses_idno"]=$edt_idno;
$caridpg=pg_escape_string($_GET["carid"]);
if(!empty($caridpg)){
	$_SESSION['carid'] = $caridpg;
} 

if(empty($edt_idno)){
    header("Location: frm_cuspayment.php");
}

/*
if( substr($sedt_idno,0,2) == "00" ){
    header ("Content-type: text/html; charset=utf-8");
    echo "<div style=\"margin: 0 auto; padding-top: 20px; text-align:center; font-size:14px\">";
    echo "ลูกค้านอกไม่สามารถใช้ตารางการชำระเงินได้<br /><input type=\"button\" name=\"btn_back\" id=\"btn_back\" value=\"  Back  \" onclick=\"javascript:location='frm_cuspayment.php'\">";
    echo "</div>";
    exit;    
}
*/

$_SESSION["logs_any_id_user"] = $_SESSION["av_iduser"];
$_SESSION["logs_any_time_open"] = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$qry_name=pg_query("select \"asset_type\" from \"UNContact\" WHERE \"IDNO\" = '$edt_idno'");
if($res_name=pg_fetch_array($qry_name)){
    $asset_type=$res_name["asset_type"];
}

if($asset_type == 1 OR $asset_type == 2){
    $menu = pg_escape_string($_GET['menu']);
    header("Location: frm_cal_cuspayment.php?menu=$menu");
}elseif($asset_type == 0){
    header("Location: ex_outcus.php?idno=$edt_idno");
}elseif($asset_type == 3){
    header("Location: ex_vcorpdetail.php?idno=$edt_idno");
}else{
    echo "asset_type error !";
}

?>