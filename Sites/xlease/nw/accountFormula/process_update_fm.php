<?php
session_start();
include("../../config/config.php");
$ifm=$_POST["s_fmid"];

$add_date = nowDateTime();
$user_id = $_SESSION["av_iduser"];
$checkRename = pg_escape_string($_POST["checkRename"]);
$renamebox = pg_escape_string($_POST["renamebox"]);

pg_query("BEGIN");
//Rename Process
if($checkRename=="Y"){
	$sql_rename="update account.\"all_accFormula\" set af_fmname='$renamebox' where af_fmid='$ifm' ";
	$db_rename=pg_query($sql_rename);
}
for($i=0;$i<count($_POST["s_acid"]);$i++) 
{
 $ii_dcr=$_POST["f_dcr"][$i];
 $ii_id=$_POST["s_autoID"][$i];  
 $str_acid=$_POST["s_acid"][$i];
 list($ii_ac,$str)= explode("#",$str_acid);

 
 //echo $ifm." ".$ii_id." ".$ii_dcr."<br>";
 
 $sql="update account.\"all_accFormulaDetails\" set afd_drcr='$ii_dcr',afd_accno='$ii_ac' where afd_fmid='$ifm' AND afd_autoid='$ii_id'";
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