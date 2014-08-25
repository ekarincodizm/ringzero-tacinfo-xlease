<?php
include("../config/config.php");
include("../nw/function/checknull.php");
session_start();

$idno=$_POST["f_idno"];

$userid=$_SESSION['uid'];
$id_cusid=$_POST["fcus_id"];
$num_fn = $_POST["num_fn"];
$CusState=$_POST["CusState"];
$officeid=$_SESSION["av_officeid"];

$dat=date("Y/m/d");
$datenow=date("Y-m-d");
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server



// Header -- END --


$fs_stat_add=$_POST["f_extadd"];




$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
// ------------  รับข้อมูลที่ POST มาจาก FORM ของหน้าก่อนหน้า (START)
$fs_idno=$_POST["fidno"];
$fs_cusid=$_POST["fcus_id"];
$fs_carid=$_POST["fcar_id"];

$fs_firname=$_POST["f_fri_name"];
$fs_name=$_POST["f_name"];
$fs_surname=$_POST["f_surname"];

$textName=$fs_firname." ".$fs_name."  ".$fs_surname;

$fs_pair=$_POST["f_pair"];
$fs_no=$_POST["f_no"];
$fs_subno=$_POST["f_subno"];
$fs_soi=$_POST["f_soi"];
$fs_rd=$_POST["f_rd"];
$fs_aum=$_POST["f_aum"];
$fs_tum=$_POST["f_tum"];
$fs_province=$_POST["f_province"];
$fs_post=$_POST["f_post"];

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
$statuscus=trim($_POST["statuscus"]); //สถานะลูกค้า 0=คนไทย 1= ชาวต่างชาติ 2=บริษัท
if($statuscus!=0){
	$fs_cardid="";
}
$fs_datecard=$_POST["f_datecard"];
$fs_cardby=$_POST["f_card_by"];
$fs_occ=$_POST["f_occ"];

$A_SEX=$_POST["A_SEX"];
$A_ROOM=$_POST["A_ROOM"];
$A_FLOOR=$_POST["A_FLOOR"];
$A_BUILDING=$_POST["A_BUILDING"];
$A_VILLAGE=$_POST["A_VILLAGE"];


$A_SEX=checknull($A_SEX);

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


// --- Start transactions ---
pg_query("BEGIN WORK"); $trans = 0; // $trans - transactions check
$qry_Fa1=pg_query("select * from \"Fn\" where \"CusID\" ='$id_cusid' ");
$num_fa1 = pg_num_rows($qry_Fa1);	
	$in_Fa1="update \"Fa1\" SET  
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
				\"A_BUILDING\"='$A_BUILDING',
				\"A_ROOM\"='$A_ROOM',
				\"A_FLOOR\"='$A_FLOOR',
				\"A_VILLAGE\"='$A_VILLAGE'
		where \"CusID\"='$id_cusid'";

	if($result=pg_query($in_Fa1)){
		$status ="OK Update at Fa1".$in_Fa1;
	}else{
		$trans++; // $trans > 0 กรณีทำรายการ SQL ไม่ผ่าน
		$status ="error Update  Fa1 Re".$in_Fa1;
	}

//กรณีจะอัพเดทใน Fn ต้องตรวจสอบดูก่อนว่าในตาราง Fn มีข้อมูลอยู่แล้วหรือไม่
$qry_Fn=pg_query("select * from \"Fn\" where \"CusID\" ='$id_cusid' ");
$num_fn = pg_num_rows($qry_Fn);	
if($num_fn == 0){

}else{
	$action ="update_contactcus.php";
}
if($fs_stat_add==2){
	$fs_ext=$_POST["f_ext"];
	$fs_conadd=$fs_ext;
}else{
	$fs_conadd=trim($fs_no)." ".trim($fs_subno)." ".trim($fs_soi)." ".trim($fs_rd)." ".trim($fs_aum)." ".trim($fs_tum)." ".trim($fs_province)." ".trim($fs_post);
}
if($num_fn == 0){
$in_fn="insert into \"Fn\" (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_ContactAdd\",\"N_OCC\",\"N_CARDREF\",statuscus) 
					values ('$id_cusid','0','$fs_san','$fs_age','$fs_card','$fs_cardid','$fs_datecard','$fs_cardby','$fs_conadd','$fs_occ','$cardref','$statuscus')"; 
}else{
$in_fn="update \"Fn\" SET
				\"N_SAN\"='$fs_san',
				\"N_AGE\"='$fs_age',
				\"N_CARD\"='$fs_card',
				\"N_IDCARD\"='$fs_cardid',
				\"N_OT_DATE\"='$fs_datecard',
				\"N_BY\"='$fs_cardby',
				\"N_OCC\"='$fs_occ',
				\"N_ContactAdd\"='$fs_conadd',
				\"N_CARDREF\"=$cardref,
				statuscus='$statuscus'
			WHERE \"CusID\"='$id_cusid'  ";
}
if($result=pg_query($in_fn)){
	$statuss ="OK update at Fn".$in_fn;
}else{
	$trans++; // $trans > 0 กรณีทำรายการ SQL ไม่ผ่าน
	$statuss ="error update  Fn Re".$in_fn;
}	 

 
/*** letter **/
$qry_cc=pg_query("select * from \"ContactCus\" WHERE  \"CusID\"='$id_cusid' and \"IDNO\"='$idno' ");
$res_cc=pg_fetch_array($qry_cc);
$cs_cc=$res_cc["CusState"];
$num_rlet=pg_num_rows($qry_cc);
	
if($num_rlet==0){ 
	$qry_lt=pg_query("select * from letter.send_address where \"IDNO\"='$idno' and \"CusState\"='$cs_cc' and active='TRUE'");
	$numr_lt=pg_num_rows($qry_lt);
	
	if($numr_lt==0){
		$gen_ltr=pg_query("select letter.gen_cusletid('$idno')"); //gen letter
		$res_genltr=pg_fetch_result($gen_ltr,0);
	
		//echo "<br>"."gen id=".$res_genltr;
	
		$ins_send_ads="insert into letter.send_address 	
					   (\"CusLetID\",\"IDNO\",record_date,\"name\",active,userid,dtl_ads,\"CusState\")
					   values
					   ('$res_genltr','$idno','$datenow','$textName',TRUE,'$userid','$fs_conadd',$cs_cc)";
		 
		if($result=pg_query($db_connect,$ins_send_ads)){
			$status ="OK".$ins_send_ads;
		}else{
			$trans++; // $trans > 0 กรณีทำรายการ SQL ไม่ผ่าน
			$status ="error insert Re".$ins_send_ads;
		}
		echo $status;
	}else{			
		$qry_lt2=pg_query($db_connect,"select * from letter.send_address 
	                              where (\"IDNO\"='$idno') and (\"CusState\"='$cs_cc') and (active=TRUE);");				  
		$res_idli=pg_fetch_array($qry_lt2);
	 
		$fs_ltsid=$res_idli["CusLetID"];
	 
		$in_lt="Update letter.send_address SET dtl_ads='$id_cusid' WHERE \"CusLetID\"='$fs_ltsid' ";
		if($result=pg_query($db_connect,$in_lt)){
			$statuss ="OK update at Fn".$in_lt;
			$st="บันทึกข้อมูลเรียบร้อย";
		}else{
			$trans++; // $trans > 0 กรณีทำรายการ SQL ไม่ผ่าน
			$statuss ="error update  Fn Re".$in_lt;
			$st="เกิดข้อผิดพลาด";
		}	
		echo $st; 
    
	}	
}else{
	  
	$qry_lt2=pg_query("select * from letter.send_address where \"IDNO\"='$idno' and \"CusState\"='$cs_cc' and active='TRUE'");
	$numlt2 = pg_num_rows($qry_lt2);
	
	$res_idli=pg_fetch_array($qry_lt2);
	 
	$fs_ltsid=$res_idli["CusLetID"];
	 
	$in_lt="Update letter.send_address SET dtl_ads='$fs_conadd' WHERE \"CusLetID\"='$fs_ltsid' ";
	if($result=pg_query($db_connect,$in_lt)){
		$statuss ="OK update at Fn".$in_lt;
		$st="บันทึกข้อมูลเรียบร้อย";

	}else{
		$trans++; // $trans > 0 กรณีทำรายการ SQL ไม่ผ่าน
		$statuss ="error update  Fn Re".$in_lt;
		$st="เกิดข้อผิดพลาด";

	}	
}
/*************/

//update ที่อยู่ใน Fp_Fa1 ด้วย โดยดึงข้อมูลเก่ามาเปรียบเทียบ
if($fs_subno==""){ $fs_subno2="null";}else{ $fs_subno2="'".$fs_subno."'";}
if($fs_soi==""){ $fs_soi2="null";}else{ $fs_soi2="'".$fs_soi."'";}
if($fs_rd==""){ $fs_rd2="null";}else{ $fs_rd2="'".$fs_rd."'";}

$chkfpfa1=pg_query("select * from \"Fp_Fa1\" where \"IDNO\"='$idno' and \"edittime\"='0' and \"CusID\"='$id_cusid'");
$numchk=pg_num_rows($chkfpfa1);
if($numchk==0){ //ให้ insert ข้อมูล
	$insfpfa1="INSERT INTO \"Fp_Fa1\"(
           \"IDNO\", \"CusID\", \"A_NO\", \"A_SUBNO\", \"A_SOI\", \"A_RD\", 
            \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"CusState\",\"addUser\",\"addStamp\",\"A_ROOM\",\"A_FLOOR\",\"A_BUILDING\",\"A_BAN\",statuscus)
    VALUES ('$idno','$id_cusid', '$fs_no', $fs_subno2, $fs_soi2, $fs_rd2, 
			'$fs_tum', '$fs_aum', '$fs_province', '$fs_post','$CusState','$userid','$add_date','$A_ROOM','$A_FLOOR','$A_BUILDING','$A_VILLAGE','$statuscus')";	
	if($resinfpfa1=pg_query($insfpfa1)){
	}else{
		$status++;
	}
}else{
		$upfpfa1="UPDATE \"Fp_Fa1\"
		SET \"A_NO\"='$fs_no', \"A_SUBNO\"=$fs_subno2, \"A_SOI\"=$fs_soi2, 
		   \"A_RD\"=$fs_rd2, \"A_TUM\"='$fs_tum', \"A_AUM\"='$fs_aum', \"A_PRO\"='$fs_province', \"A_POST\"='$fs_post',
		   \"addUser\"='$userid', \"addStamp\"='$add_date', \"A_ROOM\"='$A_ROOM', \"A_FLOOR\"='$A_FLOOR', \"A_BUILDING\"='$A_BUILDING', \"A_BAN\"='$A_VILLAGE',
		   statuscus='$statuscus'
		WHERE \"IDNO\"='$idno' and \"edittime\"='0' and \"CusID\"='$id_cusid' and \"CusState\"='$CusState'";	
		if($resupfpfa1=pg_query($upfpfa1)){
		}else{
			$status++;
		}
}

// --- Tailer transactions ---
if($trans == 0){
	pg_query("COMMIT");
	echo "<br>";
	echo "บันทึกข้อมูลเรียบร้อยแล้ว";
	echo "<br>";
}else{
	pg_query("ROLLBACK");
	echo "<br>";
	echo "การบันทึก/อัพเดทข้อมูล มีรายการผิดพลาด => ยกเลิกการทำรายการ";
	echo "<br>";
}

// --- End transactions ---
 
echo "<meta http-equiv=\"refresh\" content=\"2;URL=frm_edit.php?idnog=$idno\">";

?>