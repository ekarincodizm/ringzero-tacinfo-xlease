<?php
session_start();
include("../config/config.php");

$fs_idno=pg_escape_string($_POST["fidno"]);

$fs_beginx=pg_escape_string($_POST["f_pbeginx"]);

 
 
 $update_Fp="Update \"Fp\" SET \"P_BEGINX\"='$fs_beginx' where \"IDNO\"='$fs_idno' ";
//echo $update_Fc;
if($result=pg_query($update_Fp))
{
 $str="Update success";
}
else
{
 $str="Error At ".$result;
}
//echo "<br>".$str;





echo "<br>";
echo "    บันทึกข้อมูลเรียบร้อยแ้ล้ว ";
echo "<br>";
 
$c_cpay="select \"CreateAccPayment\"('$fs_idno')";

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
 

echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_accedit.php?idnog=$fs_idno\">"; 



?>
