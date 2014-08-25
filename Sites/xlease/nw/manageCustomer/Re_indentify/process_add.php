<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
session_start();
include('../../../config/config.php');
include("../../../nw/function/checknull.php");

$id_user = $_SESSION["av_iduser"];
$cusid = $_POST['cusID'];
$sameid = $_POST['sameid'];
$newid = $_POST['iden'];
$date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$status = 0;


if(!empty($_FILES["filedoc"]["name"])){

	@mkdir("upload_idcard",0777);
	$path="upload_idcard/";
	$YY = date('Y');
	$mm = date('m');
	$dd = date('d');
	$timenow = date('H:i:s');
	list($hh,$ii,$ss) = explode(":",$timenow);
	
					$file_name = $_FILES["filedoc"]["name"];
					
					$info = substr( $file_name , strpos( $file_name , '.' )+1 ) ;
					if(move_uploaded_file($_FILES["filedoc"]["tmp_name"],$path.$file_name))
						{		
								$md5file = md5_file($path.$file_name);
								$newfile_name = $YY.$mm.$dd.$hh.$ii.$ss."_".$md5file;
							   $file = $path.$newfile_name.".".$info;
							   $flgRename = rename($path.$file_name, $path.$newfile_name.".".$info);	
						}
					
		
		$file = "'".$file."'";
}else{
		 $file = "null";
}

pg_query('BEGIN');
$sql1 = pg_query("SELECT * FROM \"Re_indentity_cus_temp\" where \"CusID\" = '$cusid'");
$rows = pg_num_rows($sql1);
	if($rows=='0'|| empty($rows)){
		$edit = '0';
	}else{
		$sql = pg_query("SELECT max(edittime) as max FROM \"Re_indentity_cus_temp\" where \"CusID\" = '$cusid'");
		$re = pg_fetch_array($sql);
		$edit = $re['max'];
		$edit++;
	}
$sql_int = ("INSERT INTO \"Re_indentity_cus_temp\"(
            \"CusID\", identity_same, identity_new, edittime, app_status, id_user,date,docfile)
    VALUES ('$cusid', '$sameid', '$newid','$edit', '1','$id_user','$date',$file)");
$sqlquery1 = pg_query($sql_int);	

if($sqlquery1){
	
}else{
	$status++;
}
//
$sql_ctemp = pg_query("SELECT *  FROM \"Customer_Temp\" where \"CusID\" = '$cusid' and 
\"statusapp\"='2'");
$num_ctemp=pg_num_rows($sql_ctemp);
$show=0;
if($num_ctemp>0){
	$show=1;
}
else{
//เพิ่มข้อมูล
$sql_selectfa1 = pg_query("SELECT *  FROM \"Fa1\" where \"CusID\" = '$cusid'");
$re_fa1 = pg_fetch_array($sql_selectfa1);
	$fa1_firname=checknull($re_fa1["A_FIRNAME"]);
	$fa1_name=checknull($re_fa1["A_NAME"]);
	$fa1_surname=checknull($re_fa1["A_SIRNAME"]);
	$fa1_pair=checknull($re_fa1["A_PAIR"]);
	$fa1_no=checknull($re_fa1["A_NO"]);
	$fa1_subno=checknull($re_fa1["A_SUBNO"]);
	$fa1_soi=checknull($re_fa1["A_SOI"]);
	$fa1_rd=checknull($re_fa1["A_RD"]);
	$fa1_tum=checknull($re_fa1["A_TUM"]);
	$fa1_aum=checknull($re_fa1["A_AUM"]);
	$fa1_pro=checknull($re_fa1["A_PRO"]);
	$fa1_post=checknull($re_fa1["A_POST"]);
	$fa1_firname_eng=checknull($re_fa1["A_FIRNAME_ENG"]);
	$fa1_name_eng=checknull($re_fa1["A_NAME_ENG"]);
	$fa1_surname_eng=checknull($re_fa1["A_SIRNAME_ENG"]);
	$fa1_nickname=checknull($re_fa1["A_NICKNAME"]);
	$fa1_status=checknull($re_fa1["A_STATUS"]);
	$fa1_revenue2=checknull($re_fa1["A_REVENUE"]);
	$fa1_education2=checknull($re_fa1["A_EDUCATION"]);
	$fa1_country=checknull($re_fa1["addr_country"]);
	$fa1_mobile=checknull($re_fa1["A_MOBILE"]);
	$fa1_telephone=checknull($re_fa1["A_TELEPHONE"]);
	$fa1_email=checknull($re_fa1["A_EMAIL"]);
	$fa1_birthday=checknull($re_fa1["A_BIRTHDAY"]);
	$fa1_A_SEX=checknull($re_fa1["A_SEX"]);
	$fa1_A_ROOM=checknull($re_fa1["A_ROOM"]);
	$fa1_A_FLOOR=checknull($re_fa1["A_FLOOR"]);
	$fa1_A_BUILDING=checknull($re_fa1["A_BUILDING"]);
	$fa1_A_VILLAGE=checknull($re_fa1["A_VILLAGE"]);
$sql_selectfn = pg_query("SELECT *  FROM \"Fn\" where \"CusID\" = '$cusid'");
$re_fn = pg_fetch_array($sql_selectfn);
	$N_STATE=checknull($re_fn["N_STATE"]);
	$N_SAN=checknull($re_fn["N_SAN"]);
	$N_AGE=checknull($re_fn["N_AGE"]);
	$N_CARD=checknull($re_fn["N_CARD"]);
	//$N_IDCARD=$re_fn["N_IDCARD]"; 
	$N_OT_DATE=checknull($re_fn["N_OT_DATE"]);
	$N_BY=checknull($re_fn["N_BY"]);
	$N_OCC=checknull($re_fn["N_OCC"]);
	$ext_addr=checknull($re_fn["N_ContactAdd"]);
	$N_CARDREF=checknull($re_fn["N_CARDREF"]);
	$statuscus=checknull($re_fn["statuscus"]);
// ต้องค้นหาก่อนว่ามีการแก้ไข record นี้กี่ครั้งแล้วเพื่อหาค่า edittime ต่อไป
	$qry_count=pg_query("select MAX(\"edittime\") as numtime from \"Customer_Temp\" where \"CusID\"='$cusid'");
	$num_count=pg_num_rows($qry_count);
	$cno=0;
	if($num_count==0){
		$countcus=1; //แสดงว่ามีการแก้ไขข้อมูลโดยไม่ได้เพิ่มข้อมูลจากเมนูใหม่ จึงกำหนดให้ edittime=1
	}else{
		$rescount=pg_fetch_array($qry_count);
		$cno=$rescount["numtime"];
		$countcus=$rescount["numtime"] + 1;
	}

$insert_Fa1="INSERT INTO \"Customer_Temp\"(
		\"CusID\",\"add_user\",\"add_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",
		\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"N_SAN\",
		\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
		\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"addr_country\",
		\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",\"N_CARDREF\",\"A_BIRTHDAY\",\"A_SEX\",\"A_ROOM\",\"A_FLOOR\",
		\"A_BUILDING\",\"A_VILLAGE\",\"statuscus\")VALUES (
		'$cusid','$id_user','$date','2',$countcus,$fa1_firname,$fa1_name,$fa1_surname,$fa1_pair,$fa1_no,$fa1_subno,
		$fa1_soi,$fa1_rd,$fa1_tum,$fa1_aum,$fa1_pro,$fa1_post,$N_SAN,$N_AGE,$N_CARD,$newid,$N_OT_DATE,$N_BY,
		$N_OCC,$ext_addr,$N_STATE,$fa1_firname_eng,$fa1_name_eng,$fa1_surname_eng,$fa1_nickname,$fa1_status,
		$fa1_revenue2,$fa1_education2,$fa1_country,$fa1_mobile,$fa1_telephone,$fa1_email,$N_CARDREF,$fa1_birthday,
		$fa1_A_SEX,$fa1_A_ROOM,$fa1_A_FLOOR,$fa1_A_BUILDING,$fa1_A_VILLAGE,$statuscus)";

		// ตรวจสอบผลการ Query ดูว่ามีปัญหาใดหรือไม่
		
		$result = pg_query($insert_Fa1);
		if($result){
		}else{
			$status++;
		}
}

///
	if($status == 0){
		if($show==1){
			pg_query('ROLLBACK');
			echo "<script type='text/javascript'>alert('ข้อมูลลูกค้าได้รับการแก้ไขไปก่อนหน้านี้แล้ว!')</script>";
		}
		else{
		// ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ขอแก้ไขเลขบัตรประชาชน', '$date')");
		// ACTIONLOG---
		pg_query('COMMIT');
		echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_index.php\">";
		echo "<script type='text/javascript'>alert('แก้ไขสำเร็จ รอการอนมุัติ')</script>";
		}
	}else{
		pg_query('ROLLBACK');
		// echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm_index.php\">";
		echo "<script type='text/javascript'>alert('พบปัญหาในการแก้ไข!')</script>";
		echo $sql_int;
	}
	

?>