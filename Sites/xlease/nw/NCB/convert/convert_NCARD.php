<?php
set_time_limit(0);
include('../../../config/config.php');

$newtype = 'บัตรประชาชน';
$sametype1 = 'ใบเหลือง';
$sametype2 = 'ประขา';
$sametype4 = 'ประชน';
$sametype5 = 'ประะชา';
$sametype6 = 'ปะชา';
$sametype7 = 'ประชา';


$i = 0;

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

pg_query("BEGIN WORK");
$status = 0;

$sql1 = " SELECT \"CusID\" FROM \"Fn\" where (\"N_CARD\" LIKE '%$sametype1%')  OR  (\"N_CARD\" LIKE '%$sametype2%') OR  (\"N_CARD\" LIKE '%$sametype4%') OR  (\"N_CARD\" LIKE '%$sametype5%')  OR  (\"N_CARD\" LIKE '%$sametype6%') OR  (\"N_CARD\" LIKE '%$sametype7%')";
$query1 = pg_query($sql1); 
while($re1 = pg_fetch_array($query1)){
	$cusid = $re1['CusID'];

	$sql = "UPDATE \"Fn\" SET  \"N_CARD\"='$newtype' WHERE  \"CusID\" = '$cusid' ";
	$query = pg_query($sql);

	
		if($query){}
		else{ 
			$status++;
		}
		
		
			$qryedittime=pg_query("select max(\"edittime\") from \"Customer_Temp\" where \"CusID\"='$cusid'");
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
					\"A_NICKNAME\",\"A_STATUS\",\"A_REVENUE\",\"A_EDUCATION\",\"A_COUNTRY\" , \"A_MOBILE\",\"A_TELEPHONE\",\"A_EMAIL\",\"A_BIRTHDAY\",\"A_SEX\",\"addr_country\" from \"Fa1\" a
					LEFT JOIN \"Fn\" b on a.\"CusID\"=b.\"CusID\"where a.\"CusID\"='$cusid'";
				
				if($res_temp=pg_query($insert_temp)){
				}else{
					$status++;				
				}
	
}	
	
	
	
	
		if($status == 0){pg_query("COMMIT"); echo "sucesss";}else{pg_query("ROLLBACK");}
?>
