<?php
session_start();
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

include("../../config/config.php");
include("../../nw/function/checknull.php");

$datenow=date("Y-m-d");
$userid = $_SESSION["av_iduser"];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="doctitle" -->

<title><?php echo $_SESSION["session_company_name"]; ?></title>
<style type="text/css">

#warppage
	{
	width:800px;
	margin-left:auto;
	margin-right:auto;
	
	min-height: 5em;
	background: rgb(240, 240, 240);
	padding: 5px;
	border: rgb(128, 128, 128) solid 0.5px;
	border-radius: .625em;
	-moz-border-radius: .625em;
	-webkit-border-radius: .625em;
	}

</style>
<!-- InstanceEndEditable -->
<style type="text/css">
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
  }
  .style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
  }
  

-->
</style>
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"></h1>
</div>
<!-- InstanceBeginEditable name="EditRegion3" -->
<div id="warppage"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  
  <div class="style5" style="width:auto; padding-left:10px;">
  <?php
//begin //
pg_query("BEGIN");
$status=0;

$idno=$_POST["fidno"]; //เลขที่สัญญา
$id_cusid=trim($_POST["fcus_id"]); //รหัสลูกค้า
$officeid=$_SESSION["av_officeid"]; 

$dat=date("Y/m/d");
$add_date=nowDateTime();

$addidno=$_POST["addidno"]; //ที่อยู่สัญญา

$fs_fir=$_POST["f_firname"];  //คำนำหน้า
$fs_name=$_POST["cus_name"]; //ชื่อไทย
$fs_sirname=$_POST["cus_surname"]; //นามสกุล

//ตรวจสอบว่ามีคำนำหน้าที่คีย์หรือยังถ้ายังก็ให้บันทึกด้วย
$qrytitle=pg_query("select * from \"nw_title\" where \"titleName\"='$fs_fir'");
$numtitle=pg_num_rows($qrytitle);
if($numtitle==0){ //กรณีไม่พบข้อมูลให้บันทึกด้วย
	$intitle="INSERT INTO nw_title(\"titleName\") VALUES ('$fs_fir')";
	if($restitle=pg_query($intitle)){		
	}else{
		$status++;
	}
}

$textName=$fs_fir." ".$fs_name." ".$fs_sirname;


$fs_pair=$_POST["cus_pair"];
$fs_san=$_POST["f_san"];
$fs_age=$_POST["f_age"];
$fs_card=$_POST["f_cardtype"];
$fs_cardid=$_POST["f_cardid"];
$fs_datecard=$_POST["f_otdate"];
$fs_card_by=$_POST["f_cardby"];

$fs_no=$_POST["f_addno"];
$fs_subno=$_POST["f_subno"];
$fs_rd=$_POST["f_rd"];
$fs_soi=$_POST["f_soi"];
$fs_tum=$_POST["f_tum"];
$fs_aum=$_POST["f_aum"];
$fs_province=trim($_POST["f_pro"]);
$fs_post=trim($_POST["f_post"]);

$fs_occ=$_POST["f_occ"];

$fs_asid=$_POST["f_gasid"];

$fs_ads=$_POST["f_letter"];

$A_SEX2=trim($_POST["A_SEX"]); //เพศ
$A_SEX = checknull($A_SEX2); //ตรวจสอบว่าเลขบัตรอื่นๆเป็น null หรือไม่
$A_ROOM=checknull(trim($_POST["A_ROOM"])); //ห้อง
$A_FLOOR=checknull(trim($_POST["A_FLOOR"])); //ชั้น
$A_BUILDING=checknull(trim($_POST["A_BUILDING"])); //อาคาร/สถานที่
$A_VILLAGE=checknull(trim($_POST["A_VILLAGE"])); //หมู่บ้าน

$fs_fri_name_eng=trim($_POST["f_fri_name_eng"]);
$fs_name_eng=trim($_POST["f_name_eng"]);
$fs_surname_eng=trim($_POST["f_surname_eng"]);
$fs_nickname=trim($_POST["f_nickname"]); 
$fs_status=trim($_POST["f_status"]); //สถานะสมรส
$fs_revenue=trim($_POST["f_revenue"]); if($fs_revenue==""){ $fs_revenue2="null"; }else{ $fs_revenue2="'".$fs_revenue."'"; }//รายได้ต่อเดือน
$fs_education=trim($_POST["f_education"]); if($fs_education==""){ $fs_education2="null"; }else{ $fs_education2="'".$fs_education."'"; }//ระดับการศึกษา
$fs_country=trim($_POST["f_country"]); //ประเทศ
$fs_mobile=trim($_POST["f_mobile"]); //โทรศัพท์มือถือ
$fs_telephone=trim($_POST["f_telephone"]); //โทรศัพท์บ้าน
$fs_email=trim($_POST["f_email"]); //email
$fh_adds = $_POST["fh_adds"];
$fs_birthday=checknull(trim($_POST["f_brithday"]));


if($_POST["f_addno"] != ""){ $fs_notxt = 'บ้านเลขที่ '.$_POST["f_addno"]; }
if($_POST["f_subno"] != ""){ $fs_subnotxt = 'หมู่ '.$_POST["f_subno"]; }
if($_POST["A_ROOM"] != ""){ $A_ROOMtxt = 'ห้อง '.$_POST["A_ROOM"]; }
if($_POST["A_FLOOR"] != ""){ $A_FLOORtxt = 'ชั้น '.$_POST["A_FLOOR"]; }
if($_POST["A_BUILDING"] != ""){ $A_BUILDINGtxt = 'อาคาร'.$_POST["A_BUILDING"]; }
if($_POST["A_VILLAGE"] != ""){ $A_VILLAGEtxt = 'หมู่บ้าน'.$_POST["A_VILLAGE"]; }
if($_POST["f_soi"]!= ""){ $fs_soitxt = 'ซอย'.$_POST["f_soi"]; }
if($_POST["f_rd"] != ""){ $fs_rdtxt = 'ถนน'.$_POST["f_rd"]; }
if($fs_province == "กรุงเทพมหานคร"){
	$fs_tumtxt = ' แขวง'.$fs_tum; 
	$fs_aumtxt = ' เขต'.$fs_aum;
	$fs_provincetxt = $fs_province;
}else{
	$fs_tumtxt = ' ตำบล'.$fs_tum;
	$fs_aumtxt = ' อำเภอ'.$fs_aum;
	$fs_provincetxt = " จังหวัด".$fs_province;
}




$fs_stat_add=$_POST["f_extadd"];
if($fs_stat_add==2){
	$fs_ext=$_POST["f_ext"];
	$fs_conadd=$fs_ext;
}else if($fs_stat_add==0){
	$fs_conadd = $fh_adds;
}else{
	$fs_conadd=trim($fs_notxt)."  ".trim($fs_subnotxt)."  ".trim($A_ROOMtxt)."  ".trim($A_FLOORtxt)."  ".trim($A_BUILDINGtxt)."  ".trim($A_VILLAGEtxt)."  ".trim($fs_soitxt)."  ".trim($fs_rdtxt)."  ".trim($fs_tumtxt)."  ".trim($fs_aumtxt)."  ".trim($fs_provincetxt)."  ".trim($fs_post);
}

$fs_subno = checknull($fs_subno);
$fs_soi = checknull($fs_soi);
$fs_rd = checknull($fs_rd);
$fs_post = checknull($fs_post);

$other = $_POST['chk_other']; //รับค่าการเลือกบัตรอื่นๆ

	if($other == '1'){ //ถ้ามีการเลือกเพิ่มบัตรอื่นๆ
	  $list_other = $_POST['list_other']; //เลือกประเภทของบัตรอื่นๆ
	  $cardref = trim($_POST["N_CAPDREF"]);
		if($list_other == 'other'){ //เช็คว่ามีการเลือกบัตรอื่นๆเป็นประเภท อื่นๆหรือไม่
			$fs_card=trim($_POST["add_other"]); //ชื่อประเภทบัตรอื่นๆ	
		}else{		
			$fs_card=trim($_POST["list_other"]);
		}
		
	
	}else{
	
		$fs_card = 'บัตรประชาชน';
	}

$cardref = checknull($cardref); //ตรวจสอบว่าเลขบัตรอื่นๆเป็น null หรือไม่
$fs_cardid = checknull($fs_cardid); 


//update ข้อมูลส่วนตัว
$in_sql="update \"Fa1\" SET  \"A_FIRNAME\"='$fs_fir'  ,
		\"A_NAME\"='$fs_name',
		\"A_SIRNAME\"='$fs_sirname',
		\"A_PAIR\"='$fs_pair',
		\"A_NO\"='$fs_no', 
		\"A_SUBNO\"=$fs_subno,
		\"A_SOI\"=$fs_soi,
		\"A_RD\"=$fs_rd,
		\"A_TUM\"='$fs_tum',
		\"A_AUM\"='$fs_aum',
		\"A_PRO\"='$fs_province',
		\"A_POST\"=$fs_post,
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
		where \"CusID\"='$id_cusid'";
		   
if($result=pg_query($in_sql)){
}else{
	$status = $status+1;
}

//update ข้อมูลส่วนตัวเพิ่มเติม
$in_fn="Update \"Fn\" SET \"N_SAN\"='$fs_san',\"N_AGE\"='$fs_age',\"N_CARD\"='$fs_card',
\"N_IDCARD\"=$fs_cardid,\"N_OT_DATE\"='$fs_datecard',\"N_BY\"='$fs_card_by',\"N_OCC\"='$fs_occ',\"N_ContactAdd\"='$fs_conadd' ,\"N_CARDREF\"=$cardref
        WHERE \"CusID\"='$id_cusid'  ";
 
if($result=pg_query($in_fn)){
}else{
	$status = $status+1;
}

//ดึงข้อมูลขึ้นมาตรวจสอบว่ามีข้อมูลการแก้ไขในตาราง Customer_Temp หรือไม่
$qry_temp=pg_query("select MAX(\"edittime\") as maxedit from \"Customer_Temp\" where \"CusID\"='$id_cusid'");
$num_temp=pg_num_rows($qry_temp);

//หาค่า  N_STATE
$qry_state=pg_query("select \"N_STATE\" from \"Fn\" where \"CusID\"='$id_cusid'");
$res_state=pg_fetch_array($qry_state);
$N_STATE=$res_state["N_STATE"];
if($num_temp==0){ //กรณีไม่พบข้อมูลจะกำหนด edittime ให้ค่าเริ่มต้นคือ 1
	$edittime=1;
}else{
	//ให้ดึงค่า edittime ครั้งล่าสุดขึ้นมาแล้วกำหนดค่า edittime เป็นเลขถัดไป
	$res_temp=pg_fetch_array($qry_temp);
	$edittime=$res_temp["maxedit"]+1;
}	

$insert_Fa1="INSERT INTO \"Customer_Temp\"(
			\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
			\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
			\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
			\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"addr_country\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"N_CARDREF\",\"A_BIRTHDAY\",
			\"A_SEX\",\"A_ROOM\",\"A_FLOOR\",\"A_BUILDING\",\"A_VILLAGE\")
		VALUES ('$id_cusid','$userid','$add_date','000','$add_date','1','$edittime','$fs_fir', '$fs_name', '$fs_sirname', '$fs_pair', '$fs_no',
			$fs_subno, $fs_soi, $fs_rd, '$fs_tum', '$fs_aum', '$fs_province', $fs_post,'$fs_san', '$fs_age', '$fs_card', $fs_cardid, 
			'$fs_datecard','$fs_card_by', '$fs_occ', '$fs_conadd','$N_STATE','$fs_fri_name_eng','$fs_name_eng','$fs_surname_eng',
			'$fs_nickname','$fs_status',$fs_revenue2,$fs_education2,'$fs_country','$fs_mobile','$fs_telephone','$fs_email',$cardref,$fs_birthday,
			$A_SEX,$A_ROOM,$A_FLOOR,$A_BUILDING,$A_VILLAGE)";			
if($result=pg_query($insert_Fa1)){
}else{
	$status++;
} 

//update รายละเอียดสัญญา
$contactnote = $_POST["contactnote"];
$query_contactnote = pg_query("select * from \"Fp_Note\" where \"IDNO\" = '$idno'");
$num_contact = pg_num_rows($query_contactnote);
if($num_contact == 0){
	$ins_con="insert into \"Fp_Note\" (\"IDNO\",\"ContactNote\") values ('$idno','$contactnote')";
	if($result=pg_query($ins_con)){ 
	}else{
		$st3="Error At ".$result;
		$status++;
	} 
}else{
	$update_contact = "update \"Fp_Note\" set \"ContactNote\" = '$contactnote' where \"IDNO\" = '$idno'";
	if($result=pg_query($update_contact)){
	}else{
	 $st4="Error At ".$result;
	 $status++;
	} 
}

$fg_stdate=$_POST["signDate"];
$fg_down=$_POST["g_down"];
$fg_total=$_POST["g_total"];
$fg_month=$_POST["g_month"];
$fg_fdate=$_POST["f_Date"];
$fg_begin=$_POST["g_begin"];
$fg_beginx=$_POST["g_beginx"];

$fss_vdown=$_POST["g_vatdown"];
$gv_down=pg_query("select amt_before_vat($fg_down)");
$fg_vdown=pg_fetch_result($gv_down,0); //เงินดาวน์ถอด vat

$fg_vvdown=$fg_down-$fg_vdown; //vat  เงินดาวน์

$ch_vdown=$_POST["ch_dvat"];
if($fss_vdown != $ch_vdown){
	$fs_pdown=$fss_vdown;
}else{ 
	$fs_pdown=$fg_vvdown;
}
 
$fss_vmonth=$_POST["g_vatmonth"];
$gmh=pg_query("select amt_before_vat($fg_month)");
$fgmonth=pg_fetch_result($gmh,0); //งวดถอด vat

$fgth=$fg_month-$fgmonth; //vat  งวด

$ch_vmonth=$_POST["ch_mvat"];
if($fss_vmonth != $ch_vmonth){
   $fs_vmonth=$fss_vmonth;
}else{ 
    $fs_vmonth=$fgth;
}
 
 $in_fp="Update \"Fp\" SET \"P_STDATE\"='$fg_stdate',
		\"P_MONTH\"='$fgmonth',
		\"P_VAT\"='$fs_vmonth',
		\"P_TOTAL\"='$fg_total',
		\"P_DOWN\"='$fg_vdown',
		\"P_VatOfDown\"='$fs_pdown',
		\"P_BEGIN\"='$fg_begin',
		\"P_BEGINX\"='$fg_beginx',
		\"P_FDATE\"='$fg_fdate'
        WHERE \"IDNO\"='$idno' "; 
 
if($result=pg_query($in_fp)){
}else{
	$status = $status+1;
}	 

$fg_name=$_POST["g_name"];
$fg_sn=$_POST["g_tanknumber"];
$fg_type=$_POST["g_type"];
$fg_regis=$_POST["g_regis"];
$fg_pro=$_POST["g_province"];
$fg_year=$_POST["g_year"];
$fg_carnum=$_POST["g_carnum"];
$fg_marnum=$_POST["g_marnum"];

$C_Milage=checknull($_POST["f_carmi"]); //เลขไมล์
$fp_fc_type = checknull($_POST["f_type_vehicle"]); // ประเภท รถยนต์/จักรยายนต์
$fp_fc_model = checknull($_POST["f_model"]); //รุ่น
$fp_fc_category = checknull($_POST["f_useful_vehicle"]); //ชนิดรถ  กระบะ หรือ เก๋ง หรือ รถรับจ้าง 
$fp_fc_newcar = checknull($_POST["f_status_vehicle"]); //รถใหม่หรือรถใช้แล้ว
$fp_fc_brand = checknull($_POST["f_brand"]); //ยี่ห้อ
$fp_fc_gas = checknull($_POST["gas_system"]); //ระแบบแก๊สรถยนต์
 
$in_fgas="Update \"FGas\" SET gas_name='$fg_name',
		gas_number='$fg_sn',
		gas_type='$fg_type',
		car_regis='$fg_regis',
		car_regis_by='$fg_pro',
		car_year='$fg_year',
		carnum='$fg_carnum',
		marnum='$fg_marnum',
		\"fc_milage\" = $C_Milage,
		\"fc_type\" = $fp_fc_type, 
		\"fc_brand\" = $fp_fc_brand, 
		\"fc_model\" = $fp_fc_model, 
		\"fc_category\" = $fp_fc_category, 
		\"fc_newcar\" = $fp_fc_newcar,
		\"fc_gas\" = $fp_fc_gas
        WHERE \"GasID\"='$fs_asid'";
 
if($result=pg_query($in_fgas)){
}else{
	$status = $status+1;
}	 

/* cuspayment    */
$c_cpay="select \"CreateCusPayment\"('$idno')";
 $result_cpay=pg_query($c_cpay);
 /*end cuspayment */

 /* Insert Letter     */ 
if($addidno=="1"){ //กรณีเป็นที่อยู่ตามลูกค้า
	if($_POST["f_subno"]!=""){
		$subno="หมู่ ".$_POST["f_subno"];
	}
	if($_POST["f_soi"]!=""){
		$soi="ซอย".$_POST["f_soi"];
	}
	if($_POST["f_rd"]!=""){
		$road="ถนน".$_POST["f_rd"];
	}
	if($fs_province=="กรุงเทพมหานคร"){
		$txttum="แขวง".$fs_tum;
		$txtaum="เขต".$fs_aum;
	}else{
		$txttum="ตำบล".$fs_tum;
		$txtaum="อำเภอ".$fs_aum;
	}
	$fs_letter="$fs_no $subno $soi $road $txttum $txtaum $fs_province ".$_POST["f_post"];
}

$qry_lt=pg_query($db_connect,"select * from letter.cus_address
		where (\"CusID\"='$id_cusid')  And (\"Active\"=TRUE)");
$numr_lt=pg_num_rows($qry_lt);
if($numr_lt==0)
{
	$ins_send_ads="insert into letter.cus_address 	
			          (\"CusID\" , change_date , address ,user_id )
					  values
					  ('$id_cusid','$datenow','$fs_letter','$userid')";
	  
		 if($result=pg_query($db_connect,$ins_send_ads)){
		 }else{
			$st7 ="error insert Re".$ins_send_ads;
			$status++;
		 }	
	}
	else
	{			
		 
	 $in_lt="Update letter.cus_address SET address='$fs_letter' WHERE \"CusID\"='$id_cusid' ";
		if($result=pg_query($db_connect,$in_lt)){
		}else{
			$st8 ="error update  Fn Re".$in_lt;
			$status++;
		 }	
    }
 /* edn Insert Letter */
 
 //####################บันทึกใน Fp_Fa1 ด้วย##########################
//ตรวจสอบว่าในตาราง Fp_Fa1 มีข้อมูลหรือยัง
$chkfpfa1=pg_query("select * from \"Fp_Fa1\" where \"IDNO\"='$idno' and \"edittime\"='0' and \"CusState\"='0'");
$numchk=pg_num_rows($chkfpfa1);
if($addidno=="2"){ //กรณีเป็นที่อยู่ใหม่ให้ดึงข้อมูลในตาราง temp 
	$qryaddnew=pg_query("SELECT \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", 
    \"A_AUM\", \"A_PRO\", \"A_POST\" FROM \"Fp_Fa1_addtemp\" where \"IDNO\"='$idno'");
	
	$resfpfa1=pg_fetch_array($qryaddnew);
	$fs_no=$resfpfa1["A_NO"];
	$fs_subno=checknull($resfpfa1["A_SUBNO"]); 
	$fs_soi=checknull($resfpfa1["A_SOI"]);
	$fs_rd=checknull($resfpfa1["A_RD"]); 
	$fs_tum=$resfpfa1["A_TUM"];
	$fs_aum=$resfpfa1["A_AUM"];
	$fs_province=$resfpfa1["A_PRO"];
	$fs_post=checknull($resfpfa1["A_POST"]);
}
if($numchk==0){ //ให้ insert ข้อมูล
	$insfpfa1="INSERT INTO \"Fp_Fa1\"(
           \"IDNO\", \"CusID\", \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_ROOM\" ,\"A_FLOOR\",\"A_BUILDING\",\"A_BAN\",
            \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"addUser\",\"addStamp\")
    VALUES ('$idno','$id_cusid', '$fs_no', $fs_subno, $fs_soi, $fs_rd,$A_ROOM,$A_FLOOR ,$A_BUILDING,$A_VILLAGE,
			'$fs_tum', '$fs_aum', '$fs_province', $fs_post,'$userid','$add_date')";	
	if($resinfpfa1=pg_query($insfpfa1)){
	}else{
		$status++;
	}
}else if($numchk>0 and $addidno!="3"){ //ให้ update ข้อมูล
	$upfpfa1="UPDATE \"Fp_Fa1\"
	SET \"A_NO\"='$fs_no', \"A_SUBNO\"=$fs_subno, \"A_SOI\"=$fs_soi, 
       \"A_RD\"=$fs_rd, \"A_TUM\"='$fs_tum', \"A_AUM\"='$fs_aum', \"A_PRO\"='$fs_province', \"A_POST\"=$fs_post,\"A_ROOM\" = $A_ROOM,\"A_FLOOR\" = $A_FLOOR,\"A_BUILDING\" = $A_BUILDING,\"A_BAN\" = $A_VILLAGE,
       \"addUser\"='$userid', \"addStamp\"='$add_date'
	WHERE \"IDNO\"='$idno' and \"edittime\"='0' and \"CusState\"='0'";	
	if($resupfpfa1=pg_query($upfpfa1)){
	}else{
		$status++;
	}
}




//หลังจากจัดการข้อมูลเรียบร้อยแล้วให้ลบข้อมูลใน temp ออกด้วย
$deltemp="DELETE FROM \"Fp_Fa1_addtemp\" WHERE \"IDNO\"='$idno'";
if($resdeltemp=pg_query($deltemp)){
}else{
	$status++;
}
//####################จบ Fp_Fa1 ##########################
 

if($status == 0){
	pg_query("COMMIT");
	echo "<br>";
	echo " บันทึกข้อมูลเรียบร้อยแ้ล้ว";
	echo "<br><br>";
	echo "<input type=\"button\" value=\"กลับหน้าหลัก\" onclick=\"window.location='frm_av_findgas.php'\"/>";
}else{
	pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
	echo "<br><br>";
	echo "<input type=\"button\" value=\"กลับไปแก้ไข\" onclick=\"window.location='frm_editgas.php?idnos=$idno'\"/>";
}






 


?>
  </div>
</div>
<!-- InstanceEndEditable --></div>
</body>
<!-- InstanceEnd --></html>
