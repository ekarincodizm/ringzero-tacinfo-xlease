<?php
session_start();
    include("../config/config.php");
	 $id_no=pg_escape_string($_GET["idno_acc"]);
 
    	

	$c_apay="select \"CreateAccPayment\"('$id_no')";

    //$resid=pg_query($db_connect,$c_apay);


     if($result=pg_query($c_apay))
     {
       $statuss ="สร้าง Account Payment เรียบร้อยแล้ว";
     }
      else
    {
       $statuss ="เกิดข้อผิดพลาด";
     }	

    //echo $res=pg_fetch_result($resid,0);
	echo $statuss; 
?>