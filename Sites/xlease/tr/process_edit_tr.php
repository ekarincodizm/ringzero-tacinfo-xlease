<?php
session_start();
include("../config/config.php");
$e_ref1=trim($_POST["ref1"]);
$e_ref2=trim($_POST["ref2"]);
$sid=$_POST["h_sid"];
$datelog = nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$id_user=$_SESSION["av_iduser"];
$trdate=$_POST["h_date"];

$hh_id=trim($_POST["h_id"]);



$str=pg_query("select * from \"VContact\" where \"IDNO\"='$hh_id'");
				  				  

			  
				  

$num_r=pg_num_rows($str);
 if($num_r == 0)
 {
   echo $num_r."<br>".$str;
   echo "ไม่พบ เลขที่สัญญา กรุณากลับไปทำรายการอีกครั้ง"."<br>";
   echo "<meta http-equiv=\"refresh\" content=\"10;URL=list_edit_ref.php\" >";
 }
 else
 {
  $str_res=pg_query("select * from \"VContact\" where \"IDNO\"='$hh_id' ");
  $res_ref=pg_fetch_array($str_res);
				  $res_idno=$res_ref["IDNO"];
				//  $fullname=$res_ref["full_name"];
  $qry_name=pg_query("select A.\"IDNO\",A.\"CusID\",B.*  from \"Fp\" A  
                      LEFT OUTER JOIN \"Fa1\" B on B.\"CusID\" = A.\"CusID\"
                      WHERE A.\"IDNO\"='$hh_id'");
  $res_name=pg_fetch_array($qry_name);
  $str_name=trim($res_name["A_FIRNAME"])." ".trim($res_name["A_NAME"])." ".($res_name["A_SIRNAME"]);				
				  
				  $r_ref1=$res_ref["TranIDRef1"];
  				  $r_ref2=$res_ref["TranIDRef2"]; 	
 
 
  //$fullname_icon=iconv('windows-874','windows-874',$fullname);
 
   echo "อัพเดตข้อมูล".$e_ref2;
  $qry_update="UPDATE \"TranPay\" SET ref1='$r_ref1' , ref2='$r_ref2' , post_to_idno ='$res_idno',ref_name='$str_name'
               WHERE  id_tranpay=$sid ";
  if($res_upd=pg_query($qry_update))
  {
	//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$id_user', '(TAL) ทำรายการแก้ไข Ref.Bill Payment', '$datelog')");
	//ACTIONLOG---
   echo "Update Tranpay Success".$qry_update;
   echo "<meta http-equiv=\"refresh\" content=\"3;URL=list_edit_ref.php\" >";
   
  }
  else
  {
   echo "Error Update at".$qry_update;
    echo "<meta http-equiv=\"refresh\" content=\"10;URL=list_edit_ref.php\" >";
  }
  
   
   
 }
 

?>
