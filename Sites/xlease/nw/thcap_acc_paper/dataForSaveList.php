<?php
// ส่วนติดต่อกับฐานข้อมูล    
include("../../config/config.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php
	$qry_from_save = pg_query("select * from account.thcap_ledger_save_head order by ledger_year DESC, ledger_month DESC, \"doerStamp\" DESC ");
	$row_from_save = pg_num_rows($qry_from_save);
	while($res_from_save = pg_fetch_array($qry_from_save))
	{
		$save_id = $res_from_save["save_id"];
		$save_name = $res_from_save["save_name"];
		$ledger_month = $res_from_save["ledger_month"];
		$ledger_year = $res_from_save["ledger_year"];
		
		echo "<option value=\"$save_id\">$save_name(เดือน $ledger_month ปี $ledger_year)</option>";
	}
?>