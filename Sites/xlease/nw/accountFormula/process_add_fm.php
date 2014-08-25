<?php
session_start();
include("../../config/config.php");
$ifm_name=pg_escape_string($_POST["fm_name"]);
$ifm_type=pg_escape_string($_POST["fm_type"]);
$useby=pg_escape_string($_POST["useby"]);
$editP=pg_escape_string($_POST['editP']);

$add_date = nowDateTime();
$user_id = $_SESSION["av_iduser"];

pg_query("BEGIN");

if($editP!="Y"){
	//----- insert to FormularID -------- //
	//$sql_in="insert into account.\"all_accFormula\" (af_fmid,af_fmname,af_typeacb,af_comp)values('$ifm_id','$ifm_name','$ifm_type','THCAP')"; ยกเลิกการกำหนดรหัสเอง
	$sql_in="insert into account.\"all_accFormula\" (af_fmname,af_typeacb,af_comp,\"doerID\",\"doerStamp\",af_useformula)values('$ifm_name','$ifm_type','THCAP','$user_id','$add_date','$useby')";
	if($result_in=pg_query($sql_in))
	{
		$st_otr ="OK".$result_in;
	}
	 else{
		$st_otr ="error insert sql".$result_in;
	}			   
	echo $st_otr;
	//-----END insert to FormularID ----- //

	//-- หา ifm_id (รหัสสูตร)
	$sql_id = pg_query("select max(\"af_fmid\") as \"af_fmid\" from account.\"all_accFormula\" where \"af_fmname\" = '$ifm_name'");
	while($res_id = pg_fetch_array($sql_id))
	{
		$ifm_id = $res_id["af_fmid"]; // รหัสสูตร
	}
}else {
	$ifm_id=pg_escape_string($_POST["fm_id"]); 
	$useby=pg_escape_string($_POST["useby"]); // Use by 1 = auto(ใช้สูตรด้วยระบบ),0= manual(ใช้สูตรด้วยเจ้าหน้าที่)
	$fm_type=pg_escape_string($_POST["fm_type"]);//ประเภท บัญชี	
	//แก้ไขในส่วน ตาราง   account."all_accFormula"
	$sql_id = "update  account.\"all_accFormula\"  set af_useformula='$useby', af_typeacb='$fm_type',\"doerID\"='$user_id',\"doerStamp\"='$add_date'
	where \"af_fmid\" = '$ifm_id'";
	if($result_inacc=pg_query($sql_id)){
	} else {
		$status++;
	}	
}
	//------ insert to FormularAcc ----------------//	
	for($i=0;$i<count($_POST["type_acb"]);$i++)
 	{  	  
	  $ifm_type_dcr=pg_escape_string($_POST["type_acb"][$i]);
	  $ifm_dcr=pg_escape_string($_POST["fm_dcr"][$i]);
	  
	  if($ifm_dcr=="DR")
	  {
		$i_dcr=1;
	  }
	  else
	  {
		$i_dcr=2;
	  }
	  
		$sql_inacc="insert into account.\"all_accFormulaDetails\" (afd_fmid,afd_accno,afd_drcr)values('$ifm_id','$ifm_type_dcr','$i_dcr')";
	    if($result_inacc=pg_query($sql_inacc))
	    {
			$st_otracc ="OK".$sql_inacc;
		}
		else
		{
		 $st_otracc ="error insert sql".$sql_inacc;
		}			   
		 echo $st_otracc."******"."<br>"; 
 
	}//จบ for
//---------END insert to FormularAcc ----------//
if($editP!="Y"){
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
 } else {
	if(($result_inacc) and($status==0))
	{
		//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) เพิ่มสมุดทางบัญชี', '$add_date')");
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
 }
?>