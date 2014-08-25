<?php
session_start();
include("../config/config.php");
header('Cache-Control: no-cache');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-cache');
header('Pragma: no-cache');

$id_user=$_SESSION["av_iduser"];

$c_code=$_SESSION["session_company_code"];
//$c_code="THA";

  $post_id=pg_escape_string($_POST["postid"]);
  $idno_id=pg_escape_string($_POST["idno"]);
  
  $datenow=date("Y-m-d");
  $dateqry=pg_escape_string($_POST["date_q"]);
  
  
  
  
  
  $str_sql="select accept_acc_cash( '".$post_id."','".$dateqry."','".$id_user."')";
 
   /*
     echo "string befor call function =".$str_sql;
	 echo "<br>";
	 echo "post id =".$post_id."<br>";
	 echo "date  =".$dateqry."<br>";
	 echo "id user  =".$id_user."<br>";
  */
  
  $qry_passtr=pg_query("select accept_acc_cash('$post_id','$dateqry','$id_user')");
  $res_pass=pg_fetch_result($qry_passtr,0);
  
  if($res_pass=='t')
   {
    
  


   
   //  $bt_print="<input type=\"button\" value=\"PRINT\" onclick=\"window.open('frm_recprint_acc_ca.php?pid=$post_id');window.location.href='page_acc_receipt.php'\"  />";
  
  echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_recprint_acc_ca_$c_code.php?pid=$post_id\" TARGET=\"_BLANK\">";  	
  
   }
   else
   {
     echo $bt_print="เกิดข้อผิดพลาด".$str_sql;
	 /*
	 echo "<br>";
	 echo "post id =".$post_id."<br>";
	 echo "date  =".$dateqry."<br>";
	  echo "id user  =".$id_user."<br>";
	 echo  $str_sql;
	 */
   }
  
  
  ?>