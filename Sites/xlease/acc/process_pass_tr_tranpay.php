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
  $idno_id=pg_escape_string($_POST["idno_ss"]);
  $datenow=date("Y-m-d");
  $teststr="select pass_tranpay('$post_id','$idno_id','$id_user') ";
  $qry_passtr=pg_query("select pass_tranpay('$post_id','$idno_id','$id_user')");
  $res_pass=pg_fetch_result($qry_passtr,0);
  
  if($res_pass=='t')
   {
   
     $in_sql="update \"PostLog\" SET   \"UserIDAccept\"='$id_user',\"AcceptPost\"=TRUE
					   WHERE \"PostID\"='$post_id'  
		   ";
		  
		  
  
		 if($result=pg_query($in_sql))
		 {
		  $status ="Update ข้อมูลแล้ว";
		 }
		 else
		 {
		  $status ="error Update  fuser ".$in_sql;
		 }
    
	// $bt_print="<input type=\"button\" value=\"PRINT\" onclick=\"window.open('frm_recprint_acc_tr.php?pid=$post_id')\"  />";
	 
echo "<meta http-equiv=\"refresh\" content=\"0;URL=frm_recprint_acc_tr_$c_code.php?pid=$post_id\" TARGET=\"_BLANK\">";  	 
	 
   }
   else
   {
     $bt_print="เกิดข้อผิดพลาด";
   }
  
  
  ?>
  