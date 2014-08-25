<?php
include("../../config/config.php");
$revChqID = $_POST['revChqID'];
$sqlrevChqID = pg_query("SELECT \"revChqID\" FROM finance.\"V_thcap_receive_cheque_chqManage\"
						where \"revChqID\"='$revChqID'");
$rowrevChqID = pg_num_rows($sqlrevChqID);
if($rowrevChqID>0){
	echo 1;
}
else{
	echo 2;
}?>