<?php
session_start();
include("../config/config.php");
include("../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว

$in_cusid = pg_escape_string($_POST["in_cusid"]);
$in_carid = pg_escape_string($_POST["in_carid"]);

function insertZero($inputValue,$digit){
    $str = "" . $inputValue;
    while (strlen($str) < $digit){
        $str = "0" . $str;
    }
    return $str;
}

if(empty($in_cusid)){   // Insert  to  Fa1 , Fn

$add_firstname = pg_escape_string($_POST["add_firstname"]);
$add_name = pg_escape_string($_POST["add_name"]);
$add_surname = pg_escape_string($_POST["add_surname"]);
$add_reg = pg_escape_string($_POST["add_reg"]);
$add_birthdate = pg_escape_string($_POST["add_birthdate"]);
$add_pair = pg_escape_string($_POST["add_pair"]);
$add_card = pg_escape_string($_POST["add_card"]);
$add_address = pg_escape_string($_POST["add_address"]);
$add_idcard = pg_escape_string($_POST["add_idcard"]);
$add_moo = pg_escape_string($_POST["add_moo"]);
$add_dateidcard = pg_escape_string($_POST["add_dateidcard"]);
$add_soi = pg_escape_string($_POST["add_soi"]);
$add_bycard = pg_escape_string($_POST["add_bycard"]);
$add_road = pg_escape_string($_POST["add_road"]);
$add_contactadd = pg_escape_string($_POST["add_contactadd"]);
$add_tambon = pg_escape_string($_POST["add_tambon"]);
$add_ampur = pg_escape_string($_POST["add_ampur"]);
$add_province = pg_escape_string($_POST["add_province"]);
$add_post = pg_escape_string($_POST["add_post"]);

//------ ตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
	$cus_sn = GenCus();
//----------------------


$in_sql="insert into \"Fa1\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\") values 
('$cus_sn','$add_firstname','$add_name','$add_surname','$add_pair','$add_address','$add_moo','$add_soi','$add_road','$add_tambon','$add_ampur','$add_province','$add_post')";
if($result=pg_query($in_sql)){
    $insert_status1 = "Insert Fa1 Success";
}else{
    $insert_status1 = "Insert Fa1 Error : ".$result;
}

if(empty($add_birthdate)) $add_birthdate = 0;

$in_fn="insert into \"Fn\" (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_ContactAdd\") values 
('$cus_sn','0','$add_reg','$add_birthdate','$add_card','$add_idcard','$add_dateidcard','$add_bycard','$add_contactadd')";
if($result=pg_query($in_fn)){
    $insert_status2 = "Insert Fn Success";
}else{
    $insert_status2 = "Insert Fn Error : ".$result;
}

}   // End  Insert  to  Fa1 , Fn

if(empty($in_cusid)){// Start  Insert  to  Fc
    
$car_name = pg_escape_string($_POST["car_name"]);
$car_year = pg_escape_string($_POST["car_year"]);
$car_num = pg_escape_string($_POST["car_num"]);
$car_marnum = pg_escape_string($_POST["car_marnum"]);
$car_regis = pg_escape_string($_POST["car_regis"]);
$car_province = pg_escape_string($_POST["car_province"]);
$car_color = pg_escape_string($_POST["car_color"]);
$car_mile = pg_escape_string($_POST["car_mile"]);
$car_taxdate = pg_escape_string($_POST["car_taxdate"]);

$qrylast=pg_query("select count(\"CarID\") AS rescount from \"Fc\"");
$reslast=pg_fetch_array($qrylast); 
$resc=$reslast[rescount];
if($resc==0){
    $res_sn=1;
}else{
    $res_sn=$resc+1;
}
$car_id = "TAX".insertZero($res_sn,5);

 $in_fn="insert into \"Fc\" (\"CarID\",\"C_CARNAME\",\"C_YEAR\",\"C_REGIS\",\"C_REGIS_BY\",\"C_COLOR\",\"C_CARNUM\",\"C_MARNUM\",\"C_Milage\",\"C_TAX_ExpDate\") values 
('$car_id','$car_name','$car_year','$car_regis','$car_province','$car_color','$car_num','$car_marnum','$car_mile','$car_taxdate')";
if($result=pg_query($in_fn)){
    $insert_status3 = "Insert Fc Success";
}else{
    $insert_status3 = "Insert Fc Error : ".$result;
}
 
   
}// End  Insert  to  Fc


$company = pg_escape_string($_POST['company']);
$code = pg_escape_string($_POST['code']);
$kind = pg_escape_string($_POST['kind']);
$date_start = pg_escape_string($_POST['date_start']); 
$invest = pg_escape_string($_POST['invest']);
$premium = pg_escape_string($_POST['premium']);
$discount = pg_escape_string($_POST['discount']);
$collectcus = pg_escape_string($_POST['collectcus']);
$tempinsid = pg_escape_string($_POST['tempinsid']);
$insuser = pg_escape_string($_POST['insuser']);
$nowdate = date("Y/m/d");

$oins=pg_query("select \"insure\".gen_co_insid('$nowdate',1,2)");
$gen_insid=pg_fetch_result($oins,0);

if(empty($in_cusid)){
    $get_cusid = $cus_sn;
}else{
    $get_cusid = $in_cusid;
}

if(empty($in_carid)){
    $get_carid = $car_id;
}else{
    $get_carid = $in_carid;
}

$in_sql = "insert into \"insure\".\"InsureUnforce\" (\"InsUFIDNO\",\"CusID\",\"CarID\",\"TempInsID\",\"Company\",\"StartDate\",\"Code\",\"Kind\",\"Invest\",\"Premium\",\"ConfirmDate\",\"Discount\",\"CollectCus\",\"InsUser\") values  ('$gen_insid','$get_cusid','$get_carid','$tempinsid','$company','$date_start','$code','$kind','$invest','$premium','$nowdate','$discount','$collectcus','$insuser')";
if($result=pg_query($in_sql)){
    $insert_status4 = "Insert InsureUnForce Success";
}else{
    $insert_status4 = "Insert InsureUnForce Error : ".$result;
}

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
echo "$insert_status1 <br>";
echo "$insert_status2 <br>";
echo "$insert_status3 <br>";
echo "$insert_status4";
?>
<br>
</div>
<div align="center"><br><input name="button" type="button" onclick="window.location='frm_insure_unforce_outside.php'" value=" กลับ " /></div>
        </td>
    </tr>
    <tr>
        <td><img src="../images/bg_03.jpg" width="700" height="15"></td>
    </tr>
</table>

</body>
</html>