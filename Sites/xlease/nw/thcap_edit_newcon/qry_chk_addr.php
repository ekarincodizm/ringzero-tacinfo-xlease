<?php
include('../../config/config.php');
$conid = $_POST["contractid"];
	$qry_chkaddr = pg_query("SELECT * FROM \"thcap_addrContractID\" WHERE \"contractID\" = '$conid' AND \"A_NO\" IS NULL AND \"A_SUBNO\" IS NULL AND \"A_BUILDING\" IS NULL AND \"A_ROOM\" IS NULL AND 
       \"A_FLOOR\" IS NULL AND \"A_VILLAGE\" IS NULL AND \"A_SOI\" IS NULL AND \"A_RD\" IS NULL AND \"A_TUM\" IS NULL AND \"A_AUM\"  IS NULL AND \"A_PRO\"  IS NULL AND 
       \"A_POST\" IS NULL AND filerequest  IS NULL");
	$conaddr_rows = pg_num_rows($qry_chkaddr);
	IF($conaddr_rows == 0){
		echo "success";
	}else{
		echo "failed";
	}
?>