<?php
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

include("../../config/config.php");
$officeid=$_SESSION["av_officeid"];//
$f_search_fc=$_POST["f_search_fc"];//

include("../../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว
include("../../nw/function/checknull.php");



$g_carregis=checknull($_POST["g_regis"]);//ทะเบียน
$g_carnumber=$_POST["g_carnum"];//เลขตัวถัง
$g_carengine=$_POST["g_marnum"];//เลขเครื่องยนต์
$g_year=$_POST["g_year"];//ปีรถ
$g_pro=$_POST["g_province"];//ทะเีบียนจังหวัด

$C_Milage=checknull($_POST["C_Milage"]); //เลขไมล์
$fp_fc_type = checknull($_POST["f_type_vehicle"]); // ประเภท รถยนต์/จักรยายนต์
$fp_fc_model = checknull($_POST["f_model"]); //รุ่น
$fp_fc_category = checknull($_POST["f_useful_vehicle"]); //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
$fp_fc_newcar = checknull($_POST["f_status_vehicle"]); //รถใหม่หรือรถใช้แล้ว
$fp_fc_brand = checknull($_POST["f_brand"]); //ยี่ห้อ
$fc_gas = checknull($_POST["gas_system"]); //ระบบแก๊ส


$g_type=$_POST["g_type"];//ระบบ gas  LPG NGV

$g_tanksn=checknull($_POST["g_tanknumber"]);// เลขถังแก๊ส
$g_type = $_POST["gas_type"];
$g_band=checknull($_POST["g_name"]);//ยี่ห้อ gas
$g_signdate=$_POST["signDate"];// วันที่ซื้อ
$g_downprice=$_POST["g_down"];//
$g_total=$_POST["g_total"];//
$g_month=$_POST["g_month"];//
$g_fdate=$_POST["f_Date"];//
$g_begin=$_POST["g_begin"];//
$g_beginx=$_POST["g_beginx"];//

list($n_year,$n_month,$n_day) = split('/',$g_signdate);
$pcusbyyear = $n_year;



pg_query("BEGIN WORK");

//fc 

// chk carid
$ss_carid=$_POST["ch_fc_status"];
if($ss_carid==1)
{
 //$fp_car_val=$_POST["f_search_fc"];
 $pfinal_carid=trim($_POST["f_search_fc"]);
 
    $qry_fc=pg_query("select * from  \"Fc\" where \"CarID\" ='$pfinal_carid' ");
	$res_fc=pg_fetch_array($qry_fc);
	
	$g_carregis=checknull($res_fc["C_REGIS"]);
	$g_carnumber=$res_fc["C_CARNUM"];
	$g_carengine=$res_fc["C_MARNUM"];
	$g_pro=$res_fc["C_REGIS_BY"];
	$g_year=$res_fc["C_YEAR"];
	$fp_fc_type = checknull($res_fc["fc_type"]); // ประเภท รถยนต์/จักรยายนต์
	$fp_fc_model = checknull($res_fc["fc_model"]); //รุ่น
	$fp_fc_category = checknull($res_fc["fc_category"]); //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
	$fp_fc_newcar = checknull($res_fc["fc_newcar"]); //รถใหม่หรือรถใช้แล้ว
	$fp_fc_brand = checknull($res_fc["fc_brand"]); //ยี่ห้อ
	$C_Milage = checknull($res_fc["C_Milage"]); //ยี่ห้อ
	$fc_gas = checknull($res_fc["fc_gas"]); //ระบบแก๊ส
 
}
else 
{
	/*
$g_carengine=$_POST["f_marnum"];
$g_carnumber=$_POST["f_carnum"];
$g_carregis=$_POST["f_regis"];
*/
//$fp_band=$_POST["f_band"];
//$fp_color=$_POST["f_color"];
//$fp_radio=$_POST["f_radio"];
//save car

$pfinal_carid=$car_sn;

}




$as_type_gas=$_SESSION["session_company_asset_gas"];
$gencode="select generate_cash_id('$g_signdate',$officeid,$as_type_gas);";
$resid=pg_query($db_connect,$gencode);
$residno=pg_fetch_result($resid,0);


$qry_gcid=pg_query("select count(\"GasID\") AS gcount from \"FGas\";");
$res_glast=pg_fetch_array($qry_gcid);
$res_g=$res_glast[gcount];
if($res_g==0){
    $res_gn=1;
}else{
    $res_gn=$res_g+1;
}

function insertZero_id($inputValue,$digit){
    $str = "" . $inputValue;
    while (strlen($str) < $digit){
        $str = "0" . $str;
    }
    return $str;
}

//$ag =$res_gn;
$pre_idsn="GAS".insertZero_id($res_gn,5);

$ss_cusid=$_POST["sta_cusid"];

if($_POST["ch_status"]==1)
  {
    $pfinal_cusid=$_POST["f_search"];
    #$pfinal_cusid=substr($fp_cus_val,0,6);
  }
  else
  {
    $fs_firname=$_POST["f_firname"];
    $fp_name=$_POST["f_name"];
    $fp_sirname=$_POST["f_sirname"];
    
    $qry_fname=pg_query("select check_cus_name('$fp_name','$fp_sirname');");
    $res_fname=pg_fetch_result($qry_fname,0);
    if($res_fname==""){
        //------ ตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
			$cus_sn = GenCus();
		//----------------------
		
		//------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
		$sql_check_name = pg_query("select * from \"Fa1\" where \"A_NAME\" = '$fp_name' and \"A_SIRNAME\" = '$fp_sirname' ");
		$row_check_name = pg_num_rows($sql_check_name);
		if($row_check_name > 0)
		{
			$status++;
			$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
		}

        $in_sql="insert into \"Fa1\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\") values ('$cus_sn','$fs_firname','$fp_name','$fp_sirname');";
        if($result=pg_query($in_sql)){
            $process_status = 0;
            //echo "OK $in_sql<br>";
        }else{
            //echo "Error $in_sql<br>";
            $process_status = 1;
        }
	
        $in_fn="insert into \"Fn\" (\"CusID\",\"N_STATE\") values ('$cus_sn','0');";
        if($result=pg_query($in_fn)){
            //echo "OK $in_fn <br>";
            $process_status = 0;
        }else{
            //echo "Error $in_fn <br>";
            $process_status = 1;
        }
	 
        $pfinal_cusid=$cus_sn;

     }
	 else
	 {
         $pfinal_cusid=$res_fname;
     }
  }

$in_sql="insert into \"Fp\" (\"IDNO\",\"P_STDATE\",\"P_DOWN\",\"P_TOTAL\",\"P_MONTH\",\"P_FDATE\",\"P_BEGIN\",\"P_BEGINX\",\"P_VatOfDown\",\"P_VAT\",asset_type,asset_id,\"CusID\",\"P_CLDATE\", \"P_StopVatDate\", \"P_StopVat\" ,\"P_ACCLOSE\",\"P_CustByYear\") 		 
values ('$residno','$g_signdate','0','0','0','$g_signdate','0','0','0','0','2','$pre_idsn','$pfinal_cusid','$g_signdate','$g_signdate','FALSE','TRUE','$pcusbyyear')";
if($result=pg_query($in_sql)){
    //echo "OK $in_sql<br>";
    $process_status = 0;
}else{
    //echo "Error $in_sql<br>";
    $process_status = 1;
}


$in_gas="insert into \"FGas\" (\"GasID\",gas_name,gas_number,gas_type,car_regis,car_regis_by,car_year,carnum,marnum,\"fc_milage\",\"fc_type\", \"fc_brand\", \"fc_model\", \"fc_category\", \"fc_newcar\",\"fc_gas\") 
values ('$pre_idsn',$g_band,$g_tanksn,'$g_type',$g_carregis,'$g_pro','$g_year','$g_carnumber','$g_carengine',$C_Milage, $fp_fc_type,$fp_fc_brand ,$fp_fc_model ,$fp_fc_category ,$fp_fc_newcar,$fc_gas );";


if($result=pg_query($in_gas)){
    //echo "OK $in_gas<br>";
    $process_status = 0;
}else{
    //echo "Error $in_gas<br>";
    $process_status = 1;
}
 
$ins_cc="insert into \"ContactCus\" (\"IDNO\",\"CusState\",\"CusID\") values ('$residno',0,'$pfinal_cusid');";
if($result_cc=pg_query($ins_cc)){
    //echo "OK $ins_cc<br>";
    $process_status = 0;
}else{
    //echo "OK $ins_cc<br>";
    $process_status = 1;
}



?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title> </title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../act.css"></link>
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>
        
<fieldset><legend><B>สัญญาแก๊ส</B></legend>
<div align="center">
<br>
 <?php
if($process_status==0){
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
	if($error_check != ""){echo "<br>".$error_check;}
}
?>
<br><br>
<input type="button" value="  Back  " onclick="location.href='gas_step1.php'">
</div>

</fieldset>

<div align="center"><br><input type="button" value="กลับหน้าหลัก" onclick="location.href='../../list_menu.php'"></div>

        </td>
    </tr>
</table>

</body>
</html>