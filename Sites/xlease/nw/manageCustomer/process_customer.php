<?php
session_start();
include("../../config/config.php");
include("../../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว
include("../function/checknull.php");
$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

session_register("auth_refferer");
session_register("auth_cusid");


//หากมีการให้อนุมัติอัตโนมัติโดยระบบ
$autoapp = $_POST["autoapp"];
$update_gather = $_POST["update_gather"];

// ------------  รับข้อมูลที่ POST มาจาก FORM ของหน้าก่อนหน้า (START)
$fs_firname=trim($_POST["f_fri_name"]);
$fs_name=trim($_POST["f_name"]);
$fs_surname=trim($_POST["f_surname"]);
$fs_pair=trim($_POST["f_pair"]);
$fs_no=trim($_POST["f_no"]);
$fs_subno=trim($_POST["f_subno"]);
$fs_soi=trim($_POST["f_soi"]);
$fs_rd=trim($_POST["f_rd"]);
$fs_aum=trim($_POST["f_aum"]);
$fs_tum=trim($_POST["f_tum"]);
$fs_province=trim($_POST["f_province"]); // จังหวัด
$fs_post=trim($_POST["f_post"]);
$statuscus=trim($_POST["statuscus"]); //สถานะลูกค้า 0=คนไทย 1= ชาวต่างชาติ 2=บริษัท

if($fs_province == "ไม่ระบุ"){$fs_province = "";}

//ตรวจสอบ Levelemp <=15
$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$add_user' ");
$leveluser = pg_fetch_array($query_leveluser);
$emplevel=$leveluser["emplevel"];

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

$fs_fri_name_eng=checknull($_POST["f_fri_name_eng"]);
$fs_name_eng=checknull($_POST["f_name_eng"]);
$fs_surname_eng=checknull($_POST["f_surname_eng"]);
$fs_nickname=checknull($_POST["f_nickname"]); 
$fs_status=checknull($_POST["f_status"]); //สถานะสมรส
$fs_revenue=checknull($_POST["f_revenue"]); //รายได้ต่อเดือน
$fs_education=checknull($_POST["f_education"]); //ระดับการศึกษา
$fs_country=checknull($_POST["f_country"]); //ประเทศ
$fs_mobile=checknull($_POST["f_mobile"]); //โทรศัพท์มือถือ
$fs_telephone=checknull($_POST["f_telephone"]); //โทรศัพท์บ้าน
$fs_email=checknull($_POST["f_email"]); //email

$A_SEX=checknull($_POST["A_SEX"]); //เพศ
$A_ROOM=checknull($_POST["A_ROOM"]); //ห้อง
$A_FLOOR=checknull($_POST["A_FLOOR"]); //ชั้น
$A_BUILDING=checknull($_POST["A_BUILDING"]); //อาคาร/สถานที่
$A_VILLAGE=checknull($_POST["A_VILLAGE"]); //หมู่บ้าน



$fs_san=checknull($_POST["f_san"]);
$fs_age=checknull($_POST["f_age"]);
$fs_birthday=checknull($_POST["f_brithday"]);
$fs_cardid=$_POST["f_cardid"];
if($statuscus!=0){
	$fs_cardid="";
}
$fs_datecard=checknull($_POST["f_datecard"]);
$fs_cardby=checknull($_POST["f_card_by"]);
$fs_occ=checknull($_POST["f_occ"]);
$method=$_POST["method"];

pg_query("BEGIN WORK");
 $status=0;

if($method=="add"){

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

	$fs_card = checknull($fs_card);
 
 
	// ------ ตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
	$cus_sn = GenCT();
	// ----------------------

	if($fs_no != ""){ $fs_conaddtxt = $fs_conaddtxt.'บ้านเลขที่ '.$fs_no."  "; }
	if($fs_subno != ""){ $fs_conaddtxt = $fs_conaddtxt.'หมู่ '.$fs_subno."  "; }
	if($_POST["A_BUILDING"] != ""){ $fs_conaddtxt = $fs_conaddtxt.'อาคาร'.$_POST["A_BUILDING"]."  "; }
	if($_POST["A_ROOM"] != ""){ $fs_conaddtxt = $fs_conaddtxt.'ห้อง '.$_POST["A_ROOM"]."  "; }
	if($_POST["A_FLOOR"] != ""){ $fs_conaddtxt = $fs_conaddtxt.'ชั้น '.$_POST["A_FLOOR"]."  "; }
	if($_POST["A_VILLAGE"] != ""){ $fs_conaddtxt = $fs_conaddtxt.'หมู่บ้าน'.$_POST["A_VILLAGE"]."  "; }
	if($fs_soi != ""){ $fs_conaddtxt = $fs_conaddtxt.'ซอย'.$fs_soi."  "; }
	if($fs_rd != ""){ $fs_conaddtxt = $fs_conaddtxt.'ถนน'.$fs_rd."  "; }	
	if($fs_province == "กรุงเทพมหานคร"){
		$fs_conaddtxt = $fs_conaddtxt.'แขวง'.$fs_tum."  "; 
		$fs_conaddtxt = $fs_conaddtxt.'เขต'.$fs_aum."  ";
	}else{
		$fs_conaddtxt = $fs_conaddtxt.'ตำบล'.$fs_tum."  ";
		$fs_conaddtxt = $fs_conaddtxt.'อำเภอ'.$fs_aum."  ";
	}
	if($fs_province != ""){$fs_conaddtxt = $fs_conaddtxt."จังหวัด".$fs_province."  ";}
	if($fs_post != ""){ $fs_conaddtxt = $fs_conaddtxt.$fs_post; }

	$fs_stat_add=$_POST["f_extadd"];
	if($fs_stat_add==2){
		$fs_ext=$_POST["f_ext"];
		$fs_conadd=$fs_ext;
	}
	else{
		$fs_conadd=$fs_conaddtxt;
			
	}
	$fs_post=checknull($_POST["f_post"]);
	
	// ------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
	$check_card = str_replace(" ","",$fs_cardid);
	$check_card = str_replace("-","",$check_card);
	
	if($other != '1'){
		$sql_check_idcard = pg_query("select \"N_IDCARD\" from \"Fn\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$check_card'");
		$row_check_idcard = pg_num_rows($sql_check_idcard);
		if($row_check_idcard > 0)
		{
			$status++;
			$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
		}
	}	
	$sql_check_name = pg_query("select * from \"Fa1\" where \"A_NAME\" = '$fs_name' and \"A_SIRNAME\" = '$fs_surname'");
	$row_check_name = pg_num_rows($sql_check_name);
	if($row_check_name > 0)
	{   
		if($emplevel>15){
			$status++;
			$error_check = "มีลูกค้าคนนี้อยู่แล้ว";		
		}
		
	}
	
	if($check_card != "" ){
		$sql_check_idcard_CT = pg_query("select \"N_IDCARD\" from \"Customer_Temp\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$check_card' and statusapp = '2' ");
		$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
		if($row_check_idcard_CT > 0)
		{
			$status++;
			$error_check = "ลูกค้าคนนี้รอการอนุมัติหรือมีข้อมูลอยู่แล้ว";
		}
	}else{
		$sql_check_idcard_CT = pg_query("select \"N_CARDREF\" from \"Customer_Temp\" where replace(replace(\"N_CARDREF\",' ',''),'-','') = '$cardref' and statusapp = '2' ");
		$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
		if($row_check_idcard_CT > 0)
		{
			$status++;
			$error_check = "ลูกค้าคนนี้รอการอนุมัติหรือมีข้อมูลอยู่แล้ว";
		}	
	}
	
	$sql_check_name_CT = pg_query("select * from \"Customer_Temp\" where \"A_NAME\" = '$fs_name' and \"A_SIRNAME\" = '$fs_surname' and statusapp = '2'");
	$row_check_name_CT = pg_num_rows($sql_check_name_CT);
	if($row_check_name_CT > 0)
	{
		if($emplevel>15){
			$status++;
			$error_check = "ลูกค้าคนนี้รอการอนุมัติหรือมีข้อมูลอยู่แล้ว";
		}		
	}
	
	
	$fs_cardid = checknull($fs_cardid);
	$cardref = checknull($cardref); //ตรวจสอบว่าเลขบัตรอื่นๆเป็น null หรือไม่
	
	$fs_province_checknull = checknull($fs_province); // ตัวที่ใช้ในการ insert จังหวัด
	
	
	//--ตัวแปรที่ยังไม่มีข้อมูลเก็บ
	$fs_education2 = checknull($fs_education2);
	$fs_revenue2 = checknull($fs_revenue2);
	// ------------ นำข้อมูลที่ได้จากการ POST มา UPDATE ลูกค้าที่ถูกแก้ไขข้อมูล (START)
	$insert_Fa1="INSERT INTO \"Customer_Temp\"(
				\"CusID\",\"add_user\",\"add_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
				\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
				\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
				\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"N_CARDREF\",\"addr_country\",\"A_BIRTHDAY\",
				\"A_SEX\",\"A_ROOM\",\"A_FLOOR\",\"A_BUILDING\",\"A_VILLAGE\",statuscus)
			VALUES ('$cus_sn','$add_user','$add_date','2','0','$fs_firname', '$fs_name', '$fs_surname', '$fs_pair', '$fs_no',
				'$fs_subno', '$fs_soi', '$fs_rd', '$fs_tum', '$fs_aum', $fs_province_checknull, $fs_post,$fs_san, $fs_age, $fs_card, $fs_cardid, 
				$fs_datecard,$fs_cardby, $fs_occ,'$fs_conadd','0',$fs_fri_name_eng,$fs_name_eng,$fs_surname_eng,
				$fs_nickname,$fs_status,$fs_revenue,$fs_education,$fs_mobile,$fs_telephone,$fs_email,$cardref,$fs_country,$fs_birthday,
				$A_SEX,$A_ROOM,$A_FLOOR,$A_BUILDING,$A_VILLAGE,'$statuscus')";

	// ตรวจสอบผลการ Query ดูว่ามีปัญหาใดหรือไม่
	if($result=pg_query($insert_Fa1)){
	}else{
		$status++;
		$error=$result;
	}
	//}
}else if($method=="edit"){ //กรณีแก้ไขข้อมูลลูกค้าจะไม่มีการ Gen ID ใหม่


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
	
	if($fs_no != ""){ $fs_conaddtxt = $fs_conaddtxt.'บ้านเลขที่ '.$fs_no."  "; }
	if($fs_subno != ""){ $fs_conaddtxt = $fs_conaddtxt.'หมู่ '.$fs_subno."  "; }
	if($_POST["A_BUILDING"] != ""){ $fs_conaddtxt = $fs_conaddtxt.'อาคาร'.$_POST["A_BUILDING"]."  "; }
	if($_POST["A_ROOM"] != ""){ $fs_conaddtxt = $fs_conaddtxt.'ห้อง '.$_POST["A_ROOM"]."  "; }
	if($_POST["A_FLOOR"] != ""){ $fs_conaddtxt = $fs_conaddtxt.'ชั้น '.$_POST["A_FLOOR"]."  "; }
	if($_POST["A_VILLAGE"] != ""){ $fs_conaddtxt = $fs_conaddtxt.'หมู่บ้าน'.$_POST["A_VILLAGE"]."  "; }
	if($fs_soi != ""){ $fs_conaddtxt = $fs_conaddtxt.'ซอย'.$fs_soi."  "; }
	if($fs_rd != ""){ $fs_conaddtxt = $fs_conaddtxt.'ถนน'.$fs_rd."  "; }	
	if($fs_province == "กรุงเทพมหานคร"){
		$fs_conaddtxt = $fs_conaddtxt.'แขวง'.$fs_tum."  "; 
		$fs_conaddtxt = $fs_conaddtxt.'เขต'.$fs_aum."  ";
	}else{
		$fs_conaddtxt = $fs_conaddtxt.'ตำบล'.$fs_tum."  ";
		$fs_conaddtxt = $fs_conaddtxt.'อำเภอ'.$fs_aum."  ";
	}
	if($fs_province != ""){$fs_conaddtxt = $fs_conaddtxt."จังหวัด".$fs_province."  ";}
	if($fs_post != ""){ $fs_conaddtxt = $fs_conaddtxt.$fs_post; }	

	$fs_stat_add=$_POST["f_extadd"];
	if($fs_stat_add==2){
		$fs_ext=$_POST["f_ext"];
		$fs_conadd=$fs_ext;
	}
	else{
		$fs_conadd=$fs_conaddtxt;
			
	}
	$CusID=$_POST["CusID"];
	$Cus=substr($_POST["CusID"],0,2);
	
	
	// อันดับแรกให้ตรวจสอบก่อนว่าได้มีการแก้ไขข้อมูลหรือไม่ถ้าไม่มีการแก้ไขก็ไม่ให้บันทึก
	
	
	
	

	// ข้อมูลเก่าที่ถูกบันทึก
	if($Cus=="CT"){
	
		// ------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
		$sql_check_name = pg_query("select * from \"Fa1\" where \"A_NAME\" = '$fs_name' and \"A_SIRNAME\" = '$fs_surname' ");
		$row_check_name = pg_num_rows($sql_check_name);
		if($row_check_name > 0)
		{
			$status++;
			$error_check = "มีลูกค้าคนนี้อยู่แล้ว2";
		}
		
		$check_card = str_replace(" ","",$fs_cardid);
		$check_card = str_replace("-","",$check_card);
		
		if($check_card != ""){
			$sql_check_idcard_CT = pg_query("select \"N_IDCARD\" from \"Customer_Temp\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$check_card' and statusapp = '2' ");
			$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
			if($row_check_idcard_CT > 0)
			{
				$status++;
				$error_check = "ลูกค้าคนนี้รอการอนุมัติอยู่";
			}
			
			$sql_check_idcard = pg_query("select \"N_IDCARD\" from \"Fn\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$check_card'");
			$row_check_idcard = pg_num_rows($sql_check_idcard);
			if($row_check_idcard > 0)
			{
				$status++;
				$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
			}
		}else{
			$sql_check_idcard_CT = pg_query("select \"N_CARDREF\" from \"Customer_Temp\" where replace(replace(\"N_CARDREF\",' ',''),'-','') = '$cardref' and statusapp = '2' ");
			$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
			if($row_check_idcard_CT > 0)
			{
				$status++;
				$error_check = "ลูกค้าคนนี้รอการอนุมัติอยู่";
			}
			
			$sql_check_idcard = pg_query("select \"N_CARDREF\" from \"Fn\" where replace(replace(\"N_CARDREF\",' ',''),'-','') = '$cardref'");
			$row_check_idcard = pg_num_rows($sql_check_idcard);
			if($row_check_idcard > 0)
			{
				$status++;
				$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
			}
		}
		
		
		
		
	
		$qry_fa1=pg_query("select * from \"Customer_Temp\" where \"CusID\"='$CusID'");
		$res_fa1=pg_fetch_array($qry_fa1);
		$fa1_firname=trim($res_fa1["A_FIRNAME"]);
		$fa1_name=trim($res_fa1["A_NAME"]);
		$fa1_surname=trim($res_fa1["A_SIRNAME"]);
		$fa1_pair=trim($res_fa1["A_PAIR"]);
		$fa1_no=trim($res_fa1["A_NO"]);
		$fa1_subno=trim($res_fa1["A_SUBNO"]);
		$fa1_soi=trim($res_fa1["A_SOI"]);
		$fa1_rd=trim($res_fa1["A_RD"]);	
		$fa1_tum=trim($res_fa1["A_TUM"]);	
		$fa1_aum=trim($res_fa1["A_AUM"]);
		$fa1_pro=trim($res_fa1["A_PRO"]);	
		$fa1_post=trim($res_fa1["A_POST"]);
		$ext_addr=trim($res_fa1["N_ContactAdd"]);	
		$N_SAN=trim($res_fa1["N_SAN"]);
		$N_AGE=trim($res_fa1["N_AGE"]);
		$N_CARD=trim($res_fa1["N_CARD"]);
		$N_IDCARD=trim($res_fa1["N_IDCARD"]);
		$N_OT_DATE=trim($res_fa1["N_OT_DATE"]);
		$N_BY=trim($res_fa1["N_BY"]);
		$N_OCC=trim($res_fa1["N_OCC"]);
		
		$fa1_firname_eng=trim($res_fa1["A_FIRNAME_ENG"]);
		$fa1_name_eng=trim($res_fa1["A_NAME_ENG"]);
		$fa1_surname_eng=trim($res_fa1["A_SIRNAME_ENG"]);
		$fa1_nickname=trim($res_fa1["A_NICKNAME"]);
		$fa1_status=trim($res_fa1["A_STATUS"]);
		$fa1_revenue=trim($res_fa1["A_REVENUE"]);
		$fa1_education=trim($res_fa1["A_EDUCATION"]);
		$fa1_country=trim($res_fa1["addr_country"]);
		$fa1_mobile=trim($res_fa1["A_MOBILE"]);
		$fa1_telephone=trim($res_fa1["A_TELEPHONE"]);
		$fa1_email=trim($res_fa1["A_EMAIL"]);
		$fa1_brithday=trim($res_fa1["A_BIRTHDAY"]);
		
		$fa1_A_SEX=trim($res_fa1["A_SEX"]);
		$fa1_A_ROOM=trim($res_fa1["A_ROOM"]);
		$fa1_A_FLOOR=trim($res_fa1["A_FLOOR"]);
		$fa1_A_BUILDING=trim($res_fa1["A_BUILDING"]);
		$fa1_A_VILLAGE=trim($res_fa1["A_VILLAGE"]);
		
	}else{
	
		$sql_check_idcard_CT = pg_query("select \"N_IDCARD\" from \"Customer_Temp\" where \"CusID\" ='$CusID' and statusapp = '2' ");
		$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
		if($row_check_idcard_CT > 0)
		{
			$status++;
			$error_check = "ลูกค้าคนนี้รอการอนุมัติอยู่";
		}	
	
	
		$qry_fa1=pg_query("select * from \"Fa1\" where \"CusID\" ='$CusID' ");
		$res_fa1=pg_fetch_array($qry_fa1);
		$fa1_firname=trim($res_fa1["A_FIRNAME"]);
		$fa1_name=trim($res_fa1["A_NAME"]);
		$fa1_surname=trim($res_fa1["A_SIRNAME"]);
		$fa1_pair=trim($res_fa1["A_PAIR"]);
		$fa1_no=trim($res_fa1["A_NO"]);
		$fa1_subno=trim($res_fa1["A_SUBNO"]);
		$fa1_soi=trim($res_fa1["A_SOI"]);
		$fa1_rd=trim($res_fa1["A_RD"]);	
		$fa1_tum=trim($res_fa1["A_TUM"]);	
		$fa1_aum=trim($res_fa1["A_AUM"]);
		$fa1_pro=trim($res_fa1["A_PRO"]);	
		$fa1_post=trim($res_fa1["A_POST"]);
		
		$fa1_firname_eng=trim($res_fa1["A_FIRNAME_ENG"]);
		$fa1_name_eng=trim($res_fa1["A_NAME_ENG"]);
		$fa1_surname_eng=trim($res_fa1["A_SIRNAME_ENG"]);
		$fa1_nickname=trim($res_fa1["A_NICKNAME"]);
		$fa1_status=trim($res_fa1["A_STATUS"]);
		$fa1_revenue=trim($res_fa1["A_REVENUE"]);
		$fa1_education=trim($res_fa1["A_EDUCATION"]);
		$fa1_country=trim($res_fa1["addr_country"]);
		$fa1_mobile=trim($res_fa1["A_MOBILE"]);
		$fa1_telephone=trim($res_fa1["A_TELEPHONE"]);
		$fa1_email=trim($res_fa1["A_EMAIL"]);
		$fa1_brithday=trim($res_fa1["A_BIRTHDAY"]);
		
		$fa1_A_SEX=trim($res_fa1["A_SEX"]);
		$fa1_A_ROOM=trim($res_fa1["A_ROOM"]);
		$fa1_A_FLOOR=trim($res_fa1["A_FLOOR"]);
		$fa1_A_BUILDING=trim($res_fa1["A_BUILDING"]);
		$fa1_A_VILLAGE=trim($res_fa1["A_VILLAGE"]);

		$qry_Fn=pg_query("select * from \"Fn\" where \"CusID\" ='$CusID' ");
		$res_fn1=pg_fetch_array($qry_Fn);
		$ext_addr=trim($res_fn1["N_ContactAdd"]);	
		$N_SAN=trim($res_fn1["N_SAN"]);
		$N_AGE=trim($res_fn1["N_AGE"]);
		$N_CARD=trim($res_fn1["N_CARD"]);
		$N_IDCARD=trim($res_fn1["N_IDCARD"]);
		$N_OT_DATE=trim($res_fn1["N_OT_DATE"]);
		$N_BY=trim($res_fn1["N_BY"]);
		$N_OCC=trim($res_fn1["N_OCC"]);
		$N_CARDREF=trim($res_fn1["N_CARDREF"]);
	}
	// ตรวจสอบว่ามีการแก้ไขข้อมูลหรือไม่
	if($fs_firname==$fa1_firname && $fs_san==$N_SAN && $fs_name==$fa1_name && $fs_age==$N_AGE && $fs_surname==$fa1_surname && $fs_card==$N_CARD && $fs_pair==$fa1_pair 
	&& $fs_cardid==$N_IDCARD && $fs_no==$fa1_no && $fs_datecard==$N_OT_DATE && $fs_subno==$fa1_subno && $fs_cardby==$N_BY && $fs_soi==$fa1_soi 
	&& $fs_conadd==$ext_addr && $fs_rd==$fa1_rd && $fs_tum==$fa1_tum && $fs_aum==$fa1_aum && $fs_province==$fa1_pro && $fs_post==$fa1_post && $fs_occ==$N_OCC 
	&& $fs_firname_eng==$fa1_firname_eng && $fs_name_eng==$fa1_name_eng && $fs_surname_eng==$fa1_surname_eng && $fs_nickname==$fa1_nickname && $fs_status==$fa1_status 
	&& $fs_revenue==$fa1_revenue && $fs_education==$fa1_education && $fs_country==$fa1_country && $fs_mobile==$fa1_mobile && $fs_telephone==$fa1_telephone && $fs_email==$fa1_email
	&& $fs_birthday==$fa1_brithday && $fa1_A_SEX==$A_SEX2 && $fa1_A_ROOM==$A_ROOM && $fa1_A_FLOOR==$A_FLOOR && $fa1_A_BUILDING==$A_BUILDING && $fa1_A_VILLAGE==$A_VILLAGE && $cardref==$N_CARDREF){
		$status=-1;
	}else{
		// กรณีข้อมูลมีการแก้ไข
		$qury_fn=pg_query("select \"N_STATE\" from \"Fn\" where \"CusID\"='$CusID'");
		$res_fn=pg_fetch_array($qury_fn);
		$N_STATE=$res_fn["N_STATE"];
		if($Cus=="CT"){
			$N_STATE=0;
		}
		// ต้องค้นหาก่อนว่ามีการแก้ไข record นี้กี่ครั้งแล้วเพื่อหาค่า edittime ต่อไป
		$qry_count=pg_query("select MAX(\"edittime\") as numtime from \"Customer_Temp\" where \"CusID\"='$CusID'");
		$num_count=pg_num_rows($qry_count);
		$cno=0;
		if($num_count==0){
			$countcus=1; //แสดงว่ามีการแก้ไขข้อมูลโดยไม่ได้เพิ่มข้อมูลจากเมนูใหม่ จึงกำหนดให้ edittime=1
		}else{
			$rescount=pg_fetch_array($qry_count);
			$cno=$rescount["numtime"];
			$countcus=$rescount["numtime"] + 1;
		}
		//--ตัวแปรที่ยังไม่มีข้อมูลเก็บ
		$fs_education2 = checknull($fs_education2);
		$fs_revenue2 = checknull($fs_revenue2);
		$cardref = checknull($cardref); //ตรวจสอบว่าเลขบัตรอื่นๆเป็น null หรือไม่
		$fs_cardid = checknull($fs_cardid);
		
		$fs_province_checknull = checknull($fs_province); // ตัวที่ใช้ในการ insert จังหวัด
		
		$insert_Fa1="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
					\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
					\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
					\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"addr_country\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"N_CARDREF\",\"A_BIRTHDAY\",
					\"A_SEX\",\"A_ROOM\",\"A_FLOOR\",\"A_BUILDING\",\"A_VILLAGE\",statuscus)
				VALUES ('$CusID','$add_user','$add_date','2','$countcus','$fs_firname', '$fs_name', '$fs_surname', '$fs_pair', '$fs_no',
					'$fs_subno', '$fs_soi', '$fs_rd', '$fs_tum', '$fs_aum', $fs_province_checknull, '$fs_post',$fs_san, $fs_age, '$fs_card', $fs_cardid, 
					$fs_datecard,$fs_cardby, $fs_occ, '$fs_conadd',$N_STATE,$fs_fri_name_eng,$fs_name_eng,$fs_surname_eng,
					$fs_nickname,$fs_status,$fs_revenue,$fs_education,$fs_country,$fs_mobile,$fs_telephone,$fs_email,$cardref,$fs_birthday,
					$A_SEX,$A_ROOM,$A_FLOOR,$A_BUILDING,$A_VILLAGE,$statuscus)";

		// ตรวจสอบผลการ Query ดูว่ามีปัญหาใดหรือไม่
		if($result=pg_query($insert_Fa1)){
		}else{
			$status++;
			$error=$result;
		}
	}
}

// --------------------------------------------------------------------------------------------  ทำ Transaction (END)
if($appvedit=="no"){
	pg_query("ROLLBACK");	
	echo "<center></br>ไม่สามารถบันทึกข้อมูลได้ เนื่องจาก คนที่แก้ไขกับคนที่เพิ่มข้อมูลจะต้องเป็นคนละคนกัน</br></center>";
	echo "<br>";
	echo "<center><input type=\"button\" value=\"กลับ\" onclick=\"location.href='frm_IndexEdit.php'\"></center>";
	}
else{
if($status==0){// ทำรายการสมบูรณ์
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$add_user', '(ALL) ขอเพิ่มข้อมูลลูกค้า', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	if($method=="add"){
		echo "----------------</br>บันทึกเพิ่มข้อมูลลูกค้าเรียบร้อยแล้ว</br>";
		echo "<meta http-equiv='refresh' content='2; URL=frm_Index.php'>";
	}else{
		//ถ้าหากมีการให้อนุมัติอัตโนมัติโดยระบบ
		if($autoapp == 't'){
			$qry_maxtempid = pg_query("		SELECT MAX(\"CustempID\") as \"maxcustemp\"
											FROM 	\"Customer_Temp\" 
											WHERE 	\"CusID\" = '$CusID' AND 
													\"statusapp\" = '2'
									 ");
			list($maxtempid) = pg_fetch_array($qry_maxtempid);						 
			
			$_SESSION["auth_refferer"] = 'pass';
			$_SESSION["auth_cusid"] = $maxtempid;
			
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=process_approve.php?CustempID=$maxtempid&edittime=$countcus&stsapp=1&autoapp=$autoapp&update_gather=$update_gather\">";
			
		
		}else{
			echo "----------------</br>บันทึกแก้ไขข้อมูลลูกค้าเรียบร้อยแล้ว</br>";
			echo "<meta http-equiv='refresh' content='2; URL=frm_IndexEdit.php'>";
		}	
	}
}else if($status>0){ // ทำรายการไม่สมบูรณ์
	pg_query("ROLLBACK");
	echo "<center></br>บันทึกเพิ่มข้อมูลลูกค้าผิดพลาด</br>";
	echo "---- ".$error_check." ----</center>";
	echo "<br>";
	if($method=="add"){
		echo "<meta http-equiv='refresh' content='5; URL=frm_Index.php'>";
	}else{
		echo "<meta http-equiv='refresh' content='5; URL=frm_IndexEdit.php'>";
	}
}else{
	echo "----------------</br>ไม่มีการแก้ไขข้อมูล ระบบจะกลับไปยังหน้าหลัก</br>";
	echo "<meta http-equiv='refresh' content='3; URL=frm_IndexEdit.php'>";
}
}


?>
