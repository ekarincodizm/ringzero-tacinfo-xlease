<?php 
include('../../config/config.php');
include("../../nw/function/checknull.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
pg_query("BEGIN WORK");
$status=0;
$doerStamp=nowDateTime();
$doerID= $_SESSION["av_iduser"];
$datepicker =pg_escape_string($_POST['datepicker']);
$NT_ID=pg_escape_string($_POST['NT_ID']);
$note=pg_escape_string($_POST['note']);
$note=checknull($note);
$sql_chknum = pg_query("select *  from \"thcap_cancel_nt_temp\" where \"status\"='9' and \"NT_ID\"='$NT_ID'");
$num=pg_num_rows($sql_chknum);
if($num >0){$status++;}
else{
	$inter = "insert into \"thcap_cancel_nt_temp\" (\"NT_ID\",\"NT_enddate\",\"doerid\",\"doerstamp\",\"status\",\"note_ask\")
					values ('$NT_ID','$datepicker','$doerID','$doerStamp','9',$note)";
	if(!$result=pg_query($inter)){
		$status++;
	}
}
if($status==0){ 
	pg_query("COMMIT");
	$script= '<script language=javascript>';
	$script.= " alert('บันทึกข้อมูลเรียบร้อยแล้ว');
				window.opener.location.reload();
				self.close();";
	$script.= '</script>';
	echo $script;

}else {
	pg_query("ROLLBACK");    
	$script= '<script language=javascript>';
	$script.= " alert('ไม่สามารถบันทึกข้อมูลได้');
				window.opener.location.reload();
				self.close();";
	$script.= '</script>';
	echo $script;
	}

?>