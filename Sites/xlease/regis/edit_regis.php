<?php
session_start();
include("../config/config.php");

$fs_idno = pg_escape_string($_POST["fidno"]);
$fs_carid = pg_escape_string($_POST["fcar_id"]);
$fs_carregis = pg_escape_string($_POST["f_carregis"]);
$fs_exp_date = pg_escape_string($_POST["f_exp_date"]);
$fs_tax_mon = pg_escape_string($_POST["f_tax_mon"]);
$fs_st_date = pg_escape_string($_POST["f_st_date"]);
$fs_st_date2 = pg_escape_string($_POST["f_st_date"]);
$assettype = pg_escape_string($_POST["assettype"]);
$assetid = pg_escape_string($_POST["assetid"]);
$fs_car_cc = pg_escape_string($_POST["f_car_cc"]);
$fs_typecar = pg_escape_string($_POST["typecar"]); //ประเภทรถตาม พ.ร.บ.
$fs_gas = pg_escape_string($_POST["gas_system"]); // ระบบแก๊สรถยนต์
$fs_g_type = pg_escape_string($_POST["g_type"]); // ประเภทแก๊ส
$keyDate = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$get_userid = $_SESSION["av_iduser"];
$dtt = explode('-',$fs_st_date);
$add_year = $dtt[0];

if($fs_car_cc==""){
	$fs_car_cc=0;
}



//ดึงข้อมูลเก่าขึ้นมาเปรียบเทียบ
$qry_fp=pg_query("select * from \"Fp\" where (\"IDNO\" ='$fs_idno') ");
$res_fp=pg_fetch_array($qry_fp);
$asset_type=$res_fp["asset_type"];
$fp_carid=trim($res_fp["asset_id"]);

//หาว่า idno ล่าสุดที่ใช้รถคันนี้คือ idno อะไร
$qrycarnow=pg_query("select \"C_REGIS\",\"IDNO\" from \"Fp\" a
left join \"Fc\" b on a.asset_id=b.\"CarID\" where \"CarID\"='$assetid' OR \"asset_id\" = '$assetid' order by \"P_STDATE\" DESC limit 1");
$rescarnow=pg_fetch_array($qrycarnow);
list($C_REGISnew,$idnonow)=$rescarnow;

if($asset_type == 1){
	$qry_car=pg_query("select \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
		\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
		\"C_TAX_MON\", \"C_StartDate\", \"CarID\",\"C_CAR_CC\",\"type_in_act\",\"fc_gas\"
		from \"Carregis_temp\" where \"IDNO\" ='$fs_idno' order by auto_id DESC limit 1 ");
	if($res_fc=pg_fetch_array($qry_car)){		
		list($fc_regis,$fc_name,$fc_year,$fc_regis_by,$fc_color,$fc_num,$fc_mar,$fc_mi,$fc_expert,$fc_mon,$fc_startdate,$fc_carid,$fc_car_cc,$fc_typecar,$fc_gas)=$res_fc;
		if($fc_car_cc==""){
			$fc_car_cc=0;
		}
	}	
}else{
	$qry_car=pg_query("select * from \"FGas\" where \"GasID\" ='$fp_carid' ");
    if($res_fc=pg_fetch_array($qry_car)){
        $fc_regis=trim($res_fc["car_regis"]);
		$gas_type=trim($res_fc["gas_type"]);
    }
	
	$qry_car=pg_query("select \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
		\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
		\"C_TAX_MON\", \"C_StartDate\", \"CarID\",\"C_CAR_CC\",\"type_in_act\",\"fc_gas\"
		from \"Carregis_temp\" where \"IDNO\" ='$idnonow' order by auto_id DESC limit 1 ");
	if($res_fc=pg_fetch_array($qry_car)){		
		list($fc_name,$fc_year,$fc_regis_by,$fc_color,$fc_num,$fc_mar,$fc_mi,$fc_expert,$fc_mon,$fc_startdate,$fc_carid,$fc_car_cc,$fc_typecar,$fc_gas)=$res_fc;
		if($fc_car_cc==""){
			$fc_car_cc=0;
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>
        
<div class="header"><h1></h1></div>

<div class="wrapper">
<div align="right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div> 
<fieldset><legend><B>แก้ไขทะเบียน</B></legend>
<div align="center">
<?php
pg_query("BEGIN WORK");
$status = 0;
//ตรวจสอบว่ามีการแก้ไขหรือไม่ถ้ามีค่อยให้จัดการข้อมูล
if($fc_regis != $fs_carregis || $fc_startdate != $fs_st_date2 || $fc_mon != $fs_tax_mon || $fc_expert != $fs_exp_date || $fc_car_cc != $fs_car_cc || $fs_typecar != $fc_typecar || $fs_gas != $fc_gas || $fs_g_type != $gas_type){
	//ตรวจสอบว่า idno ที่ทำการแก้ไขคือ idno ที่ใช้รถปัจจุบันหรือไม่
	if($idnonow==$fs_idno){ //ถ้าเท่ากันให้ update ข้อมูลใน Fc ด้วย ถ้าไม่ใช่ไม่ต้องอัพเดทใน Fc แต่ให้อัพเดทในตาราง Carregis_temp อย่างเดียว
		if($assettype == 1){
			//กรณีมีการแก้ไขทะเบียน
			if($fc_regis != $fs_carregis){	
				$fieldedit="ตาราง Fc ฟิลด์ C_REGIS";
				$ins_logs="insert into \"logs_nw_regis\" (\"IDNO\",\"asset_id\",\"fieldedit\",\"data_old\",\"data_new\",\"datekey\",\"id_user\") values
														('$fs_idno','$fs_carid','$fieldedit','$fc_regis','$fs_carregis','$keyDate','$get_userid')";	
				if($res_logs=pg_query($ins_logs)){
				}else{
					$status=$status+1;
				}													
			}
			
			//กรณีแก้ไขวันที่จดทะเบียน
			if($fc_startdate != $fs_st_date2){
				$fieldedit="ตาราง Fc ฟิลด์ C_StartDate";
				$ins_logs="insert into \"logs_nw_regis\" (\"IDNO\",\"asset_id\",\"fieldedit\",\"data_old\",\"data_new\",\"datekey\",\"id_user\") values
																   ('$fs_idno','$fs_carid','$fieldedit','$fc_startdate','$fs_st_date2','$keyDate','$get_userid')";	
				if($res_logs=pg_query($ins_logs)){
				}else{
					$status=$status+1;
				}													
			}
			
			//กรณีแก้ไขค่าภาษี
			if($fc_mon != $fs_tax_mon){
				$fieldedit="ตาราง Fc ฟิลด์ C_TAX_MON";
				$ins_logs="insert into \"logs_nw_regis\" (\"IDNO\",\"asset_id\",\"fieldedit\",\"data_old\",\"data_new\",\"datekey\",\"id_user\") values
																   ('$fs_idno','$fs_carid','$fieldedit','$fc_mon','$fs_tax_mon','$keyDate','$get_userid')";	
				if($res_logs=pg_query($ins_logs)){
				}else{
					$status=$status+1;
				}													
			}
			
			//กรณีแก้ไขวันที่ต่ออายุภาษี
			if($fc_expert != $fs_exp_date){
				$fieldedit="ตาราง Fc ฟิลด์ exp_date";
				$ins_logs="insert into \"logs_nw_regis\" (\"IDNO\",\"asset_id\",\"fieldedit\",\"data_old\",\"data_new\",\"datekey\",\"id_user\") values
																   ('$fs_idno','$fs_carid','$fieldedit','$fc_expert','$fs_exp_date','$keyDate','$get_userid')";	
				if($res_logs=pg_query($ins_logs)){
				}else{
					$status=$status+1;
				}													
			}
			
			//กรณีแก้ไข CC รถ
			if($fc_car_cc != $fs_car_cc){
				$fieldedit="ตาราง Fc ฟิลด์ C_CAR_CC";
				$ins_logs="insert into \"logs_nw_regis\" (\"IDNO\",\"asset_id\",\"fieldedit\",\"data_old\",\"data_new\",\"datekey\",\"id_user\") values
														 ('$fs_idno','$fs_carid','$fieldedit','$fc_car_cc','$fs_car_cc','$keyDate','$get_userid')";	
				if($res_logs=pg_query($ins_logs)){
				}else{
					$status=$status+1;
				}													
			}
			
			//กรณีแก้ไขประเภทรถตาม พ.ร.บ.
			if($fc_typecar != $fs_typecar){
				$fieldedit="ตาราง Fc ฟิลด์ typecar";
				$ins_logs="insert into \"logs_nw_regis\" (\"IDNO\",\"asset_id\",\"fieldedit\",\"data_old\",\"data_new\",\"datekey\",\"id_user\") values
														 ('$fs_idno','$fs_carid','$fieldedit','$fc_typecar','$fs_typecar','$keyDate','$get_userid')";	
				if($res_logs=pg_query($ins_logs)){
				}else{
					$status=$status+1;
				}													
			}
			
			//กรณีแก้ไข ระบบแก๊สรถยนต์
			if($fc_gas != $fs_gas){
				$fieldedit="ตาราง Fc ฟิลด์ fc_gas";
				$ins_logs="insert into \"logs_nw_regis\" (\"IDNO\",\"asset_id\",\"fieldedit\",\"data_old\",\"data_new\",\"datekey\",\"id_user\") values
														 ('$fs_idno','$fs_carid','$fieldedit','$fc_gas','$fs_gas','$keyDate','$get_userid')";	
				if($res_logs=pg_query($ins_logs)){
				}else{
					$status=$status+1;
				}													
			}
			
			$update_Fc="Update \"Fc\" a SET \"C_YEAR\"='$add_year',\"C_REGIS\"='$fs_carregis',\"C_TAX_ExpDate\"='$fs_exp_date',\"C_TAX_MON\"='$fs_tax_mon',\"C_StartDate\"='$fs_st_date',\"C_CAR_CC\"='$fs_car_cc',\"type_in_act\"='$fs_typecar',\"fc_gas\"='$fs_gas',
			fc_type=tableb.fc_type,fc_brand=tableb.fc_brand,fc_model=tableb.fc_model,fc_category=tableb.fc_category,fc_newcar=tableb.fc_newcar
			from(
				select fc_type,fc_brand,fc_model,fc_category,fc_newcar from \"Carregis_temp\" where \"IDNO\"='$fs_idno' order by auto_id DESC limit 1
			) as tableb
			where a.\"CarID\"='$fs_carid'";
			if($result=pg_query($update_Fc)){
			}else{
				$status=$status+1;
			}
			
			$update_ins="Update insure.\"InsureForce\" SET \"Capacity\"='$fs_car_cc' where \"CarID\"='$fs_carid' ";
			if($result=pg_query($update_ins)){
			}else{
				$status=$status+1;
			}
		}else{ //กรณีเป็น Gas
			//กรณีมีการแก้ไขทะเบียน
			if($fc_regis != $fs_carregis){
				$fieldedit="ตาราง FGas ฟิลด์ car_regis";
				$ins_logs="insert into \"logs_nw_regis\" (\"IDNO\",\"asset_id\",\"fieldedit\",\"data_old\",\"data_new\",\"datekey\",\"id_user\") values
																   ('$fs_idno','$fs_carid','$fieldedit','$fc_regis','$fs_carregis','$keyDate','$get_userid')";	
				if($res_logs=pg_query($ins_logs)){
				}else{
					$status=$status+1;
				}
			}
			
			//กรณีมีการแก้ไข ประเภทแก๊ส
			if($fs_g_type != $gas_type){
				$fieldedit="ตาราง FGas ฟิลด์ gas_type";
				$ins_logs="insert into \"logs_nw_regis\" (\"IDNO\",\"asset_id\",\"fieldedit\",\"data_old\",\"data_new\",\"datekey\",\"id_user\") values
																   ('$fs_idno','$fs_carid','$fieldedit','$gas_type','$fs_g_type','$keyDate','$get_userid')";	
				if($res_logs=pg_query($ins_logs)){
				}else{
					$status=$status+1;
				}
			}
			
			//กรณีแก้ไข ระบบแก๊สรถยนต์
			if($fc_gas != $fs_gas){
				$fieldedit="ตาราง FGas ฟิลด์ fc_gas";
				$ins_logs="insert into \"logs_nw_regis\" (\"IDNO\",\"asset_id\",\"fieldedit\",\"data_old\",\"data_new\",\"datekey\",\"id_user\") values
														 ('$fs_idno','$fs_carid','$fieldedit','$fc_gas','$fs_gas','$keyDate','$get_userid')";	
				if($res_logs=pg_query($ins_logs)){
				}else{
					$status=$status+1;
				}
			}
			
			if($fs_gas == ""){$fs_gas_chknull = "NULL";}else{$fs_gas_chknull = "'$fs_gas'";}
			
			$update_Fg="Update \"FGas\" SET \"car_regis\"='$fs_carregis', \"gas_type\" = '$fs_g_type', \"fc_gas\" = $fs_gas_chknull where \"GasID\"='$fs_carid' ";
			if($result=pg_query($update_Fg)){
			}else{
				$status=$status+1;
			}
		}


	}
	
	if($asset_type == 1){
	//insert ข้อมูลที่แก้ไขในตาราง Carregis_temp ด้วย
		$inscarregis="insert into \"Carregis_temp\" (
			\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
			\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
			\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
			\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,fc_gas,type_in_act) 
		select 
			\"IDNO\",'$fs_carregis', \"C_CARNAME\", '$add_year', \"C_REGIS_BY\",
			\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", '$fs_exp_date',
			'$fs_tax_mon','$fs_st_date', '$fs_carid', '$get_userid', '$keyDate', '$fs_car_cc', 
			\"RadioID\", \"CarType\",fc_type,fc_brand,fc_model,fc_category,fc_newcar,'$fs_gas','$fs_typecar' from \"Carregis_temp\" where \"IDNO\"='$fs_idno' order by auto_id DESC limit 1";
		/*
		$inscarregis="INSERT INTO \"Carregis_temp\"(
			\"IDNO\", \"C_REGIS\", \"C_CARNAME\", '$add_year', \"C_REGIS_BY\", 
			\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
			\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\",\"type_in_act\")
			VALUES ('$fs_idno', '$fs_carregis', '$fc_name', '$add_year', '$fc_regis_by', 
			'$fc_color', '$fc_num','$fc_mar','$fc_mi', '$fs_exp_date', 
			'$fs_tax_mon', '$fs_st_date', '$fs_carid', '$get_userid','$keyDate', '$fs_car_cc','$fs_typecar');";
		*/
		if($rescar=pg_query($inscarregis)){
		}else{
			$status++;
		}
	}
}else{
	$status=-1;
}
if($status<0){
	echo "ไม่มีการแก้ไขข้อมูล<br /><br /><input type=\"button\" name=\"fd\" id=\"fdf\" value=\"  กลับ  \" onclick=\"javascript:location='frm_regisindo.php'\">";
}else{
	if($status == 0){
	
			//ACTIONLOG
			$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$get_userid', '(TAL) แก้ไขทะเบียน (รายละเอียด)', '$keyDate')");
			//ACTIONLOG---
		pg_query("COMMIT");
		echo "บันทึกข้อมูลเรียบร้อยแล้ว<br /><br /><input type=\"button\" name=\"fd\" id=\"fdf\" value=\"  กลับ  \" onclick=\"javascript:location='frm_regisindo.php'\">";
	}else{
		pg_query("ROLLBACK");
		echo "$ins_logs<br>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง<br /><br /><input type=\"button\" name=\"fd\" id=\"fdf\" value=\"  กลับ  \" onclick=\"javascript:location='frm_regisedit.php?idno=$fs_idno'\">";
	}
}
?>
</div>
 </fieldset> 

</div>
        </td>
    </tr>
</table>          

</body>
</html>