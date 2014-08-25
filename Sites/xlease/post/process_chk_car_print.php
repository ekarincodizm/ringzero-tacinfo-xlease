<?php
include("../config/config.php");
include("../nw/function/checknull.php");
pg_query("BEGIN");
$stauts = 0;

$idno = $_POST["ID"];
$typecar = $_POST["typecar"];
$user_id = $_SESSION["av_iduser"];
$datenow = date("Y-m-d H:i:s");

//ข้อมูลครั้งแรก ที่ ดึง ข้อมูล มา
$fc_color=$_POST["fc_color"];
$fc_num=$_POST["fc_num"];
$fc_mar=$_POST["fc_mar"];
$fc_mi=$_POST["fc_mi"];			
$fp_fc_type=$_POST["fp_fc_type"]; // ประเภท รถยนต์/จักรยายนต์
$fp_fc_brand=$_POST["fp_fc_brand"];//ยี่ห้อ
$fp_fc_model=$_POST["fp_fc_model"];  //รุ่น
$fp_fc_category=$_POST["fp_fc_category"]; //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
$fp_fc_newcar=$_POST["fp_fc_newcar"];//รถใหม่หรือรถใช้แล้ว	
$fp_fc_gas=$_POST["fp_fc_gas"]; //ระบบแก๊ส	
$fc_year=$_POST["fc_year"];
$fc_regis=$_POST["fc_regis"];
$fcs_regis_by=$_POST["fcs_regis_by"];


$fcs_regis_by=$_POST["fcs_regis_by"];

$qry_check_2 = pg_query("select *  from \"VCarregistemp\" where \"IDNO\" ='$idno'");
$row_chk = pg_num_rows($qry_check_2);
$res_fc = pg_fetch_array($qry_check_2);
	
$fc_color_check_2 =trim($res_fc["C_COLOR"]);
$fc_num_check_2 =trim($res_fc["C_CARNUM"]);
$fc_mar_check_2 =trim($res_fc["C_MARNUM"]);
$fc_mi_check_2 =trim($res_fc["C_Milage"]);				
$fp_fc_type_check_2  = $res_fc["fc_type"]; 
$fp_fc_brand_check_2  = $res_fc["fc_brand"]; 
$fp_fc_model_check_2  = $res_fc["fc_model"]; 
$fp_fc_category_check_2  = $res_fc["fc_category"]; 
$fp_fc_newcar_check_2  = $res_fc["fc_newcar"]; 
$fp_fc_gas_check_2  = $res_fc["fc_gas"]; 
$fc_year_check_2 =trim($res_fc["C_YEAR"]);
$fc_regis_check_2 =trim($res_fc["C_REGIS"]);
$fcs_regis_by_check_2 =trim($res_fc["C_REGIS_BY"]);
	
	
if(($fc_color_check_2!=$fc_color)or ($fc_num_check_2!=$fc_num) or ($fc_mar_check_2!=$fc_mar) or ($fc_mi_check_2!=$fc_mi)
or ($fp_fc_type_check_2!=$fp_fc_type)or ($fp_fc_brand_check_2!=$fp_fc_brand)or ($fp_fc_model!=$fp_fc_model_check_2 )
or ($fp_fc_category_check_2!=$fp_fc_category)or ($fp_fc_newcar!=$fp_fc_newcar_check_2)or ($fp_fc_gas_check_2!=$fp_fc_gas)
or ($fc_year_check_2!=$fc_year)or ($fc_regis_check_2!=$fc_regis) or ($fcs_regis_by_check_2!=$fcs_regis_by))
{
	$resultcheck="NO";
	$status++;	
}
else
{ 
	$resultcheck="YES";	
}

if($resultcheck=="YES"){
	$f_type_vehicle=checknull(trim($_POST["f_type_vehicle"]));
	$f_brand=checknull(trim($_POST["f_brand"]));
	$f_model=checknull(trim($_POST["f_model"]));
	$qrysel_model = pg_query("select \"model_name\" FROM \"thcap_asset_biz_model\" WHERE \"modelID\" = '".$_POST["f_model"]."' ");
	list($model_name)=pg_fetch_array($qrysel_model);
	$f_cartype=checknull(trim($_POST["f_cartype"]));				
	$f_caryear = checknull(trim($_POST["f_caryear"])); 
	$f_carnum = checknull(trim($_POST["f_carnum"])); 
	$f_carmar = checknull(trim($_POST["f_carmar"])); 
	$f_carregis = checknull(trim($_POST["f_carregis"])); 
	$f_pprovince = checknull(trim($_POST["f_pprovince"]));
	$f_carcolor = checknull(trim($_POST["f_carcolor"]));
	$f_useful_vehicle=checknull(trim($_POST["f_useful_vehicle"]));
	$f_status_vehicle=checknull(trim($_POST["f_status_vehicle"]));
	$f_carmi=checknull(trim($_POST["f_carmi"]));
	$gas_system=checknull(trim($_POST["gas_system"]));
	$qry_sel_brand = pg_query("select \"brand_name\" FROM \"thcap_asset_biz_brand\" WHERE \"brandID\" = '".$_POST["f_brand"]."' ");
	list($fp_band) = pg_fetch_array($qry_sel_brand);
	$fp_band=$fp_band." ".$model_name; //เก็บทั้งชื่อยี่ห้อและรุ่น
	$fp_band = checknull($fp_band);			
			

	if($typecar == 'normal')
	{

		$qry_chk=pg_query("	SELECT * FROM \"Fp\" A 
				LEFT OUTER JOIN \"Fc\" B on B.\"CarID\" = A.\"asset_id\" 
				WHERE  	A.\"IDNO\" = '$idno'
					");
		$res_fc = pg_fetch_array($qry_chk);
		$C_TAX_ExpDate = checknull($res_fc["C_TAX_ExpDate"]);
		$C_TAX_MON = checknull($res_fc["C_TAX_MON"]);
		$C_StartDate = checknull($res_fc["C_StartDate"]);
		$CarID = checknull($res_fc["CarID"]);
		$C_CAR_CC = checknull($res_fc["C_CAR_CC"]);
		$RadioID = checknull($res_fc["RadioID"]);

		$sql_in = pg_query("	INSERT INTO \"Carregis_temp\"(
							\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
							\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
							\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
							\"RadioID\", \"CarType\", fc_gas, fc_type, fc_brand, fc_model, fc_category, 
							fc_newcar,type_in_act)
								SELECT '$idno', $f_carregis, $fp_band, $f_caryear, $f_pprovince, 
								$f_carcolor, $f_carnum,$f_carmar, $f_carmi, $C_TAX_ExpDate, 
								$C_TAX_MON, $C_StartDate, $CarID, '$user_id', '$datenow',$C_CAR_CC, 
								$RadioID, $f_cartype, $gas_system, $f_type_vehicle, $f_brand, $f_model, $f_useful_vehicle, 
								$f_status_vehicle,type_in_act from \"Carregis_temp\" where \"IDNO\"='$idno' order by auto_id DESC limit 1");
		if($sql_in){}else{ $status++;}	
		
		$sql_in = pg_query("	UPDATE 	\"Fc\"
							 SET 	\"C_CARNAME\"=$fp_band, \"C_YEAR\"=$f_caryear, \"C_REGIS\"=$f_carregis, \"C_REGIS_BY\"=$f_pprovince, 
									\"C_COLOR\"=$f_carcolor, \"C_CARNUM\"=$f_carnum, \"C_MARNUM\"=$f_carmar, \"C_Milage\"= $f_carmi, \"C_TAX_ExpDate\"=$C_TAX_ExpDate, 
									\"C_TAX_MON\"=$C_TAX_MON, \"C_StartDate\"=$C_StartDate, \"RadioID\"=$RadioID, \"CarType\"=$f_cartype, \"C_CAR_CC\"=$C_CAR_CC, 
									 fc_gas=$gas_system, fc_type=$f_type_vehicle, fc_brand=$f_brand, fc_model=$f_model, fc_category=$f_useful_vehicle, fc_newcar=$f_status_vehicle
							WHERE \"CarID\"= $CarID

						");
		if($sql_in){}else{ $status++;}

	}
	else if($typecar == 'Gas')
	{
		$qry_chk=pg_query("	SELECT \"asset_id\" FROM \"Fp\" WHERE \"IDNO\" = '$idno'");
		$res_fgas = pg_fetch_array($qry_chk);
		$asset_id = $res_fgas["asset_id"];

		$sql_in = pg_query("	UPDATE 	\"FGas\"
							SET 	\"car_year\"=$f_caryear, \"car_regis\"=$f_carregis, \"car_regis_by\"=$f_pprovince, 
									\"carnum\"=$f_carnum, \"marnum\"=$f_carmar, \"fc_milage\"= $f_carmi, fc_gas=$gas_system, 
									fc_type=$f_type_vehicle, fc_brand=$f_brand, fc_model=$f_model, fc_category=$f_useful_vehicle, fc_newcar=$f_status_vehicle
							WHERE \"GasID\"= '$asset_id'

					");
		if($sql_in){}else{ $status++;}

	}else
	{
		$status++;
	}
}
else
{ 		
	$status++;	
}
if($status == 0)
{

	pg_query("COMMIT");	
	$script = '<script language="JavaScript">';
	$script .= "alert('บันทึกข้อมูล เสร็จสิ้น');";
	$script .= '</script>';
	echo $script;
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=pdf/pdf_contract.php?ID=$idno\">";		 
			
}
else
{
	pg_query("ROLLBACK");
	$script = '<script language="JavaScript">';
	$script .= "alert('ไม่สามารถบันทึกได้ กรุณาลองใหม่ในภายหลัง !');";
	$script .= '</script>';
	echo $script;
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_print.php\">";
}
?>