<?php
include("../../config/config.php"); ?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$appvID = $_SESSION["av_iduser"];
$appvStamp = nowDateTime();
pg_query("BEGIN WORK");
$status=0;

$autoid=pg_escape_string($_POST["autoid"]);
$qry_chkwait=pg_query("select \"IDNO\",\"NTID\",\"seize_user\",\"authorize_user\",\"witness_user1\",
\"witness_user2\",\"organizeID\" ,\"proxy_usersend\",\"proxy_datesend\" 

from \"nw_seize_cancel_btseizure\" WHERE  \"status\"='9' AND \"auto_id\"='$autoid'");
list($IDNO,$NTID,$seize_user,$authorize_user,$witness_user1,$witness_user2,$organizeID,$proxy_usersend,$proxy_datesend)=pg_fetch_array($qry_chkwait);

//ตรวจสอบว่ากด ปุม อนุมัติ/ไม่อนุมัติ
$appvcheck=pg_escape_string($_POST["btnappv"]);
if($appvcheck==""){
$appvcheck=pg_escape_string($_POST["btnunappv"]);
}
if($appvcheck=="อนุมัติ"){
	$resultappv='1';//   เมื่อกดอนุมัติ
	//update nw_seize_car =รอแจ้งงาน
	$result3="update  \"nw_seize_car\" set 	\"status_approve\" = '2' ,
											\"seize_user\" = null,											
											\"authorize_user\" =null,
											\"witness_user1\" = null,
											\"witness_user2\" = null,
											\"organizeID\" = null,
											\"proxy_usersend\" = null,
											\"proxy_datesend\" = null
	where \"IDNO\" = '$IDNO' and \"NTID\" = '$NTID' AND \"status_approve\" = '6' AND 
	\"seize_user\"='$seize_user' AND
	\"authorize_user\"='$authorize_user' AND
	\"witness_user1\"='$witness_user1' AND 
	\"witness_user2\"='$witness_user2' AND  
	\"organizeID\" ='$organizeID' AND 
	\"proxy_usersend\"='$proxy_usersend' AND 
	\"proxy_datesend\" ='$proxy_datesend'
	";
}else if($appvcheck=="ไม่อนุมัติ"){
	$resultappv='0';//   เมื่อกดไม่อนุมัติ
	$result3="update  \"nw_seize_car\" set 	\"status_approve\" = '3' 											
	where \"IDNO\" = '$IDNO' and \"NTID\" = '$NTID' AND \"status_approve\" = '6' AND 
	\"seize_user\"='$seize_user' AND
	\"authorize_user\"='$authorize_user' AND
	\"witness_user1\"='$witness_user1' AND 
	\"witness_user2\"='$witness_user2' AND  
	\"organizeID\" ='$organizeID' AND 
	\"proxy_usersend\"='$proxy_usersend' AND 
	\"proxy_datesend\" ='$proxy_datesend'
	";
}

if($res=pg_query($result3)){
			
}else{
		$status += 1;
}

//update nw_seize_cancel_btseizure
$res_update="update  \"nw_seize_cancel_btseizure\" set 	\"status\" = '$resultappv' ,
			\"approveid\" = '$appvID',\"approvestamp\" ='$appvStamp'  where \"auto_id\" = '$autoid'";
	
if($result=pg_query($res_update)){
			
}else{
	$status += 1;
}

$script= '<script language=javascript>';

if($status==0){
		pg_query("COMMIT");	
			
		$script.= " alert('บันทึกรายการเรียบร้อย');";
}
	else{
		pg_query("ROLLBACK");    
		
		$script.= " alert('ไม่สามารถบันทึกได้  กรุณาดำเนินการอีกครั้งในภายหลัง');";
	}

$script.= 'window.opener.location.reload();
			   window.close();';
$script.= '</script>';
echo $script;
?>