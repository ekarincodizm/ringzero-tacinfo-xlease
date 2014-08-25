<?php
session_start();
include("../config/config.php");
include("../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว

/*  คำอธิบายไฟล์
	ins_cusdata.php
	ดัดแปลงมาจากหน้า edit_cusdata.php
	ใช้สำหรับรับค่าจากหน้า frm_cusedit หรือหน้าอื่นใดเพื่อจะ Insert ข้อมูลของลูกค้าใหม่ลงระบบ
	ใน table public.fa1 กับ public.fn
*/

// ------------  รับข้อมูลที่ POST มาจาก FORM ของหน้าก่อนหน้า (START)
$fs_idno=$_POST["fidno"];
$fs_cusid=$_POST["fcus_id"];
$fs_carid=$_POST["fcar_id"];

$fs_firname=$_POST["f_fri_name"];
$fs_name=$_POST["f_name"];
$fs_surname=$_POST["f_surname"];
$fs_pair=$_POST["f_pair"];
$fs_no=$_POST["f_no"];
$fs_subno=$_POST["f_subno"];
$fs_soi=$_POST["f_soi"];
$fs_rd=$_POST["f_rd"];
$fs_aum=$_POST["f_aum"];
$fs_tum=$_POST["f_tum"];
$fs_province=$_POST["f_province"];
$fs_post=$_POST["f_post"];

$fs_carname=$_POST["f_carname"];
$fs_caryear=$_POST["f_caryear"];
$fs_carnum=$_POST["f_carnum"];
$fs_carmar=$_POST["f_carmar"];
$fs_carregis=$_POST["f_carregis"];
$fs_carregis_by=$_POST["f_pprovince"];
$fs_carcolor=$_POST["f_carcolor"];
$fs_carmi=$_POST["f_carmi"];
$fs_exp_date=$_POST["f_exp_date"];

$fs_san=$_POST["f_san"];
$fs_age=$_POST["f_age"];
$fs_birthday=trim($_POST["f_brithday"]);
$fs_card=$_POST["f_card"];
$fs_cardid=$_POST["f_cardid"];
$fs_datecard=$_POST["f_datecard"];
$fs_cardby=$_POST["f_card_by"];
$fs_occ=$_POST["f_occ"];

// ------------  รับข้อมูลที่ POST มาจาก FORM ของหน้าก่อนหน้า (END)


// --------------------------------------------------------------------------------------------  ทำ Transaction (START)
pg_query("BEGIN WORK"); $trans=0;

//------ ตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
	$cus_sn = GenCus();
//----------------------

//------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
$sql_check_name = pg_query("select * from \"Fa1\" where \"A_NAME\" = '$fs_name' and \"A_SIRNAME\" = '$fs_surname' ");
$row_check_name = pg_num_rows($sql_check_name);
if($row_check_name > 0)
{
	$status++;
	$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
}

// ------------ นำข้อมูลที่ได้จากการ POST มา UPDATE ลูกค้าที่ถูกแก้ไขข้อมูล (START)
$insert_Fa1="INSERT INTO \"Fa1\"(
				\"CusID\", \"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
				\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"A_BIRTHDAY\")
			VALUES ('$cus_sn', '$fs_firname', '$fs_name', '$fs_surname', '$fs_pair', '$fs_no',
				'$fs_subno', '$fs_soi', '$fs_rd', '$fs_tum', '$fs_aum', '$fs_province', '$fs_post','$fs_birthday')";

// ตรวจสอบผลการ Query ดูว่ามีปัญหาใดหรือไม่
if($result=pg_query($insert_Fa1)){
	$st1="ข้อมูลลูกค้าบันทึกถูกต้อง";
}
else{
	$trans++;
	$st1="พบปัญหาที่ ".$insert_Fa1." มีผลว่า ".$result."</br>";
}
// ------------ นำข้อมูลที่ได้จากการ POST มา UPDATE ลูกค้าที่ถูกแก้ไขข้อมูล (END)


// ------------ จัดการเรื่องข้อมูลที่อยู่ให้นำมารวมกันอยู่ในตัวแปรเดียว > $fs_conadd (START)
$fs_stat_add=$_POST["f_extadd"];
if($fs_stat_add==2){
	$fs_ext=$_POST["f_ext"];
	$fs_conadd=$fs_ext;
}
else{

	if($fs_no != ""){ $fs_notxt = 'บ้านเลขที่'.$fs_no; }
	if($fs_subno != ""){ $fs_subnotxt = ' หมู่'.$fs_subno; }
	if($fs_soi != ""){ $fs_soitxt = ' ซอย'.$fs_soi; }
	if($fs_rd != ""){ $fs_rdtxt = ' ถนน'.$fs_rd; }
	if($fs_province == "กรุงเทพมหานคร"){
		$fs_tumtxt = ' แขวง'.$fs_tum; 
		$fs_aumtxt = ' เขต'.$fs_aum;
	}else{
		$fs_tumtxt = ' ตำบล'.$fs_tum;
		$fs_aumtxt = ' อำเภอ'.$fs_aum;
	}
	$fs_provincetxt = " จังหวัด".$fs_province;

	$fs_conadd= trim($fs_notxt)." ".trim($fs_subnotxt)." ".trim($fs_soitxt)." ".trim($fs_rdtxt)." ".trim($fs_tumtxt)." ".trim($fs_aumtxt)." ".trim($fs_provincetxt)." ".trim($fs_post);

}
// ------------ จัดการเรื่องข้อมูลที่อยู่ให้นำมารวมกันอยู่ในตัวแปรเดียว > $fs_conadd (END)

//------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
	$check_card = str_replace(" ","",$fs_cardid);
	$check_card = str_replace("-","",$check_card);
	$sql_check=pg_query("select \"N_IDCARD\" from \"Fn\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$check_card'");
	$row_check = pg_num_rows($sql_check);
	if($row_check > 0)
	{
		$status++;
		$error_check = "มีลูกค้าคนนี้อยู่แล้ว";
	}

// ------------ นำข้อมูลที่ได้จากการ POST มา UPDATE ลูกค้าที่ถูกแก้ไขข้อมูล (START)
$insert_Fn="INSERT INTO \"Fn\"(
				\"CusID\", \"N_STATE\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",
				\"N_BY\", \"N_OCC\", \"N_ContactAdd\")
			VALUES ('$cus_sn', '0', '$fs_san', '$fs_age', '$fs_card', '$fs_cardid', '$fs_datecard',
				'$fs_cardby', '$fs_occ', '$fs_conadd')";

if($result=pg_query($insert_Fn)){
	$st2="ข้อมูลลูกค้าเพิ่มเติมบันทึกถูกต้อง";
	$st_fn="OK".$insert_Fn;
}else{
	$trans++;
	$st2="พบปัญหาที่ ".$insert_Fn." มีผลว่า ".$result."</br>";
}
// ------------ นำข้อมูลที่ได้จากการ POST มา UPDATE ลูกค้าที่ถูกแก้ไขข้อมูล (END)


// --------------------------------------------------------------------------------------------  ทำ Transaction (END)
if($trans==0){// ทำรายการสมบูรณ์
	pg_query("COMMIT");
	//echo $st1."<br>";
	//echo $st2."<br>";
	echo "----------------</br>บันทึกเพิ่มข้อมูลลูกค้าเรียบร้อยแล้ว</br>";
}else{ // ทำรายการไม่สมบูรณ์
	pg_query("ROLLBACK");
	echo $st1."<br>";
	echo $st2."<br>";
	echo $error_check."<br>";
	echo "ยกเลิกการทำรายการ";
}

?>
<input type="button" value="กลับเมนูหลัก" onclick="window.location='../list_menu.php'" />