<?php
session_start();
include("../config/config.php");
include("../nw/function/checknull.php");

/*  คำอธิบายไฟล์
	edit_cusdata.php
	ใช้สำหรับรับค่าจากหน้า frm_cusedit หรือหน้าอื่นใดเพื่อ update ข้อมูลของลูกค้า โดยจะ UPDATE ลง
	table public.fa1 กับ public.fn
*/
$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
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
$A_ROOM=checknull(trim($_POST["A_ROOM"])); //ห้อง
$A_FLOOR=checknull(trim($_POST["A_FLOOR"])); //ชั้น
$A_BUILDING=checknull(trim($_POST["A_BUILDING"])); //อาคาร/สถานที่
$A_VILLAGE=checknull(trim($_POST["A_VILLAGE"])); //หมู่บ้าน

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

// --------------------------------------------------------------------------------------------  ทำ Transaction (START)
pg_query("BEGIN WORK"); 
	
$trans=0;	


	//ตรวจสอบรหัสบัตรประชาชน
	$chkidcard = pg_query("select \"N_IDCARD\" from \"Fn\" where \"CusID\" = '$fs_cusid'");
	list($chkidcardsame) = pg_fetch_array($chkidcard);
	//รหัสเก่าตัดส่วนที่ไม่ต้องการออกให้เหลือแต่ตัวเลข
	$chkidcardsame = str_replace(" ","",$chkidcardsame);
	$chkidcardsame = str_replace("-","",$chkidcardsame);
	//รหัสใหม่ตัดส่วนที่ไม่ต้องการออกให้เหลือแต่ตัวเลข
	$check_card = str_replace(" ","",$fs_cardid);
	$check_card = str_replace("-","",$check_card);
	//หากไม่ตรงกันแสดงว่ามีการเปลี่ยนรหัสบัตรประชาชน
	if($chkidcardsame != $check_card){
			//ค้นหาบัตรประชาชนใหม่ในฐานข้อมูลหากมีแล้วแสดงว่าซ้ำ
			$sql_check_idcard_CT = pg_query("select \"N_IDCARD\" from \"Fn\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$check_card'");
			$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
			if($row_check_idcard_CT > 0)
			{
				$trans++;
				$error_check = "บัตรประชาชนซ้ำ";
			}
			
			//หากบัตรประชาชนถูกต้องให้มาตรวจบัตรอื่นๆด้วย
			if($cardref != ""){//หากบัตรอื่นๆถูกกรอกมาด้วย
				//ตรวจสอบรหัสบัตรอื่นๆ
				$chkidcardref = pg_query("select \"N_CARDREF\" from \"Fn\" where \"CusID\" = '$fs_cusid'");
				list($chkidcardredsame) = pg_fetch_array($chkidcardref);
				//รหัสเก่าตัดส่วนที่ไม่ต้องการออกให้เหลือแต่ตัวเลข
				$chkidcardredsame = str_replace(" ","",$chkidcardredsame);
				$chkidcardredsame = str_replace("-","",$chkidcardredsame);
				//รหัสใหม่ตัดส่วนที่ไม่ต้องการออกให้เหลือแต่ตัวเลข
				$check_cardref = str_replace(" ","",$cardref);
				$check_cardref = str_replace("-","",$check_cardref);
				//หากไม่ตรงกันแสดงว่ามีการเปลี่ยนรหัสบัตรอื่นๆ
				if($chkidcardredsame != $check_cardref){			
					$sql_check_idcard_CT = pg_query("select \"N_CARDREF\" from \"Fn\" where replace(replace(\"N_CARDREF\",' ',''),'-','') = '$check_cardref'");
					$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
					if($row_check_idcard_CT > 0)
					{
						$trans++;
						$error_check = "เลขบัตรอื่นๆซ้ำ";
					}
				}
			}	
			
			
	}else{ //หากบัตรประชาชนตรงกับในระบบ	
				//ตรวจสอบรหัสบัตรอื่นๆ
				$chkidcardref = pg_query("select \"N_CARDREF\" from \"Fn\" where \"CusID\" = '$fs_cusid'");
				list($chkidcardredsame) = pg_fetch_array($chkidcardref);
				//รหัสเก่าตัดส่วนที่ไม่ต้องการออกให้เหลือแต่ตัวเลข
				$chkidcardredsame = str_replace(" ","",$chkidcardredsame);
				$chkidcardredsame = str_replace("-","",$chkidcardredsame);
				//รหัสใหม่ตัดส่วนที่ไม่ต้องการออกให้เหลือแต่ตัวเลข
				$check_cardref = str_replace(" ","",$cardref);
				$check_cardref = str_replace("-","",$check_cardref);
				//หากไม่ตรงกันแสดงว่ามีการเปลี่ยนรหัสบัตรอื่นๆ
				if($chkidcardredsame != $check_cardref){			
					$sql_check_idcard_CT = pg_query("select \"N_CARDREF\" from \"Fn\" where replace(replace(\"N_CARDREF\",' ',''),'-','') = '$check_cardref'");
					$row_check_idcard_CT = pg_num_rows($sql_check_idcard_CT);
					if($row_check_idcard_CT > 0)
					{
						$trans++;
						$error_check = "เลขบัตรอื่นๆซ้ำ";
					}
				}
	}	

$cardref = checknull($cardref); //ตรวจสอบว่าเลขบัตรอื่นๆเป็น null หรือไม่
$fs_cardid = checknull($fs_cardid);

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
$insert_temp="INSERT INTO \"Customer_Temp\"(
			\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
			\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
			\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
			\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"addr_country\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"N_CARDREF\",\"A_BIRTHDAY\",
			\"A_SEX\",\"A_ROOM\",\"A_FLOOR\",\"A_BUILDING\",\"A_VILLAGE\")
		VALUES ('$fs_cusid','$add_user','$add_date','000','$add_date','1','$edittime','$fs_firname', '$fs_name', '$fs_surname', '$fs_pair', '$fs_no',
			'$fs_subno', '$fs_soi', '$fs_rd', '$fs_tum', '$fs_aum', '$fs_province', '$fs_post','$fs_san', '$fs_age', '$fs_card', $fs_cardid, 
			'$fs_datecard','$fs_cardby', '$fs_occ', '$fs_conadd','$N_STATE','$fs_fri_name_eng','$fs_name_eng','$fs_surname_eng',
			'$fs_nickname','$fs_status',$fs_revenue2,$fs_education2,'$fs_country','$fs_mobile','$fs_telephone','$fs_email',$cardref,'$fs_birthday',
			$A_SEX,$A_ROOM,$A_FLOOR,$A_BUILDING,$A_VILLAGE)";
if($res_temp=pg_query($insert_temp)){
}else{
	$trans++;
}

// ------------ นำข้อมูลที่ได้จากการ POST มา UPDATE ลูกค้าที่ถูกแก้ไขข้อมูล (START)
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
				\"A_BIRTHDAY\"='$fs_birthday',
				\"A_SEX\"=$A_SEX,
				\"A_ROOM\"=$A_ROOM,
				\"A_FLOOR\"=$A_FLOOR,
				\"A_BUILDING\"=$A_BUILDING,
				\"A_VILLAGE\"=$A_VILLAGE
			where \"CusID\"='$fs_cusid' ";

// ตรวจสอบผลการ Query ดูว่ามีปัญหาใดหรือไม่
if($result=pg_query($update_Fa1))
{
	$stc="ข้อมูลลูกค้าอัพเดทถูกต้อง";
}
else
{
	$trans++;
	$st1="พบปัญหาที่ ".$update_Fa1." มีผลว่า ".$result."</br>";
}
// ------------ นำข้อมูลที่ได้จากการ POST มา UPDATE ลูกค้าที่ถูกแก้ไขข้อมูล (END)


// ------------ จัดการเรื่องข้อมูลที่อยู่ให้นำมารวมกันอยู่ในตัวแปรเดียว > $fs_conadd (START)
$fs_stat_add=$_POST["f_extadd"];
if($fs_stat_add==2)
{
	$fs_ext=$_POST["f_ext"];
	$fs_conadd=$fs_ext;
}
else
{
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


// ------------ นำข้อมูลที่ได้จากการ POST มา UPDATE ลูกค้าที่ถูกแก้ไขข้อมูล (START)
$update_fn="update \"Fn\" SET
				\"N_SAN\"='$fs_san',
				\"N_AGE\"='$fs_age',
				\"N_CARD\"='$fs_card',
				\"N_IDCARD\"=$fs_cardid,
				\"N_OT_DATE\"='$fs_datecard',
				\"N_BY\"='$fs_cardby',
				\"N_OCC\"='$fs_occ',
				\"N_ContactAdd\"='$fs_conadd',
				\"N_CARDREF\"=$cardref
			WHERE \"CusID\"='$fs_cusid' ";

if($result=pg_query($update_fn))
{
	$st_fn="OK".$update_fn;
}
else
{
	$trans++;
	$st2="พบปัญหาที่ ".$update_fn." มีผลว่า ".$result."</br>";
}



// ------------ นำข้อมูลที่ได้จากการ POST มา UPDATE ลูกค้าที่ถูกแก้ไขข้อมูล (END)


// --------------------------------------------------------------------------------------------  ทำ Transaction (END)
if($trans==0){// ทำรายการสมบูรณ์
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$add_user', '(ALL) แก้ไขข้อมูลลูกค้า (พิเศษ)', '$add_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	echo "</br>บันทึกข้อมูลเรียบร้อยแล้ว</br>";
}
else{ // ทำรายการไม่สมบูรณ์
	pg_query("ROLLBACK");
	echo $st1;
	echo $st2;
	echo "<p>$error_check<p>";
	echo "ยกเลิกการทำรายการ";
}

?>
<input type="button" value="กลับหน้าแรก" onclick="window.location='frm_av_editcus.php'" />