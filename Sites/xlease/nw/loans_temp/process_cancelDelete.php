<?php
include("../../config/config.php");
$auto_tempID = pg_escape_string($_POST["contempID"]);
pg_query("BEGIN");
$status = 0;
// update thcap_contract_temp 
$qry_cancel = "update \"thcap_contract_temp\" set \"isForbidtoAlert\" = '1' where \"autoID\" = '$auto_tempID' and \"isForbidtoAlert\" = '0'";
if(pg_query($qry_cancel))
{
}else{
	$status++;
}
$script= '<script language=javascript>';
if($status == 0)
{
	pg_query("COMMIT");
	$script.= " alert('ทำรายการเรียบร้อยแล้ว');
	location.href='frm_appv.php'";
}
else
{ 
	pg_query("ROLLBACK");
	$script.= " alert('บันทึกผิดพลาด!!');
	location.href='frm_appv.php'";
}
$script.= '</script>';
echo $script;
?>