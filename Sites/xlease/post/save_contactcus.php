<?php
session_start();
include("../config/config.php");
include("../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว
include("../nw/function/checknull.php");


$idno=$_POST["fidno"];

$userid=$_SESSION['uid'];
$officeid=$_SESSION["av_officeid"];
$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$dat=date("Y/m/d");

// --- Start transactions ---
pg_query("BEGIN WORK"); $status = 0; // $trans - transactions check

//------ ตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
$cus_sn = GenCus();
//----------------------

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
$fs_province=trim($_POST["f_province"]);
$fs_post=trim($_POST["f_post"]);
$statuscus=trim($_POST["statuscus"]); //สถานะลูกค้า 0=คนไทย 1= ชาวต่างชาติ 2=บริษัท

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

$A_SEX2=trim($_POST["A_SEX"]); //เพศ
$A_SEX = checknull($A_SEX2); //ตรวจสอบว่าเลขบัตรอื่นๆเป็น null หรือไม่
$A_ROOM=trim($_POST["A_ROOM"]); //ห้อง
$A_FLOOR=trim($_POST["A_FLOOR"]); //ชั้น
$A_BUILDING=trim($_POST["A_BUILDING"]); //อาคาร/สถานที่
$A_VILLAGE=trim($_POST["A_VILLAGE"]); //หมู่บ้าน

$fs_san=trim($_POST["f_san"]);
$fs_age=trim($_POST["f_age"]);
$fs_birthday=trim($_POST["f_brithday"]);
$fs_card=trim($_POST["f_card"]);
$fs_cardid=trim($_POST["f_cardid"]);
if($statuscus!=0){
	$fs_cardid="";
}
$fs_datecard=trim($_POST["f_datecard"]);
$fs_cardby=trim($_POST["f_card_by"]);
$fs_occ=trim($_POST["f_occ"]);
$method=trim($_POST["method"]);

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
 
 
	// ------ ตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
	$cus_sn = GenCT();
	// ----------------------


	$fs_stat_add=$_POST["f_extadd"];
	if($fs_stat_add==2){
		$fs_ext=$_POST["f_ext"];
		$fs_conadd=$fs_ext;
	}
	else{
		$fs_conadd=trim($fs_no)." ".trim($fs_subno)." ".trim($fs_soi)." ".trim($fs_rd)." ".trim($fs_aum)." ".trim($fs_tum)." ".trim($fs_province)." ".trim($fs_post);
	}
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
	$sql_check_name = pg_query("select * from \"Fa1\" where \"A_NAME\" = '$fs_name' and \"A_SIRNAME\" = '$fs_surname' ");
	$row_check_name = pg_num_rows($sql_check_name);
	if($row_check_name > 0)
	{
		$status++;
		$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
	}
	
	$sql_check_idcard_CT = pg_query("select \"N_IDCARD\" from \"Customer_Temp\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$check_card' and statusapp in('1','2')");
	$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
	if($row_check_idcard_CT > 0)
	{
		$status++;
		$error_check = "ลูกค้าคนนี้รอการอนุมัติหรือมีข้อมูลอยู่แล้ว";
	}
	
	$sql_check_name_CT = pg_query("select * from \"Customer_Temp\" where \"A_NAME\" = '$fs_name' and \"A_SIRNAME\" = '$fs_surname' and statusapp in('1','2')");
	$row_check_name_CT = pg_num_rows($sql_check_name_CT);
	if($row_check_name_CT > 0)
	{
		$status++;
		$error_check = "ลูกค้าคนนี้รอการอนุมัติหรือมีข้อมูลอยู่แล้ว";
	}
	
	
	$fs_cardid = checknull($fs_cardid);
	// ------------ นำข้อมูลที่ได้จากการ POST มา UPDATE ลูกค้าที่ถูกแก้ไขข้อมูล (START)
	$insert_Fa1="INSERT INTO \"Customer_Temp\"(
				\"CusID\",\"add_user\",\"add_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
				\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
				\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
				\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"N_CARDREF\",\"addr_country\",\"A_BIRTHDAY\",
				\"A_SEX\",\"A_ROOM\",\"A_FLOOR\",\"A_BUILDING\",\"A_VILLAGE\",statuscus)
			VALUES ('$cus_sn','$add_user','$add_date','2','0','$fs_firname', '$fs_name', '$fs_surname', '$fs_pair', '$fs_no',
				'$fs_subno', '$fs_soi', '$fs_rd', '$fs_tum', '$fs_aum', '$fs_province', '$fs_post','$fs_san', '$fs_age', '$fs_card', $fs_cardid, 
				'$fs_datecard','$fs_cardby', '$fs_occ', '$fs_conadd','0','$fs_fri_name_eng','$fs_name_eng','$fs_surname_eng',
				'$fs_nickname','$fs_status',$fs_revenue2,$fs_education2,'$fs_mobile','$fs_telephone','$fs_email',$cardref,'$fs_country','$fs_birthday',
				$A_SEX,'$A_ROOM','$A_FLOOR','$A_BUILDING','$A_VILLAGE','$statuscus')";

	// ตรวจสอบผลการ Query ดูว่ามีปัญหาใดหรือไม่
	if($result=pg_query($insert_Fa1)){
	}else{
		$status++;
		$error=$result;
	}

$textName=$fs_firname." ".$fs_name."  ".$fs_surname;




$fs_stat_add=$_POST["f_extadd"];
if($fs_stat_add==2)
{
	$fs_ext=$_POST["f_ext"];
	$fs_conadd=$fs_ext;
}
else
{
	$fs_conadd=trim($fs_no)." ".trim($fs_subno)." ".trim($fs_soi)." ".trim($fs_rd)." ".trim($fs_aum)." ".trim($fs_tum)." ".trim($fs_province)." ".trim($fs_post);
} 

 
$add_ccus=pg_query("select count(*) AS c_cc from \"ContactCus\" WHERE \"IDNO\"='$idno' ");
$resccus=pg_fetch_array($add_ccus);
$cs_cc=$resccus["c_cc"];

$in_cus="insert into \"ContactCus\" (\"CusID\",\"CusState\",\"IDNO\") values ('$cus_sn','$cs_cc','$idno')";
if($result=pg_query($in_cus))
{
	$statuc ="OK at Fn".$in_cus;
}
else
{
	$status++; // $status > 0 กรณีทำรายการ SQL ไม่ผ่าน
	$statuc ="error insert  Fn Re".$in_cus;
}	 

 
/*** letter **/
$qry_lt=pg_query($db_connect,"select * from letter.send_address 
				where (\"IDNO\"='$idno') and (\"CusState\"='$cs_cc') and (active=TRUE);");
$numr_lt=pg_num_rows($qry_lt);
if($numr_lt==0)
{	 
	$gen_ltr=pg_query("select letter.gen_cusletid('$idno')"); //gen letter
	$res_genltr=pg_fetch_result($gen_ltr,0);
	
	
	$ins_send_ads="insert into letter.send_address 	
					   (\"CusLetID\",\"IDNO\",record_date,\"name\",active,userid,dtl_ads,\"CusState\")
					   values
					   ('$res_genltr','$idno','$add_date','$textName',TRUE,'$userid','$fs_conadd',$cs_cc)";
		 
	if($result=pg_query($db_connect,$ins_send_ads))
	{
		$status ="OK".$ins_send_ads;
	}
	else
	{
		$status++; // $trans > 0 กรณีทำรายการ SQL ไม่ผ่าน
		$status ="error insert Re".$ins_send_ads;
	}
		
	
	
}
else
{			
	$qry_lt2=pg_query($db_connect,"select * from letter.send_address 
	                              where (\"IDNO\"='$idno') and (\"CusState\"='$cs_cc') and (active=TRUE);");				  
	$res_idli=pg_fetch_array($qry_lt2);
	 
	$fs_ltsid=$res_idli["CusLetID"];
	 
	$in_lt="Update letter.send_address SET dtl_ads='$fs_conadd' WHERE \"CusLetID\"='$fs_ltsid' ";
	if($result=pg_query($db_connect,$in_lt))
	{
		$statuss ="OK update at Fn".$in_lt;
		$st="บันทึกข้อมูลเรียบร้อย";
	}
	else
	{
		$status++; // $status > 0 กรณีทำรายการ SQL ไม่ผ่าน
		$statuss ="error update  Fn Re".$in_lt;
		$st="เกิดข้อผิดพลาด";
	}
	 
}	 
/*************/

//นำข้อมูลในตาราง Fa1 มา insert ในตาราง Fp_Fa1
$insfpfa1="INSERT INTO \"Fp_Fa1\" (\"IDNO\", \"CusID\", \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", 
\"A_AUM\", \"A_PRO\", \"A_POST\", \"CusState\",\"addUser\",\"addStamp\",\"A_ROOM\",\"A_FLOOR\",\"A_BUILDING\",\"A_BAN\",statuscus)
  
SELECT a.\"IDNO\",b.\"CusID\",c.\"A_NO\",c.\"A_SUBNO\",c.\"A_SOI\",c.\"A_RD\",c.\"A_TUM\",
c.\"A_AUM\",c.\"A_PRO\",c.\"A_POST\",b.\"CusState\",'$add_user','$add_date',c.\"A_ROOM\",c.\"A_FLOOR\",c.\"A_BUILDING\",c.\"A_VILLAGE\",'$statuscus' FROM \"Fp\" a
LEFT JOIN \"ContactCus\" b on a.\"IDNO\"=b.\"IDNO\"
LEFT JOIN \"Fa1\" c on b.\"CusID\"=c.\"CusID\"
where b.\"IDNO\"='$idno' and b.\"CusState\"='$cs_cc'";

if($resinsfpfa1=pg_query($insfpfa1)){
}else{
	$status++;
}

// --- Tailer transactions ---
if($status == 0){
	pg_query("COMMIT");
	echo "<br>";
	echo "บันทึกข้อมูลเรียบร้อยแล้ว";
	echo "<br>";
}
else {
	pg_query("ROLLBACK");
	echo "<br>";
	echo $error_check."<br>";
	echo "การบันทึก/อัพเดทข้อมูล มีรายการผิดพลาด => ยกเลิกการทำรายการ";
	echo "<br>";
}
// --- End transactions ---

 
echo "<meta http-equiv=\"refresh\" content=\"5;URL=frm_edit.php?idnog=$idno\">"; 
?>