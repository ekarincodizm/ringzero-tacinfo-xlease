<?php
set_time_limit (0); 
ini_set("memory_limit","128M"); 
include("config/config.php");
include("include/function.php");
error_reporting(0); 
//pg_query("BEGIN WORK");
$status = 0;
$n=0;
$k=0;
$sql_fc=mssql_query("select RadioID,CusID,RadioBand,RadioModel,RadioONID,RadioPT from TacRadio where RadioBand not like 'I%' ",$conn); 
echo "<table border=1><tr><td>ลำดับ</td><td>product_id</td><td>serial_no</td><td>Band</td></tr>";
//รอบแรก
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

//หา product_id
				$query2 = pg_query("select product_id from \"Products\" where \"name\" = '$RadioBand' "); //ดึงค่า Radio IDใน PG

				if($res_r2 = pg_fetch_array($query2)){
				$product_id=trim($res_r2["product_id"]);
				//echo "Rad_ID = ".$Rad_ID ;
				}
				if($product_id==''){
					$cmp = strstr($RadioBand,"MOTOROLA");
					$cmp2 = strstr($RadioBand,"Motorola");
						if($cmp2!='' || $cmp!=''){
							$product_id = 'P008';	
						}
							
					//if($cmp!=''){
					//$n++;
					//$product_id = $n;
					//}else{
					
					//}
				}
		
		//$Rad_ID = GetRadioID(); // สร้าง ID ใหม่
		$ins="insert into \"StockProduct\" (\"product_id\",\"ref1\",\"contract_id\", \"band\", \"model\", \"serial_no\",\"ref2\",\"po_id\" ,\"po_auto_id\",\"inv_auto_id\") values 
		('$product_id','$RadioID','$CusID','$RadioBand','$RadioModel','$RadioONID','$RadioPT','old_data','0','0')";
		if($res_inss=pg_query($ins)){	
		}else{
			$k++;
			$status=$status+1;
			echo "<tr><td>$k</td><td>$product_id &nbsp;</td><td>$RadioONID</td><td>$RadioBand</td></tr>";
			//echo $k."  product_id = ".$product_id." serial_no = $RadioONID <br>";
		}
		$product_id ='';
	}
	//รอบ2
	$sql_fc=mssql_query("select CusID,RadioBand from TacRadio where RadioBand not like 'I%' ",$conn); 
while($res_fc = mssql_fetch_array($sql_fc)){
	$RadioBand=trim(iconv('WINDOWS-874','UTF-8',$res_fc["RadioBand"]));//radio_no
	$CusID=trim(iconv('WINDOWS-874','UTF-8',$res_fc["CusID"]));
	
		$ins="update \"StockProduct\" set inv_no = '$RadioBand'
				where contract_id = '$CusID' ";
		if($res_inss=pg_query($ins)){	
		}else{
			$status=$status+1;
			echo $ins."<br>";
		}
	
}
echo "</table>";
//}
if($status == 0){
   // pg_query("COMMIT");
    echo "<br>บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
   // pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}
?>

