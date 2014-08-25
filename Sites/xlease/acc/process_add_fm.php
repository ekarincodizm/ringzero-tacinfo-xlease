<?php
session_start();
include("../config/config.php");
$ifm_name=pg_escape_string($_POST["fm_name"]);
$ifm_type=pg_escape_string($_POST["fm_type"]);
$ifm_id=pg_escape_string($_POST["fm_id"]); 

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

pg_query("BEGIN");

//------ insert to FormularAcc ----------------//
for($i=0;$i<count($_POST["type_acb"]);$i++)
 	{
  	  
	  $ifm_type_dcr=pg_escape_string($_POST["type_acb"][$i]);
	  $ifm_dcr=$_POST["fm_dcr"][$i];
	  
	  if($ifm_dcr=="DR")
	  {
	   $i_dcr=1;
	  }
	  else
	  {
	   $i_dcr=0;
	  }
	  
	  $sql_inacc="insert into account.\"FormulaAcc\"(fm_id,accno,drcr) values ('$ifm_id','$ifm_type_dcr','$i_dcr')";
	  if($result_inacc=pg_query($sql_inacc))
				 {
				  $st_otracc ="OK".$sql_inacc;
				 }
				 else
				 {
				  $st_otracc ="error insert sql".$sql_inacc;
				 }			   
				 echo $st_otracc."******"."<br>";
	  
 
	}
//---------END insert to FormularAcc ----------//	


//----- insert to FormularID -------- //
$sql_in="insert into account.\"FormulaID\"(fm_id,fm_name,type_acb) values('$ifm_id','$ifm_name','$ifm_type')";
if($result_in=pg_query($sql_in))
				 {
				  $st_otr ="OK".$result_in;
				 }
				 else
				 {
				  $st_otr ="error insert sql".$result_in;
				 }			   
				 echo $st_otr;


//-----END insert to FormularID ----- //

if(($result_inacc) and ($result_in)) 
	{
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) เพิ่มสูตรทางบัญชี', '$add_date')");
		//ACTIONLOG---
 	  pg_query("COMMIT");
  	  echo "บันทึกข้อมูลเรียบร้อย รอสักครู่ .";
  	  echo "<meta http-equiv=\"refresh\" content=\"2;URL=frm_list_fm.php\" >";
	}
	else
	{
 	  pg_query("ROLLBACK");
 	  echo "มีข้อผิดพลาดในการบันทึก จะนำท่านทำรายการใหม่";
 	  echo "<meta http-equiv=\"refresh\" content=\"5;URL=frm_list_fm.php\" >";
	} 
 	
?>