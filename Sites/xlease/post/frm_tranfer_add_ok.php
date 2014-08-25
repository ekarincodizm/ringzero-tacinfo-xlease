<?php
session_start();
include("../config/config.php");
include("../nv_function.php");
include("../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว
$user_id = $_SESSION["av_iduser"];
$av_officeid = $_SESSION["av_officeid"];
$startKeyDate=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td>
        
<div class="header"><h1>โอนสิทธิ์ เช่าซื้อ</h1></div>
<div class="wrapper">
<?php
$fa_cusid = $_POST['fa_cusid']; //ตรวจสอบจากฐาน / กรอกใหม่
$add_pstdate = $_POST["f_pstdate"];
$add_startDate = $_POST["f_startDate"];

$edt_cusbyyear = $_POST["tranfer_cusbyyear"];
$edt_idno = $_POST["tranfer_idno"];
$edt_duenum = $_POST["tranfer_duenum"];
$edt_rdate = $_POST["tranfer_rdate"]; if(empty($edt_rdate)) $edt_rdate=nowDate();
$edt_start_duedate = $_POST["tranfer_start_duedate"];
$edt_cus_compri = $_POST["tranfer_cus_compri"];
$edt_acc_compri = $_POST["tranfer_acc_compri"];
$edt_acc_commis = $_POST["tranfer_acc_commis"];
$DateUpdate =date("Y-m-d", strtotime("+1 day",strtotime($edt_rdate)));

if( empty($edt_cus_compri) OR empty($edt_acc_compri) OR $edt_cus_compri == 0 OR $edt_acc_compri == 0 ){
    echo "<center>ยอดเงินต้นเป็นศูนย์ ไม่สามารถทำรายการได้ ให้ติดต่อผู้ดูแลระบบ<br />";
    echo '<input type="button" value="  Back  " onclick="location.href=\'frm_tranfer.php\'"></center>';
    exit;
}

pg_query("BEGIN WORK");

$status = 0;

$qry_tran_id=pg_query("select generate_tran_id('$DateUpdate','$av_officeid')");
$res_tran_id=pg_fetch_result($qry_tran_id,0);
if( empty($res_tran_id) ){
    $status++;
}else{
    echo "generate_tran_id : $res_tran_id<br />";
}

$update_fp="Update \"Fp\" SET \"P_CLDATE\"='$add_pstdate' ,\"P_ACCLOSE\"='TRUE' ,\"P_TransferIDNO\"='$res_tran_id' ,\"P_StopVatDate\"='$edt_rdate' ,\"P_StopVat\"='true' ,\"LockContact\"='false' WHERE \"IDNO\"='$edt_idno' ";
if(!$result=pg_query($update_fp)){
    $status++;
}else{
    echo "Update Fp : $result<br />";
}


if(!empty($fa_cusid)){
    $sql_select=pg_query("select A.*,B.* from  \"Fa1\" A 
    LEFT OUTER JOIN \"Fn\" B ON B.\"CusID\"=A.\"CusID\" 
    where (A.\"CusID\" = '$fa_cusid') ");
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
}else{
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
}

// ถ้ามีลูกค้าอยู่แล้วไม่ต้อง INSERT ข้อมูลลูกค้า เพิ่ม
if(empty($fa_cusid)) {

//------ ตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
	$cus_sn = GenCus();
//----------------------
	
	
	if(empty($cus_sn)){
		$status++;
	}else{
		echo "Gen CusID : $cus_sn<br />";
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
		$status++;
	}else{
		echo "Insert Fa1 : $result<br />";
	}

	if(empty($add_birthdate)) $add_birthdate = 0;
	
	
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
		$status++;
	}else{
		echo "Insert Fn : $result<br />";
	}
} else $cus_sn = $fa_cusid; // ถ้ามีลูกค้าอยู่แล้วก็ ให้ใช้ ID นั้นเลย

$qry_fp=pg_query("select * from \"Fp\" where \"IDNO\" ='$edt_idno' ");
if( $res_fp=pg_fetch_array($qry_fp) ){
    $TranIDRef2=$res_fp["TranIDRef2"];
    $P_DOWN=$res_fp["P_DOWN"];
    $P_MONTH=$res_fp["P_MONTH"];
    $P_VatOfDown=$res_fp["P_VatOfDown"];
    $P_VAT=$res_fp["P_VAT"];
    $LockContact=$res_fp["LockContact"];
    $asset_type=$res_fp["asset_type"];
    $asset_id=$res_fp["asset_id"];
    $ComeFrom=$res_fp["ComeFrom"];
}

$qry_ref1=pg_query("select \"gen_encode_ref1\"('$res_tran_id')");
$res_ref1=pg_fetch_result($qry_ref1,0);
if( empty($res_ref1) ){
    $status++;
}else{
    echo "gen_encode_ref1 : $res_ref1<br />";
}
$TranIDRef2 = nv_correct_TranIDRef2($TranIDRef2); // รัน function เพื่อแก้ปัญหาที่ TranIDRef2 มีตัว a-z,A-Z,- ติดอยู่ซึ่งผิดหลัก โดย function return ค่าเป็นตัวเลขล้วน เปลี่ยน a-z,A-Z เป็น 0 และ - เป็น 9
$ins_fp="insert into \"Fp\" (\"IDNO\",\"TranIDRef1\",\"TranIDRef2\",\"CusID\",\"P_STDATE\",\"P_DOWN\",\"P_TOTAL\",\"P_MONTH\",\"P_FDATE\",\"P_BEGIN\",\"P_BEGINX\",\"P_VatOfDown\",\"P_VAT\",\"LockContact\",asset_type,asset_id,\"ComeFrom\",\"P_CustByYear\",\"Comm\") values 
('$res_tran_id','$res_ref1','$TranIDRef2','$cus_sn','$add_pstdate','$P_DOWN','$edt_duenum','$P_MONTH','$add_startDate','$edt_cus_compri','$edt_acc_compri','$P_VatOfDown','$P_VAT','$LockContact','$asset_type','$asset_id','$ComeFrom','$edt_cusbyyear','$edt_acc_commis')";
if(!$result=pg_query($ins_fp)){
    $status++;
}else{
    echo "insert Fp : $result<br />";
}

$ins_cc="insert into \"ContactCus\" (\"IDNO\",\"CusState\",\"CusID\") values ('$res_tran_id',0,'$cus_sn')";
if(!$result=pg_query($ins_cc)){
    $status++;
}else{
    echo "insert ContactCus : $result<br />";
}

/*
$qry_acc_pay=pg_query("select \"CreateAccPayment\"('$res_tran_id')");
$res_acc_pay=pg_fetch_result($qry_acc_pay,0);
if(!$res_acc_pay){
    $status++;
}else{
    echo "CreateAccPayment : $res_acc_pay<br />";
}
*/

$qry_trn_acc=pg_query("select \"CrtTranAccPayment\"('$edt_idno','$res_tran_id')");
$res_trn_ac=pg_fetch_result($qry_trn_acc,0);
if(!$res_trn_ac){
    $status++;
}else{
    echo "CrtTranAccPayment : $res_trn_ac<br />";
}

$qry_cus_pay=pg_query("select \"CreateCusPayment\"('$res_tran_id')");
$res_cus_pay=pg_fetch_result($qry_cus_pay,0);
if(!$res_cus_pay){
    $status++;
}else{
    echo "CreateCusPayment : $res_cus_pay<br />";
}


//เพิ่มข้อมูลใน "Carregis_temp" ด้วย
$in_carregis="insert into \"Carregis_temp\" (\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
	\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
	\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
	\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act) 
select 
	'$res_tran_id',\"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\",
	\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\",
	\"C_TAX_MON\", \"C_StartDate\", '$asset_id', '$user_id', '$startKeyDate', \"C_CAR_CC\", 
	\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act from \"Carregis_temp\" where \"IDNO\"='$edt_idno' order by auto_id DESC limit 1";
	
if($result_carregis=pg_query($in_carregis)){
}else{
	$status++;
}

if($status == 0){
    //pg_query("ROLLBACK");
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) โอนสิทธิ์เช่าซื้อ', '$startKeyDate')");
		//ACTIONLOG---
	pg_query("COMMIT");
    $msg = " <h2>ทำการโอนสิทธิ์เรียบร้อยแล้ว</h2><br>ข้อความข้างต้นไม่มีผลต่อการโอนสิทธิ์แต่อย่างใด เพียงแสดงรายละเอียดการโอนสิทธิเท่านั้น !";
}else{
    pg_query("ROLLBACK");
    $msg = "ไม่สามารถทำรายการได้ กรุณาลองใหม่อีกครั้ง";
	if($error_check != "")
	{
		$msg = $msg." ".$error_check;
	}
}
?>

<div align="center">
<?php echo $msg; ?>
<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_tranfer.php'">
</div>

</div>
        </td>
    </tr>
</table>

</body>
</html>