<?php
set_time_limit(0);
session_start();
include("../../config/config.php");

$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status=0;

$queryfn=pg_query("select \"CusID\",\"N_IDCARD\",\"N_CARD\" from \"Fn\" where \"N_CARD\" not like '%บัตรประชาชน%'");
while($resname=pg_fetch_array($queryfn)){
	list($CusID,$N_IDCARD,$N_CARD)=$resname;	
	//echo "$CusID, $N_CARD";
	
	//ย้าย N_IDCARD มาใส่ N_IDCARDREF
	$up="UPDATE \"Fn\" SET \"N_IDCARD\"=null,\"N_CARDREF\"='$N_IDCARD' WHERE \"CusID\"='$CusID'";
	if($result2=pg_query($up)){
	}else{
		$status++;
	}
							
	//insert temp ว่ามีการแก้ไขข้อมูล
	$qryedittime=pg_query("select max(\"edittime\") from \"Customer_Temp\" where \"CusID\"='$CusID'");
	list($maxedittime)=pg_fetch_array($qryedittime);
	if($maxedittime==""){
		$maxedittime=0;
	}else{
		$maxedittime++;
	}
				
	$insert_temp="INSERT INTO \"Customer_Temp\"(
		\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
		\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
		\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
		\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",addr_country)
	select  a.\"CusID\",'000','$add_date','000','$add_date','1','$maxedittime',\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\",\"A_NO\",
	\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
	\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",\"A_NAME_ENG\",\"A_SIRNAME_ENG\",
	\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",\"addr_country\" from \"Fa1\" a
	LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
			
	if($res_temp=pg_query($insert_temp)){
	}else{
		$status++;
	}
	
}

if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>แก้ไขข้อมูลเรียบร้อยแล้ว</b></font></div>";
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถแก้ไขข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}


?>
