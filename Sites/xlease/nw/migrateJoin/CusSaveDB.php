<?php
session_start();
		 	
include("config/config.php");
 $cus_id=$_POST[cus_id];
 $id =$_POST[id_main];
 $fname=$_POST[fname];
 $lname=$_POST[lname];
 $er = 0;
$query = "INSERT INTO  public.ta_join_main_cus (\"id\",\"CusID\",\"fname\",\"lname\") VALUES  ('$id','$cus_id','$fname','$lname') ";
				//$sql_query = pg_query($query);
if($sql_query=pg_query($query))
			{}
			else $er++;
$query2 =	"UPDATE public.ta_join_main  SET CusID  ='$cus_id' where \"id\"  ='$id'";
	
	if($sql_query2=pg_query($query2))
			{}
			else $er++;
			
			$query2 =	"UPDATE public.ta_join_main_bin  SET CusID  ='$cus_id' where \"id\"  ='$id'";
	
	if($sql_query2=pg_query($query2))
			{}
			else $er++;
			
			if($er==0)echo "บันทึกข้อมูล $fname $lname รหัส $cus_id เรียบร้อยแล้ว";



?>