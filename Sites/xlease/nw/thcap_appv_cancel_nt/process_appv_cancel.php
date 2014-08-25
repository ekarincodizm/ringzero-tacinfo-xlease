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

$auto_id =pg_escape_string($_POST['auto_id']);
$note_appv=pg_escape_string($_POST['note_appv']);

$note=checknull($note_appv);

if(isset($_POST["appv"])){
	$method="1";//อนุมัติ
}else{
	$method="0";//ไม่อนุมัติ
}

	



$update ="update \"thcap_cancel_nt_temp\" set \"status\"='$method',\"note_appv\"=$note,\"appvid\"='$doerID',\"appvstamp\"='$doerStamp'
		where \"auto_id\"='$auto_id' and \"status\"='9'  ";
if(!$result=pg_query($update)){
		$status++;
}
else{
	if($method=="1"){
		$sql_chknum = pg_query("select \"NT_ID\" ,\"NT_enddate\" from \"thcap_cancel_nt_temp\" where \"auto_id\"='$auto_id'");
		$NT_ID = pg_fetch_result($sql_chknum,0);
		$NT_enddate = pg_fetch_result($sql_chknum,1);
		
		$sql_chknt = pg_query("select \"NT_number\"  from \"thcap_history_nt\" where \"NT_ID\"='$NT_ID'");
		$nt = pg_fetch_result($sql_chknt,0);
		if($nt=='1'){
			$nowdate=nowDate();
			$update_main =" update \"thcap_history_nt\" set \"NT_enddate\"='$nowdate'
			where  \"NT_ID\"='$NT_ID' and \"NT_enddate\" is null  ";
		}
		else if($nt=='2'){	
			$update_main =" update \"thcap_history_nt\" set \"NT_enddate\"='$NT_enddate'
			where  \"NT_ID\"='$NT_ID' and \"NT_enddate\" is null ";
		}		
		if(!$result=pg_query($update_main)){
			$status++;
		}
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