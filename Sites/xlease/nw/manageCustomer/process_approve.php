<?php
session_start();
include("../../config/config.php");
include("../function/checknull.php");
include("../../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว

//อนุมัติอัตโนมัติโดยระบบหรือไม่
$autoapp = pg_escape_string($_GET["autoapp"]);
if($autoapp == 't'){
	$app_user = '000';
}else{
	$app_user = $_SESSION["av_iduser"];
}
$update_gather = pg_escape_string($_GET["update_gather"]);

$app_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

$CustempID=pg_escape_string($_GET["CustempID"]); 
$statusapp=pg_escape_string($_GET["stsapp"]);  
$edittime=pg_escape_string($_GET["edittime"]);
if($statusapp==""){
	$CustempID=pg_escape_string($_POST["CustempID"]); 
	$edittime=pg_escape_string($_POST["edittime"]);
	$ap=pg_escape_string($_POST["ap"]);
	$unap=pg_escape_string($_POST["unap"]);
	if(isset($ap)){
		$statusapp=1;//อนุมัติ
	}else if(isset($unap)){
		$statusapp=0;//ไม่อนุมัติ
	}
}
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
</script> 
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<?php
if($_SESSION["auth_refferer"]!="pass"&&$CustempID!=$_SESSION["auth_cusid"])
{
	echo "<center></br>คำเตือน : ตรวจพบการใช้งานผิดวัตถุประสงค์</br>";
}
else
{
	session_unregister("auth_refferer");
	session_unregister("auth_cusid");
?>
<table width="100%" border="0" align="center">
<tr >
<td align="center" valign="middle" height="200">
 <?php
//อันดับแรกต้องตรวจสอบข้อมูลก่อนว่าข้อมูลนี้ได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง (กรณีมีผู้ใช้งานพร้อมกัน)
$qry_check=pg_query("select statusapp from \"Customer_Temp\" where \"CustempID\"='$CustempID' and \"statusapp\" in('0','1')");
$num_check=pg_num_rows($qry_check);
if($num_check > 0){
	$rescheck=pg_fetch_array($qry_check);
	$check_status=trim($rescheck["statusapp"]);
	if($check_status =="1"){
		echo "รายการนี้ได้รับการอนุมัติไปแล้ว";
		echo "<meta http-equiv='refresh' content='2; URL=frm_approve.php'>";
	}else if($check_status =="0"){
		echo "รายการนี้ไม่ได้รับการอนุมัติ";
		echo "<meta http-equiv='refresh' content='2; URL=frm_approve.php'>";
	}
}else{ //กรณียังไม่ได้รับการอนุมัติก่อนหน้านี้
pg_query("BEGIN WORK");
$status = 0;

if($statusapp==1){ //กรณีอนุมัติ ต้องดูก่อนว่าอนุมัติเพิ่มข้อมูลหรือแก้ไขโดย
	$qry_cus=pg_query("select * from \"Customer_Temp\" where \"CustempID\"='$CustempID'");
		$res_cus=pg_fetch_array($qry_cus);
		$CusID=$res_cus["CusID"];
		$fa1_firname=trim($res_cus["A_FIRNAME"]);
		$fa1_name=trim($res_cus["A_NAME"]);
		$fa1_surname=trim($res_cus["A_SIRNAME"]);
		$fa1_pair=trim($res_cus["A_PAIR"]);
		$fa1_no=trim($res_cus["A_NO"]);
		$fa1_subno=trim($res_cus["A_SUBNO"]);
		$fa1_soi=trim($res_cus["A_SOI"]);
		$fa1_rd=trim($res_cus["A_RD"]);	
		$fa1_tum=trim($res_cus["A_TUM"]);	
		$fa1_aum=trim($res_cus["A_AUM"]);
		$fa1_pro=trim($res_cus["A_PRO"]); // จังหวัด
		$fa1_post=trim($res_cus["A_POST"]);
		
		$fa1_firname_eng=trim($res_cus["A_FIRNAME_ENG"]);
		$fa1_name_eng=trim($res_cus["A_NAME_ENG"]);
		$fa1_surname_eng=trim($res_cus["A_SIRNAME_ENG"]);
		$fa1_nickname=trim($res_cus["A_NICKNAME"]);
		$fa1_status=trim($res_cus["A_STATUS"]);
		$fa1_revenue=trim($res_cus["A_REVENUE"]); if($fa1_revenue==""){ $fa1_revenue2="null"; }else{ $fa1_revenue2="'".$fa1_revenue."'"; }
		$fa1_education=trim($res_cus["A_EDUCATION"]); if($fa1_education==""){ $fa1_education2="null"; }else{ $fa1_education2="'".$fa1_education."'"; }
		$fa1_country=trim($res_cus["addr_country"]);
		$fa1_mobile=trim($res_cus["A_MOBILE"]);
		$fa1_telephone=trim($res_cus["A_TELEPHONE"]);
		$fa1_email=trim($res_cus["A_EMAIL"]);
		$fa1_birthday=trim($res_cus["A_BIRTHDAY"]);
		
		$fa1_A_SEX = checknull(trim($res_cus["A_SEX"]));
		$fa1_A_ROOM = checknull(trim($res_cus["A_ROOM"]));
		$fa1_A_FLOOR = checknull(trim($res_cus["A_FLOOR"]));
		$fa1_A_BUILDING = checknull(trim($res_cus["A_BUILDING"]));
		$fa1_A_VILLAGE = checknull(trim($res_cus["A_VILLAGE"]));
		$fa1_pro = checknull($fa1_pro);
			
		$ext_addr=$res_cus["N_ContactAdd"];
		$N_SAN=$res_cus["N_SAN"];
		$N_AGE=$res_cus["N_AGE"];
		$N_CARD=$res_cus["N_CARD"];
		$N_IDCARD=$res_cus["N_IDCARD"];
		$N_OT_DATE=$res_cus["N_OT_DATE"];
		$N_BY=$res_cus["N_BY"];
		$N_OCC=$res_cus["N_OCC"];
		$N_STATE=$res_cus["N_STATE"];
		$N_CARDREF=$res_cus["N_CARDREF"];
		$statuscus=checknull($res_cus["statuscus"]);  //สถานะลูกค้า 0=คนไทย 1= ชาวต่างชาติ 2=บริษัท
		
		
	$Cus=substr($CusID,0,2);	
	if($Cus=="CT"){
		$edittime=0;
	}
	if($edittime==0){ //กรณีเพิ่มข้อมูล
		//------ ตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
		$cus_sn = GenCus();
		//----------------------
		
		//------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
		
		$sql_check_name = pg_query("select \"CusID\" from \"Fa1\" where \"A_NAME\" = '$fa1_name' and \"A_SIRNAME\" = '$fa1_surname' ");
		$row_check_name = pg_num_rows($sql_check_name);
		//เช็ค emplevel
		$appvedit="yes";
		$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$app_user' ");
		$leveluser = pg_fetch_array($query_leveluser);
		$emplevel=$leveluser["emplevel"];	
		if($emplevel<=1){}
		else if($emplevel<=15){//ให้สามารถ อนุมัติลูกค้าที่มีชื่อและนามสกุลซ้ำกันได้ (แต่ว่าต้องเป็นคนละคนกับคนที่ขอเพิ่ม้อมูล)
			//คนที่ทำการเพิ่ม
			$query_add= pg_query("select \"add_user\" from \"Customer_Temp\" where \"CustempID\" = '$CustempID'");
			$editre_resu= pg_fetch_array($query_add);
			$app_useradd=$editre_resu["add_user"];
			if($app_useradd==$app_user){
				$status++;
				$appvedit="no";
				$error_check = "เนื่องจาก ผู้ทำการอนุมัติจะต้องเป็นคนละคนกับผู้ขออนุมัติ";//น้อยกว่า 15 และ เป็นคนเดียวกัน
				}
		}else{			
			$query_add= pg_query("select \"add_user\" from \"Customer_Temp\" where \"CustempID\" = '$CustempID'");
			$editre_resu= pg_fetch_array($query_add);
			$app_useradd=$editre_resu["add_user"];
			if($app_useradd==$app_user){
				$status++;
				$appvedit="no";
				$error_check = "เนื่องจาก ผู้ทำการอนุมัตินี้ มีสิทธิ์อนุมัติได้เมื่อผู้ทำการอนุมัติจะต้องเป็นคนละคนกับผู้ขออนุมัติ";//มากกว่า 15 และ เป็นคนเดียวกัน
			}
			if($appvedit=="yes"){
				if($row_check_name>0){
					$status++;
					$appvedit="no";
					$error_check = "เนื่องจาก  ผู้ทำการอนุมัตินี้ ไม่มีสิทธิ์อนุมัติ ชื่อ-สกุล ที่มีอยู่ในระบบแล้ว";
				}
			}		
		}			
		if($appvedit=="no"){}
		else{
			//insert ใน Fa1
			$insert_Fa1="INSERT INTO \"Fa1\"(
					\"CusID\", \"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
					\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					\"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", 
					\"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"addr_country\", \"A_MOBILE\", 
					\"A_TELEPHONE\", \"A_EMAIL\",\"A_BIRTHDAY\",
					\"A_SEX\",\"A_ROOM\",\"A_FLOOR\",\"A_BUILDING\",\"A_VILLAGE\")
				VALUES ('$cus_sn', '$fa1_firname', '$fa1_name', '$fa1_surname', '$fa1_pair', '$fa1_no',
					'$fa1_subno', '$fa1_soi', '$fa1_rd', '$fa1_tum', '$fa1_aum', $fa1_pro, '$fa1_post',
					'$fa1_firname_eng','$fa1_name_eng','$fa1_surname_eng','$fa1_nickname',
					'$fa1_status',$fa1_revenue2,$fa1_education2,'$fa1_country','$fa1_mobile',
					'$fa1_telephone','$fa1_email','$fa1_birthday',
					$fa1_A_SEX,$fa1_A_ROOM,$fa1_A_FLOOR,$fa1_A_BUILDING,$fa1_A_VILLAGE)";
			if($res_fa1=pg_query($insert_Fa1)){
			}else{
			$status++;
			}
		
			//------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง
			if($N_IDCARD != ""){
				$check_card = str_replace(" ","",$N_IDCARD);
				$check_card = str_replace("-","",$check_card);
				
				$sql_check=pg_query("select \"N_IDCARD\" from \"Fn\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$check_card'");
				$row_check = pg_num_rows($sql_check);
				if($row_check > 0)
				{
					$status++;
					$error_check = "เนื่องจาก มีเลขที่บัตรประชาชนนี้อยู่ในระบบแล้ว";
				}
			}else{
				$check_card = str_replace(" ","",$N_CARDREF);
				$check_card = str_replace("-","",$check_card);
				$sql_check=pg_query("select \"N_CARDREF\" from \"Fn\" where replace(replace(\"N_CARDREF\",' ',''),'-','') = '$check_card'");
				$row_check = pg_num_rows($sql_check);
				if($row_check > 0)
				{
					$status++;
					$error_check = "เนื่องจาก มีเลขที่บัตรนี้อยู่ในระบบแล้ว";
				}
			}
		
			$N_CARDREF = checknull($N_CARDREF);
			$N_IDCARD=checknull($N_IDCARD);
			
			//insert ใน Fn
			$insert_Fn="INSERT INTO \"Fn\"(
					\"CusID\", \"N_STATE\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",
					\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_CARDREF\",statuscus)
				VALUES ('$cus_sn', '0', '$N_SAN', '$N_AGE', '$N_CARD', $N_IDCARD, '$N_OT_DATE',
					'$N_BY', '$N_OCC', '$ext_addr', $N_CARDREF,$statuscus)";

			if($result=pg_query($insert_Fn)){
			}else{
				$status++;
			}
		
			//update ข้อมูลใน Customer_Temp ให้เปลี่ยน "CusID" เดิมเป็น "CusID" ใหม่ที่ได้จากระบบ
			$upcus="update \"Customer_Temp\" set \"CusID\"='$cus_sn' where \"CusID\" = '$CusID'";
			if($result1=pg_query($upcus)){
			}else{
				$status++;
			}
			//update ข้อมูลใน ContactCus กรณีที่มีการนำไปเชื่อมกับสัญญาแล้วให้เปลี่ยน "CusID" เดิมเป็น "CusID" ใหม่ที่ได้จากระบบ 
			$upcus2="update \"ContactCus\" set \"CusID\"='$cus_sn' where \"CusID\" = '$CusID'";
			if($result2=pg_query($upcus2)){
			}else{
				$status++;
			}
		
		//update ใน "Fp_Fa1" กรณีมีการผูกลูกค้าคนนี้กับสัญญา ต้อง update ข้อมูลลูกค้าคนนี้ในระบบด้วย
			$upfpfa1="UPDATE \"Fp_Fa1\"
			SET \"CusID\"='$cus_sn', \"A_NO\"='$fa1_no', \"A_SUBNO\"='$fa1_subno', \"A_SOI\"='$fa1_soi', 
			\"A_RD\"='$fa1_rd', \"A_TUM\"='$fa1_tum', \"A_AUM\"='$fa1_aum', \"A_PRO\"=$fa1_pro, \"A_POST\"='$fa1_post',
		   \"A_ROOM\"=$fa1_A_ROOM, \"A_FLOOR\"=$fa1_A_FLOOR, \"A_BUILDING\"=$fa1_A_BUILDING, \"A_BAN\"=$fa1_A_VILLAGE,
		   statuscus=$statuscus
			WHERE \"CusID\"='$CusID'";
			if($resfpfa1=pg_query($upfpfa1)){
			}else{
				$status++;
			}
			//update สถานะว่า approve แล้ว
			$update="update \"Customer_Temp\" set 
					\"app_user\"='$app_user', 
					\"app_date\"='$app_date', 
					statusapp='1'
					where \"CustempID\" = '$CustempID'";
			if($result=pg_query($update)){
			}else{
				$status++;
			}			
		}
	}else{ //กรณีแก้ไขข้อมูล
		//------ เช็คก่อนว่าลูกค้ามีแล้วหรือยัง	
		if($N_IDCARD != ""){
			$check_card = str_replace(" ","",$N_IDCARD);
			$check_card = str_replace("-","",$check_card);
				
			/*$sql_check=pg_query("select \"N_IDCARD\" from \"Fn\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$check_card' and \"CusID\" <> '$CusID'");
			$row_check = pg_num_rows($sql_check);
			if($row_check > 0)
			{
				$status++;
				$error_check = "เนื่องจาก มีเลขที่บัตรประชาชนนี้อยู่ในระบบแล้ว";
			}*/
		}
		
		/*$sql_check_name = pg_query("select * from \"Fa1\" where \"A_NAME\" = '$fa1_name' and \"A_SIRNAME\" = '$fa1_surname' ");
		$row_check_name = pg_num_rows($sql_check_name);*/
		//เช็ค emplevel
		$appvedit="yes";
		$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$app_user' ");
		$leveluser = pg_fetch_array($query_leveluser);
		$emplevel=$leveluser["emplevel"];
		if($emplevel<=1){}
		else if($emplevel<=15){//ให้สามารถ อนุมัติลูกค้าที่มีชื่อและนามสกุลซ้ำกันได้ (แต่ว่าต้องเป็นคนละคนกับคนที่ขอเพิ่ม้อมูล)
			//คนที่ทำการแก้ไข
			$query_add= pg_query("select \"add_user\" from \"Customer_Temp\" where \"CustempID\" = '$CustempID'");
			$editre_resu= pg_fetch_array($query_add);
			$app_useradd=$editre_resu["add_user"];
			if($app_useradd==$app_user){ 
				$status++;
				$appvedit="no";	
				$error_check = "เนื่องจาก ผู้ทำการอนุมัติจะต้องเป็นคนละคนกับผู้ขออนุมัติ";//น้อยกว่า 15 และ เป็นคนเดียวกัน
			}
		}else{	
			$query_add= pg_query("select \"add_user\" from \"Customer_Temp\" where \"CustempID\" = '$CustempID'");
			$editre_resu= pg_fetch_array($query_add);
			$app_useradd=$editre_resu["add_user"];
			if($app_useradd==$app_user){
				$status++;
				$appvedit="no";
				$error_check = "เนื่องจาก ผู้ทำการอนุมัตินี้ มีสิทธิ์อนุมัติได้เมื่อผู้ทำการอนุมัติจะต้องเป็นคนละคนกับผู้ขออนุมัติ";//มากกว่า 15 และ เป็นคนเดียวกัน
			}	
			/*if($appvedit=="yes"){
				if($row_check_name>0){					
					$status++;
					$appvedit="no";
					$error_check = "เนื่องจาก  ผู้ทำการอนุมัตินี้ ไม่มีสิทธิ์อนุมัติ ชื่อ-สกุล ที่มีอยู่ในระบบแล้ว";
				}
			}*/	
		}
		if($appvedit=="no"){}
		else{
		//update ข้อมูลใน Customer_Temp ว่าได้ approve แล้ว 
		$update="update \"Customer_Temp\" set 
				\"app_user\"='$app_user', 
				\"app_date\"='$app_date', 
				statusapp='1'
				where \"CustempID\" = '$CustempID'";
		if($result=pg_query($update)){
		}else{
			$status++;
		}		
		//Update ใน Fa1
		$Update_Fa1="update \"Fa1\" set 
				\"A_FIRNAME\"='$fa1_firname',
				\"A_NAME\"='$fa1_name',
				\"A_SIRNAME\"='$fa1_surname',
				\"A_PAIR\"='$fa1_pair',
				\"A_NO\"='$fa1_no',
				\"A_SUBNO\"='$fa1_subno',
				\"A_SOI\"='$fa1_soi',
				\"A_RD\"='$fa1_rd',
				\"A_TUM\"='$fa1_tum',
				\"A_AUM\"='$fa1_aum',
				\"A_PRO\"=$fa1_pro,
				\"A_POST\"='$fa1_post',
				\"A_FIRNAME_ENG\"='$fa1_firname_eng',
				\"A_NAME_ENG\"='$fa1_name_eng',
				\"A_SIRNAME_ENG\"='$fa1_surname_eng',
				\"A_NICKNAME\"='$fa1_nickname',
				\"A_STATUS\"='$fa1_status',
				\"A_REVENUE\"=$fa1_revenue2,
				\"A_EDUCATION\"=$fa1_education2,
				\"addr_country\"='$fa1_country',
				\"A_MOBILE\"='$fa1_mobile',
				\"A_TELEPHONE\"='$fa1_telephone',
				\"A_EMAIL\"='$fa1_email',
				\"A_BIRTHDAY\"='$fa1_birthday',
				\"A_SEX\"=$fa1_A_SEX,
				\"A_ROOM\"=$fa1_A_ROOM,
				\"A_FLOOR\"=$fa1_A_FLOOR,
				\"A_BUILDING\"=$fa1_A_BUILDING,
				\"A_VILLAGE\"=$fa1_A_VILLAGE
				where \"CusID\"='$CusID'";
		if($res_fa1=pg_query($Update_Fa1)){
		}else{
			$status++;
		}
		
		$N_CARDREF = checknull($N_CARDREF);
		$N_IDCARD=checknull($N_IDCARD);
		
		//Update ใน Fn
		$Update_Fn="update \"Fn\" set 
				\"N_STATE\"='$N_STATE',
				\"N_SAN\"='$N_SAN', 
				\"N_AGE\"='$N_AGE', 
				\"N_CARD\"='$N_CARD', 
				\"N_IDCARD\"=$N_IDCARD, 
				\"N_OT_DATE\"='$N_OT_DATE',
				\"N_BY\"='$N_BY', 
				\"N_OCC\"='$N_OCC', 
				\"N_ContactAdd\"='$ext_addr',
				\"N_CARDREF\"=$N_CARDREF,
				statuscus=$statuscus
				where \"CusID\"='$CusID'";

		if($result=pg_query($Update_Fn)){
		}else{
			$status++;
		}
		
		// update ข้อมูล ที่ "Re_indentity_cus_temp"
			$query_selectCus= pg_query("select \"CusID\",\"add_user\",\"add_date\",\"N_IDCARD\" from \"Customer_Temp\" where \"CustempID\" = '$CustempID'");
			$editre_selectCus= pg_fetch_array($query_selectCus);
			$app_CusID=$editre_selectCus["CusID"];
			$app_user=$editre_selectCus["add_user"];
			$app_date=$editre_selectCus["add_date"];
			$app_IDCARD=$editre_selectCus["N_IDCARD"];
			$app_IDCARD = str_replace(" ","",$app_IDCARD);
			$app_IDCARD = str_replace("-","",$app_IDCARD);
			if($statuscus=="'0'"){
				if((strlen($app_IDCARD)==13)){
					$query_Re_indentity_cus= pg_query("select reiden_pk,identity_new from \"Re_indentity_cus_temp\" where \"CusID\"='$app_CusID' and \"app_status\"='1' and \"id_user\"='$app_user' and 	\"date\"='$app_date' and \"identity_new\"='$app_IDCARD'");
					$row_num= pg_num_rows($query_Re_indentity_cus);
					$res_reiden= pg_fetch_array($query_Re_indentity_cus);
					$reiden=$res_reiden["reiden_pk"];
					$identity_new=$res_reiden["identity_new"];
					if($row_num>1){
						$status++;
						$error_check ="เนื่องจาก การอนุมัติเกี่ยวกับการ แก้ไขบัตรประชาชนมีข้อผิดพลาด";}
					else if($row_num==1){	
					$sql1 = "UPDATE \"Re_indentity_cus_temp\" SET app_status='2',app_user='$app_user',app_date='$app_date' WHERE reiden_pk = '$reiden'";		
					if($query1 = pg_query($sql1)){}else{ $status++; }
					}
					else{
						if($app_IDCARD==$identity_new){
							$status++;
						$error_check ="เนื่องจาก รายการที่เกี่ยวข้องกับการแก้ไขบัตรประชาชนมีการทำรายการไปก่อนหน้านี้แล้ว กรุณาลองใหม่ภายหลัง!";
						}
					}
				}
				else{
					$status++;
					$error_check ="เลขบัตรประชาชนไม่ครบ 13 หลัก".$statuscus;
				}
			}
		}
	}//0 edit
	
	
	if($status == 0)
	{
		//----------- กรองลูกค้าที่ซ้ำจากบัตรประชาชนของคนนี้
		if($update_gather!="f")
		{
			$N_IDCARD = str_replace("'","",$N_IDCARD);
			$replace_N_IDCARD = str_replace("-","",str_replace(" ","",$N_IDCARD)); // เลขบัตรประชาชนที่ตัดช่องว่างและเครื่องหมายขีดออก			
			$qry_gatherCustomerMain = pg_query("select check_duplicate_customer('$replace_N_IDCARD','1')");
			$gatherCustomerMain = pg_fetch_result($qry_gatherCustomerMain,0);
			/*$qry_main = pg_query("select distinct replace(replace(\"N_IDCARD\",' ',''),'-','') as \"mainCardID\",
									count(replace(replace(\"N_IDCARD\",' ',''),'-','')) as \"countIDCARD\",
									count(distinct \"CusID\") as \"countCusID\"
								from \"Customer_Temp\"
								where \"N_IDCARD\" is not null and \"N_IDCARD\" <> '' and \"N_IDCARD\" not like '%-' and \"statusapp\" = '1'
									and LENGTH(replace(replace(\"N_IDCARD\",' ',''),'-','')) = '13' and replace(replace(\"N_IDCARD\",' ',''),'-','') = '$replace_N_IDCARD'
								group by replace(replace(\"N_IDCARD\",' ',''),'-','') having count(replace(replace(\"N_IDCARD\",' ',''),'-','')) > 1 and count(distinct \"CusID\") > '1'
								order by \"mainCardID\" ");
			while($resMain = pg_fetch_array($qry_main))
			{
				$mainCardID = $resMain["mainCardID"]; // บัตรประจำตัว
				
				if(is_numeric($mainCardID)) // ถ้าบัตรที่หามาได้เป็นตัวเลขทั้งหมด
				{
					// หาวันที่อนุมัติล่าสุด
					$qry_maxAppvDate = pg_query("select max(\"app_date\") as \"maxAppvDate\" from \"Customer_Temp\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$mainCardID' and \"statusapp\" = '1' ");
					while($res_maxAppvDate = pg_fetch_array($qry_maxAppvDate))
					{
						$maxAppvDate = $res_maxAppvDate["maxAppvDate"]; // วันที่อนุมัติล่าสุด
					}
					
					// หา CusID ล่าสุด
					$qry_cusForMaxAppvDate = pg_query("select \"CusID\" as \"selectCusID\" from \"Customer_Temp\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$mainCardID' and \"statusapp\" = '1' and \"app_date\" = '$maxAppvDate' ");
					$nomrows_cusForMaxAppvDate = pg_num_rows($qry_cusForMaxAppvDate); // จำนวนคนที่ถูกอนุมัติตามเวลาล่าสุด
					
					if($nomrows_cusForMaxAppvDate == 1) // ถ้ามีแค่คนเดียวถึงจะทำ
					{
						while($res_cusForMaxAppvDate = pg_fetch_array($qry_cusForMaxAppvDate))
						{
							$selectCusID = $res_cusForMaxAppvDate["selectCusID"]; // รหัสลูกค้าที่เลือกใช้
						}
						
						// หา CusID ของบัตรประชาชนนั้นๆ
						$qry_noCusID = pg_query("select distinct \"CusID\" as \"noCusID\" from \"Customer_Temp\" where replace(replace(\"N_IDCARD\",' ',''),'-','') = '$mainCardID' and \"statusapp\" = '1' and \"CusID\" <> '$selectCusID' ");
						while($res_noCusID = pg_fetch_array($qry_noCusID))
						{
							$noCusID = $res_noCusID["noCusID"]; // รหัสลูกค้าที่ไม่ต้องการ
							
							$test_sql=pg_query("select * from public.\"Fa1\" where \"CusID\" = '$noCusID' and \"CusID\" <> '$selectCusID' ");
							$rowNowFa1=pg_num_rows($test_sql);
							if($rowNowFa1 == 0){continue;} // ถ้าไม่เจอ CusID ที่ต้องการจะลบ แสดงว่าเคยลบไปแล้ว ไม่ต้องทำอีก ให้วนรอบต่อไปเลย
							while($result=pg_fetch_array($test_sql))
							{
								$A_FIRNAME_changeIDCARD = $result["A_FIRNAME"];
								$A_NAME_changeIDCARD = $result["A_NAME"];
								$A_SIRNAME_changeIDCARD = $result["A_SIRNAME"];
								$A_PAIR_changeIDCARD = $result["A_PAIR"];
								$A_NO_changeIDCARD = $result["A_NO"];
								$A_SUBNO_changeIDCARD = $result["A_SUBNO"];
								$A_SOI_changeIDCARD = $result["A_SOI"];
								$A_RD_changeIDCARD = $result["A_RD"];
								$A_TUM_changeIDCARD = $result["A_TUM"];
								$A_AUM_changeIDCARD = $result["A_AUM"];
								$A_PRO_changeIDCARD = $result["A_PRO"];
								$A_POST_changeIDCARD = $result["A_POST"];
								$Approved_changeIDCARD = $result["Approved"];
					
								if($A_FIRNAME_changeIDCARD == ""){$A_FIRNAME_changeIDCARD = "NULL";} else{$A_FIRNAME_changeIDCARD = "'$A_FIRNAME_changeIDCARD'";}
								if($A_NAME_changeIDCARD == ""){$A_NAME_changeIDCARD = "NULL";} else{$A_NAME_changeIDCARD = "'$A_NAME_changeIDCARD'";}
								if($A_SIRNAME_changeIDCARD == ""){$A_SIRNAME_changeIDCARD = "NULL";} else{$A_SIRNAME_changeIDCARD ="'$A_SIRNAME_changeIDCARD'";}
								if($A_PAIR_changeIDCARD == ""){$A_PAIR_changeIDCARD = "NULL";} else{$A_PAIR_changeIDCARD ="'$A_PAIR_changeIDCARD'";}
								if($A_NO_changeIDCARD == ""){$A_NO_changeIDCARD = "NULL";} else{$A_NO_changeIDCARD = "'$A_NO_changeIDCARD'";}
								if($A_SUBNO_changeIDCARD == ""){$A_SUBNO_changeIDCARD = "NULL";} else{$A_SUBNO_changeIDCARD = "'$A_SUBNO_changeIDCARD'";}
								if($A_SOI_changeIDCARD == ""){$A_SOI_changeIDCARD = "NULL";} else{$A_SOI_changeIDCARD = "'$A_SOI_changeIDCARD'";}
								if($A_RD_changeIDCARD == ""){$A_RD_changeIDCARD = "NULL";} else{$A_RD_changeIDCARD = "'$A_RD_changeIDCARD'";}
								if($A_TUM_changeIDCARD == ""){$A_TUM_changeIDCARD = "NULL";} else{$A_TUM_changeIDCARD = "'$A_TUM_changeIDCARD'";}
								if($A_AUM_changeIDCARD == ""){$A_AUM_changeIDCARD = "NULL";} else{$A_AUM_changeIDCARD = "'$A_AUM_changeIDCARD'";}
								if($A_PRO_changeIDCARD == ""){$A_PRO_changeIDCARD = "NULL";} else{$A_PRO_changeIDCARD = "'$A_PRO_changeIDCARD'";}
								if($A_POST_changeIDCARD == ""){$A_POST_changeIDCARD = "NULL";} else{$A_POST_changeIDCARD = "'$A_POST_changeIDCARD'";}
						
								$CusIDFn  = trim($result["CusID"]);
					
								$test_sql2=pg_query("select * from public.\"Fn\" where \"CusID\" = '$CusIDFn' ");
								while($resultFn=pg_fetch_array($test_sql2))
								{
									$N_STATE_changeIDCARD = $resultFn["N_STATE"];
									$N_SAN_changeIDCARD = $resultFn["N_SAN"];
									$N_AGE_changeIDCARD = $resultFn["N_AGE"];
									$N_CARD_changeIDCARD = $resultFn["N_CARD"];
									$N_IDCARD_changeIDCARD = $resultFn["N_IDCARD"];
									$N_OT_DATE_changeIDCARD = $resultFn["N_OT_DATE"];
									$N_BY_changeIDCARD = $resultFn["N_BY"];
									$N_OCC_changeIDCARD = $resultFn["N_OCC"];
									$N_ContactAdd_changeIDCARD = $resultFn["N_ContactAdd"];
									$N_CARDREF_changeIDCARD = $resultFn["N_CARDREF"];
							
									if($N_SAN_changeIDCARD == ""){$N_SAN_changeIDCARD = "NULL";} else{$N_SAN_changeIDCARD = "'$N_SAN_changeIDCARD'";}
									if($N_CARD_changeIDCARD == ""){$N_CARD_changeIDCARD = "NULL";} else{$N_CARD_changeIDCARD = "'$N_CARD_changeIDCARD'";}
									if($N_IDCARD_changeIDCARD == ""){$N_IDCARD_changeIDCARD = "NULL";} else{$N_IDCARD_changeIDCARD = "'$N_IDCARD_changeIDCARD'";}
									if($N_BY_changeIDCARD == ""){$N_BY_changeIDCARD = "NULL";} else{$N_BY_changeIDCARD = "'$N_BY_changeIDCARD'";}
									if($N_OCC_changeIDCARD == ""){$N_OCC_changeIDCARD = "NULL";} else{$N_OCC_changeIDCARD = "'$N_OCC_changeIDCARD'";}
									if($N_ContactAdd_changeIDCARD == ""){$N_ContactAdd_changeIDCARD = "NULL";} else{$N_ContactAdd_changeIDCARD = "'$N_ContactAdd_changeIDCARD'";}
									if($N_CARDREF_changeIDCARD == ""){$N_CARDREF_changeIDCARD = "NULL";} else{$N_CARDREF_changeIDCARD = "'$N_CARDREF_changeIDCARD'";}
								}
							}
						
							// copy ข้อมูลไปไว้ในตาราง Fa1_temp
							$test_sql3 = "insert into public.\"Fa1_temp\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"Approved\")
										values ('$noCusID' , $A_FIRNAME_changeIDCARD , $A_NAME_changeIDCARD , $A_SIRNAME_changeIDCARD , $A_PAIR_changeIDCARD , $A_NO_changeIDCARD , $A_SUBNO_changeIDCARD , $A_SOI_changeIDCARD , $A_RD_changeIDCARD , $A_TUM_changeIDCARD , $A_AUM_changeIDCARD , $A_PRO_changeIDCARD , $A_POST_changeIDCARD , '$Approved_changeIDCARD')";
							if($resultFaTemp=pg_query($test_sql3))
							{}
							else
							{
								$status++;
							}
							
							// copy ข้อมูลไปไว้ในตาราง Fn_temp
							$test_sql4="insert into public.\"Fn_temp\" (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\",\"N_CARDREF\")
										values ('$noCusID' , '$N_STATE_changeIDCARD' , $N_SAN_changeIDCARD , '$N_AGE_changeIDCARD' , $N_CARD_changeIDCARD , $N_IDCARD_changeIDCARD , '$N_OT_DATE_changeIDCARD' , $N_BY_changeIDCARD , $N_OCC_changeIDCARD , $N_ContactAdd_changeIDCARD , $N_CARDREF_changeIDCARD )";
							if($resultFnTemp=pg_query($test_sql4))
							{}
							else
							{
								$status++;
							}
							
							// ลบข้อมูลออกจาก Fa1
							$test_sql5="delete from public.\"Fa1\" where \"CusID\" = '$noCusID'";
							if($resultFnTemp=pg_query($test_sql5))
							{}
							else
							{
								$status++;
							}
							
							// ลบข้อมูลออกจาก Fn
							$test_sql6="delete from public.\"Fn\" where \"CusID\" = '$noCusID'";
							if($resultFnTemp=pg_query($test_sql6))
							{}
							else
							{
								$status++;
							}
							
							//-------------- Update ตารางอื่นๆที่มี CusID เป็นส่วนประกอบ โดยให้ CusID ที่ไม่ได้เลือกเปลี่ยนเป็นอันที่เลือกให้หมด
								$sql = "select TABLE_SCHEMA as sm, TABLE_NAME as tb, COLUMN_NAME as test
										from INFORMATION_SCHEMA.COLUMNS
										where TABLE_NAME not in(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_TYPE = 'VIEW')
										and data_type in('character varying','text','character','char','regclass','name')";
								$query = pg_query($sql);
								while($re = pg_fetch_array($query))
								{
									$SCHEMA = $re['sm'];
									$realtb = $re['tb'];
									$column = $re['test'];
									
									if(($SCHEMA == "public" && $realtb == "change_cus") || ($SCHEMA == "public" && $realtb == "Customer_Temp") || ($SCHEMA == "public" && $realtb == "Fa1_temp") || ($SCHEMA == "public" && $realtb == "Fn_temp"))
									{
										continue;
									}

									$sql1 = "select \"$column\" as cusid from $SCHEMA.\"$realtb\" where \"$column\" LIKE 'C%' and \"$column\" is not null limit 1";
									$query1 = pg_query($sql1);
									$rows = pg_num_rows($query1);
									$re1 = pg_fetch_array($query1);
									if($rows > 0 )
									{
										$chkdigi = trim($re1['cusid']);
										$chkre = substr($chkdigi,1);
										$cjkre2 = strlen($chkre);

										if($cjkre2 == 5)
										{
											if(is_numeric($chkre))
											{
												$test_sql7="update $SCHEMA.\"$realtb\" set \"$column\"='$selectCusID' where \"$column\"='$noCusID'";
												if($resultFnTemp=pg_query($test_sql7))
												{}
												else
												{
													$status++;
												}
											}
										}
									}
								}
							//-------------- จบการ Update ตารางอื่นๆที่มี CusID เป็นส่วนประกอบ โดยให้ CusID ที่ไม่ได้เลือกเปลี่ยนเป็นอันที่เลือกให้หมด
							
							//--- เก็บประวัติการเปลี่ยน CusID
								$test_sql99="insert into public.\"change_cus\" (\"Cus_old\",\"Cus_new\") values ('$noCusID' , '$selectCusID')";
								if($resultHistory=pg_query($test_sql99))
								{}
								else
								{
									$status++;
								}
							//--- จบการเก็บประวัติการเปลี่ยน CusID
						}
					}
				}
			}*/
		//----------- จบการกรองลูกค้าที่ซ้ำจากบัตรประชาชนของคนนี้
		
		
		//----------- กรองลูกค้าที่ซ้ำจากบัตรอื่นๆของคนนี้
		
			$replace_N_CARDREF = str_replace("'","",str_replace("-","",str_replace(" ","",$N_CARDREF))); // เลขบัตรประชาชนที่ตัดช่องว่าง เครื่องหมายขีด และเขาเดี่ยวออก
			
			$qry_gatherCustomerRef = pg_query("select check_duplicate_customer('$replace_N_CARDREF','2')");
			$gatherCustomerRef = pg_fetch_result($qry_gatherCustomerRef,0);
			
			/*$qry_main_ref = pg_query("select distinct replace(replace(\"N_CARDREF\",' ',''),'-','') as \"mainCardID\",
									count(replace(replace(\"N_CARDREF\",' ',''),'-','')) as \"countIDCARD\",
									count(distinct \"CusID\") as \"countCusID\"
								from \"Customer_Temp\"
								where \"N_CARDREF\" is not null and \"N_CARDREF\" <> '' and \"N_CARDREF\" not like '%-' and \"statusapp\" = '1' and \"N_IDCARD\" is null
									and LENGTH(replace(replace(\"N_CARDREF\",' ',''),'-','')) = '13' and replace(replace(\"N_CARDREF\",' ',''),'-','') = '$replace_N_CARDREF'
								group by replace(replace(\"N_CARDREF\",' ',''),'-','') having count(replace(replace(\"N_CARDREF\",' ',''),'-','')) > 1 and count(distinct \"CusID\") > '1'
								order by \"mainCardID\" ");
			while($resMainRef = pg_fetch_array($qry_main_ref))
			{
				$mainCardID = $resMainRef["mainCardID"]; // บัตรประจำตัว
				
				if(is_numeric($mainCardID)) // ถ้าบัตรที่หามาได้เป็นตัวเลขทั้งหมด
				{
					// หาวันที่อนุมัติล่าสุด
					$qry_maxAppvDate = pg_query("select max(\"app_date\") as \"maxAppvDate\" from \"Customer_Temp\" where replace(replace(\"N_CARDREF\",' ',''),'-','') = '$mainCardID' and \"statusapp\" = '1' and \"N_IDCARD\" is null ");
					while($res_maxAppvDate = pg_fetch_array($qry_maxAppvDate))
					{
						$maxAppvDate = $res_maxAppvDate["maxAppvDate"]; // วันที่อนุมัติล่าสุด
					}
					
					// หา CusID ล่าสุด
					$qry_cusForMaxAppvDate = pg_query("select \"CusID\" as \"selectCusID\" from \"Customer_Temp\" where replace(replace(\"N_CARDREF\",' ',''),'-','') = '$mainCardID' and \"statusapp\" = '1' and \"N_IDCARD\" is null and \"app_date\" = '$maxAppvDate' ");
					$nomrows_cusForMaxAppvDate = pg_num_rows($qry_cusForMaxAppvDate); // จำนวนคนที่ถูกอนุมัติตามเวลาล่าสุด
					
					if($nomrows_cusForMaxAppvDate == 1) // ถ้ามีแค่คนเดียวถึงจะทำ
					{
						$i++;
						
						while($res_cusForMaxAppvDate = pg_fetch_array($qry_cusForMaxAppvDate))
						{
							$selectCusID = $res_cusForMaxAppvDate["selectCusID"]; // รหัสลูกค้าที่เลือกใช้
						}
						
						// หา CusID ของบัตรประชาชนนั้นๆ
						$qry_noCusID = pg_query("select distinct \"CusID\" as \"noCusID\" from \"Customer_Temp\" where replace(replace(\"N_CARDREF\",' ',''),'-','') = '$mainCardID' and \"statusapp\" = '1' and \"CusID\" <> '$selectCusID' and \"N_IDCARD\" is null ");
						while($res_noCusID = pg_fetch_array($qry_noCusID))
						{
							$noCusID = $res_noCusID["noCusID"]; // รหัสลูกค้าที่ไม่ต้องการ
							
							$test_sql=pg_query("select * from public.\"Fa1\" where \"CusID\" = '$noCusID' and \"CusID\" <> '$selectCusID' ");
							$rowNowFa1=pg_num_rows($test_sql);
							if($rowNowFa1 == 0){continue;} // ถ้าไม่เจอ CusID ที่ต้องการจะลบ แสดงว่าเคยลบไปแล้ว ไม่ต้องทำอีก ให้วนรอบต่อไปเลย
							while($result=pg_fetch_array($test_sql))
							{
								$A_FIRNAME_N_CARDREF = $result["A_FIRNAME"];
								$A_NAME_N_CARDREF = $result["A_NAME"];
								$A_SIRNAME_N_CARDREF = $result["A_SIRNAME"];
								$A_PAIR_N_CARDREF = $result["A_PAIR"];
								$A_NO_N_CARDREF = $result["A_NO"];
								$A_SUBNO_N_CARDREF = $result["A_SUBNO"];
								$A_SOI_N_CARDREF = $result["A_SOI"];
								$A_RD_N_CARDREF = $result["A_RD"];
								$A_TUM_N_CARDREF = $result["A_TUM"];
								$A_AUM_N_CARDREF = $result["A_AUM"];
								$A_PRO_N_CARDREF = $result["A_PRO"];
								$A_POST_N_CARDREF = $result["A_POST"];
								$Approved_N_CARDREF = $result["Approved"];
					
								if($A_FIRNAME_N_CARDREF == ""){$A_FIRNAME_N_CARDREF = "NULL";} else{$A_FIRNAME_N_CARDREF = "'$A_FIRNAME_N_CARDREF'";}
								if($A_NAME_N_CARDREF == ""){$A_NAME_N_CARDREF = "NULL";} else{$A_NAME_N_CARDREF = "'$A_NAME_N_CARDREF'";}
								if($A_SIRNAME_N_CARDREF == ""){$A_SIRNAME_N_CARDREF = "NULL";} else{$A_SIRNAME_N_CARDREF ="'$A_SIRNAME_N_CARDREF'";}
								if($A_PAIR_N_CARDREF == ""){$A_PAIR_N_CARDREF = "NULL";} else{$A_PAIR_N_CARDREF ="'$A_PAIR_N_CARDREF'";}
								if($A_NO_N_CARDREF == ""){$A_NO_N_CARDREF = "NULL";} else{$A_NO_N_CARDREF = "'$A_NO_N_CARDREF'";}
								if($A_SUBNO_N_CARDREF == ""){$A_SUBNO_N_CARDREF = "NULL";} else{$A_SUBNO_N_CARDREF = "'$A_SUBNO_N_CARDREF'";}
								if($A_SOI_N_CARDREF == ""){$A_SOI_N_CARDREF = "NULL";} else{$A_SOI_N_CARDREF = "'$A_SOI_N_CARDREF'";}
								if($A_RD_N_CARDREF == ""){$A_RD_N_CARDREF = "NULL";} else{$A_RD_N_CARDREF = "'$A_RD_N_CARDREF'";}
								if($A_TUM_N_CARDREF == ""){$A_TUM_N_CARDREF = "NULL";} else{$A_TUM_N_CARDREF = "'$A_TUM_N_CARDREF'";}
								if($A_AUM_N_CARDREF == ""){$A_AUM_N_CARDREF = "NULL";} else{$A_AUM_N_CARDREF = "'$A_AUM_N_CARDREF'";}
								if($A_PRO_N_CARDREF == ""){$A_PRO_N_CARDREF = "NULL";} else{$A_PRO_N_CARDREF = "'$A_PRO_N_CARDREF'";}
								if($A_POST_N_CARDREF == ""){$A_POST_N_CARDREF = "NULL";} else{$A_POST_N_CARDREF = "'$A_POST_N_CARDREF'";}
						
								$CusIDFn  = trim($result["CusID"]);
					
								$test_sql2=pg_query("select * from public.\"Fn\" where \"CusID\" = '$CusIDFn' ");
								while($resultFn=pg_fetch_array($test_sql2))
								{
									$N_STATE_N_CARDREF = $resultFn["N_STATE"];
									$N_SAN_N_CARDREF = $resultFn["N_SAN"];
									$N_AGE_N_CARDREF = $resultFn["N_AGE"];
									$N_CARD_N_CARDREF = $resultFn["N_CARD"];
									$N_CARDREF_N_CARDREF = $resultFn["N_CARDREF"];
									$N_OT_DATE_N_CARDREF = $resultFn["N_OT_DATE"];
									$N_BY_N_CARDREF = $resultFn["N_BY"];
									$N_OCC_N_CARDREF = $resultFn["N_OCC"];
									$N_ContactAdd_N_CARDREF = $resultFn["N_ContactAdd"];
									$N_CARDREF_N_CARDREF = $resultFn["N_CARDREF"];
							
									if($N_SAN_N_CARDREF == ""){$N_SAN_N_CARDREF = "NULL";} else{$N_SAN_N_CARDREF = "'$N_SAN_N_CARDREF'";}
									if($N_CARD_N_CARDREF == ""){$N_CARD_N_CARDREF = "NULL";} else{$N_CARD_N_CARDREF = "'$N_CARD_N_CARDREF'";}
									if($N_CARDREF_N_CARDREF == ""){$N_CARDREF_N_CARDREF = "NULL";} else{$N_CARDREF_N_CARDREF = "'$N_CARDREF_N_CARDREF'";}
									if($N_BY_N_CARDREF == ""){$N_BY_N_CARDREF = "NULL";} else{$N_BY_N_CARDREF = "'$N_BY_N_CARDREF'";}
									if($N_OCC_N_CARDREF == ""){$N_OCC_N_CARDREF = "NULL";} else{$N_OCC_N_CARDREF = "'$N_OCC_N_CARDREF'";}
									if($N_ContactAdd_N_CARDREF == ""){$N_ContactAdd_N_CARDREF = "NULL";} else{$N_ContactAdd_N_CARDREF = "'$N_ContactAdd_N_CARDREF'";}
									if($N_CARDREF_N_CARDREF == ""){$N_CARDREF_N_CARDREF = "NULL";} else{$N_CARDREF_N_CARDREF = "'$N_CARDREF_N_CARDREF'";}
								}
							}
						
							// copy ข้อมูลไปไว้ในตาราง Fa1_temp
							$test_sql3_ref = "insert into public.\"Fa1_temp\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"Approved\")
										values ('$noCusID' , $A_FIRNAME_N_CARDREF , $A_NAME_N_CARDREF , $A_SIRNAME_N_CARDREF , $A_PAIR_N_CARDREF , $A_NO_N_CARDREF , $A_SUBNO_N_CARDREF , $A_SOI_N_CARDREF , $A_RD_N_CARDREF , $A_TUM_N_CARDREF , $A_AUM_N_CARDREF , $A_PRO_N_CARDREF , $A_POST_N_CARDREF , '$Approved_N_CARDREF')";
							if($resultFaTemp=pg_query($test_sql3_ref))
							{}
							else
							{
								$status++;
							}
							
							// copy ข้อมูลไปไว้ในตาราง Fn_temp
							$test_sql4="insert into public.\"Fn_temp\" (\"CusID\",\"N_STATE\",\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_CARDREF\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\",\"N_CARDREF\")
										values ('$noCusID' , '$N_STATE_N_CARDREF' , $N_SAN_N_CARDREF , '$N_AGE_N_CARDREF' , $N_CARD_N_CARDREF , $N_CARDREF_N_CARDREF , '$N_OT_DATE_N_CARDREF' , $N_BY_N_CARDREF , $N_OCC_N_CARDREF , $N_ContactAdd_N_CARDREF , $N_CARDREF_N_CARDREF )";
							if($resultFnTemp=pg_query($test_sql4))
							{}
							else
							{
								$status++;
							}
							
							// ลบข้อมูลออกจาก Fa1
							$test_sql5="delete from public.\"Fa1\" where \"CusID\" = '$noCusID'";
							if($resultFnTemp=pg_query($test_sql5))
							{}
							else
							{
								$status++;
							}
							
							// ลบข้อมูลออกจาก Fn
							$test_sql6="delete from public.\"Fn\" where \"CusID\" = '$noCusID'";
							if($resultFnTemp=pg_query($test_sql6))
							{}
							else
							{
								$status++;
							}
							
							//-------------- Update ตารางอื่นๆที่มี CusID เป็นส่วนประกอบ โดยให้ CusID ที่ไม่ได้เลือกเปลี่ยนเป็นอันที่เลือกให้หมด
								$sql = "select TABLE_SCHEMA as sm, TABLE_NAME as tb, COLUMN_NAME as test
										from INFORMATION_SCHEMA.COLUMNS
										where TABLE_NAME not in(select TABLE_NAME from INFORMATION_SCHEMA.TABLES where TABLE_TYPE = 'VIEW')
										and data_type in('character varying','text','character','char','regclass','name')";
								$query = pg_query($sql);
								while($re = pg_fetch_array($query))
								{
									$SCHEMA = $re['sm'];
									$realtb = $re['tb'];
									$column = $re['test'];
									
									if(($SCHEMA == "public" && $realtb == "change_cus") || ($SCHEMA == "public" && $realtb == "Customer_Temp") || ($SCHEMA == "public" && $realtb == "Fa1_temp") || ($SCHEMA == "public" && $realtb == "Fn_temp"))
									{
										continue;
									}

									$sql1 = "select \"$column\" as cusid from $SCHEMA.\"$realtb\" where \"$column\" LIKE 'C%' and \"$column\" is not null limit 1";
									$query1 = pg_query($sql1);
									$rows = pg_num_rows($query1);
									$re1 = pg_fetch_array($query1);
									if($rows > 0 )
									{
										$chkdigi = trim($re1['cusid']);
										$chkre = substr($chkdigi,1);
										$cjkre2 = strlen($chkre);

										if($cjkre2 == 5)
										{
											if(is_numeric($chkre))
											{
												$test_sql7="update $SCHEMA.\"$realtb\" set \"$column\"='$selectCusID' where \"$column\"='$noCusID'";
												if($resultFnTemp=pg_query($test_sql7))
												{}
												else
												{
													$status++;
												}
											}
										}
									}
								}
							//-------------- จบการ Update ตารางอื่นๆที่มี CusID เป็นส่วนประกอบ โดยให้ CusID ที่ไม่ได้เลือกเปลี่ยนเป็นอันที่เลือกให้หมด
							
							//--- เก็บประวัติการเปลี่ยน CusID
								$test_sql99="insert into public.\"change_cus\" (\"Cus_old\",\"Cus_new\") values ('$noCusID' , '$selectCusID')";
								if($resultHistory=pg_query($test_sql99))
								{}
								else
								{
									$status++;
								}
							//--- จบการเก็บประวัติการเปลี่ยน CusID
						}
					}
				}
			}*/
		
		//----------- จบกรองลูกค้าที่ซ้ำจากบัตรอื่นๆของคนนี้
		}
	}	
}else{ //กรณีไม่อนุมัติ
		
		//เช็ค emplevel
		$appvedit="yes";
		$query_leveluser = pg_query("select \"emplevel\" from \"Vfuser\" where \"id_user\" = '$app_user' ");
		$leveluser = pg_fetch_array($query_leveluser);
		$emplevel=$leveluser["emplevel"];	
		if($emplevel<=1){}
		else {
			//คนที่ทำการเพิ่ม/แก้ไข
			$query_add= pg_query("select \"add_user\" from \"Customer_Temp\" where \"CustempID\" = '$CustempID'");
			$editre_resu= pg_fetch_array($query_add);
			$app_useradd=$editre_resu["add_user"];
			if($app_useradd==$app_user){
				$appvedit="no";
				$status++;
				$error_check = "เนื่องจาก ผู้ทำรายการไม่อนุมัติจะต้องเป็นคนละคนกับผู้ขออนุมัติ";//น้อยกว่า 15 และ เป็นคนเดียวกัน
				}	
		}				
		if($appvedit=="no"){}
		else{
			$qry_cus=pg_query("select \"CusID\" from \"Customer_Temp\" where \"CustempID\"='$CustempID'");
			$res_cus=pg_fetch_array($qry_cus);
			$CusID=$res_cus["CusID"];
	
			$CusChk=substr($CusID,0,2);
			if($CusChk=="CT"){ //ให้ตรวจสอบว่ามีการผูกสัญญาอยู่หรือไม่ ถ้ามีให้ลบข้อมูลทิ้ง 
				$del="DELETE FROM \"Fp_Fa1\" WHERE \"CusID\"='$CusID'";
				if($resdel=pg_query($del)){
				}else{
					$status++;
				}
		
				$delcontact="DELETE FROM \"ContactCus\" WHERE \"CusID\"='$CusID'";
				if($resdelcontact=pg_query($delcontact)){
				}else{
					$status++;
				}
			}
			//update ข้อมูลใน Customer_Temp ว่าได้ approve แล้ว 
			$update="update \"Customer_Temp\" set  
				\"app_user\"='$app_user', 
				\"app_date\"='$app_date', 
				statusapp='0'
				where \"CustempID\" = '$CustempID'";
			if($result=pg_query($update)){
			}else{
			$status++;
			
		}
		// update ข้อมูล ที่ "Re_indentity_cus_temp"
			$query_selectCus= pg_query("select \"CusID\",\"add_user\",\"add_date\",\"N_IDCARD\" from \"Customer_Temp\" where \"CustempID\" = '$CustempID'");
			$editre_selectCus= pg_fetch_array($query_selectCus);
			$app_CusID=$editre_selectCus["CusID"];
			$app_user=$editre_selectCus["add_user"];
			$app_date=$editre_selectCus["add_date"];
			$app_IDCARD=$editre_selectCus["N_IDCARD"];
			$app_IDCARD = str_replace(" ","",$app_IDCARD);
			$app_IDCARD = str_replace("-","",$app_IDCARD);
			if($statuscus=="'0'"){
				if(strlen($app_IDCARD)==13){
					$query_Re_indentity_cus= pg_query("select reiden_pk,identity_new from \"Re_indentity_cus_temp\" where \"CusID\"='$app_CusID' and \"app_status\"='1' and \"id_user\"='$app_user' and \"date\"='$app_date' and \"identity_new\"='$app_IDCARD'");
					$row_num= pg_num_rows($query_Re_indentity_cus);
					$res_reiden= pg_fetch_array($query_Re_indentity_cus);
					$reiden=$res_reiden["reiden_pk"];
					$identity_new=$res_reiden["identity_new"];
					if($row_num>1){
						$status++;
						$error_check ="เนื่องจาก การอนุมัติเกี่ยวกับการ แก้ไขบัตรประชาชนมีข้อผิดพลาด";}
					else if($row_num==1){	
						$sql1 = "UPDATE \"Re_indentity_cus_temp\" SET app_status='3',app_user='$app_user',app_date='$app_date' WHERE reiden_pk = '$reiden'";		
						if($query1 = pg_query($sql1)){}else{ $status++; }
					}
					else{					
						if($app_IDCARD==$identity_new){
						$status++;
						$error_check ="เนื่องจาก รายการที่เกี่ยวข้องกับการแก้ไขบัตรประชาชนมีการทำรายการไปก่อนหน้านี้แล้ว กรุณาลองใหม่ภายหลัง!";
						}
					}
				}
				else{
					$status++;
					$error_check ="เลขบัตรประชาชนไม่ครบ 13 หลัก";
				}
			}
	}
}//จบกดไม่อนุมัติ

	if($status == 0){
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$app_user', '(ALL) อนุมัติข้อมูลลูกค้า', '$app_date')");
	//ACTIONLOG---
	pg_query("COMMIT");
	
	echo "<font size=4><b>บันทึกข้อมูลเรียบร้อยแล้ว</b></font><br><br>";
	//echo "<meta http-equiv='refresh' content='2; URL=frm_approve.php'>";
	echo "<input type=\"button\" value=\" ตกลง \" onclick=\"javascript:RefreshMe();\">";
	}else{
		pg_query("ROLLBACK");
		echo $ins_error."<br>$up_error";
		echo "<div style=\"padding: 50px 0px;text-align:center;\"><font size=4><b>ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง</b></font><br>";
		echo $error_check."</div>";
	//echo "<input type=button value=\"กลับไปทำรายการ \" onclick=\"window.location='frm_approve.php'\">";
		echo "<input type=\"button\" value=\" กลับไปทำรายการ \" onclick=\"javascript:RefreshMe();\">";
		}
	}

?>
</td>
</tr>
</table>
</body>
</html>
<?php
}
?>