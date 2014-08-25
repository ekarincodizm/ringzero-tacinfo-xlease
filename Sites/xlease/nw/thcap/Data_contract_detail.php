<?php 
if($contractID != "") // ถ้ามีการส่งค่ามา  // header
{
	//ตรวจสอบว่ามีเลขที่สัญญานี้ในระบบจริงหรือไม่
	$qrychk=pg_query("select \"contractID\" from \"thcap_contract\" where \"contractID\"='$contractID'");
	if(pg_num_rows($qrychk)==0){
		echo "<div align=center><h2>ไม่พบสัญญาดังกล่าว</h2></div>";
		exit;
	}
	$qr_ct = pg_query("select \"thcap_get_creditType\"('$contractID') as credit_type");
	if($qr_ct)
	{
		$rs_ct = pg_fetch_array($qr_ct);
		$credit_type = $rs_ct['credit_type'];
	}
	
	// หา contractType
	$qry_contractType = pg_query("select \"thcap_get_contractType\"('$contractID') ");
	$contractType = pg_result($qry_contractType,0);
	
	if($credit_type=="HIRE_PURCHASE" || $credit_type=="LEASING" || $credit_type=="GUARANTEED_INVESTMENT" || $credit_type=="FACTORING")
	{
		if($contractType == "FI")
		{
			require_once("Data_contract_detail_FI.php");
		}
		else
		{
			require_once("Data_contract_detail_lease.php");
		}
	}
	elseif($credit_type=="SALE_ON_CONSIGNMENT")
	{
		require_once("Data_contract_detail_sale_on_consignment.php");
	}
	else
	{
		require_once("Data_contract_detail_installment.php");
	}
}
?>