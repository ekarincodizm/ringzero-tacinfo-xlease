<?php
session_start();
//global $reslast,$rescc,$rescount;

 $c_firname=$_POST[cus_firname];
 $c_name=$_POST[cus_name];
 $c_surname=$_POST[cus_surname];
 $c_pairname=$_POST[pair_name];
 
 $c_fullname=$c_name." ".$c_surname;

include("../config/config.php");
include("../GenCusID.php"); // ใช้หา CusID ใหม่ มี 2 function คือ GenCT() คือลูกค้าที่ยังไม่ได้อนุมัติ และ GenCus() คือลูกค้าที่อนุมัติแล้ว

//------ ตรวจสอบหา CusID ที่มากที่สุดแล้วหา CusID ตัวถัดไปจาก function
		$cus_sn = GenCus();
//----------------------

 $in_sql="insert into \"Fa1\" (\"CusID\",\"A_FIRNAME\",\"A_NAME\",\"A_SIRNAME\",\"A_PAIR\") 
          values  
          ('$cus_sn','$c_firname','$c_name','$c_surname','$c_pairname')";
 if($result=pg_query($in_sql))
 {
  $status ="OK".$in_sql;
 }
 else
 {
  $status ="error insert Re".$in_sql;
 }


 $in_fn="insert into \"Fn\" (\"CusID\" , \"N_STATE\") 
          values  
          ('$cus_sn','0')";
 if($result=pg_query($in_fn))
 {
  $st_fn="OK".$in_fn;
 }
 else
 {
  $st_fn="error insert Re".$in_sql;
 }




//echo $status;
echo "<meta http-equiv=\"refresh\" content=\"0;URL=av_step2.php?cus_id=$cus_sn&cus_name=$c_fullname\">"."<br>";   


?>
