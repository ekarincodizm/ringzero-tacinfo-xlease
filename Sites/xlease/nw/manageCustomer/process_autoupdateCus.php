<?php
session_start();
include("../../config/config.php");
include("../../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว
include("../function/checknull.php");
$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
?>

<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script>

<?php
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
$fs_province=trim($_POST["f_province"]);
$fs_post=trim($_POST["f_post"]);

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

$fs_san=trim($_POST["f_san"]);
$fs_age=trim($_POST["f_age"]);
$fs_card=trim($_POST["f_card"]);
$fs_cardid=trim($_POST["f_cardid"]);
$fs_datecard=trim($_POST["f_datecard"]);
$fs_cardby=trim($_POST["f_card_by"]);
$fs_occ=trim($_POST["f_occ"]);
$method=trim($_POST["method"]);

pg_query("BEGIN WORK");
$status=0;

if($method=="edit"){ //กรณีแก้ไขข้อมูลลูกค้าจะไม่มีการ Gen ID ใหม่


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



	$CusID=$_POST["CusID"];
	$Cus=substr($_POST["CusID"],0,2);
	$fs_stat_add=$_POST["f_extadd"];
	if($fs_stat_add==2){
		$fs_ext=trim($_POST["f_ext"]);
		$fs_conadd=$fs_ext;
	}
	else{
		$fs_conadd=trim($fs_notxt)." ".trim($fs_subnotxt)." ".trim($fs_soitxt)." ".trim($fs_rdtxt)." ".trim($fs_tumtxt)." ".trim($fs_aumtxt)." ".trim($fs_provincetxt)." ".trim($fs_post);		
	}
	// อันดับแรกให้ตรวจสอบก่อนว่าได้มีการแก้ไขข้อมูลหรือไม่ถ้าไม่มีการแก้ไขก็ไม่ให้บันทึก
	
	// ข้อมูลเก่าที่ถูกบันทึก
	if($Cus=="CT"){
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
		
	}else{
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
	}
	// ตรวจสอบว่ามีการแก้ไขข้อมูลหรือไม่
	if($fs_firname==$fa1_firname && $fs_san==$N_SAN && $fs_name==$fa1_name && $fs_age==$N_AGE && $fs_surname==$fa1_surname && $fs_card==$N_CARD && $fs_pair==$fa1_pair 
	&& $fs_cardid==$N_IDCARD && $fs_no==$fa1_no && $fs_datecard==$N_OT_DATE && $fs_subno==$fa1_subno && $fs_cardby==$N_BY && $fs_soi==$fa1_soi 
	&& $fs_conadd==$ext_addr && $fs_rd==$fa1_rd && $fs_tum==$fa1_tum && $fs_aum==$fa1_aum && $fs_province==$fa1_pro && $fs_post==$fa1_post && $fs_occ==$N_OCC 
	&& $fs_firname_eng==$fa1_firname_eng && $fs_name_eng==$fa1_name_eng && $fs_surname_eng==$fa1_surname_eng && $fs_nickname==$fa1_nickname && $fs_status==$fa1_status 
	&& $fs_revenue==$fa1_revenue && $fs_education==$fa1_education && $fs_country==$fa1_country && $fs_mobile==$fa1_mobile && $fs_telephone==$fa1_telephone && $fs_email==$fa1_email){
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
		
		if($num_count==0){
			$countcus=1; //แสดงว่ามีการแก้ไขข้อมูลโดยไม่ได้เพิ่มข้อมูลจากเมนูใหม่ จึงกำหนดให้ edittime=1
		}else{
			$rescount=pg_fetch_array($qry_count);
			$countcus=$rescount["numtime"] + 1;
		}
		$fs_cardid = checknull($fs_cardid);
		
		//ตัดเครื่องหมาย ' ออก
		$fs_firname = str_replace("'","",$fs_firname);
		$fs_name = str_replace("'","",$fs_name);
		$fs_surname = str_replace("'","",$fs_surname);
		$fs_pair = str_replace("'","",$fs_pair);
		$fs_no = str_replace("'","",$fs_no);
		$fs_subno = str_replace("'","",$fs_subno);
		$fs_soi = str_replace("'","",$fs_soi);
		$fs_rd = str_replace("'","",$fs_rd);
		$fs_tum = str_replace("'","",$fs_tum);
		$fs_aum = str_replace("'","",$fs_aum);
		$fs_province = str_replace("'","",$fs_province);
		$fs_post = str_replace("'","",$fs_post);
		$fs_san = str_replace("'","",$fs_san);
		$fs_age = str_replace("'","",$fs_age);
		$fs_card = str_replace("'","",$fs_card);
		$fs_cardid = str_replace("'","",$fs_cardid);
		$fs_datecard = str_replace("'","",$fs_datecard);
		$fs_cardby = str_replace("'","",$fs_cardby);
		$fs_occ = str_replace("'","",$fs_occ);
		$fs_conadd = str_replace("'","",$fs_conadd);
		$N_STATE = str_replace("'","",$N_STATE);
		$fs_fri_name_eng = str_replace("'","",$fs_fri_name_eng);
		$fs_name_eng = str_replace("'","",$fs_name_eng);
		$fs_surname_eng = str_replace("'","",$fs_surname_eng);
		$fs_nickname = str_replace("'","",$fs_nickname);
		$fs_status = str_replace("'","",$fs_status);
		$fs_revenue2 = str_replace("'","",$fs_revenue2);
		$fs_education2 = str_replace("'","",$fs_education2);
		$fs_country = str_replace("'","",$fs_country);
		$fs_mobile = str_replace("'","",$fs_mobile);
		$fs_telephone = str_replace("'","",$fs_telephone);
		$fs_email = str_replace("'","",$fs_email);
		$cardref = str_replace("'","",$cardref);
		
		//เช็คค่าว่างของตัวแปร เพื่อใช้ในการ insert ลงฐานข้อมูล
		$fs_firname = checknull($fs_firname);
		$fs_name = checknull($fs_name);
		$fs_surname = checknull($fs_surname);
		$fs_pair = checknull($fs_pair);
		$fs_no = checknull($fs_no);
		$fs_subno = checknull($fs_subno);
		$fs_soi = checknull($fs_soi);
		$fs_rd = checknull($fs_rd);
		$fs_tum = checknull($fs_tum);
		$fs_aum = checknull($fs_aum);
		$fs_province = checknull($fs_province);
		$fs_post = checknull($fs_post);
		$fs_san = checknull($fs_san);
		$fs_age = checknull($fs_age);
		$fs_card = checknull($fs_card);
		$fs_cardid = checknull($fs_cardid);
		$fs_datecard = checknull($fs_datecard);
		$fs_cardby = checknull($fs_cardby);
		$fs_occ = checknull($fs_occ);
		$fs_conadd = checknull($fs_conadd);
		$N_STATE = checknull($N_STATE);
		$fs_fri_name_eng = checknull($fs_fri_name_eng);
		$fs_name_eng = checknull($fs_name_eng);
		$fs_surname_eng = checknull($fs_surname_eng);
		$fs_nickname = checknull($fs_nickname);
		$fs_status = checknull($fs_status);
		$fs_revenue2 = checknull($fs_revenue2);
		$fs_education2 = checknull($fs_education2);
		$fs_country = checknull($fs_country);
		$fs_mobile = checknull($fs_mobile);
		$fs_telephone = checknull($fs_telephone);
		$fs_email = checknull($fs_email);
		$cardref = checknull($cardref);
		
		//ตัดเครื่องหมาย ' ออก กรณีเป็น null
		$fs_firname = str_replace("'null'","null",$fs_firname);
		$fs_name = str_replace("'null'","null",$fs_name);
		$fs_surname = str_replace("'null'","null",$fs_surname);
		$fs_pair = str_replace("'null'","null",$fs_pair);
		$fs_no = str_replace("'null'","null",$fs_no);
		$fs_subno = str_replace("'null'","null",$fs_subno);
		$fs_soi = str_replace("'null'","null",$fs_soi);
		$fs_rd = str_replace("'null'","null",$fs_rd);
		$fs_tum = str_replace("'null'","null",$fs_tum);
		$fs_aum = str_replace("'null'","null",$fs_aum);
		$fs_province = str_replace("'null'","null",$fs_province);
		$fs_post = str_replace("'null'","null",$fs_post);
		$fs_san = str_replace("'null'","null",$fs_san);
		$fs_age = str_replace("'null'","null",$fs_age);
		$fs_card = str_replace("'null'","null",$fs_card);
		$fs_cardid = str_replace("'null'","null",$fs_cardid);
		$fs_datecard = str_replace("'null'","null",$fs_datecard);
		$fs_cardby = str_replace("'null'","null",$fs_cardby);
		$fs_occ = str_replace("'null'","null",$fs_occ);
		$fs_conadd = str_replace("'null'","null",$fs_conadd);
		$N_STATE = str_replace("'null'","null",$N_STATE);
		$fs_fri_name_eng = str_replace("'null'","null",$fs_fri_name_eng);
		$fs_name_eng = str_replace("'null'","null",$fs_name_eng);
		$fs_surname_eng = str_replace("'null'","null",$fs_surname_eng);
		$fs_nickname = str_replace("'null'","null",$fs_nickname);
		$fs_status = str_replace("'null'","null",$fs_status);
		$fs_revenue2 = str_replace("'null'","null",$fs_revenue2);
		$fs_education2 = str_replace("'null'","null",$fs_education2);
		$fs_country = str_replace("'null'","null",$fs_country);
		$fs_mobile = str_replace("'null'","null",$fs_mobile);
		$fs_telephone = str_replace("'null'","null",$fs_telephone);
		$fs_email = str_replace("'null'","null",$fs_email);
		$cardref = str_replace("'null'","null",$cardref);
		
		$insert_Customer_Temp = "INSERT INTO \"Customer_Temp\"(
									\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
									\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
									\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
									\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"addr_country\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"N_CARDREF\")
								VALUES ('$CusID','$add_user','$add_date','000','$add_date','1','$countcus',$fs_firname, $fs_name, $fs_surname, $fs_pair, $fs_no,
									$fs_subno, $fs_soi, $fs_rd, $fs_tum, $fs_aum, $fs_province, $fs_post,$fs_san, $fs_age, $fs_card, $fs_cardid, 
									$fs_datecard,$fs_cardby, $fs_occ, $fs_conadd,$N_STATE,$fs_fri_name_eng,$fs_name_eng,$fs_surname_eng,
									$fs_nickname,$fs_status,$fs_revenue2,$fs_education2,$fs_country,$fs_mobile,$fs_telephone,$fs_email,$cardref)";
		// ตรวจสอบผลการ Query ดูว่ามีปัญหาใดหรือไม่
		if($result=pg_query($insert_Customer_Temp)){
		}else{
			$status++;
			$error=$result;
		}
		
		$test_sql1="update public.\"Fa1\" set \"A_PAIR\"=$fs_pair , \"A_PRO\"=$fs_province, \"A_POST\"=$fs_post
					, \"A_NO\"=$fs_no, \"A_SUBNO\"=$fs_subno, \"A_SOI\"=$fs_soi, \"A_RD\"=$fs_rd, \"A_TUM\"=$fs_tum, \"A_AUM\"=$fs_aum
					, \"A_FIRNAME\" = $fs_firname , \"A_NAME\" = $fs_name , \"A_SIRNAME\" = $fs_surname , \"A_STATUS\" = $fs_status
					, \"A_FIRNAME_ENG\" = $fs_fri_name_eng, \"A_NAME_ENG\" = $fs_name_eng, \"A_SIRNAME_ENG\" = $fs_surname_eng, \"A_NICKNAME\" = $fs_nickname
					, \"A_REVENUE\" = $fs_revenue2, \"A_EDUCATION\" = $fs_education2, \"addr_country\" = $fs_country, \"A_MOBILE\" = $fs_mobile
					, \"A_TELEPHONE\" = $fs_telephone, \"A_EMAIL\" = $fs_email
					where \"CusID\"='$CusID'";
		if($result1=pg_query($test_sql1)){
			}else{
				$status++;
			}
			
		$test_sql2="update public.\"Fn\" set \"N_AGE\"=$fs_age, \"N_CARD\"=$fs_card, \"N_IDCARD\"=$fs_cardid, \"N_OT_DATE\"=$fs_datecard
					, \"N_BY\"=$fs_cardby, \"N_SAN\"=$fs_san, \"N_OCC\"=$fs_occ, \"N_ContactAdd\"=$fs_conadd, \"N_STATE\" = $N_STATE, \"N_CARDREF\" = $cardref
					where \"CusID\"='$CusID'";
		if($result2=pg_query($test_sql2)){
			}else{
				$status++;
			}
		//---------- จบการ update ข้อมูล
	}
}

// --------------------------------------------------------------------------------------------  ทำ Transaction (END)
if($status==0)
{// ทำรายการสมบูรณ์
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$add_user', '(ALL) ขอแก้ไขข้อมูลลูกค้า', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "----------------</br>บันทึกแก้ไขข้อมูลลูกค้าเรียบร้อยแล้ว</br>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_IndexEdit.php'>";
	echo "<center><input type=\"button\" value=\"     ตกลง     \" onclick=\"javascript:RefreshMe();\"></center>";
}
elseif($status>0){ // ทำรายการไม่สมบูรณ์
	pg_query("ROLLBACK");
	echo "----------------</br>บันทึกเพิ่มข้อมูลลูกค้าผิดพลาด</br>";
	echo $error_check;
	echo "<br>";
	echo $test_sql1;
	//echo "<meta http-equiv='refresh' content='3; URL=frm_Edit.php?CusID=$CusID&MigrateCus=yes'>";
	echo "<center><input type=\"button\" value=\"  Close  \" class=\"ui-button\" onclick=\"javascript:window.close();\"></center>";
}

?>