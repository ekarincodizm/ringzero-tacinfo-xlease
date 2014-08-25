<?php
set_time_limit(0);
session_start();
include("./../config/config.php");
include("./function/checknull.php");
$db1="ta_mortgage_datastore";

$add_user=$_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status=0;


$qryname = pg_query("select * from \"Fa1\" where \"A_COUNTRY\" is not null");
$numname=pg_num_rows($qryname);

$i=0;
while($resname=pg_fetch_array($qryname)){
	$CusID=trim($resname["CusID"]);
	$A_COUNTRY=trim($resname["A_COUNTRY"]);
	
	if($A_COUNTRY=='ไทย'){
		$addr_country="TH";
	}else if($A_COUNTRY=='จีน'){
		$addr_country="CN";
	}
	
	//update ข้อมูล
	$upfa="UPDATE \"Fa1\" SET \"addr_country\"='$addr_country' where \"CusID\"='$CusID'";
	//echo $upfa;
	if($resup=pg_query($upfa)){
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
	/*////////// กรณีที่เคย update Fa1 ไปแล้วไม่ต้อง Insert ใน Customer_Temp อีก/////////
	$insert_temp="INSERT INTO \"Customer_Temp\"(
		\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
		\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
		\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
		\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",addr_country)
	select  a.\"CusID\",'000','$add_date','000','$add_date','1','$maxedittime',\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\",\"A_NO\",
		\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\",\"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
		\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",\"A_NAME_ENG\",\"A_SIRNAME_ENG\",
		\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",null,\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",'$addr_country' from \"Fa1\" a
		LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusID'";
		
	if($res_temp=pg_query($insert_temp)){
	}else{
		$status++;
	}	
	////////////*/
}

$qryname2 = pg_query("select * from \"Customer_Temp\" where \"A_COUNTRY\" is not null order by \"CustempID\"");
$numname2=pg_num_rows($qryname2);
while($resname2=pg_fetch_array($qryname2)){
	$CustempID=trim($resname2["CustempID"]);
	$A_COUNTRY2=trim($resname2["A_COUNTRY"]);
	
	if($A_COUNTRY2=='ไทย'){
		$addr_country2="TH";
	}else if($A_COUNTRY2=='จีน'){
		$addr_country2="CN";
	}
	
	//update ข้อมูล
	$upfa2="UPDATE \"Customer_Temp\" SET \"addr_country\"='$addr_country2' where \"CustempID\"='$CustempID'";

	if($resup2=pg_query($upfa2)){
	}else{
		$status++;
	}
	
}

if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>Update Success</b></font></div>";
}else{
	pg_query("ROLLBACK");
	echo "Can't Update, Please try again.";
}


?>
