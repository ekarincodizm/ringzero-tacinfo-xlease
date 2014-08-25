<?php
session_start();
    include("../config/config.php");
	$id_no=pg_escape_string($_GET["idno_ccc"]);
 
  $c_cpay="select \"CreateCusPayment\"('$id_no')";

    //$resid=pg_query($db_connect,$c_apay);


     if($result=pg_query($c_cpay))
     {
       $statusc ="สร้าง Customer Payment เรียบร้อยแล้ว";
     }
      else
    {
       $statusc ="เกิดข้อผิดพลาด";
     }	

    //echo $res=pg_fetch_result($resid,0);
	echo $statusc;
	
?>