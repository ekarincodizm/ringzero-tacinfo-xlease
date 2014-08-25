<?php
session_start();
		 	
include("config/config.php");
 $car_id=$_POST[car_id];
 $id =$_POST[id_main];
 $car_license=$_POST[car_license];
 $contract_id=$_POST[contract_id];
 $id_body=$_POST[id_body];
 $er = 0;
$query = "INSERT INTO  public.ta_join_main_car (\"id\",\"CarID\",\"car_license\",\"contract_id\",\"id_body\") VALUES  ('$id','$car_id','$car_license','$contract_id','$id_body') ";
				//$sql_query = pg_query($query);
if($sql_query=pg_query($query))
			{}
			else $er++;
$query2 =	"UPDATE public.ta_join_main  SET CarID  ='$car_id' where \"id\"  ='$id'";
	
	if($sql_query2=pg_query($query2))
			{}
			else $er++;
			
			$query2 =	"UPDATE public.ta_join_main_bin  SET CarID  ='$car_id' where \"id\"  ='$id'";
	
	if($sql_query2=pg_query($query2))
			{}
			else $er++;
			
			if($er==0)echo "บันทึกข้อมูล $car_license $id_body รหัสรถยนต์ $car_id เรียบร้อยแล้ว";



?>