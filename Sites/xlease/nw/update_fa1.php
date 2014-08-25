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


$qryname = mysql_query("select * from $db1.vcustomerdetail");
$numname=mysql_num_rows($qryname);

$i=0;
while($resname=mysql_fetch_array($qryname)){
	$A_FIRNAME=trim($resname["A_FIRNAME"]); if($A_FIRNAME==""){ $A_FIRNAME="null"; }else{ $A_FIRNAME="'".$A_FIRNAME."'"; }
	$A_NAME=trim($resname["A_NAME"]); if($A_NAME==""){ $A_NAME="null"; }else{ $A_NAME="'".$A_NAME."'"; }
	$A_SIRNAME=trim($resname["A_SIRNAME"]); if($A_SIRNAME==""){ $A_SIRNAME="null"; }else{ $A_SIRNAME="'".$A_SIRNAME."'"; }
	$A_PAIR=trim($resname["A_PAIR"]); if($A_PAIR==""){ $A_PAIR="null"; }else{ $A_PAIR="'".$A_PAIR."'"; }
	$A_NO=trim($resname["A_NO"]); if($A_NO==""){ $A_NO="null"; }else{ $A_NO="'".$A_NO."'"; }
	$A_SUBNO=trim($resname["A_SUBNO"]); if($A_SUBNO==""){ $A_SUBNO="null"; }else{ $A_SUBNO="'".$A_SUBNO."'"; }
	$A_SOI=trim($resname["A_SOI"]); if($A_SOI==""){ $A_SOI="null"; }else{ $A_SOI="'".$A_SOI."'"; }
	$A_RD=trim($resname["A_RD"]); if($A_RD==""){ $A_RD="null"; }else{ $A_RD="'".$A_RD."'"; }
	$A_TUM=trim($resname["A_TUM"]); if($A_TUM==""){ $A_TUM="null"; }else{ $A_TUM="'".$A_TUM."'"; }
	$A_AUM=trim($resname["A_AUM"]); if($A_AUM==""){ $A_AUM="null"; }else{ $A_AUM="'".$A_AUM."'"; }
	$A_PRO=trim($resname["A_PRO"]); if($A_PRO==""){ $A_PRO="null"; }else{ $A_PRO="'".$A_PRO."'"; }
	$A_POST=trim($resname["A_POST"]); if($A_POST==""){ $A_POST="null"; }else{ $A_POST="'".$A_POST."'"; }
	$N_AGE=$resname["N_AGE"]; if($N_AGE==""){ $N_AGE="null"; }else{ $N_AGE="'".$N_AGE."'"; }
	$N_IDCARD=$resname["N_IDCARD"]; 
	
	//นำเลขบัตรประชาชนที่ได้มาตรวจสอบว่ามีข้อมูลนี้ในระบบหรือยัง
	$N_IDCARD=strtr($N_IDCARD, "-", " "); //แปลงค่าที่คีย์ - ให้เป็นช่องว่าง
	$N_IDCARD=ereg_replace('[[:space:]]+', '', trim($N_IDCARD)); //ตัดช่องว่างออก
	
	$qry_check=pg_query("select \"CusID\" from \"Fn\" WHERE replace(replace(\"N_IDCARD\",' ',''),'-','') = '$N_IDCARD'");
	while($resfn=pg_fetch_array($qry_check)){
		list($CusFn)=$resfn;
		$CusFn=trim($CusFn);
		
		//update ข้อมูล
		$upfa="UPDATE \"Fa1\" SET \"A_FIRNAME\"=$A_FIRNAME,\"A_NAME\"=$A_NAME,\"A_SIRNAME\"=$A_SIRNAME,\"A_NO\"=$A_NO,\"A_SUBNO\"=$A_SUBNO,
		\"A_SOI\"=$A_SOI,\"A_RD\"=$A_RD,\"A_TUM\"=$A_TUM,\"A_AUM\"=$A_AUM,\"A_PRO\"=$A_PRO,\"A_POST\"=$A_POST
		where \"CusID\"='$CusFn'";
		
		if($resup=pg_query($upfa)){
		}else{
			$status++;
		}
		
		//update อายุ
		$upfn="UPDATE \"Fn\" SET \"N_AGE\"=$N_AGE where \"CusID\"='$CusFn'";
		//echo $upfn;
		if($resupfn=pg_query($upfn)){
		}else{
			$status++;
		}
		
		//insert temp ว่ามีการแก้ไขข้อมูล
		$qryedittime=pg_query("select max(\"edittime\") from \"Customer_Temp\" where \"CusID\"='$CusFn'");
		list($maxedittime)=pg_fetch_array($qryedittime);
		if($maxedittime==""){
			$maxedittime=0;
		}else{
			$maxedittime++;
		}
		
		$qryfa1_old=pg_query("select  \"A_PAIR\",\"N_SAN\", \"N_CARD\", \"N_IDCARD\", 
		\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\",\"N_STATE\",\"A_FIRNAME_ENG\",\"A_NAME_ENG\",\"A_SIRNAME_ENG\",
		\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\",\"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",
		\"A_BIRTHDAY\",\"A_SEX\",\"addr_country\",\"N_CARDREF\" from \"Fa1\" a
		LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$CusFn'");
		list($A_PAIR2,$N_SAN2,$N_CARD2,$N_IDCARD2,$N_OT_DATE2,$N_BY2,$N_OCC2,$N_ContactAdd2,$N_STATE2,$A_FIRNAME_ENG2,
		$A_NAME_ENG2,$A_SIRNAME_ENG2,$A_NICKNAME2,$A_STATUS2,$A_REVENUE2,$A_EDUCATION2,$A_COUNTRY2,$A_MOBILE2,$A_TELEPHONE2,
		$A_EMAIL2,$A_BIRTHDAY2,$A_SEX2,$addr_country2,$N_CARDREF2)=pg_fetch_array($qryfa1_old);
		
		$A_PAIR2=checknull($A_PAIR2);
		$N_SAN2=checknull($N_SAN2);
		$N_CARD2=checknull($N_CARD2);
		$N_IDCARD2=checknull($N_IDCARD2);
		$N_OT_DATE2=checknull($N_OT_DATE2);
		$N_BY2=checknull($N_BY2);
		$N_OCC2=checknull($N_OCC2);
		$N_ContactAdd2=checknull($N_ContactAdd2);
		$N_STATE2=checknull($N_STATE2);
		$A_FIRNAME_ENG2=checknull($A_FIRNAME_ENG2);
		$A_NAME_ENG2=checknull($A_NAME_ENG2);
		$A_SIRNAME_ENG2=checknull($A_SIRNAME_ENG2);
		$A_NICKNAME2=checknull($A_NICKNAME2);
		$A_STATUS2=checknull($A_STATUS2);
		$A_REVENUE2=checknull($A_REVENUE2);
		$A_EDUCATION2=checknull($A_EDUCATION2);
		$A_COUNTRY2=checknull($A_COUNTRY2);
		$A_MOBILE2=checknull($A_MOBILE2);
		$A_TELEPHONE2=checknull($A_TELEPHONE2);
		$A_EMAIL2=checknull($A_EMAIL2);
		$A_BIRTHDAY2=checknull($A_BIRTHDAY2);
		$A_SEX2=checknull($A_SEX2);
		$addr_country2=checknull($addr_country2);
		$N_CARDREF2=checknull($N_CARDREF2);
		
		$insert_temp="INSERT INTO \"Customer_Temp\"(
		\"CusID\",\"add_user\",\"add_date\",\"app_user\",\"app_date\",\"statusapp\",\"edittime\",\"A_FIRNAME\", \"A_NAME\", \"A_SIRNAME\", \"A_PAIR\", \"A_NO\",
		\"A_SUBNO\", \"A_SOI\", \"A_RD\", \"A_TUM\", \"A_AUM\", \"A_PRO\", \"A_POST\", \"N_SAN\", \"N_AGE\", \"N_CARD\", \"N_IDCARD\", 
		\"N_OT_DATE\",\"N_BY\", \"N_OCC\", \"N_ContactAdd\", \"N_STATE\", \"A_FIRNAME_ENG\", \"A_NAME_ENG\", \"A_SIRNAME_ENG\", 
		\"A_NICKNAME\", \"A_STATUS\", \"A_REVENUE\", \"A_EDUCATION\", \"A_COUNTRY\", \"A_MOBILE\", \"A_TELEPHONE\", \"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",addr_country,\"N_CARDREF\")
		VALUES ('$CusFn','000','$add_date','000','$add_date','1','$maxedittime',$A_FIRNAME, $A_NAME, $A_SIRNAME, $A_PAIR, $A_NO,
		$A_SUBNO, $A_SOI, $A_RD, $A_TUM, $A_AUM, $A_PRO, $A_POST,$N_SAN2, $N_AGE, $N_CARD2, $N_IDCARD2, 
		$N_OT_DATE2,$N_BY2, $N_OCC2, $N_ContactAdd2,$N_STATE2,$A_FIRNAME_ENG2,$A_NAME_ENG2,$A_SIRNAME_ENG2,
		$A_NICKNAME2,$A_STATUS2,$A_REVENUE2,$A_EDUCATION2,$A_COUNTRY2,$A_MOBILE2,$A_TELEPHONE2,$A_EMAIL2,$A_BIRTHDAY2,$A_SEX2,$addr_country2,$N_CARDREF2)";
			
		if($res_temp=pg_query($insert_temp)){
		}else{
			$status++;
		}
		
	}
}

if($status == 0){
	pg_query("COMMIT");
	echo "<div style=\"padding: 50px;text-align:center;\"><font size=4><b>Update เรียบร้อยแล้ว</b></font></div>";
}else{
	pg_query("ROLLBACK");
	echo "ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง";
}


?>
