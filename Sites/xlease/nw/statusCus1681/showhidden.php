<?php
session_start();
include("../../config/config.php");

$recxlease = $_REQUEST['recxlease'];
$status = $_REQUEST['status'];

if($status=="1"){
	$qryrec=pg_query("select * from \"FOtherpay\" where (\"O_Type\"='165' OR \"O_Type\"='307') and (\"O_DATE\" > '2012-01-01') and (\"O_RECEIPT\" = '$recxlease')
	and \"O_RECEIPT\" not in (select \"tacXlsRecID_Old\" from \"tacReceiveTemp_waitedit\" where \"statusApp\" in (2,3))");
}else{
	$qryrec=pg_query("select * from \"FOtherpay\" where (\"O_Type\"='165' OR \"O_Type\"='307') and (\"O_DATE\" > '2012-01-01') and (\"O_RECEIPT\" = '$recxlease')
	and \"O_RECEIPT\" NOT IN (select \"tacXlsRecID\" from \"tacReceiveTemp\")");
}

$numrow=pg_num_rows($qryrec);
if($numrow>0){
	if($resrec=pg_fetch_array($qryrec)){
		$O_MONEY=$resrec["O_MONEY"];
	}
	echo "$O_MONEY";
}else{
	if($money==""){
		echo "0";
	}
}
?>
