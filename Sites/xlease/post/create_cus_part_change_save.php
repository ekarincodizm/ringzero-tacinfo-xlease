<?php
include("../config/config.php");
include("../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว

$idno = $_POST['idno'];
$signdate = $_POST['signdate'];
$startdate = $_POST['startdate'];
$typecontact = $_POST['typecontact'];
$carnum = $_POST['carnum'];
$cusid = $_POST['cusid'];
$carid = $_POST['carid'];
$name = $_POST['name'];

if(empty($name) || $name == ""){
    $add_firstname = $_POST["add_firstname"];
    $add_name = $_POST["add_name"];
    $add_surname = $_POST["add_surname"];
    $add_reg = $_POST["add_reg"];
    $add_birthdate = $_POST["add_birthdate"];
    $add_pair = $_POST["add_pair"];
    $add_card = $_POST["add_card"];
    $add_address = $_POST["add_address"];
    $add_idcard = $_POST["add_idcard"];
    $add_moo = $_POST["add_moo"];
    $add_dateidcard = $_POST["add_dateidcard"];
    $add_soi = $_POST["add_soi"];
    $add_bycard = $_POST["add_bycard"];
    $add_road = $_POST["add_road"];
    $add_contactadd = $_POST["add_contactadd"];
    $add_tambon = $_POST["add_tambon"];
    $add_ampur = $_POST["add_ampur"];
    $add_province = $_POST["add_province"];
}else{
    $sql_select=pg_query("select A.*,B.* from  \"Fa1\" A 
    LEFT OUTER JOIN \"Fn\" B ON B.\"CusID\"=A.\"CusID\" 
    where (A.\"CusID\" = '$name') ");
    if($res_cn=pg_fetch_array($sql_select)){
        $add_firstname = $res_cn["A_FIRNAME"];
        $add_name = $res_cn["A_NAME"];
        $add_surname = $res_cn["A_SIRNAME"];
        $add_reg = $res_cn["N_SAN"];
        $add_birthdate = $res_cn["N_AGE"];
        $add_pair = $res_cn["A_PAIR"];
        $add_card = $res_cn["N_CARD"];
        $add_address = $res_cn["A_NO"];
        $add_idcard = $res_cn["N_IDCARD"];
        $add_moo = $res_cn["A_SUBNO"];
        $add_dateidcard = $res_cn["N_OT_DATE"];
        $add_soi = $res_cn["A_SOI"];
        $add_bycard = $res_cn["N_BY"];
        $add_road = $res_cn["A_RD"];
        $add_contactadd = $res_cn["N_ContactAdd"];
        $add_tambon = $res_cn["A_TUM"];
        $add_ampur = $res_cn["A_AUM"];
        $add_province = $res_cn["A_PRO"];
    }
}

pg_query("BEGIN WORK");

$status = 0;
$error_msg = "";

$qry_gen=@pg_query("select \"generate_id\"('$signdate',2,4)");
$newidno=@pg_fetch_result($qry_gen,0);
if( empty($newidno) ){
    $error_msg .= "generate_id : empty\n";
    $status++;
}

//------ ตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
	$cus_sn = GenCus();
//----------------------


if(empty($cus_sn)){
    $error_msg .= "gen_cusid : empty\n";
    $status++;
}

$update_fpoc="Update \"FpOutCus\" SET \"ACCloseDate\"='$signdate' ,\"AcClose\"='TRUE' ,\"TranferIDNO\"='$newidno' WHERE \"IDNO\"='$idno'";
if(!$result=pg_query($update_fpoc)){
    $error_msg .= "Update FpOutCus : error\n";
    $status++;
}

//------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
$sql_check_name = pg_query("select * from \"Fa1\" where \"A_NAME\" = '$add_name' and \"A_SIRNAME\" = '$add_surname' ");
$row_check_name = pg_num_rows($sql_check_name);
if($row_check_name > 0)
{
	$status++;
	$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
}

$in_sql="insert into \"Fa1\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\") values 
('$cus_sn','$add_firstname','$add_name','$add_surname','$add_pair','$add_address','$add_moo','$add_soi','$add_road','$add_tambon','$add_ampur','$add_province')";
if(!$result=pg_query($in_sql)){
    $error_msg .= "insert Fa1 : error\n";
    $status++;
}

//------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
	$check_card = str_replace(" ","",$add_idcard);
	$check_card = str_replace("-","",$check_card);
	$sql_check=pg_query("select \"N_IDCARD\" from \"Fn\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$check_card'");
	$row_check = pg_num_rows($sql_check);
	if($row_check > 0)
	{
		$status++;
		$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
	}
	
$in_fn="insert into \"Fn\" (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_ContactAdd\") values 
('$cus_sn','0','$add_reg','$add_birthdate','$add_card','$add_idcard','$add_dateidcard','$add_bycard','$add_contactadd')";
if(!$result=pg_query($in_fn)){
    $error_msg .= "insert Fn : error\n";
    $status++;
}

$qry_ref1=@pg_query("select \"gen_encode_ref1\"('$newidno')");
$ref1=@pg_fetch_result($qry_ref1,0);
if( empty($ref1) ){
    $error_msg .= "gen_encode_ref1 : empty\n";
    $status++;
}

$len_carnum = strlen($carnum);
$ref2 = substr($carnum, ($len_carnum-9), $len_carnum);

$in="insert into \"FpOutCus\" (\"IDNO\",\"CusID\",\"CarID\",\"OCRef1\",\"OCRef2\",\"ACStartDate\",\"SignDate\",\"TypeContact\") values 
('$newidno','$cusid','$carid','$ref1','$ref2','$startdate','$signdate','$typecontact')";
if(!$result=@pg_query($in)){
    $error_msg .= "insert FpOutCus : false\n";
    $status++;
}

if($status == 0){
    //pg_query("ROLLBACK");
    pg_query("COMMIT");
    $data['success'] = true;
    $data['message'] = "";
}else{
    pg_query("ROLLBACK");
    $data['success'] = false;
    $data['message'] = "ไม่สามารถบันทึกได้\nError Message:\n$error_msg".if($error_check != ""){echo " ".$error_check;};
}

echo json_encode($data);
?>