<?php

require_once("../../sys_setup.php");
include("../../../../../config/config.php");

	$idno = pg_escape_string($_REQUEST[idno]);
	$car_no = pg_escape_string($_REQUEST[car_no]);
	
	if($car_no == "pleaseSearch") // ถ้าต้องการให้หาใหม่เอง
	{
		$qrySearch = pg_query("select max(\"asset_id\") from \"VJoin\" where \"IDNO\" = '$idno' ");
		$car_no = pg_fetch_result($qrySearch,0);
	}
	
 	$sql_query=pg_query("select \"P_FDATE\",\"P_TOTAL\",\"C_CARNAME\",\"C_CARNUM\" from \"VJoin\" v WHERE v.\"asset_id\" = '$car_no' and \"IDNO\" = '$idno'  ");

	if($sql_row5 = pg_fetch_array($sql_query))
				{
					$start_contract_date  = date_ch_form_c($sql_row5['P_FDATE']);
					$car_month = $sql_row5['P_TOTAL'];
			
			
				
					
					$car_brand = $sql_row5['C_CARNAME'];
					$id_body = $sql_row5['C_CARNUM'];
				}

  
  echo $start_contract_date."#".$car_month."#".$car_brand."#".$id_body;
  

  ?>