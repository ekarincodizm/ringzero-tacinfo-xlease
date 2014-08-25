<?php
session_start();
include("../config/config.php");
$ifm=pg_escape_string($_POST["s_fmid"]);

$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server
$user_id = $_SESSION["av_iduser"];

pg_query("BEGIN");

for($i=0;$i<count($_POST["s_acid"]);$i++) 
{
 $ii_dcr=pg_escape_string($_POST["f_dcr"][$i]);
 $ii_id=pg_escape_string($_POST["s_autoID"][$i]);  
 $ii_ac=pg_escape_string($_POST["s_acid"][$i]);
 
 //echo $ifm." ".$ii_id." ".$ii_dcr."<br>";
 
 $sql="update account.\"FormulaAcc\" set drcr='$ii_dcr',accno='$ii_ac' where fm_id='$ifm' AND auto_id='$ii_id'";
 $db_query=pg_query($sql);
} 

if($db_query) 
	{
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) แก้ไขสูตรทางบัญชี', '$add_date')");
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