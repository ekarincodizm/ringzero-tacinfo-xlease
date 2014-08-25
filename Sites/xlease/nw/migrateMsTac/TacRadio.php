<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");
include("include/function.php");
//pg_query("BEGIN WORK");
$status = 0;
$sql_fc=mssql_query("select RadioID,CusID,RadioBand,RadioModel,RadioONID,RadioPT from TacRadio ",$conn); 
while($res_fc = mssql_fetch_array($sql_fc)){
	$RadioID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RadioID"]));//radio_no
	$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CusID"]));
	$RadioBand=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RadioBand"]));
	$RadioModel=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RadioModel"]));
	$RadioONID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RadioONID"]));
	$RadioPT=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RadioPT"]));	
	//$EffectDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["EffectDate"]));
	//$RentPrice=trim($res_fc["RentPrice"]);
	//$TempOff=trim($res_fc["TempOff"]);
	//$TempOffDate=trim($res_fc["TempOffDate"]);
	//$RadioOff=trim($res_fc["RadioOff"]);
	//$RadioOffDate=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RadioOffDate"]));
	
	/*$query = pg_query("select * from taxiacc.\"TacRadio\" where \"RadioID\" = '$RadioID' and \"CusID\" = '$CusID'");
	$num_row = pg_num_rows($query);
	if($num_row == 0){*/
		/*$ins="insert into \"Radios\" (\"radio_id\",\"radio_no\",\"band\", \"model\", \"serial_no\", \"pt_no\") values 
		('$RadioID','','$RadioBand','$RadioModel','$RadioONID','$RadioPT')";*/
		
		
		$Rad_ID = GetRadioID(); // สร้าง ID ใหม่
		$ins="insert into \"Radios\" (\"radio_id\",\"radio_no\",\"band\", \"model\", \"serial_no\", \"pt_no\") values 
		('$Rad_ID','$RadioID','$RadioBand','$RadioModel','$RadioONID','$RadioPT')";
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins;
		}
	}
//}
if($status == 0){
   // pg_query("COMMIT");
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
   // pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

