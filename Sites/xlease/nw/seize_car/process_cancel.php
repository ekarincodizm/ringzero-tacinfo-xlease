<?php
include("../../config/config.php");
include("../../nw/function/checknull.php"); ?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
$doerID = $_SESSION["av_iduser"];
$doerStamp = nowDateTime();
pg_query("BEGIN WORK");
$status=0;

$IDNO = pg_escape_string($_POST['IDNO']);
$NTID= pg_escape_string($_POST['NTID']);
$remark= checknull(pg_escape_string($_POST['remark']));

$qry_chkwait=pg_query("select \"auto_id\"  from \"nw_seize_cancel_btseizure\" WHERE  \"status\"='9' AND  \"IDNO\"='$IDNO' AND \"NTID\"='$NTID'");
$numrows=pg_num_rows($qry_chkwait);
if($numrows >0){	
	$status++;
}

$result2="insert into nw_seize_cancel_btseizure ( \"IDNO\", \"NTID\", \"seize_user\", \"authorize_user\", 
            \"witness_user1\", \"witness_user2\", \"organizeID\", \"proxy_usersend\", 
            \"proxy_datesend\",\"doerid\",\"doerstamp\",\"note_cancel_btseizure\",\"status\")
			SELECT  '$IDNO','$NTID',\"seize_user\",\"authorize_user\",\"witness_user1\",\"witness_user2\",
			\"organizeID\",\"proxy_usersend\" ,\"proxy_datesend\",'$doerID','$doerStamp',$remark,'9'		
			from \"nw_seize_car\"  where \"IDNO\" = '$IDNO' and \"NTID\" = '$NTID'";
	
if($result=pg_query($result2)){
			
}else{
	$status += 1;
}
$result3="update  \"nw_seize_car\" set 	\"status_approve\" = '6'
		where \"IDNO\" = '$IDNO' and \"NTID\" = '$NTID'";
	if($res=pg_query($result3)){
			
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