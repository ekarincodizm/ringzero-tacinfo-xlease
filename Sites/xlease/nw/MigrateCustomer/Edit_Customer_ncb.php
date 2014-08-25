<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include("../../config/config.php");
include("../../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว
include("../function/checknull.php"); // ไฟล์ function chacknull ใช้เพื่อตรวจสอบค่าว่างของตัวแปรนั้นๆ วิธีใช้คือ $A = checknull($A); หาก $A เป็นค่าว่างจะส่งค่า "null" กลับมา หากไม่ใช่จะส่งค่า '$A' กลับมา...
$id_user = $_SESSION["av_iduser"];
$add_date = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status=0;

// แก้ไขข้อมูลลูกค้า คนที่ 1
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'สมชาย' and \"A_SIRNAME\" = 'ตุลาธาร' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++;
	
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",'74/142',null, 'ช่างอากาศอุทิศ 18', 'ช่างอากาศอุทิศ', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '48', \"N_CARD\", '3100500625739', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1963-08-01',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1963-08-01',\"A_NO\"='74/142',\"A_SUBNO\"=null,
	\"A_SOI\"='ช่างอากาศอุทิศ 18',\"A_RD\"='ช่างอากาศอุทิศ' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3100500625739' , \"N_AGE\" = '48'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}
// แก้ไขข้อมูลลูกค้า คนที่2
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'ธนพร' and \"A_SIRNAME\" = 'เชิดชูธรรมขจร' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++;
	
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '47', \"N_CARD\", '3101701569951', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1965-04-05',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1965-04-05' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3101701569951' , \"N_AGE\" = '47'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
	// ตรวจสอบการผูกคนกับสัญญา
	$sql_SearchContract = pg_query("select * from public.\"thcap_ContactCus\" where \"contractID\" = 'MG-BK01-5500058' and \"CusID\" = '$CusID' ");
	$numrows = pg_num_rows($sql_SearchContract);
	if($numrows != 1)
	{
		$status++;
	}
	else
	{		
		$update_ContactCus = "update public.\"thcap_ContactCus\" set \"CusState\" = '1' where \"CusID\" = '$CusID' and \"contractID\" = 'MG-BK01-5500058' ";
		if($result = pg_query($update_ContactCus)){
		}else{
			$status++;
		}
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที3
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'บุญเลิศ' and \"A_SIRNAME\" = 'เชื้อจาก' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++;
	
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '56', \"N_CARD\", '3160600112032', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1956-01-01',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1956-01-01' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3160600112032' , \"N_AGE\" = '56'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 4
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'วาสนา' and \"A_SIRNAME\" = 'ดานเค่' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++;
	
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", 'นายเคล์าส ดีแทร์ ฮันส์ เฮลมุท  ดานเค่',\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '58', 'บัตรประชาชน', '3100903028518', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1953-09-19',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_PAIR\" = 'นายเคล์าส ดีแทร์ ฮันส์ เฮลมุท  ดานเค่' , \"A_BIRTHDAY\" = '1953-09-19' where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_CARD\" = 'บัตรประชาชน' , \"N_IDCARD\" = '3100903028518' , \"N_AGE\" = '58'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 5
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'กนกวรรณ' and \"A_SIRNAME\" = 'แดงเจริญ' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++;
	
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", null,\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '40', \"N_CARD\", '3210500266428', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",'0000',\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1972-06-19',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_PAIR\" = null , \"A_BIRTHDAY\" = '1972-06-19' , \"A_STATUS\" = '0000' where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '3210500266428' , \"N_AGE\" = '40'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 6
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'สพล' and \"A_SIRNAME\" = 'ผลพานิช' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID <= 0)
{
	$status++;
	
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	
		// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
		$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
		while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
		{
			$edittime = $SearchMaxEdit["edittime"];
		}
		$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
		
		$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
						\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
						\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
						\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
						\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
						\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
				select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
						\"A_SIRNAME\", 'นางหงษ์  ผลพานิช',\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
						'ไทย', '40', \"N_CARD\", '3101200449251', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
						\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
						'1971-10-08',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
						LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
		
		if($result = pg_query($insert_Customer_Temp)){
		}else{
			$status++;
		}
		
		$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\" = '1971-10-08' , \"A_PAIR\" = 'นางหงษ์  ผลพานิช' where \"CusID\" = '$CusID' ";
		if($result = pg_query($update_Fa1)){
		}else{
			$status++;
		}
		
		$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '3101200449251' , \"N_AGE\" = '40'
						where \"CusID\" = '$CusID' ";
		if($result = pg_query($update_Fn)){
		}else{
			$status++;
		}
	
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 7
$sql_SearchCusID = pg_query("select * from public.\"Fn\" where \"N_IDCARD\" = '3320700143752' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++;
	
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", 'หงษ์',
					\"A_SIRNAME\", 'นายสพล  ผลพานิช',\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '46', \"N_CARD\", '3320700143752', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1966-06-08',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\" = '1966-06-08' , \"A_PAIR\" = 'นายสพล  ผลพานิช' , \"A_NAME\" = 'หงษ์' where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '3320700143752' , \"N_AGE\" = '46'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
	// ตรวจสอบการผูกคนกับสัญญา
	$sql_SearchContract = pg_query("select * from public.\"thcap_ContactCus\" where \"contractID\" = 'MG-BK01-5500062' and \"CusID\" = '$CusID' ");
	$numrows = pg_num_rows($sql_SearchContract);
	if($numrows != 1)
	{
		$status++;
	}
	else
	{		
		$update_ContactCus = "update public.\"thcap_ContactCus\" set \"CusState\" = '1' where \"CusID\" = '$CusID' and \"contractID\" = 'MG-BK01-5500062' ";
		if($result = pg_query($update_ContactCus)){
		}else{
			$status++;
		}
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 8
$sql_SearchCusID = pg_query("select * from public.\"Fn\" where \"N_IDCARD\" = '3100603195184' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++;
	
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '54', \"N_CARD\", '3100603195184', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",'0002',\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1958-01-24',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\" = '1958-01-24' , \"A_STATUS\" = '0002' where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '3100603195184' , \"N_AGE\" = '54'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 9
$sql_SearchCusID = pg_query("select * from public.\"Fn\" where \"N_IDCARD\" = '3730101033328' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++;
	
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",'185',null, 'เพชรเกษม 5', 'เพชรเกษม', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '61', \"N_CARD\", '3730101033328', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1951-02-28',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\" = '1951-02-28' , \"A_NO\" = '185' , \"A_SUBNO\" = null , \"A_SOI\" = 'เพชรเกษม 5' , \"A_RD\" = 'เพชรเกษม' where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '3730101033328' , \"N_AGE\" = '61'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 10
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'อภินัส' and \"A_SIRNAME\" = 'นาคมั่น' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++;
	
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '22', \"N_CARD\", '1103700070494', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",'0002',\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1990-02-26',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1990-02-26',\"A_STATUS\" = '0002' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '1103700070494' , \"N_AGE\" = '22'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}


// แก้ไขข้อมูลลูกค้า คนที่ 11
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'ปาริชาติ' and \"A_SIRNAME\" = 'ทองคำดี' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++;
	
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", 'สนามบินน้ำ', \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '42', \"N_CARD\", '3100500056548', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1970-03-04',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1970-03-04',\"A_TUM\" = 'สนามบินน้ำ'  where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3100500056548' , \"N_AGE\" = '42'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}


// แก้ไขข้อมูลลูกค้า คนที่ 12
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'อภิญญา' and \"A_SIRNAME\" = 'ภักขะทิพย์' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++;
	
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", '10140',
					'ไทย', '62', \"N_CARD\", '3102401324261', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1949-09-01',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1949-09-01',\"A_POST\" = '10140' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3102401324261' , \"N_AGE\" = '62'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 13
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'บุญฤทธิ์' and \"A_SIRNAME\" = 'ภักขะทิพย์' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", '10140',
					'ไทย', '43', \"N_CARD\", '3102401324279', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1969-02-14',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1969-02-14',\"A_POST\" = '10140' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3102401324279' , \"N_AGE\" = '43'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 14
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'ณัฐพล' and \"A_SIRNAME\" = 'ภักขะทิพย์' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", '10140',
					'ไทย', '41', \"N_CARD\", '3102401324287', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1970-08-07',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1970-08-07',\"A_POST\" = '10140' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3102401324287' , \"N_AGE\" = '41'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 15
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'พิณัชชา' and \"A_SIRNAME\" = 'ใหญ่โสมานัง' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",'609/66 ชั้น 5 อาคารลุมพินี วิลล์ ศูนย์วัฒนธรรม',null, null, 'ประชาอุทิศ', \"A_TUM\", \"A_AUM\", \"A_PRO\", '10140',
					'ไทย', '29', \"N_CARD\", '3470500016306', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1982-08-03',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1982-08-03',\"A_NO\" = '609/66 ชั้น 5 อาคารลุมพินี วิลล์ ศูนย์วัฒนธรรม',
	\"A_SUBNO\"=null, \"A_SOI\"=null,\"A_RD\"='ประชาอุทิศ'  where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3470500016306' , \"N_AGE\" = '29'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 16
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'ไมตรี' and \"A_SIRNAME\" = 'อาดำอิส' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", null,'25',null, 'สวนสน 9', 'สุขาภิบาล 3', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '44', \"N_CARD\", '3101402054711', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",'0002',\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1968-04-06',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1968-04-06',\"A_NO\"='25',\"A_SUBNO\"=null,\"A_SOI\" = 'สวนสน 9',\"A_RD\"='สุขาภิบาล 3',\"A_STATUS\"='0002',\"A_PAIR\"=null where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3101402054711' , \"N_AGE\" = '44'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 17
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'วนิดา' and \"A_SIRNAME\" = 'วรินธานนท์' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", null,'25',null, 'สวนสน 9', 'สุขาภิบาล 3', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '45', \"N_CARD\", '3102001448418', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",'0002',\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1966-10-29',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1966-10-29',\"A_NO\"='25',\"A_SUBNO\"=null,\"A_SOI\" = 'สวนสน 9',\"A_RD\"='สุขาภิบาล 3',\"A_STATUS\"='0002',\"A_PAIR\" = null where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3102001448418' , \"N_AGE\" = '45'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 18
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'วิมล' and \"A_SIRNAME\" = 'ไชยยศ' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", 'นางพวงเพ็ญ  ไชยยศ',\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '70', \"N_CARD\", '3101400735673', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1942-05-23',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1942-05-23',\"A_PAIR\"='นางพวงเพ็ญ  ไชยยศ'  where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3101400735673' , \"N_AGE\" = '70'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
	// ตรวจสอบการผูกคนกับสัญญา
	$sql_SearchContract = pg_query("select * from public.\"thcap_ContactCus\" where \"contractID\" = 'MG-BK01-5500069' and \"CusID\" = '$CusID' ");
	$numrows = pg_num_rows($sql_SearchContract);
	if($numrows != 1)
	{
		$status++; 
	}
	else
	{		
		$update_ContactCus = "update public.\"thcap_ContactCus\" set \"CusState\" = '1' where \"CusID\" = '$CusID' and \"contractID\" = 'MG-BK01-5500069' ";
		if($result = pg_query($update_ContactCus)){
		}else{
			$status++;
		}
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 19
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'พวงเพ็ญ' and \"A_SIRNAME\" = 'ไชยยศ' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", 'ร.ต.ท. วิมล  ไชยยศ',\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '77', \"N_CARD\", '3101700460807', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1935-01-01',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1935-01-01',\"A_PAIR\"='ร.ต.ท. วิมล  ไชยยศ'  where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '3101700460807' , \"N_AGE\" = '77'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 20
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'นพพงศ์' and \"A_SIRNAME\" = 'ไชยยศ' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", 'นางสาวธันยมัย เกรียงไกร', \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '28', \"N_CARD\", '1841300002477', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1984-07-23',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_PAIR\" = 'นางสาวธันยมัย เกรียงไกร' , \"A_BIRTHDAY\" = '1984-07-23' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '1841300002477' , \"N_AGE\" = '28'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
	// ตรวจสอบการผูกคนกับสัญญา
	$sql_SearchContract = pg_query("select * from public.\"thcap_ContactCus\" where \"contractID\" = 'MG-BK01-5500069' and \"CusID\" = '$CusID' ");
	$numrows = pg_num_rows($sql_SearchContract);
	if($numrows != 1)
	{
		$status++; 
	}
	else
	{		
		$update_ContactCus = "update public.\"thcap_ContactCus\" set \"CusState\" = '0' where \"CusID\" = '$CusID' and \"contractID\" = 'MG-BK01-5500069' ";
		if($result = pg_query($update_ContactCus)){
		}else{
			$status++;
		}
	}
	
}


// แก้ไขข้อมูลลูกค้า คนที่ 21
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'สุธรรม' and \"A_SIRNAME\" = 'วรรณวิชิต' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",null,'37/15',null, 'หมู่บ้านพุดซ้อน ซอยคู้บอน 27 แยก 6 ', null, \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '74', \"N_CARD\", '3101401095112', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",'0002',\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1938-01-01',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_STATUS\"='0002',\"A_NO\"= '37/15',\"A_SUBNO\" = null ,\"A_SOI\" = 'หมู่บ้านพุดซ้อน ซอยคู้บอน 27 แยก 6 ',\"A_RD\"=null,\"A_PAIR\" = null , \"A_BIRTHDAY\" = '1938-01-01' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '3101401095112' , \"N_AGE\" = '74'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 22
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'บุญเหลือ' and \"A_SIRNAME\" = 'ไชยสิงห์' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",null,'37/15',null, 'หมู่บ้านพุดซ้อน ซอยคู้บอน 27 แยก 6', null, \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '68', \"N_CARD\", '3101401095121', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",'0002',\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1944-01-30',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_STATUS\"='0002',\"A_NO\"= '37/15',\"A_SUBNO\" = null ,\"A_SOI\" = 'หมู่บ้านพุดซ้อน ซอยคู้บอน 27 แยก 6',\"A_RD\"=null,\"A_PAIR\" = null , \"A_BIRTHDAY\" = '1944-01-30' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '3101401095121' , \"N_AGE\" = '68'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 23
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'เรวัต' and \"A_SIRNAME\" = 'วรรณวิชิต' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",null,'37/15',null, 'หมู่บ้านพุดซ้อน ซอยคู้บอน 27 แยก 6', null, \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '48', \"N_CARD\", '3101401095139', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",'0002',\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1963-08-01',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_STATUS\"='0002',\"A_NO\"= '37/15',\"A_SUBNO\" = null ,\"A_SOI\" = 'หมู่บ้านพุดซ้อน ซอยคู้บอน 27 แยก 6',\"A_RD\"=null,\"A_PAIR\" = null , \"A_BIRTHDAY\" = '1963-08-01' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '3101401095139' , \"N_AGE\" = '48'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 24
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'สุจินันท์' and \"A_SIRNAME\" = 'โสดา'  and \"A_BIRTHDAY\" is not null");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",\"A_PAIR\",'1068/420','5', 'หมู่บ้านพฤกษาวิลล์ คลองสอง ซอย 36', 'รังสิต-นครนายก', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '25', \"N_CARD\", '1340600040386', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1987-07-10',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_NO\"= '1068/420',\"A_SUBNO\" = '5' ,\"A_SOI\" = 'หมู่บ้านพฤกษาวิลล์ คลองสอง ซอย 36',\"A_RD\"='รังสิต-นครนายก' , \"A_BIRTHDAY\" = '1987-07-10' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '1340600040386' , \"N_AGE\" = '25'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}


// แก้ไขข้อมูลลูกค้า คนที่ 25
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'สงบ' and \"A_SIRNAME\" = 'ศรีวัน'  and \"A_BIRTHDAY\" is not null");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",\"A_PAIR\",'1068/420','5', 'หมู่บ้านพฤกษาวิลล์ คลองสอง ซอย 36', 'รังสิต-นครนายก', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '42', \"N_CARD\", '3340600044396', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1970-04-06',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_NO\"= '1068/420',\"A_SUBNO\" = '5' ,\"A_SOI\" = 'หมู่บ้านพฤกษาวิลล์ คลองสอง ซอย 36',\"A_RD\"='รังสิต-นครนายก' , \"A_BIRTHDAY\" = '1970-04-06' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '3340600044396' , \"N_AGE\" = '42'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 26
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'รัชนีวรรณ'  and \"A_SIRNAME\" = 'พรรณหาญ' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",\"A_PAIR\",'81/119 คอนโดบางแคซิตี้A ห้องที่119 ชั้น 3','4', 'ซอยเพชรเกษม 47', 'ถนนเพขรเกษม', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '32',  \"N_CARD\", '3140600455339', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",'0004',\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1979-07-29',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	//81/119 คอนโดบางแคซิตี้A ห้องที่119 ชั้น 3 หมู่ 4 ซอยเพชรเกษม 47 ถนนเพขรเกษม
	$update_Fa1 = "update public.\"Fa1\" set \"A_NO\" = '81/119 คอนโดบางแคซิตี้A ห้องที่119 ชั้น 3',\"A_SUBNO\"= '4' ,\"A_SOI\" = 'ซอยเพชรเกษม 47' , \"A_RD\" = 'ถนนเพขรเกษม' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '3140600455339' , \"N_AGE\" = '32'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 27
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'ศุภลักษณ์'  and \"A_SIRNAME\" = 'บุญมี' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",null,'1/105',null, 'รามอินทรา 39', 'รามอินทรา', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '62', \"N_CARD\", '3100503738291', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",'0004',\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1950-03-01',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_PAIR\" = null,\"A_NO\" = '1/105',\"A_SUBNO\" = null,\"A_SOI\" = 'รามอินทรา 39' , \"A_RD\" = 'รามอินทรา',\"A_STATUS\"='0004',\"A_BIRTHDAY\" = '1950-03-01'  where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3100503738291' , \"N_AGE\" = '62'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}
// แก้ไขข้อมูลลูกค้า คนที่ 28
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'ปิยะศักดิ์'  and \"A_SIRNAME\" = 'สาลีกุล' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",\"A_PAIR\",'501/105 หมู่บ้านการเคหะรามอินทรา','7', '3','รามอินทรา', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '37',  \"N_CARD\", '3100503738283', \"N_OT_DATE\",null, \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1974-10-31','2',addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_SEX\"='2',\"A_NO\" = '501/105 หมู่บ้านการเคหะรามอินทรา',\"A_SUBNO\"= '7' ,\"A_SOI\" = '3' , \"A_RD\" = 'รามอินทรา' where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	//501/105 หมู่บ้านการเคหะรามอินทรา หมู่ 7 ซอย 3 ถนนรามอินทรา 
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' ,  \"N_IDCARD\" = '3100503738283' , \"N_AGE\" = '37'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}
// แก้ไขข้อมูลลูกค้า คนที่ 29
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'ละอองทิพย์'  and \"A_SIRNAME\" = 'นุ่มแน่น' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '34',\"N_CARD\", '3660700679706', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1978-02-12','1',addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_SEX\" = '1',\"A_BIRTHDAY\" = '1978-02-12' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3660700679706' , \"N_AGE\" = '34'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 30
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'ปัญจรส'  and \"A_SIRNAME\" = 'หอมหวล' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",'นายอนันต์  หอมหวล','196/16','3', 'หลังวัดทินกร', 'ประชาราษฎร์', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '52', \"N_CARD\", '3100202690192', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1959-11-05',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_PAIR\" = 'นายอนันต์  หอมหวล',\"A_NO\" = '196/16',\"A_SUBNO\" = '3',\"A_SOI\"='หลังวัดทินกร' ,\"A_RD\"='ประชาราษฎร์' ,\"A_BIRTHDAY\"='1959-11-05' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '3100202690192' , \"N_AGE\" = '52'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 31
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'อนันต์'  and \"A_SIRNAME\" = 'หอมหวล' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",'นางปัญจรส  หอมหวล','196/16','3', 'หลังวัดทินกร', 'ประชาราษฎร์', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '53', \"N_CARD\", '3120100375417', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1959-05-13',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_PAIR\" = 'นางปัญจรส  หอมหวล',\"A_NO\" = '196/16',\"A_SUBNO\" = '3',\"A_SOI\"='หลังวัดทินกร' ,\"A_RD\"='ประชาราษฎร์' ,\"A_BIRTHDAY\" = '1959-05-13' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3120100375417' , \"N_AGE\" = '53'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 32
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'ณัฐศักดิ์'  and \"A_SIRNAME\" = 'หอมหวล' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",\"A_PAIR\",'196/16','3', 'หลังวัดทินกร', 'ประชาราษฎร์', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '22', \"N_CARD\", '1101401861862', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1989-11-20',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_NO\" = '196/16',\"A_SUBNO\" = '3',\"A_SOI\"='หลังวัดทินกร' ,\"A_RD\"='ประชาราษฎร์',\"A_BIRTHDAY\" = '1989-11-20' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '1101401861862' , \"N_AGE\" = '22'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}


// แก้ไขข้อมูลลูกค้า คนที่ 33
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'นันทนา'  and \"A_SIRNAME\" = 'ผิวก่ำ' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",\"A_PAIR\",'11/71',null, 'ราชอุทิศ 32', 'ราชอุทิศ', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '28', \"N_CARD\", '1341900005412', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1984-02-20',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_NO\" = '11/71',\"A_SUBNO\" = null,\"A_SOI\"='ราชอุทิศ 32' ,\"A_RD\"='ราชอุทิศ' ,\"A_BIRTHDAY\" = '1984-02-20' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_IDCARD\" = '1341900005412' , \"N_AGE\" = '28'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 34
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'เพทาย'  and \"A_SIRNAME\" = 'ขันทนาลัย' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\", \"A_TUM\", 'คลองสามวา', \"A_PRO\", \"A_POST\",
					'ไทย', '29',\"N_CARD\", '3100800343186', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1983-03-13',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set  \"A_AUM\" = 'คลองสามวา' ,\"A_BIRTHDAY\" = '1983-03-13' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' ,  \"N_IDCARD\" = '3100800343186' , \"N_AGE\" = '29'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 35
$sql_SearchCusID = pg_query("SELECT \"CusID\" FROM \"Fn\" where \"N_IDCARD\" = '3100502994405'");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", 'สังวรณ์',
					\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '54',\"N_CARD\", '3100502994405', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1957-09-08',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_NAME\"= 'สังวรณ์' ,\"A_BIRTHDAY\" = '1957-09-08' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' ,  \"N_IDCARD\" = '3100502994405' , \"N_AGE\" = '54'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}
$i++;

// แก้ไขข้อมูลลูกค้า คนที่ 37
$sql_SearchCusID = pg_query("select * from public.\"Fn\" where \"N_IDCARD\" = '3102100516551'");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",\"A_PAIR\",'295/35 ศศิธรคอนโดมิเนียม ห้อง 25 ชั้น 3',null, 'พหลโยธิน 50 แยก 11 (เสนาวัฒนา)', 'พหลโยธิน', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '48', \"N_CARD\", '3102100516551', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1963-11-23',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_NO\" = '295/35 ศศิธรคอนโดมิเนียม ห้อง 25 ชั้น 3',\"A_SUBNO\" = null,\"A_SOI\"='พหลโยธิน 50 แยก 11 (เสนาวัฒนา)' ,\"A_RD\"='พหลโยธิน' ,\"A_BIRTHDAY\" = '1963-11-23'  where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_AGE\" = '48', \"N_IDCARD\" = '3102100516551'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}


// แก้ไขข้อมูลลูกค้า คนที่ 38
$sql_SearchCusID = pg_query("select * from public.\"Fn\" where \"N_IDCARD\" = '3120101871442'");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\",\"A_PAIR\",'100/103 อาคารสหกรณ์ชุมชนวัดด่านสำโรง ชั้น 2','4', 'วัดด่านสำโรง 20', 'สุขุมวิท', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '33', \"N_CARD\", '3120101871442', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1978-09-14',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_NO\" = '100/103 อาคารสหกรณ์ชุมชนวัดด่านสำโรง ชั้น 2',\"A_SUBNO\" = '4',\"A_SOI\"='วัดด่านสำโรง 20' ,\"A_RD\"='สุขุมวิท' ,\"A_BIRTHDAY\" = '1978-09-14'  where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย' , \"N_AGE\" = '33', \"N_IDCARD\" = '3120101871442'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}
// แก้ไขข้อมูลลูกค้า คนที่ 39
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'อรุณี' and \"A_SIRNAME\" = 'สิทธิ์ตวัน' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '39', \"N_CARD\", '3341600940805', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1972-07-30',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1972-07-30' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3341600940805' , \"N_AGE\" = '39'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
	// ตรวจสอบการผูกคนกับสัญญา
	$sql_SearchContract = pg_query("select * from public.\"thcap_ContactCus\" where \"contractID\" = 'MG-BK01-5500088' and \"CusID\" = '$CusID' ");
	$numrows = pg_num_rows($sql_SearchContract);
	if($numrows != 1)
	{
		$status++; 
	}
	else
	{		
		$update_ContactCus = "update public.\"thcap_ContactCus\" set \"CusState\" = '1' where \"CusID\" = '$CusID' and \"contractID\" = 'MG-BK01-5500088' ";
		if($result = pg_query($update_ContactCus)){
		}else{
			$status++;
		}
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 40
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'ปภาวรินทร์' and \"A_SIRNAME\" = 'ประสงค์ทรัพย์' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '30', \"N_CARD\", '3570700432205', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1982-03-30',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1982-03-30' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3570700432205' , \"N_AGE\" = '30'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
	// ตรวจสอบการผูกคนกับสัญญา
	$sql_SearchContract = pg_query("select * from public.\"thcap_ContactCus\" where \"contractID\" = 'MG-BK01-5500089' and \"CusID\" = '$CusID' ");
	$numrows = pg_num_rows($sql_SearchContract);
	if($numrows != 1)
	{
		$status++; 
	}
	else
	{		
		$update_ContactCus = "update public.\"thcap_ContactCus\" set \"CusState\" = '0' where \"CusID\" = '$CusID' and \"contractID\" = 'MG-BK01-5500089' ";
		if($result = pg_query($update_ContactCus)){
		}else{
			$status++;
		}
	}
}

// แก้ไขข้อมูลลูกค้า คนที่ 41
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'ทัศนีย์' and \"A_SIRNAME\" = 'วิเชียรเขียว' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",'120/76','9', 'ซอย12/2 หมู่บ้านบัวทอง', 'ตลิ่งชัน-สุพรรณ', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '65', \"N_CARD\", '3100502316962', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1947-05-10',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1947-05-10',\"A_NO\"='120/76',\"A_SUBNO\"='9',
	\"A_SOI\"='ซอย12/2 หมู่บ้านบัวทอง',\"A_RD\"='ตลิ่งชัน-สุพรรณ' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3100502316962' , \"N_AGE\" = '65'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 42
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'กัมพล' and \"A_SIRNAME\" = 'อมรพิศาล' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",\"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '38', \"N_CARD\", '3600600118389', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1973-09-17',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1973-09-17' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3600600118389' , \"N_AGE\" = '38'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 43
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'ลัดดา' and \"A_SIRNAME\" = 'นาคศรีสังข์' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",'70/6','5', 'จันทร์ทองเอี่ยม', 'บางกรวย-ไทรน้อย', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '22', \"N_CARD\", '1129900067549', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1989-10-28',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1989-10-28',\"A_NO\"='70/6',\"A_SUBNO\"='5',
	\"A_SOI\"='จันทร์ทองเอี่ยม',\"A_RD\"='บางกรวย-ไทรน้อย' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '1129900067549' , \"N_AGE\" = '22'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 44
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'พงศ์เทพ' and \"A_SIRNAME\" = 'ไม่มีทุกข์' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",'70/6','5', 'จันทร์ทองเอี่ยม', 'บางกรวย-ไทรน้อย', 'บางรักพัฒนา', 'บางบัวทอง', 'นนทบุรี', '11110',
					'ไทย', '24', \"N_CARD\", '1100700796590', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1988-03-30',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1988-03-30',\"A_NO\"='70/6',\"A_SUBNO\"='5',
	\"A_SOI\"='จันทร์ทองเอี่ยม',\"A_RD\"='บางกรวย-ไทรน้อย',\"A_TUM\"='บางรักพัฒนา',\"A_AUM\"='บางบัวทอง',\"A_PRO\"='นนทบุรี',\"A_POST\"='11110' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '1100700796590' , \"N_AGE\" = '24'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 45
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'โยธิน' and \"A_SIRNAME\" = 'ภู่พลับ' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",'79/15','5', 'จันทร์ทองเอี่ยม', 'บางกรวย-ไทรน้อย', 'บางรักพัฒนา', 'บางบัวทอง', 'นนทบุรี', '11110',
					'ไทย', '43', \"N_CARD\", '3101100093731', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1968-10-03',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1968-10-03',\"A_NO\"='79/15',\"A_SUBNO\"='5',
	\"A_SOI\"='จันทร์ทองเอี่ยม',\"A_RD\"='บางกรวย-ไทรน้อย',\"A_TUM\"='บางรักพัฒนา',\"A_AUM\"='บางบัวทอง',\"A_PRO\"='นนทบุรี',\"A_POST\"='11110' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3101100093731' , \"N_AGE\" = '43'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 46
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'นฤมล' and \"A_SIRNAME\" = 'ภู่พลับ' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",'79/15','5', 'จันทร์ทองเอี่ยม', 'บางกรวย-ไทรน้อย', 'บางรักพัฒนา', 'บางบัวทอง', 'นนทบุรี', '11110',
					'ไทย', '38', \"N_CARD\", '3100300097097', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1973-07-23',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1973-07-23',\"A_NO\"='79/15',\"A_SUBNO\"='5',
	\"A_SOI\"='จันทร์ทองเอี่ยม',\"A_RD\"='บางกรวย-ไทรน้อย',\"A_TUM\"='บางรักพัฒนา',\"A_AUM\"='บางบัวทอง',\"A_PRO\"='นนทบุรี',\"A_POST\"='11110' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '3100300097097' , \"N_AGE\" = '38'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 47
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'วิสิษฎ์' and \"A_SIRNAME\" = 'เก้าเอี้ยน' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",'57/21',null, 'นวมินทร์92', 'สุขาภิบาล1', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '27', \"N_CARD\", '1920400009226', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1984-08-20',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1984-08-20',\"A_NO\"='57/21',\"A_SUBNO\"=null,
	\"A_SOI\"='นวมินทร์92',\"A_RD\"='สุขาภิบาล1' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '1920400009226' , \"N_AGE\" = '27'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
}

// แก้ไขข้อมูลลูกค้า คนที่ 48
$sql_SearchCusID = pg_query("select * from public.\"Fa1\" where \"A_NAME\" = 'สุดารัตน์' and \"A_SIRNAME\" = 'เพชรสงค์' ");
$numrows_SearchCusID = pg_num_rows($sql_SearchCusID);
if($numrows_SearchCusID != 1)
{
	$status++; 
}
else
{
	while($SearchCusID = pg_fetch_array($sql_SearchCusID))
	{
		$CusID = trim($SearchCusID["CusID"]);
	}
	
	// หาว่าแก้ไขครั้งล่าสุดเท่าไหร่
	$sql_SearchMaxEdit = pg_query("select max(\"edittime\") as \"edittime\" from public.\"Customer_Temp\" where \"CusID\" = '$CusID' ");
	while($SearchMaxEdit= pg_fetch_array($sql_SearchMaxEdit))
	{
		$edittime = $SearchMaxEdit["edittime"];
	}
	$edittime++; // จำนวนเลขที่แก้ไขครั้งนี้
	
	$insert_Customer_Temp="INSERT INTO \"Customer_Temp\"(
					\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", 
					\"A_SIRNAME\", \"A_PAIR\", \"A_NO\",\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", 
					\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", 
					\"A_NAME_ENG\", \"A_SIRNAME_ENG\", \"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",
					\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
			select  '$CusID','$id_user','$add_date','000','$add_date','1','$edittime',\"A_FIRNAME\", \"A_NAME\",
					\"A_SIRNAME\", \"A_PAIR\",'57/21',null, 'นวมินทร์92', 'สุขาภิบาล1', \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",
					'ไทย', '28', \"N_CARD\", '1800800027109', \"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",
					\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
					'1984-05-02',\"A_SEX\",addr_country,\"N_CARDREF\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
	
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}
	
	$update_Fa1 = "update public.\"Fa1\" set \"A_BIRTHDAY\"='1984-05-02',\"A_NO\"='57/21',\"A_SUBNO\"=null,
	\"A_SOI\"='นวมินทร์92',\"A_RD\"='สุขาภิบาล1' where \"CusID\" = '$CusID' ";
	
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}
	
	$update_Fn = "update public.\"Fn\" set \"N_SAN\" = 'ไทย'  , \"N_IDCARD\" = '1800800027109' , \"N_AGE\" = '28'
					where \"CusID\" = '$CusID' ";
	if($result = pg_query($update_Fn)){
	}else{
		$status++;
	}
	
	// ตรวจสอบการผูกคนกับสัญญา
	$sql_SearchContract = pg_query("select * from public.\"thcap_ContactCus\" where \"contractID\" = 'MG-BK01-5500092' and \"CusID\" = '$CusID' ");
	$numrows = pg_num_rows($sql_SearchContract);
	if($numrows != 1)
	{
		$status++; 
	}
	else
	{		
		$update_ContactCus = "update public.\"thcap_ContactCus\" set \"CusState\" = '1' where \"CusID\" = '$CusID' and \"contractID\" = 'MG-BK01-5500092' ";
		if($result = pg_query($update_ContactCus)){
		}else{
			$status++;
		}
	}
}

//--- เพิ่มคนที่หายไป
$CusID = GenCus(); // รหัสลูกค้าใหม่
$sql_SearchNewCus = pg_query("select * from public.\"Fa1\" where \"A_FIRNAME\" = 'นาง' and \"A_NAME\" = 'ณปภัช' and \"A_SIRNAME\" = 'สงวนมณี' ");
$numrowsnewcus = pg_num_rows($sql_SearchNewCus);
if($numrowsnewcus == 0)
{
	$insert_Customer_Temp = "INSERT INTO public.\"Customer_Temp\"(\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\",\"A_NAME\"
																,\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",\"A_SUBNO\",\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\"
																,\"N_SAN\",\"N_AGE\",\"N_CARD\",\"N_IDCARD\",\"N_OT_DATE\",\"N_BY\",\"N_OCC\",\"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\"
																,\"A_NAME_ENG\",\"A_SIRNAME_ENG\",\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\"
																,\"A_BIRTHDAY\",\"A_SEX\",\"addr_country\",\"N_CARDREF\")
								VALUES('$CusID','$id_user','$add_date','000','$add_date','1','$edittime','นาง','ณปภัช'
										,'สงวนมณี','นายกสิน  สงวนมณี','440',null,'หมู่บ้านสินธร ซอย 19','แฮปปี้แลนด์','คลองจั่น','บางกะปิ','กรุงเทพมหานคร','10240'
										,'ไทย','50','บัตรประชาชน','3110101773297',NULL,NULL,NULL,NULL,'0',NULL
										,NULL,NULL,NULL,'0001',NULL,NULL,'ไทย',NULL,NULL,NULL
										,'1961-09-11','1','TH',NULL)";
	if($result = pg_query($insert_Customer_Temp)){
	}else{
		$status++;
	}

	$update_Fa1 = "INSERT INTO public.\"Fa1\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\",\"A_NO\",
					\"A_SOI\",\"A_RD\",\"A_TUM\",\"A_AUM\",\"A_PRO\",\"A_POST\",\"A_COUNTRY\",\"A_STATUS\",
					\"A_BIRTHDAY\",\"A_SEX\",\"addr_country\") values
					('$CusID','นาง','ณปภัช','สงวนมณี','นายกสิน  สงวนมณี','440',
					'หมู่บ้านสินธร ซอย 19','แฮปปี้แลนด์','คลองจั่น','บางกะปิ','กรุงเทพมหานคร','10240','ไทย','0001',
					'1961-09-11','1','TH')";
	if($result = pg_query($update_Fa1)){
	}else{
		$status++;
	}

	$insert_Fn="INSERT INTO public.\"Fn\" (\"CusID\",\"N_SAN\",\"N_CARD\",\"N_IDCARD\",\"N_AGE\",\"N_STATE\") values ('$CusID','ไทย','บัตรประชาชน','3110101773297','50','0')";
	if($result = pg_query($insert_Fn)){
	}else{
		$status++;
	}

	$update_ContactCus = "update public.\"thcap_ContactCus\" set \"CusState\" = '1',\"CusID\"='$CusID' where \"CusID\" = 'C32596' and \"contractID\" = 'MG-BK01-5500059' ";
	if($result = pg_query($update_ContactCus)){
	}else{
		$status++;
	}
}


if($status==0)
{
	pg_query("COMMIT");
	//pg_query("ROLLBACK"); // test
	echo "<br><center><h2>บันทึกสำเร็จ</h2></center>";
}
else
{
	pg_query("ROLLBACK");
	echo "<br><center><h2>บันทึกผิดพลาด</h2></center>";
	echo "<br>$status";
}
?>