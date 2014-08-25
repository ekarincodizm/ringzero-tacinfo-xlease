<?php
session_start();
include("../config/config.php");
include("../nv_function.php");
include("../nw/function/checknull.php");
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
//begin //
pg_query("BEGIN");
$status=0;

$datenow=date("Y-m-d");

$userid=$_SESSION['uid'];

$fs_idno = pg_escape_string($_POST["fidno"]);
$fs_cusid = pg_escape_string($_POST["fcus_id"]);
$fs_carid = pg_escape_string($_POST["fcar_id"]);
$addidno = pg_escape_string($_POST["addidno"]);
$add_user = $_SESSION["av_iduser"];
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$fs_occ = pg_escape_string($_POST["f_occ"]);

$fs_firname = pg_escape_string($_POST["f_fri_name"]);
$fs_name = pg_escape_string($_POST["f_name"]);
$fs_surname = pg_escape_string($_POST["f_surname"]);
$contactnote = pg_escape_string($_POST["contactnote"]);
$creditID = pg_escape_string($_POST["creditID"]);

//ตรวจสอบว่ามีคำนำหน้าที่คีย์หรือยังถ้ายังก็ให้บันทึกด้วย
$qrytitle=pg_query("select * from \"nw_title\" where \"titleName\"='$fs_firname'");
$numtitle=pg_num_rows($qrytitle);
if($numtitle==0){ //กรณีไม่พบข้อมูลให้บันทึกด้วย
	$intitle="INSERT INTO nw_title(\"titleName\") VALUES ('$fs_firname')";
	if($restitle=pg_query($intitle)){		
	}else{
		$status++;
	}
}


// fullName // 
$textName=$fs_firname." ".$fs_name."  ".$fs_surname;

$fs_pair = pg_escape_string($_POST["f_pair"]);
$fs_no = pg_escape_string($_POST["f_no"]);
$fs_subno = pg_escape_string($_POST["f_subno"]); if($fs_subno==""){ $fs_subno2="null";}else{ $fs_subno2="'".$fs_subno."'";}
$fs_soi = pg_escape_string($_POST["f_soi"]); if($fs_soi==""){ $fs_soi2="null";}else{ $fs_soi2="'".$fs_soi."'";}
$fs_rd = pg_escape_string($_POST["f_rd"]); if($fs_rd==""){ $fs_rd2="null";}else{ $fs_rd2="'".$fs_rd."'";}
$fs_aum = pg_escape_string($_POST["f_aum"]);
$fs_tum = pg_escape_string($_POST["f_tum"]);
$fs_province = pg_escape_string($_POST["f_province"]);
$fs_post = pg_escape_string($_POST["f_post"]);
$fs_birthday = checknull(trim(pg_escape_string($_POST["f_brithday"])));

$A_SEX2 = trim(pg_escape_string($_POST["A_SEX"])); //เพศ
$A_SEX = checknull($A_SEX2); //ตรวจสอบว่าเลขบัตรอื่นๆเป็น null หรือไม่
$A_ROOM = checknull(trim(pg_escape_string($_POST["A_ROOM"]))); //ห้อง
$A_FLOOR = checknull(trim(pg_escape_string($_POST["A_FLOOR"]))); //ชั้น
$A_BUILDING = checknull(trim(pg_escape_string($_POST["A_BUILDING"]))); //อาคาร/สถานที่
$A_VILLAGE = checknull(trim(pg_escape_string($_POST["A_VILLAGE"]))); //หมู่บ้าน

$fs_fri_name_eng = trim(pg_escape_string($_POST["f_fri_name_eng"]));
$fs_name_eng = trim(pg_escape_string($_POST["f_name_eng"]));
$fs_surname_eng = trim(pg_escape_string($_POST["f_surname_eng"]));
$fs_nickname = trim(pg_escape_string($_POST["f_nickname"])); 
$fs_status = trim(pg_escape_string($_POST["f_status"])); //สถานะสมรส
$fs_revenue = trim(pg_escape_string($_POST["f_revenue"])); if($fs_revenue==""){ $fs_revenue2="null"; }else{ $fs_revenue2="'".$fs_revenue."'"; }//รายได้ต่อเดือน
$fs_education = trim(pg_escape_string($_POST["f_education"])); if($fs_education==""){ $fs_education2="null"; }else{ $fs_education2="'".$fs_education."'"; }//ระดับการศึกษา
$fs_country = trim(pg_escape_string($_POST["f_country"])); //ประเทศ
$fs_mobile = trim(pg_escape_string($_POST["f_mobile"])); //โทรศัพท์มือถือ
$fs_telephone = trim(pg_escape_string($_POST["f_telephone"])); //โทรศัพท์บ้าน
$fs_email = trim(pg_escape_string($_POST["f_email"])); //email

$fs_caryear = pg_escape_string($_POST["f_caryear"]);
$fs_carnum = pg_escape_string($_POST["f_carnum"]);
$fs_carmar = pg_escape_string($_POST["f_carmar"]);
$fs_carregis = checknull(pg_escape_string($_POST["f_carregis"]));
$fs_carregis_by = checknull(pg_escape_string($_POST["f_pprovince"]));
$fs_carcolor = pg_escape_string($_POST["f_carcolor"]);
$fs_carmi = checknull(pg_escape_string($_POST["f_carmi"]));
$fs_exp_date = pg_escape_string($_POST["f_exp_date"]);
$fs_radio = checknull(pg_escape_string($_POST["f_carradio"]));

$fs_cartype = checknull(pg_escape_string($_POST["f_cartype"]));

$fp_fc_type = checknull(pg_escape_string($_POST["f_type_vehicle"])); // ประเภท รถยนต์/จักรยายนต์
$fp_fc_model = checknull(pg_escape_string($_POST["f_model"])); //รุ่น
$qrysel_model = pg_query("select \"model_name\" FROM \"thcap_asset_biz_model\" WHERE \"modelID\" = '".$_POST["f_model"]."' ");
list($model_name) = pg_fetch_array($qrysel_model);
$fp_fc_category = checknull(pg_escape_string($_POST["f_useful_vehicle"])); //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
$fp_fc_newcar = checknull(pg_escape_string($_POST["f_status_vehicle"])); //รถใหม่หรือรถใช้แล้ว
$qry_sel_brand = pg_query("select \"brand_name\" FROM \"thcap_asset_biz_brand\" WHERE \"brandID\" = '".$_POST["f_brand"]."' ");
list($fs_carname) = pg_fetch_array($qry_sel_brand);
$fs_carname = $fs_carname." ".$model_name; //เก็บทั้งชื่อยี่ห้อและรุ่น
$fp_fc_brand = checknull(pg_escape_string($_POST["f_brand"])); //ยี่ห้อ
$fp_fc_gas = checknull(pg_escape_string($_POST["gas_system"])); //ระบบแก๊สรถยนต์

$fs_letter = pg_escape_string($_POST["f_letter"]);

$fs_pstdate = pg_escape_string($_POST["f_pstdate"]);
$fs_pmonths = pg_escape_string($_POST["f_pmonth"]);

$amtvat_months = pg_query("select amt_before_vat($fs_pmonths)");
$fss_pmonth = pg_fetch_result($amtvat_months,0);

$fs_pmonth_l = $fs_pmonths-$fss_pmonth;


$fs_pvat = pg_escape_string($_POST["f_pvat"]);
$c_pvat = pg_escape_string($_POST["ch_fpvat"]);

if($fs_pvat != $c_pvat)
{

   $fs_pmonth=$fs_pvat;
}
else
{ 
    $fs_pmonth=$fs_pmonth_l;
}

$fs_pmonth;

$fs_ptotal = pg_escape_string($_POST["f_ptotal"]);

$fs_pdowns = pg_escape_string($_POST["f_pdown"]);
$fs_vatofdown = pg_escape_string($_POST["f_vatofdown"]);

$amtvat_down = pg_query("select amt_before_vat($fs_pdowns)");
$fss_down = pg_fetch_result($amtvat_down,0);

$fs_pdown_l = $fs_pdowns-$fss_down;

$ch_vdown = pg_escape_string($_POST["ch_dvat"]);
if($fs_vatofdown != $ch_vdown)
{

   $fs_pdown=$fs_vatofdown;
}
else
{ 
    $fs_pdown=$fs_pdown_l;
}

$fs_pbegin = pg_escape_string($_POST["f_pbegin"]);
$fs_pbeginx = pg_escape_string($_POST["f_pbeginx"]);
$fs_startDate = pg_escape_string($_POST["f_startDate"]);

$ms_cusyear=substr(pg_escape_string($_POST["f_pstdate"]),0,4);

echo $fs_year_acc=$ms_cusyear;
echo "<p>";
     
	$gen_ref1=pg_query("select gen_encode_ref1('$fs_idno')");
	$res_gen1=pg_fetch_result($gen_ref1,0);
	   

    $resstnumber=strlen($fs_carnum);         
	$var_cnumber=substr($fs_carnum,$resstnumber-9,9);


$var_cnumber = nv_correct_TranIDRef2($var_cnumber); // รัน function เพื่อแก้ปัญหาที่ TranIDRef2 มีตัว a-z,A-Z,- ติดอยู่ซึ่งผิดหลัก โดย function return ค่าเป็นตัวเลขล้วน เปลี่ยน a-z,A-Z เป็น 0 และ - เป็น 9
$check = $_POST['package']; //ตัวเลือกว่า ระบุเองหรือ package



if($check == 2){ //ระบุเอง

$interest = trim(pg_escape_string($_POST['interestmanual'])); //ดอกเบี้ย
if($interest == ""){ $interest = '0';}	
	$update_fp="Update \"Fp\" SET 
					\"P_STDATE\"='$fs_pstdate',
					\"P_MONTH\"='$fss_pmonth',
					\"P_VAT\"='$fs_pmonth',
					\"P_TOTAL\"='$fs_ptotal',
					\"P_DOWN\"='$fss_down',
					\"P_VatOfDown\"='$fs_pdown',
					\"P_BEGIN\"='$fs_pbegin',
					\"P_FDATE\"='$fs_startDate',
					\"P_CustByYear\"=$fs_year_acc,
					\"TranIDRef1\"='$res_gen1',
					\"TranIDRef2\"='$var_cnumber',
					\"creditType\"='$creditID'
					WHERE \"IDNO\"='$fs_idno' ";

	if($result=pg_query($update_fp)){	
	}else{
		$st="Error At ".$result;
		$status++;
	}
	
	$ins_fp_interest="update \"Fp_interest\" SET								
								\"interest\" = '$interest',
								\"fpackID\" = null		
								where \"IDNO\" = '$fs_idno' ";
								
					if($result_fp=pg_query($ins_fp_interest)){
					}else{
						$status++;
						
					}
	
}else if($check == 1){ //ตามแพ็คเกจ

$interest = trim(pg_escape_string($_POST['interest1'])); //ดอกเบี้ย
if($interest == "" || empty($interest)){ 
	$interest = 0;
}
	
$fp_down_payment1 = 0;
$fp_down_payment = pg_escape_string($_POST['down_list1']);
$fp_month_payment = pg_escape_string($_POST['time_list']);
$fp_period_payment = pg_escape_string($_POST['period_list']);
$fp_begin_payment = pg_escape_string($_POST['capital']);
$numtest = pg_escape_string($_POST['car_gen1']);

$vat_down=pg_query("select amt_before_vat($fp_down_payment1)");
$vatofdown=pg_fetch_result($vat_down,0);
$vatdown=$fp_down_payment1-$vatofdown;

$vat_month=pg_query("select amt_before_vat($fp_period_payment)");
$vatofmonth=pg_fetch_result($vat_month,0);
$vatmonth=$fp_period_payment-$vatofmonth;

	$update_fp="Update \"Fp\" SET 
						\"P_STDATE\"='$fs_pstdate',
						\"P_MONTH\"='$vatofmonth',
						\"P_VAT\"='$vatmonth',
						\"P_TOTAL\"='$fp_month_payment',
						\"P_DOWN\"='$vatofdown',
						\"P_VatOfDown\"='$vatdown',
						\"P_BEGIN\"='$fp_begin_payment',
						\"P_FDATE\"='$fs_startDate',
						\"P_CustByYear\"=$fs_year_acc,
						\"TranIDRef1\"='$res_gen1',
						\"TranIDRef2\"='$var_cnumber',
						\"creditType\"='$creditID'
						WHERE \"IDNO\"='$fs_idno' ";

		if($result=pg_query($update_fp)){	
		}else{
			$st="Error At ".$result;
			$status++;
		}
		
			$package="select * from \"Fp_package\" where \"numtest\" = '$numtest' AND \"down_payment\" = '$fp_down_payment' AND \"period\" = '$fp_period_payment' AND \"month_payment\" = '$fp_month_payment'";
			$sqlpackage=pg_query($package);	
			$result_package = pg_fetch_Array($sqlpackage);
			$fpackID = 	$result_package['fpackID'];
			
			
							
	$ins_fp_interest="update \"Fp_interest\" SET								
								\"interest\" = '$interest',
								\"fpackID\" = '$fpackID'
								where \"IDNO\" = '$fs_idno' ";
								
					if($result_fp=pg_query($ins_fp_interest)){
					}else{
						$status++;
						
					}
		
		

}
//ตรวจสอบก่อนว่าเลขที่สัญญานี้เป็นเลขที่สัญญาที่ใช้รถปัจจุบันหรือไม่ถ้าใช่ให้ update
$qrycarnow=pg_query("select \"C_REGIS\",\"IDNO\" from \"Fp\" a
left join \"Fc\" b on a.asset_id=b.\"CarID\" where \"CarID\"='$fs_carid' order by \"P_STDATE\" DESC limit 1");
$rescarnow=pg_fetch_array($qrycarnow);
list($C_REGISnow,$idnonow)=$rescarnow;

if($idnonow==$fs_idno){
	$update_Fc="Update \"Fc\" SET 
					\"C_CARNAME\"='$fs_carname' ,
					\"C_YEAR\"='$fs_caryear' ,
					\"C_CARNUM\"='$fs_carnum' ,
					\"C_MARNUM\"='$fs_carmar' ,
					\"C_REGIS\"=$fs_carregis,
					\"C_REGIS_BY\"=$fs_carregis_by,
					\"C_COLOR\"='$fs_carcolor',
					\"C_Milage\"=$fs_carmi,
					\"C_TAX_ExpDate\"='$fs_exp_date',
					\"RadioID\"=$fs_radio,
					\"CarType\"=$fs_cartype,
					\"fc_type\" = $fp_fc_type, 
					\"fc_brand\" = $fp_fc_brand,
					\"fc_model\" = $fp_fc_model, 
					\"fc_category\" = $fp_fc_category, 
					\"fc_newcar\" = $fp_fc_newcar,
					\"fc_gas\" = $fp_fc_gas
					WHERE \"CarID\"='$fs_carid' ";
	if($result=pg_query($update_Fc)){
	}else{
		$st2="Error At ".$result.$update_Fc;
		$status++;
	}
}

//insert ประวัติในตาราง Carregis_temp
//หาค่าเดิมที่ไม่ได้มีการ update ออกมาเพื่อนำมา insert
$qrycar=pg_query("select \"C_CAR_CC\",\"C_TAX_MON\", \"C_StartDate\" from \"VCarregistemp\" where \"IDNO\"='$fs_idno'"); 
$rescar=pg_fetch_array($qrycar);
list($C_CAR_CC,$C_TAX_MON, $C_StartDate)=$rescar;

if($C_CAR_CC==""){ $C_CAR_CC="null";}else{ $C_CAR_CC="'".$C_CAR_CC."'"; }
if($C_StartDate==""){ $C_StartDate="null";}else{ $C_StartDate="'".$C_StartDate."'"; }
if($C_TAX_MON==""){ $C_TAX_MON="null";}else{ $C_TAX_MON="'".$C_TAX_MON."'"; }


$in_carregis="insert into \"Carregis_temp\" (\"IDNO\", \"C_REGIS\", \"C_CARNAME\", \"C_YEAR\", \"C_REGIS_BY\", 
	\"C_COLOR\", \"C_CARNUM\", \"C_MARNUM\", \"C_Milage\", \"C_TAX_ExpDate\", 
	\"C_TAX_MON\", \"C_StartDate\", \"CarID\", \"keyUser\", \"keyStamp\", \"C_CAR_CC\", 
	\"RadioID\", \"CarType\",\"fc_type\", \"fc_brand\", \"fc_model\", \"fc_category\", \"fc_newcar\", \"fc_gas\",type_in_act) 
select '$fs_idno',$fs_carregis,'$fs_carname','$fs_caryear',$fs_carregis_by,
	'$fs_carcolor','$fs_carnum','$fs_carmar',$fs_carmi,'$fs_exp_date',
	$C_TAX_MON,$C_StartDate,'$fs_carid','$add_user','$add_date',$C_CAR_CC,
	$fs_radio,$fs_cartype,$fp_fc_type, $fp_fc_brand, $fp_fc_model, $fp_fc_category, $fp_fc_newcar,$fp_fc_gas,type_in_act 
	from \"Carregis_temp\" where \"IDNO\"='$fs_idno' ORDER BY \"auto_id\" DESC LIMIT 1";

	if($result_carregis=pg_query($in_carregis)){
}else{
	$status++;
}

$query_contactnote = pg_query("select * from \"Fp_Note\" where \"IDNO\" = '$fs_idno'");
$num_contact = pg_num_rows($query_contactnote);
if($num_contact == 0){
	$ins_con="insert into \"Fp_Note\" (\"IDNO\",\"ContactNote\") values ('$fs_idno','$contactnote')";
	if($result=pg_query($ins_con)){ 
	}else{
		$st3="Error At ".$result;
		$status++;
	} 
}else{
	$update_contact = "update \"Fp_Note\" set \"ContactNote\" = '$contactnote' where \"IDNO\" = '$fs_idno'";
	if($result=pg_query($update_contact)){
	}else{
	 $st4="Error At ".$result;
	 $status++;
	} 
}

$fs_san = pg_escape_string($_POST["f_san"]);
$fs_age = pg_escape_string($_POST["f_age"]);
$fs_card = pg_escape_string($_POST["f_card"]);
$fs_cardid = pg_escape_string($_POST["f_cardid"]);
$fs_datecard = pg_escape_string($_POST["f_datecard"]);


$update_Fa1="Update \"Fa1\" SET
				\"A_FIRNAME\"='$fs_firname',
				\"A_NAME\"='$fs_name',
				\"A_SIRNAME\"='$fs_surname',
				\"A_PAIR\"='$fs_pair',
				\"A_NO\"='$fs_no',
				\"A_SUBNO\"='$fs_subno',
				\"A_SOI\"='$fs_soi',
				\"A_RD\"='$fs_rd',
				\"A_TUM\"='$fs_tum',
				\"A_AUM\"='$fs_aum' ,
				\"A_PRO\"='$fs_province',
				\"A_POST\"='$fs_post',
				\"A_FIRNAME_ENG\"='$fs_fri_name_eng',
				\"A_NAME_ENG\"='$fs_name_eng',
				\"A_SIRNAME_ENG\"='$fs_surname_eng',
				\"A_NICKNAME\"='$fs_nickname',
				\"A_STATUS\"='$fs_status',
				\"A_REVENUE\"=$fs_revenue2,
				\"A_EDUCATION\"=$fs_education2,
				\"addr_country\"='$fs_country',
				\"A_MOBILE\"='$fs_mobile',
				\"A_TELEPHONE\"='$fs_telephone',
				\"A_EMAIL\"='$fs_email',
				\"A_BIRTHDAY\"=$fs_birthday,
				\"A_SEX\"=$A_SEX,
				\"A_ROOM\"=$A_ROOM,
				\"A_FLOOR\"=$A_FLOOR,
				\"A_BUILDING\"=$A_BUILDING,
				\"A_VILLAGE\"=$A_VILLAGE
			where \"CusID\"='$fs_cusid' ";
			
/*			
$update_Fa1="Update \"Fa1\" SET \"A_FIRNAME\"='$fs_firname', \"A_NAME\"='$fs_name' ,\"A_SIRNAME\"='$fs_surname' ,\"A_PAIR\"='$fs_pair' ,\"A_NO\"='$fs_no' ,
\"A_SUBNO\"=$fs_subno2, \"A_SOI\"=$fs_soi2,\"A_RD\"=$fs_rd2,\"A_TUM\"='$fs_tum',\"A_AUM\"='$fs_aum' ,\"A_PRO\"='$fs_province',\"A_POST\"='$fs_post'
where \"CusID\"='$fs_cusid' ";
*/
if($result=pg_query($update_Fa1)){
}else{
	$st5="Error At ".$result;
	$status++;
}

// save Fn 
$fs_san = pg_escape_string($_POST["f_san"]);
$fs_age = pg_escape_string($_POST["f_age"]);
$fs_card = pg_escape_string($_POST["f_card"]);
$fs_cardid = pg_escape_string($_POST["f_cardid"]);
$fs_datecard = pg_escape_string($_POST["f_datecard"]);
$fs_cardby = pg_escape_string($_POST["f_card_by"]);
$fh_adds = pg_escape_string($_POST["fh_adds"]);
$fs_statuscus = pg_escape_string($_POST["statuscus"]);  //สถานะลูกค้า 0=คนไทย 1= ชาวต่างชาติ 2=บริษัท
if($fs_statuscus!=0){
	$fs_cardid="";
}


$fs_stat_add = pg_escape_string($_POST["f_extadd"]);

if($fs_stat_add==2)
{
 $fs_ext = pg_escape_string($_POST["f_ext"]);
 $fs_conadd=$fs_ext;
}else if($fs_stat_add==0){
	$fs_conadd = $fh_adds;
}else
{
$fs_conadd=trim($fs_no)." ".trim($fs_subno)." ".trim($fs_soi)." ".trim($fs_rd)." ".trim($fs_tum)." ".trim($fs_aum)." ".trim($fs_province)." ".trim($fs_post);
}

$other = pg_escape_string($_POST['chk_other']); //รับค่าการเลือกบัตรอื่นๆ

	if($other == '1'){ //ถ้ามีการเลือกเพิ่มบัตรอื่นๆ
	  $list_other = $_POST['list_other']; //เลือกประเภทของบัตรอื่นๆ
	  $cardref = trim($_POST["N_CAPDREF"]);
		if($list_other == 'other'){ //เช็คว่ามีการเลือกบัตรอื่นๆเป็นประเภท อื่นๆหรือไม่
			$fs_card = trim(pg_escape_string($_POST["add_other"])); //ชื่อประเภทบัตรอื่นๆ	
		}else{		
			$fs_card = trim(pg_escape_string($_POST["list_other"]));
		}
		
	
	}else{
	
		$fs_card = 'บัตรประชาชน';
	}

$cardref = checknull($cardref); //ตรวจสอบว่าเลขบัตรอื่นๆเป็น null หรือไม่
$fs_cardid = checknull($fs_cardid); 
$statuscus=checknull($fs_statuscus);  //สถานะลูกค้า 0=คนไทย 1= ชาวต่างชาติ 2=บริษัท

$update_fn="update \"Fn\" SET
				\"N_SAN\"='$fs_san',
				\"N_AGE\"='$fs_age',
				\"N_CARD\"='$fs_card',
				\"N_IDCARD\"=$fs_cardid,
				\"N_OT_DATE\"='$fs_datecard',
				\"N_BY\"='$fs_cardby',
				\"N_OCC\"='$fs_occ',
				\"N_ContactAdd\"='$fs_conadd',
				\"N_CARDREF\"=$cardref,
				statuscus=$statuscus
			WHERE \"CusID\"='$fs_cusid' ";
/*
$update_fn="update \"Fn\" SET \"N_SAN\"='$fs_san',\"N_AGE\"='$fs_age',\"N_CARD\"='$fs_card', \"N_IDCARD\"='$fs_cardid', \"N_OT_DATE\"='$fs_datecard',\"N_BY\"='$fs_cardby',\"N_ContactAdd\"='$fs_conadd',\"N_OCC\"='$fs_occ' WHERE \"CusID\"='$fs_cusid' ";
*/
if($result=pg_query($update_fn)){
}else{
	$st6="error insert Re".$update_fn;
	$status++;
}

//ดึงข้อมูลขึ้นมาตรวจสอบว่ามีข้อมูลการแก้ไขในตาราง Customer_Temp หรือไม่
$qry_temp=pg_query("select MAX(\"edittime\") as maxedit from \"Customer_Temp\" where \"CusID\"='$fs_cusid'");
$num_temp=pg_num_rows($qry_temp);

//หาค่า  N_STATE
$qry_state=pg_query("select \"N_STATE\" from \"Fn\" where \"CusID\"='$fs_cusid'");
$res_state=pg_fetch_array($qry_state);
$N_STATE=$res_state["N_STATE"];
if($num_temp==0){ //กรณีไม่พบข้อมูลจะกำหนด edittime ให้ค่าเริ่มต้นคือ 1
	$edittime=1;
}else{
	//ให้ดึงค่า edittime ครั้งล่าสุดขึ้นมาแล้วกำหนดค่า edittime เป็นเลขถัดไป
	$res_temp=pg_fetch_array($qry_temp);
	$edittime=$res_temp["maxedit"]+1;
}

//ทำการ Insert ข้อมูลในตาราง Customer_Temp
/*$insert_Fa1="INSERT INTO \"Customer_Temp\"(
			\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
			\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
			\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\")
		VALUES ('$fs_cusid','$add_user','$add_date','000','$add_date','1','$edittime','$fs_firname', '$fs_name', '$fs_surname', '$fs_pair', '$fs_no',
			$fs_subno2, $fs_soi2, $fs_rd2, '$fs_tum', '$fs_aum', '$fs_province', '$fs_post','$fs_san', '$fs_age', '$fs_card', '$fs_cardid', 
			'$fs_datecard','$fs_cardby', '$fs_occ', '$fs_conadd','$N_STATE')";*/
			
$insert_Fa1="INSERT INTO \"Customer_Temp\"(
			\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
			\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
			\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
			\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"addr_country\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"N_CARDREF\",\"A_BIRTHDAY\",
			\"A_SEX\",\"A_ROOM\",\"A_FLOOR\",\"A_BUILDING\",\"A_VILLAGE\",statuscus)
		VALUES ('$fs_cusid','$add_user','$add_date','000','$add_date','1','$edittime','$fs_firname', '$fs_name', '$fs_surname', '$fs_pair', '$fs_no',
			'$fs_subno', '$fs_soi', '$fs_rd', '$fs_tum', '$fs_aum', '$fs_province', '$fs_post','$fs_san', '$fs_age', '$fs_card', $fs_cardid, 
			'$fs_datecard','$fs_cardby', '$fs_occ', '$fs_conadd','$N_STATE','$fs_fri_name_eng','$fs_name_eng','$fs_surname_eng',
			'$fs_nickname','$fs_status',$fs_revenue2,$fs_education2,'$fs_country','$fs_mobile','$fs_telephone','$fs_email',$cardref,$fs_birthday,
			$A_SEX,$A_ROOM,$A_FLOOR,$A_BUILDING,$A_VILLAGE,$statuscus)";			
if($result=pg_query($insert_Fa1)){
}else{
	$status++;
}

 /* Insert Letter     */ 
if($addidno=="1"){ //กรณีเป็นที่อยู่ตามลูกค้า
	if($fs_subno!=""){
		$subno="หมู่ $fs_subno";
	}
	if($fs_soi!=""){
		$soi="ซอย$fs_soi";
	}
	if($fs_rd!=""){
		$road="ถนน$fs_rd";
	}
	if($fs_province=="กรุงเทพมหานคร"){
		$txttum="แขวง".$fs_tum;
		$txtaum="เขต".$fs_aum;
	}else{
		$txttum="ตำบล".$fs_tum;
		$txtaum="อำเภอ".$fs_aum;
	}
	if(trim(pg_escape_string($_POST["A_ROOM"])) != ""){ //ห้อง
		$txtA_ROOM = "ห้อง ".trim(pg_escape_string($_POST["A_ROOM"]));
	}
	if(trim(pg_escape_string($_POST["A_FLOOR"])) != ""){ //ชั้น
		$txtA_FLOOR = "ชั้น ".trim(pg_escape_string($_POST["A_FLOOR"]));
	}
	if(trim(pg_escape_string($_POST["A_BUILDING"])) != ""){ //อาคาร/สถานที่
		$txtA_BUILDING = trim(pg_escape_string($_POST["A_BUILDING"]));
	}
	if(trim(pg_escape_string($_POST["A_VILLAGE"])) != ""){ //หมู่บ้าน
		$txtA_VILLAGE = "หมู่บ้าน".trim(pg_escape_string($_POST["A_VILLAGE"]));
	}
	
	$fs_letter="$fs_no $txtA_ROOM $txtA_FLOOR $subno $txtA_BUILDING $txtA_VILLAGE $soi $road $txttum $txtaum $fs_province $fs_post";
}

$qry_lt=pg_query($db_connect,"select * from letter.cus_address
		where (\"CusID\"='$fs_cusid')  And (\"Active\"=TRUE)");
$numr_lt=pg_num_rows($qry_lt);
if($numr_lt==0)
{
	//$gen_ltr=pg_query("select letter.gen_cusletid('$fs_cusid')"); //gen letter
	//$res_genltr=pg_fetch_result($gen_ltr,0);
	
	//echo "<br>"."gen id=".$res_genltr;
	
	/*
	$ins_send_ads="insert into letter.cus_address 	
					   (\"CusLetID\",\"IDNO\",record_date,\"name\",active,userid,dtl_ads,\"CusState\")
					   values
					   ('$res_genltr','$fs_idno','$datenow','$textName',TRUE,'$userid','$fs_letter',0)";
	*/
	
	$ins_send_ads="insert into letter.cus_address 	
			          (\"CusID\" , change_date , address ,user_id )
					  values
					  ('$fs_cusid','$datenow','$fs_letter','$userid')";
	  
		 if($result=pg_query($db_connect,$ins_send_ads)){
		 }else{
			$st7 ="error insert Re".$ins_send_ads;
			$status++;
		 }	
	}
	else
	{			
		 
	 $in_lt="Update letter.cus_address SET address='$fs_letter' WHERE \"CusID\"='$fs_cusid' ";
		if($result=pg_query($db_connect,$in_lt)){
		}else{
			$st8 ="error update  Fn Re".$in_lt;
			$status++;
		 }	
    }
 /* edn Insert Letter */
 
//####################บันทึกใน Fp_Fa1 ด้วย##########################
//ตรวจสอบว่าในตาราง Fp_Fa1 มีข้อมูลหรือยัง
$chkfpfa1=pg_query("select * from \"Fp_Fa1\" where \"IDNO\"='$fs_idno' and \"edittime\"='0' and \"CusState\"='0'");
$numchk=pg_num_rows($chkfpfa1);
if($addidno=="2"){ //กรณีเป็นที่อยู่ใหม่ให้ดึงข้อมูลในตาราง temp 
	$qryaddnew=pg_query("SELECT \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", 
    \"A_AUM\", \"A_PRO\", \"A_POST\" FROM \"Fp_Fa1_addtemp\" where \"IDNO\"='$fs_idno'");
	
	$resfpfa1=pg_fetch_array($qryaddnew);
	$fs_no=$resfpfa1["A_NO"];
	$fs_subno=$resfpfa1["A_SUBNO"]; if($fs_subno==""){ $fs_subno2="null";}else{ $fs_subno2="'".$fs_subno."'";}
	$fs_soi=$resfpfa1["A_SOI"]; if($fs_soi==""){ $fs_soi2="null";}else{ $fs_soi2="'".$fs_soi."'";}
	$fs_rd=$resfpfa1["A_RD"]; if($fs_rd==""){ $fs_rd2="null";}else{ $fs_rd2="'".$fs_rd."'";}
	$fs_tum=$resfpfa1["A_TUM"];
	$fs_aum=$resfpfa1["A_AUM"];
	$fs_province=$resfpfa1["A_PRO"];
	$fs_post=$resfpfa1["A_POST"];
}

if($numchk==0){ //ให้ insert ข้อมูล
	$insfpfa1="INSERT INTO \"Fp_Fa1\"(
			\"IDNO\", \"CusID\", \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", 
			\"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"addUser\",\"addStamp\",
			\"A_ROOM\", \"A_FLOOR\", \"A_BUILDING\", \"A_BAN\",statuscus)
    VALUES ('$fs_idno','$fs_cusid', '$fs_no', $fs_subno2, $fs_soi2, $fs_rd2, 
			'$fs_tum', '$fs_aum', '$fs_province', '$fs_post','$add_user','$add_date',
			$A_ROOM, $A_FLOOR, $A_BUILDING, $A_VILLAGE,$statuscus)";
	if($resinfpfa1=pg_query($insfpfa1)){
	}else{
		$status++;
	}
}else if($numchk>0 and $addidno!="3"){ //ให้ update ข้อมูล
	$upfpfa1="UPDATE \"Fp_Fa1\"
	SET \"A_NO\"='$fs_no', \"A_SUBNO\"=$fs_subno2, \"A_SOI\"=$fs_soi2, 
       \"A_RD\"=$fs_rd2, \"A_TUM\"='$fs_tum', \"A_AUM\"='$fs_aum', \"A_PRO\"='$fs_province', \"A_POST\"='$fs_post',
       \"addUser\"='$add_user', \"addStamp\"='$add_date', \"A_ROOM\" = $A_ROOM, \"A_FLOOR\" = $A_FLOOR,
	   \"A_BUILDING\" = $A_BUILDING, \"A_BAN\" = $A_VILLAGE,statuscus=$statuscus
	WHERE \"IDNO\"='$fs_idno' and \"edittime\"='0' and \"CusState\"='0'";	
	if($resupfpfa1=pg_query($upfpfa1)){
	}else{
		$status++;
	}
}else{ //$update statuscus
	$upfpfa1="update \"Fp_Fa1\" set statuscus=$statuscus WHERE \"IDNO\"='$fs_idno' and \"edittime\"='0' and \"CusState\"='0'";
	if($resupfpfa1=pg_query($upfpfa1)){
	}else{
		$status++;
	}
}

//หลังจากจัดการข้อมูลเรียบร้อยแล้วให้ลบข้อมูลใน temp ออกด้วย
$deltemp="DELETE FROM \"Fp_Fa1_addtemp\" WHERE \"IDNO\"='$fs_idno'";
if($resdeltemp=pg_query($deltemp)){
}else{
	$status++;
}
//####################จบ Fp_Fa1 ##########################

/* create cus payment */

  $c_cpay="select \"CreateCusPayment\"('$fs_idno')";

    //$resid=pg_query($db_connect,$c_apay);
     if($result_cpayment=pg_query($c_cpay)){ 
     }
      else
    {
	   $status++;
     }	

    //echo $res=pg_fetch_result($resid,0);
	//echo $statusc;

  /* end create cuspaymnet */


if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$add_user', '(TAL) แก้ไขสัญญาเช่าซื้อ ','$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "บันทึกข้อมูลเรียบร้อย ";
	echo "<meta http-equiv=\"refresh\" content=\"2;URL=frm_av_editidno.php\">"; 
}else{
	pg_query("ROLLBACK");
	echo "<br>มีข้อผิดพลาดในการบันทึก ระกลับไปหน้าแก้ไขสัญญาเช่าซื้อภายใน 10 วินาที<br>$st<br>$st2<br>$st3<br>$st4<br>$st5<br>$st6<br>$st7<br>$st8<br>";
	echo "<meta http-equiv=\"refresh\" content=\"10;URL=frm_av_editidno.php\">"; 
}
?>
